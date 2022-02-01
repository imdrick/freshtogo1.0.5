<?php
require_once('../include/connection.php');

function DisplayOrders($userdetail)
{


    global $con;
    $q = "SELECT * FROM user where contact = '$userdetail' or email = '$userdetail' ";
    $r = mysqli_query($con, $q);
    while ($row = mysqli_fetch_assoc($r)) {
        $userid = $row['userid'];
    }
    $q5 = "SELECT * FROM masterlist where userid = '$userid' order by masterlistid desc";
    $r5 = mysqli_query($con, $q5);
    $shippingfee_ = "";
    $value = '';

    $subtotal = 0;
    while ($row5 = mysqli_fetch_assoc($r5)) {

        $masterpoid = $row5['masterpoid'];
        $masterlistid = $row5['masterlistid'];
        $setdatecreated = date_create($row5['datecreated']);
        $datecreated = date_format($setdatecreated, "F j, Y, g:i a");
        $fullname = $row5['fullname'];
        $contact = $row5['contact'];
        $address1 = $row5['address1'];
        $address2 = $row5['address2'];
        $settotal = $row5['total'];
        $setshippingfee = $row5['shippingfee'];
        $remarks = $row5['remarks'];
        $status = $row5['status'];
        $setdiscount = $row5['discount'];
        $setnettotal = $row5['nettotal'];
        $setmop = $row5['mop'];
        $total = str_replace(',', '', $settotal);
        $shippingfee = str_replace(',', '', $setshippingfee);
        $t_total = number_format((float)$total + (float)$shippingfee);
        $deliverybox = $row5['deliverybox'];

        $value .= '<article class="card mb-4" id="myFrame-' . $masterpoid . '">
    <header class="card-header">
        <a href="#print' . $masterpoid . '" class="float-right ml-2"> <i class="fa fa-print" value="click" onclick="printDiv_' . $masterlistid . '()"> <span class="text-muted">Print</span></i> </a> 
        
        <strong class="d-inline-block mr-3">POID: ' . $masterpoid . ' </strong><br>
        <span>Order Date: ' . $datecreated . '</span>
    </header>
    <div class="card-body">
        <div class="row">
            <div class="col-md-8">
                <h6 class="text-muted">Delivery to</h6>
                <p>' . $fullname . '<br>
                    Phone ' . $contact . ' <br>
                    Location: ' . $address1 . ', ' . $address2 . '<hr>
                    Status: ' . $status . '  | Box: ' . $deliverybox . '
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
                    Total Delivery Fee: ₱' . $setshippingfee . '  <hr>
                   
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
        $q2 = "SELECT * FROM userorder INNER JOIN product ON product.productid = userorder.productid INNER JOIN seller ON seller.sellerid = product.sellerid WHERE userorder.userid = '$userid' AND isDone = 'yes' and masterpoid = '$masterpoid' GROUP BY product.sellerid";
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
            <th scope="col">DELIVERYFEE</th>
            <th scope="col">AMOUNT</th>
          </tr>
        </thead>
        <tbody>
        ';

            $q3 = "SELECT * FROM userorder INNER JOIN product ON product.productid = userorder.productid inner join seller ON seller.sellerid = product.sellerid WHERE userorder.userid = '$userid' AND isDone = 'yes' and masterpoid = '$masterpoid' AND product.sellerid = '$sellerid'";
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
            Total: ₱' . number_format($minamount_matched - $price_minus) . ' | MasterPOID: ' . $masterpoid . '  
            </p></div> <hr>';

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
            Net ShippingFee:                                        <br/>
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
        $value .= '
        </div> 
        </article> 
        <script>
        function printDiv_' . $masterlistid . '() {
            var divContents = document.getElementById("myFrame-' . $masterpoid . '").innerHTML;
            var a = window.open("", "", "height=500, width=500");
            a.document.write("<html>");
            a.document.write(divContents);
            a.document.write("</body></html>");
            a.document.close();
            a.print();
        }
        </script>';
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
function random_color_part()
{
    $dt = '';
    for ($o = 1; $o <= 3; $o++) {
        $dt .= str_pad(dechex(mt_rand(0, 127)), 2, '0', STR_PAD_LEFT);
    }
    return $dt;
}
