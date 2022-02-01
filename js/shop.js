$(document).ready(function () {
  DisplayInitial(0, 0);
  //alert("Aw");
});
function PriceRange() {
  $(document).on("click", "#ok", function () {
    var min = $("#min").val();
    var max = $("#max").val();
    console.log(min, max);
    DisplayInitial(min, max);
  });
}

function DisplayInitial(min, max) {
  var pagination_set = $("#change-initial").text();
  var to = param("to");
  var x = 0;
  if (to == "deals") {
    x = 1;
  } else if (to == "freeshipping") {
    x = 2;
  }
  if (pagination_set == 0) {
    pagination_set = 1;
  }

  $.ajax({
    url: "process/shop-DisplayInitial.php",
    method: "post",
    data: { pagination_set: pagination_set, min: min, max: max, x },
    success: function (data) {
      console.log(pagination_set);
      $("#all-in-one").html(data);
      PriceRange(min, max);
      Pagination(min, max);
      PostPagination(min, max);
      PrePagination(min, max);
    },
  });
}
function Pagination(min, max) {
  $(document).on("click", "#pagination-btn", function () {
    var pagination_set = $(this).val();
    $("#change-initial").text(pagination_set);
    DisplayInitial(min, max);
    //console.log(pagination_set);
    $(".color-page1:last").addClass("active");
  });
}
function PrePagination(min, max) {
  $(document).on("click", "#prepagination-btn", function () {
    var minus = parseInt($("#change-initial").text() - 1);
    if (minus == 0) {
    } else {
      $("#change-initial").text(minus);
      DisplayInitial(min, max);
    }
    //alert("aw");
    console.log(minus);
  });
}
function PostPagination(min, max) {
  $(document).on("click", "#postpagination-btn", function () {
    var add = parseInt($("#change-initial").text());
    var gmax = parseInt($("#max-page").text());
    if (add != gmax) {
      $("#change-initial").text(add + 1);
      DisplayInitial(min, max);
      console.log(add + 1);
    } else {
    }
  });
}
function Deals() {
  var to = param("to");
  if (to == "deals") {
    alert("yes");
  }
}
function param(name) {
  return (location.search.split(name + "=")[1] || "").split("&")[0];
}
