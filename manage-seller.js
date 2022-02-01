$(document).ready(function () {
  //alert("a");
  GoToInvoice();
  SetInitialDate();
  SetInitialDate2();
  ResetBtn();
});

function DataTable() {
  $("#dtBasicExample").DataTable();
  $(".dataTables_length").addClass("bs-select");
}

function SetInitialDate() {
  var date = new Date(),
    y = date.getFullYear(),
    m = date.getMonth();
  var firstDay = new Date(y, m, 1);
  var lastDay = new Date(y, m + 1, 0);
  /////////////////
  var date = new Date(firstDay);
  var month_firstday =
    date.getMonth() > 8 ? date.getMonth() + 1 : "0" + (date.getMonth() + 1);
  var day_firstday = date.getDate() > 9 ? date.getDate() : "0" + date.getDate();
  var year_firstday = date.getFullYear();
  var firstDayofMonth =
    year_firstday + "-" + month_firstday + "-" + day_firstday;
  $(".from-date").val(firstDayofMonth);
  //////////////////
  var date2 = new Date(lastDay);
  var month_lastday =
    date2.getMonth() > 8 ? date2.getMonth() + 1 : "0" + (date2.getMonth() + 1);
  var day_lastday =
    date2.getDate() > 9 ? date2.getDate() : "0" + date2.getDate();
  var year_lastday = date2.getFullYear();
  var lastDayofMonth = year_lastday + "-" + month_lastday + "-" + day_lastday;
  $(".to-date").val(lastDayofMonth);
  //DisplayOrders(firstDayofMonth, lastDayofMonth);
  SelectStatus(firstDayofMonth, lastDayofMonth);

  DateChange(firstDayofMonth, lastDayofMonth);
  TopBanners(firstDayofMonth, lastDayofMonth);

  console.log(firstDayofMonth, lastDayofMonth);
}
function SetInitialDate2() {
  var date = new Date(),
    y = date.getFullYear(),
    m = date.getMonth();
  var firstDay = new Date(y, m, 1);
  var lastDay = new Date(y, m + 1, 0);
  /////////////////
  var date = new Date(firstDay);
  var month_firstday =
    date.getMonth() > 8 ? date.getMonth() + 1 : "0" + (date.getMonth() + 1);
  var day_firstday = date.getDate() > 9 ? date.getDate() : "0" + date.getDate();
  var year_firstday = date.getFullYear();
  var firstDayofMonth =
    year_firstday + "-" + month_firstday + "-" + day_firstday;
  $(".from-date").val(firstDayofMonth);
  //////////////////
  var date2 = new Date(lastDay);
  var month_lastday =
    date2.getMonth() > 8 ? date2.getMonth() + 1 : "0" + (date2.getMonth() + 1);
  var day_lastday =
    date2.getDate() > 9 ? date2.getDate() : "0" + date2.getDate();
  var year_lastday = date2.getFullYear();
  var lastDayofMonth = year_lastday + "-" + month_lastday + "-" + day_lastday;
  $(".to-date").val(lastDayofMonth);
  DisplayOrders(firstDayofMonth, lastDayofMonth);
  //SelectStatus(firstDayofMonth, lastDayofMonth);
  //   DateChange(firstDayofMonth, lastDayofMonth);
  //   TopBanners(firstDayofMonth, lastDayofMonth);

  console.log(firstDayofMonth, lastDayofMonth);
}
function DataTable() {
  $("#dtBasicExample").DataTable();
  $(".dataTables_length").addClass("bs-select");
}
function DisplayOrders(fromdate, todate) {
  $.ajax({
    url: "process/page-seller-orders-DisplayOrders.php",
    method: "post",
    data: { fromdate: fromdate, todate: todate },
    success: function (data) {
      //console.log(data);
      $("#display-orders").html(data);
      DataTable();
    },
  });
}
function SelectStatus(fromdate, todate) {
  $(".select-status").on("change", function () {
    var value = this.value;
    console.log(value);
    $.ajax({
      url: "process/page-seller-orders-SelectStatus.php",
      method: "post",
      data: { value: value, fromdate: fromdate, todate: todate },
      success: function (data) {
        console.log(data);

        $("#display-orders").html(data);
        DataTable();
        //BtnTriggers();
        console.log(data);
        SetInitialDate();
      },
    });
  });
}

function DateChange(firstDayofMonth, lastDayofMonth) {
  var fromdate = firstDayofMonth;
  var todate = lastDayofMonth;
  $(".from-date").change(function () {
    fromdate = $(this).val();
    var value = $(".select-status").val();
    //TopBanners(fromdate, todate);
    $.ajax({
      url: "process/page-seller-orders-SelectStatus.php",
      method: "post",
      data: { value: value, fromdate: fromdate, todate: todate },
      success: function (data) {
        //console.log(data);

        $("#display-orders").html(data);
        TopBanners(fromdate, todate);
        DataTable();
      },
    });
    console.log(fromdate, todate, value);
  });
  $(".to-date").change(function () {
    todate = $(this).val();
    var value = $(".select-status").val();

    $.ajax({
      url: "process/page-seller-orders-SelectStatus.php",
      method: "post",
      data: { value: value, fromdate: fromdate, todate: todate },
      success: function (data) {
        //console.log(data);
        $("#display-orders").html(data);
        TopBanners(fromdate, todate);
        DataTable();
      },
    });
    console.log(fromdate, todate);
  });
}

function TopBanners(fromdate, todate) {
  $.ajax({
    url: "process/page-seller-orders-TopBanners.php",
    method: "post",
    data: { fromdate: fromdate, todate: todate },
    success: function (data) {
      $("#top-banners").html(data);
    },
  });
}
function ResetBtn() {
  $(document).on("click", "#reset-top-btn", function () {
    location.reload();
  });
}
function GoToInvoice() {
  $(document).on("click", "#invoice-btn", function () {
    var fromdate = $(".from-date").val();
    var todate = $(".to-date").val();
    console.log(fromdate, todate);
    window.location.href =
      "seller-invoice.php?fromdate=" + fromdate + "&todate=" + todate;
  });
}
