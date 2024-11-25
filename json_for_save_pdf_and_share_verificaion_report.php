<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// error_reporting(E_ALL);
// ini_set('display_errors', 1);
// require '..phpmailer1/PHPMailer.php';
// require '../phpmailer1/SMTP.php';
// require '../phpmailer1/Exception.php';
include 'PHPMailer/src/PHPMailer.php';
include 'PHPMailer/src/SMTP.php';
include 'PHPMailer/src/Exception.php';
include __DIR__ . '/vendor/autoload.php';

//include 'shareMailPdf/function.php';
//include 'shareMailPdf/error_code.php';



// require_once("../libraries/Pdf.php");

// require_once("../individual_connection.php");
// require_once("../verification_api/functions.php");
/*Prepared by: Susmita Dharurkar
Name of API: : json_for_save_pdf_and_share_verificaion_report
Method: POST
Category: Action
Description:
This api enables us to save generated information in pdf format to server &
share multiple verification reports vai email.This documentation outlines the available request
modes, parameters, error handling procedures, and successful response formats.

Developed by: Akshay Patil
Note: Multi mode
mode: register_employee*/


// this json is used to add family member of resident.
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
$output = $responce = array();

// logout($_SERVER['HTTP_AUTH_KEY'], $mysqli);


if ($_SERVER["REQUEST_METHOD"] != "POST") {
	$response[] = ["error_code" => 101, "message" => "Please use POST method"];
	echo json_encode($response);
	return;
}


$common_chk_error_res = common_chk_error(
	$mysqli, $_POST['mode'], $_POST['agency_id'], $_POST['member_id'], $_POST['specification_id'], $_POST['pdf_file'], $_POST['email_ids'], $_POST['pdf_report_url'],
	$_POST['request_id'], $_POST['application_id'], $_POST['name']
);
if ($common_chk_error_res == 1) {
	$mode = $_POST['mode'];
	$agency_id = $_POST['agency_id'];
	$member_id = $_POST['member_id'];
	$specification_id = $_POST['specification_id'];
	$pdf_file = $_POST['pdf_file'];
	$email_ids = $_POST['email_ids'];
	$pdf_report_url = $_POST['pdf_report_url'];
	$request_id = $_POST['request_id'];
	$application_id = $_POST['application_id'];
	$name = $_POST['name'];
	$pdf_for = $_POST['pdf_for'];


	

}

if ($mode == "share_report") {





	$email_ids = $_POST['email_ids'];
$pdf_report_url = $_POST['pdf_report_url'];
$agency_id = $_POST['agency_id'];

$pdf_report_url_arr = explode(",", $pdf_report_url);
$decoded_pdf_urls = array_map('urldecode', $pdf_report_url_arr);

// Get member data
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
            
            <p>Dear'.$member_name.'</p>
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

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


if ($mode == "send_mail") {


	$email_ids = $_POST['email_ids'];
	$specification_id_with_request_id = $_POST['specification_id_with_request_id'];
	$member_id = $_POST['member_id'];
	$agency_id = $_POST['agency_id'];

	$specification_id_with_request_id_arr = explode(",", $specification_id_with_request_id);

	// Get member data
	$sql = "SELECT `name`, `registration_id` FROM `member_header_all` WHERE `registration_id`='$agency_id' AND member_id='$member_id'";
$res_sql = mysqli_query($mysqli, $sql);
$member_data = mysqli_fetch_assoc($res_sql);
$member_name = $member_data['name'];

$mail1 = new PHPMailer(true);

// Clear any previous output
ob_clean();

  $mail1->isSMTP();
        $mail1->Host = 'mail.mounarchtech.com';
        $mail1->SMTPAuth = true;
        $mail1->Username = 'transactions@mounarchtech.com';
        $mail1->Password = 'Mtech!@12345678';  // Ideally, load this from environment variables
        $mail1->SMTPSecure = 'ssl';
        $mail1->Port = 465;
        $mail1->setFrom('transactions@mounarchtech.com', 'Verification Report');
        $mail1->isHTML(true);

$email_ids_arr = explode(",", $email_ids);
foreach ($email_ids_arr as $email) {
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $mail1->addAddress($email);
    } else {
        $message = 'Mail format is wrong. Please check.';
        $responce = ["error_code" => 301, "message" => $message];
        echo json_encode($responce);
        return;
    }
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
            
        <p>Dear'.$member_name.'</p>
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

$attachment = 0;

for ($i = 0; $i < sizeof($specification_id_with_request_id_arr); $i++) {
    $file_arr = explode(":", $specification_id_with_request_id_arr[$i]);
    $sql12 = "SELECT `verification_id`,`name`,`table_name`,`image`,`type_id`,`type`,`status` FROM `verification_header_all` WHERE `verification_id`='" . $file_arr[0] . "'";
    $res_sql12 = mysqli_query($mysqli1, $sql12);
    $row23323 = mysqli_fetch_assoc($res_sql12);
    $table_name = $row23323['table_name'];
    $name = $row23323['name'];

    $request_id_arr = explode("@", $file_arr[1]);
    foreach ($request_id_arr as $request_id) {
        $sql2 = "SELECT `modified_on`, `verification_report`  FROM `$table_name` WHERE `request_id`='" . $request_id . "'";
        $res_sql23 = mysqli_query($mysqli1, $sql2);
        $row34 = mysqli_fetch_assoc($res_sql23);
        $updated_on = date('d-m-Y h:i:s', strtotime($row34['modified_on']));
        $file_path = $row34['verification_report'];

        // Download the file from the URL to a temporary directory on your server
        $temp_file_path = tempnam(sys_get_temp_dir(), 'voter_report');
        file_put_contents($temp_file_path, fopen($file_path, 'r'));

        if (($file_path != '') && (file_exists($temp_file_path))) {
            $attachment++;
            $mail1->addAttachment($temp_file_path, $name . '_' . $updated_on . '.pdf');
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

// updated member err






function common_chk_error($mysqli, $mode, $agency_id, $member_id)
{
	$common_chk_error = 1;

	if (!isset($mode)) {
		$response[] = ["error_code" => 102, "message" => "Please add mode"];
		echo json_encode($response);
		return;
	}
	if (empty($mode)) {
		$response[] = ["error_code" => 103, "message" => "mode is empty"];
		echo json_encode($response);
		return;
	}

	if ($mode != 'save_pdf' && $mode != 'share_report' && $mode != 'send_mail') {
		$response[] = ["error_code" => 104, "message" => "Paramter 'mode' value is invalid. please enter value either save_pdf or share_report or send_mail."];
		echo json_encode($response);
		return;
	}

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
	$sql = "SELECT `status`, `agency_id` FROM `agency_header_all` WHERE `agency_id`='$agency_id'";
	$res_sql = mysqli_query($mysqli, $sql);
	$agency_data = mysqli_fetch_assoc($res_sql);

	if (mysqli_num_rows($res_sql) == 0) {
		$response[] = ["error_code" => 107, "message" => "agency_id is not valid"]; //agency id not valid
		echo json_encode($response);
		return;
	} else if ($agency_data['status'] != '1') // 	check agency_id is active
	{
		$response[] = ["error_code" => 108, "message" => "agency_id is not active"];
		echo json_encode($response);
		return;
	} elseif ($agency_data['status'] != '1') // 	check agency_id is active
	{
		$response[] = ["error_code" => 107, "message" => "member_id is not active"];
		echo json_encode($response);
		return; 
	}

	return $common_chk_error;
}





// function generate_request_id($conn1) 
// {
// 	for( ; ; )
// 	{
// 		$digits = 8;
// 		$ref_code = 'R'.rand(pow(10, $digits-1), pow(10, $digits)-1);
// 		$sql_check="select person_id from verification_person_payment_request_details where request_id ='$ref_code'";
// 		$result_check=mysqli_query($conn1,$sql_check);
// 		if($result_check->num_rows==0)
// 		{
// 			return $ref_code;
// 		}	
// 	}
// }


function savePDF1($output_pdf, $member_id, $agency_id, $pdf_for)
{
	// FTP server credentials
	$ftp_server = '199.79.62.21';
	$ftp_username = 'centralwp@mounarchtech.com';
	$ftp_password = 'k=Y#oBK{h}OU';

	// Remote directory path
	$remote_base_dir = "/verification_data/$pdf_for/voco_xp/";

	// Nested directory structure to be created
	$new_directory_path = "$agency_id/$member_id/";

	// Initialize cURL session for FTP
	$curl = curl_init();

	// Set cURL options for FTP
	curl_setopt_array($curl, array(
		CURLOPT_URL => "ftp://$ftp_server/",
		CURLOPT_USERPWD => "$ftp_username:$ftp_password",
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_FTP_CREATE_MISSING_DIRS => true, // Create missing directories
	)
	);

	// Execute the cURL session for FTP
	ob_start(); // Start output buffering
	$response_ftp = curl_exec($curl);
	ob_end_clean(); // Discard output buffer

	// Check for errors in FTP request
	if ($response_ftp === false) {
		$error_message_ftp = curl_error($curl);
		die("Failed to connect to FTP server: $error_message_ftp");
	}

	// Set the target directory
	$remote_dir_path = $remote_base_dir . $new_directory_path;

	// Create directory recursively
	curl_setopt($curl, CURLOPT_URL, "ftp://$ftp_server/$remote_dir_path");
	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'MKD');
	ob_start(); // Start output buffering
	$response_mkdir = curl_exec($curl);
	ob_end_clean(); // Discard output buffer

	// Check for errors in directory creation
	if ($response_mkdir == false) {
		$error_message_mkdir = curl_error($curl);
	}

	// Generate a unique file name for the merged PDF
	$file_name = $pdf_for . date("YmdHis") . '.pdf';

	// Construct the full file path on the remote server
	$file_path = $remote_dir_path . $file_name;

	// Save the PDF to a temporary file
	$temp_file = tempnam(sys_get_temp_dir(), 'pdf');
	file_put_contents($temp_file, $output_pdf);

	// Initialize cURL session for file upload
	$curl_upload = curl_init();

	// Set cURL options for file upload
	curl_setopt_array($curl_upload, array(
		CURLOPT_URL => "ftp://$ftp_server/$file_path",
		CURLOPT_USERPWD => "$ftp_username:$ftp_password",
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_UPLOAD => true,
		CURLOPT_INFILE => fopen($temp_file, 'r'),
		CURLOPT_INFILESIZE => filesize($temp_file),
	)
	);

	// Execute cURL session for file upload
	ob_start(); // Start output buffering
	$response_upload = curl_exec($curl_upload);
	ob_end_clean(); // Discard output buffer

	// Check for errors in file upload
	if ($response_upload === false) {
		$error_message_upload = curl_error($curl_upload);
		die("Failed to save merged PDF file: $error_message_upload");
	}

	// Close cURL sessions
	curl_close($curl);
	curl_close($curl_upload);

	// Update the database with the path to the merged PDF file
	$base_url = "https://mounarchtech.com/central_wp/verification_data/$pdf_for/voco_xp/$agency_id/$member_id";
	$path = $base_url . '/' . $file_name;
	return $path;
}






?>