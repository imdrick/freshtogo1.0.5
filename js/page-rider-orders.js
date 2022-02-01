$(document).ready(function () {
  //alert("Aw");
  DataTable();
  DisplayOrders();
  ApplyQueuing();
  //DisplayOrders_Modal();
  ActionModal();
  Update_Delete();
  Update_Return();
  Update_Prepare();
  Update_ToDeliver();
  Update_Delivered();
  ViewOrders();
  ResetBtn();
  SetInitialDate();
  BtnTriggers("null");
  GoToInvoice();
  //
});

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
  SelectStatus(firstDayofMonth, lastDayofMonth);
  DateChange(firstDayofMonth, lastDayofMonth);
  TopBanners(firstDayofMonth, lastDayofMonth);
}
function DataTable() {
  $("#dtBasicExample").DataTable();
  $(".dataTables_length").addClass("bs-select");
}
function DisplayOrders() {
  $.ajax({
    url: "process/page-rider-orders-DisplayOrders.php",
    method: "post",
    success: function (data) {
      //console.log(data);
      $("#display-orders").html(data);
      DataTable();
    },
  });
}

function ApplyQueuing() {
  $(document).on("click", "#accept-order", function () {
    $.ajax({
      url: "process/page-rider-orders-ApplyQueuing.php",
      method: "post",
      success: function (data) {
        console.log(data);
        DisplayOrders();
        DataTable();
        BtnTriggers("null");
        var thead = "Login";
        var ttext = "Order#: " + data + " has been Accepted";
        var tcolor = "success";
        Toast(thead, ttext, tcolor);
      },
    });
  });
}

function DisplayOrders_Modal(masterlistid) {
  $.ajax({
    url: "process/page-rider-order-DisplayOrders_Modal.php",
    method: "post",
    data: { masterlistid: masterlistid },
    success: function (data) {
      $("#display-orders_modal").html(data);
      $("#action-modal").modal("show");
    },
  });
}

function ActionModal() {
  $(document).on("click", "#action-modal1", function () {
    var masterlistid = $(this).val();
    DisplayOrders_Modal(masterlistid);
    BtnTriggers(masterlistid);
    console.log(masterlistid);
  });
}
//cancel order
function Update_Delete() {
  $(document).on("click", "#cancel-order", function () {
    var masterpoid = $("#modal-masterlistid").text();
    if (masterpoid == "") {
      masterpoid = $("#modal-masterlistid2").text();
    }
    var remarks = $("#remarks-modal").val();
    ///
    var fromdate = $(".from-date").val();
    var todate = $(".to-date").val();
    if (remarks == "") {
      //alert("Please Put your reason to the remarks");
      var thead = "Order";
      var ttext = "Please put the reason in the field ''Remarks''";
      var tcolor = "error";
      Toast(thead, ttext, tcolor);
    } else {
      $.ajax({
        url: "process/page-rider-order-Update_Delete.php",
        method: "post",
        data: { masterpoid: masterpoid, remarks: remarks },
        success: function (data) {
          console.log(data, remarks);
          $("#action-modal").modal("hide");
          //alert("Cancelled Successfully!");
          DisplayOrders();
          TopBanners(fromdate, todate);
          BtnTriggers("null");
          var thead = "Order";
          var ttext = "Order has been ''Cancelled''";
          var tcolor = "error";
          Toast(thead, ttext, tcolor);
        },
      });
    }
  });
}
///
function Update_Return() {
  $(document).on("click", "#return-order", function () {
    var masterpoid = $("#modal-masterlistid").text();
    var remarks = $("#remarks-modal").val();
    var fromdate = $(".from-date").val();
    var todate = $(".to-date").val();
    if (masterpoid == "") {
      masterpoid = $("#modal-masterlistid2").text();
    }
    if (remarks == "") {
      //alert("Please Put your reason to the remarks");
      var thead = "Order";
      var ttext = "Please put the reason in the field ''Remarks''";
      var tcolor = "error";
      Toast(thead, ttext, tcolor);
    } else {
      $.ajax({
        url: "process/page-rider-order-Update_Return.php",
        method: "post",
        data: { masterpoid: masterpoid, remarks: remarks },
        success: function (data) {
          console.log(data, remarks);
          $("#action-modal").modal("hide");
          //alert("Returned Successfully!");
          DisplayOrders();
          TopBanners(fromdate, todate);
          BtnTriggers("null");
          var thead = "Login";
          var ttext = "Order has been ''Returned''";
          var tcolor = "error";
          Toast(thead, ttext, tcolor);
        },
      });
    }
  });
}
function Update_Prepare() {
  $(document).on("click", "#prepare-order", function () {
    var masterpoid = $("#modal-masterlistid").text();
    ///////////
    var masterpoid_2 = $("#modal-masterpoid").text();
    ///////////////
    var remarks = $("#remarks-modal").val();
    if (masterpoid == "") {
      masterpoid = $("#modal-masterlistid2").text();
    }
    $.ajax({
      url: "process/page-rider-order-Update_Prepare.php",
      method: "post",
      data: {
        masterpoid: masterpoid,
        remarks: remarks,
        masterpoid_2: masterpoid_2,
      },
      success: function (data) {
        console.log(data, remarks);
        $("#action-modal").modal("hide");
        //alert("Preparing Successfully!");
        DisplayOrders();
        BtnTriggers("null");
        var thead = "Order";
        var ttext = "Order has been ''Preparing''";
        var tcolor = "info";
        Toast(thead, ttext, tcolor);
      },
    });
  });
}
function Update_ToDeliver() {
  $(document).on("click", "#todeliver-order", function () {
    var masterpoid = $("#modal-masterlistid").text();
    var remarks = $("#remarks-modal").val();
    if (masterpoid == "") {
      masterpoid = $("#modal-masterlistid2").text();
    }
    $.ajax({
      url: "process/page-rider-order-Update_ToDeliver.php",
      method: "post",
      data: { masterpoid: masterpoid, remarks: remarks },
      success: function (data) {
        console.log(data, remarks);
        $("#action-modal").modal("hide");
        //alert("To Deliver Successfully!");
        DisplayOrders();
        BtnTriggers("null");
        var thead = "Order";
        var ttext = "Order has been set ''To Deliver''";
        var tcolor = "info";
        Toast(thead, ttext, tcolor);
      },
    });
  });
}
function Update_Delivered() {
  $(document).on("click", "#delivered-order", function () {
    var masterpoid = $("#modal-masterlistid").text();
    var remarks = $("#remarks-modal").val();
    var fromdate = $(".from-date").val();
    var todate = $(".to-date").val();
    if (masterpoid == "") {
      masterpoid = $("#modal-masterlistid2").text();
    }
    $.ajax({
      url: "process/page-rider-order-Update_Delivered.php",
      method: "post",
      data: { masterpoid: masterpoid, remarks: remarks },
      success: function (data) {
        console.log(data, remarks);
        $("#action-modal").modal("hide");
        //alert("Delivered Successfully!");
        DisplayOrders();
        TopBanners(fromdate, todate);
        BtnTriggers("null");
        var thead = "Order";
        var ttext = "Order has been ''Delivered''";
        var tcolor = "success";
        Toast(thead, ttext, tcolor);
      },
    });
  });
}

function ViewOrders() {
  $(document).on("click", "#view-orders", function () {
    $.ajax({
      url: "process/page-rider-orders-ViewOrders.php",
      method: "post",
      success: function (data) {
        //console.log(data);
        $("#display-orders").html(data);
        DataTable();
      },
    });
  });
}
/////////////////
function SelectStatus(fromdate, todate) {
  $(".select-status").on("change", function () {
    var value = this.value;

    $.ajax({
      url: "process/page-rider-orders-SelectStatus.php",
      method: "post",
      data: { value: value, fromdate: fromdate, todate: todate },
      success: function (data) {
        //console.log(data);
        SetInitialDate();
        $("#display-orders").html(data);
        DataTable();
        BtnTriggers("null");
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
    TopBanners(fromdate, todate);
    if (value == "Accepted Order" || value == "1") {
      alert("Accepted Order: Cant change the date");
    } else {
      $.ajax({
        url: "process/page-rider-orders-SelectStatus.php",
        method: "post",
        data: { value: value, fromdate: fromdate, todate: todate },
        success: function (data) {
          //console.log(data);

          $("#display-orders").html(data);

          DataTable();
        },
      });
      console.log(fromdate, todate, value);
    }
  });
  $(".to-date").change(function () {
    todate = $(this).val();
    var value = $(".select-status").val();
    if (value == "Accepted Order" || value == "1") {
      alert("Accepted Order: Cant change the date");
    } else {
      $.ajax({
        url: "process/page-rider-orders-SelectStatus.php",
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
    }
  });
}
function ResetBtn() {
  $(document).on("click", "#reset-top-btn", function () {
    location.reload();
  });
}
function TopBanners(fromdate, todate) {
  $.ajax({
    url: "process/page-rider-orders-TopBanners.php",
    method: "post",
    data: { fromdate: fromdate, todate: todate },
    success: function (data) {
      $("#top-banners").html(data);
    },
  });
}
function BtnTriggers(masterlistid) {
  if (masterlistid == "") {
    masterlistid = "null";
  }
  $.ajax({
    url: "process/page-rider-orders-BtnTriggers.php",
    method: "post",
    data: { masterlistid: masterlistid },
    success: function (data) {
      console.log(data);
      $("#display-triggers").html(data);
      $("#display-triggers2").html(data);
    },
  });
}

function Toast(thead, ttext, tcolor) {
  $.toast({
    heading: thead,
    text: ttext,
    showHideTransition: "slide",
    icon: tcolor,
    position: "top-center",
  });
}
function GoToInvoice() {
  $(document).on("click", "#invoice-btn", function () {
    var fromdate = $(".from-date").val();
    var todate = $(".to-date").val();
    console.log(fromdate, todate);
    window.location.href =
      "rider-invoice.php?fromdate=" + fromdate + "&todate=" + todate;
  });
}
