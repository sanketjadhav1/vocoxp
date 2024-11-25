<?php

header('Content-Type: application/json; charset=UTF-8');
header('Access-Contorl-Allow-Origin: *');
date_default_timezone_set('Asia/kolkata');
include 'connection.php';
// Now you can use the connection class

//   $connection1 = database::getInstance();
// $mysqli1 = $connection1->getConnection();
$connection = connection::getInstance();
$mysqli = $connection->getConnection();
$connection1 = database::getInstance();
$mysqli1 = $connection1->getConnection();
date_default_timezone_set('Asia/kolkata');
$system_date = date("y-m-d");
$system_date_time = date("Y-m-d H:i:s");
$output = $responce = array();
apponoff($mysqli);
// logout($_SERVER['HTTP_AUTH_KEY'], conn: $mysqli);
$connection = connection::getInstance();
$mysqli = $connection->getConnection();
$visitor_location_id = isset($_GET['visitor_location_id']) ? $mysqli->real_escape_string($_GET['visitor_location_id']) : null;
$agency_id = isset($_GET['agency_id']) ? $mysqli->real_escape_string($_GET['agency_id']) : null;

// $visitor_location_id = $_GET['visitor_location_id'];
if ($_SERVER['REQUEST_METHOD'] != "GET") {
    $response[] = array("error_code" => 102, "message" => "Request Method Is Not GET");
    echo json_encode($response);
    exit;
}

if (!empty($agency_id) && !empty($visitor_location_id)) {
    $check_visitor_location_id_query = "SELECT `visitor_location_id` FROM `agency_visitor_location_header_all` WHERE `visitor_location_id` = '$visitor_location_id'";
    $check_visitor_location_id_res = $mysqli->query($check_visitor_location_id_query);
    $check_visitor_location_id_row = $check_visitor_location_id_res->num_rows;

    if ($check_visitor_location_id_row != 1) {
        $response[] = array("error_code" => 124, "message" => "Invalid 'visitor_location_id'");
        echo json_encode($response);
        exit;
    }
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
     avl.agency_id = '$agency_id' AND
    avl.visitor_location_id = '$visitor_location_id'  AND avl.status = 1";

    
$getSinglerecordQuery = $mysqli->query($getSinglerecord);

if ($getSinglerecordQuery->num_rows > 0) {
    $data = $getSinglerecordQuery->fetch_assoc();

    // Convert 'operational_from' to dd-mm-yyyy format if it's not empty
    if (!empty($data['operational_from'])) {
         $date = new DateTime($data['operational_from']);
        $data['operational_from'] = $date->format('d/m/Y');
    }

    // Return data as an associative array
    $response = array(
        "error_code" => 100, 
        "message" => "Record found", 
        "data" => $data
    );
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
    avl.visitor_location_id, avl.location_name, avl.operational_from, avl.location_admins, avl.location_state,
    avl.location_city, avl.location_pincode, avl.location_radius, avl.location_coordinates, vls.type,
    vls.visiting_hours, vls.weekoffs, vls.printer_provided, vls.visitor_mgmt, vls.employee_upload_link, vls.approval_required, 
    vls.approval_weblink_expired_in, vls.visitor_id_required, vls.verification_amt, vls.amt_paid_by, vls.visiting_charges
    FROM agency_visitor_location_header_all avl 
    LEFT JOIN visitor_location_setting_details_all vls 
    ON avl.visitor_location_id = vls.visitor_location_id 
    WHERE avl.agency_id = '$agency_id' AND avl.status = 1";

$getSinglerecordQuery = $mysqli->query($getSinglerecord);

if ($getSinglerecordQuery->num_rows > 0) {
    $data = array(); // Initialize an empty array to hold all the records
    while($row = $getSinglerecordQuery->fetch_assoc()) {
           if (!empty($row['operational_from'])) {
            $date = new DateTime($row['operational_from']);
            $row['operational_from'] = $date->format('d/m/Y');
        }

        $data[] = $row; 
        
    }
    $response[] = array("error_code" => 100, "message" => "Records found", "data" => $data);
} else {
    $response[] = array("error_code" => 102, "message" => "No record found");
}

echo json_encode($response);

}else {
    $response[] = array("error_code" => 102, "message" => "Please Provide agency_id");
    echo json_encode($response);
    // SQL Query to fetch all records from both tables using LEFT JOIN
//     $getallQuery = "
// SELECT avl.agency_id,avl.visitor_location_id,avl.location_name,avl.operational_from,avl.location_admins,
//     avl.location_state,avl.location_city,avl.location_pincode,avl.location_radius,avl.location_coordinates,
//     vls.type,vls.visiting_hours,vls.weekoffs,vls.printer_provided,vls.visitor_mgmt,vls.employee_upload_link,
//     vls.approval_required,vls.approval_weblink_expired_in,vls.visitor_id_required,vls.verification_amt,
//     vls.amt_paid_by,vls.visiting_charges
// FROM 
//     agency_visitor_location_header_all avl
// LEFT JOIN 
//     visitor_location_setting_details_all vls
// ON 
//     avl.visitor_location_id = vls.visitor_location_id";

//     $getallSqlQuery = $mysqli->query($getallQuery);

//     if ($getallSqlQuery->num_rows > 0) {
//         $data = array();

//         // Fetch all records and push to the data array
//         while ($row = $getallSqlQuery->fetch_assoc()) {
//             $data[] = $row;
//         }
//         $response[] = array("error_code" => 100, "message" => "Records found", "data" => $data);
//         echo json_encode($response);
//     } else {
//         $response[] = array("error_code" => 102, "message" => "No record found");
//         echo json_encode($response);
//     }
}
