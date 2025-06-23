<?php 
include('config.php');
{
?>
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
	<title>Buy A Book - EA Dream Supporters</title>
	
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
	<!-- Header -->
	<?php include'header1.php'; ?>
	<!-- Header End -->
	<div class="page-content bg-grey">
		<div class="content-inner border-bottom">
			<div class="container">
				<div class="row">
					<div class="col-xl-3">
						<div class="shop-filter">
							<div class="d-flex justify-content-between">
								<h4 class="title">Filter Option</h4>
								<a href="javascript:void(0);" class="panel-close-btn"><i class="flaticon-close"></i></a>
							</div>
							<div class="accordion accordion-filter" id="accordionExample">
								
								
													<div class="accordion-item">
								  <button class="accordion-button" id="headingOne" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">Shop by Category</button>
									<div id="collapseOne" class="accordion-collapse collapse show accordion-body" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
										<div class="widget dz-widget_services d-flex justify-content-between">
											<div class="">
											<?php
$ret=mysqli_query($con,"select* from  category");
$cnt=1;
while ($row=mysqli_fetch_array($ret)) {

?>
												
													<div class="form-check search-content">
											
													<a href="catwisebook.php?catid=<?php  echo $row['category_id'];?>"><input class="form-check-input" type="checkbox"  id="productCheckBox-01">
													<label class="form-check-label" for="productCheckBox-01">
														<?php  echo $row['category'];?> </label></a>
													
													 
												</div>
												<?php 
$cnt=$cnt+1;
}?>
											</div>
											
										</div>
									</div>
								</div>
								<div class="accordion-item">
								  <button class="accordion-button" id="heading2" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2" aria-expanded="true" aria-controls="collapse2">Shop by Sub-Category</button>
									<div id="collapse2" class="accordion-collapse collapse show accordion-body" aria-labelledby="heading2" data-bs-parent="#accordionExample">
										<div class="widget dz-widget_services d-flex justify-content-between">
											<div class="">
											<?php
$ret=mysqli_query($con,"select * from  subcategory");
$cnt=1;
while ($row=mysqli_fetch_array($ret)) {

?>
												<div class="form-check search-content">
													<a href="subcatwisebook.php?catid=<?php  echo $row['subcategory_id'];?>"><input class="form-check-input" type="checkbox" value="" id="productCheckBox-01">
													<label class="form-check-label" for="productCheckBox-01">
														<?php  echo $row['subcategory'];?>
													</label></a>
												</div>
											<?php 
$cnt=$cnt+1;
}?>
											</div>
											
										</div>
									</div>
								</div>
							<div class="accordion-item">
								  <button class="accordion-button" id="heading3" type="button" data-bs-toggle="collapse" data-bs-target="#collapse3" aria-expanded="true" aria-controls="collapse3">Shop by Pricing</button>
									<div id="collapse3" class="accordion-collapse collapse show accordion-body" aria-labelledby="heading3" data-bs-parent="#accordionExample">
										<div class="widget dz-widget_services d-flex justify-content-between">
											<div class="">
											
												<div class="form-check search-content">
													<a href="pricewise.php"><input class="form-check-input" type="checkbox" value="" id="productCheckBox-01">
													<label class="form-check-label" for="productCheckBox-01">
														Low to High
													</label></a>
												</div>
										
											</div>
											<div class="">
											
												<div class="form-check search-content">
													<a href="pricewisedesc.php"><input class="form-check-input" type="checkbox" value="" id="productCheckBox-01">
													<label class="form-check-label" for="productCheckBox-01">
														Hign to Low
													</label></a>
												</div>
										
											</div>
										</div>
									</div>
								</div>
								
								<div class="accordion-item">
									<button class="accordion-button collapsed" id="headingTwo" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
									Choose Publisher
									</button>
									<div id="collapseTwo" class="accordion-collapse collapse accordion-body" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
										<div class="widget dz-widget_services">
										<?php
$ret=mysqli_query($con,"select distinct author from  books");
$cnt=1;
while ($row=mysqli_fetch_array($ret)) {

?>
											<div class="form-check search-content">
													<a href="publisherwise.php?catid=<?php  echo $row['author'];?>"><input class="form-check-input" type="checkbox" value="" id="productCheckBox15">
												<label class="form-check-label" for="productCheckBox15">
													<?php  echo $row['author'];?>
												</label></a>
											</div>
											<?php 
$cnt=$cnt+1;
}?>
										</div>
									</div>
								</div>
								
							
							</div>
							<div class="row filter-buttons">
							
							</div>
						</div>
					</div>
					<div class="col-xl-9">
						<div class="d-flex justify-content-between align-items-center">
							<h4 class="title">Books</h4>
							<a href="javascript:void(0);" class="btn btn-primary panel-btn">Filter</a>
						</div>
						<div class="filter-area m-b30">
							
							<div class="category">
								<div class="filter-category">
									<a data-bs-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
										<i class="fas fa-list me-2"></i>
										Categories
									</a>
								</div>
								
							</div>
							<div class="category">
								<div class="filter-category">
									<a data-bs-toggle="collapse" href="#collapse" role="button" aria-expanded="false" aria-controls="collapseExample">
										<i class="fas fa-list me-2"></i>
										Authors
									</a>
								</div>
								
							</div>
						</div>
						<div class="acod-content collapse " id="collapseExample">
							<div class="widget widget_services style-2">
							<?php
$ret=mysqli_query($con,"select *from  category");
$cnt=1;
while ($row1=mysqli_fetch_array($ret)) {

?>
								<div class="form-check search-content">
									<a href="catwisebook.php?catid=<?php  echo $row1['category_id'];?>"><input class="form-check-input" type="checkbox" value="" id="productCheckBox01">
									<label class="form-check-label" for="productCheckBox01">
										<?php  echo $row1['category'];?>
									</label></a>
								</div>
								<?php 
$cnt=$cnt+1;
}?>
							</div>
						</div>	
	<div class="acod-content collapse " id="collapse">
							<div class="widget dz-widget_services">
										<?php
$ret=mysqli_query($con,"select distinct author from  books");
$cnt=1;
while ($row=mysqli_fetch_array($ret)) {

?>
											<div class="form-check search-content">
													<a href="publisherwise.php?catid=<?php  echo $row['author'];?>"><input class="form-check-input" type="checkbox" value="" id="productCheckBox15">
												<label class="form-check-label" for="productCheckBox15">
													<?php  echo $row['author'];?>
												</label></a>
											</div>
											<?php 
$cnt=$cnt+1;
}?>
										</div>
						</div>
						<div class="row book-grid-row">
						<?php  
      
    // Import the file where we defined the connection to Database.     
        require_once "config.php";   
    
        $per_page_record = 50;  // Number of entries to show in a page.   
        // Look for a GET variable page if not found default is 1.        
        if (isset($_GET["page"])) {    
            $page  = $_GET["page"];    
        }    
        else {    
          $page=1;    
        }    
    
        $start_from = ($page-1) * $per_page_record;     
    
        $query = "SELECT * FROM books LIMIT $start_from, $per_page_record";     
        $rs_result = mysqli_query ($con, $query);    
    ?>
						<?php
$ret=mysqli_query($con,"SELECT a.*,b.* FROM books AS a,category AS b WHERE a.cat_id = b.category_id order by price ASC");
 $count= mysqli_num_rows($ret);
$cnt=1;
while ($row=mysqli_fetch_array($ret)) {

?>
							<div class="col-book style-2 line-content">
								<div class="dz-shop-card style-1">
									<div class="dz-media">
										<a href="book_details.php?id=<?php echo $row['book_id']; ?>"><img src="images/<?php echo $row['image']; ?>">	</a>					
									</div>
									<div class="bookmark-btn style-2">
										<input class="form-check-input" type="checkbox" id="flexCheckDefault1">
										
									</div>
									<div class="dz-content">
										<h5 class="title"><a href="book_details.php?id=<?php echo $row['book_id']; ?>"><?php $val=$row['book']; $name = substr($val, 0, 25); echo $name;?>...</a></h5>
										<ul class="dz-tags">
									
											
										</ul>
										<ul class="dz-rating">
												<span class="price-num">₹<?php  echo $row['price'];?></span>
										</ul>
										<div class="book-footer">
											<div class="price">
												<span class="price-num">₹<?php  echo $row['price'];?></span>
												<del>₹ <?php echo $row['mrp']; ?></del>
											</div>
										<a href="book_details.php?id=<?php echo $row['book_id']; ?>" class="btn btn-secondary box-btn btnhover2"> View Details</a>
										</div>
									</div>
								</div>
							</div>
<?php 
$cnt=$cnt+1;
}?>
						</div>
							<div class="row page mt-0">
				
					<div class="col-md-6">
						<nav aria-label="Blog Pagination">
					<ul id="pagin" class="pagination style-1 p-t20">
							<li><a class="current" href="#"></a></li>
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
				</div>
			</div>
		</div>
		
		<!-- Client Start-->
		
		<!-- Client End-->
		
		<!-- Feature Box -->
<?php include'count.php' ; ?>
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
//Pagination


	pageSize = 6;

	var pageCount =  $(".line-content").length / pageSize;
    
     for(var i = 0 ; i<pageCount;i++){
        
       $("#pagin").append('<li class="page-item"><a class = "page-link active"  href="#">'+(i+1)+'</a></li> ');
     }
        $("#pagin li").first().find("a").addClass("current")
    showPage = function(page) {
	    $(".line-content").hide();
	    $(".line-content").each(function(n) {
	        if (n >= pageSize * (page - 1) && n < pageSize * page)
	            $(this).show();
	    });        
	}
    
	showPage(1);

	$("#pagin li a").click(function() {
	    $("#pagin li a").removeClass("current");
	    $(this).addClass("current");
	    showPage(parseInt($(this).text())) 
	});
	</script>
</body>
</html>
<?php }?>