$(document).ready(function () {
  Login_OTP();
  Login_Verify();
  Resend();
});
var timer;

function CountDown_Otp(sec) {
  if (timer) clearInterval(timer);
  timer = setInterval(function () {
    $("#timer").text(sec-- + "s");
    if (sec == -1) {
      $.ajax({
        url: "process/otp-reset.php",
        method: "post",
        success: function (data) {
          console.log("reset otp");
        },
      });
      clearInterval(timer);
    }
  }, 1000);
}

function generateOTP() {
  var length = 6;
  var result = "";
  var characters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
  var charactersLength = characters.length;
  for (var i = 0; i < length; i++) {
    result += characters.charAt(Math.floor(Math.random() * charactersLength));
  }
  return result;
}

function LogIn(otp, userdetail) {
  $.ajax({
    url: "process/login.php",
    method: "post",
    data: { otp: otp, userdetail: userdetail },
    success: function (data) {
      if (data != "failed") {
        CountDown_Otp(60);
        $("#last-4-digits").text(userdetail.replace(/.(?=.{4})/g, "*"));
        $("#otpModal").modal("show");
      } else {
        Error_Login();
      }
    },
  });
}
function Login_OTP() {
  /*https://api-mapper.clicksend.com/http/v2/send.php?username=fbhendrickouano@gmail.com&key=7FAE4D18-0FD2-6E6F-A187-29311E56E85F&to=+63" +
userdetail +
"&message=" +
otp +
" is Your One-Time Pin. Enter this PIN in the FreshToGo Site. Please don't share your PIN&senderid=Fresh To Go&country=Philippines*/
  $(document).on("click", "#tologin", function () {
    var userdetail = $("#user-detail").val();
    var otp = generateOTP();
    $.ajax({
      url:
        "https://api-mapper.clicksend.com/http/v2/send.php?username=fbhendrickouano@gmail.com&key=7FAE4D18-0FD2-6E6F-A187-29311E56E85F&to=+63" +
        userdetail +
        "&message=" +
        otp +
        " is Your One-Time Pin. Enter this PIN in the FreshToGo Site. Please don't share your PIN&senderid=Fresh To Go&country=Philippines",
      method: "post",
      success: function (data) {
        LogIn(otp, userdetail);
        console.log(data);
      },
    });
  });
}
function Error_Login() {
  $.ajax({
    url: "process/login-error.php",
    method: "post",
    success: function (data) {
      $("#error-numemail").html(data);
    },
  });
}
function Login_Verify() {
  $(document).on("click", "#login-verify", function () {
    var first = $("#first").val();
    var second = $("#second").val();
    var third = $("#third").val();
    var fourth = $("#fourth").val();
    var fifth = $("#fifth").val();
    var sixth = $("#sixth").val();
    var str = first + second + third + fourth + fifth + sixth;
    $.ajax({
      url: "process/login-verify.php",
      method: "post",
      data: { str: str },
      success: function (data) {
        if (data == "success") {
          window.location.href = "/FreshTogo/page-index-1.html";
        } else {
          $("#error-otp").html(
            "Oops! Your <strong> OTP </strong>is <strong>INCORRECT. </strong>"
          );
        }
      },
    });
  });
}
function Resend() {
  $(document).on("click", "#resend-otp", function () {
    console.log("aw");
    var userdetail = $("#user-detail").val();
    var otp = generateOTP();
    $.ajax({
      url:
        "https://api-mapper.clicksend.com/http/v2/send.php?username=fbhendrickouano@gmail.com&key=7FAE4D18-0FD2-6E6F-A187-29311E56E85F&to=+63" +
        userdetail +
        "&message=" +
        otp +
        " is Your One-Time Pin. Enter this PIN in the FreshToGo Site. Please don't share your PIN&senderid=Fresh To Go&country=Philippines",
      method: "post",
      success: function (data) {
        console.log(otp);
        LogIn(otp, userdetail);
      },
    });
    CountDown_Otp(60);
  });
}
