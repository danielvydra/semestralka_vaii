<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Prihlásenie</title>
    <script src="javascript.js"></script>
    <link rel="stylesheet" href="dizajn.css">
    <link rel="stylesheet" href="fontawesome/css/all.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    <div class="nadpis-ramcek">Systém záverečných prác</div>
    <div id="kontent" class="kontent">
        <form class="formular-registracia" action="autentifikacia.php" method="post">
            <h1 class="stred">Prihlásenie</h1>
            <p class="stred">Vyplňte prosím nasledujúce údaje pre prihlásenie</p>
            <label for="os_cislo" class="stred"><b>Osobné číslo</b></label>
            <div class="input-riadok">
                <div class="fas fa-user ikona"></div>
                <input id="os_cislo" type="text" placeholder="Vložte osobné čislo" name="os_cislo" required>
            </div>

            <label for="heslo" class="stred"><b>Heslo</b></label>
            <div class="input-riadok">
                <div class="fas fa-key ikona"></div>
                <input id="heslo" type="password" placeholder="Vložte heslo" name="heslo" required>
            </div>

            <div>
                <a href="registracia.php"><button type="button" class="tlacidlo-registrovat tlacidlo-formular">Prejsť na registrovanie</button></a>
                <button type="submit" class="tlacidlo-prihlasit tlacidlo-formular">Prihlásiť</button>
            </div>
        </form>
    </div>
</body>
</html>