<?php

$servername = "localhost";
$username = "root";
$password = "123456789";
$dbName = "db_semestralka_vaii";
$conn =  new mysqli($servername, $username, $password, $dbName, 3306);

if ($GLOBALS['conn']->connect_error) {
    die("Pripojenie zlyhalo: " . $GLOBALS['conn']->connect_error);
}

function getZaverecnePrace() {
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
        $sql = "select t.nazov as titul_pred, t2.nazov as titul_za, ou.meno, ou.email, ou.os_cislo, ou.telefon, k.nazov as katedra, ou.vytvorenie, ou.upravenie, u.miestnost, u.volna_kapacita, f.nazov as fakulta from os_udaje ou
        join ucitelia u on ou.id_osoba = u.id_osoba
        join tituly t on t.id_titul = ou.id_titul_pred
        join katedry k on k.id_katedra = u.id_katedra
        join fakulty f on f.id_fakulta = k.id_fakulta
        join tituly t2 on t2.id_titul = ou.id_titul_za
        where ou.id_osoba = ". $id_veduci ." ;";
        $result_veduci = $GLOBALS['conn']->query($sql);
        $veduci = $result_veduci->fetch_array();
        return $veduci;
    }
}

function getTypyPrac() {
    $sql = "select * from typy_prac;";
    $result_typy_prac = $GLOBALS['conn']->query($sql);
    return $result_typy_prac;
}

function getTituly() {
    $sql = "select * from tituly order by id_titul;";
    $result_tituly = $GLOBALS['conn']->query($sql);
    return $result_tituly;
}

function getPouzivatelov() {
    $sql = "select ou.id_osoba, t.nazov as titul_pred, t2.nazov as titul_za, ou.meno, ou.email, ou.os_cislo, ou.telefon, ou.vytvorenie, ou.upravenie, r.nazov as nazov_role from os_udaje ou
    join tituly t on t.id_titul = ou.id_titul_pred
    join role r on r.id_rola = ou.id_rola
    join tituly t2 on t2.id_titul = ou.id_titul_za;";
    $result_uzivatelia = $GLOBALS['conn']->query($sql);
    return $result_uzivatelia;
}

function getKatedry() {
    $sql = "select id_katedra, nazov from katedry;";
    $result_katedry = $GLOBALS['conn']->query($sql);
    return $result_katedry;
}

function getStavyPrace() {
    $sql = "select id_stav, stav from stavy_prace;";
    $result_stavy_prace = $GLOBALS['conn']->query($sql);
    return $result_stavy_prace;
}

function getTypyOsob() {
    $sql = "select id_rola, nazov as nazov_role from role;";
    $result_typy_osob = $GLOBALS['conn']->query($sql);
    return $result_typy_osob;
}

function pridatTemuMedziOblubene($idTema, $idOsoba) {
    $sql = "insert into oblubene_temy(id_tema, id_student) values ($idTema, $idOsoba);";
    $GLOBALS['conn']->query($sql);
}

function odobratOblubenuTemu($idTema, $idOsoba) {
    $sql = "delete from oblubene_temy where id_tema = $idTema and id_student = $idOsoba;";
    $GLOBALS['conn']->query($sql);
}

function jeOblubenaTemaVDatabaze($idTema, $idOsoba) {
    $sql = "select * from oblubene_temy where id_tema = $idTema and id_student = $idOsoba;";
    $vysledok = $GLOBALS['conn']->query($sql);
    $pocet = mysqli_num_rows($vysledok);
    return $pocet > 0;
}

function getZoznamOblubenychTem($idOsoba) {
    $temy = array();
    $sql = "select id_tema from oblubene_temy where id_student = $idOsoba;";
    $vysledok = $GLOBALS['conn']->query($sql);
    while ($tema = $vysledok->fetch_array()) {
        array_push($temy, $tema["id_tema"]);
    }
    return $temy;
}

function getVsetkyMojeOblubeneTemy($idOsoba) {
    $sql = "select zp.nazov_sk, zp.nazov_en, uc.meno as meno_veduceho, st.meno as meno_studenta, u.id_katedra, zp.id_stav, zp.id_typ, id_student, id_veduci, id_tema, popis from zaver_prace zp join ucitelia u on u.id_osoba = zp.id_veduci join stavy_prace sp on zp.id_stav = sp.id_stav
    left join os_udaje st on st.id_osoba = zp.id_student
    join os_udaje uc on uc.id_osoba = u.id_osoba where id_tema in (select id_tema from oblubene_temy where id_student = $idOsoba);";
    $vysledok = $GLOBALS['conn']->query($sql);
    return $vysledok;
}

function getMojePridanePrace($idOsoba) {
    $sql = "select zp.nazov_sk, zp.nazov_en, uc.meno as meno_veduceho, st.meno as meno_studenta, u.id_katedra, zp.id_stav, zp.id_typ, id_student, id_veduci, id_tema, popis from zaver_prace zp
        join ucitelia u on u.id_osoba = zp.id_veduci
        join stavy_prace sp on zp.id_stav = sp.id_stav
        left join os_udaje st on st.id_osoba = zp.id_student
        join os_udaje uc on uc.id_osoba = u.id_osoba
        where zp.id_veduci = $idOsoba;";
    $vysledok = $GLOBALS['conn']->query($sql);
    return $vysledok;
}

function getZoznamMojichPridanychTem($idOsoba) {
    $temy = array();
    $sql = "select id_tema from zaver_prace where id_veduci = $idOsoba;";
    $vysledok = $GLOBALS['conn']->query($sql);
    while ($tema = $vysledok->fetch_array()) {
        array_push($temy, $tema["id_tema"]);
    }
    return $temy;
}

function jeTemaVDatabaze($nazovSK, $nazovEN) {
    $sql = "select * from zaver_prace where nazov_sk like '$nazovSK' or nazov_en like '$nazovEN';";
    $vysledok = $GLOBALS['conn']->query($sql);
    $pocet = mysqli_num_rows($vysledok);
    return $pocet > 0;
}

function vymazatTemuZDatabazy($idTema) {
    $sql1 = "delete from oblubene_temy where id_tema = $idTema;";
    $sql2 = "delete from zaver_prace where id_tema = $idTema;";
    $vysledok1 = $GLOBALS['conn']->query($sql1);
    $vysledok2 = $GLOBALS['conn']->query($sql2);
}

?>