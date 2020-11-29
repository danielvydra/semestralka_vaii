<?php

class Databaza {

    private $conn;
    private static $instance = null;

    public function __construct()
    {
        $configData = getConfigDataForDB();
        $this->conn = new mysqli($configData["servername"], $configData["username"], $configData["password"], $configData["dbName"], $configData["port"]);

        if ($this->conn->connect_error) {
            die("Pripojenie zlyhalo: " . $this->conn->connect_error);
        } else {
            return $this->conn;
        }
    }

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConn() {
        return $this->conn;
    }

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
    $conn = Databaza::getInstance()->getConn();
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $vysledok = $stmt->get_result();

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
    $conn = Databaza::getInstance()->getConn();
    $result_typy_prac = $conn->query($sql);
    return $result_typy_prac;
}

function getTituly() {
    $sql = "select * from tituly order by id_titul;";
    $conn = Databaza::getInstance()->getConn();
    $result_tituly = $conn->query($sql);
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
    $conn = Databaza::getInstance()->getConn();
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result_uzivatelia = $stmt->get_result();

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
    $conn = Databaza::getInstance()->getConn();
    $result_katedry = $conn->query($sql);
    return $result_katedry;
}

function getStavyPrace() {
    $sql = "select id_stav, stav from stavy_prace;";
    $conn = Databaza::getInstance()->getConn();
    $result_stavy_prace = $conn->query($sql);
    return $result_stavy_prace;
}

function getTypyOsob() {
    $sql = "select id_rola, nazov as nazov_role from role;";
    $conn = Databaza::getInstance()->getConn();
    $result_typy_osob = $conn->query($sql);
    return $result_typy_osob;
}

function pridatTemuMedziOblubene($idTema, $idOsoba) {
    $sql = "insert into oblubene_temy(id_tema, id_student) values (?, ?);";
    $conn = Databaza::getInstance()->getConn();
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $idTema, $idOsoba);
    return $stmt->execute();
}

function odobratOblubenuTemu($idTema, $idOsoba) {
    $sql = "delete from oblubene_temy where id_tema = ? and id_student = ?;";
    $conn = Databaza::getInstance()->getConn();
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $idTema, $idOsoba);
    return $stmt->execute();
}

function jeOblubenaTemaVDatabaze($idTema, $idOsoba) {
    $sql = "select * from oblubene_temy where id_tema = ? and id_student = ?;";
    $conn = Databaza::getInstance()->getConn();
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $idTema, $idOsoba);
    $stmt->execute();
    return $stmt->get_result()->num_rows > 0;
}

function getZoznamOblubenychTem($idOsoba) {
    $temy = array();
    $sql = "select id_tema from oblubene_temy where id_student = ?;";
    $conn = Databaza::getInstance()->getConn();
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idOsoba);
    $stmt->execute();
    $vysledok = $stmt->get_result();
    while ($tema = $vysledok->fetch_array()) {
        array_push($temy, $tema["id_tema"]);
    }
    return $temy;
}

function getZoznamMojichPridanychTem($idOsoba) {
    $temy = array();
    $sql = "select id_tema from zaver_prace where id_veduci = ?;";
    $conn = Databaza::getInstance()->getConn();
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idOsoba);
    $stmt->execute();
    $vysledok = $stmt->get_result();
    while ($tema = $vysledok->fetch_array()) {
        array_push($temy, $tema["id_tema"]);
    }
    return $temy;
}

function jeTemaVDatabaze($nazovSK, $nazovEN) {
    $sql = "select * from zaver_prace where nazov_sk like ? or nazov_en like ?;";
    $conn = Databaza::getInstance()->getConn();
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $nazovSK, $nazovEN);
    $stmt->execute();
    return $stmt->get_result()->num_rows > 0;
}

function vymazatTemuZDatabazy($idTema) {
    $sql1 = "delete from oblubene_temy where id_tema = ?;";
    $sql2 = "delete from zaver_prace where id_tema = ?;";
    $conn = Databaza::getInstance()->getConn();
    $stmt1 = $conn->prepare($sql1);
    $stmt2 = $conn->prepare($sql2);
    $stmt1->bind_param("i", $idTema);
    $stmt2->bind_param("i", $idTema);
    $stmt1->execute();
    $stmt2->execute();
}

function pridatNovuTemuDoDB($idVeduci, $typPrace, $nazovPraceSK, $nazovPraceEN, $popisPrace) {
    $sql = "insert into zaver_prace(id_veduci, id_typ, nazov_sk, nazov_en, popis, vytvorenie) values (?,?,?,?,?,now());";
    $conn = Databaza::getInstance()->getConn();
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iisss", $idVeduci, $typPrace, $nazovPraceSK, $nazovPraceEN, $popisPrace);
    $stmt->execute();
}

function upravitOsobneUdajeDB($titulPred, $meno, $titulZa, $email, $telefon, $idOsoba) {
    $sql = "update os_udaje set id_titul_pred = ?, meno = ?, id_titul_za = ?, email = ?, telefon = ?, upravenie = now() where id_osoba = ?";
    $conn = Databaza::getInstance()->getConn();
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isisii", $titulPred, $meno, $titulZa, $email, $telefon, $idOsoba);
    return $stmt->execute();
}

?>