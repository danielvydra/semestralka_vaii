<?php
include_once '../resources/databaza.php';
include_once '../resources/metody.php';
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
    <form id="osobne-udaje" class="formular" method="post" action="javascript:upravitOsobneUdaje()">
        <h1 class="stred transform-stred tooltip">Osobné údaje</h1>

        <?php

        if ($_SESSION["rola"] == "student") {
            $osoba = getStudent($_SESSION["id_osoba"]);
        } elseif ($_SESSION["rola"] == "ucitel") {
            $osoba = getVeduci($_SESSION["id_osoba"]);
        }

        echo '<p>';
            echo '<b>Meno: </b>' . $osoba["titul_pred"] . " " . $osoba["meno"] . " " . $osoba["titul_za"];
            echo '<br>';
            echo '<b>Osobné číslo: </b>' . $osoba["os_cislo"];
            echo '<br>';
            echo '<b>Email: </b>' . $osoba["email"];
            echo '<br>';
            echo '<b>Telefón: </b>0' . $osoba["telefon"];
            echo '<br>';
            echo '<b>Upravenie: </b>' . $osoba["upravenie"];
            echo '<br>';
            echo '<b>Vytvorenie: </b>' . $osoba["vytvorenie"];
            echo '<br>';
            echo '<b>Fakulta: </b>' . $osoba["fakulta"];
            if ($_SESSION["rola"] == "student") {
                echo '<br>';
                echo '<b>Skupina: </b>' . $osoba["skupina"];
                echo '<br>';
                echo '<b>Odbor: </b>' . $osoba["odbor"];
            } elseif ($_SESSION["rola"] == "ucitel") {
                echo '<br>';
                echo '<b>Katedra: </b>' . $osoba["katedra"];
                echo '<br>';
                echo '<b>Miestnosť: </b>' . $osoba["miestnost"];
                echo '<br>';
                echo '<b>Voľná kapacita: </b>' . ($osoba["volna_kapacita"] == 1 ? "áno" : "nie");
            }
        echo '</p>';

        echo '<h2 class="stred">Úprava údajov</h2>';
        echo '<label><b>Titul pred menom</b></label>';
        echo '<select id="titul-pred" name="titul-pred" class="medzery dropdown" form="osobne-udaje" disabled="disabled">';
            $result_tituly = getTituly();
            while ($titul = $result_tituly->fetch_array()) {
                if ($titul["nazov"] == $osoba["titul_pred"]) {
                    echo '<option value="' . $titul["id_titul"] . '" selected>' . $titul["nazov"] . '</option>';
                } else {
                    echo '<option value="' . $titul["id_titul"] . '">' . $titul["nazov"] . '</option>';
                }
            }
        echo '</select>';
        echo '<label><b>Celé meno</b></label>';
        echo '<input pattern="[A-Z+ľščťžýáíéôúäňĽŠČŤŽÝÁÍÉÚŇ]+(([\',. -][a-zA-ZľščťžýáíéôúäňĽŠČŤŽÝÁÍÉÚŇ])?[a-zA-ZľščťžýáíéôúäňĽŠČŤŽÝÁÍÉÚŇ]*)*" id="meno" name="meno" type="text" placeholder="Celé meno" value="'. $osoba["meno"] .'" disabled="disabled" required>';
        echo '<label><b>Titul za menom</b></label>';
        echo '<select id="titul-za" name="titul-za" class="medzery dropdown" form="osobne-udaje" disabled="disabled">';
            $result_tituly = getTituly();
            while ($titul = $result_tituly->fetch_array()) {
                if ($titul["nazov"] == $osoba["titul_za"]) {
                    echo '<option value="' . $titul["id_titul"] . '" selected>' . $titul["nazov"] . '</option>';
                } else {
                    echo '<option value="' . $titul["id_titul"] . '">' . $titul["nazov"] . '</option>';
                }
            }
        echo '</select>';
        echo '<label><b>Email</b></label>';
        echo '<input id="email" pattern="[a-zA-Z._]+@([a-zA-z]+\.)+[a-zA-Z]{2,4}" name="email" type="text" placeholder="Email" value="'. $osoba["email"] .'" disabled="disabled" required>';
        echo '<label><b>Telefón</b></label>';
        echo '<input id="telefon" pattern="[0]{1}[0-9]{9}" name="telefon" type="text" placeholder="Telefón" value="0' . $osoba["telefon"] .'" disabled="disabled" required>';

        ?>


        <div class="transform-stred stred">
            <button id="ulozit" type="submit" class="os-udaje-tlacidlo tlacidlo-potvrdit tlacidlo-formular tlacidlo-filter" disabled="disabled"><i class="fa fa-save ikona-tlacidlo"></i>Uložiť</button>
            <button id="upravit" type="button" class="os-udaje-tlacidlo tlacidlo-upravit tlacidlo-potvrdit tlacidlo-formular tlacidlo-filter" onclick="editovatOsobneUdaje(this)"><i class="fa fa-edit ikona-tlacidlo"></i><p>Upraviť</p></button>
        </div>
</div>

</form>
</div>

<button id="tlacidlo-ist-hore" class="tlacidlo-ist-hore" onclick="istHore()">
    <i class="fa fa-arrow-up ikona-tlacidlo"></i>Hore
</button>

</body>
</html>
