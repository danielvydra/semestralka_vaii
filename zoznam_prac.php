<?php
include 'database.php';
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Zoznam prác</title>
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
        <div class="horne-menu-vlavo">Systém záverečných prác</div>
        <div class="horne-menu-vpravo">
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
            <a href="odhlasenie.php"><div class="fa fa-power-off ikona-tlacidlo"></div>Odhlásiť</a>
        </div>
    </div>
    <div class="skrol-kontajner">
        <div id="skrol-indikator" class="skrol-indikator"></div>
    </div>

    <div class="zaver-praca filter">
        <form id="filter-prac" class="formular-registracia">
            <h1 class="stred tooltip" onclick="zobrazViacInfo(this)">Filter<span class="tooltiptext">Kliknutím zobraziť/skryť filter</span></h1
            ><div style="display: none;">
                <div class="stred">
                    <input name="nazov-prace" type="text" placeholder="Vložte názov práce">
                    <input name="meno-veduceho" type="text" placeholder="Vložte meno vedúceho">
                </div>

                <div class="stred">
                    <select name="typ-prace" class="popis-prace dropdown" form="filter-prac" >
                        <option value="0">Typ práce</option>
                        <?php
                        $result_typy_prac = getTypyPrac();
                        while ($typ_prace = $result_typy_prac->fetch_array()) {
                            echo '<option value="'. $typ_prace["id_typ"] .'">'. $typ_prace["nazov"] .'</option>';
                        }
                        ?>
                    </select>
                    <select name="katedra" class="popis-prace dropdown" form="filter-prac" >
                        <option value="0">Katedra</option>
                        <?php
                        $result_katedry = getKatedry();
                        while ($katedra = $result_katedry->fetch_array()) {
                            echo '<option value="'. $katedra["id_katedra"] .'">'. $katedra["nazov"] .'</option>';
                        }
                        ?>
                    </select>
                    <select name="stav-prace" class="popis-prace dropdown" form="filter-prac" >
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
                    <select name="triedit-podla" class="popis-prace dropdown" form="filter-prac" >
                        <option value="0">Triediť podľa</option>
                        <option value="nazov">Názov témy</option>
                        <option value="ucitel">Meno vedúceho</option>
                        <option value="typ-prace">Typ práce</option>
                        <option value="katedra">Katedra</option>
                    </select>
                    <select name="triedit-ako" class="popis-prace dropdown" form="filter-prac" >
                        <option value="vzostupne">Vzostupne</option>
                        <option value="zostupne">Zostupne</option>
                    </select>
                </div>
                <div>
                    <button type="submit" class="tlacidlo-potvrdit tlacidlo-formular tlacidlo-filter">Filtruj</button>
                </div>
            </div>

        </form>
    </div>

    <div class="kontajner-zoznam-tem">
        <?php
        $prace = getZaverecnePrace();
        if ($prace != null && mysqli_num_rows($prace) > 0) {
            while ($praca = $prace->fetch_array()) {
                $typ_prace = getNazovPrace($praca["id_typ"]);
                $student = getStudent($praca["id_student"]);
                $veduci = getVeduci($praca["id_veduci"]);

                echo '<div id="'. $praca["id_tema"] .'" class="zaver-praca">';
                echo '<div id="nazov-prace" class="nazov-prace"><b>'. $praca["nazov_sk"] .'</b></div>';
                echo '<hr class="oddelovac">';
                echo '<div><b>Anglický názov témy: '. $praca["nazov_en"] .'</b></div>';
                echo '<div><b>Predmet práce: </b>'. $praca["popis"] .'</div>';
                echo '<div><b>Typ práce: </b>'. $typ_prace .'</div>';
                echo '<div><b>Vedúci: </b>'. $veduci["titul_pred"] . " " . $veduci["meno"] . " " .  $veduci["titul_za"] .'</div>';
//            echo '<div><b>Mentor: </b>'. $praca["id_mentor"] .'</div>';
                if ($student != null) {
                    echo '<div><b>Študent: </b>'. $student["titul_pred"] . " " . $student["meno"] . " " .  $student["titul_za"] .'</div>';
                } else {
                    echo '<div><b>Študent: </b></div>';
                }
                echo '</div>';
            }
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
