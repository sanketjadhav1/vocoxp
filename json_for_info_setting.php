<?php
/* 
Name : json_for_info_storage_setting.php
Version of the Requirment Document  : 2.0.0


Purpose :- This api is to use fetch storage setting details
Mode :- Multi Mode


Developed By - Rishabh Shinde
*/
// error_reporting(1);
include_once "connection.php";

$connection = connection::getInstance();
$mysqli = $connection->getConnection();


$connection1 = database::getInstance();
$mysqli1 = $connection1->getConnection();
apponoff($mysqli);

logout($_SERVER['HTTP_AUTH_KEY'], $mysqli);
$check_error = common_check_error($mysqli, $_POST['mode'], $_POST['agency_id'], $_POST['member_id'], $_POST['application_id']);

if ($check_error == 1) {

    $agency_id = $_POST['agency_id'];
    $member_id = $_POST['member_id'];
    $application_id = $_POST['application_id'];
    $mode = $_POST['mode'];


    $fetch_agency_id = "SELECT `agency_id`, `status`, `total_storage`, `archieve_storage`, `used_storage`, `available_storage`, `current_wallet_bal` FROM `agency_header_all` WHERE `agency_id`='$agency_id'";
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

    if ($mode == "agency_setting") {

        // Function to convert '0' to 'No' and '1' to 'Yes'
        function convertZeroOneToYesNo($data)
        {
            foreach ($data as $key => $value) {
                if ($value === '1') {
                    $data[$key] = 'Yes';
                } elseif ($value === '0') {
                    $data[$key] = 'No';
                }
            }
            return $data;
        }

        // Fetch agency setting
        $fetch_agency_setting = "SELECT * FROM `agency_setting_all` WHERE `agency_id`='$agency_id'";
        $result_setting = mysqli_query($mysqli, $fetch_agency_setting);
        $arr_setting = mysqli_fetch_assoc($result_setting);

        // Fetch factory settings
        $fetch_factory = "SELECT `online_store`, `digital_verification` FROM `factory_setting_header_all`";
        $res_factory = mysqli_query($mysqli, $fetch_factory);
        $arr_factory = mysqli_fetch_assoc($res_factory);

        // Applying conversion to $arr_setting
        $arr_setting = convertZeroOneToYesNo($arr_setting);

        // Applying conversion to $arr_factory
        $arr_factory = convertZeroOneToYesNo($arr_factory);

        // Merging arrays
        $data[] = array_merge($arr_setting, $arr_factory);

        // Check if data is not empty
        if (!empty($data)) {
            $response[] = ["error_code" => 100, "message" => "Data fetched successfully", "data" => $data];
            echo json_encode($response);
            return;
        } else {
            $response = [
                "error_code" => 100,
                "message" => "Data fetch successfully",
                "data" => [
                    [

                        "agency_id" => $agency_id,
                        "add_family_member" => "no",
                        "app_setting" => "no",
                        "is_universal" => "",
                        "geo_fencing_margin" => "0",
                        "geo_location_auto_update" => "0",
                        "manual_recording" => "0",
                        "e_crime_reminder" => "0",
                        "sos" => "no",
                        "created_on" => "0000-00-00 00:00:00.000000",
                        "modified_on" => "2024-02-01 13:07:19",
                        "watch_reset_pin" => "0",
                        "mark_watch_stolen" => "no",
                        "fsn_mark_watch_stolen" => "",
                        "watch_stolen_audio_file" => "",
                        "watch_stolen_msg" => "",
                        "holidays" => "no",
                        "alert_when_watch_remove" => "no",
                        "alert_on_watch_remove_from_wrist" => "",
                        "alrert_on_sim_remove" => "no",
                        "fsn_for_sim_remove" => "",
                        "alert_on_watch_to_reconnect_server" => "no",
                        "pre_schedule_alert" => "no",
                        "alert_when_watch_out_from_gps_range" => "no",
                        "manual_recording_notification" => "",
                        "heart_rate" => "no",
                        "spo2" => "no",
                        "body_temp" => "no",
                        "bluetooth" => "no",
                        "carrier" => "no",
                        "online_store" => "yes",
                        "digital_verification" => "yes"
                    ]
                ]
            ];

            echo json_encode($responce);
            return;
        }
    } elseif ($mode == "member_setting") {
        // Function to convert 1 to 'Y' and 0 to 'N'
        function convertZeroOneToYesNo($data)
        {
            foreach ($data as $key => $value) {
                if ($value === '1') {
                    $data[$key] = 'Yes';
                } elseif ($value === '0') {
                    $data[$key] = 'No';
                }
            }
            return $data;
        }

        // Fetch member data
        $fetch_member = "SELECT `member_id` FROM `member_header_all` WHERE `member_id`='$member_id' AND `registration_id`='$agency_id'";
        $res_mem = mysqli_query($mysqli, $fetch_member);
        $arr_mem = mysqli_fetch_assoc($res_mem);

        if (!$arr_mem) {
            $response[] = array("error_code" => 106, "message" => "Invalid Member ID. Please provide correct member id");
            echo json_encode($response);
            return;
        }

        // Fetch member settings
        $fetch_member_setting = "SELECT `agency_id`, `member_id`, `sos`, `geo_location_auto_update`, `manual_recording`, `manual_recording_notification`, `ecrime_reminder` AS `e_crime_reminder`, `alert_when_watch_out_from_gps_range`, `fsn_when_out_of_gps_location`, `bluetooth`, `carrier`, `heart_rate`, `spo2`, `body_temp`, `alert_when_watch_remove`, `alert_on_watch_remove_from_wrist`, `alert_on_watch_removal_after_every`, `alrert_on_sim_remove`, `fsn_for_sim_remove`, `alert_on_watch_to_reconnect_server`, `fsn_watch_server_reconnect`, `pre_schedule_alert`, `fsn_pre_schedule_alert_not_delivered` FROM `member_settings_all` WHERE `member_id`='$member_id' AND `agency_id`='$agency_id'";
        $res_member = mysqli_query($mysqli, $fetch_member_setting);
        $arr_member = mysqli_fetch_assoc($res_member);

        // Fetch factory settings
        $fetch_factory = "SELECT `online_store`, `digital_verification` FROM `factory_setting_header_all`";
        $res_factory = mysqli_query($mysqli, $fetch_factory);
        $arr_factory = mysqli_fetch_assoc($res_factory);

        // Fetch agency setting
        $fetch_agency_setting = "SELECT `app_setting` FROM `agency_setting_all` WHERE `agency_id`='$agency_id'";
        $result_setting = mysqli_query($mysqli, $fetch_agency_setting);
        $arr_setting = mysqli_fetch_assoc($result_setting);

        // Merge data
        $data[] = array_merge(
            convertZeroOneToYesNo($arr_factory),
            convertZeroOneToYesNo($arr_member),
            convertZeroOneToYesNo($arr_setting)
        );

        // Check if data is not empty
        if (!empty($data)) {
            $response[] = ["error_code" => 100, "message" => "Data fetch successfully", "data" => $data];
            echo json_encode($response);
            return;
        } else {
            $response = [
                [
                    "error_code" => 100,
                    "message" => "Data fetch successfully",
                    "data" => [
                        [
                            "agency_id" => $agency_id,
                            "member_id" => $member_id,
                            "app_setting" => "",
                            "sos" => "",
                            "geo_location_auto_update" => "",
                            "manual_recording" => "",
                            "manual_recording_notification" => "",
                            "e_crime_reminder" => "",
                            "alert_when_watch_out_from_gps_range" => "",
                            "fsn_when_out_of_gps_location" => "",
                            "bluetooth" => "",
                            "carrier" => "",
                            "heart_rate" => "",
                            "spo2" => "",
                            "body_temp" => "",
                            "alert_when_watch_remove" => "",
                            "alert_on_watch_remove_from_wrist" => "",
                            "alert_on_watch_removal_after_every" => "",
                            "alrert_on_sim_remove" => "",
                            "fsn_for_sim_remove" => "",
                            "alert_on_watch_to_reconnect_server" => "",
                            "fsn_watch_server_reconnect" => "",
                            "pre_schedule_alert" => "",
                            "fsn_pre_schedule_alert_not_delivered" => "",
                            "online_store" => $arr_factory['online_store'],
                            "digital_verification" => $arr_factory['digital_verification']
                        ]
                    ]
                ]
            ];

            echo json_encode($response);
            return;
        }
    }
}



function replaceNullWithEmptyString($value)
{
    return is_null($value) ? '' : $value;
}

function common_check_error($mysqli, $mode, $agency_id,  $member_id, $application_id)
{

    $check_error = 1;
    if (!$mysqli) {
        $response[] = ["error_code" => 101, "message" => "An unexpected server error occurred while
        processing your request. Please try after sometime"];
        echo json_encode($response);
        return;
    }

    if ($_SERVER["REQUEST_METHOD"] != "POST") {
        $responce[] = array("error_code" => 107, "message" => "please change request method to POST");
        echo json_encode($responce);
        return;
    }
    if (!isset($mode)) {
        $response[] = ["error_code" => 108, "message" => "Please the parameter - mode"];
        echo json_encode($response);
        return;
    } else {
        if ($mode == "") {
            $response[] = ["error_code" => 109, "message" => "Value of 'mode' cannot be empty"];
            echo json_encode($response);
            return;
        }
    }
    if ($mode != "agency_setting" && $mode != "member_setting") {
        $response[] = ["error_code" => 105, "message" => "Incorrect mode. Please provide the correct mode."];
        echo json_encode($response);
        return;
    }

    if (!isset($agency_id)) {
        $response[] = ["error_code" => 108, "message" => "Please the parameter - agency_id"];
        echo json_encode($response);
        return;
    } else {
        if ($agency_id == "") {
            $response[] = ["error_code" => 109, "message" => "Value of 'agency_id' cannot be empty"];
            echo json_encode($response);
            return;
        }
    }
    if ($mode == "member_setting") {
        if (!isset($member_id)) {
            $response[] = ["error_code" => 110, "message" => "Please the parameter - member_id"];
            echo json_encode($response);
            return;
        } else {
            if ($member_id == "") {
                $response[] = ["error_code" => 111, "message" => "Value of 'member_id' cannot be empty"];
                echo json_encode($response);
                return;
            }
        }
    }





    if (!isset($application_id)) {
        $response[] = ["error_code" => 112, "message" => "Please the parameter - application_id"];
        echo json_encode($response);
        return;
    } else {
        if ($application_id == "") {
            $response[] = ["error_code" => 113, "message" => "Value of 'application_id' cannot be empty"];
            echo json_encode($response);
            return;
        }
    }

    return $check_error;
}
