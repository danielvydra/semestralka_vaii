<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Prihlásenie</title>
    <script src="../resources/javascript.js"></script>
    <link rel="stylesheet" href="../resources/dizajn.css">
    <link rel="stylesheet" href="../fontawesome/css/all.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    <div class="nadpis-ramcek">Systém záverečných prác</div>
    <div class="prihl-okno">
        <form class="formular" action="../resources/autentifikacia.php" method="post">
            <h1 class="stred">Prihlásenie</h1>
            <p class="stred">Vyplňte prosím nasledujúce údaje pre prihlásenie</p>
            <label for="os_cislo" class="stred"><b>Osobné číslo</b></label>
            <div class="input-riadok">
                <div class="fas fa-user ikona"></div>
                <input class="login-input" pattern="[0-9]{6,6}" id="os_cislo" type="text" placeholder="Vložte osobné čislo" name="os_cislo" required>
            </div>

            <label for="heslo" class="stred"><b>Heslo</b></label>
            <div class="input-riadok">
                <div class="fas fa-key ikona"></div>
                <input class="login-input" id="heslo" pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*]).{8,16}" type="password" placeholder="Vložte heslo" name="heslo" required>
            </div>

            <div>
                <button type="submit" class="transform-stred tlacidlo-potvrdit tlacidlo-formular">Prihlásiť</button>
            </div>
        </form>
    </div>
</body>
</html>