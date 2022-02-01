<?php
require_once('../include/connection.php');

function DisplaySellerInfo($userdetail)
{
    global $con;

    $q = "SELECT * FROM user WHERE email = '$userdetail' or contact = '$userdetail'";
    $r = mysqli_query($con, $q);
    while ($row = mysqli_fetch_assoc($r)) {
        $firstname = $row['firstname'];
        $firstname = $row['firstname'];
        $lastname = $row['lastname'];
        $contact = $row['contact'];
        $email = $row['email'];
        $isSeller = $row['isSeller'];
        $isBuyer = $row['isBuyer'];
        $isRider = $row['isRider'];
        $imgurl = $row['imgurl'];
        $imgurl2 = $row['imgurl2'];
        $userid = $row['userid'];
    }
    if ($isSeller == "no") {
        $isSeller_res = "hidden";
    } else {
        $isSeller_res = "";
    }
    if ($isBuyer == "no") {
        $isBuyer_res = "hidden";
    } else {
        $isBuyer_res = "";
    }
    if ($isRider == "no") {
        $isRider_res = "hidden";
    } else {
        $isRider_res = "";
    }

    $q2 = "SELECT * FROM seller WHERE userid = '$userid'";
    $r2 = mysqli_query($con, $q2);
    $rowcount2 = mysqli_num_rows($r2);
    if ($rowcount2 == 1) {
        $buttonSeller = '<button type="button" class="btn btn-primary" id="seller-btn">Seller</button>';
    } else {
        $buttonSeller = '<button type="button" class="btn btn-primary" id="notseller-btn">Apply as Seller</button>';
    }
    $q3 = "SELECT * FROM rider WHERE userid = '$userid'";
    $r3 = mysqli_query($con, $q3);
    $rowcount3 = mysqli_num_rows($r3);
    if ($rowcount3 == 1) {
        $buttonRider = '<button type="button" class="btn btn-danger" id="rider-btn">Rider</button>';
    } else {
        $buttonRider = '<button type="button" class="btn btn-danger" id="notrider-btn">Apply as Rider</button>';
    }
    echo
    '
    <div class="card">
    <span id="loading"></span>
    <div class="card-body">
    
        <span class="badge badge-pill badge-info mb-2" ' . $isBuyer_res . '><i class="fas fa-check-circle"></i> Costumer</span>
        <span class="badge badge-pill badge-primary mb-2" ' . $isSeller_res . '><i class="fas fa-check-circle"></i> Seller</span>
        <span class="badge badge-pill badge-danger mb-2" ' . $isRider_res . '><i class="fas fa-check-circle"></i> Rider</span>
     
        <form class="row">
        
            <div class="col-md-12">
            ' . $buttonSeller . '
            ' . $buttonRider . '
                <div id="buyer-card">
                 <h4 class="mt-2">BUYER SETTINGS </h4>
                 <hr />
                    <div class="col-md cover-photo" style="text-align: center;"id="color-cover">
                        <div class="container-profile" id="color-profile"> <img src="images/profile/' . $imgurl . '" class="img-md rounded-circle border profile-picture">
                            <i class="fa fa-camera input-cover" id="profile-cover"></i>
                            <input type="file" class="" name="" id="input-cover" hidden>
                            
                            <i class="fa fa-camera input-profile" id="profile-picture"></i>
                            <input type="file" class="" name="" id="input-profile" hidden>
                        </div>
                    </div> <!-- col.// -->
                    <div class="form-row mt-5">
                        <div class="col form-group">
                            <label>First Name</label>
                            <input type="text" class="form-control" value="' . $firstname . '" id="firstname">
                        </div> <!-- form-group end.// -->
                        <div class="col form-group">
                            <label>Last Name</label>
                            <input type="text" class="form-control" value="' . $lastname . '" id="lastname">
                        </div> <!-- form-group end.// -->
                    </div> <!-- form-row.// -->
                    <div class="form-row">
                        <!-- form-group end.// -->
                        <div class="form-group col-md-6">
                            <label>Contact</label>
                            <input type="text" class="form-control" value="' . $contact . '" id="contact" readonly>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Email <i class="fas fa-times-circle text-danger" hidden></i></label>
                            <input type="text" class="form-control" value="' . $email . '" id="email" readonly>
                        </div> <!-- form-group end.// -->
                    </div> <!-- form-row.// -->
                    <div class="form-row" hidden>
                        <div class="col-8 form-group">
                            <label>Set Default Address</label>
                            <select type="text" class="form-control">
                                <option value="">Address1</option>
                                <option value="">Address2</option>
                                <option value="">Address3</option>
                                <option value="">Address4</option>
                            </select>
                        </div> <!-- form-group end.// -->
                        <div class="col form-group ">
                            <button class="btn btn-primary btn-block" style="margin-top:30px">Edit</button>
                        </div>
                        <!-- form-group end.// -->
                    </div>
                </div>
                <br><br>
            <div id="seller-card" >
            <h4 class="mt-2">SELLER SETTINGS </h4>
            <hr />
                <div class="col-md cover-photo" style="text-align: center;"id="color-cover">
                    <div class="container-profile" id="color-profile"> <img src="images/profile/' . $imgurl . '" class="img-md rounded-circle border profile-picture">
                        <i class="fa fa-camera input-cover" id="profile-cover"></i>
                        <input type="file" class="" name="" id="input-cover" hidden>
                        
                        <i class="fa fa-camera input-profile" id="profile-picture"></i>
                        <input type="file" class="" name="" id="input-profile" hidden>
                    </div>
                </div> <!-- col.// -->
                <div class="form-row mt-5">
                    <div class="col form-group">
                        <label>First Name</label>
                        <input type="text" class="form-control" value="' . $firstname . '" id="firstname">
                    </div> <!-- form-group end.// -->
                    <div class="col form-group">
                        <label>Last Name</label>
                        <input type="text" class="form-control" value="' . $lastname . '" id="lastname">
                    </div> <!-- form-group end.// -->
                </div> <!-- form-row.// -->
                <div class="form-row">
                    <!-- form-group end.// -->
                    <div class="form-group col-md-6">
                        <label>Contact</label>
                        <input type="text" class="form-control" value="' . $contact . '" id="contact" readonly>
                    </div>
                    <div class="form-group col-md-6">
                        <label>Email <i class="fas fa-times-circle text-danger" hidden></i></label>
                        <input type="text" class="form-control" value="' . $email . '" id="email" readonly>
                    </div> <!-- form-group end.// -->
                </div> <!-- form-row.// -->
                <div class="form-row" hidden>
                    <div class="col-8 form-group">
                        <label>Set Default Address</label>
                        <select type="text" class="form-control">
                            <option value="">Address1</option>
                            <option value="">Address2</option>
                            <option value="">Address3</option>
                            <option value="">Address4</option>
                        </select>
                    </div> <!-- form-group end.// -->
                    <div class="col form-group ">
                        <button class="btn btn-primary btn-block" style="margin-top:30px">Edit</button>
                    </div>
                    <!-- form-group end.// -->
                </div>
            </div>
            <div id="rider-card" >
            <h4 class="mt-2">RIDER SETTINGS </h4>
                 <hr />
                <div class="col-md cover-photo" style="text-align: center;"id="color-cover">
                    <div class="container-profile" id="color-profile"> <img src="images/profile/' . $imgurl . '" class="img-md rounded-circle border profile-picture">
                        <i class="fa fa-camera input-cover" id="profile-cover"></i>
                        <input type="file" class="" name="" id="input-cover" hidden>
                        
                        <i class="fa fa-camera input-profile" id="profile-picture"></i>
                        <input type="file" class="" name="" id="input-profile" hidden>
                    </div>
                </div> <!-- col.// -->
                <div class="form-row mt-5">
                    <div class="col form-group">
                        <label>First Name</label>
                        <input type="text" class="form-control" value="' . $firstname . '" id="firstname">
                    </div> <!-- form-group end.// -->
                    <div class="col form-group">
                        <label>Last Name</label>
                        <input type="text" class="form-control" value="' . $lastname . '" id="lastname">
                    </div> <!-- form-group end.// -->
                </div> <!-- form-row.// -->
                <div class="form-row">
                    <!-- form-group end.// -->
                    <div class="form-group col-md-6">
                        <label>Contact</label>
                        <input type="text" class="form-control" value="' . $contact . '" id="contact" readonly>
                    </div>
                    <div class="form-group col-md-6">
                        <label>Email <i class="fas fa-times-circle text-danger" hidden></i></label>
                        <input type="text" class="form-control" value="' . $email . '" id="email" readonly>
                    </div> <!-- form-group end.// -->
                </div> <!-- form-row.// -->
                <div class="form-row" hidden>
                    <div class="col-8 form-group">
                        <label>Set Default Address</label>
                        <select type="text" class="form-control">
                            <option value="">Address1</option>
                            <option value="">Address2</option>
                            <option value="">Address3</option>
                            <option value="">Address4</option>
                        </select>
                    </div> <!-- form-group end.// -->
                    <div class="col form-group ">
                        <button class="btn btn-primary btn-block" style="margin-top:30px">Edit</button>
                    </div>
                    <!-- form-group end.// -->
                </div>
            </div>
            <hr>
            <button type="button" class="btn btn-primary fa-pull-right" id="save-button">Save</button>
            </div> <!-- col.// -->

        </form>
    </div>
    <style>
        .cover-photo {
            background-image: url("images/cover/' . $imgurl2 . '");
            background-repeat: no-repeat;
            background-size: cover;
        }

        .profile-picture {
            position: relative;
            top: 2.0rem;
            border: 5px solid white !important;
        }

        .container-profile:hover .profile-picture {
            opacity: 0.3;
        }

        .container-profile:hover .input-profile {
            opacity: 1;
        }

        .container-profile:hover .input-cover {
            opacity: 1;
        }

        .input-profile {
            transition: .5s ease;
            opacity: 0;
            position: absolute;
            top: 70%;
            left: 50%;
            transform: translate(-50%, -50%);
            -ms-transform: translate(-50%, -50%);
            text-align: center;
            cursor: pointer;


        }

        .input-cover {
            transition: .5s ease;
            opacity: 0;
            position: absolute;
            top: 10%;
            left: 2%;
            transform: translate(-50%, -50%);
            -ms-transform: translate(-50%, -50%);
            text-align: center;
            cursor: pointer;


        }

        .input-profile:hover {
            font-size: 20px;
            transition: .5s ease;
        }

        .input-profile:hover {
            color: #00B517;
        }

        .input-cover:hover {
            font-size: 20px;
            transition: .5s ease;
        }

        .input-cover:hover {
            color: #00B517;
        }
        #seller-card{
            display:none;
        }
        #rider-card{
            display:none;
        }
    </style>

    <!-- card-body.// -->
</div> <!-- card .// -->
    ';
}
function OnChangeProfile($userdetail)
{
    global $con;
    $q = "SELECT * FROM user WHERE contact = '$userdetail' or email = '$userdetail' ";
    $r = mysqli_query($con, $q);

    while ($row = mysqli_fetch_assoc($r)) {
        $userid = $row['userid'];
        $beforeimg = $row['imgurl'];
    }
    if ($beforeimg == "default.jpg") {
    } else {
        $file_pointer = "../images/profile/$beforeimg";
        unlink($file_pointer);
    }
    //delete file
    //update new file
    if ($_FILES["input-profile"]["name"] != '') {
        $test = explode('.', $_FILES["input-profile"]["name"]);
        $ext = end($test);
        $name = $_GET['name'] . '.' . $ext;
        $location = "../images/profile/" . $name;
        move_uploaded_file($_FILES["input-profile"]["tmp_name"], $location);

        $q2 = "UPDATE user SET imgurl = '$name' WHERE userid = '$userid'";
        mysqli_query($con, $q2);
    }

    echo 'success on change profile';
}
function OnChangeCover($userdetail)
{
    global $con;
    $q = "SELECT * FROM user WHERE contact = '$userdetail' or email = '$userdetail' ";
    $r = mysqli_query($con, $q);

    while ($row = mysqli_fetch_assoc($r)) {
        $userid = $row['userid'];
        $beforeimg = $row['imgurl2'];
    }
    if ($beforeimg == "default.jpg") {
    } else {
        $file_pointer = "../images/cover/$beforeimg";
        unlink($file_pointer);
    }
    //delete file
    //update new file
    if ($_FILES["input-cover"]["name"] != '') {
        $test = explode('.', $_FILES["input-cover"]["name"]);
        $ext = end($test);
        $name = $_GET['name'] . '.' . $ext;
        $location = "../images/cover/" . $name;
        move_uploaded_file($_FILES["input-cover"]["tmp_name"], $location);

        $q2 = "UPDATE user SET imgurl2 = '$name' WHERE userid = '$userid'";
        mysqli_query($con, $q2);
    }

    echo 'success on change cover';
}

function SaveProfileSettings($userdetail)
{
    global $con;
    $q = "SELECT * FROM user WHERE contact = '$userdetail' or email = '$userdetail' ";
    $r = mysqli_query($con, $q);

    while ($row = mysqli_fetch_assoc($r)) {
        $userid = $row['userid'];
    }

    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $contact = $_POST['contact'];
    $email = $_POST['email'];

    $q2 = "UPDATE user SET firstname = '$firstname', lastname = '$lastname', contact ='$contact', email = '$email' where userid = '$userid'";
    mysqli_query($con, $q2);
    echo "updated setting";
}

function SetUpSeller($userdetail)
{

    global $con;
    $q2 = "SELECT * FROM user WHERE contact = '$userdetail' or email = '$userdetail' ";
    $r2 = mysqli_query($con, $q2);

    while ($row2 = mysqli_fetch_assoc($r2)) {
        $userid = $row2['userid'];
    }

    $sellerstorename = $_POST['sellerstorename'];
    $sellercontact = $_POST['sellercontact'];
    $selleremail = $_POST['selleremail'];
    $selleraccountname = $_POST['selleraccountname'];
    $sellerbankname = $_POST['sellerbankname'];
    $selleraccountnumber = $_POST['selleraccountnumber'];
    $sellertin = $_POST['sellertin'];
    $sellergovid = $_POST['sellergovid'];

    $q = "INSERT INTO seller (userid,storename,contact,email,addressid,bank,accname,
    accnumber,govidurl,TIN) VALUES ('$userid','$sellerstorename','$sellercontact',
    '$selleremail','','$sellerbankname','$selleraccountname','$selleraccountnumber',
    '$sellertin','$sellergovid') ";
    $r = mysqli_query($con, $q);
    $q3 = "UPDATE user SET isSeller = 'yes' where userid = '$userid'";
    mysqli_query($con, $q3);
    //$str = "$sellerstorename $sellercontact $selleremail $selleraccountname $sellerbankname $selleraccountnumber $sellertin $sellergovid";
    echo "success set up seller";
}
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
 