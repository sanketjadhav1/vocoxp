<?php

header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');
date_default_timezone_set('Asia/Kolkata');

include 'connection.php';
$connection = connection::getInstance();
$mysqli = $connection->getConnection();
$visitor_location_id = isset($_GET['visitor_location_id']) ? $mysqli->real_escape_string($_GET['visitor_location_id']) : null;
$agency_id = isset($_GET['agency_id']) ? $mysqli->real_escape_string($_GET['agency_id']) : null;



logout($_SERVER['HTTP_AUTH_KEY'], $mysqli);
// $visitor_location_id = $_GET['visitor_location_id'];
if ($_SERVER['REQUEST_METHOD'] != "GET") {
    $response[] = array("error_code" => 102, "message" => "Request Method Is Not GET");
    echo json_encode($response);
    exit;
}

if (!empty($visitor_location_id)) {
    $check_visitor_location_id_query = "SELECT `visitor_location_id` FROM `agency_visitor_location_header_all` WHERE `agency_id` = '$visitor_location_id'";
    $check_visitor_location_id_res = $mysqli->query($check_visitor_location_id_query);
    $check_visitor_location_id_row = $check_visitor_location_id_res->num_rows;

    if ($check_visitor_location_id_row != 1) {
        $response[] = array("error_code" => 124, "message" => "Invalid 'visitor_location_id'");
        echo json_encode($response);
        exit;
    }



    $getSinglerecord = "SELECT 
    avl.agency_id,
    avl.visitor_location_id,avl.location_name,avl.operational_from,avl.location_admins,avl.location_state,
    avl.location_city,avl.location_pincode,avl.location_radius,avl.location_coordinates,vls.type,
    vls.visiting_hours,vls.weekoffs,vls.printer_provided,vls.visitor_mgmt,vls.employee_upload_link,vls.approval_required,vls.approval_weblink_expired_in,
    vls.visitor_id_required,vls.verification_amt,vls.amt_paid_by,vls.visiting_charges
    FROM agency_visitor_location_header_all avl LEFT JOIN  visitor_location_setting_details_all vls
    ON avl.visitor_location_id = vls.visitor_location_id WHERE 
    avl.visitor_location_id = '$visitor_location_id'";

    $getSinglerecordQuery = $mysqli->query($getSinglerecord);

        if ($getSinglerecordQuery->num_rows > 0) {
         $data = $getSinglerecordQuery->fetch_assoc();
             $response[] = array("error_code" => 100, "message" => "Record found", "data" => $data);
        } else {
         $response[] = array("error_code" => 102, "message" => "No record found");
        }
            echo json_encode($response);
}else if(!empty($agency_id)){
    $check_agncy_id_query = "SELECT `agency_id` FROM `agency_header_all` WHERE `agency_id` = '$agency_id'";
    $check_agncy_id_res = $mysqli->query($check_agncy_id_query);
    $check_agncy_id_row = $check_agncy_id_res->num_rows;

    if ($check_agncy_id_row != 1) {
        $response[] = array("error_code" => 124, "message" => "Invalid 'agency_id'");
        echo json_encode($response);
        exit;
    }


    $getSinglerecord = "SELECT 
    avl.agency_id,
    avl.visitor_location_id,avl.location_name,avl.operational_from,avl.location_admins,avl.location_state,
    avl.location_city,avl.location_pincode,avl.location_radius,avl.location_coordinates,vls.type,
    vls.visiting_hours,vls.weekoffs,vls.printer_provided,vls.visitor_mgmt,vls.employee_upload_link,vls.approval_required,vls.approval_weblink_expired_in,
    vls.visitor_id_required,vls.verification_amt,vls.amt_paid_by,vls.visiting_charges
    FROM agency_visitor_location_header_all avl LEFT JOIN  visitor_location_setting_details_all vls
    ON avl.visitor_location_id = vls.visitor_location_id WHERE 
    avl.agency_id = '$agency_id'";
        $getSinglerecordQuery = $mysqli->query($getSinglerecord);

        if ($getSinglerecordQuery->num_rows > 0) {
         $data = $getSinglerecordQuery->fetch_assoc();
             $response[] = array("error_code" => 100, "message" => "Record found", "data" => $data);
        } else {
         $response[] = array("error_code" => 102, "message" => "No record found");
        }
            echo json_encode($response);

}else {

    // SQL Query to fetch all records from both tables using LEFT JOIN
    $getallQuery = "
SELECT avl.agency_id,avl.visitor_location_id,avl.location_name,avl.operational_from,avl.location_admins,
    avl.location_state,avl.location_city,avl.location_pincode,avl.location_radius,avl.location_coordinates,
    vls.type,vls.visiting_hours,vls.weekoffs,vls.printer_provided,vls.visitor_mgmt,vls.employee_upload_link,
    vls.approval_required,vls.approval_weblink_expired_in,vls.visitor_id_required,vls.verification_amt,
    vls.amt_paid_by,vls.visiting_charges
FROM 
    agency_visitor_location_header_all avl
LEFT JOIN 
    visitor_location_setting_details_all vls
ON 
    avl.visitor_location_id = vls.visitor_location_id";

    $getallSqlQuery = $mysqli->query($getallQuery);

    if ($getallSqlQuery->num_rows > 0) {
        $data = array();

        // Fetch all records and push to the data array
        while ($row = $getallSqlQuery->fetch_assoc()) {
            $data[] = $row;
        }
        $response[] = array("error_code" => 100, "message" => "Records found", "data" => $data);
        echo json_encode($response);
    } else {
        $response[] = array("error_code" => 102, "message" => "No record found");
        echo json_encode($response);
    }
}