<?php
include('config.php');
if (isset($_POST["submit"]))
		{							

 $name=$_POST["name"];
 $dob=$_POST["dob"];
 $email=$_POST["email"];
 $contact=$_POST["contact"];
 $passedout=$_POST["passedout"];
 $ypass=$_POST["ypass"];
 $self=$_POST["self"];
 $address=$_POST["address"];
 $message=$_POST["message"];
 	
$sql=mysqli_query($con, "INSERT INTO enquiry(name,birth,email,contact,passout,ypass,self,address,message) VALUES('$name','$dob','$email','$contact','$passedout','$ypass','$self','$address','$message')");


         echo "<script> alert('Added Successfully')</script>";	
		 echo "<script>  window.location.assign('guidance.php')</script>";

		
	}
?> 