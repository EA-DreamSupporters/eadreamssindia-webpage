<?php
include_once("config.php");
 
		$id=$_GET["id"];
		$del=mysqli_query($con,"DELETE FROM topic WHERE topicid = '$id'");
			
		echo "<script> alert('Deleted Successfully')</script>";	

header("Location: topic_view.php");
 ?>
 