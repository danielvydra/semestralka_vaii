<?php

include_once './databaza.php';
session_start();

if (!isset($_POST['os_cislo'], $_POST['heslo'])) {
    exit('Nevyplnili ste polia');
}
if ($stmt = $GLOBALS['conn']->prepare('select id_osoba, os_cislo, password_hash, email, meno, r.nazov as rola from os_udaje
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
                header('Location: ../stranky/zoznam_prac.php');
                exit;
            }
        } else {
            echo 'Zadané nesprávne údaje';
        }
    } else {
        echo 'Používateľ neexistuje';
    }
    $stmt->close();
}

?>
