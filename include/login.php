<?php
require_once('connection.php');

function LogIn()
{
    session_start();
    global $con;
    $otp = $_POST['otp'];
    $userdetail = $_POST['userdetail'];

    $q = "SELECT * from user where contact = '$userdetail' or email = '$userdetail' ";
    $r = mysqli_query($con, $q);

    $rownum = mysqli_num_rows($r);
    if ($rownum == 1) {
        echo "$userdetail - $otp - $rownum";
        $_SESSION["otp"] = $otp;
        $_SESSION["userdetail"] = $userdetail;
    } else {
        echo "failed";
    }
}

function LogIn_Verify()
{
    session_start();
    $str = $_POST['str'];
    $otp = $_SESSION["otp"];
    if ($str == $otp) {
        echo 'success';
        unset($_SESSION["otp"]);
        $_SESSION["otp"] = 00001;
    } else {
        echo 'failed';
    }
}

function Login_Error()
{
    echo '<div class="alert alert-danger" id="success-alert">
    <button type="button" class="close" data-dismiss="alert">x</button>
    <strong>Invalid </strong> Credentials.
</div>';
}
