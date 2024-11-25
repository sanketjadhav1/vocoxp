<?php
/* 
Name : json_for_info_version_control.php
Mode :- single mode
Developed By - Ajit Bodkhe
*/
error_reporting(1);
include_once 'connection.php';

// Now you can use the connection class
$connection = connection::getInstance();
$mysqli = $connection->getConnection();
$connection1 = database::getInstance();
$mysqli1 = $connection1->getConnection();
apponoff($mysqli);
// logout($_SERVER['HTTP_AUTH_KEY'], $mysqli);

if ($_SERVER['REQUEST_METHOD'] != "POST") {
    $response[] = array("error_code" => 102, "message" => "Request Method Is not Post");
    echo json_encode($response);
    exit;
}


$common_check=commoonCHK($mysqli, $_POST['os_type']);
if($common_check==1){

$os_type=$_POST['os_type'];


    $version_control_query="SELECT `version_name`, `version_code`, `uploaded_on_date`, `mandatory_from_date`, `remind_user_from_date`, `current_user_count`, `status`, `operating_system`, `version_type`, `required`, `playstore_updated_on`, `data_migration_on` FROM `version_control_details_all` WHERE `status`='0' AND `operating_system`='$os_type'";
    $version_control_res=$mysqli->query($version_control_query);
    $version_control_row=mysqli_num_rows($version_control_res);

if($version_control_row==1){
    $version_control_arr=mysqli_fetch_assoc($version_control_res);
    
    $version_control_arr['uploaded_on_date']=date("d-m-Y", strtotime($version_control_arr['uploaded_on_date']));

    $version_control_arr['mandatory_from_date']=date("d-m-Y", strtotime($version_control_arr['mandatory_from_date']));
    
    $version_control_arr['remind_user_from_date']=date("d-m-Y", strtotime($version_control_arr['remind_user_from_date']));

    $version_control_arr['data_migration_on']=date("d-m-Y", strtotime($version_control_arr['data_migration_on']));

    $version_control_arr['version_name']=$version_control_arr['version_name'].$version_control_arr['version_code'];
    
}else{
    $version_control_array = array();
  while ($arr = mysqli_fetch_assoc($version_control_res)) {
    $arr['uploaded_on_date'] = date("d-m-Y", strtotime($arr['uploaded_on_date']));
    $arr['mandatory_from_date'] = date("d-m-Y", strtotime($arr['mandatory_from_date']));
    $arr['remind_user_from_date'] = date("d-m-Y", strtotime($arr['remind_user_from_date']));
    $arr['data_migration_on'] = date("d-m-Y", strtotime($arr['data_migration_on']));
    $arr['version_name'] = $arr['version_name'] . $arr['version_code'];
    $version_control_array[] = $arr;
  }
    $version_control_arr = array_slice($version_control_array, -1)[0];
}

    $data[]=$version_control_arr;
    $response[] = array("error_code" => 100, "message" => "Success", "data"=>$data);
    echo json_encode($response);
    exit;
}

function commoonCHK($mysqli, $os_type){
    $common_check=1;

if(!isset($os_type))
{
  	$response[]=["error_code"=>107,"message"=>"'os_type' parameter is missing"];
		echo json_encode($response);
		exit;
}
if($os_type=='')
{
   $response[]=["error_code"=>108,"message"=>"'os_type' parameter is empty"];
		echo json_encode($response);
		exit;
}
if($os_type!="0" && $os_type!="1")
{
   $response[]=["error_code"=>109,"message"=>"'os_type' type 'android=0' or 'ios=1'"];
		echo json_encode($response);
		exit;
}

    if (!$mysqli) {
        $response[] = array(
            "error_code" => 101,
            "message" => "An unexpected server error occurred while
            processing your request. Please try after sometime"
        );
        echo json_encode($response);
        exit;

    }
    return $common_check;
}

?>
