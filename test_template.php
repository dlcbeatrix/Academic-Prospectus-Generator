<?php
/**
 * Template Name: test_template
 */
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Test</title>
    <style type="text/css">
        body {
            font-family: 'Arial', sans-serif;
            align-items: center;
            background-color: #131314;
            margin: 0;
            padding: 0;
            text-align: center;
            color: white;
        }
        h1 {
            font-size: 35px;
            color: #c505e1;
        }
        h2 {
            font-size: 30px;
            color: #c505e1;
        }

    </style>
</head>
<body>
<h1>Test</h1>
<h2>Eventuali errori:</h2>

<?php
// Includi i file di test
require_once  'src/test/EsameLaureandoTest.php';
require_once  'src/test/CarrieraLaureandoInformaticaTest.php';
require_once  'src/test/CarrieraLaureandoTest.php';

// Esegui i test e gestisci eventuali errori
$dataJson = file_get_contents('wp-content/themes/twentytwentyfour/templates/src/dati_test/dati_test.json');
$data = json_decode($dataJson, true);

// Esegui i test per ogni laureando
try {
    $carrieraLaureandoTest = new CarrieraLaureandoTest();
    $carrieraLaureandoTest->test();
    echo "<br>";
    $carrieraLaureandoInformaticaTest = new CarrieraLaureandoInformaticaTest();
    $carrieraLaureandoInformaticaTest->testInfo();
    $esameTest = new EsameLaureandoTest();
    $esameTest->test();

    echo "<p>+++++++++++++++++++++++++++++++++++++++++++++++TUTTI I TEST SONO STATI ESEGUTI CON SUCCESSO+++++++++++++++++++++++++++++++++++++++++++++++</p>";
} catch (Exception $e) {
    echo "<p class='error'>Errore durante l'esecuzione dei test: {$e->getMessage()}</p>";
}
?>
</body>
</html>
