<?php
session_start();
error_reporting(0);
include ("config.php");

if(isset($_POST['pass']))
{
$uid=$_GET['user'];
$npassw=md5($_POST["npassw"]);
$cpassw=md5($_POST["cpassw"]);
if($npassw==$cpassw)
{
	$sql=mysqli_query($con, "UPDATE students SET password='$cpassw' WHERE sid='$uid'");
	if($sql)
	{
		echo "<script> alert('Password Updated Successfully')</script>";
		echo "<script>  window.location.assign('index')</script>";
	}
	else
	{
		echo "<script> alert('New Password and Confirm Password does not match')</script>";
	}
}
else{
echo "<script> alert('Something Went Wrong')</script>";
 }
 }
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="keywords" content="Online book center, Government exam materials rental, book rental, TNPSC, SSC, UPSC, BANKING, career guidance, Youtube videos, magazine subscription, institute courses, test packs for government exam preparations" />
	<meta name="author" content="DexignZone" />
	<meta name="robots" content="" />
	<meta name="description" content="EA Dream Supporters is an ED tech business that began to care about the career aspirations of Indian youngsters. Numerous youths struggled to locate the appropriate resources and opportunities for their dream. So EA creates Dream Supporting Services, a unique one-stop portal for competitive test preparation. We support youth by acting as a prodding force for their desired course."/>
	
	<!-- FAVICONS ICON -->
	<link rel="shortcut icon" type="image/x-icon" href="images/favicon.png" />
	
	<!-- PAGE TITLE HERE -->
	<title>Registration - EA Dream Supporters</title>
	
	<!-- MOBILE SPECIFIC -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
	<!-- STYLESHEETS -->
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel="stylesheet" type="text/css" href="icons/fontawesome/css/all.min.css">
	<link rel="stylesheet" type="text/css" href="vendor/bootstrap-select/dist/css/bootstrap-select.min.css">

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

			<!-- Header -->
	<?php include'header1.php'; ?>
	<!-- Header End -->	
		<div class="page-content">
			<!-- inner page banner -->
			<div class="dz-bnr-inr overlay-secondary-dark dz-bnr-inr-sm" style="background-image:url(images/bg.jpg);">
				<div class="container">
					<div class="dz-bnr-inr-entry">
						<h1>FORGOT PASSWORD</h1>
						<nav aria-label="breadcrumb" class="breadcrumb-row">
							<ul class="breadcrumb">
								<li class="breadcrumb-item"><a href="index.php"> Home</a></li>
								<li class="breadcrumb-item">FORGOT PASSWORD</li>
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
					<div class="row justify-content-center">
						<div class="col-lg-6 col-md-6 mb-4">
							<div class="login-area">
								<form method="post" enctype="multipart/form-data"> 
									<h4 class="text-secondary">FORGOT PASSWORD</h4>
									<p class="font-weight-600"></p>
									<div class="mb-4">
										<label class="label-title">New Password *</label>
										<input name="npassw" required="" class="form-control" placeholder="Enter New Password" type="password">
									</div>
									<div class="mb-4">
										<label class="label-title">Confirm Password*</label>
										<input name="cpassw" required="" class="form-control " placeholder="Confirm Password" type="password">
									</div>
									<div class="text-left">
									<input type="submit" value="Update" class="btn btn-primary btnhover w-100 me-2" name ="pass">
                
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
				<!-- Product END -->
			</section>
			<!-- contact area End--> 
		</div>
		
<!-- Footer -->
<?php include'footer1.php'; ?>
		<!-- Footer End -->
		
		<button class="scroltop" type="button"><i class="fas fa-arrow-up"></i></button>
	</div>
	
<!-- JAVASCRIPT FILES ========================================= -->
<script src="js/jquery.min.js"></script><!-- JQUERY MIN JS -->
<script src="vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script><!-- BOOTSTRAP MIN JS -->
<script src="vendor/bootstrap-select/dist/js/bootstrap-select.min.js"></script><!-- BOOTSTRAP SELECT MIN JS -->
<script src="js/custom.js"></script><!-- CUSTOM JS -->
<script src="vendor/wow/wow.min.js"></script><!-- WOW JS -->

</body>
</html>
	