$(document).ready(function () {
  //copy1
  //alert("Aw");
  DisplayReportSeller();
  DisableSeller();
  CountReportSeller();
  DisplayRequestSeller();
  EnableSeller();
  DisplayReportSellerHistory();
  GenerateUser();
  GenerateSeller();
  GenerateRider();

  DisplayReportRider();
  CountReportRider();
  DisplayRequestRider();
  DisableRider();
  EnableRider();

  DisplayReportUser();
  CountReportUser();
  DisableUser();
  DisplayRequestUser();
  EnableUser();

  AdsSection();
  ActionModalAds();
  ApproveAd();
  RejectAd();
});

function DisplayReportSeller() {
  $.ajax({
    url: "process/v2-f2g-admin-DisplayReportSeller.php",
    method: "",
    success: function (data) {
      //console.log(data);
      $("#table-report-seller").html(data);
    },
  });
}
function DisableSeller() {
  $(document).on("click", "#disabled-seller", function () {
    var reportid = $(this).val();
    $.ajax({
      url: "process/v2-f2g-admin-DisableSeller.php",
      method: "post",
      data: { reportid: reportid },
      success: function (data) {
        console.log(data);
        CountReportSeller();
        DisplayRequestSeller();
        DisplayReportSeller();
      },
    });
  });
}
function CountReportSeller() {
  $.ajax({
    url: "process/v2-f2g-admin-CountReportSeller.php",
    method: "",
    success: function (data) {
      //console.log(data);
      $("#count-report-seller").html(data);
    },
  });
}
function DisplayRequestSeller() {
  $.ajax({
    url: "process/v2-f2g-admin-DisplayRequestSeller.php",
    method: "",
    success: function (data) {
      //console.log(data);
      $("#table-request-seller").html(data);
    },
  });
}
function EnableSeller() {
  $(document).on("click", "#enable-seller", function () {
    var requestid = $(this).val();
    console.log(requestid);
    $.ajax({
      url: "process/v2-f2g-admin-EnableSeller.php",
      method: "post",
      data: { requestid: requestid },
      success: function (data) {
        console.log(data);
        DisplayRequestSeller();
        CountReportSeller();
      },
    });
  });
}
/////////RIDER////////////
function DisplayReportRider() {
  $.ajax({
    url: "process/v2-f2g-admin-DisplayReportRider.php",
    method: "",
    success: function (data) {
      //console.log(data);
      $("#table-report-rider").html(data);
    },
  });
}
function CountReportRider() {
  $.ajax({
    url: "process/v2-f2g-admin-CountReportRider.php",
    method: "",
    success: function (data) {
      //console.log(data);
      $("#count-report-rider").html(data);
    },
  });
}
function DisableRider() {
  $(document).on("click", "#disabled-rider", function () {
    var reportid = $(this).val();
    $.ajax({
      url: "process/v2-f2g-admin-DisableRider.php",
      method: "post",
      data: { reportid: reportid },
      success: function (data) {
        console.log(data);
        CountReportRider();
        DisplayRequestRider();
        DisplayReportRider();
      },
    });
  });
}
function DisplayRequestRider() {
  $.ajax({
    url: "process/v2-f2g-admin-DisplayRequestRider.php",
    method: "",
    success: function (data) {
      //console.log(data);
      $("#table-request-rider").html(data);
    },
  });
}
function EnableRider() {
  $(document).on("click", "#enable-rider", function () {
    var requestid = $(this).val();
    console.log(requestid);
    $.ajax({
      url: "process/v2-f2g-admin-EnableRider.php",
      method: "post",
      data: { requestid: requestid },
      success: function (data) {
        console.log(data);
        DisplayRequestRider();
        CountReportRider();
      },
    });
  });
}

//////end RIDER////////////////////
////////USER///////////////////
function DisplayReportUser() {
  $.ajax({
    url: "process/v2-f2g-admin-DisplayReportUser.php",
    method: "",
    success: function (data) {
      //console.log(data);
      $("#table-report-user").html(data);
    },
  });
}
function CountReportUser() {
  $.ajax({
    url: "process/v2-f2g-admin-CountReportUser.php",
    method: "",
    success: function (data) {
      //console.log(data);
      $("#count-report-user").html(data);
    },
  });
}
function DisableUser() {
  $(document).on("click", "#disabled-user", function () {
    var reportid = $(this).val();
    console.log(reportid);
    $.ajax({
      url: "process/v2-f2g-admin-DisableUser.php",
      method: "post",
      data: { reportid: reportid },
      success: function (data) {
        console.log(data);
        CountReportUser();
        DisplayRequestUser();
        DisplayReportUser();
      },
    });
  });
}
function DisplayRequestUser() {
  $.ajax({
    url: "process/v2-f2g-admin-DisplayRequestUser.php",
    method: "",
    success: function (data) {
      //console.log(data);
      $("#table-request-user").html(data);
    },
  });
}
function EnableUser() {
  $(document).on("click", "#enable-user", function () {
    var requestid = $(this).val();
    console.log(requestid);
    $.ajax({
      url: "process/v2-f2g-admin-EnableUser.php",
      method: "post",
      data: { requestid: requestid },
      success: function (data) {
        console.log(data);
        DisplayRequestUser();
        CountReportUser();
      },
    });
  });
}

////////End User//////////////////
function DisplayReportSellerHistory() {
  $.ajax({
    url: "process/v2-f2g-admin-DisplayReportSellerHistory.php",
    method: "",
    success: function (data) {
      //console.log(data);
      $("#table-report-seller-history").html(data);
    },
  });
}

function GenerateUser() {
  $.ajax({
    url: "process/v2-f2g-admin-GenerateUser.php",
    method: "",
    success: function (data) {
      //console.log(data);
      $("#generate-user").html(data);
    },
  });
}
function GenerateSeller() {
  $.ajax({
    url: "process/v2-f2g-admin-GenerateSeller.php",
    method: "",
    success: function (data) {
      //console.log(data);
      $("#generate-seller").html(data);
    },
  });
}
function GenerateRider() {
  $.ajax({
    url: "process/v2-f2g-admin-GenerateRider.php",
    method: "",
    success: function (data) {
      //console.log(data);
      $("#generate-rider").html(data);
    },
  });
}
//////////ADS/////////
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
