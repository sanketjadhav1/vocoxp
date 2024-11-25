<?php
/* 
Name : json_for_info_doc_verification_list.php
Version of the Requirment Document  : 2.0.0


Purpose :- The digital verification documents API provide information about verification
documents.

Mode :- multi mode

Developed By - Rishabh Shinde
*/
error_reporting(1);
include_once 'connection.php';

 
// Now you can use the connection class
$connection = connection::getInstance();
$mysqli = $connection->getConnection();
$connection1 = database::getInstance();
$mysqli1 = $connection1->getConnection();
apponoff($mysqli);
logout($_SERVER['HTTP_AUTH_KEY'], $mysqli);
// check_digital_verification($mysqli);
$check_error = common_check_error($mysqli, $_POST['agency_id'], $_POST['mode']);

if ($check_error == 1) {

    $agency_id = $_POST['agency_id'];
    $mode = $_POST['mode'];

    $fetch_agency_id = "SELECT `agency_id`, `status` FROM `agency_header_all` WHERE `agency_id`='$agency_id'";
    $result_agency = mysqli_query($mysqli, $fetch_agency_id);
    $arr = mysqli_fetch_assoc($result_agency);

    if (!$arr) {
        $response[] = array("error_code" => 102, "message" => "Invalid agency ID. Please provide a valid agency ID");
        echo json_encode($response);
        return;
    } elseif ($arr['status'] != "1") {
        $response[] = array("error_code" => 103, "message" => "The agency name is currently inactive");
        echo json_encode($response);
        return;
    }

    $fetch_verification = "SELECT `verification_header_all`.`verification_id`, `verification_header_all`.`name`, `verification_header_all`.`abbreviations`, `verification_header_all`.`status`, `verification_header_all`.`req_document`, `verification_header_all`.`image`, `verification_header_all`.`advantages`, `verification_configuration_all`.`rate`, `verification_configuration_all`.`sgst_percentage`, `verification_configuration_all`.`cgst_percentage`
FROM `verification_configuration_all`
INNER JOIN `verification_header_all`
ON `verification_header_all`.`verification_id` = `verification_configuration_all`.`verification_id`
WHERE `verification_header_all`.`status` = '1'
ORDER BY `verification_header_all`.`created_on` ASC;
";

    $result_verification = mysqli_query($mysqli1, $fetch_verification);

    if ($result_verification) {
        $new_arr = array(); // Initialize the array before using it
        while ($arr_verification = mysqli_fetch_assoc($result_verification)) {
            $new_arr[] = $arr_verification;
        }

        // Remove duplicate records based on 'doc_type_id'
        $uniqueVerificationList = [];
        foreach ($new_arr as $val) {
            $docTypeId = $val['verification_id'];
            if (!isset($uniqueVerificationList[$docTypeId])) {
                $uniqueVerificationList[$docTypeId] = $val;
            } else {
                // Handle duplicate (you can choose to ignore, update, or merge)
            }
        }

        // Get the order of specifications dynamically
        $specificationOrder = array_keys($uniqueVerificationList);

        // Sort the uniqueVerificationList based on the dynamically determined order
        usort($uniqueVerificationList, function ($a, $b) use ($specificationOrder) {
            $aOrder = array_search($a['verification_id'], $specificationOrder);
            $bOrder = array_search($b['verification_id'], $specificationOrder);

            // If specification ID is not found in the specification order, maintain existing order
            if ($aOrder === false) $aOrder = PHP_INT_MAX;
            if ($bOrder === false) $bOrder = PHP_INT_MAX;

            return $aOrder - $bOrder;
        });

        $verificationlist = array();

        // Existing code...
        if ($mode == "verification_only") {
            foreach ($uniqueVerificationList as $val) {
                if($val['verification_id']=="DVF-00008") {
                }
                else
                    {
                        $verificationlist[] = [
                    "doc_type_id" => $val['verification_id'],
                    "document_name" => $val['name'],
                    "doc_process_name" => $val['abbreviations'],
                    "type" => '',
                    "type_id" => '',
                    "status" => $val['status'],
                    "front_img" => $val['image'],
                    "back_img" => $val['image'],
                    "verification_charge" => $val['rate'],
                    "advantages_list" => $val['advantages'],
                    "requirements_list" => $val['req_document'],
                    "cgst" => $val['cgst_percentage'],
                    "sgst" => $val['sgst_percentage']
                ];
                    }
                
            }
            $response[] = (!empty($verificationlist)) ? ["error_code" => 100, "message" => "Success", "verification_doc_list" => $verificationlist] : ["error_code" => 100, "message" => "Data not found"];

            echo json_encode($response);
            die();
        } elseif ($mode == "verification_all") {
            foreach ($uniqueVerificationList as $val) {
                $verificationlist[] = [
                    "doc_type_id" => $val['verification_id'],
                    "document_name" => $val['name'],
                    "doc_process_name" => $val['abbreviations'],
                    "type" => '',
                    "type_id" => '',
                    "status" => $val['status'],
                    "front_img" => $val['image'],
                    "back_img" => $val['image'],
                    "verification_charge" => $val['rate'],
                    "advantages_list" => $val['advantages'],
                    "requirements_list" => $val['req_document'],
                    "cgst" => $val['cgst_percentage'],
                    "sgst" => $val['sgst_percentage']
                ];
            }



            $response[] = (!empty($verificationlist)) ? ["error_code" => 100, "message" => "Success", "verification_doc_list" => $verificationlist] : ["error_code" => 100, "message" => "Data not found"];

            echo json_encode($response);
            die();
        }
        // Add three more objects to $verificationlist


        $verificationlist[] = [
            "doc_type_id" => "S2345678901",
            "document_name" => "Smart watch",
            "doc_process_name" => "",
            "type" => '',
            "type_id" => '',
            "status" => "Active",
            "front_img" => "http://example.com/images/example2.jpeg",
            "back_img" => "http://example.com/images/example2.jpeg",
            "verification_charge" => "3.00",
            "advantages_list" => "Some advantages for Example Document 2",
            "requirements_list" => "Some requirements for Example Document 2"
        ];

        $verificationlist[] = [
            "doc_type_id" => "S3456789012",
            "document_name" => "Storage Plan",
            "doc_process_name" => "ED3",
            "type" => '',
            "type_id" => '',
            "status" => "Active",
            "front_img" => "http://example.com/images/example3.jpeg",
            "back_img" => "http://example.com/images/example3.jpeg",
            "verification_charge" => "4.00",
            "advantages_list" => "Some advantages for Example Document 3",
            "requirements_list" => "Some requirements for Example Document 3"
        ];

        $response[] = (!empty($verificationlist)) ? ["error_code" => 100, "message" => "Success", "verification_doc_list" => $verificationlist] : ["error_code" => 100, "message" => "Data not found"];

        echo json_encode($response);
        die();
    }
}

function common_check_error($mysqli, $agency_id)
{

    $check_error = 1;
    if (!$mysqli) {
        $response[] = ["error_code" => 101, "message" => "An unexpected server error occurred while
        processing your request. Please try after sometime"];
        echo json_encode($response);
        return;
    }
    if ($_SERVER["REQUEST_METHOD"] != "POST") {
        $responce[] = array("error_code" => 106, "message" => "please change request method to POST");
        echo json_encode($responce);
        return;
    }
    if (!isset($agency_id)) {
        $response[] = ["error_code" => 104, "message" => "Please the parameter - agency_id"];
        echo json_encode($response);
        return;
    } else {
        if ($agency_id == "") {
            $response[] = ["error_code" => 105, "message" => "Value of 'agency_id' cannot be empty"];
            echo json_encode($response);
            return;
        }
    }

    return $check_error;
}
