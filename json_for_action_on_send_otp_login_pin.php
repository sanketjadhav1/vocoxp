<?php
/* Prepared by: Sahil Chavan
Name of API: json_for_action_on_send_otp_login_pin
Method: POST
Category: Action
Description:
This API is to use send otp, setup pin, login using pin
Developed by: Gitanjali Jamdade
mode: Multi mode  */
error_reporting(1);


// this json is used to action on send otp login up.
header('Content-Type: application/json; charset=UTF-8');
header('Access-Contorl-Allow-Origin: *');
date_default_timezone_set('Asia/kolkata');


date_default_timezone_set('Asia/kolkata');
$system_date = date("y-m-d");
$system_date_time = date("Y-m-d H:i:s");
$output = $response = array();

include_once 'connection.php';

// Now you can use the connection class
$connection = connection::getInstance();
$conn = $connection->getConnection();
apponoff($conn);
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    $response[] = ["error_code" => 101, "message" => "Please use POST method"];
    echo json_encode($response);
    return;
}




$common_chk_error_res = common_chk_error($conn, $_POST['mode'], $_POST['mobile_no'], $_POST['call'], $_POST['login_pin']);
if ($common_chk_error_res == 1) {
    $mobile_no = $_POST["mobile_no"];
    $login_pin = $_POST["login_pin"];
    $mode = $_POST["mode"];
    $call = $_POST["call"];
}





function common_chk_error($conn, $mode, $mobile_no, $call, $login_pin)
{
    $common_chk_error_res = 1;

    if (!$conn) {
        $response[] = ["error_code" => 102, "message" => "There was an issue connecting to the database. Please try again later."];
        echo json_encode($response);
        return;
    }

    if (!isset($mobile_no)) { //check if parameter is missing or not given

        $response[] = ["error_code" => 103, "message" => "Please pass parameter - mobile_no "];
        echo json_encode($response);
        return;
    }

    if ($mobile_no == "") { //check if paramter is empty
        $response[] = ["error_code" => 104, "message" => "Paramter 'mobile_no' cannot be empty. "];
        echo json_encode($response);
        return;
    }





    if ((!isset($mode))) { // to check the value of mode is null
        $response[] = ["error_code" => 106, "message" => "Please add mode "];
        echo json_encode($response);
        return;
    } else {
        if ($mode == "") { // To check mode is empty
            $response[] = ["error_code" => 107, "message" => "Paramter 'mode' cannot be empty. "];
            echo json_encode($response);
            return;
        }
        if ($mode != 'verify_contact' && $mode != 'setup_pin' && $mode !=  'login_pin') { //to check value of mode is valid
            $response[] = array("error_code" => 108, "message" => "Parameter 'mode' value is invalid. Please enter value either verify_contact, setup_pin, login_pin.");
            echo json_encode($response);
            return;
        }


        if ((!isset($call))) { // to check the value of call is null
            if ($mode == 'verify_contact') {
                $response[] = ["error_code" => 106, "message" => "Please add call "];
                echo json_encode($response);
                return;
            }
        } else {
            if ($call == "") { // To check call is empty
                $response[] = ["error_code" => 107, "message" => "Paramter 'call' cannot be empty. "];
                echo json_encode($response);
                return;
            }
            if ($call != 'sign_up' && $call != 'login' && $call !=  'forgot_pin') { //to check value of call is valid
                $response[] = array("error_code" => 108, "message" => "Parameter 'call' value is invalid. Please enter value either sign_up, login, forgot_pin.");
                echo json_encode($response);
                return;
            }
        }



        if ($mode == "verify_contact") {
            $mobile_no = $_POST['mobile_no'];

            if ($call == 'sign_up') {

                if (validateMOB($mobile_no)) { // if mobile_no is not verified
                    $fetch_username = "SELECT `login_pin`, `name` FROM agency_header_all WHERE mobile_no = '$mobile_no'";
$result = mysqli_query($conn, $fetch_username);
$arr = mysqli_fetch_assoc($result);

// Fetch registration details from app_user_token_details_all based on mobile_no
$fetch_username_app = "SELECT `reg_mobile_no` FROM `app_user_token_details_all` WHERE `reg_mobile_no`='$mobile_no'";
$result_app = mysqli_query($conn, $fetch_username_app);

// Initialize response array
$response = [];

if (mysqli_num_rows($result) > 0 || mysqli_num_rows($result_app) > 0) {
    // If mobile_no is already registered
    
        $login_pin = $arr['login_pin'];
        $username = $arr['name'];
        $otp = "";  // Set OTP to empty string

        $response[] = [
            "error_code" => 109,
            "message" => "Mobile No is already exist",
            "username" => $username,
            "login_pin" => $login_pin,
            "otp" => '1234'
        ];
    
    echo json_encode($response);
    return;
} else {
    // If mobile_no is not registered
    $username = "";
    $login_pin = "";
    $otp = rand(1000, 9999);  // Generate random OTP

    $send_message = sms_helper_accept($mobile_no, $otp);  // Uncomment if SMS helper is available

    $response[] = [
        "error_code" => 100,
        "message" => "OTP has been sent successfully",
        "otp" => $otp,  // Replace with $otp for actual OTP
        "username" => $username,
        "login_pin" => $login_pin
    ];

    echo json_encode($response);
    return;
}
                } else {
                    $response[] = ["error_code" => 105, "message" => "Please enter the valid Mobile No"];
                    echo json_encode($response);
                    return;
                }
            } else if ($call == 'login') {

                if (validateMOB($mobile_no)) { // if mobile_no is not verified
                    $fetch_username = "SELECT `login_pin`, `name` FROM agency_header_all WHERE mobile_no = '$mobile_no'";
                    $result = mysqli_query($conn, $fetch_username);
                    $arr = mysqli_fetch_assoc($result);
                    if (mysqli_num_rows($result) > 0) { // if mobile_no is already registered
                        if ($arr > 0) {
                            $login_pin = $arr['login_pin'];
                            if ($login_pin == 0) {
                                $login_pin = "";
                            }
                            $username = $arr['name'];
                            $otp = rand(1000, 9999);
                            $send_message = sms_helper_accept($mobile_no, $otp);
                            $response[] = ["error_code" => 100, "message" => "OTP sent successfully.", "username" => $username, "login_pin" => $login_pin, "otp" => $otp];
                        }
                        echo json_encode($response);
                        return;
                    } else {
                        // if mobile_no is not registered
                        $username = "";
                        $login_pin = "";
                        $otp = "";
                        // $send_message = sms_helper_accept($mobile_no, $otp);
                        $response[] = ["error_code" => 109, "message" => "User does not exist. please signup", "otp" => $otp, "username" => $username, "login_pin" => $login_pin];

                        echo json_encode($response);
                        return;
                    }
                } else {
                    $response[] = ["error_code" => 105, "message" => "Please enter the valid Mobile No"];
                    echo json_encode($response);
                    return;
                }
            } else if ($call == 'forgot_pin') {


                if (validateMOB($mobile_no)) { // if mobile_no is not verified
                    $query = "SELECT `app_device_token` FROM `app_user_token_details_all` WHERE SUBSTRING_INDEX(app_device_token, ',', 1) = '" . $_SERVER['HTTP_AUTH_KEY'] . "';";
                    $res = mysqli_query($conn, $query);
                    $arr = mysqli_fetch_assoc($res);

                    if (!$arr) { // Check if $arr is NULL (no rows found)
                        $response[] = ["error_code" => 440, "message" => "Invalid Token"];
                        echo json_encode($response);
                        exit;
                    }
                    $fetch_username = "SELECT `login_pin`, `name` FROM agency_header_all WHERE mobile_no = '$mobile_no'";
                    $result = mysqli_query($conn, $fetch_username);
                    $arr = mysqli_fetch_assoc($result);
                    if (mysqli_num_rows($result) > 0) { // if mobile_no is already registered
                        if ($arr > 0) {
                            $login_pin = $arr['login_pin'];
                            if ($login_pin == "") {
                                $login_pin = "";
                            }
                            $username = $arr['name'];
                            $otp = rand(1000, 9999);
                            $send_message = sms_helper_accept($mobile_no, $otp);
                            $response[] = ["error_code" => 100, "message" => "OTP sent successfully.", "username" => $username, "login_pin" => $login_pin, "otp" => $otp];
                        }
                        echo json_encode($response);
                        return;
                    } else {
                        // if mobile_no is not registered
                        $username = "";
                        $login_pin = "";
                        $otp = "";
                        $send_message = sms_helper_accept($mobile_no, $otp);
                        $response[] = ["error_code" => 109, "message" => "User does not exist. please signup", "otp" => $otp, "username" => $username, "login_pin" => $login_pin];

                        echo json_encode($response);
                        return;
                    }
                } else {
                    $response[] = ["error_code" => 105, "message" => "Please enter the valid Mobile No"];
                    echo json_encode($response);
                    return;
                }
            }
        } elseif ($mode == "setup_pin") {
            $mobile_no = $_POST['mobile_no'];
            $new_login_pin = $_POST['login_pin'];
            if (!isset($_POST['login_pin'])) { //check if pin is missing or not given
                $response[] = ["error_code" => 110, "message" => "Please pass parameter - login_pin "];
                echo json_encode($response);
                return;
            } else {
                if (($_POST['login_pin']) == "") { //check if pin is empty
                    $response[] = ["error_code" => 111, "message" => "Paramter 'login_pin' cannot be empty. "];
                    echo json_encode($response);
                    return;
                } else {
                    if (validatePIN($_POST['login_pin'])) {


                        $fetch_username = "SELECT `mobile_no` FROM `agency_header_all` WHERE `mobile_no` = '$mobile_no'";
                        $result = mysqli_query($conn, $fetch_username);
                        if (mysqli_num_rows($result) > 0) {

                            $query = "update agency_header_all SET login_pin = '$new_login_pin' where mobile_no = '$mobile_no'";
                            $result = mysqli_query($conn, $query);
                            // $query1 = "update app_user_token_details_all SET login_pin = '$new_login_pin' where mobile_no = '$mobile_no'";
                            // $result1 = mysqli_query($conn, $query1);
                            if ($conn->affected_rows > 0) {
                                $response[] = ["error_code" => 100, "message" => "Login pin set successfully", "login_pin" => $new_login_pin];
                                echo json_encode($response);
                                return;
                            } else {
                                $response[] = ["error_code" => 113, "message" => "trouble in setting pin, please try again."];
                                echo json_encode($response);
                                return;
                            }
                        } else {
                            $response[] = ["error_code" => 109, "message" => "User does not exist. please signup"];

                            echo json_encode($response);
                            return;
                        }
                    } else {
                        $response[] = ["error_code" => 105, "message" => "Please Enter 4 digit pin"];
                        echo json_encode($response);
                        return;
                    }
                }
            }
        } elseif ($mode == "login_pin") {

            if (!isset($_POST['login_pin'])) { //check if pin is missing or not given
                $response[] = ["error_code" => 110, "message" => "Please pass parameter - login_pin "];
                echo json_encode($response);
                return;
            } else {
                if ($_POST['login_pin'] == "") { //check if pin is empty
                    $response[] = ["error_code" => 111, "message" => "Paramter 'login_pin' cannot be empty. "];
                    echo json_encode($response);
                    return;
                }

                //check if pin no is valid or not

                else {

                    $query = "SELECT `app_device_token` FROM `app_user_token_details_all` WHERE SUBSTRING_INDEX(app_device_token, ',', 1) = '" . $_SERVER['HTTP_AUTH_KEY'] . "';";
                    $res = mysqli_query($conn, $query);
                    $arr = mysqli_fetch_assoc($res);

                    if (!$arr) { // Check if $arr is NULL (no rows found)
                        $response[] = ["error_code" => 440, "message" => "Invalid Token"];
                        echo json_encode($response);
                        exit;
                    }
                    $fetch_factory = "SELECT `digital_varification`, `online_store`,       `smart_watch_operations` FORM `factory_setting_header_all`";
                    $result_factory = mysqli_query($conn, $fetch_factory);
                    $arr_factory = mysqli_fetch_assoc($result_factory);

                    $login_pin = $_POST['login_pin'];
                    $mobile_no = $_POST['mobile_no'];

                    $fetch_username = "SELECT `mobile_no`, `agency_id` FROM `agency_header_all` WHERE `mobile_no`='$mobile_no' && `login_pin`='$login_pin'";

                    $result = mysqli_query($conn, $fetch_username);
                    $arr = mysqli_fetch_assoc($result);



                    $fetch_url = "SELECT `status`, `contract_id`, `process_id`, `from_id`, `contract_link` FROM `form_contract_details_all` WHERE `status`='active'";
                    $result1 = mysqli_query($conn, $fetch_url);
                    $terms = array();
                    
                    while ($arr_url1 = mysqli_fetch_assoc($result1)) {
                        // Query to check if there is a matching record in the second table
                        $fetch_member_contract = "SELECT `agency_id` FROM `member_contract_combination_all` WHERE `agency_id`='" . $arr['agency_id'] . "' AND `contract_id`='" . $arr_url1['contract_id'] . "'";
                        $resultmem_contract = mysqli_query($conn, $fetch_member_contract);
                        $arr_contract = mysqli_num_rows($resultmem_contract);
                    
                        // Determine if a matching record was found
                        $accepted = ($arr_contract == 1) ? true : false;
                    
                        // Add contract details along with accepted parameter to terms array
                        $terms[] = array(
                            "status" => $arr_url1['status'],
                            "process_id" => $arr_url1['process_id'],
                            "form_id" => $arr_url1['from_id'],
                            "contract_link" => $arr_url1['contract_link'],
                            "contract_id" => $arr_url1['contract_id'],
                            "is_accepted" => $accepted
                        );
                    }
                    


                    $fetch_setting = "SELECT `add_family_member`, `agency_id` FROM agency_setting_all WHERE `agency_id`='" . $arr['agency_id'] . "'";
                    $result_setting = mysqli_query($conn, $fetch_setting);
                    $arr_setting = mysqli_fetch_assoc($result_setting);
                    if ($arr['type'] == "individual") {
                        $type = "yes";
                    } else {
                        $type = $arr_setting['add_family_member'];
                    }

                    if ($arr > 0) {
                        $agency_id = $arr['agency_id'];
                        $username = $arr['name'];
                        $mobile_no = $arr['mobile_no'];
                        $agency_name = $arr['company_name'];
                        $agency_type = $arr['type'];
                        $status = $arr['status'];
                        $address = $arr['address'];
                        $city = $arr['city'];
                        $created_on = $arr['created_on'];
                        $login_pin = $arr['login_pin'];
                        $total_storage = $arr['total_storage'];
                        $available_storage = $arr['available_storage'];
                        $used_storage = $arr['used_storage'];
                        $archieve_storage = $arr['archieve_storage'];
                        $current_wallet_amt = $arr['current_wallet_bal'];
                        $cashback_amt = $arr['coupan_add_on_amount'];
                        $employee_designation = $arr['archieve_storage'];
                        $application_id = "132";
                        $offer_image = "https://img.freepik.com//free-vector//modern-sale-flyer-template-with-abstract-design_23-2147959835.jpg";

                        $fetch_form_process = "SELECT `form_id`, `process_ids` FROM `form_process_header_all` WHERE `status`='active'";
                        $res_form = mysqli_query($conn, $fetch_form_process);

                        $arr_pro = array();
                        while ($arr_form = mysqli_fetch_assoc($res_form)) {
                            $arr_pro[] = $arr_form;
                        }

                        $form_ids = array();
                        $all_ids = array();

                        foreach ($arr_pro as $form_process) {
                            $form_id = $form_process['form_id'];
                            $process_ids = explode(',', $form_process['process_ids']); // Splitting process_ids by comma

                            // Storing form_id in form_ids array if not already present
                            if (!in_array($form_id, $form_ids)) {
                                $form_ids[] = $form_id;
                            }

                            // Storing unique process_ids in all_ids array
                            foreach ($process_ids as $process_id) {
                                if (!empty($process_id) && !in_array($process_id, $all_ids)) {
                                    $all_ids[] = $process_id;
                                }
                            }
                        }

                        // Re-index the arrays
                        $form_ids = array_values($form_ids);
                        $all_ids = array_values($all_ids);

                        // Output the arrays


                        // print_r($terms);
                        $otp = rand(1000, 9999);
                        // $send_message = sms_helper_accept($mobile_no, $otp);
                        $response[] = [
                            "error_code" => 100, "message" => "Login successfully", "otp" => $otp, "username" => $username, "agency_id" => $agency_id, "contact" => $mobile_no, "agency_name" => $agency_name, "agency_type" => $agency_type, "status" => $status, "address" => $address, "city" => $city, "created_on" => $created_on,
                            "login_pin" => $login_pin, "total_storage" => $total_storage, "available_storage" => $available_storage, "used_storage" => $used_storage, "archieve_storage" => $archieve_storage,
                            "current_wallet_amt" => number_format($current_wallet_amt, 2, '.', ''), "cashback_amt" => $cashback_amt, "employee_designation" => $employee_designation, "offer_image" => $offer_image, "application_id" => $application_id, "advance_varification" => ($arr_factory['digital_varification'] = 1) ? "yes" : "no", "add_family_member" => replaceNullWithEmptyString($type), "online_store" => ($arr_factory['online_store'] = 1) ? "yes" : "no", "smart_watch_service" => ($arr_factory['smart_watch_operations'] = 1) ? "yes" : "no", "welcome_image" => "https://img.freepik.com/free-vector/welcome-concept-illustration_114360-370.jpg", "day_image" => "https://img.freepik.com/free-vector/happy-monday-background_23-2148719244.jpg", "form_id" => $form_ids, "process_id" => $all_ids, "terms_And_condition" => $terms, "gstin_no" => $arr['agency_gst_no']
                        ];
                        echo json_encode($response);
                        return;
                    } else {
                        $response[] = ["error_code" => 112, "message" => "Login Pin Is invalid"];
                        echo json_encode($response);
                        return;
                    }
                }
            }
        } else {
            $response[] = ["error_code" => 104, "message" => "value of mode does not match"];
            echo json_encode($response);
            return;
        }
    }
}


// To validate Mobile No
function validateMOB($pin)
{
    // Check if the Mobile No is numeric and has exactly 10 digits
    if (is_numeric($pin) && strlen($pin) === 10) {
        return true;
    } else {
        return false;
    }
}

// to validate pin
function validatePIN($pin)
{
    // Check if the PIN is numeric and has exactly 4 digits
    if (is_numeric($pin) && strlen($pin) === 4) {
        return true;
    } else {
        return false;
    }
}

function replaceNullWithEmptyString($value)
{
    return is_null($value) ? 'no' : $value;
}
//to send OTP
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