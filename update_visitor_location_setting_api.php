<?php
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');
date_default_timezone_set('Asia/Kolkata');

include 'connection.php';
$connection = connection::getInstance();
$mysqli = $connection->getConnection();

if ($_SERVER['REQUEST_METHOD'] != "POST") {
    $response = array("error_code" => 102, "message" => "Request Method Is Not POST");
    echo json_encode($response);
    exit;
}

$agency_id =isset($_POST['agency_id']) ? $_POST['agency_id'] :'';
$visitor_location_id = isset($_POST['visitor_location_id']) ? $_POST['visitor_location_id'] : '';
$printer_provided =  isset($_POST['printer_provided']) ? $_POST['printer_provided'] :'';
$type = isset($_POST['type']) ? $_POST['type'] : '';
$visitor_pass_valid_upto = isset($_POST['pass_valid_upto']) ? $_POST['pass_valid_upto'] : '';
$visitor_mgmt = isset($_POST['visitor_mgmt']) ? $_POST['visitor_mgmt'] : '';
$employee_upload_link = isset($_POST['employee_upload_link']) ? $_POST['employee_upload_link'] : '';
$approval_required = isset($_POST['approval_required']) ? $_POST['approval_required'] : '';
$visitor_id_required = isset($_POST['visitor_id_required']) ? $_POST['visitor_id_required'] : '';
$approval_weblink_expired_in = isset($_POST['approval_weblink_expired_in']) ? $_POST['approval_weblink_expired_in'] : '';
$amt_paid_by = isset($_POST['amt_paid_by']) ? $_POST['amt_paid_by'] : '';
$verification_amt = isset($_POST['verification_amt']) ? $_POST['verification_amt'] : '';
$visiting_charges = isset($_POST['visiting_charges'])  ? $_POST['visiting_charges'] : '';
$visiting_hours = isset($_POST['visiting_hours']) ? $_POST['visiting_hours'] : '';
$weekoffs = isset($_POST['weekoffs']) ? $_POST['weekoffs'] : '';

$check_error = check_error($agency_id, $visitor_location_id, $type, $visitor_mgmt,$printer_provided, $employee_upload_link, $approval_required, $visitor_id_required, $approval_weblink_expired_in, $amt_paid_by, $verification_amt, $visiting_charges);
if ($check_error == 1) {
    $check_agncy_id_query = "SELECT `agency_id` FROM `agency_header_all` WHERE `agency_id` = '$agency_id'";
    $check_agncy_id_res = $mysqli->query($check_agncy_id_query);
    $check_agncy_id_row = $check_agncy_id_res->num_rows;

    if ($check_agncy_id_row != 1) {
        $response = array("error_code" => 124, "message" => "Invalid 'agency_id'");
        echo json_encode($response);
        exit;
    }

    $check_visitor_location_id_query = "SELECT `visitor_location_id` FROM `agency_visitor_location_header_all` WHERE `visitor_location_id` = '$visitor_location_id'";
    $check_visitor_location_id_res = $mysqli->query($check_visitor_location_id_query);
    $check_visitor_location_id_row = $check_visitor_location_id_res->num_rows;
    
    if ($check_visitor_location_id_row != 1) {
        $response = array("error_code" => 124, "message" => "Invalid 'visitor_location_id'");
        echo json_encode($response);
        exit;
    }
    

    $update_query = "
UPDATE visitor_location_setting_details_all 
SET 
    type = '$type',
    visitor_mgmt = '$visitor_mgmt',
    printer_provided = '$printer_provided',
    employee_upload_link = '$employee_upload_link',
    approval_required = '$approval_required',
    visitor_pass_valid_upto = '$visitor_pass_valid_upto',
    visitor_id_required = '$visitor_id_required',
    approval_weblink_expired_in = '$approval_weblink_expired_in',
    amt_paid_by = '$amt_paid_by', 
    verification_amt = '$verification_amt',
    visiting_charges = '$visiting_charges',
    visiting_hours = '$visiting_hours',
    weekoffs = '$weekoffs'
WHERE visitor_location_id = '$visitor_location_id'";
$updateSqlquery =  $mysqli->query($update_query);
if(!$updateSqlquery){
    $response = array("error_code" =>102,"message"=>"failed to update record");
    echo json_encode($response);
}
else{
    $response = array("error_code" =>100,"message"=>"Record Updated ");
    echo json_encode($response);
}
} 

function check_error($agency_id, $visitor_location_id, $type, $visitor_mgmt,$printer_provided, $employee_upload_link, $approval_required, $visitor_id_required, $approval_weblink_expired_in, $amt_paid_by, $verification_amt, $visiting_charges)
{
    if (empty($agency_id)) {
        $response = array("error_code" => 103, "message" => "The parameter 'agency_id' is required and cannot be empty");
        echo json_encode($response);
        exit;
    }

    if (empty($visitor_location_id)) {
        $response = array("error_code" => 103, "message" => "The parameter 'visitor_location_id' is required and cannot be empty");
        echo json_encode($response);
        exit;
    }

    if (empty($type)) {
        $response = array("error_code" => 103, "message" => "The parameter 'type' is required and cannot be empty");
        echo json_encode($response);
        exit;
    } else if (!in_array($type, ['1', '2', '3'])) {
        $response = array("error_code" => 103, "message" => "The parameter 'type' must be either '1', '2', or '3'");
        echo json_encode($response);
        exit;
    }

    if (!empty($visitor_mgmt) && !in_array($visitor_mgmt, ['Y', 'y', 'N', 'n'])) {
        $response = array("error_code" => 103, "message" => "If provided, the parameter 'visitor_mgmt' must be 'Y' or 'N'");
        echo json_encode($response);
        exit;
    }
    if (empty($printer_provided)) {
        $response = array("error_code" => 103, "message" => "The parameter 'printer_provided' is required and cannot be empty");
        echo json_encode($response);
        exit;
    } else if (!in_array($printer_provided, ['Y', 'y', 'N', 'n'])) {
        $response = array("error_code" => 103, "message" => "The parameter 'printer_provided' must be 'Y' or 'N'");
        echo json_encode($response);
        exit;
    }

    if (!empty($employee_upload_link) && !in_array($employee_upload_link, ['Y', 'y', 'N', 'n'])) {
        $response = array("error_code" => 103, "message" => "If provided, the parameter 'employee_upload_link' must be 'Y' or 'N'");
        echo json_encode($response);
        exit;
    }
    if (!empty($approval_required) && !in_array($approval_required, ['Y', 'y', 'N', 'n'])) {
        $response = array("error_code" => 103, "message" => "If provided, the parameter 'approval_required' must be 'Y' or 'N'");
        echo json_encode($response);
        exit;
    }
    if (!empty($visitor_id_required) && !in_array($visitor_id_required, ['Y', 'y', 'N', 'n'])) {
        $response = array("error_code" => 103, "message" => "If provided, the parameter 'visitor_id_required' must be 'Y' or 'N'");
        echo json_encode($response);
        exit;
    }
  
    if (!empty($approval_weblink_expired_in)) {
        if (!is_numeric($approval_weblink_expired_in) || (int)$approval_weblink_expired_in < 1 || (int)$approval_weblink_expired_in > 999) {
            $response = array("error_code" => 103, "message" => "The parameter 'approval_weblink_expired_in' must be a numeric value between 1 and 999");
            echo json_encode($response);
            exit;
        }
    }
    if (empty($amt_paid_by)) {
        $response = array("error_code" => 103, "message" => "The parameter 'amt_paid_by' is required and cannot be empty");
        echo json_encode($response);
        exit;
    }

    if (!empty($amt_paid_by) && !in_array($amt_paid_by, ['1', '2'])) {
        $response = array("error_code" => 103, "message" => "If provided, the parameter 'approval_weblink_expired_in' must be '1', '2'");
        echo json_encode($response);
        exit;
    }

 

    if (!empty($visiting_charges)) {
        $response = array("error_code" => 103, "message" => "The parameter 'visiting_charges' is required and cannot be empty");
        echo json_encode($response);
        exit;
    }

   
    return 1;
}
