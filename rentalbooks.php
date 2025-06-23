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
	<title>Rent A Book - EA Dream Supporters</title>
	
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
<style>


</style>
			<div class="page-content">
			<!-- inner page banner -->
				<div class="dz-bnr-inr overlay-secondary-dark dz-bnr-inr-sm" style="background-image:url(images/bg.jpg);"><div class="container">
					<div class="dz-bnr-inr-entry">
						<h1>Rental Books</h1>
						<nav aria-label="breadcrumb" class="breadcrumb-row">
							<ul class="breadcrumb">
								<li class="breadcrumb-item"><a href="index.php"> Home</a></li>
								<li class="breadcrumb-item">Rental Books</li>
							</ul>
						</nav>
					</div>
				</div>
			</div>
			<!-- inner page banner End-->
	<div class="page-content bg-grey">
		<section class="content-inner border-bottom">
			<div class="container">
				
				
				<div class="row book-grid-row">
				    <?php  
  
    
        $per_page_record = 16;  // Number of entries to show in a page.   
        // Look for a GET variable page if not found default is 1.        
        if (isset($_GET["page"])) {    
            $page  = $_GET["page"];    
        }    
        else {    
          $page=1;    
        }    
    
        $start_from = ($page-1) * $per_page_record;     
    
        $query = "SELECT * FROM rentalbooks LIMIT $start_from, $per_page_record";     
        $rs_result = mysqli_query ($con, $query);    
    ?> 
					<?php
							  $result1 = mysqli_query($con,"SELECT a.*,b.* FROM rentalbooks AS a,category AS b WHERE a.rcat_id = b.category_id");
							  $count= mysqli_num_rows($result1);
							  while($row1 = mysqli_fetch_array($result1))
							  {
							  ?>
					<div class="col-book style-1">
						<div class="dz-shop-card style-1">
							<div class="dz-media">
							<a href="rentalbook_details.php?id=<?php echo $row1['rid']; ?>">	<img src="images/<?php echo $row1['rimage']; ?>" alt="book"></a>										
							</div>
							<div class="dz-content">
									<h5 class="title"><a href="rentalbook_details.php?id=<?php echo $row1['rid']; ?>"><?php echo $row1['rbook']; ?></a></h5>
								<ul class="dz-tags">
									
									<li><a href=""><?php echo $row1['category']; ?></a></li>
								</ul>
								
								<div class="book-footer">
									
									<a href="rentalbook_details.php?id=<?php echo $row1['rid']; ?>" class="btn btn-secondary box-btn btnhover2"><i class="flaticon-shopping-cart-1 m-r10"></i> View Details</a>
								</div>
							</div>
						</div>
					</div>
				<?php
							  }
							  ?>
				</div>
		 
				<div class="row page mt-0">
					<div class="col-md-6">
						<p class="page-text">Showing <?php if($per_page_record > $count){ echo $count; }else{  echo $per_page_record;  } ?> from <?php echo $count; ?> Books</p>
					</div>
					<div class="col-md-6">
						<nav aria-label="Blog Pagination">
							<ul class="pagination style-1 p-t20">
							<?php
							$query = "SELECT COUNT(*) FROM rentalbooks";     
							$rs_result = mysqli_query($con, $query);     
							$row = mysqli_fetch_row($rs_result);     
							$total_records = $row[0];     
							  
						    echo "</br>";     
							// Number of pages required.   
							$total_pages = ceil($total_records / $per_page_record);     
							$pagLink = "";    
							if($page>=2){   
							echo "<li class='page-item'><a class='page-link prev' href='rentalbooks.php?page=".($page-1)."'>Prev</a></li>";   
							}  
							for ($i=1; $i<=$total_pages; $i++) {   
							if ($i == $page) {   
							$pagLink .= "<li class='page-item'><a class = 'page-link active' href='rentalbooks.php?page="  
                                                .$i."'>".$i." </a></li>";   
							}               
							else  {   
							$pagLink .= "<li class='page-item'><a class = 'page-link' href='rentalbooks.php?page=".$i."'>   
																	".$i." </a></li>";     
							}   
							};     
							echo $pagLink;   
					  
							if($page<$total_pages){   
								echo "<li class='page-item'><a class = 'page-link' href='rentalbooks.php?page=".($page+1)."'>  Next </a></li>";   
							}   
							?>
								</ul>
								      
						</nav>
					</div>
				<!--<div class="col-md-2">
				<div class="inline">   
      <input id="page" type="number" min="1" max="<?php //echo $total_pages?>"   
      placeholder="<?php //echo $page."/".$total_pages; ?>" required>   
      <button class='btn btn-secondary' onClick="go2Page();">Go</button>   
     </div>
				</div>-->
				</div>
			</div>
		</section>
		
		
		

		
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

  