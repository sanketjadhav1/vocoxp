<?php
/* 
Name : json for send otp (admin).
Mode :- single mode
Developed By - Ajit Bodkhe
*/
// error_reporting(E_ALL & ~E_DEPRECATED);
// ini_set('display_errors', 1);
header('Content-Type: application/json; charset=UTF-8');
header('Access-Contorl-Allow-Origin: *');
date_default_timezone_set('Asia/kolkata');
include 'connection.php';
$connection = connection::getInstance();
$mysqli = $connection->getConnection();
apponoff($mysqli);
logout($_SERVER['HTTP_AUTH_KEY'], $mysqli);

date_default_timezone_set('Asia/kolkata');
$system_date = date("y-m-d"); 
$system_date_time = date("Y-m-d H:i:s");

if ($_SERVER['REQUEST_METHOD'] != "POST") {
    $response[] = array("error_code" => 102, "message" => "Request Method Is not Post");
    echo json_encode($response);
    exit;
}

$mobile_no = $_POST['mobile_no'];
$agency_id = $_POST['agency_id'];


$comon_chk = common_chk_error($mobile_no, $mysqli, $agency_id);

if ($comon_chk == 1) {
    
    // Check if the mobile number is registered with any agency
$check_mobile_query = "SELECT `agency_id`, `mobile_no` FROM `agency_header_all` WHERE `mobile_no`='$mobile_no'";
$check_mobile_res = $mysqli->query($check_mobile_query);
$check_mobile_row = mysqli_num_rows($check_mobile_res);

// Check if the mobile number is registered with the current admin
$check_admin_mobile_query = "SELECT `mobile_no`, `agency_id` FROM `admin_header_all` where `mobile_no`='$mobile_no' AND `agency_id`='$agency_id'";
$check_admin_mobile_res = $mysqli->query($check_admin_mobile_query);
$check_admin_mobile_row = mysqli_num_rows($check_admin_mobile_res);

if ($check_mobile_row > 0) {
    // Mobile number is registered with another agency
    $response[] = array("error_code" => 111, "message" => "This mobile number is already registered with another agency");
} elseif ($check_admin_mobile_row > 0) {
    // Mobile number is already registered with this admin
    $response[] = array("error_code" => 112, "message" => "The mobile number is already registered with this agency.");
} else {
    // Generate OTP and send SMS
    $newotp = rand(1000, 9999);
    sms_helper_accept($mobile_no, $newotp);
    $response[] = ["error_code" => 100, "message" => "Success", "otp" => $newotp];
}

echo json_encode($response);
exit;


}

function common_chk_error($mobile_no, $mysqli, $agency_id)
{
    $common_check = 1;
    if (!isset($mobile_no)) {
        $response[] = ["error_code" => 107, "message" => "Please add mobile_no"];
        echo json_encode($response);
        return;
    }

    if (empty($mobile_no)) {
        $response[] = ["error_code" => 108, "message" => "mobile_no is empty"];
        echo json_encode($response);
        return;
    }

    if (!isset($agency_id)) {
        $response[] = ["error_code" => 109, "message" => "Please add agency_id"];
        echo json_encode($response);
        return;
    }

    if (empty($agency_id)) {
        $response[] = ["error_code" => 110, "message" => "agency_id is empty"];
        echo json_encode($response);
        return;
    }

    if (!$mysqli) {
        $response[] = ["error_code" => 101, "message" => "Unable to proceed your request, please try again after some time"];
        echo json_encode($response);
        return;
    }
    return $common_check;
}

function sms_helper_accept($contact, $otp)
{
    date_default_timezone_set("Asia/Kolkata");
    $date = date("d-m-Y");
    $error_flag = 0;
    $otp_prefix = ":-";
    $new_line = "\n";
    $dot = ".";
    $colon = ":";

    $message = urlencode("Welcome to VOCOxP!, Your OTP to verify contact number is $otp Developed by Micro Integrated");

    $response_type = "json";

    // Define route
    $route = "4";
    $mobile = "91" . $contact;
    // Prepare your post parameters
    $postData = [
        "authkey" => "362180AunaHgulfCm6698af66P1",
        "mobiles" => $mobile,
        "message" => $message,
        "sender" => "VOCOxP",
        "route" => $route,
        "response" => $response_type,
    ];

    // API URL
    $url = "http://api.msg91.com/api/sendhttp.php?authkey=362180AunaHgulfCm6698af66P1&sender=PMSafe&mobiles=$contact&route=$route&message=$message&DLT_TE_ID=1707172128019439144";

    // Init the resource
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $postData,
    ]);

    // Ignore SSL certificate verification
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

    // Get response
    $output = curl_exec($ch);

    // Print error if any
    if (curl_errno($ch)) {
        $error_flag = 1;
        'cURL Error: ' . curl_error($ch);
    } else {
        // Print API response
        'API Response: ' . $output;
    }

    curl_close($ch);
    return $error_flag;
}
?>