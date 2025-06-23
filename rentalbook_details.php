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
	<title>Rental Book  Details- EA Dream Supporters</title>
	
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
include("header1.php");
?>
<?php
//Code for Adding Product in to Cart
$id=$_GET["id"];
if(isset($_POST['addtocart'])){
if(strlen($_SESSION['id']) == 0)
{   
echo "<script>alert('Pleae login to rental books');</script>";
} else{
date_default_timezone_set("Asia/Kolkata");
$date=date("Y-m-d");
$userid=$_SESSION['id']; 
$rbook=$_POST['rbook'];  
$TextBox2=$_POST['TextBox2']; 
$total=$_POST['TextBox3']; 
$pay=$_POST['pay']; 
 $radvance=$_POST['radvance'];
$myDate = date("Y-m-d", strtotime( date( "Y-m-d", strtotime( date("Y-m-d") ) ) . "+$TextBox2 days" ) ); 
	
		$ret=mysqli_query($con,"insert into rentals(userId,rendalbook,rdate,enddate,tdays,tamount,paid,due) values('$userid','$rbook','$date','$myDate','$TextBox2','$total','$pay','$due')");
		
		if($ret)
		{
		
echo "<script>alert('Confirm to rental this book');</script>";
  echo "<script type='text/javascript'> document.location ='rentalbooks.php'; </script>";


		}else{
  
		echo "<script>alert('Pay minimum advance');</script>";

	}
}
}



$result1 = mysqli_query($con,"SELECT a.*,b.*,c.* FROM rentalbooks AS a, category AS b, subcategory AS c WHERE a.rid ='$id' AND a.rcat_id=b.category_id AND a.rsubcat_id=c.subcategory_id");
$row1 = mysqli_fetch_array($result1);
?>
<style>
h3.title {
    text-transform: capitalize;
}
h5.subtitle {
	text-transform: capitalize;
}
td {
	text-transform: capitalize;
}
.addcart{
	margin-right:20px;
}
h6{
	    font-weight: lighter;
    font-size: 20px;
}

#term {
  position: relative;
  top: 8px;
  right: -11px;
}
.term {
  position: relative;
  top: 8px;
  right: -11px;
}
</style>
	<div class="page-content bg-grey">
		<section class="content-inner-1">
			<div class="container">
			<form name="productdetails" method="post">
				<div class="row book-grid-row style-4 m-b60">
					<div class="col">
						<div class="dz-box">
							<div class="dz-media">
								<img src="images/<?php echo $row1['rimage']; ?>" alt="book">
							</div>
							<div class="dz-content">
								<div class="dz-header">
									<h3 class="title"><?php echo $row1['rbook']; ?></h3>
									<input class="form-control text-center me-3" id="Text"  name="rbook" type="hidden" value="<?php echo $row1['rid']; ?>" />
                            <div class="shop-item-rating">
										<div class="d-lg-flex d-sm-inline-flex d-flex align-items-center">
											<div class="social-area">
											<ul class="dz-social-icon style-3">
												<li><a href="https://www.facebook.com/dexignzone" class="fb-btn" target="_blank"><i class="fa-brands fa-facebook-f"></i></a></li>
												<li><a href="https://twitter.com/dexignzones"  class="tw-btn" target="_blank"><i class="fa-brands fa-twitter"></i></a></li>
												<li><a href="https://www.whatsapp.com/"  class="wa-btn" target="_blank"><i class="fa-brands fa-whatsapp"></i></a></li>
												<li><a href="https://www.google.com/intl/en-GB/gmail/about/" class="li-btn" target="_blank"><i class="fa-brands fa-linkedin"></i></a></li>
											</ul>
										</div>
										</div>
										
									</div>
									</div>
								<div class="dz-body">
									<div class="book-detail">
										<ul class="book-info">
										<li><span>Rent/month</span>₹100</li>
                            
											<li>
												<div class="writer-info">
												
													<div>
														<span>Author</span><?php echo $row1['rauthor']; ?>
													</div>
												</div>
											</li>
											<li><span>Category</span><?php echo $row1['category']; ?></li>
											<li><span>Subcategory</span><?php echo $row1['subcategory']; ?></li>
										</ul>
									</div>
									<p class="text-1"><?php echo $row1['rdescription']; ?></p>
									
									<div class="book-footer">
										
										<div class="product-num">
											<input class="form-control text-center me-3" id="Text2" oninput="add_number()" name="TextBox2" type="number" maxlength="2" min="1" max="5"placeholder="Total Months to Rent" required />
                            
											<input type="hidden" id="Text1" name="TextBox1" oninput="add_number()" value="100" >
											<input type="text" class="form-control text-center me-3" id="txtresult" name="TextBox3" placeholder="Total Rental Amount" readonly>
										</div>
										
										
									</div>
									<div class="book-footer">
										
										<div class="product-num">
										<label>
										Deposit Amount(₹)
										</label>
											<input class="form-control text-center me-3" id="Text"  name="pay" type="number" placeholder="Payable Amount" value="1000" required readonly />
                            
											</div>
										
										
									</div>
									<div class="book-footer">
									
										<div class="product-num">
							<input type="checkbox" required><p id="term">Accept <a href="rental_t&c">Terms & Condition</a>  For Rental</p>
										</div>
									</div>
<p class="term">*Delivery Charges Applicable*</p>
									<div class="book-footer">
									
									
										
										<div class="product-num">
											
							<button class="btn btn-secondary btnhover2" type="submit" name="addtocart">
                                <i class="bi-cart-fill me-1"></i>
                                Rent Book
                            </button>
										</div>
									
										
									</div>
									
								</div>
							</div>
						</div>
					</div>
				</div>
				</form>
			
			</div>
		</section>
<?php include'count.php' ; ?>
	<script>
var text1 = document.getElementById("Text1");
var text2 = document.getElementById("Text2");

function add_number() {
   var first_number = parseFloat(text1.value);
   if (isNaN(first_number)) first_number = 0;
   var second_number = parseFloat(text2.value);
   if (isNaN(second_number)) second_number = 0;
   var result = first_number * second_number;
   document.getElementById("txtresult").value = result;
}
</script>	
		
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
</body>
</html>