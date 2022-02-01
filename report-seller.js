function ReportSeller() {
  $.ajax({
    url: "process/send-report-ReportSeller.php",
    method: "",
    success: function (data) {
      console.log(data);
    },
  });
}
