$('#nadajUprawnienia').on("change", function() {

  var war_select_upr = this.children[this.selectedIndex].text.trim();

  $('#nauczyciel-uzu').css({"display": "none"});
  $('#uczen-uzu').css({"display": "none"});

  if (war_select_upr == "Nauczyciel")
    $('#nauczyciel-uzu').css({"display": "block"});
  else if (war_select_upr == "Uczeń")
    $('#uczen-uzu').css({"display": "block"});
});

function pokazOdpOcene() {
  $('#wyb_ocena_uzu').on("change", function() {
    let war_select_ocena = this.children[this.selectedIndex].text.trim();
    let ocena = wartosc.substr(war_select_ocena.length - 2, 2).trim();
    $('#wyb_wartosc_uzu').val(ocena);
  });
}
