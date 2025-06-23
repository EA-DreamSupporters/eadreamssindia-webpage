<?php
session_start();
include('config.php');

error_reporting(0);

if(isset($_POST["total"]))
{
 $uid=$_SESSION["id"];
 $name = $_POST['name']; //getting customer name
 $fromEmail = $_POST['email']; //getting customer email
 $phone = $_POST['phone']; //getting customer Phome number
 $dob = $_POST['dob']; //getting customer Phome number
 $pass = $_POST['passed']; //getting customer Phome number
 $pyear = $_POST['pyear']; //getting customer Phome number
 $self = $_POST['self']; //getting customer Phome number
 $address = $_POST['address']; //getting customer Phome number
 $subject = $_POST['subject']; //getting  line from client
 $payment_id = $_POST['razorpay_payment_id'];
 $plan = $_POST['plan'];

 $total = $_POST['total'];

	$ret=mysqli_query($con,"Insert into guide (user,name,dob,email,phone,pout,poutyear,pace,address,msg,plan,payment_id,amount) values('$uid','$name','$dob','$fromEmail','$phone','$pass','$pyear','$self','$address','$subject','$plan','$payment_id','$total')");


    $data = [ 

        'payment_id' => $_POST['razorpay_payment_id'],
        'amount' => $_POST['total'],
        'plan' => $_POST['plan'],
        ];
   
        $arr = array('msg' => 'Payment successfully credited', 'status' => true);  
        echo json_encode($arr);
        

        $_SESSION['payment_id'] = $payment_id;

}
?>