<?php
include_once("config.php");
 
		$id=$_GET["id"];
		$del=mysqli_query($con,"DELETE FROM students WHERE sid = '$id'");
			
		echo "<script> alert('Deleted Successfully')</script>";	

header("Location: user_view.php");
 ?>
 