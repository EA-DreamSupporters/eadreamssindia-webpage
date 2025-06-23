<?php  
error_reporting(0); 
    // define variables to empty values  
    $nameErr = $emailErr = $mobilenoErr = "";  
    $name = $email = $mobileno = ""; 
	
  //Input fields validation  
   if(isset($_POST["submit"]))
{
          
    //String Validation  
        if (empty($_POST["name"])) {  
		 echo "<script> alert('Name is required')</script>";
		 echo "<script>document.location.href='contact-us.php'</script>";
		 
             $nameErr = "Name is required";  
        } 
		else{  
            $name = input_data($_POST["name"]);  
                // check if name only contains letters and whitespace  
                if (!preg_match("/^[a-zA-Z ]*$/",$name)) { 
 echo "<script> alert('Only alphabets and white space are allowed')</script>";
 echo "<script>document.location.href='contact-us.php'</script>";; 
                    $nameErr = "Only alphabets and white space are allowed";  
                }  
        }  
          
        //Email Validation   
        if (empty($_POST["email"])) {  
		 echo "<script> alert('Email is required')</script>";	
		  echo "<script>document.location.href='contact-us.php'</script>";
                $emailErr = "Email is required";  
        } else {  
                $email = input_data($_POST["email"]);  
                // check that the e-mail address is well-formed  
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { 
 echo "<script> alert('Invalid email format')</script>";	
 echo "<script>document.location.href='contact-us.php'</script>";
                    $emailErr = "Invalid email format";  
                }  
         }  
         
        //Number Validation  
        if (empty($_POST["phone"])) {  
		 echo "<script> alert('Mobile no is required')</script>";
 echo "<script>document.location.href='contact-us.php'</script>";	 
                $mobilenoErr = "Mobile no is required";  
        } else {  
                $mobileno = input_data($_POST["phone"]);  
                // check if mobile no is well-formed  
                if (!preg_match ("/^[0-9]*$/", $mobileno) ) { 
 echo "<script> alert('Only numeric value is allowed.')</script>";	
 echo "<script>document.location.href='contact-us.php'</script>";
                $mobilenoErr = "Only numeric value is allowed.";  
                }  
          
        }   
		
		if($nameErr != "" || $emailErr != "" ||  $mobilenoErr != "" ){
			$err ="somthing wrong!";
			 echo "<script>document.location.href='contact-us.php'</script>";
		}
		else{
			 $mailto = "eadreamkkdi@gmail.com";  //My email address
 //getting customer data
 $name = $_POST['name']; //getting customer name
 $fromEmail = $_POST['email']; //getting customer email
 $phone = $_POST['phone']; //getting customer Phome number
 $subject = $_POST['subject']; //getting  line from client
 $subject2 = "Confirmation: Message was submitted successfully!"; // For customer confirmation
 
 
 
 //Email body I will receive
 $message = "Cleint Name: " . $name . "\n"
 . "Phone Number: " . $phone . "\n\n"
 . "Client Message: " . "\n" . $_POST['subject'];
 
 //Message for client confirmation
 $message2 = "Dear" . $name . "\n"
 . "Thank you for contacting us. We will get back to you shortly!" . "\n\n"
 . "You submitted the following message: " . "\n" . $_POST['subject'] . "\n\n"
 . "Regards," . "\n" . "-  EA Dream Supporters";
 
 //Email headers
 $headers  = "From: " . $fromEmail; // Client email, I will receive
 $headers2 = "From: " . $mailto; // This will receive client
 
 //PHP mailer function
 
  $result1 = mail($mailto, $subject, $message, $headers); // This email sent to My address
  $result2 = mail($fromEmail, $subject2, $message2, $headers2); //This confirmation email to client
 
  //Checking if Mails sent successfully popup

    if ($result1 && $result2) {
    echo "<script>alert('Your Message was sent Successfully!');</script>";
	echo "<script>document.location.href='contact-us.php'</script>";
  } 
  else {
	   echo "<script>alert('Sorry! Message was not sent, Try again Later.!');</script>";
	   echo "<script>document.location.href='contact-us.php'</script>";
	  }
		}
		
		
    }
	
    function input_data($data) {  
      $data = trim($data);  
      $data = stripslashes($data);  
      $data = htmlspecialchars($data);  
      return $data;  
    } 
	
?>

