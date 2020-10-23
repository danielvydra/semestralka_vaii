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

function filtrovatPrace()
{
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
        $sql .= " order by $trieditPodla ";
    }
    if ($trieditAko != "0") {
        $sql .= " $trieditAko ";
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

?>