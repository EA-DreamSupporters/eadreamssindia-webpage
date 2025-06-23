<?php
    error_reporting(E_ERROR | E_PARSE);
	session_start();
	include ("config.php");	
	if($_SESSION["admin"])
	{
	 $admin= $_SESSION["admin"];
	$sql = "SELECT * FROM user WHERE username='$admin'";
    $result=mysqli_query($con, $sql);
    $row = mysqli_fetch_array($result);
	 ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Admin Dashboard - EA Dream Supporters </title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="vendors/feather/feather.css">
  <link rel="stylesheet" href="vendors/mdi/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" href="vendors/typicons/typicons.css">
  <link rel="stylesheet" href="vendors/simple-line-icons/css/simple-line-icons.css">
  <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
  <!-- endinject -->
  <!-- Plugin css for this page -->
  <link rel="stylesheet" href="vendors/datatables.net-bs4/dataTables.bootstrap4.css">
  <link rel="stylesheet" href="js/select.dataTables.min.css">
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="css/vertical-layout-light/style.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="images/favicon.png" />
</head>
<style>
.form-control{
 border: 1px solid #c7cacd;
}
.content-wrapper {
    background: #dee2e6!important;

}
.card .card-title {

    margin-bottom: 40px;

    border-bottom: 1px solid #dee2e6;
    padding-bottom: 25px;
}
.card {

    border-radius: 0px;
}
select.form-control {
    color: black!important;
    background: #fbfbfb!important;
}
.sidebar .nav:not(.sub-menu) > .nav-item > .nav-link {
    margin: 0;
    padding: 20px 31px;
    border: 1px solid #dee2e6;
    border-radius: 0;
}
.sidebar {

    width: 300px;
}
span.menu-title {
    font-size: 16px!important;
}
a.nav-link {
    font-size: 15px!important;
}
.navbar .navbar-brand-wrapper {

    width: 300px;

}
.navbar .navbar-menu-wrapper {

    width: calc(100% - 300px);
}
.navbar .navbar-brand-wrapper .navbar-brand img {
    height: auto;
}
.content-wrapper {

    padding: 24px;

}
input.form-control {
    background: none!important;
}
textarea.form-control {
    height: 138px!important;
}
@media screen and (max-width: 991px){
.sidebar-offcanvas {

    position: fixed;

    right: -300px;
    -webkit-transition: all 0.25s ease-out;
    -o-transition: all 0.25s ease-out;
    transition: all 0.25s ease-out;

    right: -300px;
}
	}
	
	.navbar .navbar-brand-wrapper .brand-logo-mini img {
  width: 100%;
	}
	table.table-bordered.dataTable tbody th, table.table-bordered.dataTable tbody td {
  border-bottom-width: 0;
  white-space: break-spaces;
}
.table > :not(:last-child) > :last-child > *, .jsgrid .jsgrid-table > :not(:last-child) > :last-child > * {
  border-bottom-color: #dee2e6;
  width: 100px;
}

.sidebar .nav .nav-item.active > .nav-link i.menu-arrow::before {
 
  content: "\F142";
}

.sidebar .nav.sub-menu .nav-item .nav-link.active {
  color: unset;
  background: transparent;
}
.sidebar .nav.sub-menu .nav-item .nav-link:hover,.sidebar .nav.sub-menu .nav-item .nav-link:focus{
	color: #1f3bb3;
}
</style>

<body>
  <div class="container-scroller"> 
    <!-- partial:partials/_navbar.html -->
    <nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex align-items-top flex-row">
      <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
        <div class="me-3">
          <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-bs-toggle="minimize">
            <span class="icon-menu"></span>
          </button>
        </div>
        <div>
          <a class="navbar-brand brand-logo" href="index.php">
            <img src="images/logo.png" alt="logo" />
          </a>
          <a class="navbar-brand brand-logo-mini" href="index.php">
            <img src="images/logo.png" alt="logo" />
          </a>
        </div>
      </div>
      <div class="navbar-menu-wrapper d-flex align-items-top"> 
        <ul class="navbar-nav">
          <li class="nav-item font-weight-semibold d-none d-lg-block ms-0">
            <h1 class="welcome-text"><span class="text-black fw-bold"><?php echo $row['username'] ?> Dashboard</span></h1>
         
          </li>
        </ul>
        <ul class="navbar-nav ms-auto">

          <li class="nav-item d-none d-lg-block">
            <div id="datepicker-popup" class="input-group date datepicker navbar-date-picker">
              <span class="input-group-addon input-group-prepend border-right">
                <span class="icon-calendar input-group-text calendar-icon"></span>
              </span>
              <input type="text" disabled  class="form-control">
            </div>
          </li>
       
         

          <li class="nav-item dropdown d-none d-lg-block user-dropdown">
        <a href="logout.php" class="dropdown-item"><i class="dropdown-item-icon mdi mdi-power text-primary me-2"></i>Sign Out</a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="UserDropdown">
              <div class="dropdown-header text-center">
                <img class="img-md rounded-circle" src="images/faces/face8.jpg" alt="Profile image">
                <p class="mb-1 mt-3 font-weight-semibold"><?php echo $row['username'] ?></p>
              </div>
              <a class="dropdown-item"><i class="dropdown-item-icon mdi mdi-account-outline text-primary me-2"></i> My Profile</a>
              <a href="logout.php" class="dropdown-item"><i class="dropdown-item-icon mdi mdi-power text-primary me-2"></i>Sign Out</a>
            </div>
          </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-bs-toggle="offcanvas">
          <span class="mdi mdi-menu"></span>
        </button>
      </div>
    </nav>

    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <!-- partial:partials/_settings-panel.html -->
      <div class="theme-setting-wrapper">
        <div id="settings-trigger"><i class="ti-settings"></i></div>
        <div id="theme-settings" class="settings-panel">
          <i class="settings-close ti-close"></i>
          <p class="settings-heading">SIDEBAR SKINS</p>
          <div class="sidebar-bg-options selected" id="sidebar-light-theme"><div class="img-ss rounded-circle bg-light border me-3"></div>Light</div>
          <div class="sidebar-bg-options" id="sidebar-dark-theme"><div class="img-ss rounded-circle bg-dark border me-3"></div>Dark</div>
          <p class="settings-heading mt-2">HEADER SKINS</p>
          <div class="color-tiles mx-0 px-4">
            <div class="tiles success"></div>
            <div class="tiles warning"></div>
            <div class="tiles danger"></div>
            <div class="tiles info"></div>
            <div class="tiles dark"></div>
            <div class="tiles default"></div>
          </div>
        </div>
      </div>
     
      <!-- partial -->
      <!-- partial:partials/_sidebar.html -->
      <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav">
          <li class="nav-item">
            <a class="nav-link" href="index.php">
              <i class="mdi mdi-grid-large menu-icon"></i>
              <span class="menu-title">Dashboard</span>
            </a>
          </li>
		  <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#ui-bas77" aria-expanded="false" aria-controls="ui-bas77">
              <i class="menu-icon mdi mdi-key"></i>
              <span class="menu-title">Settings</span>
              <i class="menu-arrow"></i> 
            </a>
            <div class="collapse" id="ui-bas77">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="changepassword.php">Change Password</a></li>
              </ul>
            </div>
          </li>
		
          <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#ui-basic1" aria-expanded="false" aria-controls="ui-basic1">
              <i class="menu-icon mdi mdi-book-multiple"></i>
              <span class="menu-title">Book</span>
              <i class="menu-arrow"></i> 
            </a>
            <div class="collapse" id="ui-basic1">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="category_add.php">Add Category</a></li>
                <li class="nav-item"> <a class="nav-link" href="category_view.php">Manage Category</a></li>
				<li class="nav-item"> <a class="nav-link" href="subcategory_add.php">Add SubCategory</a></li>
                <li class="nav-item"> <a class="nav-link" href="subcategory_view.php">Manage SubCategory</a></li>
				<li class="nav-item"> <a class="nav-link" href="book_add.php">Add Book</a></li>
                <li class="nav-item"> <a class="nav-link" href="book_view.php">Manage Book</a></li>
				
              </ul>
            </div>
          </li>
	
          <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#ui-bas" aria-expanded="false" aria-controls="ui-bas">
              <i class="menu-icon mdi mdi-newspaper"></i>
              <span class="menu-title">Magazines</span>
              <i class="menu-arrow"></i> 
            </a>
            <div class="collapse" id="ui-bas">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="magazine_add.php">Add Magazine</a></li>
                <li class="nav-item"> <a class="nav-link" href="magazine_view.php">Manage Magazine</a></li>
              </ul>
            </div>
          </li>
 <li class="nav-item"><script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
            <a class="nav-link" data-bs-toggle="collapse" href="#ui-basss" aria-expanded="false" aria-controls="ui-basss">
           <i class='menu-icon mdi mdi-school' style="margin-right: 18px;font-size: 20px;"></i>
              <span class="menu-title">Insights</span>
              <i class="menu-arrow"></i> 
            </a>
            <div class="collapse" id="ui-basss">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="career.php">Careers</a></li>
              </ul>
            </div>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#ui-basic2" aria-expanded="false" aria-controls="ui-basic2">
              <i class="menu-icon mdi mdi-book-open"></i>
              <span class="menu-title">Rental Book</span>
              <i class="menu-arrow"></i> 
            </a>
            <div class="collapse" id="ui-basic2">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="rental_add.php">Add Book</a></li>
                <li class="nav-item"> <a class="nav-link" href="rental_view.php">Manage Book</a></li>
              </ul>
            </div>
          </li>
		            <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#ui-basic3" aria-expanded="false" aria-controls="ui-basic3">
              <i class="menu-icon mdi mdi-account-card-details"></i>
              <span class="menu-title">EA Guidence</span>
              <i class="menu-arrow"></i> 
            </a>
            <div class="collapse" id="ui-basic3">
              <ul class="nav flex-column sub-menu">
                <!--<li class="nav-item"> <a class="nav-link" href="customer_add.php">Add</a></li>-->
                <li class="nav-item"> <a class="nav-link" href="guide.php">New Enquiry</a></li>
				<li class="nav-item"> <a class="nav-link" href="respond.php">Respond Enquiry</a></li>
				<li class="nav-item"> <a class="nav-link" href="unrespond.php">Unrespond Enquiry</a></li>
              </ul>
            </div>
          </li>
		   <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#ui-basic4" aria-expanded="false" aria-controls="ui-basic4">
              <i class="menu-icon mdi mdi-youtube"></i>
              <span class="menu-title">Preparation Zone</span>
              <i class="menu-arrow"></i> 
            </a>
            <div class="collapse" id="ui-basic4">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="subject_add.php">Add Subject</a></li>
                <li class="nav-item"> <a class="nav-link" href="subject_view.php">Manage Subject</a></li>
                <li class="nav-item"> <a class="nav-link" href="topic_add.php">Add Topic</a></li>
                <li class="nav-item"> <a class="nav-link" href="topic_view.php">Manage Topic</a></li>
                <li class="nav-item"> <a class="nav-link" href="precontent_add.php">Add Precontent</a></li>
                <li class="nav-item"> <a class="nav-link" href="precontent_view.php">Manage Precontent</a></li>
              </ul>
            </div>
          </li>
		   <li class="nav-item">
            <a class="nav-link" href="sub.php">
              <i class="mdi mdi-grid-large menu-icon"></i>
              <span class="menu-title">Subscription</span>
            </a>
          </li>
		  <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#ui-basic7" aria-expanded="false" aria-controls="ui-basic7">
              <i class="menu-icon mdi mdi-account-network"></i>
              <span class="menu-title">Test</span>
              <i class="menu-arrow"></i> 
            </a>
            <div class="collapse" id="ui-basic7">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="test_add.php">Add Test</a></li>
                <li class="nav-item"> <a class="nav-link" href="test_view.php">Manage Test</a></li>
                
               
               
              </ul>
            </div>
          </li>
		  <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#ui-basic8" aria-expanded="false" aria-controls="ui-basic8">
              <i class="menu-icon mdi mdi-account-network"></i>
              <span class="menu-title">Institute Course</span>
              <i class="menu-arrow"></i> 
            </a>
            <div class="collapse" id="ui-basic8">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="course_add.php">Add Course</a></li>
                <li class="nav-item"> <a class="nav-link" href="course_view.php">Manage Course</a></li>
                
          
               
              </ul>
            </div>
          </li>
		  <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#ui-basic66" aria-expanded="false" aria-controls="ui-basic66">
              <i class="menu-icon mdi mdi-image-filter"></i>
              <span class="menu-title">Advertisement Banner</span>
              <i class="menu-arrow"></i> 
            </a>
            <div class="collapse" id="ui-basic66">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="add_banner.php">Add Banner</a></li>
                <li class="nav-item"> <a class="nav-link" href="manage_banner.php">Manage Banner</a></li>
                
          
               
              </ul>
            </div>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#ui-basic68" aria-expanded="false" aria-controls="ui-basic68">
              <i class="menu-icon mdi mdi-image"></i>
              <span class="menu-title">Landing Page</span>
              <i class="menu-arrow"></i> 
            </a>
            <div class="collapse" id="ui-basic68">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"><a class="nav-link" href="landing_page.php">Landing Page </a></li>
                <li class="nav-item"><a class="nav-link" href="manage_landing.php">Manage Landing Page</a></li>
                
          
               
              </ul>
            </div>
          </li>
		  	<li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#ui-basic6" aria-expanded="false" aria-controls="ui-basic4">
              <i class="menu-icon mdi mdi-account-check"></i>
              <span class="menu-title">Registered User</span>
              <i class="menu-arrow"></i> 
            </a>
            <div class="collapse" id="ui-basic6">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="user_view.php">Manage User</a></li>
              </ul>
            </div>
          </li>
		  <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#ui-basic99" aria-expanded="false" aria-controls="ui-basic99">
              <i class="menu-icon mdi mdi-account-multiple-check"></i>
              <span class="menu-title">Vendor's Registration</span>
              <i class="menu-arrow"></i> 
            </a>
            <div class="collapse" id="ui-basic99">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="manage_vendors.php">Registered Vendors</a></li>
              </ul>
            </div>
          </li>
		            <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#ui-basic5" aria-expanded="false" aria-controls="ui-basic5">
              <i class="menu-icon mdi mdi-cart"></i>
              <span class="menu-title">Orders</span>
              <i class="menu-arrow"></i> 
            </a>
            <div class="collapse" id="ui-basic5">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="report_book.php">Book Orders</a></li>
                <li class="nav-item"> <a class="nav-link" href="online_order.php">Online Payment Orders</a></li>
                <li class="nav-item"> <a class="nav-link" href="report_magazine.php">Magazine Subscription</a></li>
				<li class="nav-item"> <a class="nav-link" href="report_rental.php">Rental Books</a></li>
				<li class="nav-item"> <a class="nav-link" href="test_status.php">Test Subscription Status</a></li>
				<li class="nav-item"> <a class="nav-link" href="course_status.php">Course Purchase</a></li>
				
              </ul>
            </div>
          </li>
         


        </ul>
      </nav>
	  	<?php
	}
		else
	{
		header("location:login.php");
	}
 ?>