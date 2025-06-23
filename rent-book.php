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
<style>
   .swiper-container{
   height:px;
   }
   
    .swiper-slide img {
      width: 100%;
      height: 300px;
    }
	body
	{
	margin:0px;
	}
	.swiper-pagination {
      bottom: 10px;
    }

    .swiper-pagination-bullet {
      background-color: #ddd;
    }

    .swiper-pagination-bullet-active {
      background-color: White;
    }
    
    .swiper-pagination-bullet:hover {
      background-color: blue;
  </style>
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
                <div class="form-check search-content">
                    <input type="radio" name="category" value="all" class="filter-option" id="category-all">
                    <label for="category-all">All</label>
                </div>
                <?php
                $ret = mysqli_query($con, "select * from category");
                while ($row = mysqli_fetch_array($ret)) {
                ?>
                    <div class="form-check search-content">
                        <input type="radio" name="category" value="<?php echo $row['category_id']; ?>" class="filter-option" id="category-<?php echo $row['category_id']; ?>">
                        <label for="category-<?php echo $row['category_id']; ?>"><?php echo $row['category']; ?></label>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>


<div class="accordion-item">
    <button class="accordion-button" id="heading2" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2" aria-expanded="true" aria-controls="collapse2">Shop by Sub-Category</button>
    <div id="collapse2" class="accordion-collapse collapse show accordion-body" aria-labelledby="heading2" data-bs-parent="#accordionExample">
        <div class="widget dz-widget_services d-flex justify-content-between">
            <div class="">
                <div class="form-check search-content">
                    <input type="radio" name="subcategory" value="all" class="filter-option" id="subcategory-all">
                    <label for="subcategory-all">All</label>
                </div>
                <?php
                $ret = mysqli_query($con, "select * from subcategory");
                while ($row = mysqli_fetch_array($ret)) {
                ?>
                    <div class="form-check search-content">
                        <input type="radio" name="subcategory" value="<?php echo $row['subcategory_id']; ?>" class="filter-option" id="subcategory-<?php echo $row['subcategory_id']; ?>">
                        <label for="subcategory-<?php echo $row['subcategory_id']; ?>"><?php echo $row['subcategory']; ?></label>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>


<div class="accordion-item">
    <button class="accordion-button" id="heading3" type="button" data-bs-toggle="collapse" data-bs-target="#collapse3" aria-expanded="true" aria-controls="collapse3">Shop by Author</button>
    <div id="collapse3" class="accordion-collapse collapse show accordion-body" aria-labelledby="heading3" data-bs-parent="#accordionExample">
        <div class="widget dz-widget_services">
            <div class="form-check search-content">
                <input type="radio" name="author" value="all" class="filter-option" id="author-all">
                <label for="author-all">All</label>
            </div>
            <?php
            $ret = mysqli_query($con, "select distinct author from books");
            while ($row1 = mysqli_fetch_array($ret)) {
            ?>
                <div class="form-check search-content">
                    <input type="radio" name="author" value="<?php echo $row1['author']; ?>" class="filter-option" id="author-<?php echo $row1['author']; ?>">
                    <label for="author-<?php echo $row1['author']; ?>"><?php echo $row1['author']; ?></label>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
								<div class="accordion-item">
									<div class="swiper-container" id="swiper-container">
    <div class="swiper-wrapper">
	<?php
$ret=mysqli_query($con,"SELECT * FROM tblbanner where position='side1'");
$cnt=1;
	  $ct= mysqli_num_rows($ret);
while ($row1=mysqli_fetch_array($ret)) {

?>
      <div class="swiper-slide"><img src="admin/image/<?php echo $row1['banner']; ?>"></div>
      <?php
	  
}?>
    </div>
    <div class="swiper-pagination" id="banner"></div>
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
							
							<div class="swiper-container" id="swiper-container">
    <div class="swiper-wrapper">
	<?php
$ret=mysqli_query($con,"SELECT * FROM tblbanner where position='rbook'");
$cnt=1;
	  $ct= mysqli_num_rows($ret);
while ($row1=mysqli_fetch_array($ret)) {

?>
      <div class="swiper-slide"><img src="admin/image/<?php echo $row1['banner']; ?>"></div>
      <?php
	  
}?>
    </div>
    <div class="swiper-pagination" id="banner"></div>
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
									<a href="rcatwise.php?catid=<?php  echo $row1['category_id'];?>"><input class="form-check-input" type="checkbox" value="" id="productCheckBox01">
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
							<div class="widget widget_services style-2">
							<?php
$ret=mysqli_query($con,"select distinct rauthor from rentalbooks");
$cnt=1;
while ($row1=mysqli_fetch_array($ret)) {

?>
								<div class="form-check search-content">
									<a href="rpubwise.php?catid=<?php  echo $row1['rauthor'];?>"><input class="form-check-input" type="checkbox" value="" id="productCheckBox01">
									<label class="form-check-label" for="productCheckBox01">
										<?php  echo $row1['rauthor'];?>
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
    
        $query = "SELECT * FROM rentalbooks LIMIT $start_from, $per_page_record";     
        $rs_result = mysqli_query ($con, $query); 
$bok=mysqli_query($con,"SELECT * FROM rentalbooks ");
		 $tot= mysqli_num_rows($bok);		
    ?>
						<?php
$ret=mysqli_query($con,"SELECT a.*,b.* FROM rentalbooks As a,category As b where a.rcat_id=b.category_id LIMIT $start_from, $per_page_record");
 $count= mysqli_num_rows($ret);
$cnt=1;
while ($row=mysqli_fetch_array($ret)) {

?>
							<div class="col-book style-2">
								<div class="dz-shop-card style-1">
									<div class="dz-media">
										<a href="rentalbook_details.php?id=<?php echo $row['rid']; ?>"><img src="images/<?php echo $row['rimage']; ?>">	</a>					
									</div>
								
									<div class="dz-content">
										<h5 class="title"><a href="rentalbook_details.php?id=<?php echo $row['rid']; ?>"><?php  echo $row['rbook'];?></a></h5>
										<ul class="dz-tags">
											<li><a href=""><?php  echo $row['category'];?></a></li>
										</ul>
										
										<div class="book-footer">
											<a href="rentalbook_details.php?id=<?php echo $row['rid']; ?>" class="btn btn-secondary box-btn btnhover2"> View Details</a>
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
						<p class="page-text">Showing <?php
            // Determine the number of books displayed on this page
            $end_count = $start_from + $per_page_record;
            if ($end_count > $count) {
                $end_count = $count;
            }
            echo $start_from + 1 . " to " . $end_count . " of " . $count . " Books";
            ?></p>
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
							echo "<li class='page-item'><a class='page-link prev' href='rent-book.php?page=".($page-1)."'>Prev</a></li>";   
							}  
							for ($i=1; $i<=$total_pages; $i++) {   
							if ($i == $page) {   
							$pagLink .= "<li class='page-item'><a class = 'page-link active' href='rent-book.php?page="  
                                                .$i."'>".$i." </a></li>";   
							}               
							else  {   
							$pagLink .= "<li class='page-item'><a class = 'page-link' href='rent-book.php?page=".$i."'>   
																	".$i." </a></li>";     
							}   
							};     
							echo $pagLink;   
					  
							if($page<$total_pages){   
								echo "<li class='page-item'><a class = 'page-link' href='rent-book.php?page=".($page+1)."'>  Next </a></li>";   
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
				</div>
			</div>
		</div>
		
		<!-- Client Start-->
		
		<!-- Client Start-->

		<!-- Client End-->
		
		<!-- Feature Box -->
	
		<!-- Feature Box End -->
		
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
$(document).ready(function() {
    // When a filter option changes (category, subcategory, author)
    $('.filter-option').on('change', function() {
        var category = $('input[name="category"]:checked').val();
        var subcategory = $('input[name="subcategory"]:checked').val();
        var author = $('input[name="author"]:checked').val();

        // Send AJAX request to fetch filtered books
        $.ajax({
            url: 'filter_rental.php', // PHP file to handle the filtering logic
            type: 'GET',
            data: {
                category: category === 'all' ? '' : category, // If "all" is selected, send an empty string to show all
                subcategory: subcategory === 'all' ? '' : subcategory, // Same for subcategory and author
                author: author === 'all' ? '' : author
            },
            success: function(response) {
                // Update the book grid with the filtered results
                $('.book-grid-row').html(response);
            }
        });
    });
});

</script>
<script>
    var swiper = new Swiper('#swiper-container', {
      pagination: {
        el: '#banner',
        clickable: true,
		
      },
	  autoplay: {
      delay: 3000, // Change delay value to adjust slide speed
      disableOnInteraction: false, // Set to false to continue autoplay even when user interacts with the slider
    },
    });
  </script>
</body>
</html>
<?php }?>