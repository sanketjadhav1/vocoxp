<?php
// Capture the raw POST data
$payload = file_get_contents('php://input');
$webhookData = json_decode($payload, true);
$webhookSecret = "ramesh"; // Replace with Razorpay's webhook secret
$headers = getallheaders();
$razorpaySignature = $headers['X-Razorpay-Signature'] ?? '';

// Validate the webhook signature
if (!validateWebhookSignature($payload, $razorpaySignature, $webhookSecret)) {
    http_response_code(400); // Invalid signature
    exit('Webhook signature verification failed');
}

// Process only the "payment.captured" event
if (isset($webhookData['event']) && $webhookData['event'] === 'payment.captured') {
    $orderId = $webhookData['payload']['payment']['entity']['order_id'];

    // Update payment status in the database
    // $conn = new mysqli('db_host', 'db_user', 'db_pass', 'db_name');
// $conn = new mysqli('199.79.62.21', 'mounac53_vocoxp', 'mX#&V~o_ksOS', 'mounac53_vocoxp3.0'); // replace with actual credentials
   // Database connection
$conn = new mysqli('199.79.62.21', 'mounac53_vocoxp', 'mX#&V~o_ksOS', 'mounac53_vocoxp3.0');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

    $sql = "UPDATE temporary_table_for_callback SET status = 'captured' WHERE order_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $orderId);

    if ($stmt->execute()) {
        echo "Success";
    } else {
        http_response_code(500);
        echo "Database update failed";
    }

    $stmt->close();
    $conn->close();
}

// Function to validate Razorpay webhook signature
function validateWebhookSignature($payload, $signature, $secret) {
    $expectedSignature = hash_hmac('sha256', $payload, $secret);
    return hash_equals($expectedSignature, $signature);
}
?>
