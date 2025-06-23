<?php
    error_reporting(E_ERROR | E_PARSE);
	session_start();
	include ("config.php");	
     $userid= $_SESSION["id"];
	 $user= $_SESSION["user"];
	$sql = "SELECT * FROM students WHERE username='$user'";
    $result=mysqli_query($con, $sql);
    $row = mysqli_fetch_array($result);
    
    // Fetch button status and schedule date
$result = mysqli_query($con, "SELECT button_status, schedule_date FROM settings LIMIT 1");
$row = mysqli_fetch_assoc($result);
$button_status = $row['button_status'];
$schedule_date = $row['schedule_date'];

$show_button = false;
$button_text = "✨ New ✨";
$button_disabled = false;

if ($button_status === 'active_now') {
    $show_button = true;
} elseif ($button_status === 'scheduled' && $schedule_date) {
    $current_date = new DateTime();
    $scheduled_date = new DateTime($schedule_date);
    $diff = $current_date->diff($scheduled_date)->days;

    if ($scheduled_date <= $current_date) {
        $show_button = true;
    } else {
        $show_button = true;
        $button_disabled = true;
        $button_text = "Active in {$diff} day" . ($diff > 1 ? "s" : "");
    }
}
	
	 ?>
<?php
include("config.php");

if (isset($_POST['submit'])) {
    $sql = "SELECT * FROM students WHERE username='" . $_POST["User_Name"] . "'";
    $result = mysqli_query($con, $sql);


    $row = mysqli_fetch_array($result);

    $id = $row['sid'];
    $name = $row['username'];
    $pass = $row['password'];

    $name1 = $_POST["User_Name"];
    $pass1 = $_POST["passw"];

    $pass2 = md5($pass1);

    if ($name == $name1 & $pass == $pass2) {
        $_SESSION["id"] = $id;
        $_SESSION["user"] = $name1;

        $_SESSION['return_to'] = $_SERVER['REQUEST_URI'];
        header("Location: " . $_SESSION['return_to']);

    } else {
        echo "<script> alert('Invalid Credentials')</script>";
    }


}

if (isset($_POST['forgot'])) {

    $ret = mysqli_query($con, "SELECT * FROM students WHERE email='" . $_POST["remail"] . "'");
    $rev = mysqli_fetch_array($ret);

    $eid = $rev['sid'];
    $eemail = $rev['email'];
    $emob = $rev['phone'];

    $remail = $_POST['remail'];
    $rmob = $_POST['rmob'];

    if ($eemail == $remail && $emob == $rmob) {


        header("Location: changepassword.php?user=$eid");
    } else {
        echo "<script>alert('Invalid Details');</script>";
        $_SESSION['return_to'] = $_SERVER['REQUEST_URI'];
        header("Location: " . $_SESSION['return_to']);
    }
    mysqli_close($con);
}

?>

<style>
@media only screen and (max-width: 575px) {
    .dz-social-icon.style-3 {
        display: flex;
    }
}

@media only screen and (max-width:992px) {
    .site-header .extra-nav {
        display: block;
    }

    .dddd {
        display: none !important;
    }

    .extra-nav .extra-cell .header-right .nav-item .nav-link {
        position: absolute;
        top: 14px;
        left: -256px;
    }

    .extra-nav .extra-cell .header-right .dropdown-menu {

        margin: 77px 1px !important;
        transform: unset !important;
    }

    .reg {
        margin-left: 71px;
    }
}

@media only screen and (min-width:992px) {
    .mobile {
        display: none !important;
    }

}


.main-swiper .swiper-pagination-five {
    position: relative;
    top: 4px;
    right: -80px;
    left: unset;
}

.main-slider.style-1 .swiper-pagination-wrapper {
    position: absolute;
}

.main-swiper .swiper-pagination-five .swiper-pagination-bullet {

    background-color: #1e1e1e;
}

.swiper-pagination-five .swiper-pagination-bullet.swiper-pagination-bullet-active {
    background-color: #D9E545;
}

.main-slider .main-swiper-thumb .swiper-slide {
    opacity: 0.6;
    width: 391px;
}

.main-slider .main-swiper-thumb {

    width: 31vw;
}

.navbar-nav {
    padding-right: 10px !important;
}

.extra-nav .extra-cell .header-right .dropdown-menu {
    width: 285px !important;
}

.prebook{
	position: fixed;
	z-index: 999999999999;
	height:100vh;
	width:100%;
	background-color:#FCFCFF;
	display:flex;
	justify-content:center;
	align-items:center;
}
.prebook img{
	max-width:200px;
}
.pre{
	animation:fade 1s 1 ease-in-out forwards;
	animation-delay: 5s;
}
@keyframes fade{
	0%{
		opacity:1;
		display:flex;
		visibility:visible;
	}
	100%{
		display:none;
		opacity:0;
		visibility:hidden;
	}
}
</style>
<div class="prebook" id="prebook">
    <img src="image/prel.gif" alt="">
</div>
<header class="site-header mo-left header style-1">
    <!-- Main Header -->
    <div class="header-info-bar">
        <div class="container clearfix">
            <!-- Website Logo -->
            <div class="logo-header logo-dark">
                <a href="index"><img src="images/logo.png" alt="logo" style="max-width: 257px;
height: 56px;"></a>
            </div>

            <!-- EXTRA NAV -->
            <div class="extra-nav">
                <div class="extra-cell">
                    <ul class="navbar-nav header-right">
                        <?php if($_SESSION["user"]){ ?>

                        <li class="nav-item dropdown profile-dropdown  ms-4">

                            <a class="nav-link" href="javascript:void(0);" role="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <?php 
if($row['photo']=='')
{ ?>
                                <img src="images/profile.jpg">
                                <?php } else {
									?>
                                <img src="images/<?php echo $row['photo'] ?>" alt="/">
                                <?php } ?>
                                <div class="profile-info">
                                    <h6 class="title"><?php echo $row['name'] ?></h6>
                                    <span><?php echo $row['email'] ?></span>
                                </div>
                            </a>
                            <div class="dropdown-menu py-0 dropdown-menu-end">
                                <div class="dropdown-header">
                                    <h6 class="m-0"><?php echo $row['name'] ?></h6>
                                    <span><?php echo $row['email'] ?></span>
                                </div>
                                <div class="dropdown-body">
                                    <a href="dashboard"
                                        class="dropdown-item d-flex justify-content-between align-items-center ai-icon">
                                        <div>
                                            <svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 0 24 24"
                                                width="20px" fill="#000000">
                                                <path d="M0 0h24v24H0V0z" fill="none" />
                                                <path
                                                    d="M12 6c1.1 0 2 .9 2 2s-.9 2-2 2-2-.9-2-2 .9-2 2-2m0 10c2.7 0 5.8 1.29 6 2H6c.23-.72 3.31-2 6-2m0-12C9.79 4 8 5.79 8 8s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4zm0 10c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                                            </svg>
                                            <span class="ms-2">Dashboard</span>
                                        </div>
                                    </a>
                                    <a href="orders"
                                        class="dropdown-item d-flex justify-content-between align-items-center ai-icon">
                                        <div>
                                            <svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 0 24 24"
                                                width="20px" fill="#000000">
                                                <path d="M0 0h24v24H0V0z" fill="none" />
                                                <path
                                                    d="M15.55 13c.75 0 1.41-.41 1.75-1.03l3.58-6.49c.37-.66-.11-1.48-.87-1.48H5.21l-.94-2H1v2h2l3.6 7.59-1.35 2.44C4.52 15.37 5.48 17 7 17h12v-2H7l1.1-2h7.45zM6.16 6h12.15l-2.76 5H8.53L6.16 6zM7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zm10 0c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z" />
                                            </svg>
                                            <span class="ms-2">My Order</span>
                                            <i class="icon-dashboard"></i>
                                        </div>

                                    </a>
                                    <a href="cart"
                                        class="dropdown-item d-flex justify-content-between align-items-center ai-icon">
                                        <div>
                                            <svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 0 24 24"
                                                width="20px" fill="#000000">
                                                <path d="M0 0h24v24H0V0z" fill="none" />
                                                <path
                                                    d="M16.5 3c-1.74 0-3.41.81-4.5 2.09C10.91 3.81 9.24 3 7.5 3 4.42 3 2 5.42 2 8.5c0 3.78 3.4 6.86 8.55 11.54L12 21.35l1.45-1.32C18.6 15.36 22 12.28 22 8.5 22 5.42 19.58 3 16.5 3zm-4.4 15.55l-.1.1-.1-.1C7.14 14.24 4 11.39 4 8.5 4 6.5 5.5 5 7.5 5c1.54 0 3.04.99 3.57 2.36h1.87C13.46 5.99 14.96 5 16.5 5c2 0 3.5 1.5 3.5 3.5 0 2.89-3.14 5.74-7.9 10.05z" />
                                            </svg>
                                            <span class="ms-2">My Cart</span>
                                        </div>
                                    </a>



                                </div>
                                <div class="dropdown-footer">
                                    <a class="btn btn-primary w-100 btnhover btn-sm" href="logout">Log Out</a>
                                </div>
                            </div>
                        </li>
                        <?php } else{  ?>
                        <li class="nav-item">
                            <a class="nav-link" href="" data-bs-toggle="modal" data-bs-target="#myModal">
                                <i class="fa fa-sign-in" aria-hidden="true" style="padding-right: 5px;"></i>

                                Login
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="register">
                                <i class="fa fa-user" aria-hidden="true" style="padding-right: 5px;"></i>

                                Register
                            </a>
                        </li>
                        <?php }  ?>
                    </ul>
                </div>
            </div>


            <div class="extra-nav" style="display:none;">
                <div class="extra-cell">
                    <ul class="navbar-nav header-right">
                        <li class="nav-item">
                            <a class="nav-link" href="wishlist.html">
                                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px"
                                    fill="#000000">
                                    <path d="M0 0h24v24H0V0z" fill="none" />
                                    <path
                                        d="M16.5 3c-1.74 0-3.41.81-4.5 2.09C10.91 3.81 9.24 3 7.5 3 4.42 3 2 5.42 2 8.5c0 3.78 3.4 6.86 8.55 11.54L12 21.35l1.45-1.32C18.6 15.36 22 12.28 22 8.5 22 5.42 19.58 3 16.5 3zm-4.4 15.55l-.1.1-.1-.1C7.14 14.24 4 11.39 4 8.5 4 6.5 5.5 5 7.5 5c1.54 0 3.04.99 3.57 2.36h1.87C13.46 5.99 14.96 5 16.5 5c2 0 3.5 1.5 3.5 3.5 0 2.89-3.14 5.74-7.9 10.05z" />
                                </svg>
                                <span class="badge">21</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <button type="button" class="nav-link box cart-btn">
                                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px"
                                    fill="#000000">
                                    <path d="M0 0h24v24H0V0z" fill="none" />
                                    <path
                                        d="M15.55 13c.75 0 1.41-.41 1.75-1.03l3.58-6.49c.37-.66-.11-1.48-.87-1.48H5.21l-.94-2H1v2h2l3.6 7.59-1.35 2.44C4.52 15.37 5.48 17 7 17h12v-2H7l1.1-2h7.45zM6.16 6h12.15l-2.76 5H8.53L6.16 6zM7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zm10 0c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z" />
                                </svg>
                                <span class="badge">5</span>
                            </button>
                            <ul class="dropdown-menu cart-list">
                                <li class="cart-item">
                                    <div class="media">
                                        <div class="media-left">
                                            <a href="books-detail.html">
                                                <!--<img alt="" class="media-object" src="images/books/small/pic1.jpg">-->
                                            </a>
                                        </div>
                                        <div class="media-body">
                                            <h6 class="dz-title"><a href="books-detail.html" class="media-heading">Real
                                                    Life</a></h6>
                                            <span class="dz-price">$28.00</span>
                                            <span class="item-close">&times;</span>
                                        </div>
                                    </div>
                                </li>
                                <li class="cart-item">
                                    <div class="media">
                                        <div class="media-left">
                                            <a href="books-detail.html">
                                                <!--<img alt="" class="media-object" src="images/books/small/pic2.jpg">-->
                                            </a>
                                        </div>
                                        <div class="media-body">
                                            <h6 class="dz-title"><a href="books-detail.html"
                                                    class="media-heading">Home</a></h6>
                                            <span class="dz-price">$28.00</span>
                                            <span class="item-close">&times;</span>
                                        </div>
                                    </div>
                                </li>
                                <li class="cart-item">
                                    <div class="media">
                                        <div class="media-left">
                                            <a href="books-detail.html">
                                                <!--<img alt="" class="media-object" src="images/books/small/pic3.jpg">-->
                                            </a>
                                        </div>
                                        <div class="media-body">
                                            <h6 class="dz-title"><a href="books-detail.html" class="media-heading">Such
                                                    a fun age</a></h6>
                                            <span class="dz-price">$28.00</span>
                                            <span class="item-close">&times;</span>
                                        </div>
                                    </div>
                                </li>
                                <li class="cart-item text-center">
                                    <h6 class="text-secondary">Totle = $500</h6>
                                </li>
                                <li class="text-center d-flex">
                                    <a href="shop-cart.html" class="btn btn-sm btn-primary me-2 btnhover w-100">View
                                        Cart</a>
                                    <a href="shop-checkout.html"
                                        class="btn btn-sm btn-outline-primary btnhover w-100">Checkout</a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown profile-dropdown  ms-4">
                            <a class="nav-link" href="javascript:void(0);" role="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <!--<img src="images/profile1.jpg" alt="/">-->
                                <div class="profile-info">
                                    <h6 class="title">Brian</h6>
                                    <span>info@gmail.com</span>
                                </div>
                            </a>
                            <div class="dropdown-menu py-0 dropdown-menu-end">
                                <div class="dropdown-header">
                                    <h6 class="m-0">Brian</h6>
                                    <span>info@gmail.com</span>
                                </div>
                                <div class="dropdown-body">
                                    <a href="my-profile.html"
                                        class="dropdown-item d-flex justify-content-between align-items-center ai-icon">
                                        <div>
                                            <svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 0 24 24"
                                                width="20px" fill="#000000">
                                                <path d="M0 0h24v24H0V0z" fill="none" />
                                                <path
                                                    d="M12 6c1.1 0 2 .9 2 2s-.9 2-2 2-2-.9-2-2 .9-2 2-2m0 10c2.7 0 5.8 1.29 6 2H6c.23-.72 3.31-2 6-2m0-12C9.79 4 8 5.79 8 8s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4zm0 10c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                                            </svg>
                                            <span class="ms-2">Profile</span>
                                        </div>
                                    </a>
                                    <a href="shop-cart.html"
                                        class="dropdown-item d-flex justify-content-between align-items-center ai-icon">
                                        <div>
                                            <svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 0 24 24"
                                                width="20px" fill="#000000">
                                                <path d="M0 0h24v24H0V0z" fill="none" />
                                                <path
                                                    d="M15.55 13c.75 0 1.41-.41 1.75-1.03l3.58-6.49c.37-.66-.11-1.48-.87-1.48H5.21l-.94-2H1v2h2l3.6 7.59-1.35 2.44C4.52 15.37 5.48 17 7 17h12v-2H7l1.1-2h7.45zM6.16 6h12.15l-2.76 5H8.53L6.16 6zM7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zm10 0c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z" />
                                            </svg>
                                            <span class="ms-2">My Order</span>
                                        </div>
                                    </a>
                                    <a href="wishlist.html"
                                        class="dropdown-item d-flex justify-content-between align-items-center ai-icon">
                                        <div>
                                            <svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 0 24 24"
                                                width="20px" fill="#000000">
                                                <path d="M0 0h24v24H0V0z" fill="none" />
                                                <path
                                                    d="M16.5 3c-1.74 0-3.41.81-4.5 2.09C10.91 3.81 9.24 3 7.5 3 4.42 3 2 5.42 2 8.5c0 3.78 3.4 6.86 8.55 11.54L12 21.35l1.45-1.32C18.6 15.36 22 12.28 22 8.5 22 5.42 19.58 3 16.5 3zm-4.4 15.55l-.1.1-.1-.1C7.14 14.24 4 11.39 4 8.5 4 6.5 5.5 5 7.5 5c1.54 0 3.04.99 3.57 2.36h1.87C13.46 5.99 14.96 5 16.5 5c2 0 3.5 1.5 3.5 3.5 0 2.89-3.14 5.74-7.9 10.05z" />
                                            </svg>
                                            <span class="ms-2">Wishlist</span>
                                        </div>
                                    </a>
                                </div>
                                <div class="dropdown-footer">
                                    <a class="btn btn-primary w-100 btnhover btn-sm" href="shop-login.html">Log Out</a>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>




            <!-- header search nav -->




        </div>

    </div>

    <!-- Main Header End -->

    <!-- Main Header -->
    <div class="sticky-header main-bar-wraper navbar-expand-lg">
        <div class="main-bar clearfix">
            <div class="container clearfix">
                <!-- Website Logo -->
                <div class="logo-header logo-dark ">
                    <a href="index"><img src="images/logo.png" alt="logo" style="max-width: 257px;
height: 56px;margin-top: 17px;"></a>
                </div>

                <!-- Nav Toggle Button -->
                <button class="navbar-toggler collapsed navicon justify-content-end" type="button"
                    data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>

                <!-- EXTRA NAV -->
                <div class="extra-nav dddd">
                    <div class="extra-cell">
                        <a href="career" class="btn btn-primary btnhover">Career Counselling </a>
                    </div>
                </div>
               <?php if ($show_button): ?>
                    <div class="extra-nav extra-nav-outside">
                        <div class="extra-cell">
                            <?php if (!$button_disabled): ?>
                                <a href="landing" class="btn btnhover custom-btn"><?php echo $button_text; ?></a>
                            <?php else: ?>
                                <a class="btn custom-btn disabled-btn"
                                    style="pointer-events: none;"><?php echo $button_text; ?></a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>


                <style>
                    .custom-btn {
                        padding: 10px 20px;
                        font-size: 16px;
                        border: 2px solid rgb(26, 22, 104);
                        background-color: transparent;
                        color: rgb(26, 22, 104);
                        border-radius: 5px;
                        transition: all 0.3s ease-in-out;
                    }

                    .custom-btn:hover {
                        background-color: rgb(26, 22, 104);
                        color: white;
                        border-color: rgb(14, 12, 70);
                    }

                    .custom-btn:focus {
                        outline: none;
                        box-shadow: 0 0 5px 2px rgba(26, 22, 104, 0.5);
                    }

                    .disabled-btn {
                        opacity: 0.6;
                        cursor: not-allowed;
                    }

                    @media only screen and (max-width: 992px) {
                        .extra-nav-outside {
                            display: none !important;
                        }
                    }
                </style>

                <!-- Main Nav -->
                <div class="header-nav navbar-collapse collapse justify-content-start" id="navbarNavDropdown">
                    <ul class="navbar-nav mobile">
                        <li>
                            <div class="extra-nav">
                                <div class="extra-cell">
                                    <ul class="navbar-nav header-right">
                                        <?php if($_SESSION["user"]){ ?>



                                        <li class="nav-item dropdown profile-dropdown  ms-4">

                                            <a class="nav-link" href="javascript:void(0);" role="button"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                <?php 
if($row['photo']=='')
{ ?>
                                                <img src="images/profile.jpg">
                                                <?php } else {
									?>
                                                <img src="images/<?php echo $row['photo'] ?>" alt="/">
                                                <?php } ?>
                                                <div class="profile-info">
                                                    <h6 class="title"><?php echo $row['name'] ?></h6>
                                                    <span><?php echo $row['email'] ?></span>
                                                </div>
                                            </a>
                                            <div class="dropdown-menu py-0 dropdown-menu-end">
                                                <div class="dropdown-header">
                                                    <h6 class="m-0"><?php echo $row['name'] ?></h6>
                                                    <span><?php echo $row['email'] ?></span>
                                                </div>
                                                <div class="dropdown-body">
                                                    <a href="dashboard.php" target="_blank"
                                                        class="dropdown-item d-flex justify-content-between align-items-center ai-icon">
                                                        <div>
                                                            <svg xmlns="http://www.w3.org/2000/svg" height="20px"
                                                                viewBox="0 0 24 24" width="20px" fill="#000000">
                                                                <path d="M0 0h24v24H0V0z" fill="none" />
                                                                <path
                                                                    d="M12 6c1.1 0 2 .9 2 2s-.9 2-2 2-2-.9-2-2 .9-2 2-2m0 10c2.7 0 5.8 1.29 6 2H6c.23-.72 3.31-2 6-2m0-12C9.79 4 8 5.79 8 8s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4zm0 10c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                                                            </svg>
                                                            <span class="ms-2">Dashboard</span>
                                                        </div>
                                                    </a>
                                                    <a href="orders.php"
                                                        class="dropdown-item d-flex justify-content-between align-items-center ai-icon">
                                                        <div>
                                                            <svg xmlns="http://www.w3.org/2000/svg" height="20px"
                                                                viewBox="0 0 24 24" width="20px" fill="#000000">
                                                                <path d="M0 0h24v24H0V0z" fill="none" />
                                                                <path
                                                                    d="M15.55 13c.75 0 1.41-.41 1.75-1.03l3.58-6.49c.37-.66-.11-1.48-.87-1.48H5.21l-.94-2H1v2h2l3.6 7.59-1.35 2.44C4.52 15.37 5.48 17 7 17h12v-2H7l1.1-2h7.45zM6.16 6h12.15l-2.76 5H8.53L6.16 6zM7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zm10 0c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z" />
                                                            </svg>
                                                            <span class="ms-2">My Order</span>
                                                            <i class="icon-dashboard"></i>
                                                        </div>
                                                    </a>
                                                    <a href="checkout.php"
                                                        class="dropdown-item d-flex justify-content-between align-items-center ai-icon">
                                                        <div>
                                                            <svg xmlns="http://www.w3.org/2000/svg" height="20px"
                                                                viewBox="0 0 24 24" width="20px" fill="#000000">
                                                                <path d="M0 0h24v24H0V0z" fill="none" />
                                                                <path
                                                                    d="M16.5 3c-1.74 0-3.41.81-4.5 2.09C10.91 3.81 9.24 3 7.5 3 4.42 3 2 5.42 2 8.5c0 3.78 3.4 6.86 8.55 11.54L12 21.35l1.45-1.32C18.6 15.36 22 12.28 22 8.5 22 5.42 19.58 3 16.5 3zm-4.4 15.55l-.1.1-.1-.1C7.14 14.24 4 11.39 4 8.5 4 6.5 5.5 5 7.5 5c1.54 0 3.04.99 3.57 2.36h1.87C13.46 5.99 14.96 5 16.5 5c2 0 3.5 1.5 3.5 3.5 0 2.89-3.14 5.74-7.9 10.05z" />
                                                            </svg>
                                                            <span class="ms-2">My Cart</span>
                                                        </div>
                                                    </a>

                                                </div>
                                                <div class="dropdown-footer">
                                                    <a class="btn btn-primary w-100 btnhover btn-sm"
                                                        href="logout.php">Log Out</a>
                                                </div>
                                            </div>
                                        </li>
                                        <?php } else{  ?>
                                        <li class="nav-item">
                                            <a class="nav-link" data-bs-toggle="modal" data-bs-target="#myModal">
                                                <i class="fa fa-sign-in" aria-hidden="true"
                                                    style="padding-right: 5px;"></i>

                                                Login
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link reg" href="register">
                                                <i class="fa fa-user" aria-hidden="true"
                                                    style="padding-right: 5px;"></i>

                                                Register
                                            </a>
                                        </li>
                                        <?php }  ?>
                                    </ul>
                                </div>
                            </div>
                        </li>

                    </ul>
                    <ul class="nav navbar-nav">
                        <style>
                            .mm {
                                display: none;
                            }

                            .fiximg {
                                padding: unset !important;
                                position: relative;
                                right: 10px;
                            }

                            @media only screen and (max-width:992px) {

                                .mm {
                                    display: none;
                                }

                                .fiximg img {
                                    display: none;
                                }


                            }

                            #loginPopup {
                                display: none;
                                position: fixed;
                                z-index: 1;
                                left: 0;
                                top: 0;
                                width: 100%;
                                height: 100%;
                                overflow: auto;
                                background-color: rgba(0, 0, 0, 0.5);
                            }

                            .popupContent {
                                background-color: #fefefe;
                                margin: 15% auto;
                                padding: 20px;
                                border: 1px solid #888;
                                width: 50%;
                            }

                            #closeBtn {
                                margin-top: 10px;
                            }

                            #loginPopup {

                                z-index: 100;
                            }

                            .modal-backdrop {
                                z-index: 10000;
                            }

                            .alert {
                                background-color: #f44336;
                                /* Red */
                                color: white;
                                padding: 12px;
                                border: none;
                                border-radius: 4px;
                                font-size: 18px;
                                margin-bottom: 15px;
                            }
                        </style>
                        <li class="mm"><a href="index" class="fiximg"><img src="image/ll.png" alt="logo"
                                    width="79px"></a>

                        </li>
                        <li><a href="index"><span>Home</span></a>

                        </li>
                        <li><a href="about-us"><span>About Us</span></a></li>
                        <li class="sub-menu-down"><a href="#"><span>Products</span></a>
                            <ul class="sub-menu">
                                <li><a href="buy-book">Buy a Book</a></li>
                                <li><a href="subscribe">Magazines - Subscription</a></li>
                                <li><a href="rent-book">Rent a Book</a></li>
                                <!--<li><a href="course_edu">Educator's Course</a></li>-->
                                <!--<li><a href="coursefull">Institute's Course</a></li>-->
                                <li><a href="testefull">Test Batch</a></li>
                            </ul>
                        </li>

                        <li><a href="self_preparation"><span>Self Study India</span></a>

                        </li>
                        <li><a href="guidance"><span>GovPrep</span></a>

                        </li>
                        <li><a href="contact-us"><span>Contact us</span></a></li>
                        <li><a href="vendors_registration"><span>Partners</span></a></li>

                    </ul>





                    <div class="dz-social-icon">
                        <div class="extra-cell" id="car">
                            <a href="career" style="width: unset; height: unset;padding: 5px;"
                                class="btn btn-primary btnhover">Career Counselling</a>
                        </div>
                        &nbsp;
                        <?php if ($show_button): ?>
                            <div class="extra-cell mobile-only" id="car">
                                <?php if (!$button_disabled): ?>
                                    <a href="landing" class="btn btnhover custom-btn-mbl" style="width: auto;"><span
                                            style="font-family: 'Segoe UI Emoji', 'Arial', sans-serif;"><?php echo $button_text; ?></span></a>
                                <?php else: ?>
                                    <a class="btn custom-btn-mbl disabled-btn" style="pointer-events: none; width:
                                        auto"><span style="font-family: 'Segoe UI Emoji' , 'Arial' ,
                                            sans-serif;"><?php echo $button_text; ?></span></a>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                        &nbsp;
                        <style>
                            .custom-btn-mbl {
                                padding: 10px 20px;
                                font-size: 16px;
                                border: 2px solid rgb(26, 22, 104);
                                background-color: transparent;
                                color: rgb(26, 22, 104);
                                border-radius: 5px;
                                transition: all 0.3s ease-in-out;
                            }

                            .custom-btn-mbl:hover {
                                background-color: rgb(26, 22, 104);
                                color: white;
                                border-color: rgb(14, 12, 70);
                            }

                            .custom-btn-mbl:focus {
                                outline: none;
                                box-shadow: 0 0 5px 2px rgba(26, 22, 104, 0.5);
                            }

                            .disabled-btn {
                                opacity: 0.6;
                                cursor: not-allowed;
                            }

                            @media only screen and (min-width: 993px) {
                                .mobile-only {
                                    display: none !important;
                                }
                            }
                        </style>
                    </div>
                    
                    <div class="dz-social-icon" style="padding: unset;">
                        <ul>
                            <li><a class="fab fa-facebook-f" target="_blank"
                                    href="https://www.facebook.com/profile.php?id=100084243005569"></a></li>
                            <li><a class="fab fa-twitter" target="_blank"
                                    href="https://twitter.com/eadreamssindia?t=g7l770eakCwtbzWWAG6FCg&s=09"></a></li>
                            <li><a class="fab fa-linkedin-in" target="_blank"
                                    href="https://www.linkedin.com/in/eadreamssindia"></a></li>
                            <li><a class="fab fa-instagram" target="_blank"
                                    href="https://www.instagram.com/eadreamssindia/"></a></li>
                            <br>


                        </ul>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Main Header End -->

</header>

<div class="modal" id="myModal">
    <div class="modal-dialog ">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">

                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">

                <div class="tab-content container">
                    <form id="login" class="tab-pane active col-12" method="post">
                        <h4 class="text-secondary">LOGIN</h4>
                        <p class="font-weight-600">If you have an account with us, please log in.</p>
                        <div class="mb-4">
                            <label class="label-title">USERNAME *</label>
                            <input name="User_Name" required="" class="form-control" placeholder="Your Username"
                                type="text">
                        </div>
                        <div class="mb-4">
                            <label class="label-title">PASSWORD *</label>
                            <input name="passw" required="" class="form-control " placeholder="Type Password"
                                type="password">
                        </div>
                        <div class="text-left">

                            <button type="submit" class="btn btn-primary btnhover me-2" name="submit">login</button>
                            <a href="register" class="btn btn-outline-primary">New User</a>
                            <a data-bs-toggle="tab" href="#forgot-password" class="m-l5" style="color:grey;"><i
                                    class="fas fa-unlock-alt" style="color:grey;"></i> Forgot Password</a>&nbsp;
									
                        </div>
                    </form>
                    <form id="forgot-password" class="tab-pane fade  col-12" method="post">
                        <h4 class="text-secondary">FORGET PASSWORD ?</h4>
                        <p class="font-weight-600">* Verify Your Email ID & Mobile number to Change Password *</p>
                        <div class="mb-3">
                            <label class="label-title">Registered E-mail *</label>
                            <input name="remail" required="" class="form-control" placeholder="Registered Email Id"
                                type="text">
                        </div>
                        <div class="mb-3">
                            <label class="label-title">Registered Mobile Number *</label>
                            <input name="rmob" required="" class="form-control" placeholder="Registered Mobile Number"
                                type="text">
                        </div>
                        <div class="text-left">
                            <a class="btn btn-outline-secondary btnhover m-r10" data-bs-toggle="tab"
                                href="#login">Back</a>
                            <button class="btn btn-primary btnhover" name="forgot">Submit</button>
                        </div>
                    </form>
                </div>

            </div>

            <!-- Modal footer -->

        </div>
    </div>
</div>
<script>
window.addEventListener('scroll', function() {
    var scrollPosition = window.scrollY;
    var image = document.querySelector('.mm');
    if (scrollPosition > 200) {
        image.style.display = 'block';
    } else {
        image.style.display = 'none';
    }
});
window.addEventListener('load', function() {
	var element = document.getElementById("prebook");
   element.classList.toggle("pre");
});
</script>