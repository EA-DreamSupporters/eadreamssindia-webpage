<?php
include_once("config.php");
 
		$id=$_GET["id"];
		$del=mysqli_query($con,"DELETE FROM rentalbooks WHERE rid = '$id'");
			
		echo "<script> alert('Deleted Successfully')</script>";	

header("Location: rental_view.php");
 ?>
 