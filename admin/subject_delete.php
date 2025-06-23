<?php
include_once("config.php");
 
		$id=$_GET["id"];
		$del=mysqli_query($con,"DELETE FROM subject WHERE subjectid = '$id'");
			
		echo "<script> alert('Deleted Successfully')</script>";	

header("Location: subject_view.php");
 ?>
 