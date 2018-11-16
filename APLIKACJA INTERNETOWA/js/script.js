function pokazUzupelnienie() {
  $('#dodawanie-osob-select').on("change", function() {

    //Pobieram wartość selecta uprawnień osoby
    var tekst = this.children[this.selectedIndex].text.trim();

    //Poniżej sprawdzam jakie to uprawnienie i na podstawie tego
    //zmieniam wyświetlanie odpowiednich bloków uzupełnijących
    if (tekst == "Administrator") {
      $('#nauczyciel-uzu').css({"display": "none"});
      $('#uczen-uzu').css({"display": "none"});
    } else if (tekst == "Nauczyciel") {
      $('#nauczyciel-uzu').css({"display": "block"});
      $('#uczen-uzu').css({"display": "none"});
    } else if (tekst == "Uczeń") {
      $('#nauczyciel-uzu').css({"display": "none"});
      $('#uczen-uzu').css({"display": "block"});
    }
  });
}

function pokazOdpOcene() {
  $('#wyb_ocena_uzu').on("change", function() {

    //Pobieram wartość selecta oceny
    let wartosc = this.children[this.selectedIndex].text.trim();

    //Pobieram ocenę z var ocena
    let ocena = wartosc.substr(wartosc.length - 2, 2).trim();

    //Ustawiam wartosc selecta wartosci
    $('#wyb_wartosc_uzu').val(ocena);
  });
}
