<?php
include_once '../connection.php';
require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PhpOffice\PhpSpreadsheet\IOFactory;

error_reporting(E_ALL & ~E_DEPRECATED);
ini_set('display_errors', 1);

$connection = connection::getInstance();
$mysqli = $connection->getConnection();

$system_datetime = date("Y-m-d H:i:s");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $agency_id = $_POST['agency_id'] ?? '';
    $new_tentative_amount = $_POST['tentative_amount'];

    // Fetch the current tentative_amount from the database
    $agency_query  = "SELECT tentative_amount FROM agency_header_all WHERE agency_id = '$agency_id'";
    $res_agency_query = $mysqli->query($agency_query);

    if ($res_agency_query->num_rows > 0) {
        // Fetch the result as an associative array
        $row = $res_agency_query->fetch_assoc();
        $current_tentative_amount = $row['tentative_amount'];
        if (is_null($current_tentative_amount)) {
            $current_tentative_amount = 0;
        }
        $updated_tentative_amount = $current_tentative_amount + $new_tentative_amount;

    } else {
        $updated_tentative_amount = $new_tentative_amount;
    }
    $update_Query = "UPDATE agency_header_all SET tentative_amount = '$updated_tentative_amount' WHERE agency_id = '$agency_id'";
    $res_update_query = $mysqli->query($update_Query);

    if ($res_update_query) {
        echo json_encode(['status' => 'success', 'message' => "Record updated successfully", 'updated_tentative_amount' => $updated_tentative_amount]);
    } else {
        echo json_encode(['status' => 'error', 'message' => "Failed to update record"]);
    }
}
