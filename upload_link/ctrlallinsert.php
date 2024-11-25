<?php
include_once '../connection.php';
require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PhpOffice\PhpSpreadsheet\IOFactory;

error_reporting(E_ALL & ~E_DEPRECATED);
ini_set('display_errors', 1);

$connection = connection::getInstance();
$mysqli = $connection->getConnection();

$system_datetime = date("Y-m-d H:i:s");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $agency_id = $_POST['agency_id'] ?? '';
    $bulk_id = $_POST['bulk_id'] ?? '';
    $amount = $_POST['amount'] ?? '';
    $activate_date = $_POST['activate_date'] ?? '';
    $valid_till = $_POST['valid_till'] ?? '';
    $reminder_sms = $_POST['reminder_sms'] ?? '';
    $reminder_email = $_POST['reminder_email'] ?? '';
    $fileName1 = $_FILES['file_name']['name'];

    $system_date_time = date("Y-m-d H:i:s");


    if ($amount === "agency_wallet") {
        $jsonString = $_POST['table1'] ?? '';
        $table1 = json_decode($jsonString, true);
        // print_r($table1);
        $paid_by = 1;
    } else {
        $paid_by = 2;
        $jsonString = $_POST['table2'] ?? '';
        $table2 = json_decode($jsonString, true);
    }

    // Validate required POST data
    if (empty($agency_id) || empty($bulk_id) || empty($activate_date) || empty($valid_till)) {
        echo json_encode(['status' => 'error', 'message' => 'Missing required fields.']);
        exit();
    }
    if ($amount == "end_user") {
    // Initialize variables for concatenation and tracking
    $concatenatedResults = "";
    $beforeTotalAmount = 0;  // For amount before "1=0-0"
    $afterTotalAmount = 0;   // For amount after "1=0-0"
    $childConcatenatedResults = "";
    $found_zero_result = false; // Track if "1=0-0" is found
    $arr = array(); // For storing names
    
    // Iterate over the table2 data
    foreach ($table2 as $row) {
        $label = $row['column0'];
        $value2 = isset($row['column2']) ? floatval($row['column2']) : 0;
        $value3 = isset($row['column3']) ? floatval($row['column3']) : 0;

        $value1 = $value3 - $value2;

        // Determine the name based on the label
        switch ($label) {
            case "Adhaar":
                $name = 1;
                break;
            case "Pan Card":
                $name = 2;
                break;
            case "Voter":
                $name = 3;
                break;
            case "Driving licence":
                $name = 4;
                break;
            case "E-Crime":
                $name = 5;
                break;
            default:
                continue; // Skip unknown labels
        }

        $result = "$name=$value2-$value1";

        // Check if result is "1=0-0"
        if ($result === "1=0-0") {
            $found_zero_result = true; // Mark that "1=0-0" has been found
            continue; // Skip processing for "1=0-0"
        }

        if (!$found_zero_result) {
            // Before "1=0-0", store in client_addon_amt and calculate the beforeTotalAmount
            $concatenatedResults .= "$result,";
            $beforeTotalAmount += $value3;  // Add to before total amount
        } else {
            // After "1=0-0", store in child_client_addon_amt and calculate the afterTotalAmount
            $childConcatenatedResults .= "$result,";
            $afterTotalAmount += $value3;   // Add to after total amount
        }

        array_push($arr, $name);
    }

    // Trim trailing commas
    $concatenatedResults = rtrim($concatenatedResults, ',');
    $childConcatenatedResults = rtrim($childConcatenatedResults, ',');

    // Prepare the SQL update query, setting "amount" as beforeTotalAmount and "client_addon_amt" as afterTotalAmount
    $query = "UPDATE `bulk_weblink_request_all` 
              SET `amount` = '$beforeTotalAmount',
                  `client_addon_amt` = '$concatenatedResults',
                  `child_client_addon_amt` = '$childConcatenatedResults', 
                  `child_amount`='$afterTotalAmount'
              WHERE `bulk_id` = '$bulk_id' AND `agency_id` = '$agency_id'";

    // Prepare and execute the query
    $res_query = mysqli_query($mysqli, $query);
} else {
        $arr = array();
        foreach ($table1 as $row) {
            $label = $row['column0'];
            // Determine the name based on the label
            switch ($label) {
                case "Adhaar":
                    $name = 1;
                    break;
                case "Pan Card":
                    $name = 2;
                    break;
                case "Voter":
                    $name = 3;
                    break;
                case "Driving licence":
                    $name = 4;
                    break;
                case "E-Crime":
                    $name = 5;
                    break;
                default:
                    continue; // Skip unknown labels
            }

            array_push($arr, $name);
            $result = $value2 . '-' . $value1;


            $concatenatedResults .= "$name=$result,";
        }

        $concatenatedResults = rtrim($concatenatedResults, ',');

        $schedle = implode(",", $arr);
    }
    // echo $schedle;

    $upload_id = unique_id_generate_bulk('UPL', 'bulk_end_user_transaction_all', $mysqli, "upload_id");
    // Insert into `bulk_upload_file_information_all`
    $insert_upload_bulk = "INSERT INTO `bulk_upload_file_information_all` 
        (`agency_id`, `bulk_id`, `upload_id`, `status`, `uploaded_datetime`, `weblink_generated`, `weblink_activated_from`, `weblink_valid_till`, `total_end_user`, `paid_by`, `reminder_email`, `reminder_sms`) VALUES ('$agency_id', '$bulk_id', '$upload_id', '1', '$system_datetime', '$system_date_time', '$activate_date', '$valid_till', '', '$paid_by', '$reminder_email', '$reminder_sms')
    ";
    $res_upload_link = mysqli_query($mysqli, $insert_upload_bulk);

    $update = "UPDATE `bulk_weblink_request_all` SET `status`='3', `upload_weblink`='$fileName1', `successful_upload_id`='$upload_id', `upload_weblink_generated_on`='$system_date_time' WHERE `agency_id`='$agency_id' AND `bulk_id`='$bulk_id'";
    $res_request = mysqli_query($mysqli, $update);

    if (!$res_upload_link) {
        echo json_encode(['status' => 'error', 'message' => 'Failed to insert upload data: ' . mysqli_error($mysqli)]);
        exit();
    }

    if (empty($_FILES['file_name']['name'])) {
        echo json_encode(['status' => 'error', 'message' => 'No file selected.']);
        exit();
    }

    $fileName = $_FILES['file_name']['name'];
    $file_ext = pathinfo($fileName, PATHINFO_EXTENSION);
    $allowed_ext = ['xls', 'csv', 'xlsx'];

    if (!in_array($file_ext, $allowed_ext)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid file type.']);
        exit();
    }

    $inputFileNamePath = $_FILES['file_name']['tmp_name'];

    try {
        // Load the Excel file
        $spreadsheet = IOFactory::load($inputFileNamePath);
        $data = $spreadsheet->getActiveSheet()->toArray();
        $type = $data[1][5] ?? '';
        // $type = $data[1][5] ?? '';
        $excel_no = $data[2][5] ?? '';
        $headerRow = $data[5] ?? [];
        $nameIndices = [];
        $emailIndices = [];
        $mobileIndices = [];

        // Find multiple possible columns for Name, Email, Mobile
        foreach ($headerRow as $index => $header) {
            if (stripos($header, 'Name') !== false) {
                $nameIndices[] = $index;
            }
            if (stripos($header, 'Email') !== false) {
                $emailIndices[] = $index;
            }
            if (stripos($header, 'Mobile') !== false) {
                $mobileIndices[] = $index;
            }
        }

        // Check if any column indices are found
        if (empty($nameIndices) || empty($emailIndices) || empty($mobileIndices)) {
            echo json_encode(['status' => 'error', 'message' => 'Missing required columns (Name, Email, or Mobile) in the Excel file.']);
            exit();
        }

        $rows_inserted = false;
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = 'mail.mounarchtech.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'transactions@mounarchtech.com';
        $mail->Password = 'Mtech!@12345678';  // Ideally, load this from environment variables
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;
        $mail->setFrom('transactions@mounarchtech.com', 'Micro Integrated Semi Conductor Systems Pvt. Ltd.');
        $mail->isHTML(true);
        $mail->Subject = 'Action Required: Complete Your Verification Data';

        $insert_end_user = "INSERT INTO `bulk_end_user_transaction_all`  
        (`agency_id`, `bulk_id`, `upload_id`, `end_user_id`, `type`, `name`, `mobile`, `email_id`, `sms_sent`, `email_sent`, `status`, `scheduled_verifications`, `payment_done_by`, `reminder_email`, `reminder_sms`, `role`, `ref_enduser_id`) 
        VALUES ";

        $values = [];
        foreach ($data as $index => $row) {
            if ($index < 6) continue;
            if ($type == 'Hostel Registration' || $excel_no == 1043) {
                $names = [];
                foreach ($nameIndices as $nameIndex) {
                    $names[] = $row[$nameIndex] ?? '';
                }


                $emails = [];
                foreach ($emailIndices as $emailIndex) {
                    $emails[] = $row[$emailIndex] ?? '';
                }

                $mobiles = [];
                foreach ($mobileIndices as $mobileIndex) {
                    $mobiles[] = !empty($row[$mobileIndex]) ? trim($row[$mobileIndex]) : '';
                }

                $roles = ["student", "parent", "local Guardian"];
                $end_user_ids = [];
                for ($i = 0; $i < 3; $i++) {
                    $end_user_ids[$i] = unique_id_generate_bulk('END', 'bulk_end_user_transaction_all', $mysqli, "end_user_id");
                }

                // Add values for the current row
                for ($i = 0; $i < 3; $i++) {
                    $name = $names[$i] ?? '';
                    $mobile = $mobiles[$i] ?? '';
                    $email = $emails[$i] ?? '';
                    $role = $roles[$i];
                    $ref_enduser_ids = implode(",", array_diff($end_user_ids, [$end_user_ids[$i]]));
                    $values[] = "('$agency_id', '$bulk_id', '$upload_id', '{$end_user_ids[$i]}', '$type', '$name', '$mobile', '$email', '', '', '0', '$schedle', '$paid_by', '$reminder_email', '$reminder_sms', '$role','$ref_enduser_ids')";
                    if (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        $mail->addAddress($email);  // Add the recipient email
    
                        // Prepare the email body content
                        $mail->Body = '<!DOCTYPE html>
                        <html>
                        <head>
                            <style>
                                .email-body {
                                    font-family: Arial, sans-serif;
                                    line-height: 1.6;
                                    color: #333;
                                }
                                .email-header {
                                    background-color: #f0f0f0;
                                    padding: 20px;
                                    text-align: center;
                                }
                                .email-content {
                                    padding: 20px;
                                }
                                .button {
                                    display: inline-block;
                                    padding: 10px 20px;
                                    margin: 20px 0;
                                    font-size: 16px;
                                    color: white;
                                    background-color: #007BFF;
                                    text-decoration: none;
                                    border-radius: 5px;
                                }
                                .email-footer {
                                    padding: 20px;
                                    text-align: center;
                                    font-size: 12px;
                                    color: #777;
                                }
                            </style>
                        </head>
                        <body>
                          <div class="email-body">
                            <div class="email-content">
                                <p>Dear Recipient,</p>
                                <p>Please click on the link below to access your member profile and complete the data required for verification. This link is valid for 24 hours only for security reasons:</p>
                                <p><a href="https://mounarchtech.com/vocoxp/bulk_welcome_screen.php?enduser_id=' . $end_user_ids[$i] . '">Click here to complete your verification</a></p>
                                <p>If you have any questions or require assistance, please don\'t hesitate to reach out to our support team at <a href="mailto:support@microintegrated.in">support@microintegrated.in</a>.</p>
                                <p>Thank you for your time and cooperation.</p>
                                <p><b>Best regards,</b></p>
                                <p>Micro Integrated Semi Conductor Systems Pvt. Ltd.</p>
                            </div>
                          </div>
                        </body>
                        </html>';
    
                        try {
                            if ($mail->send()) {
                                echo "Email sent successfully to $email";
                            } else {
                                echo "Email sending failed to $email: " . $mail->ErrorInfo;
                            }
                        } catch (Exception $e) {
                            echo "Mailer Error: " . $mail->ErrorInfo;
                        }
    
                        // Clear the recipient list for the next email
                        $mail->clearAddresses();
                    } else {
                        echo "Invalid email: $email";  // Debug invalid email
                    }
                }
            } else if ($type == 'Students - Above 16 Yrs' || $excel_no == 1035) {
                $names = [];
                foreach ($nameIndices as $nameIndex) {
                    $names[] = $row[$nameIndex] ?? '';
                }

                $emails = [];
                foreach ($emailIndices as $emailIndex) {
                    $emails[] = $row[$emailIndex] ?? '';
                }

                $mobiles = [];
                foreach ($mobileIndices as $mobileIndex) {
                    $mobiles[] = !empty($row[$mobileIndex]) ? trim($row[$mobileIndex]) : '';
                }

                $roles = ["student", "parent"];
                $end_user_ids = [];
                for ($i = 0; $i < 2; $i++) {
                    $end_user_ids[$i] = unique_id_generate_bulk('END', 'bulk_end_user_transaction_all', $mysqli, "end_user_id");
                }

                // Add values for the current row (2 records)
                for ($i = 0; $i < 2; $i++) {
                    $name = $names[$i] ?? '';
                    $mobile = $mobiles[$i] ?? '';
                    $email = $emails[$i] ?? '';
                    $role = $roles[$i];
                    $ref_enduser_ids = implode(",", array_diff($end_user_ids, [$end_user_ids[$i]]));

                    // Only add to values array if name, mobile, and email are not empty
                    if (!empty($name) && !empty($mobile) && !empty($email)) {
                        $values[] = "('$agency_id', '$bulk_id', '$upload_id', '{$end_user_ids[$i]}', '$type', '$name', '$mobile', '$email', '', '', '0', '$schedle', '$paid_by', '$reminder_email', '$reminder_sms', '$role','$ref_enduser_ids')";
                        if (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
                            $mail->addAddress($email);  // Add the recipient email
        
                            // Prepare the email body content
                            $mail->Body = '<!DOCTYPE html>
                            <html>
                            <head>
                                <style>
                                    .email-body {
                                        font-family: Arial, sans-serif;
                                        line-height: 1.6;
                                        color: #333;
                                    }
                                    .email-header {
                                        background-color: #f0f0f0;
                                        padding: 20px;
                                        text-align: center;
                                    }
                                    .email-content {
                                        padding: 20px;
                                    }
                                    .button {
                                        display: inline-block;
                                        padding: 10px 20px;
                                        margin: 20px 0;
                                        font-size: 16px;
                                        color: white;
                                        background-color: #007BFF;
                                        text-decoration: none;
                                        border-radius: 5px;
                                    }
                                    .email-footer {
                                        padding: 20px;
                                        text-align: center;
                                        font-size: 12px;
                                        color: #777;
                                    }
                                </style>
                            </head>
                            <body>
                              <div class="email-body">
                                <div class="email-content">
                                    <p>Dear Recipient,</p>
                                    <p>Please click on the link below to access your member profile and complete the data required for verification. This link is valid for 24 hours only for security reasons:</p>
                                    <p><a href="https://mounarchtech.com/vocoxp/bulk_welcome_screen.php?enduser_id=' . $end_user_ids[$i] . '">Click here to complete your verification</a></p>
                                    <p>If you have any questions or require assistance, please don\'t hesitate to reach out to our support team at <a href="mailto:support@microintegrated.in">support@microintegrated.in</a>.</p>
                                    <p>Thank you for your time and cooperation.</p>
                                    <p><b>Best regards,</b></p>
                                    <p>Micro Integrated Semi Conductor Systems Pvt. Ltd.</p>
                                </div>
                              </div>
                            </body>
                            </html>';
        
                            try {
                                if ($mail->send()) {
                                    echo "Email sent successfully to $email";
                                } else {
                                    echo "Email sending failed to $email: " . $mail->ErrorInfo;
                                }
                            } catch (Exception $e) {
                                echo "Mailer Error: " . $mail->ErrorInfo;
                            }
        
                            // Clear the recipient list for the next email
                            $mail->clearAddresses();
                        } else {
                            echo "Invalid email: $email";  // Debug invalid email
                        }
                    }
                }
            } else if ($type == 'Parent & students' || $excel_no == 1034) {
                $names = [];
                foreach ($nameIndices as $nameIndex) {
                    $names[] = $row[$nameIndex] ?? '';
                }

                if (empty($names[1])) {
                    $names[1] = $names[2];
                    $names[2] = '';
                }
                $emails = [];
                foreach ($emailIndices as $emailIndex) {
                    $emails[] = $row[$emailIndex] ?? '';
                }
                if (empty($emails[0])) {
                    // $emails[0] = $emails[1];  
                    $emails[1] = '';
                    $emails[2] = $emails[1];
                }
                $emails[2] = $emails[1];
                $emails[1] = $emails[0];
                $emails[0] = '';


                $mobiles = [];
                foreach ($mobileIndices as $mobileIndex) {
                    $mobiles[] = !empty($row[$mobileIndex]) ? trim($row[$mobileIndex]) : '';
                }
                if (empty($mobiles[0])) {
                    // $mobiles[0] = $mobiles[1];
                    $mobiles[1] = '';
                    $mobiles[2] = $mobiles[1];
                }
                $mobiles[2] = $mobiles[1];
                $mobiles[1] = $mobiles[0];
                $mobiles[0] = '';


                $roles = ["student", "Parent", "Parent"];
                $end_user_ids = [];
                for ($i = 0; $i < 3; $i++) {
                    $end_user_ids[$i] = unique_id_generate_bulk('END', 'bulk_end_user_transaction_all', $mysqli, "end_user_id");
                }

                // Add values for the current row
                for ($i = 0; $i < 3; $i++) {
                    $name = $names[$i] ?? '';
                    $mobile = $mobiles[$i] ?? '';
                    $email = $emails[$i] ?? '';
                    $role = $roles[$i];
                    if (!empty($name) || !empty($mobile) || !empty($email)) {
                        $ref_enduser_ids = implode(",", array_diff($end_user_ids, [$end_user_ids[$i]]));
                        $values[] = "('$agency_id', '$bulk_id', '$upload_id', '{$end_user_ids[$i]}', '$type', '$name', '$mobile', '$email', '', '', '0', '$schedle', '$paid_by', '$reminder_email', '$reminder_sms', '$role','$ref_enduser_ids')";
                        if (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
                            $mail->addAddress($email);  // Add the recipient email
        
                            // Prepare the email body content
                            $mail->Body = '<!DOCTYPE html>
                            <html>
                            <head>
                                <style>
                                    .email-body {
                                        font-family: Arial, sans-serif;
                                        line-height: 1.6;
                                        color: #333;
                                    }
                                    .email-header {
                                        background-color: #f0f0f0;
                                        padding: 20px;
                                        text-align: center;
                                    }
                                    .email-content {
                                        padding: 20px;
                                    }
                                    .button {
                                        display: inline-block;
                                        padding: 10px 20px;
                                        margin: 20px 0;
                                        font-size: 16px;
                                        color: white;
                                        background-color: #007BFF;
                                        text-decoration: none;
                                        border-radius: 5px;
                                    }
                                    .email-footer {
                                        padding: 20px;
                                        text-align: center;
                                        font-size: 12px;
                                        color: #777;
                                    }
                                </style>
                            </head>
                            <body>
                              <div class="email-body">
                                <div class="email-content">
                                    <p>Dear Recipient,</p>
                                    <p>Please click on the link below to access your member profile and complete the data required for verification. This link is valid for 24 hours only for security reasons:</p>
                                         <p><a href="https://mounarchtech.com/vocoxp/upload_link/web_verification_form.php?enduser_id=' .$end_user_ids[$i] . '&t='.time().'&reset_captcha=true">Click here to complete your verification</a></p>
                                    <p>If you have any questions or require assistance, please don\'t hesitate to reach out to our support team at <a href="mailto:support@microintegrated.in">support@microintegrated.in</a>.</p>
                                    <p>Thank you for your time and cooperation.</p>
                                    <p><b>Best regards,</b></p>
                                    <p>Micro Integrated Semi Conductor Systems Pvt. Ltd.</p>
                                </div>
                              </div>
                            </body>
                            </html>';
        
                            try {
                                if ($mail->send()) {
                                    echo "Email sent successfully to $email";
                                } else {
                                    echo "Email sending failed to $email: " . $mail->ErrorInfo;
                                }
                            } catch (Exception $e) {
                                echo "Mailer Error: " . $mail->ErrorInfo;
                            }
        
                            // Clear the recipient list for the next email
                            $mail->clearAddresses();
                        } else {
                            echo "Invalid email: $email";  // Debug invalid email
                        }
                    }
                }
            }
            
else {
    $role = '';
    if ($type == 'Candidate  / Event Participant Onboarding') {
        $role = 'Candidate';
    } else if ($type == 'Employees / Teachers') {
        $role = 'Employee';
    } else if ($type == 'Students only') {
        $role = 'Student';
    } else if ($type == 'Parent') {
        $role = 'Parent';
    }

    $name = '';
    foreach ($nameIndices as $nameIndex) {
        if (!empty($row[$nameIndex])) {
            $name = trim($row[$nameIndex]);
            break;
        }
    }
    $email = '';
    foreach ($emailIndices as $emailIndex) {
        $email = trim($row[$emailIndex]);
        break;
    }  
    $mobile = '';
    foreach ($mobileIndices as $mobileIndex) {
        $mobile =  trim($row[$mobileIndex]);
        break;
    }
    echo "name = " . $name . " Email =  " . $email . "Mobile = " . $mobile . "Role = " . $role;
    $end_user_id = unique_id_generate_bulk('END', 'bulk_end_user_transaction_all', $mysqli, "end_user_id");
    echo "Generated end_user_id for user $i: " . $end_user_id . "\n";
    if (!empty($name)) {

        $insert_end_user = "INSERT INTO `bulk_end_user_transaction_all` 
            (`agency_id`, `bulk_id`, `upload_id`, `end_user_id`, `type`, `name`, `mobile`, `email_id`, `sms_sent`, `email_sent`, `status`, `scheduled_verifications`, `payment_done_by`, `reminder_email`, `reminder_sms`,`role`) 
                VALUES ('$agency_id', '$bulk_id', '$upload_id', '$end_user_id', '$type', '$name', '$mobile', '$email', '', '', '0', '$schedle', '$paid_by', '$reminder_email', '$reminder_sms', '$role')";
    
    $res_end_user = mysqli_query($mysqli, $insert_end_user);
    $weblink_details = "SELECT `verifications` FROM `bulk_weblink_request_all` WHERE `bulk_id` = '$bulk_id'";
    $weblink_result = $mysqli->query($weblink_details);
    $weblink_array = mysqli_fetch_assoc($weblink_result);
    $verifications = $weblink_array['verifications'];

    $update_end_details = "UPDATE `bulk_end_user_transaction_all` SET `scheduled_verifications`='$verifications' WHERE `bulk_id`='$bulk_id'";
    $res_update = mysqli_query($mysqli, $update_end_details);
    if ($res_end_user) {
        $rows_inserted = true;
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to insert end user data: ' . mysqli_error($mysqli)]);
        exit();
    }
}
    // Send email notification
    $mail->Body = '<!DOCTYPE html>
                            <html>
                            <head>
                                <style>
                                    .email-body {
                                        font-family: Arial, sans-serif;
                                        line-height: 1.6;
                                        color: #333;
                                    }
                                    .email-header {
                                        background-color: #f0f0f0;
                                        padding: 20px;
                                        text-align: center;
                                    }
                                    .email-content {
                                        padding: 20px;
                                    }
                                    .button {
                                        display: inline-block;
                                        padding: 10px 20px;
                                        margin: 20px 0;
                                        font-size: 16px;
                                        color: white;
                                        background-color: #007BFF;
                                        text-decoration: none;
                                        border-radius: 5px;
                                    }
                                    .email-footer {
                                        padding: 20px;
                                        text-align: center;
                                        font-size: 12px;
                                        color: #777;
                                    }
                                </style>
                            </head>
                            <body>
                              <div class="email-body">
                                <div class="email-content">
                                    <p>Dear Recipient,</p>
                                    <p>Please click on the link below to access your member profile and complete the data required for verification. This link is valid for 24 hours only for security reasons:</p>
                                         <p><a href="https://mounarchtech.com/vocoxp/upload_link/web_verification_form.php?enduser_id=' .$end_user_id . '">Click here to complete your verification</a></p>
                                    <p>If you have any questions or require assistance, please don\'t hesitate to reach out to our support team at <a href="mailto:support@microintegrated.in">support@microintegrated.in</a>.</p>
                                    <p>Thank you for your time and cooperation.</p>
                                    <p><b>Best regards,</b></p>
                                    <p>Micro Integrated Semi Conductor Systems Pvt. Ltd.</p>
                                </div>
                              </div>
                            </body>
                            </html>';

    $mail->addAddress($email);
    $mail->send();
    $mail->clearAddresses();
}
        }
            if (!empty($values)) { 
                $insert_end_user .= implode(", ", $values);
                echo $insert_end_user;
        
                // Insert end user data
                $res_end_user = mysqli_query($mysqli, $insert_end_user);
        
                // Fetch web link details
                $weblink_details = "SELECT `verifications` FROM `bulk_weblink_request_all` WHERE `bulk_id` = '$bulk_id'";
                $weblink_result = $mysqli->query($weblink_details);
                $weblink_array = mysqli_fetch_assoc($weblink_result);
                $verifications = $weblink_array['verifications'];
        
                // Update end user records with scheduled verifications
                $update_end_details = "UPDATE `bulk_end_user_transaction_all` SET `scheduled_verifications`='$verifications' WHERE `bulk_id`='$bulk_id'";
                $res_update = mysqli_query($mysqli, $update_end_details);
        
                if ($res_end_user) {
                    $rows_inserted = true;
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Failed to insert end user data: ' . mysqli_error($mysqli)]);
                    exit();
                }
            }  
        } 
 catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'Error processing file: ' . $e->getMessage()]);
        exit();
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}


function unique_id_generate_bulk($id_prefix, $table_name, $mysqli, $id_for)
{
    date_default_timezone_set('Asia/Kolkata');
    $system_date_time = date("Y-m-d H:i:s");

    $unique_header_query = "SELECT `prefix`, `last_id`, `id_for` FROM `unique_id_header_all` WHERE `table_name`='$table_name' AND `id_for`='$id_for'";
    $unique_header_res = $mysqli->query($unique_header_query);
    $unique_header_arr = $unique_header_res->fetch_assoc();

    if (empty($unique_header_arr)) {
        $initial_id = $id_prefix . '-' . str_pad(1, 5, '0', STR_PAD_LEFT);
        $insert_query = "INSERT INTO `unique_id_header_all` (`table_name`, `id_for`, `prefix`, `last_id`, `created_on`) 
                          VALUES ('$table_name', '$id_for', '$id_prefix', '$initial_id', '$system_date_time')";
        $mysqli->query($insert_query);
        return $initial_id;
    } else {
        $last_id = $unique_header_arr['last_id'];
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
