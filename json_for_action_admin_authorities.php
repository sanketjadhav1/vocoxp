<?php
/* 
Name : json for add, update, active, deactivate admin.
Mode :- multi mode
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
// logout($_SERVER['HTTP_AUTH_KEY'], $mysqli);

date_default_timezone_set('Asia/kolkata');
$system_date = date("y-m-d");
$system_date_time = date("Y-m-d H:i:s");


if ($_SERVER['REQUEST_METHOD'] != "POST") {
    $response[] = array("error_code" => 102, "message" => "Request Method Is not Post");
    echo json_encode($response);
    exit;
}

$agency_id = $_POST['agency_id'];
$admin_id = $_POST['admin_id'];
$name = $_POST['name'];
$contact_no = $_POST['contact_no'];
$email_id = $_POST['email_id'];
$assign_roles = $_POST['assign_roles'];
$mode = $_POST['mode'];
$member_id = $_POST['member_id'];
$group_id = $_POST['group_id'];
$profile_id = $_POST['profile_id'];
$permitted_download = $_POST['permitted_download'];
$permitted_email = $_POST['permitted_email'];
$send_invoice_on = $_POST['send_invoice_on'];
$send_report_on = $_POST['send_report_on'];
$update_setting = $_POST['update_setting'];


$common_check = common_check_error($agency_id, $mode, $name, $contact_no, $email_id, $assign_roles, $mysqli, $member_id, $group_id, $update_setting);

if ($common_check == 1) {
    // check agency id
    $check_agncy_id_query = "SELECT `agency_id` FROM `agency_header_all` where `agency_id`='$agency_id'";
    $check_agncy_id_res = $mysqli->query($check_agncy_id_query);
    $check_agncy_id_row = mysqli_num_rows($check_agncy_id_res);

    if ($check_agncy_id_row != 1) {
        $response[] = array("error_code" => 124, "message" => "invalid 'agency_id'");
        echo json_encode($response);
        exit;
    }

    $check_mobile_query = "SELECT `agency_id`, `mobile_no` FROM `agency_header_all`";
    $check_mobile_res = $mysqli->query($check_mobile_query);

    if ($check_mobile_res) {
        while ($check_mobile_arr = $check_mobile_res->fetch_assoc()) {
            if ($check_mobile_arr['mobile_no'] == $contact_no) {
                $response[] = array("error_code" => 124, "message" => "'$contact_no' This mobile number is already registered with an agency");
                echo json_encode($response);
                exit;
            }
        }

    }

    // check admin id
    if ($mode == 'update' || $mode == 'active' || $mode == 'deactive') {

        check_admin_id($admin_id);
 
        $check_admin_id_query = "SELECT `admin_id` FROM `admin_header_all` WHERE `admin_id`='$admin_id' AND `agency_id`='$agency_id'";
        $check_admin_id_res = $mysqli->query($check_admin_id_query);
        $check_admin_id_row = mysqli_num_rows($check_admin_id_res);

        if ($check_admin_id_row != 1) {
            $response[] = array("error_code" => 125, "message" => "invalid 'admin_id'");
            echo json_encode($response);
            exit;
        }
    }


    // add new admin
    if ($mode == "create") {
        // error_reporting(E_ALL);
        // ini_set('display_errors', 1);


        $factory_query = "SELECT `max_permitted_admin`,  `maximum_no_allottment`, `multiple_role_permitted` FROM `factory_setting_header_all`";
        $factory_res = $mysqli->query($factory_query);
        $factory_arr = mysqli_fetch_assoc($factory_res);
        $permited_admin = $factory_arr['max_permitted_admin'];
        $maximum_no_allottment = $factory_arr['maximum_no_allottment'];

        $agncy_id_query = "SELECT `agency_id` FROM `admin_header_all` WHERE `agency_id`='$agency_id'";
        $agncy_id_res = $mysqli->query($agncy_id_query);
        $agncy_id_row = mysqli_num_rows($agncy_id_res);

     


            if ($permited_admin == 0) {
                $response[] = array("error_code" => 128, "message" => "not permited to create new admin");
                echo json_encode($response);
                exit;
            } else {
                if ($agncy_id_row == $permited_admin) {


                    $response[] = array("error_code" => 126, "message" => "Admin limit is reached");
                    echo json_encode($response);
                    exit;
                } else {


                    //connection.php
                    $admin_id = unique_id_genrate('ADM', 'admin_header_all', $mysqli);

                    $check_contact_query = "SELECT `mobile_no`, `agency_id` FROM `admin_header_all` WHERE `mobile_no`='$contact_no' AND `agency_id`='$agency_id'";
                    $check_con_res = $mysqli->query($check_contact_query);
                    $check_con_row = mysqli_num_rows($check_con_res);
                    $create;
                 

                    if ($check_con_row != 1) {

                        $check_profile_query = "SELECT `linked_profile` FROM `admin_header_all` WHERE  `agency_id`='$agency_id' ";
                        $check_profile_res = $mysqli->query($check_profile_query);


                        $profile_arrs=array();
                        while($arr=mysqli_fetch_assoc($check_profile_res)){
                          $profile_ar=explode(",", $arr['linked_profile']);
                        // print_r($profile_ar);
                          foreach($profile_ar as $commaSeparated){
                        array_push($profile_arrs, $commaSeparated);
                          }
                        }
    
                        $assign_role=explode(",", $assign_roles);
    
                        $valueCounts = array_count_values($profile_arrs);


                if ($factory_arr['multiple_role_permitted'] == 1) {

                    // check no of allottment
                    foreach ($assign_role as $assign_rol) {
                        $check_profile = "SELECT `profile_name` FROM `profile_header_all` WHERE `profile_id`='$assign_rol'";
                        $check_profile_res = $mysqli->query($check_profile);
                        $arr = mysqli_fetch_assoc($check_profile_res); // Added missing semicolon
                    
                        foreach ($valueCounts as $key => $value) {
                            if ($assign_rol == $key) {
                                if ($value == $maximum_no_allottment) {
                                    $response[] = array("error_code" => 109, "message" => "".$arr['profile_name']." role assign limit reached.");
                                    $errorOccurred = true;
                                    break; 
                                }
                            }
                        }
                        if ($errorOccurred) {

                            break; // Exit the outer loop
                        }
                    }
                    if (isset($errorOccurred) && $errorOccurred) {
                        echo json_encode($response);
                        exit;
                    }else{
                        $create="new";
                        // echo "1";

                    }
                }


                if($factory_arr['multiple_role_permitted'] == 0){
                    foreach ($assign_role as $assign_rol) {
                        foreach($profile_arrs as $profile_arr){
                          if($assign_rol==$profile_arr){
                             $response[] = array("error_code" => 109, "message" => "This role has been already assigned to another admin. Please select a different role");

                             $errorOccurred = true;
                             break; 
                          }
                        }
                        if ($errorOccurred) {

                            break; 
                        }
                     }
                     if (isset($errorOccurred) && $errorOccurred) {
                        echo json_encode($response);
                        exit;
                    }else{
                        $create="new";
                        // echo "0";
                    }
                }
                  


                if($create=='new'){


                 
                        $insert_admin_query = "INSERT INTO `admin_header_all` 
    (`agency_id`, `admin_id`, `admin_name`, `mobile_no`, `email_id`, `linked_profile`, `status`, `created_on`, `permitted_dwnlds`, `permitted_emails`) 
    VALUES ('$agency_id', '$admin_id', '$name', '$contact_no', '$email_id', '$assign_roles', '1', '$system_date_time', '$permitted_download', '$permitted_email')";
                        $add_admin_res = $mysqli->query($insert_admin_query);


                        if ($add_admin_res == true) {

                            $check_app_user_query = "SELECT `reg_mobile_no`, `linked_agency_id` FROM `app_user_token_details_all` WHERE `reg_mobile_no`='$contact_no'";
                            $check_app_user_res = $mysqli->query($check_app_user_query);
                            $check_app_user_row = mysqli_num_rows($check_app_user_res);

                            if ($check_app_user_row != 1) {
                                $app_user_query = "INSERT INTO `app_user_token_details_all` (`reg_mobile_no`, `linked_agency_id`, `registered_on`, `status`) VALUES ('$contact_no', '$agency_id', '$system_date_time', '1')
                          ";
                                $app_user_res = $mysqli->query($app_user_query);
                            } else {
                                $arr = mysqli_fetch_assoc($check_app_user_res);
                                if (!in_array($agency_id, $arr)) {
                                    $new_agency_id = $arr['linked_agency_id'] . ',' . $agency_id;
                                    $update_app_user_query = "UPDATE `app_user_token_details_all` SET `linked_agency_id`='$new_agency_id' WHERE `reg_mobile_no`='$contact_no'";
                                    $update_app_user_res = $mysqli->query($update_app_user_query);
                                }

                            }

                            $response[] = array("error_code" => 100, "message" => "Admin add successfully");
                            echo json_encode($response);
                            exit;

                        } else {
                            $response[] = array("error_code" => 199, "message" => "Failed to add admin");
                            echo json_encode($response);
                            exit;
                        }
                    }
                     
                   
               
                    } else {
                        $response[] = array("error_code" => 100, "message" => "The mobile number is already registered with this agency.");
                        echo json_encode($response);
                        exit;
                    }
                }
            }
        


    }

    if ($mode == "create_admin_for_group") {

        $check_member_query = "SELECT `member_id`, `name`,
        `contact_no`,
        `email_id` FROM `member_header_all` WHERE `member_id`='$member_id'";
        $check_member_res = $mysqli->query($check_member_query);
        if ($check_member_res->num_rows == 0) {
            $response[] = array("error_code" => 113, "message" => "Invalid member_id. Please pass valid member_id.");
            echo json_encode($response);
            exit;
        }

        $check_group_query = "SELECT `group_id`, `group_members` FROM `Agency_groups_header_all` WHERE `group_id`='$group_id'";
        $check_group_res = $mysqli->query($check_group_query);
        $check_group_arr = mysqli_fetch_assoc($check_group_res);

        $group_mem_arr = explode(",", $check_group_arr['group_members']);

        if ($check_group_res->num_rows == 0) {
            $response[] = array("error_code" => 113, "message" => "Invalid group_id. Please pass valid group_id.");
            echo json_encode($response);
            exit;
        } else {
            $member_ids = [];
            foreach ($group_mem_arr as $arr) {
                $member_ids[] = explode("=", $arr);
           
            }
            $new_member_ids=[];
            // print_r($member_ids);
            for($i=0;count($member_ids)>$i;$i++){
                // print_r($member_ids[0][0]);
                array_push($new_member_ids, $member_ids[$i][0]);
            
            }
            // print_r($new_member_ids);
            if (!in_array($member_id, $new_member_ids)) {
                $response = [
                    "error_code" => 113,
                    "message" => "This member is not available in this group."
                ];
                echo json_encode($response);
                exit;
            }else{
                $arr=mysqli_fetch_assoc($check_member_res);
                $mem_name=$arr['name'];
                $mem_contact_no=$arr['contact_no'];
                $mem_email_id=$arr['email_id'];
                $admin_id = unique_id_genrate('ADM', 'admin_header_all', $mysqli);

                $create_group_admin_query="INSERT INTO `admin_header_all` 
                (`agency_id`, `admin_id`, `admin_name`, `mobile_no`, `email_id`, `linked_profile`, `status`, `created_on`) 
                VALUES ('$agency_id', '$admin_id', '$mem_name', '$mem_contact_no', '$mem_email_id', '$assign_roles', '1', '$system_date_time')";
                $create_group_admin_res=$mysqli->query($create_group_admin_query);

                if($create_group_admin_res==true){
                    $response[] = array("error_code" => 100, "message" => "Group admin add successfully");
                    echo json_encode($response);
                    exit;
                }
            }
           
        }




    }


    // update admin
    if ($mode == "update") {
        check_admin_id($admin_id);
        $factory_query = "SELECT `max_permitted_admin`,  `maximum_no_allottment`, `multiple_role_permitted` FROM `factory_setting_header_all`";
        $factory_res = $mysqli->query($factory_query);
        $factory_arr = mysqli_fetch_assoc($factory_res);
 
        $maximum_no_allottment = $factory_arr['maximum_no_allottment'];



        $check_profile_query = "SELECT `linked_profile` FROM `admin_header_all` WHERE  `agency_id`='$agency_id' AND `admin_id`='$admin_id'";
$check_profile_res = $mysqli->query($check_profile_query);
$check_profile_arr = mysqli_fetch_assoc($check_profile_res);

$linked_profile_arr = explode(",", $check_profile_arr['linked_profile']);
$assign_roles_arr = explode(",", $assign_roles);

$new_values = array_diff($assign_roles_arr, $linked_profile_arr);

if(!empty($new_values)){
    $check_profile_query = "SELECT `linked_profile` FROM `admin_header_all` WHERE  `agency_id`='$agency_id'";
    $check_profile_res = $mysqli->query($check_profile_query);

    $profile_arrs = array();
    while ($arr = mysqli_fetch_assoc($check_profile_res)) {
        $profile_ar = explode(",", $arr['linked_profile']);
        foreach ($profile_ar as $commaSeparated) {
            array_push($profile_arrs, $commaSeparated);
        }
    }

    $valueCounts = array_count_values($profile_arrs);
    $response = [];
    $errorOccurred = false;

    

    foreach ($new_values as $new_value) {
        $check_profile = "SELECT `profile_name` FROM `profile_header_all` WHERE `profile_id`='$new_value'";
        $check_profile_res = $mysqli->query($check_profile);
        $arr = mysqli_fetch_assoc($check_profile_res);

        foreach ($valueCounts as $key => $value) {
            if ($new_value == $key && $value == $maximum_no_allottment) {


                $response[] = array("error_code" => 109, "message" => "" . $arr['profile_name'] . " role assign limit reached. You can't update.");
                $errorOccurred = true;
                break 2; // Exit both loops
            }
        }
    }

    if ($errorOccurred) {
        echo json_encode($response);
        exit;
    }

}
        


if($update_setting!=1){
    $update_admin_query = "UPDATE `admin_header_all` 
    SET `admin_name`='$name', 
        `mobile_no`='$contact_no', 
        `email_id`='$email_id', 
        `linked_profile`='$assign_roles' 
    WHERE `agency_id`='$agency_id' 
        AND `admin_id`='$admin_id'";

    $update_admin_res = $mysqli->query($update_admin_query);
}
if($update_setting==1){
    $update_admin_query = "UPDATE `admin_header_all` 
    SET `permitted_dwnlds`='$permitted_download', 
        `permitted_emails`='$permitted_email'
         
    WHERE `agency_id`='$agency_id' 
        AND `admin_id`='$admin_id'";

    $update_admin_res = $mysqli->query($update_admin_query);
}


    if ($update_admin_res == true || $update_admin_res == true) {
        $response[] = array("error_code" => 100, "message" => "Admin update successfully");
        echo json_encode($response);
        exit;
    } else {
        $response[] = array("error_code" => 199, "message" => "Failed to update admin");
        echo json_encode($response);
        exit;
    }

        
    }


    // deactivate admin active
    if ($mode == "active") {
        check_admin_id($admin_id);


        $_query = "SELECT `admin_id` FROM `admin_header_all` WHERE `status`='1' AND `agency_id`='$agency_id' AND `admin_id`='$admin_id'";
        $_res = $mysqli->query($_query);
        $_arr = $_res->fetch_assoc();

        if (!empty($_arr)) {
            $response[] = array("error_code" => 127, "message" => "Admin status already 'active'");
            echo json_encode($response);
            exit;
        }



        $active_admin_query = "UPDATE `admin_header_all` 
    SET `status`='active' 
    WHERE `agency_id`='$agency_id' 
        AND `admin_id`='$admin_id'";

        $active_admin_res = $mysqli->query($active_admin_query);

        if ($active_admin_res == true) {
            $response[] = array("error_code" => 100, "message" => "Admin status 'active' successfully");
            echo json_encode($response);
            exit;
        } else {
            $response[] = array("error_code" => 199, "message" => "Failed to 'active' status admin");
            echo json_encode($response);
            exit;
        }
    }

    // activate admin deactive
    if ($mode == "deactive") {
        check_admin_id($admin_id);

        $_query = "SELECT `admin_id` 
        FROM `admin_header_all` 
        WHERE `status`='0' 
              AND `agency_id`='$agency_id' 
              AND `admin_id`='$admin_id'";

        $_res = $mysqli->query($_query);
        $_arr = $_res->fetch_assoc();

        if (!empty($_arr)) {
            $response[] = array("error_code" => 127, "message" => "Admin status already 'deactive'");
            echo json_encode($response);
            exit;
        }


        $deactive_admin_query = "UPDATE `admin_header_all` 
        SET `status`='deactive', 
            `deactivated_on`='$system_date_time' 
        WHERE `agency_id`='$agency_id' 
              AND `admin_id`='$admin_id'";

        $deactive_admin_res = $mysqli->query($deactive_admin_query);

        if ($deactive_admin_res == true) {
            $response[] = array("error_code" => 100, "message" => "Admin status 'deactive' successfully");
            echo json_encode($response);
            exit;
        } else {
            $response[] = array("error_code" => 199, "message" => "Failed to 'deactive' status admin");
            echo json_encode($response);
            exit;
        }
    }

// remove profile
if ($mode == "remove_profile") {
    check_admin_id($admin_id);
    if (!isset($profile_id)) {
        $response[] = array("error_code" => 109, "message" => "Please pass the parameter of profile_id");
        echo json_encode($response);
        exit;
    }
    if ($profile_id == "") {
        $response[] = array("error_code" => 110, "message" => "Value of profile_id  can not be empty");
        echo json_encode($response);
        exit;
    }
    // echo "check";

    $query = "SELECT `admin_id`, `linked_profile`, `old_linked_profile` 
    FROM `admin_header_all` 
    WHERE `status`='0' 
          AND `agency_id`='$agency_id' 
          AND `admin_id`='$admin_id'";

    $res = $mysqli->query($query);
    $arr = $res->fetch_assoc();


$linked_profile=explode(",", $arr['linked_profile']);
$old_linked_profile= $arr['old_linked_profile'];


$key = array_search($profile_id, $linked_profile);

if ($key !== false) {
    unset($linked_profile[$key]); // Remove the element with the specified key
    $comma_separated = implode(", ", $linked_profile);
    
       $remove_profile= $old_linked_profile==""?  $profile_id : $old_linked_profile.','.$profile_id;

     $update_admin_profile_query = "UPDATE `admin_header_all` 
    SET `linked_profile`='$comma_separated', `old_linked_profile`='$remove_profile'
    WHERE `agency_id`='$agency_id' 
          AND `admin_id`='$admin_id'";

    $update_admin_profile_res = $mysqli->query($update_admin_profile_query);

    $response[] = array("error_code" => 100, "message" => "Profile remove successfully.");
    echo json_encode($response);
    exit;
}else{
    
    $response[] = array("error_code" => 100, "message" => "This profile not assign.");
    echo json_encode($response);
    exit;
}



}


}

function common_check_error($agency_id, $mode, $name, $contact_no, $email_id, $assign_roles, $mysqli, $member_id, $group_id, $update_setting)
{
    $common_check = 1;

    // echo "check";
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

    if (!isset($mode)) {
        $response[] = ["error_code" => 118, "message" => "Please add mode"];
        echo json_encode($response);
        exit;
    }
    if (empty($mode)) {
        $response[] = ["error_code" => 119, "message" => "mode is empty"];
        echo json_encode($response);
        exit;
    }

    if ($mode != 'create' && $mode != 'update' && $mode != 'active' && $mode != 'deactive' && $mode != 'create_admin_for_group' && $mode !='remove_profile') {
        $response[] = ["error_code" => 120, "message" => "Paramter 'mode' value is invalid."];
        echo json_encode($response);
        exit;
    }

    if ($mode == 'create' || $mode == 'update') {
        if($update_setting!=1){
        if (!isset($name)) {
            $response[] = array("error_code" => 109, "message" => "Please pass the parameter of name");
            echo json_encode($response);
            exit;
        }
        if ($name == "") {
            $response[] = array("error_code" => 110, "message" => "Value of name can not be empty");
            echo json_encode($response);
            exit;
        }

        if (!isset($contact_no)) {
            $response[] = array("error_code" => 111, "message" => "Please pass the parameter of contact_no");
            echo json_encode($response);
            exit;
        }
        if ($contact_no == "") {
            $response[] = array("error_code" => 112, "message" => "value of contact_no can not be empty");
            echo json_encode($response);
            exit;
        }
        if (!isset($email_id)) {
            $response[] = array("error_code" => 114, "message" => "Please pass the parameter of email_id");
            echo json_encode($response);
            exit;
        }
        if ($email_id == "") {
            $response[] = array("error_code" => 115, "message" => "value of email_id can not be empty");
            echo json_encode($response);
            exit;
        }


        if (!isset($assign_roles)) {
            $response[] = array("error_code" => 116, "message" => "Please pass the parameter of assign_roles");
            echo json_encode($response);
            exit;
        }
        if ($assign_roles == "") {
            $response[] = array("error_code" => 117, "message" => "value of assign_roles can not be empty");
            echo json_encode($response);
            exit;
        }
    }
}

    if ($mode == "create_admin_for_group") {
        if (!isset($member_id)) {
            $response[] = array("error_code" => 109, "message" => "Please pass the parameter of member_id");
            echo json_encode($response);
            exit;
        }
        if ($member_id == "") {
            $response[] = array("error_code" => 110, "message" => "value of member_id can not be empty");
            echo json_encode($response);
            exit;
        }

        if (!isset($group_id)) {
            $response[] = array("error_code" => 111, "message" => "Please pass the parameter of group_id");
            echo json_encode($response);
            exit;
        }
        if ($group_id == "") {
            $response[] = array("error_code" => 112, "message" => "value of group_id can not be empty");
            echo json_encode($response);
            exit;
        }
    }




    // if (sizeof($contact_no) == 10) {
    //     $response[] = array("error_code" => 113, "message" => "contact_no is not 10 digit. please enter 10 digit contact_no");
    //     echo json_encode($response);
    //     exit;
    // }







    if (!$mysqli) {
        $response[] = ["error_code" => 121, "message" => "Unable to proceed your request, please try again after some time"];
        echo json_encode($response);
        return;
    }

    function check_admin_id($admin_id)
    {
        if (!isset($admin_id)) {
            $response[] = array("error_code" => 122, "message" => "Please pass the parameter of admin_id");
            echo json_encode($response);
            exit;
        }
        if ($admin_id == "") {
            $response[] = array("error_code" => 123, "message" => "value of admin_id can not be empty");
            echo json_encode($response);
            exit;
        }
    }

    return $common_check;
}


?>