
function DisplayReview($userdetail)
{
    global $con;
    $productid = $_POST['productid'];
    $q = "SELECT * FROM user where contact = '$userdetail' or email = '$userdetail' ";
    $r = mysqli_query($con, $q);
    while ($row = mysqli_fetch_assoc($r)) {
        $userid = $row['userid'];
    }
    $rating_count = 0;
    $rating_percent = 0;
    $rating_total = 0;
    $q3 = "SELECT * from review inner join user on user.userid = review.userid where productid = '$productid' order by reviewid desc";
    $r3 = mysqli_query($con, $q3);
    $rowcount3 = mysqli_num_rows($r3);
    while ($row3 = mysqli_fetch_assoc($r3)) {
        $rating_count_ = $row3['rating'];
        $rating_count += (float)$rating_count_;
    }
    if ($rowcount3 == 0) {
        $rating_total = 0;
    } else {
        $rating_total = ((float)$rating_count / (float)$rowcount3);
    }

    $rating_percent = ($rating_total / 5) * 100;
    $value = '';

    $value .= '<div class="box">
        <div class="row">
          <div class="col-md-12">
            <header class="section-heading">
              <button class="btn btn-outline-primary btn-block mb-4 add-review" data-toggle="modal" data-target="#exampleModal">Add Review</button>
              <div class="rating-wrap">
                <ul class="rating-stars stars-lg">
                  <li style="width:' . $rating_percent . '%" class="stars-active">
                    <img src="others/images/icons/stars-active.svg" alt="">
                  </li>
                  <li>
                    <img src="others/images/icons/starts-disable.svg" alt="">
                  </li>
                </ul>
                <strong class="label-rating text-lg"> ' . round($rating_total, 1) . '/5.0

                  <span class="text-muted">| ' . $rowcount3 . ' reviews</span>
                </strong>
              </div>
            </header>
            <div>
        ';
    $q1 = "SELECT * from review inner join user on user.userid = review.userid where productid = '$productid' order by reviewid desc";
    $r1 = mysqli_query($con, $q1);
    while ($row1 = mysqli_fetch_assoc($r1)) {

        $rating_ = $row1['rating'];
        $rating = (((float)$rating_ / 5) * 100);
        $comment = "";
        if ($rating_ < 3) {
            $comment = "Ok";
        } else if ($rating_total  == 5) {
            $comment = "Best!";
        } else {
            $comment = "Good!";
        }
        $value .= '
              <article class="box mb-3">
                <div class="icontext w-100">
                  <img src="images/profile/' . $row1['imgurl'] . '" class="img-xs icon rounded-circle">
                  <div class="text">
                    <span class="date text-muted float-md-right">' . date("M-d-Y h:i A", strtotime($row1['date'])) . '</span>
                    <h6 class="mb-1">' . $row1['firstname'] . '</h6>
                    <ul class="rating-stars">
                      <li style="width:' . $rating . '%" class="stars-active">
                        <img src="others/images/icons/stars-active.svg" alt="">
                      </li>
                      <li>                  
                        <img src="others/images/icons/starts-disable.svg" alt="">
                      </li>
                    </ul>
                    <span class="label-rating text-warning">' . $comment . '</span>
                  </div>
                </div> 
                <div class="mt-3">
                  <p>
                  ' . $row1['comment'] . '
                  </p>
                </div>
              </article>
              ';
    }
    $value .= '
            </div>
          </div>
        </div>

      </div>';

    echo $value;
}
function AddReview($userdetail)
{
    global $con;
    $q = "SELECT * FROM user where contact = '$userdetail' or email = '$userdetail' ";
    $r = mysqli_query($con, $q);
    while ($row = mysqli_fetch_assoc($r)) {
        $userid = $row['userid'];
    }

    $today = date("Y-m-d H:i:s");
    $productid = $_POST['productid'];
    $rating = $_POST['sim'];
    $comment = $_POST['comment'];
    $q2 = "SELECT * FROM review where productid = '$productid' and userid = '$userid'";
    $r2 = mysqli_query($con, $q2);
    $rowcount2 = mysqli_num_rows($r2);
    if ($rowcount2 == 1) {
        echo "You already added review of this Product.";
    } else {
        $q1 = "INSERT INTO review (userid,comment,date,productid,rating) values ('$userid','$comment','$today','$productid','$rating')";
        $r1 = mysqli_query($con, $q1);
        echo "Thank you for your ratings..";
    }
}
