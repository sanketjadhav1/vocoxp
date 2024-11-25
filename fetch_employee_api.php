<?php
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');
date_default_timezone_set('Asia/Kolkata');

include 'connection.php';
$connection = connection::getInstance();
$mysqli = $connection->getConnection();

if ($_SERVER['REQUEST_METHOD'] != "GET") {
    $response = array("error_code" => 102, "message" => "Request Method Is Not GET");
    echo json_encode($response);
    exit;
}

$agency_id = isset($_GET['agency_id']) ? $_GET['agency_id'] : '';
$emp_id = isset($_GET['emp_id']) ? $_GET['emp_id'] : '';
$check_error = check_error($agency_id);

if ($check_error == 1) {
    $check_agncy_id_query = "SELECT `agency_id` FROM `agency_header_all` WHERE `agency_id` = '$agency_id'";
    $check_agncy_id_res = $mysqli->query($check_agncy_id_query);
    $check_agncy_id_row = $check_agncy_id_res->num_rows;

    if ($check_agncy_id_row != 1) {
        $response[] = array("error_code" => 124, "message" => "Invalid 'agency_id'");
        echo json_encode($response);
        exit;
    }
    
    // Count the statuses separately
    $countQuery = "
        SELECT 
            COUNT(CASE WHEN status = 1 THEN 1 END) AS count_status_1,
            COUNT(CASE WHEN status = 2 THEN 1 END) AS count_status_2,
            COUNT(CASE WHEN status = 3 THEN 1 END) AS count_status_3,
            COUNT(CASE WHEN status = 5 THEN 1 END) AS count_status_5
        FROM bulk_weblink_request_all 
        WHERE agency_id ='$agency_id'
    ";
    
    $countResult = $mysqli->query($countQuery);
    $countRow = $countResult->fetch_assoc();
    
    // Extract individual counts
    $count_status_1 = $countRow['count_status_1'];
    $count_status_2 = $countRow['count_status_2'];
    $count_status_3 = $countRow['count_status_3'];
    $count_status_5 = $countRow['count_status_5'];
$total_active_weblink = $count_status_1 + $count_status_2 + $count_status_3 ; 
    if (!empty($agency_id) && !empty($emp_id)) {
        $check_emp_id_query = "SELECT `emp_id` FROM `employee_header_all` WHERE `emp_id` = '$emp_id'";
        $check_emp_id_res = $mysqli->query($check_emp_id_query);
        $check_emp_id_row = $check_emp_id_res->num_rows;

        if ($check_emp_id_row != 1) {
            $response[] = array("error_code" => 124, "message" => "Invalid 'emp_id'");
            echo json_encode($response);
            exit;
        }

        $agency_records = "SELECT agency_id, emp_id, name, contact, department, designation, email_id, visitor_approval_required, visiting_charges, verification_paid_by 
        FROM employee_header_all WHERE agency_id = '$agency_id' AND emp_id = '$emp_id' AND status = 1"; // Ensure status is correct
        
        $geting_agency_records = $mysqli->query($agency_records);
        
        if ($geting_agency_records->num_rows > 0) {
            $data = array();  
            while ($row = $geting_agency_records->fetch_assoc()) {
                $data[] = $row;
            }
            
            $response = array(
                "error_code" => 100,
                "message" => "Record found",
                "data" => $data,
          
            );
        } else {
            $response = array(
                "error_code" => 103,
                "message" => "No Record found"
            );
        }
        
        echo json_encode($response);
    } else {
        $agency_records = "SELECT agency_id, emp_id, name, contact, department, designation, email_id, visitor_approval_required, visiting_charges, verification_paid_by 
        FROM employee_header_all WHERE agency_id = '$agency_id' AND status = 1"; // Ensure status is correct
        
        $geting_agency_records = $mysqli->query($agency_records);
        
        if ($geting_agency_records->num_rows > 0) {
            $data = array();  
            while ($row = $geting_agency_records->fetch_assoc()) {
                $data[] = $row;
            }
            
            $response = array(
                "error_code" => 100,
                "message" => "Record found",
                "data" => $data,
                "status_counts" => array(
                    "generated" => $count_status_1,
                    "uploaded" => $count_status_2,
                    "weblinks_generated " => $count_status_3,
                    "completed" => $count_status_5,
                    "total_active_weblink"=> $total_active_weblink
                )
            );
        } else {
            $response = array(
                "error_code" => 103,
                "message" => "No Record found"
            );
        }
        
        echo json_encode($response);
    }
}

function check_error($agency_id) {
    if (empty($agency_id)) {
        $response = array("error_code" => 103, "message" => "The parameter 'agency_id' is required and cannot be empty");
        echo json_encode($response);
        exit;
    }
    return 1;
}
