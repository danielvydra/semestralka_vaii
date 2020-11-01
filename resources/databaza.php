<?php

$servername = "localhost";
$username = "root";
$password = "123456789";
$dbName = "db_semestralka_vaii";
$conn =  new mysqli($servername, $username, $password, $dbName, 3306);

if ($GLOBALS['conn']->connect_error) {
    die("Pripojenie zlyhalo: " . $GLOBALS['conn']->connect_error);
}

function getZaverecnePrace($where = "") {
    $sql = "select zp.nazov_sk, zp.nazov_en, uc_t_pred.nazov as uc_t_pred, uc.meno as meno_veduceho, uc_t_za.nazov as uc_t_za, st_t_pred.nazov as st_t_pred,
       st.meno as meno_studenta, st_t_za.nazov as st_t_za, u.id_katedra, zp.id_stav, zp.id_typ,
       id_student, id_veduci, id_tema, popis, tp.nazov as nazov_typu_temy from zaver_prace zp
        join ucitelia u on u.id_osoba = zp.id_veduci
        join stavy_prace sp on zp.id_stav = sp.id_stav
        left join os_udaje st on st.id_osoba = zp.id_student
        join os_udaje uc on uc.id_osoba = u.id_osoba
        left join tituly st_t_pred on st.id_titul_pred = st_t_pred.id_titul
        left join tituly st_t_za on st.id_titul_za = st_t_za.id_titul
        join tituly uc_t_pred on uc_t_pred.id_titul = uc.id_titul_pred
        join tituly uc_t_za on uc_t_za.id_titul = uc.id_titul_za
        join typy_prac tp on zp.id_typ = tp.id_typ $where;";
    $vysledok = $GLOBALS['conn']->query($sql);

    $zaverecnePrace = array();
    while ($tema = $vysledok->fetch_array()) {
        $zaverPraca = new ZaverecnaPraca($tema["id_tema"], $tema["nazov_sk"], $tema["nazov_en"], $tema["popis"], $tema["nazov_typu_temy"],
        $tema["uc_t_pred"] . " " . $tema["meno_veduceho"] . " " . $tema["uc_t_za"],
            $tema["st_t_pred"] . " " . $tema["meno_studenta"] . " " . $tema["st_t_za"]);

        array_push($zaverecnePrace, $zaverPraca);
    }
    return $zaverecnePrace;
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

function getPouzivatelov($where = "") {
    $sql = "select ou.id_osoba, t.nazov as titul_pred, t2.nazov as titul_za, ou.meno, ou.email, ou.os_cislo, ou.telefon, ou.vytvorenie,
       ou.upravenie, r.nazov as nazov_role, f.nazov as s_nazov_fakulty, o.nazov as nazov_odboru, sk.nazov as skupina, u.miestnost, u.volna_kapacita,
       k.nazov as nazov_katedry, f2.nazov as u_nazov_fakulty from os_udaje ou
        join tituly t on t.id_titul = ou.id_titul_pred
        join role r on r.id_rola = ou.id_rola
        join tituly t2 on t2.id_titul = ou.id_titul_za
        left join ucitelia u on ou.id_osoba = u.id_osoba
        left join studenti s on ou.id_osoba = s.id_osoba
        left join odbory o on o.id_odbor = s.id_odbor
        left join fakulty f on f.id_fakulta = o.id_fakulta
        left join skupiny sk on sk.id_skupina = s.id_skupina
        left join katedry k on k.id_katedra = u.id_katedra
        left join fakulty f2 on f2.id_fakulta = k.id_fakulta $where;";
    $result_uzivatelia = $GLOBALS['conn']->query($sql);

    $pouzivatelia = array();
    while ($pouzivatel = $result_uzivatelia->fetch_array()) {
        if ($pouzivatel["nazov_role"] == "student") {
            $uzivatel = new Student($pouzivatel["id_osoba"], $pouzivatel["titul_pred"], $pouzivatel["meno"], $pouzivatel["titul_za"],
                $pouzivatel["email"], $pouzivatel["os_cislo"], "0" . $pouzivatel["telefon"],
                $pouzivatel["vytvorenie"], $pouzivatel["upravenie"], $pouzivatel["nazov_role"], $pouzivatel["skupina"],
                $pouzivatel["s_nazov_fakulty"], $pouzivatel["nazov_odboru"]);
        } elseif ($pouzivatel["nazov_role"] == "ucitel") {
            $uzivatel = new Ucitel($pouzivatel["id_osoba"], $pouzivatel["titul_pred"], $pouzivatel["meno"], $pouzivatel["titul_za"],
                $pouzivatel["email"], $pouzivatel["os_cislo"], "0" . $pouzivatel["telefon"],
                $pouzivatel["vytvorenie"], $pouzivatel["upravenie"], $pouzivatel["nazov_role"], $pouzivatel["u_nazov_fakulty"],
                $pouzivatel["miestnost"], $pouzivatel["nazov_katedry"], $pouzivatel["volna_kapacita"]);
        }
        array_push($pouzivatelia, $uzivatel);
    }
    return $pouzivatelia;
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

function pridatNovuTemuDoDB($idVeduci, $typPrace, $nazovPraceSK, $nazovPraceEN, $popisPrace) {
    $sql = "insert into zaver_prace(id_veduci, id_typ, nazov_sk, nazov_en, popis, vytvorenie)
        values ($idVeduci, $typPrace, '$nazovPraceSK', '$nazovPraceEN', '$popisPrace', now());";
    $GLOBALS["conn"]->query($sql);
}

function upravitOsobneUdajeDB($titulPred, $meno, $titulZa, $email, $telefon, $idOsoba) {
    $sql = "update os_udaje set id_titul_pred = $titulPred, meno = '$meno', id_titul_za = $titulZa, email = '$email', telefon = $telefon, 
        upravenie = now() where id_osoba = $idOsoba;";
    return $GLOBALS["conn"]->query($sql);
}

?>