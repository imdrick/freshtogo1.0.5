<?php
require_once('connection.php');
error_reporting(0);
function ConfirmPayment($userdetail)
{
    $today = date("Y-m-d H:i:s");

    $masterpoid = $_POST['masterpoid'];
    $fullname = $_POST['fullname'];
    $contact = $_POST['contact'];
    $address1 = $_POST['address1'];
    $postalcode = $_POST['postalcode'];
    $label = $_POST['label'];
    $address2 = $_POST['address2'];
    $total = $_POST['total'];
    $deliveryid = $_POST['mop'];
    $shippingfee = $_POST['shippingfee'];
    $nettotal = $_POST['nettotal'];
    $discount = $_POST['discount'];
    $subtotal = $_POST['total'];
    $mop2 = $_POST['mop2'];
    $isPaid = "no";
    $status = "Queuing";
    $datestatus = "$today";
    $datecreated = "$today";
    $remarks = "";
    $esttime = "";
    $riderid = "";
    $sellerid = "";
    $stock = "";
    $deliverybox_ = $_POST['deliverybox'];
    if ($deliverybox_ == "1") {
        $deliverybox = "Medium Box";
    }
    else if ($deliverybox_ == "2") {
        $deliverybox = "Large Box";
    }
    else{
        $deliverybox = "Normal Box";
    }
    //
    global $con;
    $q = "SELECT * FROM user where contact = '$userdetail' or email = '$userdetail' ";
    $r = mysqli_query($con, $q);
    while ($row = mysqli_fetch_assoc($r)) {
        $userid = $row['userid'];
    }
    $q2 = "INSERT INTO masterlist (masterpoid,fullname,contact,address1,postalcode,label,address2,deliveryid,total,shippingfee,discount,nettotal,mop,isPaid,status,datestatus,datecreated,remarks,esttime,riderid,userid,deliverybox) 
    VALUES ('$masterpoid','$fullname','$contact','$address1','$postalcode','$label','$address2','$deliveryid','$total','$shippingfee','$discount','$nettotal','Cash On Delivery','$isPaid','$status','$datestatus','$datecreated','$remarks','$esttime','$riderid','$userid','$deliverybox')";
    $r2 = mysqli_query($con, $q2);

    $q4 = "SELECT * FROM userorder INNER JOIN product ON product.productid = userorder.productid WHERE userid = '$userid' AND isDone = 'no'";
    $r4 = mysqli_query($con, $q4);
    $aw = '';
    while ($row4 = mysqli_fetch_assoc($r4)) {
        $productid = $row4['productid'];
        $stock = $row4['stock'];
        $qty = $row4['qty'];
        $new_stock = ((int)$stock - (int)$qty);
        $q5 = "UPDATE product set stock = '$new_stock' where productid = '$productid'";
        mysqli_query($con, $q5);
        ///overrite
        $variatoinid2 = $row4['variationid'];
        if ((int)$variatoinid2 == 0) {
            $regular_ = $row4['regularprice'];
            $saleprice_ = $row4['saleprice'];
            $shippingtype_ = $row4['shippingtype'];
            $total3 = 0;
            if ((int)$saleprice_ == 0) {
                $total3 = ((float)$regular_ * (float)$qty) + (float)$shippingtype_;
                $q5 = "UPDATE userorder set price = '$regular_', shippingfee = '$shippingtype_',total = '$total3'  where productid = '$productid' and userid = '$userid' AND isDone = 'no'";
                mysqli_query($con, $q5);
            } else {
                $total3 = ((float)$saleprice_ * (float)$qty) + (float)$shippingtype_;
                $q5 = "UPDATE userorder set price = '$saleprice_', shippingfee = '$shippingtype_',total = '$total3'  where productid = '$productid' and userid = '$userid' AND isDone = 'no'";
                mysqli_query($con, $q5);
            }
        }

        $var2 = (int)$variatoinid2;
        if ($var2 != 0) {
            $q10 = "SELECT * FROM variation where variationid = '$var2'";
            $r10 = mysqli_query($con, $q10);
            while ($row10 = mysqli_fetch_assoc($r10)) {
                $var3 = $row10['variationid'];
                $varsale3 = $row10['varsale'];
                $varregular3 = $row10['varregular'];
                $varshipping3 = $row10['shippingtype'];
                $vartotal3 = 0;
                if ((int)$varsale3 == 0) {
                    $vartotal3 = ((float)$varregular3 * (float)$qty) + (float)$varshipping3;
                    $q6 = "UPDATE userorder set price = '$varregular3', shippingfee = '$varshipping3',total = '$vartotal3' where variationid ='$var3' and isDone = 'no'";
                    mysqli_query($con, $q6);
                } else {
                    $vartotal3 = ((float)$varsale3 * (float)$qty) + (float)$varshipping3;
                    $q6 = "UPDATE userorder set price = '$varsale3', shippingfee = '$varshipping3',total = '$vartotal3' where variationid ='$var3' and isDone = 'no'";
                    mysqli_query($con, $q6);
                }
            }
            $aw .= "$varsale3/ $varregular3 //";
        }
    }


    $q3 = "UPDATE userorder set masterpoid = '$masterpoid',isDone='yes',deliverystatus = 'Order Confirmed' WHERE userid = '$userid' and masterpoid = ''";
    mysqli_query($con, $q3);
    $q15 = "UPDATE voucherapplied set masterpoid = '$masterpoid', isApplied = 'yes',dateApplied = '$today' where userid = '$userid' and isApplied = 'no' and discountamount != '0'";
    mysqli_query($con, $q15);

    ////update apply vouchers




    echo "Confirmed Payment";
}
function FillInfo($userdetail)
{

    global $con;
    $q = "SELECT * FROM user where contact = '$userdetail' or email = '$userdetail' ";
    $r = mysqli_query($con, $q);
    while ($row = mysqli_fetch_assoc($r)) {
        $userid = $row['userid'];
    }
    $value = '';

    $q3 = "SELECT * FROM address where userid = '$userid' and isDefault=1";
    $r3 = mysqli_query($con, $q3);
    while ($row3 = mysqli_fetch_assoc($r3)) {
        $fullname = $row3['fullname'];
        $contact = $row3['contact'];
        $address1 = $row3['address1'];
        $postalcode = $row3['postalcode'];
        $address2 = $row3['address2'];
        $isWork = $row3['isWork'];
    }
    $selectedHome = "";
    $selectedWork = "";
    if ($isWork == 1) {
        $selectedWork = "selected";
    } else {
        $selectedHome = "selected";
    }
    $value .= '
    </div>
    <div class="col-6">
        <div class="form-group">
            <label for="recipient-name" class="col-form-label">Fullname:</label>
            <input type="text" class="form-control" value="' . $fullname .  '" id="fullname">
        </div>
    </div>
    <div class="col-6">
        <div class="form-group">
            <label for="recipient-name" class="col-form-label">Contact:</label>
            <input type="text" class="form-control" value="' . $contact . '" id="contact">
        </div>
    </div>
    <div class="col-12">
        <div class="form-group">
            <label for="recipient-name" class="col-form-label">Region/Province/City/Barangay:</label>
            <input type="text" class="form-control" value="' . $address1 . '" id="address1">
        </div>
    </div>
    <div class="col-6">
        <div class="form-group">
            <label for="recipient-name" class="col-form-label">Postal Code:</label>
            <input type="text" class="form-control" value="' . $postalcode . '" id="postalcode">
        </div>
    </div>
    <div class="col-6">
        <div class="form-group">
            <label for="recipient-name" class="col-form-label" >Label:</label>
            <select class="form-control" id="label">
                <option value= "1"  ' . $selectedWork . '  id="label-work">Work</option>
                <option value= "0" ' . $selectedHome . ' id="label-home">Home</option>
            </select>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group">
            <label for="recipient-name" class="col-form-label">street Name/Building/House No/:</label>
            <input type="text" class="form-control" value="' . $address2     . '" id="address2">
        </div>
    </div>

';
    $value .= '';
    echo $value;
}
function  RefillInfo($userdetail)
{

    $addressid = $_POST['addressid'];
    global $con;
    $q = "SELECT * FROM address where addressid = '$addressid' ";
    $r = mysqli_query($con, $q);
    while ($row = mysqli_fetch_assoc($r)) {
        $fullname = $row['fullname'];
        $contact = $row['contact'];
        $address1 = $row['address1'];
        $postalcode = $row['postalcode'];
        $address2 = $row['address2'];
        $setLabel = $row['isWork'];
    }
    $selectedHome = "";
    $selectedWork = "";
    if ($setLabel == 1) {
        $label = "work";
    } else {
        $label = "home";
    }
    $res = [];
    $res['fullname'] = "$fullname";
    $res['contact'] = "$contact";
    $res['address1'] = "$address1";
    $res['postalcode'] = "$postalcode";
    $res['address2'] = "$address2";
    $res['label'] = "$label";

    echo json_encode($res);
}
function DisplayTotal($userdetail)
{
    global $con;


    $q = "SELECT * FROM user where contact = '$userdetail' or email = '$userdetail' ";
    $r = mysqli_query($con, $q);
    while ($row = mysqli_fetch_assoc($r)) {
        $userid = $row['userid'];
    }
    $value = '';

    $q2 = "SELECT * FROM userorder INNER JOIN product on product.productid = userorder.productid where userid = '$userid' AND isDone = 'no' order by userorderid DESC ";
    $r2 = mysqli_query($con, $q2);

    $value .= '<h4>Order Summary</h4>
    <table class="table">
        <thead>
            <tr>
                <th scope="col" style="width:20%">Img</th>
                <th scope="col">Item</th>
                <th scope="col">Price|Qty</th>
                <th scope="col">Shipping Fee</th>
                <th scope="col">Amount</th>
            </tr>
        </thead>
        <tbody>
        ';
    $total = 0;
    $tprice = 0;
    $netSubTotal = 0;
    $totalshippingfee = "";
    $shippingtype_ = "";
    while ($row2 = mysqli_fetch_assoc($r2)) {
        $productname =  $row2['productname'];
        $imgurl =  $row2['imgurl'];
        $regularprice =  $row2['regularprice'];
        $qty =  $row2['qty'];
        $imgurl =  $row2['imgurl'];
        $shippingtype_ =  (float)$row2['shippingtype'];
        $total += ((float)$qty *  (float)$regularprice);

        //////////
        $variationid = $row2['variationid'];
        $qty_ = $row2['qty'];
        //$tprice += ((float)$row2['regularprice'] * (float)$row2['qty']);
        if ((int)$variationid == 0) {
            $regular_ = $row2['regularprice'];
            $saleprice_ = $row2['saleprice'];

            if ((int)$saleprice_ == 0) {
                $price = $regular_;
                $res = ' <p class="card-text float-left">Price: ' . '₱' . (number_format($price)) . '<br> Qty: ' . (float)$row2['qty'] . '</p>';
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
                $shippingtype_ = $row4["shippingtype"];
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
        $totalshippingfee += $shippingtype_;
        /////////
        $value .= '
            <tr>
                <td><img src="images/product/img/' . $imgurl . '" alt="prod" style="width:70%"></td>
                <th scope="row">' . $productname . '</th>
                <td>' . $res . '</td>
                <td>' . $shippingtype_  . '</td>
                <td>₱' . number_format((((float)$qty *  (float)$price)) + $shippingtype_) . '</td>
            </tr>
    ';
    }
    $value .= '
        </tbody>
    </table>
    <dl class="dlist-align">
        <dt>SubTotal:</dt>
        <dd class="text-left">₱<span id="span-subtotal">' . $tprice . '</span></dd>
    </dl>
    <dl class="dlist-align">
        <dt>MoP:</dt>
        <dd class="text-left"><span id="span-mop" style="color:green">Cash On Delivery</span></dd>
    </dl>
    <dl class="dlist-align">
        <dt>Total Delivery:</dt>
        <dd class="text-left">₱<span id="span-shippingfee">' . $totalshippingfee . '</span></dd>
    </dl>
    <dl class="dlist-align">
        <dt>Discount:</dt>
        <dd class="text-left">-₱0</dd>
    </dl> <hr>
    <dl class="dlist-align mb-3">
        <dt>Net Total:</dt>
        <dd class="text-left">₱<span id="span-total">' . number_format($tprice + $totalshippingfee) . '</span></dd>
    </dl>
   
    ';
    echo $value;
}
function HideConfirmOrder($userdetail)
{
    global $con;


    $q = "SELECT * FROM user where contact = '$userdetail' or email = '$userdetail' ";
    $r = mysqli_query($con, $q);
    while ($row = mysqli_fetch_assoc($r)) {
        $userid = $row['userid'];
    }
    $q2 = "SELECT * FROM userorder where userid = '$userid' and isDone = 'No'";
    $r2 = mysqli_query($con, $q2);
    $rowcount2 = mysqli_num_rows($r2);
    if ((int)$rowcount2 >= 1) {
        echo "show";
    } else {
        echo "hide";
    }
}





////voucher
function ApplyVoucher($userdetail)
{
    $vouchercode = $_POST['vouchercode'];
    global $con;
    $q = "SELECT * FROM user where contact = '$userdetail' or email = '$userdetail' ";
    $r = mysqli_query($con, $q);
    while ($row = mysqli_fetch_assoc($r)) {
        $userid = $row['userid'];
    }
    //match
    $isPublished = "";
    $q2 = "SELECT * FROM voucher where vouchercode = '$vouchercode'";
    $r2 = mysqli_query($con, $q2);
    while ($row2 = mysqli_fetch_assoc($r2)) {
        $vouchercode_match = $row2['vouchercode'];
        $isPublished = $row2['isPublished'];
    }
    if ($vouchercode == $vouchercode_match) {
        $q3 = "SELECT * from voucherapplied where vouchercode = '$vouchercode' and userid ='$userid' and isApplied = 'no'";
        $r3 = mysqli_query($con, $q3);
        $rowcount3 = mysqli_num_rows($r3);
        if ($rowcount3 >= 1) {
            echo  'You already used this Voucher Code..';
        } else {
            if ($isPublished == "false") {
                $q16 = "SELECT * FROM voucher where vouchercode = '$vouchercode'";
                $r16 = mysqli_query($con, $q16);
                while ($row16 = mysqli_fetch_assoc($r16)) {
                    $isExpired = $row16['isExpired'];
                }
                if ($isExpired == "yes") {
                    echo 'Voucher is Expired';
                } else {
                    echo 'Voucher is InActive sorry!';
                }
            } else {
                $q16 = "SELECT * FROM voucher where vouchercode = '$vouchercode'";
                $r16 = mysqli_query($con, $q16);
                while ($row16 = mysqli_fetch_assoc($r16)) {
                    $apply = $row16['apply'];
                    $limitok = $row16['limitok'];
                    $isExpired = $row16['isExpired'];
                }

                if ((int)$apply == (int)$limitok) {
                    echo 'Voucher is Fully Applied';
                } else {
                    $q1 = "INSERT INTO voucherapplied (userid,vouchercode,isApplied,dateApplied,discountamount) values ('$userid','$vouchercode','no','$isPublished','0')";
                    $r1 = mysqli_query($con, $q1);
                    $q17 = "UPDATE voucher set apply = " . ((int)$apply + 1) . " WHERE vouchercode = '$vouchercode' ";
                    mysqli_query($con, $q17);
                    echo 'Voucher Applied';
                }
            }
        }
    } else {
        echo 'Invalid Voucher';
    }
}
function DisplayAppliedVouchers($userdetail)
{

    global $con;


    $q = "SELECT * FROM user where contact = '$userdetail' or email = '$userdetail' ";
    $r = mysqli_query($con, $q);
    while ($row = mysqli_fetch_assoc($r)) {
        $userid = $row['userid'];
    }
    $value = '';
    $q2 = "SELECT * from voucherapplied inner join voucher on voucher.vouchercode = voucherapplied.vouchercode INNER JOIN seller ON seller.sellerid = voucher.sellerid WHERE voucherapplied.userid = '$userid' AND isApplied = 'no'";
    $r2 = mysqli_query($con, $q2);
    $dis = '';
    $value .= '
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">Code</th>
                    <th scope="col">Store</th>
                    <th scope="col">Amount</th>
                    <th scope="col">#</th>
                </tr>
            </thead>
            <tbody>
            ';
    while ($row2 = mysqli_fetch_assoc($r2)) {
        $v_shipping = $row2['v_shipping'];
        $v_less = $row2['v_less'];
        $v_discount = $row2['v_discount'];
        if ($v_shipping != "0") {
            $dis = '<span>-₱' . $v_shipping . '</span>';
        } else if ($v_less != "0") {
            $dis = '<span>-₱' . $v_less . '</span>';
        } else {
            $dis = '<span>' . $v_discount . '%</span>';
        }
        $value .= '
                <tr>
                    <th scope="row">' . $row2['vouchercode'] . '</th>
                    <td>' . $row2['storename'] . '</td>
                    <td>' . $dis . '</td>
                    <td><button class="btn btn-info btn-sm" value="' . $row2['voucherappliedid'] . '" id="delete-applied"><i class="fa fa-times"></i></button></td>
                </tr>
                ';
    }
    $value .= '
            </tbody>
        </table>
        ';

    echo $value;
}

function DeleteAppliedVouchers()
{
    $voucherappliedid = $_POST['voucherappliedid'];
    global $con;




    $q2 = "SELECT * FROM voucherapplied INNER JOIN voucher ON voucher.vouchercode = voucherapplied.vouchercode where voucherappliedid = '$voucherappliedid'";
    $r2 = mysqli_query($con, $q2);
    while ($row2 = mysqli_fetch_assoc($r2)) {
        $vouchercode = $row2['vouchercode'];
        $apply = $row2['apply'];
    }

    $q = "DELETE FROM voucherapplied where voucherappliedid = '$voucherappliedid'";
    $r = mysqli_query($con, $q);
    $q17 = "UPDATE voucher set apply = " . ((int)$apply - 1) . " WHERE vouchercode = '$vouchercode' ";
    mysqli_query($con, $q17);

    echo "$r2";
}

function FiveLogicsCombined($userdetail)
{
    global $con;


    $q = "SELECT * FROM user where contact = '$userdetail' or email = '$userdetail' ";
    $r = mysqli_query($con, $q);
    while ($row = mysqli_fetch_assoc($r)) {
        $userid = $row['userid'];
    }
    ///
    $value = '';
    ///
    $netPrice = 0;
    $netShipping = 0;
    $netDiscount = 0;
    $netTotal = 0;
    $q2 = "SELECT * FROM userorder INNER JOIN product ON product.productid = userorder.productid INNER JOIN seller ON seller.sellerid = product.sellerid WHERE userorder.userid = '$userid' AND isDone = 'no' GROUP BY product.sellerid";
    $r2 = mysqli_query($con, $q2);
    while ($row2 = mysqli_fetch_assoc($r2)) {
        //
        $total = 0;
        $tprice = 0;
        $totalshippingfee = 0;
        $shipping___ = "0";
        $storename = $row2['storename'];
        $variation = "";
        $issale = "";
        $qty_ = $row2['qty'];
        $sellerid = $row2['sellerid'];
        $color = random_color_part();
        $value .= '<div class="col-12">
    <h4><i class="fas fa-store-alt" style="color:#' . $color . '"></i>    ' . $storename . ' </h4>
    <table class="table" style="background-color: #' . $color . '; color:white">
    <thead>
      <tr>
        <th scope="col">Img</th>
        <th scope="col">Item</th>
        <th scope="col">PRICE</th>
        <th scope="col">SHIPPING</th>
        <th scope="col">AMOUNT</th>
      </tr>
    </thead>
    <tbody>
    ';

        $q3 = "SELECT * FROM userorder INNER JOIN product ON product.productid = userorder.productid inner join seller ON seller.sellerid = product.sellerid WHERE userorder.userid = '$userid' AND isDone = 'no' AND product.sellerid = '$sellerid'";
        $r3 = mysqli_query($con, $q3);
        while ($row3 = mysqli_fetch_assoc($r3)) {
            $variationid = $row3['variationid'];
            if ((int)$variationid == 0) {
                $regular_ = $row2['regularprice'];
                $saleprice_ = $row2['saleprice'];
                $shippingtype__ = $row2['shippingtype'];
                if ((int)$saleprice_ == 0) {
                    $issale = '';
                    $shipping___ =  $shippingtype__;
                    $price = $regular_;
                    $res = ' <p class="card-text float-left">' . '₱' . (number_format($price)) . '(' . (float)$row2['qty'] . ')</p>';
                } else {
                    $issale = '<span class="badge badge-danger">Sale!</span> ';
                    $shipping___ =  $shippingtype__;
                    $price = $saleprice_;
                    $res = ' <p class="card-text float-left">' . ' ₱' . (number_format($price)) . '(' . (float)$row2['qty'] . ')<span style="color:red;text-decoration:line-through;font-size:12px"><br>₱' . $regular_ . '</span></p>';
                }
                $tprice += ((float)$price * $qty_);
                $totalshippingfee += $shipping___;
            } else {
                $q4 = "SELECT * FROM variation where variationid = '$variationid'";
                $r4 = mysqli_query($con, $q4);
                while ($row4 = mysqli_fetch_assoc($r4)) {
                    $varregular_ = $row4["varregular"];
                    $varsale_ = $row4["varsale"];
                    $shippingtype_ = $row4["shippingtype"];
                    $variation = "(" . $row4['variationname'] . ")<br>";
                }
                if ((int)$varsale_ == 0) {
                    $issale = '';
                    $price =  $varregular_;
                    $shipping___ =  $shippingtype_;
                    $res = ' <p class="card-text float-left"> ' . '₱' . (number_format($price)) . '(' . (float)$row2['qty'] . ')</p>';
                } else {
                    $issale = '<span class="badge badge-danger">Sale!</span> ';
                    $price =  $varsale_;
                    $shipping___ =  $shippingtype_;
                    $res = ' <p class="card-text float-left">' . '₱' . (number_format($price)) . '(' . (float)$row2['qty'] . ')<br><span style="color:red;text-decoration:line-through;font-size:12px">₱' . $varregular_ . '</span> </p>';
                }
                $tprice += ((float)$price * $qty_);
                $totalshippingfee += (float)$shipping___;
            }
            $value .= '
                <tr>
                    <th scope="row" style="width:20%"><div style="padding:5%; background-color:white" ><img src="images/product/img/' . $row3['imgurl'] . '" alt="aw" style="width:100%"></div></th>
                    <td>' . $issale . '' . $variation . '' . $row3['productname'] . '</td>
                    <td>' . $res . '</td>
                    <td>₱' . $shipping___ . '</td>
                    <td>' . (((float)$price * $qty_) + (float)$shipping___) . '</td>
                </tr>
                ';
            $netShipping += $shipping___;
        }
        $value .= '
    </tbody>
  </table>
  ';
        $price_minus = 0;
        $voucher_setamount = 0;
        $minamount_matched = $tprice + $totalshippingfee;
        $q13 = "SELECT * FROM voucherapplied INNER JOIN voucher ON voucher.vouchercode = voucherapplied.vouchercode WHERE userid='$userid' AND sellerid = '$sellerid' and isPublished = 'true' and isApplied = 'no'";
        $r12 = mysqli_query($con, $q13);
        while ($row12 = mysqli_fetch_assoc($r12)) {

            $minamount = $row12['minamount'];
            $v_discount = $row12['v_discount'];
            $v_less = $row12['v_less'];
            $v_shipping = $row12['v_shipping'];
            $vouchercode_ =  $row12['vouchercode'];
            $minamount = $row12['minamount'];
            if ($minamount_matched >= (float)$minamount) { //matched

                if ((int)$v_discount != 0) {
                    //discount
                    //calc
                    $price_minus += ($minamount_matched * ((float)$v_discount / 100)); //40
                    $voucher_setamount = ($minamount_matched * ((float)$v_discount / 100));
                    //
                    $value .= '<button class="btn btn-sm mb-2 mr-2"  style="background-color: #' . $color . '; color:white"><i class="fas fa-check-circle"></i> ' . $row12['vouchercode'] . ' • ' . $v_discount . '% DISCOUNT</button>';

                    $totalpertable = '<p>Price: <span class="text-muted"> ₱'  . number_format($tprice) . '</span> Delivery:  <span class="text-muted"> ₱' . number_format($totalshippingfee) . '</span> Total: <span class="text-muted">₱' . number_format($minamount_matched) . '</span></p></div> <hr>';
                } else if ((int)$v_less != 0) {
                    $price_minus += (float)$v_less;
                    $voucher_setamount = (float)$v_less;
                    //less
                    $value .= '<button class="btn btn-sm mb-2 mr-2"  style="background-color: #' . $color . '; color:white"><i class="fas fa-check-circle"></i> ' . $row12['vouchercode'] . ' • ₱' . number_format($v_less) . ' LESS</button>';
                } else {
                    //shipping
                    $value .= '<button class="btn btn-sm mb-2 mr-2"  style="background-color: #' . $color . '; color:white"><i class="fas fa-check-circle"></i> ' . $row12['vouchercode'] . ' • 50% DISCOUNT</button>';
                }
            } else {
                $minres = (float)$minamount - (float)$minamount_matched;
                $value .= '<button class="btn btn-outline btn-sm mb-2 mr-2"><i class="fa fa-exclamation-circle text-warning"></i> ' . $row12['vouchercode'] . ' - Not Applied Need Minimum of ₱' . number_format($minamount) . ' and need of ₱' . number_format($minres) . ' to take effect.</button><br>';
            }
            $q14 = "UPDATE voucherapplied set discountamount = '$voucher_setamount' where vouchercode = '$vouchercode_'";
            $r14 = mysqli_query($con, $q14);
        }
        /////here the true logic begins
        //totalamount

        /////

        $totalpertable = '<p>Price: <span class="text-muted"> ₱'  . number_format($tprice) . '</span> 
        Delivery:  <span class="text-muted"> ₱' . number_format($totalshippingfee) . '</span> 
        SubTotal: <span class="text-muted">₱' . number_format($minamount_matched) . '</span>
        <span style="color:#' . $color . '">Discount:</span><span class="text-muted"> ₱' . number_format($price_minus) . '</span><hr>
        Total: ₱' . number_format($minamount_matched - $price_minus) . ' 
        </p></div> <hr>';

        $value .= $totalpertable;
        //
        $netPrice += $tprice;
        $netDiscount += $price_minus;
        $netTotal += $minamount_matched - $price_minus;
    }
    $value .= '
    <div class="row">
        <div class="col-3"> 
        <p class="text-right">
        Net Price:                              <br/>
        Net Delivery:                                        <br/>
        Net Discount:                                <br/>
        Net Total:                                          <br/>
        </p>
        </div>
        <div class="col-9"> 
        <p class="text-left">
        ₱<span id="span-total">' . number_format($netPrice) . '</span>                        <br/>
        ₱<span id="span-shippingfee">' . number_format($netShipping) . '</span>                                    <br/>
        <span id="span-shippingfee-reset" hidden>' . number_format($netShipping) . '</span>                                    
        ₱<span id="span-discount">' . number_format($netDiscount) . ' </span>                             <br/>
        ₱<span id="span-nettotal">' . number_format($netTotal) . ' </span>                                      <br/>
        <span id="span-nettotal-reset" hidden>' . number_format($netTotal) . ' </span>                                      
        </p>
        </div>
    </div>';
    echo $value;
}
function random_color_part()
{
    $dt = '';
    for ($o = 1; $o <= 3; $o++) {
        $dt .= str_pad(dechex(mt_rand(0, 127)), 2, '0', STR_PAD_LEFT);
    }
    return $dt;
}
