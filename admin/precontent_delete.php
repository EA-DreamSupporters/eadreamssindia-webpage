<?php
include_once("config.php");
 
		$id=$_GET["id"];
		$del=mysqli_query($con,"DELETE FROM precontent WHERE p_id = '$id'");
			
		echo "<script> alert('Deleted Successfully')</script>";	

header("Location: precontent_view.php");
 ?>
 