<?php
include_once "./databaza.php";
include_once "./metody.php";
session_start();

if (isset($_POST['nazovFunkcie']) && !empty($_POST['nazovFunkcie'])) {
    $nazovFunkcie = $_POST['nazovFunkcie'];
    switch ($nazovFunkcie) {
        case 'pridatMedziOblubene' :
            pridatMedziOblubene();
            break;
        case 'filtrovatPrace' :
            filtrovatPrace();
            break;
        case 'zobrazitOblubenePrace' :
            zobrazitOblubenePrace();
            break;
        case 'pridatNovuTemu' :
            pridatNovuTemu();
            break;
        case 'odobratPracuZoZoznamuPrac' :
            odobratPracuZoZoznamuPrac();
            break;
        case 'odobratTemuZPridavaniaTem' :
            odobratTemuZPridavaniaTem();
            break;
        case 'filtrovatUzivatelov' :
            filtrovatUzivatelov();
            break;
        case 'upravitOsobneUdaje' :
            upravitOsobneUdaje();
            break;
        default:
            echo "chyba";
            break;
    }
}

function pridatMedziOblubene($echoResult = true)
{
    $idOsoba = $_SESSION["id_osoba"];
    $idTema = $_POST["id_tema"];
    if (jeOblubenaTemaVDatabaze($idTema, $idOsoba)) {
        odobratOblubenuTemu($idTema, $idOsoba);
        $feedback = "odobrana";
    } else {
        pridatTemuMedziOblubene($idTema, $idOsoba);
        $feedback = "pridana";
    }
    if ($echoResult)
        echo $feedback;
}

function filtrovatPrace() {
    parse_str($_POST["formular"], $formular);
    $nazovPrace = $formular["nazov-prace"];
    $menoVeduceho = $formular["meno-veduceho"];
    $menoStudenta = $formular["meno-studenta"];
    $typPrace = $formular["typ-prace"];
    $katedra = $formular["katedra"];
    $stavPrace = $formular["stav-prace"];
    $trieditPodla = $formular["triedit-podla"];
    $trieditAko = $formular["triedit-ako"];
    $counter = 0;

    $sql = "select zp.nazov_sk, zp.nazov_en, uc.meno as meno_veduceho, st.meno as meno_studenta, u.id_katedra, zp.id_stav, zp.id_typ, id_student, id_veduci, id_tema, popis from zaver_prace zp
        join ucitelia u on u.id_osoba = zp.id_veduci
        join stavy_prace sp on zp.id_stav = sp.id_stav
        left join os_udaje st on st.id_osoba = zp.id_student
        join os_udaje uc on uc.id_osoba = u.id_osoba";
    if (!empty($nazovPrace) or $katedra > 0 or $stavPrace > 0 or $typPrace > 0 or !empty($menoVeduceho) or !empty($menoStudenta)) {
        $sql .= " where ";
        if (!empty($nazovPrace)) {
            $sql .= " (nazov_sk like '%$nazovPrace%' or nazov_en like '%$nazovPrace%') ";
            $counter++;
        }
        if ($katedra > 0) {
            if ($counter > 0) $sql .= " and ";
            $sql .= " u.id_katedra = $katedra ";
            $counter++;
        }
        if ($stavPrace > 0) {
            if ($counter > 0) $sql .= " and ";
            $sql .= " sp.id_stav = $stavPrace ";
            $counter++;
        }
        if ($typPrace > 0) {
            if ($counter > 0) $sql .= " and ";
            $sql .= " zp.id_typ = $typPrace ";
            $counter++;
        }
        if (!empty($menoVeduceho)) {
            if ($counter > 0) $sql .= " and ";
            $sql .= " uc.meno like '%$menoVeduceho%' ";
            $counter++;
        }
        if (!empty($menoStudenta)) {
            if ($counter > 0) $sql .= " and ";
            $sql .= " st.meno like '%$menoStudenta%' ";
        }
    }
    if ($trieditPodla != "0") {
        $sql .= " order by $trieditPodla $trieditAko";
    }
    $sql .= ";";

    $prace = $GLOBALS['conn']->query($sql);
    if ($prace != null && mysqli_num_rows($prace) > 0) {
        if ($_SESSION["rola"] == "student") {
            $zoznamPrac = getZoznamOblubenychTem($_SESSION["id_osoba"]);
            $onclick = "pridatOblubenuTemu(this)";
        } elseif ($_SESSION["rola"] == "ucitel") {
            $zoznamPrac = getZoznamMojichPridanychTem($_SESSION["id_osoba"]);
            $onclick = "odobratPracuZoZoznamuPrac(this)";
        }
        echo '<div id="zoznam-prac" class="kontajner-zoznam-tem transform-stred">';
        vypisPrac($prace, $zoznamPrac, $onclick);
        echo '</div>';
    } else {
        echo '<div id="zoznam-prac" class="kontajner-zoznam-tem transform-stred">';
        echo '<div class="zaver-praca">';
        echo '<div class="stred">Žiadna práca nespĺňa zadaný filer.</div>';
        echo '</div>';
        echo '</div>';
    }
}

function filtrovatUzivatelov() {
    parse_str($_POST["formular"], $formular);
    $menoOsoby = $formular["meno-osoby"];
    $typOsoby = intval($formular["typ-osoby"]);
    $trieditPodla = $formular["triedit-podla"];
    $trieditAko = $formular["triedit-ako"];
    $counter = 0;

    $sql = "select ou.id_osoba, t.nazov as titul_pred, t2.nazov as titul_za, ou.meno, ou.email, ou.os_cislo, ou.telefon, ou.vytvorenie, ou.upravenie, r.nazov as nazov_role from os_udaje ou
        join tituly t on t.id_titul = ou.id_titul_pred
        join role r on r.id_rola = ou.id_rola
        join tituly t2 on t2.id_titul = ou.id_titul_za";

    if (!empty($menoOsoby) or !empty($typOsoby)) {
        $sql .= " where ";
    }
    if (!empty($menoOsoby)) {
        $counter++;
        $sql .= " meno like '%$menoOsoby%' ";
    }
    if (!empty($typOsoby)) {
        if ($counter > 0) $sql .= " and ";
        $sql .= " ou.id_rola = $typOsoby ";
    }
    if (!empty($trieditPodla)) {
       $sql .= " order by $trieditPodla $trieditAko; ";
    }

    $uzivatelia = $GLOBALS['conn']->query($sql);
    if ($uzivatelia != null && mysqli_num_rows($uzivatelia) > 0) {
        echo '<div id="zoznam-uzivatelov" class="kontajner-zoznam-tem transform-stred">';
        while ($pouzivatel = $uzivatelia->fetch_array()) {
            if ($pouzivatel["nazov_role"] == "ucitel") {
                $osoba = getVeduci($pouzivatel["id_osoba"]);
                $viac_info = '<div><b>Miestnosť: </b> ' . $osoba["miestnost"] . '</div>';
                $viac_info .= '<div><b>Fakulta: </b> ' . $osoba["fakulta"] . '</div>';
                $viac_info .= '<div><b>Katedra: </b> ' . $osoba["katedra"] . '</div>';
                if ($osoba["volna_kapacita"]) {
                    $viac_info .= '<div><b>Prijíma témy: </b>áno<div class="fa fa-check info-ikona ok"></div></div>';
                } else {
                    $viac_info .= '<div><b>Prijíma témy: </b>nie<div class="fa fa-times info-ikona not-ok"></div></div>';
                }
            } else if ($pouzivatel["nazov_role"] == "student") {
                $osoba = getStudent($pouzivatel["id_osoba"]);
                $viac_info = '<div><b>Študijná skupina: </b> ' . $osoba["skupina"] . '</div>';
                $viac_info .= '<div><b>Odbor: </b> ' . $osoba["odbor"] . '</div>';
                $viac_info .= '<div><b>Fakulta: </b> ' . $osoba["fakulta"] . '</div>';
            }

            echo '<div id="' . $pouzivatel["os_cislo"] . '" class="zaver-praca pouzivatelia">';
            echo '<div onclick="zobrazViacInfo(this)" class="nazov-prace"><b>' . $pouzivatel["titul_pred"] . " " . $pouzivatel["meno"] . " " . $pouzivatel["titul_za"] . '</b></div>';
            echo '<div style="display: none;">';
            echo '<hr class="oddelovac">';
            echo '<div><b>Email: </b> ' . $pouzivatel["email"] . '</div>';
            echo '<div><b>Osobné číslo: </b>' . $pouzivatel["os_cislo"] . '</div>';
            echo '<div><b>Telefón: </b>' . "0" . $pouzivatel["telefon"] . '</div>';
            echo $viac_info;
            echo '</div>';
            echo '</div>';
        }
        echo '</div>';
    } else {
        echo '<div id="zoznam-uzivatelov" class="kontajner-zoznam-tem transform-stred">';
        echo '<div class="zaver-praca pouzivatelia stred">Žiaden používateľ nespĺňa zadaný filer.</div>';
        echo '</div>';
    }

}

function zobrazitOblubenePrace()
{
    pridatMedziOblubene(false);

    echo '<div id="zoznam-prac" class="kontajner-zoznam-tem transform-stred">';
    $prace = getVsetkyMojeOblubeneTemy($_SESSION["id_osoba"]);
    $oblubenePrace = array();
    if ($_SESSION["rola"] == "student") {
        $oblubenePrace = getZoznamOblubenychTem($_SESSION["id_osoba"]);
    }

    if ($prace != null && mysqli_num_rows($prace) > 0) {
        vypisPrac($prace, $oblubenePrace, "zobrazitOblubenePrace(this)");
    } else {
        echo '<div class="zaver-praca">';
        echo '<div class="stred">Neboli nájdené žiadne obľúbené práce.</div>';
        echo '</div>';
    }
    echo '</div>';
}

function pridatNovuTemu() {
    parse_str($_POST["formular"], $formular);
    $nazovPraceSK = $formular["nazov-prace"];
    $nazovPraceEN = $formular["ang-nazov-prace"];
    $popisPrace = $formular["popis-prace"];
    $typPrace = $formular["typ-prace"];
    $idVeduci = $_SESSION["id_osoba"];

    if (jeTemaVDatabaze($nazovPraceSK, $nazovPraceEN)) {
        echo "chyba";
    } else {
        $sql = "insert into zaver_prace(id_veduci, id_typ, nazov_sk, nazov_en, popis, vytvorenie)
        values ($idVeduci, $typPrace, '$nazovPraceSK', '$nazovPraceEN', '$popisPrace', now());";
        $GLOBALS["conn"]->query($sql);

        $prace = getMojePridanePrace($_SESSION["id_osoba"]);
        $zoznamPrac = getZoznamMojichPridanychTem($_SESSION["id_osoba"]);
        if ($prace != null && mysqli_num_rows($prace) > 0) {
            echo '<div id="zoznam-prac" class="kontajner-zoznam-tem transform-stred">';
            vypisPrac($prace, $zoznamPrac, "odobratTemuZPridavaniaTem(this)");
            echo '</div>';
        } else {
            echo '<div id="zoznam-prac" class="kontajner-zoznam-tem transform-stred">';
            echo '<div class="zaver-praca">';
            echo '<div class="stred">Neboli nájdené žiadne pridané práce.</div>';
            echo '</div>';
            echo '</div>';
        }
    }

}

function odobratPracuZoZoznamuPrac() {
    vymazatTemuZDatabazy($_POST["id_tema"]);

    $prace = getZaverecnePrace();
    if ($prace != null && mysqli_num_rows($prace) > 0) {
        if ($_SESSION["rola"] == "student") {
            $zoznamPrac = getZoznamOblubenychTem($_SESSION["id_osoba"]);
            $onclick = "pridatOblubenuTemu(this)";
        } elseif ($_SESSION["rola"] == "ucitel") {
            $zoznamPrac = getZoznamMojichPridanychTem($_SESSION["id_osoba"]);
            $onclick = "odobratPracuZoZoznamuPrac(this)";
        }
        vypisPrac($prace, $zoznamPrac, $onclick);
    } else {
        echo '<div class="zaver-praca">';
        echo '<div class="stred">Neboli nájdené žiadne práce.</div>';
        echo '</div>';
    }
}

function odobratTemuZPridavaniaTem() {
    vymazatTemuZDatabazy($_POST["id_tema"]);

    $prace = getMojePridanePrace($_SESSION["id_osoba"]);
    $zoznamPrac = getZoznamMojichPridanychTem($_SESSION["id_osoba"]);
    if ($prace != null && mysqli_num_rows($prace) > 0) {
        echo '<div id="zoznam-prac" class="kontajner-zoznam-tem transform-stred">';
        vypisPrac($prace, $zoznamPrac, "odobratTemuZPridavaniaTem(this)");
        echo '</div>';
    } else {
        echo '<div id="zoznam-prac" class="kontajner-zoznam-tem transform-stred">';
        echo '<div class="zaver-praca">';
        echo '<div class="stred">Neboli nájdené žiadne pridané práce.</div>';
        echo '</div>';
        echo '</div>';
    }
}

function upravitOsobneUdaje() {
    parse_str($_POST["formular"], $formular);
    $meno = $formular["meno"];
    $titulZa = $formular["titul-za"];
    $titulPred = $formular["titul-pred"];
    $email = $formular["email"];
    $telefon = $formular["telefon"];
    $idOsoba = $_SESSION["id_osoba"];

    $sql = "update os_udaje set id_titul_pred = $titulPred, meno = '$meno', id_titul_za = $titulZa, email = '$email', telefon = $telefon, 
        upravenie = now() where id_osoba = $idOsoba;";
    if($GLOBALS["conn"]->query($sql)) {
        echo "ok";
    } else {
        echo "error";
    }
}

?>