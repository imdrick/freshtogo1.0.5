<?php
require_once('../include/connection.php');

function DisplayOrders($userdetail)
{
    $fromdate = $_POST['fromdate'];
    $todate = $_POST['todate'];
    global $con;
    $q1 = "SELECT * from user INNER JOIN seller ON seller.userid = user.userid WHERE (user.contact = '$userdetail' or user.email = '$userdetail');";
    $r1 = mysqli_query($con, $q1);

    while ($row1 = mysqli_fetch_assoc($r1)) {
        $sellerid = $row1['sellerid'];
    }


    //no button,
    $q = "SELECT masterlist.riderid,userorder.userid,userorderid,poid,firstname,lastname,productname,userorder.price,userorder.shippingfee,qty,userorder.total,ridername,status,datestatus,datecreated FROM userorder 
    INNER JOIN product ON product.productid = userorder.productid 
    INNER JOIN user ON user.userid = userorder.userid 
    INNER JOIN masterlist ON masterlist.masterpoid = userorder.masterpoid 
    INNER JOIN rider ON rider.riderid = masterlist.riderid 
    WHERE sellerid = '$sellerid' and (datecreated BETWEEN '" . $fromdate . " 00:00:00' and '" . $todate . " 23:59:59')";
    $r = mysqli_query($con, $q);
    $value = "";
    $value .= '<table id="dtBasicExample" class="table table-hover table-striped table-sm" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th class="th-sm">#</th>
                <th class="th-sm">Poid</th>
                <th class="th-sm">Buyer
                </th>
                <th class="th-sm">Item_Name
                </th>
                <th class="th-sm">Price
                </th>
                <th class="th-sm">Qty
                </th>
                <th class="th-sm">DeliveryFee
                </th>
                <th class="th-sm">Total
                </th> 
                <th class="th-sm">Rider
                </th>
                <th class="th-sm">Status
                </th>
                <th class="th-sm">Date_Status
                </th>
                <th class="th-sm">Date_Created
                </th>
            </tr>
        </thead>
        <tbody>
        ';
    while ($row = mysqli_fetch_assoc($r)) {
        $riderid = $row['riderid'];
        if ($row['status'] == "Delivered") {
            $statcolor = "#59EE61";
            $statcolor_2nd = "#90F395";
        } else if ($row['status'] == "Returned") {
            $statcolor = "#E7CD67";
            $statcolor_2nd = "#EFDD99   ";
        } else if ($row['status'] == "Cancelled") {
            $statcolor = "#E15361";
            $statcolor_2nd = "#B9797F";
        } else if ($row['status'] == "Queuing") {
            $statcolor = "#FF8F59";
            $statcolor_2nd = "#FFB490";
        } else {
            $statcolor = "#FFFFFF";
            $statcolor_2nd = "#DDDEDE";
        }
        $value .= '
            <tr style="background-color:' . $statcolor . '">
                <td>' . $row['userorderid'] . '</td>
                <td><span id="">' . $row['poid'] . '</span></td>
                <td><a href="send-report.php?report=buyer&fromreport=' . $sellerid . '&toreport=' .$row['userid']. '" target="_blank"><i class="fa fa-exclamation-triangle text-warning"></i></a>' . strtoupper($row['firstname']) . '_' . strtoupper($row['lastname']) . '</td>
                <td>' . $row['productname'] . '</td>
                <td>' . $row['price'] . '</td>
                <td>' . $row['qty'] . '</td>
                <td>' . $row['shippingfee'] . '</td>
                <td>' . $row['total'] . '</td>
                <td><a href="send-report.php?report=rider&fromreport=' . $sellerid . '&toreport=' .$row['riderid']. '" target="_blank"><i class="fa fa-exclamation-triangle text-warning"></i></a> ' . strtoupper($row['ridername']) . '</td>
                <td  style="background-color:' . $statcolor_2nd . '">' . $row['status'] . '</td>
                <td>' . $row['datestatus'] . '</td>
                <td>' . $row['datecreated'] . '</td>
          
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
                <th class="th-sm">Trigger
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
    $q1 = "SELECT * from user INNER JOIN seller ON seller.userid = user.userid WHERE (user.contact = '$userdetail' or user.email = '$userdetail');";
    $r1 = mysqli_query($con, $q1);

    while ($row1 = mysqli_fetch_assoc($r1)) {
        $sellerid = $row1['sellerid'];
    }

    if ((int)$x == 0) {
        //all
        DisplayOrders($userdetail);
    } else if ((int)$x == 4) {
        $q = "SELECT userorderid,poid,firstname,lastname,productname,userorder.price,userorder.shippingfee,qty,userorder.total,status,datestatus,datecreated FROM userorder 
        INNER JOIN product ON product.productid = userorder.productid 
        INNER JOIN user ON user.userid = userorder.userid 
        INNER JOIN masterlist ON masterlist.masterpoid = userorder.masterpoid 
       
        WHERE (status = 'Queuing') and sellerid = '$sellerid' and (datecreated BETWEEN '" . $fromdate . " 00:00:00' and '" . $todate . " 23:59:59')";
        $r = mysqli_query($con, $q);
        $value = "";
        $value .= '<table id="dtBasicExample" class="table table-hover table-striped table-sm" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th class="th-sm">#</th>
                    <th class="th-sm">Poid</th>
                    <th class="th-sm">Buyer
                    </th>
                    <th class="th-sm">Item_Name
                    </th>
                    <th class="th-sm">Price
                    </th>
                    <th class="th-sm">Qty
                    </th>
                    <th class="th-sm">DeliveryFee
                    </th>
                    <th class="th-sm">Total
                    </th> 
                    <th class="th-sm">Rider
                    </th>
                    <th class="th-sm">Status
                    </th>
                    <th class="th-sm">Date_Status
                    </th>
                    <th class="th-sm">Date_Created
                    </th>
                </tr>
            </thead>
            <tbody>
            ';
        while ($row = mysqli_fetch_assoc($r)) {
            if ($row['status'] == "Delivered") {
                $statcolor = "#59EE61";
                $statcolor_2nd = "#90F395";
            } else if ($row['status'] == "Returned") {
                $statcolor = "#E7CD67";
                $statcolor_2nd = "#EFDD99   ";
            } else if ($row['status'] == "Cancelled") {
                $statcolor = "#E15361";
                $statcolor_2nd = "#B9797F";
            } else if ($row['status'] == "Queuing") {
                $statcolor = "#FF8F59";
                $statcolor_2nd = "#FFB490";
            } else {
                $statcolor = "#FFFFFF";
                $statcolor_2nd = "#DDDEDE";
            }
            $value .= '
                <tr style="background-color:' . $statcolor . '">
                    <td>' . $row['userorderid'] . '</td>
                    <td><span id="">' . $row['poid'] . '</span></td>
                    <td>' . strtoupper($row['firstname']) . ' ' . strtoupper($row['lastname']) . '</td>
                    <td>' . $row['productname'] . '</td>
                    <td>' . $row['price'] . '</td>
                    <td>' . $row['qty'] . '</td>
                    <td>' . $row['shippingfee'] . '</td>
                    <td>' . $row['total'] . '</td>
                    <td></td>
                    <td  style="background-color:' . $statcolor_2nd . '">' . $row['status'] . '</td>
                    <td>' . $row['datestatus'] . '</td>
                    <td>' . $row['datecreated'] . '</td>
              
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
                    <th class="th-sm">Trigger
                    </th>
                </tr>
            </tfoot>
        </table>';


        echo $value;
    } else if ((int)$x == 1) {
        //accepted
        $q = "SELECT userorderid,poid,firstname,lastname,productname,userorder.price,userorder.shippingfee,qty,userorder.total,ridername,status,datestatus,datecreated FROM userorder 
    INNER JOIN product ON product.productid = userorder.productid 
    INNER JOIN user ON user.userid = userorder.userid 
    INNER JOIN masterlist ON masterlist.masterpoid = userorder.masterpoid 
    INNER JOIN rider ON rider.riderid = masterlist.riderid 
    WHERE (status = 'Accepted' or status = 'Preparing' or status = 'To Deliver') and sellerid = '$sellerid' and (datecreated BETWEEN '" . $fromdate . " 00:00:00' and '" . $todate . " 23:59:59')";
        $r = mysqli_query($con, $q);
        $value = "";
        $value .= '<table id="dtBasicExample" class="table table-hover table-striped table-sm" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th class="th-sm">#</th>
                <th class="th-sm">Poid</th>
                <th class="th-sm">Buyer
                </th>
                <th class="th-sm">Item_Name
                </th>
                <th class="th-sm">Price
                </th>
                <th class="th-sm">Qty
                </th>
                <th class="th-sm">DeliveryFee
                </th>
                <th class="th-sm">Total
                </th> 
                <th class="th-sm">Rider
                </th>
                <th class="th-sm">Status
                </th>
                <th class="th-sm">Date_Status
                </th>
                <th class="th-sm">Date_Created
                </th>
            </tr>
        </thead>
        <tbody>
        ';
        while ($row = mysqli_fetch_assoc($r)) {
            if ($row['status'] == "Delivered") {
                $statcolor = "#59EE61";
                $statcolor_2nd = "#90F395";
            } else if ($row['status'] == "Returned") {
                $statcolor = "#E7CD67";
                $statcolor_2nd = "#EFDD99   ";
            } else if ($row['status'] == "Cancelled") {
                $statcolor = "#E15361";
                $statcolor_2nd = "#B9797F";
            } else if ($row['status'] == "Queuing") {
                $statcolor = "#FF8F59";
                $statcolor_2nd = "#FFB490";
            } else {
                $statcolor = "#FFFFFF";
                $statcolor_2nd = "#DDDEDE";
            }
            $value .= '
            <tr style="background-color:' . $statcolor . '">
                <td>' . $row['userorderid'] . '</td>
                <td><span id="">' . $row['poid'] . '</span></td>
                <td>' . strtoupper($row['firstname']) . ' ' . strtoupper($row['lastname']) . '</td>
                <td>' . $row['productname'] . '</td>
                <td>' . $row['price'] . '</td>
                <td>' . $row['qty'] . '</td>
                <td>' . $row['shippingfee'] . '</td>
                <td>' . $row['total'] . '</td>
                <td>' . strtoupper($row['ridername']) . '</td>
                <td  style="background-color:' . $statcolor_2nd . '">' . $row['status'] . '</td>
                <td>' . $row['datestatus'] . '</td>
                <td>' . $row['datecreated'] . '</td>
          
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
                <th class="th-sm">Trigger
                </th>
            </tr>
        </tfoot>
    </table>';


        echo $value;
    } else {
        if ((int)$x == 2) {
            //cancel/return
            $q = "SELECT userorderid,poid,firstname,lastname,productname,userorder.price,userorder.shippingfee,qty,userorder.total,ridername,status,datestatus,datecreated FROM userorder 
    INNER JOIN product ON product.productid = userorder.productid 
    INNER JOIN user ON user.userid = userorder.userid 
    INNER JOIN masterlist ON masterlist.masterpoid = userorder.masterpoid 
    INNER JOIN rider ON rider.riderid = masterlist.riderid 
    WHERE (status = 'Cancelled' or status = 'Returned' ) and sellerid = '$sellerid' and (datecreated BETWEEN '" . $fromdate . " 00:00:00' and '" . $todate . " 23:59:59')";
            $r = mysqli_query($con, $q);
            $value = "";
            $value .= '<table id="dtBasicExample" class="table table-hover table-striped table-sm" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th class="th-sm">#</th>
                <th class="th-sm">Poid</th>
                <th class="th-sm">Buyer
                </th>
                <th class="th-sm">Item_Name
                </th>
                <th class="th-sm">Price
                </th>
                <th class="th-sm">Qty
                </th>
                <th class="th-sm">DeliveryFee
                </th>
                <th class="th-sm">Total
                </th> 
                <th class="th-sm">Rider
                </th>
                <th class="th-sm">Status
                </th>
                <th class="th-sm">Date_Status
                </th>
                <th class="th-sm">Date_Created
                </th>
            </tr>
        </thead>
        <tbody>
        ';
            while ($row = mysqli_fetch_assoc($r)) {
                if ($row['status'] == "Delivered") {
                    $statcolor = "#59EE61";
                    $statcolor_2nd = "#90F395";
                } else if ($row['status'] == "Returned") {
                    $statcolor = "#E7CD67";
                    $statcolor_2nd = "#EFDD99   ";
                } else if ($row['status'] == "Cancelled") {
                    $statcolor = "#E15361";
                    $statcolor_2nd = "#B9797F";
                } else if ($row['status'] == "Queuing") {
                    $statcolor = "#FF8F59";
                    $statcolor_2nd = "#FFB490";
                } else {
                    $statcolor = "#FFFFFF";
                    $statcolor_2nd = "#DDDEDE";
                }
                $value .= '
            <tr style="background-color:' . $statcolor . '">
                <td>' . $row['userorderid'] . '</td>
                <td><span id="">' . $row['poid'] . '</span></td>
                <td>' . strtoupper($row['firstname']) . ' ' . strtoupper($row['lastname']) . '</td>
                <td>' . $row['productname'] . '</td>
                <td>' . $row['price'] . '</td>
                <td>' . $row['qty'] . '</td>
                <td>' . $row['shippingfee'] . '</td>
                <td>' . $row['total'] . '</td>
                <td>' . strtoupper($row['ridername']) . '</td>
                <td  style="background-color:' . $statcolor_2nd . '">' . $row['status'] . '</td>
                <td>' . $row['datestatus'] . '</td>
                <td>' . $row['datecreated'] . '</td>
          
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
                <th class="th-sm">Trigger
                </th>
            </tr>
        </tfoot>
    </table>';


            echo $value;
        } else if ((int)$x == 3) {
            //delivered
            $q = "SELECT userorderid,poid,firstname,lastname,productname,userorder.price,userorder.shippingfee,qty,userorder.total,ridername,status,datestatus,datecreated FROM userorder 
    INNER JOIN product ON product.productid = userorder.productid 
    INNER JOIN user ON user.userid = userorder.userid 
    INNER JOIN masterlist ON masterlist.masterpoid = userorder.masterpoid 
    INNER JOIN rider ON rider.riderid = masterlist.riderid 
    WHERE (status = 'Accepted' or status = 'Delivered') and sellerid = '$sellerid' and (datecreated BETWEEN '" . $fromdate . " 00:00:00' and '" . $todate . " 23:59:59')";
            $r = mysqli_query($con, $q);
            $value = "";
            $value .= '<table id="dtBasicExample" class="table table-hover table-striped table-sm" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th class="th-sm">#</th>
                <th class="th-sm">Poid</th>
                <th class="th-sm">Buyer
                </th>
                <th class="th-sm">Item_Name
                </th>
                <th class="th-sm">Price
                </th>
                <th class="th-sm">Qty
                </th>
                <th class="th-sm">DeliveryFee
                </th>
                <th class="th-sm">Total
                </th> 
                <th class="th-sm">Rider
                </th>
                <th class="th-sm">Status
                </th>
                <th class="th-sm">Date_Status
                </th>
                <th class="th-sm">Date_Created
                </th>
            </tr>
        </thead>
        <tbody>
        ';
            while ($row = mysqli_fetch_assoc($r)) {
                if ($row['status'] == "Delivered") {
                    $statcolor = "#59EE61";
                    $statcolor_2nd = "#90F395";
                } else if ($row['status'] == "Returned") {
                    $statcolor = "#E7CD67";
                    $statcolor_2nd = "#EFDD99   ";
                } else if ($row['status'] == "Cancelled") {
                    $statcolor = "#E15361";
                    $statcolor_2nd = "#B9797F";
                } else if ($row['status'] == "Queuing") {
                    $statcolor = "#FF8F59";
                    $statcolor_2nd = "#FFB490";
                } else {
                    $statcolor = "#FFFFFF";
                    $statcolor_2nd = "#DDDEDE";
                }
                $value .= '
            <tr style="background-color:' . $statcolor . '">
                <td>' . $row['userorderid'] . '</td>
                <td><span id="">' . $row['poid'] . '</span></td>
                <td>' . strtoupper($row['firstname']) . ' ' . strtoupper($row['lastname']) . '</td>
                <td>' . $row['productname'] . '</td>
                <td>' . $row['price'] . '</td>
                <td>' . $row['qty'] . '</td>
                <td>' . $row['shippingfee'] . '</td>
                <td>' . $row['total'] . '</td>
                <td>' . strtoupper($row['ridername']) . '</td>
                <td  style="background-color:' . $statcolor_2nd . '">' . $row['status'] . '</td>
                <td>' . $row['datestatus'] . '</td>
                <td>' . $row['datecreated'] . '</td>
          
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
                <th class="th-sm">Trigger
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


    $fromdate = $_POST['fromdate'];
    $todate = $_POST['todate'];
    global $con;
    $q1 = "SELECT * from user INNER JOIN seller ON seller.userid = user.userid WHERE (user.contact = '$userdetail' or user.email = '$userdetail');";
    $r1 = mysqli_query($con, $q1);

    while ($row1 = mysqli_fetch_assoc($r1)) {
        $sellerid = $row1['sellerid'];
    }
    $q = "SELECT userorderid,poid,firstname,lastname,productname,userorder.price,userorder.shippingfee,qty,userorder.total,ridername,status,datestatus,datecreated FROM userorder 
    INNER JOIN product ON product.productid = userorder.productid 
    INNER JOIN user ON user.userid = userorder.userid 
    INNER JOIN masterlist ON masterlist.masterpoid = userorder.masterpoid 
    INNER JOIN rider ON rider.riderid = masterlist.riderid 
    WHERE sellerid = '$sellerid' and (datecreated BETWEEN '" . $fromdate . " 00:00:00' and '" . $todate . " 23:59:59')
    ";
    $r = mysqli_query($con, $q);

    $q5 = "SELECT * FROM userorder 
    INNER JOIN product ON product.productid = userorder.productid 
    INNER JOIN user ON user.userid = userorder.userid 
    INNER JOIN masterlist ON masterlist.masterpoid = userorder.masterpoid 
 
    WHERE (status = 'Queuing') and sellerid = '$sellerid' and (datecreated BETWEEN '" . $fromdate . " 00:00:00' and '" . $todate . " 23:59:59')";
    $r5 = mysqli_query($con, $q5);
    $rowcount5 = mysqli_num_rows($r5);

    $delivered = 0;
    ////////////
    $collected_ = 0;
    $shippingfee_ = 0;

    ///////////
    $returned = 0;
    $cancelled = 0;
    $delivered_ = 0;
    $returned_ = 0;
    $cancelled_ = 0;
    $accepted_ = 0;
    $prepare_ = 0;
    $todeliver_ = 0;

    $queuing_ = $rowcount5;
    while ($row = mysqli_fetch_assoc($r)) {
        $price = $row['price'];
        $qty = $row['qty'];
        $shippingfee = $row['shippingfee'];

        if ($row['status'] == "Delivered") {
            $delivered_ += ($delivered + 1);
            $collected_ += ((float)$price * (float)$qty);
            $shippingfee_ += (float)$shippingfee;
        }
        if ($row['status'] == "Returned") {
            $returned_ += ($returned + 1);
        }
        if ($row['status'] == "Cancelled") {
            $cancelled_ += ($cancelled + 1);
        }
        if ($row['status'] == "Accepted") {
            $accepted_ += ($accepted_ + 1);
        }
        if ($row['status'] == "Preparing") {
            $prepare_ += ($prepare_ + 1);
        }
        if ($row['status'] == "To Deliver") {
            $todeliver_ += ($todeliver_ + 1);
        }
    }

    echo '
    
    <div class="col-md-4 mb-3">
    <article class="card card-body">
        <figure class="text-center">
            <figcaption class="pt-2">
                <h5 class="title">₱' . number_format($collected_) . '</h5>
                <p>Collected Amount</p>
            </figcaption>
        </figure> 
    </article>
</div>
<div class="col-md-4 mb-3">
    <article class="card card-body">
        <figure class="text-center">
            <figcaption class="pt-2">
                <h5 class="title">₱' . number_format($shippingfee_) . '</h5>
                <p>Delivery Fee</p>
            </figcaption>
        </figure> 
    </article> 
</div>
<div class="col-md-4 mb-3">
    <article class="card card-body">
        <figure class="text-center">
            <figcaption class="pt-2">
                <h5 class="title">₱' . number_format($collected_ + $shippingfee_) . '</h5>
                <p>Total Sales</p>
            </figcaption>
        </figure> 
    </article> 
</div>
    
    
    <div class="col-md-3">
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
</div>
<div class="col-md-4 mt-3">
    <article class="card card-body">
        <figure class="text-center">
            <span class="rounded-circle icon-md bg-info"><i class="fa fa-user-alt white"></i></span>
            <figcaption class="pt-4">
                <h5 class="title">' . $accepted_ . '</h5>
                <p>Accepted</p>
            </figcaption>
        </figure> 
    </article>
</div>
<div class="col-md-4 mt-3">
    <article class="card card-body">
        <figure class="text-center">
            <span class="rounded-circle icon-md bg-info"><i class="fas fa-hand-holding-heart text-white"></i></span>
            <figcaption class="pt-4">
                <h5 class="title">' . $prepare_ . '</h5>
                <p>Preparing</p>
            </figcaption>
        </figure> 
    </article> 
</div>
<div class="col-md-4 mt-3">
    <article class="card card-body">
        <figure class="text-center">
            <span class="rounded-circle icon-md bg-info"><i class="fa fa-truck white"></i></span>
            <figcaption class="pt-4">
                <h5 class="title">' . $todeliver_ . '</h5>
                <p>To Deliver</p>
            </figcaption>
        </figure> 
    </article> 
</div>
';
}
