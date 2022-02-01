$(document).ready(function () {
  AddProductModal();
  DisplayProduct();
  AddProduct();
  var category = $("#category").val();
  //test drive
  ResetFormButton();
  VariationSwitch();
  //$("#edit-product-modal").modal("show");
  GetStarted();
  GetStartedBtn();
  AddMasterVariation();
  AddChildVariation();
  DeleteTableVariation();
  DeleteChildVariation();
  EditChildVariation();
  UpdateChildVariation();
  CancelChildVariation();
  EditTablevariation();
  Test1();
  //
  CheckVariation();
  //alert("se");
  //edit
  EditProduct();
  DeleteProduct();
  SaveProduct();
  UploadImage_Edit();

  PopulateAd();
  RestoreAd();
});

//time
let a = [
  { day: "numeric" },
  { month: "numeric" },
  { year: "numeric" },
  { minute: "numeric" },
  { second: "numeric" },
];
let s = join(new Date(), a, "");
function join(t, a, s) {
  function format(m) {
    let f = new Intl.DateTimeFormat("en", m);
    return f.format(t);
  }
  return a.map(format).join(s);
}
//random str
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
//display product
function DisplayProduct() {
  $.ajax({
    url: "process/page-seller-displayproduct.php",
    method: "post",
    success: function (data) {
      $("#product-fetch").html(data);
    },
  });
}

//
function AddProductModal() {
  $(document).on("click", "#add-product-modal2", function () {
    $.ajax({
      url: "process/pag-seller-addproduct.php",
      method: "post",
      success: function (data) {
        $("#add-product-modal").modal("show");
      },
    });
  });
}
function ResetForm() {
  $("#product-name").val("");
  $("#main-description").val("");
  $("#regular-price").val("");
  $("#sale-price").val("");
  $("#sku").val("");
  $("#stock").val("");
  $("#weight").val("");
  $("#length").val("");
  $("#width").val("");
  $("#height").val("");
  $("#shipping-type").val("");
  $("#short-description").val("");
}
function ResetFormButton() {
  $(document).on("click", "#reset-form", function () {
    ResetForm();
  });
}
function AddProduct() {
  $(document).on("click", "#add-product", function () {
    var productname = $("#product-name").val();
    var maindescription = $("#main-description").val();
    var regularprice = $("#regular-price").val();
    var saleprice = $("#sale-price").val();
    var sku = $("#sku").val();
    var stock = $("#stock").val();
    var weight = $("#weight").val();
    var length = $("#length").val();
    var width = $("#width").val();
    var height = $("#height").val();
    var shippingtype = $("#shipping-type").val();
    var shortdescription = $("#short-description").val();
    var category = $("#category").val();
    var switchStatus = $("#variation-switch").is(":checked");
    if (saleprice == "") {
      saleprice = "0";
    }
    //trap

    if (
      productname == "" ||
      maindescription == "" ||
      regularprice == "" ||
      sku == "" ||
      stock == "" ||
      weight == "" ||
      length == "" ||
      width == "" ||
      height == "" ||
      shippingtype == ""
    ) {
      //alert("Please fill in the blanks");
      // $("#add-product-modal").modal("hide");
      var thead = "Product";
      var ttext = "Please fill in the blanks";
      var tcolor = "warning";

      Toast(thead, ttext, tcolor);
    } else {
      if (
        parseFloat(saleprice) > parseFloat(regularprice) ||
        parseFloat(saleprice) == parseFloat(regularprice)
      ) {
        //alert("Sale Price is never been greater than or equal to the Regular Price..");
        //$("#add-product-modal").modal("hide");
        var thead = "Product";
        var ttext =
          "Sale Price is never been greater than or equal to the Regular Price..";
        var tcolor = "warning";
        Toast(thead, ttext, tcolor);
      } else {
        $.ajax({
          url: "process/pag-seller-addproduct.php?name=" + s + "-" + makeid(4),
          method: "post",
          data: {
            productname: productname,
            maindescription: maindescription,
            regularprice: regularprice,
            saleprice: saleprice,
            sku: sku,
            stock: stock,
            weight: weight,
            length: length,
            width: width,
            height: height,
            shippingtype: shippingtype,
            shortdescription: shortdescription,
            category: category,
            switchStatus: switchStatus,
          },
          success: function (data) {
            if (switchStatus) {
              //True
              if (parseInt(data) == 1) {
                UploadImage(
                  productname,
                  maindescription,
                  regularprice,
                  saleprice,
                  sku,
                  stock,
                  weight,
                  length,
                  width,
                  height,
                  shippingtype,
                  shortdescription,
                  category,
                  switchStatus
                );
                //alert(data);
                DisplayProduct();
              } else {
                //alert("Please Make atleast 1 Variation");
                var thead = "Variation";
                var ttext = "Please Make atleast 2 Variations";
                var tcolor = "warning";
                Toast(thead, ttext, tcolor);
              }
            } else {
              UploadImage(
                productname,
                maindescription,
                regularprice,
                saleprice,
                sku,
                stock,
                weight,
                length,
                width,
                height,
                shippingtype,
                shortdescription,
                category,
                switchStatus
              );

              //alert(data);
              DisplayProduct();
              //
              GetStarted2();
              DisplayTableVariation();
            }
          },
        });
      }
    }
  });
}
//

function UploadImage(
  productname,
  maindescription,
  regularprice,
  saleprice,
  sku,
  stock,
  weight,
  length,
  width,
  height,
  shippingtype,
  shortdescription,
  category,
  switchStatus
) {
  var fileNameCheck = $("#product-image").val();
  if (fileNameCheck == "") {
    $.ajax({
      url: "process/page-product-upload.php?name=" + s + "-" + makeid(4),
      method: "POST",
      data: form_data,
      contentType: false,
      cache: false,
      processData: false,
      success: function (data) {
        var productimage = "default.jpg";
        AddProduct_Server(
          productname,
          productimage,
          maindescription,
          regularprice,
          saleprice,
          sku,
          stock,
          weight,
          length,
          width,
          height,
          shippingtype,
          shortdescription,
          category,
          switchStatus
        );
        DisplayProduct();
        //
        GetStarted2();
        DisplayTableVariation();
      },
    });
  } else {
    var name = document.getElementById("product-image").files[0].name;
    var form_data = new FormData();
    var ext = name.split(".").pop().toLowerCase();

    if (jQuery.inArray(ext, ["gif", "png", "jpg", "jpeg"]) == -1) {
      //alert("Invalid Image File");
      var thead = "Image";
      var ttext = "Invalid Image File";
      var tcolor = "warning";
      Toast(thead, ttext, tcolor);
    } else {
      var oFReader = new FileReader();
      oFReader.readAsDataURL(document.getElementById("product-image").files[0]);
      var f = document.getElementById("product-image").files[0];
      var fsize = f.size || f.fileSize;
      if (fsize > 2000000) {
        //alert("Image File Size is very big");
        var thead = "Image";
        var ttext = "Image File Size is very big";
        var tcolor = "warning";
        Toast(thead, ttext, tcolor);
      } else {
        form_data.append(
          "product-image",
          document.getElementById("product-image").files[0]
        );
        $.ajax({
          url: "process/page-product-upload.php?name=" + s + "-" + makeid(4),
          method: "POST",
          data: form_data,
          contentType: false,
          cache: false,
          processData: false,
          success: function (data) {
            var productimage = data;
            AddProduct_Server(
              productname,
              productimage,
              maindescription,
              regularprice,
              saleprice,
              sku,
              stock,
              weight,
              length,
              width,
              height,
              shippingtype,
              shortdescription,
              category,
              switchStatus
            );
            DisplayProduct();
            //
            GetStarted2();
            DisplayTableVariation();
          },
        });
      }
    }
  }
  //

  function AddProduct_Server(
    productname,
    productimage,
    maindescription,
    regularprice,
    saleprice,
    sku,
    stock,
    weight,
    length,
    width,
    height,
    shippingtype,
    shortdescription,
    category,
    switchStatus
  ) {
    $.ajax({
      url: "process/page-addproduct-server.php",
      method: "post",
      data: {
        productname: productname,
        productimage: productimage,
        maindescription: maindescription,
        regularprice: regularprice,
        saleprice: saleprice,
        sku: sku,
        stock: stock,
        weight: weight,
        length: length,
        width: width,
        height: height,
        shippingtype: shippingtype,
        shortdescription: shortdescription,
        category: category,
        switchStatus: switchStatus,
      },
      success: function (data) {
        console.log(data);
        DisplayProduct();
        GetStarted2();
        ResetForm();
        //
        $("#add-product-modal").modal("hide");
        var thead = "Product";
        var ttext = "Product Added!";
        var tcolor = "success";

        Toast(thead, ttext, tcolor);
      },
    });
  }
}

//variation
function VariationSwitch() {
  $("#variation-switch").change(function () {
    if ($(this).is(":checked")) {
      switchStatus = $(this).is(":checked");
      console.log(switchStatus);
      //animate
      $("#variation-display-getstarted").fadeIn("3000");
      $("#variation-display").fadeIn("7000");
      $("#variation-displayt-table").fadeIn("5000");
      //true
      $("#variation-text").text(" On");
      $("#variation-text").attr("class", "text-primary");
      $(".reg-sale").attr("readonly", true);
      $("#variation-display-getstarted").attr("hidden", false);
      $("#variation-display").attr("hidden", false);
      $("#variation-displayt-table").attr("hidden", false);
      $("#mid-line").css({ "text-decoration": "line-through" });
      $("#mid-line2").css({ "text-decoration": "line-through" });

      $("#regular-price").val("1");
      $("#sale-price").val("0");
      $("#regular-price").css({ color: "#E5E7EA" });
      /////////////////
      $("#shipping-type").attr("readonly", true);
      $("#shipping-type").val("0");
      $("#shipping-type").css({ color: "#E5E7EA" });
      $("#mid-line3").css({ "text-decoration": "line-through" });

      $("#sale-price").css({ color: "#E5E7EA" });
    } else {
      switchStatus = $(this).is(":checked");
      console.log(switchStatus);
      //false
      $("#variation-text").text(" Off");
      $("#variation-text").attr("class", "text-muted");
      $(".reg-sale").attr("readonly", false);
      $("#variation-display-getstarted").attr("hidden", true);
      $("#variation-display").attr("hidden", true);
      $("#variation-displayt-table").attr("hidden", true);
      $("#mid-line").css({ "text-decoration": "none" });
      $("#mid-line2").css({ "text-decoration": "none" });
      $("#regular-price").val("");
      $("#regular-price").css({ color: "#495057" });
      $("#sale-price").val("");
      $("#sale-price").css({ color: "#495057" });
      /////////////////
      $("#shipping-type").attr("readonly", false);
      $("#shipping-type").val("");
      $("#shipping-type").css({ color: "#495057" });
      $("#mid-line3").css({ "text-decoration": "none" });
    }
  });
}
//readIfNoOutPut

function GetStarted() {
  $.ajax({
    url: "process/page-seller-getstarted.php",
    method: "post",
    success: function (data) {
      $("#variation-display-getstarted").html(data);
      if (data == "") {
        DisplayAddVariation();
        DisplayTableVariation();
      }
    },
  });
}
function GetStarted2() {
  $.ajax({
    url: "process/page-seller-getstarted.php",
    method: "post",
    success: function (data) {
      $("#variation-display-getstarted").html(data);
      $("#regular-price").val("1");
      $("#sale-price").val("0");
    },
  });
}
//
function DisplayAddVariation() {
  $.ajax({
    url: "process/page-seller-displayaddvariation.php",
    method: "post",
    success: function (data) {
      console.log(data);
      $("#variation-display-getstarted").html(data);
    },
  });
}
function DisplayTableVariation() {
  $.ajax({
    url: "process/page-seller-displaytablevariation.php",
    method: "post",
    success: function (data) {
      $("#variation-displayt-table").html(data);
    },
  });
}
function GetStartedBtn() {
  $(document).on("click", "#get-started", function () {
    var randomColor = Math.floor(Math.random() * 16777215).toString(16);

    $.ajax({
      url: "process/page-seller-getstartedbtn.php",
      method: "post",
      data: { makeid: makeid(12), randomColor: randomColor },
      success: function (data) {
        //animate
        $("#variation-display-getstarted").fadeIn("3000");
        $("#variation-display").fadeIn("7000");
        $("#variation-displayt-table").fadeIn("5000");
        var thead = "Variation";
        var ttext = "You can add variations";
        var tcolor = "info";
        Toast(thead, ttext, tcolor);
        GetStarted();
      },
    });
  });
}

function AddMasterVariation() {
  $(document).on("click", "#add-parent-variation", function () {
    var name = $("#add-master-variation").val();
    var x = Math.floor(Math.random() * 256);
    var y = 100 + Math.floor(Math.random() * 256);
    var z = 50 + Math.floor(Math.random() * 256);
    var randomColor = x + y + z;
    if (name == "") {
      //alert("Fill in the blank!");
      var thead = "Image";
      var ttext = "Fill in the blank!";
      var tcolor = "warning";
      Toast(thead, ttext, tcolor);
    } else {
      $.ajax({
        url: "process/page-seller-addmastervariation.php",
        method: "post",
        data: { name: name, randomColor: randomColor, makeid: makeid(12) },
        success: function (data) {
          console.log(data);
          DisplayTableVariation();
          var thead = "Variation";
          var ttext = "Variation Table Added!";
          var tcolor = "success";
          Toast(thead, ttext, tcolor);
        },
      });
    }
  });
}
function AddChildVariation() {
  $(document).on("click", "#add-child-varation", function () {
    var varcode = $(this).val();
    var variationname = $("#variationname-" + varcode).val();
    var varregular = $("#varregular-" + varcode).val();
    var varsale = $("#varsale-" + varcode).val();
    var shippingtype = $("#shippingtype-" + varcode).val();
    if (shippingtype == "") {
      shippingtype = 0;
    }
    if (variationname == "" || varregular == "") {
      //alert("Please fill in the blank");
      var thead = "Child Variation";
      var ttext = "Fill in the blank!";
      var tcolor = "warning";
      Toast(thead, ttext, tcolor);
    } else {
      if (parseFloat(varsale) >= parseFloat(varregular)) {
        //alert("Sale is not always greater than or equal to regular");
        var thead = "Child Variation";
        var ttext = "Sale is not always greater than or equal to regular";
        var tcolor = "warning";
        Toast(thead, ttext, tcolor);
      } else {
        $.ajax({
          url: "process/page-seller-addchildvariation.php",
          method: "post",
          data: {
            varcode: varcode,
            variationname: variationname,
            varregular: varregular,
            varsale: varsale,
            shippingtype: shippingtype,
          },
          success: function (data) {
            DisplayTableVariation();
            $("#variationname-" + varcode).val("");
            $("#varregular-" + varregular).val("");
            $("#varsale-" + varregular).val("");
            $("#shippingtype-" + varregular).val("");
            // console.log(
            //   varcode,
            //   variationname,
            //   varregular,
            //   varsale,
            //   shippingtype
            // );
            var thead = "Variation";
            var ttext = "Variation Added!";
            var tcolor = "success";
            Toast(thead, ttext, tcolor);
          },
        });
      }
    }
  });
}
function DeleteTableVariation() {
  //delete-table-varitation
  $(document).on("click", "#delete-table-varitation", function () {
    var mastervariationid = $(this).val();

    $.ajax({
      url: "process/page-seller-deletetablevariation.php",
      method: "post",
      data: { mastervariationid: mastervariationid },
      success: function (data) {
        console.log(data);
        DisplayTableVariation();
        var thead = "Variation";
        var ttext = "Variation Table Deleted!";
        var tcolor = "error";
        Toast(thead, ttext, tcolor);
      },
    });
  });
}
function DeleteChildVariation() {
  //delete-table-varitation
  $(document).on("click", "#delete-child-variation", function () {
    var variationid = $(this).val();
    $.ajax({
      url: "process/page-seller-deletechildvariation.php",
      method: "post",
      data: { variationid: variationid },
      success: function (data) {
        console.log(variationid);
        DisplaySingleVariation(variationid);
        var thead = "Variation";
        var ttext = "Variation Deleted!";
        var tcolor = "error";
        Toast(thead, ttext, tcolor);
      },
    });
  });
}
function EditChildVariation() {
  $(document).on("click", "#edit-child-variation", function () {
    var variationid = $(this).val();
    $.ajax({
      url: "process/page-seller-editchildvariation.php",
      method: "post",
      data: { variationid: variationid },
      success: function (data) {
        console.log(variationid);
        $("#edit-single-variation-" + variationid).html(data);
      },
    });
  });
}
function UpdateChildVariation() {
  $(document).on("click", "#update-child-variation", function () {
    var variationid = $(this).val();
    var variationname = $("#variationname-" + variationid).val();
    var varregular = $("#varregular-" + variationid).val();
    var varsale = $("#varsale-" + variationid).val();
    var shippingtype = $("#shippingtype-" + variationid).val();
    if (shippingtype == "") {
      shippingtype = 0;
    }
    if (variationname == "" || varregular == "") {
      //alert("Please fill in the blank");
      var thead = "Update Child Variation";
      var ttext = "Please fill in the blank";
      var tcolor = "warning";
      Toast(thead, ttext, tcolor);
    } else {
      if (parseFloat(varsale) >= parseFloat(varregular)) {
        //alert("Sale is not always greater than or equal to regular");
        var thead = "Child Variation";
        var ttext = "Sale is not always greater than or equal to regular";
        var tcolor = "warning";
        Toast(thead, ttext, tcolor);
      } else {
        $.ajax({
          url: "process/page-seller-updatechildvariation.php",
          method: "post",
          data: {
            variationid: variationid,
            variationname: variationname,
            varregular: varregular,
            varsale: varsale,
            shippingtype: shippingtype,
          },
          success: function (data) {
            console.log(
              variationid,
              variationname,
              varregular,
              varsale,
              shippingtype
            );
            DisplaySingleVariation(variationid);
            //$("#edit-single-variation-" + variationid).html(data);
            var thead = "Variation";
            var ttext = "Variation Update!";
            var tcolor = "success";
            Toast(thead, ttext, tcolor);
          },
        });
      }
    }
  });
}

function DisplaySingleVariation(variationid) {
  $.ajax({
    url: "process/page-seller-displaysinglechildvariation.php",
    method: "post",
    data: {
      variationid: variationid,
    },
    success: function (data) {
      $("#edit-single-variation-" + variationid).html(data);
    },
  });
}

function CancelChildVariation() {
  //cancel-child-varation
  $(document).on("click", "#cancel-child-varation", function () {
    var variationid = $(this).val();
    DisplaySingleVariation(variationid);
  });
}

function EditTablevariation() {
  //edit-table-varitation
  $(document).on("click", "#edit-table-varitation", function () {
    var mastervariationid = $(this).val();
    console.log(mastervariationid);
  });
}
function Test1() {
  $(document).on("click", "#test", function () {
    var switchStatus = $("#variation-switch").is(":checked");
    console.log(switchStatus);
  });
}

function CheckVariation() {
  $.ajax({
    url: "process/page-seller-checkvariation.php",
    method: "",
    success: function (data) {
      if (parseInt(data) == 1) {
        console.log("ok");
      } else {
        console.log("not ok");
      }
    },
  });
}
////////edit
function EditProduct() {
  $(document).on("click", "#edit-product", function () {
    var productid = $(this).val();
    console.log($(this).val());
    $.ajax({
      url: "process/page-seller-editproduct.php",
      method: "post",
      data: { productid: productid },
      success: function (data) {
        var res = JSON.parse(data);
        $("#productid-text").html(productid);
        $("#edit-product-name").val(res.productname);
        $("#edit-main-description").val(res.description);
        $("#edit-categoryid").val(res.categoryid);
        $("#edit-regular-price").val(res.regularprice);
        $("#edit-sale-price").val(res.saleprice);
        $("#edit-sku").val(res.sku);
        $("#edit-stock").val(res.stock);
        $("#edit-weight").val(res.weight);
        $("#edit-length").val(res.length);
        $("#edit-width").val(res.width);
        $("#edit-height").val(res.height);
        $("#edit-shipping-type").val(res.shippingtype);
        $("#edit-short-description").val(res.shortdescription);

        if (parseInt(res.coderes) == 1) {
          $(".editswitch").attr("checked", true);
        }
        if (res.coderes == "0") {
          $(".editswitch2").attr("checked", false);
        }
        ////logic
        if (res.code != "") {
          $("#code-reg").attr("hidden", true);
          $("#code-sale").attr("hidden", true);
          $("#code-shipping").attr("hidden", true);
        } else {
          $("#code-reg").attr("hidden", false);
          $("#code-sale").attr("hidden", false);
          $("#code-shipping").attr("hidden", false);
        }
        console.log(res.code);
      },
    });
  });
}
//populate
function DeleteProduct() {
  $(document).on("click", "#delete-productid", function () {
    var productid = $(this).val();
    console.log($(this).val());
    if (confirm("Are you sure to Delete this product?")) {
      $.ajax({
        url: "process/page-seller-DeleteProduct.php",
        method: "post",
        data: { productid: productid },
        success: function (data) {
          console.log(data);
          DisplayProduct();
          var thead = "Product";
          var ttext = "Product Deleted!";
          var tcolor = "error";
          Toast(thead, ttext, tcolor);
        },
      });
    } else {
      // Do nothing!
      console.log("Thing was not saved to the database.");
    }
  });
}
function SaveProduct() {
  $(document).on("click", "#save-product", function () {
    var productid = $("#productid-text").html();
    var productname = $("#edit-product-name").val();
    var getimagename = $("#get-img-name").html();
    var productimage = $("#edit-product-image").val();
    var maindescription = $("#edit-main-description").val();
    var regularprice = $("#edit-regular-price").val();
    var saleprice = $("#edit-sale-price").val();
    var sku = $("#edit-sku").val();
    var stock = $("#edit-stock").val();
    var weight = $("#edit-weight").val();
    var length = $("#edit-length").val();
    var width = $("#edit-width").val();
    var height = $("#edit-height").val();
    var shippingtype = $("#edit-shipping-type").val();
    var shortdescription = $("#edit-short-description").val();
    var categoryid = $("#edit-categoryid").val();

    $.ajax({
      url: "process/page-seller-SaveProduct.php",
      method: "post",
      data: {
        productid: productid,
        productname: productname,
        getimagename: getimagename,
        productimage: productimage,
        maindescription: maindescription,
        regularprice: regularprice,
        saleprice: saleprice,
        sku: sku,
        stock: stock,
        weight: weight,
        length: length,
        width: width,
        height: height,
        shippingtype: shippingtype,
        shortdescription: shortdescription,
        categoryid: categoryid,
      },
      success: function (data) {
        console.log(data);
        console.log(categoryid);
        DisplayProduct();
        $("#edit-product-modal").modal("hide");
        var thead = "Product";
        var ttext = "Product Updated!";
        var tcolor = "success";
        Toast(thead, ttext, tcolor);
      },
    });
  });
}
function UploadPicture() {}

function UploadImage_Edit() {
  $(document).on("change", "#edit-product-image", function () {
    var fileNameCheck = $("#edit-product-image").val();

    var name = document.getElementById("edit-product-image").files[0].name;
    var form_data = new FormData();
    var ext = name.split(".").pop().toLowerCase();

    if (jQuery.inArray(ext, ["gif", "png", "jpg", "jpeg"]) == -1) {
      //alert("Invalid Image File");
      var thead = "Image";
      var ttext = "Invalid Image File";
      var tcolor = "warning";
      Toast(thead, ttext, tcolor);
    } else {
      var oFReader = new FileReader();
      oFReader.readAsDataURL(
        document.getElementById("edit-product-image").files[0]
      );
      var f = document.getElementById("edit-product-image").files[0];
      var fsize = f.size || f.fileSize;
      if (fsize > 2000000) {
        //alert("Image File Size is very big");
        var thead = "Image";
        var ttext = "Image File Size is very big";
        var tcolor = "warning";
        Toast(thead, ttext, tcolor);
      } else {
        form_data.append(
          "edit-product-image",
          document.getElementById("edit-product-image").files[0]
        );
        $.ajax({
          url:
            "process/page-product-upload-edit.php?name=" + s + "-" + makeid(4),
          method: "POST",
          data: form_data,
          contentType: false,
          cache: false,
          processData: false,
          success: function (data) {
            console.log(data);
            $("#get-img-name").html(data);
          },
        });
      }
    }
  });
}
//////ADS////////
function ResetDate() {
  var now = new Date();

  var day = ("0" + now.getDate()).slice(-2);
  var month = ("0" + (now.getMonth() + 1)).slice(-2);
  var today = now.getFullYear() + "-" + month + "-" + day;

  var now2 = new Date();

  var day2 = ("0" + (now2.getDate() + 7)).slice(-2);
  var month2 = ("0" + (now2.getMonth() + 1)).slice(-2);
  var today2 = now2.getFullYear() + "-" + month2 + "-" + day2;
  console.log("aw");
  $("#start_date").val(today);
  console.log(today2);
  $("#exp_date").val(today2);
}
function PopulateAd() {
  $(document).on("click", "#ad-product", function () {
    var productid = $(this).val();
    $.ajax({
      url: "process/page-seller-PopulateAd.php",
      method: "post",
      data: { productid: productid },
      success: function (data) {
        console.log(data);
        //$("#productname").val(data);
        RequestAd(productid);
        ResetDate();
        UploadImage_AdPic();
        UploadImage_Proof();
        OnlyOneReview(productid, data);
        $("#productname").val(data);
      },
    });
  });
}
function OnlyOneReview(productid_, name) {
  var productid = productid_;
  $.ajax({
    url: "process/page-seller-OnlyOneReview.php",
    method: "post",
    data: { productid: productid },
    success: function (data) {
      console.log(data);
      $("#row-ad").html(data);
      $("#productname").val(name);
      ResetDate();
    },
  });
}
function RequestAd(productid_) {
  $(document).on("click", "#request-ad", function () {
    var productid = productid_;
    var start_date = $("#start_date").val();
    var exp_date = $("#exp_date").val();
    var ad_picture = $("#get_ad_picture").html();
    var proof_picture = $("#get_proof_picture").html();
    if (ad_picture == "" || proof_picture == "") {
      alert("Required to Upload Picture.");
    } else {
      $.ajax({
        url: "process/page-seller-RequestAd.php",
        method: "post",
        data: {
          productid: productid,
          start_date: start_date,
          exp_date: exp_date,
          ad_picture: ad_picture,
          proof_picture: proof_picture,
        },
        success: function (data) {
          console.log(data);
          $("#exampleModal").modal("hide");
        },
      });
    }
  });
}

function UploadImage_AdPic() {
  $(document).on("change", "#ad_picture", function () {
    var fileNameCheck = $("#ad_picture").val();

    var name = document.getElementById("ad_picture").files[0].name;
    var form_data = new FormData();
    var ext = name.split(".").pop().toLowerCase();

    if (jQuery.inArray(ext, ["gif", "png", "jpg", "jpeg"]) == -1) {
      //alert("Invalid Image File");
      var thead = "Image";
      var ttext = "Invalid Image File";
      var tcolor = "warning";
      Toast(thead, ttext, tcolor);
    } else {
      var oFReader = new FileReader();
      oFReader.readAsDataURL(document.getElementById("ad_picture").files[0]);
      var f = document.getElementById("ad_picture").files[0];
      var fsize = f.size || f.fileSize;
      if (fsize > 2000000) {
        //alert("Image File Size is very big");
        var thead = "Image";
        var ttext = "Image File Size is very big";
        var tcolor = "warning";
        Toast(thead, ttext, tcolor);
      } else {
        form_data.append(
          "ad_picture",
          document.getElementById("ad_picture").files[0]
        );
        $.ajax({
          url:
            "process/page-product-upload-UploadImage_AdPic.php?name=" +
            s +
            "-" +
            makeid(4),
          method: "POST",
          data: form_data,
          contentType: false,
          cache: false,
          processData: false,
          success: function (data) {
            console.log(data);
            $("#get_ad_picture").html(data);
          },
        });
      }
    }
  });
}

function UploadImage_Proof() {
  $(document).on("change", "#proof_picture", function () {
    var fileNameCheck = $("#proof_picture").val();

    var name = document.getElementById("proof_picture").files[0].name;
    var form_data = new FormData();
    var ext = name.split(".").pop().toLowerCase();

    if (jQuery.inArray(ext, ["gif", "png", "jpg", "jpeg"]) == -1) {
      //alert("Invalid Image File");
      var thead = "Image";
      var ttext = "Invalid Image File";
      var tcolor = "warning";
      Toast(thead, ttext, tcolor);
    } else {
      var oFReader = new FileReader();
      oFReader.readAsDataURL(document.getElementById("proof_picture").files[0]);
      var f = document.getElementById("proof_picture").files[0];
      var fsize = f.size || f.fileSize;
      if (fsize > 2000000) {
        //alert("Image File Size is very big");
        var thead = "Image";
        var ttext = "Image File Size is very big";
        var tcolor = "warning";
        Toast(thead, ttext, tcolor);
      } else {
        form_data.append(
          "proof_picture",
          document.getElementById("proof_picture").files[0]
        );
        $.ajax({
          url:
            "process/page-product-upload-UploadImage_Proof.php?name=" +
            s +
            "-" +
            makeid(4),
          method: "POST",
          data: form_data,
          contentType: false,
          cache: false,
          processData: false,
          success: function (data) {
            console.log(data);
            $("#get_proof_picture").html(data);
          },
        });
      }
    }
  });
}
function RestoreAd() {
  //restore-btn
  $(document).on("click", "#restore-btn", function () {
    var adid = $(this).val();
    console.log(adid);
    $.ajax({
      url: "process/page-seller-RestoreAd.php",
      method: "post",
      data: { adid: adid },
      success: function (data) {},
    });
  });
}
//
function Toast(thead, ttext, tcolor) {
  $.toast({
    heading: thead,
    text: ttext,
    showHideTransition: "slide",
    icon: tcolor,
    position: "top-center",
  });
}
