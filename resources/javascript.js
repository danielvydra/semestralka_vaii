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

function zobrazMenu() {
    var element = document.getElementById("horne-menu-vpravo");
    if (element.style.display === "none" || element.style.display == "") {
        element.style.display = "block";
    } else {
        element.style.display = "none";
    }
    $(window).resize(function(){
        if($(this).width() >= 1280){
            $('.horne-menu-vpravo').show();
        }
        else{
            $('.horne-menu-vpravo').hide();
        }
    });
}

function filtrovatTemy() {
    $.ajax({
        type: "POST",
        url: "ajax.php",
        data: {
            formular: $("#filter-prac").serialize(),
            nazovFunkcie: "filtrovatPrace"
        },
        success: function(result){
            $("#zoznam-prac").replaceWith(result);
        }
    });
}

function pridatOblubenuTemu(button) {
    var idTemy = parseInt(button.parentElement.getAttribute("id"));
    $.ajax({
        type: "POST",
        url: "ajax.php",
        data: {
            nazovFunkcie: "pridatMedziOblubene",
            id_tema: idTemy
        },
        success: function(feedback){
            if (feedback === "odobrana") {
                button.classList.remove("tlacidlo-oblubene-odobrat");
                button.classList.add("tlacidlo-oblubene-pridat");
                button.lastChild.innerText = "Pridať obľúbenú tému";
                alert("Téma bola odobraná.");
            } else if (feedback === "pridana") {
                button.classList.add("tlacidlo-oblubene-odobrat");
                button.classList.remove("tlacidlo-oblubene-pridat");
                button.lastChild.innerText = "Odobrať obľúbenú tému";
                alert("Téma bola pridaná.");
            }
        }
    });
}

function zobrazitOblubenePrace(button) {
    var idTemy = parseInt(button.parentElement.getAttribute("id"));
    $.ajax({
        type: "POST",
        url: "ajax.php",
        data: {
            nazovFunkcie: "zobrazitOblubenePrace",
            id_tema: idTemy
        },
        success: function(result){
            $("#zoznam-prac").replaceWith(result);
        }
    });
}