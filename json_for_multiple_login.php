<?php

include_once 'connection.php';

// Now you can use the connection class
$connection = connection::getInstance();
$mysqli = $connection->getConnection();

$connection1 = database::getInstance();
$mysqli1 = $connection1->getConnection();
apponoff($mysqli);

$common_check_error = common_check_error($mysqli, $_POST['mode'], $_POST['mobile_no'], $_POST['otp'], $_POST['agency_id'], $_POST['login_pin']);
if ($common_check_error == 1) {

    $mode = $_POST['mode'];
    $mobile_no = $_POST['mobile_no'];
    $otp = $_POST['otp'];
    $agency_id = $_POST['agency_id'];
    $device_id = $_POST['device_id'];
    $login_pin = $_POST['login_pin'];
    $otp = rand(0, 9999);
    $otp_string = sprintf("%04d", $otp);
    $token=generateRandomString(10);
$device_token=$token.",".$device_id;
    if ($mode == "verify_contact") {
        
        $fetch_app_user = "SELECT `reg_mobile_no`, `login_pin` FROM `app_user_token_details_all` WHERE `reg_mobile_no`='$mobile_no'";
        $res_app_user = mysqli_query($mysqli, $fetch_app_user);
        $arr_appuser = mysqli_fetch_assoc($res_app_user);
        
        $fetch_agency = "SELECT `status` FROM `agency_header_all` WHERE `mobile_no`='" . $arr_appuser['reg_mobile_no'] . "' AND `status`=0";
        $res_agency = mysqli_query($mysqli, $fetch_agency);
        
        if (mysqli_num_rows($res_agency) > 0) {
            $res[] = ["error_code" => 109, "message" => "Your agency is currently inactive. You cannot log in using this mobile number."];
            echo json_encode($res);
            exit;
        }
        $reg_mobile=$arr_appuser['reg_mobile_no'];
        $fetch_admin = "SELECT `status` FROM `admin_header_all` WHERE `mobile_no`='$reg_mobile' AND `status`=0";
        $res_admin = mysqli_query($mysqli, $fetch_admin);
        if (mysqli_num_rows($res_admin) > 0) {
            $res[] = ["error_code" => 109, "message" => "Your mobile number has been suspended by the agency. If you wish to login, please contact the agency owner."];
            echo json_encode($res);
            exit;
        }       
        
        if ($res_app_user->num_rows > 0) {
            $send_otp = sms_helper_accept($mobile_no, $otp_string);
            $res[] = ["error_code" => 100, "message" => "success", "otp" => $otp_string, "login_pin"=>replaceNullWithEmptyString($arr_appuser['login_pin'])];
            echo json_encode($res);
            return;
        } else {
            $res[] = ["error_code" => 200, "message" => "Mobile number is not registered"];
            echo json_encode($res);
            return;
        }
    }elseif($mode == "create_pin"){
        $update_login_pin="UPDATE `app_user_token_details_all` SET `login_pin`='$login_pin' WHERE `reg_mobile_no`='$mobile_no'";
        $res_update=mysqli_query($mysqli,$update_login_pin);

        $data[]=["error_code"=>100, "message"=>"Login pin change successfully", "login_pin"=>$login_pin];
        echo json_encode($data);
        return;
    }elseif($mode == "enter_pin"){

         $fetch_app_user = "SELECT `app_device_token`, `linked_agency_id` FROM `app_user_token_details_all` WHERE `reg_mobile_no`='$mobile_no'";
$res_app_user = mysqli_query($mysqli, $fetch_app_user);
$arr_user = mysqli_fetch_assoc($res_app_user);

$token_arr= explode(",", $arr_user['app_device_token']);
// print_r($token_arr);
if ($arr_user['app_device_token'] == "") {   

    $update_appuser = "UPDATE `app_user_token_details_all` SET `app_device_token`='$device_token' WHERE `reg_mobile_no`='$mobile_no'";
    $res_appuser = mysqli_query($mysqli, $update_appuser);
  
} 

if ($arr_user['app_device_token'] == "" || $device_id == $token_arr[1]) {

    $val="no";
}else{
    $val="yes";
}
        
        $arr_agency = explode(",", $arr_user['linked_agency_id']);
        $data = [];
        
        if (count($arr_agency) > 1) {
            $admin_yes_no = "yes";
        } else {
            $admin_yes_no = "no";
        }
        
        foreach ($arr_agency as $arr) {
        
            $fetch_agency = "SELECT `company_name`, `name`, `type` FROM `agency_header_all` WHERE `agency_id`='$arr'";
            $res_agency = mysqli_query($mysqli, $fetch_agency);
            $arr_agency_details = mysqli_fetch_assoc($res_agency);
            if ($arr_agency_details['type'] == 1) {
                $arr_type = $arr_agency_details['company_name'];
            } else {
                $arr_type = $arr_agency_details['name'];
            }
            $data[] = [
                "agency_id" => $arr,
                "mobile_no" => $mobile_no,
                "agency_name" => $arr_type,
                "another_device_already_login" => $val,
                "is_admin" => $admin_yes_no,
                "auth_token" => $arr_user['app_device_token']==""? $token:$token_arr[0]
            ];
        }
        
        $res[] = ["error_code" => 100, "message" => "data fetch successfully", "data" => $data];
        echo json_encode($res);
        
        
    }elseif($mode=="get_agency_data"){
        
        $fetch_app_user="SELECT `reg_mobile_no` FROM `app_user_token_details_all` WHERE `reg_mobile_no`='$mobile_no'";
        $res_app_user=mysqli_query($mysqli, $fetch_app_user);
        $arr_user_user=mysqli_fetch_assoc($res_app_user);

         $fetch_application="SELECT `application_id` FROM application_header_all WHERE `application_name`='VOCOxP' AND `status`=1";
        $res_application=mysqli_query($mysqli1, $fetch_application);
        $arr_application=mysqli_fetch_assoc($res_application);

        $fetch_agency = "SELECT `agency_id`, `name`, `mobile_no`, `email_id`, `company_name`, `type`,
        `status`, `address`, `total_storage`, `agency_gst_no`,
        `city`,
        `created_on`,
        `login_pin`,
        `available_storage`,
        `used_storage`,
        `archieve_storage`,
        `current_wallet_bal`,
        `coupan_add_on_amount`,
        `archieve_storage`,
        `agency_logo` FROM `agency_header_all` WHERE `mobile_no`='$mobile_no'";
        $fetch_result = mysqli_query($mysqli, $fetch_agency);
        $fetch_row = mysqli_num_rows($fetch_result);

          $fetch_factory = "SELECT `digital_verification`, `online_store`, `smart_watch_operations`, `first_offer_image`, `default_welcome_image` FROM `factory_setting_header_all`";
            $result_factory = mysqli_query($mysqli, $fetch_factory);
            $arr_factory = mysqli_fetch_assoc($result_factory);

            

        if($fetch_row==1){
            $fetch_arr = mysqli_fetch_assoc($fetch_result);


            $fetch_form_process = "SELECT `form_id`, `process_ids` FROM `form_process_header_all` WHERE `status`='1'";
            $res_form = mysqli_query($mysqli, $fetch_form_process);

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

            
            

            $fetch_agency_setting = "SELECT `add_family_member`, `agency_id` FROM `agency_setting_all` WHERE `agency_id`='$agency_id'";
            $result_agency_setting = mysqli_query($mysqli, $fetch_agency_setting);
            $arr_agency_setting = mysqli_fetch_assoc($result_agency_setting);

            $fetch_url = "SELECT `status`, `process_id`, `from_id`, `contract_link`,
            `contract_id` FROM `form_contract_details_all` WHERE `status`='1'";
            $result1 = mysqli_query($mysqli, $fetch_url);
            $terms = array();


            while ($arr_url1 = mysqli_fetch_assoc($result1)) {
                $agency=$fetch_arr['agency_id'];
                $contract= $arr_url1['contract_id'];
                // Query to check if there is a matching record in the second table
                $fetch_member_contract = "SELECT `agency_id`, `contract_id` FROM `member_contract_combination_all` WHERE `agency_id`='$agency' AND `contract_id`='$contract'";
                $resultmem_contract = mysqli_query($mysqli, $fetch_member_contract);
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
            $agency_id = $fetch_arr['agency_id'];
            $username = $fetch_arr['name'];
            $mobile_no = $fetch_arr['mobile_no'];
            $agency_name = $fetch_arr['company_name'];
            $agency_type = $fetch_arr['type'] == 1 ? "organization" : "individual";
           
            $status = ($fetch_arr['status']==1)?"Active":"Deactive";
            $address = $fetch_arr['address'];
            $city = $fetch_arr['city'];
            $created_on = $fetch_arr['created_on'];
            $login_pin = $fetch_arr['login_pin'];
            $total_storage = $arr['total_storage'];
            $available_storage = $fetch_arr['available_storage'];
            $used_storage = $fetch_arr['used_storage'];
            $archieve_storage = $fetch_arr['archieve_storage'];
            $current_wallet_amt = $fetch_arr['current_wallet_bal'];
            $cashback_amt = $fetch_arr['coupan_add_on_amount'];
            $employee_designation = $fetch_arr['archieve_storage'];
            $application_id = $arr_application['application_id'];
            $offer_image = $arr_factory['first_offer_image'];
            $otp = rand(1000, 9999);
            // $send_message = sms_helper_accept($mobile_no, $otp);
            $arr['agency_gst_no'];

            $visitor_data=array("is_guard"=>'yes',"v_location_id"=>'VIS-00025');

            $response[] = [
                "error_code" => 100, "message" => "Login successfully", "otp" => $otp, "username" => $username, "agency_id" => $agency_id, "contact" => $mobile_no, "agency_name" => $agency_name, "agency_type" => $agency_type, "status" => $status, "address" => $address, "city" => $city, "created_on" => $created_on,
                "login_pin" => $login_pin, "total_storage" => $total_storage, "available_storage" => $available_storage, "used_storage" => $used_storage, "archieve_storage" => $archieve_storage,
                "current_wallet_amt" => number_format($current_wallet_amt, 2, '.', ''), "cashback_amt" => $cashback_amt, "employee_designation" => $employee_designation, "offer_image" => $offer_image, "application_id" => $application_id, "advance_varification" => ($arr_factory['digital_verification'] == 1) ? "yes" : "no", "add_family_member" => replaceNullWithEmptyString($arr_agency_setting['add_family_member']==1?"yes":"no"), "online_store" => ($arr_factory['online_store'] == 1) ? "yes" : "no", "smart_watch_service" => ($arr_factory['smart_watch_operations'] == 1) ? "yes" : "no", "welcome_image" => $arr_factory['default_welcome_image'], "day_image" => "https://www.bakesandblunders.com/wp-content/uploads/2019/09/Wonderful-Wednesday-fall.png", "form_id" => $form_ids, "process_id" => $all_ids, "terms_And_condition" => $terms, "gstin_no" => $fetch_arr['agency_gst_no'], "is_owner"=>"Yes","agency_owner_name"=>$fetch_arr['name'], 
                    "agency_owner_contact"=>$fetch_arr['mobile_no'],
                    "agency_owner_email"=>$fetch_arr['email_id'],
                    "agency_logo"=>$fetch_arr['agency_logo'],
                     "visitor_data"=>$visitor_data
            ];

            echo json_encode($response);

        }else{
             $fetch_admin = "SELECT `linked_profile`, `admin_name`, `mobile_no`, `status`, `admin_id` FROM `admin_header_all` WHERE `mobile_no`='$mobile_no' AND `agency_id`='$agency_id'";
            $result_admin = mysqli_query($mysqli, $fetch_admin);
            $arr_admin = mysqli_fetch_assoc($result_admin);

            
            $fetch_agency = "SELECT `agency_id`, `company_name`, `type`, `status`, `address`,       `city`, `created_on`, `login_pin`, `total_storage`, `available_storage`,          `used_storage`, `archieve_storage`, `current_wallet_bal`, `coupan_add_on_amount`,
            `archieve_storage`, `agency_gst_no`, `name`, `mobile_no`, `email_id` FROM `agency_header_all` WHERE `agency_id`='$agency_id'";
            $result_agency = mysqli_query($mysqli, $fetch_agency);
            $arr_agency = mysqli_fetch_assoc($result_agency);

            $fetch_agency_setting = "SELECT `add_family_member`, `agency_id` FROM `agency_setting_all` WHERE `agency_id`='$agency_id'";
            $result_agency_setting = mysqli_query($mysqli, $fetch_agency_setting);
            $arr_agency_setting = mysqli_fetch_assoc($result_agency_setting);

           

            // if (!$arr_admin || !$arr_agency) {
            //     $response[] = ["error_code" => 200, "message" => "Invalid credentials"];
            //     echo json_encode($response);
            //     return;
            // }

            $profile_id = explode(",", $arr_admin['linked_profile']);
            $form_ids = [];
            $all_ids = [];

            foreach ($profile_id as $pro_id) {
                $fetch_profile = "SELECT `form_id`, `process_id`, `profile_id` FROM `profile_header_all` WHERE `profile_id`='$pro_id'";
                $res_profile = mysqli_query($mysqli, $fetch_profile);
                $arr_profile = mysqli_fetch_assoc($res_profile);

                $form_ids1 =  explode(",", trim($arr_profile['form_id']));
                $all_ids1 =  explode(",", trim($arr_profile['process_id']));

                array_push($form_ids, $form_ids1);
                array_push($all_ids, $all_ids1);
            }
            $form_ids = array_merge(...$form_ids);
            $all_ids = array_merge(...$all_ids);

            $agency_id = $arr_agency['agency_id'];
            $username = $arr_admin['admin_name'];
            $mobile_no = $arr_admin['mobile_no'];
            $agency_name = $arr_agency['company_name'];
            $agency_type = $arr_agency['type']==1?"organization":"individual";
            $status = $arr_agency['status'];
            $address = $arr_agency['address'];
            $city = $arr_agency['city'];
            $created_on = $arr_agency['created_on'];
            $login_pin = $arr_agency['login_pin'];
            $total_storage = $arr_agency['total_storage'];
            $available_storage = $arr_agency['available_storage'];
            $used_storage = $arr_agency['used_storage'];
            $archieve_storage = $arr_agency['archieve_storage'];
            $current_wallet_amt = $arr_agency['current_wallet_bal'];
            $cashback_amt = $arr_agency['coupan_add_on_amount'];
            $employee_designation = $arr_agency['archieve_storage'];
            $application_id = $arr_application['application_id'];
            $offer_image = "https://img.freepik.com//free-vector//modern-sale-flyer-template-with-abstract-design_23-2147959835.jpg";

            

            $response[] = [
                "error_code" => 100,
                "message" => "Login successfully",
                // "data" => $data,
                "otp" => $otp,
                "username" => $username,
                "agency_id" => $agency_id,
                "contact" => $mobile_no,
                "agency_name" => $agency_name,
                "agency_type" => $agency_type,
                "status" => $status,
                "address" => $address,
                "city" => $city,
                "created_on" => $created_on,
                "login_pin" => $arr_agency['login_pin'],
                "total_storage" => $total_storage,
                "available_storage" => $available_storage,
                "used_storage" => $used_storage,
                "archieve_storage" => $archieve_storage,
                "current_wallet_amt" => number_format($current_wallet_amt, 2, '.', ''),
                "cashback_amt" => $cashback_amt,
                "employee_designation" => $employee_designation,
                
                "application_id" => $application_id,
                "form_id" => $form_ids,
                "process_id" => $all_ids,
                "gstin_no" => $arr_agency['agency_gst_no'],
                "add_family_member" => $arr_agency_setting['add_family_member']==1?"yes":"no",
                
                "advance_varification" => ($arr_factory['digital_verification'] == 1) ? "yes" : "no",
                 "online_store" => ($arr_factory['online_store'] == 1) ? "yes" : "no", "smart_watch_service" => ($arr_factory['smart_watch_operations'] == 1) ? "yes" : "no",
                 "is_owner"=>"No",
                 "admin_id"=>$arr_admin['admin_id'], 
                 "agency_owner_name"=>$arr_agency['name'], 
                 "agency_owner_contact"=>$arr_agency['mobile_no'],
                 "agency_owner_email"=>$arr_agency['email_id']
                
            ];

            echo json_encode($response);
            return;



        }
    }elseif($mode=="forgot_pin"){
        $fetch_appuser = "SELECT `login_pin`, `reg_mobile_no`  FROM app_user_token_details_all WHERE reg_mobile_no = '$mobile_no'";
                    $result = mysqli_query($mysqli, $fetch_appuser);
                    $arr = mysqli_fetch_assoc($result);

        $fetch_username_agency = "SELECT `name`  FROM `agency_header_all` WHERE mobile_no = '$mobile_no'";
                    $result_agency = mysqli_query($mysqli, $fetch_username_agency);
                    $arr_agency = mysqli_fetch_assoc($result_agency);

        $fetch_admin = "SELECT `admin_name`  FROM `admin_header_all` WHERE mobile_no = '$mobile_no'";
                    $result_admin = mysqli_query($mysqli, $fetch_admin);
                    $arr_admin = mysqli_fetch_assoc($result_admin);
                    if (mysqli_num_rows($result) > 0) { // if mobile_no is already registered
                        if ($arr > 0) {
                            $login_pin = $arr['login_pin'];
                            if ($login_pin == "") {
                                $login_pin = "";
                            }
                            $username = $arr_agency['name']==""?$arr_admin['admin_name']:$arr_agency['name'];
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
    }
    
}

function common_check_error($mysqli, $mode, $mobile_no, $otp, $agency_id, $login_pin)
{
    $common_check_error = 1;
    if (!$mysqli) {
        $response[] = ["error_code" => 101, "message" => "An unexpected server error occurred while
        processing your request. Please try after sometime"];
        echo json_encode($response);
        return;
    }
    if ($_SERVER["REQUEST_METHOD"] != "POST") {
        $responce[] = array("error_code" => 102, "message" => "please change request method to POST");
        echo json_encode($responce);
        return;
    }

    if (!isset($mode)) {
        $response[] = ["error_code" => 103, "message" => "Please add mode"];
        echo json_encode($response);
        return;
    }
    if (empty($mode)) {
        $response[] = ["error_code" => 104, "message" => "mode is empty"];
        echo json_encode($response);
        return;
    }

    if ($mode != 'verify_contact' && $mode != 'get_agency_data' && $mode != 'enter_pin' && $mode!="create_pin" && $mode!="forgot_pin") {
        $response[] = ["error_code" => 105, "message" => "Paramter 'mode' value is invalid. please enter value either verify_contact or get_agency_data or enter_pin or create_pin or forgot_pin."];
        echo json_encode($response);
        return;
    }
    if (!isset($mobile_no)) {
        $response[] = ["error_code" => 106, "message" => "Please parameter of mobile_no"];
        echo json_encode($response);
        return;
    }

    if ($mobile_no == "") {
        $response[] = ["error_code" => 107, "message" => "Value of mobile_no is cannot be empty"];
        echo json_encode($response);
        return;
    }

    if ($mode == "verify_otp") {
        if (!isset($otp)) {
            $response[] = ["error_code" => 108, "message" => "Please parameter of otp"];
            echo json_encode($response);
            return;
        }

        if ($otp == "") {
            $response[] = ["error_code" => 109, "message" => "Value of otp is cannot be empty"];
            echo json_encode($response);
            return;
        }
    }
    if ($mode == "get_agency_data") {
        if (!isset($agency_id)) {
            $response[] = ["error_code" => 110, "message" => "Please parameter of agency_id"];
            echo json_encode($response);
            return;
        }

        if ($agency_id == "") {
            $response[] = ["error_code" => 111, "message" => "Value of agency_id is cannot be empty"];
            echo json_encode($response);
            return;
        }
    }
    if ($mode == "create_pin" || $mode=="enter_pin") {
        if (!isset($login_pin)) {
            $response[] = ["error_code" => 112, "message" => "Please parameter of login_pin"];
            echo json_encode($response);
            return;
        }

        if ($login_pin == "") {
            $response[] = ["error_code" => 113, "message" => "Value of login_pin is cannot be empty"];
            echo json_encode($response);
            return;
        }
    }
    return $common_check_error;
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

    $message = urlencode(" Welcome to VOCOxP!, Your OTP to verify contact number is $otp Developed by Micro Integrated");

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
function replaceNullWithEmptyString($value)
{
    return ($value === null) ? "" : $value;
}

function validatePIN($pin)
{
    // Check if the PIN is numeric and has exactly 4 digits
    if (is_numeric($pin) && strlen($pin) === 4) {
        return true;
    } else {
        return false;
    }
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