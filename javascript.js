function test() {
    var dropdown = document.getElementById("role-dropdown");
    var value = dropdown.options[dropdown.selectedIndex].value;
    if (value == 1) {
        var formular = document.getElementById("formular-registracia");
        var input = document.createElement("input");
        input.type = "text";
        input.name = "miestnost"
        input.id = "miestnost";
        input.required = true;
        input.placeholder = "Vložte miestnosť";
        if (document.getElementById("miestnost") == null)
            formular.appendChild(input);
    } else  {
        document.getElementById("miestnost").remove();
    }
}

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