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
error_reporting(0);
include("header1.php");
if(strlen($_SESSION['id'])==0)
{   header('location:logout.php');
}else{

$uid=8;
$query2 ="SELECT * FROM orders WHERE userId='$uid' ORDER BY id DESC LIMIT 1";  
$result2 = mysqli_query($con, $query2);
$row2 = mysqli_fetch_array($result2);
$orderno='645910188';
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Shopping Portal | Order Details</title>
        <!-- Favicon-->
        <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
        <!-- Bootstrap icons-->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
        <!-- Core theme CSS (includes Bootstrap)-->
        <link href="css/styles.css" rel="stylesheet" />
        <script src="js/jquery.min.js"></script>
       <!--  <link href="css/bootstrap.min.css" rel="stylesheet" /> -->
        <script language="javascript" type="text/javascript">
var popUpWin=0;
function popUpWindow(URLStr, left, top, width, height)
{
 if(popUpWin)
{
if(!popUpWin.closed) popUpWin.close();
}
popUpWin = open(URLStr,'popUpWin', 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,copyhistory=yes,width='+600+',height='+600+',left='+left+', top='+top+',screenX='+left+',screenY='+top+'');
}

</script>

    </head>
<style type="text/css">

</style>
    <body>

				<div class="page-content">
			<!-- inner page banner -->
			<div class="dz-bnr-inr overlay-secondary-dark dz-bnr-inr-sm" style="background-image:url(images/background/bg3.jpg);">
				<div class="container">
					<div class="dz-bnr-inr-entry">
						<h1>#<?php echo $orderno;?> Order Details</h1>
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
<h5>Order Details</h5>
<hr />
<?php
$query ="SELECT a.*,b.* FROM online_order AS a, students AS b WHERE a.uid=b.sid AND a.orderno='645910188'";  
$result = mysqli_query($con, $query); 						  
$row = mysqli_fetch_array($result); ?>
<div class="row">
<div class="col-6">
    <table class="table table-bordered" border="1">
<tr>
    <th>Order Number</th>
    <td><?php echo htmlentities($row['orderno']);?></td>
</tr>
<tr>
    <th>Order Date</th>
    <td><?php echo htmlentities($row['orddate']);?></td>
</tr>
<tr>
    <th>Total Amount</th>
    <td>₹ <?php echo htmlentities($row['amount']);?></td>
</tr>
<tr>
    <th>Payment Mode</th>
    <td><?php echo htmlentities($row['mode']);?></td>
</tr>
<tr>

<tr>
    <th>Status</th>
    <td><?php $ostatus=$row['ordsts'];
                    if( $ostatus==''): echo "Not Processed Yet";
                        else: echo $ostatus; endif;?>
                         


                        </td>
</tr>
    </table>
</div>
<div class="col-6">
    <table class="table table-bordered" border="1">
<tr>
    <th>Billing Address</th>
    <td><address><?php echo htmlentities($row['address']);?><br />
<?php echo htmlentities($row['address']);?>,<?php echo htmlentities($row['address']);?><br />
<?php echo htmlentities($row['address']);?>, <?php echo htmlentities($row['address']);?>
</address>
    </td>
</tr>
<tr>
    <th>Shipping Address</th>
    <td><address><?php echo htmlentities($row['block']);?><br />
<?php echo htmlentities($row['city']);?>,<?php echo htmlentities($row['state']);?><br />
<?php echo htmlentities($row['zip']);?>.
</address>
    </td>
</tr>

    </table>
</div>

</div>
<?php  ?>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th colspan="4"><h4>Order Books</h4></th>
                </tr>
            </thead>
            <tr>
                <thead>
                    <th>Book</th>
                    <th>Book Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total Amount</th>
                </thead>
            </tr>
            <tbody>
<?php
$query1 ="SELECT a.*,b.* FROM  ordersdetails AS a, books AS b WHERE a.orderNumber='645910188' AND a.userId='8' AND a.productId=b.book_id";  
$result1 = mysqli_query($con, $query1); 
$num=mysqli_num_rows($result1);
    if($num>0)
    {
						  
while($row1=mysqli_fetch_array($result1))
{	

?>

                <tr>
                    <td class="col-md-2"><img src="images/<?php echo htmlentities($row1['image']);?>" alt="<?php echo htmlentities($row1['book']);?>" width="100" height="100"></td>
                    <td>
                       <a href="product-details.php?pid=<?php echo htmlentities($pd=$row1['book_id']);?>"><?php echo htmlentities($row1['book']);?></a></td>
<td>
                       
                            <span>₹ <?php echo htmlentities($row1['price']);?></span>
                    </td>
                    <td><?php echo htmlentities($row1['quantity']);?></td>
                     <td>₹ <?php echo htmlentities($totalamount=$row1['quantity']*$row1['price']);?></td>
        
                </tr>
            
                <?php $grantotal+=$totalamount; } ?>
<tr>
    <th colspan="4">Grand Total</th>
    <th colspan="2">₹ <?php echo $grantotal;?></th>
</tr>
                <?php } else{ ?>
                <tr>
                    <td style="font-size: 18px; font-weight:bold; color:red;">Invalid Request

                    </td>

                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
              
            </div>

 
</div>
        </section>
        <!-- Track Order Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Modal Header</h4>
      </div>
      <div class="modal-body">
        <p>Some text in the modal.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
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