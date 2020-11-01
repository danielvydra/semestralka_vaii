<?php
include_once '../resources/databaza.php';
include_once '../resources/metody.php';
include_once '../resources/OOP.php';
session_start();
?>

<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <title>Osobné údaje</title>
    <script src="../resources/javascript.js"></script>
    <script src="../resources/jquery-3.5.1.js"></script>
    <script src="../jquery-ui/jquery-ui.js"></script>
    <link rel="stylesheet" href="../jquery-ui/jquery-ui.css">
    <link rel="stylesheet" href="../resources/dizajn.css">
    <link rel="stylesheet" href="../fontawesome/css/all.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body onscroll="skrolovanie()">

<div class="horne-menu">
    <div>
        <div class="horne-menu-vlavo">Systém záverečných prác
            <div onclick="zobrazMenu()" class="fa fa-bars ikona menu-ikona"></div>
        </div>
    </div>
    <div id="horne-menu-vpravo" class="horne-menu-vpravo">
        <a href="zoznam_prac.php">
            <div class="fa fa-stream ikona-tlacidlo"></div>
            Zoznam prác</a>
        <a href="pouzivatelia.php">
            <div class="fa fa-users ikona-tlacidlo"></div>
            Používatelia</a>
        <a class="aktivne" href="osobne_udaje.php">
            <div class="fa fa-address-card ikona-tlacidlo"></div>
            Osobné údaje</a>
        <?php
        if ($_SESSION["rola"] == "ucitel") {
            echo '<a href="pridavanie_tem.php"><div class="fa fa-plus ikona-tlacidlo"></div>Pridávanie tém</a>';

        } else if ($_SESSION["rola"] == "student") {
            echo '<a href="oblubene_temy.php"><div class="fa fa-star ikona-tlacidlo"></div>Obľúbené témy</a>';
        }
        ?>
        <a href="../resources/odhlasenie.php">
            <div class="fa fa-power-off ikona-tlacidlo"></div>
            Odhlásiť</a>
    </div>
</div>
<div class="skrol-kontajner">
    <div id="skrol-indikator" class="skrol-indikator"></div>
</div>

<div class="zaver-praca filter osobne-udaje">
    <?php
    vypisOsobnychUdajov();
    ?>
</div>


<button id="tlacidlo-ist-hore" class="tlacidlo-ist-hore" onclick="istHore()">
    <i class="fa fa-arrow-up ikona-tlacidlo"></i>Hore
</button>

<div id="snackbar">Text</div>

</body>
</html>
