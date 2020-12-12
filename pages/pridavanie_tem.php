<?php
include_once "../resources/dependencies.php";
session_start();

if (!isset($_SESSION["rola"])) {
    header("Location: ./prihlasenie.php");
    exit();
}
if ($_SESSION["rola"] != "ucitel") {
    header("Location: ./nepovoleny_pristup.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="sk">

<?php
vypisHlavicku("Pridávanie tém");
?>

<body onscroll="skrolovanie()">

    <?php
    vypisMenu(4);
    vypisPridavaniaTem();
    vypisMojichVytvorenychPrac();
    ?>

    <button id="tlacidlo-ist-hore" class="tlacidlo-ist-hore" onclick="istHore()"><i class="fa fa-arrow-up ikona-tlacidlo"></i>Hore</button>
    <div id="snackbar">Text</div>

</body>
</html>
