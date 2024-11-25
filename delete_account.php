<?php
header('Content-Type: application/json; charset=UTF-8');
header('Access-Contorl-Allow-Origin: *');
date_default_timezone_set('Asia/kolkata');
include 'connection.php';
$connection = connection::getInstance();
$mysqli = $connection->getConnection();

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $agency_id=$_POST['agency_id'];

    $update_agency="UPDATE `agency_header_all` SET `status`=1 WHERE `agency_id`='$agency_id'";
    $res_agency=mysqli_query($mysqli, $update_agency);
    if($res_agency==true){
        $res[]=["error_code"=>100, "message"=>"Agency deleted successfully"];
        echo json_encode($res);
        exit;
    }
}
?>