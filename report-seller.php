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
