<?php
include_once("config.php");
 
		$id=$_GET["id"];
		$del=mysqli_query($con,"DELETE FROM test WHERE id = '$id'");
			
		echo "<script> alert('Deleted Successfully')</script>";	

header("Location: test_view.php");
 ?>
 