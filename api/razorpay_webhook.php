<?php
include('../config.php');
ini_set('display_errors', 1);
error_reporting(E_ALL);

$webhook_secret = "EzhilAmilthu@0723";

$payload = file_get_contents('php://input');
$headers = getallheaders();
$signature = $headers['X-Razorpay-Signature'] ?? '';

// Signature verification
function verifySignature($payload, $signature, $secret) {
    $expected = hash_hmac('sha256', $payload, $secret);
    return hash_equals($expected, $signature);
}

if (!verifySignature($payload, $signature, $webhook_secret)) {
    http_response_code(403);
    echo "Invalid Signature";
    exit;
}

$data = json_decode($payload, true);
$event = $data['event'] ?? 'unknown';
$payment_id = $data['payload']['payment']['entity']['id'] ?? null;

// Log webhook events
$escaped_payload = mysqli_real_escape_string($con, $payload);
mysqli_query($con, "INSERT INTO payments_log (payment_id, event_type, payload, status) VALUES ('$payment_id', '$event', '$escaped_payload', 'received')");

// Only process payment.captured
if ($event === "payment.captured" && $payment_id) {
    $check = mysqli_query($con, "SELECT id FROM online_order WHERE transacton = '$payment_id'");

    if (mysqli_num_rows($check) === 0) {
        // 💡 Business logic to process order from backup data if needed

        // 🧠 IDEA 1: You can create a temporary backup table to hold pending orders
        // and insert the data from your checkout form BEFORE redirecting to Razorpay.
        // Then, on webhook, match payment_id with session/order_no/email and confirm it.

        // 🧠 IDEA 2: If you want to just update an existing order’s status:
        mysqli_query($con, "UPDATE online_order SET payment_status='success' WHERE transacton='$payment_id'");
    }

    http_response_code(200);
    echo "Webhook processed successfully";
} else {
    http_response_code(200);
    echo "Event ignored";
}
