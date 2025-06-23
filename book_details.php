<!DOCTYPE html>

<html lang="en">
<?php
include("header1.php");
include("config.php");

// --- Book ID Fetch ---
$pid = intval($_GET['id'] ?? 0);

if (!$pid) {
    die("⚠️ Error: No book ID provided.");
}

// --- Get Book Details FIRST ---
$stmt = $con->prepare("
    SELECT a.*, b.*, c.* 
    FROM books AS a 
    JOIN category AS b ON a.cat_id = b.category_id 
    JOIN subcategory AS c ON a.subcat_id = c.subcategory_id 
    WHERE a.book_id = ?
");
$stmt->bind_param("i", $pid);
$stmt->execute();
$result1 = $stmt->get_result();

if (!$result1 || mysqli_num_rows($result1) == 0) {
    die("❌ Book not found.");
}

$row1 = mysqli_fetch_array($result1);
$bookTitle  = htmlspecialchars($row1['book']);
$bookAuthor = htmlspecialchars($row1["author"]);
$bookImage = !empty($row1['image']) ? $row1['image'] : 'default-book.jpg';
$imageUrl = "https://www.eadreamsupporters.com/images/books/" . urlencode($bookImage);
$books = $bookTitle;
$owners = $bookAuthor;
?>

<head>
	<!-- BASIC META -->
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
	<!-- SEO META -->
	<meta name="keywords" content="Online book center, Government exam materials rental, book rental, TNPSC, SSC, UPSC, BANKING, career guidance, Youtube videos, magazine subscription, institute courses, test packs, EA Dream Supporters">
	<meta name="description" content="Buy or rent <?php echo $books; ?> by <?php echo $owners; ?> for your government exam preparation. Available at EA Dream Supporters.">

	<meta name="author" content="EA Dream Supporters">
	<meta name="robots" content="index, follow">

	<!-- TITLE -->
    <title><?php echo $books; ?> - EA Dream Supporters</title>

	<!-- FAVICON -->
    <link rel="icon" type="image/x-icon" href="images/favicon.png" />

	<!-- FONTS -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700&family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

	<!-- STYLESHEETS -->
	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="icons/fontawesome/css/all.min.css">
	<link rel="stylesheet" href="vendor/bootstrap-select/dist/css/bootstrap-select.min.css">
	<link rel="stylesheet" href="vendor/swiper/swiper-bundle.min.css">
	<link rel="stylesheet" href="vendor/nouislider/nouislider.min.css">
	
	<!-- OG Meta -->
  <meta property="og:title" content="<?php echo $books; ?> - EA Dream Supporters" />
  <meta property="og:description" content="Buy or rent <?php echo $books; ?> by <?php echo $owners; ?> for government exams." />
  <meta property="og:image" content="<?php echo $bookImage; ?>" />
  <meta property="og:url" content="https://www.eadreamsupporters.com/book_details.php?id=<?php echo $pid; ?>" />
  <meta property="og:type" content="product" />
  <!-- Optional: Enhance -->
  <meta property="og:image:width" content="1200" />
  <meta property="og:image:height" content="630" />

<!-- Twitter Card -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="<?php echo $books; ?>">
<meta name="twitter:description" content="Buy or rent this government exam preparation book.">
<meta name="twitter:image" content="https://www.eadreamsupporters.com/images/books/<?php echo urlencode($row1['image']); ?>">

	
	
	<!-- AI & SEO FRIENDLY META -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Book",
  "name": "<?php echo htmlspecialchars($books); ?>",
  "author": {
    "@type": "Person",
    "name": "<?php echo htmlspecialchars($owners); ?>"
  },
  "image": "https://www.eadreamsupporters.com/images/books/<?php echo urlencode($row1['image']); ?>",
  "publisher": {
    "@type": "Organization",
    "name": "EA Dream Supporters"
  },
  "offers": {
    "@type": "Offer",
    "priceCurrency": "INR",
    "price": "<?php echo floatval($row1['price']); ?>",
    "availability": "https://schema.org/InStock",
    "url": "https://www.eadreamsupporters.com/book_details.php?id=<?php echo $pid; ?>"
  }
}
</script>

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

// --- Handle Add to Cart (Buy Now) ---
if (isset($_POST['addtocart'])) {
    if (empty($_SESSION['id'])) {
        echo "<script>alert('Please login to buy this book');</script>";
        echo "<script type='text/javascript'>document.location = 'login.php';</script>";
        exit;
    } else {
        $userid = $_SESSION['id'];
        $pqty = intval($_POST['inputQuantity']);

        $query = mysqli_query($con, "SELECT id, productQty FROM cart WHERE userId='$userid' AND productId='$pid'");
        if (mysqli_num_rows($query) == 0) {
            mysqli_query($con, "INSERT INTO cart(userId, productId, productQty) VALUES('$userid', '$pid', '$pqty')");
        } else {
            $row = mysqli_fetch_array($query);
            $newQty = $pqty + intval($row['productQty']);
            mysqli_query($con, "UPDATE cart SET productQty='$newQty' WHERE userId='$userid' AND productId='$pid'");
        }
        echo "<script type='text/javascript'>document.location = 'cart';</script>";
        exit;
    }
}

// --- Handle Add to Cart (Rent or Alternate Add) ---
if (isset($_POST['addtocart1'])) {
    if (empty($_SESSION['id'])) {
        echo "<script>alert('Please login to rent this book');</script>";
        echo "<script type='text/javascript'>document.location = 'login.php';</script>";
        exit;
    } else {
        echo "<script>alert('Book Added!');</script>";
        $userid = $_SESSION['id'];
        $pqty = intval($_POST['inputQuantity']);

        $query = mysqli_query($con, "SELECT id, productQty FROM cart WHERE userId='$userid' AND productId='$pid'");
        if (mysqli_num_rows($query) == 0) {
            mysqli_query($con, "INSERT INTO cart(userId, productId, productQty) VALUES('$userid', '$pid', '$pqty')");
        } else {
            $row = mysqli_fetch_array($query);
            $newQty = $pqty + intval($row['productQty']);
            mysqli_query($con, "UPDATE cart SET productQty='$newQty' WHERE userId='$userid' AND productId='$pid'");
        }
        echo "<script type='text/javascript'>document.location = 'book_details.php?id=$pid';</script>";
        exit;
    }
}

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

			.addcart {
				margin-right: 20px;
			}

			#term {
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
										<img src="images/books/<?php echo $row1['image']; ?>" alt="book">
									</div>
									<div class="dz-content">
										<div class="dz-header">
											<h3 class="title"><?php echo $row1['book']; ?></h3>
											<div class="shop-item-rating">
												<div class="d-lg-flex d-sm-inline-flex d-flex align-items-center">
													<div class="social-area">
														<ul class="dz-social-icon style-3 share-btn">
															<li><a href="" class="fb-btn" target="_blank"><i
																		class="fa-brands fa-facebook-f"></i></a></li>
															<li><a href="" class="tw-btn" target="_blank"><i
																		class="fa-brands fa-twitter"></i></a></li>
															<li><a href="" class="wa-btn" target="_blank"><i
																		class="fa-brands fa-whatsapp"></i></a></li>
															<li><a href="" class="li-btn" target="_blank"><i
																		class="fa-brands fa-linkedin"></i></a></li>
														</ul>
													</div>
												</div>

											</div>
										</div>

										<div class="dz-body">
											<div class="book-detail">
												<ul class="book-info">
													<li>
														<div class="writer-info">

															<div>
																<span>Writen by</span><?php echo $row1['author']; ?>
															</div>
														</div>
													</li>
													<li><span>Category</span><?php echo $row1['category']; ?></li>
													<li><span>Subcategory</span><?php echo $row1['subcategory']; ?></li>
												</ul>
											</div>
											<div class="text-1"><?php echo $row1['description']; ?></div>
											<div class="book-footer">

												<div class="price">
													<h5>₹ <?php echo $row1['price']; ?></h5>

													<del style="margin-left:10px;">₹ <?php echo $row1['mrp']; ?></del>
												</div>

											</div>
											<div class="book-footer">

												<div class="product-num">
													<input class="form-control text-center me-3" id="inputQuantity"
														name="inputQuantity" min="1" max="3" type="number" value="1" />


												</div>


											</div>
											<div class="product-num">
												<p id="term">*Delivery Charges Applicable*</p>
											</div>
											<div class="book-footer">

												<div class="product-num">
													<button class="btn btn-secondary btnhover2 addcart" type="submit"
														name="addtocart1">
														<i class="bi-cart-fill me-1"></i>
														Add to Cart
													</button>


													<?php

													if (strlen($_SESSION['id']) == 0) {
														?>

														<div><a class="btn btn-secondary btnhover2" href=""
																data-bs-toggle="modal" data-bs-target="#myModal">
																<i aria-hidden="true" style="padding-right: 5px;"></i>

																Buy Now
															</a></div>

														<?php


													} else {

														?>

														<button class="btn btn-secondary btnhover2 " type="submit"
															name="addtocart">
															<i class="bi-cart-fill me-1"></i>
															Buy Now
														</button>

														<?php


													}

													?>
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



			<!-- Feature Box -->
			<?php include 'count.php'; ?>->

		</div>

		<!-- Footer -->
		<?php include 'footer-white.php'; ?>
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
		const fbBtn = document.querySelector(".fb-btn");
		const waBtn = document.querySelector(".wa-btn");
		const twBtn = document.querySelector(".tw-btn");
		const liBtn = document.querySelector(".li-btn");

		const pageUrl = location.href
		const message = "hi every one ,please check  out"


		function init() {

			let postUrl = encodeURI(document.location.href);
			let postTitle = encodeURI("hi every one ,please check this out: ");

			fbBtn.setAttribute("href", `https://www.facebook.com/sharer.php?u=${postUrl}`);
			waBtn.setAttribute("href", `https://api.whatsapp.com/send?text=${message} , ${pageUrl}`);
			twBtn.setAttribute("href", `https://twitter.com/share?url=${postUrl}&text=${postTitle}&via=[via]&hashtags=[hashtags]`);
			liBtn.setAttribute("href", `https://www.linkedin.com/shareArticle?url=${postUrl}&title=${postTitle}`);
		}
		init();
	</script>
</body>

</html>