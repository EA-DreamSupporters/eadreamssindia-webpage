<?php
include_once("config.php");
 
		$id=$_GET["id"];
		$del=mysqli_query($con,"DELETE FROM subscription WHERE subid = '$id'");
			
		echo "<script> alert('Deleted Successfully')</script>";	

header("Location: sub.php");
 ?>
 