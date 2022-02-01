function SetUpRider($userdetail)
{
    global $con;
    $q2 = "SELECT * FROM user WHERE contact = '$userdetail' or email = '$userdetail' ";
    $r2 = mysqli_query($con, $q2);

    $ridername = $_POST['ridername'];
    $ridercontact = $_POST['ridercontact'];
    $rideremail = $_POST['rideremail'];
    $riderlicense = $_POST['riderlicense'];
    $riderplatenumber = $_POST['riderplatenumber'];
    $ridermodel = $_POST['ridermodel'];
    $ridercolor = $_POST['ridercolor'];

    while ($row2 = mysqli_fetch_assoc($r2)) {
        $userid = $row2['userid'];
    }

    $q = "INSERT INTO rider (userid,ridername,contact,email,driverlicense,platenumber,motormodel,motorcolor) VALUES ('$userid','$ridername','$ridercontact','$rideremail','$riderlicense','$riderplatenumber','$ridermodel','$ridercolor')";
    $r = mysqli_query($con, $q);
    $q3 = "UPDATE user SET isRider = 'yes' where userid = '$userid'";
    mysqli_query($con, $q3);
    echo $r;
}
