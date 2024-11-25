<?php
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');
date_default_timezone_set('Asia/Kolkata');

include 'connection.php';
$connection = connection::getInstance();
$mysqli = $connection->getConnection();
$agency_id =isset($_POST['agency_id'])? $_POST['agency_id'] :'';
$visitor_location_id = isset($_POST['visitor_location_id']) ? $_POST['visitor_location_id']:'';
$mode = isset($_POST['mode']) ? $_POST['mode'] : '';
if ($_SERVER['REQUEST_METHOD'] != "POST") {
    $response[] = array("error_code" => 102, "message" => "Request Method Is Not POST");
    echo json_encode($response);
    exit;
}

$check_error = check_error($agency_id,$visitor_location_id,$mode);

if($mode == "delete_mode"){
    
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
    $deleteQuery = "UPDATE agency_visitor_location_header_all SET status = 2 where   agency_id = '$agency_id' AND
visitor_location_id = '$visitor_location_id'";
$deleteQuery = $mysqli->query($deleteQuery);
if($deleteQuery){
    $response[] = array("error_code"=>100, "message"=>"record deleted successfully");
    echo json_encode($response);
}
}


function check_error($agency_id,$visitor_location_id,$mode)
{
    if ($_SERVER["REQUEST_METHOD"] != "POST") {
        $response[] = array("error_code" => 103, "message" => "Please change the request method to POST");
        echo json_encode($response);
        exit;
    }

    if (empty($agency_id)) {
        $response[] = array("error_code" => 103, "message" => "The parameter 'agency_id' is required and cannot be empty");
        echo json_encode($response);
        exit;
    }
    if (empty($visitor_location_id)) {
        $response[] = array("error_code" => 103, "message" => "The parameter 'visitor_location_id' is required and cannot be empty");
        echo json_encode($response);
        exit;
    }

    if (empty($mode)) {
        $response[] = array("error_code" => 103, "message" => "The parameter 'mode' is required and cannot be empty");
        echo json_encode($response);
        exit;
    }
 
   
    return 1;
}

