<?php
require_once('connection.php');

function ReportSeller($userdetail)
{
    global $con;
    $q = "SELECT * FROM user where contact = '$userdetail' or email = '$userdetail' ";
    $r = mysqli_query($con, $q);
    while ($row = mysqli_fetch_assoc($r)) {
        $userid = $row['userid'];
    }
    echo $userid;
}
function SubmitReport($userdetail)
{
    $today = $today = date("Y-m-d H:i:s");
    global $con;

    $q = "SELECT * FROM user where contact = '$userdetail' or email = '$userdetail' ";
    $r = mysqli_query($con, $q);
    $userid = "";
    while ($row = mysqli_fetch_assoc($r)) {
        $userid = $row['userid'];
    }

    $reason = $_POST['reason'];
    $report = $_POST['report'];
    $fromreport = $_POST['fromreport'];
    $toreport = $_POST['toreport'];
    if ($report == "buyer") {
        //buyer
        $q5 = "SELECT * from seller where sellerid = '$fromreport'";
        $r5 = mysqli_query($con, $q5);
        while ($row5 = mysqli_fetch_assoc($r5)) {
            $storename = $row5['storename'];
        }
        $q2 = "INSERT INTO reportuser (user,userid,reason,date) values ('STORE: $storename','$toreport','$reason','$today')";
        $r2 = mysqli_query($con, $q2);
    } else if ($report == "rider") {
        //rider
        $q5 = "SELECT * from seller where sellerid = '$fromreport'";
        $r5 = mysqli_query($con, $q5);
        while ($row5 = mysqli_fetch_assoc($r5)) {
            $storename = $row5['storename'];
        }
        $q2 = "INSERT INTO reportrider (user,riderid,reason,date) values ('STORE: $storename','$toreport','$reason','$today')";
        $r2 = mysqli_query($con, $q2);
    } else {
        $sellerid = $_POST['sellerid'];
        $productid = $_POST['productid'];
        $riderid = $_POST['riderid'];
        if ((int)$riderid != 0) {
            //rider
            $q5 = "SELECT * from rider inner join user on user.userid = rider.userid where riderid = '$riderid'";
            $r5 = mysqli_query($con, $q5);
            while ($row5 = mysqli_fetch_assoc($r5)) {
                $firstname = $row5['firstname'];
                $lastname = $row5['lastname'];
            }
            $q2 = "INSERT INTO reportseller (user,sellerid,reason,date,link) values ('RIDER: $firstname $lastname','$sellerid','$reason','$today','0')";
            $r2 = mysqli_query($con, $q2);
        } else {
            $q5 = "SELECT * from user where userid = '$userid'";
            $r5 = mysqli_query($con, $q5);
            while ($row5 = mysqli_fetch_assoc($r5)) {
                $firstname = $row5['firstname'];
                $lastname = $row5['lastname'];
            }
            //costumer
            $q2 = "INSERT INTO reportseller (user,sellerid,reason,date,link) values ('CUSTOMER: $firstname $lastname','$sellerid','$reason','$today','$productid')";
            $r2 = mysqli_query($con, $q2);
        }
    }
    ///////////////reportseller///////////////



    if ((int)$r2 == 1) {
        echo 'Success!';
    } else {
        echo 'something went wrong';
    }
}
