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
    vypisZoznamTitulov($osoba->titulPred);
    echo '</select>';
    echo '<label><b>Celé meno</b></label>';
    echo "<input pattern='[A-ZľščťžýáíéďôúäňĽŠČŤŽÝÁĎÍÉÚŇ]+(([,. -][a-zA-ZľščťžýďáíéôúäňĽŠČŤŽĎÝÁÍÉÚŇ])?[a-zA-ZľščťžďýáíéôúäňĽŠČŤŽĎÝÁÍÉÚŇ]*)*' id='meno' name='meno' type='text' placeholder='Celé meno' value='$osoba->meno' disabled='disabled' required>";
    echo '<label><b>Titul za menom</b></label>';
    echo '<select id="titul-za" name="titul-za" class="medzery dropdown" form="osobne-udaje" disabled="disabled">';
    vypisZoznamTitulov($osoba->titulZa);
    echo '</select>';
    echo '<label><b>Email</b></label>';
    echo "<input id='email' pattern='[a-zA-Z._]+@([a-zA-z]+\.)+[a-zA-Z]{2,4}' name='email' type='text' placeholder='Email' value='$osoba->email' disabled='disabled' required>";
    echo '<label><b>Telefón</b></label>';
    echo "<input id='telefon' pattern='[0]{1}[0-9]{9}' name='telefon' type='text' placeholder='Telefón' value='$osoba->telefon' disabled='disabled' required>";

    echo '<div id="chyby-formulara" class="stred"><div class="fa fa-exclamation-triangle ikona"></div> <span> Boli zadané nesprávne údaje</span></div>';
    echo '<div class="transform-stred stred">';
    echo '<button id="ulozit" type="submit" class="os-udaje-tlacidlo tlacidlo-potvrdit tlacidlo-formular tlacidlo-filter" disabled="disabled"><i class="fa fa-save ikona-tlacidlo"></i>Uložiť</button>';
    echo '<button id="upravit" type="button" class="os-udaje-tlacidlo tlacidlo-upravit tlacidlo-potvrdit tlacidlo-formular tlacidlo-filter" onclick="editovatOsobneUdaje(this)"><i class="fa fa-edit ikona-tlacidlo"></i><span>Upraviť</span></button>';
    echo '</div>';
    echo '</form>';
}

function vypisZoznamTitulov($selected) {
    $result_tituly = getTituly();
    while ($titul = $result_tituly->fetch_array()) {
        if ($titul["nazov"] == $selected) {
            echo '<option  label="'. $titul["nazov"] .' " value="' . $titul["id_titul"] . '" selected>' . $titul["nazov"] . '</option>';
        } else {
            echo '<option  label="'. $titul["nazov"] .' " value="' . $titul["id_titul"] . '">' . $titul["nazov"] . '</option>';
        }
    }
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

function vypisMenu($aktivne) {
    $class = 'class="aktivne"';
    $empty = '';

    echo '<div class="horne-menu">';
        echo '<div>';
            echo '<div class="horne-menu-vlavo">Systém záverečných prác<div onclick="zobrazMenu()" class="fa fa-bars ikona menu-ikona"></div></div>';
        echo '</div>';
        echo '<div id="horne-menu-vpravo" class="horne-menu-vpravo">';
            echo '<a href="zoznam_prac.php" ' . ($aktivne == 1 ? $class : $empty) . '><div class="fa fa-stream ikona-tlacidlo"></div>Zoznam prác</a>';
            echo '<a href="pouzivatelia.php" ' . ($aktivne == 2 ? $class : $empty) . '><div class="fa fa-users ikona-tlacidlo"></div>Používatelia</a>';
            echo '<a href="osobne_udaje.php"' . ($aktivne == 3 ? $class : $empty) . '><div class="fa fa-address-card ikona-tlacidlo"></div>Osobné údaje</a>';
            if ($_SESSION["rola"] == "ucitel") {
                echo '<a href="pridavanie_tem.php"' . ($aktivne == 4 ? $class : $empty) . '><div class="fa fa-plus ikona-tlacidlo"></div>Pridávanie tém</a>';

            } else if ($_SESSION["rola"] == "student") {
                echo '<a href="oblubene_temy.php"' . ($aktivne == 4 ? $class : $empty) . '><div class="fa fa-star ikona-tlacidlo"></div>Obľúbené témy</a>';
            }
            echo '<a href="../resources/odhlasenie.php"><div class="fa fa-power-off ikona-tlacidlo"></div>Odhlásiť</a>';
        echo '</div>';
    echo '</div>';
    echo '<div class="skrol-kontajner">';
        echo '<div id="skrol-indikator" class="skrol-indikator"></div>';
    echo '</div>';
}

function vypisHlavicku($nadpis) {
    $header = '<head>
        <meta charset="UTF-8">
        <title>'. $nadpis .'</title>
        <script src="../resources/javascript.js"></script>
        <script src="../resources/jquery-3.5.1.js"></script>
        <link rel="stylesheet" href="../resources/dizajn.css">
        <link rel="stylesheet" href="../fontawesome/css/all.css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        </head>';
    echo $header;
}

function vypisPrihlasenie() {
    ?>
    <div class="nadpis-ramcek">Systém záverečných prác</div>
    <div class="prihl-okno">
        <form id="form-prihlasenie" class="formular" action="javascript:prihlasit()" method="post">
            <h1 class="stred">Prihlásenie</h1>
            <p class="stred">Vyplňte prosím nasledujúce údaje pre prihlásenie</p>
            <label for="os_cislo" class="stred"><b>Osobné číslo</b></label>
            <div class="input-riadok">
                <div class="fas fa-user ikona"></div>
                <input maxlength="6" class="login-input" pattern="[0-9]{6,6}" id="os_cislo" type="text" placeholder="Vložte osobné čislo" name="os_cislo" required>
            </div>

            <label for="heslo" class="stred"><b>Heslo</b></label>
            <div class="input-riadok">
                <div class="fas fa-key ikona"></div>
                <input class="login-input" id="heslo" pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*]).{8,16}" type="password" placeholder="Vložte heslo" name="heslo" required>
            </div>

            <div id="chyba-prihlasenie" class="stred"><div class="fa fa-exclamation-triangle ikona"></div> <span> Boli zadané nesprávne údaje</span></div>
            <div>
                <button type="submit" class="transform-stred tlacidlo-potvrdit tlacidlo-formular">Prihlásiť</button>
            </div>
        </form>
    </div>
<?php
}

function vypisPridavaniaTem() {
    ?>
        <div class="kontajner-zoznam-tem kont-nova-tema transform-stred pridavanie-tem">
            <div id="id-prace" class="zaver-praca nova-tema">

                <form id="nova-tema" class="formular" method="post" action="javascript:pridatNovuTemu()">
                    <h1 class="stred">Pridávanie tém</h1>
                    <p class="stred">Vyplňte prosím nasledujúce údaje pre pridanie témy</p>

                    <label for="nazov-prace-input" class="stred stitok"><b>Názov práce</b></label>
                    <div class="input-riadok">
                        <div class="fas fa-heading ikona"></div>
                        <input id="nazov-prace-input" type="text" placeholder="Vložte názov práce" name="nazov-prace" minlength="1" maxlength="200" required>
                    </div>

                    <label for="ang-nazov-prace" class="stred stitok"><b>Anglický názov práce</b></label>
                    <div class="input-riadok">
                        <div class="fas fa-language ikona"></div>
                        <input id="ang-nazov-prace" type="text" placeholder="Vložte anglický názov práce" name="ang-nazov-prace" minlength="1" maxlength="200" required>
                    </div>

                    <label for="popis-prace" class="stred stitok"><b>Popis práce</b></label>
                    <textarea id="popis-prace" class="medzery" placeholder="Zadajte cieľ práce" rows="4" cols="50" name="popis-prace" form="nova-tema" minlength="1" maxlength="2000" required></textarea>

                    <label for="typ-prace" class="stred stitok"><b>Typ práce</b></label>
                    <select name="typ-prace" class="medzery dropdown transform-stred" id="typ-prace" form="nova-tema">
                        <?php
                    $result_typy_prac = getTypyPrac();
                    while ($typ_prace = $result_typy_prac->fetch_array()) {
                        echo '<option value="'. $typ_prace["id_typ"] .'">'. $typ_prace["nazov"] .'</option>';
                    }
                    ?>
                    </select>

                    <div id="chyby-formulara" class="stred"><div class="fa fa-exclamation-triangle ikona"></div> <span> Boli zadané nesprávne údaje</span></div>
                    <div>
                        <button type="submit" class="transform-stred tlacidlo-potvrdit tlacidlo-formular"><i class="fa fa-plus ikona-tlacidlo"></i>Pridať</button>
                    </div>
                </form>

            </div>
        </div>
<?php
}

function vypisFilterPrac() {
    echo '<div class="zaver-praca filter">';
        echo '<form id="filter-prac" class="formular" method="post" action="javascript:filtrovatTemy()">';
            echo '<h1 class="stred transform-stred tooltip" onclick="zobrazViacInfo(this)">Filter<span class="tooltiptext">Kliknutím zobraziť/skryť filter</span></h1><div style="display: none;">';
                echo '<div class="stred">';
                    echo '<input name="nazov-prace" type="text" placeholder="Názov práce">';
                    echo '<input name="meno-veduceho" type="text" placeholder="Meno vedúceho">';
                    echo '<input name="meno-studenta" type="text" placeholder="Meno študenta">';
                echo '</div>';

                echo '<div class="stred">';
                    echo '<select name="typ-prace" class="medzery dropdown" form="filter-prac">';
                        echo '<option value="">Typ práce</option>';
                        $result_typy_prac = getTypyPrac();
                        while ($typ_prace = $result_typy_prac->fetch_array()) {
                            echo '<option value="'. $typ_prace["id_typ"] .'">'. $typ_prace["nazov"] .'</option>';
                        }
                    echo '</select>';
                    echo '<select name="katedra" class="medzery dropdown" form="filter-prac" >';
                        echo '<option value="">Katedra</option>';
                        $result_katedry = getKatedry();
                        while ($katedra = $result_katedry->fetch_array()) {
                            echo '<option value="'. $katedra["id_katedra"] .'">'. $katedra["nazov"] .'</option>';
                        }
                    echo '</select>';
                    echo '<select name="stav-prace" class="medzery dropdown" form="filter-prac" >';
                    echo '<option value="">Stav práce</option>';
                    $result_stavy_prace = getStavyPrace();
                    while ($stav = $result_stavy_prace->fetch_array()) {
                        echo '<option value="'. $stav["id_stav"] .'">'. $stav["stav"] .'</option>';
                    }
                echo '</select>';
                echo '</div>';

                echo '<div class="stred">';
                    echo '<select name="triedit-podla" class="medzery dropdown" form="filter-prac" >';
                        echo '<option value="0">Triediť podľa</option>';
                        echo '<option value="nazov_sk">Názov témy</option>';
                        echo '<option value="uc.meno">Meno vedúceho</option>';
                        echo '<option value="st.meno">Meno študenta</option>';
                        echo '<option value="zp.id_typ">Typ práce</option>';
                        echo '<option value="zp.id_stav">Stav práce</option>';
                        echo '<option value="u.id_katedra">Katedra</option>';
                    echo '</select>';
                    echo '<select name="triedit-ako" class="medzery dropdown" form="filter-prac" >';
                        echo '<option value="asc">Vzostupne</option>';
                        echo '<option value="desc">Zostupne</option>';
                    echo '</select>';
                echo '</div>';
                echo '<div>';
                    echo '<button id="filtrovat" type="submit" class="transform-stred tlacidlo-potvrdit tlacidlo-formular tlacidlo-filter"><i class="fa fa-filter ikona-tlacidlo"></i>Filtruj</button>';
                echo '</div>';
            echo '</div>';
        echo '</form>';
    echo '</div>';
}

function vypisNenajdenyPouzivatel() { ?>
    <div id="zoznam-uzivatelov" class="kontajner-zoznam-tem transform-stred">
    <div class="zaver-praca pouzivatelia stred">Žiaden používateľ nespĺňa zadaný filer.</div>
    </div>
<?php
}

function vypisFilterPouzivatelov() {
    echo '<div class="zaver-praca filter">';
        echo '<form id="filter-uzivatelov" class="formular" action="javascript:filtrovatPouzivatelov()">';
            echo '<h1 class="stred transform-stred tooltip" onclick="zobrazViacInfo(this)">Filter<span class="tooltiptext">Kliknutím zobraziť/skryť filter</span></h1><div style="display: none;">';
                echo '<div class="stred">';
                    echo '<input name="meno-osoby" type="text" placeholder="Meno osoby">';
                    echo '<select name="typ-osoby" class="medzery dropdown" form="filter-uzivatelov">';
                    echo '<option value="">Typ osoby</option>';
                        $result_typy_osob = getTypyOsob();
                        while ($typ_osoby = $result_typy_osob->fetch_array()) {
                            echo '<option value="' . $typ_osoby["id_rola"] . '">' . $typ_osoby["nazov_role"] . '</option>';
                        }
                    echo '</select>';
                echo '</div>';

                echo '<div class="stred">';
                    echo '<select name="triedit-podla" class="medzery dropdown" form="filter-uzivatelov">';
                        echo '<option value="">Triediť podľa</option>';
                        echo '<option value="meno">Meno osoby</option>';
                        echo '<option value="ou.id_rola">Typ osoby</option>';
                    echo '</select>';
                    echo '<select name="triedit-ako" class="medzery dropdown" form="filter-uzivatelov">';
                        echo '<option value="asc">Vzostupne</option>';
                        echo '<option value="desc">Zostupne</option>';
                    echo '</select>';
                echo '</div>';

                echo '<div>';
                    echo '<button type="submit" class="tlacidlo-potvrdit tlacidlo-formular tlacidlo-filter transform-stred"><i class="fa fa-filter ikona-tlacidlo"></i>Filtruj</button>';
                echo '</div>';
            echo '</div>';
        echo '</form>';
    echo '</div>';
}

?>