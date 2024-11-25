<?php
include_once "connection.php";

// Create database connection instance
$connection1 = database::getInstance();
$mysqli1 = $connection1->getConnection();

// Get JSON data from input stream
$json_data = file_get_contents("php://input");

// Decode JSON data into PHP associative array
$data_array = json_decode($json_data, true);

// Check if item_no and ref_code are provided in the JSON data
if (!isset($data_array['item_no']) || !isset($data_array['ref_code'])) {
    $response[] = ["error_code" => 103, "message" => "Please provide the parameters - item_no and ref_code"];
    echo json_encode($response);
    return;
}

$item_no = $data_array['item_no'];
$ref_code = $data_array['ref_code'];

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    $response[] = ["error_code" => 102, "message" => "Please change the request method to POST"];
    echo json_encode($response);
    return;
}

// Check for database connection
if (!$mysqli1) {
    $response[] = ["error_code" => 101, "message" => "An unexpected server error occurred while processing your request. Please try again later."];
    echo json_encode($response);
    return;
}

// Check if item_no and ref_code are empty
if (empty($item_no) || empty($ref_code)) {
    $response[] = ["error_code" => 104, "message" => "Value of 'item_no' and 'ref_code' cannot be empty"];
    echo json_encode($response);
    return;
}

// Check if the item exists in the database
$fetch_product_item = "SELECT `item_no`, `ref_code` FROM item_master_all WHERE `item_no`='$item_no' AND `ref_code`='$ref_code'";
$res_product_item = mysqli_query($mysqli1, $fetch_product_item);

if (mysqli_num_rows($res_product_item) == 0) {
    $response[] = ["error_code" => 109, "message" => "item_no or ref_code is invalid"];
    echo json_encode($response);
    return;
}

// Generate a random watch code and update the database
$rand = rand(1000, 9999);
$update_item = "UPDATE `item_master_all` SET `watch_code`='$rand' WHERE `item_no`='$item_no' AND `ref_code`='$ref_code'";
$res_item = mysqli_query($mysqli1, $update_item);

if ($res_item) {
    $response[] = ["error_code" => 100, "message" => "Watch code updated successfully", "watch_code" => $rand];
    echo json_encode($response);
    return;
} else {
    $response[] = ["error_code" => 101, "message" => "An unexpected server error occurred while updating watch code"];
    echo json_encode($response);
    return;
}
?>
