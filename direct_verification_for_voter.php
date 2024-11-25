
<?php

error_reporting(E_ALL & ~E_DEPRECATED);
ini_set('display_errors', 0);

header('Content-Type: application/json; charset=UTF-8');
header('Access-Contorl-Allow-Origin: *');
date_default_timezone_set('Asia/kolkata');
include 'connection.php';
$connection = connection::getInstance();
$mysqli = $connection->getConnection();

$connection1 = database::getInstance();
$mysqli1 = $connection1->getConnection();


date_default_timezone_set('Asia/kolkata');
$system_date = date("y-m-d");
$system_date_time = date("Y-m-d H:i:s");
$output = $responce = array();

//data from user
$user_name = $_POST['name'];
$edited_name = $_POST['edited_name'];
$user_voter_number = $_POST['voter_number'];
$edited_voter_number = $_POST['edited_voter_number'];

if($_POST['date_of_birth']!="") {
    if (str_contains($_POST['date_of_birth'], "-")) {
 $user_date_of_birth = DateTime::createFromFormat('Y-m-d', $_POST['date_of_birth'])->format('d-m-Y');
  } else {
     $date_of_birth = DateTime::createFromFormat('d/m/Y', $_POST['date_of_birth']);
    if ($date_of_birth !== false) {
        $user_date_of_birth = $date_of_birth->format('d-m-Y');
    } else {
        // Handle the error, e.g., set a default value or log an error message
        $user_date_of_birth = 'Invalid date format';
    }
  }    
}
if($_POST['edited_date_of_birth']!="") {
    if (str_contains($_POST['edited_date_of_birth'], "-")) {
 $edited_date_of_birth = DateTime::createFromFormat('Y-m-d', $_POST['edited_date_of_birth'])->format('d-m-Y');
  } else {
    $edited_date_of_birth_obj = DateTime::createFromFormat('d/m/Y', $_POST['edited_date_of_birth']);
    if ($edited_date_of_birth_obj !== false) {
        $edited_date_of_birth = $edited_date_of_birth_obj->format('d-m-Y');
    } else {
        // Handle the error, e.g., set a default value or log an error message
        $edited_date_of_birth = 'Invalid date format';
    }
  }    
}



// $user_date_of_birth = $_POST['date_of_birth'];
// $edited_date_of_birth = $_POST['edited_date_of_birth'];
$user_father_guardian_name = $_POST['father_guardian_name'];
$edited_father_guardian_name = $_POST['edited_father_guardian_name'];
$user_gender = $_POST['gender'];
$edited_gender = $_POST['edited_gender'];
$user_polling_details = $_POST['polling_details'];
$edited_polling_details = $_POST['edited_polling_details'];
$user_address = $_POST['address'];
$edited_address = $_POST['edited_address'];

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
$data_fetch_through_ocr = $_POST['data_fetch_through_ocr'];

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

$total_amount = $base_amount + $sgst_amount + $cgst_amount;




$check_error_res = check_error($mysqli, $mysqli1, $application_id, $verification_id, $agency_id);
if ($check_error_res == 1) {
    if($is_verified=="bulkyes"){
           
            $verification_data = json_decode(verify_voter_id($user_voter_number), true);
            // print_r($verification_data);
            // exit;
            if ($verification_data['data']['code'] == 1000) {
               $bulk_id=$_POST['bulk_id'];
                    $admin_id=$_POST['admin_id'];
            }
            else 
            {
                  
                   $responce = ["error_code" => 199, "message" => "Voter number is invalid. Please provide the valid Voter number"];
                echo json_encode($responce);
                return;
            }
        }
    if($is_verified=="yes"){
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
        if($is_verified=="yes"){
            $verification_data = json_decode(verify_voter_id($user_voter_number), true);
// print_r($verification_data);
// exit;
            if ($verification_data['data']['status'] == "INVALID") {
                $responce = ["error_code" => 199, "message" => "Voter number is invalid. Please provide the valid Voter number"];
                echo json_encode($responce);
                return;
            }
        }
        
        

        if ($is_proceed == "yes") 
        {
            $status = 1;
        } else {
            $status = 2;
        }
        

     

        
        
        if ($verification_data['data']['code'] == 1000) {

       if ($verification_data['data']['voter_data']['name'] == strtoupper($user_name)) {
                $name_match = "Match";
            } else {
                $name_match = "Not Match";
            }

            if ($verification_data['data']['voter_data']['father_name'] == strtoupper($user_father_guardian_name)) {
                $father_match = "Match";
            } else {
                $father_match = "Not Match";
            }
            
            if ($verification_data['data']['voter_data']['gender'] == strtoupper($user_gender)) {
                $gender_match = "Match";
            } else {
                $gender_match = "Not Match";
            }

            if ($verification_data['data']['voter_data']['polling_station'] == $user_polling_details) {
                $polling_match = "Match";
            } else {
                $polling_match = "Not Match";
            }

            
           

    } 
    $direct_id = unique_id_genrate('DIR', 'direct_verification_details_all', $mysqli);
    if($user_photo!=""){
        $user_img=save_doc_photo($user_photo, $direct_id, $agency_id);
    }
  $front_img=save_doc_photo($front_photo, $direct_id, $agency_id);
  $back_img=save_doc_photo($back_photo, $direct_id, $agency_id);
    $postdata = [
        'bulk_id' => $bulk_id,
        'name' => $user_name,
        'edited_name' => $edited_name,
        'voter_number' => $user_voter_number,
        'edited_voter_number' => $edited_voter_number,
        'date_of_birth' => $user_date_of_birth,
        'edited_date_of_birth' => $edited_date_of_birth,
        'father_guardian_name' => $user_father_guardian_name,
        'edited_father_guardian_name' => $edited_father_guardian_name,
        'gender' => $user_gender,
        'edited_gender' => $edited_gender,
        'polling_details' => $user_polling_details,
        'edited_polling_details' => $edited_polling_details,
        'address' => $user_address,
        'edited_address' => $edited_address,
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
        'verification_name' => $verification_data['data']['full_name'],
        'verification_category' => 'Individual',
        'name_match' => $name_match,
        'father_match' => $father_match,
        'gender_match' => $gender_match,
        'polling_match' => $polling_match,
        'front_img' => $front_img,
        'back_img' => $back_img,
        'user_img' => $user_img,
        'direct_id' => $direct_id,
        'birth_date_match' => '',  // Add actual data if available
        'pincode_match' => '',  // Add actual data if available
        'percentage' => '',  // Add actual data if available
        'source_from'=>$source_from,
        'res_name'=>$verification_data['data']['voter_data']['name'],
        'polling_station'=>$verification_data['data']['voter_data']['polling_station'],
        'res_voter_number'=>$verification_data['data']['voter_data']['voter_number'],
        'res_date_of_birth'=>$verification_data['data']['voter_data']['date_of_birth'],
        'res_father_guardian_name'=>!empty($verification_data['data']['voter_data']['father_name']) 
        ? $verification_data['data']['voter_data']['father_name'] 
        : $verification_data['data']['voter_data']['husband_name'] ,
        'res_gender'=>$verification_data['data']['voter_data']['gender'] 
         ];

    


$url = get_base_url() . '/voter_pdf_direct.php';

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


function check_error($mysqli, $mysqli1, $application_id, $verification_id, $agency_id)
{

    $check_error = 1;
    if (!$mysqli) {
        $response[] = ["error_code" => 101, "message" => "Unable to proceed your request, please try again after some time"];
        echo json_encode($response);
        return;
    }

    if ($_SERVER['REQUEST_METHOD'] != "POST") {
        $response[] = array("error_code" => 102, "message" => "Request Method is not Post");
        echo json_encode($response);
        return;
    }

    if (!isset($application_id)) {
        $response[] = ["error_code" => 103, "message" => "Please pass the parameter of application_id"];
        echo json_encode($response);
        return;
    }
    if (empty($application_id)) {
        $response[] = ["error_code" => 104, "message" => "application_id can not be empty"];
        echo json_encode($response);
        return;
    }

    if (!isset($verification_id)) {
        $response[] = ["error_code" => 103, "message" => "Please pass the parameter of verification_id"];
        echo json_encode($response);
        return;
    }
    if (empty($verification_id)) {
        $response[] = ["error_code" => 104, "message" => "verification_id can not be empty"];
        echo json_encode($response);
        return;
    }

    if (!isset($agency_id)) {
        $response[] = ["error_code" => 105, "message" => "Please pass the parameter of agency_id"];
        echo json_encode($response);
        return;
    }
    if (empty($agency_id)) {
        $response[] = ["error_code" => 106, "message" => "agency_id can not be empty"];
        echo json_encode($response);
        return;
    }

    

    return $check_error;
}


function verify_voter_id($voter_id_no) {
	$curl = curl_init();
	curl_setopt_array($curl, [
			CURLOPT_URL => "https://api.gridlines.io/voter-api/boson/fetch",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => json_encode([
				'voter_id' => $voter_id_no,
				'consent' => 'Y'
			]),
		CURLOPT_HTTPHEADER => [
			"Accept: application/json",
			"Content-Type: application/json",
			"X-API-Key: C8EbVBaNqR4g3vhBAiPXdt8cLPkNLJoL",
			"X-Auth-Type: API-Key"
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
