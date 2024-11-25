<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include 'PHPMailer/src/PHPMailer.php';
include 'PHPMailer/src/SMTP.php';
include 'PHPMailer/src/Exception.php';
include __DIR__ . '/vendor/autoload.php';

header('Content-Type: application/json; charset=UTF-8');
header('Access-Contorl-Allow-Origin: *');
date_default_timezone_set('Asia/kolkata');
include 'connection.php';
// Now you can use the connection class
$connection = connection::getInstance();
$mysqli = $connection->getConnection();

$connection1 = database::getInstance();
$mysqli1 = $connection1->getConnection();
apponoff($mysqli);
// logout($_SERVER['HTTP_AUTH_KEY'], $mysqli);

date_default_timezone_set('Asia/kolkata');
$system_date = date("y-m-d");
$system_date_time = date("Y-m-d H:i:s");





if ($_SERVER["REQUEST_METHOD"] != "POST") {
	$response[] = ["error_code" => 101, "message" => "Please use POST method"];
	echo json_encode($response);
	return;
}
$common_chk_error_res = common_chk_error($mysqli,$_POST['agency_id']);
if ($common_chk_error_res == 1) {
	 
	$agency_id = $_POST['agency_id'];
	$email_ids = $_POST['email_ids'];
	$pdf_report_url = $_POST['pdf_report_url'];

	$pdf_report_url_arr = explode(",", $pdf_report_url);
    $decoded_pdf_urls = array_map('urldecode', $pdf_report_url_arr);

    $sql = "SELECT `name`, `registration_id` FROM `member_header_all` WHERE `registration_id`='$agency_id'";
	$res_sql = mysqli_query($mysqli, $sql);
	$member_data = mysqli_fetch_assoc($res_sql);
	$member_name = $member_data['name'];


	$mail1 = new PHPMailer(true);
	ob_clean();
	 $mail1->isSMTP();
	        $mail1->Host = 'mail.mounarchtech.com';
	        $mail1->SMTPAuth = true;
	        $mail1->Username = 'transactions@mounarchtech.com';
	        $mail1->Password = 'Mtech!@12345678';  // Ideally, load this from environment variables
	        $mail1->SMTPSecure = 'ssl';
	        $mail1->Port = 465;
	        $mail1->setFrom('transactions@mounarchtech.com', 'Micro Integrated Semi Conductor Systems Pvt. Ltd.');
	        $mail1->isHTML(true);

	$email_ids_arr = explode(",", $email_ids);
	foreach ($email_ids_arr as $email) {
	    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
	        $message = 'Mail format is wrong. Please check.';
	        $responce = ["error_code" => 301, "message" => $message];
	        echo json_encode($responce);
	        return;
	    }
	    $mail1->addAddress($email);
	}

	$mail1->addReplyTo('info@microintegrated.in');
	$messageBody = '<!DOCTYPE html>
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
	            
	            <p>Dear '.$member_name.'</p>
	            <p>We are pleased to confirm that your identity verification process has been successfully completed using VOCOXP.</p>
	            <p>As part of our commitment to security and transparency, please find attached your confidential member reports.</p>
	            <p>If you have any questions or require assistance, please don\'t hesitate to reach out to our support team at <a href="mailto:support@microintegrated.in">support@microintegrated.in</a>.</p>
	            <p>Thank you for choosing our service to verify your identity. We value your trust and look forward to continuing to serve you.</p>
	           
	           
	            <p><b>Best regards,</b></p>
	            <p>Micro Integrated Semi Conductor Systems Pvt. Ltd. </p>
	           
	        </div>
	       
	        
	    </div>
	</body>
	</html>';
	// Content
	$mail1->isHTML(true);
	$mail1->Subject = 'Member Verification Completed: Access Your Secure Reports';
	$mail1->Body = $messageBody;

	$attachment = 0; // Initialize outside the loop

	foreach ($decoded_pdf_urls as $pdf_url) {
	    // Split URLs if there are multiple URLs in one string
	    $individual_urls = explode(",", $pdf_url);

	    foreach ($individual_urls as $url) {
	        // Trim spaces from the URL
	        $url = trim($url);

	        $given_file_name = basename($url);

	        // Check if the file is accessible using file_get_contents
	        $file_content = @file_get_contents($url);
	        if ($file_content !== false) {
	            $mail1->addStringAttachment($file_content, $given_file_name);
	            $attachment++; // Increment for each successful attachment
	        }
	    }
	}

	try {
	    if ($attachment > 0) {
	        if ($mail1->send()) {
	            $message = 'Mail sent successfully.';
	            $responce[] = ["error_code" => 100, "message" => $message];
	            echo json_encode($responce);
	            return;
	        } else {
	            $message = 'Failed to send mail. Please try again.';
	            $responce[] = ["error_code" => 199, "message" => $message];
	            echo json_encode($responce);
	            return;
	        }
	    } else {
	        $message = 'File does not exist or failed to add attachment. Please try after some time.';
	        $responce[] = ["error_code" => 199, "message" => $message];
	        echo json_encode($responce);
	        return;
	    }
	} catch (Exception $e) {
	    $message = $mail1->ErrorInfo;
	    $responce[] = ["error_code" => 199, "message" => $message];
	    echo json_encode($responce);
	    return;
	}

}




function common_chk_error($mysqli,$agency_id)
{
	$common_chk_error = 1;

	 
	 
	if (!isset($agency_id)) {
		$response[] = ["error_code" => 105, "message" => "agency_id parameter is missing"];
		echo json_encode($response);
		return;
	}
	if (empty($agency_id)) {
		$response[] = ["error_code" => 106, "message" => "agency_id parameter is empty"];
		echo json_encode($response);
		return;
	}
	 

	return $common_chk_error;
}

?>