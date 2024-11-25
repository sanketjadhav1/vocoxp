<?php
error_reporting(0);
use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

include __DIR__ . '/vendor/autoload.php';
include_once 'connection.php';
error_reporting(E_ALL & ~E_DEPRECATED);

$connection = connection::getInstance();
$mysqli = $connection->getConnection();

$connection1 = database::getInstance();
$mysqli1 = $connection1->getConnection();

$system_date = date("Y-m-d H:i:s");
$agency_id = $_POST['agency_id'] ?? "";
$mode = $_POST['mode'] ?? '';
$excel_no = $_POST['excel_no'] ?? "";
$for_which_premises = $_POST['for_which_premises'] ?? "";
$email_id = $_POST['email_id'] ?? "";

$ids = explode(",", $email_id);
$request_no = $_POST['request_no'] ?? "";
$obj_1 = $_POST["obj_1"] ?? "";
$obj_2 = $_POST['obj_2'] ?? "";
$obj_3 = $_POST['obj_3'] ?? "";
$custom_id = null;
 
function convertToJson($input) {
    // Step 1: Remove curly braces and trim extra spaces
    $input = trim($input, "{} ");

    // Step 2: Split into key-value pairs using regex to preserve commas in values
    $pairs = preg_split('/, (?=\w+=)/', $input);

    // Step 3: Initialize an associative array
    $json_array = [];

    foreach ($pairs as $pair) {
        // Step 4: Split each pair by '=' to get key and value
        list($key, $value) = explode('=', $pair, 2); // limit to 2 to handle '=' in the value part
        $key = trim($key);   // Remove any extra spaces around the key
        $value = trim($value, '" '); // Remove any extra quotes or spaces around the value

        // Step 5: Handle case where value ends with '}' from original input
        $value = rtrim($value, '}');

        // Step 6: Assign to array
        $json_array[$key] = $value;
    }

    // Step 7: Convert associative array to JSON
    return json_encode($json_array, JSON_PRETTY_PRINT);
}
$check_error = check_error($agency_id, $excel_no, $email_id,$mode);
// die();
 $json_1 = convertToJson($obj_1);
 // echo "\n";
  $json_2 = convertToJson($obj_2);
 // echo "\n";
  $json_3 = convertToJson($obj_3);
 // die();
$json_data_obj_1 = json_decode($json_1, true); 

$obj_1_value = $json_data_obj_1['for'] ?? ''; 
$obj_1_verifications = $json_data_obj_1['verification_type'] ?? '';

$json_data_obj_2 = json_decode($json_2, true);
$obj_2_value = $json_data_obj_2['for'] ?? '';
$obj_2_verifications = $json_data_obj_2['verification_type'] ?? '';

$json_data_obj_3 = json_decode($json_3, true);
$obj_3_value = $json_data_obj_3['for'] ?? '';
$obj_3_verifications = $json_data_obj_3['verification_type'] ?? '';

if ($check_error == 1) {

    //check agency_id is valid or not valid
    $check_agncy_id_query = "SELECT `agency_id` FROM `agency_header_all` WHERE `agency_id` = '$agency_id'";
    $check_agncy_id_res = $mysqli->query($check_agncy_id_query);
    $check_agncy_id_row = $check_agncy_id_res->num_rows;

    if ($check_agncy_id_row != 1) {
        $response[] = array("error_code" => 124, "message" => "Invalid 'agency_id'");
        echo json_encode($response);
        exit;
    }

    // qyery for get data from sample rule table 
    $excel_attachment_query = "SELECT excel_url, type,user_validation_url FROM sample_excel_definations_all WHERE excel_no = '$excel_no' AND type_for_excel='1'";
    $res_excel_attachment_query = $mysqli->query($excel_attachment_query);
    if ($res_excel_attachment_query->num_rows > 0) {
        $row = $res_excel_attachment_query->fetch_assoc();
        $excel_url = $row['excel_url'];
        $user_validation_url = trim($row['user_validation_url']);
        $type = $row['type'];
    }
    $filename = basename(stripslashes($excel_url));
    $filename_user_validation_url = basename(stripslashes($user_validation_url));


    
    //if  request no is empty then generate
    if ($request_no == "") {
        $request_no = unique_id_generate_bulk('RUS', 'bulk_weblink_request_all', $mysqli, 'request_no');
    }

    // mode for generate web link and update 
    if ($mode == "generate_request") {
        $bulk_id = unique_id_generate_bulk('BUL', 'bulk_weblink_request_all', $mysqli, 'bulk_id');

        $query = "INSERT INTO `bulk_weblink_request_all` (`bulk_id`, `agency_id`, `request_no`, `excel_no`, `status`, `upload_weblink_generated_on`, `upload_weblink`, `custom_id`, `premises_location`, `obj_1`, `obj_2`, `obj_3`, `obj_1_verifications`, `obj_2_verifications`, `obj_3_verifications`,`request_email_sent`) 
                  VALUES ('$bulk_id', '$agency_id', '$request_no', '$excel_no', '1',  '$system_date', '$excel_url', '$custom_id', '$for_which_premises', '$obj_1_value', '$obj_2_value', '$obj_3_value', '$obj_1_verifications', '$obj_2_verifications', '$obj_3_verifications','$system_date')";
                    $res_web = mysqli_query($mysqli, $query);

    } else if ($mode == "update_request") {
        $bulk_id = $_POST['bulk_id'];
        $query = "UPDATE `bulk_weblink_request_all` 
        SET `status` = '1', 
            `upload_weblink_generated_on` = '$system_date', 
            `upload_weblink` = '$excel_url', 
            `custom_id` = '$custom_id', 
            `premises_location` = '$for_which_premises', 
            `obj_1` = '$obj_1_value', 
            `obj_2` = '$obj_2_value', 
            `obj_3` = '$obj_3_value', 
            `obj_1_verifications` = '$obj_1_verifications', 
            `obj_2_verifications` = '$obj_2_verifications', 
            `obj_3_verifications` = '$obj_3_verifications', 
            `request_email_sent` = '$system_date'
        WHERE `request_no` = '$request_no' 
          AND `agency_id` = '$agency_id' 
          AND `bulk_id` = '$bulk_id'";
          

          $res_web = mysqli_query($mysqli, $query);
       

    }else{
        $response = ["error_code" => 103, "message" => "Invalid Mode"];
        echo json_encode($response);
        return;
    }

    // Send email with PHPMailer 
$mail = new PHPMailer(true);

try {
    // Configure PHPMailer for SMTP
    $mail->isSMTP();
    $mail->Host = 'mail.mounarchtech.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'transactions@mounarchtech.com';
    $mail->Password = 'Mtech!@12345678';
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465;

    // Set sender details
    $mail->setFrom('transactions@mounarchtech.com', 'Micro Integrated Semi Conductor Systems Pvt. Ltd.');
    $mail->isHTML(true);

    // Email content
    $mail->Subject = 'New weblink generated for the verifications of ' . $type ;
    $mail->Body = '
        <!DOCTYPE html>
        <html>
        <head>
            <style>
                .email-body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .email-header { background-color: #f0f0f0; padding: 20px; text-align: center; }
                .email-content { padding: 20px; }
                .button { display: inline-block; padding: 10px 20px; margin: 20px 0; font-size: 16px; color: white; background-color: #007BFF; text-decoration: none; border-radius: 5px; }
                .email-footer { padding: 20px; text-align: center; font-size: 12px; color: #777; }
            </style>
        </head>
        <body>
            <div class="email-body">
                <div class="email-content">
                    <p>Dear Recipient,</p>
                    <p>This is to inform you that as per your request, a weblink is generated for the verifications of <b>' . $type . ' </b>. Please download the attachment and fill the data (whose verification is to be done). Once you are done with the data filling, use the link below to upload it and proceed further. </p>
                    <p><a href="https://mounarchtech.com/vocoxp/upload_link/index.php?agency_id=' . $agency_id . '&bulk_id=' . $bulk_id . '">Click here to upload the data</a></p>
                    <p>If you have any questions or require assistance, please reach out to support@microintegrated.in.</p>
                    <p><b>Note: Please do not change the column names and numbers as this will affect data analysis.</b></p>
                    <p>Thank you for your cooperation.</p>
                    <p><b>Best regards,</b></p>
                    <p><b>Micro Integrated Semi Conductor Systems Pvt. Ltd.</b></p>
                </div>
            </div>
        </body>
        </html>';

    // Attach files (if they exist)
    $attachments = [$filename, $filename_user_validation_url];
    foreach ($attachments as $attachment) {
        if (file_exists($attachment)) {
            $mail->addAttachment($attachment);
        } else {
            error_log("Attachment file not found: $attachment");
        }
    }

    // Send email to all recipients
    foreach ($ids as $email) {
        $mail->addAddress($email);
        $mail->send();
        $mail->clearAddresses(); // Clear recipients for next iteration
    }

    // Success response
    if ($res_web) {
        $response = [
            "error_code" => 100,
            "message" => "Mail sent to provided email IDs. Please check your inbox."
        ];
        echo json_encode($response);
    }
} catch (Exception $e) {
    // Error response
    $response = [
        "error_code" => 101,
        "message" => "Mail could not be sent. Mailer Error: {$mail->ErrorInfo}"
    ];
    echo json_encode($response);
}

}

// Helper function to validate input
function check_error($agency_id, $excel_no, $email_id,$mode) {
    if (empty($agency_id)) {
        $response = array("error_code" => 103, "message" => "The parameter 'agency_id' is required and cannot be empty");
        echo json_encode($response);
        exit;
    }
    if (empty($excel_no)) {
        $response = array("error_code" => 103, "message" => "The parameter 'excel_no' is required and cannot be empty");
        echo json_encode($response);
        exit;
    }
    if (empty($email_id)) {
        $response = array("error_code" => 103, "message" => "The parameter 'email_id' is required and cannot be empty");
        echo json_encode($response);
        exit;
    }
    if (empty($mode)) {
        $response = array("error_code" => 103, "message" => "The parameter 'mode' is required and cannot be empty");
        echo json_encode($response);
        exit;
    }
    return 1;
}






function unique_id_generate_bulk($id_prefix, $table_name, $mysqli, $id_for)
{
    date_default_timezone_set('Asia/Kolkata');
    $system_date_time = date("Y-m-d H:i:s");

    $unique_header_query = "SELECT `prefix`, `last_id`, `id_for` FROM `unique_id_header_all` WHERE `table_name`='$table_name' AND `id_for`='$id_for'";
    $unique_header_res = $mysqli->query($unique_header_query);
    $unique_header_arr = $unique_header_res->fetch_assoc();

    $last_id = $unique_header_arr['last_id'];
    // $id_for = $unique_header_arr['id_for'];

    if (empty($unique_header_arr)) {
        $initial_id = $id_prefix . '-' . str_pad(1, 5, '0', STR_PAD_LEFT);
        $insert_query = "INSERT INTO `unique_id_header_all` (`table_name`, `id_for`, `prefix`, `last_id`, `created_on`) 
                          VALUES ('$table_name', '$id_for', '$id_prefix', '$initial_id', '$system_date_time')";
        $mysqli->query($insert_query);
        return $initial_id;
    } else {
        $last_digit = explode('-', $last_id);
        $last_id_number = $last_digit[1];

        if (strlen($last_id_number) > 5) {
            return 'ID cannot be more than 5 characters';
        }

        $digits = preg_replace('/[^0-9]/', '', $last_id_number);

        if ($digits === str_repeat('9', strlen($digits))) {
            return 'You have reached the last ID string';
        }

        $next_id = str_pad((intval($digits) + 1), strlen($digits), '0', STR_PAD_LEFT);
        $unique_id = $id_prefix . "-" . $next_id;

        $update_unique_header_query = "UPDATE `unique_id_header_all` SET `last_id`='$unique_id', `modified_on`='$system_date_time' WHERE `table_name`='$table_name' AND `id_for`='$id_for'";
        $mysqli->query($update_unique_header_query);

        return $unique_id;
    }
}
