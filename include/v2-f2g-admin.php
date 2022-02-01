<?php
require_once('connection.php');

function DisplayReportSeller()
{

    global $con;
    $value = "";
    $q = "SELECT * FROM reportseller 
    INNER JOIN seller on seller.sellerid = reportseller.sellerid 
     order by reportsellerid desc";
    $r = mysqli_query($con, $q);
    $rowcount = mysqli_num_rows($r);
    $value .= '
    <h4 class="section-title">Reported Sellers</h4>
    <table id="example" class="table table-striped table-bordered dt-responsive nowrap table-dark" style="width:100%" >
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Report User</th>
                <th scope="col">Seller Name</th>
                <th scope="col">Reason</th>
                <th scope="col">Link</th>
                <th scope="col">Date</th>
            </tr>
        </thead>
        <tbody>
        ';
    $i = 0;
    while ($row = mysqli_fetch_assoc($r)) {
        $isDisabled = $row['link'];
        if ($isDisabled == "0") {
            $hidden = '';
        } else {
            $hidden = '<a href="page-detail-product.php?id=' . $row['link'] . '" class="text-warning">View</a>';
        }
        $i += 1;
        $value .= '
            <tr>
                <th scope="row">' . $i . '</th>
                <td>' . $row['user'] . '</td>
                <td>' . $row['storename'] . '</td>
                <td>' . $row['reason'] . '</td>
                <td >' . $hidden . '</td>
                <td>' . $row['date'] . '</td>
            </tr>
            ';
    }
    $value .= '
        </tbody>
    </table>

<script>
    $(document).ready(function() {
        var table = $("#example").DataTable({
            lengthChange: true,
            buttons: ["copy", "excel", "csv", "pdf"]
        });

        table.buttons().container()
            .appendTo("#example_wrapper1 .col-md-6:eq(0)");
    });
</script>';
    echo $value;
}
function DisplayReportRider()
{
    global $con;
    $value = "";
    $q = "SELECT * FROM reportrider 
    INNER JOIN rider on rider.riderid = reportrider.riderid 
     order by reportriderid desc";
    $r = mysqli_query($con, $q);
    $rowcount = mysqli_num_rows($r);
    $value .= '
    <h4 class="section-title">Reported Riders</h4>
    <div id="example_wrapper1">
	</div>
    <table id="example1" class="table table-striped table-bordered dt-responsive nowrap table-dark" style="width:100%" >
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Report Seller</th>
                <th scope="col">Rider Name</th>
                <th scope="col">Reason</th>
                <th scope="col">Date</th>
            </tr>
        </thead>
        <tbody>
        ';
    $i = 0;
    while ($row = mysqli_fetch_assoc($r)) {
        $i += 1;
        $value .= '
            <tr>
                <th scope="row">' . $i . '</th>
                <td>' . $row['user'] . '</td>
                <td>' . $row['ridername'] . '</td>
                <td>' . $row['reason'] . '</td>
                <td>' . $row['date'] . '</td>
            </tr>
            ';
    }
    $value .= '
        </tbody>
    </table>

<script>
    $(document).ready(function() {
        var table = $("#example1").DataTable({
            lengthChange: true,
            buttons: ["copy", "excel", "csv", "pdf"]
        });

        table.buttons().container()
            .appendTo("#example_wrapper1 .col-md-6:eq(0)");
    });
</script>';
    echo $value;
}
function DisplayReportUser()
{
    global $con;
    $value = "";
    $q = "SELECT * FROM reportuser 
    INNER JOIN user on user.userid = reportuser.userid 
     order by reportuserid desc";
    $r = mysqli_query($con, $q);
    $rowcount = mysqli_num_rows($r);
    $value .= '
    <h4 class="section-title">Reported Users</h4>
    <div id="example_wrapper1">
	</div>
    <table id="example21" class="table table-striped table-bordered dt-responsive nowrap table-dark" style="width:100%" >
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Report Seller</th>
                <th scope="col">Customer Name</th>
                <th scope="col">Reason</th>
                <th scope="col">Date</th>
            </tr>
        </thead>
        <tbody>
        ';
    $i = 0;
    while ($row = mysqli_fetch_assoc($r)) {
        $i += 1;
        $value .= '
            <tr>
                <th scope="row">' . $i . '</th>
                <td>' . $row['user'] . '</td>
                <td>' . $row['firstname'] . ' ' . $row['lastname'] . '</td>
                <td>' . $row['reason'] . '</td>
                <td>' . $row['date'] . '</td>
            </tr>
            ';
    }
    $value .= '
        </tbody>
    </table>

<script>
    $(document).ready(function() {
        var table = $("#example21").DataTable({
            lengthChange: true,
            buttons: ["copy", "excel", "csv", "pdf"]
        });

        table.buttons().container()
            .appendTo("#example_wrapper21 .col-md-6:eq(0)");
    });
</script>';
    echo $value;
}
function CountReportSeller()
{
    global $con;
    $value = "";
    $q = "SELECT * FROM reportseller 
    INNER JOIN seller on seller.sellerid = reportseller.sellerid  where isDone is null GROUP BY reportseller.sellerid 
     order by reportsellerid desc ";
    $r = mysqli_query($con, $q);


    $rowcount = mysqli_num_rows($r);
    $value .= '
    <h4 class="section-title">Count Reports(SELLER)</h4>
    <table class="table table-secondary " >
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Report_Count</th>
                <th scope="col">Seller Name</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
        ';
    $i = 0;
    while ($row = mysqli_fetch_assoc($r)) {
        $sellerid_ = $row['sellerid'];
        $q3 = "SELECT * FROM reportseller where sellerid = '$sellerid_' ";
        $r3 = mysqli_query($con, $q3);
        $count = 0;
        while ($row3 = mysqli_fetch_assoc($r3)) {
            $count += 1;
        }

        $isDisabled = $row['isDisabled'];
        if ($isDisabled != "") {
            $hidden = 'disabled';
        } else {
            $hidden = "";
        }
        $i += 1;
        $value .= '
            <tr>
                <th scope="row">' . $i . '</th>
                <td>' . $count . '</td>
                <td>' . $row['storename'] . '</td>
                <td><button ' . $hidden . ' class="btn btn-danger" value="' . $row['reportsellerid'] . '" id="disabled-seller">Disabled</button></td>
            </tr>
            ';
    }
    $value .= '
        </tbody>
    </table>
    ';
    echo $value;
}
function CountReportRider()
{
    global $con;
    $value = "";
    $q = "SELECT * FROM reportrider 
     INNER JOIN rider on rider.riderid = reportrider.riderid 
       where isDone is null GROUP BY reportrider.riderid 
       order by reportriderid DESC";
    $r = mysqli_query($con, $q);


    $rowcount = mysqli_num_rows($r);
    $value .= '
    <h4 class="section-title">Count Reports(RIDER)</h4>
    <table class="table table-secondary " >
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Report_Count</th>
                <th scope="col">Rider Name</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
        ';
    $i = 0;
    while ($row = mysqli_fetch_assoc($r)) {
        $riderid_ = $row['riderid'];
        $q3 = "SELECT * FROM reportrider where riderid = '$riderid_' ";
        $r3 = mysqli_query($con, $q3);
        $count = 0;
        while ($row3 = mysqli_fetch_assoc($r3)) {
            $count += 1;
        }

        $isDisabled = $row['isDisabled'];
        if ($isDisabled != "") {
            $hidden = 'disabled';
        } else {
            $hidden = "";
        }
        $i += 1;
        $value .= '
            <tr>
                <th scope="row">' . $i . '</th>
                <td>' . $count . '</td>
                <td>' . $row['ridername'] . '</td>
                <td><button ' . $hidden . ' class="btn btn-danger" value="' . $row['reportriderid'] . '" id="disabled-rider">Disabled</button></td>
            </tr>
            ';
    }
    $value .= '
        </tbody>
    </table>
    ';
    echo $value;
}
function CountReportUser()
{
    global $con;
    $value = "";
    $q = "SELECT * FROM reportuser 
    INNER JOIN user on user.userid = reportuser.userid 
      where isDone is null GROUP BY reportuser.userid 
      order by reportuserid DESC";
    $r = mysqli_query($con, $q);


    $rowcount = mysqli_num_rows($r);
    $value .= '
    <h4 class="section-title">Count Reports(USER)</h4>
    <table class="table table-secondary " >
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Report_Count</th>
                <th scope="col">Customer Name</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
        ';
    $i = 0;
    while ($row = mysqli_fetch_assoc($r)) {
        $userid_ = $row['userid'];
        $q3 = "SELECT * FROM reportuser where userid = '$userid_'";
        $r3 = mysqli_query($con, $q3);
        $count = 0;
        while ($row3 = mysqli_fetch_assoc($r3)) {
            $count += 1;
        }

        $isDisabled = $row['isDisabled'];
        if ($isDisabled != "") {
            $hidden = 'disabled';
        } else {
            $hidden = "";
        }
        $i += 1;
        $value .= '
            <tr>
                <th scope="row">' . $i . '</th>
                <td>' . $count . '</td>
                <td>' . $row['firstname'] . '' . $row['lastname'] . '</td>
                <td><button ' . $hidden . ' class="btn btn-danger" value="' . $row['reportuserid'] . '" id="disabled-user">Disabled</button></td>
            </tr>
            ';
    }
    $value .= '
        </tbody>
    </table>
    ';
    echo $value;
}
function DisableSeller()
{
    global $con;
    $reportid = $_POST['reportid'];
    $q = "SELECT * FROM reportseller where reportsellerid = '$reportid'";
    $r = mysqli_query($con, $q);
    while ($row = mysqli_fetch_assoc($r)) {
        $sellerid = $row['sellerid'];
    }
    $q1 = "UPDATE seller set isDisabled = 'yes' where sellerid = '$sellerid'";
    $r1 = mysqli_query($con, $q1);
    $q2 = "UPDATE product set isDisabled = 'yes' where sellerid = '$sellerid'";
    $r2 = mysqli_query($con, $q2);
    $q3 = "UPDATE reportseller set isDone = 'yes' where sellerid = '$sellerid'";
    $r3 = mysqli_query($con, $q3);
    echo $r2;
}
function DisableRider()
{
    global $con;
    $reportid = $_POST['reportid'];
    $q = "SELECT * FROM reportrider where reportriderid = '$reportid'";
    $r = mysqli_query($con, $q);
    while ($row = mysqli_fetch_assoc($r)) {
        $riderid = $row['riderid'];
    }
    $q1 = "UPDATE rider set isDisabled = 'yes' where riderid = '$riderid'";
    $r1 = mysqli_query($con, $q1);
    $q3 = "UPDATE reportrider set isDone = 'yes' where riderid = '$riderid'";
    $r3 = mysqli_query($con, $q3);
    echo "$r1 $r3";
}
function DisableUser()
{
    global $con;
    $reportid = $_POST['reportid'];
    $q = "SELECT * FROM reportuser where reportuserid = '$reportid'";
    $r = mysqli_query($con, $q);
    while ($row = mysqli_fetch_assoc($r)) {
        $userid = $row['userid'];
    }
    $q1 = "UPDATE user set isDisabled = 'yes' where userid = '$userid'";
    $r1 = mysqli_query($con, $q1);
    $q3 = "UPDATE reportuser set isDone = 'yes' where userid = '$userid'";
    $r3 = mysqli_query($con, $q3);
    echo "$userid $r3";
}
function DisplayRequestSeller()
{
    global $con;
    $value = "";
    $q = "SELECT * FROM requestseller 
    INNER JOIN seller on seller.sellerid = requestseller.sellerid where isDone is null group by storename order by requestsellerid desc";
    $r = mysqli_query($con, $q);
    $rowcount
        = mysqli_num_rows($r);
    $value .= '
    <h4 class="section-title">Request Review(SELLER)</h4>
    <table class="table table-dark " >
        <thead>
            <tr>
            <th scope="col">#</th>
            <th scope="col">Seller</th>
            <th scope="col">Reason</th>
            <th scope="col">Date</th>
            <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
        ';
    $i = 0;
    while ($row = mysqli_fetch_assoc($r)) {
        $isDisabled = $row['isDisabled'];
        if ($isDisabled != "") {
            $hidden = "";
        } else {
            $hidden = 'disabled';
        }
        $i += 1;
        $value .= '
            <tr>
                <th scope="row">' . $i . '</th>
                <td>' . $row['storename'] . '</td>
                <td>' . $row['reason'] . '</td>
                <td>' . $row['date'] . '</td>
                <td><button ' . $hidden . ' class="btn btn-success" value="' . $row['requestsellerid'] . '" id="enable-seller">Enabled</button></td>

            </tr>
            ';
    }
    $value .= '
        </tbody>
    </table>';
    echo $value;
}
function DisplayRequestRider()
{
    global $con;
    $value = "";
    $q = "SELECT * FROM requestrider INNER JOIN rider ON rider.riderid = requestrider.riderid WHERE isDone IS NULL GROUP BY requestrider.riderid ORDER BY requestriderid desc";
    $r = mysqli_query($con, $q);
    $rowcount
        = mysqli_num_rows($r);
    $value .= '
    <h4 class="section-title">Request Review(RIDER)</h4>
    <table class="table table-dark " >
        <thead>
            <tr>
            <th scope="col">#</th>
            <th scope="col">Rider</th>
            <th scope="col">Reason</th>
            <th scope="col">Date</th>
            <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
        ';
    $i = 0;
    while ($row = mysqli_fetch_assoc($r)) {
        $isDisabled = $row['isDisabled'];
        if ($isDisabled != "") {
            $hidden = "";
        } else {
            $hidden = 'disabled';
        }
        $i += 1;
        $value .= '
            <tr>
                <th scope="row">' . $i . '</th>
                <td>' . $row['ridername'] . '</td>
                <td>' . $row['reason'] . '</td>
                <td>' . $row['date'] . '</td>
                <td><button ' . $hidden . ' class="btn btn-success" value="' . $row['requestriderid'] . '" id="enable-rider">Enabled</button></td>
            </tr>
            ';
    }
    $value .= '
        </tbody>
    </table>';
    echo $value;
}
function DisplayRequestUser()
{
    global $con;
    $value = "";
    $q = "SELECT * FROM requestuser INNER JOIN user ON user.userid = requestuser.userid WHERE isDone IS NULL GROUP BY requestuser.userid ORDER BY requestuserid desc";
    $r = mysqli_query($con, $q);
    $rowcount
        = mysqli_num_rows($r);
    $value .= '
    <h4 class="section-title">Request Review(USER)</h4>
    <table class="table table-dark " >
        <thead>
            <tr>
            <th scope="col">#</th>
            <th scope="col">User</th>
            <th scope="col">Reason</th>
            <th scope="col">Date</th>
            <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
        ';
    $i = 0;
    while ($row = mysqli_fetch_assoc($r)) {
        $isDisabled = $row['isDisabled'];
        if ($isDisabled != "") {
            $hidden = "";
        } else {
            $hidden = 'disabled';
        }
        $i += 1;
        $value .= '
            <tr>
                <th scope="row">' . $i . '</th>
                <td>' . $row['firstname'] . ' ' . $row['lastname'] . '</td>
                <td>' . $row['reason'] . '</td>
                <td>' . $row['date'] . '</td>
                <td><button ' . $hidden . ' class="btn btn-success" value="' . $row['requestuserid'] . '" id="enable-user">Enabled</button></td>
            </tr>
            ';
    }
    $value .= '
        </tbody>
    </table>';
    echo $value;
}
function EnableSeller()
{
    global $con;
    $requestid = $_POST['requestid'];
    $q = "SELECT * FROM requestseller where requestsellerid = '$requestid'";
    $r = mysqli_query($con, $q);
    while ($row = mysqli_fetch_assoc($r)) {
        $sellerid = $row['sellerid'];
    }
    $q1 = "UPDATE seller set isDisabled = null where sellerid = '$sellerid'";
    $r1 = mysqli_query($con, $q1);
    $q2 = "UPDATE product set isDisabled = null where sellerid = '$sellerid'";
    $r2 = mysqli_query($con, $q2);
    $q3 = "UPDATE requestseller set isDone = 'yes' where sellerid = '$sellerid'";
    $r3 = mysqli_query($con, $q3);
    echo $r2;
}
function EnableRider()
{
    global $con;
    $requestid = $_POST['requestid'];
    $q = "SELECT * FROM requestrider where requestriderid = '$requestid'";
    $r = mysqli_query($con, $q);
    while ($row = mysqli_fetch_assoc($r)) {
        $riderid = $row['riderid'];
    }
    $q1 = "UPDATE rider set isDisabled = null where riderid = '$riderid'";
    $r1 = mysqli_query($con, $q1);

    $q3 = "UPDATE requestrider set isDone = 'yes' where riderid = '$riderid'";
    $r3 = mysqli_query($con, $q3);
    echo $r1;
}
function EnableUser()
{
    global $con;
    $requestid = $_POST['requestid'];
    $q = "SELECT * FROM requestuser where requestuserid = '$requestid'";
    $r = mysqli_query($con, $q);
    while ($row = mysqli_fetch_assoc($r)) {
        $userid = $row['userid'];
    }
    $q1 = "UPDATE user set isDisabled = null where userid = '$userid'";
    $r1 = mysqli_query($con, $q1);

    $q3 = "UPDATE requestuser set isDone = 'yes' where userid = '$userid'";
    $r3 = mysqli_query($con, $q3);
    echo $r1;
}
function DisplayRequestSellerHistory()
{
    global $con;
    $value = "";
    $q = "SELECT * FROM reportseller 
    INNER JOIN seller on seller.sellerid = reportseller.sellerid 
    where isDone is null
     order by reportsellerid desc";
    $r = mysqli_query($con, $q);
    $rowcount = mysqli_num_rows($r);
    $value .= '
    <h4 class="section-title">Reported Seller</h4>
    <table class="table table-dark " >
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Report User</th>
                <th scope="col">Seller Name</th>
                <th scope="col">Reason</th>
                <th scope="col">Link</th>
                <th scope="col">Date</th>
            </tr>
        </thead>
        <tbody>
        ';
    $i = 0;
    while ($row = mysqli_fetch_assoc($r)) {
        $isDisabled = $row['isDisabled'];
        if ($isDisabled != "") {
            $hidden = 'disabled';
        } else {
            $hidden = "";
        }
        $i += 1;
        $value .= '
            <tr>
                <th scope="row">' . $i . '</th>
                <td>' . $row['userid'] . '</td>
                <td>' . $row['storename'] . '</td>
                <td>' . $row['reason'] . '</td>
                <td><a href="page-detail-product.php?id=' . $row['link'] . '" class="text-warning">View</a></td>
                <td>' . $row['date'] . '</td>
            </tr>
            ';
    }
    $value .= '
        </tbody>
    </table>
    ';
    echo $value;
}
function GenerateUser()
{
    global $con;
    $value = "";
    $q = "SELECT * from user order by userid desc";
    $r = mysqli_query($con, $q);
    $value .= '
    <table id="example_seller" class="table table-striped table-bordered dt-responsive nowrap table-dark" style="width:100%">
        <thead>
            <tr>
            <th scope="col">#</th>
            <th scope="col">FIRSTNAME</th>
            <th scope="col">LASTNAME</th>
            <th scope="col">CONTACT</th>
            <th scope="col">EMAIL
            </th>
            <th scope="col">ISBUYER
            </th>
            <th scope="col">ISSELLER
            </th>
            <th scope="col">ISGUEST
            </th>
            </tr>
        </thead>
        <tbody>
        ';
    while ($row = mysqli_fetch_assoc($r)) {
        $value .= '
            <tr>
                <th scope="row">1</th>
                <td>' . $row['firstname'] . '</td>
                <td>' . $row['lastname'] . '</td>
                <td>' . $row['contact'] . '</td>
                <td>' . $row['email'] . '</td>
                <td>' . $row['isBuyer'] . '</td>
                <td>' . $row['isSeller'] . '</td>
                <td>' . $row['isGuest'] . '</td>
            </tr>
            ';
    }
    $value .= '
        </tbody>
    </table>
<script>
    $(document).ready(function() {
        var table = $("#example_user").DataTable({
            lengthChange: true,
            buttons: ["copy", "excel", "csv", "pdf"]
        });

        table.buttons().container()
            .appendTo("#example_wrapper_user");
    });
</script>
    ';
    echo $value;
}

function GenerateSeller()
{
    global $con;
    $value = "";
    $q = "SELECT * from seller order by sellerid desc";
    $r = mysqli_query($con, $q);
    $value .= '
    <table id="example_user" class="table table-striped table-bordered dt-responsive nowrap table-dark" style="width:100%">
        <thead>
            <tr>
            <th scope="col">#</th>
            <th scope="col">STORENAME</th>
            <th scope="col">CONTACT</th>
            <th scope="col">EMAIL</th>
            <th scope="col">ISDISABLED</th>
            </tr>
        </thead>
        <tbody>
        ';
    while ($row = mysqli_fetch_assoc($r)) {
        $value .= '
            <tr>
                <th scope="row">1</th>
                <td>' . $row['storename'] . '</td>
                <td>' . $row['contact'] . '</td>
                <td>' . $row['email'] . '</td>
                <td>' . $row['isDisabled'] . '</td>
            </tr>
            ';
    }
    $value .= '
        </tbody>
    </table>
<script>
    $(document).ready(function() {
        var table = $("#example_seller").DataTable({
            lengthChange: true,
            buttons: ["copy", "excel", "csv", "pdf"]
        });

        table.buttons().container()
            .appendTo("#example_wrapper_seller");
    });
</script>
    ';
    echo $value;
}

function GenerateRider()
{
    global $con;
    $value = "";
    $q = "SELECT * from rider order by riderid desc";
    $r = mysqli_query($con, $q);
    $value .= '
    <table id="example_rider" class="table table-striped table-bordered dt-responsive nowrap table-dark" style="width:100%">
        <thead>
            <tr>
            <th scope="col">#</th>
            <th scope="col">RIDERNAME</th>
            <th scope="col">CONTACT</th>
            <th scope="col">EMAIL</th>
            <th scope="col">DRIVERLICENSE</th>
            <th scope="col">PLATENUMBER</th>
            <th scope="col">MOTORMODEL</th>
            <th scope="col">MOTORCOLOR</th>
            </tr>
        </thead>
        <tbody>
        ';
    while ($row = mysqli_fetch_assoc($r)) {
        $value .= '
            <tr>
                <th scope="row">1</th>
                <td>' . $row['ridername'] . '</td>
                <td>' . $row['contact'] . '</td>
                <td>' . $row['email'] . '</td>
                <td>' . $row['driverlicense'] . '</td>
                <td>' . $row['platenumber'] . '</td>
                <td>' . $row['motormodel'] . '</td>
                <td>' . $row['motorcolor'] . '</td>
            </tr>
            ';
    }
    $value .= '
        </tbody>
    </table>
<script>
    $(document).ready(function() {
        var table = $("#example_rider").DataTable({
            lengthChange: true,
            buttons: ["copy", "excel", "csv", "pdf"]
        });

        table.buttons().container()
            .appendTo("#example_wrapper_rider");
    });
</script>
    ';
    echo $value;
}
function AdsSection()
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
