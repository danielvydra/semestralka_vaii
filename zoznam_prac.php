<?php
$servername = "localhost";
$username = "root";
$password = "123456789";
$dbName = "db_semestralka_vaii";
$conn = new mysqli($servername, $username, $password, $dbName);

if ($conn->connect_error) {
    die("Pripojenie zlyhalo: " . $conn->connect_error);
}

$sql = "select * from zaver_prace;";
$result = $conn->query($sql);

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Zoznam prác</title>
    <script src="javascript.js"></script>
    <link rel="stylesheet" href="dizajn.css">
    <link rel="stylesheet" href="fontawesome/css/all.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto">
</head>
<body onscroll="skrolovanie()">

    <div class="horne-menu">
        <div class="horne-menu-vlavo">Systém záverečných prác</div>
        <div class="horne-menu-vpravo">
            <a class="aktivne"><div class="fa fa-stream ikona-tlacidlo"></div>Zoznam prác</a>
            <a><div class="fa fa-star ikona-tlacidlo"></div>Obľúbené témy</a>
            <a><div class="fa fa-address-card ikona-tlacidlo"></div>Osobné údaje</a>
            <a><div class="fa fa-plus ikona-tlacidlo"></div>Pridávanie tém</a>
            <a><div class="fa fa-power-off ikona-tlacidlo"></div>Odhlásiť</a>
        </div>

    </div>
    <div class="skrol-kontajner">
        <div id="skrol-indikator" class="skrol-indikator"></div>
    </div>

    <div class="kontajner-zoznam-tem">
        <div id="id-prace" class="zaver-praca">
            <div id="nazov-prace"><b>Názov témy Názov témy Názov témy Názov témy </b></div>
            <hr class="oddelovac">
            <div><b>Anglický názov témy:</b> aslkdjfasjdfasjdfjasldfj</div>
            <div><b>Predmet práce:</b> asdofsdofijasidjfoaisjdfoiaj</div>
            <div><b>Typ práce:</b> asfdjfajs dffgndfg</div>
            <div><b>Vedúci:</b> asfdjfajsoi dfjlsajid</div>
            <div><b>Mentor:</b> zrhzzbn rgcdc</div>
            <div><b>Študent:</b> sadfjoa osidfjis</div>
        </div>

        <?php
        while ($row = $result->fetch_array()) {
            $typ_prace = "";
            if ($row["id_typ"] != null) {
                $sql = "select nazov from typy_prac where id_typ = ". $row["id_typ"] .";";
                $result_typ_prace = $conn->query($sql);
                $row1 = $result_typ_prace->fetch_array();
                $typ_prace = $row1["nazov"];
            }

            echo '<div id="'. $row["id_tema"] .'" class="zaver-praca">';
            echo '<div id="nazov-prace"><b>'. $row["nazov_sk"] .'</b></div>';
            echo '<hr class="oddelovac">';
            echo '<div><b>Anglický názov témy: </b>'. $row["nazov_en"] .'</div>';
            echo '<div><b>Predmet práce: </b>'. $row["popis"] .'</div>';
            echo '<div><b>Typ práce: </b>'. $typ_prace .'</div>';
            echo '<div><b>Vedúci: </b>'. $row["id_veduci"] .'</div>';
            echo '<div><b>Mentor: </b>'. $row["id_mentor"] .'</div>';
            echo '<div><b>Študent: </b>'. $row["id_student"] .'</div>';
            echo '</div>';
        }
        ?>

    </div>

    <button id="tlacidlo-ist-hore" class="tlacidlo-ist-hore" onclick="istHore()"><div class="fa fa-arrow-up ikona-tlacidlo"></div>Hore</button>

</body>
</html>

<?php
?>