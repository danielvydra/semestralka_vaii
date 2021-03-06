function skrolovanie() {
    var vyska = document.documentElement.scrollHeight - document.documentElement.clientHeight;
    var skrol = document.body.scrollTop || document.documentElement.scrollTop;
    var odskrolovane = (skrol / vyska) * 100;
    document.getElementById("skrol-indikator").style.width = odskrolovane + "%";

    var tlacidloHore = document.getElementById("tlacidlo-ist-hore");
    if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
        tlacidloHore.style.display = "block";
    } else {
        tlacidloHore.style.display = "none";
    }
}

function istHore() {
    document.documentElement.scrollTop = 0;
    document.body.scrollTop = 0;
}

function zobrazViacInfo(element) {
    var sibling = element.nextSibling;
    if (sibling.style.display === "none") {
        sibling.style.display = "block";
    } else {
        sibling.style.display = "none";
    }
}

function prihlasit() {
    element = document.getElementById("chyba-prihlasenie");

    $.ajax({
        type: "POST",
        url: "../resources/autentifikacia.php",
        data: $("#form-prihlasenie").serialize(),
        success: function (result) {
            if (result.includes("Chyba")) {
                element.lastElementChild.innerHTML = result;
                element.style.display = "block";
            } else if (result == "login") {
                window.location.replace('../pages/zoznam_prac.php');
            }
        }
    });
}

function zobrazMenu() {
    var element = document.getElementById("horne-menu-vpravo");
    if (element.style.display === "none" || element.style.display === "") {
        element.style.display = "block";
    } else {
        element.style.display = "none";
    }
    $(window).resize(function () {
        if ($(this).width() >= 1280) {
            $('.horne-menu-vpravo').show();
        } else {
            $('.horne-menu-vpravo').hide();
        }
    });
}

function filtrovatTemy() {
    $.ajax({
        type: "POST",
        url: "../resources/ajax.php",
        data: {
            formular: $("#filter-prac").serialize(),
            nazovFunkcie: "filtrovatPrace"
        },
        success: function (result) {
            $("#zoznam-prac").replaceWith(result);
        }
    });
}

function filtrovatPouzivatelov() {
    $.ajax({
        type: "POST",
        url: "../resources/ajax.php",
        data: {
            formular: $("#filter-uzivatelov").serialize(),
            nazovFunkcie: "filtrovatUzivatelov"
        },
        success: function (result) {
            $("#zoznam-uzivatelov").replaceWith(result);
        }
    });
}

function pridatOblubenuTemu(button) {
    var idTemy = parseInt(button.parentElement.getAttribute("id"));
    $.ajax({
        type: "POST",
        url: "../resources/ajax.php",
        data: {
            nazovFunkcie: "pridatMedziOblubene",
            id_tema: idTemy
        },
        success: function (feedback) {
            if (feedback == "odobrana") {
                button.classList.remove("tlacidlo-oblubene-odobrat");
                button.classList.add("tlacidlo-oblubene-pridat");
                button.lastChild.innerText = "Pridať obľúbenú tému";
                zobrazSnackbar("Téma bola odobraná.");
            } else if (feedback == "pridana") {
                button.classList.add("tlacidlo-oblubene-odobrat");
                button.classList.remove("tlacidlo-oblubene-pridat");
                button.lastChild.innerText = "Odobrať z obľúbených";
                zobrazSnackbar("Téma bola pridaná.");
            } else {
                zobrazSnackbar("Nastala chyba s databázou.");
            }
        }
    });
}

function zobrazitOblubenePrace(button) {
    var idTemy = parseInt(button.parentElement.getAttribute("id"));
    $.ajax({
        type: "POST",
        url: "../resources/ajax.php",
        data: {
            nazovFunkcie: "zobrazitOblubenePrace",
            id_tema: idTemy
        },
        success: function (result) {
            if (result == 1) {
                zobrazSnackbar("Nastala chyba s databázou.");
            } else {
                $("#zoznam-prac").replaceWith(result);
                zobrazSnackbar("Téma bola odobraná.");
            }
        }
    });
}

function pridatNovuTemu() {
    $.ajax({
        type: "POST",
        url: "../resources/ajax.php",
        data: {
            nazovFunkcie: "pridatNovuTemu",
            formular: $("#nova-tema").serialize()
        },
        success: function (result) {
            let element = document.getElementById("chyby-formulara");

            if (result == 1) {
                zobrazSnackbar("Téma už existuje v databáze.");
            } else if (result.includes("zoznam-prac")) {
                $("#zoznam-prac").replaceWith(result);
                zobrazSnackbar("Nová téma bola úspešne pridaná.");
                element.style.display = "none";
            } else {
                element.lastElementChild.innerHTML = result;
                element.style.display = "block";
                zobrazSnackbar("Nepodarilo sa pridať novú tému.");
            }
            $("#nova-tema")[0].reset();
        }
    });
}

function odobratPracuZoZoznamuPrac(button) {
    var idTemy = parseInt(button.parentElement.getAttribute("id"));
    if (confirm("Naozaj vymazať?")) {
        $.ajax({
            type: "POST",
            url: "../resources/ajax.php",
            data: {
                nazovFunkcie: "odobratPracuZoZoznamuPrac",
                id_tema: idTemy
            },
            success: function (result) {
                $("#zoznam-prac").replaceWith(result);
                zobrazSnackbar("Téma bola úspešne vymazaná.");
            }
        });
    }
}

function odobratTemuZPridavaniaTem(button) {
    var idTemy = parseInt(button.parentElement.getAttribute("id"));
    if (confirm("Naozaj vymazať?")) {
        $.ajax({
            type: "POST",
            url: "../resources/ajax.php",
            data: {
                nazovFunkcie: "odobratTemuZPridavaniaTem",
                id_tema: idTemy
            },
            success: function (result) {
                $("#zoznam-prac").replaceWith(result);
                zobrazSnackbar("Téma bola úspešne vymazaná.");
            }
        });
    }
}

function editovatOsobneUdaje(upravit) {
    var ulozit = document.getElementById("ulozit");
    var titulPred = document.getElementById("titul-pred");
    var meno = document.getElementById("meno");
    var titulZa = document.getElementById("titul-za");
    var email = document.getElementById("email");
    var telefon = document.getElementById("telefon");

    if (ulozit.disabled) {
        ulozit.disabled = false;
        titulPred.disabled = false;
        meno.disabled = false;
        titulZa.disabled = false;
        email.disabled = false;
        telefon.disabled = false;
        upravit.lastChild.innerText = "Zrušiť";
    } else {
        ulozit.disabled = true;
        titulPred.disabled = true;
        meno.disabled = true;
        titulZa.disabled = true;
        email.disabled = true;
        telefon.disabled = true;
        upravit.lastChild.innerText = "Upraviť";
    }
}

function upravitOsobneUdaje() {
    if (confirm("Naozaj upraviť osobné údaje?")) {
        $.ajax({
            type: "POST",
            url: "../resources/ajax.php",
            data: {
                nazovFunkcie: "upravitOsobneUdaje",
                formular: $("#osobne-udaje").serialize()
            },
            success: function (result) {
                let element = document.getElementById("chyby-formulara");

                if (result == 1) {
                    zobrazSnackbar("Nastala chyba databázy. Osobné údaje neboli upravené.");
                } else if (result.includes("osobne-udaje")) {
                    $("#osobne-udaje").replaceWith(result);
                    zobrazSnackbar("Osobné údaje boli upravené.");
                    element.style.display = "none";
                } else {
                    element.lastElementChild.innerHTML = result;
                    element.style.display = "block";
                    zobrazSnackbar("Nepodarilo sa upraviť osobné údaje.");
                }
            }
        });
    } else {
        location.reload();
    }
}

function zobrazSnackbar(text) {
    let snackbar = document.getElementById("snackbar");
    snackbar.innerText = text;
    snackbar.className = "show";
    setTimeout(function () {
        snackbar.className = snackbar.className.replace("show", "");
    }, 3000);
}