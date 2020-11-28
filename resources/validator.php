<?php

function validujPridanieNovejTemy($nazovPraceSK, $nazovPraceEN, $popisPrace, $typPrace) {
    $nazovPraceSK = upravitVstup($nazovPraceSK);
    $nazovPraceEN = upravitVstup($nazovPraceEN);
    $popisPrace = upravitVstup($popisPrace);
    $typPrace = upravitVstup($typPrace);

    $chyby = [];

    if (empty($nazovPraceSK) || empty($nazovPraceEN) || empty($popisPrace) || empty($typPrace)) {
        array_push($chyby, "Niektoré z polí je prázdne.");
    }
    if (!spravnaDlzkaRetazca($nazovPraceSK, 1, 200) or !spravnaDlzkaRetazca($nazovPraceEN, 1, 200)) {
        array_push($chyby, "Názov práce musí byť v rozsahu od 1 do 200 znakov.");
    }
    if (!spravnaDlzkaRetazca($popisPrace, 1, 2000)) {
        array_push($chyby, "Popis práce musí byť v rozsahu od 1 do 2000 znakov.");
    }
    if (!is_numeric($typPrace)) {
        array_push($chyby, "Typ práce musí byť platné číslo.");
    }

    return $chyby;
}

function validujOsobneUdaje($meno, $titulZa, $titulPred, $email, $telefon) {
    $meno = upravitVstup($meno);
    $titulZa = upravitVstup($titulZa);
    $titulPred = upravitVstup($titulPred);
    $email = upravitVstup($email);
    $telefon = upravitVstup($telefon);

    $chyby = [];
    $emailPattern = "/\b[a-zA-Z]+[a-zA-Z._]*@([a-zA-z]+\.)+[a-zA-Z]{2,4}\b/i";
    $telefonPattern = "/\b0{1}[0-9]{9,9}\b/i";
    $menoPattern = "/([A-ZľščťžďýáíéôúäňĽŠČŤŽÝÁÍĎÉÚŇ]+\s{1}[A-ZľščťžďýáíéôúäňĽŠČŤŽÝÁÍĎÉÚŇ]+){1,45}/i";

    if (empty($meno) || empty($titulZa) || empty($titulPred) || empty($email) || empty($telefon)) {
        array_push($chyby, "Niektoré z polí je prázdne.");
    }
    if (!spravnaDlzkaRetazca($meno, 1, 45)) {
        array_push($chyby, "Meno musí byť v rozsahu od 1 do 45 znakov.");
    }
    if (!spravnaDlzkaRetazca($email, 1, 50)) {
        array_push($chyby, "Email musí byť v rozsahu od 1 do 50 znakov.");
    }
    if (!is_numeric($titulZa) or !is_numeric($titulPred)) {
        array_push($chyby, "Titul pred/za musí byť platné číslo.");
    }
    if (!preg_match($emailPattern, $email)) {
        array_push($chyby, "Email musí byť v platnom tvare.");
    }
    if (!preg_match($telefonPattern, $telefon) or !spravnaDlzkaRetazca($telefon, 10, 10)) {
        array_push($chyby, "Telefón musí byť v platnom tvare.");
    }
    if (!preg_match($menoPattern, $meno)) {
        array_push($chyby, "Meno musí byť v platnom tvare.");
    }

    return $chyby;
}

function upravitVstup($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function spravnaDlzkaRetazca($retazec, $od, $do) {
    $dlzka = strlen($retazec);
    return ($dlzka >= $od and $dlzka <= $do);
}

function vypisChyby($validator) {
    foreach ($validator as $chyba) {
        echo $chyba . "<br>";
    }
}

?>