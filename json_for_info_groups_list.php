<?php
/* 
Name : json_for_info_groups_list.php
Version of the Requirment Document  : 2.0.0


Purpose :- This api is use to fetch a list of groups in groups & messages
Mode :- single mode

Developed By - Rishabh Shinde
*/
// error_reporting(1);
include_once "connection.php";

$connection = connection::getInstance();
$mysqli = $connection->getConnection();


$connection1 = database::getInstance();
$mysqli1 = $connection1->getConnection();


$check_error=check_error($mysqli, $mysqli1, $_POST['agency_id'], $_POST['application_id']);

if($check_error==1){

    $agency_id=$_POST['agency_id'];     
     $application_id=$_POST['application_id'];
     
     
    
     $fetch_agency_id = "SELECT `agency_id`, status FROM `agency_header_all` WHERE `agency_id`='$agency_id'";
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

$fetch_groups = "SELECT `group_id`, `group_name`, `group_members` FROM `agency_groups_header_all` WHERE `agency_id`='$agency_id' AND `status`='1'";
$res_groups = mysqli_query($mysqli, $fetch_groups);

$data = [];

while ($arr_groups = mysqli_fetch_assoc($res_groups)) {
    $group_id = $arr_groups['group_id'];
    $group_name = $arr_groups['group_name'];

    // Extracting group members
    $mem_id = explode(",", $arr_groups['group_members']);
    $members = [];
    foreach ($mem_id as $mem) {
        $ids = explode("=", $mem);
        $member_id = trim($ids[0]);
        
        // Assuming $ids[1] contains the member's name or any relevant information
        $member_name = isset($ids[1]) ? trim($ids[1]) : "";
        $members[] = [
            "member_id" => $member_id,
            "member_name" => $member_name
        ];
    }

    $data[] = [
        "group_id" => $group_id,
        "group_name" => $group_name,
        "total_member" => count($members),
        "total_sos"=>2,
        "total_message"=>3
    ];
}
if(!empty($data)){
    $res[]=["error_code"=>100, "message"=>"Data fetch successfully", "data"=>$data];
    echo json_encode($res);
    return;
}else{
    $res[]=["error_code"=>100, "message"=>"Data not found"];
    echo json_encode($res);
    return;
}

}


function replaceNullWithEmptyString($value) {
    return is_null($value) ? '' : $value;
}


function check_error($mysqli, $mysqli1, $agency_id, $application_id){

    $check_error=1;
    if(!$mysqli){
        $response[]=["error_code" => 103, "message" => "An unexpected server error occurred while
        processing your request. Please try after sometime"];
        echo json_encode($response);
        return;
    }
    if(!$mysqli1){
        $response[]=["error_code" => 103, "message" => "An unexpected server error occurred while
        processing your request. Please try after sometime"];
        echo json_encode($response);
        return;
    }
    if($_SERVER["REQUEST_METHOD"] != "POST"){
        $responce[] = array( "error_code" => 105, "message" => "please change request method to POST");
        echo json_encode($responce);
        return; 
    
            
    }
    if(!isset($agency_id)){
        $response[]=["error_code" => 106, "message" => "Please the parameter - agency_id"];
        echo json_encode($response);
        return;
    }else{
        if($agency_id==""){
            $response[]=["error_code" => 107, "message" => "Value of 'agency_id' cannot be empty"];
            echo json_encode($response);
            return;
        }
    }

   
    if(!isset($application_id)){
        $response[]=["error_code" => 108, "message" => "Please the parameter - application_id"];
        echo json_encode($response);
        return;
    }else{
        if($application_id==""){
            $response[]=["error_code" => 109, "message" => "Value of 'application_id' cannot be empty"];
            echo json_encode($response);
            return;
        }elseif($application_id!="132"){
            $response[]=["error_code" => 110, "message" => "invalid application_id. Please enter valid application_id"];
            echo json_encode($response);
            return;
        }
    }
    
    return $check_error;
}
?>