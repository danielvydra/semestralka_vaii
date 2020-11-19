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
vypisHlavicku("Pridávanie tém");
?>

<body onscroll="skrolovanie()">

    <?php
    vypisMenu(4);
    ?>

    <div class="kontajner-zoznam-tem kont-nova-tema transform-stred pridavanie-tem">
        <div id="id-prace" class="zaver-praca nova-tema">

            <form id="nova-tema" class="formular" method="post" action="javascript:pridatNovuTemu()">
                <h1 class="stred">Pridávanie tém</h1>
                <p class="stred">Vyplňte prosím nasledujúce údaje pre pridanie témy</p>

                <label for="nazov-prace-input" class="stred stitok"><b>Názov práce</b></label>
                <div class="input-riadok">
                    <div class="fas fa-heading ikona"></div>
                    <input id="nazov-prace-input" type="text" placeholder="Vložte názov práce" name="nazov-prace" maxlength="200" required>
                </div>

                <label for="ang-nazov-prace" class="stred stitok"><b>Anglický názov práce</b></label>
                <div class="input-riadok">
                    <div class="fas fa-language ikona"></div>
                    <input id="ang-nazov-prace" type="text" placeholder="Vložte anglický názov práce" name="ang-nazov-prace" maxlength="200" required>
                </div>

                <label for="popis-prace" class="stred stitok"><b>Popis práce</b></label>
                <textarea id="popis-prace" class="medzery" placeholder="Zadajte cieľ práce" rows="4" cols="50" name="popis-prace" form="nova-tema" maxlength="2000" required></textarea>

                <label for="typ-prace" class="stred stitok"><b>Typ práce</b></label>
                <select name="typ-prace" class="medzery dropdown transform-stred" id="typ-prace" form="nova-tema">
                    <?php
                    $result_typy_prac = getTypyPrac();
                    while ($typ_prace = $result_typy_prac->fetch_array()) {
                        echo '<option value="'. $typ_prace["id_typ"] .'">'. $typ_prace["nazov"] .'</option>';
                    }
                    ?>
                </select>

                <div>
                    <button type="submit" class="transform-stred tlacidlo-potvrdit tlacidlo-formular"><i class="fa fa-plus ikona-tlacidlo"></i>Pridať</button>
                </div>
            </form>

        </div>
    </div>

        <?php
        vypisMojichVytvorenychPrac();
        ?>

    <button id="tlacidlo-ist-hore" class="tlacidlo-ist-hore" onclick="istHore()"><i class="fa fa-arrow-up ikona-tlacidlo"></i>Hore</button>
    <div id="snackbar">Text</div>

    </body>
    </html>
