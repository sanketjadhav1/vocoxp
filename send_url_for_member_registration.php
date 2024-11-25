<?php
date_default_timezone_set("Asia/Kolkata");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

include __DIR__ . '/vendor/autoload.php';
include_once 'connection.php';
error_reporting(E_ALL & ~E_DEPRECATED);
ini_set('display_errors', 0);
$connection = connection::getInstance();
$mysqli = $connection->getConnection();
$system_date_time = date("Y-m-d H:i:s");
error_reporting(E_ALL);

$response = [];
$check_error = check_error($mysqli, $_POST['agency_id'], $_POST['member_ids']);

if ($check_error == 1) {
    $member_ids = $_POST['member_ids'];
    $agency_id = $_POST['agency_id'];
    $profile_action = $_POST['profile_action'];
    $mobile_no = $_POST['mobile_no'];
    $email_id = $_POST['email_id'];
    $mem_id = explode(",", $member_ids);
    if ($profile_action == 1) {
        $check_query = "SELECT * FROM member_header_all WHERE contact_no = '$mobile_no' and registration_id='$agency_id'";
			$result = mysqli_query($mysqli, $check_query);

			if ($result) {
				if (mysqli_num_rows($result) > 0) {
					$output[] = ["error_code" => 105, "message" => "Mobile number already exists. Please use another mobile number"];
					echo json_encode($output);
					return;
				}
			}
        $update_member = "UPDATE `member_header_all` SET `contact_no`='$mobile_no', `email_id`='$email_id' WHERE `member_id`='$member_ids' AND `registration_id`='$agency_id'";
        $update_member_query = $mysqli->query($update_member);
        if ($update_member_query) {
              $update_webdate = "UPDATE `member_header_all` SET `web_link_date`='$system_date_time' WHERE `member_id`='$member_ids' AND `registration_id`='$agency_id'";
                $res_webdate = mysqli_query($mysqli, $update_webdate);

            $mail = new PHPMailer(true);

            try {
                // Server settings
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


                 $fetch_member_email = "SELECT `email_id`, `contact_no`, `name`, `web_link` FROM `member_header_all` WHERE `member_id`='$member_ids' AND `registration_id`='$agency_id'";
                $res_mem = mysqli_query($mysqli, $fetch_member_email);
                $arr_mem = mysqli_fetch_assoc($res_mem);

                if ($arr_mem) {
                    $member_id1 = urlencode($mem);
                    $timestamp = date("d-m-Y H:i:s");
                    $token = generateToken($mem, $timestamp);
                    sms_helper_accept($arr_mem['contact_no'], $token);
                    $url = $arr_mem['web_link'];

                    $mail->addAddress($arr_mem['email_id']);
                    $mail->Body='<!DOCTYPE html>
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
									
									<p>Dear ' . $full_name. ',</p>
									<p>Please click on the link below to access your member profile and complete the data required for verification. This link is valid for 24 hours only for security reasons:</p><br>
									<p> '.$url.'</p><br>
								  <p>If you have any questions or require assistance, please don\'t hesitate to reach out to our support team at <a href="mailto:support@microintegrated.in">support@microintegrated.in</a>.</p>
								  <p>Thank you for your time and cooperation.</p><br>
									<p><b>Best regards,</b></p>
									<p>Micro Integrated Semi Conductor Systems Pvt. Ltd. </p>
								   
								</div>
							   
								
							</div>
						</body>
						</html>';
                    // $mail->Body = 'Hello ' . $arr_mem['name'] . ', below is the URL for self data filling. Please fill the form as soon as possible. This URL is valid for 24 hours. URL: ' . $url;

                    $mail->send();
                    $mail->clearAddresses();
                } else {
                    $response[] = ["error_code" => 107, "message" => "Member not found"];
                }

                
                // $response[] = ["error_code" => 100, "message" => "Mail sent successfully"];
            } catch (Exception $e) {
                $response[] = ["error_code" => 108, "message" => "Mail could not be sent. Mailer Error: {$mail->ErrorInfo}"];
            }
            $response[] = ["error_code" => 100, "message" => "Member updated successfully"];
            echo json_encode($response);
            return;
        }
    } else {
        // Create a new PHPMailer instance
        $mail = new PHPMailer(true);

        try {
            // Server settings
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

            foreach ($mem_id as $mem) {
                $fetch_member_email = "SELECT `email_id`, `contact_no`, `name`, `web_link` FROM `member_header_all` WHERE `member_id`='$mem' AND `registration_id`='$agency_id'";
                $res_mem = mysqli_query($mysqli, $fetch_member_email);
                $arr_mem = mysqli_fetch_assoc($res_mem);


                if ($arr_mem) {
                    $member_id1 = urlencode($mem);
                    $timestamp = date("d-m-Y H:i:s");
                    $token = generateToken($mem, $timestamp);
                    sms_helper_accept($arr_mem['contact_no'], $token);
                    $url = $arr_mem['web_link'];

                    $mail->addAddress($arr_mem['email_id']);
                    $mail->Body='<!DOCTYPE html>
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
									
									<p>Dear ' . $full_name. ',</p>
									<p>Please click on the link below to access your member profile and complete the data required for verification. This link is valid for 24 hours only for security reasons:</p><br>
									<p> '.$url.'</p><br>
								  <p>If you have any questions or require assistance, please don\'t hesitate to reach out to our support team at <a href="mailto:support@microintegrated.in">support@microintegrated.in</a>.</p>
								  <p>Thank you for your time and cooperation.</p><br>
									<p><b>Best regards,</b></p>
									<p>Micro Integrated Semi Conductor Systems Pvt. Ltd. </p>
								   
								</div>
							   
								
							</div>
						</body>
						</html>';
                    // $mail->Body = 'Hello ' . $arr_mem['name'] . ', below is the URL for self data filling. Please fill the form as soon as possible. This URL is valid for 24 hours. URL: ' . $url;

                    $mail->send();
                    $mail->clearAddresses();
                } else {
                    $response[] = ["error_code" => 107, "message" => "Member not found"];
                }
                $update_webdate = "UPDATE `member_header_all` SET `web_link_date`='$system_date_time' WHERE `member_id`='$mem' AND `registration_id`='$agency_id'";
                $res_webdate = mysqli_query($mysqli, $update_webdate);
            }

            $response[] = ["error_code" => 100, "message" => "Mail sent successfully"];
        } catch (Exception $e) {
            $response[] = ["error_code" => 108, "message" => "Mail could not be sent. Mailer Error: {$mail->ErrorInfo}"];
        }
        echo json_encode($response);
    }
}
function check_error($mysqli, $agency_id, $member_ids)
{
    $check_error = 1;
    $response = [];

    if (!$mysqli) {
        $response[] = ["error" => 101, "message" => "Unable to proceed your request, please try again after some time"];
        echo json_encode($response);
        return;
    }
    if ($_SERVER["REQUEST_METHOD"] != "POST") {
        $response[] = ["error_code" => 102, "message" => "Please change request method to POST"];
        echo json_encode($response);
        return;
    }

    if (!isset($agency_id) || $agency_id == "") {
        $response[] = ["error" => 103, "message" => "Please provide the parameter - agency_id"];
        echo json_encode($response);
        return;
    }

    if (!isset($member_ids) || $member_ids == "") {
        $response[] = ["error" => 105, "message" => "Please provide the parameter - member_ids"];
        echo json_encode($response);
        return;
    }

    return $check_error;
}

function generateToken($member_id, $timestamp)
{
    $delimiter = '|';
    $data = $member_id . $delimiter . $timestamp;
    $base64_encoded_token = base64_encode($data);
    return $base64_encoded_token;
}

function sms_helper_accept($contact, $token)
{
    $message = urlencode("VOCOxP: Our records indicate that your VOCOxP member profile is incomplete. Please complete the profile process by clicking the link below: https://mounarchtech.com/vocoxp/member_self_data_entry_form.php?token=$token");
    $route = "4";
    $mobile = "91" . $contact;

    $postData = [
        "authkey" => "362180AhWSVXbjwW5Z60c43f9aP1",
        "mobiles" => $mobile,
        "message" => $message,
        "sender" => "VOCOxP",
        "route" => $route,
        "response" => "json"
    ];

    $url = "http://api.msg91.com/api/sendhttp.php?authkey=362180AhWSVXbjwW5Z60c43f9aP1&sender=PMSafe&mobiles=$contact&route=$route&message=$message&DLT_TE_ID=1307171618329656100";

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $postData
    ]);

    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

    $output = curl_exec($ch);

    if (curl_errno($ch)) {
        return 'cURL Error: ' . curl_error($ch);
    } else {
        return 'API Response: ' . $output;
    }

    curl_close($ch);
}
