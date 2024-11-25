<?php

/*Prepared by: Ashish Khandare
Name of API: json_for_action_member_management
Method: POST
Category: Action
Description:
This API is to adding, updating, and deleting member details
Developed by: Akshay Patil
Note: Multi mode
mode: register_employee*/
// error_reporting(E_ALL & ~E_DEPRECATED);
// ini_set('display_errors', 1);

// this json is used to add family member of resident.
header('Content-Type: application/json; charset=UTF-8');
header('Access-Contorl-Allow-Origin: *');
date_default_timezone_set('Asia/kolkata');


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

include __DIR__ . '/vendor/autoload.php';
include 'connection.php';
// Now you can use the connection class
$connection = connection::getInstance();
$mysqli = $connection->getConnection();
date_default_timezone_set('Asia/kolkata');
$system_date = date("y-m-d");
$system_date_time = date("Y-m-d H:i:s");
$output = $responce = array();
apponoff($mysqli);
logout($_SERVER['HTTP_AUTH_KEY'], $mysqli);
// check_digital_verification($mysqli);


if ($_SERVER["REQUEST_METHOD"] != "POST") {
	$response[] = ["error_code" => 101, "message" => "Please use POST method"];
	echo json_encode($response);
	return;
}


$common_chk_error_res = common_chk_error($mysqli, $_POST['mode'], $_POST['agency_id'], $_POST['register_id'], $_POST['profile_image'], $_POST['full_name'], $_POST['contact'], $_POST['type'], $_POST['dob_or_doj'], $_POST['duty_time'], $_POST['duty_type'], $_POST['email_id']);
if ($common_chk_error_res == 1) {
	$mode = $_POST['mode'];
	$agency_id = $_POST['agency_id'];
	$member_id = $_POST['member_id'];
	$register_id = $_POST['register_id'];
	$profile_image = $_POST['profile_image'];
	$full_name = $_POST['full_name'];
	$email_id = $_POST['email_id'];
	$weblink = $_POST['weblink_type'];
	$verification_type = $_POST['verification_type'];
	$contact = $_POST['contact'];
	// $type = $_POST['type'];
	if($_POST['type']=="member"){
		$type=1;
	}elseif($_POST['type']=="employee"){
		$type=0;
	}
	$gender = $_POST['gender']; //optional
	$city = $_POST['city']; //optional
	$relation = $_POST['relation'];
	$designation = $_POST['designation']; //optional
	$address = $_POST['address']; //optional
	$dob_or_doj = $_POST['dob_or_doj'];
	// $duty_type = $_POST['duty_type'];
	if($_POST['duty_type']=="same"){
		$duty_type=1;
	}else{
		$duty_type=0;
	}
	$is_admin = $_POST['is_admin'];
	$duty_time = $_POST['duty_time'];
	$responsibilities = $_POST['responsibilities'];
	$responsibility_arr = explode(",", $responsibilities);
	$duty_time_arr = explode(',', $duty_time);
	$emp_imageURL = '';



	if ($mode == 'add_member') // add memeber start
	{
		//  add member error 

		if (!isset($agency_id)) {
			$response[] = ["error_code" => 102, "message" => "agency_id parameter is missing"];
			echo json_encode($response);
			return;
		}
		if (empty($agency_id)) {
			$response[] = ["error_code" => 102, "message" => "agency_id parameter is empty"];
			echo json_encode($response);
			return;
		}

		if (!isset($full_name)) {
			$response[] = ["error_code" => 105, "message" => "full_name parameter is missing"];
			echo json_encode($response);
			return;
		}
		if (empty($full_name)) {
			$response[] = ["error_code" => 105, "message" => "full_name parameter is empty"];
			echo json_encode($response);
			return;
		}
		//print_r($_FILES["profile_image"]);




		if (!isset($contact)) {
			$response[] = ["error_code" => 106, "message" => "contact parameter is missing"];
			echo json_encode($response);
			return;
		}
		if (empty($contact)) {
			$response[] = ["error_code" => 106, "message" => "contact parameter is empty"];
			echo json_encode($response);
			return;
		}
		// validateMOB


		if (!isset($_POST['type'])) {
			$response[] = ["error_code" => 107, "message" => "type parameter is missing"];
			echo json_encode($response);
			return;
		}
		if (empty($_POST['type'])) {
			$response[] = ["error_code" => 107, "message" => "type parameter is empty"];
			echo json_encode($response);
			return;
		}


		if ($_POST['type'] != 'member' && $_POST['type'] != 'employee') {
			$response[] = ["error_code" => 107, "message" => "Type is invalid. please enter type either member or employee ."];
			echo json_encode($response);
			return;
		}


		if ($_POST['type'] == 'member') {
			$gender = $_POST['gender']; //optional
			$city = $_POST['city']; //optional
			$address = $_POST['address']; //optional
			$dob_or_doj = $_POST['dob_or_doj'];
			$relation = $_POST['relation'];

			

		} else {


			$designation = $_POST['designation'];
			$city = $_POST['city']; //optional
			$address = $_POST['address']; //optional
			$dob_or_doj = $_POST['dob_or_doj'];
			if($_POST['duty_type']=="same"){
				$duty_type=1;
			}else{
				$duty_type=0;
			}
			$is_admin = $_POST['is_admin'];
			$responsibilities = $_POST['responsibilities'];
			$duty_time = $_POST['duty_time'];

			if($weblink==""){
				if (!isset($dob_or_doj)) {
					$response[] = ["error_code" => 110, "message" => "date_of_joining parameter is missing"];
					echo json_encode($response);
					return;
				}
				if (empty($dob_or_doj)) {
					$response[] = ["error_code" => 110, "message" => "date_of_joining parameter is empty"];
					echo json_encode($response);
					return;
				}
		
				if (!isset($address)) {
					$response[] = ["error_code" => 111, "message" => "address parameter is empty"];
					echo json_encode($response);
					return;
				}
		
				if (empty($address)) {
					$response[] = ["error_code" => 111, "message" => "address parameter is empty"];
					echo json_encode($response);
					return;
				}
		
		
				if (!isset($city)) {
					$response[] = ["error_code" => 112, "message" => "city parameter is empty"];
					echo json_encode($response);
					return;
				}
				if (empty($city)) {
					$response[] = ["error_code" => 112, "message" => "city parameter is empty"];
					echo json_encode($response);
					return;
				}
		
			}
			
			if ($is_admin == 'Y') {

				if (!isset($_POST['responsibilities'])) {
					$response[] = ["error_code" => 113, "message" => "responsibilities parameter is empty"];
					echo json_encode($response);
					return;
				}

			}

			// if ($duty_type == 'shift_type_in_weekday' && $duty_type == 'shift_weekday_with_time') {
			// 	$response[] = ["error_code" => 114, "message" => "value of duty_type must be either shift_type_in_weekday or shift_weekday_with_time"];
			// 	echo json_encode($response);
			// 	return;
			// }
			// if ($duty_type == 'shift_type_in_weekday') {
			// 	if (!isset($_POST['duty_time'])) {
			// 		$response[] = ["error_code" => 115, "message" => "Please pass duty_time parameter  day_start_date  or day_end_date"];
			// 		echo json_encode($response);
			// 		return;
			// 	} else {
			// 		if ($_POST['duty_time'] == '') {
			// 			$response[] = ["error_code" => 115, "message" => "Please pass parameter duty_time day_start_date or day_end_date"];
			// 			echo json_encode($response);
			// 			return;
			// 		} else {
			// 			$duty_time = $_POST['duty_time'];

			// 		}
			// 	}




			// }



		}
		if ($dob_or_doj != '') {
			$dob_or_doj = date("Y-m-d", strtotime($dob_or_doj));
		} else {
			$dob_or_doj = '';
		}

        //connection.php
		$member_id = unique_id_genrate('MEM', 'member_header_all', $mysqli);

				



		///////////////////////////////////////////////////////
		if ($profile_image != '') {
			$base_url = 'https://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);

			if (!is_dir($createDir)) {
				mkdir($createDir, 0777, true); // Recursive directory creation
			}

			$base64_image =$_POST['profile_image'];

			// Decode the base64 image data
			$image_data = base64_decode($base64_image);

			// Specify the directory where you want to save the image
			$target_dir_profile = __DIR__ . "/active_folder/agency/member/profile_picture/"; // Use the __DIR__ constant to get the absolute path

			// Create the target directory if it doesn't exist
			if (!file_exists($target_dir_profile)) {
				if (!mkdir($target_dir_profile, 0777, true)) {
					die('Failed to create directory...');
				}
			}

			// Generate a unique filename
			$file_name = uniqid() . '.png';

			// Construct the full URL of the file
			$emp_imageURL = $base_url . "/active_folder/agency/member/profile_picture/" . $file_name;

			// Construct the full path of the file
			$file_path = $target_dir_profile . $file_name;

			// Write the decoded image data to the file
			$success = file_put_contents($file_path, $image_data);

			if ($success !== false) {
				// echo "Image successfully saved: " . $emp_imageURL;
			} else {
				// echo "Failed to save the image.";
			}
		}
		if ($_POST['type'] == 'employee') {
			$check_query = "SELECT * FROM member_header_all WHERE contact_no = '$contact' and registration_id='$agency_id'";
			$result = mysqli_query($mysqli, $check_query);

			if ($result) {
				if (mysqli_num_rows($result) > 0) {
					$output[] = ["error_code" => 105, "message" => "Mobile number already exists. Please use another mobile number"];
					echo json_encode($output);
					return;
				}
			} else {
				$output[] = ["error_code" => 500, "message" => "Database error: " . mysqli_error($mysqli)];
				echo json_encode($output);
				return;
			}
		}
		// exit;
		/////////////////////////////////////////
		if($weblink!="0"){
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
		
				
					
		
					
				$member_id1 = urlencode($member_id);
                $timestamp = date("d-m-Y H:i:s");
                $token = generateToken($member_id, $timestamp);
                sms_helper_accept($contact, $token);
                $url = 'https://mounarchtech.com/vocoxp/member_self_data_entry_form.php?token=' . $token;
		
						$mail->addAddress($email_id);
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
						
		
						$mail->send();
						$mail->clearAddresses();
					
				
		
				$response[] = ["error_code" => 100, "message" => "Mail sent successfully"];
			} catch (Exception $e) {
				$response[] = ["error_code" => 108, "message" => "Mail could not be sent. Mailer Error: {$mail->ErrorInfo}"];
			}
			// echo json_encode($response);
			// exit;
		}
		

		

		 $insert_member_setting = "INSERT INTO `member_settings_all` (`id`, `agency_id`, `member_id`, `app_setting_on_off`, `sos`, `geo_location_auto_update`, `manual_recording`, `manual_recording_notification`, `ecrime_reminder`, `alert_when_watch_out_from_gps_range`, `fsn_when_out_of_gps_location`, `bluetooth`, `carrier`, `heart_rate`, `spo2`, `body_temp`, `alert_when_watch_remove`, `alert_on_watch_remove_from_wrist`, `alert_on_watch_removal_after_every`, `alrert_on_sim_remove`, `fsn_for_sim_remove`, `alert_on_watch_to_reconnect_server`, `fsn_watch_server_reconnect`, `pre_schedule_alert`, `fsn_pre_schedule_alert_not_delivered`) VALUES (NULL, '$agency_id', '$member_id', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '');";
		$result_member_setting = mysqli_query($mysqli, $insert_member_setting);
if($weblink!="0"){
	$web_date=date("Y-m-d H:i:s");
}else{
	$web_date='';
}
		 $sql_insert = "INSERT INTO `member_header_all` (`registration_id`, `member_id`, `profile_image`, `name`, `contact_no`, `email_id`, `type`, `gender`, `city`, `address`, `relation`, `designation`, `dob_or_doj`, `duty_type`, `duty_time`, `is_admin`, `status`, `created_on`, `web_link`, `web_link_type`, `web_link_status`, `web_link_date`, `web_link_verifications`)
	VALUES ('$agency_id', '$member_id','$emp_imageURL', '$full_name', '$contact','$email_id', '$type', '$gender', '$city', '$address', '$relation', '$designation', '$dob_or_doj', '$duty_type', '$duty_time', '$is_admin', '1', '$system_date_time', '$url', '$weblink', '0', '$web_date', '$verification_type')";

		$result_insert = mysqli_query($mysqli, $sql_insert);

		
		//echo $result_insert;
		if ($mysqli->affected_rows > 0) {


			$output[] = ["error_code" => 100, "message" => "Member added successfully.", "member_id" => $member_id, "member_name" => $full_name, "send_url"=>"url send successfully"];
			echo json_encode($output);
			return;

		} else {
			$output[] = ["error_code" => 101, "message" => "Member not added."];
			echo json_encode($output);
			return;
		}


	}
} else {
	echo $common_chk_error_res;
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////






if ($mode == 'update_member') {



	// updated member error
	$sql = "SELECT * FROM `member_header_all` WHERE member_id='$member_id'";
	$res_sql = mysqli_query($mysqli, $sql);
	$agency_data = mysqli_fetch_assoc($res_sql);

	if (mysqli_num_rows($res_sql) == 0) {
		$response[] = ["error_code" => 106, "message" => "member_id is not valid"]; //agency id not valid
		echo json_encode($response);
		return;
	}




	if (!isset($full_name)) {
		$response[] = ["error_code" => 105, "message" => "full_name parameter is missing"];
		echo json_encode($response);
		return;
	} else if (empty($full_name)) {
		$response[] = ["error_code" => 105, "message" => "full_name parameter is empty"];
		echo json_encode($response);
		return;
	} else if (!isset($contact)) {
		$response[] = ["error_code" => 106, "message" => "contact parameter is missing"];
		echo json_encode($response);
		return;
	} else if (empty($contact)) {
		$response[] = ["error_code" => 106, "message" => "contact parameter is empty"];
		echo json_encode($response);
		return;
	} else if (!isset($_POST['type'])) {
		$response[] = ["error_code" => 107, "message" => "type parameter is missing"];
		echo json_encode($response);
		return;
	} else if (empty($_POST['type'])) {
		$response[] = ["error_code" => 107, "message" => "type parameter is empty"];
		echo json_encode($response);
		return;
	} else if ($_POST['type'] != 'member' && $_POST['type'] != 'employee') {
		$response[] = ["error_code" => 107, "message" => "Type is invalid. please enter type either member or employee ."];
		echo json_encode($response);
		return;
	}


	if ($_POST['type'] == 'member') {
		$gender = $_POST['gender']; //optional
		$city = $_POST['city']; //optional
		$address = $_POST['address']; //optional
		$dob_or_doj = $_POST['dob_or_doj'];
		$relation = $_POST['relation'];

		// if (!isset($dob_or_doj)) {
		// 	$response[] = ["error_code" => 108, "message" => "date_of_birth parameter is missing"];
		// 	echo json_encode($response);
		// 	return;
		// }
		// if (empty($dob_or_doj)) {
		// 	$response[] = ["error_code" => 108, "message" => "date_of_birth  parameter is empty"];
		// 	echo json_encode($response);
		// 	return;
		// }


	} else {


		$designation = $_POST['designation'];
		$city = $_POST['city']; //optional
		$address = $_POST['address']; //optional
		$dob_or_doj = $_POST['dob_or_doj'];
		if($_POST['duty_type']=="same"){
			$duty_type=1;
		}else{
			$duty_type=0;
		}
		$is_admin = $_POST['is_admin'];
		$responsibilities = $_POST['$responsibilities'];
		$duty_time = $_POST['duty_time'];

		// if (!isset($dob_or_doj)) {
		// 	$response[] = ["error_code" => 110, "message" => "date_of_joining parameter is missing"];
		// 	echo json_encode($response);
		// 	return;
		// }
		// if (empty($dob_or_doj)) {
		// 	$response[] = ["error_code" => 110, "message" => "date_of_joining parameter is empty"];
		// 	echo json_encode($response);
		// 	return;
		// }

		// if (!isset($address)) {
		// 	$response[] = ["error_code" => 111, "message" => "address parameter is empty"];
		// 	echo json_encode($response);
		// 	return;
		// }

		// if (empty($address)) {
		// 	$response[] = ["error_code" => 111, "message" => "address parameter is empty"];
		// 	echo json_encode($response);
		// 	return;
		// }


		// if (!isset($city)) {
		// 	$response[] = ["error_code" => 112, "message" => "city parameter is empty"];
		// 	echo json_encode($response);
		// 	return;
		// }
		// if (empty($city)) {
		// 	$response[] = ["error_code" => 112, "message" => "city parameter is empty"];
		// 	echo json_encode($response);
		// 	return;
		// }



		if ($is_admin == 'Y') {

			if (!isset($_POST['responsibilities'])) {
				$response[] = ["error_code" => 113, "message" => "responsibilities parameter is empty"];
				echo json_encode($response);
				return;
			}

		}


		if ($duty_type == 'shift_type_in_weekday' && $duty_type == 'shift_weekday_with_time') {
			$response[] = ["error_code" => 114, "message" => "value of duty_type must be either shift_type_in_weekday or shift_weekday_with_time"];
			echo json_encode($response);
			return;
		}
		if ($duty_type == 'shift_type_in_weekday') {
			if (!isset($_POST['duty_time'])) {
				$response[] = ["error_code" => 115, "message" => "Please pass duty_time parameter  day_start_date  or day_end_date"];
				echo json_encode($response);
				return;
			} else {
				if ($_POST['duty_time'] == '') {
					$response[] = ["error_code" => 115, "message" => "Please pass parameter duty_time day_start_date or day_end_date"];
					echo json_encode($response);
					return;
				} else {
					$duty_time = $_POST['duty_time'];

				}
			}




		}



	}

	//////////////////////////////////////////////////////////////////////////////////////

	if ($profile_image != '') {


		$base_url = 'https://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);

			if (!is_dir($createDir)) {
				mkdir($createDir, 0777, true); // Recursive directory creation
			}

			$base64_image =$_POST['profile_image'];

			// Decode the base64 image data
			$image_data = base64_decode($base64_image);

			// Specify the directory where you want to save the image
			$target_dir_profile = __DIR__ . "/active_folder/agency/member/profile_picture/"; // Use the __DIR__ constant to get the absolute path

			// Create the target directory if it doesn't exist
			if (!file_exists($target_dir_profile)) {
				if (!mkdir($target_dir_profile, 0777, true)) {
					die('Failed to create directory...');
				}
			}

			// Generate a unique filename
			$file_name = uniqid() . '.png';

			// Construct the full URL of the file
			$emp_imageURL = $base_url . "/active_folder/agency/member/profile_picture/" . $file_name;

			// Construct the full path of the file
			$file_path = $target_dir_profile . $file_name;

			// Write the decoded image data to the file
			$success = file_put_contents($file_path, $image_data);

			if ($success !== false) {
				// echo "Image successfully saved: " . $emp_imageURL;
			} else {
				// echo "Failed to save the image.";
			}
		// $base_url = 'https://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
		// $createDir = "/active_folder/agency/member/profile_picture/$member_id/";

		// if (!is_dir($createDir)) {
		// 	mkdir($createDir, 0777, true); // Recursive directory creation
		// }


		// $kya_huva = "pata nahi";
		// $uploaddir = $createDir;

		// define('UPLOAD_DIR', $uploaddir);
		// $image_parts = explode(";base64,", $_POST['profile_image']);
		// $image_type_aux = explode("profile_image/", $image_parts[0]);
		// $image_type = $image_type_aux[0];
		// $image_base64 = base64_decode($image_parts[0]);
		// $file1 = uniqid() . '.png';
		// $file = UPLOAD_DIR . $file1;
		// if (file_exists($file)) {
		// 	$kya_huva = "nahi huva";
		// 	unlink($file);
		// 	$success = file_put_contents($file, $image_base64);
		// } else {
		// 	$kya_huva = "ho gaya";
		// 	file_put_contents($file, $image_base64);
		// }

		// if ($kya_huva == "ho gaya") {

		// 	$emp_imageURL = $base_url . $createDir . $file1;
		// }
	}



	//////////////////////////////////////////////////////////////////////////////////////////////////////////////

	if ($dob_or_doj != '') {
		$dob_or_doj = date("Y-m-d", strtotime($dob_or_doj));
	} else {
		$dob_or_doj = '';
	}
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	if ($_POST['type']== 'employee') {
		$check_query = "SELECT * FROM `member_header_all` WHERE `contact_no` = '$contact' and `member_id`='$member_id'";
		$result = mysqli_query($mysqli, $check_query);

		// if ($result) {
		// 	if (mysqli_num_rows($result) > 0) {
		// 		$output[] = ["error_code" => 105, "message" => "Mobile number already exists. Please use another mobile number"];
		// 		echo json_encode($output);
		// 		return;
		// 	}
		// }
	}
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//  $check_query = "SELECT * FROM member_header_all WHERE registration_id='$agency_id'";
	// $result = mysqli_query($mysqli, $check_query);
	// if ($mysqli->affected_rows > 0) {
	// 	$row_check_same_user = mysqli_fetch_assoc($result);
	// 	$output[] = ["error_code" => 1005, "message" => "Please enter the valid agency ID "];
	// 	echo json_encode($output);
	// 	return;
	// }

	//$member_id=generate_member_id($mysqli,$agency_id);

$currentDate = date('Y-m-d H:i:s');
	$sql_update = "UPDATE `member_header_all` SET `name` = '$full_name', `contact_no` = '$contact', `email_id` = '$email_id', `type` = '$type', `gender` = '$gender', `city` = '$city', `address` = '$address', `relation` = '$relation', `designation` = '$designation', `dob_or_doj` = '$dob_or_doj',	`duty_type` = '$duty_type',	`duty_time` = '$duty_time',	`is_admin` = '$is_admin', `status` = '1', `updated_on` = '$currentDate' WHERE `member_id` = '$member_id' ";


	$result_insert = mysqli_query($mysqli, $sql_update);
	//echo $result_insert;
	if ($profile_image != "") {
		$sql_update1 = "UPDATE `member_header_all` SET `profile_image` = '$emp_imageURL' WHERE 
	`member_id` = '$member_id' ";

		$result_insert = mysqli_query($mysqli, $sql_update1);
	}
	if ($mysqli->affected_rows > 0) {

		$output[] = ["error_code" => 100, "message" => "Member Updated successfully.", "member_id" => $member_id, "member_name" => $full_name];
		echo json_encode($output);
		return; 
	} else {
		$output[] = ["error_code" => 101, "message" => "Member not Updated."];
		echo json_encode($output);
		return;
	}
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

if ($mode == 'delete_member') {

	$sql = "SELECT * FROM `member_header_all` WHERE `member_id`='$member_id'";
	$res_sql = mysqli_query($mysqli, $sql);
	$agency_data = mysqli_fetch_assoc($res_sql);

	if (mysqli_num_rows($res_sql) == 0) {
		$response[] = ["error_code" => 106, "message" => "member_id is not valid"]; //agency id not valid
		echo json_encode($response);
		return;
	}

	$sql_copy_to_archeive = "INSERT INTO `member_header_archive_all` SELECT * FROM `member_header_all` WHERE `registration_id`='$agency_id' and `member_id`='$member_id'";
	$copy = mysqli_query($mysqli, $sql_copy_to_archeive);




	$delete_member = "DELETE FROM `member_header_all` WHERE `member_id`='$member_id'";
	$result = mysqli_query($mysqli, $delete_member);
	//echo $result_insert;
	if ($result == true) {

		$output[] = ["error_code" => 100, "message" => "Member deleted successfully."];
		echo json_encode($output);
		return;


		//insert duty times day wise
	} else {
		$output[] = ["error_code" => 101, "message" => "Member not deleted."];
		echo json_encode($output);
		return;
	}


} else {
	echo $common_chk_error_res;
}




function common_chk_error($mysqli, $mode, $agency_id, $profile_image, $full_name, $contact, $type)
{
	$common_chk_error = 1;
	if (!isset($mode)) {
		$response[] = ["error_code" => 102, "message" => "Please add mode"];
		echo json_encode($response);
		return;
	} else if (empty($mode)) {
		$response[] = ["error_code" => 103, "message" => "mode is empty"];
		echo json_encode($response);
		return;
	} else if ($mode != 'add_member' && $mode != 'update_member' && $mode != 'delete_member') {
		$response[] = ["114", "Paramter 'mode' value is invalid. please enter value either add_member or update_member or delete_member."];
		echo json_encode($response);
		return;
	}

	$sql = "SELECT * FROM `agency_header_all` WHERE agency_id='$agency_id'";
	$res_sql = mysqli_query($mysqli, $sql);
	$agency_data = mysqli_fetch_assoc($res_sql);

	if (mysqli_num_rows($res_sql) == 0) {
		$response[] = ["error_code" => 106, "message" => "agency_id is not valid"]; //agency id not valid
		echo json_encode($response);
		return;
	} else if ($agency_data['status'] != '1') // 	check agency_id is active
	{
		$response[] = ["error_code" => 107, "message" => "agency_id is not active"];
		echo json_encode($response);
		return; ///agency is not active
	}


	return $common_chk_error;
}

function sms_helper_accept($contact)
{
    $message = urlencode("VOCOxP: Our records indicate that your VOCOxP member profile is incomplete. Please complete the profile process by clicking the link below: https://mounarchtech.com/vocoxp/member_self_data_entry_form.php");
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
function generateToken($member_id, $timestamp)
{
    $delimiter = '|';
    $data = $member_id . $delimiter . $timestamp;
    $base64_encoded_token = base64_encode($data);
    return $base64_encoded_token;
}
?>