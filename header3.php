<?php
    error_reporting(E_ERROR | E_PARSE);
	session_start();
	include ("config.php");	
	if($_SESSION["user"])
	{
	 $user= $_SESSION["user"];
	$sql = "SELECT * FROM students WHERE username='$user'";
    $result=mysqli_query($con, $sql);
    $row = mysqli_fetch_array($result);
	 ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Dashboard - EA Dream Supporters </title>
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
.theme-setting-wrapper {
    display: none;
}
.float{
	position:fixed;
	width:60px;
	height:60px;
	bottom:40px;
	right:40px;
	background-color:#25d366;
	color:#FFF;
	border-radius:50px;
	text-align:center;
  font-size:30px;
	box-shadow: 2px 2px 3px #999;
  z-index:100;
}

.my-float{
	margin-top:16px;
}
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
	.table td, .table th {
   
	width: 130px !important;
}
.navbar .navbar-brand-wrapper .brand-logo-mini img {
  width: unset;
}
  @media (max-width: 991px){
.navbar .navbar-menu-wrapper .navbar-nav .nav-item.dropdown {
  position: fixed;
  display: block !important;
  top: 65px;
right: 0;
}

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
            <h1 class="welcome-text"><span class="text-black fw-bold"><?php echo $row['username'] ?> </span> Dashboard</h1>
         
          </li>
        </ul>
        <ul class="navbar-nav ms-auto">

          <li class="nav-item d-none d-lg-block">
            <div id="datepicker-popup" class="input-group date datepicker navbar-date-picker">
              <span class="input-group-addon input-group-prepend border-right">
                <span class="icon-calendar input-group-text calendar-icon"></span>
              </span>
              <input type="text" class="form-control">
            </div>
          </li>
        
         

          <li class="nav-item dropdown d-none d-lg-block user-dropdown sout">
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
      <div id="right-sidebar" class="settings-panel">
        <i class="settings-close ti-close"></i>
        <ul class="nav nav-tabs border-top" id="setting-panel" role="tablist">
          <li class="nav-item">
            <a class="nav-link active" id="todo-tab" data-bs-toggle="tab" href="#todo-section" role="tab" aria-controls="todo-section" aria-expanded="true">TO DO LIST</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="chats-tab" data-bs-toggle="tab" href="#chats-section" role="tab" aria-controls="chats-section">CHATS</a>
          </li>
        </ul>
        <div class="tab-content" id="setting-content">
          <div class="tab-pane fade show active scroll-wrapper" id="todo-section" role="tabpanel" aria-labelledby="todo-section">
            <div class="add-items d-flex px-3 mb-0">
              <form class="form w-100">
                <div class="form-group d-flex">
                  <input type="text" class="form-control todo-list-input" placeholder="Add To-do">
                  <button type="submit" class="add btn btn-primary todo-list-add-btn" id="add-task">Add</button>
                </div>
              </form>
            </div>
            <div class="list-wrapper px-3">
              <ul class="d-flex flex-column-reverse todo-list">
                <li>
                  <div class="form-check">
                    <label class="form-check-label">
                      <input class="checkbox" type="checkbox">
                      Team review meeting at 3.00 PM
                    </label>
                  </div>
                  <i class="remove ti-close"></i>
                </li>
                <li>
                  <div class="form-check">
                    <label class="form-check-label">
                      <input class="checkbox" type="checkbox">
                      Prepare for presentation
                    </label>
                  </div>
                  <i class="remove ti-close"></i>
                </li>
                <li>
                  <div class="form-check">
                    <label class="form-check-label">
                      <input class="checkbox" type="checkbox">
                      Resolve all the low priority tickets due today
                    </label>
                  </div>
                  <i class="remove ti-close"></i>
                </li>
                <li class="completed">
                  <div class="form-check">
                    <label class="form-check-label">
                      <input class="checkbox" type="checkbox" checked>
                      Schedule meeting for next week
                    </label>
                  </div>
                  <i class="remove ti-close"></i>
                </li>
                <li class="completed">
                  <div class="form-check">
                    <label class="form-check-label">
                      <input class="checkbox" type="checkbox" checked>
                      Project review
                    </label>
                  </div>
                  <i class="remove ti-close"></i>
                </li>
              </ul>
            </div>
            <h4 class="px-3 text-muted mt-5 fw-light mb-0">Events</h4>
            <div class="events pt-4 px-3">
              <div class="wrapper d-flex mb-2">
                <i class="ti-control-record text-primary me-2"></i>
                <span>Feb 11 2018</span>
              </div>
              <p class="mb-0 font-weight-thin text-gray">Creating component page build a js</p>
              <p class="text-gray mb-0">The total number of sessions</p>
            </div>
            <div class="events pt-4 px-3">
              <div class="wrapper d-flex mb-2">
                <i class="ti-control-record text-primary me-2"></i>
                <span>Feb 7 2018</span>
              </div>
              <p class="mb-0 font-weight-thin text-gray">Meeting with Alisa</p>
              <p class="text-gray mb-0 ">Call Sarah Graves</p>
            </div>
          </div>
          <!-- To do section tab ends -->
          <div class="tab-pane fade" id="chats-section" role="tabpanel" aria-labelledby="chats-section">
            <div class="d-flex align-items-center justify-content-between border-bottom">
              <p class="settings-heading border-top-0 mb-3 pl-3 pt-0 border-bottom-0 pb-0">Friends</p>
              <small class="settings-heading border-top-0 mb-3 pt-0 border-bottom-0 pb-0 pr-3 fw-normal">See All</small>
            </div>
            <ul class="chat-list">
              <li class="list active">
                <div class="profile"><img src="images/faces/face1.jpg" alt="image"><span class="online"></span></div>
                <div class="info">
                  <p>Thomas Douglas</p>
                  <p>Available</p>
                </div>
                <small class="text-muted my-auto">19 min</small>
              </li>
              <li class="list">
                <div class="profile"><img src="images/faces/face2.jpg" alt="image"><span class="offline"></span></div>
                <div class="info">
                  <div class="wrapper d-flex">
                    <p>Catherine</p>
                  </div>
                  <p>Away</p>
                </div>
                <div class="badge badge-success badge-pill my-auto mx-2">4</div>
                <small class="text-muted my-auto">23 min</small>
              </li>
              <li class="list">
                <div class="profile"><img src="images/faces/face3.jpg" alt="image"><span class="online"></span></div>
                <div class="info">
                  <p>Daniel Russell</p>
                  <p>Available</p>
                </div>
                <small class="text-muted my-auto">14 min</small>
              </li>
              <li class="list">
                <div class="profile"><img src="images/faces/face4.jpg" alt="image"><span class="offline"></span></div>
                <div class="info">
                  <p>James Richardson</p>
                  <p>Away</p>
                </div>
                <small class="text-muted my-auto">2 min</small>
              </li>
              <li class="list">
                <div class="profile"><img src="images/faces/face5.jpg" alt="image"><span class="online"></span></div>
                <div class="info">
                  <p>Madeline Kennedy</p>
                  <p>Available</p>
                </div>
                <small class="text-muted my-auto">5 min</small>
              </li>
              <li class="list">
                <div class="profile"><img src="images/faces/face6.jpg" alt="image"><span class="online"></span></div>
                <div class="info">
                  <p>Sarah Graves</p>
                  <p>Available</p>
                </div>
                <small class="text-muted my-auto">47 min</small>
              </li>
            </ul>
          </div>
          <!-- chat tab ends -->
        </div>
      </div>
      <!-- partial -->
      <!-- partial:partials/_sidebar.html -->
      <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav">
		    <li class="nav-item">
            <a class="nav-link" href="dashboard.php">
              <i class="mdi mdi-grid-large menu-icon"></i>
              <span class="menu-title">Dashboard</span>
            </a>
          </li>
         <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#ui-basic99" aria-expanded="false" aria-controls="ui-basic99">
              <i class="menu-icon mdi mdi-key"></i>
              <span class="menu-title">Settings</span>
              <i class="menu-arrow"></i> 
            </a>
            <div class="collapse" id="ui-basic99">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="profile.php">Profile</a></li>
                <li class="nav-item"> <a class="nav-link" href="cpassword.php">Change Password</a></li>
				
              </ul>
            </div>
          </li>
		
          <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#ui-basic1" aria-expanded="false" aria-controls="ui-basic1">
              <i class="menu-icon mdi mdi-book-multiple"></i>
              <span class="menu-title">Book Purchased</span>
              <i class="menu-arrow"></i> 
            </a>
            <div class="collapse" id="ui-basic1">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="book_current.php">Current</a></li>
                <li class="nav-item"> <a class="nav-link" href="book_previous.php">Previous</a></li>
				
              </ul>
            </div>
          </li>
		  <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#ui-basic77" aria-expanded="false" aria-controls="ui-basic77">
              <i class="menu-icon mdi mdi-book-multiple"></i>
              <span class="menu-title">Online Book Purchase</span>
              <i class="menu-arrow"></i> 
            </a>
            <div class="collapse" id="ui-basic77">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="online_bookc.php">Current</a></li>
                <li class="nav-item"> <a class="nav-link" href="online_bookp.php">Previous</a></li>
				
              </ul>
            </div>
          </li>
	
          <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#ui-bas" aria-expanded="false" aria-controls="ui-bas">
              <i class="menu-icon mdi mdi-newspaper"></i>
              <span class="menu-title">Magazine Purchased</span>
              <i class="menu-arrow"></i> 
            </a>
            <div class="collapse" id="ui-bas">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="magazine_current.php">Current</a></li>
                <li class="nav-item"> <a class="nav-link" href="magazine_previous.php">Previous</a></li>
              </ul>
            </div>
          </li>
		   <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#ui-bas3" aria-expanded="false" aria-controls="ui-bas3">
              <i class="menu-icon mdi mdi-newspaper"></i>
              <span class="menu-title">Test-Subscription</span>
              <i class="menu-arrow"></i> 
            </a>
            <div class="collapse" id="ui-bas3">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="test_currect.php">Current</a></li>
                <li class="nav-item"> <a class="nav-link" href="test_previous.php">Previous</a></li>
              </ul>
            </div>
          </li>
		   <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#ui-bas4" aria-expanded="false" aria-controls="ui-bas4">
              <i class="menu-icon mdi mdi-newspaper"></i>
              <span class="menu-title">Course Purchased</span>
              <i class="menu-arrow"></i> 
            </a>
            <div class="collapse" id="ui-bas4">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="course_current.php">Current</a></li>
                <li class="nav-item"> <a class="nav-link" href="course_previous.php">Previous</a></li>
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
                <li class="nav-item"> <a class="nav-link" href="rental_current.php">Current</a></li>
                <li class="nav-item"> <a class="nav-link" href="rental_previous.php">Previous</a></li>
                <li class="nav-item"> <a class="nav-link" href="rental_overdue.php">Overdue</a></li>
              </ul>
            </div>
			
          </li>
		         <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#ui-basic990" aria-expanded="false" aria-controls="ui-basic990">
              <i class="menu-icon mdi mdi-key"></i>
              <span class="menu-title">Ea Guidance</span>
              <i class="menu-arrow"></i> 
            </a>
            <div class="collapse" id="ui-basic990">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="res_gui.php">Guidance</a></li>
                
              </ul>
            </div>
          </li>    
         


        </ul>
      </nav>
	  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
<a href="https://api.whatsapp.com/send?phone=+919487277924&text=Hello!" class="float" target="_blank">
<i class="fa fa-whatsapp my-float"></i>
</a>
	  	<?php
	}
		else
	{
		$_SESSION['return_to'] = $_SERVER['REQUEST_URI'];
   header("Location: " . $_SESSION['return_to']);
	}
 ?>