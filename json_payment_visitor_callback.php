<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);

header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');

$input = file_get_contents('php://input');
$eventData = json_decode($input, true);

$secret = 'paymentdone';
$signature = $_SERVER['HTTP_X_RAZORPAY_SIGNATURE'] ?? '';

function validateRazorpaySignature($input, $signature, $secret) {
    $expectedSignature = hash_hmac('sha256', $input, $secret);
    return hash_equals($expectedSignature, $signature);
}

if ($eventData === null) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid JSON payload']);
    exit;
}

if (validateRazorpaySignature($input, $signature, $secret)) {
    if (isset($eventData['event']) && $eventData['event'] === 'payment.captured') {

        $paymentId = $eventData['payload']['payment']['entity']['id'];
        $amount = $eventData['payload']['payment']['entity']['amount'];
        $status = $eventData['payload']['payment']['entity']['status'];
        $qrCodeId = $eventData['payload']['payment']['entity']['notes']['qr_code_id'] ?? null;

        if (!$qrCodeId) {
            echo json_encode(['status' => 'error', 'message' => 'QR code ID not found in payment data']);
            exit;
        }

        $mysqli = mysqli_connect("199.79.62.21", "mounac53_vocoxp", "mX#&V~o_ksOS", "mounac53_vocoxp3.0");

        if ($mysqli) {
            $stmt = $mysqli->prepare("UPDATE visitor_payment_transaction_all SET payment_status = ? WHERE gateway_id = ?");
            $statusPaid = 'paid';
            $stmt->bind_param("ss", $statusPaid, $qrCodeId);

            if ($stmt->execute() && $stmt->affected_rows > 0) {
                echo json_encode(['status' => 'success', 'message' => 'Payment processed and updated successfully']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Database update failed or no matching records found']);
            }

            $stmt->close();
            $mysqli->close();
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Database connection failed']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Unhandled event type']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid signature']);
}
error_log("Webhook received at: " . date("Y-m-d H:i:s"));
?>
