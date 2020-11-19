<?php

include_once "../resources/dependencies.php";
session_start();

if (!isset($_POST['os_cislo'], $_POST['heslo'])) {
    echo 'Chyba - Nesprávne vyplnené polia';
}

$conn = Databaza::getInstance()->getConn();
if ($stmt = $conn->prepare('select id_osoba, os_cislo, password_hash, email, meno, r.nazov as rola from os_udaje
    join role r on r.id_rola = os_udaje.id_rola where os_cislo like ?;')) {
    $stmt->bind_param('s', $_POST['os_cislo']);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $os_cislo = $password_hash = $email = $meno = $id_osoba = $rola = "";
        $stmt->bind_result($id_osoba, $os_cislo, $password_hash, $email, $meno, $rola);
        $stmt->fetch();

        if (password_verify($_POST['heslo'], $password_hash)) {
            session_regenerate_id();
            $_SESSION['prihlaseny'] = TRUE;
            $_SESSION['os_cislo'] = $_POST['os_cislo'];
            $_SESSION['email'] = $email;
            $_SESSION['meno'] = $meno;
            $_SESSION['id_osoba'] = $id_osoba;
            $_SESSION['rola'] = $rola;

            if (isset($_SESSION['prihlaseny'])) {
                echo "login";
            }
        } else {
            echo 'Chyba - Zadané heslo je nesprávne';
        }
    } else {
        echo 'Chyba - Používateľ neexistuje';
    }
    $stmt->close();
}

?>
