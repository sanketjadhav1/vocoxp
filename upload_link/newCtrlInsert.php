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
// var_dump($_POST);
// die();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $agency_id = $_POST['agency_id'] ?? '';
    $bulk_id = $_POST['bulk_id'] ?? '';
    $amount = $_POST['amount'] ?? '';
    $activate_date = $_POST['activate_date'] ?? '';
    $valid_till = $_POST['valid_till'] ?? '';
    $reminder_sms = $_POST['reminder_sms'] ?? '';
    $reminder_email = $_POST['reminder_email'] ?? '';
    $fileName1 = "https://mounarchtech/vocoxp/".$_FILES['file_name']['name'];

    $system_date_time = date("Y-m-d H:i:s");
    
    
      $weblink_details = "SELECT `obj_1_verifications`,`obj_2_verifications`,`obj_3_verifications` FROM `bulk_weblink_request_all` WHERE `bulk_id` = '$bulk_id'";
    $weblink_result = $mysqli->query($weblink_details);
    $weblink_array = mysqli_fetch_assoc($weblink_result);
    

    if ($amount === "agency_wallet") {
        $jsonString = $_POST['table1'] ?? '';
        $total_revenue = $_POST['total_revenue'] ?? '';
        $table1 = json_decode($jsonString, true);
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
        $result1 = "";
        $results1 = "";
        $found_zero_result = false;
        $arr = array();

        // Iterate over table2 data
        foreach ($table2 as $row) {
            
          
            $label = $row['column0'];
            $obj_number = explode('@', $label);
           $column_name = $obj_number[1];            
            $parts = explode('-', $obj_number[0]);
           $actualLabel = isset($parts[1]) ? trim($parts[1]) : trim($parts[0]);
             $result1 .= $column_name."_mi_amt=";
             $results1 .= $column_name."_addon_amount=";
        
            $value2 = isset($row['column2']) ? floatval($row['column2']) : 0;
            $value3 = isset($row['column3']) ? floatval($row['column3']) : 0;
            $value4 = isset($row['column4']) ? floatval($row['column4']) : 0;
            $value0 = $value3 + $value2;
            $value1 = $value4 - $value0;
            $value1_gst = 0.18 * $value1;
            $value1_commission = $value1 - $value1_gst;
            switch ($actualLabel) {
                case "Adhaar":
                    $name = "DVF-00001";
                    break;
                case "Pan Card":
                    $name = "DVF-00002";
                    break;
                case "Voter":
                    $name = "DVF-00003";
                    break;
                case "Driving licence":
                    $name = "DVF-00004";
                    break;
                case "E-Crime":
                    $name = "DVF-00005";
                    break;
                case "Mobile Verification":
                    $name = "DVF-00008";
                    break;
                default:
                    continue;
            }
                          
            $result = "$name=$value2"."+"."$value3".",";
            $results = "$name=$value1"."+"."$value1_gst"."-"."$value1_commission".",";

            $result1 .= "$result";
            $results1 .= "$results";
            

            array_push($arr, $name);
        }
         $result1 = rtrim($result1,',');
         $results1 = rtrim($results1,',');
            $pairs = explode(',', $result1);
            $pairs2 = explode(',', $results1);

        // Initialize an empty array to store the results
        $resulted = [];
        $resulted2 = [];

        // Loop through each key-value pair
        foreach ($pairs as $pair) {
            // Split each pair by '=' for the object and value
            list($key, $value) = explode('=', $pair, 2);
            
            // If the key already exists in the results array, append the value
            if (isset($resulted[$key])) {
                $resulted[$key] .= ',' . $value;
            } else {
                // Otherwise, just add the value
                $resulted[$key] = $value;
            }
        }
        $column_mi = '';
        // Output the result
        foreach ($resulted as $key => $value) {
            $column_mi .= "`$key` = '$value',";
        }
        // Loop through each key-value pair
        foreach ($pairs2 as $pair2) {
            // Split each pair by '=' for the object and value
            list($key2, $value2) = explode('=', $pair2, 2);
            
            // If the key already exists in the results array, append the value
            if (isset($resulted2[$key2])) {
                $resulted2[$key2] .= ',' . $value2;
            } else {
                // Otherwise, just add the value
                $resulted2[$key2] = $value2;
            }
        }
        $column_client = '';

        // Output the result
        foreach ($resulted2 as $key2 => $value2) {
            $column_client .= "`$key2` = '$value2',";
        }
        $column_mi = rtrim($column_mi, ', ');
        $column_client = rtrim($column_client, ', ');
        $combined_columns = $column_mi . ', ' . $column_client;
       

        $query = "UPDATE `bulk_weblink_request_all` 
          SET $combined_columns 
          WHERE `bulk_id` = '$bulk_id' 
          AND `agency_id` = '$agency_id'";
          
        $res_query = mysqli_query($mysqli, $query);
    } else {
      $result1 = "";
        $results1 = "";
        $found_zero_result = false;
        $arr = array();

        // Iterate over table2 data
        foreach ($table1 as $row) {
            
          
            $label = $row['column0'];
            $obj_number = explode('@', $label);
           $column_name = $obj_number[1];            
            $parts = explode('-', $obj_number[0]);
           $actualLabel = isset($parts[1]) ? trim($parts[1]) : trim($parts[0]);
             $result1 .= $column_name."_mi_amt=";
             $results1 .= $column_name."_addon_amount=";
        
            $value1 = isset($row['column1']) ? floatval($row['column1']) : 0;
            $value2 = isset($row['column2']) ? floatval($row['column2']) : 0;
           
            switch ($actualLabel) {
                case "Adhaar":
                    $name = "DVF-00001";
                    break;
                case "Pan Card":
                    $name = "DVF-00002";
                    break;
                case "E-Crime":
                    $name = "DVF-00003";
                    break;
                case "Driving licence":
                    $name = "DVF-00004";
                    break;
                case "Voter":
                    $name = "DVF-00005";
                    break;
                default:
                    continue;
            }
                          
            $result = "$name=$value1"."+"."$value2".",";
            $results = "$name=0+0-0,";
            $result1 .= "$result";          
            $results1 .= "$results";
            array_push($arr, $name);
        }
         $result1 = rtrim($result1,',');
         $results1 = rtrim($results1,',');
            $pairs = explode(',', $result1);
            $pairs2 = explode(',', $results1);

        // Initialize an empty array to store the results
        $resulted = [];
        $resulted2 = [];


        // Loop through each key-value pair
        foreach ($pairs as $pair) {
            // Split each pair by '=' for the object and value
            list($key, $value) = explode('=', $pair, 2);
            
            // If the key already exists in the results array, append the value
            if (isset($resulted[$key])) {
                $resulted[$key] .= ',' . $value;
            } else {
                // Otherwise, just add the value
                $resulted[$key] = $value;
            }
        }
        $column_mi = '';
        // Output the result
        foreach ($resulted as $key => $value) {
            $column_mi .= "`$key` = '$value',";
        }
     
       foreach ($pairs2 as $pair2) {
            // Split each pair by '=' for the object and value
            list($key2, $value2) = explode('=', $pair2, 2);
            
            // If the key already exists in the results array, append the value
            if (isset($resulted2[$key2])) {
                $resulted2[$key2] .= ',' . $value2;
            } else {
                // Otherwise, just add the value
                $resulted2[$key2] = $value2;
            }
        }
        $column_client = '';

        // Output the result
        foreach ($resulted2 as $key2 => $value2) {
            $column_client .= "`$key2` = '$value2',";
        }

        $column_mi = rtrim($column_mi, ', ');
        $column_client = rtrim($column_client, ', ');
        $combined_columns = $column_mi . ', ' . $column_client;
       
       $select_tentative = "SELECT `tentative_amount` FROM `agency_header_all` WHERE `agency_id`='$agency_id' ";
    $res_select_tentative = $mysqli->query($select_tentative);
    $rowselect_tentative = $res_select_tentative->fetch_assoc();
        $tentative_amount = ($rowselect_tentative['tentative_amount']*1) + ($total_revenue*1);

       $query1 = "UPDATE `agency_header_all` 
          SET  `tentative_amount`='$total_revenue'
          WHERE `agency_id` = '$agency_id'";
          // die();
        $res_query1 = mysqli_query($mysqli, $query1);

         $query = "UPDATE `bulk_weblink_request_all` 
          SET $combined_columns , `tentative_amount`='$total_revenue'
          WHERE `bulk_id` = '$bulk_id' 
          AND `agency_id` = '$agency_id'";
          // die();
        $res_query = mysqli_query($mysqli, $query);
    }

    $upload_id = unique_id_generate_bulk('UPL', 'bulk_end_user_transaction_all', $mysqli, "upload_id");
$select_date = "SELECT `upload_weblink_generated_on` FROM `bulk_weblink_request_all` WHERE `agency_id`='$agency_id' AND `bulk_id`='$bulk_id'";
    $res_select_date = $mysqli->query($select_date);
    $rowdd = $res_select_date->fetch_assoc();
        $upload_weblink_generated_on = $rowdd['upload_weblink_generated_on'];

    $insert_upload_bulk = "INSERT INTO `bulk_upload_file_information_all` 
        (`agency_id`, `bulk_id`, `upload_id`, `status`, `uploaded_datetime`, `weblink_generated`, `weblink_activated_from`, `weblink_valid_till`, `total_end_user`, `paid_by`, `reminder_email`, `reminder_sms`) 
        VALUES ('$agency_id', '$bulk_id', '$upload_id', '1', '$system_datetime', '$upload_weblink_generated_on', '$activate_date', '$valid_till', '', '$paid_by', '$reminder_email', '$reminder_sms')";

    $res_upload_link = mysqli_query($mysqli, $insert_upload_bulk);

    $update = "UPDATE `bulk_weblink_request_all` 
               SET `status`='3', `upload_weblink`='$fileName1', `successful_upload_id`='$upload_id'
               WHERE `agency_id`='$agency_id' AND `bulk_id`='$bulk_id'";

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
        $excel_no = $data[2][5] ?? '';
        $headerRow = $data[5] ?? [];
        $nameIndices = [];
        $emailIndices = [];
        $mobileIndices = [];
        $rollnum = [];
        $empnum = [];

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
            if (stripos($header, 'Roll no') !== false) {
                $rollnum[] = $index;
            }
            if (stripos($header, 'Employee No') !== false) {
                $empnum[] = $index;
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

        $insert_end_user = "
        INSERT INTO `bulk_end_user_transaction_all` (`agency_id`, `bulk_id`, `upload_id`, `end_user_id`, `excel_no`, `obj_no`, `obj_name` , `name`, `mobile`, `email_id`, `enroll_no`, `status`, `scheduled_verifications`, `reminder_email`, `reminder_sms`, `payment_from`, `ref_enduser_id`)        
        VALUES ";

        $values = [];
        foreach ($data as $index => $row) {

            if ($index <= 5 || empty($row[1])) { continue; } 
            

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

                $roles = ["Resident", "Parent", "Local Guardian"];
               
               $end_user_ids = [];
                $valid_roles = [];
                // for ($i = 0; $i < 3; $i++) {
                //     $end_user_ids[$i] = unique_id_generate_bulk('END', 'bulk_end_user_transaction_all', $mysqli, "end_user_id");
                // }
                //For removing extra enduser id when there is no verification is selected for any object. eg: if verification is not selected for Mother and there is data in excel for mother column also then this code will skip Mother data to be inserted in db.
                for ($i = 0, $j = 1; $i < 3; $i++, $j++) {
                    $verification_key = "obj_" . $j . "_verifications";

                    if (!empty($weblink_array[$verification_key])) {
                        // Generate unique ID only if the verification is selected
                        $end_user_ids[$i] = unique_id_generate_bulk('END', 'bulk_end_user_transaction_all', $mysqli, "end_user_id");
                        $valid_roles[] = $roles[$i]; // Track corresponding role
                    } else {
                        $end_user_ids[$i] = null; // Keep alignment consistent with $roles
                    }
                }

                // Add values for the current row
                for ($i = 0,$j=1; $i < 3; $i++,$j++) {
                    $name = $names[$i] ?? '';
                    $mobile = $mobiles[$i] ?? '';
                    $email = $emails[$i] ?? '';
                    $role = $roles[$i];
                    // $obj_number = $roles[$i];
                    $obj_num=$j;

                    $strr="obj_".$j."_verifications";
                    // Check if the current field is empty
                    if (empty($weblink_array[$strr])) {
                        // Skip this iteration if the field is empty
                        continue;
                    }
                    if($name!=''){
                        $ref_enduser_ids = array_filter($end_user_ids, function ($id, $index) use ($i) {
                            // Exclude the current role and null IDs
                            return $id !== null && $index !== $i;
                        }, ARRAY_FILTER_USE_BOTH);

                        $ref_enduser_ids_str = implode(",", $ref_enduser_ids);
                    
                    $values[] = "('$agency_id', '$bulk_id', '$upload_id', '{$end_user_ids[$i]}', '$excel_no', '$obj_num','$role','$name', '$mobile', '$email', '', '0', '$weblink_array[$strr]', '$reminder_email', '$reminder_sms', '$paid_by','$ref_enduser_ids_str')";
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
                                <p><a href="https://mounarchtech.com/vocoxp/upload_link/web_verification_form.php?enduser_id=' . $end_user_ids[$i] . '">Click here to complete your verification</a></p>
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
            } else if ($type == 'Students - Above 16 Yrs' || $excel_no == 1035) {
                // print_r($row); 
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
                $roll_number = [];
                foreach ($rollnum as $rollIndex) {
                    $roll_number[] = !empty($row[$rollIndex]) ? trim($row[$rollIndex]) : '';
                }

                $roles = ["Student", "Parent"];
                                $end_user_ids = [];
                $valid_roles = [];
                // for ($i = 0; $i < 3; $i++) {
                //     $end_user_ids[$i] = unique_id_generate_bulk('END', 'bulk_end_user_transaction_all', $mysqli, "end_user_id");
                // }
                //For removing extra enduser id when there is no verification is selected for any object. eg: if verification is not selected for Mother and there is data in excel for mother column also then this code will skip Mother data to be inserted in db.
              for ($i = 0, $j = 1; $i < 3; $i++, $j++) {
                    $verification_key = "obj_" . $j . "_verifications";

                    if (!empty($weblink_array[$verification_key])) {
                        // Generate unique ID only if the verification is selected
                        $end_user_ids[$i] = unique_id_generate_bulk('END', 'bulk_end_user_transaction_all', $mysqli, "end_user_id");
                        $valid_roles[] = $roles[$i]; // Track corresponding role
                    } else {
                        $end_user_ids[$i] = null; // Keep alignment consistent with $roles
                    }
                }
                // Add values for the current row (2 records)
                for ($i = 0, $j=1; $i < 2; $i++,$j++) {
                    $name = $names[$i] ?? '';
                    $mobile = $mobiles[$i] ?? '';
                    $roll = $roll_number[$i] ?? '';
                    $email = $emails[$i] ?? '';
                    $role = $roles[$i];
                    $obj_num=$j;
                    $strr="obj_".$j."_verifications";
                     // Check if the current field is empty
                    if (empty($weblink_array[$strr])) {
                        // Skip this iteration if the field is empty
                        continue;
                    }
                    // Only add to values array if name, mobile, and email are not empty
                    // if (!empty($name) && !empty($mobile) && !empty($email)) {
                   
                       
                        $ref_enduser_ids = array_filter($end_user_ids, function ($id, $index) use ($i) {
                            // Exclude the current role and null IDs
                            return $id !== null && $index !== $i;
                        }, ARRAY_FILTER_USE_BOTH);

                        $ref_enduser_ids_str = implode(",", $ref_enduser_ids);

                        $values[] = "('$agency_id', '$bulk_id', '$upload_id', '{$end_user_ids[$i]}', '$excel_no', '$obj_num','$role','$name', '$mobile', '$email', '$roll', '0', '$weblink_array[$strr]', '$reminder_email', '$reminder_sms', '$paid_by','$ref_enduser_ids_str')";
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
                                    <p><a href="https://mounarchtech.com/vocoxp/upload_link/web_verification_form.php?enduser_id=' . $end_user_ids[$i] . '">Click here to complete your verification</a></p>
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

                $roll_number = [];
                foreach ($rollnum as $rollIndex) {
                    $roll_number[] = !empty($row[$rollIndex]) ? trim($row[$rollIndex]) : '';
                }
                $roles = ["Student", "Father", "Mother"];
                $end_user_ids = [];
                $valid_roles = [];

                // Generate `end_user_ids` only for verified roles
                for ($i = 0, $j = 1; $i < 3; $i++, $j++) {
                    $verification_key = "obj_" . $j . "_verifications";

                    if (!empty($weblink_array[$verification_key])) {
                        // Generate unique ID only if the verification is selected
                        $end_user_ids[$i] = unique_id_generate_bulk('END', 'bulk_end_user_transaction_all', $mysqli, "end_user_id");
                        $valid_roles[] = $roles[$i]; // Track corresponding role
                    } else {
                        $end_user_ids[$i] = null; // Keep alignment consistent with $roles
                    }
                }
              
                // Add values for the current row
                for ($i = 0,$j=1; $i < 3; $i++,$j++) {
                    $name = $names[$i] ?? '';                     
                    $mobile = $mobiles[$i] ?? '';
                    $roll = $roll_number[$i] ?? '';
                    $email = $emails[$i] ?? '';
                    $role = $roles[$i];
                    $strr="obj_".$j."_verifications";
                     // echo $email;
                    // Check if the current field is empty
                    if (empty($weblink_array[$strr])) {
                        // Skip this iteration if the field is empty
                        continue;
                    }

                 
                        $obj_num = $j;
                        $ref_enduser_ids = array_filter($end_user_ids, function ($id, $index) use ($i) {
                            // Exclude the current role and null IDs
                            return $id !== null && $index !== $i;
                        }, ARRAY_FILTER_USE_BOTH);

                        $ref_enduser_ids_str = implode(",", $ref_enduser_ids);
                        $values[] = "('$agency_id', '$bulk_id', '$upload_id', '{$end_user_ids[$i]}', '$excel_no', '$obj_num','$role','$name', '$mobile', '$email', '$roll', '0', '$weblink_array[$strr]', '$reminder_email', '$reminder_sms', '$paid_by','$ref_enduser_ids_str')";
                        if (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
                            $mail->addAddress($email);  
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
                                         <p><a href="https://mounarchtech.com/vocoxp/upload_link/web_verification_form.php?enduser_id=' .$end_user_ids[$i] . '">Click here to complete your verification</a></p>
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
                            $mail->clearAddresses();
                        } else {
                            echo "Invalid email: $email"; 
                        }
                    
                }
            } else {
                $role = '';
                if ($type == 'Candidate  / Event Participant Onboarding' || $excel_no == 1041) {
                    $role = 'Candidate';
                    $roll = '';
                    
                } else if ($type == 'Employees / Teachers' || $excel_no == 1036) {
                    $role = 'Employees / Teachers';
                    $roll = '';
                foreach ($empnum as $empIndex) {
                    $roll =  trim($row[$empIndex]);
                    break;
                }
                } else if ($type == 'Students only' || $excel_no == 1040) {
                    $role = 'Student';
                    $roll = '';
                    foreach ($rollnum as $rollIndex) {
                        $roll =  trim($row[$rollIndex]);
                        break;
                    }
                } else if ($type == 'Parent' || $excel_no == 1042) {
                    $role = 'Parent';
                    $roll = '';
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
                
             $obj_num='1';
                                $strr="obj_1_verifications";

                // echo "name = " . $name . " Email =  " . $email . "Mobile = " . $mobile . "Role = " . $role;
                $end_user_id = unique_id_generate_bulk('END', 'bulk_end_user_transaction_all', $mysqli, "end_user_id");
                // echo "Generated end_user_id for user $i: " . $end_user_id . "\n";
                if (!empty($name)) {

                    $insert_end_user = "INSERT INTO `bulk_end_user_transaction_all` (`agency_id`, `bulk_id`, `upload_id`, `end_user_id`, `excel_no`, `obj_no`, `obj_name` , `name`, `mobile`, `email_id`, `enroll_no`, `status`, `scheduled_verifications`, `reminder_email`, `reminder_sms`, `payment_from`, `ref_enduser_id`) 
                            VALUES ('$agency_id', '$bulk_id', '$upload_id', '$end_user_id', '$excel_no', '$obj_num','$role','$name', '$mobile', '$email', '$roll', '0', '$weblink_array[$strr]', '$reminder_email', '$reminder_sms', '$paid_by','')";
                
                    $res_end_user = mysqli_query($mysqli, $insert_end_user);
                    


                    // $update_end_details = "UPDATE `bulk_end_user_transaction_all` SET `obj_1_verifications`='$obj_1_verifications' WHERE `bulk_id`='$bulk_id'";
                    // $res_update = mysqli_query($mysqli, $update_end_details);
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
                            <p><a href="https://mounarchtech.com/vocoxp/upload_link/web_verification_form.php?enduser_id=' . $end_user_id . '">Click here to complete your verification</a></p>
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

