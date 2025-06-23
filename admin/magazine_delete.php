<?php
include_once("config.php");
 
		$id=$_GET["id"];
		$del=mysqli_query($con,"DELETE FROM magazines WHERE mid = '$id'");
			
		echo "<script> alert('Deleted Successfully')</script>";	

header("Location: magazine_view.php");
 ?>
 