<?php



include('config.php');
session_start();
error_reporting(0);
date_default_timezone_set("Asia/Calcutta");
if(isset($_POST['total'])){
	

    $userid=$_SESSION['id']; 
    $magazine=$_POST['magazine'];
      $splan=implode(',',$_POST["splan"]); 
      $sprice=implode(',',$_POST["sprice"]); 
   $payment_id = $_POST['razorpay_payment_id'];
   date_default_timezone_set("Asia/Kolkata");
   $date=date("Y-m-d"); 
   $myDate = date("Y-m-d", strtotime( date( "Y-m-d", strtotime( date("Y-m-d") ) ) . "+$splan month" ) );
      $check=mysqli_query($con,"select * from subscriptions where magazine='$magazine' AND user_id='$userid'");
      $row2 = mysqli_fetch_array($check);
       $subid=$row2["sub_id"];
      $checkrows=mysqli_num_rows($check);
  
     if($checkrows>0) {
        $sql1=mysqli_query($con, "UPDATE subscriptions SET user_id='$userid',sdate='$date',magazine='$magazine',splan='$splan',sprice='$sprice',edate='$myDate',payment_id='$payment_id' WHERE sub_id='$subid'");

     } else {
  mysqli_query($con,"insert into subscriptions(user_id,sdate,magazine,splan,sprice,edate,payment_id) values('$userid','$date','$magazine','$splan','$sprice','$myDate','$payment_id')");
     
 
  }
  
    
       



$data = [ 

'payment_id' => $_POST['razorpay_payment_id'],
'amount' => $_POST['total'],
'product_id' => $id,
];
// you can write your database insertation code here
// after successfully insert transaction in database, pass the response accordingly
$arr = array('msg' => 'Payment successfully credited', 'status' => true);  
echo json_encode($arr);



}
?>