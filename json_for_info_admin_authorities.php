<?php
/* 
Name : json for admin authority
Mode :- single mode
Developed By - Ajit Bodkhe
*/

// error_reporting(E_ALL & ~E_DEPRECATED);
// ini_set('display_errors', 1);

header('Content-Type: application/json; charset=UTF-8');
header('Access-Contorl-Allow-Origin: *');
date_default_timezone_set('Asia/kolkata');
include 'connection.php';
$connection = connection::getInstance();
$mysqli = $connection->getConnection();
apponoff($mysqli);
logout($_SERVER['HTTP_AUTH_KEY'], $mysqli);

date_default_timezone_set('Asia/kolkata');
$system_date = date("y-m-d");
$system_date_time = date("Y-m-d H:i:s");


if ($_SERVER['REQUEST_METHOD'] != "POST") {
    $response[] = array("error_code" => 102, "message" => "Request Method Is not Post");
    echo json_encode($response);
    exit;
}
$agency_id = $_POST['agency_id'];
$common_check = common_check_error($agency_id, $mysqli);

if ($common_check == 1) {
    // $admin_array = array();


    $check_agncy_id_query = "SELECT `status` FROM `agency_header_all` WHERE `agency_id`='$agency_id'";
    $check_agncy_id_res = $mysqli->query($check_agncy_id_query);
    $check_agncy_id_row = mysqli_num_rows($check_agncy_id_res);
    $check_agncy_id_arr = mysqli_fetch_assoc($check_agncy_id_res);

    if ($check_agncy_id_row != 1) {
        $response[] = array("error_code" => 109, "message" => "invalid 'agency_id'");
        echo json_encode($response);
        exit;
    }

    if ($check_agncy_id_arr['status'] != "1") {
        $response[] = array("error_code" => 110, "message" => " 'agency_id' inactive");
        echo json_encode($response);
        exit;
    }


$fetch_site="SELECT * FROM `construction_site_header_all` WHERE `agency_id`='$agency_id'";
$res_site=mysqli_query($mysqli, $fetch_site);
while($arr_site=mysqli_fetch_assoc($res_site)){
    $all_site[]=$arr_site;
}
    $admin_query = "SELECT `admin_id`, `admin_name`, `mobile_no`, `email_id`, `status`, `linked_profile`, `permitted_emails`, `permitted_dwnlds` FROM `admin_header_all` WHERE `agency_id`='$agency_id'";
    $admin_res = $mysqli->query($admin_query);

    while ($arr = mysqli_fetch_assoc($admin_res)) {

        $profile_ids = explode(',', $arr['linked_profile']);
        // $profile_array = array();
        $push_val = '';
        foreach ($profile_ids as $key => $value) {
            $profile_query = "SELECT `profile_id`, `profile_name` AS rolesAssigned FROM `profile_header_all` WHERE `profile_id`='$value'";
            $profile_res = $mysqli->query($profile_query);
            $profile_arr = mysqli_fetch_assoc($profile_res);
            if (!empty($profile_arr)) {
                // $profile_array[] = $profile_arr;
                $push_val .= $profile_arr['rolesAssigned'] . '@' . $profile_arr['profile_id'].',';

            }
        }


        $reg_mobile_no=$arr['mobile_no'];
        $check_app_user_token_query="SELECT `id`, `app_device_token` FROM `app_user_token_details_all` WHERE `reg_mobile_no`='$reg_mobile_no'";
        $check_app_user_token_res = $mysqli->query($check_app_user_token_query);
        $check_app_user_token_arr = mysqli_fetch_assoc($check_app_user_token_res);


        if (!empty($arr)) {
            $admin_array[] = array(
                "admin_id" => $arr['admin_id'],
                "authorityName" => $arr['admin_name'],
                "contactNo" => $arr['mobile_no'],
                "email" => $arr['email_id'],
                "permitted_dwnlds" => $arr['permitted_dwnlds'],
                "permitted_emails" => $arr['permitted_emails'],
                "status" => ($arr['status']==1)?"Active":"Deactive",
                "linkedprofile" =>rtrim($push_val, ','),
                "isLogin"=>$check_app_user_token_arr['app_device_token']!=""?"yes":"no"
            );
        }


    }

    $prof_arr = array();
    $profile_query = "SELECT `profile_id`, `profile_name` AS `rolesAssigned` FROM `profile_header_all`";
    $profile_res = $mysqli->query($profile_query);
    while ($arr = mysqli_fetch_assoc($profile_res)) {
        array_push($prof_arr, $arr);
    }

    $query = "SELECT `max_permitted_admin`, `maximum_no_allottment` AS `max_role_limit` FROM `factory_setting_header_all`";
    $res_ = $mysqli->query($query);
    $res_arr = mysqli_fetch_assoc($res_);
    $max_admins_limit = $res_arr['max_permitted_admin'];
    $max_role_limit= $res_arr['max_role_limit'];

    $data[] = array("adminAuthorityData" => $admin_array, "profileRolesData" => $prof_arr, "max_admins_limit" => $max_admins_limit, "max_role_limit"=>$max_role_limit, "site_data"=>$all_site);
   
    $res[] = array("error_code" => 100, "message" => "Success", "data" => $data);
    echo json_encode($res);
}


function common_check_error($agency_id, $mysqli)
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
    if (!$mysqli) {
        $response[] = ["error_code" => 103, "message" => "Unable to proceed your request, please try again after some time"];
        echo json_encode($response);
        return;
    }
    return $common_check;

}



?>