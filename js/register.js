$(document).ready(function () {
  Register();
  TermsAndCondition();
  //alert("check");
  Toast("Success", "Giatay ka", "info");
});


function Register() {
  $(document).on("click", "#register", function () {
    var firstname = $("#firstname").val();
    var lastname = $("#lastname").val();
    var contact = $("#contact").val();
    var email = $("#email").val();
    if (firstname == "" || lastname == "" || contact == "" || email == "") {
      $("#error-otp").html(
        "Please <strong>Fill</strong> in the <strong>Empty Fields</strong>."
      );
    } else {
      if (!$("#terms-conditions").is(":checked")) {
        $("#error-otp").html(
          "Please <strong>Check</strong> the terms and Conditions if You're going to <strong>Continue</strong>."
        );
      } else {
        $.ajax({
          url: "process/register.php",
          method: "post",
          data: {
            firstname: firstname,
            lastname: lastname,
            contact: contact,
            email: email,
          },
          success: function (data) {
            if (data == "success") {
              //alert("Your Account created Successfully!");
              window.location.href = "v2-login.php?registered=y";
            } else {
              $("#error-otp").html(data);
            }
          },
        });
      }
    }
  });
}
function ErrorRegister() {}

function Resend() {
  $(document).on("click", "#resend-otp", function () {
    console.log();
  });
}

function TermsAndCondition() {
  //modalTermsAndCondition
  $(document).on("click", "#term-condition", function () {
    $("#modalTermsAndCondition").modal("show");
  });
}
