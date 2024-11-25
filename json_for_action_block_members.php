<?php

/* 
Name : json_for_action_block_members
Mode :- Multi mode
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


logout($_SERVER['HTTP_AUTH_KEY'], $mysqli);


if ($_SERVER['REQUEST_METHOD'] != "POST") {
    $response[] = array("error_code" => 102, "message" => "Request Method Is not Post");
    echo json_encode($response);
    exit;
}

$agency_id = $_POST['agency_id'];
$member_id = $_POST['member_id'];
$mode = $_POST['mode'];

$common_check = common_check_error1($agency_id, $member_id, $mode, $mysqli);

if ($common_check == 1) {

    $agency_query = "SELECT `status` FROM `agency_header_all` WHERE `agency_id`='$agency_id'";
    $agency_result = $mysqli->query($agency_query);
    $agency_num_rows = mysqli_num_rows($agency_result);
    $agency_array = mysqli_fetch_assoc($agency_result);

    if ($agency_num_rows != 1) {
        $response[] = array("error_code" => 103, "message" => "Invalid agency_id");
        echo json_encode($response);
        exit;

    }

    if ($agency_array['status'] != "1") {
        $response[] = array("error_code" => 104, "message" => "Inactive agency_id ");
        echo json_encode($response);
        exit;

    }

     $member_query = "SELECT `member_id` FROM `member_header_all` WHERE `member_id`='$member_id' AND `registration_id`='$agency_id'";
    $member_result = $mysqli->query($member_query);

    $member_num_rows = mysqli_num_rows($member_result);
    $member_array = mysqli_fetch_assoc($member_result);
// print_r($member_array);
    if (!$member_array) {
        $response[] = array("error_code" => 105, "message" => "Invalid member_id");
        echo json_encode($response);
        exit;

    }

    // if ($member_array['status'] != "Active") {
    //     $response[] = array("error_code" => 106, "message" => "Inactive member_id ");
    //     echo json_encode($response);
    // }


    if ($mode == "block") {

        $member_query = "UPDATE `member_header_all` 
        SET `status`='0' 
        WHERE `member_id`='$member_id' 
        AND `registration_id`='$agency_id'";

        $member_result = $mysqli->query($member_query);

        if ($member_result == true) {
            $response[] = array("error_code" => 100, "message" => "member block successfully");
            echo json_encode($response);
        } else {
            $response[] = array("error_code" => 100, "message" => "member block Unsuccessfully");
            echo json_encode($response);
        }

    }

    if ($mode == "unblock") {
        $member_query = "UPDATE `member_header_all` 
        SET `status`='1' 
        WHERE `member_id`='$member_id' 
        AND `registration_id`='$agency_id'";

        $member_result = $mysqli->query($member_query);

        if ($member_result == true) {
            $response[] = array("error_code" => 100, "message" => "member Unblock successfully");
            echo json_encode($response);
        } else {
            $response[] = array("error_code" => 100, "message" => "member Unblock Unsuccessfully");
            echo json_encode($response);
        }

    }




}

function common_check_error1($agency_id, $member_id, $mode, $mysqli)
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

    if (!isset($member_id)) {
        $response[] = array("error_code" => 109, "message" => "Please pass the parameter of member_id");
        echo json_encode($response);
        exit;

    }
    if ($member_id == "") {
        $response[] = array("error_code" => 110, "message" => "value of member_id  can not be empty");
        echo json_encode($response);
        exit;


    }
    if (!isset($mode)) {
        $response[] = array("error_code" => 111, "message" => "Please pass the parameter of mode");
        echo json_encode($response);
        exit;

    }
    if ($mode == "") {
        $response[] = array("error_code" => 112, "message" => "value of mode  can not be empty");
        echo json_encode($response);
        exit;

    }
    if ($mode != "block" && $mode != "unblock") {
        $response[] = array("error_code" => 113, "message" => "value of mode  is not correct");
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