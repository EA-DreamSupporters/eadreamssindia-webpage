<?php
include_once("config.php");
 
		$id=$_GET["id"];
		$del=mysqli_query($con,"DELETE FROM tblbanner WHERE bannerID = '$id'");
			
		echo "<script> alert('Deleted Successfully')</script>";	

header("Location: Manage_banner.php");
 ?>
 