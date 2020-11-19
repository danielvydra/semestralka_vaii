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
vypisHlavicku("Zoznam prác");
?>

<body onscroll="skrolovanie()">

    <?php
    vypisMenu(1);
    vypisFilterPrac();
    vypisZaverecnePrace();
    ?>

    <button id="tlacidlo-ist-hore" class="tlacidlo-ist-hore" onclick="istHore()"><i class="fa fa-arrow-up ikona-tlacidlo"></i>Hore</button>
    <div id="snackbar">Text</div>

</body>
</html>
