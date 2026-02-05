<?php

require_once 'ProspettoPDFLaureandoSimulazione.php';
require_once realpath('wp-content/themes/twentytwentyfour/templates/src/lib/fpdf184/fpdf.php');
require_once realpath('wp-content/themes/twentytwentyfour/templates/src/lib/PHPMailer/src/PHPMailer.php');
require_once realpath('wp-content/themes/twentytwentyfour/templates/src/lib/PHPMailer/src/SMTP.php');
require_once realpath('wp-content/themes/twentytwentyfour/templates/src/lib/PHPMailer/src/Exception.php');

class InvioPDFEmail
{
    private $matricole;

    public $prospettoPDFLaureando;

    public function __construct($matricole, $cdl, $dataLaurea)
    {
        $this->matricole = $matricole;

        for ($i = 0; $i < sizeof($this->matricole); $i++) {
            $this->prospettoPDFLaureando = new ProspettoPDFLaureando($this->matricole[$i], $cdl, $dataLaurea);
            $laureando = new CarrieraLaureando($this->matricole[$i], $cdl);
            $this->inviaProspetto($laureando, $i+1, sizeof($this->matricole));
        }
    }

    public function inviaProspetto($laureando, $n, $total)
    {
        $messaggio = new PHPMailer\PHPMailer\PHPMailer();

        $messaggio->IsSMTP();
        $messaggio->Host = "mixer.unipi.it";
        $messaggio->SMTPSecure = "tls";
        $messaggio->SMTPAuth = false;
        $messaggio->Port = 25;
        $messaggio->From = 'no-reply-laureandosi@ing.unipi.it';
        $messaggio->addAddress($laureando->email);
        $messaggio->Subject = 'Appello di laurea in Ing. TEST- indicatori per voto di laurea';
        $messaggio->Body =
            'Gentile laureando/laureanda,
            Allego un prospetto contenente: la sua carriera, gli indicatori e la formula che la commissione adopererà per determinare il voto di laurea.
            La prego di prendere visione dei dati relativi agli esami.
            In caso di dubbi scrivere a: ...
            
            Alcune spiegazioni:
            - gli esami che non hanno un voto in trentesimi, hanno voto nominale zero al posto di giudizio o idoneità\', in quanto non contribuiscono al calcolo della media ma solo al numero di crediti curriculari;
            - gli esami che non fanno media (pur contribuendo ai crediti curriculari) non hanno la spunta nella colonna MED;
            - il voto di tesi (T) appare nominalmente a zero in quanto verra\' determinato in sede di laurea, e va da 18 a 30.
            
             Cordiali saluti
             Unità Didattica DII';


        $messaggio->addAttachment(__DIR__ . '/../prospetti/' . $laureando->matricola . "-prospetto.pdf");

        if (!$messaggio->send()) {
            echo "Invio $n di $total non riuscito: " . $messaggio->ErrorInfo . "\n";
        } else {
            echo 'Email inviata correttamente!';
        }
    }
}
