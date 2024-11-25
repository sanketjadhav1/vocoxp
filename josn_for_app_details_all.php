<?php

/* 
Name : json for app details
Developed By - Ajit Bodkhe
*/


// error_reporting(E_ALL);
// ini_set('display_errors', 1);
include 'connection.php';
$connection = connection::getInstance();
$mysqli = $connection->getConnection();
apponoff($mysqli);
date_default_timezone_set('Asia/kolkata');
$system_date = date("y-m-d");
$system_date_time = date("Y-m-d H:i:s");

// logout($_SERVER['HTTP_AUTH_KEY'], $mysqli);

if ($_SERVER['REQUEST_METHOD'] != "POST") {
    $response[] = array("error_code" => 102, "message" => "Request Method Is not Post");
    echo json_encode($response);
    exit;
}

$data[]=["app_maintenance"=>"No", "maintenance_message" => "Our App Is Under Maintenance", "P_SIN_0001"=>"https://mounarchtech.com/vocoxp/terms_&_condition.php", "P_SIN_0002"=>"https://mounarchtech.com/vocoxp/terms_&_condition.php", "P_DIG_0001"=>"https://mounarchtech.com/vocoxp/terms_&_condition.php", "P_WAR_0001"=>"https://mounarchtech.com/vocoxp/terms_&_condition.php"];
$response[] = ["error_code" => 100, "data"=>$data];
echo json_encode($response);



?>