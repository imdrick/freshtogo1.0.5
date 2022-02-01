<?php

require_once('connection.php');

function DisplayInitial($userdetail)
{
    global $con;

    if (isset($_GET['to'])) {
        $to = $_GET['to'];
    } else {
        $to = "all";
    }

    $min = $_POST['min'];
    $max = $_POST['max'];
    if ((int)$min == 0 && (int)$max == 0) {
        $minmax_where = '';
    } else {
        $minmax_where = "WHERE ((saleprice BETWEEN $min AND $max) and saleprice != 0) OR ((regularprice BETWEEN $min AND $max) AND saleprice = 0) OR ((varsale BETWEEN $min AND $max) and varsale != 0) OR ((varregular BETWEEN $min AND $max) and varsale = 0)";
    }
    $count_product = 0;
    $q100 = "SELECT * FROM product 
    LEFT JOIN mastervariation ON mastervariation.code = product.code
    LEFT JOIN variation ON variation.varcode = mastervariation.varcode
    $minmax_where
    GROUP  BY productid";
    $r100 = mysqli_query($con, $q100);
    while ($row100 = mysqli_fetch_assoc($r100)) {
        $count_product += 1;
    }
    $value = '';
    $value .= '<header class="mb-3">
   <div class="form-inline">
       <strong class="mr-md-auto">' . $count_product . ' Items found </strong>
       <select class="mr-2 form-control" hidden>
           <option>Latest items</option>
           <option>Top Products</option>
       </select>
       <div class="btn-group" hidden>
           <a href="page-listing-grid.html" class="btn btn-light active" data-toggle="tooltip" title="List view">
               <i class="fa fa-bars"></i></a>
           <a href="page-listing-large.html" class="btn btn-light" data-toggle="tooltip" title="Grid view">
               <i class="fa fa-th"></i></a>
       </div>
   </div>
</header>
<div class="row">
';
    /////////////////////
    /////Pagination//////////////
    $pageno_set = (int)$_POST['pagination_set'];
    $no_of_records_per_page = 8;
    $total_pages = ceil($count_product / $no_of_records_per_page);
    ///Get the current page number
    if ($pageno_set == 0) {
        $pageno = 1;
    } else {
        $pageno = $pageno_set;
    }
    $x = '';
    $x = $_POST['x'];
    $show_hidden = "";
    if ($x == 1) {
        //exist dontruin hidden
        $show_hidden = "";
    } else {
        //not exist ruin hidden
        $show_hidden = "1";
    }
    $offset = ($pageno - 1) * $no_of_records_per_page;
    //////////////////////
    $isSale = "hidden";
    $q = "SELECT * FROM product 
    INNER JOIN seller ON seller.sellerid = product.sellerid
    LEFT JOIN mastervariation ON mastervariation.code = product.code
    LEFT JOIN variation ON variation.varcode = mastervariation.varcode
    $minmax_where
    GROUP  BY productid  order by productid desc LIMIT $offset,$no_of_records_per_page";
    $r = mysqli_query($con, $q);

    $deal_show = '';
    $ship_show = '';
    $varshipping = 0;
    $isShip = '';
    while ($row = mysqli_fetch_assoc($r)) {
        $reg = $row['regularprice'];
        $sale = $row['saleprice'];
        $code = $row["code"];
        $shipping = $row['shippingtype'];
        $productid = $row['productid'];

        //filter by category
        //
        if ($code == "") {
            if ((int)$shipping == 0) {
                //no shipp
                $ship_show = '';
            } else {
                //have shipping
                $ship_show = 'hidden';
            }
            ////////
            if ((int)$sale == 0) {
                $deal_show = 'hidden' . $show_hidden;
                $price =  '₱' . number_format($reg) . '';
            } else {
                $isSale = "";
                $deal_show = '' . $show_hidden;
                $price =  '<p><font  size= "2" style="color:red; text-decoration: line-through;" > ₱' . number_format($reg) . '</font> ₱' . number_format($sale) . '</p> ';
            }
        } else {
            $q1 = "SELECT shippingtype,varsale,min(varregular) AS min_regularprice, max(varregular) AS max_regularprice, min(varsale) AS min_saleprice, max(varsale) AS max_saleprice FROM variation INNER JOIN mastervariation ON mastervariation.varcode = variation.varcode WHERE CODE = '$code'";
            $r1 = mysqli_query($con, $q1);
            while ($row1 = mysqli_fetch_assoc($r1)) {

                $min_regularprice = $row1['min_regularprice'];
                $max_regularprice = $row1['max_regularprice'];
                $min_saleprice = $row1['min_saleprice'];
                $max_saleprice = $row1['max_saleprice'];
                $varshipping = $row1['shippingtype'];
                if ((int)$row1['varsale'] == 0) {
                    if ((int)$varshipping == 0) {
                        //no shipp
                        $ship_show = '';
                    } else {
                        //have shipping
                        $ship_show = 'hidden';
                    }

                    $deal_show = 'hidden' . $show_hidden;
                    $price =  '<p> ₱' . number_format($min_regularprice) . ' - ₱' . number_format($max_regularprice) . '</p> ';
                } else {
                    $isSale = "";
                    $deal_show = '' . $show_hidden;
                    $price =  '<p> ₱' . number_format($min_saleprice) . ' - ₱' . number_format($max_saleprice) . '</p> ';
                }
            }
        }
        $value .= '
   <div class="col-md-3" ' . $deal_show . ' ' . $ship_show . '>
       <figure class="card card-product-grid">
           <div class="img-wrap">
               <a href="page-detail-product.php?id=' . $row['productid'] . '"><img src="images/product/img/' . $row['imgurl'] . '"></a>
               
           </div> 
           
           <figcaption class="info-wrap">
           
               <a href="#" class="title mb-2">' . $row['productname'] . '</a>
               <div class="price-wrap">
                   <span class="price">' . $price . '</span>
               </div> 
               <p class="mb-2">' . $row['stock'] . ' Stocks <small class="text-muted">(left)</small></p>
               <p class="text-muted ">' . $row['storename'] . '</p>
               <span class="badge badge-danger" ' . $isSale . '> SALE! </span>
               <span class="badge badge-info" ' . $ship_show . '> Free Delivery! </span>
               <p class="mb-3" hidden>
                   <span class="tag"> <i class="fa fa-check"></i> Verified</span>
                   <span class="tag"> 2 Years </span>
                   <span class="tag"> 23 reviews </span>
                   <span class="tag"> Japan </span>
               </p>
               <label class="custom-control mb-3 custom-checkbox" hidden>
                   <input type="checkbox" class="custom-control-input">
                   <div class="custom-control-label">Add to compare
                   </div>
               </label>
               <a href="#" class="btn btn-outline-primary" hidden> <i class="fa fa-envelope"></i> Contact supplier </a>
           </figcaption>
       </figure>
   </div>    ';
    }
    ////////////////////////////
    $value .= '
</div> ';
    $value .= '<nav class="mb-4" aria-label="Page navigation sample" id="pagination">
    <ul class="pagination">
    <li class="page-item"><button class="page-link" value = "minus" id="prepagination-btn">Previous</button></li>
    ';
    for ($i = 1; $i <= $total_pages; $i++) {
        if ($pageno_set == $i) {
            $active = "active";
        } else {
            $active = "";
        }
        $value .= '
    <li class="page-item ' . $active . '"><button value="' . $i . '" class="page-link " id="pagination-btn">' . $i . '</button></li>
    ';
    }
    $value .= '
    <li class="page-item"><button class="page-link" value = "plus" id="postpagination-btn">Next</button></li>
</ul>
</nav><span id="max-page" class = "text-white">' . $total_pages . '</span>';
    echo $value;
}

function Pagination($userdetail)
{
    $value = '';
    $value .= '';
}
