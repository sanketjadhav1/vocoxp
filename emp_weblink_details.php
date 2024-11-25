<?php
error_reporting(1);
use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

include __DIR__ . '/vendor/autoload.php';
include_once 'connection.php';
error_reporting(E_ALL & ~E_DEPRECATED);
ini_set('display_errors', 1);
// Now you can use the connection class
$connection = connection::getInstance();
$mysqli = $connection->getConnection();

$connection1 = database::getInstance();
$mysqli1 = $connection1->getConnection();

$system_date = date("Y-m-d H:i:s");

$agency_id = $_POST['agency_id'] ?? "" ;
$email_id = $_POST['email_id'] ?? "";
$ids = explode(",", $email_id);
$emp_id = unique_id_genrate('EMP', 'visitor_emp_weblink_details_all', $mysqli, 'emp_upload_id');
 
$check_error = check_error($mysqli, $mysqli1, $agency_id, $email_id);
if ($check_error == 1) 
{
	$upload_weblink = "https://mounarchtech.com/vocoxp/upload_link/add_employee.php?agency_id=".$agency_id."&emp_id=".$emp_id;

	
	    $query="INSERT INTO `visitor_emp_weblink_details_all`( `agency_id`, `emp_upload_id`, `weblink_url`, `email_ids`, `uploaded_excel_url`, `function_for`, `weblink_generated_on`, `weblink_valid_till`)   VALUES ( '$agency_id', '$emp_id', '$upload_weblink', '$email_id', '','', '$system_date', '$system_date')";
		// $weblink_title="";
      $res_web = mysqli_query($mysqli, $query);
      
    // Initialize PHPMailer
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'mail.mounarchtech.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'transactions@mounarchtech.com';
        $mail->Password = 'Mtech!@12345678';  // Ideally, load this from environment variables
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;
        $mail->setFrom('transactions@mounarchtech.com', 'Micro Integrated Semi Conductor Systems Pvt. Ltd.');
        $mail->isHTML(true);
        $mail->Subject = 'Upload Employee: Kindly upload the information of employees.';
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
                               
                                   <p>Greetings, Recipient,</p>    
                                   <p>To upload employee list, kindly follow the link below. For safety concerns, it is only valid for 24 hours</p>                               
                                   <p><a href="https://mounarchtech.com/vocoxp/upload_link/add_employee.php?agency_id='.$agency_id.'&emp_id='.$emp_id.'">Click here to complete this process</a></p>
                                   <p>Kindly email <a href="mailto:support@microintegrated.in">support@microintegrated.in</a> if youre looking for help.</p>
                                   
                                   <p><b>Best regards,</b></p>
                                   <p>Micro Integrated Semi Conductor Systems Pvt. Ltd.</p>
                               </div>
                           </div>
                       </body>
                       </html>';

        // Attach the file
       
            $mail->addAttachment('Employee_excel_for_Visitor.xlsx');
        

        // Send email to each address
        foreach ($ids as $email) {
            $mail->addAddress($email);
            $mail->send();
            $mail->clearAddresses();
        }

        if ($res_web) {
            $response = array("error_code" => 100, "message" => "mail sent to provided email id. Please check your mail.");
            echo json_encode($response);
            return;
        }
    } catch (Exception $e) {
        $response = array("error_code" => 101, "message" => "Mail could not be sent. Mailer Error: {$mail->ErrorInfo}");
        echo json_encode($response);
        return;
    }


}
function check_error($mysqli, $mysqli1, $agency_id,  $email_id) {
    if (!$mysqli || !$mysqli1) {
        $response = array("error_code" => 101, "message" => "An unexpected server error occurred while processing your request. Please try again later.");
        echo json_encode($response);
        return 0;
    }
    if ($_SERVER["REQUEST_METHOD"] != "POST") {
        $response= array("error_code" => 102, "message" => "Please change the request method to POST");
        echo json_encode($response);
        return 0;
    }
    if (!isset($agency_id) || empty($agency_id)) {
        $response = array("error_code" => 103, "message" => "The parameter 'agency_id' is required and cannot be empty");
        echo json_encode($response);
        return 0;
    }
    
    
    if (!isset($email_id) || empty($email_id)) {
        $response= array("error_code" => 109, "message" => "The parameter 'email_id' is required and cannot be empty");
        echo json_encode($response);
        return 0;
    }
    
    return 1;
}
function unique_id_generate_bulk($id_prefix, $table_name, $mysqli, $id_for) {
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


?>