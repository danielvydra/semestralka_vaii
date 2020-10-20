<?php
include 'databaza.php';
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Používatelia</title>
    <script src="javascript.js"></script>
    <script src="jquery-3.5.1.js"></script>
    <script src="jquery-ui/jquery-ui.js"></script>
    <link rel="stylesheet" href="jquery-ui/jquery-ui.css">
    <!--    <script src="bootstrap/js/bootstrap.js"></script>-->
    <!--    <link rel="stylesheet" href="bootstrap/css/bootstrap.css">-->
    <link rel="stylesheet" href="dizajn.css">
    <link rel="stylesheet" href="fontawesome/css/all.css">
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
            <a href="odhlasenie.php">
                <div class="fa fa-power-off ikona-tlacidlo"></div>
                Odhlásiť</a>
        </div>

    </div>
    <div class="skrol-kontajner">
        <div id="skrol-indikator" class="skrol-indikator"></div>
    </div>

    <div class="zaver-praca filter">
        <form id="filter-prac" class="formular">
            <h1 class="stred transform-stred tooltip" onclick="zobrazViacInfo(this)">Filter<span class="tooltiptext">Kliknutím zobraziť/skryť filter</span></h1
            ><div style="display: none;">
                <div class="stred">
                    <input name="meno-osoby" type="text" placeholder="Meno osoby">
                    <select name="typ-osoby" class="medzery dropdown" form="filter-prac" required>
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
                </div>

                <div class="stred">
                    <select name="triedit-podla" class="medzery dropdown" form="filter-prac">
                        <option value="0">Triediť podľa</option>
                        <option value="meno">Meno osoby</option>
                        <option value="typ">Typ osoby</option>
                    </select>
                    <select name="triedit-ako" class="medzery dropdown" form="filter-prac" required>
                        <option value="">Vzostupne</option>
                        <option value="zostupne">Zostupne</option>
                    </select>
                </div>

                <div>
                    <button type="submit" class="tlacidlo-potvrdit tlacidlo-formular tlacidlo-filter transform-stred">Filtruj</button>
                </div>
            </div>
        </form>
    </div>

    <div class="kontajner-zoznam-tem transform-stred">
        <?php
        $pouzivatelia = getPouzivatelov();
        while ($pouzivatel = $pouzivatelia->fetch_array()) {
            if ($pouzivatel["nazov_role"] == "ucitel") {
                $osoba = getVeduci($pouzivatel["id_osoba"]);
                $viac_info = '<div><b>Miestnosť: </b> ' . $osoba["miestnost"] . '</div>';
                $viac_info .= '<div><b>Fakulta: </b> ' . $osoba["nazov_fakulty"] . '</div>';
                $viac_info .= '<div><b>Katedra: </b> ' . $osoba["katedra"] . '</div>';
                if ($osoba["volna_kapacita"]) {
                    $viac_info .= '<div><b>Prijíma témy: </b>áno<div class="fa fa-check info-ikona ok"></div></div>';
                } else {
                    $viac_info .= '<div><b>Prijíma témy: </b>nie<div class="fa fa-times info-ikona not-ok"></div></div>';
                }
            } else if ($pouzivatel["nazov_role"] == "student") {
                $osoba = getStudent($pouzivatel["id_osoba"]);
                $viac_info = '<div><b>Študijná skupina: </b> ' . $osoba["skupina"] . '</div>';
                $viac_info .= '<div><b>Odbor: </b> ' . $osoba["odbor"] . '</div>';
                $viac_info .= '<div><b>Fakulta: </b> ' . $osoba["fakulta"] . '</div>';
            }

            echo '<div id="' . $pouzivatel["os_cislo"] . '" class="zaver-praca pouzivatelia">';
            echo '<div onclick="zobrazViacInfo(this)" class="nazov-prace"><b>' . $pouzivatel["titul_pred"] . " " . $pouzivatel["meno"] . " " . $pouzivatel["titul_za"] . '</b></div>';
            echo '<div style="display: none;">';
            echo '<hr class="oddelovac">';
            echo '<div><b>Email: </b> ' . $pouzivatel["email"] . '</div>';
            echo '<div><b>Osobné číslo: </b>' . $pouzivatel["os_cislo"] . '</div>';
            echo '<div><b>Telefón: </b>' . "0" . $pouzivatel["telefon"] . '</div>';
            echo $viac_info;
            echo '</div>';
            echo '</div>';
        }
        ?>
    </div>

    <button id="tlacidlo-ist-hore" class="tlacidlo-ist-hore" onclick="istHore()">
        <i class="fa fa-arrow-up ikona-tlacidlo"></i>
        Hore
    </button>

</body>
</html>
