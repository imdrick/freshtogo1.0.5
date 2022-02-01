$(document).ready(function () {
  DisplayVoucher();
});

function DisplayVoucher() {
  $.ajax({
    url: "process/pag-voucher-DisplayVoucher.php",
    method: "post",
    data: {},
    success: function (data) {
      console.log(data);
      $("#display-voucher").html(data);
    },
  });
}
