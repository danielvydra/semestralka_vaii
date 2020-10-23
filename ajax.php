<?php
include "databaza.php";
session_start();

if(isset($_POST['nazovFunkcie']) && !empty($_POST['nazovFunkcie'])) {
    $nazovFunkcie = $_POST['nazovFunkcie'];
    switch($nazovFunkcie) {
        case 'pridatMedziOblubene' : pridatMedziOblubene(); break;
        case 'filtrovatPrace' : filtrovatPrace(); break;
        default: echo "chyba"; break;
    }
}

function pridatMedziOblubene() {
    $idOsoba = $_SESSION["id_osoba"];
    $idTema = $_POST["id_tema"];
    $feedback = "";
    if (jeOblubenaTemaVDatabaze($idTema, $idOsoba)) {
        odobratOblubenuTemu($idTema, $idOsoba);
        $feedback = "odobrana";
    } else {
        pridatTemuMedziOblubene($idTema, $idOsoba);
        $feedback = "pridana";
    }
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
        $sql .= " order by $trieditPodla ";
    }
    if ($trieditAko != "0") {
        $sql .= " $trieditAko ";
    }
    $sql .= ";";

    $prace = $GLOBALS['conn']->query($sql);
    if ($prace != null and mysqli_num_rows($prace) > 0) {
        echo '<div id="zoznam-prac" class="kontajner-zoznam-tem transform-stred">';
            $oblubenePrace = array();
            if ($_SESSION["rola"] == "student") {
                $oblubenePrace = getMojeOblubeneTemy($_SESSION["id_osoba"]);
            }

            if ($prace != null && mysqli_num_rows($prace) > 0) {
                while ($praca = $prace->fetch_array()) {
                    $typ_prace = getNazovPrace($praca["id_typ"]);
                    $student = getStudent($praca["id_student"]);
                    $veduci = getVeduci($praca["id_veduci"]);

                    echo '<div id="'. $praca["id_tema"] .'" class="zaver-praca">';
                    echo '<div class="nazov-prace"><b>'. $praca["nazov_sk"] .'</b></div>';
                    echo '<hr class="oddelovac">';
                    echo '<div><b>Anglický názov témy: '. $praca["nazov_en"] .'</b></div>';
                    echo '<div><b>Predmet práce: </b>'. $praca["popis"] .'</div>';
                    echo '<div><b>Typ práce: </b>'. $typ_prace .'</div>';
                    echo '<div><b>Vedúci: </b>'. $veduci["titul_pred"] . " " . $veduci["meno"] . " " .  $veduci["titul_za"] .'</div>';
                    if ($student != null) {
                        echo '<div><b>Študent: </b>'. $student["titul_pred"] . " " . $student["meno"] . " " .  $student["titul_za"] .'</div>';
                    } else {
                        echo '<div><b>Študent: </b></div>';
                    }
                    if (!empty($oblubenePrace)) {
                        if (in_array($praca["id_tema"], $oblubenePrace)) {
                            echo '<button onclick="pridatOblubenuTemu(this)" class="transform-stred tlacidlo-oblubene-odobrat tlacidlo-oblubene"><i class="fa fa-star ikona-tlacidlo"></i><p>Odobrať z obľúbených</p></button>';
                        } else {
                            echo '<button onclick="pridatOblubenuTemu(this)" class="transform-stred tlacidlo-oblubene-pridat tlacidlo-oblubene"><i class="fa fa-star ikona-tlacidlo"></i><p>Pridať medzi obľúbené</p></button>';
                        }
                    }
                    echo '</div>';
                }
            } else {
                echo '<div class="zaver-praca">';
                echo '<div class="stred">Neboli nájdené žiadne práce.</div>';
                echo '</div>';
            }
        echo '</div>';
    } else {
        echo '<div id="zoznam-prac" class="kontajner-zoznam-tem transform-stred">'; //TODO: upraviť výpis pre nesplnený filter
        echo "Žiadna práca nespĺňa zadaný filer.";
        echo '</div>';
    }

}

?>