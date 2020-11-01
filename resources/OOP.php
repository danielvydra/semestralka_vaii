<?php

class ZaverecnaPraca {
    public $idTemy;
    public $nazovSK;
    public $nazovEN;
    public $popis;
    public $typPrace;
    public $veduci;
    public $student;

    public function __construct($idTemy, $nazovSK, $nazovEN, $popis, $typPrace, $veduci, $student)
    {
        $this->idTemy = $idTemy;
        $this->nazovSK = $nazovSK;
        $this->nazovEN = $nazovEN;
        $this->popis = $popis;
        $this->typPrace = $typPrace;
        $this->veduci = $veduci;
        $this->student = $student;
    }
}

class Pouzivatel {
    public $idPouzivatela;
    public $titulPred;
    public $titulZa;
    public $meno;
    public $email;
    public $osCislo;
    public $telefon;
    public $vytvorenie;
    public $upravenie;
    public $nazovRole;
    public $fakulta;

    function getCeleMeno() {
        return $this->titulPred . " " . $this->meno . " " . $this->titulZa;
    }
}

class Student extends Pouzivatel {
    public $skupina;
    public $odbor;

    public function __construct($idPouzivatela, $titulPred, $meno, $titulZa, $email, $osCislo, $telefon, $vytvorenie, $upravenie, $nazovRole, $skupina, $fakulta, $odbor)
    {
        $this->idPouzivatela = $idPouzivatela;
        $this->titulPred = $titulPred;
        $this->meno = $meno;
        $this->titulZa = $titulZa;
        $this->email = $email;
        $this->osCislo = $osCislo;
        $this->telefon = $telefon;
        $this->vytvorenie = $vytvorenie;
        $this->upravenie = $upravenie;
        $this->nazovRole = $nazovRole;
        $this->skupina = $skupina;
        $this->fakulta = $fakulta;
        $this->odbor = $odbor;
    }
}

class Ucitel extends Pouzivatel {
    public $miestnost;
    public $katedra;
    public $volnaKapacita;

    public function __construct($idPouzivatela, $titulPred, $meno, $titulZa, $email, $osCislo, $telefon, $vytvorenie, $upravenie, $nazovRole, $fakulta, $miestnost, $katedra, $volnaKapacita)
    {
        $this->idPouzivatela = $idPouzivatela;
        $this->titulPred = $titulPred;
        $this->meno = $meno;
        $this->titulZa = $titulZa;
        $this->email = $email;
        $this->osCislo = $osCislo;
        $this->telefon = $telefon;
        $this->vytvorenie = $vytvorenie;
        $this->upravenie = $upravenie;
        $this->nazovRole = $nazovRole;
        $this->fakulta = $fakulta;
        $this->miestnost = $miestnost;
        $this->katedra = $katedra;
        $this->volnaKapacita = $volnaKapacita;
    }
}

?>