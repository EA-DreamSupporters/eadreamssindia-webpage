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
	<title>User Login - EA Dream Supporters</title>
	
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
include ("config.php");

if(isset($_POST['submit']))
{
$sql = "SELECT * FROM students WHERE username='".$_POST["User_Name"]."'";
$result=mysqli_query($con,$sql);


$row = mysqli_fetch_array($result);

$id=$row['sid'];
$name=$row['username'];
$pass=$row['password']; 


$name1 = stripslashes($_REQUEST['User_Name']);
$name1 = mysqli_real_escape_string($con,$name1);
$pass1 = stripslashes($_REQUEST['passw']);
$pass1 = mysqli_real_escape_string($con,$pass1);

$pass2=md5($pass1);

if($name==$name1 & $pass==$pass2)
	{
	$_SESSION["id"] = $id;
	$_SESSION["user"] = $name1;
 
   $_SESSION['return_to'] = $_SERVER['REQUEST_URI'];
   header("Location: " . $_SESSION['return_to']);

	}
	else
	{
    echo "<script> alert('Invalid Credentials')</script>";
	}


 }?>

			<!-- Header -->
	<?php include'header1.php'; ?>
	<!-- Header End -->	
		<div class="page-content">
			<!-- inner page banner -->
			<div class="dz-bnr-inr overlay-secondary-dark dz-bnr-inr-sm" style="background-image:url(images/bg.jpg);">
				<div class="container">
					<div class="dz-bnr-inr-entry">
						<h1>User Login</h1>
						<nav aria-label="breadcrumb" class="breadcrumb-row">
							<ul class="breadcrumb">
								<li class="breadcrumb-item"><a href="index.php"> Home</a></li>
								<li class="breadcrumb-item">User Login</li>
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
									<h4 class="text-secondary">User Login</h4>
									<p class="font-weight-600"></p>
									<div class="mb-4">
										<label class="label-title">Username *</label>
										<input name="User_Name" required="" class="form-control" placeholder="Your Username" type="text">
									</div>
									<div class="mb-4">
										<label class="label-title">Password *</label>
										<input name="passw" required="" class="form-control " placeholder="Type Password" type="password">
									</div>
									<div class="mb-5">
										<small>Don't have any Account?<a href="register"> Register now!</a>.</small>|
										<a data-bs-toggle="tab" href="#forgot-password" class="m-l5"><i
                                    class="fas fa-unlock-alt"></i> Forgot Password</a>
									</div>
									
									<div class="text-left">
									<input type="submit" value="Login" class="btn btn-primary btnhover w-100 me-2" name ="submit">
                
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
	