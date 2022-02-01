<?php
require_once('connection.php');
//dis product
function DisplayProduct()
{
    session_start();
    $userdetail = $_SESSION['userdetail'];
    global $con;
    $q1 = "SELECT * from user INNER JOIN seller ON seller.userid = user.userid WHERE (user.contact = '$userdetail' or user.email = '$userdetail');";
    $r1 = mysqli_query($con, $q1);

    while ($row1 = mysqli_fetch_assoc($r1)) {
        $sellerid = $row1['sellerid'];
    }
    $q2 = "SELECT * FROM product WHERE sellerid ='$sellerid'";
    $r2 = mysqli_query($con, $q2);

    while ($row2 = mysqli_fetch_assoc($r2)) {
        $reg = $row2['regularprice'];
        $sale = $row2['saleprice'];
        $code = $row2["code"];
        $productid = $row2['productid'];
        if ($code == "") {
            if ((int)$sale == 0) {
                $price =  '₱' . number_format($reg) . '';
            } else {
                $price =  '<p><font  size= "2" style="color:red; text-decoration: line-through;" > ₱' . number_format($reg) . '</font> ₱' . number_format($sale) . '</p> ';
            }
        } else {
            $q1 = "SELECT varsale,min(varregular) AS min_regularprice, max(varregular) AS max_regularprice, min(varsale) AS min_saleprice, max(varsale) AS max_saleprice FROM variation INNER JOIN mastervariation ON mastervariation.varcode = variation.varcode WHERE CODE = '$code'";
            $r1 = mysqli_query($con, $q1);
            while ($row1 = mysqli_fetch_assoc($r1)) {
                $min_regularprice = $row1['min_regularprice'];
                $max_regularprice = $row1['max_regularprice'];
                $min_saleprice = $row1['min_saleprice'];
                $max_saleprice = $row1['max_saleprice'];
                if ((int)$row1['varsale'] == 0) {
                    $price =  '<p> ₱' . number_format($min_regularprice) . ' - ₱' . number_format($max_regularprice) . '</p> ';
                } else {
                    $price =  '<p> ₱' . number_format($min_saleprice) . ' - ₱' . number_format($max_saleprice) . '</p> ';
                }
            }
        }

        echo
        '<div class="col-md-4" >
            <figure class="card card-product-grid">
                <div class="img-wrap">
                    <img src="images/product/img/' . $row2['imgurl'] . '">
                    
                </div> <!-- img-wrap.// -->
                <figcaption class="info-wrap">
                    <a href="#" class="title mb-2">' . $row2['productname'] . '</a>
                <div class="price-wrap mb-3">
                    <span class="price">' . $price . '</span>
                    <small class="text-muted">/per item</small>
                </div> <!-- price-wrap.// -->
                <button class="btn btn-outline-primary" value="' . $productid . '" data-toggle="modal" data-target="#edit-product-modal" id="edit-product"> <i class="fa fa-pen"></i> </button>
                <button class="btn btn-outline-danger" value="' . $productid . '" id="delete-productid"> <i class="fa fa-trash"></i>
                <button class="btn btn-outline-info ml-1" value="' . $productid . '" id="ad-product"  data-toggle="modal" data-target="#exampleModal"> <i class="fa fa-exclamation-circle"></i> </button>
                </button>
                <a href="page-detail-product.php?id=' . $productid . '" class="btn btn-primary view"> <i class="fa fa-eye "></i> View </a>
            </figure>	
        </div>

<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Advertisement</h5>
      
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        
      </div>
      <div class="modal-body">
      <p class="text-muted">Send your payment to the ff: <br> 
      BANK | Name:Hendrick Ouano | Account no.:000-000-000 <br>
      G CASH | Name:Hendrick Ouano | CELLPHONE #: 090000000
      
      </p>
      <div class="row" id="row-ad">
      <div class="col-12">
          <div class="form-group">
              <label for="productname" class="col-form-label">Product Name:</label>
              <input type="text" class="form-control" id="productname" readonly>
          </div>
      </div>
      <div class="col-6">
          <div class="form-group">
              <label for="recipient-name" class="col-form-label">Start Date:</label>
              <input type="date" class="form-control" id="start_date">
          </div>
      </div>
      <div class="col-6">
          <div class="form-group">
              <label for="recipient-name" class="col-form-label">Expiration Date:</label>
              <input type="date" class="form-control" id="exp_date">
          </div>
      </div>
      <div class="col-6">
          <div class="form-group">
              <label for="recipient-name" class="col-form-label">Ad Picture: <span style="font-size:2px" class="text-white" id="get_ad_picture"></span></label>
              <input type="file" class="form-control" id="ad_picture">
          </div>
      </div>
      <div class="col-6">
          <div class="form-group">
              <label for="recipient-name" class="col-form-label">Proof of Payment:<span style="font-size:2px" class="text-white" id="get_proof_picture"></span> </label>
              <input type="file" class="form-control" id="proof_picture">
          </div>
      </div>
  </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="request-ad">Request</button>
      </div>
    </div>
  </div>
</div>
        ';
    }
}
//pop ad
function PopulateAd($userdetail)
{
    global $con;
    $productid = $_POST['productid'];
    $q = "SELECT * FROM product where productid = '$productid'";
    $r = mysqli_query($con, $q);
    while ($row = mysqli_fetch_assoc($r)) {
        $productname = $row['productname'];
    }
    echo $productname;
}
function OnlyOneReview1($userdetail)
{
    global $con;
    $productid = $_POST['productid'];
    $q = "SELECT * FROM ad where productid = '$productid' and isExpired = 'no'";
    $r = mysqli_query($con, $q);
    $rowcount = mysqli_num_rows($r);
    while ($row = mysqli_fetch_assoc($r)) {
        $isPublished = $row['isPublished'];
        $adid = $row['adid'];
    }
    if ($rowcount >= 1) {
        if ($isPublished == "approved") {
            echo "Your Ad is APPROVED.";
        } else if (($isPublished == "rejected")) {
            $q5 = "SELECT * from ad where adid = '$adid'";
            $r5 = mysqli_query($con, $q5);
            while ($row5 = mysqli_fetch_assoc($r5)) {
                $remarks = $row5['remarks'];
            }
            echo "Your Ad is REJECTED. Try to request review again. Click&nbsp<button  data-dismiss='modal' value='" . $adid . "' class='btn btn-primary btn-sm' id='restore-btn'>here.</button>
            <br>REASON: $remarks ";
        } else {
            echo "Sorry You already requested.. Try to request another ad in Different product.";
        }
    } else {
        echo '<div class="col-12">
        <div class="form-group">
            <label for="productname" class="col-form-label">Product Name:</label>
            <input type="text" class="form-control" id="productname" readonly>
        </div>
    </div>
    <div class="col-6">
        <div class="form-group">
            <label for="recipient-name" class="col-form-label">Start Date:</label>
            <input type="date" class="form-control" id="start_date">
        </div>
    </div>
    <div class="col-6">
        <div class="form-group">
            <label for="recipient-name" class="col-form-label">Expiration Date:</label>
            <input type="date" class="form-control" id="exp_date">
        </div>
    </div>
    <div class="col-6">
        <div class="form-group">
            <label for="recipient-name" class="col-form-label">Ad Picture: <span style="font-size:2px" class="text-white" id="get_ad_picture"></span></label>
            <input type="file" class="form-control" id="ad_picture">
        </div>
    </div>
    <div class="col-6">
        <div class="form-group">
            <label for="recipient-name" class="col-form-label">Proof of Payment:<span style="font-size:2px" class="text-white" id="get_proof_picture"></span> </label>
            <input type="file" class="form-control" id="proof_picture">
        </div>
    </div>';
    }
}
function UploadImage_AdPic()
{
    if ($_FILES["ad_picture"]["name"] != '') {

        $test = explode('.', $_FILES["ad_picture"]["name"]);
        $ext = end($test);
        $name = $_GET['name'] . '.' . $ext;
        $location = "../images/product/ad/" . $name;
        move_uploaded_file($_FILES["ad_picture"]["tmp_name"], $location);
        echo "$name";
    }
}

function UploadImage_Proof()
{
    if ($_FILES["proof_picture"]["name"] != '') {

        $test = explode('.', $_FILES["proof_picture"]["name"]);
        $ext = end($test);
        $name = $_GET['name'] . '.' . $ext;
        $location = "../images/product/proof/" . $name;
        move_uploaded_file($_FILES["proof_picture"]["tmp_name"], $location);
        echo "$name";
    }
}
function RequestAd()
{
    global $con;
    $productid = $_POST['productid'];
    $start_date = $_POST['start_date'];
    $exp_date = $_POST['exp_date'];
    $ad_picture = $_POST['ad_picture'];
    $proof_picture = $_POST['proof_picture'];
    $q = "INSERT INTO ad (productid,start_date,exp_date,isExpired,proof,remarks,adpic,isPublished) VALUES ('$productid','$start_date','$exp_date','no','$proof_picture','','$ad_picture','no')";
    $r = mysqli_query($con, $q);
    echo $r;
}

function UploadProduct()
{
    if ($_FILES["product-image"]["name"] != '') {

        $test = explode('.', $_FILES["product-image"]["name"]);
        $ext = end($test);
        $name = $_GET['name'] . '.' . $ext;
        $location = "../images/product/img/" . $name;
        move_uploaded_file($_FILES["product-image"]["tmp_name"], $location);
        echo "$name";
    }
}
function AddProduct()
{
    session_start();
    $userdetail = $_SESSION['userdetail'];
    global $con;
    $q1 = "SELECT * from user INNER JOIN seller ON seller.userid = user.userid WHERE (user.contact = '$userdetail' or user.email = '$userdetail');";
    $r1 = mysqli_query($con, $q1);
    while ($row1 = mysqli_fetch_assoc($r1)) {
        $sellerid = $row1['sellerid'];
    }
    $q = "SELECT * FROM mastervariation  INNER JOIN variation ON variation.varcode = mastervariation.varcode WHERE sellerid = '$sellerid' AND isDone = 'no'  ";
    $r = mysqli_query($con, $q);
    $rowcount = mysqli_num_rows($r);
    if ($rowcount >= 2) {
        echo '1';
    } else {
        echo '0';
    }
}
function AddProduct_Server()
{
    session_start();
    $userdetail = $_SESSION['userdetail'];
    global $con;
    $q1 = "SELECT * from user INNER JOIN seller ON seller.userid = user.userid WHERE (user.contact = '$userdetail' or user.email = '$userdetail');";
    $r1 = mysqli_query($con, $q1);

    while ($row1 = mysqli_fetch_assoc($r1)) {
        $userid = $row1['sellerid'];
    }

    $sellerid = $userid;
    $productname = $_POST['productname'];
    $productimage = $_POST['productimage'];
    $maindescription = $_POST['maindescription'];
    $regularprice = $_POST['regularprice'];
    $saleprice = $_POST['saleprice'];
    $sku = $_POST['sku'];
    $stock = $_POST['stock'];
    $weight = $_POST['weight'];
    $length = $_POST['length'];
    $width = $_POST['width'];
    $height = $_POST['height'];
    $shippingtype = $_POST['shippingtype'];
    $shortdescription = $_POST['shortdescription'];
    $category = $_POST['category'];
    $switchStatus = $_POST['switchStatus'];

    $q2 = "SELECT * FROM mastervariation where sellerid = '$userid' and isDone = 'no'";
    $r2 = mysqli_query($con, $q2);
    while ($row2 = mysqli_fetch_assoc($r2)) {
        $code = $row2['code'];
    }
    if ($switchStatus == true) {
    } else {
        $code = "";
    }


    echo "$productname $productimage $maindescription $regularprice 
    $saleprice $sku $stock $weight $length $width $height $shippingtype $shortdescription";

    $q = "INSERT INTO product (sellerid,productname, imgurl,description,regularprice,
    saleprice,sku,stock,weight,length,width,height,shippingtype,shortdescription,categoryid,code) 
    values ('$sellerid','$productname','$productimage','$maindescription','$regularprice',
    '$saleprice','$sku','$stock','$weight','$length','$width','$height','$shippingtype','$shortdescription','$category','$code')";
    mysqli_query($con, $q);
    $q4 = "UPDATE mastervariation set isDone = 'yes' where sellerid = '$userid' ";
    mysqli_query($con, $q4);
}

//display product
function GetStarted($userdetail)
{
    global $con;

    $q1 = "SELECT * from user INNER JOIN seller ON seller.userid = user.userid WHERE (user.contact = '$userdetail' or user.email = '$userdetail');";
    $r1 = mysqli_query($con, $q1);
    $var = '';
    while ($row1 = mysqli_fetch_assoc($r1)) {
        $sellerid = $row1['sellerid'];
    }
    $q = "SELECT * from mastervariation where sellerid = '$sellerid' and isDone = 'no' ";
    $r = mysqli_query($con, $q);
    $rowcount = mysqli_num_rows($r);
    if ($rowcount == 0) {
        //if exist
        $var = '<button class="btn btn-primary btn-block" id="get-started"><i class="fa fa-exclamation-circle"></i> Get Started</button>';
    }
    echo $var;
}

function DisplayAddVariation($userdetail)
{
    echo '
        <div class="form-group">
            <label for="recipient-name" class="col-form-label"><span id="mid-line2">Variation: <div id="master-variation-code"></div></span></label>
            <div class="d-flex">
                <input type="text" class="form-control" id="add-master-variation" placeholder="Add Variation">
                <button class="btn btn-primary" id="add-parent-variation">Add</button>
            </div>
        </div>

        ';
}
function DisplayTableVariation($userdetail)
{
    global $con;
    $q1 = "SELECT * from user INNER JOIN seller ON seller.userid = user.userid WHERE (user.contact = '$userdetail' or user.email = '$userdetail');";
    $r1 = mysqli_query($con, $q1);
    while ($row1 = mysqli_fetch_assoc($r1)) {
        $sellerid = $row1['sellerid'];
    }

    $q = "SELECT * FROM mastervariation where sellerid = '$sellerid' and isDone = 'no' order by mastervariationid desc";
    $r = mysqli_query($con, $q);

    $value = '';

    while ($row = mysqli_fetch_assoc($r)) {
        $name = $row['name'];
        $varcode = $row['varcode'];
        $mastervariationid = $row['mastervariationid'];
        if ($name != "") {
            $value .= '
            <table class="table table-bordered">
                <thead>
                    <tr id="edit-single-table-' . $mastervariationid . '" style="background-color:#' . $row['randomcolor'] . ' !important;">
                        <th colspan="5" class="text-white text-center" >' . $row['name'] . '</th>
                        <th colspan="1"><button class="btn btn-outline-danger text-white btn-sm" style="border-style:none;" value="' . $mastervariationid . '" id="delete-table-varitation"><i class="fa fa-trash"></i></button></button></th>
                    </tr>
                    <tr>
                        <th class="col-md-1">#</th>
                        <th class="col-md-4">Name</th>
                        <th class="col-md-2">Regular</th>
                        <th class="col-md-2">Sale</th>
                        <th class="col-md-2">Fee</th>
                        <th class="col-md-1">#</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td></td>
                        <td><input type="text" id="variationname-' . $varcode . '" placeholder="Name"></td>
                        <td><input type="text" id="varregular-' . $varcode . '" placeholder="Regular.."></td>
                        <td><input type="text" id="varsale-' . $varcode . '" placeholder="SalePri.."></td>
                        <td><input type="text" id="shippingtype-' . $varcode . '" placeholder="Deliv.."></td>
                        <td><button class="btn btn-primary btn-sm" value="' . $varcode . '" id="add-child-varation"><i class="fa fa-plus" ></i></button></button></td>
                    </tr>
                    ';
            $q3 = "SELECT * FROM variation where varcode = '$varcode' order by variationid desc";
            $r3 = mysqli_query($con, $q3);
            while ($row3 = mysqli_fetch_assoc($r3)) {
                $variationid = $row3['variationid'];
                $value .= '
                <tr id="edit-single-variation-' . $variationid . '">
                    <td><button class="btn btn-warning btn-sm"  value = "' . $variationid . '" id = "edit-child-variation"><i class="fa fa-edit"></i></button></button></td>
                    <td>' . $row3['variationname'] . '</td>
                    <td>' . $row3['varregular'] . 'PHP</td>
                    <td>' . $row3['varsale'] . 'PHP</td>
                    <td>' . $row3['shippingtype'] . 'PHP</td>
                    <td><button class="btn btn-danger btn-sm" value="' . $variationid . '" id="delete-child-variation"><i class="fa fa-trash"  ></i></button></button></td>
                </tr>
                ';
            }
            $value .= '
                </tbody>
            </table>';
        }
    }
    echo $value;
}
function GetStartedBtn($userdetail)
{
    $makeid = $_POST['makeid'];
    $randomColor = $_POST['randomColor'];
    global $con;
    $q1 = "SELECT * from user INNER JOIN seller ON seller.userid = user.userid WHERE (user.contact = '$userdetail' or user.email = '$userdetail');";
    $r1 = mysqli_query($con, $q1);
    while ($row1 = mysqli_fetch_assoc($r1)) {
        $sellerid = $row1['sellerid'];
    }
    $q = "INSERT INTO mastervariation (name,code,date,isDone,sellerid,randomcolor) values ('',' $makeid','','no','$sellerid','$randomColor')";
    $r = mysqli_query($con, $q);
    echo "$makeid";
}
function AddMasterVariation($userdetail)
{
    $makeid = $_POST['makeid'];
    $randomColor = $_POST['randomColor'];
    $today = date("Y-m-d H:i:s");
    $name = $_POST['name'];

    global $con;
    $q1 = "SELECT * from user INNER JOIN seller ON seller.userid = user.userid WHERE (user.contact = '$userdetail' or user.email = '$userdetail');";
    $r1 = mysqli_query($con, $q1);
    while ($row1 = mysqli_fetch_assoc($r1)) {
        $sellerid = $row1['sellerid'];
    }

    $q = "SELECT * FROM mastervariation where sellerid = '$sellerid' and isDone = 'no'";
    $r = mysqli_query($con, $q);
    while ($row = mysqli_fetch_assoc($r)) {
        $code = $row['code'];
    }
    $q2 = "INSERT INTO mastervariation (name,code,date,isDone,sellerid,randomcolor,varcode) values ('$name','$code','$today','no','$sellerid','$randomColor','$makeid')";
    $r2 = mysqli_query($con, $q2);

    echo "$makeid";
}
function AddChildVariation($userdetail)
{
    global $con;
    $varcode = $_POST['varcode'];
    $variationname = $_POST['variationname'];
    $varregular = (float)$_POST['varregular'];
    $varsale = (float)$_POST['varsale'];
    $shippingtype = $_POST['shippingtype'];
    $today = date("Y-m-d H:i:s");
    $q = "INSERT INTO variation (variationname,varregular,varsale,shippingtype,varcode,date) values ('$variationname','$varregular','$varsale','$shippingtype','$varcode','$today')";
    $r = mysqli_query($con, $q);
    echo 'hahaha';
}
function DeleteTableVariation($userdetail)
{
    $mastervariationid = $_POST['mastervariationid'];

    global $con;
    $q1 = "DELETE FROM mastervariation where mastervariationid = '$mastervariationid'";
    mysqli_query($con, $q1);
    echo 'deleted';
}

function DeleteChildVariation($userdetail)
{
    $variationid = $_POST['variationid'];

    global $con;
    $q1 = "DELETE FROM variation where variationid = '$variationid'";
    mysqli_query($con, $q1);
    echo 'deleted';
}

function EditTableVariation($userdetail)
{
    $variationid = $_POST['variationid'];
    global $con;
    $q1 = "SELECT * from variation where variationid = '$variationid'  ";
    $r1 = mysqli_query($con, $q1);
    while ($row1 = mysqli_fetch_assoc($r1)) {
        echo '<td><button class="btn btn-outling-secondary btn-sm" value="' . $variationid . '" id="cancel-child-varation"><i class="fa fa-times" ></i></button></button></td>
    <td><input type="text" value="' . $row1['variationname'] . '" id="variationname-' . $variationid . '" placeholder="Name"></td>
    <td><input type="text" value="' . $row1['varregular'] . '" id="varregular-' . $variationid . '" placeholder="Regular.."></td>
    <td><input type="text" value="' . $row1['varsale'] . '" id="varsale-' . $variationid . '" placeholder="SalePri.."></td>
    <td><input type="text" value="' . $row1['shippingtype'] . '" id="shippingtype-' . $variationid . '" placeholder="Shippin.."></td>
    <td><button class="btn btn-success btn-sm" value="' . $variationid . '" id="update-child-variation"><i class="fa fa-check" ></i></button></button></td>';
    }
}
function UpdateChildVariation($userdetail)
{
    //
    $variationid = $_POST['variationid'];
    $variationname = $_POST['variationname'];
    $varregular = $_POST['varregular'];
    $varsale = $_POST['varsale'];
    $shippingtype = $_POST['shippingtype'];

    global $con;
    $q1 = "UPDATE variation set variationname = '$variationname', varregular = '$varregular',varsale = '$varsale',shippingtype = '$shippingtype' WHERE variationid = '$variationid'";
    mysqli_query($con, $q1);

    echo 'updated';
}
function DisplaySingleVariation($userdetail)
{
    $variationid = $_POST['variationid'];
    global $con;
    $q1 = "SELECT * from variation where variationid = '$variationid'  ";
    $r1 = mysqli_query($con, $q1);
    while ($row1 = mysqli_fetch_assoc($r1)) {
        echo '
                    <td><button class="btn btn-warning btn-sm"  value = "' . $variationid . '" id = "edit-child-variation"><i class="fa fa-edit"></i></button></button></td>
                    <td>' . $row1['variationname'] . '</td>
                    <td>' . $row1['varregular'] . 'PHP</td>
                    <td>' . $row1['varsale'] . 'PHP</td>
                    <td>' . $row1['shippingtype'] . 'PHP</td>
                    <td><button class="btn btn-danger btn-sm" value="' . $variationid . '" id="delete-child-variation"><i class="fa fa-trash"  ></i></button></button></td>
    ';
    }
}
function CheckVariation($userdetail)
{
    global $con;
    $q1 = "SELECT * from user INNER JOIN seller ON seller.userid = user.userid WHERE (user.contact = '$userdetail' or user.email = '$userdetail');";
    $r1 = mysqli_query($con, $q1);
    while ($row1 = mysqli_fetch_assoc($r1)) {
        $sellerid = $row1['sellerid'];
    }
    $q = "SELECT * FROM mastervariation  INNER JOIN variation ON variation.varcode = mastervariation.varcode WHERE sellerid = '$sellerid' AND isDone = 'no'  ";
    $r = mysqli_query($con, $q);
    $rowcount = mysqli_num_rows($r);
    if ($rowcount == 1) {
        echo '1';
    } else {
        echo '0';
    }
}


//edit         
function EditProduct($userdetail)
{
    $productid = $_POST['productid'];
    global $con;
    $q1 = "SELECT * from user INNER JOIN seller ON seller.userid = user.userid WHERE (user.contact = '$userdetail' or user.email = '$userdetail');";
    $r1 = mysqli_query($con, $q1);
    while ($row1 = mysqli_fetch_assoc($r1)) {
        $sellerid = $row1['sellerid'];
    }
    $q2 = "SELECT * FROM product where productid = '$productid'";
    $r2 = mysqli_query($con, $q2);
    while ($row2 = mysqli_fetch_assoc($r2)) {
        $productname = $row2['productname'];

        $description = $row2['description'];
        $regularprice = $row2['regularprice'];
        $saleprice = $row2['saleprice'];
        $sku = $row2['sku'];
        $stock = $row2['stock'];
        $weight  = $row2['weight'];
        $length = $row2['length'];
        $width = $row2['width'];
        $height = $row2['height'];
        $shippingtype = $row2['shippingtype'];
        $shortdescription    = $row2['shortdescription'];
        $categoryid = $row2['categoryid'];
        $code = $row2['code'];
    }
    $coderes = "0";
    if ($code != "") {
        $coderes = "1";
    } else {
        $coderes = "0";
    }
    $res = array(
        'productname' => "$productname",
        'description' => "$description",
        'regularprice' => "$regularprice",
        'saleprice' => "$saleprice",
        'sku' => "$sku",
        'stock' => "$stock",
        'weight' => "$weight",
        'length' => "$length",
        'width' => "$width",
        'height' => "$height",
        'shippingtype' => "$shippingtype",
        'shortdescription' => "$shortdescription",
        'categoryid' => "$categoryid",
        'coderes' => "$coderes",
        'code' => "$code"
    );
    echo json_encode($res);
}
function DeleteProduct($userdetail)
{
    global $con;
    $productid = $_POST['productid'];
    $q = "DELETE FROM product where productid = '$productid'";
    $r = mysqli_query($con, $q);
    echo $r;
}
function UploadProductEdit()
{
    if ($_FILES["edit-product-image"]["name"] != '') {

        $test = explode('.', $_FILES["edit-product-image"]["name"]);
        $ext = end($test);
        $name = $_GET['name'] . '.' . $ext;
        $location = "../images/product/img/" . $name;
        move_uploaded_file($_FILES["edit-product-image"]["tmp_name"], $location);
        echo "$name";
    }
}
function SaveProduct($userdetail)
{
    $productid = $_POST['productid'];
    $productname = $_POST['productname'];
    $getimagename = $_POST['getimagename'];
    $productimage = $_POST['productimage'];
    $maindescription = $_POST['maindescription'];
    $regularprice = $_POST['regularprice'];
    $saleprice = $_POST['saleprice'];
    $sku = $_POST['sku'];
    $stock = $_POST['stock'];
    $weight = $_POST['weight'];
    $length = $_POST['length'];
    $width = $_POST['width'];
    $height = $_POST['height'];
    $shippingtype = $_POST['shippingtype'];
    $shortdescription = $_POST['shortdescription'];
    $categoryid = $_POST['categoryid'];
    global $con;

    if ($productimage != "") {
        $q = "UPDATE product set 
        productname = '$productname',
        imgurl = '$getimagename',
        description = '$maindescription',
        regularprice = '$regularprice',
        saleprice = '$saleprice',
        sku = '$sku',
        stock = '$stock',
        weight = '$weight',
        length = '$length',
        width = '$width',
        height = '$height',
        shippingtype = '$shippingtype',
        shortdescription = '$shortdescription',
        categoryid = '$categoryid'
    
        
         where productid = '$productid'";
        $r = mysqli_query($con, $q);
        echo "$r";
    } else {
        $q = "UPDATE product set 
        productname = '$productname',
        description = '$maindescription',
        regularprice = '$regularprice',
        saleprice = '$saleprice',
        sku = '$sku',
        stock = '$stock',
        weight = '$weight',
        length = '$length',
        width = '$width',
        height = '$height',
        shippingtype = '$shippingtype',
        shortdescription = '$shortdescription',
        categoryid = '$categoryid'
    
        
         where productid = '$productid'";
        $r = mysqli_query($con, $q);
        echo "$r";
    }
}
function RestoreAd($userdetail)
{
    global $con;
    $adid = $_POST['adid'];
    $q = "UPDATE ad set isExpired = 'yes' where adid = '$adid' ";
    $r = mysqli_query($con, $q);
}
