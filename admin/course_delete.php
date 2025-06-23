<?php
include_once("config.php");
 
		$id=$_GET["id"];
		$del=mysqli_query($con,"DELETE FROM course WHERE id = '$id'");
			
		echo "<script> alert('Deleted Successfully')</script>";	

header("Location: course_view.php");
 ?>
 