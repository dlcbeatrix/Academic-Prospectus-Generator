<?php
require_once 'wp-content/themes/twentytwentyfour/templates/src/classi/CarrieraLaureandoInformatica.php';
require_once 'CarrieraLaureandoTest.php';

class CarrieraLaureandoInformaticaTest extends CarrieraLaureandoTest
{
    public function testInfo() {
        $dataJson= file_get_contents('wp-content/themes/twentytwentyfour/templates/src/dati_test/dati_test.json');
        $data= json_decode($dataJson,true);

        echo "+++++++++++++++++++++++++++++++++++++++++++++++TEST ING. INFORMATICA+++++++++++++++++++++++++++++++++++++++++++++++" . "<br>";
        $numLaureandi = count($data);
        for ($i = 0; $i < $numLaureandi; $i++) {
            $laureando = $data["laureando$i"];
            if($laureando['cdl'] == "T. Ing. Informatica") {
                $carriera = new CarrieraLaureandoInformatica($laureando['matricola'], $laureando['cdl'],
                    $laureando['anno_chiusura']);
                echo "TEST: " . $laureando ['cognome'] . " " . $laureando['nome'] . " " . $laureando['matricola'] . "<br>";
                $this->test_costruttore($carriera, $laureando);
                $this->test_media($carriera, $laureando);
                $this->testCFUMedia($carriera, $laureando);
                $this->testCreditiCurriculari($carriera, $laureando);
                $this->testBonus($carriera, $laureando);
                $this->testMediaInfo($carriera, $laureando);
                echo "<font color='green'>CARRIERA LAUREANDO ING. INFORMATICA: TUTTI I TEST ESEGUITI" . "</font><br>";
                echo "<br>";
            }
        }
        echo "<br>";
    }

    function testBonus ($carriera, $laureando){
        $expected_bonus = $laureando['bonus'];
        $actual_bonus = $carriera->getBonus();
        echo "Test Bonus: ";
        if ($actual_bonus != $expected_bonus) {
            echo "<font color = 'red'>Errore nel test del bonus: expected: " . $expected_bonus . ", received: " . $actual_bonus . "</font><br>";
        } else {
            echo "<font color='green'>Test bonus superato" . "</font><br>";
        }

    }
    function testMediaInfo($carriera, $laureando){
        $expected= $laureando['media_pesata_inf'];
        $actual= $carriera->getMediaEsamiInformatici();
        echo "Test media esami informatici: ";
        if($actual!=$expected) {
            echo "<font color = 'red'>Errore nel calcolo della media degli esami informatici: expected: " . $expected . " received: " . $actual . "</font><br>";
        } else {
            echo "<font color='green'>Test media esami informatici  superato </font><br>";
        }
    }
}