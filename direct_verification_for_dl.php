<?php
/* 
Name : json_for_generate_driving_licence_report.php
Version of the Requirment Document  : 2.0.1


Purpose :- : This API is to use generate driving license details

Mode :- single mode

Developed By - Rishabh Shinde 
*/
// error_reporting(1);
// error_reporting(E_ALL & ~E_DEPRECATED);
// ini_set('display_errors', 1);
include 'connection.php';

// Now you can use the connection class
$connection = connection::getInstance();
$mysqli = $connection->getConnection();

$connection1 = database::getInstance();
$mysqli1 = $connection1->getConnection();
apponoff($mysqli);  
logout($_SERVER['HTTP_AUTH_KEY'], $mysqli);



$driving_licence_no = $_POST['driving_licence_no'];
$edited_driving_licence_no = $_POST['edited_driving_licence_no'];

$user_name = $_POST['name'];
$edited_name = $_POST['edited_name'];

if($_POST['date_of_birth']!="") {
    if (str_contains($_POST['date_of_birth'], "-")) {
    $date_of_birth = DateTime::createFromFormat('Y-m-d', $_POST['date_of_birth'])->format('d-m-Y');
  } else {
    $date_of_birth = DateTime::createFromFormat('d/m/Y', $_POST['date_of_birth'])->format('d-m-Y');
  }    
}

if($_POST['edited_date_of_birth']!=""){
    if (str_contains($_POST['edited_date_of_birth'], "-")) {
$edited_date_of_birth = DateTime::createFromFormat('Y-m-d', $_POST['edited_date_of_birth'])->format('d-m-Y');
    } else {
$edited_date_of_birth = DateTime::createFromFormat('d/m/Y', $_POST['edited_date_of_birth'])->format('d-m-Y');
    }    
}

$dob = empty($date_of_birth) ? $edited_date_of_birth : $date_of_birth;

$father_name = $_POST['father_name'];
$edited_father_name = $_POST['edited_father_name'];
$address = $_POST['address'];
$edited_address = $_POST['edited_address'];

if($_POST['valid_till']!="") {
    if (str_contains($_POST['valid_till'], "-")) {
$valid_till = DateTime::createFromFormat('Y-m-d', $_POST['valid_till'])->format('d-m-Y');
    } else {
$valid_till = DateTime::createFromFormat('d/m/Y', $_POST['valid_till'])->format('d-m-Y');
    }    
}

if($_POST['edited_valid_till']!="") {
    if (str_contains($_POST['edited_valid_till'], "-")) {
$edited_valid_till = DateTime::createFromFormat('Y-m-d', $_POST['edited_valid_till'])->format('d-m-Y');
    } else {
$edited_valid_till = DateTime::createFromFormat('d/m/Y', $_POST['edited_valid_till'])->format('d-m-Y');
    }    
}

if($_POST['date_of_issue']!="") {
    if (str_contains($_POST['date_of_issue'], "-")) {
$date_of_issue = DateTime::createFromFormat('Y-m-d', $_POST['date_of_issue'])->format('d-m-Y');
    } else {
$date_of_issue = DateTime::createFromFormat('d/m/Y', $_POST['date_of_issue'])->format('d-m-Y');
    }    
}
if($_POST['edited_date_of_issue']!="") {
    if (str_contains($_POST['edited_date_of_issue'], "-")) {
$edited_date_of_issue = DateTime::createFromFormat('Y-m-d', $_POST['edited_date_of_issue'])->format('d-m-Y');
    } else {
$edited_date_of_issue = DateTime::createFromFormat('d/m/Y', $_POST['edited_date_of_issue'])->format('d-m-Y');
    }    
}
if($_POST['date_of_expiry']!="") {
    if (str_contains($_POST['date_of_expiry'], "-")) {
$date_of_expiry = DateTime::createFromFormat('Y-m-d', $_POST['date_of_expiry'])->format('d-m-Y');
    } else {
$date_of_expiry = DateTime::createFromFormat('d/m/Y', $_POST['date_of_expiry'])->format('d-m-Y');
    }    
}
if($_POST['edited_date_of_expiry']!="") {
    if (str_contains($_POST['edited_date_of_expiry'], "-")) {
$edited_date_of_expiry = DateTime::createFromFormat('Y-m-d', $_POST['edited_date_of_expiry'])->format('d-m-Y');
    } else {
$edited_date_of_expiry = DateTime::createFromFormat('d/m/Y', $_POST['edited_date_of_expiry'])->format('d-m-Y');
    }    
}

$vehicle_class = $_POST['vehicle_class'];
$edited_vehicle_class = $_POST['edited_vehicle_class'];
$state_name = $_POST['state_name'];
$edited_state_name = $_POST['edited_state_name'];
$blood_group = $_POST['blood_group'];
$edited_blood_group = $_POST['edited_blood_group'];

$application_id = $_POST['application_id'];
$agency_id = $_POST['agency_id'];
$admin_id = $_POST['admin_id'];
$verification_id = $_POST['verification_id'];
$base_amount = $_POST['base_amount'];
$sgst_amount = $_POST['sgst_amount'];
$cgst_amount = $_POST['cgst_amount'];
$front_photo = $_FILES['front_photo'];
$back_photo = $_FILES['back_photo'];
$user_photo = $_FILES['user_photo'];
$source_from = $_POST['source_from'];
$is_verified = $_POST['is_verified'];
$is_edited = $_POST['is_edited'];
$bulk_id = $_POST['bulk_id'] ?? "";
$data_fetch_through_ocr = $_POST['data_fetch_through_ocr'];
if($is_edited=="yes"){
    $driving_licence_no = $edited_driving_licence_no;
}else{
    $driving_licence_no = $driving_licence_no;
}
if($is_edited=="yes"){
    $dob1 = $edited_date_of_birth;
}else{
    $dob1 = $date_of_birth;
}

$site_id = $_POST['site_id'];
$create_worker = $_POST['create_worker'];

if($create_worker==1){
    $worker_id=unique_id_genrate('WOR', 'construction_site_worker_header_all', $mysqli);
    $name=$_POST['name'];
    $contact=$_POST['contact'];
    $login_code=$_POST['login_code'];
    $gender=$_POST['gender'];
    $address=$_POST['address'];
    $status=$_POST['status'];
    $insert_worker="INSERT INTO `construction_site_worker_header_all` (`agency_id`, `site_id`, `worker_id`, `name`, `contact`, `login_code`, `gender`, `address`, `status`, `inserted_on`) VALUES ('$agency_id', '$site_id', '$worker_id', '$name', '$contact', '$login_code', '$gender', '$address', '$status', '$system_datetime')";
    $res_worker=mysqli_query($mysqli, $insert_worker);
}
$check_error=check_error($mysqli, $mysqli1, $application_id, $verification_id, $agency_id);

if($check_error==1){
  
      if($is_verified=="bulkyes"){
        //echo "hi";
    $verify_driving = verify_driving_licence($driving_licence_no, $dob1);
    // print_r($verify_driving);
    // die();
          
}
    if ($is_verified == "yes") {
        $fetch_wallet = "SELECT `current_wallet_bal` FROM `agency_header_all` WHERE `agency_id`='$agency_id'";
        $res_wallet = mysqli_query($mysqli, $fetch_wallet);
        $arr_wallet = mysqli_fetch_assoc($res_wallet);
        if ($arr_wallet['current_wallet_bal'] < $total_amount) {
            $responce = ["error_code" => 113, "message" => "Your wallet balance is too Low
    To proceed. Please recharge your wallet."];
            echo json_encode($responce);
            return;
        } 
    }
     
  
    //   if(isValidIndianLicense($driving_licence_no)==0){
    //         $final=["error_code" => 126, "message" => "Invalid Driving lincense no "];
    //     echo json_encode($final);
    //     exit;
    //   }
     
      

     $currdate=date('Y-m-d H:i:s');
if($is_verified=="yes"){
    $verify_driving = verify_driving_licence($driving_licence_no, $dob1);
    // print_r($verify_driving);
    // exit;
    // if($verify_driving['status']=="500"){
    //     $responce = ["error_code" => 126, "message" => "Internal server error"];
    //     echo json_encode($responce);
    //     exit;
    // }
    
}
    $direct_id = unique_id_genrate('DIR', 'direct_verification_details_all', $mysqli);
    if($user_photo!=""){
        $user_img=save_doc_photo($user_photo, $direct_id, $agency_id);
    }
    if($front_photo!=""){
  $front_img=save_doc_photo($front_photo, $direct_id, $agency_id);
    }
  if($back_photo!=""){
    $back_img=save_doc_photo($back_photo, $direct_id, $agency_id);
  }
//   $back_img=save_doc_photo($back_photo, $direct_id, $agency_id);
         
         $data = json_decode($verify_driving);
    //   print_r($data->data->driving_license_data);
    
     // Debugging: Output the entire JSON response
        "JSON Response: " . json_encode($data, JSON_PRETTY_PRINT) . "\n";
         $requestId = $data->request_id;
         $transactionId = $data->transaction_id;
         $status = $data->status;
         
         $code = $data->data->code;
         
         $message = $data->data->message;
         if($code=="1001"){
            $responce = ["error_code" => 1001, "message" => $message];
            echo json_encode($responce);
            exit;
         }
         
         // Extracting driving license data
         $drivingLicenseData = $data->data->driving_license_data;
      
         if($status==500){
            $error_msg=$data->error->message;
            $responce = ["error_code" => 126, "message" => $error_msg];
            echo json_encode($responce);
            exit;
         }
    
        //  print_r($drivingLicenseData);
         $documentType = $drivingLicenseData->document_type;
         $documentId = $drivingLicenseData->document_id;
           $as_per_doc_name = $drivingLicenseData->name;
    
          $dateOfBirth = $drivingLicenseData->date_of_birth;
           $dateOfBirth=date('d-m-Y', strtotime($dateOfBirth));
         
         $dependentName = $drivingLicenseData->dependent_name;
         $address1 = $drivingLicenseData->address;
         
         // Extracting validity information
         $validity = $drivingLicenseData->validity;
        //  print_r($drivingLicenseData);
         // Transport category validity
         $transportIssuedOnDate = $validity->transport->issue_date;
         $transportValidTillDate = $validity->transport->expiry_date;
         
         // Non-transport category validity
         $nonTransportIssuedOnDate = date('d-m-Y', strtotime($validity->non_transport->issue_date));
         $nonTransportValidTillDate = date('d-m-Y', strtotime($validity->non_transport->expiry_date));
         
         // Driving license categories
         $categories = $drivingLicenseData->vehicle_class_details;
      



$valid = empty($valid_till) ? $edited_valid_till : $valid_till;
if($nonTransportValidTillDate==$date_of_expiry){
    $valid_match="Match";
}else{
    $valid_match="Not Match";
}

if($nonTransportIssuedOnDate==$date_of_issue){
    $valid_match_i="Match";
}else{
    $valid_match_i="Not Match";
}
if($categories==$vehicle_class){
    $vehicle_match="Match";
}else{
    $vehicle_match="Not Match";
}

// Check if the array is not empty before iterating
if (!empty($categories)) {
    $csvString = ""; // Initialize CSV string

    foreach ($categories as $category) {
        $csvString .= $category->category . ",";
    }

    // Remove the trailing comma
    $csvString = rtrim($csvString, ",");
        if($csvString==$vehicle_class){
    $vehicle_match="Match";
}else{
    $vehicle_match="Not Match";
}
   
}
$name1 = empty($user_name) ? $edited_name : $user_name;
     if($as_per_doc_name == $name1){
        $name_match="Match";
     }else{
        $name_match="Not Match";
     }
     if($dateOfBirth == $dob){
        $date_of_match="Match";
     }else{
        $date_of_match="Not Match";
     }
    if($address1 == $address){
        $address_match="Match";
     }else{
        $address_match="Not Match";
     }

     if($dependentName == $father_name){
        $father_match="Match";
     }else{
        $father_match="Not Match";
     }




$responce[]=[
"name_as_per_doc" => replaceNullWithEmptyString($as_per_doc_name),
"name" => replaceNullWithEmptyString($name),
"driving_license_no" => replaceNullWithEmptyString($driving_licence_no),
"is_name_match" => replaceNullWithEmptyString($name_is),
"date_of_birth" => replaceNullWithEmptyString($date_of_birth),
"is_date_of_birth_match" => replaceNullWithEmptyString($dob),
"transport_issued_on_date" => replaceNullWithEmptyString($transportIssuedOnDate),
"transport_valid_till_date" => replaceNullWithEmptyString($transportValidTillDate),
"non_transport_issued_on_date" => replaceNullWithEmptyString($nonTransportIssuedOnDate),
"non_transport_valid_till_date" => replaceNullWithEmptyString($nonTransportValidTillDate),
"category" => replaceNullWithEmptyString($csvString)
];
 
// print_r($responce[0]);/
 
// include_once "driving_licence_verification.php";


// Data to send
$postdata = [
    'is_name_match' => $response['is_name_match'],
    'is_date_of_birth_match' => replaceNullWithEmptyString($dob),
    'bulk_id' => $bulk_id,
    'name' => $user_name,
    'edited_name' => $edited_name,
    'as_per_doc_name' => $as_per_doc_name,
    'driving_licence_no' => $driving_licence_no,
    'edited_driving_licence_no' => $edited_driving_licence_no,
    'date_of_birth' => $date_of_birth,
    'edited_date_of_birth' => $edited_date_of_birth,
    'father_name' => $father_name,
    'dependentName' => $dependentName,
    'father_match' => $father_match,
    'edited_father_name' => $edited_father_name,
    'address' => $address,
    'address1' => $address1,
    'edited_address' => $edited_address,
    'valid_till' => $valid_till,
    'edited_valid_till' => $edited_valid_till,
    'date_of_issue' => $date_of_issue,
    'edited_date_of_issue' => $edited_date_of_issue,
    'date_of_expiry' => $date_of_expiry,
    'edited_date_of_expiry' => $edited_date_of_expiry,
    'vehicle_class' => $vehicle_class,
    'edited_vehicle_class' => $edited_vehicle_class,
    'state_name' => $state_name,
    'edited_state_name' => $edited_state_name,
    'blood_group' => $blood_group,
    'edited_blood_group' => $edited_blood_group,
    'driving_licence_picture' => $driving_licence_picture,
    'docs_name' => $name,
    'transport_issued_on_date' => replaceNullWithEmptyString($transportIssuedOnDate),
    'transport_valid_till_date' => replaceNullWithEmptyString($transportValidTillDate),
    'non_transport_issued_on_date' => replaceNullWithEmptyString($nonTransportIssuedOnDate),
    'non_transport_valid_till_date' => replaceNullWithEmptyString($nonTransportValidTillDate),
    'application_id' => $application_id,
    'agency_id' => $agency_id,
    'admin_id' => $admin_id,
    'verification_id' => $verification_id,
    'base_amount' => $base_amount,
    'sgst_amount' => $sgst_amount,
    'cgst_amount' => $cgst_amount,
    'is_verified' => $is_verified,
    'is_edited' => $is_edited,
    'data_fetch_through_ocr' => $data_fetch_through_ocr,
    'name_match' => $name_match,
    'dob_match' => $date_of_match,
    'valid_match' => $valid_match,
    'valid_match_i' => $valid_match_i,
    'vehicle_match' => $vehicle_match,
    'address_match' => $address_match,
    'front_img' => $front_img,
    'back_img' => $back_img,
    'user_img' => $user_img,
    'direct_id' => $direct_id,
    'category' => replaceNullWithEmptyString($csvString),
    'source_from'=>$source_from, 
    'res_dob'=>$dateOfBirth
];


// Initialize cURL session
$url = get_base_url() . '/dl_pdf_direct.php';

        // Initialize cURL session
        $ch = curl_init($url);

        // Configure cURL options
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postdata));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Execute the POST request
        $response = curl_exec($ch);

        // Check for errors
        if (curl_errno($ch)) {
            // Print the error
            echo 'cURL error: ' . curl_error($ch);
        } else {
            // Print the response
            echo $response;
        }

        // Close the cURL session
        curl_close($ch);

    


}
function replaceNullWithEmptyString($value) {
    return is_null($value) ? '' : $value;
}
function check_error($mysqli, $mysqli1, $application_id, $verification_id, $agency_id){

    $check_error=1;
    if (!$mysqli) {
        $response = ["error_code" => 101, "message" => "Unable to proceed your request, please try again after some time"];
        echo json_encode($response);
        return;
    }

    if ($_SERVER['REQUEST_METHOD'] != "POST") {
        $response = array("error_code" => 102, "message" => "Request Method is not Post");
        echo json_encode($response);
        return;
    }

    if (!isset($application_id)) {
        $response = ["error_code" => 103, "message" => "Please pass the parameter of application_id"];
        echo json_encode($response);
        return;
    }
    if (empty($application_id)) {
        $response = ["error_code" => 104, "message" => "application_id can not be empty"];
        echo json_encode($response);
        return;
    }

    if (!isset($verification_id)) {
        $response = ["error_code" => 103, "message" => "Please pass the parameter of verification_id"];
        echo json_encode($response);
        return;
    }
    if (empty($verification_id)) {
        $response = ["error_code" => 104, "message" => "verification_id can not be empty"];
        echo json_encode($response);
        return;
    }

    if (!isset($agency_id)) {
        $response = ["error_code" => 105, "message" => "Please pass the parameter of agency_id"];
        echo json_encode($response);
        return;
    }
    if (empty($agency_id)) {
        $response = ["error_code" => 106, "message" => "agency_id can not be empty"];
        echo json_encode($response);
        return;
    }
    return $check_error;
}

function verify_driving_licence($driving_licence_no, $date_of_birth) {
    // Format date of birth
    $date_of_birth = date('Y-m-d', strtotime($date_of_birth));

    // Initialize cURL session
    $curl = curl_init();

    // Set cURL options
    curl_setopt_array($curl, [
        CURLOPT_URL => "https://api.gridlines.io/dl-api/fetch",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode([
            'driving_license_number' => $driving_licence_no,
            'date_of_birth' => $date_of_birth,
            'consent' => 'Y'
        ]),
        CURLOPT_HTTPHEADER => [
            "Accept: application/json",
            "Content-Type: application/json",
            "X-API-Key: C8EbVBaNqR4g3vhBAiPXdt8cLPkNLJoL",
            "X-Auth-Type: API-Key"
        ],
    ]);

    // Execute cURL session
    $response = curl_exec($curl);
    $err = curl_error($curl);

    // Close cURL session
    curl_close($curl);

    // Check for cURL errors
    if ($err) {
        return json_encode(['error' => 'API error', 'message' => $err]);
    } else {
        // Decode the JSON response
        $result = json_decode($response, true);

        // Check if the API response indicates success
       
            // Driving license is valid
            return json_encode($result);
        
            // Driving license is invalid
            // return json_encode(['error' => 'Invalid driving license', 'message' => $result]);
        
    }
}


function isValidIndianLicense($licenseNumber) {
    // Regular expression to match the general Indian license number format
    $pattern = '/^[A-Z]{2}\s?\d{2}\s?\d{4}\s?\d{7}$/';

    // Check if the license number matches the expected format
    if (preg_match($pattern, $licenseNumber)) {
        return 1;
    }

    return 0;
}
function isDateValid($dateString) {
    $format = 'd-m-Y';
    $date = DateTime::createFromFormat($format, $dateString);
    return $date && $date->format($format) === $dateString;
}

function save_user_photo($output_pdf, $direct_id, $agency_id)
{
	// FTP server credentials
	$ftp_server = '199.79.62.21';
	$ftp_username = 'centralwp@mounarchtech.com';
	$ftp_password = 'k=Y#oBK{h}OU';

	// Remote directory path
	$remote_base_dir = "/verification_data/voco_xp/";

	// Nested directory structure to be created
	$new_directory_path = "$agency_id/$direct_id/user_photo/";

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
	$file_name =  $direct_id . '.jpg';

	// Construct the full file path on the remote server
	$file_path = $remote_dir_path . $file_name;

	// Save the PDF to a temporary file
	$temp_file = tempnam(sys_get_temp_dir(), 'jpg');
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
	$base_url = "https://mounarchtech.com/central_wp/verification_data/voco_xp/$agency_id/$direct_id/user_photo";
	$path = $base_url . '/' . $file_name;
	return $path;
}
function save_doc_photo($upload_file, $direct_id, $agency_id)
{
    // Ensure $upload_file is an array and has the necessary keys
    if (!is_array($upload_file) || !isset($upload_file['name']) || !isset($upload_file['tmp_name'])) {
        throw new Exception('Invalid file upload array.');
    }

    // FTP server credentials
    $ftp_server = '199.79.62.21';
    $ftp_username = 'centralwp@mounarchtech.com';
    $ftp_password = 'k=Y#oBK{h}OU';

    // Remote directory path
    $remote_base_dir = "/verification_data/voco_xp/";

    // Nested directory structure to be created
    $new_directory_path = "$agency_id/$direct_id/doc_photo/";

    // Initialize cURL session for FTP
    $curl = curl_init();

    // Set cURL options for FTP
    curl_setopt_array($curl, array(
        CURLOPT_URL => "ftp://$ftp_server/",
        CURLOPT_USERPWD => "$ftp_username:$ftp_password",
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_FTP_CREATE_MISSING_DIRS => true, // Create missing directories
    ));

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
    if ($response_mkdir === false) {
        $error_message_mkdir = curl_error($curl);
        // It's possible the directory already exists, so we can ignore this error in some cases
    }

    // Get file extension
    $ext = strtolower(pathinfo($upload_file['name'], PATHINFO_EXTENSION));

    // Generate a unique file name
    $file_name = uniqid();

    // Path to save the uploaded file on the remote server
    $file_path = $remote_dir_path . "docs-" . $file_name . "." . $ext;

    // Create an image resource from the uploaded file
    switch ($ext) {
        case 'jpeg':
        case 'jpg':
            $image = imagecreatefromjpeg($upload_file['tmp_name']);
            break;
        case 'png':
            $image = imagecreatefrompng($upload_file['tmp_name']);
            break;
        case 'gif':
            $image = imagecreatefromgif($upload_file['tmp_name']);
            break;
        default:
            throw new Exception('Unsupported file format');
    }

    // Get original dimensions
    list($width, $height) = getimagesize($upload_file['tmp_name']);

    // Desired maximum size in bytes
    $maxSize = 20 * 1024; // 20 KB

    // Resize image dimensions (e.g., reduce to 20% of original size)
    $new_width = $width * 0.2;
    $new_height = $height * 0.2;
    $resized_image = imagecreatetruecolor($new_width, $new_height);

    // Preserve transparency for PNG and GIF
    if ($ext === 'png' || $ext === 'gif') {
        imagecolortransparent($resized_image, imagecolorallocatealpha($resized_image, 0, 0, 0, 127));
        imagealphablending($resized_image, false);
        imagesavealpha($resized_image, true);
    }

    // Copy and resize the original image into the resized image
    imagecopyresampled($resized_image, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

    // Start with lower quality
    $quality = 50; // Adjust the quality setting

    // Temporary output buffer
    ob_start();
    switch ($ext) {
        case 'jpeg':
        case 'jpg':
            imagejpeg($resized_image, null, $quality);
            break;
        case 'png':
            imagepng($resized_image, null, (int)($quality / 10 - 1));
            break;
        case 'gif':
            imagegif($resized_image, null);
            break;
    }
    $imageData = ob_get_clean();
    $fileSize = strlen($imageData);

    // Decrease quality until file size is within desired range
    while ($fileSize > $maxSize && $quality > 10) {
        $quality -= 5;
        ob_start();
        switch ($ext) {
            case 'jpeg':
            case 'jpg':
                imagejpeg($resized_image, null, $quality);
                break;
            case 'png':
                imagepng($resized_image, null, (int)($quality / 10 - 1));
                break;
            case 'gif':
                imagegif($resized_image, null);
                break;
        }
        $imageData = ob_get_clean();
        $fileSize = strlen($imageData);
    }

    // Save the resized image to a temporary file
    $temp_file = tempnam(sys_get_temp_dir(), 'img');
    file_put_contents($temp_file, $imageData);

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
    ));

    // Execute cURL session for file upload
    ob_start(); // Start output buffering
    $response_upload = curl_exec($curl_upload);
    ob_end_clean(); // Discard output buffer

    // Check for errors in file upload
    if ($response_upload === false) {
        $error_message_upload = curl_error($curl_upload);
        die("Failed to upload image file: $error_message_upload");
    }

    // Close cURL sessions
    curl_close($curl);
    curl_close($curl_upload);

    // Clean up temporary files
    imagedestroy($image);
    imagedestroy($resized_image);
    unlink($temp_file);

    // Construct the full URL of the uploaded file
    $base_url = "https://mounarchtech.com/central_wp/verification_data/voco_xp/$agency_id/$direct_id/doc_photo";
    $emp_imageURL = $base_url . '/docs-' . $file_name . '.' . $ext;

    // Return the URL of the uploaded file
    return $emp_imageURL;
}





?>