<?php
session_start();
include_once 'connection.php';
// error_reporting(E_ALL & ~E_DEPRECATED);
// ini_set('display_errors', 1);

// Now you can use the connection class
$connection = connection::getInstance();
$mysqli = $connection->getConnection();

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

// print_r($_POST);
// print_r($_FILES);
$response = array('status' => 'error', 'message' => 'Unknown error');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if required fields are present
    if (empty($_POST['agency_id']) || empty($_POST['bulk_id'])) {
        $response['message'] = 'Please enter all required details.';
        echo json_encode($response);
        exit();
    }

    $agency_id = $_POST['agency_id'];
    $bulk_id = $_POST['bulk_id'];

    // Check if file is uploaded
    if (empty($_FILES['upload_excel']['name'])) {
        $response['message'] = 'No file selected.';
        echo json_encode($response);
        exit();
    }

    $fileName = $_FILES['upload_excel']['name'];
    $file_ext = pathinfo($fileName, PATHINFO_EXTENSION);
    $allowed_ext = ['xls', 'csv', 'xlsx'];

    if (in_array($file_ext, $allowed_ext)) {
        $inputFileNamePath = $_FILES['upload_excel']['tmp_name'];
        $spreadsheet = IOFactory::load($inputFileNamePath);
        $data = $spreadsheet->getActiveSheet()->toArray();

        // Check if data array is valid and contains rows
        if (count($data) <= 1) { // Only header row
            $response['message'] = 'The uploaded file is empty or only contains headers.';
            echo json_encode($response);
            exit();
        }

        $upload_id = unique_id_generate_bulk('UPL', 'bulk_end_user_transaction_all', $mysqli, "upload_id");
        $count = 0;
        $rows_inserted = false;

        $data_array = array(); // Initialize an array to store the valid data

foreach ($data as $row) {
    if ($count > 0) { // Skipping the header row
        $end_user_id = unique_id_generate_bulk('END', 'bulk_end_user_transaction_all', $mysqli, "end_user_id");
        $sr_no = trim($row[0]);
        $full_name = trim($row[1]);
        $mobile_no = trim($row[2]);
        $email_id = trim($row[3]);
        // $request_no = trim($row[4]);

        if (!empty($sr_no) && !empty($full_name) && !empty($mobile_no)) {
            if (strlen($mobile_no) == 10) { // Check if the mobile number length is 10
                array_push($data_array, array(
                    'end_user_id' => $end_user_id,
                    'sr_no' => $sr_no,
                    'full_name' => $full_name,
                    'mobile_no' => $mobile_no,
                    'email_id' => $email_id,
                ));
            }else{
                $response['message'] = 'Invalid Mobile No please. At row '.$sr_no.'';
            }
        }
    }
    $count++;
}

// Now $data_array contains all the valid data


        if (!empty($data_array)) {
            $response['status'] = 'success';
            $response['message'] = 'Analyze success';
            $response['data'] = $data_array;
        } else {
            $response['message'] = 'No valid rows found in the Excel file.';
        }
    } else {
        $response['message'] = 'Invalid File Type. Allowed types are xls, csv, and xlsx.';
    }
    echo json_encode($response);
}

function unique_id_generate_bulk($id_prefix, $table_name, $mysqli, $id_for) {
    date_default_timezone_set('Asia/Kolkata');
    $system_date_time = date("Y-m-d H:i:s");

    $unique_header_query = "SELECT `prefix`, `last_id`, `id_for` FROM `unique_id_header_all` WHERE `table_name`='$table_name' AND `id_for`='$id_for'";
    $unique_header_res = $mysqli->query($unique_header_query);
    $unique_header_arr = $unique_header_res->fetch_assoc();

    if (empty($unique_header_arr)) {
        $initial_id = $id_prefix . '-' . str_pad(1, 5, '0', STR_PAD_LEFT);
        $insert_query = "INSERT INTO `unique_id_header_all` (`table_name`, `id_for`, `prefix`, `last_id`, `created_on`) 
                          VALUES ('$table_name', '$id_for', '$id_prefix', '$initial_id', '$system_date_time')";
        $mysqli->query($insert_query);
        return $initial_id;
    } else {
        $last_id = $unique_header_arr['last_id'];
        $last_digit = explode('-', $last_id);
        $last_id_number = $last_digit[1];

        if (strlen($last_id_number) > 5) {
            return 'ID cannot be more than 5 characters';
        }

        $digits = preg_replace('/[^0-9]/', '', $last_id_number);

        if ($digits === str_repeat('9', strlen($digits))) {
            return 'You have reached the last ID string';
        }

        $next_id = str_pad((intval($digits) + 1), strlen($digits), '0', STR_PAD_LEFT);
        $unique_id = $id_prefix . "-" . $next_id;
        
        $update_unique_header_query = "UPDATE `unique_id_header_all` SET `last_id`='$unique_id', `modified_on`='$system_date_time' WHERE `table_name`='$table_name' AND `id_for`='$id_for'";
        $mysqli->query($update_unique_header_query);
        
        return $unique_id;
    }
}
?>
