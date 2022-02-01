<?php
require_once('../include/connection.php');

function AddToCart($userdetail)
{
    global $con;
    $today = date("Y-m-d H:i:s");
    $productid = $_POST['productid'];
    $poid = $_POST['poid'];
    $qty = $_POST['qty'];
    $variationid = $_POST['variationid'];
    $q = "SELECT * FROM user where contact = '$userdetail' or email = '$userdetail' ";
    $r = mysqli_query($con, $q);
    while ($row = mysqli_fetch_assoc($r)) {
        $userid = $row['userid'];
    }

    $q3 = "SELECT * FROM product where productid = '$productid' ";
    $r3 = mysqli_query($con, $q3);
    while ($row3 = mysqli_fetch_assoc($r3)) {
        $stocks = $row3['stock'];
    }
    if ((int)$stocks == 0) {
        echo "No More Stocks!";
    } else {
        $q2 = "INSERT INTO userorder (poid,productid,userid,qty,date,isDone,masterpoid,variationid) values ('$poid','$productid','$userid','$qty','$today','no','','$variationid') ";
        $r2 = mysqli_query($con, $q2);
        echo "Add to Cart!";
    }
}
/////
function DisplaySideCart($userdetail)
{
    global $con;
    $q = "SELECT * FROM user where contact = '$userdetail' or email = '$userdetail' ";
    $r = mysqli_query($con, $q);
    $tprice = 0;
    $tqty = 0;
    while ($row = mysqli_fetch_assoc($r)) {
        $userid = $row['userid'];
    }
    $q2 = "SELECT * FROM userorder INNER JOIN product on product.productid = userorder.productid where userid = '$userid' and isDone ='no'";
    $r2 = mysqli_query($con, $q2);
    $q3 = "SELECT * FROM ";
    $r3 = mysqli_query($con, $q3);
    $value = '';
    while ($row2 = mysqli_fetch_assoc($r2)) {
        $tprice += ((float)$row2['regularprice'] * (float)$row2['qty']);
        $value .= '<div class="card text-center m-2">
        <div class="card-header">
            <div class="row">
                <div class="col"><img src="images/product/img/' . $row2['imgurl'] . '" style="width: 100%;"></div>
                <div class="col">' . $row2['productname'] . '</div>
            </div>
            </div>
            <div class="card-footer text-muted pb-0 bt-1">
            <p class="card-text float-left">Price: ' . (float)$row2['regularprice'] . ' <br> Qty: ' . (float)$row2['qty'] . '</p>
            <button class="btn btn-light float-right" value="' . $row2['userorderid'] . '" id="delete-side-cart"><i class="fas fa-trash"></i></button>
            </div>
        </div>';
    }
    $value .= '  
    <div class="card text-center m-2">
        <div class="card-footer text-muted pb-0 bt-1">
        <p class="card-text">Total: ' . ((float)$tprice) . 'pesos <br> <button  class="btn btn-light mt-1 mb-2" id="check-out"><i class="fas fa-check">Check Out</i></button></p>
        </div>
    </div>';
    echo $value;
}
function DeleteSideCart($userdetail)
{
    global $con;
    $userorderid = $_POST['userorderid'];
    $q = "DELETE FROM userorder where userorderid = '$userorderid'";
    $r = mysqli_query($con, $q);
    echo $r;
}
function SideCartCount($userdetail)
{
    global $con;
    $q = "SELECT * FROM user where contact = '$userdetail' or email = '$userdetail' ";
    $r = mysqli_query($con, $q);
    while ($row = mysqli_fetch_assoc($r)) {
        $userid = $row['userid'];
    }
    $q2 = "SELECT * FROM userorder where userid = '$userid' and isDone = 'no'";
    $r2 = mysqli_query($con, $q2);
    $rowcount2 = mysqli_num_rows($r2);
    echo $rowcount2;
}
////////variation
function VariationSelect($userdetail)
{
    $variationid = $_POST['variationid'];
    global $con;
    $q = "SELECT * FROM variation where variationid = '$variationid'";
    $r = mysqli_query($con, $q);
    while ($row = mysqli_fetch_assoc($r)) {
        $regularprice_set = (float)$row['varregular'];
        $saleprice_set = (float)$row['varsale'];
    }
    ////
    $regularprice_whole = floor($regularprice_set);
    $regularprice_dec = $regularprice_set - $regularprice_whole;

    $saleprice_whole = floor($saleprice_set);
    $saleprice_dec = $saleprice_set - $saleprice_whole;
    if ($regularprice_dec != 0) {
        $regularprice = number_format($regularprice_set, 2);
    } else {
        $regularprice = number_format($regularprice_set);
    }

    if ($saleprice_dec != 0) {
        $saleprice = number_format($saleprice_set, 2);
    } else {
        $saleprice = number_format($saleprice_set);
    }
    // if ($saleprice_set % 1 == 0) {
    //     //no dec
    //     $saleprice = number_format($saleprice_set);
    // } else {
    //     $saleprice = number_format($saleprice_set, 2);
    // }
    ////
    if ($saleprice_set == 0) {
        $res = '<var class="price h4"> ₱' . $regularprice . '</var>';
    } else {
        $res = '<var class="price h4"><font size="4" style = "color:red;text-decoration:line-through">₱' . $regularprice . '</font> ₱' . $saleprice . '</var> ';
    }
    echo "$res";
}
function StockDisplay($userdetail)
{
    global $con;
    $productid = $_POST['productid'];
    $q3 = "SELECT * FROM product where productid = '$productid' ";
    $r3 = mysqli_query($con, $q3);
    while ($row3 = mysqli_fetch_assoc($r3)) {
        $stocks = $row3['stock'];
    }
    if ((int)$stocks <= 0) {
        echo "0";
    } else {
        echo "1";
    }
}
function DisplayCountOrders($userdetail)
{
    global $con;
    $rowcount3 = 0;
    $productid = $_POST['productid'];
    $q3 = "SELECT * FROM userorder WHERE productid = '$productid' ";
    $r3 = mysqli_query($con, $q3);
    $rowcount3 = mysqli_num_rows($r3);
    echo $rowcount3;
}
function DisplayReview($userdetail)
{
    global $con;
    $productid = $_POST['productid'];
    $q = "SELECT * FROM user where contact = '$userdetail' or email = '$userdetail' ";
    $r = mysqli_query($con, $q);
    while ($row = mysqli_fetch_assoc($r)) {
        $userid = $row['userid'];
    }
    $rating_count = 0;
    $rating_percent = 0;
    $rating_total = 0;
    $q3 = "SELECT * from review inner join user on user.userid = review.userid where productid = '$productid' order by reviewid desc";
    $r3 = mysqli_query($con, $q3);
    $rowcount3 = mysqli_num_rows($r3);
    while ($row3 = mysqli_fetch_assoc($r3)) {
        $rating_count_ = $row3['rating'];
        $rating_count += (float)$rating_count_;
    }
    if ($rowcount3 == 0) {
        $rating_total = 0;
    } else {
        $rating_total = ((float)$rating_count / (float)$rowcount3);
    }

    $rating_percent = ($rating_total / 5) * 100;
    $value = '';

    $value .= '<div class="box">
        <div class="row">
          <div class="col-md-12">
            <header class="section-heading">
              <button class="btn btn-outline-primary btn-block mb-4 add-review" data-toggle="modal" data-target="#exampleModal">Add Review</button>
              <div class="rating-wrap">
                <ul class="rating-stars stars-lg">
                  <li style="width:' . $rating_percent . '%" class="stars-active">
                    <img src="others/images/icons/stars-active.svg" alt="">
                  </li>
                  <li>
                    <img src="others/images/icons/starts-disable.svg" alt="">
                  </li>
                </ul>
                <strong class="label-rating text-lg"> ' . round($rating_total, 1) . '/5.0

                  <span class="text-muted">| ' . $rowcount3 . ' reviews</span>
                </strong>
              </div>
            </header>
            <div>
        ';
    $q1 = "SELECT * from review inner join user on user.userid = review.userid where productid = '$productid' order by reviewid desc";
    $r1 = mysqli_query($con, $q1);
    while ($row1 = mysqli_fetch_assoc($r1)) {

        $rating_ = $row1['rating'];
        $rating = (((float)$rating_ / 5) * 100);
        $comment = "";
        if ($rating_ < 3) {
            $comment = "Ok";
        } else if ($rating_total  == 5) {
            $comment = "Best!";
        } else {
            $comment = "Good!";
        }
        $value .= '
              <article class="box mb-3">
                <div class="icontext w-100">
                  <img src="images/profile/' . $row1['imgurl'] . '" class="img-xs icon rounded-circle">
                  <div class="text">
                    <span class="date text-muted float-md-right">' . date("M-d-Y h:i A", strtotime($row1['date'])) . '</span>
                    <h6 class="mb-1">' . $row1['firstname'] . '</h6>
                    <ul class="rating-stars">
                      <li style="width:' . $rating . '%" class="stars-active">
                        <img src="others/images/icons/stars-active.svg" alt="">
                      </li>
                      <li>                  
                        <img src="others/images/icons/starts-disable.svg" alt="">
                      </li>
                    </ul>
                    <span class="label-rating text-warning">' . $comment . '</span>
                  </div>
                </div> 
                <div class="mt-3">
                  <p>
                  ' . $row1['comment'] . '
                  </p>
                </div>
              </article>
              ';
    }
    $value .= '
            </div>
          </div>
        </div>

      </div>';

    echo $value;
}
function AddReview($userdetail)
{
    global $con;
    $q = "SELECT * FROM user where contact = '$userdetail' or email = '$userdetail' ";
    $r = mysqli_query($con, $q);
    while ($row = mysqli_fetch_assoc($r)) {
        $userid = $row['userid'];
    }

    $today = date("Y-m-d H:i:s");
    $productid = $_POST['productid'];
    $rating = $_POST['sim'];
    $comment = $_POST['comment'];
    $q2 = "SELECT * FROM review where productid = '$productid' and userid = '$userid'";
    $r2 = mysqli_query($con, $q2);
    $rowcount2 = mysqli_num_rows($r2);
    if ($rowcount2 == 1) {
        echo "You already added review of this Product.";
    } else {
        $q1 = "INSERT INTO review (userid,comment,date,productid,rating) values ('$userid','$comment','$today','$productid','$rating')";
        $r1 = mysqli_query($con, $q1);
        echo "Thank you for your ratings..";
    }
}
