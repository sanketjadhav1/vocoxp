<?php
/* 
Name : json for agency profile
Developed By - Ajit Bodkhe
*/
header('Content-Type: application/json; charset=UTF-8');
header('Access-Contorl-Allow-Origin: *');
date_default_timezone_set('Asia/kolkata');
include 'connection.php';
$connection = connection::getInstance();
$mysqli = $connection->getConnection();
// apponoff($mysqli);
date_default_timezone_set('Asia/kolkata');
$system_date = date("y-m-d");
$system_date_time = date("Y-m-d H:i:s");

if ($_SERVER['REQUEST_METHOD'] != "POST") {
    $response[] = array("error_code" => 102, "message" => "Request Method Is not Post");
    echo json_encode($response);
    exit;
}
$agency_id=$_POST['agency_id'];

$commom_check=commom_check($agency_id, $mysqli);
if($commom_check==1){

  $query="SELECT `agency_id`, `name`, `mobile_no`, `email_id`, `company_name`, `city`, `address`, `profession`, `no_of_employees`, `employee_designation`, `type`, `employee_type`, `work_type`, `owning_company`, `agency_gst_no`, `business_type` AS `function_area`, `employee_designation` AS `working_as`, `employee_designation` AS `working_profession`,  `employee_type` AS `are_you_a` FROM `agency_header_all` WHERE `agency_id`='$agency_id' AND `status`=1";
$res=$mysqli->query($query);

 $arr=mysqli_fetch_assoc($res);

// print_r($arr);
if(empty($arr)){
    $response[] = ["error_code" => 199, "message" => "invalid agency_id "];
    echo json_encode($response);
    exit; 
}else{
    $arr['type']=$arr['type']==1?"organization":"individual";
$data[]=$arr;
    $response[] = ["error_code" => 100, "message" => "Success", "data"=>$data];
    echo json_encode($response);
    exit; 
}

}



function commom_check($agency_id, $mysqli){
    $commom_check=1;
    if (!isset($agency_id)) {
        $response[] = array("error_code" => 107, "message" => "Please pass the parameter of agency_id");
        echo json_encode($response);
        exit;
    }
    if ($agency_id == "") {
        $response[] = array("error_code" => 108, "message" => "value of agency_id  can not be empty");
        echo json_encode($response);
        exit;
    }

    if (!$mysqli) {
        $response[] = ["error_code" => 103, "message" => "Unable to proceed your request, please try again after some time"];
        echo json_encode($response);
        exit;
    }
    return $commom_check;
}



?>