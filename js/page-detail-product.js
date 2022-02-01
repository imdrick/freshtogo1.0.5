$(document).ready(function () {
  AddToCart();
  QtyButton();
  test();
  //copy1
  $("#tooltip-cart").tooltip("dispose");
  VariationSelect();
  //$("#stocks-left").html("aw");
  StockDisplay();
  DisplayCountOrders();
  //getStarRating();
  DisplayReview();
  AddReview();
  AddServerReview();
  //Toast("aw", "aw", "success");
});
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

function AddToCart() {
  $(document).on("click", "#add-to-cart", function () {
    var variationid = $("#get-variationid").html();
    let urlParams = new URLSearchParams(window.location.search);
    let productid = urlParams.get("id");
    var poid = "poid-" + makeid(17);
    var qty = $("#qty").val();
    $.ajax({
      url: "process/page-detail-product-addtocart.php",
      method: "post",
      data: {
        productid: productid,
        poid: poid,
        qty: qty,
        variationid: variationid,
      },
      success: function (data) {
        DisplaySideCart();
        SideCartCount();
        if (data == "No More Stocks!") {
          alert(data);
          StockDisplay();
        } else {
          // NotifCart("You recently Add to Cart");
          //Toast("aw", "aw", "success");
          var thead = "Cart";
          var ttext = "Order has been successfully added to the cart!";
          var tcolor = "success";
          Toast(thead, ttext, tcolor);
        }
        console.log(data);
      },
    });
  });
}

function QtyButton() {
  $(document).on("click", "#button-plus", function () {
    var qty = parseInt($("#qty").val());

    var newqty = qty + 1;
    $("#qty").val(newqty);
    console.log("plus");
  });
  $(document).on("click", "#button-minus", function () {
    var qty = parseInt($("#qty").val());
    if (qty <= 1) {
      console.log("0 quantity");
    } else {
      var newqty = qty - 1;
      $("#qty").val(newqty);
      console.log("minus");
    }
  });
}

function VariationSelect() {
  $("#var-select").on("change", function () {
    var variationid = $(this).val();
    $.ajax({
      url: "process/page-detail-product-variationselect.php",
      method: "post",
      data: { variationid: variationid },
      success: function (data) {
        $("#price-title").html(data);
        $("#get-variationid").html(variationid);
        console.log(data);
      },
    });
  });
}

function test() {
  $(document).on("click", "#test-btn", function () {
    var variationid = $("#get-variationid").html();
    console.log(variationid);
  });
}

function StockDisplay() {
  var productid = param("id");
  $.ajax({
    url: "process/page-detail-product-StockDisplay.php",
    method: "post",
    data: { productid: productid },
    success: function (data) {
      if (data == "0") {
        $("#stocks-left").html("Out of Stocks...");
        $("#add-to-cart").attr("disabled", true);
      }
      console.log(data);
    },
  });
}
function DisplayCountOrders() {
  var productid = param("id");
  $.ajax({
    url: "process/page-detail-product-DisplayCountOrders.php",
    method: "post",
    data: { productid: productid },
    success: function (data) {
      // ("#count-orders").html("Out of Stocks...");
      $("#count-orders").html(data);
      console.log(data);
    },
  });
}

//////reviews
function getStarRating() {
  $("input[type='radio']").click(function () {
    var sim = $("input[type='radio']:checked").val();
    //alert(sim);
    if (sim < 3) {
      $(".myratings").css("color", "red");
      $(".myratings").text(sim);
    } else {
      $(".myratings").css("color", "green");
      $(".myratings").text(sim);
    }
  });
}
///////
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

function param(name) {
  return (location.search.split(name + "=")[1] || "").split("&")[0];
}
function Toast(thead, ttext, tcolor) {
  $.toast({
    heading: thead,
    text: ttext,
    showHideTransition: "slide",
    icon: tcolor,
    position: "top-right",
  });
}
