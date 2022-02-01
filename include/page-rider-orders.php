<?php
require_once('../include/connection.php');

function DisplayOrders($userdetail)
{
    global $con;
    $q1 = "SELECT * from user INNER JOIN rider ON rider.userid = user.userid WHERE (user.contact = '$userdetail' or user.email = '$userdetail');";
    $r1 = mysqli_query($con, $q1);

    while ($row1 = mysqli_fetch_assoc($r1)) {
        $riderid = $row1['riderid'];
    }

    $q5 = "SELECT * FROM masterlist where status = 'Queuing' ";
    $r5 = mysqli_query($con, $q5);
    $rowcount5 = mysqli_num_rows($r5);

    $q2 = "SELECT * FROM masterlist where (status = 'Accepted' or status = 'Preparing' or status = 'To Deliver') and riderid = '$riderid' order by masterlistid desc LIMIT 1";
    $r2 = mysqli_query($con, $q2);
    $rowcount_accepted = mysqli_num_rows($r2);
    $value = '';
    if ((int)$rowcount_accepted == 0) {
        $value .= '';
        $value .= '<label>You Dont have accepted Order..</label>';
        $value .= '<button class="btn btn-primary btn-block" id="accept-order">Accept Order</button>';
        $value .= '<button class="btn btn-info btn-block" id="view-orders">View Orders</button>';
    } else {
        //no button,
        $q = "SELECT * FROM masterlist inner join rider on rider.riderid = masterlist.riderid  where masterlist.riderid = '$riderid' and (status = 'Accepted' or status = 'Preparing' or status = 'To Deliver') order by masterlistid ASC";
        $r = mysqli_query($con, $q);

        $value .= '<table id="dtBasicExample" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th class="th-sm">#</th>
                <th class="th-sm">Master_Poid</th>
                <th class="th-sm">Status
                </th>
                <th class="th-sm">Date_Status
                </th>
                <th class="th-sm">Date_Created
                </th>
                <th class="th-sm">Rider
                </th>
                <th class="th-sm">Remarks
                </th>
                <th class="th-sm">Action
                </th>
                <th hidden class="th-sm">Trigger
                </th>
            </tr>
        </thead>
        <tbody>
        ';
        while ($row = mysqli_fetch_assoc($r)) {
            $value .= '
            <tr>
                <td>' . $row['masterlistid'] . '</td>
                <td><span id="modal-masterlistid2">' . $row['masterpoid'] . '</span></td>
                <td>' . $row['status'] . '</td>
                <td>' . $row['datestatus'] . '</td>
                <td>' . $row['datecreated'] . '</td>
                <td>' . $row['ridername'] . '</td>
                <td>' . $row['remarks'] . '</td>
                <td><button type="button" value="' . $row['masterlistid'] . '" class="btn btn-primary btn-block" id="action-modal1"><i class="fa fa-caret-square-up"></i></button></td>
                <td hidden id = "display-triggers2"></td>
            </tr>
            ';
        }
        $value .= '
        </tbody>
        <tfoot>
            <tr>
                <th class="th-sm">#</th>
                <th class="th-sm">Master_Poid</th>
                <th class="th-sm">Status
                </th>
                <th class="th-sm">Date_Status
                </th>
                <th class="th-sm">Date_Created
                </th>
                <th class="th-sm">Rider
                </th>
                <th class="th-sm">Remarks
                </th>
                <th class="th-sm">Action
                </th>
                <th hidden class="th-sm">Trigger
                </th>
            </tr>
        </tfoot>
    </table>';
    }

    echo $value;
}
function ApplyQueuing($userdetail)
{

    global $con;
    $today = date("Y-m-d H:i:s");
    $q1 = "SELECT * from user INNER JOIN rider ON rider.userid = user.userid WHERE (user.contact = '$userdetail' or user.email = '$userdetail');";
    $r1 = mysqli_query($con, $q1);

    while ($row1 = mysqli_fetch_assoc($r1)) {
        $riderid = $row1['riderid'];
    }
    $q = "UPDATE masterlist set riderid = '$riderid', status = 'Accepted', datestatus = '$today' where status = 'Queuing' order by masterlistid ASC LIMIT 1";
    $r = mysqli_query($con, $q);

    /////////////
    $q100 = "SELECT masterlist.masterpoid,ridername,masterlist.userid FROM masterlist INNER JOIN rider on rider.riderid = masterlist.riderid WHERE status='Accepted' and masterlist.riderid = '$riderid'";
    $r100 = mysqli_query($con, $q100);
    while ($row100 = mysqli_fetch_assoc($r100)) {
        $masterpoid = $row100['masterpoid'];
        $ridername = $row100['ridername'];
        $userid = $row100['userid'];
        $msg = "ORDER#:" . $masterpoid . " has been Accepted by [Rider: $ridername]";
        $q101 = "INSERT INTO notification (notifmasterpoid,notificationname,notificationtype,notificationdate,userid,riderid,isRead_User,isRead_Rider,notifstatus,isSendText) values ('$masterpoid','$msg','Delivery','$today','$userid','$riderid','no','no','Accepted','no')";
        $r101 = mysqli_query($con, $q101);
    }
    ////////
    echo $masterpoid;
}

function DisplayOrders_Modal($userdetail)
{


    global $con;
    $masterlistid = $_POST['masterlistid'];
    $q = "SELECT * FROM user where contact = '$userdetail' or email = '$userdetail' ";
    $r = mysqli_query($con, $q);
    while ($row = mysqli_fetch_assoc($r)) {
        $userid = $row['userid'];
    }
    $q5 = "SELECT * FROM masterlist where masterlistid='$masterlistid' order by masterlistid desc";
    $r5 = mysqli_query($con, $q5);
    $shippingfee_ = "";
    $value = '';

    $subtotal = 0;
    while ($row5 = mysqli_fetch_assoc($r5)) {

        $masterpoid = $row5['masterpoid'];
        $riderid_ = $row5['riderid'];

        $setdatecreated = date_create($row5['datecreated']);
        $datecreated = date_format($setdatecreated, "F j, Y, g:i a");
        $fullname = $row5['fullname'];
        $contact = $row5['contact'];
        $address1 = $row5['address1'];
        $address2 = $row5['address2'];
        $settotal = $row5['total'];
        $setshippingfee = $row5['shippingfee'];
        $setdiscount = $row5['discount'];
        $setnettotal = $row5['nettotal'];
        $setmop = $row5['mop'];
        $deliverybox = $row5['deliverybox'];


        $total = str_replace(',', '', $settotal);
        $shippingfee = str_replace(',', '', $setshippingfee);
        $t_total = number_format((float)$total + (float)$shippingfee);


        $value .= '<article class="card mb-4">
    <header class="card-header">
        <a href="#" class="float-right ml-2"> <i class="fa fa-print"></i> Print</a>
        <a href="#" class="btn btn-outline-primary float-right" hidden>Track order</a>
        <strong class="d-inline-block mr-3">POID: <span id="modal-masterpoid">' . $masterpoid . '</span> </strong><br>
        <span>Order Date: ' . $datecreated . '</span>
    </header>
    <div class="card-body">
        <div class="row">
            <div class="col-md-8">
                <h6 class="text-muted">Delivery to</h6>
                <p>' . $fullname . '<br>
                    Phone ' . $contact . ' <br>
                    Location: ' . $address1 . ', ' . $address2 . ' <br>
                    Delivery Box: ' . $deliverybox . '
                </p>
            </div>
            <div class="col-md-4" hidden>
                <h6 class="text-muted">Payment</h6>
                <span class="text-success">
                    <i class="fab fa-lg fa-cc-visa"></i>
                    Visa **** 4216
                </span>
                <p>Subtotal: ₱' . $t_total  . ' <br>
                    Shipping fee: $56 <br>
                    <span class="b">Total: ₱456 </span>
                </p>
            </div>
            <div class="col-md-4">
                <h6 class="text-muted">Payment</h6>
                <span class="text-success">
                   ' . $setmop . '
                </span>
                <p>Subtotal: ₱' . $settotal . ' <br>
                    Total Delivery: ₱' . $setshippingfee . '  <hr>
                   
                </p>
               Total Discount:₱' . $setdiscount . ' | Total: ₱' . $setnettotal . ' <br>
              
            </div>
        </div> <!-- row.// -->
    </div> <!-- card-body .// -->
    <div class="table-responsive">
          
        ';
        //////////////////
        $netPrice = 0;
        $netShipping = 0;
        $netDiscount = 0;
        $netTotal = 0;
        $q2 = "SELECT * FROM userorder INNER JOIN product ON product.productid = userorder.productid INNER JOIN seller ON seller.sellerid = product.sellerid WHERE masterpoid = '$masterpoid' GROUP BY product.sellerid";
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

            $q3 = "SELECT * FROM userorder INNER JOIN product ON product.productid = userorder.productid inner join seller ON seller.sellerid = product.sellerid WHERE masterpoid = '$masterpoid' AND product.sellerid = '$sellerid'";
            $r3 = mysqli_query($con, $q3);
            while ($row3 = mysqli_fetch_assoc($r3)) {
                $variationid = $row3['variationid'];
                if ((int)$variationid == 0) {
                    $regular_ = $row2['regularprice'];
                    $saleprice_ = $row2['saleprice'];
                    $shippingtype__ = $row2['shippingtype'];
                    $sellerid_ = $row2['sellerid'];
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
            <span style="color:#' . $color . '">Discount:</span><span class="text-muted"> ₱' . number_format($price_minus) . '</span>
            </p></div>
            <a href="send-report.php?sellerid=' . $sellerid_ . '&riderid=' . $riderid_ . '&productid=0 " target="_blank"  class="dropdown-item"><i class="fa fa-exclamation-triangle text-warning"></i> Report Seller</a>
            ';

            $value .= $totalpertable;
            //
            $netPrice += $tprice;
            $netDiscount += $price_minus;
            $netTotal += $minamount_matched - $price_minus;
        }
        $value .= '
        <div class="row" hidden>
            <div class="col-3"> 
            <p class="text-right">
            Net Price:                              <br/>
            Net DeliveryFee:                                        <br/>
            Net Discount:                                <br/>
            Net Total:                                          <br/>
            </p>
            </div>
            <div class="col-9"> 
            <p class="text-left">
            ₱<span id="span-total">' . number_format($netPrice) . '</span>                        <br/>
            ₱<span id="span-shippingfee">' . number_format($netShipping) . '</span>                                    <br/>
            ₱<span id="span-discount">' . number_format($netDiscount) . ' </span>                             <br/>
            ₱<span id="span-nettotal">' . number_format($netTotal) . ' </span>                                      <br/>
            </p>
            </div>
        </div>
        
       ';

        ////////////////////
        $value .= '
    
        </div> 
        </article> ';
    }
    echo $value;
}

function adjustBrightness($n)
{
    $characters = 'ABCDEF';
    $randomString = '';

    for ($i = 0; $i < $n; $i++) {
        $index = rand(0, strlen($characters) - 1);
        $randomString .= $characters[$index];
    }

    return $randomString;
}
function Update_Delete($userdetail)
{

    $remarks = $_POST['remarks'];
    $masterpoid = $_POST['masterpoid'];
    global $con;
    $today = date("Y-m-d H:i:s");
    $q1 = "SELECT * from user INNER JOIN rider ON rider.userid = user.userid WHERE (user.contact = '$userdetail' or user.email = '$userdetail');";
    $r1 = mysqli_query($con, $q1);

    while ($row1 = mysqli_fetch_assoc($r1)) {
        $riderid = $row1['riderid'];
    }
    $q = "UPDATE masterlist set status = 'Cancelled', datestatus = '$today', remarks = '$remarks' where masterpoid = '$masterpoid'";
    $r = mysqli_query($con, $q);
    ///////
    $q40 = "SELECT notifmasterpoid,ridername,notification.userid FROM notification INNER JOIN rider ON rider.riderid = notification.riderid WHERE notifstatus = 'Accepted' and notifmasterpoid = '$masterpoid'  AND notification.riderid = '$riderid'";
    $r40 = mysqli_query($con, $q40);
    while ($row40 = mysqli_fetch_assoc($r40)) {
        $ridername_ = $row40['ridername'];
        $userid_ = $row40['userid'];
    }
    $msg = "ORDER#:" . $masterpoid . " has been Cancelled by [Rider: $ridername_] because [Remarks: $remarks]";
    $q101 = "INSERT INTO notification (notifmasterpoid,notificationname,notificationtype,notificationdate,userid,riderid,isRead_User,isRead_Rider,notifstatus,isSendText) values ('$masterpoid','$msg','Delivery','$today','$userid_','$riderid','no','no','Cancelled','no')";
    $r101 = mysqli_query($con, $q101);
    ///
    echo "$masterpoid";
}
function Update_Return($userdetail)
{
    $remarks = $_POST['remarks'];
    $masterpoid = $_POST['masterpoid'];
    global $con;
    $today = date("Y-m-d H:i:s");
    $q1 = "SELECT * from user INNER JOIN rider ON rider.userid = user.userid WHERE (user.contact = '$userdetail' or user.email = '$userdetail');";
    $r1 = mysqli_query($con, $q1);

    while ($row1 = mysqli_fetch_assoc($r1)) {
        $riderid = $row1['riderid'];
    }
    $q = "UPDATE masterlist set status = 'Returned', datestatus = '$today', remarks = '$remarks' where masterpoid = '$masterpoid'";
    $r = mysqli_query($con, $q);
    ///////
    $q40 = "SELECT notifmasterpoid,ridername,notification.userid FROM notification INNER JOIN rider ON rider.riderid = notification.riderid WHERE notifstatus = 'Accepted' and notifmasterpoid = '$masterpoid'  AND notification.riderid = '$riderid'";
    $r40 = mysqli_query($con, $q40);
    while ($row40 = mysqli_fetch_assoc($r40)) {
        $ridername_ = $row40['ridername'];
        $userid_ = $row40['userid'];
    }
    $notifstatus = "Returned";
    $msg = "ORDER#:" . $masterpoid . " has been $notifstatus by [Rider: $ridername_] because [Remarks: $remarks]";
    $q101 = "INSERT INTO notification (notifmasterpoid,notificationname,notificationtype,notificationdate,userid,riderid,isRead_User,isRead_Rider,notifstatus,isSendText) values ('$masterpoid','$msg','Delivery','$today','$userid_','$riderid','no','no','$notifstatus','no')";
    $r101 = mysqli_query($con, $q101);
    ///

    echo "$masterpoid";
}
function Update_Prepare($userdetail)
{
    $remarks = $_POST['remarks'];
    $masterpoid = $_POST['masterpoid'];
    $masterpoid_2 = $_POST['masterpoid_2'];
    if ($masterpoid == "") {
        $masterpoid = $masterpoid_2;
    }
    global $con;
    $today = date("Y-m-d H:i:s");
    $q1 = "SELECT * from user INNER JOIN rider ON rider.userid = user.userid WHERE (user.contact = '$userdetail' or user.email = '$userdetail');";
    $r1 = mysqli_query($con, $q1);

    while ($row1 = mysqli_fetch_assoc($r1)) {
        $riderid = $row1['riderid'];
    }
    $q = "UPDATE masterlist set status = 'Preparing', datestatus = '$today', remarks = '$remarks' where masterpoid = '$masterpoid'";
    $r = mysqli_query($con, $q);
    ///////
    $q40 = "SELECT notifmasterpoid,ridername,notification.userid FROM notification INNER JOIN rider ON rider.riderid = notification.riderid WHERE notifstatus = 'Accepted' and notifmasterpoid = '$masterpoid'  AND notification.riderid = '$riderid'";
    $r40 = mysqli_query($con, $q40);
    while ($row40 = mysqli_fetch_assoc($r40)) {
        $ridername_ = $row40['ridername'];
        $userid_ = $row40['userid'];
    }
    $notifstatus = "Preparing";
    $msg = "ORDER#:" . $masterpoid . " has been $notifstatus by [Rider: $ridername_]";
    $q101 = "INSERT INTO notification (notifmasterpoid,notificationname,notificationtype,notificationdate,userid,riderid,isRead_User,isRead_Rider,notifstatus,isSendText) values ('$masterpoid','$msg','Delivery','$today','$userid_','$riderid','no','no','$notifstatus','no')";
    $r101 = mysqli_query($con, $q101);
    ///


    echo "$masterpoid";
}

function Update_ToDeliver($userdetail)
{
    $remarks = $_POST['remarks'];
    $masterpoid = $_POST['masterpoid'];
    global $con;
    $today = date("Y-m-d H:i:s");
    $q1 = "SELECT * from user INNER JOIN rider ON rider.userid = user.userid WHERE (user.contact = '$userdetail' or user.email = '$userdetail');";
    $r1 = mysqli_query($con, $q1);

    while ($row1 = mysqli_fetch_assoc($r1)) {
        $riderid = $row1['riderid'];
    }
    $q = "UPDATE masterlist set status = 'To Deliver', datestatus = '$today', remarks = '$remarks' where masterpoid = '$masterpoid'";
    $r = mysqli_query($con, $q);
    ///////
    $q40 = "SELECT notifmasterpoid,ridername,notification.userid FROM notification INNER JOIN rider ON rider.riderid = notification.riderid INNER JOIN masterlist ON masterlist.masterpoid = notification.notifmasterpoid WHERE notifstatus = 'Accepted' and notifmasterpoid = '$masterpoid'  AND notification.riderid = '$riderid'";
    $r40 = mysqli_query($con, $q40);
    while ($row40 = mysqli_fetch_assoc($r40)) {
        $ridername_ = $row40['ridername'];
        $userid_ = $row40['userid'];
    }
    $notifstatus = "To Deliver";
    $msg = "ORDER#:" . $masterpoid . " has been $notifstatus by [Rider: $ridername_]";
    $q101 = "INSERT INTO notification (notifmasterpoid,notificationname,notificationtype,notificationdate,userid,riderid,isRead_User,isRead_Rider,notifstatus,isSendText) values ('$masterpoid','$msg','Delivery','$today','$userid_','$riderid','no','no','$notifstatus','no')";
    $r101 = mysqli_query($con, $q101);
    ///

    echo "$masterpoid";
}
function Update_Delivered($userdetail)
{
    $remarks = $_POST['remarks'];
    $masterpoid = $_POST['masterpoid'];
    global $con;
    $today = date("Y-m-d H:i:s");
    $q1 = "SELECT * from user INNER JOIN rider ON rider.userid = user.userid WHERE (user.contact = '$userdetail' or user.email = '$userdetail');";
    $r1 = mysqli_query($con, $q1);

    while ($row1 = mysqli_fetch_assoc($r1)) {
        $riderid = $row1['riderid'];
    }
    $q = "UPDATE masterlist set status = 'Delivered', datestatus = '$today', remarks = '$remarks' where masterpoid = '$masterpoid'";
    $r = mysqli_query($con, $q);
    ///////
    $q40 = "SELECT notifmasterpoid,ridername,notification.userid FROM notification INNER JOIN rider ON rider.riderid = notification.riderid WHERE notifstatus = 'Accepted' and notifmasterpoid = '$masterpoid'  AND notification.riderid = '$riderid'";
    $r40 = mysqli_query($con, $q40);
    while ($row40 = mysqli_fetch_assoc($r40)) {
        $ridername_ = $row40['ridername'];
        $userid_ = $row40['userid'];
    }
    $notifstatus = "Delivered";
    $msg = "ORDER#:" . $masterpoid . " has been $notifstatus by [Rider: $ridername_]";
    $q101 = "INSERT INTO notification (notifmasterpoid,notificationname,notificationtype,notificationdate,userid,riderid,isRead_User,isRead_Rider,notifstatus,isSendText) values ('$masterpoid','$msg','Delivery','$today','$userid_','$riderid','no','no','$notifstatus','no')";
    $r101 = mysqli_query($con, $q101);
    ///
    echo "$masterpoid";
}
function ViewOrders($userdetail)
{
    global $con;
    $q1 = "SELECT * from user INNER JOIN rider ON rider.userid = user.userid WHERE (user.contact = '$userdetail' or user.email = '$userdetail');";
    $r1 = mysqli_query($con, $q1);

    while ($row1 = mysqli_fetch_assoc($r1)) {
        $riderid = $row1['riderid'];
    }

    $q5 = "SELECT * FROM masterlist where status = 'Queuing' ";
    $r5 = mysqli_query($con, $q5);
    $rowcount5 = mysqli_num_rows($r5);

    $q2 = "SELECT * FROM masterlist where (status = 'Accepted' or status = 'Preparing' or status = 'To Deliver') and riderid = '$riderid' order by masterlistid desc LIMIT 1";
    $r2 = mysqli_query($con, $q2);
    $rowcount_accepted = mysqli_num_rows($r2);
    $value = '';

    //no button,
    $q = "SELECT * FROM masterlist inner join rider on rider.riderid = masterlist.riderid  where masterlist.riderid = '$riderid' order by masterlistid ASC";
    $r = mysqli_query($con, $q);

    $value .= '<table id="dtBasicExample" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th class="th-sm">#</th>
                <th class="th-sm">Master_Poid</th>
                <th class="th-sm">Status
                </th>
                <th class="th-sm">Date_Status
                </th>
                <th class="th-sm">Date_Created
                </th>
                <th class="th-sm">Rider
                </th>
                <th class="th-sm">Remarks
                </th>
                <th class="th-sm">Action
                </th>
            </tr>
        </thead>
        <tbody>
        ';
    while ($row = mysqli_fetch_assoc($r)) {
        if ($row['status'] == "Delivered" || $row['status'] == "Cancelled") {
            $disabled_delivered = "disabled";
        } else {
            $disabled_delivered = "";
        }
        $value .= '
            <tr>
                <td>' . $row['masterlistid'] . '</td>
                <td>' . $row['masterpoid'] . '</td>
                <td>' . $row['status'] . '</td>
                <td>' . $row['datestatus'] . '</td>
                <td>' . $row['datecreated'] . '</td>
                <td>' . $row['ridername'] . '</td>
                <td>' . $row['remarks'] . '</td>
                <td><button ' . $disabled_delivered . ' type="button" value="' . $row['masterlistid'] . '" class="btn btn-primary btn-block" id="action-modal1"><i class="fa fa-caret-square-up"></i></button></td>
            </tr>
            ';
    }
    $value .= '
        </tbody>
        <tfoot>
            <tr>
                <th class="th-sm">#</th>
                <th class="th-sm">Master_Poid</th>
                <th class="th-sm">Status
                </th>
                <th class="th-sm">Date_Status
                </th>
                <th class="th-sm">Date_Created
                </th>
                <th class="th-sm">Rider
                </th>
                <th class="th-sm">Remarks
                </th>
                <th class="th-sm">Action
                </th>
            </tr>
        </tfoot>
    </table>';

    echo $value;
}



function SelectStatus($userdetail)
{
    $x = $_POST['value'];
    $fromdate = $_POST['fromdate'];
    $todate = $_POST['todate'];
    global $con;
    $q1 = "SELECT * from user INNER JOIN rider ON rider.userid = user.userid WHERE (user.contact = '$userdetail' or user.email = '$userdetail');";
    $r1 = mysqli_query($con, $q1);

    while ($row1 = mysqli_fetch_assoc($r1)) {
        $riderid = $row1['riderid'];
    }
    if ((int)$x == 1) {
        DisplayOrders($userdetail);
    } else {

        if ((int)$x == 2) {
            //cancel/return
            global $con;
            $q1 = "SELECT * from user INNER JOIN rider ON rider.userid = user.userid WHERE (user.contact = '$userdetail' or user.email = '$userdetail');";
            $r1 = mysqli_query($con, $q1);

            while ($row1 = mysqli_fetch_assoc($r1)) {
                $riderid = $row1['riderid'];
            }
            $value = '';

            //no button,
            $q = "SELECT * FROM masterlist inner join rider on rider.riderid = masterlist.riderid  where (datestatus BETWEEN '" . $fromdate . " 00:00:00' and '" . $todate . " 23:59:59') AND (status = 'Cancelled' or status = 'Returned') and masterlist.riderid = '$riderid' order by masterlistid ASC";
            $r = mysqli_query($con, $q);

            $value .= '<table id="dtBasicExample" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th class="th-sm">#</th>
                <th class="th-sm">Master_Poid</th>
                <th class="th-sm">Status
                </th>
                <th class="th-sm">Date_Status
                </th>
                <th class="th-sm">Date_Created
                </th>
                <th class="th-sm">Rider
                </th>
                <th class="th-sm">Remarks
                </th>
                <th class="th-sm">Action
                </th>
            </tr>
        </thead>
        <tbody>
        ';
            while ($row = mysqli_fetch_assoc($r)) {
                if ($row['status'] == "Delivered" || $row['status'] == "Cancelled") {
                    $disabled_delivered = "disabled";
                } else {
                    $disabled_delivered = "";
                }
                $value .= '
            <tr>
                <td>' . $row['masterlistid'] . '</td>
                <td>' . $row['masterpoid'] . '</td>
                <td>' . $row['status'] . '</td>
                <td>' . $row['datestatus'] . '</td>
                <td>' . $row['datecreated'] . '</td>
                <td>' . $row['ridername'] . '</td>
                <td>' . $row['remarks'] . '</td>
                <td><button ' . $disabled_delivered . ' type="button" value="' . $row['masterlistid'] . '" class="btn btn-primary btn-block" id="action-modal1"><i class="fa fa-caret-square-up"></i></button></td>
            </tr>
            ';
            }
            $value .= '
        </tbody>
        <tfoot>
            <tr>
                <th class="th-sm">#</th>
                <th class="th-sm">Master_Poid</th>
                <th class="th-sm">Status
                </th>
                <th class="th-sm">Date_Status
                </th>
                <th class="th-sm">Date_Created
                </th>
                <th class="th-sm">Rider
                </th>
                <th class="th-sm">Remarks
                </th>
                <th class="th-sm">Action
                </th>
            </tr>
        </tfoot>
    </table>';

            echo $value;
        } else if ((int)$x == 3) {
            //delivered
            global $con;
            $q1 = "SELECT * from user INNER JOIN rider ON rider.userid = user.userid WHERE (user.contact = '$userdetail' or user.email = '$userdetail');";
            $r1 = mysqli_query($con, $q1);

            while ($row1 = mysqli_fetch_assoc($r1)) {
                $riderid = $row1['riderid'];
            }
            $value = '';

            //no button,
            $q = "SELECT * FROM masterlist inner join rider on rider.riderid = masterlist.riderid  where (datestatus BETWEEN '" . $fromdate . " 00:00:00' and '" . $todate . " 23:59:59') AND (status = 'Delivered') and masterlist.riderid = '$riderid' order by masterlistid ASC";
            $r = mysqli_query($con, $q);

            $value .= '<table id="dtBasicExample" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th class="th-sm">#</th>
                <th class="th-sm">Master_Poid</th>
                <th class="th-sm">Status
                </th>
                <th class="th-sm">Date_Status
                </th>
                <th class="th-sm">Date_Created
                </th>
                <th class="th-sm">Rider
                </th>
                <th class="th-sm">Remarks
                </th>
                <th class="th-sm">Action
                </th>
            </tr>
        </thead>
        <tbody>
        ';
            while ($row = mysqli_fetch_assoc($r)) {
                if ($row['status'] == "Delivered" || $row['status'] == "Cancelled") {
                    $disabled_delivered = "disabled";
                } else {
                    $disabled_delivered = "";
                }
                $value .= '
            <tr>
                <td>' . $row['masterlistid'] . '</td>
                <td>' . $row['masterpoid'] . '</td>
                <td>' . $row['status'] . '</td>
                <td>' . $row['datestatus'] . '</td>
                <td>' . $row['datecreated'] . '</td>
                <td>' . $row['ridername'] . '</td>
                <td>' . $row['remarks'] . '</td>
                <td><button ' . $disabled_delivered . ' type="button" value="' . $row['masterlistid'] . '" class="btn btn-primary btn-block" id="action-modal1"><i class="fa fa-caret-square-up"></i></button></td>
            </tr>
            ';
            }
            $value .= '
        </tbody>
        <tfoot>
            <tr>
                <th class="th-sm">#</th>
                <th class="th-sm">Master_Poid</th>
                <th class="th-sm">Status
                </th>
                <th class="th-sm">Date_Status
                </th>
                <th class="th-sm">Date_Created
                </th>
                <th class="th-sm">Rider
                </th>
                <th class="th-sm">Remarks
                </th>
                <th class="th-sm">Action
                </th>
            </tr>
        </tfoot>
    </table>';

            echo $value;
        } else {
            global $con;
            $q1 = "SELECT * from user INNER JOIN rider ON rider.userid = user.userid WHERE (user.contact = '$userdetail' or user.email = '$userdetail');";
            $r1 = mysqli_query($con, $q1);

            while ($row1 = mysqli_fetch_assoc($r1)) {
                $riderid = $row1['riderid'];
            }
            $value = '';

            //no button,
            $q = "SELECT * FROM masterlist inner join rider on rider.riderid = masterlist.riderid  where (datestatus BETWEEN '" . $fromdate . " 00:00:00' and '" . $todate . " 23:59:59') AND masterlist.riderid = '$riderid' order by masterlistid ASC";
            $r = mysqli_query($con, $q);

            $value .= '<table id="dtBasicExample" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th class="th-sm">#</th>
                <th class="th-sm">Master_Poid</th>
                <th class="th-sm">Status
                </th>
                <th class="th-sm">Date_Status
                </th>
                <th class="th-sm">Date_Created
                </th>
                <th class="th-sm">Rider
                </th>
                <th class="th-sm">Remarks
                </th>
                <th class="th-sm">Action
                </th>
            </tr>
        </thead>
        <tbody>
        ';
            while ($row = mysqli_fetch_assoc($r)) {
                if ($row['status'] == "Delivered" || $row['status'] == "Cancelled") {
                    $disabled_delivered = "disabled";
                } else {
                    $disabled_delivered = "";
                }
                $value .= '
            <tr>
                <td>' . $row['masterlistid'] . '</td>
                <td>' . $row['masterpoid'] . '</td>
                <td>' . $row['status'] . '</td>
                <td>' . $row['datestatus'] . '</td>
                <td>' . $row['datecreated'] . '</td>
                <td>' . $row['ridername'] . '</td>
                <td>' . $row['remarks'] . '</td>
                <td><button ' . $disabled_delivered . ' type="button" value="' . $row['masterlistid'] . '" class="btn btn-primary btn-block" id="action-modal1"><i class="fa fa-caret-square-up"></i></button></td>
            </tr>
            ';
            }
            $value .= '
        </tbody>
        <tfoot>
            <tr>
                <th class="th-sm">#</th>
                <th class="th-sm">Master_Poid</th>
                <th class="th-sm">Status
                </th>
                <th class="th-sm">Date_Status
                </th>
                <th class="th-sm">Date_Created
                </th>
                <th class="th-sm">Rider
                </th>
                <th class="th-sm">Remarks
                </th>
                <th class="th-sm">Action
                </th>
            </tr>
        </tfoot>
    </table>';

            echo $value;
        }
    }
}
function TopBanners($userdetail)
{
    global $con;
    $q1 = "SELECT * from user INNER JOIN rider ON rider.userid = user.userid WHERE (user.contact = '$userdetail' or user.email = '$userdetail');";
    $r1 = mysqli_query($con, $q1);

    $fromdate = $_POST['fromdate'];
    $todate = $_POST['todate'];
    while ($row1 = mysqli_fetch_assoc($r1)) {
        $riderid = $row1['riderid'];
    }
    $q = "SELECT * FROM masterlist WHERE (datestatus BETWEEN '" . $fromdate . " 00:00:00' and '" . $todate . " 23:59:59') AND riderid = '$riderid'";
    $r = mysqli_query($con, $q);

    $q5 = "SELECT * FROM masterlist where status = 'Queuing' ";
    $r5 = mysqli_query($con, $q5);
    $rowcount5 = mysqli_num_rows($r5);

    $delivered = 0;

    $returned = 0;
    $cancelled = 0;
    $delivered_ = 0;
    $returned_ = 0;
    $cancelled_ = 0;
    $queuing_ = $rowcount5;
    while ($row = mysqli_fetch_assoc($r)) {
        if ($row['status'] == "Delivered") {
            $delivered_ += ($delivered + 1);
        }
        if ($row['status'] == "Returned") {
            $returned_ += ($returned + 1);
        }
        if ($row['status'] == "Cancelled") {
            $cancelled_ += ($cancelled + 1);
        }
    }

    echo '<div class="col-md-3">
   <article class="card card-body">
       <figure class="text-center">
           <span class="rounded-circle icon-md bg-primary"><i class="fa fa-user-alt white"></i></span>
           <figcaption class="pt-4">
               <h5 class="title">' . $queuing_ . '</h5>
               <p>Queuing</p>
           </figcaption>
       </figure> 
   </article>
</div>
<div class="col-md-3">

   <article class="card card-body">
       <figure class="text-center">
           <span class="rounded-circle icon-md bg-success"><i class="fa fa-truck white"></i></span>
           <figcaption class="pt-4">
               <h5 class="title">' . $delivered_ . '</h5>
               <p>Delivered</p>
           </figcaption>
       </figure> 
   </article> 
</div>
<div class="col-md-3">
   <article class="card card-body">
       <figure class="text-center">
           <span class="rounded-circle icon-md" style="background-color:#F0A501"><i class="fa fa-undo-alt white"></i></span>
           <figcaption class="pt-4">
               <h5 class="title">' . $returned_ . '</h5>
               <p>Returned</p>
           </figcaption>
       </figure>
   </article>
</div>
<div class="col-md-3">
   <article class="card card-body">
       <figure class="text-center">
           <span class="rounded-circle icon-md bg-danger"><i class="fa fa-times white"></i></span>
           <figcaption class="pt-4">
               <h5 class="title">' . $cancelled_ . '</h5>
               <p>Cancelled</p>
           </figcaption>
       </figure>
   </article> 
</div>';
}
function BtnTriggers($userdetail)
{
    $masterlistid = $_POST['masterlistid'];
    if ($masterlistid == "null") {
        global $con;
        $q1 = "SELECT * from user INNER JOIN rider ON rider.userid = user.userid WHERE (user.contact = '$userdetail' or user.email = '$userdetail');";
        $r1 = mysqli_query($con, $q1);
        while ($row1 = mysqli_fetch_assoc($r1)) {
            $riderid = $row1['riderid'];
        }
        $preparing_ = '';
        $todeliver_ = '';
        $delivered_ = '';

        $q = "SELECT * FROM masterlist where status ='Accepted' and riderid = '$riderid' ";
        $r = mysqli_query($con, $q);
        $rowcount = mysqli_num_rows($r);

        if ((int)$rowcount == 1) {
            //ifaccepted
            $preparing_ = '';
            $todeliver_ = 'hidden';
            $delivered_ = 'hidden';
        }

        $q2 = "SELECT * FROM masterlist where status ='Preparing' and riderid = '$riderid'";
        $r2 = mysqli_query($con, $q2);
        $rowcount2 = mysqli_num_rows($r2);

        if ((int)$rowcount2 == 1) {
            //ifaccepted
            $preparing_ = 'hidden';
            $todeliver_ = '';
            $delivered_ = 'hidden';
        }
        $q3 = "SELECT * FROM masterlist where status ='To Deliver' and riderid = '$riderid' ";
        $r3 = mysqli_query($con, $q3);
        $rowcount3 = mysqli_num_rows($r3);

        if ((int)$rowcount3 == 1) {
            //ifaccepted
            $preparing_ = 'hidden';
            $todeliver_ = 'hidden';
            $delivered_ = '';
        }

        $q6 = "SELECT * FROM masterlist where status ='Returned' and riderid = '$riderid'";
        $r6 = mysqli_query($con, $q6);
        $rowcount3 = mysqli_num_rows($r6);

        if ((int)$rowcount3 == 1) {
            //ifaccepted
            $preparing_ = '';
            $todeliver_ = 'hidden';
            $delivered_ = 'hidden';
        }
        echo '
    
        <button ' . $preparing_ . ' id="prepare-order" class="btn btn-success">Preparing Order</button>
        <button ' . $todeliver_ . ' id="todeliver-order" class="btn btn-success">To Deliver</button>
        <button ' . $delivered_ . ' id="delivered-order" class="btn btn-success">Delivered</button>
        ';
    } else {
        global $con;
        $q1 = "SELECT * from user INNER JOIN rider ON rider.userid = user.userid WHERE (user.contact = '$userdetail' or user.email = '$userdetail');";
        $r1 = mysqli_query($con, $q1);
        while ($row1 = mysqli_fetch_assoc($r1)) {
            $riderid = $row1['riderid'];
        }
        $preparing_ = '';
        $todeliver_ = '';
        $delivered_ = '';

        $q7 = "SELECT * from masterlist where masterlistid = '$masterlistid'";
        $r7 = mysqli_query($con, $q7);
        while ($row7 = mysqli_fetch_assoc($r7)) {
            $masterpoid = $row7['masterpoid'];
        }

        $q = "SELECT * FROM masterlist where status ='Accepted' and riderid = '$riderid' and masterpoid = '$masterpoid' ";
        $r = mysqli_query($con, $q);
        $rowcount = mysqli_num_rows($r);

        if ((int)$rowcount == 1) {
            //ifaccepted
            $preparing_ = '';
            $todeliver_ = 'hidden';
            $delivered_ = 'hidden';
        }

        $q2 = "SELECT * FROM masterlist where status ='Preparing' and riderid = '$riderid' and masterpoid = '$masterpoid'";
        $r2 = mysqli_query($con, $q2);
        $rowcount2 = mysqli_num_rows($r2);

        if ((int)$rowcount2 == 1) {
            //ifaccepted
            $preparing_ = 'hidden';
            $todeliver_ = '';
            $delivered_ = 'hidden';
        }
        $q3 = "SELECT * FROM masterlist where status ='To Deliver' and riderid = '$riderid' and masterpoid = '$masterpoid'";
        $r3 = mysqli_query($con, $q3);
        $rowcount3 = mysqli_num_rows($r3);

        if ((int)$rowcount3 == 1) {
            //ifaccepted
            $preparing_ = 'hidden';
            $todeliver_ = 'hidden';
            $delivered_ = '';
        }

        $q6 = "SELECT * FROM masterlist where status ='Returned' and riderid = '$riderid' and masterpoid = '$masterpoid'";
        $r6 = mysqli_query($con, $q6);
        $rowcount3 = mysqli_num_rows($r6);

        if ((int)$rowcount3 == 1) {
            //ifaccepted
            $preparing_ = '';
            $todeliver_ = 'hidden';
            $delivered_ = 'hidden';
        }
        echo '

    <button ' . $preparing_ . ' id="prepare-order" class="btn btn-success">Preparing Order</button>
    <button ' . $todeliver_ . ' id="todeliver-order" class="btn btn-success">To Deliver</button>
    <button ' . $delivered_ . ' id="delivered-order" class="btn btn-success">Delivered</button>
    ';
    }
}
function random_color_part()
{
    $dt = '';
    for ($o = 1; $o <= 3; $o++) {
        $dt .= str_pad(dechex(mt_rand(0, 127)), 2, '0', STR_PAD_LEFT);
    }
    return $dt;
}
