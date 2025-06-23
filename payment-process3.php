<?php



include('config.php');
session_start();
error_reporting(0);

if (isset($_POST['total'])) {

    $id = $_POST['id'];
    $userid = $_SESSION['id'];
    $payment_id = $_POST['razorpay_payment_id'];
    $query = mysqli_query($con, "select id from test_subscription where uid='$userid' and mid='$id'");
    $count = mysqli_num_rows($query);
    if ($count == 0) {
        // Remove payment_id from insert, as it does not exist in the table
        mysqli_query($con, "insert into test_subscription(uid,mid) values('$userid','$id')");
    } else {
        $row = mysqli_fetch_array($query);
        mysqli_query($con, "update test_subscription set status='1' where uid='$userid' and mid='$id'");
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