<?php
include_once "../resources/dependencies.php";
session_start();
?>

<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <title>Používatelia</title>
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
        <div class="horne-menu-vlavo">Systém záverečných prác<div onclick="zobrazMenu()" class="fa fa-bars ikona menu-ikona"></div></div>
        <div id="horne-menu-vpravo"  class="horne-menu-vpravo">
            <a href="zoznam_prac.php">
                <div class="fa fa-stream ikona-tlacidlo"></div>
                Zoznam prác</a>
            <a class="aktivne" href="pouzivatelia.php">
                <div class="fa fa-users ikona-tlacidlo"></div>
                Používatelia</a>
            <a href="osobne_udaje.php">
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

    <div class="zaver-praca filter">
        <form id="filter-uzivatelov" class="formular" action="javascript:filtrovatPouzivatelov()">
            <h1 class="stred transform-stred tooltip" onclick="zobrazViacInfo(this)">Filter<span class="tooltiptext">Kliknutím zobraziť/skryť filter</span></h1
            ><div style="display: none;">
                <div class="stred">
                    <input name="meno-osoby" type="text" placeholder="Meno osoby">
                    <select name="typ-osoby" class="medzery dropdown" form="filter-uzivatelov">
                        <option value="">Typ osoby</option>
                        <?php
                        $result_typy_osob = getTypyOsob();
                        while ($typ_osoby = $result_typy_osob->fetch_array()) {
                            echo '<option value="' . $typ_osoby["id_rola"] . '">' . $typ_osoby["nazov_role"] . '</option>';
                        }
                        ?>
                    </select>
                </div>

                <div class="stred">
                    <select name="triedit-podla" class="medzery dropdown" form="filter-uzivatelov">
                        <option value="">Triediť podľa</option>
                        <option value="meno">Meno osoby</option>
                        <option value="ou.id_rola">Typ osoby</option>
                    </select>
                    <select name="triedit-ako" class="medzery dropdown" form="filter-uzivatelov">
                        <option value="asc">Vzostupne</option>
                        <option value="desc">Zostupne</option>
                    </select>
                </div>

                <div>
                    <button type="submit" class="tlacidlo-potvrdit tlacidlo-formular tlacidlo-filter transform-stred"><i class="fa fa-filter ikona-tlacidlo"></i>Filtruj</button>
                </div>
            </div>
        </form>
    </div>

        <?php
        $pouzivatelia = getPouzivatelov();
        vypisPouzivatelov($pouzivatelia);
        ?>

    <button id="tlacidlo-ist-hore" class="tlacidlo-ist-hore" onclick="istHore()">
        <i class="fa fa-arrow-up ikona-tlacidlo"></i>
        Hore
    </button>

</body>
</html>
