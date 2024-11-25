<?php
error_reporting(1);
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
include __DIR__ . '/vendor/autoload.php';
include_once 'connection.php';

$connection = connection::getInstance();
$mysqli = $connection->getConnection();

$connection1 = database::getInstance();
$mysqli1 = $connection1->getConnection();

$system_date = date('Y-m-d H:i:s');
$agency_id = $_POST['agency_id'];
$email_id = $_POST['email_id'];
$ids = explode(",", $email_id);
$request_no = $_POST['request_no'] ?? '';

// Check for errors
$check_error = check_error($agency_id);
if ($check_error == 1) {
    // Initialize PHPMailer
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'mail.mounarchtech.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'transactions@mounarchtech.com';
        $mail->Password = 'Mtech!@12345678'; 
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;
        $mail->setFrom('transactions@mounarchtech.com', 'Micro Integrated Semi Conductor Systems Pvt. Ltd.');
        $mail->isHTML(true);
        $mail->Subject = 'Action Required: Complete Your Verification Data';
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
                                   <p><a href="https://mounarchtech.com/vocoxp/upload_link/index.php?agency_id='.$agency_id.'">Click here to upload the data</a></p>
                                   <p>If you have any questions or require assistance, please don\'t hesitate to reach out to our support team at <a href="mailto:support@microintegrated.in">support@microintegrated.in</a>.</p>
                                   <p><b>Note: Please do not change the column names and numbers, as this will affect data analysis. Kindly upload the data in Excel and validate it using the following link:</b></p>
                                   <p>Thank you for your time and cooperation.</p>
                                   <p><b>Best regards,</b></p>
                                   <p>Micro Integrated Semi Conductor Systems Pvt. Ltd.</p>
                               </div>
                           </div>
                       </body>
                       </html>';

        // Download the file locally
        $temp_file = tempnam(sys_get_temp_dir(), 'sample_excel_');
        file_put_contents($temp_file, file_get_contents('https://mounarchtech.com/vocoxp/sample_excel.xlsx'));
        
        // Attach the temporary file
        $mail->addAttachment($temp_file, 'sample_excel.xlsx');

        foreach ($ids as $email) {
            $mail->addAddress(trim($email)); // Trim whitespace
            $mail->send();
            $mail->clearAddresses();
        }

        // Clean up
        unlink($temp_file);

        // Success response
        $response = ["error_code" => 100, "message" => "Mail sent to provided email ID. Please check your mail."];
        echo json_encode($response);
        return;

    } catch (Exception $e) {
        // Error response
        $response = ["error_code" => 101, "message" => "Mail could not be sent. Mailer Error: {$mail->ErrorInfo}"];
        echo json_encode($response);
        return;
    }
}

function check_error($agency_id) {
    if ($_SERVER["REQUEST_METHOD"] != "POST") {
        $response = ["error_code" => 102, "message" => "Please change the request method to POST"];
        echo json_encode($response);
        return 0;
    }
    if (!isset($agency_id) || empty($agency_id)) {
        $response = ["error_code" => 103, "message" => "The parameter 'agency_id' is required and cannot be empty"];
        echo json_encode($response);
        return 0;
    }
    return 1;
}
?>
