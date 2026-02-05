<?php

class GestioneCarrieraStudente
{


    public function restituisciCarriera($matricola){

        $carrieraJson= file_get_contents(__DIR__ . '/../data/' . $matricola . '_esami.json');


        return $carrieraJson;
    }

    public function restituisciAnagrafica($matricola){

        $anagraficaJson= file_get_contents(__DIR__ . '/../data/'.  $matricola . '_anagrafica.json');


        return $anagraficaJson;

    }

}