<?php 
include('config.php');

$pid=$_GET['id'];
$rem=mysqli_query($con,"SELECT a.*,b.*,c.*,d.*,e.*,f.* FROM category AS a,subcategory AS b,product AS c, user As d, orders AS e, payments AS f WHERE a.CategoryID=b.CategoryID AND c.Subcategory=b.SubcatID AND a.categoryID=c.category AND f.PayID='$pid' AND e.UserID=d.UserID AND c.productID=e.ProductID AND e.OrderID=f.OrderID");

$ret=mysqli_fetch_array($rem);
$vid=$ret['productID'];


if(isset($_POST['update']))
{
	$sts=$_POST['status'];
	$remarks=$_POST['remarks'];
	
	if($sts=='Cancelled')
	{
		$ret=mysqli_query($con,"INSERT INTO paymenttrackhistory(PaymentID,Status,pay_remarks)VALUES('$pid','Cancelled','$remarks')");
	}
	else
	{
		$ret=mysqli_query($con,"INSERT INTO paymenttrackhistory (PaymentID,Status,pay_remarks) VALUES ('$pid','$sts','$remarks')");
	}
	
	$ret=mysqli_query($con,"UPDATE payments SET Pay_sts='$sts',premarks='$remarks' WHERE PayID='$pid'");
	if($ret)
	{
		echo "<script>alert('Updated Sucessfully..!')</script>";
		echo "<script>  window.location.assign('new_payments.php')</script>";
	}
}
?>

<!DOCTYPE html>

<html
  lang="en"
  class="light-style layout-menu-fixed"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="assets/"
  data-template="vertical-menu-template-free"
>
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
    />

    <title>Update Payment - Admin | Tenant</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="assets/img/favicon/LOGO(1).jpg" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet"
    />

    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="assets/vendor/fonts/boxicons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

    <!-- Page CSS -->

    <!-- Helpers -->
    <script src="assets/vendor/js/helpers.js"></script>
	

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="assets/js/config.js"></script>
<style>

.form-label
{
	font-weight:bolder;
}
</style>
<style type="text/css">
	table tr td,
	table tr th {
		border: 1px solid #dee2e6;
		padding: 10px;
		    text-align: center;
	}
	.plan {
    width: 100%;
	border-style: none;
}
.col-sm-4.form-group {
    margin-bottom: 40px;
}
button{
	border-style: none;
}
#myTable{
    width: 100%;
}
#pimg {
  width: 489px;
}
#pimgw {
  width: 489px;
}
</style>
  </head>

  <body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
        <!-- Menu -->

        <?php include('sidebar.php'); ?>
        <!-- / Menu -->

        <!-- Layout container -->
        <div class="layout-page">
          <!-- Navbar -->

          <?php include('navbar.php'); ?>

          <!-- / Navbar -->

          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->

            <div class="container-xxl flex-grow-1 container-p-y">
              <h4 class="fw-bold py-3 mb-4">View Order</h4>

              <!-- Basic Layout & Basic with Icons -->
              <div class="row">
                <!-- Basic Layout -->
                <div class="col-xxl">
                  <div class="card mb-4">
                    
                    <div class="card-body">
					<div class="card-header d-flex align-items-center justify-content-between">
                      <h5 class="fw-bold py-3 mb-4">Product Details</h5>
					
                    </div>
                      <form method="post" enctype="multipart/form-data">
						<div class="row mb-3">
                          <label class="form-label" for="basic-name">Product Title</label>
                            <input type="text" class="form-control" id="basic-name" name="title" value="<?php echo $ret['Productname']; ?>" readonly />
                        </div>
						<div class="row mb-3">
                          <label for="formcon" class="form-label">Product Configuration</label>
                        <input type="text" class="form-control" id="formcon" name="config"  value="<?php echo $ret['config'] ?>" readonly />
                        </div>
						<div class="row mb-3">
						<div class="col-md-6">
                          <label for="formFile" class="form-label">Product Image 1</label>
                        <img src="productimages/<?php echo $ret['image'] ?>" width="140px" height="200px" id="pimg" readonly >
                        </div>
						<div class="col-md-6">
                          <label for="formFile" class="form-label">Product Image 2</label>
                        <img src="productimages/<?php echo $ret['image'] ?>" width="140px" height="200px" id="pimgw" readonly >
                        </div>
                        </div>
						<div class="row mb-3">
                        <a href="view_products.php?id=<?php echo $vid;?>"><label>See full product details</label></a>
                        </div>
						<hr>
						<div class="card-header d-flex align-items-center justify-content-between">
                      <h5 class="fw-bold py-3 mb-4">User Details</h5>
                    </div>
					<hr>
						<div class="row mb-3">
						<div class="col-md-6">
                          <label class="form-label" for="b">Customer Name</label>
                            <input type="text" class="form-control" id="b" name="title" value="<?php echo $ret['Username']; ?>" readonly />
                        </div>
						<div class="col-md-6">
                          <label class="form-label" for="ba">Customer Email Address</label>
                            <input type="text" class="form-control" id="ba" name="title" value="<?php echo $ret['Email']; ?>" readonly />
                        </div>
                      </div>
					  <div class="row mb-3">
						<div class="col-md-6">
                          <label class="form-label" for="bas">Customer Mobile.No</label>
                            <input type="text" class="form-control" id="bas" name="title" value="<?php echo $ret['Phone']; ?>" readonly />
                        </div>
						<div class="col-md-6">
                          <label class="form-label" for="basi">Customer Address</label>
                            <input type="text" class="form-control" id="basi" name="title" value="<?php echo $ret['Address']; ?>" readonly />
                        </div>
                      </div>
					  <hr>
						<div class="card-header d-flex align-items-center justify-content-between">
                      <h5 class="fw-bold py-3 mb-4">Order Details</h5>
                    </div>
					<hr>
					<div class="row mb-3">
						<div class="col-md-6">
                          <label class="form-label" for="e">Order ID</label>
                            <input type="text" class="form-control" id="e" name="title" value="<?php echo $ret['OrderID']; ?>" readonly />
                        </div>
						<div class="col-md-6">
                          <label class="form-label" for="me">Product Name</label>
                            <input type="text" class="form-control" id="me" name="title" value="<?php echo $ret['Productname']; ?>" readonly />
                        </div>
                      </div>
					  <div class="row mb-3">
						<div class="col-md-6">
                          <label class="form-label" for="ame">Plan</label>
                            <input type="text" class="form-control" id="ame" name="title" value="<?php echo $ret['plan']; ?>" readonly />
                        </div>
						<div class="col-md-6">
                          <label class="form-label" for="pame">Price</label>
                            <input type="text" class="form-control" id="pame" name="title" value="₹ <?php echo $ret['price']; ?>" readonly />
                        </div>
                      </div>
					   <div class="row mb-3">
						<div class="col-md-6">
                          <label class="form-label" for="c">Security Deposit</label>
                            <input type="text" class="form-control" id="c" name="title" value="₹ <?php echo $ret['sdeposit']; ?>" readonly />
                        </div>
						<div class="col-md-6">
                          <label class="form-label" for="ic">Order Placed Date </label>
                            <input type="text" class="form-control" id="ic" name="title" value="<?php echo $ret['O_date']; ?>" readonly />
                        </div>
                      </div>
					  <div class="row mb-3">
						<div class="col-md-6">
                          <label class="form-label" for="sic">Remarks</label>
                            <input type="text" class="form-control" id="sic" name="title" value="<?php if($ret['premarks']==''){ echo "Not Updated Yet";} else { echo $ret['premarks'];}  ?>" readonly />
                        </div>
						<div class="col-md-6">
                          <label class="form-label" for="asic">Order Status</label>
                            <input type="text" class="form-control" id="asic" name="title" value="<?php if($ret['Pay_sts']==''){ echo "Not Updated Yet";} elseif($ret['Pay_sts']=='Security') { echo "Security Payment Received";} elseif($ret['Pay_sts']=='Rental') { echo "Rental Payment Received"; } elseif($ret['Pay_sts']=='Completed') { echo "Rental Completed"; } else { echo "Cancelled";} ?>" readonly />
                        </div>
                      </div>
					 
					  <div class="row mb-3">
						<div class="col-md-6">
                          <label class="form-label" for="as">Remarks</label>
                            <input type="text" class="form-control" id="as" name="remarks"/>
                        </div>
						<div class="col-md-6">
                          <label class="form-label" for="asi">Update Status</label>
                            <select name="status" class="form-control" id="asi">
							<option value="">Select status</option>
							<?php if($ret['Pay_sts']==''): ?>
        <option value="Cancelled">Rejected</option>
    <option value="Security">Security Deposit Received</option>
    <option value="Rental">Rental Payment Received</option>
    <option value="Completed">Rental Completed</option>
    <?php elseif($ret['Pay_sts']=='Security'):?>
    <option value="Rental">Rental Payment Received</option>
    <option value="Completed">Rental Completed</option>
    <?php elseif($ret['Pay_sts']=='Rental'):?>
    <option value="Completed">Rental Completed</option>
        <?php endif;?>
							</select>
                        </div>
                      </div>
                        <div class="row justify-content-end">
                          <div class="col-sm-10">
                            <button type="submit" name="update" class="btn btn-primary">Update</button>
                          </div>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
                <!-- Basic with Icons -->
                
              </div>
            </div>
            <!-- / Content -->

            <!-- Footer -->
            <?php include('footer.php') ?>
            <!-- / Footer -->

            <div class="content-backdrop fade"></div>
          </div>
          <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
      </div>

      <!-- Overlay -->
      <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="assets/vendor/libs/jquery/jquery.js"></script>
    <script src="assets/vendor/libs/popper/popper.js"></script>
    <script src="assets/vendor/js/bootstrap.js"></script>
    <script src="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

    <script src="assets/vendor/js/menu.js"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->

    <!-- Main JS -->
    <script src="assets/js/main.js"></script>

    <!-- Page JS -->

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
  </body>
</html>
