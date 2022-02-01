$(document).ready(function () {
  DisplayOrders();
});
function DisplayOrders() {
  $.ajax({
    url: "process/page-profile-order-displayorders.php",
    method: "post",
    data: {},
    success: function (data) {
      $("#display-orders").html(data);
    },
  });
}

