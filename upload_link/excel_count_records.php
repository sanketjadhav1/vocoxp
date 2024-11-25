<?php
include_once '../connection.php';
require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

error_reporting(E_ALL & ~E_DEPRECATED);
ini_set('display_errors', 1);

$connection = connection::getInstance();
$mysqli = $connection->getConnection();

$system_datetime = date("Y-m-d H:i:s");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $agency_id = $_POST['agency_id'] ?? '';
    $bulk_id = $_POST['bulk_id'] ?? '';
    $amount = $_POST['amount'] ?? '';
    $valid_till = $_POST['valid_till'] ?? '';
    $reminder_sms = $_POST['reminder_sms'] ?? '';
   $reminder_email = $_POST['reminder_email'] ?? '';
    $upload_id = uniqid(); // Generate a unique ID for the upload
    $fileName = $_FILES['file_name']['name'] ?? '';

    if (empty($fileName)) {
        echo json_encode(['status' => 'error', 'message' => 'No file selected.']);
        exit();
    }

    $file_ext = pathinfo($fileName, PATHINFO_EXTENSION);
    $allowed_ext = ['xls', 'csv', 'xlsx'];

    if (!in_array($file_ext, $allowed_ext)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid file type.']);
        exit();
    }

    $inputFileNamePath = $_FILES['file_name']['tmp_name'];

    try {
        // Load the Excel file
        $spreadsheet = IOFactory::load($inputFileNamePath);
        $data = $spreadsheet->getActiveSheet()->toArray();
        $type = $data[1][5] ?? '';
        $headerRow = $data[5] ?? [];
        $nameIndices = [];
        $emailIndices = [];
        $mobileIndices = [];

        // Find multiple possible columns for Name, Email, Mobile
        foreach ($headerRow as $index => $header) {
            if (stripos($header, 'Name') !== false) {
                $nameIndices[] = $index;
            }
            if (stripos($header, 'Email') !== false) {
                $emailIndices[] = $index;
            }
            if (stripos($header, 'Mobile') !== false) {
                $mobileIndices[] = $index;
            }
        }

        if (empty($nameIndices) || empty($emailIndices) || empty($mobileIndices)) {
            echo json_encode(['status' => 'error', 'message' => 'Missing required columns (Name, Email, or Mobile) in the Excel file.']);
            exit();
        }

        // Flag for inserting or just counting
        $insert_data = $_POST['insert_data'] ?? false;
        $rows_to_insert = 0;
        $first_name_count = 0;
        $second_name_count = 0;
        $third_name_count = 0;
        $values = [];

        foreach ($data as $index => $row) {
            if ($index < 6) continue; // Skip header rows

            // First name (name in first name column)
            $first_name = trim($row[$nameIndices[0]] ?? '');
            $second_name = isset($nameIndices[1]) ? trim($row[$nameIndices[1]] ?? '') : ''; // Only check if there's a second name column
            $third_name = isset($nameIndices[2]) ? trim($row[$nameIndices[2]] ?? '') : '';  // Only check if there's a third name column

            // Increment counters for each name
            if (!empty($first_name)) $first_name_count++;
            if (!empty($second_name)) $second_name_count++;
            if (!empty($third_name)) $third_name_count++;

            // Prepare mobile and email values (if available)
            $mobile = '';
            $email = '';

            // Get the first available mobile and email if they exist
            foreach ($mobileIndices as $mobileIndex) {
                if (!empty($row[$mobileIndex])) {
                    $mobile = trim($row[$mobileIndex]);
                    break; // Get the first available mobile number
                }
            }

            foreach ($emailIndices as $emailIndex) {
                if (!empty($row[$emailIndex])) {
                    $email = trim($row[$emailIndex]);
                    break; // Get the first available email
                }
            }

            // If any name is filled, count this row for insertion
            if (!empty($first_name) || !empty($second_name) || !empty($third_name)) {
                $rows_to_insert++; // Count this row for insertion

                // If insert_data is true, prepare the value for insertion
                if ($insert_data) {
                    $values[] = "('$agency_id', '$bulk_id', '$upload_id', '$type', '$first_name', '$mobile', '$email', '', '', '0', '', '', '$reminder_email', '$reminder_sms')";
                }
            }
        }

        // Return counts of first, second, and third names
        echo json_encode([
            'status' => 'success',
            'Total records' => $rows_to_insert,
            'First name count' => $first_name_count,
            'Second name count' => $second_name_count,
            'Third name count' => $third_name_count
        ]);

    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'Error processing file: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
