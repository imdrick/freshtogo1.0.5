<?php
require_once('connection.php');
date_default_timezone_set("Asia/Bangkok");

function DisplayVoucher($userdetail)
{
    global $con;
    $q1 = "SELECT * from user INNER JOIN seller ON seller.userid = user.userid WHERE (user.contact = '$userdetail' or user.email = '$userdetail');";
    $r1 = mysqli_query($con, $q1);
    $today = date("Y-m-d");
    while ($row1 = mysqli_fetch_assoc($r1)) {
        $sellerid = $row1['sellerid'];
    }
    $q2 = "SELECT * FROM voucher where sellerid = '$sellerid' order by voucherid desc";
    $r2 = mysqli_query($con, $q2);

    while ($row2 = mysqli_fetch_assoc($r2)) {
        $exp_date = $row2['exp_date'];
        $voucherid_ = $row2['voucherid'];
        if ($exp_date <= $today) {
            //auto expiration
            $q3 = "UPDATE voucher set isExpired = 'yes',isPublished = 'false' where voucherid = '$voucherid_'";
            $r3 =  mysqli_query($con, $q3);
            $isExpired = "EXPIRED";
        } else {
            $q3 = "UPDATE voucher set isExpired = 'no' where voucherid = '$voucherid_'";
            $r3 =  mysqli_query($con, $q3);
            $isExpired = "";
        }
        if ($row2['isPublished'] == "true") {
            $p_color = '<i style="color:green" id="pulse" class="fas fa-circle"></i>';
            $isPublish_dis = '<button class="btn btn-outline-danger" value="' . $row2['voucherid'] . '" id="deactivate-voucher"> Deactivate</button> <button class="btn btn-light" id="edit-voucher" value="' . $row2['voucherid'] . '"> <i class="fa fa-pen"></i> </button> <button href="#" class="btn btn-light" id="delete-modal-voucher" value="' . $row2['voucherid'] . '"> <i class="text-danger fa fa-trash" ></i> </button>';
        } else { //not Default
            $isPublish_dis = '<button class="btn btn-outline-success" value="' . $row2['voucherid'] . '" id="activate-voucher">  Activate</button> <button class="btn btn-light" id="edit-voucher" value="' . $row2['voucherid'] . '"> <i class="fa fa-pen"></i> </button> <button href="#" class="btn btn-light" id="delete-modal-voucher" value="' . $row2['voucherid'] . '"> <i class="text-danger fa fa-trash" ></i> </button>';
            $p_color = '<i style="color:red" id="pulse" class="fas fa-circle"></i>';
        }
        if ($row2['v_discount'] != 0) {
            $amount_dis = '<p>  <h6 >Voucher Code: <span style="color:#D95A00">' . $row2['vouchercode'] . '</span></h6><hr>  Discount: ' . number_format($row2['v_discount']) . '%<hr></p>';
        } else if ($row2['v_less'] != 0) {
            $amount_dis = '<p>  <h6 >Voucher Code: <span style="color:#D95A00">' . $row2['vouchercode'] . '</span></h6><hr>Less: - ₱' . number_format($row2['v_less']) . '<hr></p>';
        } else {
            $amount_dis = '<p>  <h6 >Voucher Code: <span style="color:#D95A00">' . $row2['vouchercode'] . '</span></h6><hr> Shipping Fee: - ₱' . number_format($row2['v_shipping'])  . ' <hr></p>';
        }
        echo '
        <div class="col-md-6 ' . $row2['voucherid'] . '">
            <article class="box mb-4">
                <h5>' . $p_color . '' . $row2['vouchername'] . ' <i class="text-danger">' . $isExpired . '</i></h5>
                ' . $amount_dis . '
                <div class="row mb-3">
                    <div class="col-6"><p><span class ="text-muted"> ' . number_format($row2['minamount']) . ' </span>minimum <br><span class ="text-muted"> ' . $row2['apply'] . '/' . number_format($row2['limitok']) . ' </span>applied</p></div>
                    <div class="col-6"><p><span class ="text-muted"> ' . $row2['start_date'] . ' </span> start <br><span class ="text-muted"> ' .  $exp_date . ' </span>end</p></div>
                </div>
                ' . $isPublish_dis . '
                </article>
        </div>
        <div class="modal fade" id="delete-prompt-voucher" tabindex="-1" aria-labelledby="delete-prompt-voucher" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="delete-prompt-voucher">Do you want to delete this voucher?</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-primary" data-dismiss="modal">No</button>
						<button type="button" class="btn btn-danger" id="delete-voucher"><span id="please-wait">Yes</span></button>
					</div>
				</div>
			</div>
		</div>
		</div>';
    }
}
function AddVoucher($userdetail)
{
    $vouchername = $_POST['vouchername'];
    $vouchercode = $_POST['vouchercode'];
    $minamount = $_POST['minamount'];
    $limit = $_POST['limit'];
    $start_date = $_POST['start_date'];
    $exp_date = $_POST['exp_date'];
    $amount = $_POST['amount'];
    $type = $_POST['type'];
    $ispublish = $_POST['ispublish'];
    $v_discount = $_POST['v_discount'];
    $v_less = $_POST['v_less'];
    $v_shipping = $_POST['v_shipping'];

    $today = date("Y-m-d");
    global $con;
    $q1 = "SELECT * from user INNER JOIN seller ON seller.userid = user.userid WHERE (user.contact = '$userdetail' or user.email = '$userdetail');";
    $r1 = mysqli_query($con, $q1);

    while ($row1 = mysqli_fetch_assoc($r1)) {
        $sellerid = $row1['sellerid'];
    }

    $q = "INSERT INTO voucher (sellerid,vouchername,vouchercode,minamount,limitok,date,start_date,exp_date,v_discount,v_less,v_shipping,isPublished,isExpired,apply)
    VALUES ('$sellerid','$vouchername','$vouchercode','$minamount','$limit','$today','$start_date','$exp_date','$v_discount','$v_less','$v_shipping','$ispublish','no','0')";
    $r = mysqli_query($con, $q);


    echo "$r";
}
function DeactivateVoucher($userdetail)
{
    global $con;
    $voucherid = $_POST['voucherid'];

    $q = "UPDATE voucher set isPublished = 'false' where voucherid = '$voucherid'";
    mysqli_query($con, $q);
    echo 'deactivated';
}
function ActivateVoucher($userdetail)
{
    global $con;
    $voucherid = $_POST['voucherid'];

    ////////////////
    $q2 = "SELECT * FROM voucher where voucherid = '$voucherid'";
    $r2 = mysqli_query($con, $q2);
    $today = date("Y-m-d");
    while ($row2 = mysqli_fetch_assoc($r2)) {

        $exp_date = $row2['exp_date'];
    }
    if ($exp_date <= $today) {
        //expired
        echo '0';
    } else {
        //notexpired
        $q = "UPDATE voucher set isPublished = 'true' where voucherid = '$voucherid'";
        mysqli_query($con, $q);
        echo '1';
    }
    ////////////




}
function EditVoucher($userdetail)
{
    $voucherid = $_POST['voucherid'];
    global $con;
    $q = "SELECT * from voucher where voucherid = '$voucherid'";
    $r = mysqli_query($con, $q);
    $type = "";
    $amount = "";
    while ($row = mysqli_fetch_assoc($r)) {
        $vouchername = $row['vouchername'];
        $vouchercode = $row['vouchercode'];
        $limit = $row['limitok'];
        $start_date = $row['start_date'];
        $exp_date = $row['exp_date'];
        $minamount = $row['minamount'];
        $v_less = $row['v_less'];
        $v_discount = $row['v_discount'];
        $v_shipping = $row['v_shipping'];

        $ispublish = $row['isPublished'];
    }
    if ($v_discount != 0) {
        $type = "1";
        $amount = $v_discount;
    } else if ($v_less != 0) {
        $type = "2";
        $amount = $v_less;
    } else {
        $type = "3";
        $amount = $v_shipping;
    }
    $res = array(
        'vouchername' => "$vouchername",
        'vouchercode' => "$vouchercode",
        'limit' => "$limit",
        'minamount' => "$minamount",
        'start_date' => "$start_date",
        'exp_date' => "$exp_date",
        'amount' => "$amount",
        'type' => "$type",
        'ispublish' => "$ispublish",
    );
    echo json_encode($res);
}
function SaveVoucher($userdetail)
{
    $vouchername = $_POST['vouchername'];
    $vouchercode = $_POST['vouchercode'];
    $minamount = $_POST['minamount'];
    $limit = $_POST['limit'];
    $start_date = $_POST['start_date'];
    $exp_date = $_POST['exp_date'];
    $amount = $_POST['amount'];
    $type = $_POST['type'];
    $ispublish = $_POST['ispublish'];
    $v_discount = $_POST['v_discount'];
    $v_less = $_POST['v_less'];
    $v_shipping = $_POST['v_shipping'];
    $today = date("Y-m-d");


    global $con;
    $q1 = "SELECT * from user INNER JOIN seller ON seller.userid = user.userid WHERE (user.contact = '$userdetail' or user.email = '$userdetail');";
    $r1 = mysqli_query($con, $q1);

    while ($row1 = mysqli_fetch_assoc($r1)) {
        $sellerid = $row1['sellerid'];
    }

    $q = "UPDATE voucher set vouchername = '$vouchername', minamount = '$minamount', limitok = '$limit', start_date = '$start_date', exp_date = '$exp_date', v_discount = '$v_discount', v_less = '$v_less', v_shipping = '$v_shipping', isPublished = '$ispublish' where vouchercode = '$vouchercode'";
    $r = mysqli_query($con, $q);
    echo $r;
}
function DeleteVoucher($userdetail)
{
    global $con;
    $voucherid = $_POST['voucherid'];
    $q = "DELETE FROM voucher where voucherid = '$voucherid' ";
    $r = mysqli_query($con, $q);
    echo "deleted";
}
