<?php

/*Prepared by: Ashish Khandare
Name of API:  json_for_action_generate_ecrime_report
Method: POST
Category: Action
Description:
This API is to use generate e-crime details
Developed by: Akshay Patil
Note: single mode
mode: register_employee*/
// error_reporting(1);

// error_reporting(E_ALL & ~E_DEPRECATED);
// ini_set('display_errors', 1);

// this json is used to add family member of resident.
header('Content-Type: application/json; charset=UTF-8');
header('Access-Contorl-Allow-Origin: *');
date_default_timezone_set('Asia/kolkata');
include 'connection.php';
// Now you can use the connection class

//   $connection1 = database::getInstance();
// $mysqli1 = $connection1->getConnection();
$connection = connection::getInstance();
$mysqli = $connection->getConnection();
$connection1 = database::getInstance();
$mysqli1 = $connection1->getConnection();
date_default_timezone_set('Asia/kolkata');
$system_date = date("y-m-d");
$system_date_time = date("Y-m-d H:i:s");
$output = $responce = array();
apponoff($mysqli);

// echo $_SERVER['HTTP_AUTHORIZATION'];
logout($_SERVER['HTTP_AUTH_KEY'], $mysqli);
// check_digital_verification($mysqli);


if ($_SERVER["REQUEST_METHOD"] != "POST") {
	$response[] = ["error_code" => 101, "message" => "Please use POST method"];
	echo json_encode($response);
	return;
}


$check_error_res = check_error($mysqli, $_POST['agency_id'], $_POST['application_id'], $_POST['type']);
if ($check_error_res == 1) {

	$agency_id = $_POST['agency_id'];
	$application_id = $_POST['application_id'];
	$type = $_POST['type'];
	$is_link_present = $_POST['is_link_present'];
	if ($type == "member") {
		$where = "AND `type`='1'";
	} elseif ($type == "employee") {
		$where = "AND `type`='0'";
	} elseif ($type == "all") {
		$where = "";
	}


	// $sql = "SELECT * FROM `member_header_all` WHERE `status`='1' AND `registration_id`='$agency_id' AND `type`='$type' AND `web_link`<> '' ORDER BY `created_on` DESC";

	$sql = "SELECT * FROM `member_header_all` WHERE `status`='1' AND `registration_id`='$agency_id' $where ORDER BY `created_on` DESC";






	$result_submit = mysqli_query($mysqli, $sql);
	if ($result_submit) {
		while ($row = mysqli_fetch_assoc($result_submit)) {
			$person_id = $row['member_id'];
			$fetch_verification_aadhar = "SELECT `agency_id` FROM `aadhar_transaction_all` WHERE `agency_id`='$agency_id' AND `person_id`='$person_id' AND `verification_status`=2";
			$res_aadhar = mysqli_query($mysqli1, $fetch_verification_aadhar);
			$aadhar = (mysqli_num_rows($res_aadhar) > 0) ? 1 : 0;

			$fetch_verification_pan = "SELECT `agency_id` FROM `pan_transaction_all` WHERE `agency_id`='$agency_id' AND `person_id`='$person_id' AND `verification_status`=2";
			$res_pan = mysqli_query($mysqli1, $fetch_verification_pan);
			$pan = (mysqli_num_rows($res_pan) > 0) ? 1 : 0;

			$fetch_verification_voter = "SELECT `agency_id` FROM `voter_transaction_all` WHERE `agency_id`='$agency_id' AND `person_id`='$person_id' AND `verification_status`=2";
			$res_voter = mysqli_query($mysqli1, $fetch_verification_voter);
			$voter = (mysqli_num_rows($res_voter) > 0) ? 1 : 0;

			$fetch_verification_ecrime = "SELECT `agency_id` FROM `ecrime_transaction_all` WHERE `agency_id`='$agency_id' AND `person_id`='$person_id' AND `verification_status`=2";
			$res_ecrime = mysqli_query($mysqli1, $fetch_verification_ecrime);
			$ecrime = (mysqli_num_rows($res_ecrime) > 0) ? 1 : 0;

			// Check if all verification variables are 1
			if ($aadhar == 1 && $pan == 1 && $voter == 1 && $ecrime == 1) {
				// Perform the action (e.g., adding them)
				$sum = $aadhar + $pan + $voter + $ecrime;
				// echo "All verifications are complete. Sum: " . $sum;
			} else {
				$sum = 0;
			}
			if ($aadhar == 1 || $pan == 1 || $voter == 1 || $ecrime == 1) {
				// Perform the action (e.g., adding them)
				$sum_partial = $aadhar + $pan + $voter + $ecrime;
				// echo "All verifications are complete. Sum: " . $sum;
			} else {
				$sum_partial = 0;
			}




			$req_data = array();
			$person_data = $row;
			if ($person_data['web_link_status'] == 0) {
				$person_data['web_link_status'] = "Initiated";
			} elseif ($person_data['web_link_status'] == 1) {
				$person_data['web_link_status'] = "Opened";
			} elseif ($person_data['web_link_status'] == 2) {
				$person_data['web_link_status'] = "Completed";
			} elseif ($person_data['web_link_status'] == 3) {
				$person_data['web_link_status'] = "Cancelled";
			} elseif ($person_data['web_link_status'] == 4) {
				$person_data['web_link_status'] = "Partially Completed";
			}


			if ($person_data['dob_or_doj'] == '0000-00-00') {
				$person_data['dob_or_doj'] = "";
			} else {
				$person_data['dob_or_doj'] = date('d-m-Y', strtotime($person_data['dob_or_doj']));
			}

			if ($row['type'] == 1) {
				if ($person_data['dob_or_doj'] == "" || $person_data['city'] == "" || $person_data['address'] == "" || $person_data['gender'] == "" || $person_data['relation'] == "" || $person_data['profile_image'] == "") {
					$person_data['profile_status'] = "Incomplete";
				} else {
					$person_data['profile_status'] = "Completed";
				}
			} elseif ($row['type'] == 0) {
				if ($person_data['dob_or_doj'] == "" || $person_data['city'] == "" || $person_data['address'] == "" || $person_data['designation'] == "" || $person_data['profile_image'] == "") {
					$person_data['profile_status'] = "Incomplete";
				} else {
					$person_data['profile_status'] = "Completed";
				}
			}

			if ($person_data['duty_type'] == 1) {
				$person_data['duty_type'] = "same";
			} else {
				$person_data['duty_type'] = "different";
			}
			///get watch detail
			$watch_allocated = 'No';
			$advance_verification = 'No';
			$watch_detail = array();



			$watch_allocated = 'no';
			$data['order_id'] = "O1121";
			$data['item_no'] = "I545";
			$data['allocated_on'] = date('d-m-Y');


			$product_id = "P4545";


			$product_name = "Noice";
			$images = "DFF";
			$data['product_name'] = "$product_name";
			$data['images'] = $images;

			$watch_detail[] = $data;

			$person_data['watch_allocated'] = $watch_allocated;



			$k = 0;

			$person_data['advance_verification'] = $advance_verification;
			$person_data['responsibility_data'] = $req_data;
			$responsibility_data_str = '';

			foreach ($person_data['responsibility_data'] as $responsibility) {
				$responsibility_keys = array_keys($responsibility);
				$filtered_keys = array_filter($responsibility_keys, function ($key) use ($responsibility) {
					return $responsibility[$key] == "y";
				});

				$responsibility_str = implode(', ', $filtered_keys);
				$responsibility_data_str .= " $responsibility_str  | "; // Adjust the separator as needed
			}

			$person_data['responsibility_data'] = rtrim($responsibility_data_str, '| '); // Remove the trailing separator
			$person_data['watch_detail'] = $watch_detail;
			$person_data['profile_completion'] = 20;
			$person_data['aadhar'] = $aadhar;
			$person_data['pan'] = $pan;
			$person_data['voter'] = $voter;
			$person_data['e-crime'] = $ecrime;
			if ($is_link_present == 1) {
				if ($person_data['web_link'] != "") {
					$data2[] = $person_data;
				}
			} else {
				$data2[] = $person_data;
			}
		}
		if (empty($data2)) {
			$output[] = ["message" => "Empty data.", "error_code" => 102];
			echo json_encode($output);
			return;
		} else {
			foreach ($data2 as $person_data) {
    $aadhar = $person_data['aadhar'];
    $pan = $person_data['pan'];
    $voter = $person_data['voter'];
    $ecrime = $person_data['e-crime'];

    if ($aadhar == 1 && $pan == 1 && $voter == 1 && $ecrime == 1) {
        $full_verified++;
    } elseif ($aadhar == 1 || $pan == 1 || $voter == 1 || $ecrime == 1) {
        $partial_verified++;
    }
}
			$total_add = count($data2);
			$pending = $total_add - ($sum + $partial_verified);
			$output[] = [
				"error_code" => 100,
				"message" => "Data found",
				"total_add" => $total_add,
				"full_verified" => $sum,
				"partial_verified" => $partial_verified,
				"pending_verified" => $pending,
				"member_details" => $data2,
			];
			echo json_encode($output);
			return;
		}
	} else {
		$output[] = ["message" => "Data not found.", "error_code" => 101];
		echo json_encode($output);
		return;
	}
} else {
	echo $common_chk_error_res;
}
function check_error($mysqli, $agency_id, $application_id, $type)
{
	$check_error = 1;


	if (!$mysqli) {
		$response[] = ["error_code" => 101, "message" => "There was an issue connecting to the database. Please try again later."];
		echo json_encode($response);
		return;
	}
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
	$sql = "SELECT `status` FROM `agency_header_all` WHERE agency_id='$agency_id'";
	$res_sql = mysqli_query($mysqli, $sql);
	$agency_data = mysqli_fetch_assoc($res_sql);

	if (mysqli_num_rows($res_sql) == 0) {
		$response[] = ["error_code" => 106, "message" => "agency_id is not valid"];  //agency id not valid
		echo json_encode($response);
		return;
	} else if ($agency_data['status'] != '1') // 	check agency_id is active
	{
		$response[] = ["error_code" => 107, "message" => "agency_id is not active"];
		echo json_encode($response);
		return;
	}
	// if(!isset($member_id))
	// {
	// 	$response[]=["error_code"=>103,"message"=>"member_id parameter is missing"];
	// 	echo json_encode($response);
	// 	return;
	// }
	//  if(empty($member_id))
	// {
	// 	$response[]=["error_code"=>103,"message"=>"member_id parameter is empty"];
	// 	echo json_encode($response);
	// 	return;
	// }

	if (!isset($type)) {
		$response[] = ["error_code" => 102, "message" => "type parameter is missing"];
		echo json_encode($response);
		return;
	}
	if (empty($type)) {
		$response[] = ["error_code" => 102, "message" => "type parameter is empty"];
		echo json_encode($response);
		return;
	}
	if (!isset($application_id)) {
		$response[] = ["error_code" => 102, "message" => "application_id parameter is missing"];
		echo json_encode($response);
		return;
	}
	if (empty($application_id)) {
		$response[] = ["error_code" => 102, "message" => "application_id parameter is empty"];
		echo json_encode($response);
		return;
	}



	return $check_error;
}
