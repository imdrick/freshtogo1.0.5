$(document).ready(function () {
  //copy1
  //alert("Aw");
  SubmitReport();
});
function SubmitReport() {
  $(document).on("click", "#submit-report", function () {
    let searchParams = new URLSearchParams(window.location.search);
    let sellerid = searchParams.get("sellerid");
    let riderid = searchParams.get("riderid");
    let userid = searchParams.get("userid");
    let request = searchParams.get("request");
    var reason = $(".reason").val();
    if (reason == "") {
      alert("Please put your reason...");
    }
    $.ajax({
      url: "process/send-request-SubmitReport.php",
      method: "post",
      data: {
        sellerid: sellerid,
        reason: reason,
        request: request,
        riderid: riderid,
        userid: userid,
      },
      success: function (data) {
        if (data == "Success!") {
          alert(
            data +
              " Thank you for your report! The admin will review and take action as soon as possible."
          );
          window.close();
        } else {
          alert(data);
        }
      },
    });
  });
}
