<?php
/* 
Name : json_for_info_block_member.php
Version of the Requirment Document  : 2.0.0


Purpose :- This json will give the information of Block Members in the agency
Mode :- single mode

Developed By - Rishabh Shinde
*/
error_reporting(1);
include_once 'connection.php';

// Now you can use the connection class
$connection = connection::getInstance();
$mysqli = $connection->getConnection();
apponoff($mysqli);
logout($_SERVER['HTTP_AUTH_KEY'], $mysqli);
$check_error=check_error($mysqli, $_POST['agency_id']);

if($check_error==1){

    $agency_id = $_POST['agency_id'];
    // $agency_id = $_POST['type'];

    $fetch_agency_id = "SELECT `status` FROM `agency_header_all` WHERE `agency_id`='$agency_id'";
    $result_agency = mysqli_query($mysqli, $fetch_agency_id);
    $arr = mysqli_fetch_assoc($result_agency);
    
    if (!$arr) {
        $response = array("error_code" => 102, "message" => "Invalid agency ID. Please provide a valid agency ID");
        echo json_encode($response);
        return;
    } elseif ($arr['status'] != "1") {
        $response = array("error_code" => 103, "message" => "The agency name is currently inactive");
        echo json_encode($response);
        return;
    }
    
    $fetch_member = "SELECT `name`, `profile_image`, `contact_no`, `type`, `designation`, `relation`, `member_id` FROM `member_header_all` WHERE `registration_id`='$agency_id' AND `status`='0'";
    $result_member = mysqli_query($mysqli, $fetch_member);
    
    if (!$result_member) {
        $response = array("error_code" => 104, "message" => "Error fetching member data");
        echo json_encode($response);
        return;
    }
    
    $arr_agency = array(); // Initialize an array to hold member data
    
    while ($arr_member = mysqli_fetch_assoc($result_member)) {
        if($arr_member['type']==1){
            $arr_member['type']="member";
        }elseif($arr_member['type']==0){
            $arr_member['type']="employee";
        }
        $arr_agency[] = $arr_member;
       
    }
    if($arr_agency){
        $response[] = array("error_code" => 100, "message" => "Data fetched successfully", "data" => $arr_agency);
        echo json_encode($response);
        return;
    }else{
        $response[] = array("error_code" => 100, "message" => "Data not found");
        echo json_encode($response);
        return;
    }
    
}



function replaceNullWithEmptyString($value) {
    return is_null($value) ? '' : $value;
}


function check_error($mysqli, $agency_id){

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
   
    return $check_error;
}
?>