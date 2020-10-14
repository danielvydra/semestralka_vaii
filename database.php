<?php

$servername = "localhost";
$username = "root";
$password = "123456789";
$dbName = "db_semestralka_vaii";
$conn = new mysqli($servername, $username, $password, $dbName);

function getZaverecnePrace() {
    if ($GLOBALS['conn']->connect_error) {
        die("Pripojenie zlyhalo: " . $GLOBALS['conn']->connect_error);
    }

    $sql = "select * from zaver_prace;";
    $vysledok = $GLOBALS['conn']->query($sql);
    return $vysledok;
}

function getNazovPrace($id_typ_prace) {
    if ($id_typ_prace != null) {
        $sql = "select nazov from typy_prac where id_typ = ". $id_typ_prace .";";
        $result_typ_prace = $GLOBALS['conn']->query($sql);
        $riadok = $result_typ_prace->fetch_array();
        $typ_prace = $riadok["nazov"];
        return $typ_prace;
    }
}

function getStudent($id_student) {
    if ($id_student != null) {
        $sql = "select t.nazov as titul_pred, t2.nazov as titul_za, ou.meno, ou.email, ou.os_cislo, ou.telefon, ou.vytvorenie, ou.upravenie, s2.nazov as skupina, o.nazov as odbor, f.nazov as fakulta from os_udaje ou
        join studenti s on ou.id_osoba = s.id_osoba
        join tituly t on t.id_titul = ou.id_titul_pred
        join tituly t2 on t2.id_titul = ou.id_titul_za
        join odbory o on o.id_odbor = s.id_odbor
        join fakulty f on f.id_fakulta = o.id_fakulta
        join skupiny s2 on s2.id_skupina = s.id_skupina
        where ou.id_osoba = ". $id_student ." ;";
        $result_student = $GLOBALS['conn']->query($sql);
        $student = $result_student->fetch_array();
        return $student;
    }
}

function getVeduci($id_veduci) {
    if ($id_veduci != null) {
        $sql = "select t.nazov as titul_pred, t2.nazov as titul_za, ou.meno, ou.email, ou.os_cislo, ou.telefon, k.nazov as katedra, ou.vytvorenie, ou.upravenie from os_udaje ou
        join ucitelia u on ou.id_osoba = u.id_osoba
        join tituly t on t.id_titul = ou.id_titul_pred
        join tituly t2 on t2.id_titul = ou.id_titul_za
        join katedry k on k.id_katedra = u.id_katedra
        where ou.id_osoba = ". $id_veduci ." ;";
        $result_veduci = $GLOBALS['conn']->query($sql);
        $veduci = $result_veduci->fetch_array();
        return $veduci;
    }
}

?>