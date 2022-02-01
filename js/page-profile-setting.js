$(document).ready(function () {
  //test drive
  DisplaySellerInfo();
  ClickIconCover();
  ClickIconProfile();
  //alert("aw");
  OnChangeProfile();
  OnChangCover();
  //////////////
  SaveProfileSettings();
  BtnToggle();
  //seller
  NotSellerBtn();
  SetUpSeller();
  //rider
  NotRiderBtn();
  SetUpRider();
});
//time
let a = [
  { day: "numeric" },
  { month: "numeric" },
  { year: "numeric" },
  { minute: "numeric" },
  { second: "numeric" },
];
let s = join(new Date(), a, "");
function join(t, a, s) {
  function format(m) {
    let f = new Intl.DateTimeFormat("en", m);
    return f.format(t);
  }
  return a.map(format).join(s);
}
//random str
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

function DisplaySellerInfo() {
  $.ajax({
    url: "process/page-profile-setting-display-seller-info.php",
    method: "post",
    success: function (data) {
      $("#form-seller-info").html(data);
    },
  });
}
function ClickIconProfile() {
  $(document).on("click", "#profile-picture", function () {
    $("#input-profile").trigger("click");
  });
}
function ClickIconCover() {
  $(document).on("click", "#profile-cover", function () {
    $("#input-cover").trigger("click");
  });
}

function OnChangeProfile() {
  $(document).on("change", "#input-profile", function () {
    var str = $("#input-profile").val();
    //var str2 = $("#input-cover").val();
    // $.ajax({
    //   url: "process/page-profile-setting-onchangeprofile.php",
    //   method: "post",
    //   success: function (data) {
    //     console.log(data);
    //   },
    // });

    //
    var name = document.getElementById("input-profile").files[0].name;
    var form_data = new FormData();
    var ext = name.split(".").pop().toLowerCase();

    if (jQuery.inArray(ext, ["gif", "png", "jpg", "jpeg"]) == -1) {
      alert("Invalid Image File");
    } else {
      var oFReader = new FileReader();
      oFReader.readAsDataURL(document.getElementById("input-profile").files[0]);
      var f = document.getElementById("input-profile").files[0];
      var fsize = f.size || f.fileSize;
      if (fsize > 2000000) {
        alert("Image File Size is very big");
      } else {
        form_data.append(
          "input-profile",
          document.getElementById("input-profile").files[0]
        );
        $.ajax({
          url:
            "process/page-profile-setting-onchangeprofile.php?name=" +
            s +
            "-" +
            makeid(4),
          method: "POST",
          data: form_data,
          contentType: false,
          cache: false,
          processData: false,
          beforeSend: function () {
            $("#loading").text("loading");
          },
          success: function (data) {
            DisplaySellerInfo();
            console.log(data);
          },
        });
      }
    }

    //
  });
}
function OnChangCover() {
  $(document).on("change", "#input-cover", function () {
    var str = $("#input-cover").val();
    //var str2 = $("#input-cover").val();
    // $.ajax({
    //   url: "process/page-profile-setting-onchangeprofile.php",
    //   method: "post",
    //   success: function (data) {
    //     console.log(data);
    //   },
    // });

    //
    var name = document.getElementById("input-cover").files[0].name;
    var form_data = new FormData();
    var ext = name.split(".").pop().toLowerCase();

    if (jQuery.inArray(ext, ["gif", "png", "jpg", "jpeg"]) == -1) {
      alert("Invalid Image File");
    } else {
      var oFReader = new FileReader();
      oFReader.readAsDataURL(document.getElementById("input-cover").files[0]);
      var f = document.getElementById("input-cover").files[0];
      var fsize = f.size || f.fileSize;
      if (fsize > 2000000) {
        alert("Image File Size is very big");
      } else {
        form_data.append(
          "input-cover",
          document.getElementById("input-cover").files[0]
        );
        $.ajax({
          url:
            "process/page-profile-setting-onchangecover.php?name=" +
            s +
            "-" +
            makeid(4),
          method: "POST",
          data: form_data,
          contentType: false,
          cache: false,
          processData: false,
          beforeSend: function () {
            $("#loading").text("loading");
          },
          success: function (data) {
            DisplaySellerInfo();
            console.log(data);
          },
        });
      }
    }

    //
  });
}
/////////////////update////////////
function SaveProfileSettings() {
  $(document).on("click", "#save-button", function () {
    var firstname = $("#firstname").val();
    var lastname = $("#lastname").val();
    var contact = $("#contact").val();
    var email = $("#email").val();

    $.ajax({
      url: "process/page-profile-settings-saveprofilesettings.php",
      method: "post",
      data: {
        firstname: firstname,
        lastname: lastname,
        contact: contact,
        email: email,
      },
      success: function (data) {
        console.log(data);
        var thead = "Voucher";
        var ttext = "Product Updated!";
        var tcolor = "success";
        Toast(thead, ttext, tcolor);
      },
    });
  });
}
/////////////////.update////////////

//////////////////seller////////
function BtnToggle() {
  SellerBtn();
  RiderBtn();
}
function SellerBtn() {
  $(document).on("click", "#seller-btn", function () {
    if ($("#buyer-card").is(":visible") || $("#rider-card").is(":visible")) {
      if ($("#rider-card").is(":visible")) {
        $("#rider-card").slideToggle("4000", "swing", function () {});
        $("#seller-card").slideToggle("4000", "swing", function () {});
        $("#rider-btn").removeClass("btn-info").addClass("btn-danger");
        $("#seller-btn").text("Buyer");
      }
      if ($("#buyer-card").is(":visible")) {
        $("#seller-card").slideToggle("4000", "swing", function () {});
        $("#buyer-card").slideToggle("4000", "swing", function () {});
        $("#seller-btn").text("Buyer");
      }
      $("#seller-btn").removeClass("btn-primary").addClass("btn-info");
      $("#rider-btn").text("Rider");
      console.log("off");
    } else {
      console.log("on");
      $("#seller-btn").removeClass("btn-info").addClass("btn-primary");

      $("#buyer-card").slideToggle("4000", "swing", function () {});
      $("#seller-card").slideToggle("4000", "swing", function () {});

      $("#seller-btn").removeClass("btn-outline-dark").addClass("btn-primary ");
      $("#seller-btn").text("Seller");
    }
  });
}
function NotSellerBtn() {
  $(document).on("click", "#notseller-btn", function () {
    $("#seller-modal").modal("show");
  });
}
function SetUpSeller() {
  $(document).on("click", "#setup-seller", function () {
    var sellerstorename = $("#sellerstorename").val();
    var sellercontact = $("#sellercontact").val();
    var selleremail = $("#selleremail").val();
    var selleraccountname = $("#selleraccountname").val();
    var sellerbankname = $("#sellerbankname").val();
    var selleraccountnumber = $("#selleraccountnumber").val();
    var sellertin = $("#sellertin").val();
    var sellergovid = $("#sellergovid").val();
    $.ajax({
      url: "process/page-profile-settings-setupseller.php",
      method: "post",
      data: {
        sellerstorename: sellerstorename,
        sellercontact: sellercontact,
        selleremail: selleremail,
        selleraccountname: selleraccountname,
        sellerbankname: sellerbankname,
        selleraccountnumber: selleraccountnumber,
        sellertin: sellertin,
        sellergovid: sellergovid,
      },
      success: function (data) {
        DisplaySellerInfo();
        console.log(data);
        $("#seller-modal").modal("hide");
      },
    });
  });
}
//////////////////seller////////
//////////////////rider////////
function RiderBtn() {
  $(document).on("click", "#rider-btn", function () {
    if ($("#buyer-card").is(":visible") || $("#seller-card").is(":visible")) {
      if ($("#seller-card").is(":visible")) {
        $("#rider-btn").removeClass("btn-danger").addClass("btn-info");
        $("#seller-btn").removeClass("btn-info").addClass("btn-primary");
        $("#seller-btn").text("Seller");
        $("#seller-card").slideToggle("4000", "swing", function () {});
        $("#rider-card").slideToggle("4000", "swing", function () {});
      }
      if ($("#buyer-card").is(":visible")) {
        $("#buyer-card").slideToggle("4000", "swing", function () {});
        $("#rider-card").slideToggle("4000", "swing", function () {});
      }
      $("#rider-btn").removeClass("btn-danger").addClass("btn-info ");
      $("#rider-btn").text("Buyer");
      console.log("off");
    } else {
      $("#rider-btn").text("Rider");
      $("#rider-btn").removeClass("btn-info").addClass("btn-danger ");
      $("#buyer-card").slideToggle("4000", "swing", function () {});
      $("#rider-card").slideToggle("4000", "swing", function () {});
      console.log("on");
    }
  });
}
function SetUpRider() {
  $(document).on("click", "#setup-rider", function () {
    var ridername = $("#ridername").val();
    var ridercontact = $("#ridercontact").val();
    var rideremail = $("#rideremail").val();
    var riderlicense = $("#riderlicense").val();
    var riderplatenumber = $("#riderplatenumber").val();
    var ridermodel = $("#ridermodel").val();
    var ridercolor = $("#ridercolor").val();
    console.log(ridercolor, rideremail, ridermodel);
    $.ajax({
      url: "process/page-profile-settings-setupsrider.php",
      method: "post",
      data: {
        ridername: ridername,
        ridercontact: ridercontact,
        rideremail: rideremail,
        riderlicense: riderlicense,
        riderplatenumber: riderplatenumber,
        ridermodel: ridermodel,
        ridercolor: ridercolor,
      },
      success: function (data) {
        // console.log(
        //   ridername,
        //   ridercontact,
        //   rideremail,
        //   riderlicense,
        //   riderplatenumber,
        //   ridermodel,
        //   ridercolor
        // );
        console.log(data);
        DisplaySellerInfo();
        $("#rider-modal").modal("hide");
      },
    });
  });
}
function NotRiderBtn() {
  $(document).on("click", "#notrider-btn", function () {
    $("#rider-modal").modal("show");
  });
}
//////////////////rider////////
