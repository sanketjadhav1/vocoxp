<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'vendor/autoload.php';
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

// Generate a unique order ID
$orderId = uniqid("order_");

// Save order details in the database with status 'pending'
// $conn = new mysqli('199.79.62.21', 'mounac53_vocoxp', 'mX#&V~o_ksOS', 'mounac53_vocoxp3.0'); // replace with actual credentials
// Database connection
$conn = new mysqli('199.79.62.21', 'mounac53_vocoxp', 'mX#&V~o_ksOS', 'mounac53_vocoxp3.0');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "INSERT INTO temporary_table_for_callback (order_id, status) VALUES (?, 'pending')";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $orderId);
$stmt->execute();
$stmt->close();
$conn->close();

// Prepare QR code data
$qrData = json_encode(['order_id' => $orderId, 'amount' => 100]); // customize QR data as needed
$qrCode = new QrCode($qrData);
$writer = new PngWriter();

// Output the QR code image to the browser
header('Content-Type: image/png');
echo $writer->write($qrCode)->getString();
?>
