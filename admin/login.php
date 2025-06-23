<?php
session_start();
include ("config.php");

if(isset($_POST['submit']))
{
$sql = "SELECT * FROM user WHERE username='".$_POST["User_Name"]."'";
$result=mysqli_query($con,$sql);


$row = mysqli_fetch_array($result);

$id=$row['id'];
$name=$row['username'];
$pass=$row['password']; 

$name1=$_POST["User_Name"];
$pass1=$_POST["passw"];

$pass2=md5($pass1);

if($name==$name1 & $pass==$pass2)
	{
	
	$_SESSION["admin"] = $name1;
 header("Location:index.php");
	}
	else
	{
    echo "<script> alert('Invalid Credentials')</script>";
    echo "<script>  window.location.assign('login.php')</script>";
	}

mysqli_close($con);
 }
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Admin Login -  </title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="vendors/feather/feather.css">
  <link rel="stylesheet" href="vendors/mdi/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" href="vendors/typicons/typicons.css">
  <link rel="stylesheet" href="vendors/simple-line-icons/css/simple-line-icons.css">
  <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
  <!-- endinject -->
  <!-- Plugin css for this page -->
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="css/vertical-layout-light/style.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="images/favicon.png" />
</head>

<style>
.auth .brand-logo img {
    width: 80%;
}
.content-wrapper {
  background-image: url("https://i.pinimg.com/originals/67/18/22/671822c2f63dd5f65d8fd15c9710420b.jpg");
  background-color: #cccccc;
  height: 500px;
  background-position: center;
  background-repeat: no-repeat;
  background-size: cover;
  position: relative;
}
.auth-form-light.text-left.py-5.px-4.px-sm-5 {
    border-radius: 6px;
    border: 1px solid #dee2e6;
}
.content-wrapper {
  height: 100vh;

  background-size: 110% 110%;
  background-position: center center;
  animation: shrink 5s infinite alternate;
}
@keyframes shrink {
  0% {
    background-size: 110% 110%;
  }
  100% {
    background-size: 100% 100%;
  }
}
</style>
<body>
  <div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="content-wrapper d-flex align-items-center auth px-0">
        <div class="row w-100 mx-0">
          <div class="col-lg-4 mx-auto">
            <div class="auth-form-light text-left py-5 px-4 px-sm-5">
              <div class="brand-logo">
			  
                <h2 align="center"><img src="images/logo.png" alt="logo" /></h2>
              </div>
              <h6 class="fw-light">Sign in to continue.</h6>
              <form class="pt-3" action="" method="post">
			  <div class="form-group">
                  <input type="text" class="form-control form-control-lg" id="exampleInputEmail1" name="User_Name" placeholder="Username">
                </div>
                <div class="form-group">
                  <input type="password" class="form-control form-control-lg" id="exampleInputPassword1" name="passw" placeholder="Password">
                </div>
                <div class="mt-3">
					<input type="submit" value="SIGN IN" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn" name ="submit">
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      <!-- content-wrapper ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->
  <!-- plugins:js -->
  <script src="vendors/js/vendor.bundle.base.js"></script>
  <!-- endinject -->
  <!-- Plugin js for this page -->
  <script src="vendors/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
  <!-- End plugin js for this page -->
  <!-- inject:js -->
  <script src="js/off-canvas.js"></script>
  <script src="js/hoverable-collapse.js"></script>
  <script src="js/template.js"></script>
  <script src="js/settings.js"></script>
  <script src="js/todolist.js"></script>
  <!-- endinject -->
</body>

</html>
<script>
var close = document.getElementsByClassName("closebtn");
var i;

for (i = 0; i < close.length; i++) {
  close[i].onclick = function(){
    var div = this.parentElement;
    div.style.opacity = "0";
    setTimeout(function(){ div.style.display = "none"; }, 600);
  }
}
</script>