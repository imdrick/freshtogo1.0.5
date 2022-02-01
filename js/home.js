$(document).ready(function () {
  DisplayHotProducts();
  $("#wewe").click(function () {
    console.log();
  });
  DealsAndOffers();
});

function DisplayHotProducts() {
  $.ajax({
    url: "process/home-displayhotproduct.php",
    method: "post",
    success: function (data) {
      $("#recommended-items").html(data);
    },
  });
}
function DealsAndOffers() {
  $(function () {
    function getCounterData(obj) {
      var days = parseInt($(".s").text());
      var hours = parseInt($(".h").text());
      var minutes = parseInt($(".m").text());
      var seconds = parseInt($(".s").text());
      return seconds + minutes * 60 + hours * 3600 + days * 3600 * 24;
    }

    function setCounterData(s, obj) {
      var days = Math.floor(s / (3600 * 24));
      var hours = Math.floor((s % (60 * 60 * 24)) / 3600);
      var minutes = Math.floor((s % (60 * 60)) / 60);
      var seconds = Math.floor(s % 60);

      console.log(days, hours, minutes, seconds);

      parseInt($(".s").text(days));
      parseInt($(".h").text(hours));
      parseInt($(".m").text(minutes));
      parseInt($(".s").text(seconds));
    }

    var count = getCounterData($(".counter"));

    var timer = setInterval(function () {
      count--;
      if (count == 0) {
        clearInterval(timer);
        return;
      }
      setCounterData(count, $(".counter"));
    }, 1000);
  });
}
