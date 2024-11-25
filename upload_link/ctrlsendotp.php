<?php
include_once '../connection.php';

// Establish database connections
$connection = connection::getInstance();
$mysqli = $connection->getConnection();

$connection1 = database::getInstance();
$mysqli1 = $connection1->getConnection();

// Fetch POST data
$mobile_no = $_POST['mobile_no'] ?? '';
$action = $_POST['action'] ?? '';

// Assuming $agency_id is defined somewhere else in your code
$agency_id = ''; // Initialize this variable properly

// Fetch agency details
 $fetch_agency = "SELECT `company_name`, `address`, `mobile_no` FROM `agency_header_all` WHERE `agency_id` = '$agency_id'";
$res_agency=mysqli_query($mysqli, $fetch_agency);
$arr_agency=mysqli_fetch_assoc($res_agency);

    $otp = rand(100000, 999999);
    sms_helper_accept($mobile_no, $otp);
    echo $otp;


// Function to send SMS via API
function sms_helper_accept($contact, $otp) {
    date_default_timezone_set("Asia/Kolkata");
    
    $message = urlencode("OTP for Document Verification is $otp. Developed by Micro Integrated");
    
    $route = "4";
    $mobile = "91" . $contact;
    
    $postData = [
        "authkey" => "362180AunaHgulfCm6698af66P1",
        "mobiles" => $mobile,
        "message" => $message,
        "sender" => "VOCOxP",
        "route" => $route,
        "response" => "json",
        "DLT_TE_ID" => "1707172128178664516"
    ];

    $url = "http://api.msg91.com/api/sendhttp.php";

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $postData,
        CURLOPT_SSL_VERIFYHOST => 0,
        CURLOPT_SSL_VERIFYPEER => 0
    ]);

    $output = curl_exec($ch);

    if (curl_errno($ch)) {
        echo 'cURL Error: ' . curl_error($ch);
    } 

    curl_close($ch);
}



?>
