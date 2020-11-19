<?php
include_once "../resources/dependencies.php";
session_start();

if (!isset($_SESSION["rola"])) {
    header("Location: ./prihlasenie.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="sk">

<?php
vypisHlavicku("Osobné údaje");
?>

<body onscroll="skrolovanie()">


    <?php
    vypisMenu(3);
    ?>

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
