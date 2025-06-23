<?php
include_once("config.php");
 
		$id=$_GET["id"];
		$del=mysqli_query($con,"DELETE FROM books WHERE book_id = '$id'");
			
		echo "<script> alert('Deleted Successfully')</script>";	

header("Location: book_view.php");
 ?>
 