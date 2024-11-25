<?php
/* 
Name: json_for_action_block_member.php
Version of the Requirement Document: 2.0.0

Purpose: This JSON will give the action of Block Members in the agency
Mode: single mode

Developed By: Rishabh Shinde
*/
error_reporting(E_ALL);
include_once 'connection.php';

// Now you can use the connection class
$connection = connection::getInstance();
$mysqli = $connection->getConnection();
$connection1 = database::getInstance();
$mysqli1 = $connection1->getConnection();
apponoff($mysqli);
logout($_SERVER['HTTP_AUTH_KEY'], $mysqli);
$check_error = check_error($mysqli, $_POST['agency_id'], $_POST['gstin_no']);

if ($check_error == 1) {
    // Check GST number
    

    $agency_id = $_POST['agency_id'];
    $gstin_no = $_POST['gstin_no'];
    
    $fetch_agency_id = "SELECT `status`, `agency_id`, `agency_gst_no` FROM `agency_header_all` WHERE `agency_id`='$agency_id'";
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
    
    $validGST = validateGST($_POST['gstin_no']);
    if ($validGST !== true) {
        echo $validGST; // Return error response for invalid GST number
        return;
    }

    $fetch_verification="SELECT `id`, `invoice_url` FROM `verification_payment_transaction_all` WHERE `agency_id`='$agency_id'";
    $res_verification=mysqli_query($mysqli1, $fetch_verification);
    $payment_row = mysqli_num_rows($res_verification);


if($arr['agency_gst_no']=="" || $payment_row==0){
    $update_gst = "UPDATE `agency_header_all` SET `agency_gst_no`='$gstin_no' WHERE `agency_id`='$agency_id'";
    $res_update = mysqli_query($mysqli, $update_gst);
    if ($res_update == true) {
        $response[] = array( "error_code" => 100, "message" => "GSTIN No updated successfully");
        echo json_encode($response);
        exit; 
    }
}else{
    $response[] = array( "error_code" => 109, "message" => "Can't update GSTIN once you have done verification");
        echo json_encode($response);
        exit;
}

}

function validateGST($gstin_no) {
    // Regular expression for GST number validation
    $regex = '/^\d{2}[A-Z]{5}\d{4}[A-Z]{1}[1-9A-Z]{1}[Z]{1}[A-Z\d]{1}$/';
    
    if (preg_match($regex, $gstin_no)) {
        return true; // GST number is valid
    } else {
        $response[] = array( "error_code" => 109, "message" => "Please enter a correct GST number");
        return json_encode($response); // Return JSON response
    }
}

function check_error($mysqli, $agency_id, $gstin_no) {
    $check_error = 1;
    if (!$mysqli) {
        $response[] = array("error_code" => 101, "message" => "An unexpected server error occurred while processing your request. Please try again later.");
        echo json_encode($response);
        return;
    }
    if ($_SERVER["REQUEST_METHOD"] != "POST") {
        $response[] = array("error_code" => 108, "message" => "Please change request method to POST");
        echo json_encode($response);
        return; 
    }
    if (!isset($agency_id)) {
        $response[] = array("error_code" => 104, "message" => "Please provide the parameter - agency_id");
        echo json_encode($response);
        return;
    } else {
        if ($agency_id == "") {
            $response[] = array("error_code" => 105, "message" => "Value of 'agency_id' cannot be empty");
            echo json_encode($response);
            return;
        }
    }
    
    if (!isset($gstin_no)) {
        $response[] = array("error_code" => 106, "message" => "Please provide the parameter - gstin_no");
        echo json_encode($response);
        return;
    } else {
        if ($gstin_no == "") {
            $response[] = array("error_code" => 107, "message" => "Value of 'gstin_no' cannot be empty");
            echo json_encode($response);
            return;
        }
    }
    
    return $check_error;
}
?>
