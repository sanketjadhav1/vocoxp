<?php
include 'connection.php';
$connection = connection::getInstance();
$mysqli = $connection->getConnection();

$connection1 = database::getInstance();
$mysqli1 = $connection1->getConnection();

if ($_SERVER['REQUEST_METHOD'] == "PUT") {
    $bulk_id = $_GET['bulk_id'];
    $agency_id = $_GET['agency_id'];

    $update_setting = "UPDATE `bulk_weblink_request_all` SET `status` = 4 WHERE `agency_id`='$agency_id' AND `bulk_id`='$bulk_id'";
$res_setting = mysqli_query($mysqli, $update_setting);

if ($res_setting) {
   
        $res = ["error_code" => 100, "message" => "success"];
    
} else {
    $res = ["error_code" => 110, "message" => "update query failed"];
}

echo json_encode($res);
}
?>
