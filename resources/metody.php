<?php

function vypisPrac($prace, $oblubenePrace, $onclick = "pridatOblubenuTemu(this)") {
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
        if ($_SESSION["rola"] == "student") {
            if (in_array($praca["id_tema"], $oblubenePrace)) {
                echo '<button onclick="'. $onclick .'" class="transform-stred tlacidlo-oblubene-odobrat tlacidlo-oblubene"><i class="fa fa-star ikona-tlacidlo"></i><p>Odobrať z obľúbených</p></button>';
            } else {
                echo '<button onclick="'. $onclick .'" class="transform-stred tlacidlo-oblubene-pridat tlacidlo-oblubene"><i class="fa fa-star ikona-tlacidlo"></i><p>Pridať medzi obľúbené</p></button>';
            }
        }
        echo '</div>';
    }
}

?>