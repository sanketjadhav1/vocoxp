<?php
include 'connection.php';
$connection = connection::getInstance();
$mysqli = $connection->getConnection();

$connection1 = database::getInstance();
$mysqli1 = $connection1->getConnection();

$check_error=check_error($mysqli, $mysqli1, $_POST['agency_id'], $_POST['application_id']);
if($check_error==1){

$agency_id=$_POST['agency_id'];
$application_id=$_POST['application_id'];

$fetch_agency="SELECT `agency_id`, `current_wallet_bal` FROM `agency_header_all` WHERE `agency_id`='$agency_id'";
$res_agency=mysqli_query($mysqli, $fetch_agency);
$arr_agency=mysqli_fetch_assoc($res_agency);
if(mysqli_num_rows($res_agency)==0){
    $response[] = ["error_code" => 107, "message" => "agency_id is not valid. Please enter correct agency_id."];
    echo json_encode($response);
    return;
}

$fetch_application="SELECT `application_id` FROM `application_header_all` WHERE `application_name`='VOCOxP'";
$res_application=mysqli_query($mysqli1, $fetch_application);
if(mysqli_num_rows($res_application)==0){
    $response[] = ["error_code" => 108, "message" => "application_id is not valid. Please enter correct application_id."];
    echo json_encode($response);
    return;
}

$fetch_verification="SELECT `verification_configuration_all`.`verification_id`, `verification_configuration_all`.`rate`, `verification_configuration_all`.`sgst_percentage`, `verification_configuration_all`.`cgst_percentage`, `verification_header_all`.`name`, `verification_header_all`.`image`, `verification_header_all`.`status` FROM `verification_configuration_all` INNER JOIN `verification_header_all` ON `verification_header_all`.`verification_id`=`verification_configuration_all`.`verification_id` WHERE `verification_configuration_all`.`operational_status`=1 AND `verification_header_all`.`status`=1 AND `verification_configuration_all`.`ver_type`=1";
$res_verification=mysqli_query($mysqli1, $fetch_verification);

while($arr=mysqli_fetch_assoc($res_verification)){
    if($arr['name']!="Criminal Record Verification"){
        $data[]=[
            "verification_name"=>$arr['name'],
            "image"=>$arr['image'],
            "status"=>$arr['status'],
            "verification_id"=>$arr['verification_id'],
            "price"=>$arr['rate'],
            "sgst_per"=>$arr['sgst_percentage'],
            "cgst_per"=>$arr['cgst_percentage']
    
        ];
    }
}

$data[]=[
    "verification_name"=>'International Passport',
    "image"=>'https://firebasestorage.googleapis.com/v0/b/vocoxp.appspot.com/o/_a055b620-b923-4f99-9eb0-d38bf65f9498.jpeg?alt=media&token=4418550a-891f-4033-904c-94f2a1a3b4d6',
    "status"=>'1',
    "verification_id"=>'DVF-00007',
    "price"=>'20',
    "sgst_per"=>'9',
    "cgst_per"=>'9'

];
$data[]=[
    "verification_name"=>'Individual Weblink',
    "image"=>'https://mounarchtech.com/vocoxp/web_link_logo.png',
    "status"=>'1',
    "verification_id"=>'DVF-00008',
    "price"=>'20',
    "sgst_per"=>'9',
    "cgst_per"=>'9'

];
$response[]=["error_code"=>100, "message"=>"Data fetch", "data"=>$data, "current_wallet_bal"=>$arr_agency['current_wallet_bal']];
    echo json_encode($response);
    return;

}


function check_error($mysqli, $mysqli1, $agency_id, $application_id){
    $check_error=1;

    if (!$mysqli) {
        $response[] = ["error_code" => 101, "message" => "Unable to proceed your request, please try again after some time"];
        echo json_encode($response);
        return;
    }
    if (!$mysqli1) {
        $response[] = ["error_code" => 101, "message" => "Unable to proceed your request, please try again after some time"];
        echo json_encode($response);
        return;
    }

    if ($_SERVER['REQUEST_METHOD'] != "POST") {
        $response[] = array("error_code" => 102, "message" => "Request Method is not Post");
        echo json_encode($response);
        return;
    }

    if(!isset($agency_id)){
        $response[] = ["error_code" => 103, "message" => "Please pass the parameter of agency_id"];
        echo json_encode($response);
        return;
    }
    if(empty($agency_id)){
        $response[] = ["error_code" => 104, "message" => "agency_id can not be empty"];
        echo json_encode($response);
        return;
    }
    if(!isset($application_id)){
        $response[] = ["error_code" => 105, "message" => "Please pass the parameter of application_id"];
        echo json_encode($response);
        return;
    }
    if(empty($application_id)){
        $response[] = ["error_code" => 106, "message" => "application_id can not be empty"];
        echo json_encode($response);
        return;
    }
    return $check_error;
}
?>