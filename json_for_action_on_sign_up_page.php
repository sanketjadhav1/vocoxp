<?php
/* 
Name : json_for_action_on_sign_up_page.php
Version of the Requirment Document  : 2.0.3


Purpose :- This api is to register individual / organization agency along with all the
basic / personal / organizational information.

Mode :- single mode

Developed By - Rishabh Shinde
*/
use PHPMailer\PHPMailer\PHPMailer;
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

include __DIR__ . '/vendor/autoload.php';

include_once 'connection.php';

// error_reporting(E_ALL & ~E_DEPRECATED);
// ini_set('display_errors', 1);
// Now you can use the connection class
$connection = connection::getInstance();
$mysqli = $connection->getConnection();
apponoff($mysqli);
// pause_signup($mysqli);
$check_error = check_error($mysqli, $_POST["name"], $_POST["email_id"], $_POST["mobile_no"], $_POST["address"], $_POST["type"]); // pass all the required fields here..

// if check error return value 1 then good to go
if ($check_error == 1) {


    $currdatetime = date('Y-m-d H:i:s');

    $name = $_POST["name"];
    $email_id = $_POST["email_id"];
    $mobile_no = $_POST["mobile_no"];
    $address = $_POST["address"];
    $type = $_POST["type"];
    if ($type == "individual") {
        if($_POST["are_you_a"]=="working professional"){
            $are_you_a_ind =1 ;
        }else{
            $are_you_a_ind =0 ;
        }
        
        $type_=0;
        if($are_you_a_ind=="working professional"){
         $own_com=1;
        }else{
         $own_com=0;
        }
    } elseif ($type == "organization") {
        $type_=1;
if($_POST["are_you_a"]=="owner"){
    $are_you_a_org = 1;
}else{
    $are_you_a_org =0 ;
}
        
        
    }
    $profession = $_POST["profession"];
    $city = $_POST["city"];

    $owning_company_or_consultant = $_POST["owning_company_or_consultant"];
    $org_name = $_POST["org_name"];
    $functional_area = $_POST["functional_area"];
    $org_city = $_POST["org_city"];
    $no_of_employee = $_POST["no_of_employee"];

    $designation = $_POST["designation"];

    $current_version = $_POST['current_version'];
    $operating_system = $_POST['operating_system'];
    $os_version = $_POST['os_version'];
    $login_pin = $_POST['login_pin'];
    $agency_id = $_POST['agency_id'];
    $device_id = $_POST['device_id'];
    $token = generateRandomString(10);
    $system_date_time = date("Y-m-d H:i:s"); 
    $device_token = $token . "," . $device_id;
   
    $registration_id= unique_id_genrate('AGN', 'agency_header_all', $mysqli);
   

    $fetch_factory="SELECT `pause_switch`, `pause_message` FROM `factory_setting_header_all` WHERE `pause_switch`='1'";
    $res_factory=mysqli_query($mysqli, $fetch_factory);
    $arr_factory=mysqli_fetch_assoc($res_factory);

    if(!empty($arr_factory)){

        $sql_check_same_user = "SELECT `email_id`,`mobile_no` FROM `pending_enquiry_header_all` WHERE `email_id`='$email_id' OR `mobile_no`='$mobile_no' ";

        $result_check_same_user = mysqli_query($mysqli, $sql_check_same_user);
    
        if ($mysqli->affected_rows > 0) {
            $row_check_same_user = mysqli_fetch_assoc($result_check_same_user);
            $d_email = $row_check_same_user['email_id'];
            $d_mobile_no = $row_check_same_user['mobile_no'];
    
            if ($mobile_no == $d_mobile_no)
                $responce[] = ["error_code" => 121, "message" => "Your enquiry is already pending due to under maintenance."];
            else if ($d_email == $email_id)
                $responce[] = ["error_code" => 122, "message" => "Your enquiry is already pending due to under maintenance."];
    
            echo json_encode($responce);
            exit;
        }




         $insert_Penn_user = "INSERT INTO `pending_enquiry_header_all`
            (`agency_id`, `name`, `mobile_no`, `email_id`, `company_name`, `business_type`, `city`, `address`, 
            `profession`, `no_of_employees`, `status`, `created_on`, `type`, `employee_type`, 
            `employee_designation`, `work_type`, `owning_company`) 
            VALUES 
            ('$registration_id', '$name', '$mobile_no', '$email_id', '$org_name', '$functional_area', '$city', '$address', 
            '$profession', '$no_of_employee', '1', '$currdatetime', '$type_', '$are_you_a_org', '$designation',
            '$are_you_a_ind', '$own_com')";
    $result = mysqli_query($mysqli, $insert_Penn_user);

$response[] = ["error_code" => 104, "message" => $arr_factory['pause_message']];
echo json_encode($response);
exit;


    }else{

        $sql_check_app = "SELECT `reg_mobile_no` FROM `app_user_token_details_all` WHERE  `reg_mobile_no`='$mobile_no' ";
        $sql_check_same_user = "SELECT `email_id`,`mobile_no` FROM `agency_header_all` WHERE `email_id`='$email_id' OR `mobile_no`='$mobile_no' ";
        $result_check_same_user = mysqli_query($mysqli, $sql_check_same_user);
        $result_check_app = mysqli_query($mysqli, $sql_check_app);
        if (mysqli_num_rows($result_check_app)>0 || $mysqli->affected_rows > 0) {
            // $row_check_same_user = mysqli_fetch_assoc($result_check_same_user);
            // // $d_email = $row_check_same_user['email_id'];
            // $d_mobile_no = $row_check_same_user['reg_mobile_no'];
    
            
                $responce[] = ["error_code" => 121, "message" => "mobile no already exist"];
    
            echo json_encode($responce);
            return;
        }

    $insert_app_user = "INSERT INTO app_user_token_details_all (`reg_mobile_no`, `current_version`, `linked_agency_id`, `app_device_token`, `operating_system`, `os_version`, `login_pin`, `last_logged_in_on`, `registered_on`, `status`, `deactivated_on`) VALUES('$mobile_no', '$current_version', '$registration_id', '$device_token', '$operating_system', '$os_version', '$login_pin', '', '$system_date_time', '1', '')";
    $res_app_user = mysqli_query($mysqli, $insert_app_user);


    

    

    // if ($mysqli->affected_rows > 0) {
    //     $row_check_same_user = mysqli_fetch_assoc($result_check_same_user);
    //     $d_email = $row_check_same_user['email_id'];
    //     $d_mobile_no = $row_check_same_user['mobile_no'];

    //     if ($mobile_no == $d_mobile_no)
    //         $responce[] = ["error_code" => 121, "message" => "mobile no already exist"];
    //     else if ($d_email == $email_id)
    //         $responce[] = ["error_code" => 122, "message" => "email id already exist"];

    //     echo json_encode($responce);
    //     return;
    // }

    $insert_new_user = "INSERT INTO agency_header_all
            (`agency_id`, `name`, `mobile_no`, `email_id`, `company_name`, `business_type`, `city`, `address`, 
            `profession`, `no_of_employees`, `status`, `created_on`, `type`, `employee_type`, 
            `employee_designation`, `work_type`, `owning_company`) 
            VALUES 
            ('$registration_id', '$name', '$mobile_no', '$email_id', '$org_name', '$functional_area', '$city', '$address', 
            '$profession', '$no_of_employee', '1', '$currdatetime', '$type_', '$are_you_a_org', '$designation',
            '$are_you_a_ind', '$own_com')";
    
    $result = mysqli_query($mysqli, $insert_new_user);

if($result){
    $addwallet="UPDATE wallet_recharge_transaction_all SET added_amount='2000',final_blnce='2000',transaction_date='$currdatetime',recharge_from='3',coupon_amnt='2000' WHERE agency_id='$registration_id'";
    $res_update = mysqli_query($mysqli, $addwallet); 
    $mail = new PHPMailer(true);


    // Server settings
     $mail->isSMTP();
        $mail->Host = 'mail.mounarchtech.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'transactions@mounarchtech.com';
        $mail->Password = 'Mtech!@12345678';  // Ideally, load this from environment variables
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;
        $mail->setFrom('transactions@mounarchtech.com', 'Micro Integrated Semi Conductor Systems Pvt. Ltd.');
        $mail->isHTML(true);

    // Recipients
      
    $mail->addAddress($email_id, $name);  // Add a recipient

    // Content
  
    $mail->Subject = 'Welcome to VOCOxP - Registration Successful!';

    // HTML email content
    $html = '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to VOCOxP</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            padding: 20px;
            border: 1px solid #dddddd;
        }
        .email-header {
            text-align: center;
            margin-bottom: 20px;
        }
        .email-header h1 {
            margin: 0;
            color: #333333;
        }
        .email-body {
            font-size: 16px;
            color: #555555;
            line-height: 1.5;
        }
        .email-body p {
            margin: 10px 0;
        }
        .email-footer {
            margin-top: 20px;
            text-align: center;
            font-size: 14px;
            color: #777777;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h1>Welcome to VOCOxP</h1>
        </div>
        <div class="email-body">
            <p>Dear '.$name.',</p>
            <p>Welcome to VOCOxP!</p>
            <p>Congratulations, you have successfully registered with VOCOxP. You can now log in to your account using your registered contact number.</p>
            <p>At VocoXp, we are committed to providing you with secure and efficient verification services. If you have any questions or require assistance, please don\'t hesitate to reach out to our support team at <a href="mailto:support@microintegrated.in">support@microintegrated.in</a>.</p>
            <p>Thank you for choosing VocoXp! We look forward to assisting you further.</p>
        </div>
        <div class="email-footer">
            <p>Best regards,</p>
            <p>Micro Integrated Semi Conductor Systems Pvt. Ltd.</p>
        </div>
    </div>
</body>
</html>';

    
    $mail->Body = $html;

    $mail->send();
} 
}
    $insert_setting="INSERT INTO `agency_setting_all` (`id`, `agency_id`, `add_family_member`, `app_setting`, `is_univarsal`, `geo_fancing_margin`, `geo_location_auto_update`, `manual_recording`, `e_crime_reminder`, `sos`, `created_on`, `modified_on`, `watch_reset_pin`, `mark_watch_stolen`, `fsn_mark_watch_stolen`, `watch_stolen_audio_file`, `watch_stolen_msg`, `holidays`, `alert_when_watch_remove`, `alert_on_watch_remove_from_wrist`, `alrert_on_sim_remove`, `fsn_for_sim_remove`, `alert_on_watch_to_reconnect_server`, `pre_schedule_alert`, `alert_when_watch_out_from_gps_range`, `manual_recording_notification`, `heart_rate`, `spo2`, `body_temp`, `bluetooth`, `carrier`) VALUES (NULL, '$registration_id', '0', '0', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '');";
    $res_setting=mysqli_query($mysqli,$insert_setting);
    if ($result == true) {

        $send_message = sms_helper_accept($mobile_no);
        if ($type == "individual") {
            $response[] = ["error_code" => 100, "message" => "Sign up Successfully.", "name" => $name];
        } else {
            $response[] = ["error_code" => 100, "message" => "Sign up Successfully.", "name" => $org_name];
        }
        echo json_encode($response);
        return;
    }else{
        $response[] = ["error_code" => 100, "message" => "failed."];
    }
    echo json_encode($response);
        return;
    }




//function for check error
function check_error($mysqli, $name, $email_id, $mobile_no, $address, $type)
{
    $check_error = 1;
    if (!$mysqli) {
        $responce[] = array("error_code" => 01, "message" => "There was an issue connecting to the database. Please try again later.");
        echo json_encode($responce);
        return;
    }
    if ($_SERVER["REQUEST_METHOD"] != "POST") {
        $responce[] = array("error_code" => 115, "message" => "please change request method to POST");
        echo json_encode($responce);
        return;
    }
    if (!isset($name)) {  //check if parameter is missing or not given

        $responce[] = ["error_code" => 116, "message" => "Please pass parameter - name "];
        echo json_encode($responce);
        return;
    } else {
        if ($name == "") { //check if paramter is empty
            $responce[] = ["error_code" => 117, "message" => "Paramter 'name' cannot be empty."];
            echo json_encode($responce);
            return;
        }
    }


    if (!isset($email_id)) { //check if parameter is missing or not given
        $responce[] = ["error_code" => 123, "message" => "Please pass parameter - email_id"];
        echo json_encode($responce);
        return;
    } else {
        if ($email_id == "") { //check if paramter is empty
            $responce[] = ["error_code" => 124, "message" => "Paramter 'email_id' cannot be empty."];
            echo json_encode($responce);
            return;
        }
    }


    if (!isset($mobile_no)) { //check if parameter is missing or not given
        $responce[] = ["error_code" => 125, "message" => "Please pass parameter - mobile_no "];
        echo json_encode($responce);
        return;
    } else {
        if ($mobile_no == "") { //check if paramter is empty
            $responce[] = ["error_code" => 126, "message" => "Paramter 'mobile_no' cannot be empty. "];
            echo json_encode($responce);
            return;
        }
    }


    if (!isset($address)) { //check if parameter is missing or not given
        $responce[] = ["error_code" => 127, "message" => "Please pass parameter - address "];
        echo json_encode($responce);
        return;
    } else {
        if ($address == "") { //check if paramter is empty
            $responce[] = ["error_code" => 108, "message" => "Paramter 'address' cannot be empty. "];
            echo json_encode($responce);
            return;
        }
    }


    if (!isset($type)) { //check if parameter is missing or not given
        $responce[] = ["error_code" => 128, "message" => "Please pass parameter - type "];
        echo json_encode($responce);
        return;
    } else {
        if ($type == "") { //check if paramter is empty
            $responce[] = ["error_code" => 129, "message" => "Paramter 'type' cannot be empty."];
            echo json_encode($responce);
            return;
        }
    }

    if ($type != 'individual' && $type != 'organization') {
        $responce[] = ["error_code" => 106, "message" => "Please enter valid type"];
        echo json_encode($responce);
        return;
    }

    if ($type == 'individual') {

        $profession = $_POST['profession'];  //optional
        $city = $_POST['city'];  //optional
        if (!isset($_POST['are_you_a'])) {
            $responce[] = ["error_code" => 109, "message" => "Please select one value. are_you_a "];
            echo json_encode($responce);
            return;
        } else {
            if ($_POST['are_you_a'] == "") { //check if paramter is empty
                $responce[] = ["error_code" => 130, "message" => "Paramter 'are_you_a' cannot be empty. "];
                echo json_encode($responce);
                return;
            } else {
                if ($_POST['are_you_a'] == 'working_professional') {
                    if (!isset($_POST['owning_company_or_consultant'])) {
                        $responce[] = ["error_code" => 110, "message" => "Please select one value. "];
                        echo json_encode($responce);
                        return;
                    } else {
                        if ($_POST['owning_company_or_consultant'] == '') {
                            $responce[] = ["error_code" => 136, "message" => "Paramter 'owning_company_or_consultant' cannot be empty. "];
                            echo json_encode($responce);
                            return;
                        } else {
                            if ($_POST['owning_company_or_consultant'] != 'owning_company' && $_POST['owning_company_or_consultant'] != 'consultant') {

                                $responce[] = ["error_code" => 135, "message" => "Paramter 'value of owning_company_or_consultant must be either owning_company or consultant"];
                                echo json_encode($responce);
                                return;
                            }
                        }
                    }
                }
            }
        }
    } else { //type=organization

        $functional_area = $_POST['functional_area'];     //optional
        $city = $_POST['city'];                           //optional
        $no_of_employees = $_POST['no_of_employees'];     //optional

        if (!isset($_POST['org_name'])) { //check if parameter is missing or not given
            $responce[] = ["error_code" => 131, "message" => "Please pass parameter - org_name"];
            echo json_encode($responce);
            return;
        } else {
            if ($_POST['org_name'] == "") { //check if paramter is empty
                $responce[] = ["error_code" => 107, "message" => "Please enter organization name"];
                echo json_encode($responce);
                return;
            } else {
                $org_name = $_POST['org_name'];
            }
        }




        $designation = "";
        if (!isset($_POST['are_you_a'])) {
            $responce[] = ["error_code" => 132, "message" => "Paramter 'are_you_a' is missing. "];
            echo json_encode($responce);
            return;
        } else {
            if ($_POST['are_you_a'] == "") { //check if paramter is empty
                $responce[] = ["error_code" => 112, "message" => "Please select one value "];
                echo json_encode($responce);
                return;
            } else {

                if ($_POST['are_you_a'] == 'owner' || $_POST['are_you_a'] == 'working_as') {
                    $are_you_a = $_POST['are_you_a'];
                } else {
                    $responce[] = ["error_code" => 133, "message" => "Paramter 'value of are_you_a must be either owner or working_as."];
                    echo json_encode($responce);
                    return;
                }


                if ($_POST['are_you_a'] == 'working_as') {
                    if (!isset($_POST['designation'])) {
                        $responce[] = ["error_code" => 134, "message" => "Please pass parameter - designation "];
                        echo json_encode($responce);
                        return;
                    } else {
                        if ($_POST['designation'] == '') {
                            $responce[] = ["error" => 111, "message" => "Please enter designation."];
                            echo json_encode($responce);
                            return;
                        } else {
                            $designation = $_POST['designation'];
                        }
                    }
                }
            }
        }
    }

    return $check_error;
}

function sms_helper_accept($mobile_no)
{
    date_default_timezone_set("Asia/Kolkata");
    $date = date("d-m-Y");
    $error_flag = 0;
    $otp_prefix = ":-";
    $new_line = "\n";
    $dot = ".";
    $colon = ":";

    $message = urlencode("Welcome to VOCOxP! Congratulations, you have been successfully registered. To log in to your account, please use your registered contact number. Developed by Micro Integrated");

    $response_type = "json";

    // Define route
    $route = "4";
    $mobile_no = "91" . $mobile_no;
    // Prepare your post parameters
    $postData = [
        "authkey" => "362180AunaHgulfCm6698af66P1",
        "mobiles" => $mobile_no,
        "message" => $message,
        "sender" => "VOCOxP",
        "route" => $route,
        "response" => $response_type,
    ];

    $url = "http://api.msg91.com/api/sendhttp.php?authkey=362180AunaHgulfCm6698af66P1&sender=PMSafe&mobiles=$mobile_no&route=$route&message=$message&DLT_TE_ID=1707172128158104485";

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
function generateRandomString($length) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    $max = strlen($characters) - 1;
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $max)];
    }
    return $randomString;
}
