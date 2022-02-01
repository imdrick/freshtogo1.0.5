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
