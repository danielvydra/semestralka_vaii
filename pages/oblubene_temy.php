<?php
include_once "../resources/dependencies.php";
session_start();

if (!isset($_SESSION["rola"])) {
    header("Location: ./prihlasenie.php");
    exit();
}
if ($_SESSION["rola"] != "student") {
    header("Location: ./nepovoleny_pristup.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="sk">

<?php
vypisHlavicku("Obľúbené témy");
?>

<body onscroll="skrolovanie()">


    <?php
    vypisMenu(4);
    vypisOblubenychPrac();
    ?>

<button id="tlacidlo-ist-hore" class="tlacidlo-ist-hore" onclick="istHore()"><i class="fa fa-arrow-up ikona-tlacidlo"></i>Hore</button>
<div id="snackbar">Text</div>

</body>
</html>
