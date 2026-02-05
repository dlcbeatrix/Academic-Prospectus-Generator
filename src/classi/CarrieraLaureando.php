<?php

require_once 'GestioneCarrieraStudente.php';
require_once 'EsameLaureando.php';
class CarrieraLaureando
{
    public $matricola; //int
    public $nome; //string
    public $cognome; //string
    public $cdl; //string
    public $email;
    public $esami;
    public $mediaPonderata;
    public $formulaVotoLaurea;
    public $formulaStampa;
    public $CFULaureando;
    public $CFUPerConseguimentoLaurea;
    public $CFUMedia;



    public function __construct($matricola, $corso)
    {
        $this->matricola = $matricola;

        $gestioneCS = new GestioneCarrieraStudente();
        $carrieraJson= $gestioneCS->restituisciCarriera($matricola);
        $carriera= json_decode($carrieraJson, true);

        $configurazioneJson = file_get_contents(__DIR__ . '/../file_configurazione/info-CdL.json');
        $datiConfigurazione = json_decode($configurazioneJson, true);
        $anagraficaJson= $gestioneCS->restituisciAnagrafica($matricola);
        $anagrafica= json_decode($anagraficaJson, true);


        $this->nome = $anagrafica["Entries"]["Entry"]["nome"];
        $this->cognome = $anagrafica["Entries"]["Entry"]["cognome"];
        $this->email = $anagrafica["Entries"]["Entry"]["email_ate"];
        $this->cdl = $corso;
        $this->formulaVotoLaurea = $datiConfigurazione[$this->cdl]["formulaLaureaCalcolo"];
        $this->formulaStampa = $datiConfigurazione[$this->cdl]["formulaLaurea"];
        $this->CFUPerConseguimentoLaurea = $datiConfigurazione[$this->cdl]["CFUCurriculari"];
        $this->esami= array();

        for ($i = 0; $i < sizeof($carriera["Esami"]["Esame"]); $i++) {
            $esame = $this->inserisciEsame(
                $carriera["Esami"]["Esame"][$i]["DES"],
                $carriera["Esami"]["Esame"][$i]["VOTO"],
                $carriera["Esami"]["Esame"][$i]["PESO"],
                1,
                1,  $carriera["Esami"]["Esame"][$i]["DATA_ESAME"]);
            if ($esame != null && is_string($esame->nomeEsame)) {
                array_push($this->esami, $esame);
            }
            usort($this->esami, function ($element1, $element2) {
                $datetime1 = strtotime(str_replace('/', '-', $element1->dataConseguimento));
                $datetime2 = strtotime(str_replace('/', '-', $element2->dataConseguimento));
                return $datetime1 - $datetime2;
            });
            $this->CFUMedia = $this->CFUCheFannoMedia();
            $this->CFULaureando = $this->CFUCurricolariConseguiti();
            $this->mediaPonderata = $this->calcolaMedia();
        }
    }
    private function inserisciEsame($nome, $voto, $cfu, $faMedia, $curricolare, $dataConseguimento)
    {
        if ($nome == "LIBERA SCELTA PER RICONOSCIMENTI" || $nome == "PROVA FINALE" || $nome ==  "TEST DI VALUTAZIONE DI INGEGNERIA"
            || $nome == "PROVA DI LINGUA INGLESE B2" || $nome== "PROVA DI LINGUA INGLESE (B1)" || $voto == 0) {
                $faMedia = 0;
        }

        if ($nome != "TEST DI VALUTAZIONE DI INGEGNERIA" && $nome != null) {
            if ($voto == "30 e lode" || $voto == "30 e lode " || $voto == "30  e lode" && $this->cdl != "M. Cybersecurity") {
                $voto = "33";
            } else {
                if ($voto == "30 e lode" || $voto == "30 e lode " || $voto == "30  e lode" && $this->cdl == "M. Cybersecurity")
                {
                    $voto = "32";
                }
            }
            $esame = new EsameLaureando();
            $esame->nomeEsame = $nome;
            $esame->voto = $voto;
            $esame->cfu = $cfu;
            $esame->faMedia = $faMedia;
            $esame->curricolare = $curricolare;
            $esame->dataConseguimento= $dataConseguimento;
            return $esame;
        } else {
            return null;
        }
    }
    public function CFUCheFannoMedia(){
        $crediti = 0;
        for ($i = 0; $i< sizeof($this->esami); $i++) {
            $crediti += ($this->esami[$i]->curricolare == 1 && $this->esami[$i]->faMedia == 1) ? $this->esami[$i]->cfu : 0;
        }
        return $crediti;
    }

    public function CFUCurricolariConseguiti(){
        $crediti = 0;
        for ($i = 0; $i< sizeof($this->esami); $i++) {
            if ($this->esami[$i]->nomeEsame != "PROVA FINALE" &&  $this->esami[$i]->nomeEsame != "LIBERA SCELTA PER RICONOSCIMENTI") {
                $crediti += ($this->esami[$i]->curricolare == 1) ? $this->esami[$i]->cfu : 0;
            }
        }
        return $crediti;
    }

    public function calcolaMedia()
    {
        $esami = $this->esami;
        $sommaVotoCFU = 0;
        $sommaCFUTOT = 0;

        for ($i = 0; $i < sizeof($esami); $i++) {
            if ($esami[$i]->faMedia == 1) {

                $sommaVotoCFU += $esami[$i]->voto * $esami[$i]->cfu;

                $sommaCFUTOT += $esami[$i]->cfu;
            }

        }

        $this->mediaPonderata = round($sommaVotoCFU / $sommaCFUTOT, 3);
        return $this->mediaPonderata;
    }


    public function getMedia()
    {
        return $this->mediaPonderata;
    }
    public function getFormula(){
        return $this->formulaVotoLaurea;
    }

    public function getCdL()
    {
        return $this->cdl;
    }

    public function getCFUPerConseguimentoLaurea()
    {
        return $this-> CFUPerConseguimentoLaurea;
    }


    public function getFormulaStampa()
    {
        return $this-> formulaStampa;
    }




}