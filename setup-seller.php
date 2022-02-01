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
