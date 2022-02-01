$(document).ready(function () {
  BtnLogin();
  PhoneClose();
  PhoneCountDownReset();

  //alert("awe");
});

var downloadTimer;
function PhoneCountDown60s() {
  var timeleft = 120;
  downloadTimer = setInterval(function () {
    if (timeleft <= 0) {
      clearInterval(downloadTimer);
      OtpExpired();
      $("#ctimer").text(" " + "0s");
    } else {
      $("#ctimer").text(" " + timeleft + "s");
    }
    timeleft -= 1;
  }, 1000);
}
var downloadTimer_email;
function EmailCountDown60s() {
  var timeleft = 120;
  downloadTimer_email = setInterval(function () {
    if (timeleft <= 0) {
      console.log(0);
    } else {
      $("#ctimer-email").text(" " + timeleft + "s");
      Email_ifSuccess();
    }
    timeleft -= 1;
  }, 1000);
}

//main
function BtnLogin() {
  $(document).on("click", "#login", function () {
    var userDetail = $("#user-detail").val();

    $.ajax({
      url: "process/v2-login.php",
      method: "post",
      success: function (data) {
        var emailMatch =
          /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        var phoneMatch = /^0(9|4)\d{9}$/;
        var usernameMatch = /^[a-zA-Z0-9]+$/;
        if (emailMatch.test(userDetail)) {
          //isEmail=true
          CheckEmail(userDetail);
        } else if (phoneMatch.test(userDetail)) {
          //isPhone=true
          CheckPhone(userDetail);
        } else if (usernameMatch.test(userDetail)) {
          var thead = "Login";
          var ttext = "Not Phone/Not Email!";
          var tcolor = "error";
          Toast(thead, ttext, tcolor);
          //AutoCloseAlert2("Not Phone/Not Email");
          console.log("username");
        } else {
          console.log("user detail cannot found!");
        }
      },
    });
  });
}
//Email
function CheckEmail(userDetail) {
  var errortype = "Email";
  var length = 32;
  var token = Email_Token(length);
  $.ajax({
    url: "process/v2-check-email.php",
    method: "post",
    data: { userDetail: userDetail },
    success: function (data) {
      if (data == "success") {
        $("#emailModal").modal("show");
        EmailCountDown60s();
        $("#testing").text(token);
        Email_Token_Session(token);
        Email_Token2_Session(token, userDetail);
      } else {
        var thead = "Login";
        var ttext = "Email doesn't exist!";
        var tcolor = "error";
        Toast(thead, ttext, tcolor);
        //AutoCloseAlert(errortype);
        console.log("Email Doesn't Exists");
      }
    },
  });
}
function Email_Token(length) {
  //edit the token allowed characters
  var a =
    "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890".split("");
  var b = [];
  for (var i = 0; i < length; i++) {
    var j = (Math.random() * (a.length - 1)).toFixed(0);
    b[i] = a[j];
  }
  return b.join("");
}
function Email_Token_Session(token) {
  $.ajax({
    url: "process/v2-session-token.php",
    method: "post",
    data: { token: token },
    success: function (data) {
      console.log(data);
    },
  });
}
function Email_Token2_Session(token, userDetail) {
  var userDetail2 = $("#user-detail").val();
  console.log(
    "Link:" +
      "http://localhost/freshTogo-1.0.2/v2-check-email-otp.php?token=" +
      token
  );
  $.ajax({
    url: "process/v2-send-email-token2.php",
    method: "post",
    data: { token: token, userDetail: userDetail2 },
    sucess: function (data) {
      console.log(data);
      console.log(userDetail2);
    },
  });
}
function Email_ifSuccess() {
  var userDetail2 = $("#user-detail").val();
  $.ajax({
    url: "process/v2-email-ifSuccess.php",
    method: "post",
    data: { userDetail2: userDetail2 },
    success: function (data) {
      if (data == "success") {
        window.location.href = "v2-home.php?email";
      } else {
        console.log("Please Click the link weve email to you...");
      }
    },
  });
}
function Email() {}

//Phone
function PhoneClose() {
  $(document).on("click", "#phoneModal-close", function () {
    $("#phoneModal").modal("hide");
    clearInterval(downloadTimer);
  });
}
function PhoneCountDownReset() {
  var userdetail = $("#user-detail").val();
  $(document).on("click", "#resend", function () {
    clearInterval(downloadTimer);
    PhoneCountDown60s();
    PhoneSessionOtp(Phone4DigitNum(), userdetail);
    console.log(userdetail);
  });
}
function Phone4DigitNum() {
  var val = Math.floor(1000 + Math.random() * 9000);
  return val;
}
function CheckPhone(userdetail) {
  var errortype = "Phone Number";
  $.ajax({
    url: "process/v2-checkphone.php",
    method: "post",
    data: { userdetail: userdetail },
    success: function (data) {
      if (data == "success") {
        //true
        clearInterval(downloadTimer);
        PhoneCountDown60s();
        PhoneSessionOtp(Phone4DigitNum(), userdetail);
        $("#phoneModal").modal("show");
      } else {
        //AutoCloseAlert(errortype);
        var thead = "Login";
        var ttext = "Phone number doesn't exist!";
        var tcolor = "error";
        Toast(thead, ttext, tcolor);
        console.log("No Number Match...");
      }
    },
  });
}
function AutoCloseAlert(errortype) {
  $("#error-msg").html(
    "<div class='alert alert-danger m-0' role='alert'>" +
      errortype +
      " doesn't exists!</div>"
  );
  setTimeout(function () {
    $(".alert")
      .fadeTo(200, 0)
      .slideUp(200, function () {
        $(this).remove();
      });
  }, 3000);
}
function AutoCloseAlert2(errortype) {
  $("#error-msg").html(
    "<div class='alert alert-danger m-0' role='alert'>" + errortype + "</div>"
  );
  setTimeout(function () {
    $(".alert")
      .fadeTo(200, 0)
      .slideUp(200, function () {
        $(this).remove();
      });
  }, 3000);
}
////////////////////
function PhoneSessionOtp(otp, userdetail) {
  var userdetail1 = $("#user-detail").val();
  $.ajax({
    url: "process/v2-session-otp.php",
    method: "post",
    data: { otp: otp, userdetail: userdetail1 },
    success: function (data) {
      PhoneOtpVerification();
      console.log("otp-" + otp);
      console.log("data-" + data);
      PhoneSendOtp(otp, userdetail1);
    },
  });
}
function PhoneSendOtp(otp, userdetail) {
  var str = "Your OTP is " + otp + "..";
  $.ajax({
    url:
      "https://rest*.nexmo.com/sms/json?from=f2g&text=" +
      str +
      "&to=639294046222&api_key=4ce5048e&api_secret=Uilms5tNJeWP3aah",
    method: "post",
    success: function (data) {
      console.log("OTP SENT!");
    },
  });
}
function PhoneOtpVerification() {
  $(document).on("click", "#phone-verify", function () {
    var first = $("#first").val();
    var second = $("#second").val();
    var third = $("#third").val();
    var fourth = $("#fourth").val();
    var userdetail = $("#user-detail").val();
    $.ajax({
      url: "process/v2-phone-verification.php",
      method: "post",
      data: {
        first: first,
        second: second,
        third: third,
        fourth: fourth,
        userdetail: userdetail,
      },
      success: function (data) {
        if (data == "matched") {
          //verified
          window.location.href = "v2-home.php?welcome=y";
        } else if (data == "matched guest") {
          window.location.href = "page-payment.php";
        } else {
          //not verified
          alert(data);
        }
      },
    });
  });
}
function OtpExpired() {
  $.ajax({
    url: "process/v2-otp-expired.php",
    method: "post",
    success: function (data) {
      console.log(data);
    },
  });
}
//////////////////////////////////////////
function Phone() {}

//UserName
function Username() {}

function Toast(thead, ttext, tcolor) {
  $.toast({
    heading: thead,
    text: ttext,
    showHideTransition: "slide",
    icon: tcolor,
    position: "top-center",
  });
}
