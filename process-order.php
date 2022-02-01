<?php
require_once('connection.php');

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
    $price = "0";
    $res = '';
    $value = '';
    while ($row2 = mysqli_fetch_assoc($r2)) {
        $variationid = $row2['variationid'];
        $qty_ = $row2['qty'];
        //$tprice += ((float)$row2['regularprice'] * (float)$row2['qty']);
        if ((int)$variationid == 0) {
            $regular_ = $row2['regularprice'];
            $saleprice_ = $row2['saleprice'];

            if ((int)$saleprice_ == 0) {
                $price = $regular_;
                $res = ' <p class="card-text float-left">Price: ' . '₱' . (number_format($price)) . ' <br> Qty: ' . (float)$row2['qty'] . '</p>';
            } else {
                $price = $saleprice_;
                $res = ' <p class="card-text float-left">Price: ' . '<span style="color:red;text-decoration:line-through;font-size:12px">₱' . $regular_ . '</span> ₱' . (number_format($price)) . ' <br> Qty: ' . (float)$row2['qty'] . '</p>';
            }
            $tprice += ((float)$price * $qty_);
        } else {
            $q4 = "SELECT * FROM variation where variationid = '$variationid'";
            $r4 = mysqli_query($con, $q4);
            while ($row4 = mysqli_fetch_assoc($r4)) {
                $varregular_ = $row4["varregular"];
                $varsale_ = $row4["varsale"];
            }
            if ((int)$varsale_ == 0) {
                $price =  $varregular_;
                $res = ' <p class="card-text float-left">Price: ' . '₱' . (number_format($price)) . ' <br> Qty: ' . (float)$row2['qty'] . '</p>';
            } else {
                $price =  $varsale_;
                $res = ' <p class="card-text float-left">Price: ' . '<span style="color:red;text-decoration:line-through;font-size:12px">₱' . $varregular_ . '</span> ₱' . (number_format($price)) . ' <br> Qty: ' . (float)$row2['qty'] . '</p>';
            }
            $tprice += ((float)$price * $qty_);
        }
        $value .= '<div class="card text-center m-2">
        <div class="card-header">
            <div class="row">
                <div class="col"><img src="images/product/img/' . $row2['imgurl'] . '" style="width: 100%;"></div>
                <div class="col">' . $row2['productname'] . '</div>
            </div>
            </div>
            <div class="card-footer text-muted pb-0 bt-1">' . $res . '
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
function PopulateNotif($userdetail)
{

    global $con;
    $q = "SELECT * FROM user where contact = '$userdetail' or email = '$userdetail' ";
    $r = mysqli_query($con, $q);
    while ($row = mysqli_fetch_assoc($r)) {
        $userid = $row['userid'];
    }
    $value = '';
    $q2 = "SELECT * FROM notification INNER JOIN rider on rider.riderid = notification.riderid where notification.userid = '$userid' order by notificationid desc";
    $r2 = mysqli_query($con, $q2);
    $rowcount2 = mysqli_num_rows($r2);

    $value .= '
    <li >Notifications</li>';
    while ($row2 = mysqli_fetch_assoc($r2)) {
        if ($row2['isRead_User'] == "no") {
            $color = "#DEE7E0";
        } else {
            $color = "#fff";
        }

        $value .= '<li style="background-color:' . $color . '" class="mt-2"> 
    <div class="row">
        <div class="col-md-6"><span style="font-size: 0.8rem;">From:' . $row2['ridername'] . '</span></div>
    </div>
    <div class="row">
        <div class="col-md-12">' . $row2['notificationname'] . '</div>
    </div>
        <div class="row">
            <div class="col-md-6"><span class="text-muted" style="font-size: 0.8rem;">Date:' . date("m-d-y", strtotime($row2['notificationdate'])) . '</span></div>
            <div class="col-md-6"><span class="text-muted" style="font-size: 0.8rem;">Time:' . date("h:i A", strtotime($row2['notificationdate'])) . '</span></div>
        </div>
        <div class="row">
        <div class="col-md-11" style="background-color:gray; height:1px;"></span></div>
    </div>
    </li>
';
    }
    echo $value;
}

function PopCountNotif($userdetail)
{
    global $con;
    $q = "SELECT * FROM user where contact = '$userdetail' or email = '$userdetail' ";
    $r = mysqli_query($con, $q);
    while ($row = mysqli_fetch_assoc($r)) {
        $userid = $row['userid'];
    }
    $q2 = "SELECT * FROM notification where userid = '$userid' and isRead_User = 'no' order by notificationid desc";
    $r2 = mysqli_query($con, $q2);
    $rowcount2 = mysqli_num_rows($r2);
    echo $rowcount2;
}
function updateReadNotif($userdetail)
{
    global $con;
    $q = "SELECT * FROM user where contact = '$userdetail' or email = '$userdetail' ";
    $r = mysqli_query($con, $q);
    while ($row = mysqli_fetch_assoc($r)) {
        $userid = $row['userid'];
    }
    $q2 = "UPDATE notification set isRead_User = 'yes' where userid = '$userid' ";
    $r2 = mysqli_query($con, $q2);
}
function FillDataList($userdetail)
{
    global $con;
    $q = "SELECT * FROM product order by productid desc";
    $r = mysqli_query($con, $q);
    $value = '';
    $value .= '	
    <datalist class=".filldlist" id="dlist" mulitple size="3">';
    while ($row = mysqli_fetch_assoc($r)) {
        $value .= '
    <option value="' . $row['productid'] . '">' . $row['productname'] . '</option>';
    }
    $value .=  '
    </datalist>';
    echo $value;
}
