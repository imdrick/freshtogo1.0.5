<?php
require_once('connection.php');
function Register($userdetail)
{
    global $con;
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $contact = $_POST['contact'];
    $email = $_POST['email'];
    $q30 = "SELECT * FROM user where email = '$userdetail' and isGuest = 'yes'";
    $r30 = mysqli_query($con, $q30);
    $rowcount20 = mysqli_num_rows($r30);
    if ($rowcount20 == 1) {
        $q4 = "SELECT * FROM user where contact = '$contact' or email = '$email'";
        $r4 = mysqli_query($con, $q4);
        $rc4 = mysqli_num_rows($r4);
        $q2 = "SELECT * FROM user where email = '$email'";
        $r2 = mysqli_query($con, $q2);
        $rc2 = mysqli_num_rows($r2);
        $q3 = "SELECT * FROM user where contact = '$contact'";
        $r3 = mysqli_query($con, $q3);
        $rc3 = mysqli_num_rows($r3);
        if ($rc4 != 1) {
            $q = "UPDATE user SET 
            firstname = '$firstname',
            lastname = '$lastname', contact = '$contact', email = '$email', storename = 'no', storekey = 'no', isEmailVerified = 'no', isBuyer = 'yes', 
            isSeller = 'no', isRider = 'no', isGuest = null, emailMatch = '', imgurl = 'default.jpg', imgurl2 = 'default.jpg'
            WHERE email = '$userdetail'";
            mysqli_query($con, $q);
            $_SESSION['success_register_guest'] = "yes";
            echo "success";
        } else {
            if ($rc2 >= 1 || $rc3 >= 1) {
                if ($rc2 >= 1) {
                    echo "<strong>*Email</strong> Already Exist! <br>";
                }
                if ($rc3 >= 1) {
                    echo "*<strong>Contact</strong> Already Exist!";
                }
            }
        }
    } else {
        $q4 = "SELECT * FROM user where contact = '$contact' or email = '$email'";
        $r4 = mysqli_query($con, $q4);
        $rc4 = mysqli_num_rows($r4);
        //email
        $q2 = "SELECT * FROM user where email = '$email'";
        $r2 = mysqli_query($con, $q2);
        $rc2 = mysqli_num_rows($r2);
        //contact
        $q3 = "SELECT * FROM user where contact = '$contact'";
        $r3 = mysqli_query($con, $q3);
        $rc3 = mysqli_num_rows($r3);
        if ($rc4 != 1) {
            $q = "INSERT INTO user (firstname, lastname, contact, email, storename, storekey, isEmailVerified, isBuyer, isSeller, isRider, emailMatch,imgurl,imgurl2) values 
            ('$firstname','$lastname','$contact','$email','no','no','no','yes','no','no','','default.jpg','default.jpg')";
            mysqli_query($con, $q);
            session_destroy();
            echo "success";
        } else {
            if ($rc2 >= 1 || $rc3 >= 1) {
                if ($rc2 >= 1) {
                    echo "<strong>*Email</strong> Already Exist! <br>";
                }
                if ($rc3 >= 1) {
                    echo "*<strong>Contact</strong> Already Exist!";
                }
            }
        }
    }
}
