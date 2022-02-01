$(document).ready(function () {
  AddVoucher();
  DisplayVoucher();
  //$("#modal-voucher").modal("show");
  AddModalVoucher();
  ResetDate();
  DeactivateVoucher();
  ActivateVoucher();
  EditVoucher();
  SaveVoucher();
  DeleteModalShow();
  DeleteVoucher();
  //alert("aw");
});
function makeid(length) {
  var result = "";
  var characters = "ABCDEFGHJKLMNOPQRSTUVWXYZ0123456789";
  var charactersLength = characters.length;
  for (var i = 0; i < length; i++) {
    result += characters.charAt(Math.floor(Math.random() * charactersLength));
  }
  return result;
}
function ResetDate() {
  var now = new Date();

  var day = ("0" + now.getDate()).slice(-2);
  var month = ("0" + (now.getMonth() + 1)).slice(-2);
  var today = now.getFullYear() + "-" + month + "-" + day;

  var now2 = new Date();

  var day2 = ("0" + (now2.getDate() + 7)).slice(-2);
  var month2 = ("0" + (now2.getMonth() + 1)).slice(-2);
  var today2 = now2.getFullYear() + "-" + month2 + "-" + day2;
  console.log("aw");
  $("#start_date").val(today);
  console.log(today2);
  $("#exp_date").val(today2);
}
function AddVoucher() {
  $(document).on("click", "#add-address", function () {
    var vouchercode = makeid(5);
    $("#voucher-title").html("New Voucher");
    $("#modal-voucher").modal("show");
    $("#vouchercode").val(vouchercode);
    $("#add-modal-voucher").show();
    $("#save-modal-voucher").hide();

    //
    $("#vouchername").val("");
    $("#minamount").val("");
    $("#limit").val("");
    ResetDate();
    $("#amount").val("");
  });
}
function RandomVoucherCode() {
  var vouchercode = makeid(5);

  $("#vouchercode").val(vouchercode);
}

function DisplayVoucher() {
  $.ajax({
    url: "process/page-profile-voucher-displayvoucher.php",
    method: "post",
    data: {},
    success: function (data) {
      $("#display-address").html(data);
    },
  });
}
function AddModalVoucher() {
  $(document).on("click", "#add-modal-voucher", function () {
    var vouchername = $("#vouchername").val();
    var vouchercode = $("#vouchercode").val();
    var minamount = $("#minamount").val();
    var limit = $("#limit").val();
    var amount = $("#amount").val();
    var start_date = $("#start_date").val();
    var exp_date = $("#exp_date").val();
    var type = $("#type").val();
    var ispublish = $(".ispublish").is(":checked");
    var v_discount = "0";
    var v_less = "0";
    var v_shipping = "0";
    if (type == "1") {
      v_discount = amount;
    } else if (type == "2") {
      v_less = amount;
    } else {
      v_shipping = amount;
    }

    if (
      vouchername == "" ||
      vouchercode == "" ||
      minamount == "" ||
      limit == "" ||
      vouchername == "" ||
      amount == ""
    ) {
      alert("Please fill in the blank");
    } else {
      $.ajax({
        url: "process/page-profile-voucher-addvoucher.php",
        method: "post",
        data: {
          vouchername: vouchername,
          vouchercode: vouchercode,
          minamount: minamount,
          limit: limit,
          start_date: start_date,
          exp_date: exp_date,
          amount: amount,
          type: type,
          ispublish: ispublish,
          v_discount: v_discount,
          v_less: v_less,
          v_shipping: v_shipping,
        },
        success: function (data) {
          console.log(data);
          $("#vouchername").val("");
          $("#minamount").val("");
          $("#limit").val("");
          $("#amount").val("");
          DisplayVoucher();
          ResetDate();
          RandomVoucherCode();
          var thead = "Voucher";
          var ttext = "Voucher Added!";
          var tcolor = "success";
          Toast(thead, ttext, tcolor);
        },
      });
    }

    console.log(
      vouchername,
      vouchercode,
      minamount,
      limit,
      start_date,
      exp_date,
      amount,
      type,
      ispublish
    );
  });
}
function DeactivateVoucher() {
  $(document).on("click", "#deactivate-voucher", function () {
    var voucherid = $(this).val();
    $.ajax({
      url: "process/page-profile-voucher-deactivatevoucher.php",
      method: "post",
      data: { voucherid: voucherid },
      success: function (data) {
        console.log(data);

        DisplayVoucher();
        var thead = "Voucher";
        var ttext = "Voucher Deactivated!";
        var tcolor = "error";
        Toast(thead, ttext, tcolor);
      },
    });
    console.log(voucherid);
  });
}
function ActivateVoucher() {
  $(document).on("click", "#activate-voucher", function () {
    var voucherid = $(this).val();
    $.ajax({
      url: "process/page-profile-voucher-activatevoucher.php",
      method: "post",
      data: { voucherid: voucherid },
      success: function (data) {
        if (data == "1") {
          console.log(data);
          DisplayVoucher();
          var thead = "Voucher";
          var ttext = "Voucher Activated!";
          var tcolor = "success";
          Toast(thead, ttext, tcolor);
        } else {
          DisplayVoucher();
          var thead = "Voucher";
          var ttext =
            "You cant Activate it, because the Voucher is Expired! Please adjust the expiration date to activate this voucher.";
          var tcolor = "warning";
          Toast(thead, ttext, tcolor);
        }
      },
    });
    console.log(voucherid);
  });
}

function EditVoucher() {
  $(document).on("click", "#edit-voucher", function () {
    var voucherid = $(this).val();
    console.log(voucherid);
    $.ajax({
      url: "process/page-profile-voucher-editvoucher.php",
      method: "post",
      data: { voucherid: voucherid },
      success: function (res) {
        $("#voucher-title").html("Edit Voucher");
        $("#add-modal-voucher").hide();
        $("#save-modal-voucher").show();
        $("#modal-voucher").modal("show");
        /////////
        var data = JSON.parse(res);
        $("#vouchername").val(data.vouchername);
        $("#vouchercode").val(data.vouchercode);
        $("#minamount").val(data.minamount);
        $("#limit").val(data.limit);
        $("#start_date").val(data.start_date);
        $("#exp_date").val(data.exp_date);
        $("#amount").val(data.amount);
        $("#type").val(data.type);

        console.log(data);
      },
    });

    //
  });
}
function SaveVoucher() {
  $(document).on("click", "#save-modal-voucher", function () {
    var vouchername = $("#vouchername").val();
    var vouchercode = $("#vouchercode").val();
    var minamount = $("#minamount").val();
    var limit = $("#limit").val();
    var amount = $("#amount").val();
    var start_date = $("#start_date").val();
    var exp_date = $("#exp_date").val();
    var type = $("#type").val();
    var ispublish = $(".ispublish").is(":checked");
    var v_discount = "0";
    var v_less = "0";
    var v_shipping = "0";
    if (type == "1") {
      v_discount = amount;
    } else if (type == "2") {
      v_less = amount;
    } else {
      v_shipping = amount;
    }

    if (
      vouchername == "" ||
      vouchercode == "" ||
      minamount == "" ||
      limit == "" ||
      vouchername == "" ||
      amount == ""
    ) {
      alert("Please fill in the blank");
    }
    $.ajax({
      url: "process/page-profile-voucher-savevoucher.php",
      method: "post",
      data: {
        vouchername: vouchername,
        vouchercode: vouchercode,
        minamount: minamount,
        limit: limit,
        start_date: start_date,
        exp_date: exp_date,
        amount: amount,
        type: type,
        ispublish: ispublish,
        v_discount: v_discount,
        v_less: v_less,
        v_shipping: v_shipping,
      },
      success: function (data) {
        console.log(data);
        DisplayVoucher();
        var thead = "Voucher";
        var ttext = "Voucher Updated!";
        var tcolor = "success";
        Toast(thead, ttext, tcolor);
        $("#modal-voucher").modal("hide");
      },
    });
  });
}
function DeleteModalShow() {
  $(document).on("click", "#delete-modal-voucher", function () {
    var voucherid = $(this).val();
    $("#delete-prompt-voucher").modal("show");
    $("#delete-voucher").val(voucherid);
  });
}
function DeleteVoucher(voucherid_set) {
  $(document).on("click", "#delete-voucher", function () {
    var voucherid = $(this).val();
    $.ajax({
      url: "process/page-profile-voucher-deletevoucher.php",
      method: "post",
      data: { voucherid: voucherid },
      success: function (data) {
        console.log(data);
        var counter = 0;
        var interval = setInterval(function () {
          counter++;
          $("#please-wait").html("Wait  ");
          if (counter == 1) {
            var thead = "Voucher";
            var ttext = "Voucher Deleted!";
            var tcolor = "error";
            Toast(thead, ttext, tcolor);
            $("#delete-prompt-voucher").modal("hide");
          }
          if (counter == 4) {
            DisplayVoucher();
            $("#please-wait").html("Yes");

            clearInterval(interval);
          }
        }, 1000);
      },
    });
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
