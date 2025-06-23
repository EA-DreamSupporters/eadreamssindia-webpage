<?php
include('config.php');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
date_default_timezone_set("Asia/Calcutta");
if (isset($_POST['total'])) {
        $address = $_POST['selectedaddress'];
        $gtotal = $_POST['total'];
        $_SESSION['address'] = $address;
        $_SESSION['gtotal'] = $gtotal;
        $name = $_POST['fname'];
        $orderno = mt_rand(100000000, 999999999);
        $userid = $_SESSION['id'];
        $address = $_SESSION['address'];
        $totalamount = $_POST['total'];
        $txntype = "Online Payment";
        $phone = $_POST['txnnumber'];
        $block = $_POST['block'];
        $city = $_POST['city'];
        $state = $_POST['state'];
        $zip = $_POST['zip'];
        $notes = $_POST['notes'];
        $payment_id = $_POST['razorpay_payment_id'];
        $query = mysqli_query($con, "insert into online_order(orderno,name,phone,uid,addressId,amount,mode,transacton,block,city,state,zip,notes) values('$orderno','$name','$phone','$userid','$address','$gtotal','$txntype','$payment_id','$block','$city','$state','$zip','$notes')");
        if ($query) {
                $sql = "insert into ordersdetails (userId,productId,quantity) select userID,productId,productQty from cart where userID='$userid';";
                $sql .= "update ordersdetails set orderNumber='$orderno' where userId='$userid' and orderNumber is null;";
                $sql .= "delete from  cart where userID='$userid'";
                $result = mysqli_multi_query($con, $sql);
                // --- AFFILIATE REFERRAL COMMISSION LOGIC (SCHEMA-COMPLIANT) ---
                if (isset($_SESSION['affiliate_ref']) && isset($_SESSION['affiliate_product'])) {
                    $referral_code = $_SESSION['affiliate_ref'];
                    $buyer_user_id = isset($_SESSION['id']) ? $_SESSION['id'] : 0;
                    $product = $_SESSION['affiliate_product'];
                    $referrer_query = mysqli_query($con, "SELECT sid FROM students WHERE referral_code='$referral_code'");
                    if ($referrer_row = mysqli_fetch_assoc($referrer_query)) {
                        $referrer_user_id = $referrer_row['sid'];
                        if ($referrer_user_id != $buyer_user_id) {
                            // Parse product string to get type and id (e.g., book_123)
                            if (preg_match('/^(book|course|test)_(\\d+)$/', $product, $pmatch)) {
                                $product_type = $pmatch[1];
                                $product_id = $pmatch[2];
                                // Prevent duplicate commission for same buyer/product/order
                                $exists = mysqli_query($con, "SELECT id FROM affiliate_referrals WHERE buyer_user_id='$buyer_user_id' AND product_type='$product_type' AND product_id='$product_id'");
                                if (mysqli_num_rows($exists) == 0) {
                                    $now = date('Y-m-d H:i:s');
                                    // Insert into affiliate_referrals (schema: referrer_user_id, buyer_user_id, product_type, product_id, referral_code, created_at)
                                    mysqli_query($con, "INSERT INTO affiliate_referrals (referrer_user_id, buyer_user_id, product_type, product_id, referral_code, created_at) VALUES ('$referrer_user_id', '$buyer_user_id', '$product_type', '$product_id', '$referral_code', '$now')");
                                    $referral_id = mysqli_insert_id($con);
                                    // Get price and commission percent from correct table
                                    $commission_amount = 0;
                                    $commission_percent = 10;
                                    if ($product_type == 'book') {
                                        $row = mysqli_fetch_assoc(mysqli_query($con, "SELECT price, affiliate_commission_percent FROM books WHERE book_id='$product_id'"));
                                        if ($row) {
                                            $commission_percent = isset($row['affiliate_commission_percent']) ? $row['affiliate_commission_percent'] : 10;
                                            $commission_amount = round($row['price'] * ($commission_percent / 100), 2);
                                        }
                                    } elseif ($product_type == 'course') {
                                        $row = mysqli_fetch_assoc(mysqli_query($con, "SELECT price, affiliate_commission_percent FROM course WHERE course_id='$product_id'"));
                                        if ($row) {
                                            $commission_percent = isset($row['affiliate_commission_percent']) ? $row['affiliate_commission_percent'] : 10;
                                            $commission_amount = round($row['price'] * ($commission_percent / 100), 2);
                                        }
                                    } elseif ($product_type == 'test') {
                                        $row = mysqli_fetch_assoc(mysqli_query($con, "SELECT price, affiliate_commission_percent FROM test WHERE test_id='$product_id'"));
                                        if ($row) {
                                            $commission_percent = isset($row['affiliate_commission_percent']) ? $row['affiliate_commission_percent'] : 10;
                                            $commission_amount = round($row['price'] * ($commission_percent / 100), 2);
                                        }
                                    }
                                    // Insert into affiliate_commissions (schema: referral_id, referrer_user_id, buyer_user_id, product_type, product_id, order_id, commission_amount, status, created_at)
                                    $order_id = isset($orderno) ? $orderno : null;
                                    mysqli_query($con, "INSERT INTO affiliate_commissions (referral_id, referrer_user_id, buyer_user_id, product_type, product_id, order_id, commission_amount, status, created_at) VALUES ('$referral_id', '$referrer_user_id', '$buyer_user_id', '$product_type', '$product_id', '$order_id', '$commission_amount', 'pending', '$now')");
                                }
                            }
                        }
                    }
                    unset($_SESSION['affiliate_ref']);
                    unset($_SESSION['affiliate_product']);
                }
                // --- END AFFILIATE REFERRAL COMMISSION LOGIC (SCHEMA-COMPLIANT) ---
                $arr = array('msg' => 'Payment successfully credited', 'status' => true);
                echo json_encode($arr);
        } else {
                $arr = array('msg' => 'Order insert failed', 'status' => false);
                echo json_encode($arr);
        }
}
