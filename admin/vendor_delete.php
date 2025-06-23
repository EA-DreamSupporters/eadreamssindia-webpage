<?php
include_once("config.php");
 
		$id=$_GET["id"];
		$del=mysqli_query($con,"DELETE FROM vendors WHERE vid = '$id'");
			
		echo "<script> alert('Deleted Successfully')</script>";	

header("Location: manage_vendors.php");
 ?>
 