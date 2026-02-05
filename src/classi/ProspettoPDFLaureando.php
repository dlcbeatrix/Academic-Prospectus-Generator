<?php

require_once 'CarrieraLaureando.php';
require_once 'CarrieraLaureandoInformatica.php';
require_once (realpath('wp-content/themes/twentytwentyfour/templates/src/lib/fpdf184/fpdf.php'));

class ProspettoPDFLaureando
{
    public $laureando;
    public $dataLaurea;

    public function __construct($matricola, $cdl, $dataLau)
    {
        $this->dataLaurea= $dataLau;

        if ($cdl == "T. Ing. Informatica") {
            $this->laureando = new CarrieraLaureandoInformatica($matricola, $cdl, $dataLau);
        } else {
            $this->laureando = new CarrieraLaureando($matricola, $cdl);
        }
    }


    public function generaProspetto()
    {
            $fontFamily = "Arial";
            $tipoInfo = 0;

            //creazione pdf
            $pdf = new FPDF();
            $pdf->AddPage();
            $pdf->SetFont($fontFamily, "", 16);

            //intestazione
            $pdf->Cell(0, 6, $this->laureando->cdl, 0, 1, 'C');
            $pdf->Cell(0, 8, 'CARRIERA E SIMULAZIONE DEL VOTO DI LAUREA', 0, 1, 'C');
            $pdf->Ln(2);

            //informazioni anagrafiche dello studente

            $pdf->SetFont($fontFamily, "", 9);
            $stringaAnagrafica = "Matricola:                       " . $this->laureando->matricola .
                "\nNome:                            " . $this->laureando->nome .
                "\nCognome:                      " . $this->laureando->cognome .
                "\nEmail:                             " . $this->laureando->email .
                "\nData:                              " . $this->dataLaurea;

            if ($this->laureando->cdl=="T. Ing. Informatica") {
                $tipoInfo = 1;
                $stringaAnagrafica .= "\nBonus:                            " . $this->laureando->getBonus();
            }


            $pdf->MultiCell(0, 6, $stringaAnagrafica, 1, 'L');
            $pdf->Ln(3);

            //informazioni sugli esami
            $larghezzaCellaPicc = 12;
            $larghezzaCellaGrande = 190 - (3 * $larghezzaCellaPicc);
            $altezza = 5.5;

            if ($tipoInfo != 1) {
                $pdf->Cell($larghezzaCellaGrande, $altezza, "ESAME", 1, 0, "C");
                $pdf->Cell($larghezzaCellaPicc, $altezza, "CFU", 1, 0, 'C');
                $pdf->Cell($larghezzaCellaPicc, $altezza, "VOT", 1, 0, 'C');
                $pdf->Cell($larghezzaCellaPicc, $altezza, "MED", 1, 1, 'C');
            } else {
                $larghezzaCellaPicc -= 1;
                $larghezzaCellaGrande = 190 - (4 * $larghezzaCellaPicc);
                $pdf->Cell($larghezzaCellaGrande, $altezza, "ESAME", 1, 0, 'C');
                $pdf->Cell($larghezzaCellaPicc, $altezza, "CFU", 1, 0, 'C');
                $pdf->Cell($larghezzaCellaPicc, $altezza, "VOT", 1, 0, 'C');
                $pdf->Cell($larghezzaCellaPicc, $altezza, "MED", 1, 0, 'C');
                $pdf->Cell($larghezzaCellaPicc, $altezza, "INF", 1, 1, 'C');
            }

            $altezza = 4;
            $pdf->SetFont($fontFamily, "", 8);

            for ($i = 0; $i < sizeof($this->laureando->esami); $i++) {
                $esame = $this->laureando->esami[$i];
                $pdf->Cell($larghezzaCellaGrande, $altezza, $esame->nomeEsame, 1, 0, 'L');
                $pdf->Cell($larghezzaCellaPicc, $altezza, $esame->cfu, 1, 0, 'C');
                $pdf->Cell($larghezzaCellaPicc, $altezza, $esame->voto, 1, 0, 'C');

                if ($tipoInfo != 1) {
                    $pdf->Cell($larghezzaCellaPicc, $altezza, ($esame->faMedia == 1) ? 'X' : ' ', 1, 1, 'C');
                } else {
                    $pdf->Cell($larghezzaCellaPicc, $altezza, ($esame->faMedia == 1) ? 'X' : '', 1, 0, 'C');
                    $pdf->Cell($larghezzaCellaPicc, $altezza, ($esame->informatico == 1) ? 'X' : '', 1, 1, 'C');
                }
                }

            $pdf->Ln(5);

            //parte finale
            $pdf->SetFont($fontFamily, "", 9);
            $stringa = "Media Pesata (M):                                                  " . $this->laureando->getMedia() .
                "\nCrediti che fanno media (CFU):                             " . $this->laureando->CFUCheFannoMedia() .
                "\nCrediti curriculari conseguiti:                                  " . $this->laureando->CFUCurricolariConseguiti() . "/" . $this->laureando->getCFUPerConseguimentoLaurea() .
                "\nFormula calcolo voto di laurea:                               " . $this->laureando->getFormulaStampa();
            if ($tipoInfo == 1) {
                $stringa .= "\nMedia pesata esami INF:                                        " . $this->laureando->getMediaEsamiInformatici();
            }

            $pdf->MultiCell(0, 6, $stringa, 1, 'L');


            $percorsoOutput = __DIR__ . '/../prospetti/';
            $nomeFile = $this->laureando->matricola . "-prospetto.pdf";
            $pdf->Output($percorsoOutput. $nomeFile, 'F');

            return $pdf;

    }

}