<?php



include('config.php');
session_start();
error_reporting(0);
date_default_timezone_set("Asia/Calcutta");
if(isset($_POST['total'])){
	

    
    $id=$_POST['id']; 
    $userid=$_SESSION['id']; 
    $payment_id = $_POST['razorpay_payment_id'];
    $query = mysqli_query($con, "SELECT id FROM course_subscription WHERE uid='$userid' AND cid='$id'");
    $count = mysqli_num_rows($query);

    if($count == 0) {
        // Insert a new record into the database for subscription
        $insert_query = mysqli_query($con,"INSERT INTO course_subscription (uid, cid, payment_id) VALUES ('$userid', '$id', '$payment_id')");
        
        $response = array('msg' => 'Payment successfully credited', 'status' => true);
      
    } else {
        $response = array('msg' => 'Payment_Failed | Course is already in your possession.', 'status' => false);
    }
    

    echo json_encode($response);
    




}
?>