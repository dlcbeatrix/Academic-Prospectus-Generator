<?php

require_once 'CarrieraLaureando.php';

class CarrieraLaureandoInformatica extends CarrieraLaureando
{
    private $annoImmatricolazione;
    private $dataLaurea;

    private $mediaEsamiInformatici;
    private $bonus= "NO";
    private $esameNonInMedia;

    //dataLaurea presa da CarrieraLaureando
    public function __construct($matricola, $corso, $dataLau)
    {
        parent::__construct($matricola, $corso);
        $this->dataLaurea = $dataLau;

        $gestioneStudente= new GestioneCarrieraStudente();
        $carrieraJson= $gestioneStudente->restituisciCarriera($matricola);
        $carriera= json_decode($carrieraJson, true);
        $this->annoImmatricolazione= $carriera["Esami"]["Esame"][0]["ANNO_IMM"];
        $this->calcolaBonus();
        $this->mediaEsamiInformatici= $this->calcolaMediaEsamiInformatici();
    }
    public function calcolaBonus(){
        $fine_bonus = ($this->annoImmatricolazione + 4) . "05/01/";

        if ($this->dataLaurea < $fine_bonus) {
            $this->bonus = "SI";
            $minVoto = 33;
            $indiceMin = -1;
            $CFUMinVoto = 0;

            // Trova l'indice dell'esame con il voto più basso
            for ($i = 0; $i < sizeof($this->esami); $i++) {
                $esame = $this->esami[$i];
                $voto = $esame->voto;


                if ($esame->faMedia == 1 && $voto < $minVoto) {
                    $minVoto = $voto;
                    $indiceMin = $i;
                    $CFUMinVoto = $esame->cfu;
                } elseif ($esame->faMedia == 1 && $voto == $minVoto && $esame->cfu > $CFUMinVoto) {
                    // In caso di parità di voto, tiene conto dei CFU
                    $CFUMinVoto = $esame->cfu;
                    $indiceMin = $i;
                }
            }

            if ($indiceMin != -1) {
                // Rimuove l'esame con il voto più basso dalla media ponderata
                $esameRimosso = $this->esami[$indiceMin];
                $this->esami[$indiceMin]->faMedia = 0;
                $this->esameNonInMedia = $esameRimosso->nomeEsame;

                // Aggiorna la media ponderata e i CFU totali
                $this->CFUMedia -= $CFUMinVoto;
                $this->mediaPonderata = round(($this->mediaPonderata * $this->CFUMedia - $CFUMinVoto * $minVoto) / ($this->CFUMedia - $CFUMinVoto), 3);
            }
        }
    }
    private function calcolaMediaEsamiInformatici()
    {
        //esami informatici
        $esInfoJson = file_get_contents(realpath(__DIR__ . '/../file_configurazione/esami-informatici.json'));
        $esamiInformatici = json_decode($esInfoJson, true);
        for ($i = 0; $i < sizeof($this->esami); $i++) {
            if (in_array($this->esami[$i]->nomeEsame, $esamiInformatici["nomiEsami"])) {
                $this->esami[$i]->informatico = 1;
            }
        }
        $sommaVotiInformatici = 0;
        $numeroEsamiInformatici = 0;
        for ($i = 0; $i < sizeof($this->esami); $i++) {
            if ($this->esami[$i]->faMedia == 1) {
                $sommaVotiInformatici += intval($this->esami[$i]->voto) ;
                $numeroEsamiInformatici++;
            }
        }
        $mediaVotiInformatici = $numeroEsamiInformatici > 0 ? $sommaVotiInformatici / $numeroEsamiInformatici : 0;

        return round($mediaVotiInformatici, 3);
    }
    public function getMediaEsamiInformatici()
    {
        return $this->mediaEsamiInformatici;
    }

    public function getBonus()
    {
        return $this->bonus;
    }

}