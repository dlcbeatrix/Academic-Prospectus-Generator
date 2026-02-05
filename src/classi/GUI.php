<?php
require_once 'CarrieraLaureando.php';
require_once 'CarrieraLaureandoInformatica.php';
require_once 'ProspettoPDFLaureandoSimulazione.php';
require_once 'GeneratoreProspetti.php';
require_once 'GestioneCarrieraStudente.php';
require_once 'InvioPDFEmail.php';

class GUI
{
    private $matricole= array();
    private $cdl;
    private $dataAppello;

    public function __construct($matricole, $corso, $dataLau){
        if (is_string($matricole)) {
            $this->matricole = explode(" ", $matricole);
        } else {
            // Gestione dell'errore o avviso appropriato se $matricole non è una stringa
            // Potresti anche considerare di lanciare un'eccezione
            echo "Le matricole devono essere passate come una stringa.";
        }
        $this->cdl= $corso;
        $this->dataAppello= $dataLau;
    }

    public function CreaProspetti()
    {
        for ($i = 0; $i < sizeof($this->matricole); $i++) {
            $matricola = trim($this->matricole[$i]);
            $prospettoLaureando = new ProspettoPDFLaureando($matricola, $this->cdl, $this->dataAppello);
            $prospettoLaureando->generaProspetto();
            $prospettoLaureandoSimulazione = new ProspettoPDFLaureandoSimulazione($matricola, $this->cdl, $this->dataAppello);
            $prospettoLaureandoSimulazione->generaProspettoConSimulazione();

        }
        // Aggiunta della generazione del ProspettoCommissione
        $prospettiCommissione= new GeneratoreProspetti($this->matricole, $this->cdl, $this->dataAppello);
        $prospettiCommissione->generaProspettoCommissione();

        echo "Prospetti Generati";
    }


    public function ApriProspetti(){
        return __DIR__ . '/../prospetti/';
    }

    public function InviaProspetti(){
        if (!empty($this->matricole)) {
            $invioPDFEmail = new InvioPDFEmail($this->matricole, $this->cdl, $this->dataAppello);
            echo "Prospetti Inviati con successo!";
        } else {
            echo "Nessuna matricola disponibile per l'invio di prospetti.";
        }
    }
}




