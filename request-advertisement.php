<?php function AdsSection()
{
    global $con;
    $value = "";
    $q = "SELECT * from ad 
    INNER JOIN product on product.productid = ad.productid
    INNER JOIN seller on seller.sellerid = product.sellerid
    order by adid desc";
    $r = mysqli_query($con, $q);
    $value .= '
    <table id="example_ad" class="table table-striped table-bordered dt-responsive nowrap table-dark" style="width:100%">
        <thead>
            <tr>
            <th scope="col">#</th>
            <th scope="col">ProductName</th>
            <th scope="col">StoreName</th>
            <th scope="col">StartDate</th>
            <th scope="col">ExpDate</th>
            <th scope="col">isPublished
            </th>
            <th scope="col">isExpired
            </th>
            <th scope="col">Remarks
            </th>
            <th scope="col">Action
            </th>
            </tr>
        </thead>
        <tbody>
        ';
    while ($row = mysqli_fetch_assoc($r)) {
        if ($row['isExpired'] == "yes") {
            $disabled = "disabled";
        } else {
            $disabled = "";
        }
        $value .= '
            <tr>
                <th scope="row">1</th>
                <td>' . $row['productname'] . '</td>
                <td>' . $row['storename'] . '</td>
                <td>' . $row['start_date'] . '</td>
                <td>' . $row['exp_date'] . '</td>
                <td>' . $row['isPublished'] . '</td>
                <td>' . $row['isExpired'] . '</td>
                <td>' . $row['remarks'] . '</td>
                <td><button ' . $disabled . ' type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#exampleModal" value="' . $row['adid'] . '" id="action-btn-ad"><i class="fas fa-caret-up"></i></button></td>
            </tr>
            ';
    }
    $value .= '
        </tbody>
    </table>
<script>
    $(document).ready(function() {
        var table = $("#example_ad").DataTable({
            lengthChange: true,
            buttons: ["copy", "excel", "csv", "pdf"]
        });

        table.buttons().container()
            .appendTo("#example_wrapper_ad");
    });
</script>

    ';
    echo $value;
}
function PopulateModalAd()
{
    global $con;
    $adid = $_POST['adid'];
    $q = "SELECT * FROM ad where adid = '$adid'";
    $r = mysqli_query($con, $q);
    while ($row = mysqli_fetch_assoc($r)) {
        echo '
    <div class="card" style="width: 100%">
    <div class="card-body">
        <p class="card-text">Proof of Payment: <span id="get-adid" class="text-white">' . $row['adid'] . '</span></p>
    </div>
    <img src="images/product/proof/' . $row['proof'] . '" class="card-img-top" alt="...">
    
</div>
<div class="card" style="width: 100%">
    <div class="card-body">
        <p class="card-text">Ad Picture:</p>
    </div>
    <img src="images/product/ad/' . $row['adpic'] . '" class="card-img-top" alt="...">
</div>
<div class="card" style="width: 100%">
    <div class="card-body">
        <input class="form-control" placeholder="Remarks" id="ad-remarks">
    </div>
</div>
';
    }
}
function ApproveAd()
{
    global $con;
    $adid = $_POST['adid'];
    $q = "UPDATE ad set isPublished = 'approved' where adid = '$adid'";
    $r = mysqli_query($con, $q);
}
function RejectAd()
{
    global $con;
    $adid = $_POST['adid'];
    $remarks = $_POST['remarks'];

    $q = "UPDATE ad set isPublished = 'rejected',remarks ='$remarks' where adid = '$adid'";
    $r = mysqli_query($con, $q);
}
