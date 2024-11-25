 

<?php
// Load Razorpay's PHP SDK
require('https://mounarchtech.com/vocoxp/razorpay-php/Razorpay.php');
use Razorpay\Api\Api;

$webhookSecret = 'mujawar';

// Capture the payload and headers
$payload = file_get_contents('php://input');
$headers = getallheaders();
$signature = $headers['X-Razorpay-Signature'];

// Verify the webhook signature
if (verifySignature($payload, $signature, $webhookSecret)) {
    $data = json_decode($payload, true);
    $mysqli = mysqli_connect("199.79.62.21", "mounac53_vocoxp", "mX#&V~o_ksOS", "mounac53_vocoxp3.0");
    // Check if the event is payment.captured
    if ($data['event'] === 'payment.captured') {
        $paymentId = $data['payload']['payment']['entity']['id'];
        $amount = $data['payload']['payment']['entity']['amount'];
        $currency = $data['payload']['payment']['entity']['currency'];
        
        // Update your database and handle the successful payment here
        // e.g., marking the order as paid, notifying the user, etc.
        $sql="UPDATE visitor_payment_transaction_all SET payment_status = 'paid' WHERE gateway_id = '$qrCodeId'";
        mysqli_query($mysqli,$sql);
    }
    else
    {
        $sql="UPDATE visitor_payment_transaction_all SET payment_status = 'f' WHERE gateway_id = '$qrCodeId'";
        mysqli_query($mysqli,$sql);
        echo json_encode(['res' => 'error', 'message' => "Event not relevant"]);
        exit;
    }
}

function verifySignature($payload, $signature, $secret) {
    $expectedSignature = hash_hmac('sha256', $payload, $secret);
    return hash_equals($expectedSignature, $signature);
}
?>


// $key_id = 'rzp_live_wXmbkXPVVABiDl';
// $secret = 'mujawar';

// // Capture webhook payload and signature from Razorpay
// $webhookPayload = file_get_contents('php://input');
// $webhookData = json_decode($webhookPayload, true);
// $razorpaySignature = $_SERVER['HTTP_X_RAZORPAY_SIGNATURE'] ?? '';

// // Verify the signature to ensure authenticity
// function verifySignature($webhookPayload, $razorpaySignature, $secret) {
//     $generatedSignature = hash_hmac('sha256', $webhookPayload, $secret);
//     return hash_equals($generatedSignature, $razorpaySignature);
// }
//  $mysqli = mysqli_connect("199.79.62.21", "mounac53_vocoxp", "mX#&V~o_ksOS", "mounac53_vocoxp3.0");
// if (verifySignature($webhookPayload, $razorpaySignature, $secret)) {
//     // Check if the event is a QR payment capture
//     if (isset($webhookData['event'])) {
//         $payment = $webhookData['payload']['payment']['entity'];
//         $payid = $payment['id'];
//         $payStatus = $payment['status'];
//         $qrCodeId = $eventData['payload']['payment']['entity']['notes']['qr_code'];
//         // // Check if payment is successfully captured
//         // if ($payStatus == 'captured') {
//         //     echo json_encode(['res' => 'success', 'payid' => $payid]);
//         //     exit;
//         // } else {
//         //     echo json_encode(['res' => 'error', 'message' => "Payment not captured"]);
//         //     exit;
//         // }
//         $sql="UPDATE visitor_payment_transaction_all SET payment_status = 'paid' WHERE gateway_id = '$qrCodeId'";
//         mysqli_query($mysqli,$sql);
//         echo json_encode(['res' => 'error', 'message' => "Event not relevant"]);
//         exit;
//     } else {

//         $sql="UPDATE visitor_payment_transaction_all SET payment_status = 'f' WHERE gateway_id = '$qrCodeId'";
//         mysqli_query($mysqli,$sql);
//         echo json_encode(['res' => 'error', 'message' => "Event not relevant"]);
//         exit;
//     }
// } else {
//     // Invalid signature response
//     http_response_code(400);
//     echo json_encode(['res' => 'error', 'message' => "Invalid signature"]);
//     exit;
// }
