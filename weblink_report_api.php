<?php
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');
date_default_timezone_set('Asia/Kolkata');
error_reporting(1);
include 'connection.php';
$connection = connection::getInstance();
$mysqli = $connection->getConnection();
$connection1 = database::getInstance();
$mysqli1 = $connection1->getConnection();
if ($_SERVER['REQUEST_METHOD'] == "GET") {
    $agency_id = $_GET['agency_id'];
    $bulk_id = $_GET['bulk_id'];
    $verification_for = $_GET['verification_for'];

    // Check if agency_id is valid
    $check_agncy_id_query = "SELECT `agency_id` FROM `agency_header_all` WHERE `agency_id` = '$agency_id'";
    $check_agncy_id_res = $mysqli->query($check_agncy_id_query);
    $check_agncy_id_row = $check_agncy_id_res->num_rows;

    if ($check_agncy_id_row != 1) {
        $response = array("error_code" => 124, "message" => "Invalid 'agency_id'");
        echo json_encode($response);
        exit;
    }

    if ($verification_for == 1) {
        $select_query = "SELECT end_user_id, first_name, document_type, aadhar_ambiguity, report_url, completed_on FROM end_user_verification_transaction_all WHERE agency_id = '$agency_id' AND weblink_id = '$bulk_id'";
    } else {
        $select_query = "SELECT end_user_id, first_name, document_type, pan_ambiguity, report_url, completed_on FROM end_user_verification_transaction_all WHERE agency_id = '$agency_id' AND weblink_id = '$bulk_id'";
    }

    $res_select_query = $mysqli->query($select_query);

    if ($res_select_query->num_rows > 0) {
        $data = array(); // Initialize an array to hold the fetched data

        while ($row = $res_select_query->fetch_assoc()) {
            $document_type = $row["document_type"];
            $verification_details = "SELECT * FROM `verification_header_all` WHERE `verification_id`='$document_type'";
            $details_result = $mysqli1->query($verification_details); 
            $ver_array = mysqli_fetch_assoc($details_result);

            $ambiguity = $verification_for == 1 ? $row['aadhar_ambiguity'] : $row['pan_ambiguity'];
            $is_report_ok = strtolower(trim($ambiguity)) === "ok=all" ? "Yes" : "No";

            $data[] = array(
                "end_user_id" => $row["end_user_id"],
                "name" => $row["first_name"],
                "verification_type" => $ver_array["name"],
                "is_report_ok" => $is_report_ok,
                "report_url" => $row["report_url"],
                "completed_on" => $row["completed_on"]
            );
        }


        // Send a success response with error code 100 and the fetched data
        $response = array(
            "error_code" => 100,
            "message" => "Data fetched",
            "data" => $data
        );
        echo json_encode($response);
    } else {
        // If no records are found, return an appropriate message
        $response = array("error_code" => 103, "message" => "No records found");
        echo json_encode($response);
    }
}
