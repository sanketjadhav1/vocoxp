<?php

header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');



$webhookURL = 'https://mounarchtech.com/vocoxp/callback_A.php';
$payload = json_encode([
    "event" => "payment.captured",
    "payload" => [
        "payment" => [
            "entity" => [
                "id" => "pay_FAcKF45v7JVFtt",
                "amount" => 50000,
                "currency" => "INR",
                "status" => "captured",
                "description" => "Test Payment",
                "vpa" => "test@upi",
                "email" => "customer@example.com",
                "contact" => "9876543210",
            ]
        ]
    ]
]);

$razorpay_secret = 'ramesh'; // Replace with your Razorpay secret key
$generatedSignature = hash_hmac('sha256', $payload, $razorpay_secret);

$ch = curl_init($webhookURL);
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'X-Razorpay-Signature: ' . $generatedSignature
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
curl_close($ch);

echo "Response from Webhook: " . $response;
