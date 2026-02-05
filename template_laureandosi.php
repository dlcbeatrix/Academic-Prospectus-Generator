<?php
/** Template Name: template_laureandosi
 *
 */
?>

<!DOCTYPE html>
<head>
    <title>Laureandosi 2.0</title>
    <style type="text/css">
        body{
            font-family: 'Arial', sans-serif;

            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            max-width: 1200px;
            text-align: center;
            background-color: lightblue;
            font-size: larger;
           margin: 0 auto;
        }
        h1 {
            font-size: 35px;
            color: #0026e0;
        }

        h2 {
            font-size: 30px;
            color: #0026e0;
        }
        p{
            color: #0026e0;
            font-weight: bold;
            font-size: 24px;
        }
        input[type= "date"]{
            width: 100%;
            color: #0026e0;
            height: 20px;
        }
        form{
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            width: 100%;
            margin: 0 auto;
        }
        .column-left{
            flex:1;
            margin-right: 50px;
            margin-left: 20px;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }
        .column-center{
            flex:2;
            margin-right: 40px;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }
        .column-right{
            flex:1;
            margin-right: 20px;
            margin-left: 10px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        button{
            color: #0026e0;
            background-color: #87aae7;
            font-size: 20px;
            padding: 24px;
            margin: 0.5em;
            border-radius: 5px;
            text-align: center;
        }
        select{
            color: #0026e0;
            width: 100%;
            font-size: 18px;
        }
        textarea{
            color: black;
            width: 100%;
            height: 350px;
        }
        #header {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            width: 100%;
            padding: 5px;
            margin-bottom: 0;
        }

        #logo {
            width: 50px; 
            margin-right: 10px;
        }

        #Name {
            font-size: 20px;
            font-weight: bold;
            color: #0026e0
        }
    </style>
</head>
<body>

<!-- Header con logo e nome -->
<div id="header">
    <img id="logo" src="https://upload.wikimedia.org/wikipedia/it/e/e2/Stemma_unipi.svg" alt="Logo Università di Pisa">
    <span id="Name">Beatrice De Luca</span>
</div>

<h1>Laureandosi 2.0</h1>
<h2>Gestione Prospetti di Laurea</h2>


<form action="http://prova.local/laureandosi-2-0/" method="get" onsubmit="setMatricole()">
    <div class="column-left">


        <p>Cdl:</p>
        <select name="cdl">
            <option name="cdl">T. Ing. Informatica</option>
            <option name="cdl">M. Cybersecurity</option>
            <option name="cdl">M. Ing. Elettronica</option>
            <option name="cdl">T.Ing. Biomedica</option>
            <option name="cdl">M. Ing. Biomedica, Bionics Engineering</option>
            <option name="cdl">T. Ing. Elettronica</option>
            <option name="cdl">T. Ing. delle Telecomunicazioni</option>
            <option name="cdl">M. Ing. delle Telecomunicazioni</option>
            <option name="cdl">M. Computer Engineering, Artificial Intelligence and Data Engineering</option>
            <option name="cdl">M. Ing, Robotica e della Automazione</option>
            <option name="cdl">M. Cybersecurity</option>
        </select>
        <br>
        <br>
        <p>Data Laurea:</p>
        <input type= "date" name="data_laurea"/>

        <br>
        <br>
        <br>
    </div>
    <br>

    <div class="column-center">
        <p style="margin-bottom: 2px;">Matricole:</p>
        <p style="font-size: 10px; color: black; margin-bottom: 5px;">NB: separare le matricole con uno spazio bianco</p>
        <textarea class= "altro textarea" name="Matricole"></textarea>

        <br>
    </div>



    <div class="column-right">
        <br>
        <button type="submit" name="crea_prospetti">
            Crea Prospetti
        </button>
        <br>
        <br>

        <button onclick="apriCartella()">Apri Prospetti</button>

        <br>
        <br>

        <button type="submit" name="invia_prospetti">
            Invia Prospetti
        </button>
        <br>
    </div>
    <input type="hidden" name="matricole" id="matricole" value="">
    <script>
        function apriCartella() {
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "http://prova.local/laureandosi-2-0/?action=apri_prospetti", true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                }
            };
            xhr.send();
        }
        function setMatricole() {
            var matricole = document.getElementById("Matricole").value;
            document.getElementById("matricole").value = matricole;
        }
    </script>

</form>

</body>



<?php
require_once(__DIR__ . '/src/classi/GUI.php');

// Controlla se il modulo è stato inviato
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Controlla quale pulsante è stato premuto
    if (isset($_GET['crea_prospetti'])) {
        $cdl = sanitize_text_field($_GET['cdl']);
        $dataLaurea = sanitize_text_field($_GET['data_laurea']);
        $matricole = sanitize_text_field($_GET['Matricole']);
        // Crea un'istanza della classe GUI con i parametri necessari
        $gui = new GUI($matricole, $cdl, $dataLaurea);



        if (isset($_GET['crea_prospetti'])) {
            // Chiama il metodo per creare i prospetti
            $gui->CreaProspetti();
        } else {
            if (isset($_GET['invia_prospetti'])) {
                $matricole = explode(' ', sanitize_text_field($_GET['matricole']));
                $cdl = sanitize_text_field($_GET['cdl']);
                $dataLaurea = sanitize_text_field($_GET['data_laurea']);
                $gui->InviaProspetti();
            }
        }
    }
    if (isset($_GET['action']) && $_GET['action'] === 'apri_prospetti') {
        // Logica per ottenere il percorso della cartella
        $percorsoCartella = __DIR__ . '/src/prospetti/';

        // Utilizza il comando appropriato per aprire la cartella nel sistema operativo dell'utente
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            // Se Windows, usa il comando "start"
            exec('start "" "' . $percorsoCartella . '"');
        } else {
            // Altrimenti, usa il comando "open"
            exec('open "' . $percorsoCartella . '"');
        }

        exit;
    }

}
?>

