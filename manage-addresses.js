$(document).ready(function () {
  AddAddress();
  AddModalAddress();
  DisplayAddress();
  MakeDefault();
  //DeleteAddress();
  DeleteModalAddress();
  EditAddress();
  SaveAddress();
  //alert('aw');
  //$("#modal-address").modal("show");
});

function AddAddress() {
  $(document).on("click", "#add-address", function () {
    $("#modal-address").modal("show");
  });
}
function AddModalAddress() {
  $(document).on("click", "#add-modal-address", function () {
    var fullname = $("#fullname").val();
    var contact = $("#contact").val();
    var address1 = $("#address1").val();
    var postalcode = $("#postalcode").val();
    var address2 = $("#address2").val();
    var isDefault = 0;
    var work = 0;
    var home = 0;
    if ($(".isDefault").is(":checked")) {
      isDefault = 1;
    }
    if ($(".home").is(":checked")) {
      home = 1;
    }
    if ($(".work").is(":checked")) {
      work = 1;
    }

    console.log(
      "Add Address" + fullname,
      contact,
      address1,
      postalcode,
      address2,
      isDefault,
      home,
      work
    );
    $.ajax({
      url: "process/page-profile-address-addmodaladdress.php",
      method: "post",
      data: {
        fullname: fullname,
        contact: contact,
        address1: address1,
        postalcode: postalcode,
        address2: address2,
        isDefault: isDefault,
        home: home,
        work: work,
      },
      success: function (data) {
        if (
          fullname == "" ||
          contact == "" ||
          address1 == "" ||
          address2 == ""
        ) {
          var thead = "Address";
          var ttext = "Please Fill the Blankss!";
          var tcolor = "error";
          Toast(thead, ttext, tcolor);
        } else if (home == 0 && work == 0) {
          //alert("Choose Home or Work!");
          var thead = "Address";
          var ttext = "Choose Home or Work!";
          var tcolor = "error";
          Toast(thead, ttext, tcolor);
        } else {
          $("#modal-address").modal("hide");
          DisplayAddress();
          var thead = "Address";
          var ttext = "Address Added";
          var tcolor = "success";
          Toast(thead, ttext, tcolor);
        }
      },
    });
  });
}

function DisplayAddress() {
  $.ajax({
    url: "process/page-profile-address-displayaddress.php",
    method: "post",
    data: {},
    success: function (data) {
      $("#display-address").html(data);
    },
  });
}

function MakeDefault() {
  $(document).on("click", "#make-default", function () {
    var addressid = $(this).val();
    $.ajax({
      url: "process/page-profile-address-makedefault.php",
      method: "post",
      data: { addressid: addressid },
      success: function (data) {
        console.log(data);
        DisplayAddress();
        var thead = "Address";
        var ttext = "Address Default";
        var tcolor = "success";
        Toast(thead, ttext, tcolor);
      },
    });
  });
}

function DeleteAddress() {
  $(document).on("click", "#delete-address", function () {
    var addressid = $(this).val();

    console.log(addressid);
    $.ajax({
      url: "process/page-profile-address-deleteaddress.php",
      method: "post",
      data: { addressid: addressid },
      success: function (data) {
        //$("#modal-address").modal("hide");
        var thead = "Address";
        var ttext = "Address Deleted";
        var tcolor = "error";
        Toast(thead, ttext, tcolor);
        var counter = 0;
        var interval = setInterval(function () {
          counter++;
          // Display 'counter' wherever you want to display it.
          if (counter == 3) {
            // Display a login box
            DisplayAddress();
            location.href = "page-profile-address.php";
            clearInterval(interval);
          }
        }, 1000);
      },
    });
  });
}
function DeleteModalAddress() {
  //yes
  $(document).on("click", "#delete-modal-address", function () {
    $("#delete-prompt-address").modal("show");
    DeleteAddress();
  });
  //no
}

function EditAddress() {
  $(document).on("click", "#edit-address", function () {
    var addressid = $(this).val();

    console.log(addressid);
    $.ajax({
      url: "process/page-profile-address-editaddress.php",
      method: "post",
      data: { addressid: addressid },
      success: function (data) {
        //$("#edit-modal-show").html(data);
        //$("#edit-modal-show").modal("show");
        $("." + addressid).html(data);
      },
    });
  });
}
function SaveAddress() {
  $(document).on("click", "#save-address", function () {
    var addressid = $(this).val();
    var fullname = $("#fullname" + addressid).val();
    var contact = $("#contact" + addressid).val();
    var address1 = $("#address1" + addressid).val();
    var address2 = $("#address2" + addressid).val();
    var postalcode = $("#postalcode" + addressid).val();
    var label = $("#label" + addressid).val();

    $.ajax({
      url: "process/page-profile-address-saveaddress.php",
      method: "post",
      data: {
        addressid: addressid,
        fullname: fullname,
        contact: contact,
        address1: address1,
        address2: address2,
        postalcode: postalcode,
        label: label,
      },
      success: function (data) {
        console.log(data);
        //DisplayAddress();
        DisplaySingleAddress(addressid);
        var thead = "Address";
        var ttext = "Address Updated";
        var tcolor = "success";
        Toast(thead, ttext, tcolor);
      },
    });
  });
}
function DisplaySingleAddress(addressid) {
  $.ajax({
    url: "process/page-profile-address-displaysingleaddress.php",
    method: "post",
    data: { addressid: addressid },
    success: function (data) {
      $("." + addressid).html(data);
    },
  });
}
/*

*/
function Toast(thead, ttext, tcolor) {
  $.toast({
    heading: thead,
    text: ttext,
    showHideTransition: "slide",
    icon: tcolor,
    position: "top-center",
  });
}
