<?php

header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');
date_default_timezone_set('Asia/Kolkata');

include 'connection.php';
$connection = connection::getInstance();
$mysqli = $connection->getConnection();

if ($_SERVER['REQUEST_METHOD'] != "POST") {
    $response[] = array("error_code" => 102, "message" => "Request Method Is Not POST");
    echo json_encode($response);
    exit;
}

$mode = isset($_POST['mode']) ? $_POST['mode'] : '';
$agency_id = isset($_POST['agency_id']) ? $_POST['agency_id'] : '';
$agency_logo = isset($_FILES['agency_logo']) ? $_FILES['agency_logo'] : null;

$check_error = check_error($agency_id, $mode, $agency_logo);

if ($check_error == 1) {
    // Check if the agency_id exists
    $check_agncy_id_query = "SELECT `agency_id` FROM `agency_header_all` WHERE `agency_id` = '$agency_id'";
    $check_agncy_id_res = $mysqli->query($check_agncy_id_query);
    $check_agncy_id_row = $check_agncy_id_res->num_rows;

    if ($check_agncy_id_row != 1) {
        $response[] = array("error_code" => 124, "message" => "Invalid 'agency_id'");
        echo json_encode($response);
        exit;
    }

    if ($mode == 'update') {
        if ($agency_logo && $agency_logo['error'] === UPLOAD_ERR_OK) {
            
            $file = $agency_logo;
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            $maxFileSize = 10 * 1024 * 1024; // 2MB

            // Validate file type and size
            // if ( $file['size'] <= $maxFileSize) {
                
                $uploadDir = 'uploads/';
                $fileName = basename($file['name']);
                $uploadFilePath = $uploadDir . $fileName;

                // Create the uploads directory if it does not exist
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
$pthh="https://mounarchtech.com/vocoxp/".$uploadFilePath;
                // Move the uploaded file
                if (move_uploaded_file($file['tmp_name'], $uploadFilePath)) {
                    // Update the image path in the database
                    $update_query = "UPDATE `agency_header_all` SET `agency_logo` = '$pthh' WHERE `agency_id` = '$agency_id'";
                    $sqlquery = $mysqli->query($update_query);
                    
                    if ($sqlquery) {
                         $response[] = array(
                            "error_code" => 100, 
                            "message" => "Record updated successfully",
                            "uploaded_directory" => "https://mounarchtech.com/vocoxp/".$uploadFilePath // Return the directory where the image was uploaded
                        );
                        // $response[] = array("error_code" => 100, "message" => "Record updated successfully");
                    } else {
                        $response[] = array("error_code" => 103, "message" => "Failed to update image path in database");
                    }
                } else {
                    $response[] = array("error_code" => 104, "message" => "Failed to move uploaded file");
                }
            // } else {
            //     $response[] = array("error_code" => 105, "message" => "Invalid file type or size");
            // }
        } else {
            $response[] = array("error_code" => 106, "message" => "No file uploaded or upload error");
        }
    }
    echo json_encode($response);
    exit;
}


function check_error($agency_id, $mode, $agency_logo)
{
    if ($_SERVER["REQUEST_METHOD"] != "POST") {
        $response[] = array("error_code" => 102, "message" => "Please change the request method to POST");
        echo json_encode($response);
        exit;
    }

    if (empty($agency_id)) {
        $response[] = array("error_code" => 103, "message" => "The parameter 'agency_id' is required and cannot be empty");
        echo json_encode($response);
        exit;
    }

    if (empty($mode)) {
        $response[] = array("error_code" => 107, "message" => "The parameter 'mode' is required and cannot be empty");
        echo json_encode($response);
        exit;
    }

    if ($mode != 'update') {
        $response[] = array("error_code" => 108, "message" => "Invalid mode. Only 'update' is allowed");
        echo json_encode($response);
        exit;
    }

    // Check if agency_logo is provided
    if (empty($agency_logo) || $agency_logo['error'] === UPLOAD_ERR_NO_FILE) {
        $response[] = array("error_code" => 109, "message" => "The parameter 'agency_logo' is required and cannot be empty");
        echo json_encode($response);
        exit;
    }

    return 1;
}
?>
