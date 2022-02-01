$(document).ready(function () {
  getIp();
});
function getIp() {
  //   $.get("ipinfo.io/130.105.29.88?token=fc6c27da6031c4", function (response) {
  //     console.log(response);
  //   });
  fetch("https://ipinfo.io/json?token=fc6c27da6031c4")
    .then((response) => response.json())
    .then((jsonResponse) => console.log(jsonResponse));
}
