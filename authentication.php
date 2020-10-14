<?php

include 'database.php';

session_start();
if (!isset($_POST['os_cislo'], $_POST['heslo'])) {
    exit('Nevyplnili ste polia');
}
if ($stmt = $GLOBALS['conn']->prepare('select os_cislo, password_hash, email, meno from os_udaje where os_cislo like ?;')) {
    $stmt->bind_param('s', $_POST['os_cislo']);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $os_cislo = "";
        $password_hash = "";
        $email = "";
        $meno = "";
        $stmt->bind_result($os_cislo, $password_hash, $email, $meno);
        $stmt->fetch();
        if (password_verify($_POST['heslo'], $password_hash)) {
            session_regenerate_id();
            $_SESSION['prihlaseny'] = TRUE;
            $_SESSION['os_cislo'] = $_POST['os_cislo'];
            $_SESSION['email'] = $email;
            $_SESSION['meno'] = $meno;

            if (isset($_SESSION['prihlaseny'])) {
                header('Location: zoznam_prac.php');
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
