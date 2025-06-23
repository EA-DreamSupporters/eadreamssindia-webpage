<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="keywords" content="Online book center, Government exam materials rental, book rental, TNPSC, SSC, UPSC, BANKING, career guidance, Youtube videos, magazine subscription, institute courses, test packs for government exam preparations" />
	<meta name="author" content="" />
	<meta name="robots" content="" />
	<meta name="description" content="EA Dream Supporters is an ED tech business that began to care about the career aspirations of Indian youngsters. Numerous youths struggled to locate the appropriate resources and opportunities for their dream. So EA creates Dream Supporting Services, a unique one-stop portal for competitive test preparation. We support youth by acting as a prodding force for their desired course."/>
	
	<!-- FAVICONS ICON -->
	<link rel="shortcut icon" type="image/x-icon" href="images/favicon.png" />
	
	<!-- PAGE TITLE HERE -->
	<title>Checkout - EA Dream Supporters</title>
	
	<!-- MOBILE SPECIFIC -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
	
	<!-- STYLESHEETS -->
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel="stylesheet" type="text/css" href="icons/fontawesome/css/all.min.css">
	<link rel="stylesheet" type="text/css" href="vendor/bootstrap-select/dist/css/bootstrap-select.min.css">
	<link rel="stylesheet" type="text/css" href="vendor/swiper/swiper-bundle.min.css">
	<link rel="stylesheet" type="text/css" href="vendor/nouislider/nouislider.min.css">
	
	
	<!-- GOOGLE FONTS-->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600;700;800&family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

</head>
<body>
<div class="page-wraper">
	<div id="loading-area" class="preloader-wrapper-2">
		<div class="preloader-inner">
			<span></span>
			<span></span>
			<span></span>
			<span></span>
			<span></span>
			<span></span>
			<span></span>
			<span></span>
		</div> 
	</div>
<?php 
session_start();
include("header1.php");
error_reporting(0);
if(strlen($_SESSION['id'])==0)
{   header('location:logout.php');
}else{

//For Proceeding Payment
$granttotal=$_GET['total'];
if(isset($_POST['online']))
{
	$uid=$_SESSION['id'];
	$orderno1= mt_rand(100000000,999999999);
	$name=$_POST['name']; 
	$phone=$_POST['phone'];
	$gtotal=$_POST['grandtotal'];
	$transaction=$_POST['transaction'];
	$mode=$_POST['mode'];
	$block=$_POST['block'];
$city=$_POST['city'];
$state=$_POST['state'];
$zip=$_POST['zip'];
$notes=$_POST['notes'];
	$ret=mysqli_query($con,"INSERT INTO online_order(uid,orderno,name,amount,phone,mode,transacton,block,city,state,zip,notes)values('$uid','$orderno1','$name','$granttotal','$phone','$mode','$transaction','$block','$city','$state','$zip','$notes')");
	
if($ret)
{

$sql="insert into ordersdetails (userId,productId,quantity) select userID,productId,productQty from cart where userID='$uid';";
$sql.="update ordersdetails set orderNumber='$orderno1' where userId='$uid' and orderNumber is null;";
$sql.="delete from  cart where userID='$uid'";
$result = mysqli_multi_query($con, $sql);
if ($sql) {   
echo '<script>alert("Your order successfully placed. Order number is "+"'.$orderno1.'")</script>';
    echo "<script type='text/javascript'> document.location ='orders.php'; </script>";
} } else{
echo "<script>alert('Something went wrong. Please try again');</script>";
    echo "<script type='text/javascript'> document.location ='checkout.php'; </script>";
}   
}

?>
<style>


.notes{
	    margin-top: 58px;
}
input#paymenttype {
    margin: 3px 10px 0px 0px;
}

#myDiv {
  padding: 10px;
}
</style>	
<?php

$userid=$_SESSION['id'];
$granttotal=$_GET['total'];
$result = mysqli_query($con,"SELECT * FROM students WHERE sid='$userid'");
$row = mysqli_fetch_array($result);
?>
		<div class="page-content">
			<!-- inner page banner -->
			<div class="dz-bnr-inr overlay-secondary-dark dz-bnr-inr-sm" style="background-image:url(images/background/bg3.jpg);">
				<div class="container">
					<div class="dz-bnr-inr-entry">
						<h1>Checkout Online</h1>
						<nav aria-label="breadcrumb" class="breadcrumb-row">
							<ul class="breadcrumb">
								<li class="breadcrumb-item"><a href="index.php"> Home</a></li>
								<li class="breadcrumb-item">Checkout</li>
							</ul>
						</nav>
					</div>
				</div>
			</div>
			<!-- inner page banner End-->
			
			<!-- contact area -->
			<section class="content-inner shop-account">
				<!-- Product -->
				<div class="container">
					<form class="shop-form" method="post">
					<input type="hidden" name="grandtotal" value="<?php echo $grantotal; ?>">
						<div class="row">
							<div class="col-lg-6 col-md-6">
								<div class="widget">
									<h4 class="widget-title">Billing & Shipping Address</h4>
									
									<div class="row">
										<div class="form-group col-md-6">
											<input type="text" value="<?php echo $row['name']; ?>" class="form-control" placeholder="First Name" required>
										</div>
										<div class="form-group col-md-6">
											<input type="text" class="form-control" placeholder="Last Name">
										</div>
									</div>
									<div class="form-group">
										<input type="text" class="form-control" placeholder="Company Name">
									</div>
									<div class="form-group">
										<input type="text" class="form-control" name="selectedaddress" value="<?php echo $row['address']; ?>" placeholder="Address" required >
									</div>
									<div class="row">
										<div class="form-group col-md-6">
											<input type="text" class="form-control" name="block" placeholder="Apartment, suite, unit etc.">
										</div>
										<div class="form-group col-md-6">
											<input type="text" class="form-control" name="city" placeholder="Town / City" required>
										</div>
									</div>
									<div class="row">
										<div class="form-group col-md-6">
											<input type="text" class="form-control" name="state" placeholder="State / County" required>
										</div>
										<div class="form-group col-md-6">
											<input type="text" class="form-control" name="zip" placeholder="Postcode / Zip" required>
										</div>
									</div>
									<div class="row">
										<div class="form-group col-md-6">
											<input type="email" class="form-control" value="<?php echo $row['email']; ?>" placeholder="Email" required>
										</div>
										<div class="form-group col-md-6">
											<input type="text" class="form-control" name="txnnumber" value="<?php echo $row['phone']; ?>" placeholder="Phone" required>
										</div>
									</div>
									
								</div>
							</div>
							<div class="col-lg-6 col-md-6">
								
								<div class="form-group notes">
									<textarea class="form-control" placeholder="Notes about your order, e.g. special notes for delivery" name="notes"></textarea>
								</div>
							
							</div>
						</div>
					
					
					<div class="dz-divider bg-gray-dark text-gray-dark icon-center  my-5"><i class="fa fa-circle bg-white text-gray-dark"></i></div>
					<div class="row">
						<div class="col-lg-6">
							<div class="widget">
								<h4 class="widget-title">Your Order</h4>
								<table class="table-bordered check-tbl">
									<thead class="text-center">

										<tr>
											<th>IMAGE</th>
											<th>BOOK NAME</th>
											<th>TOTAL</th>
										</tr>
										
									</thead>
									<tbody>
																			<?php
					$ret = mysqli_query($con,"SELECT a.*,b.* FROM books AS a, cart AS b WHERE a.book_id=b.productId AND b.userID='$userid'");
					while ($row1=mysqli_fetch_array($ret)) {
					?>
										<tr>
											<td class="product-item-img"><img src="images/<?php echo $row1['image']; ?>" alt=""></td>
											<td class="product-item-name"><?php echo $row1['book']; ?></td>
											<td class="product-price">₹ <?php echo htmlentities($totalamount=$row1['productQty']*$row1['price']);?></td>
										</tr>
										<?php $grantotal=$totalamount; 
										}
					?>
									</tbody>
								</table>
							</div>
						</div>
						<div class="col-lg-6">
						
								<h4 class="widget-title">Order Total</h4>
								<table class="table-bordered check-tbl mb-4">
									<tbody>
										<tr>
											<td>Order Subtotal</td>
											<td class="product-price">₹ <?php echo $granttotal;?></td>
										</tr>
										<tr>
											<td>Shipping</td>
											<td>Free Shipping</td>
										</tr>
										<tr>
											<td>Grand Total</td>
											<td class="product-price-total">₹ <?php echo $granttotal;?></td>
										</tr>
									</tbody>
								</table>
								<h4 class="widget-title">Payment Method</h4>
								<div id="myDiv">
							
							<img id="image1" src="image/qr.png">
							
							<form method="post">
  <div class="form-group">
    <label for="name">Name</label>
    <input type="text" class="form-control" id="name" name="name" placeholder="Enter name">
  </div>
  <div class="form-group">
    <label for="email">Phone Number</label>
    <input type="text" class="form-control" name="phone" id="email" aria-describedby="emailHelp" placeholder="Enter Phone no">
  </div>
  <div class="form-group">
    <label for="mode">UPI Mode</label>
    <select  class="form-control" name="mode" id="mode">
	<option selected>Select UPI Mode</option>
	<option value="Google Pay">Google Pay</option>
	<option value="Phone Pay">Phone Pay</option>
	<option value="Paytm">Paytm</option>
    </select>
  </div>
  <div class="form-group">
    <label for="email">Transaction ID</label>
    <input type="text" class="form-control" name="transaction"  placeholder="Enter TransactonID">
  </div>
  <button type="submit" name="online" class="btn btn-primary">Submit</button>
</form>

								</div>
						</div>
					</div>
				</div>
				<!-- Product END -->
			</section>
			<!-- contact area End--> 
		</div>
		<?php } ?>
	<!-- Footer -->
	<?php include'footer-white.php'; ?>
	<!-- Footer End -->
	
	<button class="scroltop" type="button"><i class="fas fa-arrow-up"></i></button>
</div>

<!-- JAVASCRIPT FILES ========================================= -->
<script src="js/jquery.min.js"></script><!-- JQUERY MIN JS -->
<script src="vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script><!-- BOOTSTRAP MIN JS -->
<script src="vendor/bootstrap-select/dist/js/bootstrap-select.min.js"></script><!-- BOOTSTRAP SELECT MIN JS -->
<script src="vendor/swiper/swiper-bundle.min.js"></script><!-- SWIPER JS -->
<script src="vendor/countdown/counter.js"></script><!-- COUNTER JS -->
<script src="vendor/counter/waypoints-min.js"></script><!-- WAYPOINTS JS -->
<script src="vendor/counter/counterup.min.js"></script><!-- COUNTERUP JS -->
<script src="vendor/wnumb/wNumb.js"></script><!-- WNUMB -->
<script src="vendor/nouislider/nouislider.min.js"></script><!-- NOUSLIDER MIN JS-->
<script src="js/dz.carousel.js"></script><!-- DZ CAROUSEL JS -->
<script src="js/dz.ajax.js"></script><!-- AJAX -->
<script src="js/custom.js"></script><!-- CUSTOM JS -->
<script src="vendor/wow/wow.min.js"></script><!-- WOW JS -->
<script>
function showDiv() {
  var div = document.getElementById('myDiv');
  div.style.display = 'block';
}

function hideDiv() {
  var div = document.getElementById('myDiv');
  div.style.display = 'none';
}</script><!-- WOW JS -->
</body>
</html>