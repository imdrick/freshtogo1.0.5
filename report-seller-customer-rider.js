function SubmitReport() {
  $(document).on("click", "#submit-report", function () {
    let searchParams = new URLSearchParams(window.location.search);
    let sellerid = searchParams.get("sellerid");
    let productid = searchParams.get("productid");
    let riderid = searchParams.get("riderid");

    let report = searchParams.get("report");
    let fromreport = searchParams.get("fromreport");
    let toreport = searchParams.get("toreport");
    console.log(fromreport, toreport, sellerid);
    var reason = $(".reason").val();
    if (reason == "") {
      alert("Please put your reason...");
    }
    $.ajax({
      url: "process/send-report-SubmitReport.php",
      method: "post",
      data: {
        sellerid: sellerid,
        reason: reason,
        productid: productid,
        riderid: riderid,
        fromreport: fromreport,
        toreport: toreport,
        report: report,
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
