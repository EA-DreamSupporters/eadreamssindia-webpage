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
	<title>Order Details - EA Dream Supporters</title>
	
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
?>

        <link href="css/styles.css" rel="stylesheet" />
        <script src="js/jquery.min.js"></script>
       <!--  <link href="css/bootstrap.min.css" rel="stylesheet" /> -->
    </head>
<style type="text/css">

</style>
    <body>

		<div class="page-content">
			<!-- inner page banner -->
			<div class="dz-bnr-inr overlay-secondary-dark dz-bnr-inr-sm" style="background-image:url(images/background/bg3.jpg);">
				<div class="container">
					<div class="dz-bnr-inr-entry">
						<h1>My Orders</h1>
						<nav aria-label="breadcrumb" class="breadcrumb-row">
							<ul class="breadcrumb">
								<li class="breadcrumb-item"><a href="index.php"> Home</a></li>
								<li class="breadcrumb-item">Orders</li>
							</ul>
						</nav>
					</div>
				</div>
			</div>
			<!-- inner page banner End-->
        <!-- Section-->
        <section class="py-5">
            <div class="container px-4  mt-5">
     

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th colspan="4"><h4>My Orders</h4></th>
                </tr>
            </thead>
            <tr>
                <thead>
                    <th>#</th>
                    <th>Order Number </th>
                    <th>Order Date</th>
                    <th>Transaction Type</th>
                    <th>Total Amount</th>
                    <th>Order Status</th>
                    <th>Action</th>
                </thead>
            </tr>
            <tbody>
<?php
$uid=$_SESSION['id'];
$ret=mysqli_query($con,"select * from orders where userId='$uid'");
$num=mysqli_num_rows($ret);
$cnt=1;
    if($num>0)
    {
while ($row=mysqli_fetch_array($ret)) {

?>

                <tr>
                    <td><?php echo htmlentities($cnt);?></td>
                    <td><?php echo htmlentities($row['orderNumber']);?></td>
                    <td><?php echo htmlentities($row['orderDate']);?></td>
                    <td><?php echo htmlentities($row['txnType']);?></td>
                    <td>₹ <?php echo htmlentities($row['totalAmount']);?></td>
                    <td><?php $ostatus=$row['orderStatus'];
                    if( $ostatus==''): echo "Not Processed Yet";
                        else: echo $ostatus; endif;?><br />
                    </td>
                    <td><a href="order_details.php?onumber=<?php echo htmlentities($row['orderNumber']);?>" class="btn-upper btn btn-primary">Details</a></td>
                
                </tr>
            
                <?php $cnt++;}  } else{ ?>
                <tr>
                    <td style="font-size: 18px; font-weight:bold ">Not Order Yet.&nbsp;
<a href="books.php" class="btn-upper btn btn-warning">Continue Shopping</a>
                    </td>

                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
	<div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th colspan="4"><h4>My Online Orders</h4></th>
                </tr>
            </thead>
            <tr>
                <thead>
                    <th>#</th>
                    <th>Order Number </th>
                    <th>Order Date</th>
                    <th>Transaction Type</th>
                    <th>Total Amount</th>
                    <th>Order Status</th>
                    <th>Action</th>
                </thead>
            </tr>
            <tbody>
<?php
$uid=$_SESSION['id'];
$ret=mysqli_query($con,"select * from online_order where uid='$uid'");
$num=mysqli_num_rows($ret);
$cnt=1;
    if($num>0)
    {
while ($row=mysqli_fetch_array($ret)) {

?>

                <tr>
                    <td><?php echo htmlentities($cnt);?></td>
                    <td><?php echo htmlentities($row['orderno']);?></td>
                    <td><?php echo htmlentities($row['orddate']);?></td>
                    <td><?php echo htmlentities($row['mode']);?></td>
                    <td>₹ <?php echo htmlentities($row['amount']);?></td>
                    <td><?php $ostatus=$row['ordersts'];
                    if( $ostatus==''): echo "Not Processed Yet";
                        else: echo $ostatus; endif;?><br />
                    </td>
                    <td><a href="online_orderdetails.php?onumber=<?php echo htmlentities($row['orderno']);?>" class="btn-upper btn btn-primary">Details</a></td>
                
                </tr>
            
                <?php $cnt++;}  } else{ ?>
                <tr>
                    <td style="font-size: 18px; font-weight:bold ">Not Order Yet.&nbsp;
<a href="books.php" class="btn-upper btn btn-warning">Continue Shopping</a>
                    </td>

                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
	 <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th colspan="4"><h4>My Courses</h4></th>
                </tr>
            </thead>
            <tr>
                <thead>
                    <th>#</th>
                    <th>Course-Name </th>
                    <th>Purchase-By</th>
                    <th>Purchase-Date</th>
                    <th>Course-Amount</th>
                    <th>Status</th>
                    <th>Action</th>
                </thead>
            </tr>
            <tbody>
<?php
$uid=$_SESSION['id'];
$ret=mysqli_query($con,"SELECT a.*,b.*,c.* FROM course AS a, students AS b, course_subscription AS c WHERE a.id=c.cid AND b.sid='$uid' AND c.cid=a.id AND b.sid=c.uid");
$num=mysqli_num_rows($ret);
$cnt=1;
    if($num>0)
    {
while ($row=mysqli_fetch_array($ret)) {

?>

                <tr>
                    <td><?php echo htmlentities($cnt);?></td>
                    <td><?php echo htmlentities($row['ctitle']);?></td>
                    <td><?php echo htmlentities($row['name']);?></td>
                    <td><?php echo htmlentities($row['intime']);?></td>
                    <td>₹ <?php echo htmlentities($row['csprice']);?></td>
                   <td>
									<?php if($row["status"] == -1){ ?>
									<span class="badge badge-danger">Rejected</span>
									<?php }elseif($row["status"] == 1){ ?>
									<span class="badge badge-success">Accepted</span>
									<?php }else{ ?>
									<span class="badge badge-warning">Not Processed Yet</span>
									<?php } ?></td>
                    <td><a href="ordcourse.php?id=<?php echo htmlentities($row['id']);?>" class="btn-upper btn btn-primary">Details</a></td>
                
                </tr>
            
                <?php $cnt++;}  } else{ ?>
                <tr>
                    <td style="font-size: 18px; font-weight:bold ">Not Order Yet.&nbsp;<br>
<a href="coursefull.php" class="btn-upper btn btn-warning">Continue Shopping</a>
                    </td>

                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
	 <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th colspan="4"><h4>My Test-Subscription</h4></th>
                </tr>
            </thead>
            <tr>
                <thead>
                    <th>#</th>
                    <th>Title</th>
                    <th>Purchased By</th>
                    <th>Purchase-Date</th>
                    <th>Total Price</th>
                    <th>status</th>
                    <th>Action</th>
                </thead>
            </tr>
            <tbody>
<?php
$uid=$_SESSION['id'];
$ret=mysqli_query($con,"SELECT a.*,b.*,c.* FROM test AS a, students AS b, test_subscription AS c WHERE a.id=c.mid AND b.sid='$uid' AND c.mid=a.id AND b.sid=c.uid");
$num=mysqli_num_rows($ret);
$cnt=1;
    if($num>0)
    {
while ($row=mysqli_fetch_array($ret)) {

?>

                <tr>
                    <td><?php echo htmlentities($cnt);?></td>
                    <td><?php echo htmlentities($row['title']);?></td>
                    <td><?php echo htmlentities($row['name']);?></td>
                    <td><?php echo htmlentities($row['intime']);?></td>
                    <td>₹ <?php echo htmlentities($row['sprice']);?></td>
                    <td>
									<?php if($row["status"] == -1){ ?>
									<span class="badge badge-danger">Rejected</span>
									<?php }elseif($row["status"] == 1){ ?>
									<span class="badge badge-success">Accepted</span>
									<?php }else{ ?>
									<span class="badge badge-warning">Not Processed Yet</span>
									<?php } ?></td>
                    <td><a href="ordtest.php?id=<?php echo htmlentities($row['id']);?>" class="btn-upper btn btn-primary">Details</a></td>
                
                </tr>
            
                <?php $cnt++;}  } else{ ?>
                <tr>
                    <td style="font-size: 18px; font-weight:bold ">Not Order Yet.&nbsp;<br>
<a href="testefull.php" class="btn-upper btn btn-warning">Continue Shopping</a>
                    </td>

                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
              
            </div>

 
</div>
        </section>

        
        <!-- Footer-->
   
        <!-- Bootstrap core JS-->
        <script src="js/bootstrap.bundle.min.js"></script>
        <!-- Core theme JS-->
        <script src="js/scripts.js"></script>
    </body>
</html>
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