<?php
require_once('../include/connection.php');
require_once('../include/home.php');


function DisplayHotProduct($userdetail)
{

    global $con;
    $q = "SELECT * FROM product INNER JOIN category ON category.categoryid = product.categoryid where isDisabled is null ORDER by productid desc LIMIT 8";
    $r = mysqli_query($con, $q);
    while ($row =  mysqli_fetch_assoc($r)) {
        $saleprice = $row["saleprice"];
        $regularprice = $row["regularprice"];
        $code = $row["code"];
        $productid = $row['productid'];
        //////////
        /////////////////
        $q30 = "SELECT * from review inner join user on user.userid = review.userid where productid = '$productid' order by reviewid desc";
        $r30 = mysqli_query($con, $q30);
        $rowcount30 = mysqli_num_rows($r30);
        $rating_count = 0;
        $rating_count_ = 0;
        while ($row30 = mysqli_fetch_assoc($r30)) {
            $rating_count_ = $row30['rating'];
            $rating_count += (float)$rating_count_;
        }
        if ($rowcount30 == 0) {
            $rating_total = 0;
        } else {
            $rating_total = ((float)$rating_count / (float)$rowcount30);
        }

        $rating_percent = (($rating_total / 5) * 100) . "%";
        ////////////////


        ///////////
        if ($code == "") {
            if ((int)$saleprice == 0) {
                $price =  '₱' . number_format($regularprice) . '';
            } else {
                $price =  '<p><font  size= "2" style="color:red; text-decoration: line-through;" > ₱' . number_format($regularprice) . '</font> ₱' . number_format($saleprice) . '</p> ';
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
        echo '
        <div class="col-xl-3 col-lg-3 col-md-4 col-6"; >
            <div class="card card-product-grid">
                <a href="page-detail-product.php?id=' . $row['productid'] . '" class="img-wrap"> <img src="images/product/img/' . $row['imgurl'] . '"> </a>
                <figcaption class="info-wrap">
                    <ul class="rating-stars mb-1">
                        <li style="width:'.$rating_percent.'" class="stars-active">
                            <img src="images/icons/stars-active.svg" alt="">
                        </li>
                        <li>
                            <img src="images/icons/starts-disable.svg" alt="">
                        </li>
                    </ul>
                    <div>
                        <a class="text-muted">' . $row['categoryname'] . '</a>
                        <a class="title">' . $row['productname'] . '</a>
                    </div>
                    <div class="price h5 mt-2">' . $price . '</div> <!-- price.// -->
                </figcaption>
            </div>           
        </div>';
    }
}
