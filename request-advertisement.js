function AdsSection() {
  $.ajax({
    url: "process/v2-f2g-admin-AdsSection.php",
    method: "",
    success: function (data) {
      //console.log(data);
      $("#ads-section").html(data);
    },
  });
}
function ActionModalAds() {
  $(document).on("click", "#action-btn-ad", function () {
    var adid = $(this).val();
    PopulateModalAd(adid);
  });
}
function PopulateModalAd(adid_) {
  var adid = adid_;
  $.ajax({
    url: "process/v2-f2g-admin-PopulateModalAd.php",
    method: "post",
    data: { adid: adid },
    success: function (data) {
      //consol  e.log(data);
      $("#pop-ad").html(data);
    },
  });
}
function ApproveAd() {
  $(document).on("click", "#ad-approve", function () {
    var adid = $("#get-adid").html();
    console.log(adid);
    $.ajax({
      url: "process/v2-f2g-admin-ApproveAd.php",
      method: "post",
      data: { adid: adid },
      success: function (data) {
        //console.log(data);
        AdsSection();
      },
    });
  });
}
function RejectAd() {
  $(document).on("click", "#ad-reject", function () {
    var adid = $("#get-adid").html();
    var remarks = $("#ad-remarks").val();
    if (remarks == "") {
      alert("Please compose remark to reject.");
    } else {
      $.ajax({
        url: "process/v2-f2g-admin-RejectAd.php",
        method: "post",
        data: { adid: adid, remarks: remarks },
        success: function (data) {
          //console.log(data);
          AdsSection();
        },
      });
    }
  });
}
