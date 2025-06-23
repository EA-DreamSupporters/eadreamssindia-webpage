<?php
    error_reporting(E_ERROR | E_PARSE);
	session_start();
	include ("config.php");	
     $userid= $_SESSION["id"];
	 $user= $_SESSION["user"];
	$sql = "SELECT * FROM students WHERE username='$user'";
    $result=mysqli_query($con, $sql);
    $row = mysqli_fetch_array($result);
	
	// Code for Product deletion from  cart  
if(isset($_GET['del']))
{
$wid=intval($_GET['del']);
$query=mysqli_query($con,"delete from cart where id='$wid'");
 //echo "<script>alert('Book deleted from cart.');</script>";
echo "<script type='text/javascript'> document.location ='index.php'; </script>";
}
	 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	
	<!-- Meta -->
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="keywords" content="" />
	<meta name="author" content="DexignZone" />
	<meta name="robots" content="" />
	<meta name="description" content="Bookland-Book Store Ecommerce Website"/>
	<meta property="og:title" content="Bookland-Book Store Ecommerce Website"/>
	<meta property="og:description" content="Bookland-Book Store Ecommerce Website"/>
	<meta property="og:image" content="https://makaanlelo.com/tf_products_007/bookland/xhtml/social-image.png"/>
	<meta name="format-detection" content="telephone=no">
	
	<!-- FAVICONS ICON -->
	<link rel="icon" type="image/x-icon" href="images/favicon.png"/>
	
	<!-- PAGE TITLE HERE -->
	<title>Bookland Book Store Ecommerce Website</title>
	
	<!-- MOBILE SPECIFIC -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
	<!-- STYLESHEETS -->
	<link rel="stylesheet" type="text/css" href="vendor/bootstrap-select/dist/css/bootstrap-select.min.css">
	<link rel="stylesheet" type="text/css" href="icons/fontawesome/css/all.min.css">
	<link rel="stylesheet" type="text/css" href="vendor/swiper/swiper-bundle.min.css">
	<link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	
	<!-- GOOGLE FONTS-->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Michroma&display=swap" rel="stylesheet">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600;700;800&family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

</head>	
<body>

<div class="page-wraper">
	<div id="loading-area" class="preloader-wrapper-1">
		<div class="preloader-inner">
			<div class="preloader-shade"></div>
			<div class="preloader-wrap"></div>
			<div class="preloader-wrap wrap2"></div>
			<div class="preloader-wrap wrap3"></div>
			<div class="preloader-wrap wrap4"></div>
			<div class="preloader-wrap wrap5"></div>
		</div> 
	</div>
	
	<!-- Header -->
	<header class="site-header mo-left header style-1">
		<!-- Main Header -->

		
		<!-- Main Header -->
		<div class="sticky-header main-bar-wraper navbar-expand-lg">
			<div class="main-bar clearfix">
				<div class="container clearfix">
					<!-- Website Logo -->
					<div class="logo-header logo-dark">
						<a href="index.php"><img src="images/logo.png" alt="logo"></a>
					</div>
					
					<!-- Nav Toggle Button -->
					<button class="navbar-toggler collapsed navicon justify-content-end" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
						<span></span>
						<span></span>
						<span></span>
					</button>
					
							
				
<?php 
$ret=mysqli_query($con,"select sum(productQty) as qtyy from cart where userId='$userid'");
$result1=mysqli_fetch_array($ret);
 $cartcount=$result1['qtyy'];
                        ?>			
				<!-- EXTRA NAV -->
				<div class="extra-nav">
					<div class="extra-cell">
						<ul class="navbar-nav header-right">
								
							<li class="nav-item">
								<button type="button" class="nav-link box cart-btn">
									<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M15.55 13c.75 0 1.41-.41 1.75-1.03l3.58-6.49c.37-.66-.11-1.48-.87-1.48H5.21l-.94-2H1v2h2l3.6 7.59-1.35 2.44C4.52 15.37 5.48 17 7 17h12v-2H7l1.1-2h7.45zM6.16 6h12.15l-2.76 5H8.53L6.16 6zM7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zm10 0c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z"/></svg>
								
						<?php if($cartcount == 0):?>
                            <span class="badge">0</span>
                        <?php else: ?>
                            <span class="badge"><?php echo $cartcount; ?></span>
                            <?php endif;?>
								</button>
								<ul class="dropdown-menu cart-list">
								<?php 
								 if($cartcount != 0){
$ret1 = mysqli_query($con,"SELECT a.*,b.* FROM books AS a, cart AS b WHERE a.book_id=b.productId AND b.userID='$userid'");
while ($row1=mysqli_fetch_array($ret1)) {
	
		 ?>
                        
									<li class="cart-item">
										<div class="media"> 
											<div class="media-left"> 
												<a href="books-detail.html"> 
													<img alt="" class="media-object" src="images/<?php echo $row1['image'];?>"> 
												</a> 
											</div> 
											<div class="media-body"> 
												<h6 class="dz-title"><a href="books-detail.html" class="media-heading"><?php echo $row1['book'];?></a></h6>
												<span class="dz-title">Qty: <?php echo $row1['productQty'];?></span></br>
												<span class="dz-price">₹ <?php echo $totalamount=$row1['productQty']*$row1['price'];?></span>
												<a href="header1.php?del=<?php echo htmlentities($row1['id']);?>" onClick="return"><span class="item-close">&times;</span></a>

											</div> 
										</div>
									</li>
									<?php
									$grantotal+=$totalamount; 
									}
                        ?>
									<li class="cart-item text-center">
										<h6 class="text-secondary">Total = ₹ <?php echo $grantotal; ?></h6>
									</li>
									
									<li class="text-center d-flex">
										<a href="cart.php" class="btn btn-sm btn-primary me-2 btnhover w-100">View Cart</a>
										<a href="checkout.php" class="btn btn-sm btn-outline-primary btnhover w-100">Checkout</a>
									</li>
									<?php }else{ ?>
									<li class="cart-item text-center">
										<h6 class="text-secondary">No Books Added</h6>
									</li>
									<?php } ?>
								</ul>
							</li>
								
							<?php if($_SESSION["user"]){ ?>
							<li class="nav-item dropdown profile-dropdown  ms-4">
								<a class="nav-link" href="javascript:void(0);" role="button" data-bs-toggle="dropdown" aria-expanded="false">
									<img src="images/<?php echo $row['photo'] ?>" alt="/">
									<div class="profile-info">
										<h6 class="title"><?php echo $row['name'] ?></h6>
										<span><?php echo $row['email'] ?></span>
									</div>
								</a>
								<div class="dropdown-menu py-0 dropdown-menu-end">
									<div class="dropdown-header">
										<h6 class="m-0"><?php echo $row['name'] ?></h6>
										<span><?php echo $row['email'] ?></span>
									</div>
									<div class="dropdown-body">
										<a href="dashboard.php" target="_blank" class="dropdown-item d-flex justify-content-between align-items-center ai-icon">
											<div>
												<svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 0 24 24" width="20px" fill="#000000"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M12 6c1.1 0 2 .9 2 2s-.9 2-2 2-2-.9-2-2 .9-2 2-2m0 10c2.7 0 5.8 1.29 6 2H6c.23-.72 3.31-2 6-2m0-12C9.79 4 8 5.79 8 8s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4zm0 10c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
												<span class="ms-2">Dashboard</span>
											</div>
										</a>
										<a href="orders.php" class="dropdown-item d-flex justify-content-between align-items-center ai-icon">
											<div>
												<svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 0 24 24" width="20px" fill="#000000"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M15.55 13c.75 0 1.41-.41 1.75-1.03l3.58-6.49c.37-.66-.11-1.48-.87-1.48H5.21l-.94-2H1v2h2l3.6 7.59-1.35 2.44C4.52 15.37 5.48 17 7 17h12v-2H7l1.1-2h7.45zM6.16 6h12.15l-2.76 5H8.53L6.16 6zM7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zm10 0c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z"/></svg>
												<span class="ms-2">My Order</span>
												<i class="icon-dashboard"></i>
											</div>
										</a>
									</div>
									<div class="dropdown-footer">
										<a class="btn btn-primary w-100 btnhover btn-sm" href="logout.php">Log Out</a>
									</div>
								</div>
							</li>
							
							<?php } else{  ?>
							
							<div class="extra-nav">
						<div class="extra-cell">
							<a href="login.php" class="btn btn-primary btnhover">SIGNIN / SIGNUP</a>	
						</div>
					</div>
							<?php }  ?>

							
							
						</ul>
	
				

			</div>
		</div>
		<!-- Main Header End -->
					
					<!-- Main Nav -->
					<div class="header-nav navbar-collapse collapse justify-content-start" id="navbarNavDropdown">
						<div class="logo-header logo-dark">
							<a href="index.php"><img src="images/logo.png" alt=""></a>
						</div>
						
						<ul class="nav navbar-nav menu">
							<li><a href="index.php"><span>Home</span></a>
							<li><a href="about.php"><span>About</span></a></li>
							<li class="sub-menu-down"><a href="javascript:void(0);"><span>Products</span></a>
								<ul class="sub-menu">
									<li><a href="books.php">Books</a></li>
									<li><a href="magazines.php">Magazines</a></li>
									<li><a href="rentalbooks.php">Rental Books</a></li>
								</ul>
							</li>
							<li><a href="ea_guidance.php"><span>EA Guidance</span></a></li>
							<li><a href="contact.php"><span>Contact</span></a></li>
						</ul>
						<div class="dz-social-icon">
							<ul>
								<li><a class="fab fa-facebook-f" target="_blank" href="https://www.facebook.com/dexignzone"></a></li>
								<li><a class="fab fa-twitter" target="_blank" href="https://twitter.com/dexignzones"></a></li>
								<li><a class="fab fa-linkedin-in" target="_blank" href="https://www.linkedin.com/showcase/3686700/admin/"></a></li>
								<li><a class="fab fa-instagram" target="_blank" href="https://www.instagram.com/website_templates__/"></a></li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- Main Header End -->
		
	</header>
	<!-- Header End -->
