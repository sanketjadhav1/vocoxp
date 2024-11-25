<?php
include_once '../connection.php';

// error_reporting(E_ALL & ~E_DEPRECATED);

// ini_set('display_errors', 1);

// Now you can use the connection class

$connection = connection::getInstance();

$mysqli = $connection->getConnection();



$connection1 = database::getInstance();

$mysqli1 = $connection1->getConnection();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $requestNo = $_POST['request_no'];
    $agencyId = $_POST['agency_id'];
    $bulkId = $_POST['bulk_id'];

    // Include your database connection
    // include 'db_connection.php'; // Adjust the path as necessary

    // Use prepared statements to prevent SQL injection
    $stmt = $mysqli->prepare("SELECT * FROM `bulk_weblink_request_all` WHERE `request_no` = ?");
    $stmt->bind_param("s", $requestNo);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
        echo json_encode($data);
    } else {
        echo json_encode(array('error' => 'Data not found'));
    }

    $stmt->close();
    $mysqli->close();
}
?>
