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
	<title>Cart - EA Dream Supporters</title>
	
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
if(strlen($_SESSION['id'])==0)
{   header('location:logout.php');
}else{

// Code for Product deletion from  cart  
if(isset($_GET['del']))
{
$wid=intval($_GET['del']);
$query=mysqli_query($con,"delete from cart where id='$wid'");
 //echo "<script>alert('Book deleted from cart.');</script>";
echo "<script type='text/javascript'> document.location ='cart.php'; </script>";
}


?>
	<style>
	h5{
	    margin: -8px 0px 0px -6px;
    color: white;	
	}

</style>		
	<div class="page-content">
		<!-- inner page banner -->
			<div class="dz-bnr-inr overlay-secondary-dark dz-bnr-inr-sm" style="background-image:url(images/background/bg3.jpg);">
				<div class="container">
					<div class="dz-bnr-inr-entry">
						<h1>Cart</h1>
						<nav aria-label="breadcrumb" class="breadcrumb-row">
							<ul class="breadcrumb">
								<li class="breadcrumb-item"><a href="index.php"> Home</a></li>
								<li class="breadcrumb-item">Cart</li>
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
				<div class="row mb-5">
					<div class="col-lg-12">
						<div class="table-responsive">
							<table class="table check-tbl">
								<thead>
									<tr>
										<th>Book</th>
										<th>Book Name</th>
										<th>Book Price</th>
										<th>Quantity</th>
										<th>Total</th>
										<th class="text-end">Close</th>
									</tr>
								</thead>
								<tbody>
								<?php
$uid=$_SESSION['id'];
$ret = mysqli_query($con,"SELECT a.*,b.* FROM books AS a, cart AS b WHERE a.book_id=b.productId AND b.userID='$uid'");

//$ret=mysqli_query($con,"select products.productName as pname,products.productName as proid,products.productImage1 as pimage,products.productPrice as pprice,cart.productId as pid,cart.id as cartid,products.productPriceBeforeDiscount,cart.productQty from cart join products on products.id=cart.productId where cart.userId='$uid'");
$num=mysqli_num_rows($ret);
    if($num>0)
    {
while ($row=mysqli_fetch_array($ret)) {
if(isset($_POST['addtocart'])){

echo $userid=$_SESSION['id']; 
echo $productqty=$_POST['inputQuantity'];
echo $pid=$_POST['inputpid'];
mysqli_query($con,"update cart set productQty='$productqty' where userId='$userid' and productId='$pid'");
echo "<script>alert('Cart Updated');</script>";
echo "<script type='text/javascript'> document.location ='cart.php'; </script>";
}
?>
<form method="post">
									<tr>
										<td class="product-item-img"><a href="book_details.php?id=<?php echo $row['book_id'];?>"><img src="images/books/<?php echo $row['image'];?>" alt=""></a></td>
										<td class="product-item-name"><a href="book_details.php?id=<?php echo $row['book_id'];?>"><?php echo $row['book'];?></a></td>
										<td class="product-item-price">₹ <?php echo $row['price'];?></td>
										<td class="product-item-quantity">
											<div class="quantity btn-quantity style-1 me-3">
											
												<?php echo $row['productQty'];?>
											</div>
										</td>
										<td>₹ <?php echo htmlentities($totalamount=$row['productQty']*$row['price']);?></td>
										<td class="product-item-close">
										<a href="cart.php?del=<?php echo htmlentities($row['id']);?>" onClick="return" class="btn-upper btn btn-danger">
										<h5>X</h5>
										</a>
										</td>
									</tr>
									
									<?php $grantotal+=$totalamount; 
									} ?>
									<tr>
                    <td colspan="6" style="text-align:right;">
<a href="books.php" class="btn-upper btn btn-warning">Continue Shopping</a>
                        <!--<button class="btn-upper btn btn-primary" type="submit" name="addtocart">Update Cart</button>--></td>
                </tr>
				</form>
                <?php } else{ ?>
                <tr>
                    <td style="font-size: 18px; font-weight:bold ">Your Cart is Empty.&nbsp;
<a href="books.php" class="btn-upper btn btn-warning">Continue Shopping</a>
                    </td>

                </tr>
                <?php } ?>
								</tbody>
							</table>
						</div>
					</div>
					
				</div>
				<div class="row">
					<div class="col-lg-6">
						
					</div>
					<div class="col-lg-6">
						<div class="widget">
							<h4 class="widget-title">Cart Subtotal</h4>
							<table class="table-bordered check-tbl m-b25">
								<tbody>
									<tr>
										<td>Order Subtotal</td>
										<td>₹ <?php echo $grantotal;?></td>
									</tr>
									<tr>
										<td>Shipping</td>
										<td>Free Shipping</td>
									</tr>
									<tr>
										<td>Grand Total</td>
										<td>₹ <?php echo $grantotal;?></td>
									</tr>
								</tbody>
							</table>
							<div class="form-group m-b25" style="text-align:right;">
								<a href="checkout.php?total=<?php echo htmlentities($grantotal);?>" class="btn btn-primary btnhover" type="button">Proceed to Checkout</a>
							</div>
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
</body>
</html>