<?php
include '../resources/databaza.php';
include '../resources/metody.php';
session_start();
?>

<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <title>Zoznam prác</title>
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
            <div class="horne-menu-vlavo">Systém záverečných prác<div onclick="zobrazMenu()" class="fa fa-bars ikona menu-ikona"></div></div>
        </div>
        <div id="horne-menu-vpravo" class="horne-menu-vpravo">
            <a class="aktivne"><div class="fa fa-stream ikona-tlacidlo"></div>Zoznam prác</a>
            <a href="pouzivatelia.php"><div class="fa fa-users ikona-tlacidlo"></div>Používatelia</a>
            <a href="osobne_udaje.php"><div class="fa fa-address-card ikona-tlacidlo"></div>Osobné údaje</a>
            <?php
            if ($_SESSION["rola"] == "ucitel") {
                echo '<a href="pridavanie_tem.php"><div class="fa fa-plus ikona-tlacidlo"></div>Pridávanie tém</a>';

            } else if ($_SESSION["rola"] == "student") {
                echo '<a href="oblubene_temy.php"><div class="fa fa-star ikona-tlacidlo"></div>Obľúbené témy</a>';
            }
            ?>
            <a href="../resources/odhlasenie.php"><div class="fa fa-power-off ikona-tlacidlo"></div>Odhlásiť</a>
        </div>
    </div>
    <div class="skrol-kontajner">
        <div id="skrol-indikator" class="skrol-indikator"></div>
    </div>

    <div class="zaver-praca filter">
        <form id="filter-prac" class="formular" method="post" action="javascript:filtrovatTemy()">
            <h1 class="stred transform-stred tooltip" onclick="zobrazViacInfo(this)">Filter<span class="tooltiptext">Kliknutím zobraziť/skryť filter</span></h1
            ><div style="display: none;">
                <div class="stred">
                    <input name="nazov-prace" type="text" placeholder="Názov práce">
                    <input name="meno-veduceho" type="text" placeholder="Meno vedúceho">
                    <input name="meno-studenta" type="text" placeholder="Meno študenta">
                </div>

                <div class="stred">
                    <select name="typ-prace" class="medzery dropdown" form="filter-prac">
                        <option value="0">Typ práce</option>
                        <?php
                        $result_typy_prac = getTypyPrac();
                        while ($typ_prace = $result_typy_prac->fetch_array()) {
                            echo '<option value="'. $typ_prace["id_typ"] .'">'. $typ_prace["nazov"] .'</option>';
                        }
                        ?>
                    </select>
                    <select name="katedra" class="medzery dropdown" form="filter-prac" >
                        <option value="0">Katedra</option>
                        <?php
                        $result_katedry = getKatedry();
                        while ($katedra = $result_katedry->fetch_array()) {
                            echo '<option value="'. $katedra["id_katedra"] .'">'. $katedra["nazov"] .'</option>';
                        }
                        ?>
                    </select>
                    <select name="stav-prace" class="medzery dropdown" form="filter-prac" >
                    <option value="0">Stav práce</option>
                    <?php
                    $result_stavy_prace = getStavyPrace();
                    while ($stav = $result_stavy_prace->fetch_array()) {
                        echo '<option value="'. $stav["id_stav"] .'">'. $stav["stav"] .'</option>';
                    }
                    ?>
                </select>
                </div>

                <div class="stred">
                    <select name="triedit-podla" class="medzery dropdown" form="filter-prac" >
                        <option value="0">Triediť podľa</option>
                        <option value="nazov_sk">Názov témy</option>
                        <option value="uc.meno">Meno vedúceho</option>
                        <option value="st.meno">Meno študenta</option>
                        <option value="zp.id_typ">Typ práce</option>
                        <option value="zp.id_stav">Stav práce</option>
                        <option value="u.id_katedra">Katedra</option>
                    </select>
                    <select name="triedit-ako" class="medzery dropdown" form="filter-prac" >
                        <option value="0">Vzostupne</option>
                        <option value="desc">Zostupne</option>
                    </select>
                </div>
                <div>
                    <button id="filtrovat" type="submit" class="transform-stred tlacidlo-potvrdit tlacidlo-formular tlacidlo-filter">Filtruj</button>
                </div>
            </div>

        </form>
    </div>

    <div id="zoznam-prac" class="kontajner-zoznam-tem transform-stred">
        <?php
        $prace = getZaverecnePrace();
        $oblubenePrace = array();
        if ($_SESSION["rola"] == "student") {
            $oblubenePrace = getZoznamOblubenychTem($_SESSION["id_osoba"]);
        }

        if ($prace != null && mysqli_num_rows($prace) > 0) {
            vypisPrac($prace, $oblubenePrace);
        } else {
            echo '<div class="zaver-praca">';
            echo '<div class="stred">Neboli nájdené žiadne práce.</div>';
            echo '</div>';
        }
        ?>
    </div>

    <button id="tlacidlo-ist-hore" class="tlacidlo-ist-hore" onclick="istHore()"><i class="fa fa-arrow-up ikona-tlacidlo"></i>Hore</button>

</body>
</html>
