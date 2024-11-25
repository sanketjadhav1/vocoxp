<?php

$webhookSecret = 'h0QEqFVRQsYlzhFSgML3Dl2J';

$requestBody = file_get_contents('php://input');
$receivedSignature = $_SERVER['HTTP_X_RAZORPAY_SIGNATURE'] ?? '';

function verifyWebhookSignature($payload, $secret, $receivedSignature) {
    $generatedSignature = hash_hmac('sha256', $payload, $secret);
    return hash_equals($generatedSignature, $receivedSignature);
}

if (verifyWebhookSignature($requestBody, $webhookSecret, $receivedSignature)) {
 
    $data = json_decode($requestBody, true);
 
    file_put_contents('razorpay_webhook_log.txt', print_r($data, true), FILE_APPEND);

    $eventType = $data['event'];

    if ($eventType == 'payment.authorized' || $eventType == 'payment.captured') {
        $paymentId = $data['payload']['payment']['entity']['id'];
        $amount = $data['payload']['payment']['entity']['amount'];
        $status = $data['payload']['payment']['entity']['status'];
        $uniqueIdentifier = $data['payload']['payment']['entity']['notes']['unique_identifier'] ?? null;

        $conn = new mysqli('199.79.62.21', 'mounac53_vocoxp', 'mX#&V~o_ksOS', 'mounac53_vocoxp3.0');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$stmt = "UPDATE `visitor_payment_transaction_all`  SET `payment_status` = '$status' WHERE `v_transaction_id` = '$uniqueIdentifier';";

mysqli_query($conn, $stmt);
        // $conn->close();
    }

    http_response_code(200); 
} else {
    http_response_code(403); 
    echo "Invalid signature";
}
