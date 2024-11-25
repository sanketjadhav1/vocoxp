<?php
/* 
Name : json_for_verification_process.php
Version of the Requirment Document  : 2.0.1


Purpose :- This Api Is Performing Send OTP. for Verification
Mode :- multi mode

Developed By - Rishabh Shinde
*/
error_reporting(1);
include_once 'connection.php';

// Now you can use the connection class
$connection = connection::getInstance();
$mysqli = $connection->getConnection();

$connection1 = database::getInstance();
$mysqli1 = $connection1->getConnection();
apponoff($mysqli);
logout($_SERVER['HTTP_AUTH_KEY'], $mysqli);
$check_error=common_check_error($mysqli, $_POST['agency_id'], $_POST['member_id'], $_POST['request_id'], $_POST['application_id'], $_POST['mode'], $_POST['specification_id']);

if($check_error==1){

    $agency_id=$_POST['agency_id'];
     $request_id=$_POST['request_id'];     
    $member_id=$_POST['member_id'];
    $application_id=$_POST['application_id'];
    $specification_id=$_POST['specification_id'];
    $type_id=$_POST['type_id'];
    $mode=$_POST['mode'];
    
    $fetch_agency_id = "SELECT `agency_id`, `status` FROM `agency_header_all` WHERE `agency_id`='$agency_id'";
$result_agency = mysqli_query($mysqli, $fetch_agency_id);
$arr = mysqli_fetch_assoc($result_agency);

if (!$arr) {
    $response[] = array("error_code" => 102, "message" => "Invalid agency ID. Please provide a valid agency ID");
    echo json_encode($response);
    return;
} elseif ($arr['status'] != "1") {
    $response[] = array("error_code" => 103, "message" => "The agency name is currently inactive");
    echo json_encode($response);
    return;
}

$fetch_member = "SELECT `name`, `contact_no`, `member_id` FROM `member_header_all` WHERE `member_id`='$member_id'";
$result_member = mysqli_query($mysqli, $fetch_member);
$arr_member = mysqli_fetch_assoc($result_member);

if (!$arr_member) {
    $response[] = array("error_code" => 103, "message" => "Invalid member ID. Please provide a valid member ID");
    echo json_encode($response);
    return;
} 

$fetch_request = "SELECT `otp_of_confirmation`, `application_id`, `request_id` FROM `verification_payment_transaction_all` WHERE `request_id`='$request_id' AND `application_id`='$application_id'";
$result_request = mysqli_query($mysqli1, $fetch_request);
$arr_resquest = mysqli_fetch_assoc($result_request);


if($mode=="send_OTP"){
if (!$arr_resquest) {
    $response[] = array("error_code" => 106, "message" => "Invalid request ID. Please provide a valid request ID");
    echo json_encode($response);
    return;
} 

$otp=rand(1000,9999);
$send_otp=sms_helper_accept($arr_member['contact_no'], $otp);



$response[]=["otp" => $otp];
$data[]=["error_code" => 100, "message" => "Otp send successfully", "data" => $response];
echo json_encode($data);
return;


}elseif($mode=="send_verification_permission"){
    $update_payment="UPDATE `verification_payment_transaction_all` SET `otp_of_confirmation`='1' WHERE `request_id`='$request_id' AND `application_id`='$application_id'";
$res_update=mysqli_query($mysqli1, $update_payment);

$fetch_request = "SELECT `otp_of_confirmation`, `application_id`, `request_id` FROM `verification_payment_transaction_all` WHERE `request_id`='$request_id' AND `application_id`='$application_id'";
$result_request = mysqli_query($mysqli1, $fetch_request);
$arr_resquest1 = mysqli_fetch_assoc($result_request);

    if (!$arr_resquest) {
        $response[] = array("error_code" => 107, "message" => "Invalid request ID. Please provide a valid request ID");
        echo json_encode($response);
        return;
    }
    $response[]=["request_id" => $request_id, "is_permitted" => $arr_resquest1['otp_of_confirmation']==1?"Yes":"No"];
    $data[]=["error_code" => 100, "message" => "Data fetch successfully", "data" => $response];
    echo json_encode($data);
    return;

}elseif ($mode == "get_data_for_email") {
    $fetch_specification = "SELECT `verification_id`,
    `table_name`,
    `name`,
    `image`,
    `status` FROM `verification_header_all` WHERE `status`=1";
    $result_specification_id = mysqli_query($mysqli1, $fetch_specification);
    
    $response = []; // Initialize the response array outside the loop
    
    while ($arr_speci = mysqli_fetch_assoc($result_specification_id)) {
        $specification_id = $arr_speci['verification_id'];
        $table_name = $arr_speci['table_name'];
    
        $fetch_table = "SELECT `verification_status`,
        `verification_report`,
        `modified_on`,
        `request_id`,
        `type_id`,
        `request_id`,
        `modified_on`
FROM $table_name 
WHERE `agency_id`='$agency_id' 
    AND `person_id`='$member_id' 
    AND `application_id`='$application_id' 
    AND `verification_status`=2
ORDER BY `modified_on` DESC
LIMIT 1;
";
        $result_table = mysqli_query($mysqli1, $fetch_table);
    
        $report_arr = []; // Initialize the report_arr array inside the outer loop
    
        if (mysqli_num_rows($result_table) > 0) {
            while ($arr_table = mysqli_fetch_assoc($result_table)) {
                $verification_status = replaceNullWithEmptyString($arr_table['verification_status']);
    
                $report_entry = [];
                
                if ($verification_status == 1 || $verification_status == 2) {
                    $report_entry[] = [
                        "report_url" => replaceNullWithEmptyString($arr_table['verification_report']),
                        "last_verified" => replaceNullWithEmptyString(date("d-m-Y h:i A",strtotime($arr_table['modified_on']))),
                        "request_id" => replaceNullWithEmptyString($arr_table['request_id'])
                    ];
                }
    
                $response[] = [
                    "specification_id" => replaceNullWithEmptyString($specification_id),
                    "name" => replaceNullWithEmptyString($arr_speci['name']),
                    "table_name" => replaceNullWithEmptyString($table_name),
                    "image" => replaceNullWithEmptyString($arr_speci['image']),
                    "type_id" => replaceNullWithEmptyString($arr_table['type_id']),
                    "verification_status" => $verification_status,
                    "report_arr" => $report_entry, // Include the populated or empty array
                    "is_permitted" => replaceNullWithEmptyString($arr_table['is_permitted']),
                    "request_id" => replaceNullWithEmptyString($arr_table['request_id']),
                    "last_verified" => replaceNullWithEmptyString($arr_table['modified_on']),
                    "report_generated" => '',
                    "status" => replaceNullWithEmptyString($arr_speci['status']),
                    "type" => '' // Include the status field in the output
                ];
            }
        } 
    }
    
    $data[] = [
        "error_code" => 100,
        "message" => "Data fetch successfully",
        "data" => $response
    ];
    
    echo json_encode($data);
    return;

}



}






function common_check_error($mysqli, $agency_id,$member_id, $request_id, $application_id, $mode, $specification_id){

    $check_error=1;
    if(!$mysqli){
        $response[]=["error_code" => 101, "message" => "An unexpected server error occurred while
        processing your request. Please try after sometime"];
        echo json_encode($response);
        return;
    }
    if($_SERVER["REQUEST_METHOD"] != "POST"){
        $responce[] = array( "error_code" => 110, "message" => "please change request method to POST");
        echo json_encode($responce);
        return; 
    
            
    }
    if(!isset($agency_id)){
        $response[]=["error_code" => 104, "message" => "Please the parameter - agency_id"];
        echo json_encode($response);
        return;
    }else{
        if($agency_id==""){
            $response[]=["error_code" => 105, "message" => "Value of 'agency_id' cannot be empty"];
            echo json_encode($response);
            return;
        }
    }
    if(!isset($member_id)){
        $response[]=["error_code" => 107, "message" => "Please the parameter - member_id"];
        echo json_encode($response);
        return;
    }else{
        if($member_id==""){
            $response[]=["error_code" => 108, "message" => "Value of 'member_id' cannot be empty"];
            echo json_encode($response);
            return;
        }
    }
    if($mode=="send_OTP" && $mode=="send_verification_permission"){
    if(!isset($request_id)){
        $response[]=["error_code" => 109, "message" => "Please the parameter - request_id"];
        echo json_encode($response);
        return;
    }else{
        if($request_id==""){
            $response[]=["error_code" => 110, "message" => "Value of 'request_id' cannot be empty"];
            echo json_encode($response);
            return;
        }
    }
}
    if(!isset($application_id)){
        $response[]=["error_code" => 113, "message" => "Please the parameter - application_id"];
        echo json_encode($response);
        return;
    }else{
        if($application_id==""){
            $response[]=["error_code" => 114, "message" => "Value of 'application_id' cannot be empty"];
            echo json_encode($response);
            return;
        }
    }
    if($_POST['mode']=="get_data_for_email"){
    // if(!isset($specification_id)){
    //     $response[]=["error_code" => 118, "message" => "Please the parameter - specification_id"];
    //     echo json_encode($response);
    //     return;
    // }else{
    //     if($specification_id==""){
    //         $response[]=["error_code" => 119, "message" => "Value of 'specification_id' cannot be empty"];
    //         echo json_encode($response);
    //         return;
    //     }
    // }
}
    if(!isset($mode)){
        $response[]=["error_code" => 115, "message" => "Please the parameter - mode"];
        echo json_encode($response);
        return;
    }else{
        if($mode==""){
            $response[]=["error_code" => 116, "message" => "Value of 'mode' cannot be empty"];
            echo json_encode($response);
            return;
        }elseif($mode!="send_OTP" && $mode!="send_verification_permission" && $mode!="get_data_for_email"){
            $response[]=["error_code" => 117, "message" => "Please enter value of mode. Please select 'send_OTP', 'send_verification_permission', 'get_data_for_email'"];
            echo json_encode($response);
            return;
        }
    }
    return $check_error;
}
function replaceNullWithEmptyString($value) {
    return is_null($value) ? '' : $value;
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

    $message = urlencode("OTP for Document Verification is $otp. Developed by Micro Integrated");

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
    $url = "http://api.msg91.com/api/sendhttp.php?authkey=362180AunaHgulfCm6698af66P1&sender=PMSafe&mobiles=$contact&route=$route&message=$message&DLT_TE_ID=1707172128178664516";

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