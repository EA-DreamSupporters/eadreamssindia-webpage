
<?php
include('config.php');

if(isset($_POST["send"]))
{
	 $name = $_POST['name']; //getting customer name
 $fromEmail = $_POST['email']; //getting customer email
 $phone = $_POST['phone']; //getting customer Phome number
 $dob = $_POST['dob']; //getting customer Phome number
 $pass = $_POST['passed']; //getting customer Phome number
 $self = $_POST['self']; //getting customer Phome number
 $address = $_POST['address']; //getting customer Phome number
 $subject = $_POST['subject']; //getting  line from client


	$ret=mysqli_query($con,"Insert into career (name,dob,email,phone,pout,pace,address,msg) values('$name','$dob','$fromEmail','$phone','$pass','$self','$address','$subject')");
	if($ret)
	{
		 echo '<script>alert("Application Submitted Sucessfully!")</script>';
		
		
	}
	else
	{
		 echo '<script>alert("Something Went Wrong Try again.")</script>';
		
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
	<title>Career Counselling  - EA Dream Supporters</title>
	
	<!-- MOBILE SPECIFIC -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
	<!-- STYLESHEETS -->
	<link rel="stylesheet" type="text/css" href="your_website_domain/css_root/flaticon.css"> 
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel="stylesheet" type="text/css" href="icons/fontawesome/css/all.min.css">
	<link rel="stylesheet" type="text/css" href="vendor/swiper/swiper-bundle.min.css">
	<link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
	<link rel="stylesheet" type="text/css" href="vendor/bootstrap-select/dist/css/bootstrap-select.min.css">	
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<!-- GOOGLE FONTS-->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600;700;800&family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
	
	
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel="stylesheet" type="text/css" href="icons/fontawesome/css/all.min.css">
	<link rel="stylesheet" type="text/css" href="vendor/bootstrap-select/dist/css/bootstrap-select.min.css">
	
	
	<!-- GOOGLE FONTS-->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600;700;800&family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<style>
.error {
	color: #FF0001;
	} 
  @media screen and (max-width: 576px){
.map-iframe {
  margin: 0;
  background-size: contain;
  background-repeat: no-repeat;
  margin-bottom: -23vh;
}
  }

    @media screen and (min-width:576px){
.map-iframe {
  margin: 0;
    margin-bottom: 0px;
  background-size: cover;
  background-repeat: no-repeat;
  margin-bottom: -40vh;
}
	}
	  @media screen and (min-width: 992px){
.map-iframe {
  margin: 0;
}
}
  @media screen and (min-width:768px){
#car{
 display:none;
}
}
@media screen and (max-width:992px){
	
	#gg{
display: none;

}
}
</style>
</head>

<body>
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
		
			<div id="gg" class="content-inner-2 pt-0">
				<div id="career" class="map-iframe" style="background-image:url(images/career.jpg); ">
				
				</div>
				
			</div>
		
			<section class="contact-wraper1" style="background: linear-gradient(to right, rgba(26, 22, 104, 0.5) 0%, rgb(26, 22, 104) 100%);">	
				<div class="container">
					<div class="row">
					
						<div class="col-lg-5">
							<div class="contact-info">
								<div class="section-head text-white style-1">
									<h3 class="title text-white">Career Counselling</h3>
									<p>Finding the right career is more about finding the ideal work for you than it is about finding the perfect job. Many people wind up in jobs they hate because they chose a trade or career path in the hope of earning more money or living a better lifestyle. You'll succeed eventually if you appreciate what you do, regardless of your profession or vocation. To begin, think about what has regularly aroused your curiosity since you were a child. Don't put too much pressure on yourself to answer with precision right away, even though it could seem challenging. The answers might even be otherworldly.</p>
								</div>
					
							</div>
							<div class="contact-info" >
								<div class="section-head text-white style-1">
									<h3 class="title text-white">Get In Touch</h3>
									<p>If you are interested in working with us, Please get in touch.</p>
								</div>
								<ul class="no-margin">
									<li class="icon-bx-wraper text-white left m-b30">
										<div class="icon-md">
											<span class="icon-cell text-primary">
												<svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-map-pin"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
											</span>
										</div>
										<div class="icon-content">
											<h5 class=" dz-tilte text-white">Our Address</h5>
											<p>NO.2, CMC Road, Keelaoorani South, Karaikudi, Sivaganga District, TamilNadu - 630001</p>
										</div>
									</li>
									<li class="icon-bx-wraper text-white left m-b30">
										<div class="icon-md">
											<span class="icon-cell text-primary">
												<svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-mail"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
											</span>
										</div>
										<div class="icon-content">
											<h5 class="dz-tilte text-white">Our Email</h5>
											<p>eadreamssindia@gmail.com</p>
										</div>
									</li>
										<li class="icon-bx-wraper text-white left m-b30">
										<div class="icon-md">
											<span class="icon-cell text-primary" style="margin-right: -34px;">
												<svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" fill="currentColor" class="bi bi-phone" stroke-width="2"  viewBox="0 0 24 24"> <path d="M11 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h6zM5 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H5z"/> <path d="M8 14a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/></svg>
											</span>
										</div>
										<div class="icon-content">
											<h5 class="dz-tilte text-white">Phone</h5>
											<p>+91 9487277924</p>
										</div>
									</li>
								</ul>
							</div>
						</div>
						<div class="col-lg-7 m-b40">
							<div class="contact-area1 m-r20 m-md-r0">
								<div class="section-head style-1">
									<h6 class="sub-title text-primary"> Let's Discuss </h6>
									<h3 class="title m-b20">Want To Choose The Right Career Path ?</h3>
								</div>
					<form method="POST">
									
										
                               <p>Name<span class="error">* <?php echo $nameErr; ?> </span></p>  									
									<div class="input-group">
										<input required type="text" class="form-control" name="name" placeholder="Full Name">
									</div>
									<p>Date Of Birth</p>	
									<div class="input-group">
										<input required type="date" class="form-control" name="dob" value="Full Name">
									</div>
									<p>Email<span class="error">* <?php echo $emailErr; ?></p>	
									<div class="input-group">
										<input required type="email" class="form-control" name="email" placeholder="Email Adress">
									</div>
									<p>Contact<span class="error">* <?php echo $mobilenoErr; ?> </span> </p>	
									<div class="input-group">
										<input required type="tel" class="form-control"  pattern="[0-9]{10}" name="phone" placeholder="Phone No.">
									</div>
									<div class="form-group">
						
									<label for="sel1">Do you completed your degree ?</label>
									</br>
  <select required name="passed" class="form-control" id="sel1" style="margin-top: 14px;">
    <option>Yes</option>
    <option>No</option>
    
  </select>
  	</div></br>
	<p>Graduation Passing year (m-y)</p>	
									<div class="form-group">
										<input required type="month" class="form-control" name="pyear" placeholder="Enter year">
									</div>
				</br>
												<div class="form-group">
						
									<label for="sel1">Are you Self Preparing Aspirant ?</label>
									</br>
  <select required class="form-control" id="sel1" name="self" style="margin-top: 14px;">
    <option>Yes</option>
    <option>No</option>
    
  </select>
  	</div></br>
									<p>Address</p>	
									<div class="input-group">
										<input required type="text" class="form-control" name="address" placeholder="Address">
									</div>
									<div class="input-group">
										<textarea required name="subject" rows="5" class="form-control"placeholder="Message"></textarea>
									</div>
									<div class="mb-3">
										<div class="g-recaptcha" data-sitekey="6LfZlSETAAAAAC5VW4R4tQP8Am_to4bM3dddxkEt" data-callback="verifyRecaptchaCallback" data-expired-callback="expiredRecaptchaCallback"></div>
										<input class="form-control d-none" style="display:none;" data-recaptcha="true" required data-error="Please complete the Captcha">
									</div>
									<div>
										<button name="send" type="submit" value="submit" class="btn w-100 btn-primary btnhover">SUBMIT</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</section>
			
			<!-- Feature Box -->
			<section class="content-inner">
			<div class="container">
				<div class="row sp15">
					<div class="col-lg-3 col-md-6 col-sm-6 col-6 wow fadeInUp" data-wow-delay="0.1s">
						<div class="icon-bx-wraper style-2 m-b30 text-center">
							<div class="icon-bx-lg">
								<i class="fa-solid fa-smile icon-cell"></i>
							</div>
							<div class="icon-content">
								<h2><span class="dz-title counter m-b0">100</span><small>%</small></h2>
								<p class="font-20">Customers Satisfaction</p>
							</div>
						</div>
					</div>
					<div class=" col-lg-3 col-md-6 col-sm-6 col-6 wow fadeInUp" data-wow-delay="0.2s">
						<div class="icon-bx-wraper style-2 m-b30 text-center">
							<div class="icon-bx-lg"> 
								<i class="fa-solid fa-book icon-cell"></i>
							</div>
							<div class="icon-content">
								<h2><span class="dz-title counter m-b0">500</span><small>+</small></h2>
								<p class="font-20">Book Collections</p>
							</div>
						</div>
					</div>
					<div class="col-lg-3 col-md-6 col-sm-6 col-6 wow fadeInUp" data-wow-delay="0.3s">
						<div class="icon-bx-wraper style-2 m-b30 text-center">
							<div class="icon-bx-lg"> 
								<i class="fa-solid fa-users icon-cell"></i>
							</div>
							<div class="icon-content">
							<h2><span class="dz-title counter m-b0">1000</span><small>+</small></h2>
								<p class="font-20">Aspirants</p>
							</div>
						</div>
					</div>
					<div class="col-lg-3 col-md-6 col-sm-6 col-6 wow fadeInUp" data-wow-delay="0.4s">
						<div class="icon-bx-wraper style-2 m-b30 text-center">
							<div class="icon-bx-lg"> 
								<i class="fa-solid fa-leaf icon-cell"></i>
							</div>
							<div class="icon-content">
								<h2><span class="dz-title counter m-b0">24,295</span></h2>
								<p class="font-20">Community Engagement</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
			<!-- Feature Box End -->
			
			<!-- Newsletter -->
			
			<!-- Newsletter End -->
				
		</div>
		
		<!-- Footer -->
			<?php include'footer-white.php'; ?>
		<!-- Footer End -->
		
		<button class="scroltop" type="button"><i class="fas fa-arrow-up"></i></button>
	</div>
	
<!-- JAVASCRIPT FILES ========================================= -->
<script src="js/jquery.min.js"></script><!-- JQUERY MIN JS -->
<script src="vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script><!-- BOOTSTRAP MIN JS -->
<script src="vendor/bootstrap-select/dist/js/bootstrap-select.min.js"></script><!-- BOOTSTRAP SELECT MIN JS -->
<script src='https://www.google.com/recaptcha/api.js'></script> <!-- Google API For Recaptcha  -->
<script src="vendor/counter/waypoints-min.js"></script><!-- WAYPOINTS JS -->
<script src="vendor/counter/counterup.min.js"></script><!-- COUNTERUP JS -->
<script src="js/dz.ajax.js"></script><!-- AJAX -->
<script src="js/custom.js"></script><!-- CUSTOM JS -->
<script src="vendor/wow/wow.min.js"></script><!-- WOW JS -->
<script src="vendor/swiper/swiper-bundle.min.js"></script><!-- SWIPER JS -->
</body>
</html>