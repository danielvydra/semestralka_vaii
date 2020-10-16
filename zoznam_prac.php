<?php
include 'database.php';
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
            <a href="oblubene_temy.php"><div class="fa fa-star ikona-tlacidlo"></div>Obľúbené témy</a>
            <a href="pouzivatelia.php"><div class="fa fa-users ikona-tlacidlo"></div>Používatelia</a>
            <a href="osobne_udaje.php"><div class="fa fa-address-card ikona-tlacidlo"></div>Osobné údaje</a>
            <a href="pridavanie_tem.php"><div class="fa fa-plus ikona-tlacidlo"></div>Pridávanie tém</a>
            <a href="odhlasenie.php"><div class="fa fa-power-off ikona-tlacidlo"></div>Odhlásiť</a>
        </div>

    </div>
    <div class="skrol-kontajner">
        <div id="skrol-indikator" class="skrol-indikator"></div>
    </div>

    <div class="kontajner-zoznam-tem">
        <div id="id-prace" class="zaver-praca modal">
            <div id="nazov-prace" class="nazov-prace"><b>Názov témy Názov témy Názov témy Názov témy </b></div>
            <hr class="oddelovac">
            <div><b>Anglický názov témy:</b> aslkdjfasjdfasjdfjasldfj</div>
            <div><b>Predmet práce:</b> asdofsdofijasidjfoaisjdfoiaj</div>
            <div><b>Typ práce:</b> asfdjfajs dffgndfg</div>
            <div><b>Vedúci:</b> asfdjfajsoi dfjlsajid</div>
            <div><b>Mentor:</b> zrhzzbn rgcdc</div>
            <div><b>Študent:</b> sadfjoa osidfjis</div>
        </div>

        <?php
        $prace = getZaverecnePrace();
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
        ?>

    </div>

    <button id="tlacidlo-ist-hore" class="tlacidlo-ist-hore" onclick="istHore()"><div class="fa fa-arrow-up ikona-tlacidlo"></div>Hore</button>

</body>
</html>
