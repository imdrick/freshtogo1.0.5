$(document).ready(function () {
  ifRegistered();
  ifWelcome();
});
function Toast(thead, ttext, tcolor) {
  $.toast({
    heading: thead,
    text: ttext,
    showHideTransition: "slide",
    icon: tcolor,
    position: "top-center",
  });
}
function ifRegistered() {
  var isRegistered = param("registered");
  if (isRegistered == "y") {
    //"Success", "Giatay ka", "info"
    var thead = "Register";
    var ttext = "Your Account created Successfully!";
    var tcolor = "success";
    Toast(thead, ttext, tcolor);
  }
}

function ifWelcome() {
  var isRegistered = param("welcome");
  if (isRegistered == "y") {
    //"Success", "Giatay ka", "info"
    var thead = "Register";
    var ttext = "Welcome to Fresh2Go!";
    var tcolor = "success";
    Toast(thead, ttext, tcolor);
  }
}

function param(name) {
  return (location.search.split(name + "=")[1] || "").split("&")[0];
}
