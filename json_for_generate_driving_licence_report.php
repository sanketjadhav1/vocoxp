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
include_once 'connection.php';

// Now you can use the connection class
$connection = connection::getInstance();
$mysqli = $connection->getConnection();

$connection1 = database::getInstance();
$mysqli1 = $connection1->getConnection();
apponoff($mysqli); 
logout($_SERVER['HTTP_AUTH_KEY'], $mysqli);

$driving_licence_picture=$_FILES['driving_licence_picture']['name'];

$check_error=check_error($mysqli, $_POST['agency_id'], $_POST['member_id'], $_POST['driving_licence_no'], $_POST['as_per_doc_name'], $_POST['date_of_birth'], $_POST['image'], $_POST['application_id'], $_POST['specification_id'], $_POST['request_id']);


if(!isset($driving_licence_picture)){
    $response[]=["error_code" => 126, "message" => "Please the parameter - driving_licence_picture"];
    echo json_encode($response);
    return;
}else{
    if($driving_licence_picture==""){
        $response[]=["error_code" => 127, "message" => "driving_licence_picture is empty. Please provide driving_licence_picture"];
        echo json_encode($response);
        return;
    }
}


if($check_error==1){

     $agency_id=$_POST['agency_id'];
     $member_id=$_POST['member_id'];
     $driving_licence_no=$_POST['driving_licence_no'];
     $as_per_doc_name=strtoupper($_POST['as_per_doc_name']);      
     $date_of_birth=date("d-m-Y", strtotime($_POST['date_of_birth']));
     $application_id=$_POST['application_id'];
     $specification_id=$_POST['specification_id'];
     $request_id=$_POST['request_id'];
     $verification_status="Initiated";
  
      if(isValidIndianLicense($driving_licence_no)==0){
            $final[]=["error_code" => 126, "message" => "Invalid Driving lincense no "];
        echo json_encode($final);
        exit;
      }
     
      

     $currdate=date('Y-m-d H:i:s');

         $verify_driving = verify_driving_licence($driving_licence_no, $date_of_birth);
        // print_r($verify_driving);
      $data = json_decode($verify_driving);
    //   print_r($data->data->driving_license_data);
    
     // Debugging: Output the entire JSON response
        "JSON Response: " . json_encode($data, JSON_PRETTY_PRINT) . "\n";
     
     // Accessing data
     $requestId = $data->request_id;
     $transactionId = $data->transaction_id;
     $status = $data->status;
     $code = $data->data->code;
     $message = $data->data->message;
     
     // Extracting driving license data
     $drivingLicenseData = $data->data->driving_license_data;
  


    //  print_r($drivingLicenseData);
     $documentType = $drivingLicenseData->document_type;
     $documentId = $drivingLicenseData->document_id;
       $name = $drivingLicenseData->name;

      $dateOfBirth = $drivingLicenseData->date_of_birth;
       $dateOfBirth=date('d-m-Y', strtotime($dateOfBirth));
     
     $dependentName = $drivingLicenseData->dependent_name;
     $address = $drivingLicenseData->address;
     
     // Extracting validity information
     $validity = $drivingLicenseData->validity;
    //  print_r($drivingLicenseData);
     // Transport category validity
     $transportIssuedOnDate = $validity->transport->issue_date;
     $transportValidTillDate = $validity->transport->expiry_date;
     
     // Non-transport category validity
     $nonTransportIssuedOnDate = $validity->non_transport->issue_date;
     $nonTransportValidTillDate = $validity->non_transport->expiry_date;
     
     // Driving license categories
     $categories = $drivingLicenseData->vehicle_class_details;


// Check if the array is not empty before iterating
if (!empty($categories)) {
    $csvString = ""; // Initialize CSV string

    foreach ($categories as $category) {
        $csvString .= $category->category . ",";
    }

    // Remove the trailing comma
    $csvString = rtrim($csvString, ",");

   
}

     if($as_per_doc_name == $name){
        $name_is="Yes";
     }else{
        $name_is="No";
     }
     if($dateOfBirth == $date_of_birth){
        $dob="Yes";
     }else{
        $dob="No";
     }
   
    $fetch_agency_id = "SELECT `agency_id`, `status`, `type` FROM `agency_header_all` WHERE `agency_id`='$agency_id'";
$result_agency = mysqli_query($mysqli, $fetch_agency_id);
$arr_agency = mysqli_fetch_assoc($result_agency);
if($arr_agency['type']=="organization"){
    $request_for="other_person";
}else{
    $request_for="family";
}
if (!$arr_agency) {
    $response[] = array("error_code" => 102, "message" => "Invalid agency ID. Please provide a valid agency ID");
    echo json_encode($response);
    return;
} elseif ($arr_agency['status'] != "1") {
    $response[] = array("error_code" => 103, "message" => "The agency name is currently inactive");
    echo json_encode($response);
    return;
}
    $fetch_member_id = "SELECT `member_id`, `name`, `contact_no` FROM member_header_all WHERE member_id='$member_id'";
$result_member = mysqli_query($mysqli, $fetch_member_id);
$arr = mysqli_fetch_assoc($result_member);

if (!$arr) {
    $response[] = array("error_code" => 104, "message" => "Member Id is not valid. Please provide a valid
    member id");
    echo json_encode($response);
    return;
}

$fetch_specification_id = "SELECT `verification_id`  FROM `verification_header_all` WHERE `verification_id`='$specification_id'";
$result_specification_id = mysqli_query($mysqli1, $fetch_specification_id);
$arr_specification = mysqli_fetch_assoc($result_specification_id);
if (!$arr_specification) {
    $response[] = array("error_code" => 110, "message" => "Invalid Specification ID. Please provide the correct Specification ID.");
    echo json_encode($response);
    return;
}


if(isset($_FILES['driving_licence_picture']) && !empty($_FILES['driving_licence_picture']['name'])) {
    // echo "check";
    $driving_licence_picture = upload_document($_FILES['driving_licence_picture']);
}




$fetch_request_id = "SELECT `request_id`  FROM `verification_payment_transaction_all` WHERE `request_id`='$request_id'";
$result_request_id = mysqli_query($mysqli1, $fetch_request_id);
$arr_specification = mysqli_fetch_assoc($result_request_id);
if (!$arr) {
    $response[] = array("error_code" => 106, "message" => "Invalid request Id, Please provide correct
    request Id");
    echo json_encode($response);
    return;
}


 $fetch_specification="SELECT `verification_configuration_all`.`application_id`, `verification_configuration_all`.`rate`,`verification_header_all`.* FROM `verification_header_all` INNER JOIN `verification_configuration_all` ON `verification_configuration_all`.`verification_id`=`verification_header_all`.`verification_id` WHERE `verification_header_all`.`verification_id`='$specification_id' AND `verification_configuration_all`.`application_id`='$application_id'";
$result_specification = mysqli_query($mysqli1, $fetch_specification);
$arr_specification = mysqli_fetch_assoc($result_specification);

//  $request_id=rand(100000000,19999999);
// $request_id="R".$request_id;
$price = $arr_specification['rate'];
$gst_rate = 0.18; // 18%
$gst_amount = $price * $gst_rate;
$net_amount=$price + $gst_amount;
$rate=$arr_specification['rate'];
$fetch_member="SELECT `name`, `type` FROM `member_header_all` WHERE `member_id`='$member_id'";
$result_member = mysqli_query($mysqli, $fetch_member);
$arr_member = mysqli_fetch_assoc($result_member);
if($name!=null){
$update_dl = "UPDATE `driving_license_transaction_all` 
SET 
    `verification_status` = 'Report Generating',
    `price` = '$rate',
    `modified_on` = '$new_currdate'
WHERE 
    `application_id` = '$application_id' AND
    `agency_id` = '$agency_id' AND
    `person_id` = '$member_id' AND
    `request_id` = '$request_id'";

// Execute the update query
$result = mysqli_query($mysqli1, $update_dl);
}
// else{
//     $final[]=["error_code" => 126, "message" => "Invalid Driving lincense no / Date of birth"];
// echo json_encode($final);
// return;
// }
// Check for errors 


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
 if($data->data->code==1000){
// include_once "driving_licence_verification.php";
$url = 'https://mounarchtech.com/vocoxp/driving_licence_verification.php';

// Data to send
$postdata = array(
	'is_name_match' => $responce[0]['is_name_match'],
	'is_date_of_birth_match' => $responce[0]['is_date_of_birth_match'],
	'is_name_match' => $responce[0]['is_name_match'],
    'is_date_of_birth_match' => $responce[0]['is_date_of_birth_match'],
    'name' => $arr['name'],
    'contact_no' => $arr['contact_no'],
    'as_per_doc_name' => $as_per_doc_name,
    'driving_licence_no' => $driving_licence_no,
    'date_of_birth' => $date_of_birth,
    'driving_licence_picture' => $driving_licence_picture,
    'category' => $responce[0]['category'],
    'docs_name' => $drivingLicenseData->name,
    'transport_issued_on_date' => $responce[0]['transport_issued_on_date'],
    'transport_valid_till_date' => $responce[0]['transport_valid_till_date'],
    'doc_date_of_birth' => $dateOfBirth,
    'non_transport_issued_on_date' => $responce[0]['non_transport_issued_on_date'],
    'non_transport_valid_till_date' => $responce[0]['non_transport_valid_till_date'],
	'agency_id' => $agency_id,
	'member_id' => $member_id,
	'specification_id' => $specification_id,
	'request_id' => $request_id, 
	'application_id' => $application_id,
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
// $responce['pdf_url']=$path;
 
// $final[]=["error_code" => 100, "data" => $responce];
// echo json_encode($final);


 $path_data[]=array("pdf_url"=>$path);
$new_responce[]= ["error_code" => 100, "data" =>$path_data];
echo json_encode($new_responce);

// exit;

$integrated_url = 'https://mounarchtech.com/vocoxp/integrated_pdf.php';
// Data to send
$integreat_data = array(
	'agency_id' => $agency_id,
	'member_id' => $member_id,
	'path' => $path,
);
// echo "check";
// Initialize cURL session
$ch = curl_init();

// Set cURL options
curl_setopt($ch, CURLOPT_URL, $integrated_url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($integreat_data)); // Use $postdata instead of $data
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
 }else{
    $new_responce[]= ["error_code" => 109, "message" =>"Invalid driving lincence no or Date of birth."];
    echo json_encode($new_responce);
    exit;
 }


}
function replaceNullWithEmptyString($value) {
    return is_null($value) ? '' : $value;
}
function check_error($mysqli, $agency_id, $member_id, $driving_licence_no, $as_per_doc_name, $date_of_birth, $image, $application_id, $specification_id, $request_id){

    $check_error=1;
    if(!$mysqli){
        $response[]=["error_code" => 101, "message" => "An unexpected server error occurred while
        processing your request. Please try after sometime"];
        echo json_encode($response);
        return;
    }
    if($_SERVER["REQUEST_METHOD"] != "POST"){
        $responce[] = array( "error_code" => 109, "message" => "please change request method to POST");
        echo json_encode($responce);
        return; 
    
            
    }
    if(!isset($agency_id)){
        $response[]=["error_code" => 125, "message" => "Please the parameter - agency_id"];
        echo json_encode($response);
        return;
    }else{
        if($agency_id==""){
            $response[]=["error_code" => 111, "message" => "Value of 'agency_id' cannot be empty"];
            echo json_encode($response);
            return;
        }
    }
    if(!isset($application_id)){
        $response[]=["error_code" => 119, "message" => "Please the parameter - application_id"];
        echo json_encode($response);
        return;
    }else{
        if($application_id==""){
            $response[]=["error_code" => 120, "message" => "Value of 'application_id' cannot be empty"];
            echo json_encode($response);
            return;
        }
    }
    if(!isset($member_id)){
        $response[]=["error_code" => 112, "message" => "Please the parameter - member_id"];
        echo json_encode($response);
        return;
    }else{
        if($member_id==""){
            $response[]=["error_code" => 113, "message" => "Value of 'member_id' cannot be empty"];
            echo json_encode($response);
            return;
        }
    }
    if(!isset($driving_licence_no)){
        $response[]=["error_code" => 114, "message" => "Please the parameter - driving_licence_no"];
        echo json_encode($response);
        return;
    }else{
        if($driving_licence_no==""){
            $response[]=["error_code" => 115, "message" => "Value of 'driving_licence_no' cannot be empty"];
            echo json_encode($response);
            return;
        }
    }
    if(!isset($as_per_doc_name)){
        $response[]=["error_code" => 116, "message" => "Please the parameter - as_per_doc_name"];
        echo json_encode($response);
        return;
    }else{
        if($as_per_doc_name==""){
            $response[]=["error_code" => 108, "message" => "as_per_doc_name is empty. Please enter Member
            Name."];
            echo json_encode($response);
            return;
        }
    }
    if(!isset($date_of_birth)){
        $response[]=["error_code" => 117, "message" => "Please the parameter - date_of_birth"];
        echo json_encode($response);
        return;
    }else{
        if($date_of_birth==""){
            $response[]=["error_code" => 107, "message" => "Date of Birth is empty. Please provide date of
            birth"];
            echo json_encode($response);
            return;
        }
    }
    if(!isset($specification_id)){
        $response[]=["error_code" => 123, "message" => "Please the parameter - specification_id"];
        echo json_encode($response);
        return;
    }else{
        if($specification_id==""){
            $response[]=["error_code" => 124, "message" => "specification_id is empty. Please provide specification_id"];
            echo json_encode($response);
            return;
        }
    }


    if(!isset($request_id)){
        $response[]=["error_code" => 121, "message" => "Please the parameter - request_id"];
        echo json_encode($response);
        return;
    }else{
        if($request_id==""){
            $response[]=["error_code" => 122, "message" => "request_id is empty. Please provide request_id"];
            echo json_encode($response);
            return;
        }
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



?>