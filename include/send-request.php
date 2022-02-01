<?php
require_once('connection.php');

function SubmitReport($userdetail)
{
    $today = $today = date("Y-m-d H:i:s");;
    global $con;
    $q = "SELECT * FROM user where contact = '$userdetail' or email = '$userdetail' ";
    $r = mysqli_query($con, $q);
    while ($row = mysqli_fetch_assoc($r)) {
        $userid = $row['userid'];
    }
    $request = $_POST['request'];
    $reason = $_POST['reason'];
    if ($request == "seller") {
        $sellerid = $_POST['sellerid'];
        $q2 = "INSERT INTO requestseller (sellerid,reason,date) values ('$sellerid','$reason','$today')";
        $r2 = mysqli_query($con, $q2);
    } else if ($request == "rider") {
        $riderid = $_POST['riderid'];
        $q2 = "INSERT INTO requestrider (riderid,reason,date) values ('$riderid','$reason','$today')";
        $r2 = mysqli_query($con, $q2);
    } else {
        $userid = $_POST['userid'];
        $q2 = "INSERT INTO requestuser (userid,reason,date) values ('$userid','$reason','$today')";
        $r2 = mysqli_query($con, $q2);
    }


    if ((int)$r2 == 1) {
        echo 'Success!';
    } else {
        echo 'something went wrong';
    }
}
