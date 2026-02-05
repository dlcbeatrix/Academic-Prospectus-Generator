<?php
require_once 'wp-content/themes/twentytwentyfour/templates/src/classi/EsameLaureando.php';
require_once 'wp-content/themes/twentytwentyfour/templates/src/classi/CarrieraLaureando.php';
class EsameLaureandoTest
{
    public function test(){
        echo "+++++++++++++++++++++++++++++++++++++++++++++++TEST SUGLI ESAMI+++++++++++++++++++++++++++++++++++++++++++++++" . "<br>";

        $dataJson = file_get_contents('wp-content/themes/twentytwentyfour/templates/src/dati_test/dati_test.json');
        $data = json_decode($dataJson, true);
        $numLaureandi = count($data);

        if ($dataJson === false) {
            die("Errore: impossibile leggere il file JSON.");
        }
        if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
            die("Errore: il file JSON non è nel formato corretto.");
        }


        for ($i = 0; $i < $numLaureandi; $i++) {
            $laureandoKey = "laureando$i";
            $laureando = $data[$laureandoKey];
            echo "TEST: " . $laureando ['cognome'] . " " . $laureando['nome'] . " " . $laureando['matricola'] . "<br>";
            /*if ($laureando['cdl'] === 'M. Cybersecurity') {
                echo "<font color = 'red'>Errore: il corso di laurea M. Cybersecurity non è presente nel file di configurazione.</font><br>";

                continue;
            }*/
            $carrieraLaureando = new CarrieraLaureando($laureando['matricola'], $laureando['cdl']);
            $esamiDaConfrontare = $laureando['esami'];
            $this->runTest($carrieraLaureando, $esamiDaConfrontare);
            echo "<font color='green'> ESAME LAUREANDO: TUTTI I TEST ESEGUITI </font><br>";
            echo "<br>";
        }
    }

    public function runTest(CarrieraLaureando $carriera, array $esamiDaConfrontare)
    {
        $esamiCarriera = $carriera->esami;
        if (!is_array($esamiDaConfrontare) || empty($esamiDaConfrontare)) {
            echo "Nessun esame da confrontare.<br>";
            return;
        }

        for ($i = 0; $i < count($esamiCarriera); $i++) {
            $esamePresente = false;
            for ($j = 0; $j < count($esamiDaConfrontare); $j++) {
                if ($esamiCarriera[$i]->nomeEsame == $esamiDaConfrontare[$j]) {
                    $esamePresente = true;
                    break;
                }
            }
            if (!$esamePresente) {
                echo "<font color='red'>Errore: l'esame " . $esamiCarriera[$i]->nomeEsame . " non è presente nel file di configurazione.</font><br>";
            }
        }

        echo "<font color='green'> Tutti i test sugli esami sono stati eseguiti con successo.</font><br>";
    }
}