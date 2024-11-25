<?php
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');
date_default_timezone_set('Asia/Kolkata');

include 'connection.php';
$connection = connection::getInstance();
$mysqli = $connection->getConnection();
$mode = isset($_POST['mode']) ? $_POST['mode'] : '';
$agency_id = isset($_POST['agency_id']) ? $_POST['agency_id'] : '';
$emp_id = isset($_POST['emp_id']) ? $_POST['emp_id'] : '';
$name = isset($_POST['name']) ? $_POST['name'] : '';
$contact = isset($_POST['contact']) ? $_POST['contact'] : '';
$department = isset($_POST['department']) ? $_POST['department'] : '';
$designation = isset($_POST['designation']) ? $_POST['designation'] : '';
$email_id = isset($_POST['email_id']) ? $_POST['email_id'] : '';
$visitor_approval_required = isset($_POST['visitor_approval_required']) ? $_POST['visitor_approval_required'] : '';
$visiting_charges = isset($_POST['visiting_charges']) ? $_POST['visiting_charges'] : '';
$verification_paid_by = isset($_POST['verification_paid_by']) ? $_POST['verification_paid_by'] : '';

if ($_SERVER['REQUEST_METHOD'] != "POST") {
    $response = array("error_code" => 102, "message" => "Request Method Is Not POST");
    echo json_encode($response);
    exit;
}

$check_error = check_error($mode, $agency_id, $emp_id);
if ($check_error == 1) {
    $check_agncy_id_query = "SELECT `agency_id` FROM `agency_header_all` WHERE `agency_id` = '$agency_id'";
    $check_agncy_id_res = $mysqli->query($check_agncy_id_query);
    $check_agncy_id_row = $check_agncy_id_res->num_rows;

    if ($check_agncy_id_row != 1) {
        $response = array("error_code" => 103, "message" => "Invalid 'agency_id'");
        echo json_encode($response);
        exit;
    }
    if ($mode == "update_mode") {
        $check_emp_id_query = "SELECT `emp_id` FROM `employee_header_all` WHERE `emp_id` = '$emp_id' AND status ='1'";
        $check_emp_id_res = $mysqli->query($check_emp_id_query);
        $check_emp_id_row = $check_emp_id_res->num_rows;
    
        if ($check_emp_id_row != 1) {
            $response = array("error_code" => 103, "message" => "Invalid 'emp_id'");
            echo json_encode($response);
            exit;
        }

        $update_query = "UPDATE employee_header_all SET name='$name',contact='$contact',department='$department',designation='$designation',email_id='$email_id',visitor_approval_required='$visitor_approval_required',visiting_charges='$visiting_charges',verification_paid_by='$verification_paid_by' WHERE agency_id='$agency_id' AND emp_id='$emp_id'";
        $res_update_query = $mysqli->query($update_query);
        if($res_update_query){
            $response = array("error_code" => 100, "message" => "Record Updated");
            echo json_encode($response);

        }
    } else if ($mode == "delete_mode") {
        if (!empty($emp_id)) {
            $emp_ids_array = explode(',', $emp_id);
            $emp_ids_array = array_map(function($emp_id) use ($mysqli) {
                return "'" . $mysqli->real_escape_string(trim($emp_id)) . "'";
            }, $emp_ids_array);
            $emp_ids_str = implode(',', $emp_ids_array);
            $delete_query = "UPDATE employee_header_all 
                             SET status=0 
                             WHERE agency_id='$agency_id' 
                             AND emp_id IN ($emp_ids_str)";
            $res_delete_query = $mysqli->query($delete_query);
            if ($res_delete_query) {
                $response = array("error_code" => 100, "message" => "Records Deleted");
            } else {
                $response = array("error_code" => 101, "message" => "Failed to delete records");
            }
        } else {
            $response = array("error_code" => 102, "message" => "emp_id not provided");
        }
        echo json_encode($response);
    } else {
        $response = array("error_code" => 103, "message" => "Invalid 'mode' the mode can be either 'update_mode' or 'delete_mode'");
        echo json_encode($response);
        exit;
    }
}

function check_error($mode, $agency_id, $emp_id)
{
    if (empty($mode)) {
        $response = array("error_code" => 103, "message" => "The parameter 'mode' is required and cannot be empty");
        echo json_encode($response);
        exit;
    }
    if (empty($agency_id)) {
        $response = array("error_code" => 103, "message" => "The parameter 'agency_id' is required and cannot be empty");
        echo json_encode($response);
        exit;
    }
    if (empty($emp_id)) {
        $response = array("error_code" => 103, "message" => "The parameter 'emp_id' is required and cannot be empty");
        echo json_encode($response);
        exit;
    }
    return 1;
}
