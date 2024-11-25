<?php

include 'connection.php';
$connection = connection::getInstance();
$mysqli = $connection->getConnection();
$connection1 = database::getInstance();
$mysqli1 = $connection1->getConnection();
$system_datetime = date("Y-m-d H:i:s");

// Capture the raw POST data
$payload = file_get_contents('php://input');
$webhookData = json_decode($payload, true);

// Verify if Razorpay webhook secret is set and valid
$webhookSecret = "ramesh"; // Replace with your Razorpay webhook secret
$headers = getallheaders();
$razorpaySignature = $headers['X-Razorpay-Signature'] ?? '';

// Validate the webhook signature
if (!validateWebhookSignature($payload, $razorpaySignature, $webhookSecret)) {
    http_response_code(400); // Invalid signature
    exit('Webhook signature verification failed');
}
// Check if the event type is "payment.captured"
if (isset($webhookData['event']) && $webhookData['event'] === 'payment.captured') {
    // Proceed only if the payment is captured
$statusMessage = "Payment captured for order: " . $webhookData['payload']['payment']['entity']['order_id'];
    $timestamp = date("Y-m-d H:i:s");

    $sql = "INSERT INTO temporary_table_for_callback (status_message, timestamp) VALUES (?, ?)";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("ss", $statusMessage, $timestamp);

    if ($stmt->execute()) {
        echo "Success";
    } else {
        http_response_code(500);
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    // Ignore other webhook events
    http_response_code(200);
    exit('Event ignored');
}

?>

