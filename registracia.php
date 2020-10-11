<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registrácia</title>
    <script src="javascript.js"></script>
    <link rel="stylesheet" href="dizajn.css">
    <link rel="stylesheet" href="fontawesome/css/all.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto">
</head>
<body>
    <div id="pozadie" class="pozadie"></div>
    <div class="nadpis">Systém záverečných prác</div>
    <div id="kontent" class="kontent">
        <form class="formular-registracia">
            <h1 class="stred">Registrácia</h1>
            <p class="stred">Vyplňte prosím nasledujúce údaje pre vytvorenie účtu</p>
            <label for="os_cislo" class="stred"><b>Osobné číslo</b></label>
            <div class="input-riadok">
                <div class="fas fa-user ikona"></div>
                <input type="text" placeholder="Vložte osobné čislo" name="os_cislo" required>
            </div>

            <label for="password" class="stred"><b>Heslo</b></label>
            <div class="input-riadok">
                <div class="fas fa-key ikona"></div>
                <input type="password" placeholder="Vložte heslo" name="password" required>
            </div>

            <label for="heslo-znova" class="stred"><b>Heslo znova</b></label>
            <div class="input-riadok">
                <div class="fas fa-redo-alt ikona"></div>
                <input type="password" placeholder="Vložte heslo znova" name="heslo-znova" required>
            </div>

            <div>
                <button type="submit" class="tlacidlo-registrovat tlacidlo-formular">Registrovať</button>
                <a href="prihlasenie.php"><button type="button" class="tlacidlo-prihlasit tlacidlo-formular">Prejsť na prihlásenie</button></a>
            </div>
        </form>
    </div>
</body>
</html>