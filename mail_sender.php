<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'script/PHPMailer/PHPMailer.php';
require 'script/PHPMailer/SMTP.php';
require 'script/PHPMailer/Exception.php';
require 'config.php'; // mysqli connection

// Use PDO for prepared queries (for mail_log table logic)
$pdo = new PDO("mysql:host=localhost;dbname=u978061437_eadreams", "u978061437_EAdreams_admin", "EAdreams@1234");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$mail = new PHPMailer(true);
$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->Username = 'eadreamssindia@gmail.com'; // Gmail
$mail->Password = 'anei lxjq pzgb raue'; // App password
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$mail->Port = 587;

$mail->setFrom('yourgmail@gmail.com', 'Your Website');
$mail->addAddress('eadreamssindia@gmail.com', 'Admin');
$mail->isHTML(true);


// --- COD Order ---
$result = mysqli_query($con, "SELECT * FROM orders WHERE orderStatus = 'Unprocessed' ORDER BY orderDate DESC LIMIT 1");
$cod = mysqli_fetch_assoc($result);

if ($cod) {
    $orderno = $cod['orderNumber'];
    $stmt = $pdo->prepare("SELECT 1 FROM mail_log WHERE order_type = 'cod' AND order_no = ?");
    $stmt->execute([$orderno]);

    if ($stmt->rowCount() === 0) {
        $mail->Subject = "New COD Order Received – {$orderno}";
        $mail->Body = "
            <h3>New COD Order Received</h3>
            <p><strong>Order No:</strong> {$orderno}</p>
            <p><strong>Amount:</strong> ₹{$cod['totalAmount']}</p>
            <p><strong>City:</strong> {$cod['city']}, {$cod['state']} - {$cod['zip']}</p>
            <p><strong>Notes:</strong> {$cod['notes']}</p>
        ";
        $mail->send();

        $pdo->prepare("INSERT INTO mail_log (order_type, order_no) VALUES ('cod', ?)")->execute([$orderno]);
        echo "✅ COD order mail sent.<br>";
        exit;
    }
}

// --- Online Order ---
$result2 = mysqli_query($con, "SELECT * FROM online_order WHERE ordersts = '' ORDER BY orddate DESC LIMIT 1");
$online = mysqli_fetch_assoc($result2);

if ($online) {
    $orderno = $online['orderno'];
    $stmt = $pdo->prepare("SELECT 1 FROM mail_log WHERE order_type = 'online' AND order_no = ?");
    $stmt->execute([$orderno]);

    if ($stmt->rowCount() === 0) {
        $mail->Subject = "New Online Order Received – {$orderno}";
        $mail->Body = "
            <h3>New Online Paid Order</h3>
            <p><strong>Order No:</strong> {$orderno}</p>
            <p><strong>Name:</strong> {$online['name']}</p>
            <p><strong>Phone:</strong> {$online['phone']}</p>
            <p><strong>Amount:</strong> ₹{$online['amount']}</p>
            <p><strong>Txn ID:</strong> {$online['transacton']}</p>
            <p><strong>City:</strong> {$online['city']}, {$online['state']} - {$online['zip']}</p>
            <p><strong>Notes:</strong> {$online['notes']}</p>
        ";
        $mail->send();

        $pdo->prepare("INSERT INTO mail_log (order_type, order_no) VALUES ('online', ?)")->execute([$orderno]);
        echo "✅ Online order mail sent.<br>";
        exit;
    }
}

echo "✅ No new orders to notify.";
?>
