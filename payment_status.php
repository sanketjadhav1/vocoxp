<?php
session_start();
include_once "../../config/connection.php"; // Make sure this file exists and contains the necessary database connection code

$date = date("Y-m-d H:i:s");

// Verify that the request came from Razorpay
$signature = $_SERVER['HTTP_X_RAZORPAY_SIGNATURE'];
$webhookSecret = 'kyRJumqRUaYHUxKIHgGFBMrO'; // Your webhook secret from Razorpay dashboard

$payload = file_get_contents('php://input');

if (!verify_signature($payload, $signature, $webhookSecret)) {
    http_response_code(400);
    exit();
}

// Parse the incoming webhook payload
$event = json_decode($payload, true);
$encode_ev = json_encode($event);
print_r($event);
?>