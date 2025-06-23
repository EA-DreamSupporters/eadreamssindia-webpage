<?php

include("header1.php");
?>
<?php
$id=$_GET["id"];
$result = mysqli_query($con,"SELECT * FROM category WHERE category_id='$id'");
$row = mysqli_fetch_array($result);

?>
	<style>

.overlay-secondary-light:before, .overlay-secondary-middle:before, .overlay-secondary-dark:before {
    background: initial;
}

</style>
			<div class="page-content">
			<!-- inner page banner -->
			<div class="dz-bnr-inr overlay-secondary-dark dz-bnr-inr-sm" style="background-image:url(images/bg.jpg);">
				<div class="container">
					<div class="dz-bnr-inr-entry">
						<h1><?php echo $row['category']; ?></h1>
						<nav aria-label="breadcrumb" class="breadcrumb-row">
							<ul class="breadcrumb">
								<li class="breadcrumb-item"><a href="index.php"> Home</a></li>
								<li class="breadcrumb-item"><?php echo $row['category']; ?></li>
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
							  $result1 = mysqli_query($con,"SELECT a.*,b.* FROM books AS a,category AS b WHERE a.cat_id=b.category_id AND b.category_id='$id'");
							  while($row1 = mysqli_fetch_array($result1))
							  {
							  ?>
					<div class="col-book style-1">
						<div class="dz-shop-card style-1">
							<div class="dz-media">
								<img src="images/<?php echo $row1['image']; ?>" alt="book">										
							</div>
							<div class="dz-content">
								<h5 class="title"><a href="books-grid-view.html"><?php echo $row1['book']; ?></a></h5>
								<ul class="dz-tags">
									<li><a href="books-grid-view.html">HORROR,</a></li>
									<li><a href="books-grid-view.html">DRAMA</a></li>
								</ul>
								<ul class="dz-rating">
									<li><i class="flaticon-star text-yellow"></i></li>	
									<li><i class="flaticon-star text-yellow"></i></li>	
									<li><i class="flaticon-star text-yellow"></i></li>	
									<li><i class="flaticon-star text-yellow"></i></li>		
									<li><i class="flaticon-star text-yellow"></i></li>		
								</ul>
								<div class="book-footer">
									<div class="price">
										<span class="price-num">₹ <?php echo $row1['price']; ?></span>
										
									</div>
									<a href="shop-cart.html" class="btn btn-secondary box-btn btnhover2"><i class="flaticon-shopping-cart-1 m-r10"></i> Add to cart</a>
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
						<p class="page-text">Showing 12 from 50 data</p>
					</div>
					<div class="col-md-6">
						<nav aria-label="Blog Pagination">
							<ul class="pagination style-1 p-t20">
								<li class="page-item"><a class="page-link prev" href="javascript:void(0);">Prev</a></li>
								<li class="page-item"><a class="page-link active" href="javascript:void(0);">1</a></li>
								<li class="page-item"><a class="page-link" href="javascript:void(0);">2</a></li>
								<li class="page-item"><a class="page-link" href="javascript:void(0);">3</a></li>
								<li class="page-item"><a class="page-link next" href="javascript:void(0);">Next</a></li>
							</ul>
						</nav>
					</div>
				</div>
			</div>
		</section>
		
		
		

		
	</div>
	
<?php
include("footer1.php");
?>