<?php
include_once "../resources/dependencies.php";
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
    $idTema = intval($_POST["id_tema"]);
    if (jeOblubenaTemaVDatabaze($idTema, $idOsoba)) {
        if (odobratOblubenuTemu($idTema, $idOsoba)) {
            $feedback = "odobrana";
        } else {
            $feedback = 1;
        }
    } else {
        if (pridatTemuMedziOblubene($idTema, $idOsoba)) {
            $feedback = "pridana";
        } else {
            $feedback = 1;
        }
    }
    if ($echoResult)
        echo $feedback;
    else
        return $feedback;
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
    $where = "";

    if (!empty($nazovPrace) or $katedra > 0 or $stavPrace > 0 or $typPrace > 0 or !empty($menoVeduceho) or !empty($menoStudenta)) {
        $where .= " where ";
        if (!empty($nazovPrace)) {
            $where .= " (nazov_sk like '%$nazovPrace%' or nazov_en like '%$nazovPrace%') ";
            $counter++;
        }
        if ($katedra > 0) {
            if ($counter > 0) $where .= " and ";
            $where .= " u.id_katedra = $katedra ";
            $counter++;
        }
        if ($stavPrace > 0) {
            if ($counter > 0) $where .= " and ";
            $where .= " sp.id_stav = $stavPrace ";
            $counter++;
        }
        if ($typPrace > 0) {
            if ($counter > 0) $where .= " and ";
            $where .= " zp.id_typ = $typPrace ";
            $counter++;
        }
        if (!empty($menoVeduceho)) {
            if ($counter > 0) $where .= " and ";
            $where .= " uc.meno like '%$menoVeduceho%' ";
            $counter++;
        }
        if (!empty($menoStudenta)) {
            if ($counter > 0) $where .= " and ";
            $where .= " st.meno like '%$menoStudenta%' ";
        }
    }
    if (!empty($trieditPodla)) {
        $where .= " order by $trieditPodla $trieditAko ;";
    }

    vypisFiltrovanePrace($where);
}

function filtrovatUzivatelov() {
    parse_str($_POST["formular"], $formular);
    $menoOsoby = $formular["meno-osoby"];
    $typOsoby = intval($formular["typ-osoby"]);
    $trieditPodla = $formular["triedit-podla"];
    $trieditAko = $formular["triedit-ako"];
    $counter = 0;
    $sql = "";

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

    $pouzivatelia = getPouzivatelov($sql);
    if (isset($pouzivatelia) and !empty($pouzivatelia)) {
        vypisPouzivatelov($pouzivatelia);
    } else {
        vypisNenajdenyPouzivatel();
    }

}

function zobrazitOblubenePrace()
{
    if (pridatMedziOblubene(false) != 1) {
        vypisOblubenychPrac();
    } else {
        echo 1;
    }
}

function pridatNovuTemu() {
    parse_str($_POST["formular"], $formular);
    $nazovPraceSK = $formular["nazov-prace"];
    $nazovPraceEN = $formular["ang-nazov-prace"];
    $popisPrace = $formular["popis-prace"];
    $typPrace = $formular["typ-prace"];
    $idVeduci = $_SESSION["id_osoba"];

    $validator = validujPridanieNovejTemy($nazovPraceSK, $nazovPraceEN, $popisPrace, $typPrace);

    if (empty($validator)) {
        if (jeTemaVDatabaze($nazovPraceSK, $nazovPraceEN)) {
            echo 1;
        } else {
            pridatNovuTemuDoDB($idVeduci, $typPrace, $nazovPraceSK, $nazovPraceEN, $popisPrace);
            vypisMojichVytvorenychPrac();
        }
    } else {
        vypisChyby($validator);
    }
}

function odobratPracuZoZoznamuPrac() {
    vymazatTemuZDatabazy($_POST["id_tema"]);
    vypisZaverecnePrace();
}

function odobratTemuZPridavaniaTem() {
    vymazatTemuZDatabazy($_POST["id_tema"]);
    vypisMojichVytvorenychPrac();
}

function upravitOsobneUdaje() {
    parse_str($_POST["formular"], $formular);
    $meno = $formular["meno"];
    $titulZa = $formular["titul-za"];
    $titulPred = $formular["titul-pred"];
    $email = $formular["email"];
    $telefon = $formular["telefon"];
    $idOsoba = $_SESSION["id_osoba"];

    $validator = validujOsobneUdaje($meno, $titulZa, $titulPred, $email, $telefon);

    if (empty($validator)) {
        if (upravitOsobneUdajeDB($titulPred, $meno, $titulZa, $email, $telefon, $idOsoba)) {
            vypisOsobnychUdajov();
        } else {
            echo 1;
        }
    } else {
        vypisChyby($validator);
    }

}

?>