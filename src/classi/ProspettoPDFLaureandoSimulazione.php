<?php
require_once 'ProspettoPDFLaureando.php';



class ProspettoPDFLaureandoSimulazione extends ProspettoPDFLaureando
{

    public function __construct($matricola, $cdl, $dataLaurea)
    {
        parent::__construct($matricola, $cdl, $dataLaurea);




    }

    public function generaProspettoConSimulazione()
    {
        $pdf = new FPDF();
        $pdf->AddPage();

        $this->generaContenuto($pdf);

        $percorsoOutput = __DIR__ . '/../prospetti/';
        $nomeFile = $this->laureando->matricola . "-prospetto_simulazione.pdf";
        $pdf->Output('F', $percorsoOutput . $nomeFile);



        return $pdf;
    }

    public function generaContenuto($pdf)
    {
        $fontFamily = "Arial";
        $tipoInfo = 0;

        $pdf->SetFont($fontFamily, "", 16);

        //intestazione
        $pdf->Cell(0, 6, $this->laureando->cdl, 0, 1, 'C');
        $pdf->Cell(0, 8, 'CARRIERA E SIMULAZIONE DEL VOTO DI LAUREA', 0, 1, 'C');
        $pdf->Ln(2);

        //informazioni anagrafiche dello studente

        $pdf->SetFont($fontFamily, "", 9);
        $stringaAnagrafica = "Matricola:" . $this->laureando->matricola .
            "\nNome:" . $this->laureando->nome .
            "\nCognome:" . $this->laureando->cognome .
            "\nEmail:" . $this->laureando->email .
            "\nData:" . $this->dataLaurea;

        if ($this->laureando->cdl=="T. Ing. Informatica") {
            $tipoInfo = 1;
            $stringaAnagrafica .= "\nBonus:" . $this->laureando->getBonus();
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
        $stringa = "Media Pesata (M):                                                  " . $this->laureando->getMedia(
            ) .
            "\nCrediti che fanno media (CFU):                             " . $this->laureando->CFUCheFannoMedia() .
            "\nCrediti curriculari conseguiti:                                  " . $this->laureando->CFUCurricolariConseguiti() . "/" . $this->laureando->getCFUPerConseguimentoLaurea() .
            "\nFormula calcolo voto di laurea:                               " . $this->laureando->getFormulaStampa();
        if ($tipoInfo == 1) {
            $stringa .= "\nMedia pesata esami INF:                                        " . $this->laureando->getMediaEsamiInformatici();
        }

        $pdf->MultiCell(0, 6, $stringa, 1, 'L');
        //parte simulazione
        $confJson = file_get_contents(
            realpath('wp-content/themes/twentytwentyfour/templates/src/file_configurazione/info-CdL.json')
        );
        $configurazione = json_decode($confJson, true);

        $tMin = $configurazione[$this->laureando->cdl]["Tmin"];
        $tMax = $configurazione[$this->laureando->cdl]["Tmax"];
        $tStep = $configurazione[$this->laureando->cdl]["Tstep"];
        $cMin = $configurazione[$this->laureando->cdl]["Cmin"];
        $cMax = $configurazione[$this->laureando->cdl]["Cmax"];
        $cStep = $configurazione[$this->laureando->cdl]["Cstep"];
        $CFU = $this->laureando->CFUCheFannoMedia();

        $pdf->Ln(4);
        $pdf->Cell(0, 5.5, "SIMULAZIONE DI VOTO DI LAUREA", 1, 1, 'C');
        $larghezza = 190 / 2;
        $altezza = 4.5;

        if ($cMin != 0) {
            $pdf->Cell($larghezza, $altezza, "VOTO COMMISSIONE (C)", 1, 0, 'C');
            $pdf->Cell($larghezza, $altezza, "VOTO LAUREA", 1, 1, 'C');
            $M = $this->laureando->getMedia();
            $T = 0;

            for ($C = $cMin; $C <= $cMax; $C += $cStep) {
                $voto = 0;
                eval("\$voto = " . $this->laureando->getFormula() . ";");
                $voto = number_format($voto, 3);
                $pdf->Cell($larghezza, $altezza, $C, 1, 0, 'C');
                $pdf->Cell($larghezza, $altezza, $voto, 1, 1, 'C');
            }
        }
        if ($tMin != 0) {
            $pdf->Cell($larghezza, $altezza, "VOTO TESI (T)", 1, 0, 'C');
            $pdf->Cell($larghezza, $altezza, "VOTO LAUREA", 1, 1, 'C');
            $M = $this->laureando->getMedia();
            $C = 0;

            for ($T = $tMin; $T <= $tMax; $T += $tStep) {
                $voto = 0;
                eval("\$voto = " . $this->laureando->getFormula() . ";");
                $voto = number_format($voto, 3);
                $pdf->Cell($larghezza, $altezza, $T, 1, 0, 'C');
                $pdf->Cell($larghezza, $altezza, $voto, 1, 1, 'C');
            }
        }
        $pdf->Ln(3);
        $messaggioCommissione = $configurazione[$this->laureando->cdl]["MessaggioCommissione"];
        $pdf->Cell(190, $altezza, $messaggioCommissione, 0, 1, 'C');

        return $pdf;
    }


    public function aggiungiRiga($pdf)
    {
        $width = 190 / 4;
        $height = 5;
        $pdf->Cell($width, $height, $this->laureando->cognome, 1, 0, 'L');
        $pdf->Cell($width, $height, $this->laureando->nome, 1, 0, 'L');
        $pdf->Cell($width, $height, "", 1, 0, 'C');
        $pdf->Cell($width, $height, "/110", 1, 1, 'C');
        return $pdf;
    }


}


