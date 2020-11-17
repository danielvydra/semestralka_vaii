<?php

function vypisZaverecnePrace($prace = null) {
    if ($prace == null) {
        $prace = getZaverecnePrace();
    }

    if ($_SESSION['rola'] == "student") {
        $zoznamOblubenychPrac = getZoznamOblubenychTem($_SESSION["id_osoba"]);
        $onclick = "pridatOblubenuTemu(this)";
    } else {
        $zoznamOblubenychPrac = getZoznamMojichPridanychTem($_SESSION["id_osoba"]);
        $onclick = "odobratPracuZoZoznamuPrac(this)";
    }

    echo '<div id="zoznam-prac" class="kontajner-zoznam-tem transform-stred">';
    if (isset($prace) and sizeof($prace) > 0) {
        vypisPrac($prace,"$onclick", $zoznamOblubenychPrac);
    } else {
        echo '<div class="zaver-praca">';
        echo '<div class="stred">Neboli nájdené žiadne práce.</div>';
        echo '</div>';
    }
    echo '</div>';
}

function vypisFiltrovanePrace($where) {
    $prace = getZaverecnePrace($where);
    if ($prace == null) {
        echo '<div id="zoznam-prac" class="kontajner-zoznam-tem transform-stred">';
        echo '<div class="zaver-praca">';
        echo '<div class="stred">Filtru nezodpovedajú žiadne práce.</div>';
        echo '</div>';
        echo '</div>';
    } else {
        vypisZaverecnePrace($prace);
    }
}

function vypisPrac($prace, $onclick, $zoznamPrac = null) {
    foreach ($prace as $praca) {
        echo "<div id='$praca->idTemy' class='zaver-praca'>";
        echo "<div class='nazov-prace'><b>$praca->nazovSK</b></div>";
        echo '<hr class="oddelovac">';
        echo "<div><b>Anglický názov témy: $praca->nazovEN</b></div>";
        echo "<div><b>Predmet práce: </b>$praca->popis</div>";
        echo "<div><b>Typ práce: </b>$praca->typPrace</div>";
        echo "<div><b>Vedúci: </b>$praca->veduci</div>";
        if (isset($praca->student)) {
            echo "<div><b>Študent: </b>$praca->student</div>";
        } else {
            echo '<div><b>Študent: </b></div>';
        }
        if ($_SESSION["rola"] == "student") {
            if (!isset($zoznamPrac) or in_array($praca->idTemy, $zoznamPrac)) {
                echo "<button onclick='$onclick' class='transform-stred tlacidlo-oblubene-odobrat tlacidlo-oblubene'><i class='fa fa-star ikona-tlacidlo'></i><span>Odobrať z obľúbených</span></button>";
            } else {
                echo "<button onclick='$onclick' class='transform-stred tlacidlo-oblubene-pridat tlacidlo-oblubene'><i class='fa fa-star ikona-tlacidlo'></i><span>Pridať medzi obľúbené</span></button>";
            }
        } elseif ($_SESSION["rola"] == "ucitel") {
            if (!isset($zoznamPrac) or in_array($praca->idTemy, $zoznamPrac)) {
                echo "<button onclick='$onclick' class='transform-stred tlacidlo-oblubene-odobrat tlacidlo-oblubene'><i class='fa fa-trash-alt ikona-tlacidlo'></i><span>Vymazať tému zo systému</span></button>";
            }
        }
        echo '</div>';
    }
}

function vypisOblubenychPrac() {
    $mojeId = $_SESSION["id_osoba"];
    $where = "where id_tema in (select id_tema from oblubene_temy where id_student = $mojeId)";
    $prace = getZaverecnePrace($where);

    echo '<div id="zoznam-prac" class="kontajner-zoznam-tem transform-stred oblubene-temy">';
    if (isset($prace) and sizeof($prace) > 0) {
        vypisPrac($prace, "zobrazitOblubenePrace(this)",null);
    } else {
        echo '<div class="zaver-praca">';
        echo '<div class="stred">Neboli nájdené žiadne obľúbené práce.</div>';
        echo '</div>';
    }
    echo '</div>';
}

function vypisMojichVytvorenychPrac() {
    $mojeId = $_SESSION["id_osoba"];
    $where = "where id_veduci = $mojeId";
    $prace = getZaverecnePrace($where);

    echo '<div id="zoznam-prac" class="kontajner-zoznam-tem transform-stred">';
    if (isset($prace) and sizeof($prace) > 0) {
        vypisPrac($prace,  "odobratTemuZPridavaniaTem(this)", null);
    } else {
        echo '<div class="zaver-praca">';
        echo '<div class="stred">Neboli nájdené žiadne pridané práce.</div>';
        echo '</div>';
    }
    echo '</div>';
}

function vypisOsobnychUdajov() {
    echo '<form id="osobne-udaje" class="formular" method="post" action="javascript:upravitOsobneUdaje()">';
        echo '<h1 class="stred transform-stred tooltip">Osobné údaje</h1>';

        $mojeId = $_SESSION["id_osoba"];
        $where = "where ou.id_osoba = $mojeId";
        $osoba = getPouzivatelov($where)[0];

        echo '<p>';
            echo '<b>Meno: </b>' . $osoba->getCeleMeno();
            echo '<br>';
            echo '<b>Osobné číslo: </b>' . $osoba->osCislo;
            echo '<br>';
            echo '<b>Email: </b>' . $osoba->email;
            echo '<br>';
            echo '<b>Telefón: </b>' . $osoba->telefon;
            echo '<br>';
            echo '<b>Upravenie: </b>' . $osoba->upravenie;
            echo '<br>';
            echo '<b>Vytvorenie: </b>' . $osoba->vytvorenie;
            echo '<br>';
            echo '<b>Fakulta: </b>' . $osoba->fakulta;
            if ($_SESSION["rola"] == "student") {
                echo '<br>';
                echo '<b>Skupina: </b>' . $osoba->skupina;
                echo '<br>';
                echo '<b>Odbor: </b>' . $osoba->odbor;
            } elseif ($_SESSION["rola"] == "ucitel") {
                echo '<br>';
                echo '<b>Katedra: </b>' . $osoba->katedra;
                echo '<br>';
                echo '<b>Miestnosť: </b>' . $osoba->miestnost;
                echo '<br>';
                echo '<b>Voľná kapacita: </b>' . ($osoba->volnaKapacita == 1 ? "áno" : "nie");
            }
        echo '</p>';

        echo '<h2 class="stred">Úprava údajov</h2>';
        echo '<label><b>Titul pred menom</b></label>';
        echo '<select id="titul-pred" name="titul-pred" class="medzery dropdown" form="osobne-udaje" disabled="disabled">';
            $result_tituly = getTituly();
            while ($titul = $result_tituly->fetch_array()) {
                if ($titul["nazov"] == $osoba->titulPred) {
                    echo '<option label="'. $titul["nazov"] .' " value="' . $titul["id_titul"] . '" selected>' . $titul["nazov"] . '</option>';
                } else {
                    echo '<option label="'. $titul["nazov"] .' " value="' . $titul["id_titul"] . '">' . $titul["nazov"] . '</option>';
                }
            }
        echo '</select>';
        echo '<label><b>Celé meno</b></label>';
        echo "<input pattern='[A-Z+ľščťžýáíéôúäňĽŠČŤŽÝÁÍÉÚŇ]+(([,. -][a-zA-ZľščťžýáíéôúäňĽŠČŤŽÝÁÍÉÚŇ])?[a-zA-ZľščťžýáíéôúäňĽŠČŤŽÝÁÍÉÚŇ]*)*' id='meno' name='meno' type='text' placeholder='Celé meno' value='$osoba->meno' disabled='disabled' required>";
        echo '<label><b>Titul za menom</b></label>';
        echo '<select id="titul-za" name="titul-za" class="medzery dropdown" form="osobne-udaje" disabled="disabled">';
            $result_tituly = getTituly();
            while ($titul = $result_tituly->fetch_array()) {
                if ($titul["nazov"] == $osoba->titulZa) {
                    echo '<option  label="'. $titul["nazov"] .' " value="' . $titul["id_titul"] . '" selected>' . $titul["nazov"] . '</option>';
                } else {
                    echo '<option  label="'. $titul["nazov"] .' " value="' . $titul["id_titul"] . '">' . $titul["nazov"] . '</option>';
                }
            }
        echo '</select>';
        echo '<label><b>Email</b></label>';
        echo "<input id='email' pattern='[a-zA-Z._]+@([a-zA-z]+\.)+[a-zA-Z]{2,4}' name='email' type='text' placeholder='Email' value='$osoba->email' disabled='disabled' required>";
        echo '<label><b>Telefón</b></label>';
        echo "<input id='telefon' pattern='[0]{1}[0-9]{9}' name='telefon' type='text' placeholder='Telefón' value='$osoba->telefon' disabled='disabled' required>";

        echo '<div class="transform-stred stred">';
            echo '<button id="ulozit" type="submit" class="os-udaje-tlacidlo tlacidlo-potvrdit tlacidlo-formular tlacidlo-filter" disabled="disabled"><i class="fa fa-save ikona-tlacidlo"></i>Uložiť</button>';
            echo '<button id="upravit" type="button" class="os-udaje-tlacidlo tlacidlo-upravit tlacidlo-potvrdit tlacidlo-formular tlacidlo-filter" onclick="editovatOsobneUdaje(this)"><i class="fa fa-edit ikona-tlacidlo"></i><span>Upraviť</span></button>';
        echo '</div>';
    echo '</form>';
}

function vypisPouzivatelov($pouzivatelia) {
    echo '<div id="zoznam-uzivatelov" class="kontajner-zoznam-tem transform-stred">';
        foreach ($pouzivatelia as $uzivatel) {
            if ($uzivatel instanceof Ucitel) {
                $viac_info = "<div><b>Miestnosť: </b>$uzivatel->miestnost</div>";
                $viac_info .= "<div><b>Fakulta: </b>$uzivatel->fakulta</div>";
                $viac_info .= "<div><b>Katedra: </b>$uzivatel->katedra</div>";
                if ($uzivatel->volnaKapacita) {
                    $viac_info .= '<div><b>Prijíma témy: </b>áno<div class="fa fa-check info-ikona ok"></div></div>';
                } else {
                    $viac_info .= '<div><b>Prijíma témy: </b>nie<div class="fa fa-times info-ikona not-ok"></div></div>';
                }
            } else if ($uzivatel instanceof Student) {
                $viac_info = "<div><b>Študijná skupina: </b>$uzivatel->skupina</div>";
                $viac_info .= "<div><b>Odbor: </b>$uzivatel->odbor</div>";
                $viac_info .= "<div><b>Fakulta: </b>$uzivatel->fakulta</div>";
            }

            echo "<div id='$uzivatel->osCislo' class='zaver-praca pouzivatelia'>";
            echo "<div onclick='zobrazViacInfo(this)' class='nazov-prace'><b>" .$uzivatel->getCeleMeno(). "</b></div>";
            echo '<div style="display: none;">';
            echo '<hr class="oddelovac">';
            echo "<div><b>Email: </b>$uzivatel->email</div>";
            echo "<div><b>Osobné číslo: </b>$uzivatel->osCislo</div>";
            echo "<div><b>Telefón: </b>$uzivatel->telefon</div>";
            echo $viac_info;
            echo '</div>';
            echo '</div>';
        }
    echo '</div>';
}

?>