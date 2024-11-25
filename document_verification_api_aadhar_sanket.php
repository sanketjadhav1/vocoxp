<?php
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);
// Get the raw POST data

include 'connection.php';
$connection = connection::getInstance();
$mysqli = $connection->getConnection();

$connection1 = database::getInstance();
$mysqli1 = $connection1->getConnection();

$system_datetime = date('Y-m-d H:i:s');

$visitor_id = $_POST['visitor_id'] ?? '';
$payment_type = $_POST['payment_type'] ?? '';  /* wallet/end user */
$base_amount = $_POST['base_amount'] ?? '';
$sgst_amount = $_POST['sgst_amount'] ?? '';
$cgst_amount = $_POST['cgst_amount'] ?? '';
if ($base_amount != '') {
    $total_amount = $base_amount + $sgst_amount + $cgst_amount;
} else {
    $total_amount = '';
}

$name = $_POST['name'];
$dob = $_POST['dob'];
//$dob = date("Y-m-d", strtotime($_POST['date_of_birth']));
$address = $_POST['address'];
$gender = $_POST['gender'];
$aadhar_number = $_POST['aadhar_number'];
$front_photo = $_POST['front_photo'] ?? '';
$back_photo = $_POST['back_photo'] ?? '';
$user_photo = $_POST['user_photo'] ?? '';
$generated_by = $_POST['generated_by'];
$is_athenticate = $_POST['is_athenticate'];

$check_error = check_error($mysqli, $visitor_id);
if ($check_error == 1) {
    //get visitor details
    $visitor_temp_detail_all = "SELECT * FROM `visitor_temp_activity_detail_all` WHERE `visitor_id` = '$visitor_id'";
    $res_visitor_detail = mysqli_query($mysqli, $visitor_temp_detail_all);
    $visitor_temp_detail_arr = mysqli_fetch_assoc($res_visitor_detail);

    $agency_id = $visitor_temp_detail_arr['agency_id'];
    $emp_id = $visitor_temp_detail_arr['meeting_with'];

    //get payment details of employee
    $employee_header_all_all = "SELECT `verification_paid_by` FROM `employee_header_all` WHERE `emp_id` = '$emp_id'";
    $res_employee_detail = mysqli_query($mysqli, $employee_header_all_all);
    $employee_detail_arr = mysqli_fetch_assoc($res_employee_detail);
    $verification_paid_by = $employee_detail_arr['verification_paid_by'];


    try {
        if ($verification_paid_by == 'W') {  //check current wallet balance if payment from wallet
            $fetch_wallet = "SELECT `current_wallet_bal` FROM `agency_header_all` WHERE `agency_id`='$agency_id'";
            $res_wallet = mysqli_query($mysqli, $fetch_wallet);
            $arr_wallet = mysqli_fetch_assoc($res_wallet);
            if ($arr_wallet['current_wallet_bal'] < $total_amount) {
                $responce = ["error_code" => 113, "message" => "Your wallet balance is too Low To proceed. Please recharge your wallet."];
                echo json_encode($responce);
                return;
            }

            //get total verification rate & gst for aadhar card 
            //DVF-00001'= aadhar card id
            $verification_id = $visitor_temp_detail_all['verification_type'];
            $verify_query = "SELECT * FROM verification_configuration_all WHERE verification_id='$verification_id' AND ver_type='1' AND operational_status='1'";
            $verify_res = $mysqli1->query($verify_query);
            $verify_row = $verify_res->fetch_assoc();

            $sgst_percentage = $verify_row["sgst_percentage"];
            $cgst_percentage = $verify_row["cgst_percentage"];
            $rate = $verify_row["rate"];
            $gst = $sgst_percentage + $cgst_percentage;
            $total_gst = ($rate * $gst) / 100;
            $total_rate = ($total_gst * 1) + ($rate * 1);
            $sgst_amount = ($rate * $sgst_percentage) / 100;
            $cgst_amount = ($rate * $cgst_percentage) / 100;

            $current_wallet_bal = $arr_wallet['current_wallet_bal'] - $total_rate;

            //update current wallet balance
            $update_wallet = "UPDATE `agency_header_all` SET `current_wallet_bal` = '$current_wallet_bal' WHERE `agency_id` = '$agency_id'";
            $updatesqlQuery =  $mysqli->query($update_wallet);
            if (!$updatesqlQuery) {
                throw new Exception("Failed to Update into agency_header_all");
            }

            //insert wallet payment transaction
            $wallet_trans_query = "INSERT INTO `wallet_payment_transaction_all` (`agency_id`,`user_id`,`requested_from`,`purchase_type`,`verification_id`,`base_amount`,`cgst_amount`,`sgst_amount`,`transaction_on`,`transaction_id`,`line_type`,`quantity`,`settled_for`,`ref_transaction_id`) VALUES ('$agency_id','$user_id','$requested_from','$purchase_type','$verification_id', '$rate', '$cgst_amount','$sgst_amount', '$system_datetime', '$transaction_id','$line_type','$quantity','$settled_for','$ref_transaction_id')";

            $insert_wallet_trans =  $mysqli->query($wallet_trans_query);
            if (!$insert_wallet_trans) {
                throw new Exception("Failed to Insert into wallet_payment_transaction_all");
            }
        } else {

            //hit api

        }


        //insert aadhar card details visitor_aadhar_details_all (aadhar ambiguity details)
        $activity_status = 0;  //0 = default initiated
        $ambiguity_details = '';
        $report_url = '';

        //check data is matching or for ambiguity common data for all types of documents
        if ($visitor_temp_detail_arr['visitor_name'] != $name) {
            $ambiguity = 'name@' . $visitor_temp_detail_arr['visitor_name'] . '!' . $name;
            $ambiguity_details = $ambiguity;
        }
        if ($visitor_temp_detail_arr['dob'] != $dob) {
            $ambiguity = 'dob@' . $visitor_temp_detail_arr['dob'] . '!' . $dob;
            $ambiguity_details = ($ambiguity_details != '') ? ',' . $ambiguity : $ambiguity;
        }
        if ($visitor_temp_detail_arr['address'] != $address) {
            $ambiguity = 'address@' . $visitor_temp_detail_arr['address'] . '!' . $address;
            $ambiguity_details = ($ambiguity_details != '') ? ',' . $ambiguity : $ambiguity;
        }
        if ($visitor_temp_detail_arr['gender'] != $gender) {
            $ambiguity = 'gender@' . $visitor_temp_detail_arr['gender'] . '!' . $gender;
            $ambiguity_details = ($ambiguity_details != '') ? ',' . $ambiguity : $ambiguity;
        }
        if ($visitor_temp_detail_arr['aadhar_number'] != $aadhar_number) {
            $ambiguity = 'aadhar_number@' . $visitor_temp_detail_arr['aadhar_number'] . '!' . $aadhar_number;
            $ambiguity_details = ($ambiguity_details != '') ? ',' . $ambiguity : $ambiguity;
        }

        $insert_aadhar_query = "INSERT INTO `visitor_aadhar_details_all` (`visitor_id`,`agency_id`,`initiated_on`,`completed_on`,`activity_status`,`aadhar_number`,`name`,`dob`,`gender`,`address`,`front_photo`,`back_photo`,`user_photo`,`generated_by`,`is_athenticate`,`aadhar_ambiguity`,`report_url`) VALUES ('$visitor_id','$agency_id','$system_datetime','$system_datetime','$activity_status','{$visitor_temp_detail_arr['aadhar_number']}', '{$visitor_temp_detail_arr['visitor_name']}','{$visitor_temp_detail_arr['dob']}','{$visitor_temp_detail_arr['gender']}', '{$visitor_temp_detail_arr['address']}','$front_photo', '$back_photo','$user_photo','$generated_by','$is_athenticate','$ambiguity_details','$report_url')";

        $insert_aadhar_detail =  $mysqli->query($insert_aadhar_query);
        if (!$insert_aadhar_detail) {
            throw new Exception("Failed to Insert into visitor_aadhar_details_all");
        }


        $mysqli->commit();
        $response[] = array("error_code" => 100, "message" => "Records Inserted successfully");
    } catch (Exception $e) {
        $mysqli->rollback();
        $response[] = array("error_code" => 103, "message" => $e->getMessage());
    }

    echo json_encode($response);
}
