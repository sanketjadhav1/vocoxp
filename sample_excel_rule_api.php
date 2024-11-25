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

$excel_records = "SELECT id, excel_no,stake_holder, type, obj_1, obj_2, obj_3 FROM sample_excel_definations_all Where type_for_excel='1'";
$geting_excel_records = $mysqli->query($excel_records);

if ($geting_excel_records->num_rows > 0) {
    $data = array();

    while ($row = $geting_excel_records->fetch_assoc()) {
        // Initialize an empty array for the data to return for each row
        $record = array(
            "id" => $row['id'],
            "excel_no" => $row['excel_no'],
            "type" => $row['type'],
            "count"=>$row['stake_holder']
        );

        // Decode the JSON objects and check for empty arrays
        $verification_for_obj_1 = json_decode($row['obj_1'], true);
        $verification_for_obj_2 = json_decode($row['obj_2'], true);
        $verification_for_obj_3 = json_decode($row['obj_3'], true);
        
        // Assign the objects to the record, ensuring empty arrays are converted to empty objects
        $record['obj_1'] = !empty($verification_for_obj_1) ? $verification_for_obj_1 : new stdClass();
        $record['obj_2'] = !empty($verification_for_obj_2) ? $verification_for_obj_2 : new stdClass();
        $record['obj_3'] = !empty($verification_for_obj_3) ? $verification_for_obj_3 : new stdClass();
        
        // Add the record to the final data array
        $data[] = $record;
    }

    // Successful response
    $response = array(
        "error_code" => 100,
        "message" => "Records fetched successfully",
        "data" => $data
    );
} else {
    // No records found
    $response = array(
        "error_code" => 125,
        "message" => "No records found"
    );
}

// Output the response as JSON
echo json_encode($response);
