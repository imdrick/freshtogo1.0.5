<?php
require_once('connection.php');

function LogIn()
{
    global $con;

    $q = "SELECT * FROM user where userid = 54";
    $r = mysqli_query($con, $q);

    while ($row = mysqli_fetch_assoc($r)) {
        $user = $row['firstname'];
    }
    echo $user;
}
function CheckPhone()
{
    global $con;
    $userdetail = $_POST['userdetail'];
    $q = "SELECT * FROM user where contact = $userdetail";
    $r = mysqli_query($con, $q);
    $rowcount = mysqli_num_rows($r);

    if ($rowcount == 1) {
        echo "success";
    } else {
        echo "failed";
    }
}

function PhoneSessionOtp()
{
    session_start();
    $otp = $_POST['otp'];

    $_SESSION['otp'] = $otp;

    echo $otp;
}
function PhoneVerification()
{
    session_start();
    global $con;
    $otp = $_SESSION['otp'];
    $first = $_POST['first'];
    $second = $_POST['second'];
    $third = $_POST['third'];
    $fourth = $_POST['fourth'];
    $userdetail = $_POST['userdetail'];
    $str = "$first$second$third$fourth";
    if ($otp == $str) {
        //is Guest matched

        if (isset($_SESSION["userdetail"]) == false) {
            $userdetail_guest = "";
        } else {
            $userdetail_guest = $_SESSION["userdetail"];
        }
        //is Guest
        $q30 = "SELECT * FROM user where email = '$userdetail_guest' and isGuest = 'yes'";
        $r30 = mysqli_query($con, $q30);
        $rowcount20 = mysqli_num_rows($r30);
        if ($rowcount20 == 1) {
            while ($row30 = mysqli_fetch_assoc($r30)) {
                $userid_guest = $row30['userid'];
            }
            $q32 = "SELECT * FROM user where contact = '$userdetail' or email ='$userdetail'";
            $r32 = mysqli_query($con, $q32);
            while ($row32 = mysqli_fetch_assoc($r32)) {
                $userid_ = $row32['userid'];
            }

            $q31 = "UPDATE userorder set userid = '$userid_' where userid = '$userid_guest' and isDone = 'no'";
            $r31 = mysqli_query($con, $q31);
            $_SESSION["userdetail"] = $userdetail;
            echo "matched guest";
        } else {
            $_SESSION["userdetail"] = $userdetail;
            if (isset($_SESSION['success_register_guest']) == "yes") {
                echo "matched guest";
            } else {
                echo "matched";
            }
        }
        /////
    } else {
        echo "unmatched";
    }
}
function OtpExpired()
{
    session_start();
    $_SESSION['otp'] = "----";
    echo "expired";
}

function CheckEmail()
{
    global $con;
    $userdetail = $_POST['userDetail'];
    $q = "SELECT * FROM user where email = '$userdetail'";
    $r = mysqli_query($con, $q);
    $rowcount = mysqli_num_rows($r);
    if ($rowcount == 1) {
        echo 'success';
    } else {
        //no email match
        echo 'failed';
    }
}
function SessionEmailToken()
{
    session_start();
    $token = $_POST['token'];
    $_SESSION['token'] = $token;
    echo "Sessioned Token";
}
function EmailIfSuccess()
{
    session_start();
    $token = $_SESSION["token"];
    $token2 = $_SESSION["token2"];

    if ($token == $token2) {
        echo "success";
        $userdetail = $_POST['userDetail2'];
        $_SESSION['userdetail'] = $userdetail;
    } else {
        echo "failed";
    }
}
function SendEmailToken2()
{
    $token = $_POST['token'];
    $email = "fbhendrickouano@outlook.com";
    $userdetail = $_POST['userDetail'];
    $name = "hendrick";
    $body = "This is the verification Link >>> http://localhost/freshTogo-1.0.2/v2-check-email-otp.php?token=$token";
    $subject = "---Fresh2GoV2 Verification---";
    $headers = array(
        'Authorization: Bearer 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',
        /*****ENTER_YOUR_API_KEY*****/
        'Content-Type: application/json'
    );
    $data = array(

        "personalizations" => array(
            array(
                "to" => array(
                    array(
                        "email" => $userdetail,
                        "name"  => $name
                    )
                )
            )
        ),
        "from" => array(
            "email" => $email
        ),
        "subject" => $subject,
        "content" => array(
            array(
                "type" => "text/html",
                "value" => $body
            )
        )
    );


    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, "https://api.sendgrid.com/v3/mail/send");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    $response = curl_exec($ch);

    curl_close($ch);

    echo $response;
}
