
<?php

/*Prepared by: Sahil Chavan
Name of API:  : json_for_action_generate_pan_report.
Method: POST
Category: Action 
Description:
This API is to use generate pan details.
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
$connection = connection::getInstance();
$mysqli = $connection->getConnection();
$connection1 = database::getInstance();
$mysqli1 = $connection1->getConnection();
date_default_timezone_set('Asia/kolkata');
$system_date = date("y-m-d");
$system_date_time = date("Y-m-d H:i:s");
$output = $responce = array();
apponoff($mysqli);
logout($_SERVER['HTTP_AUTH_KEY'], $mysqli);



if ($_SERVER["REQUEST_METHOD"] != "POST") {
	$response[] = ["error_code" => 101, "message" => "Please use POST method"];
	echo json_encode($response);
	return;
}


$check_error_res = check_error($mysqli, $_POST['agency_id'], $_POST['member_id'], $_POST['pancard_no'], $_POST['pan_card_type'], $_POST['name_as_per_document'], $_POST['image'], $_POST['specification_id'], $_POST['person_id'], $_POST['application_id'], $_POST['request_id'], $_POST['person_id']);
if ($check_error_res == 1) {


	$member_id = $_POST['member_id'];
	$application_id = $_POST['application_id'];
	$request_id = $_POST['request_id'];
	$pancard_no = $_POST['pancard_no'];
	$specification_id = $_POST['specification_id'];
	$pan_card_type = $_POST['pan_card_type'];
	$agency_id = $_POST['agency_id'];
	$type_id = $_POST['type_id'];
	$name_as_per_document = $_POST['name_as_per_document'];
	$pan_front_picture = $_FILES['pan_front_picture']['name'];

	$reason = '';
	$image = '';

	// if(isValidPAN($pancard_no)==0){

	// 	exit;
	// }




	$sql_submit = "SELECT `name`, `table_name`  FROM `verification_header_all` where `verification_id` = '$specification_id'";
	$result_submit = mysqli_query($mysqli1, $sql_submit);
	if (mysqli_num_rows($result_submit) > 0) {
		$specifiction_data = $result_submit->fetch_assoc();
		$table_name = $specifiction_data['table_name'];

		$verification_data = json_decode(verify_pan($pancard_no, $reason), true);
		if ($verification_data['data']['status'] == "INVALID") {
			$responce = ["error_code" => 199, "message" => "Pan number is invalid. Please provide the valid pan number"];
			echo json_encode($responce);
			return;
		}
		if ($verification_data['code'] == 200) {
			//member detail

			$sql = "SELECT `name`, `contact_no` FROM `member_header_all` WHERE `member_id`='$member_id' and `registration_id`='$agency_id'";
			$res_sql = mysqli_query($mysqli, $sql);
			$member_data = mysqli_fetch_assoc($res_sql);

			$person_name = '';

			$name = (isset($member_data['name'])) ? $member_data['name'] : '';
			if ($name_as_per_document != '') {
				$person_name = $name_as_per_document;
			} else {
				$person_name = (isset($member_data['name'])) ? $member_data['name'] : '';
			}
			$mismatch_data = '';
			$percentage = 0;
			$cnt = 0;
			$j = 0;
			if ($verification_data['data']['full_name'] != '') {
				$adhar_name_arr = explode(" ", strtoupper($verification_data['data']['full_name']));
				//print_r($adhar_name_arr);
				$person_name_arr = explode(" ", strtoupper(trim($person_name, " ")));
				//print_r($person_name_arr);
				if (sizeof($adhar_name_arr) > sizeof($person_name_arr)) {
					$cnt = sizeof($adhar_name_arr);
					foreach ($adhar_name_arr as $adhar) {
						if (in_array($adhar, $person_name_arr)) {
							$j++;
						}
					}
				} else {
					$cnt = sizeof($person_name_arr);
					foreach ($adhar_name_arr as $adhar) {
						if (in_array($adhar, $person_name_arr)) {
							$j++;
						}
					}
				}
			}

			if ($j == $cnt) {
				$percentage = 50;
				$is_name_match = 'Match';
			} else {
				$mismatch_data = 'Name';
				$is_name_match = 'Not Match';
			}





			if ($verification_data['data']['category'] == 'Individual') {
				if (ucwords($pan_card_type) == ($verification_data['data']['category'])) {
					$mismatch_data = $mismatch_data;
					$percentage = $percentage + 50;
					$is_card_type_match = 'Match';
				} else {
					$mismatch_data = ($mismatch_data == '') ? 'Category' : $mismatch_data . ',Category';
					$percentage = $percentage + 0;
					$is_card_type_match = 'Not Match';
				}
			} else {
				if (ucwords($pan_card_type) == 'Individual') {
					if (ucwords($pan_card_type) == ($verification_data['data']['category'])) {
						$mismatch_data = $mismatch_data;
						$percentage = $percentage + 50;
						$is_card_type_match = 'Match';
					} else {
						$mismatch_data = ($mismatch_data == '') ? 'Category' : $mismatch_data . ',Category';
						$percentage = $percentage + 0;
						$is_card_type_match = 'Not Match';
					}
				} else {
					$mismatch_data = $mismatch_data;
					$percentage = $percentage + 50;
					$is_card_type_match = 'Match';
				}
			}
			$data['id'] = $member_id;
			$data['name'] = $name;
			$data['is_name_match'] = $is_name_match;
			$data['service_name'] = $specifiction_data['name'];
			$data['member_name'] = $person_name;
			$data['pan_no'] = $pancard_no;
			$data['pancard_type'] = $pan_card_type;
			$data['is_card_type_match'] = $is_card_type_match;
			//$data['uploaded_image']=$image;
			$data['pan_name'] = $verification_data['data']['full_name'];
			$data['pan_card_type'] = $verification_data['data']['category'];




			// save pan img
			// if(isset($_FILES['pan_front_picture']) && !empty($pan_front_picture)) {
			$uploaded_documents = upload_document($_FILES['pan_front_picture']);
			// include_once "pan_verification_pdf.php";
			$url = get_base_url().'/pan_verification_pdf.php';

// Data to send
$postdata = array(
    'name' => $name,
    'contact' => $member_data['contact_no'],
    'name_as_per_document' => $name_as_per_document,
    'pancard_no' => $pancard_no,
    'pan_card_type' => $pan_card_type,
    'is_name_match' => $is_name_match,
    'is_card_type_match' => $is_card_type_match,
    'uploaded_documents' => $uploaded_documents,
    'verification_name' => $verification_data['data']['full_name'],
    'verification_category' => ucwords($verification_data['data']['category']) != 'Individual' ? "Bussiness" : "Individual",
    'agency_id' => $agency_id,
    'member_id' => $member_id,
    'specification_id' => $specification_id,
    'request_id' => $request_id,
    'application_id' => $application_id
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

// Check for errors
if($response === false) {
    echo 'Curl error: ' . curl_error($ch);
} else {
    // Decode JSON response
    $response_data = json_decode($response, true);

    // Check if JSON decoding was successful
    if ($response_data !== null) {
        // Handle the response data
        $path = $response_data['path'];
    } 
}

// Close cURL session
curl_close($ch);

if(!empty($path)) {
    // Initialize $path_data as an empty array
    $path_data = array();

    // Append data to $path_data
    $path_data[] = array("pdf_url" => $path);

    // Create the response array
    $response = array(
        array(
            "error_code" => 100,
            "data" => $path_data
        )
    );

    // Echo the JSON response
    echo json_encode($response);

    $integrated_url = get_base_url().'/integrated_pdf.php';

    // Data to send
    $integreat_data = array(
        'agency_id' => $agency_id,
        'member_id' => $member_id,
        'path' => $path,
    );

    // Initialize cURL session
    $ch = curl_init();

    // Set cURL options
    curl_setopt($ch, CURLOPT_URL, $integrated_url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($integreat_data)); // Use $integreat_data instead of $postdata
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Execute cURL request
    $integreat_response = curl_exec($ch);

    // Check for errors
    if($integreat_response === false) {
        echo 'Curl error: ' . curl_error($ch);
    } else {
        // Decode JSON response
        $response_data = json_decode($integreat_response, true);

        // Check if JSON decoding was successful
        if ($response_data !== null) {
            // Handle the response data
            $path_ = $response_data['path'];
        } 
    }

    // Close cURL session
    curl_close($ch);

    return;
} else {
    // Initialize $response as an array if not already
    $response = array();

    // Append the error response
    $response[] = ["error_code" => 199, "data" => $data];

    // Echo the JSON response
    echo json_encode($response);
    return;
}
		} else {
			$responce[] = ["error_code" => 199, "message" => $verification_data['message']];
			echo json_encode($responce);
			return;
		}
	} else {
		// echo response($error_code=109,$data=(object)[]);
		// return;
		$response[] = ["error_code" => 190, "message" => $data];
		echo json_encode($response);
		return;
	}
}


function check_error($mysqli, $agency_id, $member_id, $pancard_no, $pan_card_type, $name_as_per_document, $pan_image, $specification_id, $person_id, $application_id, $request_id)
{
	$check_error = 1;



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
	$sql = "SELECT `status` FROM `agency_header_all` WHERE `agency_id`='$agency_id'";
	$res_sql = mysqli_query($mysqli, $sql);
	$agency_data = mysqli_fetch_assoc($res_sql);

	if (mysqli_num_rows($res_sql) == 0) {
		$response[] = ["error_code" => 103, "message" => "agency_id is not valid"];  //agency id not valid
		echo json_encode($response);
		return;
	} else if ($agency_data['status'] != '1') // 	check agency_id is active
	{
		$response[] = ["error_code" => 104, "message" => "agency_id is not active"];
		echo json_encode($response);
		return;
	}
	if (!isset($member_id)) {
		$response[] = ["error_code" => 105, "message" => "member_id parameter is missing"];
		echo json_encode($response);
		return;
	}
	if (empty($member_id)) {
		$response[] = ["error_code" => 106, "message" => "member_id parameter is empty"];
		echo json_encode($response);
		return;
	}
	// $sql="SELECT * FROM `member_header_all` WHERE member_id='$member_id'";
	// $res_sql=mysqli_query($mysqli,$sql);
	// $agency_data=mysqli_fetch_assoc($res_sql);

	// if(mysqli_num_rows($res_sql)==0)
	// {
	// 	$response []= ["error_code" => 107, "message" => "member_id is not valid"];  //agency id not valid
	// 	echo json_encode($response);
	// 	return;   
	// }

	// elseif($agency_data['status']!='active') // 	check agency_id is active
	// {
	// 	$response[] = ["error_code" => 107, "message" => "member_id is not active"]; 
	// 	echo json_encode($response);
	// 	return;  ///agency is not active
	// }

	if (!isset($pancard_no)) {
		$response[] = ["error_code" => 108, "message" => "pancard_no parameter is missing"];
		echo json_encode($response);
		return;
	}
	if (empty($pancard_no)) {
		$response[] = ["error_code" => 109, "message" => "pancard_no parameter is empty"];
		echo json_encode($response);
		return;
	}
	if (!isset($pan_card_type)) {
		$response[] = ["error_code" => 110, "message" => "pan_card_type parameter is missing"];
		echo json_encode($response);
		return;
	}
	if (empty($pan_card_type)) {
		$response[] = ["error_code" => 111, "message" => "pan_card_type parameter is empty"];
		echo json_encode($response);
		return;
	}
	if (!isset($name_as_per_document)) {
		$response[] = ["error_code" => 112, "message" => "name_as_per_document parameter is missing"];
		echo json_encode($response);
		return;
	}
	if (empty($name_as_per_document)) {
		$response[] = ["error_code" => 113, "message" => "name_as_per_document parameter is empty"];
		echo json_encode($response);
		return;
	}


	// if(!isset($_FILES["pan_image"]))
	// {
	// 	$response[]=["error_code"=>114,"message"=>"pan_image parameter is missing"];
	// 	echo json_encode($response);
	// 	return;
	// }
	//  if(empty($_FILES["pan_image"]))
	// {
	// 	$response[]=["error_code"=>115,"message"=>"pan_image parameter is empty"];
	// 	echo json_encode($response);
	// 	return;
	// }

	if (!isset($specification_id)) {
		$response[] = ["error_code" => 116, "message" => "specification_id parameter is missing"];
		echo json_encode($response);
		return;
	}
	if (empty($specification_id)) {
		$response[] = ["error_code" => 117, "message" => "specification_id parameter is empty"];
		echo json_encode($response);
		return;
	}
	// if(!isset($person_id))
	// {
	// 	$response[]=["error_code"=>118,"message"=>"person_id parameter is missing"];
	// 	echo json_encode($response);
	// 	return;
	// }
	//  if(empty($person_id))
	// {
	// 	$response[]=["error_code"=>119,"message"=>"person_id parameter is empty"];
	// 	echo json_encode($response);
	// 	return;
	// }

	if (!isset($application_id)) {
		$response[] = ["error_code" => 120, "message" => "application_id parameter is missing"];
		echo json_encode($response);
		return;
	}
	if (empty($application_id)) {
		$response[] = ["error_code" => 121, "message" => "application_id parameter is empty"];
		echo json_encode($response);
		return;
	}
	if (!isset($request_id)) {
		$response[] = ["error_code" => 122, "message" => "request_id parameter is missing"];
		echo json_encode($response);
		return;
	}
	if (empty($request_id)) {
		$response[] = ["error_code" => 123, "message" => "request_id parameter is empty"];
		echo json_encode($response);
		return;
	}

	// if (!isset($_FILES['pan_front_picture'])) {
	// 	$response[] = ["error_code" => 124, "message" => "pan_front_picture parameter is missing"];
	// 	echo json_encode($response);
	// 	return;
	// }
	// if (empty($_FILES['pan_front_picture'])) {
	// 	$response[] = ["error_code" => 125, "message" => "pan_front_picture parameter is empty"];
	// 	echo json_encode($response);
	// 	return;
	// }


	return $check_error;
}


function verify_pan($pancard_no, $reason)
{
	$curl = curl_init();
	$token = json_decode(get_token());
	//echo $token;exit;
	curl_setopt_array($curl, [
		CURLOPT_URL => "https://api.sandbox.co.in/pans/" . $pancard_no . "/verify?consent=y&reason=For%20KYC%20of%20User",
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 180,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "GET",
		CURLOPT_HTTPHEADER => [
			"Authorization: " . $token->access_token,
			"accept: application/json",
			"x-api-key: key_live_R7mjeIgaSUvT54Kdny3AhSuaQVeBkj37",
			"x-api-version: 1.0"
		],
	]);

	$response = curl_exec($curl);
	$err = curl_error($curl);
	curl_close($curl);
	if ($err) {
		return $err;
	} else {
		return $response;
	}
}

function upload_image($person_id, $doc_name, $old_image = '')
{
	$target_dir_profile = "person_documents/" . $person_id;
	if (!empty($old_image) && file_exists($target_dir_profile . '/' . $old_image)) {
		unlink($target_dir_profile . '/' . $old_image);
	}
	if (isset($_FILES[$doc_name]['name']) && !empty($_FILES[$doc_name]['name'])) {
		// $rid=rand(1000,9999);
		$new_name_profile = "img_" . $_FILES[$doc_name]['name'];
		if (!file_exists('person_documents')) {
			mkdir('person_documents', 0777, true);
		}
		if (!file_exists($target_dir_profile)) {
			mkdir($target_dir_profile, 0777, true);
		}
		$target_file_profile = $target_dir_profile . "/" . $new_name_profile;
		if (move_uploaded_file($_FILES[$doc_name]["tmp_name"], $target_file_profile)) {
			$new_name_profile;
		} else {
			$new_name_profile = '';
		}
	} else {
		$new_name_profile = '';
	}
	return $new_name_profile;
	// exit;
}

function get_token()
{
	$curl = curl_init();
	//echo "in fun";
	curl_setopt_array($curl, [
		CURLOPT_URL => "https://api.sandbox.co.in/authenticate",
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 30,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "POST",
		CURLOPT_HTTPHEADER => [
			"accept: application/json",
			"x-api-key: key_live_R7mjeIgaSUvT54Kdny3AhSuaQVeBkj37",
			"x-api-secret: secret_live_qZ1nAgKIPosx1LnZamiafPS6z2aq0mJk",
			"x-api-version: 1.0"
		],
	]);

	$response = curl_exec($curl);
	//print_r ($response);
	//	die;
	$err = curl_error($curl);

	curl_close($curl);

	if ($err) {
		return $err;
	} else {
		return $response;
	}
}

function isValidPAN($pan)
{
	// Regular expression to match PAN format
	$pattern = '/^[A-Z]{5}[0-9]{4}[A-Z]$/';

	// Check if the PAN matches the pattern
	if (preg_match($pattern, $pan)) {
		return 1; // PAN is valid
	} else {
		return 0; // PAN is invalid
	}
}


?>
