<?php

require_once 'ProspettoPDFLaureandoSimulazione.php';
class GeneratoreProspetti
{
    private $matricole= array();
    private $dataLaurea;
    private $cdl;


    public function __construct(array $matricole, $cdl, $dataLau)
    {
        $this->matricole = $matricole;
        $this->cdl = $cdl;
        $this->dataLaurea = $dataLau;

    }


    public function generaProspettoCommissione()
    {
        $pdf = new FPDF();
        $fontFamily = "Arial";
        $pdf->SetFont($fontFamily, "", 14);
        $pdf->AddPage();

        // Pagina con la lista
        $pdf->Cell(0, 6, $this->cdl, 0, 1, 'C');
        $pdf->Ln(2);
        $pdf->SetFont($fontFamily, "", 16);
        $pdf->Cell(0, 6, 'LISTA LAUREANDI', 0, 1, 'C');
        $pdf->Ln(5);
        $pdf->SetFont($fontFamily, "", 14);
        $width = 190 / 4;
        $height = 5;
        $pdf->Cell($width, $height, "COGNOME", 1, 0, 'C');
        $pdf->Cell($width, $height, "NOME", 1, 0, 'C');
        $pdf->Cell($width, $height, "CDL", 1, 0, 'C');
        $pdf->Cell($width, $height, "VOTO LAUREA", 1, 1, 'C');
        $pdf->SetFont($fontFamily, "", 12);

        for($i=0; $i<sizeof($this->matricole); $i++) {
            $matricola = $this->matricole[$i];
            $paginaSimulazione = new ProspettoPDFLaureandoSimulazione($matricola, $this->cdl, $this->dataLaurea);
            $pdf = $paginaSimulazione->aggiungiRiga($pdf);
        }
        for($i=0; $i<sizeof($this->matricole); $i++) {
            $matricola = $this->matricole[$i];
            $paginaSimulazione = new ProspettoPDFLaureandoSimulazione($matricola, $this->cdl, $this->dataLaurea);
            $pdf->AddPage(); // Aggiungi una nuova pagina per il prospetto PDF
            $pdf = $paginaSimulazione->generaContenuto($pdf);
        }

        $percorsoOutput = __DIR__ . '/../prospetti/';
        $nomeFile = "prospettoCommissione.pdf";
        $pdf->Output($percorsoOutput . $nomeFile, 'F');
    }

}