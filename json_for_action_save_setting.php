<?php
/* 
Name : json_for_action_save_setting.php
Version of the Requirment Document  : 2.0.1


Purpose :- This json will act on Setting respective Members or agencies
Mode :- multi mode

Developed By - Rishabh Shinde
*/
// error_reporting(E_ALL & ~E_DEPRECATED);
// ini_set('display_errors', 1);
include_once 'connection.php';

// Now you can use the connection class
$connection = connection::getInstance();
$mysqli = $connection->getConnection();
apponoff($mysqli);
logout($_SERVER['HTTP_AUTH_KEY'], $mysqli);
$common_check_error=common_check_error($mysqli, $_POST['agency_id'], $_POST['mode'], $_POST['member_id']);

if($common_check_error==1){

    $mapping = ['yes' => 1, 'no' => 0];

$agency_id = $_POST['agency_id'];
$mode = $_POST['mode'];
$alert_watch_remove = $mapping[$_POST['alert_watch_remove']];
$alert_sim_card_remove = $mapping[$_POST['alert_sim_card_remove']];
$alert_watch_reconnect_server = $mapping[$_POST['alert_watch_reconnect_server']];
$alert_pre_schedule = $mapping[$_POST['alert_pre_schedule']];
$out_of_gps_range_location = $mapping[$_POST['out_of_gps_range_location']];
$geo_fancing_margin = $mapping[$_POST['geo_fancing_margin']];
$manual_recording = $mapping[$_POST['manual_recording']];
$mr_notification = $mapping[$_POST['mr_notification']];
$sos = $mapping[$_POST['sos']];
$e_crime_alert = $mapping[$_POST['e_crime_alert']];
$heart_rate = $mapping[$_POST['heart_rate']];
$spo2 = $mapping[$_POST['spo2']];
$body_temp = $mapping[$_POST['body_temp']];
$bluetooth = $mapping[$_POST['bluetooth']];
$carrier = $mapping[$_POST['carrier']];
$app_setting = $mapping[$_POST['app_setting']];
$geo_location_auto_update = $mapping[$_POST['geo_location_auto_update']];

if ($mode == "agency_setting") {
    $watch_reset_pin = $mapping[$_POST['watch_reset_pin']];
    $mark_watch_stolen = $mapping[$_POST['mark_watch_stolen']];
    $fsn_mark_watch_stolen = $mapping[$_POST['fsn_mark_watch_stolen']];
    $watch_stolen_audio_file = $mapping[$_FILES['watch_stolen_audio_file']['name']];
    $watch_stolen_msg = $mapping[$_POST['watch_stolen_msg']];
    $holidays = $mapping[$_POST['holidays']];
    $add_family_member = $mapping[$_POST['add_family_member']];
    $configuration_lock = $mapping[$_POST['configuration_lock']];
    $add_family_member = $mapping[$_POST['add_family_member']];
    $fsn_for_watch_remove = $mapping[$_POST['fsn_for_watch_remove']];
    $fsn_sim_card_remove = $mapping[$_POST['fsn_sim_card_remove']];
} elseif ($mode == "member_setting") {
    $member_id = $_POST['member_id'];
    $fsn_for_watch_remove = $mapping[$_POST['fsn_for_watch_remove']];
    $watch_remove_between_time = $mapping[$_POST['watch_remove_between_time']];
    $fsn_sim_card_remove = $mapping[$_POST['fsn_sim_card_remove']];
    $fsn_watch_reconnect_server = $mapping[$_POST['fsn_watch_reconnect_server']];
    $fsn_non_delivery_pre_schedule_alert = $mapping[$_POST['fsn_non_delivery_pre_schedule_alert']];
    $out_of_gps_range_location_values = $mapping[$_POST['out_of_gps_range_location_values']];
    $fsn_out_of_gps_range_location = $mapping[$_POST['fsn_out_of_gps_range_location']];
    $carrier_type = $mapping[$_POST['carrier_type']];
}

   $currdate=date("Y-m-d H:i:s");
    $fetch_agency_id = "SELECT `agency_id`, `status` FROM `agency_header_all` WHERE `agency_id`='$agency_id'";
$result_agency = mysqli_query($mysqli, $fetch_agency_id);
$arr_agency = mysqli_fetch_assoc($result_agency);

if (!$arr_agency) {
    $response[] = array("error_code" => 102, "message" => "Please enter the valid agency id");
    echo json_encode($response);
    return;
} elseif ($arr_agency['status'] != "1") {
    $response[] = array("error_code" => 103, "message" => "Please enter active agency id");
    echo json_encode($response);
    return;
}

    if($mode=="agency_setting"){

        $fetch_agency_setting="SELECT `agency_id` FROM `agency_setting_all` WHERE `agency_id`='$agency_id'";
        $res_agency_setting=mysqli_query($mysqli, $fetch_agency_setting);
        
       while($arr_agency_setting=mysqli_fetch_assoc($res_agency_setting)){
        $update_mem_set="UPDATE member_settings_all SET `alert_on_watch_remove_from_wrist`='$alert_watch_remove', `alrert_on_sim_remove`='$alert_sim_card_remove', `alert_when_watch_out_from_gps_range`='$out_of_gps_range_location', `sos`='$sos'";
        $res_mem_set=mysqli_query($mysqli, $update_mem_set);
       }
       $base_url = 'https://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
$createDir = "audio/{$agency_id}/";

// Create the directory if it doesn't exist
if (!is_dir($createDir)) {
    if (!mkdir($createDir, 0777, true)) {
        die('Failed to create directory');
    }
}

$file_path = $createDir . $watch_stolen_audio_file;

// Full file path including base URL
$full_file_path = $base_url . "/" . $file_path;
file_put_contents($full_file_path, $file_content);


// Write file contents


       
       
        $fetch_exiting_agency="SELECT `agency_id` FROM `agency_setting_all` WHERE `agency_id`='$agency_id'";
        $result=mysqli_query($mysqli, $fetch_exiting_agency);
        $row=mysqli_num_rows($result);
        if($row==0){
                $insert_agency_setting="INSERT INTO `agency_setting_all` (`id`, `agency_id`, `add_family_member`, `app_setting`, `is_univarsal`, `geo_fancing_margin`, `geo_location_auto_update`, `manual_recording`, `e_crime_reminder`, `sos`, `created_on`,`watch_reset_pin`, `mark_watch_stolen`, `fsn_mark_watch_stolen`, `watch_stolen_audio_file`, `watch_stolen_msg`, `holidays`, `alert_on_watch_remove_from_wrist`, `alrert_on_sim_remove`, `alert_on_watch_to_reconnect_server`, `alert_pre_schedule`, `alert_when_watch_out_from_gps_range`, `manual_recording_notification`, `heart_rate`, `spo2`, `body_temp`, `bluetooth`, `carrier`, `alert_when_watch_remove`, `fsn_for_sim_remove`) VALUES (NULL, '$agency_id', '$add_family_member', '$app_setting', 'N', '$geo_fancing_margin', '$out_of_gps_range_location', '$manual_recording', '$e_crime_alert', '$sos', '$currdate',  '$watch_reset_pin', '$mark_watch_stolen', '$fsn_mark_watch_stolen', '$file_path', '$watch_stolen_msg', '$holidays', '$fsn_for_watch_remove', '$alert_sim_card_remove', '$alert_watch_reconnect_server', '$alert_pre_schedule', '$out_of_gps_range_location', '$mr_notification', '$heart_rate', '$spo2', '$body_temp', '$bluetooth', '$carrier','$alert_watch_remove', '$fsn_sim_card_remove');";
            
            $result=mysqli_query($mysqli, $insert_agency_setting);
            $response[] = array("error_code" => 100, "message" => "Setting Upload Successfully");
    echo json_encode($response);
    return;
        }else{
            $base_url = 'https://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
$createDir = "audio/{$agency_id}/";

// Create the directory if it doesn't exist
if (!is_dir($createDir)) {
    if (!mkdir($createDir, 0777, true)) {
        die('Failed to create directory');
    }
}

$file_path = $createDir . $watch_stolen_audio_file;

// Full file path including base URL
$full_file_path = $base_url . "/" . $file_path;
file_put_contents($full_file_path, $file_content);

        
        
        
        
              $update_agency_setting = "UPDATE `agency_setting_all`
             SET 
                 `add_family_member` = '$add_family_member',
                 `app_setting` = '$app_setting',
                 `geo_fancing_margin` = '$geo_fancing_margin',
                 `geo_location_auto_update` = '$out_of_gps_range_location',
                 `manual_recording` = '$manual_recording',
                 `e_crime_reminder` = '$e_crime_alert',
                 `sos` = '$sos',
                 `watch_reset_pin` = '$watch_reset_pin',
                 `mark_watch_stolen` = '$mark_watch_stolen',
                 `fsn_mark_watch_stolen` = '$fsn_mark_watch_stolen',
                 `watch_stolen_audio_file` = '$file_path',
                 `watch_stolen_msg` = '$watch_stolen_msg',
                 `holidays` = '$holidays',
                 `alert_on_watch_remove_from_wrist` = '$fsn_for_watch_remove',
                 `alrert_on_sim_remove` = '$alert_sim_card_remove',
                 `alert_on_watch_to_reconnect_server` = '$alert_watch_reconnect_server',
                 `pre_schedule_alert` = '$alert_pre_schedule',
                 `alert_when_watch_out_from_gps_range` = '$out_of_gps_range_location',
                 `manual_recording_notification` = '$mr_notification',
                 `heart_rate` = '$heart_rate',
                 `spo2` = '$spo2',
                 `body_temp` = '$body_temp',
                 `bluetooth` = '$bluetooth',
                 `carrier` = '$carrier',
                 `alert_when_watch_remove`='$alert_watch_remove',
                 `fsn_for_sim_remove`='$fsn_sim_card_remove'
             WHERE
                 `agency_id` = '$agency_id';
             ";

$result = mysqli_query($mysqli, $update_agency_setting);
$response[] = array("error_code" => 100, "message" => "Setting upload successfully");
    echo json_encode($response);
    return;
        }

    }else if($mode=="member_setting"){

        $fetch_agency_setting="SELECT `body_temp`, `bluetooth`, `carrier`,`heart_rate`, `spo2`  FROM `agency_setting_all` WHERE `agency_id`='$agency_id'";
        $res_agency_setting=mysqli_query($mysqli, $fetch_agency_setting);
        $arr_agency_setting=mysqli_fetch_assoc($res_agency_setting);
        // print_r($arr_agency_setting);
if($arr_agency_setting['alert_pre_schedule']=="yes" || $arr_agency_setting['alert_pre_schedule']!="" || $arr_agency_setting['alert_pre_schedule']!="no"){
    $alert_pre_schedule_member=$_POST['alert_pre_schedule'];
}else{
    $response[]=["error_code" => 123, "message" => "To use alert_pre_schedule for member. Please enable it from the agency setting"];
    echo json_encode($response);
    return;
}
if($arr_agency_setting['body_temp']=="yes" || $arr_agency_setting['body_temp']!="" || $arr_agency_setting['body_temp']!="no"){
    $body_temp_member=$_POST['body_temp'];
}else{
    $response[]=["error_code" => 124, "message" => "To use body_temp for member. Please enable it from the agency setting"];
    echo json_encode($response);
    return;
}
if($arr_agency_setting['bluetooth']=="yes" || $arr_agency_setting['bluetooth']!="" || $arr_agency_setting['bluetooth']!="no"){
    $bluetooth_member=$_POST['bluetooth'];
}else{
    $response[]=["error_code" => 125, "message" => "To use bluetooth for member. Please enable it from the agency setting"];
    echo json_encode($response);
    return;
}
if($arr_agency_setting['carrier']=="yes" || $arr_agency_setting['carrier']!="" || $arr_agency_setting['carrier']!="no"){
    $carrier_member=$_POST['carrier'];
}else{
    $response[]=["error_code" => 126, "message" => "To use carrier for member. Please enable it from the agency setting"];
    echo json_encode($response);
    return;
}
if($arr_agency_setting['heart_rate']=="yes" || $arr_agency_setting['heart_rate']!="" || $arr_agency_setting['heart_rate']!="no"){
    $heart_rate_member=$_POST['heart_rate'];
}else{
    $response[]=["error_code" => 127, "message" => "To use heart_rate for member. Please enable it from the agency setting"];
    echo json_encode($response);
    return;
}
if($arr_agency_setting['spo2']=="yes" || $arr_agency_setting['spo2']!="" || $arr_agency_setting['spo2']!="no"){
    $spo2_member=$_POST['spo2'];
}else{
    $response[]=["error_code" => 128, "message" => "To use spo2 for member. Please enable it from the agency setting"];
    echo json_encode($response);
    return;
}



 $fetch_member_setting = "SELECT `member_id` FROM `member_header_all` WHERE `registration_id`='$agency_id' AND `member_id`='$member_id'";
$res_member_setting = mysqli_query($mysqli, $fetch_member_setting);

if (mysqli_num_rows($res_member_setting) == 0) {
    $response[] = array("error_code" => 120, "message" => "member_id invalid. Please enter a valid member_id");
    echo json_encode($response);
    return;
}

$fetch_mem_setting="SELECT `member_id`, `agency_id` FROM `member_settings_all` WHERE `agency_id`='$agency_id' AND `member_id`='$member_id'";
$res_mem_setting=mysqli_query($mysqli, $fetch_mem_setting);
       if(mysqli_num_rows($res_mem_setting)==0){
         $insert_member_setting="INSERT INTO `member_settings_all` (`id`, `agency_id`, `member_id`, `app_setting_on_off`, `sos`, `geo_location_auto_update`, `manual_recording`, `manual_recording_notification`, `ecrime_reminder`, `alert_when_watch_out_from_gps_range`, `fsn_when_out_of_gps_location`, `bluetooth`, `carrier`, `heart_rate`, `spo2`, `body_temp`, `alert_when_watch_remove`, `alert_on_watch_remove_from_wrist`, `alert_on_watch_removal_after_every`, `alrert_on_sim_remove`, `fsn_for_sim_remove`, `alert_on_watch_to_reconnect_server`, `fsn_watch_server_reconnect`, `pre_schedule_alert`, `fsn_pre_schedule_alert_not_delivered`) VALUES (NULL, '$agency_id', '$member_id', '$app_setting', '$sos', '$geo_location_auto_update', '$manual_recording', '$mr_notification', '$e_crime_alert', '$out_of_gps_range_location', '$fsn_out_of_gps_range_location', '$bluetooth_member', '$carrier_member', '$heart_rate_member', '$spo2_member', '$body_temp_member', '$alert_watch_remove', '$fsn_for_watch_remove', '$watch_remove_between_time', '$alert_sim_card_remove', '$fsn_sim_card_remove', '$alert_watch_reconnect_server', '$fsn_watch_reconnect_server', '$alert_pre_schedule_member', '$fsn_non_delivery_pre_schedule_alert');";
        $result = mysqli_query($mysqli, $insert_member_setting);
        $response[] = array("error_code" => 100, "message" => "Setting Upload Successfully");
    echo json_encode($response);
    return;
       }else{
        $update_member_setting = "UPDATE `member_settings_all`
        SET 
            `app_setting_on_off` = '$app_setting',
            `sos` = '$sos',
            `geo_location_auto_update` = '$geo_location_auto_update',
            `manual_recording` = '$manual_recording',
            `manual_recording_notification` = '$mr_notification',
            `ecrime_reminder` = '$e_crime_alert',
            `alert_when_watch_out_from_gps_range` = '$out_of_gps_range_location',
            `fsn_when_out_of_gps_location` = '$fsn_out_of_gps_range_location',
            `bluetooth` = '$bluetooth_member',
            `carrier` = '$carrier_member',
            `heart_rate` = '$heart_rate_member',
            `spo2` = '$spo2_member',
            `body_temp` = '$body_temp_member',
            `alert_when_watch_remove` = '$alert_watch_remove',
            `alert_on_watch_remove_from_wrist` = '$fsn_for_watch_remove',
            `alert_on_watch_removal_after_every` = '$watch_remove_between_time',
            `alrert_on_sim_remove` = '$alert_sim_card_remove',
            `fsn_for_sim_remove` = '$fsn_sim_card_remove',
            `alert_on_watch_to_reconnect_server` = '$alert_watch_reconnect_server',
            `fsn_watch_server_reconnect` = '$fsn_watch_reconnect_server',
            `pre_schedule_alert` = '$alert_pre_schedule_member',
            `fsn_pre_schedule_alert_not_delivered` = '$fsn_non_delivery_pre_schedule_alert'
        WHERE
            `agency_id` = '$agency_id'
            AND `member_id` = '$member_id';
        ";
       }
       
            

$result = mysqli_query($mysqli, $update_member_setting);
$response[] = array("error_code" => 100, "message" => "Setting Update Successfully");
    echo json_encode($response);
    return;
        

    }

}


function common_check_error($mysqli, $agency_id, $mode, $member_id){

    $common_check_error=1;
    if(!$mysqli){
        $response[]=["error_code" => 101, "message" => "There was an issue connecting to the database. Please try
        again later"];
        echo json_encode($response);
        return;
    }
    if($_SERVER["REQUEST_METHOD"] != "POST"){
        $responce[] = array( "error_code" => 116, "message" => "please change request method to POST");
        echo json_encode($responce);
        return; 
    
            
    }
    if(!isset($agency_id)){
        $response[]=["error_code" => 117, "message" => "Please the parameter - agency_id"];
        echo json_encode($response);
        return;
    }else{
        if($agency_id==""){
            $response[]=["error_code" => 118, "message" => "Value of 'agency_id' cannot be empty"];
            echo json_encode($response);
            return;
        }
    }
    if(!isset($mode)){
        $response[]=["error_code" => 119, "message" => "Please the parameter - mode"];
        echo json_encode($response);
        return;
    }else{
        if($mode==""){
            $response[]=["error_code" => 120, "message" => "Value of 'mode' cannot be empty"];
            echo json_encode($response);
            return;
        }elseif($mode!="agency_setting" && $mode!="member_setting"){
            $response[]=["error_code" => 104, "message" => "Please enter the correct mode. please select one agency_setting or member_setting"];
            echo json_encode($response);
            return;
        }elseif($mode=="member_setting"){
            if(!isset($member_id)){
                $response[]=["error_code" => 121, "message" => "Please the parameter - member_id"];
                echo json_encode($response);
                return;
            }else{
                if($member_id==""){
                    $response[]=["error_code" => 122, "message" => "Value of 'member_id' cannot be empty"];
                    echo json_encode($response);
                    return;
                }
            }

            if($_POST['geo_fancing_margin']=="Yes"){        
                if(empty($_POST['geo_fancing_margin'])){
                    $response[]=["error_code" => 111, "message" => "Geo fencing margin cannot be empty"];
                    echo json_encode($response);
                    return;
                }
            }
            if($_POST['carrier']=="Yes"){        
                if(empty($_POST['carrier_type'])){
                    $response[]=["error_code" => 112, "message" => "Please select atleast one carrier type"];
                    echo json_encode($response);
                    return;
                }
            }
            if($_POST['heart_rate']=="Yes"){        
                if(empty($_POST['heart_rate_length'])){
                    $response[]=["error_code" => 113, "message" => "Please fill the fields"];
                    echo json_encode($response);
                    return;
                }
            }
            if($_POST['spo2']=="Yes"){        
                if(empty($_POST['spo2_level'])){
                    $response[]=["error_code" => 114, "message" => "Please enter value"];
                    echo json_encode($response);
                    return;
                }
            }
            if($_POST['body_temperature']=="Yes"){        
                if(empty($_POST['body_temperature_level'])){
                    $response[]=["error_code" => 115, "message" => "Please enter value (36.7-37.2)"];
                    echo json_encode($response);
                    return;
                }
            }
        }elseif($mode=="agency_setting"){
            if($_POST['configuration_lock']=="Yes"){
                if(empty($_POST['configuration_lock']) || $_POST['configuration_lock'] < 4){
                    $response[]=["error_code" => 109, "message" => "Rest watch configuration 4 digit pin is required"];
                    echo json_encode($response);
                    return;
                }
            }
        }
    }

    if($_POST['geo_fancing_margin']=="Yes"){        
        if(empty($_POST['geo_fancing_margin'])){
            $response[]=["error_code" => 105, "message" => "Geo fencing margin cannot be empty"];
            echo json_encode($response);
            return;
        }
    }
    if($_POST['geo_location_auto_update']=="Yes"){        
        if(empty($_POST['geo_location_auto_update'])){
            $response[]=["error_code" => 106, "message" => "Time is mandatory filled"];
            echo json_encode($response);
            return;
        }
    }
    if($_POST['manual_recording']=="Yes"){        
        if(empty($_POST['manual_recording'])){
            $response[]=["error_code" => 107, "message" => "Manual recording duration be more than zero"];
            echo json_encode($response);
            return;
        }
    }
    if($_POST['e_crime_reminder']=="Yes"){        
        if(empty($_POST['e_crime_reminder'])){
            $response[]=["error_code" => 108, "message" => "E- crime verification reminder days is mandatory"];
            echo json_encode($response);
            return;
        }
    }
    if($_POST['sos']=="Yes"){        
        if(empty($_POST['sos_act_as'])){
            $response[]=["error_code" => 116, "message" => "E- crime verification reminder days is mandatory"];
            echo json_encode($response);
            return;
        }
    }
   
    return $common_check_error;
}
?>