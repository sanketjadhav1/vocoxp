<?php
error_reporting(E_ALL & ~E_DEPRECATED);
ini_set('display_errors', 0);

include __DIR__ . '/vendor/autoload.php';
include_once 'connection.php';

header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');

// Database connection
$connection = connection::getInstance();
$mysqli = $connection->getConnection();

$connection1 = database::getInstance();
$mysqli1 = $connection1->getConnection();

apponoff($mysqli);  
logout($_SERVER['HTTP_AUTH_KEY'], $mysqli);

$system_date = date("Y-m-d H:i:s");
$agency_id = $_GET['agency_id'] ?? "";
$app_id = $_GET['app_id'] ?? "";
$visitor_id = $_GET['visitor_id'] ?? ""; 

// Validate inputs
if (($error = check_error($mysqli, $mysqli1, $agency_id, $visitor_id)) !== 1) {
    echo json_encode($error);
    exit;
}

// Fetch visitor details
$visitor_query = "SELECT * FROM `visitor_temp_activity_detail_all` WHERE `visitor_id`='$visitor_id' AND `agency_id`='$agency_id'";
$visitor_res = $mysqli->query($visitor_query);

if ($visitor_res && $visitor_res->num_rows > 0) {
    // echo "hello";
    $visitor_row = $visitor_res->fetch_assoc();
    $visitor_name = $visitor_row["visitor_name"];
    $visitor_email = $visitor_row["visitor_email"];
    $visitor_mobile = $visitor_row["visitor_mobile"];
    $meeting_with = $visitor_row["meeting_with"];
    $visitor_location_id = $visitor_row["visitor_location_id"];
    $mode = $visitor_row["mode"];
    $verification_id = $visitor_row["verification_type"];

    // Fetch employee details
    $emp_query = "SELECT * FROM `employee_header_all` WHERE `emp_id`='$meeting_with' AND `agency_id`='$agency_id'";
    $emp_res = $mysqli->query($emp_query);
    $emp_row = $emp_res->fetch_assoc();
    $is_approval_required = $emp_row["visitor_approval_required"];
    $is_approval_accepted = $emp_row["status"];
    $emp_designation = $emp_row["designation"];
    $emp_name = $emp_row["name"];
    $emp_id = $emp_row["emp_id"];
    $verification_paid_by = $emp_row["verification_paid_by"];

    $payment_completed = "";
    $visiting_pass_valid_upto = "";

    $visitr_amt_query = "SELECT * FROM `visitor_location_setting_details_all` WHERE `visitor_location_id`='$visitor_location_id'  AND `agency_id`='$agency_id'";
    $visitr_amt_res = $mysqli->query($visitr_amt_query);
    $visitr_amt_row = $visitr_amt_res->fetch_assoc();
	$verification_amt=$visitr_amt_row["verification_amt"];
	  $visiting_charges=$visitr_amt_row["visiting_charges"];
    // $verification_amt = "0";
    // $visiting_charges = "1.2";

    $visitor_entry_through = get_entry_mode($mode, $verification_id);
    if($verification_id != "MVF-00000")
     {

            $verify_query = "SELECT * FROM `verification_configuration_all` WHERE `verification_id`='$verification_id' AND `ver_type`='1'";
            $verify_res = $mysqli1->query($verify_query);
            $verify_row = $verify_res->fetch_assoc();

            $sgst_percentage = $verify_row["sgst_percentage"];
            $cgst_percentage = $verify_row["cgst_percentage"];
            $rate = $verify_row["rate"];
            $gst = $sgst_percentage + $cgst_percentage; 
            $total_gst = ($rate * $gst) / 100; 
            
    } else {
        $total_gst = 0;
        $rate = 0;
    }
    $verification_total_amt = $verification_amt + $total_gst + $rate;
            $verification_charge = $verification_total_amt + $visiting_charges;
            $verification_charge1 = number_format($verification_charge, 2, '.', '');
    if (!empty($visiting_charges) && $visiting_charges != 0) {
      
        // $amount_in_paise = $verification_charge1 * 100; // Convert to paise
        $amount_in_paise = 1 * 100;

        // $key_id = 'rzp_test_EHLRuyAduIUaEe';
        // $secret = 'g62rHYGOgkwDsDseFygZc1GW';
        $key_id = 'rzp_live_wXmbkXPVVABiDl';
		$secret = 'h0QEqFVRQsYlzhFSgML3Dl2J';
        $headers = [
            'Content-Type: application/json',
            'Authorization: Basic ' . base64_encode($key_id . ':' . $secret)
        ];

        // Create customer on Razorpay
        $customerId = create_customer($visitor_name, $visitor_email, $visitor_mobile, $headers);
        if (!$customerId) {
            echo json_encode(['error_code' => 101, 'message' => 'Failed to create or retrieve customer']);
            exit;
        }

        // Create Razorpay QR code
        $qrRes = create_qr_code($visitor_name, $amount_in_paise, $customerId, $verification_charge1, $headers);
        if (isset($qrRes['image_url'])) {
            $qrID = $qrRes['id'];
            $qrImage = $qrRes['image_url'];
            $date = date("Y-m-d");
            $transaction_id = 'v_txn_' . time() . random_int(100000, 999999);

            $sql_pay = "INSERT INTO `visitor_payment_transaction_all` (`agency_id`, `date`, `visitor_id`, `paid_amount`, `verification_amount`, `visiting_amount`, `paid_from`, `paid_via`, `gateway_id`, `inserted_on`, `payment_status`, `v_transaction_id`) VALUES ('$agency_id', '$date', '$visitor_id', '$verification_charge1', '$verification_total_amt', '$visiting_charges', '0', 'QR', '$qrID', '$system_date', 'failed', '$transaction_id')";
            mysqli_query($mysqli, $sql_pay);

            $data_res = format_response($is_approval_required, $is_approval_accepted, $visitor_entry_through, $payment_completed, $verification_paid_by, $verification_total_amt, $verification_id, $verification_charge1, $emp_id, $emp_name, $emp_designation, $visiting_pass_valid_upto, $qrImage, $qrID, $transaction_id);
            echo json_encode(["error_code" => 100, "message" => "Visitor Details Successfully Fetched.", "data" => $data_res]);
            exit;
        } else {
            echo json_encode(["error_code" => 101, "message" => "QR code not generated."]);
            exit;
        }
    } else {
        $data_res = format_response($is_approval_required, $is_approval_accepted, $visitor_entry_through, $payment_completed, $verification_paid_by, $verification_total_amt, $verification_id, $verification_charge1, $emp_id, $emp_name, $emp_designation, $visiting_pass_valid_upto, "", "", "");
        echo json_encode(["error_code" => 100, "message" => "Visitor Details Successfully Fetched.", "data" => $data_res]);
        exit;
    }
} else {
    echo json_encode(["error_code" => 101, "message" => "Record not found."]);
    exit;
}
 

// Helper Functions

function check_error($mysqli, $mysqli1, $agency_id, $visitor_id) {
     $check_error = 1;

    if (!$mysqli) { 
        $response = ["error_code" => 101, "message" => "Unable to proceed your request, please try again after some time"]; 
        echo json_encode($response); 
        return; 
    }
 
    if ($_SERVER['REQUEST_METHOD'] != "GET") { 
        $response = array("error_code" => 102, "message" => "Request Method is not Post"); 
        echo json_encode($response); 
        return; 
    } 
  
    if (!isset($agency_id)) { 
        $response = ["error_code" => 105, "message" => "Please pass the parameter of agency_id"]; 
        echo json_encode($response); 
        return; 
    }

    if (empty($agency_id)) { 
        $response = ["error_code" => 106, "message" => "agency_id can not be empty"]; 
        echo json_encode($response); 
        return; 
    }



    if (!isset($visitor_id)) { 
        $response = ["error_code" => 105, "message" => "Please pass the parameter of visitor_id"]; 
        echo json_encode($response); 
        return; 
    }

    if (empty($visitor_id)) { 
        $response = ["error_code" => 106, "message" => "visitor_id can not be empty"]; 
        echo json_encode($response); 
        return; 
    } 
    return $check_error;
}

function get_entry_mode($mode, $verification_id) {
    if ($mode == 1 && $verification_id != "MVF-00000") {
        return "id_scan_verification";
    } elseif ($mode == 1 && $verification_id == "MVF-00005") {
        return "manual_id_verification";
    } else {
        return "manual_entry_without_verification";
    }
}
function create_customer($name, $email, $contact, $headers) {
    // Razorpay API call to create or retrieve customer
    $note="customer create";
    // Visitor data
					$postdata = array(
					    "name" => $name,
					    "email" => $email,
					    "contact" => $contact,
					    "notes" => array(
					        "notes_key_1" => $note,
					        "notes_key_2" => ""
					    )
					);

					// Create customer on Razorpay
					$curl = curl_init();
					curl_setopt_array($curl, array(
					    CURLOPT_URL => 'https://api.razorpay.com/v1/customers',
					    CURLOPT_RETURNTRANSFER => true,
					    CURLOPT_CUSTOMREQUEST => 'POST',
					    CURLOPT_POSTFIELDS => json_encode($postdata),
					    CURLOPT_HTTPHEADER => $headers,
					));

					$response = curl_exec($curl);
					curl_close($curl);

					$customerRes = json_decode($response, true);

					if (isset($customerRes['error'])) {
					    // Check if error is due to customer already existing
					    if ($customerRes['error']['code'] == 'BAD_REQUEST_ERROR') {
					        // Retrieve customer ID from the error message
					        // Assuming visitor_email is unique; use Razorpay's customer search endpoint to retrieve ID
					        $curl = curl_init();
					        curl_setopt_array($curl, array(
					            CURLOPT_URL => 'https://api.razorpay.com/v1/customers?email=' . $email,
					            CURLOPT_RETURNTRANSFER => true,
					            CURLOPT_HTTPHEADER => $headers,
					        ));
					        
					        $response = curl_exec($curl);
					        curl_close($curl);

					        $existingCustomer = json_decode($response, true);
					        if (isset($existingCustomer['items'][0]['id'])) {
					            $customerId = $existingCustomer['items'][0]['id'];
					        } else {
					            echo json_encode(['error_code' => '101', 'message' => 'Unable to retrieve existing customer']);
					            exit;
					        }
					    } else {
					        // Handle other errors
					        $errMessage = $customerRes['error']['description'];
					        echo json_encode(['res' => 'error', 'message' => $errMessage]);
					        exit;
					    }
					} else {
					    $customerId = $customerRes['id'];
					}
    return $customerId ?? null;
}

 function create_qr_code($name, $amount_in_paise, $customerId, $total, $headers) {
    // Razorpay API call to create QR code
    $qrNote = "QR code payment of " . ($amount_in_paise / 100) . " INR"; // Amount should be displayed in INR
    $pdesc = "Razorpay QR code Payment";

    // QR code request data
    $qrpostData = array(
        "type" => "upi_qr",
        "name" => $name,
        "usage" => "single_use",
        "fixed_amount" => true,
        "payment_amount" => $amount_in_paise,
        "description" => $pdesc,
        "customer_id" => $customerId,
        "notes" => array(
            "purpose" => $qrNote
        )
    );

    // Initialize CURL and make API request to Razorpay
    $curl1 = curl_init();
    curl_setopt_array($curl1, array(
        CURLOPT_URL => 'https://api.razorpay.com/v1/payments/qr_codes',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode($qrpostData),
        CURLOPT_HTTPHEADER => $headers,
    ));

    $response1 = curl_exec($curl1);

    // Error handling for CURL request
    if (curl_errno($curl1)) {
        echo json_encode([
            "error_code" => 101,
            "message" => "CURL Error: " . curl_error($curl1)
        ]);
        curl_close($curl1);
        exit;
    }

    curl_close($curl1);

    // Decode the JSON response
    $qr_response = json_decode($response1, true);

    // Check if there was an error in the Razorpay API response
    if (isset($qr_response['error'])) {
        echo json_encode([
            "error_code" => 101,
            "message" => "Razorpay Error: " . $qr_response['error']['description']
        ]);
        exit;
    }

    // Return QR response if successful
    return $qr_response ?? null;
}

 
 

function format_response($approval_required, $approval_accepted, $entry_mode, $payment_completed, $paid_by, $verification_amt, $verification_id, $total_charged, $emp_id, $emp_name, $emp_designation, $pass_valid_upto, $qr_image, $qr_id, $transaction_id) {
    return [
        "is_approval_required" => $approval_required,
        "is_approval_accepted" => $approval_accepted,
        "visitor_entry_through" => $entry_mode,
        "payment_completed" => $payment_completed,
        "verification_paid_by" => $paid_by,
        "verification_charge" => number_format($verification_amt, 2, '.', ''),
        "verification_id" => $verification_id,
        "total_amount_charged" => number_format($total_charged, 2, '.', ''),
        "meeting_with_emp_id" => $emp_id,
        "meeting_with_emp_name" => $emp_name,
        "meeting_with_emp_designation" => $emp_designation,
        "visiting_pass_valid_upto" => $pass_valid_upto,
        "qr_image_url" => $qr_image,
        "qr_id" => $qr_id,
        "transaction_id" => $transaction_id
    ];
}
?>
