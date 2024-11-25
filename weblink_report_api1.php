<?php
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');
date_default_timezone_set('Asia/Kolkata');

include 'connection.php';
$connection = connection::getInstance();
$mysqli = $connection->getConnection();
$agency_id = $_GET['agency_id'];
$bulk_id = $_GET['bulk_id'];

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    $check_error = check_error($agency_id, $bulk_id);
    if ($check_error == 1) {

        $verification_for = $_GET['verification_for'];
        $check_agncy_id_query = "SELECT `agency_id` FROM `agency_header_all` WHERE `agency_id` = '$agency_id'";
        $check_agncy_id_res = $mysqli->query($check_agncy_id_query);
        $check_agncy_id_row = $check_agncy_id_res->num_rows;

        if ($check_agncy_id_row != 1) {
            $response = array("error_code" => 124, "message" => "Invalid 'agency_id'");
            echo json_encode($response);
            exit;
        }

        $ambiguity_column = '';
        switch ($verification_for) {
            case 1:
                $ambiguity_column = 'aadhar_ambiguity';
                break;
            case 2:
                $ambiguity_column = 'pan_ambiguity';
                break;
            case 3:
                $ambiguity_column = 'dl_ambiguity';
                break;
                
            case 4:
                $ambiguity_column = 'voter_ambiguity';
                break;
       
            case 5:
                $ambiguity_column = 'passport_ambiguity';
                break;
            
             
            default:
                $select_All_Query = "SELECT end_user_id, first_name, document_type, aadhar_ambiguity, pan_ambiguity, dl_ambiguity, voter_ambiguity, passport_ambiguity, report_url, completed_on 
            FROM end_user_verification_transaction_all 
            WHERE agency_id = '$agency_id' AND weblink_id = '$bulk_id'";

                $res_select_All_Query = $mysqli->query($select_All_Query);
                if ($res_select_All_Query->num_rows > 0) {
                    $data = array();
                    while ($row = $res_select_All_Query->fetch_assoc()) {
                        $userData = array(
                            "end_user_id" => $row["end_user_id"],
                            "name" => $row["first_name"],
                            "verification_type" => $row["document_type"]
                        );
                        if (!empty($row["aadhar_ambiguity"])) {
                            $userData["aadhar_report"] = ($row["aadhar_ambiguity"] == "ok=all") ? "yes" : "no";
                        }
                        if (!empty($row["pan_ambiguity"])) {
                            $userData["pan_report"] = ($row["pan_ambiguity"] == "ok=all") ? "yes" : "no";
                        }
                        if (!empty($row["voter_ambiguity"])) {
                            $userData["voter_report"] = ($row["voter_ambiguity"] == "ok=all") ? "yes" : "no";
                        }
                        if (!empty($row["passport_ambiguity"])) {
                            $userData["passport_report"] = ($row["passport_ambiguity"] == "ok=all") ? "yes" : "no";
                        }
                        if (!empty($row["dl_ambiguity"])) {
                            $userData["dl_report"] = ($row["dl_ambiguity"] == "ok=all") ? "yes" : "no";
                        }

                        $userData["report_url"] = $row["report_url"];
                        $userData["completed_on"] = $row["completed_on"];
                        $data[] = $userData;
                    }

                    // Response
                    $response = array(
                        "error_code" => 100,
                        "message" => "Data fetched",
                        "data" => $data
                    );
                    echo json_encode($response);
                } else {
                    $response = array("error_code" => 125, "message" => "No records found");
                    echo json_encode($response);
                }
        }


        $select_query = "SELECT end_user_id, first_name, document_type, $ambiguity_column AS ambiguity, report_url, completed_on 
                         FROM end_user_verification_transaction_all 
                         WHERE agency_id = '$agency_id' 
                         AND weblink_id = '$bulk_id'
                         AND $ambiguity_column IS NOT NULL 
                         AND $ambiguity_column != ''";

        $res_select_query = $mysqli->query($select_query);

        if ($res_select_query->num_rows > 0) {
            $data = array();

            while ($row = $res_select_query->fetch_assoc()) {
                $is_report_ok = ($row['ambiguity'] == "ok=all") ? "yes" : "no";
                $report_key = str_replace('ambiguity', 'report', $ambiguity_column);
                $data[] = array(
                    "end_user_id" => $row["end_user_id"],
                    "name" => $row["first_name"],
                    "verification_type" => $row["document_type"],
                    $report_key =>  $is_report_ok,
                    "report_url" => $row["report_url"],
                    "completed_on" => $row["completed_on"]
                );
            }

            $response = array(
                "error_code" => 100,
                "message" => "Data fetched",
                "data" => $data
            );
            echo json_encode($response);
        } else {
            $response = array("error_code" => 125, "message" => "No records found");
            echo json_encode($response);
        }
    }
}

function check_error($agency_id, $bulk_id)
{
    if (empty($agency_id)) {
        $response = array("error_code" => 103, "message" => "The parameter 'agency_id' is required and cannot be empty");
        echo json_encode($response);
        exit;
    }
    if (empty($bulk_id)) {
        $response = array("error_code" => 103, "message" => "The parameter 'bulk_id' is required and cannot be empty");
        echo json_encode($response);
        exit;
    }
    return 1;
}
