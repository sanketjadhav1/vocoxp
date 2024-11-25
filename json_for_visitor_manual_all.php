<?php

 // error_reporting(E_ALL & ~E_DEPRECATED);
 // ini_set('display_errors', 1);
require_once __DIR__ . '/vendor/autoload.php'; 
header('Content-Type: application/json; charset=UTF-8');
header('Access-Contorl-Allow-Origin: *');
date_default_timezone_set('Asia/kolkata');
include 'connection.php';
$connection = connection::getInstance();
$mysqli = $connection->getConnection();

$connection1 = database::getInstance();
$mysqli1 = $connection1->getConnection();
// apponoff($mysqli);  
// logout($_SERVER['HTTP_AUTH_KEY'], $mysqli);

date_default_timezone_set('Asia/kolkata');
$system_date = date("y-m-d");
$system_date_time = date("Y-m-d H:i:s");


 
 
$initiated_on  = $system_date;
$completed_on = $system_date;
$agency_id = $_POST['agency_id'] ?? '';
$visitor_id  = $visitor_id = unique_id_genrate('VIS', 'visitor_header_all', $mysqli); 
$verification_id = $_POST['verification_id'] ?? '';
$visitor_location_id = $_POST['visitor_location_id'] ?? '';
$visitor_name = $_POST['visitor_name'] ?? '';
$visitor_email = $_POST['visitor_email'] ?? '';
$visitor_mobile = $_POST['visitor_mobile'] ?? '';
$people_with_visitor = $_POST['people_with_visitor'] ?? '';
$person_arrival_date = $system_date;
$requested_on = $system_date_time;
$mode = "2";
$meeting_with = $_POST['meeting_with'] ?? '';
$meeting_status = $_POST['meeting_status'] ?? '';
$visitor_location_id = $_POST['visitor_location_id'] ?? '';
$address = $_POST['address'] ?? '';
$user_photo = $_FILES['user_photo'] ?? "";  


$admin_id=$_POST['admin_id'];
if($admin_id!=""){
    $admin_id = $_POST['admin_id'] ?? '';
}else{
    $admin_id = $_POST['agency_id'] ?? '';
}
 // Save documents if they are provided
if ($user_photo != "") {
    $user_img = save_user_photo($user_photo, $meeting_with, $agency_id,$visitor_id);
}
else
{
    $user_img="";
}
 
if (isValidMobile($visitor_mobile) == 1) {

} 
else {
        $response = array("error_code" => 199, "message" => "Mobile number is invalid. Please provide a valid Mobile number");
        echo json_encode($response);
        return;
    }

$check_error_res =check_error($mysqli, $mysqli1, $agency_id,$verification_id,$visitor_name ,$visitor_mobile,$visitor_location_id,$address);
if ($check_error_res == 1) 
{
            $fetch_agency = "SELECT `company_name`, `name`, `type` FROM `agency_header_all` WHERE `agency_id`='$agency_id'";
            $res_agency = mysqli_query($mysqli, $fetch_agency);
            $arr_agency_details = mysqli_fetch_assoc($res_agency);
	 		if (!$arr_agency_details) {
                // Handle no results found
                // die("No agency found with ID: " . $agency_id);
                $response = ["error_code" => 105, "message" => "No agency found with ID: " . $agency_id];
                echo json_encode($response);
                return;
            }
            else
            {


                if($verification_id=="DVF-00001")
                { $aadhar_number = $_POST['aadhar_number'] ?? '';

                    if (isValidAadhar($aadhar_number) == 1) {
                                                if($aadhar_number!="")
                        {
                            //for Aadhar
                            $sql_query="INSERT INTO `visitor_temp_activity_detail_all`( `agency_id`, `visitor_id`, `visitor_location_id`, `visitor_name`, `visitor_email`, `visitor_mobile`, `people_with_visitor`, `person_arrival_date`, `requested_on`, `verification_type`, `mode`, `meeting_with`, `meeting_status`,`aadhar_number`,`address`,`user_photo`) VALUES ('$agency_id','$visitor_id','$visitor_location_id','$visitor_name','$visitor_email','$visitor_mobile','$people_with_visitor','$person_arrival_date','$requested_on','$verification_id','$mode','$meeting_with','$meeting_status','$aadhar_number','$address','$user_img')";
                            mysqli_query($mysqli,$sql_query);
                             $sql_query=mysqli_query($mysqli,"SELECT * FROM `employee_header_all` WHERE emp_id='$meeting_with'");
                             $row_emp=mysqli_fetch_assoc($sql_query);
                            $visitor_approval_required="";
                            $visiting_charges="";
                            $verification_paid_by="";
                            if($row_emp["visitor_approval_required"]==1)
                            {
                                $visitor_approval_required="Y";
                            }
                            else
                            {
                                $visitor_approval_required="N";

                            }
                            if($row_emp["visiting_charges"]==1)
                            {
                                $visiting_charges="Y";
                            }
                            else
                            {
                                $visiting_charges="N";

                            }
                             if($row_emp["visiting_charges"]=='W')
                            {
                                $verification_paid_by="W";
                            }
                            else
                            {
                                $verification_paid_by="E";

                            }
                            $data[]=["visitor_id"=>$visitor_id,"visitor_name"=>$visitor_name,"employee_id"=>$meeting_with,"visitor_mobile"=>$mobile_no,"visitor_photo"=>$user_img,"visitor_approval_required"=>$visitor_approval_required,"visiting_charges"=>$visiting_charges,"verification_paid_by"=>$verification_paid_by]; 
                            $response = array("error_code" => 100, "message" => "Visitor added successfully.","data"=>$data);
                            echo json_encode($response);
                            return;
                        }
                        else
                        {
                             $response = ["error_code" => 105, "message" => "Please pass the parameter of aadhar_number"];
                            echo json_encode($response);
                            return;
                        }
                    } 
                    else {
                        $response = array("error_code" => 199, "message" => "Aadhaar number is invalid. Please provide a valid Aadhaar number");
                        echo json_encode($response);
                        return;
                    }
                }
                elseif ($verification_id=="DVF-00002") { 
                        $pan_number = $_POST['pan_number'] ?? ''; 
                     if (isValidPAN($pan_number) == 1) {
                        if($pan_number!="")
                        {
                            //for pan
                            $sql_query="INSERT INTO `visitor_temp_activity_detail_all`( `agency_id`, `visitor_id`, `visitor_location_id`, `visitor_name`, `visitor_email`, `visitor_mobile`, `people_with_visitor`, `person_arrival_date`, `requested_on`, `verification_type`, `mode`, `meeting_with`, `meeting_status`,`pan_number`,`address`,`user_photo`) VALUES ('$agency_id','$visitor_id','$visitor_location_id','$visitor_name','$visitor_email','$visitor_mobile','$people_with_visitor','$person_arrival_date','$requested_on','$verification_id','$mode','$meeting_with','$meeting_status','$pan_number','$address','$user_img')";
                            mysqli_query($mysqli,$sql_query);
                              $sql_query=mysqli_query($mysqli,"SELECT * FROM `employee_header_all` WHERE emp_id='$meeting_with'");
                            $row_emp=mysqli_fetch_assoc($sql_query);
                            $visitor_approval_required="";
                            $visiting_charges="";
                            $verification_paid_by="";
                            if($row_emp["visitor_approval_required"]==1)
                            {
                                $visitor_approval_required="Y";
                            }
                            else
                            {
                                $visitor_approval_required="N";

                            }
                            if($row_emp["visiting_charges"]==1)
                            {
                                $visiting_charges="Y";
                            }
                            else
                            {
                                $visiting_charges="N";

                            }
                             if($row_emp["visiting_charges"]=='W')
                            {
                                $verification_paid_by="W";
                            }
                            else
                            {
                                $verification_paid_by="E";

                            }
                            $data[]=["visitor_id"=>$visitor_id,"visitor_name"=>$visitor_name,"employee_id"=>$meeting_with,"visitor_mobile"=>$mobile_no,"visitor_photo"=>$user_img,"visitor_approval_required"=>$visitor_approval_required,"visiting_charges"=>$visiting_charges,"verification_paid_by"=>$verification_paid_by];
                            $response = array("error_code" => 100, "message" => "Visitor added successfully.","data"=>$data);
                            echo json_encode($response);
                            return;
                         }
                        else
                        {
                             $response = ["error_code" => 105, "message" => "Please pass the parameter of pan_number"];
                            echo json_encode($response);
                            return;
                        }
                     } 
                     else {
                        $response = array("error_code" => 199, "message" => "PAN number is invalid. Please provide a valid PAN number");
                        echo json_encode($response);
                        return;
                    }
                 }
                elseif ($verification_id=="DVF-00004") { 
                    //for DL
                    $dl_number = $_POST['dl_number'] ?? '';
                     $dob = $_POST['dob'] ?? '';
                     if (isValidDl($dl_number) == 1) {
                             if($dl_number!="" && $dob!="" )
                            { 
                                 
                              $dob = DateTime::createFromFormat('d/m/Y', $dob);
                              $dob = $dob->format('Y-m-d');
                                
                                $sql_query="INSERT INTO `visitor_temp_activity_detail_all`( `agency_id`, `visitor_id`, `visitor_location_id`, `visitor_name`, `visitor_email`, `visitor_mobile`, `people_with_visitor`, `person_arrival_date`, `requested_on`, `verification_type`, `mode`, `meeting_with`, `meeting_status`,`dl_number`,`dob`,`address`,`user_photo`) VALUES ('$agency_id','$visitor_id','$visitor_location_id','$visitor_name','$visitor_email','$visitor_mobile','$people_with_visitor','$person_arrival_date','$requested_on','$verification_id','$mode','$meeting_with','$meeting_status','$dl_number','$dob','$address','$user_img')";
                                mysqli_query($mysqli,$sql_query);
                                 $sql_query=mysqli_query($mysqli,"SELECT * FROM `employee_header_all` WHERE emp_id='$meeting_with'");
                                $row_emp=mysqli_fetch_assoc($sql_query);
                                $visitor_approval_required="";
                                $visiting_charges="";
                                $verification_paid_by="";
                                if($row_emp["visitor_approval_required"]==1)
                                {
                                    $visitor_approval_required="Y";
                                }
                                else
                                {
                                    $visitor_approval_required="N";

                                }
                                if($row_emp["visiting_charges"]==1)
                                {
                                    $visiting_charges="Y";
                                }
                                else
                                {
                                    $visiting_charges="N";

                                }
                                 if($row_emp["visiting_charges"]=='W')
                                {
                                    $verification_paid_by="W";
                                }
                                else
                                {
                                    $verification_paid_by="E";

                                }
                                $data[]=["visitor_id"=>$visitor_id,"visitor_name"=>$visitor_name,"employee_id"=>$meeting_with,"visitor_mobile"=>$mobile_no,"visitor_photo"=>$user_img,"visitor_approval_required"=>$visitor_approval_required,"visiting_charges"=>$visiting_charges,"verification_paid_by"=>$verification_paid_by];
                                $response = array("error_code" => 100, "message" => "Visitor added successfully.","data"=>$data);
                                echo json_encode($response);
                                return;
                            }
                            else
                            {
                                if($dl_number=="")
                                {
                                 $msg .=" dl_number ";
                                }
                                 if($dob=="")
                                {
                                 $msg .=" dob ";
                                 
                                }
                                $response = ["error_code" => 105, "message" => "Please pass the parameter of $msg"];
                                echo json_encode($response);
                                return;
                            }
                         } 
                         else {
                        $response = array("error_code" => 199, "message" => "DL number is invalid. Please provide a valid DL number");
                        echo json_encode($response);
                        return;
                    }

                 }
                elseif ($verification_id=="DVF-00005") { 
                    //for Voter

                     $voter_number = $_POST['voter_number'] ?? ''; 
                      if (isValidVoter($voter_number) == 1) {
                            if($voter_number!="")
                            { 
                                $sql_query="INSERT INTO `visitor_temp_activity_detail_all`( `agency_id`, `visitor_id`, `visitor_location_id`, `visitor_name`, `visitor_email`, `visitor_mobile`, `people_with_visitor`, `person_arrival_date`, `requested_on`, `verification_type`, `mode`, `meeting_with`, `meeting_status`,`voter_number`,`address`,`user_photo`) VALUES ('$agency_id','$visitor_id','$visitor_location_id','$visitor_name','$visitor_email','$visitor_mobile','$people_with_visitor','$person_arrival_date','$requested_on','$verification_id','$mode','$meeting_with','$meeting_status','$voter_number','$address','$user_img')";
                                mysqli_query($mysqli,$sql_query);
                                 $sql_query=mysqli_query($mysqli,"SELECT * FROM `employee_header_all` WHERE emp_id='$meeting_with'");
                                $row_emp=mysqli_fetch_assoc($sql_query);
                                $visitor_approval_required="";
                                $visiting_charges="";
                                $verification_paid_by="";
                                if($row_emp["visitor_approval_required"]==1)
                                {
                                    $visitor_approval_required="Y";
                                }
                                else
                                {
                                    $visitor_approval_required="N";

                                }
                                if($row_emp["visiting_charges"]==1)
                                {
                                    $visiting_charges="Y";
                                }
                                else
                                {
                                    $visiting_charges="N";

                                }
                                 if($row_emp["visiting_charges"]=='W')
                                {
                                    $verification_paid_by="W";
                                }
                                else
                                {
                                    $verification_paid_by="E";

                                }
                                $data[]=["visitor_id"=>$visitor_id,"visitor_name"=>$visitor_name,"employee_id"=>$meeting_with,"visitor_mobile"=>$mobile_no,"visitor_photo"=>$user_img,"visitor_approval_required"=>$visitor_approval_required,"visiting_charges"=>$visiting_charges,"verification_paid_by"=>$verification_paid_by];
                                $response = array("error_code" => 100, "message" => "Visitor added successfully.","data"=>$data);
                                echo json_encode($response);
                                return;
                            }
                            else
                            {
                                 $response = ["error_code" => 105, "message" => "Please pass the parameter of voter_number"];
                                echo json_encode($response);
                                return;
                            }
                     }
                      else {
                        $response = array("error_code" => 199, "message" => "Voter number is invalid. Please provide a valid Voter ID");
                        echo json_encode($response);
                        return;
                    }
                 }
                elseif ($verification_id=="DVF-00006") { 
                    //for Passport
                     $passport_number = $_POST['passport_number'] ?? ''; 
                     $dob = $_POST['dob'] ?? ''; 
                     $first_name = $_POST['first_name'] ?? ''; 
                     $last_name = $_POST['last_name'] ?? ''; 
                     $file_number = $_POST['file_number'] ?? ''; 
                      if (isValidPassport($passport_number) == 1) {
                              if($passport_number!="" && $dob!="" && $first_name!="" && $last_name!="" && $file_number!="" )
                            {
                                 $dob = DateTime::createFromFormat('d/m/Y', $dob);
                                 $dob = $dob->format('Y-m-d');

                                $sql_query="INSERT INTO `visitor_temp_activity_detail_all`( `agency_id`, `visitor_id`, `visitor_location_id`, `visitor_name`, `visitor_email`, `visitor_mobile`, `people_with_visitor`, `person_arrival_date`, `requested_on`, `verification_type`, `mode`, `meeting_with`, `meeting_status`,`passport_number`,`dob`,`first_name`,`last_name`,`file_number`,`address`,`user_photo`) VALUES ('$agency_id','$visitor_id','$visitor_location_id','$visitor_name','$visitor_email','$visitor_mobile','$people_with_visitor','$person_arrival_date','$requested_on','$verification_id','$mode','$meeting_with','$meeting_status','$passport_number','$dob','$first_name','$last_name','$file_number','$address','$user_img')";
                                mysqli_query($mysqli,$sql_query);
                                                 $sql_query=mysqli_query($mysqli,"SELECT * FROM `employee_header_all` WHERE emp_id='$meeting_with'");
                            $row_emp=mysqli_fetch_assoc($sql_query);
                            $visitor_approval_required="";
                            $visiting_charges="";
                            $verification_paid_by="";
                            if($row_emp["visitor_approval_required"]==1)
                            {
                                $visitor_approval_required="Y";
                            }
                            else
                            {
                                $visitor_approval_required="N";

                            }
                            if($row_emp["visiting_charges"]==1)
                            {
                                $visiting_charges="Y";
                            }
                            else
                            {
                                $visiting_charges="N";

                            }
                             if($row_emp["visiting_charges"]=='W')
                            {
                                $verification_paid_by="W";
                            }
                            else
                            {
                                $verification_paid_by="E";

                            }
                            $data[]=["visitor_id"=>$visitor_id,"visitor_name"=>$visitor_name,"employee_id"=>$meeting_with,"visitor_mobile"=>$mobile_no,"visitor_photo"=>$user_img,"visitor_approval_required"=>$visitor_approval_required,"visiting_charges"=>$visiting_charges,"verification_paid_by"=>$verification_paid_by];
                                $response = array("error_code" => 100, "message" => "Visitor added successfully.","data"=>$data);
                                echo json_encode($response);
                                return;
                            }
                            else
                            {
                                 if($passport_number=="")
                                {
                                 $msg .=" passport_number ";
                                }
                                 if($dob=="")
                                {
                                 $msg .=" dob ";
                                 
                                }
                                if($dob=="")
                                {
                                 $msg .=" dob ";
                                 
                                }
                                if($first_name=="")
                                {
                                 $msg .=" first_name ";
                                 
                                }
                                if($last_name=="")
                                {
                                 $msg .=" last_name ";
                                 
                                }
                                 if($file_number=="")
                                {
                                 $msg .=" file_number ";
                                 
                                }
                                $response = ["error_code" => 105, "message" => "Please pass the parameter of $msg"];
                                echo json_encode($response);
                                return;
                            }
                        }
                      else {
                        $response = array("error_code" => 199, "message" => "passport number is invalid. Please provide a valid passport ID");
                        echo json_encode($response);
                        return;
                    }

                 }
                 else
                 {
                    $response = ["error_code" => 105, "message" => "No verification id found with ID: " . $verification_id];
                        echo json_encode($response);
                        return;
                 }
        }
}




function check_error($mysqli, $mysqli1, $agency_id ,$verification_id,$visitor_name ,$visitor_mobile,$visitor_location_id,$address)
{

    $check_error = 1;
    if (!$mysqli) {
        $response = ["error_code" => 101, "message" => "Unable to proceed your request, please try again after some time"];
        echo json_encode($response);
        return;
    }

    if ($_SERVER['REQUEST_METHOD'] != "POST") {
        $response = array("error_code" => 102, "message" => "Request Method is not Post");
        echo json_encode($response);
        return;
    }

     

    if (!isset($agency_id)) {
        $response = ["error_code" => 105, "message" => "Please pass the parameter of agency_id"];
        echo json_encode($response);
        return;
    }
    if (empty($agency_id)) {
        $response = ["error_code" => 106, "message" => "agency_id can not be empty"];
        echo json_encode($response);
        return;
    }

     if (!isset($verification_id)) {
        $response = ["error_code" => 105, "message" => "Please pass the parameter of verification_id like DVF-00001,DVF-00002...etc "];
        echo json_encode($response);
        return;
    }
    if (empty($verification_id)) {
        $response = ["error_code" => 106, "message" => "verification_id can not be empty like DVF-00001,DVF-00002...etc"];
        echo json_encode($response);
        return;
    }
    if (!isset($visitor_name)) {
        $response = ["error_code" => 105, "message" => "Please pass the parameter of visitor_name"];
        echo json_encode($response);
        return;
    }
    if (empty($visitor_name)) {
        $response = ["error_code" => 106, "message" => "visitor_name can not be empty"];
        echo json_encode($response);
        return;
    }
     
     if (!isset($visitor_mobile)) {
        $response = ["error_code" => 105, "message" => "Please pass the parameter of visitor_mobile"];
        echo json_encode($response);
        return;
    }
    if (empty($visitor_mobile)) {
        $response = ["error_code" => 106, "message" => "visitor_mobile can not be empty"];
        echo json_encode($response);
        return;
    }
 if (!isset($visitor_location_id)) {
        $response = ["error_code" => 105, "message" => "Please pass the parameter of visitor_location_id"];
        echo json_encode($response);
        return;
    }
    if (empty($visitor_location_id)) {
        $response = ["error_code" => 106, "message" => "visitor_location_id can not be empty"];
        echo json_encode($response);
        return;
    }
    if (!isset($address)) {
        $response = ["error_code" => 105, "message" => "Please pass the parameter of address"];
        echo json_encode($response);
        return;
    }
    if (empty($address)) {
        $response = ["error_code" => 106, "message" => "address can not be empty"];
        echo json_encode($response);
        return;
    }

    return $check_error;
}
function isValidAadhar($pan)
{
    // Regular expression to match PAN format
    $pattern = '/(^[0-9]{4}[0-9]{4}[0-9]{4}$)|(^[0-9]{4}\s[0-9]{4}\s[0-9]{4}$)|(^[0-9]{4}-[0-9]{4}-[0-9]{4}$)/';

    // Check if the PAN matches the pattern
    if (preg_match($pattern, $pan)) {
        return 1; // PAN is valid
    } else {
        return 0; // PAN is invalid
    }
}
function isValidPAN($pan)
{
    // Regular expression to match PAN format
    $pattern = '/^([a-zA-Z]){5}([0-9]){4}([a-zA-Z]){1}?$/';

    // Check if the PAN matches the pattern
    if (preg_match($pattern, $pan)) {
        return 1; // PAN is valid
    } else {
        return 0; // PAN is invalid
    }
}
function isValidVoter($pan) {
    // Regular expression to match PAN format (case-insensitive)
    $pattern = '/^[A-Z]{3}[0-9]{7}$/i'; 

    // Check if the PAN matches the pattern
    if (preg_match($pattern, $pan)) {
        return 1; // PAN is valid
    } else {
        return 0; // PAN is invalid
    }
}

function isValidDl($pan)
{
    // Regular expression to match PAN format
     $pattern = '/^[A-Z]{2}\s?\d{2}\s?\d{4}\s?\d{7}$/';

    // Check if the PAN matches the pattern
    if (preg_match($pattern, $pan)) {
        return 1; // PAN is valid
    } else {
        return 0; // PAN is invalid
    }
}

function isValidMobile($mobile)
{
    // Regular expression to match PAN format
     $pattern = '/^\+?([0-9]{1,3})?[-.\s]?([0-9]{3})[-.\s]?([0-9]{3})[-.\s]?([0-9]{4})$/';

    // Check if the PAN matches the pattern
    if (preg_match($pattern, $mobile)) {
        return 1; // PAN is valid
    } else {
        return 0; // PAN is invalid
    }
}
function isValidPassport($passport)
{
    // Regular expression to match PAN format
     $pattern = '/^[A-Z]{1,2}[0-9]{6,9}$|^[A-Z0-9]{6,9}$|^[0-9]{9}$|^[A-Z][0-9]{7}$|^[A-Z]{2}[0-9]{6}$/';

    // Check if the PAN matches the pattern
    if (preg_match($pattern, $passport)) {
        return 1; // PAN is valid
    } else {
        return 0; // PAN is invalid
    }
}

 function save_user_photo($upload_file, $direct_id, $agency_id,$visitor_id)
{
   // Ensure $upload_file is an array and has the necessary keys
    if (!is_array($upload_file) || !isset($upload_file['name']) || !isset($upload_file['tmp_name'])) {
        throw new Exception('Invalid file upload array.');
    }

    // FTP server credentials
    $ftp_server = '199.79.62.21';
    $ftp_username = 'centralwp@mounarchtech.com';
    $ftp_password = 'k=Y#oBK{h}OU';

    // Remote directory path
    $remote_base_dir = "/verification_data/voco_xp/";

    // Nested directory structure to be created
    $new_directory_path = "$agency_id/$direct_id/$visitor_id/user_photo/";

    // Initialize cURL session for FTP
    $curl = curl_init();

    // Set cURL options for FTP
    curl_setopt_array($curl, array(
        CURLOPT_URL => "ftp://$ftp_server/",
        CURLOPT_USERPWD => "$ftp_username:$ftp_password",
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_FTP_CREATE_MISSING_DIRS => true, // Create missing directories
    ));

    // Execute the cURL session for FTP
    ob_start(); // Start output buffering
    $response_ftp = curl_exec($curl);
    ob_end_clean(); // Discard output buffer

    // Check for errors in FTP request
    if ($response_ftp === false) {
        $error_message_ftp = curl_error($curl);
        die("Failed to connect to FTP server: $error_message_ftp");
    }

    // Set the target directory
    $remote_dir_path = $remote_base_dir . $new_directory_path;

    // Create directory recursively
    curl_setopt($curl, CURLOPT_URL, "ftp://$ftp_server/$remote_dir_path");
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'MKD');
    ob_start(); // Start output buffering
    $response_mkdir = curl_exec($curl);
    ob_end_clean(); // Discard output buffer

    // Check for errors in directory creation
    if ($response_mkdir === false) {
        $error_message_mkdir = curl_error($curl);
        // It's possible the directory already exists, so we can ignore this error in some cases
    }

    // Get file extension
    $ext = strtolower(pathinfo($upload_file['name'], PATHINFO_EXTENSION));

    // Generate a unique file name
    $file_name = uniqid();

    // Path to save the uploaded file on the remote server
    $file_path = $remote_dir_path . $file_name . "." . $ext;

    // Create an image resource from the uploaded file
    switch ($ext) {
        case 'jpeg':
        case 'jpg':
            $image = imagecreatefromjpeg($upload_file['tmp_name']);
            break;
        case 'png':
            $image = imagecreatefrompng($upload_file['tmp_name']);
            break;
        case 'gif':
            $image = imagecreatefromgif($upload_file['tmp_name']);
            break;
        default:
            throw new Exception('Unsupported file format');
    }

    // Get original dimensions
    list($width, $height) = getimagesize($upload_file['tmp_name']);

    // Desired maximum size in bytes
    $maxSize = 20 * 1024; // 20 KB

    // Resize image dimensions (e.g., reduce to 20% of original size)
    $new_width = $width * 0.2;
    $new_height = $height * 0.2;
    $resized_image = imagecreatetruecolor($new_width, $new_height);

    // Preserve transparency for PNG and GIF
    if ($ext === 'png' || $ext === 'gif') {
        imagecolortransparent($resized_image, imagecolorallocatealpha($resized_image, 0, 0, 0, 127));
        imagealphablending($resized_image, false);
        imagesavealpha($resized_image, true);
    }

    // Copy and resize the original image into the resized image
    imagecopyresampled($resized_image, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

    // Start with lower quality
    $quality = 50; // Adjust the quality setting

    // Temporary output buffer
    ob_start();
    switch ($ext) {
        case 'jpeg':
        case 'jpg':
            imagejpeg($resized_image, null, $quality);
            break;
        case 'png':
            imagepng($resized_image, null, (int)($quality / 10 - 1));
            break;
        case 'gif':
            imagegif($resized_image, null);
            break;
    }
    $imageData = ob_get_clean();
    $fileSize = strlen($imageData);

    // Decrease quality until file size is within desired range
    while ($fileSize > $maxSize && $quality > 10) {
        $quality -= 5;
        ob_start();
        switch ($ext) {
            case 'jpeg':
            case 'jpg':
                imagejpeg($resized_image, null, $quality);
                break;
            case 'png':
                imagepng($resized_image, null, (int)($quality / 10 - 1));
                break;
            case 'gif':
                imagegif($resized_image, null);
                break;
        }
        $imageData = ob_get_clean();
        $fileSize = strlen($imageData);
    }

    // Save the resized image to a temporary file
    $temp_file = tempnam(sys_get_temp_dir(), 'img');
    file_put_contents($temp_file, $imageData);

    // Initialize cURL session for file upload
    $curl_upload = curl_init();

    // Set cURL options for file upload
    curl_setopt_array($curl_upload, array(
        CURLOPT_URL => "ftp://$ftp_server/$file_path",
        CURLOPT_USERPWD => "$ftp_username:$ftp_password",
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_UPLOAD => true,
        CURLOPT_INFILE => fopen($temp_file, 'r'),
        CURLOPT_INFILESIZE => filesize($temp_file),
    ));

    // Execute cURL session for file upload
    ob_start(); // Start output buffering
    $response_upload = curl_exec($curl_upload);
    ob_end_clean(); // Discard output buffer

    // Check for errors in file upload
    if ($response_upload === false) {
        $error_message_upload = curl_error($curl_upload);
        die("Failed to upload image file: $error_message_upload");
    }

    // Close cURL sessions
    curl_close($curl);
    curl_close($curl_upload);

    // Clean up temporary files
    imagedestroy($image);
    imagedestroy($resized_image);
    unlink($temp_file);

    // Construct the full URL of the uploaded file
    $base_url = "https://mounarchtech.com/central_wp/verification_data/voco_xp/$agency_id/$direct_id/$visitor_id/user_photo";
    $emp_imageURL = $base_url . '/' . $file_name. '.' . $ext;

    // Return the URL of the uploaded file
    return $emp_imageURL;
}

  
?>