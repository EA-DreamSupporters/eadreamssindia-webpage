<?php
include_once("config.php");
 
		$id=$_GET["id"];
		$del=mysqli_query($con,"DELETE FROM subcategory WHERE subcategory_id = '$id'");
			
		echo "<script> alert('Deleted Successfully')</script>";	

header("Location: subcategory_view.php");
 ?>
 