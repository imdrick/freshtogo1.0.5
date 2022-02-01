<?php
require_once('../include/connection.php');

function AddModalAddress($userdetail)
{
    global $con;
    $q = "SELECT * FROM user where contact = '$userdetail' or email = '$userdetail' ";
    $r = mysqli_query($con, $q);
    while ($row = mysqli_fetch_assoc($r)) {
        $userid = $row['userid'];
    }
    $q3 = "SELECT * FROM address where isDefault = 1 and userid = '$userid'";
    $r3 = mysqli_query($con, $q3);
    $rowcount3 = mysqli_num_rows($r3);



    $fullname = $_POST['fullname'];
    $contact = $_POST['contact'];
    $address1 = $_POST['address1'];
    $postalcode = $_POST['postalcode'];
    $address2 = $_POST['address2'];
    $isDefault = $_POST['isDefault'];
    $home = $_POST['home'];
    $work = $_POST['work'];

    if ($home == 0 && $work == 0) {
        echo "Please Select 1 Label";
    } else {
        if ($rowcount3 == 1) {
            //1 default
            if ($isDefault == 1) { //isdefault = checked
                $q4 = "UPDATE address set isDefault = 0 where userid = '$userid' and isDefault = 1";
                mysqli_query($con, $q4);
                $q2 = "INSERT INTO address (userid,fullname,contact,address1,postalcode,address2,isHome,isWork,isDefault) values ('$userid','$fullname','$contact','$address1','$postalcode','$address2','$home','$work','$isDefault')";
                mysqli_query($con, $q2);
            } else {
                $q2 = "INSERT INTO address (userid,fullname,contact,address1,postalcode,address2,isHome,isWork,isDefault) values ('$userid','$fullname','$contact','$address1','$postalcode','$address2','$home','$work','$isDefault')";
                mysqli_query($con, $q2);
            }
            echo "Added Successfully! 1 default";
        } else {
            $q2 = "INSERT INTO address (userid,fullname,contact,address1,postalcode,address2,isHome,isWork,isDefault) values ('$userid','$fullname','$contact','$address1','$postalcode','$address2','$home','$work','$isDefault')";
            mysqli_query($con, $q2);
        }
    }
}
function DisplayAddress($userdetail)
{
    global $con;
    $q = "SELECT * FROM user where contact = '$userdetail' or email = '$userdetail' ";
    $r = mysqli_query($con, $q);
    while ($row = mysqli_fetch_assoc($r)) {
        $userid = $row['userid'];
    }
    $label = "";
    $q2 = "SELECT * FROM address where userid = '$userid' order by addressid DESC";
    $r2 = mysqli_query($con, $q2);
    while ($row2 = mysqli_fetch_assoc($r2)) {
        if ($row2['isWork'] == 1) {
            $label = "Work";
        } else {
            $label = "Home";
        }
        if ($row2['isDefault'] == 1) {
            $isDefault = '<a href="#" class="btn btn-light disabled"> <i class="fa fa-check"></i> Default</a> <button class="btn btn-light" id="edit-address" value="' . $row2['addressid'] . '"> <i class="fa fa-pen"></i> </button> <button href="#" class="btn btn-light" id="delete-modal-address" value="' . $row2['addressid'] . '"> <i class="text-danger fa fa-trash" ></i> </button>';
        } else { //not Default
            $isDefault = '<button href="#" value="' . $row2['addressid'] . '" class="btn btn-light" id="make-default">Make default</button> <button class="btn btn-light" id="edit-address" value="' . $row2['addressid'] . '"> <i class="fa fa-pen"></i> </button> <button href="#" class="btn btn-light" id="delete-modal-address" value="' . $row2['addressid'] . '"> <i class="text-danger fa fa-trash" ></i> </button>';
        }

        echo '
        <div class="col-md-6 ' . $row2['addressid'] . '">
            <article class="box mb-4">
                <h6>' . $row2['fullname'] . ', ' . $row2['contact'] . '</h6>
                <p>  Address: ' . $row2['address1'] . ',' . $row2['address2'] . ' <br>Postal Code: ' . $row2['postalcode'] . ' <br> Label: ' . $label . ' </p>
            ' . $isDefault . '
            </article>
        </div>
        <div class="modal fade" id="delete-prompt-address" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel">Do you want to delete this address?</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-primary" data-dismiss="modal">No</button>
						<button class="btn btn-danger" id="delete-address" value="' . $row2['addressid'] . '"> Yes </button>
					</div>
				</div>
			</div>
		</div>
		</div>';
    }
}

function MakeDefault($userdetail)
{
    $addressid = $_POST['addressid'];

    global $con;

    $q = "SELECT * FROM user where contact = '$userdetail' or email = '$userdetail' ";
    $r = mysqli_query($con, $q);
    while ($row = mysqli_fetch_assoc($r)) {
        $userid = $row['userid'];
    }

    $q2 = "UPDATE address SET isDefault = 0 where userid = '$userid'";
    mysqli_query($con, $q2);
    $q3 = "UPDATE address SET isDefault = 1 where addressid = '$addressid'";
    mysqli_query($con, $q3);
    echo "Default!";
}
function DeleteAddress($userdetail)
{
    $addressid = $_POST['addressid'];
    global $con;
    $q = "SELECT * FROM user where contact = '$userdetail' or email = '$userdetail' ";
    $r = mysqli_query($con, $q);
    while ($row = mysqli_fetch_assoc($r)) {
        $userid = $row['userid'];
    }
    $q3 = "DELETE FROM address where addressid = '$addressid'";
    mysqli_query($con, $q3);
    echo "deleted";
    $q2 = "SELECT * FROM address where userid = '$userid' and isDefault = 1 ";
    $r2 = mysqli_query($con, $q2);
    while ($row2 = mysqli_fetch_assoc($r2)) {
        $addressid = $row2['addressid'];
    }
    $rowcount2 = mysqli_num_rows($r2);
    if ($rowcount2 == 0) {
        $q4 = "UPDATE address SET isDefault=1 where userid = '$userid' ORDER BY addressid DESC LIMIT 1";
        mysqli_query($con, $q4);
    }
    echo "$rowcount2";
}
function EditAddress($userdetail)
{
    global $con;
    $addressid = $_POST['addressid'];
    $q = "SELECT * FROM user where contact = '$userdetail' or email = '$userdetail' ";
    $r = mysqli_query($con, $q);
    while ($row = mysqli_fetch_assoc($r)) {
        $userid = $row['userid'];
    }   
    $q2 = "SELECT * FROM address where addressid = '$addressid'";
    $r2 = mysqli_query($con, $q2);

    while ($row2 = mysqli_fetch_assoc($r2)) {
        $fullname = $row2['fullname'];
        $contact = $row2['contact'];
        $address1 = $row2['address1'];
        $postalcode = $row2['postalcode'];
        $address2 = $row2['address2'];
        $isDefault = $row2['isDefault'];
        $isHome = $row2['isHome'];
        $isWork = $row2['isWork'];
    }
    if ($isHome == 1) {
        $selected1 = "selected";
    } else {
        $selected1 = "";
    }
    if ($isWork == 1) {
        $selected2 = "selected";
    } else {
        $selected2 = "";
    }
    echo '<article class="box mb-4">
    <div class="row">
    <div class="col-6">
        <div class="form-group">
            <label for="recipient-name" class="col-form-label">Full Name:</label>
            <input type="text" class="form-control" value="' . $fullname . '" id="fullname' . $addressid . '">
        </div>
    </div>
    <div class="col-6">
        <div class="form-group">
            <label for="recipient-name" class="col-form-label">Contact:</label>
            <input type="text" class="form-control" value="' . $contact . '" id="contact' . $addressid . '">
        </div>
    </div>
    <div class="col-12">
        <div class="form-group">
            <label for="recipient-name" class="col-form-label">Region/Province/City/Barangay:</label>
            <input type="text" class="form-control" value="' . $address1 . '" id="address1' . $addressid . '">
        </div>
    </div>
    <div class="col-12">
        <div class="form-group">
            <label for="recipient-name" class="col-form-label">street Name/Building/House No/:</label>
            <input type="text" class="form-control" value="' . $address2 . '" id="address2' . $addressid . '">
        </div>
    </div>
    <div class="col-6">
        <div class="form-group">
            <label for="recipient-name" class="col-form-label">Postal Code:</label>
            <input type="text" class="form-control" value="' . $postalcode . '" id="postalcode' . $addressid . '">
        </div>
    </div>
    
    <div class="col-6">
        <div class="form-group">
            <label for="recipient-name"  class="col-form-label">Label:</label>
            <select type="text" class="form-control"" id="label' . $addressid . '">
                <option value ="0" ' . $selected2 . '> Work </option>
                <option value ="1" ' . $selected1 . '> Home </option>
            </select>
        </div>
    </div>
 

</div>
<div class="modal-footer mt-2">
<button type="button" class="btn btn-primary" value="' . $addressid . '" id="save-address">Save</button>
</div>
</article>';
}
function DisplaySingleAddress($userdetail)
{
    $addressid = $_POST['addressid'];
    global $con;
    $q = "SELECT * FROM user where contact = '$userdetail' or email = '$userdetail' ";
    $r = mysqli_query($con, $q);
    while ($row = mysqli_fetch_assoc($r)) {
        $userid = $row['userid'];
    }
    $label = "";
    $q2 = "SELECT * FROM address where userid = '$userid' and addressid = '$addressid' order by addressid DESC";
    $r2 = mysqli_query($con, $q2);
    while ($row2 = mysqli_fetch_assoc($r2)) {
        if ($row2['isWork'] == 1) {
            $label = "Work";
        } else {
            $label = "Home";
        }
        if ($row2['isDefault'] == 1) {
            $isDefault = '<a href="#" class="btn btn-light disabled"> <i class="fa fa-check"></i> Default</a> <button class="btn btn-light" id="edit-address" value="' . $row2['addressid'] . '"> <i class="fa fa-pen"></i> </button> <button href="#" class="btn btn-light" id="delete-modal-address" value="' . $row2['addressid'] . '"> <i class="text-danger fa fa-trash" ></i> </button>';
        } else { //not Default
            $isDefault = '<button href="#" value="' . $row2['addressid'] . '" class="btn btn-light" id="make-default">Make default</button> <button class="btn btn-light" id="edit-address" value="' . $row2['addressid'] . '"> <i class="fa fa-pen"></i> </button> <button href="#" class="btn btn-light" id="delete-modal-address" value="' . $row2['addressid'] . '"> <i class="text-danger fa fa-trash" ></i> </button>';
        }

        echo '
            <article class="box mb-4">
                <h6>' . $row2['fullname'] . ', ' . $row2['contact'] . '</h6>
                <p>  Address: ' . $row2['address1'] . ',' . $row2['address2'] . ' <br>Postal Code: ' . $row2['postalcode'] . ' <br> Label: ' . $label . ' </p>
            ' . $isDefault . '
            </article>';
    }
}
function SaveAddress($userdetail)
{
    global $con;
    $addressid = $_POST['addressid'];
    $fullname = $_POST['fullname'];
    $contact = $_POST['contact'];
    $address1 = $_POST['address1'];
    $postalcode = $_POST['postalcode'];
    $address2 = $_POST['address2'];
    $label = $_POST['label'];


    $q = "SELECT * FROM user where contact = '$userdetail' or email = '$userdetail' ";
    $r = mysqli_query($con, $q);
    while ($row = mysqli_fetch_assoc($r)) {
        $userid = $row['userid'];
    }
    $work = $home = 0;
    if ($label == 0) {
        $work = 1;
    }
    if ($label == 1) {
        $home = 1;
    }
    $q2 = "UPDATE address SET 
    fullname = '$fullname', contact='$contact' , address1='$address1' ,
    postalcode = '$postalcode' , address2='$address2', isHome = '$home', isWork = '$work'
    WHERE addressid = '$addressid'";
    $r2 = mysqli_query($con, $q2);

    echo "$r2";
}
