<?php
include 'database.php';
?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Pridávanie tém</title>
        <script src="javascript.js"></script>
        <script src="jquery-3.5.1.js"></script>
<!--        <script src="bootstrap/js/bootstrap.js"></script>-->
<!--        <link rel="stylesheet" href="bootstrap/css/bootstrap.css">-->
        <link rel="stylesheet" href="dizajn.css">
        <link rel="stylesheet" href="fontawesome/css/all.css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    <body onscroll="skrolovanie()">

    <div class="horne-menu">
        <div class="horne-menu-vlavo">Systém záverečných prác</div>
        <div class="horne-menu-vpravo">
            <a href="zoznam_prac.php"><div class="fa fa-stream ikona-tlacidlo"></div>Zoznam prác</a>
            <a href="oblubene_temy.php"><div class="fa fa-star ikona-tlacidlo"></div>Obľúbené témy</a>
            <a href="pouzivatelia.php"><div class="fa fa-users ikona-tlacidlo"></div>Používatelia</a>
            <a href="osobne_udaje.php"><div class="fa fa-address-card ikona-tlacidlo"></div>Osobné údaje</a>
            <a class="aktivne" href="pridavanie_tem.php"><div class="fa fa-plus ikona-tlacidlo"></div>Pridávanie tém</a>
            <a href="odhlasenie.php"><div class="fa fa-power-off ikona-tlacidlo"></div>Odhlásiť</a>
        </div>

    </div>
    <div class="skrol-kontajner">
        <div id="skrol-indikator" class="skrol-indikator"></div>
    </div>

    <div class="kontajner-zoznam-tem">
        <div id="id-prace" class="zaver-praca">

            <form id="nova-tema" class="formular-registracia">
                <h1 class="stred">Pridávanie tém</h1>
                <p class="stred">Vyplňte prosím nasledujúce údaje pre pridanie témy</p>

                <label for="nazov-prace-input" class="stred"><b>Názov práce</b></label>
                <div class="input-riadok">
                    <div class="fas fa-user ikona"></div>
                    <input id="nazov-prace-input" type="text" placeholder="Vložte názov práce" name="nazov-prace" required>
                </div>

                <label for="popis-prace" class="stred"><b>Popis práce</b></label>
                <textarea id="popis-prace" class="popis-prace" rows="4" cols="50" name="popis-prace" form="nova-tema"></textarea>

                <label for="ang-nazov-prace" class="stred"><b>Anglický názov práce</b></label>
                <div class="input-riadok">
                    <div class="fas fa-user ikona"></div>
                    <input id="ang-nazov-prace" type="text" placeholder="Vložte anglický názov práce" name="ang-nazov-prace" required>
                </div>

                <div>
                    <button type="submit" class="tlacidlo-prihlasit tlacidlo-formular">Prihlásiť</button>
                </div>
            </form>

        </div>
    </div>

    <button id="tlacidlo-ist-hore" class="tlacidlo-ist-hore" onclick="istHore()"><div class="fa fa-arrow-up ikona-tlacidlo"></div>Hore</button>

    </body>
    </html>
