$(document).ready(function () {
  //copy1
  $("#tooltip-cart").tooltip("dispose");
  DisplaySideCart();
  DeleteSideCart();
  SideCartCount();
  CheckOut();
  $("#set-countnotif").hide();
  $("#set-countnotif2").hide();

  PopulateNotif_mobile();
  ToggleNotifMobile();
  //copy1
  //alert("aw");
  ToggleNotif();
  AutoRefresh_Notif();
  FillDataList();
});

//copy1
function DisplaySideCart() {
  $.ajax({
    url: "process/top-displaysidecart.php",
    method: "",
    data: {},
    success: function (data) {
      $("#side-cart").html(data);
    },
  });
}
function DeleteSideCart() {
  $(document).on("click", "#delete-side-cart", function () {
    var userorderid = $(this).val();
    $.ajax({
      url: "process/top-deletesidecart.php",
      method: "post",
      data: { userorderid: userorderid },
      success: function (data) {
        DisplaySideCart();
        SideCartCount();
        //NotifCart("Successfully Deleted!");
        var thead = "Cart";
        var ttext = "Order has been successfully deleted";
        var tcolor = "error";
        Toast(thead, ttext, tcolor);
        console.log(data);
      },
    });
  });
}
function CheckOut() {
  $(document).on("click", "#check-out", function () {
    window.location.href = "page-payment.php";
  });
}
function SideCartCount() {
  $.ajax({
    url: "process/top-sidecartcount.php",
    method: "post",
    data: {},
    success: function (data) {
      $("#minicart").html(data);
      $("#minicart_mobile").html(data);
      console.log(data);
    },
  });
}
function NotifCart(notifval) {
  $("#tooltip-cart").attr("title", notifval);
  $("#tooltip-cart").tooltip("show");
  var counter = 0;
  var interval = setInterval(function () {
    counter++;
    if (counter == 5) {
      $("#tooltip-cart").tooltip("hide");
      $("#tooltip-cart").tooltip("dispose");

      clearInterval(interval);
    }
  }, 1000);
}
//copy1

function ToggleNotif() {
  $(".notification-drop .item").on("click", function () {
    $(this).find("ul").toggle();
    $.ajax({
      url: "process/top-updateReadNotif.php",
      method: "post",
      data: {},
      success: function (data) {
        PopulateNotif();
        PopCountNotif();
      },
    });
  });
}
function ToggleNotifMobile() {
  $("#notif-btn-mobile").on("click", function () {
    $.ajax({
      url: "process/top-updateReadNotif.php",
      method: "post",
      data: {},
      success: function (data) {
        PopulateNotif_mobile();
        PopCountNotif();
      },
    });
  });
}
function PopulateNotif() {
  $.ajax({
    url: "process/top-PopulateNotif.php",
    method: "post",
    data: {},
    success: function (data) {
      $("#top-notification").html(data);
      $("#top-notification-mobile").html(data);
    },
  });
}
function PopulateNotif_mobile() {
  $.ajax({
    url: "process/top-PopulateNotif.php",
    method: "post",
    data: {},
    success: function (data) {
      $("#top-notification-mobile").html(data);
    },
  });
}
function PopCountNotif() {
  $.ajax({
    url: "process/top-PopCountNotif.php",
    method: "post",
    data: {},
    success: function (data) {
      if (data != "0") {
        $("#set-countnotif").html(data);
        $("#set-countnotif").show();

        $("#set-countnotif2").html(data);
        $("#set-countnotif2").show();
        //console.log("Notif");
      } else {
        //$("#set-countnotif").remove();
        $("#set-countnotif").hide();

        $("#set-countnotif2").hide();
        //console.log("No Notif");
      }
    },
  });
}
function AutoRefresh_Notif() {
  $(document).ready(function () {
    var count = 0;
    setInterval(function () {
      console.log(count++);
      PopCountNotif();
    }, 1500);
  });
}
function FillDataList() {
  $.ajax({
    url: "process/top-FillDataList.php",
    method: "post",
    data: {},
    success: function (data) {
      $("#filldlist").html(data);
      console.log(data);
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
