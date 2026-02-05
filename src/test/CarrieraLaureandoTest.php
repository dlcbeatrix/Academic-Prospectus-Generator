<?php
require_once 'wp-content/themes/twentytwentyfour/templates/src/classi/CarrieraLaureando.php';
class CarrieraLaureandoTest
{
    public function test() {
        $dataJson = file_get_contents('wp-content/themes/twentytwentyfour/templates/src/dati_test/dati_test.json');
        $data = json_decode($dataJson, true);
        echo "+++++++++++++++++++++++++++++++++++++++++++++++TEST SULLA CARRIERA+++++++++++++++++++++++++++++++++++++++++++++++" . "<br>";

        $numLaureandi = count($data);
        for ($i = 0; $i < $numLaureandi; $i++) {
            $laureando = $data["laureando$i"];
            if($laureando['cdl']== 'T. Ing. Informatica')
                continue;

            //if ($laureando['cdl'] != "M. Cybersecurity")  {
                $carrLau = new CarrieraLaureando($laureando['matricola'], $laureando['cdl']);
                echo "TEST: " . $laureando['cognome'] . " " . $laureando['nome'] . " " . $laureando['matricola'] . " " . $laureando['cdl'] . "<br>";


                $this->test_costruttore($carrLau, $laureando);
                $this->test_media($carrLau, $laureando);
                $this->testCFUMedia($carrLau, $laureando);
                $this->testCreditiCurriculari($carrLau, $laureando);
                echo "<font color = 'green'>CARRIERA LAUREANDO: TUTTI I TEST ESEGUITI" . "<br>" . "</font><br>";
            /*} else {
                echo "TEST: " . $laureando['cognome'] . " " . $laureando['nome'] . " " . $laureando['matricola'] . "<br>";
                echo "<font color = 'red'>Errore: Il corso di laurea M. Cybersecurity non è presente nel file di configurazione.</font><br>";
            }*/
        }
    }

    function test_costruttore($carrLau, $laureando) {
        echo "Test Carriera: ";
        $expected = $laureando['matricola'];
        $result = $carrLau->matricola;
        if ($expected != $result)
            echo "<font color = 'red'>CarrieraLaureando : errore: expected:" . $expected . " recived:" . $result . "</font><br>";

        $expected = $laureando['nome'];
        $result = $carrLau->nome;
        if ($expected != $result)
            echo "<font color = 'red'>CarrieraLaureando : errore: expected:" . $expected . " recived:" . $result . "</font><br>";

        $expected = $laureando['cognome'];
        $result = $carrLau->cognome;
        if ($expected != $result)
            echo "<font color = 'red'>CarrieraLaureando : errore: expected:" . $expected . " recived:" . $result . "</font><br>";

        $expected = $laureando['email_ate'];
        $result = $carrLau->email;
        if ($expected != $result)
            echo "<font color = 'red'>CarrieraLaureando : errore: expected:" . $expected . " recived:" . $result  . "</font><br>";

        echo "<font color = 'green'>Test sulla Carriera eseguiti </font><br>";

    }

    function test_media(CarrieraLaureando $carrLau, $laureando) {
        echo "Test sulla media: \n";
        if ($carrLau->getMedia()<18 || $carrLau->getMedia()> 33)
            echo "<font color = 'red'>Errore nel calcolo della media: non è nel range [18,33]". "</font><br>";

        else if ($carrLau->getMedia() != $laureando['media_pesata']){
            echo "<font color = 'red'>Errore nel calcolo della media: expected: " . $laureando['media_pesata'] . " received: " . $carrLau->mediaPonderata  . "</font><br>";
        }
        else {
            echo "<font color = 'green'>Test sulla media eseguiti </font><br>";
        }
    }

    function testCFUMedia($carrLau, $laureando) {
        echo "Test Crediti che fanno media: \n";
        if($carrLau->CFUMedia != $laureando['crediti_media'])
            echo "<font color = 'red'>Crediti che fanno media errati: expected: " . $laureando['crediti_media'] . " received: " . $carrLau->CFUMedia  . "</font><br>";
        else{
            echo "<font color = 'green'>Test sui crediti che fanno media eseguiti </font><br>";
        }
    }

    function testCreditiCurriculari($carrLau, $laureando) {
        echo "Test Crediti curriculari ottenuti: \n";
        if($carrLau->CFULaureando != $laureando['crediti_curriculari_conseguiti'])
            echo "<font color = 'red'>Crediti curriculari ottenuti errati: expected: " . $laureando['crediti_curriculari_conseguiti'] . " received: " . $carrLau->CFULaureando  . "</font><br>";
        else{
            echo "<font color = 'green'>Test sui crediti curriculari ottenuti eseguiti </font><br>";
        }
    }

}