function DisplayReview() {
  var productid = param("id");
  $.ajax({
    url: "process/page-detail-product-DisplayReview.php",
    method: "post",
    data: { productid: productid },
    success: function (data) {
      // ("#count-orders").html("Out of Stocks...");
      $("#review-display").html(data);
      console.log(data);
    },
  });
}
function AddReview() {
  $("input[type='radio']").click(function () {
    var sim = $("input[type='radio']:checked").val();
    if (sim < 3) {
      $(".myratings").css("color", "red");
      $(".myratings").text(sim);
    } else {
      $(".myratings").css("color", "green");
      $(".myratings").text(sim);
    }
  });
}
function AddServerReview() {
  $(document).on("click", "#add-review", function () {
    var sim = $("#get-ratingss").text();
    var comment = $("#comment").val();
    var productid = param("id");
    $.ajax({
      url: "process/page-detail-product-AddReview.php",
      method: "post",
      data: { productid: productid, sim: sim, comment: comment },
      success: function (data) {
        console.log(data);
        alert(data);
        $("#exampleModal").modal("hide");
        DisplayReview();
      },
    });
  });
}
