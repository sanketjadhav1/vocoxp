<?php
error_reporting(0);
// use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\Exception;

// require 'PHPMailer/src/Exception.php';
// require 'PHPMailer/src/PHPMailer.php';
// require 'PHPMailer/src/SMTP.php';

include __DIR__ . '/vendor/autoload.php';
include_once 'connection.php';
error_reporting(E_ALL & ~E_DEPRECATED);
ini_set('display_errors', 1);

// Initialize database connections
$connection = connection::getInstance();
$mysqli = $connection->getConnection();

$connection1 = database::getInstance();
$mysqli1 = $connection1->getConnection();

$system_date = date("Y-m-d H:i:s");

// Collect input data
$meeting_with = $_POST['meeting_with'] ?? "";
// echo json_encode($meeting_with);die;
$verification_type = $_POST['verification_type'] ?? "";  
$agency_id = $_POST['agency_id'] ?? "";  
$visitor_location_id = $_POST['visitor_location_id'] ?? "";  
$requested_on = $system_date;  
$mode = $_POST['mode'] ?? "";  
$first_name = $_POST['first_name'] ?? "";  
$last_name = $_POST['last_name'] ?? "";  
$father_name = $_POST['father_name'] ?? "";  
$gender = $_POST['gender'] ?? "";  
$dob = $_POST['dob'] ?? "";  
 
if($dob=="")
{
    $dob="";
}
else
{
    $dob = DateTime::createFromFormat('d/m/Y', $dob);
    $dob = $dob->format('Y/m/d');
}

$blood_group = $_POST['blood_group'] ?? "";  
$address = $_POST['address'] ?? "";  
$front_photo = $_FILES['front_photo'] ?? "";  
$back_photo = $_FILES['back_photo'] ?? "";  
$user_photo = $_FILES['user_photo'] ?? "";  
$cover_photo = $_FILES['cover_photo'] ?? ""; 
$admin_id = $_POST['admin_id'] ?? "";   
$visitor_name = $_POST['visitor_name'] ?? "";   
$mobile_no = $_POST['mobile_no'] ?? "";   
$visitor_email = $_POST['visitor_email'] ?? "";   
$visitor_id = unique_id_genrate('VIS', 'visitor_temp_activity_detail_all', $mysqli);  
$purpose = $_POST['purpose'] ?? "";   
$people_with_visitor = $_POST['people_with_visitor'] ?? "";   

// Save documents if they are provided
if ($user_photo != "") {
    $user_img = save_doc_photo($user_photo, $meeting_with, $agency_id);
}
else
{
    $user_img="";
}
if ($front_photo != "") {
    $front_img = save_doc_photo($front_photo, $meeting_with, $agency_id);
}
else
{
    $front_img="";
}
if ($back_photo != "") {
    $back_img = save_doc_photo($back_photo, $meeting_with, $agency_id);
}
else
{
    $back_img="";
}
if ($cover_photo != "") {
    $cover_img = save_doc_photo($cover_photo, $meeting_with, $agency_id);
}
else
{
    $cover_img="";
}

// Check for errors
$check_error = check_error($mysqli, $mysqli1, $agency_id, $verification_type, $meeting_with);
if ($check_error == 1) {

    if ($verification_type == "DVF-00001") {
        // Aadhar data
        $aadhar_number = $_POST['aadhar_number'] ?? "";  
        if (isValidAadhar($aadhar_number) == 1) {
            $query = "INSERT INTO `visitor_temp_activity_detail_all`(`agency_id`, `visitor_id`, `visitor_location_id`, `requested_on`, `verification_type`, `mode`, `meeting_with`, `meeting_status`, `request_link_url`, `sms_status`, `email_status`, `approved_on`, `rejected_on`, `final_status`, `first_name`, `last_name`, `aadhar_number`, `pan_number`, `dl_number`, `voter_number`, `passport_number`, `father_name`, `mother_name`, `spouse_name`, `gender`, `dob`, `blood_group`, `address`, `country_code`, `state_name`, `nationality`, `front_photo`, `back_photo`, `user_photo`, `cover_photo`, `date_of_issue`, `date_of_expiry`, `place_of_issue`, `classes_of_vehicle`, `polling_details`, `republic_of_india`, `passport_type`, `file_number`, `visitor_name`, `visitor_email`, `people_with_visitor`,`visitor_mobile`) VALUES ('$agency_id','$visitor_id','$visitor_location_id','$requested_on','$verification_type','$mode','$meeting_with','1','','','','','','','$first_name','$last_name','$aadhar_number','','','','','','','','$gender','$dob','','$address','','','','$front_img','$back_img','$user_img','','','','','','','','','','$visitor_name','$visitor_email','$people_with_visitor','$mobile_no')";
            $res = mysqli_query($mysqli, $query);
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
             if($row_emp["verification_paid_by"]=='W')
            {
                $verification_paid_by="W";
            }
            else
            {
                $verification_paid_by="E";

            }
            $data[]=["visitor_id"=>$visitor_id,"visitor_name"=>$visitor_name,"employee_id"=>$meeting_with,"visitor_mobile"=>$mobile_no,"visitor_photo"=>$user_img,"visitor_approval_required"=>$visitor_approval_required,"visiting_charges"=>$visiting_charges,"verification_paid_by"=>$verification_paid_by];
            $response = array("error_code" => 100, "message" => "Visitor  record successfully added.","data"=>$data);
            echo json_encode($response);
            return;
        } else {
            $response = array("error_code" => 199, "message" => "Aadhaar number is invalid. Please provide a valid Aadhaar number");
            echo json_encode($response);
            return;
        }

    } elseif ($verification_type == "DVF-00002") {
        // PAN data
        $pan_number = $_POST['pan_number'] ?? "";  
        if (isValidPAN($pan_number) == 1) {
            $query = "INSERT INTO `visitor_temp_activity_detail_all`(`agency_id`, `visitor_id`, `visitor_location_id`, `requested_on`, `verification_type`, `mode`, `meeting_with`, `meeting_status`, `request_link_url`, `sms_status`, `email_status`, `approved_on`, `rejected_on`, `final_status`, `first_name`, `last_name`, `aadhar_number`, `pan_number`, `dl_number`, `voter_number`, `passport_number`, `father_name`, `mother_name`, `spouse_name`, `gender`, `dob`, `blood_group`, `address`, `country_code`, `state_name`, `nationality`, `front_photo`, `back_photo`, `user_photo`, `cover_photo`, `date_of_issue`, `date_of_expiry`, `place_of_issue`, `classes_of_vehicle`, `polling_details`, `republic_of_india`, `passport_type`, `file_number`, `visitor_name`, `visitor_email`, `people_with_visitor`,`visitor_mobile`) VALUES ('$agency_id','$visitor_id','$visitor_location_id','$requested_on','$verification_type','$mode','$meeting_with','1','','','','','','','$first_name','$last_name','','$pan_number','','','' ,'$father_name','','','','$dob','','$address','','','','$front_img','','$user_img','','','','','','','','','','$visitor_name','$visitor_email','$people_with_visitor','$mobile_no')";
            $res = mysqli_query($mysqli, $query);
            $res = mysqli_query($mysqli, $query);

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
             if($row_emp["verification_paid_by"]=='W')
            {
                $verification_paid_by="W";
            }
            else
            {
                $verification_paid_by="E";

            }
            $data[]=["visitor_id"=>$visitor_id,"visitor_name"=>$visitor_name,"employee_id"=>$meeting_with,"visitor_mobile"=>$mobile_no,"visitor_photo"=>$user_img,"visitor_approval_required"=>$visitor_approval_required,"visiting_charges"=>$visiting_charges,"verification_paid_by"=>$verification_paid_by];
            $response = array("error_code" => 100, "message" => "Visitor  record successfully added.","data"=>$data);
            echo json_encode($response);
            return;
        } else {
            $response = array("error_code" => 199, "message" => "PAN number is invalid. Please provide a valid PAN number");
            echo json_encode($response);
            return;
        }

    } elseif ($verification_type == "DVF-00004") {
        // DL data
        $dl_number = $_POST['dl_number'] ?? "";
        if (isValidDl($dl_number) == 1) {
             $date_of_issue = $_POST['date_of_issue'] ?? "";
             $date_of_issue = DateTime::createFromFormat('d/m/Y', $date_of_issue);
             $date_of_issue = $date_of_issue->format('Y/m/d');

            $date_of_expiry = $_POST['date_of_expiry'] ?? "";  
              $date_of_expiry = DateTime::createFromFormat('d/m/Y', $date_of_expiry);
             $date_of_expiry = $date_of_expiry->format('Y/m/d');
            $classes_of_vehicle = $_POST['classes_of_vehicle'] ?? "";   
            $state_name = $_POST['state_name'] ?? "";  
            $query = "INSERT INTO `visitor_temp_activity_detail_all`(`agency_id`, `visitor_id`, `visitor_location_id`, `requested_on`, `verification_type`, `mode`, `meeting_with`, `meeting_status`, `request_link_url`, `sms_status`, `email_status`, `approved_on`, `rejected_on`, `final_status`, `first_name`, `last_name`, `aadhar_number`, `pan_number`, `dl_number`, `voter_number`, `passport_number`, `father_name`, `mother_name`, `spouse_name`, `gender`, `dob`, `blood_group`, `address`, `country_code`, `state_name`, `nationality`, `front_photo`, `back_photo`, `user_photo`, `cover_photo`, `date_of_issue`, `date_of_expiry`, `place_of_issue`, `classes_of_vehicle`, `polling_details`, `republic_of_india`, `passport_type`, `file_number`, `visitor_name`, `visitor_email`, `people_with_visitor`,`visitor_mobile`) VALUES ('$agency_id','$visitor_id','$visitor_location_id','$requested_on','$verification_type','$mode','$meeting_with','1','','','','','','','$first_name','$last_name','','','$dl_number','','','$father_name','','','','$dob','','$address','','$state_name','','$front_img','','$user_img','','$date_of_issue','$date_of_expiry','','$classes_of_vehicle','','','','','$visitor_name','$visitor_email','$people_with_visitor','$mobile_no')";
            $res = mysqli_query($mysqli, $query);
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
             if($row_emp["verification_paid_by"]=='W')
            {
                $verification_paid_by="W";
            }
            else
            {
                $verification_paid_by="E";

            }
            $data[]=["visitor_id"=>$visitor_id,"visitor_name"=>$visitor_name,"employee_id"=>$meeting_with,"visitor_mobile"=>$mobile_no,"visitor_photo"=>$user_img,"visitor_approval_required"=>$visitor_approval_required,"visiting_charges"=>$visiting_charges,"verification_paid_by"=>$verification_paid_by];
            $response = array("error_code" => 100, "message" => "Visitor  record successfully added.","data"=>$data);
            echo json_encode($response);
            return;
        } else {
            $response = array("error_code" => 199, "message" => "DL number is invalid. Please provide a valid DL number");
            echo json_encode($response);
            return;
        }

    } elseif ($verification_type == "DVF-00005") {
        
        // Voter data
        $voter_number = $_POST['voter_number'] ?? "";  
        if (isValidVoter($voter_number) == 1) {
            $polling_details = $_POST['polling_details'] ?? "";  
            $query = "INSERT INTO `visitor_temp_activity_detail_all`(`agency_id`, `visitor_id`, `visitor_location_id`, `requested_on`, `verification_type`, `mode`, `meeting_with`, `meeting_status`, `request_link_url`, `sms_status`, `email_status`, `approved_on`, `rejected_on`, `final_status`, `first_name`, `last_name`, `aadhar_number`, `pan_number`, `dl_number`, `voter_number`, `passport_number`, `father_name`, `mother_name`, `spouse_name`, `gender`, `dob`, `blood_group`, `address`, `country_code`, `state_name`, `nationality`, `front_photo`, `back_photo`, `user_photo`, `cover_photo`, `date_of_issue`, `date_of_expiry`, `place_of_issue`, `classes_of_vehicle`, `polling_details`, `republic_of_india`, `passport_type`, `file_number`, `visitor_name`, `visitor_email`, `people_with_visitor`,`visitor_mobile`) VALUES ('$agency_id','$visitor_id','$visitor_location_id','$requested_on','$verification_type','$mode','$meeting_with','1','','','','','','','$first_name','$last_name','','','','$voter_number','','$father_name','','','','$dob','','$address','','$state_name','','$front_img','$back_img','$user_img','','','','','','','','','','$visitor_name','$visitor_email','$people_with_visitor','$mobile_no')";
            $res = mysqli_query($mysqli, $query);
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
             if($row_emp["verification_paid_by"]=='W')
            {
                $verification_paid_by="W";
            }
            else
            {
                $verification_paid_by="E";

            }
            $data[]=["visitor_id"=>$visitor_id,"visitor_name"=>$visitor_name,"employee_id"=>$meeting_with,"visitor_mobile"=>$mobile_no,"visitor_photo"=>$user_img,"visitor_approval_required"=>$visitor_approval_required,"visiting_charges"=>$visiting_charges,"verification_paid_by"=>$verification_paid_by];
            $response = array("error_code" => 100, "message" => "Visitor  record successfully added.","data"=>$data);
            echo json_encode($response);
            return;
        } else {
            $response = array("error_code" => 199, "message" => "Voter number is invalid. Please provide a valid Voter ID");
            echo json_encode($response);
            return;
        }

    } 
    elseif($verification_type == "DVF-00006") {
        // Passport data
        $passport_number = $_POST['passport_number'] ?? "";
        $passport_type = $_POST['passport_type'] ?? "";  
        $file_number = $_POST['file_number'] ?? ""; 
        $place_of_issue = $_POST['place_of_issue'] ?? ""; 
        $country_code = $_POST['country_code'] ?? "";  
        $nationality = $_POST['nationality'] ?? "";  
        $republic_of_india = $_POST['republic_of_india'] ?? "";  
        $mother_name = $_POST['mother_name'] ?? "";  
        $spouse_name = $_POST['spouse_name'] ?? ""; 
         $date_of_issue = $_POST['date_of_issue'] ?? "";
         $date_of_issue = DateTime::createFromFormat('d/m/Y', $date_of_issue);
         $date_of_issue = $date_of_issue->format('Y/m/d');
         $date_of_expiry = $_POST['date_of_expiry'] ?? "";
         $date_of_expiry = DateTime::createFromFormat('d/m/Y', $date_of_expiry);
         $date_of_expiry = $date_of_expiry->format('Y/m/d');
         $query = "INSERT INTO `visitor_temp_activity_detail_all`(`agency_id`, `visitor_id`, `visitor_location_id`, `requested_on`, `verification_type`, `mode`, `meeting_with`, `meeting_status`, `request_link_url`, `sms_status`, `email_status`, `approved_on`, `rejected_on`, `final_status`, `first_name`, `last_name`, `aadhar_number`, `pan_number`, `dl_number`, `voter_number`, `passport_number`, `father_name`, `mother_name`, `spouse_name`, `gender`, `dob`, `blood_group`, `address`, `country_code`, `state_name`, `nationality`, `front_photo`, `back_photo`, `user_photo`, `cover_photo`, `date_of_issue`, `date_of_expiry`, `place_of_issue`, `classes_of_vehicle`, `polling_details`, `republic_of_india`, `passport_type`, `file_number`, `visitor_name`, `visitor_email`, `people_with_visitor`,`visitor_mobile`) VALUES ('$agency_id','$visitor_id','$visitor_location_id','$requested_on','$verification_type','$mode','$meeting_with','1','','','','','','','$first_name','$last_name','','','','','$passport_number','$father_name','$mother_name','$spouse_name','$gender','$dob','','$address','$country_code','','$nationality','$front_img','$back_img','$user_img','$cover_img','$date_of_issue','$date_of_expiry','$place_of_issue','','','$republic_of_india','$passport_type','$file_number','$visitor_name','$visitor_email','$people_with_visitor','$mobile_no')";  
        
        $res = mysqli_query($mysqli, $query);
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
             if($row_emp["verification_paid_by"]=='W')
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
    elseif ($verification_type == "DVF-00007") {
        // International Passport data
        $passport_number = $_POST['passport_number'] ?? "";
        $passport_type = $_POST['passport_type'] ?? "";  
        $file_number = $_POST['file_number'] ?? ""; 
        $place_of_issue = $_POST['place_of_issue'] ?? ""; 
        $country_code = $_POST['country_code'] ?? "";  
        $nationality = $_POST['nationality'] ?? "";  
        $republic_of_india = $_POST['republic_of_india'] ?? "";  
        $mother_name = $_POST['mother_name'] ?? "";  
        $spouse_name = $_POST['spouse_name'] ?? ""; 
         $date_of_issue = $_POST['date_of_issue'] ?? "";
         $date_of_issue = DateTime::createFromFormat('d/m/Y', $date_of_issue);
         $date_of_issue = $date_of_issue->format('Y/m/d');
         $date_of_expiry = $_POST['date_of_expiry'] ?? "";
         $date_of_expiry = DateTime::createFromFormat('d/m/Y', $date_of_expiry);
         $date_of_expiry = $date_of_expiry->format('Y/m/d');
         $visa_photo= $_FILES['visa_photo']?? "";
         $visa_photo_url = "";
         if (!empty($visa_photo)) {
             $visa_photo_url = save_doc_photo($visa_photo, $meeting_with, $agency_id);
         }
        
        else
        {
            $front_img="";
        }
        $country=$_POST['country']?? "";
        $visa_type = $_POST['visa_type']?? "";
        $visa_expiry_date = $_POST['visa_expiry_date'] ?? "";
        if (!empty($visa_expiry_date)) {
            $visa_expiry_date = DateTime::createFromFormat('d/m/Y', $visa_expiry_date);
            if ($visa_expiry_date) {
                $visa_expiry_date = $visa_expiry_date->format('Y-m-d');
            } else {
                // Handle invalid date format
                $visa_expiry_date = "";
            }
        }
        
        $person_arrival_date = $_POST['person_arrival_date'] ?? "";
        if (!empty($person_arrival_date)) {
            $person_arrival_date = DateTime::createFromFormat('d/m/Y', $person_arrival_date);
            if ($person_arrival_date) {
                $person_arrival_date = $person_arrival_date->format('Y-m-d');
            } else {
                // Handle invalid date format
                $person_arrival_date = "";
            }
        }
        
        $query = "INSERT INTO `visitor_temp_activity_detail_all`(
            `agency_id`, `visitor_id`, `visitor_location_id`, `requested_on`, 
            `verification_type`, `mode`, `meeting_with`, `meeting_status`, 
            `request_link_url`, `sms_status`, `email_status`, `approved_on`, 
            `rejected_on`, `final_status`, `first_name`, `last_name`, 
            `aadhar_number`, `pan_number`, `dl_number`, `voter_number`, 
            `passport_number`, `father_name`, `mother_name`, `spouse_name`, 
            `gender`, `dob`, `blood_group`, `address`, `country_code`, `state_name`, 
            `nationality`, `front_photo`, `back_photo`, `user_photo`, `cover_photo`, 
            `date_of_issue`, `date_of_expiry`, `place_of_issue`, `classes_of_vehicle`, 
            `polling_details`, `republic_of_india`, `passport_type`, `file_number`, 
            `visitor_name`, `visitor_email`, `people_with_visitor`, `visitor_mobile`, 
            `visa_photo`, `country`, `visa_type`, `visa_expiry_date`, `person_arrival_date`) 
        VALUES (
            '$agency_id', '$visitor_id', '$visitor_location_id', '$requested_on', 
            '$verification_type', '$mode', '$meeting_with', '1', '', '', '', '', '', '', 
            '$first_name', '$last_name', '', '', '', '', '$passport_number', 
            '$father_name', '$mother_name', '$spouse_name', '$gender', '$dob', '', 
            '$address', '$country_code', '', '$nationality', '$front_img', '$back_img', 
            '$user_img', '$cover_img', '$date_of_issue', '$date_of_expiry', 
            '$place_of_issue', '', '', '$republic_of_india', '$passport_type', 
            '$file_number', '$visitor_name', '$visitor_email', '$people_with_visitor', 
            '$mobile_no', '$visa_photo_url', '$country', '$visa_type', '$visa_expiry_date', 
            '$person_arrival_date')";
          
        
        
        $res = mysqli_query($mysqli, $query);
    
        if ($res) {
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
             if($row_emp["verification_paid_by"]=='W')
            {
                $verification_paid_by="W";
            }
            else
            {
                $verification_paid_by="E";

            }
            $data[]=["visitor_id"=>$visitor_id,"visitor_name"=>$visitor_name,"employee_id"=>$meeting_with,"visitor_mobile"=>$mobile_no,"visitor_photo"=>$user_img,"visitor_approval_required"=>$visitor_approval_required,"visiting_charges"=>$visiting_charges,"verification_paid_by"=>$verification_paid_by];
            $response = array("error_code" => 100, "message" => "Visitor added successfully.", "data" => $data);
        } else {
            $response = array("error_code" => 500, "message" => "Failed to add data.");
        }
    
        echo json_encode($response);
        return;
    }
    elseif($verification_type == "MVF-00000"){
        $query = "INSERT INTO `visitor_temp_activity_detail_all`(`agency_id`, `visitor_id`, `visitor_location_id`, `requested_on`, `verification_type`, `mode`, `meeting_with`, `first_name`, `last_name`, `father_name`, `gender`, `dob`, `blood_group`, `address`, `user_photo`, `visitor_name`, `visitor_email`, `people_with_visitor`, `visitor_mobile`) 
              VALUES ('$agency_id','$visitor_id','$visitor_location_id','$requested_on','$verification_type','$mode','$meeting_with','$first_name','$last_name','$father_name','$gender','$dob','$blood_group','$address','$user_img','$visitor_name','$visitor_email','$people_with_visitor','$mobile_no')";

    $res = mysqli_query($mysqli, $query);

    // Prepare the response
    if ($res) {
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
             if($row_emp["verification_paid_by"]=='W')
            {
                $verification_paid_by="W";
            }
            else
            {
                $verification_paid_by="E";

            }
            $data[]=["visitor_id"=>$visitor_id,"visitor_name"=>$visitor_name,"employee_id"=>$meeting_with,"visitor_mobile"=>$mobile_no,"visitor_photo"=>$user_img,"visitor_approval_required"=>$visitor_approval_required,"visiting_charges"=>$visiting_charges,"verification_paid_by"=>$verification_paid_by];
        $response = array("error_code" => 100, "message" => "Visitor Manual record successfully added.", "data" => $data);
    } else {
        $response = array("error_code" => 101, "message" => "Failed to add visitor record.");
    }
    echo json_encode($response);
    return;
    }
    
    
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
function isValidVoter($pan)
{
    // Regular expression to match PAN format
    $pattern = '/^[A-Z]{3}[0-9]{7}$/';

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
function check_error($mysqli, $mysqli1, $agency_id,  $verification_type, $meeting_with) {
    if (!$mysqli || !$mysqli1) {
        $response = array("error_code" => 101, "message" => "An unexpected server error occurred while processing your request. Please try again later.");
        echo json_encode($response);
        return 0;
    }
    if ($_SERVER["REQUEST_METHOD"] != "POST") {
        $response = array("error_code" => 102, "message" => "Please change the request method to POST");
        echo json_encode($response);
        return 0;
    }
    if (!isset($agency_id) || empty($agency_id)) {
        $response = array("error_code" => 103, "message" => "The parameter 'agency_id' is required and cannot be empty");
        echo json_encode($response);
        return 0;
    }
    
    if (!isset($verification_type) || empty($verification_type)) {
        $response = array("error_code" => 107, "message" => "The parameter 'verification_type' is required and cannot be empty");
        echo json_encode($response);
        return 0;
    }
    if (!isset($meeting_with) || empty($meeting_with)) {
        $response = array("error_code" => 109, "message" => "The parameter 'meeting_with' is required and cannot be empty");
        echo json_encode($response);
        return 0;
    }
    
    return 1;
}
 function save_doc_photo($upload_file, $direct_id, $agency_id)
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
    $new_directory_path = "$agency_id/$direct_id/doc_photo/";

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
    $base_url = "https://mounarchtech.com/central_wp/verification_data/voco_xp/$agency_id/$direct_id/doc_photo";
    $emp_imageURL = $base_url .$file_name . '.' . $ext;

    // Return the URL of the uploaded file
    return $emp_imageURL;
}
 function save_user_photo($output_photo, $direct_id, $agency_id)
{
    // FTP server credentials
    $ftp_server = '199.79.62.21';
    $ftp_username = 'centralwp@mounarchtech.com';
    $ftp_password = 'k=Y#oBK{h}OU';

    // Remote directory path
    $remote_base_dir = "/verification_data/voco_xp/";

    // Nested directory structure to be created
    $new_directory_path = "$agency_id/$direct_id/user_photo/";

    // Initialize cURL session for FTP
    $curl = curl_init();

    // Set cURL options for creating missing directories
    curl_setopt_array($curl, array(
        CURLOPT_URL => "ftp://$ftp_server/$remote_base_dir$new_directory_path",
        CURLOPT_USERPWD => "$ftp_username:$ftp_password",
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_CUSTOMREQUEST => 'MKD', // Make Directory
    ));

    // Execute the cURL session to create the directory
    ob_start(); // Start output buffering
    $response_mkdir = curl_exec($curl);
    ob_end_clean(); // Discard output buffer

    // Check for errors in directory creation
    if ($response_mkdir === false && curl_errno($curl) != 550) {  // Ignore 'directory already exists' error
        $error_message_mkdir = curl_error($curl);
        curl_close($curl);
        die("Failed to create directory: $error_message_mkdir");
    }

    // Close the cURL session for directory creation
    curl_close($curl);

    // Generate a unique file name for the photo
    $file_name =  $direct_id . '.jpg';

    // Construct the full file path on the remote server
    $file_path = $remote_base_dir . $new_directory_path . $file_name;

    // Save the photo to a temporary file
    $temp_file = tempnam(sys_get_temp_dir(), 'jpg');
    file_put_contents($temp_file, $output_photo);

    // Initialize cURL session for file upload
    $curl_upload = curl_init();

    // Set cURL options for uploading the file
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
        curl_close($curl_upload);
        die("Failed to upload photo: $error_message_upload");
    }

    // Close cURL session after upload
    curl_close($curl_upload);

    // Remove the temporary file
    unlink($temp_file);

    // Construct and return the URL of the uploaded photo
    $base_url = "https://mounarchtech.com/centralwp/verification_data/voco_xp/$agency_id/$direct_id/user_photo";
    $path = $base_url . '/' . $file_name;
    return $path;
}


?>