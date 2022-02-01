$(document).ready(function () {
  ConfrimPayment();
  FillInfo();
  RefillInfo();
  DisplayTotal();
  //alert("aw")
  KapoyScrollUp();
  FiveLogicsCombined();
});
function makeid(length) {
  var result = "";
  var characters =
    "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
  var charactersLength = characters.length;
  for (var i = 0; i < length; i++) {
    result += characters.charAt(Math.floor(Math.random() * charactersLength));
  }
  return result;
}

function ConfrimPayment() {
  $(document).on("click", "#confirm-payment", function () {
    var addressid = $("#addressid").val();
    var fullname = $("#fullname").val();
    var contact = $("#contact").val();
    var address1 = $("#address1").val();
    var postalcode = $("#postalcode").val();
    var address2 = $("#address2").val();
    var label = $("#label").val();
    var total = $("#span-total").text();
    var shippingfee = $("#span-shippingfee").text();
    var nettotal = $("#span-nettotal").text();
    var discount = $("#span-discount").text();
    var subtotal = $("#span-subtotal").text();
    var deliverybox = $("#deliverybox").val();
    var mop2 = $("#span-mop").text();
    var masterpoid = "f2g-" + makeid(17);
    var mop = "";
    if (
      fullname == "" ||
      contact == "" ||
      fullname == "" ||
      contact == "" ||
      address1 == "" ||
      contact == ""
    ) {
      //alert("Please Fill in the Blanks");
      var thead = "Payment";
      var ttext = "Please fill all the fields to complete the payment process";
      var tcolor = "error";
      Toast(thead, ttext, tcolor);
    } else {
      if ($("#mop1").is(":checked")) {
        //var mop1 = $("#mop1").val();
        mop = 0;
      } else if ($("#mop2").is(":checked")) {
        //var mop1 = $("#mop1").val();
        mop = 2;
      } else if ($("#mop3").is(":checked")) {
        //var mop1 = $("#mop1").val();
        mop = 3;
      } else if ($("#mop4").is(":checked")) {
        //var mop1 = $("#mop1").val();
        mop = 4;
      }
      $.ajax({
        url: "process/page-payment-confirmpayment.php",
        method: "post",
        data: {
          addressid: addressid,
          fullname: fullname,
          contact: contact,
          address1: address1,
          postalcode: postalcode,
          address2: address2,
          label: label,
          total: total,
          masterpoid: masterpoid,
          shippingfee: shippingfee,
          mop: mop,
          mop2: mop2,
          subtotal: subtotal,
          discount: discount,
          nettotal: nettotal,
          deliverybox: deliverybox,
        },
        success: function (data) {
          //
          HideConfirmOrder();
          FiveLogicsCombined();
          //alert(data);
          if (data == "Confirmed Payment") {
            var counter = 0;
            var counter_reverse = 3;
            var thead = "Payment";
            var ttext =
              "Order has been successfully confirmed! Redirecting to ''My Orders'' Menu in" +
              " <span id='gotoorder-5s'>3</span>s";
            var tcolor = "success";
            Toast(thead, ttext, tcolor);
            var interval = setInterval(function () {
              counter++;
              counter_reverse--;
              // Display 'counter' wherever you want to display it.
              $("#gotoorder-5s").text(counter_reverse);
              if (counter == 3) {
                // Display a login box

                location.href = "page-profile-orders.php";
                clearInterval(interval);
              }
            }, 1000);
          }
        },
      });
    }
  });
}
function FillInfo() {
  $.ajax({
    url: "process/page-payment-fillinfo.php",
    method: "post",
    data: {},
    success: function (data) {
      $("#fill-info").html(data);
      console.log($("#label-work").val());
    },
  });
}
function RefillInfo() {
  $("#addressid").change(function () {
    var addressid = $(this).val();
    $.ajax({
      url: "process/page-payment-refillinfo.php",
      method: "post",
      data: { addressid: addressid },
      success: function (data) {
        var res = JSON.parse(data);
        $("#fullname").val(res.fullname);
        $("#contact").val(res.contact);
        $("#address1").val(res.address1);
        $("#postalcode").val(res.postalcode);
        $("#address2").val(res.address2);
        var label = res.label;
        $("#label-" + label).attr("selected", "selected");
        console.log(label);
      },
    });
  });
}

function DisplayTotal() {
  $.ajax({
    url: "process/page-payment-displaytotal.php",
    method: "post",
    data: {},
    success: function (data) {
      $("#display-total").html(data);
    },
  });
}

function HideConfirmOrder() {
  $.ajax({
    url: "process/page-payment-HideConfirmOrder.php",
    method: "post",
    data: {},
    success: function (data) {
      if (data == "hide") {
        $("#display-total").html(
          '<span class="text-center">No Orders, back to <a href="v2-home.php" class="text-primary">Home</a></span>'
        );
        $("#confirm-payment").css("display", "none");
        // location.href = "page-profile-orders.php";
      } else {
        DisplayTotal();
      }
    },
  });
}

///////////voucher
function ApplyVoucher() {
  $(document).on("click", "#apply-voucher", function () {
    var vouchercode = $("#voucher-code").val();
    if (vouchercode == "") {
      var thead = "Voucher";
      var ttext = "Empty Voucher!";
      var tcolor = "warning";
      Toast(thead, ttext, tcolor);
    } else {
      $.ajax({
        url: "process/page-payment-applyvoucher.php",
        method: "post",
        data: { vouchercode: vouchercode },
        success: function (data) {
          //alert(data);
          if (data == "Invalid Voucher") {
            var thead = "Voucher";
            var ttext = "Invalid Voucher!";
            var tcolor = "error";
            Toast(thead, ttext, tcolor);
          } else if (data == "Voucher Applied") {
            var thead = "Voucher";
            var ttext = "Voucher Applied!";
            var tcolor = "success";
            Toast(thead, ttext, tcolor);
          } else if (data == "You already used this Voucher Code..") {
            var thead = "Voucher";
            var ttext = "You already used this Voucher Code..";
            var tcolor = "error";
            Toast(thead, ttext, tcolor);
          } else if (data == "Voucher is Expired") {
            var thead = "Voucher1";
            var ttext = "Voucher is Expired.";
            var tcolor = "error";
            Toast(thead, ttext, tcolor);
          } else if (data == "Voucher is Fully Applied") {
            var thead = "Voucher";
            var ttext = "Voucher reached its limit.";
            var tcolor = "error";
            Toast(thead, ttext, tcolor);
          } else {
            var thead = "Voucher";
            var ttext = "Voucher is Inactive.";
            var tcolor = "error";
            Toast(thead, ttext, tcolor);
          }

          DisplayAppliedVouchers();
          $("#voucher-code").val("");
          FiveLogicsCombined();
        },
      });
    }
  });
}

function DisplayAppliedVouchers() {
  $.ajax({
    url: "process/page-payment-DisplayAppliedVouchers.php",
    method: "post",
    data: {},
    success: function (data) {
      $("#fill-applied-voucher").html(data);
      console.log();
    },
  });
}

function DeleteAppliedVoucher() {
  $(document).on("click", "#delete-applied", function () {
    var voucherappliedid = $(this).val();
    $.ajax({
      url: "process/page-payment-DeleteAppliedVoucher.php",
      method: "post",
      data: { voucherappliedid: voucherappliedid },
      success: function (data) {
        console.log(data);
        var thead = "Voucher";
        var ttext = "Voucher Deleted!";
        var tcolor = "error";
        Toast(thead, ttext, tcolor);
        DisplayAppliedVouchers();
        FiveLogicsCombined();
      },
    });
  });
}

function FiveLogicsCombined() {
  $.ajax({
    url: "process/page-payment-FiveLogicsCombined.php",
    method: "post",
    data: {},
    success: function (data) {
      console.log(data);
      $("#logic5").html(data);
    },
  });
}

function DeliveryBox() {
  $(document).on("change", "#deliverybox", function () {
    var deliveryboxstr = $(this).val();
    var shippingfee_ = parseInt($("#span-shippingfee-reset").html());
    var nettotal_ = parseInt($("#span-nettotal-reset").html());
    if (deliveryboxstr == "1") {
      $("#span-shippingfee").html("" + (shippingfee_ + 10) + "");
      $("#span-nettotal").html("" + (nettotal_ + 10) + "");
    } else if (deliveryboxstr == "2") {
      $("#span-shippingfee").html("" + (shippingfee_ + 15) + "");
      $("#span-nettotal").html("" + (nettotal_ + 15) + "");
    } else {
      $("#span-shippingfee").html("" + shippingfee_ + "");
      $("#span-nettotal").html("" + nettotal_ + "");
    }
  });
}

function KapoyScrollUp() {
  HideConfirmOrder();
  ApplyVoucher();
  DisplayAppliedVouchers();
  DeleteAppliedVoucher();
  DeliveryBox();
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
