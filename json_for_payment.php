<?php
// ini_set('display_errors', 1);
// error_reporting(E_ALL);
// require_once("../libraries/Pdf.php");

// require_once("../individual_connection.php");
// require_once("../verification_api/functions.php");
/*Prepared by: Sahil Chavan
Name of API: : json_for_payment.
Method: POST
Category: Action
Description:
 This API is to use Aadd money in wallet and also use make payment for advance verfication.

Developed by: Akshay Patil
Note: Multi mode
mode: register_employee*/

use PHPMailer\PHPMailer\PHPMailer;
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

include __DIR__ . '/vendor/autoload.php';
// this json is used to add family member of resident.
header('Content-Type: application/json; charset=UTF-8');
header('Access-Contorl-Allow-Origin: *');
date_default_timezone_set('Asia/kolkata');
include 'connection.php';
// Now you can use the connection class
$connection = connection::getInstance();
$mysqli = $connection->getConnection();
///////////////////////////////////////////////
$connection1 = database::getInstance();
$mysqli1 = $connection1->getConnection();
///////////////////////////////////////
date_default_timezone_set('Asia/kolkata');
$system_date = date("y-m-d");
$system_date_time = date("Y-m-d H:i:s");
$output = $responce = array();
apponoff($mysqli);

logout($_SERVER['HTTP_AUTH_KEY'], $mysqli);

// check_digital_verification($mysqli);

// if ($_SERVER["REQUEST_METHOD"] != "POST") 
// {
// 	$response[]=["error_code"=>101,"message"=>"Please use POST method"];
// 	echo json_encode($response);
// 	return;
// }





$mode = $_POST['mode'];
$amount = $_POST['amount'];
$status = $_POST['status'];
$offer_id = $_POST['offer_id'];
$cashback_amount = $_POST['cashback_amount'];
$agency_id = $_POST['agency_id'];
$payment_status = $_POST['payment_status'];
$application_id = $_POST['application_id'];
$status = $_POST['payment_status'];

$jsonData = file_get_contents("php://input");

// Decode the JSON data
$decodedData = json_decode($jsonData, true);

// Access individual values
$mode = $decodedData['mode'];
$member_id = $decodedData['member_id'];
$application_id = $decodedData['application_id'];
$sub_amount = $decodedData['sub_amount'];
$cgst_amount = $decodedData['cgst_amount'];
$sgst_amount = $decodedData['sgst_amount'];
$net_amount = $decodedData['net_amount'];
$payment_status = $decodedData['payment_status'];
$transaction_id = $decodedData['payment_trans_id'];
$specification_list = $decodedData['specification_list'];
$request_for = $decodedData['request_for'];
$check_payment_terms = $decodedData['check_payment_terms'];
$agency_id = $decodedData['agency_id'];
$payment_via = $decodedData['payment_via'];
$status = $decodedData['status'];
$type = $decodedData['type'];

$specification_ids = [];
foreach ($specification_list as $specification) {
	if (isset($specification['specification_id'])) {
		$specification_ids[] = $specification['specification_id'];
		
	}
}

// Now $specification_ids contains all the specification IDs



/////////////////////////////////////////////////////////////////////////////////
$purpose = "wallet recharge"; //   add money
$amount = $decodedData['amount'];
 $status = $decodedData['payment_status'];

$offer_id = $decodedData['offer_id'];
$cashback_amount = $decodedData['cashback_amount'];
$type = 'credit';
$member_id = $decodedData['member_id'];

/////////////////////////////////////////////////////////////////////////////////////
$amount = $decodedData['amount'];       // purchase storage plan
$storage_plan = $decodedData['storage_plan'];
$payment_via = $decodedData['payment_via'];
$order_id = $decodedData['order_id'];
$transaction_id = $decodedData['transaction_id'];
$payment_status = $decodedData['payment_status'];
$agency_id = $decodedData['agency_id'];
$application_id = $decodedData['application_id'];
$order_id = $decodedData['order_id'];
$type = $decodedData['type'];
$member_id = $decodedData['member_id'];
$admin_id = $decodedData['admin_id'];
//////////////////////////////////////////////////////////////////////////

$amount = $decodedData['amount'];
$sold_to = $decodedData['sold_to'];
$order_id = $decodedData['order_id'];
$address = $decodedData['address'];
$mobile_no = $decodedData['mobile_no'];
$payment_via = $decodedData['payment_via'];
$payment_status = $decodedData['payment_status'];
$agency_id = $decodedData['agency_id'];
$application_id = $decodedData['application_id'];
$order_total = $decodedData['order_total'];
$transation_id = $decodedData['transation_id'];
$address2 = $decodedData['address2'];
$agency_mobile_no = $decodedData['agency_mobile_no'];
$member_id = $decodedData['member_id'];
$product_id = $decodedData['product_id'];






// $order_total = mysqli_real_escape_string($mysqli, $_GET['order_total']);
// $transation_id = mysqli_real_escape_string($mysqli, $_GET['transation_id']);
// $agency_id = mysqli_real_escape_string($mysqli, $_GET['agency_id']);
// $address2 = mysqli_real_escape_string($mysqli, $_GET['address']);
// $agency_mobile_no = mysqli_real_escape_string($mysqli, $_GET['agency_mobile_no']);
// $sold_to = mysqli_real_escape_string($mysqli, $_GET['sold_to']);
// $payment_mode = mysqli_real_escape_string($mysqli, $_GET['payment_mode']);


///////////////////////////////////////////////////////////////////////////////
$common_chk_error_res = common_chk_error($mysqli, $mode, $agency_id, $transaction_id, $payment_status, $application_id, $sub_amount, $purpose, $amount, $status, $offer_id, $cashback_amount, $type, $product_id, $member_id);
if ($common_chk_error_res == 1) {


	if ($mode == 'add_money') {



		if (!isset($amount)) {
			$response[] = ["error_code" => 101, "message" => "Invalid Data"];
			echo json_encode($response);
			return;
		} else if (empty($amount)) {
			$response[] = ["error_code" => 101, "message" => "amout parameter"];
			echo json_encode($response);
			return;
		} else {


			$sql = "SELECT * FROM `agency_header_all` WHERE `agency_id`='$agency_id'";
			$res_sql = mysqli_query($mysqli, $sql);
			$row = mysqli_fetch_assoc($res_sql);
			$current_wallet_bal = $row['current_wallet_bal'];
			$new_coupan_add_on_amount = $row['coupan_add_on_amount'];
			$new_current_bal = floatval($current_wallet_bal) + floatval($amount) + floatval($cashback_amount);
			//echo "$new_current_bal";
			if (($cashback_amount != '') && ($cashback_amount > 0))
				$new_coupan_add_on_amount = $new_coupan_add_on_amount + $cashback_amount;
			if ($status == 'success') {
				$closing_bal = number_format((float)$new_current_bal, 2, '.', '');
			} else {
				$closing_bal = $current_wallet_bal;
			}
			$fetch_wallet_trans="SELECT `line_no` FROM `wallet_recharge_transaction_all` WHERE `agency_id`='$agency_id' ORDER BY `id` DESC LIMIT 1";
			$res_wallet_trans=mysqli_query($mysqli, $fetch_wallet_trans);
			$arr_wallet_trans=mysqli_fetch_assoc($res_wallet_trans);
			if($arr_wallet_trans==""){
				$line_no=1;
			}else{
				$line_no=$arr_wallet_trans['line_no']+1;
			}
			
			// $sql1 = "INSERT INTO `wallet_transaction_all`(`agency_id`,`member_id`,`transaction_id`, `purpose`, `amount`, `date`, `status`,`type`,`closing_balance`,`offer_id`,`offer_add_on_amount`)VALUES ('$agency_id ','$admin_id ','$transaction_id','$purpose',' $amount',' $system_date_time', '$status','1','$closing_bal','$offer_id','$cashback_amount')";
			if($status=="success"){
			$sql1 = "INSERT INTO `wallet_recharge_transaction_all` (`id`, `agency_id`, `line_no`, `transaction_id`, `payment_gateway_id`, `initial_wallet_balance`, `added_amount`, `final_blnce`, `transaction_date`, `recharge_from`, `coupon_amnt`) VALUES (NULL, '$agency_id', '$line_no', '', '$transaction_id', '$current_wallet_bal', '$amount', '$new_current_bal', '$system_date_time', '1', '0');";
			$result1 = mysqli_query($mysqli, $sql1);
			}else{
				$sql1 = "INSERT INTO `wallet_recharge_failed_transaction_all` (`id`, `agency_id`, `line_no`, `transaction_id`, `payment_gateway_id`, `initial_wallet_balance`, `added_amount`, `final_blnce`, `transaction_date`, `recharge_from`, `coupon_amnt`) VALUES (NULL, '$agency_id', '$line_no', '', '$transaction_id', '$current_wallet_bal', '$amount', '$new_current_bal', '$system_date_time', '1', '0');";
				$result_failed = mysqli_query($mysqli, $sql1);
			}
			if ($result1 === true) {
				if ($status == 'success') {
					$mail = new PHPMailer(true);
			
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
			
				// Recipients
				    // $mail->setFrom('info@microintegrated.in', 'Micro Integrated Semi Conductor Systems Pvt. Ltd.');
				$mail->addAddress($row['email_id'], $row['name']);  // Add a recipient
			
				// Content
				$mail->isHTML(true);
				$mail->Subject = 'Confirmation: Money Added to Your VocoXp Wallet';
			
				// HTML email content
				$html = '<!DOCTYPE html>
			<html lang="en">
			<head>
				<meta charset="UTF-8">
				<meta name="viewport" content="width=device-width, initial-scale=1.0">
				<title>Confirmation: Money Added to Your VocoXp Wallet</title>
				<style>
					body {
						font-family: Arial, sans-serif;
						background-color: #f4f4f4;
						margin: 0;
						padding: 0;
					}
					.email-container {
						max-width: 600px;
						margin: 20px auto;
						background-color: #ffffff;
						padding: 20px;
						border: 1px solid #dddddd;
					}
					.email-header {
						text-align: center;
						margin-bottom: 20px;
					}
					.email-header h1 {
						margin: 0;
						color: #333333;
					}
					.email-body {
						font-size: 16px;
						color: #555555;
						line-height: 1.5;
					}
					.email-body p {
						margin: 10px 0;
					}
					.email-footer {
						margin-top: 20px;
						text-align: center;
						font-size: 14px;
						color: #777777;
					}
				</style>
			</head>
			<body>
				<div class="email-container">
					<div class="email-header">
						<h1>Confirmation: Money Added to Your VocoXp Wallet</h1>
					</div>
					<div class="email-body">
						<p>Dear '.$row['name'].',</p>
						<p>We are pleased to inform you that money has been successfully added to your VocoXp wallet.</p>
						<p><strong>Transaction Details:</strong></p>
						<ul>
							<li>Amount Added: Rs. '.$amount.' /-</li>
							<li>Transaction Date: '.date("d-m-Y", strtotime($system_date_time)).'</li>
							<li>Wallet Balance: Rs. '.$new_current_bal.' /-</li>
						</ul>
						<p>If you have any questions or require assistance, please don\'t hesitate to reach out to our support team at <a href="mailto:support@microintegrated.in">support@microintegrated.in</a>.</p>
						<p>Thank you for choosing VocoXp! We look forward to continuing to serve you.</p>
					</div>
					<div class="email-footer">
						<p>Best Regards,</p>
						<p>Micro Integrated Semi Conductor Systems Pvt. Ltd.</p>
					</div>
				</div>
			</body>
			</html>';
			
				
			
				$mail->Body = $html;
			
				$mail->send();
					$sql2 = "UPDATE `agency_header_all` SET `current_wallet_bal`='$new_current_bal',`coupan_add_on_amount`='$new_coupan_add_on_amount' WHERE `agency_id`='$agency_id'";
					$res_sql2 = mysqli_query($mysqli, $sql2);
				}

				if ($result1 === true) {
					$response[] = ["error_code" => 100, "message" => "Money sucessfully added to wallet", "wallet_current_bal" => $closing_bal];
					echo json_encode($response);
					return;
				} else {
					$response[] = ["error_code" => 101, "message" => "payement failed"];
					echo json_encode($response);
					return;
				}
			} else {
				$response[] = ["error_code" => 101, "message" => $mysqli->error];
				echo json_encode($response);
				return;
			}
		}
		
	}
	




	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	if ($mode == 'verification_payment') {

		// Assuming $specification_ids is an array
		if($payment_status=="payment_failed"){
			$response[] = ["error_code" => 100, "message" => "Payment failed"];
			echo json_encode($response);
			return;
		}

		if (!isset($member_id)) {
			$response[] = ["error_code" => 109, "message" => "member_id parameter is missing"];
			echo json_encode($response);
			return;
		}
		if (empty($member_id)) {
			$response[] = ["error_code" => 110, "message" => "member_id parameter is empty"];
			echo json_encode($response);
			return;
		}
		$sql = "SELECT * FROM `member_header_all` WHERE `member_id`='$member_id'";
		$res_sql = mysqli_query($mysqli, $sql);
		$agency_data = mysqli_fetch_assoc($res_sql);

		if (mysqli_num_rows($res_sql) == 0) {
			$response[] = ["error_code" => 111, "message" => "member_id is not valid"];  //agency id not valid
			echo json_encode($response);
			return;
		}

		// elseif($agency_data['status']!='active') // 	check agency_id is active
		// {
		// 	$response[] = ["error_code" => 107, "message" => "member_id is not active"]; 
		// 	echo json_encode($response);
		// 	return;  ///agency is not active
		// }


		if (!isset($net_amount)) {
			$response[] = ["error_code" => 109, "message" => "net_amount parameter is missing"];
			echo json_encode($response);
			return;
		}
		if (empty($net_amount)) {
			$response[] = ["error_code" => 110, "message" => "net_amount parameter is empty"];
			echo json_encode($response);
			return;
		}

		if (!isset($cgst_amount)) {
			$response[] = ["error_code" => 109, "message" => "cgst_amount parameter is missing"];
			echo json_encode($response);
			return;
		}
		if (empty($cgst_amount)) {
			$response[] = ["error_code" => 110, "message" => "cgst_amount parameter is empty"];
			echo json_encode($response);
			return;
		}
		if (!isset($sgst_amount)) {
			$response[] = ["error_code" => 109, "message" => "sgst_amount parameter is missing"];
			echo json_encode($response);
			return;
		}
		if (empty($sgst_amount)) {
			$response[] = ["error_code" => 110, "message" => "sgst_amount parameter is empty"];
			echo json_encode($response);
			return;
		}

		if (!isset($sub_amount)) {
			$response[] = ["error_code" => 109, "message" => "sub_amount parameter is missing"];
			echo json_encode($response);
			return;
		}
		if (empty($sub_amount)) {
			$response[] = ["error_code" => 110, "message" => "sub_amount parameter is empty"];
			echo json_encode($response);
			return;
		}


		if (!isset($specification_list)) {
			$response[] = ["error_code" => 109, "message" => "specification_list parameter is missing"];
			echo json_encode($response);
			return;
		}
		if (empty($specification_list)) {
			$response[] = ["error_code" => 110, "message" => "specification_list parameter is empty"];
			echo json_encode($response);
			return;
		}

		if (!isset($request_for)) {
			$response[] = ["error_code" => 109, "message" => "request_for parameter is missing"];
			echo json_encode($response);
			return;
		}
		if (empty($request_for)) {
			$response[] = ["error_code" => 110, "message" => "request_for parameter is empty"];
			echo json_encode($response);
			return;
		}


		if (!isset($check_payment_terms)) {
			$response[] = ["error_code" => 109, "message" => "check_payment_terms parameter is missing"];
			echo json_encode($response);
			return;
		}
		if (empty($check_payment_terms)) {
			$response[] = ["error_code" => 110, "message" => "check_payment_terms parameter is empty"];
			echo json_encode($response);
			return;
		}

		if (!isset($payment_via)) {
			$response[] = ["error_code" => 109, "message" => "payment_via parameter is missing"];
			echo json_encode($response);
			return;
		}
		if (empty($payment_via)) {
			$response[] = ["error_code" => 110, "message" => "payment_via parameter is empty"];
			echo json_encode($response);
			return;
		}


		if ($post['payment_via'] == 'online') {
			if (empty($post['payment_trans_id'])) {
				//echo response($error_code=102,$data=(object)[]);
				$response[] = ["error_code" => 110, "message" => "payment_via parameter is empty", "data" => $data];
				echo json_encode($response);
				return;
			}
		}
		if ($payment_via == 'wallet') {
			$res_chk_wallet = mysqli_query($mysqli, "SELECT `current_wallet_bal` FROM `agency_header_all` WHERE `agency_id`='$agency_id'");
			$row_chk_wallet = mysqli_fetch_assoc($res_chk_wallet);
			if ($row_chk_wallet['current_wallet_bal'] < $net_amount) {
				$responce[] = ["error_code" => 173, "message" => "Your wallet amount is not sufficient for this process...!"];
				echo json_encode($responce);
				return;
			}
		}
		$payment_data['agency_id'] = $payment_trans_data['agency_id'] = $post['agency_id'];
		$payment_data['person_id'] = $payment_trans_data['person_id'] = $post['person_id'];
		$payment_data['application_id'] = $payment_trans_data['application_id'] = $post['application_id'];
		$payment_data['request_for'] = $payment_trans_data['request_for'] = $post['request_for'];
		$payment_data['request_id'] =  unique_id_genrate('REQ', 'verification_payment_transaction_all', $mysqli1);;
		$payment_data['sub_amount'] = $post['sub_amount'];
		$payment_data['gst_amount'] = $post['gst_amount'];
		$payment_data['net_amount'] = $post['net_amount'];
		$payment_data['payment_status'] = $post['payment_status'];
		$payment_data['check_payment_terms'] = $post['check_payment_terms'];
		$payment_data['payment_via'] = $post['payment_via'];
		$payment_data['agency_id'] = $post['agency_id'];
		$payment_data['payment_trans_id'] = $post['payment_trans_id'];
		$payment_data['created_on'] = $payment_trans_data['created_on'] = $system_date_time;
		$payment_data['modified_on'] = $payment_trans_data['modified_on'] = $system_date_time;

		$sql = "SELECT * FROM agency_header_all WHERE agency_id='$agency_id'";
		$res_sql = mysqli_query($mysqli, $sql);
		$row = mysqli_fetch_assoc($res_sql);
		$closing_balance = $row['current_wallet_bal'];

		 $is_insert = "INSERT INTO `verification_payment_transaction_all` (`application_id`, `agency_id`, `person_id`,`request_for`,`request_id`,`sub_amount`,`sgst_amount`,`cgst_amount`, `net_amount`,`payment_trans_id`,`payment_status`,`status`,`payment_via`,`check_payment_terms`,`created_on`) 
 VALUES ('$application_id','$agency_id','$member_id' ,'" . $payment_data['request_for'] . "','" . $payment_data['request_id'] . "','$sub_amount','$sgst_amount','$cgst_amount', '$net_amount', '$transaction_id','$payment_status','1','$payment_via','$check_payment_terms','" . date('Y-m-d H:i:s') . "')";
		// $is_insert=add_new($mysqli,$payment_data,"verification_person_payment_request_details");
		$res_arrr = mysqli_query($mysqli1, $is_insert);

		if ($is_insert) {



			$specification_list = $post['specification_list'];
			$payment_trans_data['request_id'] = $payment_data['request_id'];
			$payment_trans_data['agency_id'] = $payment_data['agency_id'];
			$payment_trans_data['application_id'] = $payment_data['application_id'];
			$payment_trans_data['member_id'] = $member_id;
			$payment_trans_data['request_for'] = $payment_data['request_for'];

			if ($payment_via == 'wallet') {
				$sql = "SELECT * FROM `agency_header_all` WHERE `agency_id`='$agency_id'";
				$res_sql = mysqli_query($mysqli, $sql);
				$row = mysqli_fetch_assoc($res_sql);
				$current_wallet_bal = $row['current_wallet_bal'];
				$new_current_bal = $current_wallet_bal - $net_amount;
				$type = 'debit';
				if ($payment_status == 'payment_success') {
					$wallet_bal = number_format((float)$new_current_bal, 2, '.', '');
					$status = "success";
					$closing_balance = $wallet_bal;


					$sql2 = "UPDATE `agency_header_all` SET `current_wallet_bal`='$wallet_bal' WHERE `agency_id`='$agency_id'";
					$res_sql2 = mysqli_query($mysqli, $sql2);
				} else {
					$status = "failed";
					$closing_balance = $current_wallet_bal;
				}
				foreach ($specification_ids as $spec) {
					
					
						
						$fetch_price="SELECT `rate`, `sgst_percentage`, `cgst_percentage` FROM `verification_configuration_all` WHERE `ver_type`=2 AND `operational_status`=1 AND verification_id='$spec'";
						$res_price=mysqli_query($mysqli1, $fetch_price);
						$arr_price=mysqli_fetch_assoc($res_price);
						$sub_amt=$arr_price['rate'];
						$cgst_amount=$arr_price['rate']*$arr_price['cgst_percentage']/100;
						$sgst_amount=$arr_price['rate']*$arr_price['sgst_percentage']/100;

						$sql1 = "INSERT INTO `wallet_payment_transaction_all` (`id`, `agency_id`, `user_id`, `requested_from`, `purchase_type`, `verification_id`, `base_amount`, `cgst_amount`, `sgst_amount`, `transaction_on`, `transaction_id`, `line_type`, `quantity`, `settled_for`) VALUES (NULL, '$agency_id', '$member_id', '1', '1', '$spec', '$sub_amt', '$cgst_amount', '$sgst_amount', '$system_date_time', '', '1', '', '');";
				$result1 = mysqli_query($mysqli, $sql1);
						
					
				}
		// 		$sql1 = "INSERT INTO `wallet_transaction_all` (`agency_id`, `member_id`, `transaction_id`, `purpose`, `amount`,  `date`, `status`, `type`, `closing_balance`)
        //  VALUES ('$agency_id', '$member_id', '$transaction_id', 'verification', '$net_amount',  '" . date('Y-m-d H:i:s') . "', '$status', '$type', '$closing_balance')";
				// $sql1 = "INSERT INTO `wallet_payment_transaction_all` (`id`, `agency_id`, `user_id`, `requested_from`, `purchase_type`, `verification_id`, `base_amount`, `cgst_amount`, `sgst_amount`, `transaction_on`, `transaction_id`, `line_type`, `quantity`, `settled_for`) VALUES (NULL, '$agency_id', '$member_id', '1', '1', '$sp', '3', '3', '3', '2024-07-01 12:28:29.000000', 'sd', '1', '', '');";
				// $result1 = mysqli_query($mysqli, $sql1);
				// $last_id = mysqli_insert_id($mysqli);

				$update_sql = "UPDATE `verification_payment_transaction_all` SET `payment_trans_id`='$last_id' WHERE `request_id`='" . $payment_data['request_id'] . "'";
				$res_update_sql = mysqli_query($mysqli1, $update_sql);
			}
			// $payment_data= json_decode($specification_list, true);
			$specification_ids_str = implode("','", $specification_ids);

			$fetch_speci = "SELECT * FROM `verification_header_all` 
               WHERE `verification_id` IN ('$specification_ids_str')";

			$result_speci = mysqli_query($mysqli1, $fetch_speci);

			while ($arr = mysqli_fetch_assoc($result_speci)) {
				// print_r($arr);

				$fetch_config = "SELECT `rate`, `sgst_percentage`, `cgst_percentage` FROM `verification_configuration_all` WHERE `verification_id`='" . $arr['verification_id'] . "'";
				$result_config = mysqli_query($mysqli1, $fetch_config);
				$arr_config = mysqli_fetch_assoc($result_config);
				$per_cgst=$arr_config['cgst_percentage'];
				$per_sgst=$arr_config['sgst_percentage'];
				$cgst_amount=$arr_config['rate']*$per_cgst/100;
				$sgst_amount=$arr_config['rate']*$per_sgst/100;

				$insert_table = "INSERT INTO " . $arr['table_name'] . "(`agency_id`, `request_id`, `application_id`, `person_id`, `request_for`, `verification_id`, `type_id`, `price`, `verification_status`, `verification_report`, `created_on`, `sgst_percentage`, `sgst_amount`, `cgst_percentage`, `cgst_amount`) VALUES ('$agency_id', '" . $payment_data['request_id'] . "', '$application_id', '$member_id', '" . $payment_data['request_for'] . "', '" . $arr['verification_id'] . "', '', '" . $arr_config['rate'] . "', '0', '', '" . date('Y-m-d H:i:s') . "', '$per_sgst', '$sgst_amount', '$per_cgst', '$cgst_amount')";

				// Use a different variable for the inner query result
				$inner_result = mysqli_query($mysqli1, $insert_table);
			}


			$data['request_id'] = $payment_data['request_id'];
			if (is_null($closing_balance)) {
				$data['wallet_current_bal'] = 0;
			} else {
				$data['wallet_current_bal'] = $closing_balance;
			}

			// $data['current_wallet_bal'] = $closing_balance;
			$data['is_permitted'] = 'No';
			$data_arr[] = $data;
			$response[] = ["error_code" => 100, "message" => "Payment successfully", "data" => $data_arr];
			echo json_encode($response);
			// echo "check";
			// include_once "invoice.php";

			$url = get_base_url().'/invoice.php';

			// Data to send
			$postdata = array(
				'specification_id' => $specification_ids_str,
				'req_id' => $payment_data['request_id'],
				// 'member_id' => $member_id,
				// 'agency_id' => $agency_id,
			);
			// Initialize cURL session
			$ch = curl_init();
			// Set cURL options
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postdata)); // Use $postdata instead of $data
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			// Execute cURL request
			$response = curl_exec($ch);
			// Close cURL session
			curl_close($ch);
			return;
		} else {
			// echo response($error_code=199,$data=(object)[]);            
			// return;
			$response[] = ["error_code" => 199, "data" => $postdata];
			echo json_encode($response);
			return;
		}
	}

	//   }

	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	///////////////////////////////////////////////////////////


}
	

function common_chk_error($mysqli, $mode, $agency_id, $transaction_id, $payment_status, $application_id)
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
	} else if ($mode != 'add_money' && $mode != 'verification_payment' && $mode != 'purchase_storage_plan' && $mode != 'order_payment') {
		$response[] = ["error_code" => 104, "message" => "Paramter 'mode' value is invalid. please enter value either add_money or verification_payment or purchase_storage_plan.or order_payment "];
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
	$sql = "SELECT * FROM agency_header_all WHERE agency_id='$agency_id'";
	$res_sql = mysqli_query($mysqli, $sql);
	$agency_data = mysqli_fetch_assoc($res_sql);

	if (mysqli_num_rows($res_sql) == 0) {
		$response[] = ["error_code" => 107, "message" => "agency_id is not valid"];  //agency id not valid
		echo json_encode($response);
		return;
	} else if ($agency_data['status'] != '1') // 	check agency_id is active
	{
		$response[] = ["error_code" => 108, "message" => "agency_id is not active"];
		echo json_encode($response);
		return;
	}

	// elseif($agency_data['status']!='active') // 	check agency_id is active
	// {
	// 	$response[] = ["error_code" => 107, "message" => "member_id is not active"]; 
	// 	echo json_encode($response);
	// 	return;  ///agency is not active
	// }




	if (!isset($payment_status)) {
		$response[] = ["error_code" => 104, "message" => "payment_status parameter is missing"];
		echo json_encode($response);
		return;
	}
	if (empty($payment_status)) {
		$response[] = ["error_code" => 105, "message" => "payment_status parameter is empty"];
		echo json_encode($response);
		return;
	}


	if (!isset($application_id)) {
		$response[] = ["error_code" => 112, "message" => "application_id parameter is missing"];
		echo json_encode($response);
		return;
	}
	if (empty($application_id)) {
		$response[] = ["error_code" => 113, "message" => "application_id parameter is empty"];
		echo json_encode($response);
		return;
	}

	return $common_chk_error;
}



function add_new($mysqli, $data, $table_name)
{
	// Escape values to prevent SQL injection
	$escaped_data = array_map([$mysqli, 'real_escape_string'], $data);

	// Create a comma-separated list of keys and values
	$keys = implode(', ', array_keys($escaped_data));
	$values = "'" . implode("', '", $escaped_data) . "'";

	// Construct the SQL query
	$sql = "INSERT INTO $table_name ($keys) VALUES ($values)";

	// Execute the query
	if ($mysqli->query($sql)) {
		return true; // Insert successful
	} else {
		return false; // Insert failed
	}
}


function generate_request_id($mysqli1)
{
	for (;;) {
		$digits = 8;
		$ref_code = 'R' . rand(pow(10, $digits - 1), pow(10, $digits) - 1);
		$sql_check = "select person_id from verification_payment_transaction_all where request_id ='$ref_code'";
		$result_check = mysqli_query($mysqli1, $sql_check);
		if ($result_check->num_rows == 0) {
			return $ref_code;
		}
	}
}



function send_sms($sms_array, $auth_key)
{
	$mobile_no = "91" . $sms_array['mobile_no'];
	date_default_timezone_set('Asia/Kolkata');
	$date = Date('d-m-Y');
	$error_flag = 0;
	$otp_prefix = ':-';
	$new_line = "\n";
	$dot = ".";
	$dash = "-";
	$colon = ":";
	$localIP = $_SERVER['SERVER_NAME'];
	$weblink = $localIP . '/salesmanager/index.php';
	$app = $localIP . '/production/apk/SalesManager.apk';
	$exmilitary = "!";


	// $message = urlencode("Welcome to Premisafe! Congratulations, You have been registration successfully as a Security Agency. Your login details - Username : $user_name Password : $user_password Don't share your login details with anyone. Regards, Developed by MISCOS Technologies Pvt. Ltd.");

	$message = urlencode("Hi " . ucwords($sms_array['agency_name']) . ",$new_line" . ", Your order placed successfully having order number " . $sms_array['order_no'] . " and total of INR " . $sms_array['order_amount'] . ". We will keep you posted for further information. Developed by MISCOS Technologies Pvt. Ltd.");


	$response_type = 'json';
	$route = "4";
	$contact_no = "+91" . "" . $sms_array['mobile_no'];
	$postData = array(

		'authkey' => $auth_key,
		'mobiles' => $contact_no,
		'message' => $message,
		'sender' => 'MisCos',
		'route' => $route,
		'response' => $response_type

	);
	//API URL
	$url = "http://api.msg91.com/api/sendhttp.php?authkey=$auth_key&sender=MisCos&mobiles=$contact_no&route=$route&message=$message&DLT_TE_ID=130716799964290977";
	//$url = "https://control.msg91.com/api/sendhttp.php?authkey=138906AXhOHtw6e588c8fda&mobiles=8319547270&message=Test&sender=MSGIND&route=4&country=91&schtime=2017-01-30%2000:00:00&response=Hey";
	// init the resource
	$ch = curl_init();
	curl_setopt_array(
		$ch,
		array(
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POST => true,
			CURLOPT_POSTFIELDS => $postData
			//,CURLOPT_FOLLOWLOCATION => true
		)
	);


	//Ignore SSL certificate verification
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);


	//get response
	$output = curl_exec($ch);
	// print_r($output);
	// print_r($message);die();
	//Print error if any
	if (curl_errno($ch)) {
		$error_flag = 1;
	} else {
		$error_flag = 0;
	}

	curl_close($ch);
	return $error_flag;
}
