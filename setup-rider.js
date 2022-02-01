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
