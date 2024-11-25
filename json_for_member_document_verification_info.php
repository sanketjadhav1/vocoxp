<?php
/* 
Name : json_for_member_document_verification_info.php
Version of the Requirment Document  : 2.0.0


Purpose :- This api is to use fetch json_for_member_document_verification_info details
Mode :- single mode

Developed By - Rishabh Shinde
// */
// error_reporting(1);
// error_reporting(E_ALL & ~E_DEPRECATED);
// ini_set('display_errors', 1);
include_once 'connection.php';

// Now you can use the connection class
$connection = connection::getInstance();
$mysqli = $connection->getConnection();

$connection1 = database::getInstance();
  $mysqli1 = $connection1->getConnection();
apponoff($mysqli);

logout($_SERVER['HTTP_AUTH_KEY'], $mysqli);
$check_error=check_error($mysqli, $_POST['agency_id'], $_POST['application_id'], $_POST['member_id'], $_POST['specification_id']);

if($check_error==1){

    $agency_id=$_POST['agency_id'];
     $application_id=$_POST['application_id'];
     $member_id=$_POST['member_id'];
     $specification_id=$_POST['specification_id'];
    
    $fetch_agency_id = "SELECT `agency_id`, `status` FROM `agency_header_all` WHERE `agency_id`='$agency_id'";
$result_agency = mysqli_query($mysqli, $fetch_agency_id);
$arr_agency = mysqli_fetch_assoc($result_agency);

if (!$arr_agency) {
    $response[] = array("error_code" => 102, "message" => "Invalid agency ID. Please provide a valid agency ID");
    echo json_encode($response);
    return;
} elseif ($arr_agency['status'] != "1") {
    $response[] = array("error_code" => 103, "message" => "The agency name is currently inactive");
    echo json_encode($response);
    return;
}


// Fetch member details
$fetch_member_id = "SELECT `member_id`, `name`, `profile_image`, `contact_no`, `designation`, `relation` FROM `member_header_all` WHERE `member_id`='$member_id'";
$result_member = mysqli_query($mysqli, $fetch_member_id);

if (!$result_member) {
    $response[] = array("error_code" => 104, "message" => "Error fetching member details");
    echo json_encode($response);
    return;
}

// Fetch verification specifications
$fetch_specification = "SELECT `table_name`, `verification_id`, `image`, `abbreviations`, `name` FROM `verification_header_all` WHERE `status`='1'";
$result_specification = mysqli_query($mysqli1, $fetch_specification);

$member_details = [];

// Loop through member details
while ($arr_member = mysqli_fetch_assoc($result_member)) {
    mysqli_data_seek($result_specification, 0); // Reset the pointer for the specification result set

    $documents_list = []; // Reset the documents_list array for each member

    $member_id = $arr_member['member_id'];

    // Loop through verification specifications
    // Loop through verification specifications
while ($arr_specification = mysqli_fetch_assoc($result_specification)) {
    // not empty mujawar khayum
    if($arr_specification['table_name']!="")
    {
        $table_name = $arr_specification['table_name'];
    }
    

    // Fetch the latest verification response for the current member and specification
       $fetch_table_name = "SELECT `verification_status`, `created_on`, `modified_on`, `request_id`, `verification_report` FROM $table_name WHERE `agency_id`='$agency_id' AND `application_id`='$application_id' AND `person_id`='$member_id' ";

    $result_response = mysqli_query($mysqli1, $fetch_table_name);
    
    if(mysqli_num_rows($result_response) > 1) {
        $latest_status_2 = null; // Initialize variable to hold the latest status 2 record
        $has_status_0 = false; // Flag to check if there's a record with status 0
    
        while($arr_response1 = mysqli_fetch_assoc($result_response)) {
             
            if($arr_response1['verification_status'] == 0) {
                $arr_response = $arr_response1; // Set arr_response to the status 0 record
                $has_status_0 = true; // Set flag to true
                break; // Exit loop once status 0 record is found
            } elseif($arr_response1['verification_status'] == 2) {
                // Check if latest_status_2 is null or the current record's modified_on is greater than the latest_status_2's modified_on
                if ($latest_status_2 === null || strtotime($arr_response1['modified_on']) > strtotime($latest_status_2['modified_on'])) {
                    $latest_status_2 = $arr_response1; // Update latest_status_2 with the current record
                }
            }
        }
        
        if (!$has_status_0 && $latest_status_2 !== null) {
            $arr_response = $latest_status_2; // Set arr_response to the latest status 2 record if no status 0 record is found
        }
    } else {
        $arr_response = mysqli_fetch_assoc($result_response);
    }
    $fetch_transaction="SELECT `otp_of_confirmation` FROM verification_payment_transaction_all WHERE `request_id`='".$arr_response['request_id']."'";
            $res_trans=mysqli_query($mysqli1, $fetch_transaction);
            $arr_trans=mysqli_fetch_assoc($res_trans);
    
    // Determine the status based on verification_status
    if (empty($arr_response)) {
        $arr_status = "Not Initiated";
        $last_time = "";
    } else {
        if($arr_response['verification_status'] == 1) {
            $arr_status = "Report Generating";
            $last_time = date('d-m-Y h:iA', strtotime($arr_response['modified_on']));
        } elseif($arr_response['verification_status'] == 2) {
            $arr_status = "Report Generated";
            $last_time = date('d-m-Y h:iA', strtotime($arr_response['modified_on']));
        } elseif($arr_response['verification_status'] == 0) {
            $arr_status = "Initiated";
            $last_time = date('d-m-Y h:iA', strtotime($arr_response['created_on']));
        }
    }
    

    // Date logic based on status
    

    // Add document details to the documents list
    $documents_list[] = [
        "doc_type_id" => replaceNullWithEmptyString($arr_specification['verification_id']),
        "image" => replaceNullWithEmptyString($arr_specification['image']),
        "abbrivation" => replaceNullWithEmptyString($arr_specification['abbreviations']),
        "name" => replaceNullWithEmptyString($arr_specification['name']),
        "status" => replaceNullWithEmptyString($arr_status),
        "type_id" => '',
        "request_id" => replaceNullWithEmptyString($arr_response['request_id']),
        "date" => replaceNullWithEmptyString($last_time),
        "user_consent" => $arr_trans['otp_of_confirmation']==1?"Yes":"No",
        "pdf_report_url" => replaceNullWithEmptyString($arr_response['verification_report']),
        "pdf_report_id" => ''
    ];
}


    // Add member details to the array
    $member_details[] = [
        "member_id" => replaceNullWithEmptyString($member_id),
        "name" => replaceNullWithEmptyString($arr_member['name']),
        "profile_url" => replaceNullWithEmptyString($arr_member['profile_image']),
        "contact_no" => replaceNullWithEmptyString($arr_member['contact_no']),
        "designation" => replaceNullWithEmptyString($arr_member['designation']),
        "relation" => replaceNullWithEmptyString($arr_member['relation']),
        "documents_list" => $documents_list
    ];
}

// Prepare the final response
$data[] = [
    "error_code" => 100,
    "message" => "Data fetch successfully",
    "member_details" => $member_details
];

echo json_encode($data);
return;


}


function replaceNullWithEmptyString($value) {
    return is_null($value) ? '' : $value;
}


function check_error($mysqli, $agency_id, $application_id, $member_id){

    $check_error=1;
    if(!$mysqli){
        $response[]=["error_code" => 101, "message" => "An unexpected server error occurred while
        processing your request. Please try after sometime"];
        echo json_encode($response);
        return;
    }
    if($_SERVER["REQUEST_METHOD"] != "POST"){
        $responce[] = array( "error_code" => 106, "message" => "please change request method to POST");
        echo json_encode($responce);
        return; 
    
            
    }
    if(!isset($agency_id)){
        $response[]=["error_code" => 107, "message" => "Please the parameter - agency_id"];
        echo json_encode($response);
        return;
    }else{
        if($agency_id==""){
            $response[]=["error_code" => 108, "message" => "Value of 'agency_id' cannot be empty"];
            echo json_encode($response);
            return;
        }
    }
    if(!isset($member_id)){
        $response[]=["error_code" => 109, "message" => "Please the parameter - member_id"];
        echo json_encode($response);
        return;
    }else{
        if($member_id==""){
            $response[]=["error_code" => 110, "message" => "Value of 'member_id' cannot be empty"];
            echo json_encode($response);
            return;
        }
    }
    // if(!isset($specification_id)){
    //     $response[]=["error_code" => 111, "message" => "Please the parameter - specification_id"];
    //     echo json_encode($response);
    //     return;
    // }else{
    //     if($specification_id==""){
    //         $response[]=["error_code" => 112, "message" => "Value of 'specification_id' cannot be empty"];
    //         echo json_encode($response);
    //         return;
    //     }
    // }
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
    return $check_error;
}
?>