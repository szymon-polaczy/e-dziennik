$('#nadajUprawnienia').on("change", function() {

  var war_select_upr = this.children[this.selectedIndex].text.trim();

  $('#nauczyciel-uzu').css({"display": "none"});
  $('#uczen-uzu').css({"display": "none"});

  document.getElementById("wybierzKlase").required = false;
  document.getElementById("wybierzSale").required = false;
  document.getElementById("dataUrodzenia").required = false;

  if (war_select_upr == "Teachers") {
    $('#nauczyciel-uzu').css({"display": "block"}); 
    document.getElementById("wybierzSale").required = true;
  }
  else if (war_select_upr == "Learners") {
    $('#uczen-uzu').css({"display": "block"});
    document.getElementById("wybierzKlase").required = true;
    document.getElementById("dataUrodzenia").required = true;
  }
});

//NIE MAM ZIELONEGO POJĘCIA GDZIE JEST #wyb_ocena_uzu WIĘC GDZIEŚ MOŻE SIĘ ZEPSUC
function pokazOdpOcene() {
  $('#wyb_ocena_uzu').on("change", function() {
    let war_select_ocena = this.children[this.selectedIndex].text.trim();
    let ocena = wartosc.substr(war_select_ocena.length - 2, 2).trim();
    $('#wyb_wartosc_uzu').val(ocena);
  });
}
