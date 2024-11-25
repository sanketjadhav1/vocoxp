<?php
include 'connection.php';
$connection = connection::getInstance();
$mysqli = $connection->getConnection();

$connection1 = database::getInstance();
$mysqli1 = $connection1->getConnection();

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    $bulk_id = $_GET['bulk_id'];
    $agency_id = $_GET['agency_id'];

    $fetc_setting = "SELECT `payment_invoice` FROM `bulk_end_user_transaction_all` WHERE `agency_id`='$agency_id' AND `bulk_id`='$bulk_id' AND `payment_invoice`<> ''";
    $res_setting = mysqli_query($mysqli, $fetc_setting);

    $data = array();

    if ($res_setting) {
        while ($arr_setting = mysqli_fetch_assoc($res_setting)) {
            $data[] = $arr_setting;
        }
        
        if (!empty($data)) {
            $res = ["error_code" => 100, "message" => "success", "data" => $data];
        } else {
            $res = ["error_code" => 109, "message" => "no records found"];
        }
    } else {
        $res = ["error_code" => 109, "message" => "query execution failed"];
    }

    echo json_encode($res);
}
?>
