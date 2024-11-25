<?php

/* 
Name : json for single device login
Mode :- single mode
Developed By - Ajit Bodkhe/ Rishabh shinde
*/
// verify_device logout_device 

header('Content-Type: application/json; charset=UTF-8');
header('Access-Contorl-Allow-Origin: *');
date_default_timezone_set('Asia/kolkata');
include 'connection.php';
$connection = connection::getInstance();
$mysqli = $connection->getConnection();
apponoff($mysqli);
date_default_timezone_set('Asia/kolkata');



if ($_SERVER['REQUEST_METHOD'] != "POST") {
    $response[] = array("error_code" => 102, "message" => "Request Method Is not Post");
    echo json_encode($response);
    exit;
}

$system_date = date("y-m-d");
$system_date_time = date("Y-m-d H:i:s"); 
   
$mobile_no = $_POST['mobile_no'];

$status="active";
$otp=rand(0000,9999);
$admin_id = "AD-".rand(00000,99999);


$mode=$_POST['mode'];
$current_version = $_POST['current_version'];
$operating_system = $_POST['operating_system'];
$os_version = $_POST['os_version'];
$login_pin = $_POST['login_pin'];
// $agency_id = $_POST['agency_id'];
$device_id = $_POST['device_id'];
$token=generateRandomString(10);
$device_token=$token.",".$device_id;
$common_check = common_check_error($device_id, $mobile_no, $mysqli, $agency_id, $current_version ,$operating_system, $os_version, $login_pin);
if ($common_check == 1) {
    
    $fetch_appuser="SELECT `app_device_token`, 
    `login_pin`, `reg_mobile_no` FROM `app_user_token_details_all` WHERE `reg_mobile_no`='$mobile_no'";
    $res_user=mysqli_query($mysqli, $fetch_appuser);
    $arr_user=mysqli_fetch_assoc($res_user);

    

    
    
    if($mode=="verify_device" ){
    

    $check_device=explode(",",$arr_user['app_device_token']);
    if ($device_id != $check_device[1]) {
        $response[] = array(
            "error_code" => 100,
            "auth_token" => $check_device[0], // Remove unnecessary quotes
            "login_pin" => $arr_user['login_pin'], // Remove unnecessary quotes
            // "username" => $arr_agency['name'], // Assuming 'name' is the agency username
            "message" => "another device already login",
            "another_device_already_login" => "yes"
        );
        echo json_encode($response);
    } else {
        $response[] = array(
            "error_code" => 100,
            "auth_token" => $check_device[0], // Remove unnecessary quotes
            "login_pin" => $arr_user['login_pin'], // Remove unnecessary quotes
            // "username" => $arr_agency['name'], // Assuming 'name' is the agency username
            "message" => "success",
            "another_device_already_login" => "no"
        );
        echo json_encode($response);
    }
}
elseif($mode=="logout_device"){

     $update_user_app="UPDATE `app_user_token_details_all` SET `app_device_token`='$device_token', `current_version`='$current_version', `operating_system`='$operating_system', `os_version`='$os_version' WHERE `reg_mobile_no`='$mobile_no'";
    $res_update=mysqli_query($mysqli, $update_user_app);


$data[]=["auth_key"=>"$token",  "login_pin"=>$arr_user['login_pin']];
    $response[] = array("error_code" => 100, "message" => "Update successfully", "data"=>$data);
    echo json_encode($response);


}
}
    

function common_check_error($device_id, $mobile_no, $mysqli, $agency_id, $current_version ,$operating_system, $os_version, $login_pin)
{
    $common_check = 1;

   
    if (!isset($device_id)) {
        $response[] = array("error_code" => 107, "message" => "Please pass the parameter of device_id");
        echo json_encode($response);
        exit;
    }
    if ($device_id == "") {
        $response[] = array("error_code" => 108, "message" => "value of device_id  can not be empty");
        echo json_encode($response);
        exit;


    }

    if (!isset($mobile_no)) {
        $response[] = array("error_code" => 109, "message" => "Please pass the parameter of mobile_no");
        echo json_encode($response);
        exit;

    }
    if ($mobile_no == "") {
        $response[] = array("error_code" => 110, "message" => "value of mobile_no  can not be empty");
        echo json_encode($response);
        exit;


    }
    
    // if (!isset($agency_id)) {
    //     $response[] = array("error_code" => 113, "message" => "Please pass the parameter of agency_id");
    //     echo json_encode($response);
    //     exit;
    // }
    // if ($agency_id == "") {
    //     $response[] = array("error_code" => 114, "message" => "value of agency_id  can not be empty");
    //     echo json_encode($response);
    //     exit;
    // }

   
    if (!isset($current_version)) {
        $response[] = array("error_code" => 121, "message" => "Please pass the parameter of current_version");
        echo json_encode($response);
        exit;
    }
    if ($current_version == "") {
        $response[] = array("error_code" => 122, "message" => "value of current_version  can not be empty");
        echo json_encode($response);
        exit;
    }
    if (!isset($operating_system)) {
        $response[] = array("error_code" => 123, "message" => "Please pass the parameter of operating_system");
        echo json_encode($response);
        exit;
    }
    if ($operating_system == "") {
        $response[] = array("error_code" => 124, "message" => "value of operating_system  can not be empty");
        echo json_encode($response);
        exit;
    }
    if (!isset($os_version)) {
        $response[] = array("error_code" => 125, "message" => "Please pass the parameter of os_version");
        echo json_encode($response);
        exit;
    }
    if ($os_version == "") {
        $response[] = array("error_code" => 126, "message" => "value of os_version  can not be empty");
        echo json_encode($response);
        exit;
    }
    // if (!isset($login_pin)) {
    //     $response[] = array("error_code" => 127, "message" => "Please pass the parameter of login_pin");
    //     echo json_encode($response);
    //     exit;
    // }
    // if ($login_pin == "") {
    //     $response[] = array("error_code" => 128, "message" => "value of login_pin  can not be empty");
    //     echo json_encode($response);
    //     exit;
    // }

    
    if (!$mysqli) {
        $response[] = array(
            "error_code" => 101,
            "message" => "An unexpected server error occurred while
        processing your request. Please try after sometime"
        );
        echo json_encode($response);
        exit;

    }

    return $common_check;
}

function generateRandomString($length) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    $max = strlen($characters) - 1;
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $max)];
    }
    return $randomString;
}
?>