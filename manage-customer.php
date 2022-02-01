<?php
session_start();
require_once('include/connection.php');
global $con;


if (isset($_SESSION["userdetail"]) == false) {
	$userdetail = "";
} else {
	$userdetail = $_SESSION["userdetail"];
}


$q1 = "SELECT * from user where contact = '$userdetail' or email = '$userdetail';";
$r1 = mysqli_query($con, $q1);
$rowcount1 = mysqli_num_rows($r1);

while ($row1 = mysqli_fetch_assoc($r1)) {
	$userid = $row1['userid'];
	$firstname = $row1['firstname'];
	$isSeller = $row1['isSeller'];
	$isRider = $row1['isRider'];
}
if ($rowcount1 == 1) {
	//ifguest
	$q22 = "SELECT * from user where userid = '$userid' and isGuest = 'yes';";
	$r22 = mysqli_query($con, $q22);
	$rowcount22 = mysqli_num_rows($r22);
	if ($rowcount22 == 1) {
		$isUserValid = '<a> ' . $firstname . '</a>, <a href="v2-login.php"> Sign in </a> or <a href="v2-register.php"> Register </a>';
		$isMyAccount = "";
		$mobile_myacc = "";
		$orderNav = "";
		$signout = '';
		$notifNav = '';
	} else {
		$isUserValid = '<a> ' . $firstname . '</a>';
		$isMyAccount = " My Account";
		$mobile_myacc = '<a href="page-profile-setting.php" class="btn btn-light"><i class="fa fa-user"></i> </a>';
		$orderNav = "<li><a href='page-profile-orders.php' class='nav-link'> My Orders </a></li>";
		$signout = '<a href="v2-login.php?logout=1" class="nav-link"> Logout</a>';
		$notifNav = '';
	}
} else {
	$isUserValid = '<a href="v2-login.php"> Sign in </a> or <a href="v2-register.php"> Register </a>';
	$signout = '';
	$isMyAccount = '';
	$orderNav = "";
	$mobile_myacc = "";
	$notifNav = '';
}
?>
<!DOCTYPE HTML>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="pragma" content="no-cache" />
	<meta http-equiv="cache-control" content="max-age=604800" />
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<style>
		.sidenav {
			height: 100%;
			width: 0;
			position: fixed;
			z-index: 1;
			top: 0;
			right: 0;
			background-color: rgba(0, 59, 8, 0.4);
			overflow-x: hidden;
			transition: 0.5s;
			padding-top: 60px;
		}

		.sidenav a {
			padding: 8px 8px 8px 32px;
			text-decoration: none;
			font-size: 25px;
			color: #818181;
			display: block;
			transition: 0.3s;
		}

		.sidenav a:hover {
			color: #f1f1f1;
		}

		.sidenav .closebtn {
			position: absolute;
			top: 0;
			right: 25px;
			font-size: 36px;
			margin-left: 50px;
		}

		@media screen and (max-height: 450px) {
			.sidenav {
				padding-top: 15px;
			}

			.sidenav a {
				font-size: 18px;
			}
		}
	</style>
	<title>Orders</title>

	<link href="images/favicon.ico" rel="shortcut icon" type="image/x-icon">

	<!-- jQuery -->
	<script src="js/jquery-2.0.0.min.js" type="text/javascript"></script>

	<!-- Bootstrap4 files-->
	<script src="js/bootstrap.bundle.min.js" type="text/javascript"></script>
	<link href="css/bootstrap.css?v=2.0" rel="stylesheet" type="text/css" />

	<!-- Font awesome 5 -->
	<link href="fonts/fontawesome/css/all.min.css?v=2.0" type="text/css" rel="stylesheet">

	<!-- custom style -->
	<link href="css/ui.css?v=2.0" rel="stylesheet" type="text/css" />
	<link href="css/responsive.css?v=2.0" rel="stylesheet" type="text/css" />

	<!-- custom javascript -->
	<script src="js/script.js?v10" type="text/javascript"></script>
	<script src="js/page-profile-orders.js?v10" type="text/javascript"></script>
	<script src="js/top.js?v10" type="text/javascript"></script>

</head>

<body>
	<header class="section-header">
		<nav class="navbar d-none d-md-flex p-md-0 navbar-expand-sm navbar-light border-bottom">
			<div class="container">
				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTop4" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>
				<div class="collapse navbar-collapse" id="navbarTop4">
					<ul class="navbar-nav mr-auto">
						<li><span class="nav-link">Hi,<?php echo $isUserValid ?></span>
						</li>
						<li><a href="page-profile-setting.php" class="nav-link"><?php echo $isMyAccount ?> </a></li>
						<li><?php echo $signout ?></li>
					</ul>
					<ul class="nav-link navbar-nav">
						<?php echo $orderNav ?>
						<li hidden><a href="#" class=""> <i class="fa fa-comment-alt"></i> <b style="color:#ED1E24">0</b></a></li>
						<li><a href="#" class="nav-link" onclick="openNav()" data-toggle="tooltip" data-placement="bottom" title="" id="tooltip-cart1"> <i class="fa fa-shopping-cart"></i> <b style="color:#ED1E24"><span id="minicart">0</span></b></a></li>
						<ul class="notification-drop">
							<li class="item" style="padding: 5px 0 0 10px;color:#797979">
								<i class="fa fa-bell notification-bell" aria-hidden="true" style="position:relative; right:15px"></i> <span class="btn__badge pulse-button" id="set-countnotif">-</span>
								<ul class="list-group" id="top-notification">
								</ul>
							</li>
						</ul>
					</ul>


					<!-- list-inline //  -->
				</div> <!-- navbar-collapse .// -->
			</div> <!-- container //  -->
		</nav>

		<div class="container">
			<section class="header-main border-bottom">
				<div class="row row-sm">
					<div class="col-6 col-sm col-md col-lg  flex-grow-0">
						<a href="v2-home.php" class="brand-wrap">
							<img class="logo" src="images/logo.png">
						</a> <!-- brand-wrap.// -->
					</div>
					<div class="col-6 col-sm col-md col-lg flex-md-grow-0">

						<!-- mobile-only -->
						<div class="d-md-none float-right" style="width:178px;">

							<?php echo $mobile_myacc ?>
							<a href="#" class="btn btn-light" onclick="openNav()" data-toggle="tooltip" data-placement="bottom" title="" id="tooltip-cart1"> <i class="fa fa-shopping-cart"></i> <b style="color:#ED1E24"><span id="minicart_mobile">4</span></b></a>
							<a type="button" class="btn btn-light dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="notif-btn-mobile">
								<i class="fa fa-bell"></i> <b style="color:#ED1E24"><span id="set-countnotif2">0</span></b>
							</a>
							<ul class="dropdown-menu dropdown-menu-right list-group2" id="top-notification-mobile" style=" padding:10px">


							</ul>

						</div>
						<!-- mobile-only //end  -->

						<div class="category-wrap d-none dropdown d-md-inline-block">
							<button hidden type="button" class="btn btn-light dropdown-toggle" data-toggle="dropdown">
								Shop by
							</button>
							<div class="dropdown-menu">
								<a class="dropdown-item" href="#">Machinery / Mechanical Parts / Tools </a>
								<a class="dropdown-item" href="#">Consumer Electronics / Home Appliances </a>
								<a class="dropdown-item" href="#">Auto / Transportation</a>
								<a class="dropdown-item" href="#">Apparel / Textiles / Timepieces </a>
								<a class="dropdown-item" href="#">Home & Garden / Construction / Lights </a>
								<a class="dropdown-item" href="#">Beauty & Personal Care / Health </a>
							</div>
						</div> <!-- category-wrap.// -->
					</div> <!-- col.// -->
					<div class="col-lg-6 col-xl col-md-5 col-sm-12 flex-grow-1">
						<form action="#" class="search-header" style="border: 3px solid #00B517">
							<div class="input-group w-100">
								<input type="text" oninput="onInput()" id="input" list="dlist" class="form-control" placeholder="Search">
								<div id="filldlist"></div>
								<div class="input-group-append">
								</div>
						</form>

						<div hidden class="col col-lg col-md flex-grow-0">
							<button class="btn btn-block btn-light" type="submit"> Advanced </button>
						</div>
					</div> <!-- row.// -->
			</section> <!-- header-main .// -->


			<nav class="navbar navbar-main navbar-expand pl-0">
				<ul class="navbar-nav flex-wrap">
					<li class="nav-item">
						<a class="nav-link" href="v2-home.php">Home</a>
					</li>
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" hidden> Category </a>
						<div class="dropdown-menu dropdown-large">
							<nav class="row">
								<div class="col-6">
									<a href="#">Fruits</a>
									<a href="#">Vegetables</a>
								</div>
								<div class="col-6">
									<a href="#">Eggs</a>
									<a href="#">Poultries</a>

								</div>
							</nav> <!--  row end .// -->
						</div> <!--  dropdown-menu dropdown-large end.// -->
					</li>
					<li class="nav-item">
						<a class="nav-link" href="shop.php">Shop</a>
					</li>
				</ul>
			</nav> <!-- navbar-main  .// -->

		</div> <!-- container.// -->
	</header> <!-- section-header.// -->
	<section class="section-pagetop bg-gray">
		<div class="container">
			<h2 class="title-page">My account</h2>
		</div> <!-- container //  -->
	</section>
	<div class="container">
		<section class="padding-bottom-sm">
			<div class="row row-sm">
				<div id="mySidenav" class="sidenav">
					<a class="text-white"> Your Cart</a>
					<a href="javascript:void(0)" class="closebtn text-white" onclick="closeNav()">&times;</a>
					<div id="side-cart">
					</div>
				</div>

		</section>

		<section class="section-content padding-y">
			<div class="container">

				<?php
				//if not seller hidden
				if ($isSeller != "yes") {
					$hid_seller = "hidden";
				} else {
					$hid_seller = "";
				}
				if ($isRider != "yes") {
					$hid_rider = "hidden";
				} else {
					$hid_rider = "";
				}
				?>
				<div class="row">
					<aside class="col-md-3">
						<nav class="list-group">
							<a class="list-group-item" href="page-profile-main.php" hidden> Account overview </a>
							<a class="list-group-item " href="page-profile-address.php"> My Address </a>
							<a class="list-group-item active" href="page-profile-orders.php"> My Orders <button class='btn btn-success btn-sm mr-2' style="  position: -webkit-sticky;
  position: sticky;
  top: 0;" value='click' onclick='printDiv()'>Print All </button> </a>
							<a <?php echo $hid_rider ?> class="list-group-item" href="page-rider-orders.php"> Rider Orders</a>
							<a <?php echo $hid_seller ?> class="list-group-item" href="page-seller-orders.php"> Seller Orders</a>
							<a class="list-group-item" href="page-profile-wishlist.php" hidden> My wishlist </a>
							<a <?php echo $hid_seller ?> class="list-group-item" href="page-profile-seller.php"> My Selling Items </a>
							<a <?php echo $hid_seller ?> class="list-group-item" href="page-profile-voucher.php"> Vouchers </a>
							<a class="list-group-item " href="page-profile-setting.php"> Settings </a>
							<a class="list-group-item" href="page-index-1.html"> Log out </a>
						</nav>
					</aside> <!-- col.// -->
					<main class="col-md-9" id="form-seller-info">
						<div id="display-orders"></div>
					</main> <!-- col.// -->
				</div>

			</div> <!-- container .//  -->
		</section>
		<script>
			function printDiv() {
				var divContents = document.getElementById("display-orders").innerHTML;
				var a = window.open("", "", "height=500, width=500");
				a.document.write("<html>");
				a.document.write(divContents);
				a.document.write("</body></html>");
				a.document.close();
				a.print();
			}
		</script>
		<?php
		$q200 = "SELECT * FROM user where userid='$userid'";
		$r200 = mysqli_query($con, $q200);
		while ($row200 = mysqli_fetch_assoc($r200)) {
			$isDisabled = $row200['isDisabled'];
		}
		echo $isDisabled;
		if ($isDisabled == "yes") {
		?>
			<div class="modal fade" id="auto-show" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="exampleModalLabel">Sorry.</h5>
						</div>
						<div class="modal-body">
							We noticed that there was too much reports on your user account, so the f2g team decided to disabled it.
							<br>
							Try to Request Review <a class="text-primary" href="send-request.php?request=user&userid=<?php echo $userid ?>" target="_blank">here</a>.
						</div>
						<div class="modal-footer">
							<a href="v2-login.php?logout=1" type="button" class="btn btn-secondary">Logout</a>
							<a href="send-request.php?request=user&userid=<?php echo $userid ?>" target="_blank" type="button" class="btn btn-primary">Send Request</a>
						</div>
					</div>
				</div>
			</div>
		<?php
		}
		?>
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.22/pdfmake.min.js"></script>
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>
		<script>
			$(document).ready(function() {
				$("#auto-show").modal("show");
			});
		</script>

	</div>
	<!-- container end.// -->

	<!-- ========================= FOOTER ========================= -->
	<script>
		function openNav() {
			document.getElementById("mySidenav").style.width = "270px";

		}

		function closeNav() {
			document.getElementById("mySidenav").style.width = "0";

		}

		function onInput() {
			var val = document.getElementById("input").value;
			var opts = document.getElementById("dlist").childNodes;
			for (var i = 0; i < opts.length; i++) {
				if (opts[i].value === val) {
					// An item was selected from the list!
					// yourCallbackHere()
					location.href = "page-detail-product.php?id=" + opts[i].value;
					break;
				}
			}
		}
	</script>

</body>
<style>
	a.fill-div {
		position: abso;

		height: 100%;
		width: 100%
	}

	ul {
		list-style: none;
		margin: 0;
		padding: 0;
	}

	.notification-drop {
		font-family: ' Ubuntu', sans-serif;
		color: #444;
	}

	.notification-drop .item {
		padding: 10px;
		font-size: 18px;
		position: relative;
		border-bottom: 1px solid #ddd;
	}

	.notification-drop .item:hover {
		cursor: pointer;
	}

	.notification-drop .item i {
		margin-left: 10px;
	}

	.notification-drop .item ul {
		display: none;
		position: absolute;
		top: 100%;
		background: #fff;
		left: -200px;
		right: 0;
		z-index: 1;
		border-top: 1px solid #ddd;
	}

	.notification-drop .item ul li {
		font-size: 16px;
		padding: 15px 0 15px 25px;
	}

	.notification-drop .item ul li:hover {
		background: #ddd;
		color: rgba(0, 0, 0, 0.8);
	}

	@media screen and (min-width: 500px) {
		.notification-drop {
			display: flex;
			justify-content: flex-end;
		}

		.notification-drop .item {
			border: none;
		}
	}

	.btn__badge {
		background: #FF5D5D;
		color: white;
		font-size: 12px;
		position: absolute;
		top: 0;
		right: 0px;
		padding: 1px 7px;
		border-radius: 50%;
	}

	.pulse-button {
		box-shadow: 0 0 0 0 rgba(255, 0, 0, 0.5);
		animation: pulse 1.5s infinite;
	}

	.pulse-button:hover {
		animation: none;
	}

	@keyframes pulse {
		0% {
			-moz-transform: scale(0.9);
			-ms-transform: scale(0.9);
			-webkit-transform: scale(0.9);
			transform: scale(0.9);
		}

		70% {
			-moz-transform: scale(1);
			-ms-transform: scale(1);
			-webkit-transform: scale(1);
			transform: scale(1);
			box-shadow: 0 0 0 50px rgba(255, 0, 0, 0);
		}

		100% {
			-moz-transform: scale(0.9);
			-ms-transform: scale(0.9);
			-webkit-transform: scale(0.9);
			transform: scale(0.9);
			box-shadow: 0 0 0 0 rgba(255, 0, 0, 0);
		}
	}

	.notification-text {
		font-size: 14px;
		font-weight: bold;
	}

	.notification-text span {
		float: right;
	}

	.list-group {
		max-height: 600px;
		margin-bottom: 10px;
		overflow: scroll;
		-webkit-overflow-scrolling: touch;
	}

	.list-group2 {
		max-height: 300px;
		margin-bottom: 10px;
		overflow: scroll;
		-webkit-overflow-scrolling: touch;
	}
</style>

</html>
