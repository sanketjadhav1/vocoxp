<?php
/* 
Name : json for terms and conddition
Mode :- single mode
Developed By - Ajit Bodkhe
*/


header('Content-Type: application/json; charset=UTF-8');
header('Access-Contorl-Allow-Origin: *');
date_default_timezone_set('Asia/kolkata');
include 'connection.php';
$connection = connection::getInstance();
$mysqli = $connection->getConnection();
apponoff($mysqli);
date_default_timezone_set('Asia/kolkata');
$system_date = date("y-m-d");
$system_date_time = date("Y-m-d H:i:s");


if ($_SERVER['REQUEST_METHOD'] != "POST") {
    $response[] = array("error_code" => 102, "message" => "Request Method Is not Post");
    echo json_encode($response);
    exit;
}

$agency_id = $_POST['agency_id'];
$process_id = $_POST['process_ids'];


$common_check = common_check_error($agency_id, $process_id, $mysqli);

if ($common_check == 1) {
    $contract_accepted_on = $system_date_time;
    $process_id_contract_id = explode(',', $process_id);
    $new_arr = array();



    $agency_query = "SELECT `agency_id` FROM `agency_header_all` where `agency_id`='$agency_id'";
    $agency_result = $mysqli->query($agency_query);
    $agency_row = mysqli_num_rows($agency_result);

    if ($agency_row != 1) {
        $response[] = array("error_code" => 117, "message" => "Invalid agency_id");
        echo json_encode($response);
        exit;
    }




    foreach ($process_id_contract_id as $string) {

        $parts = explode('@', $string);
        // print_r($string);

        if (count($parts) == 2) {


            $new_process_id = $parts[0];
            $contract_id = $parts[1];

            $check_query = "SELECT `agency_id`, `contract_id` FROM `member_contract_combination_all` WHERE `agency_id`='$agency_id' AND `contract_id`='$contract_id'";
            $check_res = $mysqli->query($check_query);
            $check_row = mysqli_num_rows($check_res);

            if ($check_row == 0) {
                $add_contrct_query = "INSERT INTO `member_contract_combination_all`(`agency_id`, `contract_id`, `contract_accepted_on`, `acceptance_no`) VALUES('$agency_id', '$contract_id', '$contract_accepted_on', '1')";
                $contract_result = $mysqli->query($add_contrct_query);
            }


        }
    }

    if ($contract_result == true) {
        $response[] = array("error_code" => 100, "message" => "contract accepted successfully");
        echo json_encode($response);
        exit;
    } else {
        $response[] = array("error_code" => 100, "message" => "contract accepted successfull");
        echo json_encode($response);
        exit;
    }






}


function common_check_error($agency_id, $process_id, $mysqli)
{
    $common_check = 1;


    if (!isset($agency_id)) {
        $response[] = array("error_code" => 107, "message" => "Please pass the parameter of agency_id");
        echo json_encode($response);
        exit;
    }
    if ($agency_id == "") {
        $response[] = array("error_code" => 108, "message" => "value of agency_id  can not be empty");
        echo json_encode($response);
        exit;
    }

    if (!isset($process_id)) {
        $response[] = array("error_code" => 111, "message" => "Please pass the parameter of process_id");
        echo json_encode($response);
        exit;
    }
    if ($process_id == "") {
        $response[] = array("error_code" => 112, "message" => "value of process_id  can not be empty");
        echo json_encode($response);
        exit;
    }
    if (!$mysqli) {
        $response[] = ["error_code" => 103, "message" => "Unable to proceed your request, please try again after some time"];
        echo json_encode($response);
        return;
    }

    return $common_check;
}


?>