<?php
require_once('connection.php');
//dis product
function DisplayVoucher()
{
    global $con;

    $q = "select * from voucher inner join seller on seller.sellerid = voucher.sellerid where isPublished = 'true'";
    $r = mysqli_query($con, $q);
    while ($row = mysqli_fetch_assoc($r)) {
        $v_discount = $row['v_discount'];
        $v_less = $row['v_less'];
        $img = "";
        $amount = "";
        if ($v_discount != 0) {
            $img = 'discount.png';
            $amount = $v_discount . "% off";
        } else {
            $img = 'less.png';
            $amount = '<span style="font-size:12px;color:black">less</span> ₱' . $v_less;
        }

        echo '<div class="col-xl-2 col-lg-3 col-md-4 col-6">
    <div class="card card-sm card-product-grid">
        <a href="#" class="img-wrap"> <img src="images/items/' . $img . '"> </a>
        <figcaption class="info-wrap text-center">
            <a href="#" class="title">' . $row['storename'] . '</a>
            <div class="price mt-1">' . $row['vouchercode'] . '</div> <!-- price-wrap.// -->
            <hr>
            <div class="row mb-3">
                <div class="col-12">
                    <p class="text-muted"><span style="font-size:12px;color:black">min of</span> ₱' . $row['minamount'] . '</p>
                </div>
                <div class="col-12">
                    <p class="text-muted">' . $amount . '</p>
                </div>
               
            </div>
            <button class="btn btn-primary" hidden>Apply</button>

        </figcaption>
    </div>
</div>';
    }
}
