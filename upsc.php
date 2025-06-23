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
	<title>UPSC - EA Dream Supporters</title>
	
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
	
	<style>
	#reg{
	  font-size: 20px;
white-space: nowrap;
padding: 17px 90px;
	  
  }
	</style>
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
	
	<div class="page-content">
		<!-- inner page banner -->
		<div class="dz-bnr-inr overlay-secondary-dark dz-bnr-inr-sm" style="background-image:url(images/background/bg3.jpg);">
			<div class="container">
				<div class="dz-bnr-inr-entry">
					<h1>Union Public Service Commission </h1>
					<nav aria-label="breadcrumb" class="breadcrumb-row">
						<ul class="breadcrumb">
							<li class="breadcrumb-item"><a href="index.php"> Home</a></li>
							<li class="breadcrumb-item">UPSC</li>
						</ul>
					</nav>
				</div>
			</div>
		</div>
		<!-- inner page banner End-->
		<!-- Blog Large -->
		<section class="content-inner-1 bg-img-fix">
			<div class="container">
				<div class="row">
					<div class="col-xl-8 col-lg-8">
						<!-- blog start -->
						<div class="dz-blog blog-single style-1">
							<div class="dz-media rounded-md">
								<img src="images/blog/default/blog1.jpg" alt="">
							</div>
							<div class="dz-info">
							
								<h4 class="dz-title">About UPSC</h4>
								<div class="dz-post-text">
									<p>Union Public Service Commission is how UPSC is officially referred to. The Civil Services Exam (CSE) is administered by UPSC, India's central government body, to select applicants for prestigious government positions. If a top central government position like IAS or IPS is your ideal profession, you should surely be familiar with UPSC.</p>
									<p>The Union Public Service Commission (UPSC) is the hiring organisation for senior positions in the central government. Typically, UPSC only hires for Group-A and a small number of Group-B level bureaucratic positions. Other agencies/commissions. government services including IAS, IPS, and IFS, among others, conduct recruiting for Group C and Group D. Candidates are sought after by UPSC for both civil and military services.</p>
									
									
									<h4 class="m-b30">The UPSC Civil Services Exam Syllabus Is As Follows,</h4>
									
									<p>The UPSC Civil Services Exam consists of three stages: the Preliminary Exam (Objective Test), the Main Exam (Written Test), and the Personality Test (Interview). </p>
											<h5 class="m-b30">1. Preliminary Exam</h5>
									<p>Civil services are made up of The preparatory test consists of the General Studies Papers I and II, each earning 200 points. Multiple-choice, unbiased questions will be present. Preliminaries will only be used to qualify for the main exam; final rankings will not be determined by them.</p>
									<h5 class="m-b30">2. Written Exam</h5>
									<p>Nine papers will make up the written test (main), although only seven of those papers will be used to determine the final merit ranking. The candidate must obtain the minimum scores required by the commission each year for the other two papers.</p>
									<h5 class="m-b30">3.Personality Test</h5>
									<p>A Board that will have a record of the candidate's career in front of them will interview the applicant. He or she will be questioned on topics of broad interest. The Preliminary Exam's scores are not factored towards the final ranking. Candidates are chosen for the Main Exam through a screening process called Preliminary. The main exam is for 1750 points, while the interview is worth 275 points. Total marks are computed out of 2025. Having said that, passing the preliminary exam without adequate preparation is difficult. The Union Public Service Commission only invites the top 3 applicants out of 100 to take the Main test.</p>
								</div>							
							</div>
						</div>
						<h4 class="m-b30">Approaches To Preparing</h4>
										<ul class="list-check primary m-b30">
								<li>Practice the mock tests and previous year's questions for the UPSC.</li>
								<li>Make notes while you read the newspaper.</li>
								<li>For the UPSC Mains test, practise answering questions every day.</li>
								<li>Select an optional subject for the Mains exam and do well in it.</li>
								<li>Read the NCERT and other required materials.</li>
								<li>Get ready for your interview</li>
								
							</ul>
						<!-- blog END -->
						
					</div>
					
					<div class="col-xl-4 col-lg-4">
						<aside class="side-bar sticky-top">
							
						
									<div class="widget recent-posts-entry">
								<h4 class="widget-title">Top Book Recommendations </h4>
								<div class="widget-post-bx">
								<?php
							  $result = mysqli_query($con,"SELECT a.*,b.* FROM books AS a, category AS b WHERE a.cat_id=b.category_id AND b.category='UPSC' Order by time DESC LIMIT 5");
							  $num=mysqli_num_rows($result);
							  if($num>0)
							  {
							  while($row = mysqli_fetch_array($result))
							  {
							  ?>
									<div class="widget-post clearfix">
										<div class="dz-media"> 
											<a href="book_details.php?id=<?php echo $row['book_id']; ?>"><img src="images/<?php echo $row['image']; ?>" alt="book"></a>
										</div>
										<div class="dz-info">
											<h6 class="title"><a href="book_details.php?id=<?php echo $row['book_id']; ?>"><?php echo $row['book']; ?></a></h6>
											<div class="dz-meta">
												<ul>
													<li class="post-date">₹ <?php echo $row['price']; ?></li>
												</ul>
											</div>
										</div>
									</div>
									<?php
							  }} else{?>
								  <div class="dz-info">
											<h6 class="title">Not Updated Yet </h6>
										</div>
								  
							 <?php  }
							  ?>
								</div>
							</div>
							<div class="widget widget widget_categories">
								<h4 class="widget-title">Other  Related  Books</h4>
								<ul>
								<?php
							  $result = mysqli_query($con,"SELECT a.*,b.* FROM books AS a, category AS b WHERE a.cat_id=b.category_id AND b.category='UPSC' Order by time DESC LIMIT 5");
							   $num=mysqli_num_rows($result);
							  if($num>0)
								   {
							  while($row = mysqli_fetch_array($result))
								  
							  {
							  ?>
									<li><a href="javascript:void(0);"><a href="book_details.php?id=<?php echo $row['book_id']; ?>"><?php echo $row['book']; ?></a> – by <?php echo $row['author']; ?></a></li>
									
						<?php
							  }} else{?>
								  <div class="dz-info">
											<h6 class="title">Not Updated Yet </h6>
										</div>
								  
							 <?php  }
							  ?>
								
								</ul>
							</div>
							<div class="widget widget_tag_cloud">
								<h4 class="widget-title">Other Exams</h4>
								<div class="tagcloud"> 
									<a href="ssc.php">SSC</a>
									<a href="tnpsc.php">TNPSC</a>
									<a href="banking.php">BANKING</a>
								
								</div>
							</div>
						</aside>
					</div>
					
				</div>
			</div>
		</section>
		
		<!-- Feature Box -->
	</div>
		<?php include'poster.php' ; ?>
	<!-- Footer -->
<?php include'footer-white.php'; ?>
	<!-- Footer End -->
	
	<button class="scroltop" type="button"><i class="fas fa-arrow-up"></i></button>
</div>


<!-- JAVASCRIPT FILES ========================================= -->
<script src="js/jquery.min.js"></script><!-- JQUERY MIN JS -->
<script src="vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script><!-- BOOTSTRAP MIN JS -->
<script src="vendor/bootstrap-select/dist/js/bootstrap-select.min.js"></script><!-- BOOTSTRAP SELECT MIN JS -->
<script src="js/dz.ajax.js"></script><!-- AJAX -->
<script src="js/custom.js"></script><!-- CUSTOM JS -->
<script src="vendor/wow/wow.min.js"></script><!-- WOW JS -->
</body>
</html>