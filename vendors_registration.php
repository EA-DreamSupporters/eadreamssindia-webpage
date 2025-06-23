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
	<title>Partner's Registration - EA Dream Supporters</title>
	
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
<?php
session_start();
error_reporting(0);
include ("config.php");

if(isset($_POST['OK']))
{
$name=$_POST["name"];
$email=$_POST["email"];
$phone=$_POST["phone"];
$address=$_POST["address"];
$message=$_POST["message"];
$purpose=$_POST["purpose"];
$who_are_you=$_POST["who_are_you"];

$ret=mysqli_query($con, "select Email from vendors where Email='$email'");
    $result=mysqli_fetch_array($ret);
    if($result>0){
 echo "<script>alert('This email already associated with another account');</script>";
    }
    else{
	
$sql=mysqli_query($con, "INSERT INTO vendors(name, Email, phone_no, address, message, Purpose, who_are_you) 
VALUES('$name','$email','$phone','$address','$message','$purpose','$who_are_you')");


    echo "<script> alert('Registered Successfully')</script>";

 }
 }
?>
			<!-- Header -->
	<?php include'header1.php'; ?>
	<!-- Header End -->	
		<div class="page-content">
			<!-- inner page banner -->
			<div class="dz-bnr-inr overlay-secondary-dark dz-bnr-inr-sm" style="background-image:url(images/bg.jpg);">
				<div class="container">
					<div class="dz-bnr-inr-entry">
						<h1>Partner's Registration</h1>
						<nav aria-label="breadcrumb" class="breadcrumb-row">
							<ul class="breadcrumb">
								<li class="breadcrumb-item"><a href="index.php"> Home</a></li>
								<li class="breadcrumb-item">Partner's Registration</li>
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
									<h4 class="text-secondary">Partnership Registration</h4>
									<p class="font-weight-600"></p>
									<div class="mb-4">
										<label class="label-title">Fullname *</label>
										<input name="name" required="" class="form-control " placeholder="Your Fullname" type="text">
									</div>
									<div class="mb-4">
										<label class="label-title">Email *</label>
										<input name="email" required="" class="form-control" placeholder="Your Email address" type="email">
									</div>
									<div class="mb-4">
										<label class="label-title">Phone *</label>
										<input name="phone" required="" class="form-control " placeholder="Your Phone No" type="text">
									</div>
									<div class="mb-4">
										<label class="label-title">Address *</label>
										<textarea  rows="3" cols="80" class="form-control" name="address" placeholder="Your Address"></textarea>
									
									</div>
                                    <div class="mb-4">
										<label class="label-title">Who are you? *</label><br>
										<input type="radio" name="who_are_you" value="Institute" required> Institute <br>
										<input type="radio" name="who_are_you" value="Publisher" required> Book Seller / Publisher <br>
										<input type="radio" name="who_are_you" value="Student" required> Student <br>
										<input type="radio" name="who_are_you" value="Affiliate" required> Affiliate<br>
										<input type="radio" name="who_are_you" value="Educator" required> Educators ( Individual )<br>
										<input type="radio" name="who_are_you" value="Other" required> Other<br>
									</div>
									<div class="mb-4">
										<label class="label-title">Purpose *</label>
										<select class="form-control" name="purpose" required>
											<option value="">Choose Below</option>
											<option value="CBM Option 1">Add my Course, Books, Material</option>
											<option value="JR Option 2">Joblication Rights</option>
											<option value="Govprep Option 3">Add GovPrep with my Institute</option>
										</select>

									</div>
									<div class="mb-4">
										<label class="label-title">Message *</label>
										<textarea  rows="3" cols="80" class="form-control" name="message" placeholder="Your Message"></textarea>
									
									</div>
									<div class="mb-5">
										<small>Your personal data will be used to support your experience throughout this website, to manage access to your account, and for other purposes described in our 
										<a href="privacy">Privacy policy</a>.</small>
									</div>
									<div class="text-left">
									<input type="submit" value="Register" class="btn btn-primary btnhover w-100 me-2" name="OK">
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