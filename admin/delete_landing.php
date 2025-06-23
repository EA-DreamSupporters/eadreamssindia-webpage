<?php
include_once("config.php");
 
		$id=$_GET["id"];
		$rr=mysqli_query($con,"SELECT * FROM landing WHERE LP_ID='$id'");
$ro=mysqli_fetch_array($rr);
$img = $ro['Banner'];
$imgpath = "uploads/".$img;
unlink( $imgpath );
		$del=mysqli_query($con,"DELETE FROM landing WHERE LP_ID = '$id'");
			
		echo "<script> alert('Deleted Successfully')</script>";	

header("Location: manage_landing.php");
 ?>
 