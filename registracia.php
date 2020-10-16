<?php
$servername = "localhost";
$username = "root";
$password = "123456789";
$dbName = "db_semestralka_vaii";
$conn = new mysqli($servername, $username, $password, $dbName);

if ($conn->connect_error) {
    die("Pripojenie zlyhalo: " . $conn->connect_error);
}

$sql = "select * from role order by id_rola";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registrácia</title>
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
        <form id="formular-registracia" class="formular-registracia">
            <h1 class="stred">Registrácia</h1>
            <p class="stred">Vyplňte prosím nasledujúce údaje pre vytvorenie účtu</p>
            <label for="os_cislo" class="stred"><b>Osobné číslo</b></label>
            <div class="input-riadok">
                <div class="fas fa-user ikona"></div>
                <input id="os_cislo" type="text" placeholder="Vložte osobné čislo" name="os_cislo" required>
            </div>

            <label for="cele_meno" class="stred"><b>Celé meno</b></label>
            <div class="input-riadok">
                <div class="fas fa-id-card ikona"></div>
                <input id="cele_meno" type="text" placeholder="Vložte celé meno" name="cele_meno" required>
            </div>

            <label for="email" class="stred"><b>Email</b></label>
            <div class="input-riadok">
                <div class="fas fa-envelope ikona"></div>
                <input id="email" type="text" placeholder="Vložte email" name="email" required>
            </div>

            <label for="telefon" class="stred"><b>Telefón</b></label>
            <div class="input-riadok">
                <div class="fas fa-phone ikona"></div>
                <input id="telefon" type="tel" placeholder="Vložte telefónne číslo" name="telefon" required>
            </div>

            <div class="dropdown">
                <select id="role-dropdown" onchange="test()">
                    <option value="0">vybrať</option>
                    <?php
                    while ($row = $result->fetch_array()) {
                        echo '<option id="role_id' . $row["id_rola"] . '" value="' . $row["id_rola"] . '">' . $row["nazov"] . '</option>';
                    }
                    ?>
                </select>
            </div>

            <label for="heslo" class="stred"><b>Heslo</b></label>
            <div class="input-riadok">
                <div class="fas fa-key ikona"></div>
                <input id="heslo" type="password" placeholder="Vložte heslo" name="heslo" required>
            </div>

            <label for="heslo-znova" class="stred"><b>Heslo znova</b></label>
            <div class="input-riadok">
                <div class="fas fa-redo-alt ikona"></div>
                <input id="heslo-znova" type="password" placeholder="Vložte heslo znova" name="heslo-znova" required>
            </div>

            <div id="test">
                <button type="submit" class="tlacidlo-registrovat tlacidlo-formular">Registrovať</button>
                <a href="prihlasenie.php"><button type="button" class="tlacidlo-prihlasit tlacidlo-formular">Prejsť na prihlásenie</button></a>
            </div>
        </form>
    </div>
</body>
</html>