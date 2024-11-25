<?php
// Suppress deprecation notices
error_reporting(E_ALL & ~E_DEPRECATED);
ini_set('display_errors', 0);

require_once __DIR__ . '/vendor/autoload.php'; 

include 'connection.php';

// ini_set('display_errors', 1);
$connection = connection::getInstance();
$mysqli = $connection->getConnection();
///////////////////////////////////////////////
$connection1 = database::getInstance(); 
$mysqli1 = $connection1->getConnection();

date_default_timezone_set('Asia/kolkata');
    $system_date = date("y-m-d");
    $system_date_time = date("Y-m-d H:i:s");
    // $date_of_birth = DateTime::createFromFormat('d/m/Y', $_POST['date_of_birth']);
    // if ($date_of_birth !== false) {
	// 	$date_of_birth = $date_of_birth->format('Y-m-d');
	// } else {
	// 	// Handle the error
	// 	$date_of_birth = null; // or some error handling code
	// }
	$given_name = $_POST['given_name'];
	$edited_given_name = $_POST['edited_given_name'];
	$passport_no = $_POST['passport_no'];
	$edited_passport_no = $_POST['edited_passport_number'];
	 $date_of_birth = $_POST['date_of_birth'];
	
	$dob = date("Y-m-d", strtotime($_POST['date_of_birth']));
	$edited_dob = $_POST['edited_dob'];
	$edited_dob_save = date('Y-m-d',strtotime($_POST['edited_dob']));
	$surname = $_POST['surname'];
	$edited_surname = $_POST['edited_surname'];
	$file_number = $_POST['file_number'];
	$edited_file_numbers = $_POST['edited_file_number'];
	$origin_country = $_POST['origin_country'];
	$edited_origin_country = $_POST['edited_origin_country'];
	$expiry_date = $_POST['expiry_date'];
	$expiry_date_save = date('Y-m-d',strtotime($_POST['expiry_date']));
	
	$edited_expiry_date = $_POST['edited_expiry_date'];
	$edited_expiry_date_save = date('Y-m-d',strtotime($_POST['edited_expiry_date']));
	$address = $_POST['address'];
	$edited_address = $_POST['edited_address'];
	$father_name = $_POST['father_name'];
	$edited_father_name = $_POST['edited_father_name'];
    
    $application_id = $_POST['application_id'];
    $direct_id = $_POST['direct_id'];
    $agency_id = $_POST['agency_id'];
    if($_POST['admin_id']!=""){
		$admin_id = $_POST['admin_id'];
	}else{
		$admin_id = $_POST['agency_id'];
	}
    
    $verification_id = $_POST['verification_id']; 
    $base_amount = $_POST['base_amount'];
    $sgst_amount = $_POST['sgst_amount'];
    $cgst_amount = $_POST['cgst_amount'];
     $front_img = $_POST['front_photo'];
    $back_img = $_POST['back_photo'];
    $cover_img = $_POST['cover_photo'];
    $user_img = $_POST['user_photo'];
    $visa_img = $_POST['visa_photo'];
    $landing_date = $_POST['landing_date'];
    $landing_date_save = date('Y-m-d', strtotime($_POST['landing_date']));
    $edited_landing_date = $_POST['edited_landing_date'];
    $edited_landing_date_save = date('Y-m-d', strtotime($_POST['edited_landing_date']));
    $visa_validity = $_POST['visa_validity'];
    $visa_validity_save = date('Y-m-d', strtotime($_POST['visa_validity']));
    $edited_visa_validity = $_POST['edited_visa_validity'];
    $edited_visa_validity_save = date('Y-m-d', strtotime($_POST['edited_visa_validity']));
    $country = $_POST['country'];
    $edited_country = $_POST['edited_country'];
    $visa_type = $_POST['visa_type'];
    $edited_visa_type = $_POST['edited_visa_type'];
    $source_from = $_POST['source_from'];
    
    $is_verified = $_POST['is_verified'];
    $is_edited = $_POST['is_edited'];
    $data_fetch_through_ocr = $_POST['data_fetch_through_ocr'];

 $fetch_wallet = "SELECT `current_wallet_bal` FROM `agency_header_all` WHERE `agency_id`='$agency_id'";
    $res_wallet = mysqli_query($mysqli, $fetch_wallet);
    $arr_wallet = mysqli_fetch_assoc($res_wallet);
    
$direct_id = $_POST['direct_id'];

if ($is_verified == "yes") {
	$status = 1;
	$base_amount=$_POST['base_amount'];
  $sgst_amount=$_POST['sgst_amount'];
  $cgst_amount=$_POST['cgst_amount'];
  $gst=$sgst_amount+$cgst_amount;
	  $sgst_amt=$base_amount*$sgst_amount/100;
	  $cgst_amt=$base_amount*$cgst_amount/100;
	  $grand_total=$base_amount*$gst/100;
	$deducted_amt=$arr_wallet['current_wallet_bal'] - $grand_total- $base_amount;
   $total=$base_amount+$sgst_amt+$cgst_amt;
} else {
  $status = 2;
}
if($is_name_match=='Match'){ 
  $color='green';
}else{
  $color='red';
}
if($is_card_type_match=='Match'){
  $color1='green';
}else{
  $color1='red';
}
// HTML content with embedded image

$html = '<html>
<head>
   <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

  <style>
  #firstDiv{
    padding:15px;
    background: #889dd1;
  }
  #header{
      margin-top:20px !important;
      font-weight: bold;
      font-size:20px;
  }
  #first{
      font-size:23px;
  }
  #second{
      font-size:23px;
  }
  #table{
    margin-top:10px;
  }
  th, td{
    line-height: 20px;
  }
  </style>
</head>
<body>

<div id="firstDiv">
    <table style="width:100%; border-collapse: collapse;">
        <thead>  
        </thead>
        <tbody>
            <tr>
                <td width="70%">
                   <h3 id="header"> PASSPORT VERIFICATION REPORT</h3>
                </td>
                <td width="30%" align="left">
                <img src="vendor/microintegrated_logo.png" alt="Placeholder image" width="20%" />
                </td>
            </tr>
        </tbody>
    </table>
</div>
<table style="width:100%; border-collapse: collapse;" id="table">
    <tr>
      
    </tr> 
    <tr>
      <td scope="col" colspan="2">
      <hr>
      <p style="font-weight: bold;">Provided Information</p>
      </td>
    </tr>
    <tr>
        <td scope="col"><span style="font-weight: bold;"> Name:</span> '.(!empty($given_name) ? $given_name : $edited_given_name).'</td>
        <td scope="col"><span style="font-weight: bold;">Passport Number: </span>'.(!empty($passport_no) ? $passport_no : $edited_passport_no).'</td>
    </tr>
    <tr>
        <td scope="col"><span style="font-weight: bold;">Date Of Birth:</span> '.(!empty($date_of_birth) ? date("d-m-Y", strtotime($date_of_birth)) : date("d-m-Y", strtotime($edited_dob))).'</td>
    </tr>
    <tr>
        <td scope="col"><span style="font-weight: bold;">Surname:</span> '.(!empty($surname) ? $surname : $edited_surname).'</td>
        <td scope="col"><span style="font-weight: bold;">File Number:</span> '.(!empty($file_number) ? $file_number : $edited_file_numbers).'</td>
    </tr>
    <tr>
        <td scope="col"><span style="font-weight: bold;">Origin Country:</span> '.(!empty($origin_country) ? $origin_country : $edited_origin_country).'</td>
        <td scope="col"><span style="font-weight: bold;">Expiry Date:</span> '.(!empty($expiry_date) ? date("d-m-Y", strtotime($expiry_date)) : date("d-m-Y", strtotime($edited_expiry_date))).'</td>
    </tr>
    <tr>
        <td scope="col"><span style="font-weight: bold;">Address:</span> '.(!empty($address) ? $address : $edited_address).'</td>
    </tr>
    <tr>
        <td scope="col"><span style="font-weight: bold;">Father Name:</span> '.(!empty($father_name) ? $father_name : $edited_father_name).'</td>
    </tr>
</table>
<hr>
<table style=" width:100%; border-collapse: collapse;">
    <tr>
      <td scope="col" colspan="2">
      <h1 id="second"><u>Provided Document</u></h1>
      <br>
      </td>
    </tr>
    <tr>';
	if($visa_img!=""){
		$html='
		<td scope="col" align="center">
		<p style="text-align: center; font-size:15px;">Visa Image</p>
	<br>
		<img src="'.$visa_img.'" alt="Placeholder image" width="30%" />
		</td>';
	}
	
    $html='<td scope="col" align="center">
    <p style="text-align: center; font-size:15px;">Front Image</p>
    <br>
    <img src="'.$front_img.'" alt="Placeholder image" width="30%" />
    </td>';
	if($back_img!=""){
$html=' <td scope="col" align="center">
    <p style="text-align: center; font-size:15px;">Back Image</p>
    <br>
    <img src="'.$back_img.'" alt="Placeholder image" width="30%" />
    </td>';
	}
   
  $html='</tr>
</table>
<br><br>

<h1 id="first" ><u>Result</u></h1>

<p style="font-weight: bold;">Report Summary</p>

<span style="font-weight: bold;">This Report Information Generated Against Passport Number: </span>'.$passport_no.'

<table style=" width:100%; border-collapse: collapse;" id="table">
<tr>
<td scope="col" style="width:50%"><span style="font-weight: bold;">Passport Number:</span> '.$passport_no.'</td>

<td scope="col" style="width:50%"><span style="font-weight: bold;"> Name:</span> '.strtoupper($given_name).'<br><span style="color:'.$color.';">'.$name_match.'</span></td>
</tr>
<tr>
<td scope="col" style="width:50%"><span style="font-weight: bold;">Date Of Birth:</span> '.date("d-m-Y", strtotime($date_of_birth)).'<br><span style="color:'.$color1.';">'.$birth_date_match.'</span></td>
</tr>
</table>

<hr>
<p style="margin-top:10px;"><span style="color:red;">*</span>Please note that the contents shown above are provided in good faith, we do not warrant that the information will be kept up to date, be true, accurate and not misleading.</p>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</body>
</html>';

	



// Initialize mPDF
$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4']);


 // Write HTML content to mPDF
$mpdf->WriteHTML($html);

// Output PDF to a variable
$output_pdf = $mpdf->Output('', 'S');

// Save PDF to a file
  $path = savePDF1($output_pdf, $direct_id, $agency_id);
  
  
 
// Define response array
$response = array();

// Check if the path is valid
if ($path !== false) {
    // PDF generated successfully
    $response['error_code'] = 100;
    $response['message'] = 'PDF generated successfully.';
	
    $response['pdf_url'] = $path;
	$data = [
		"path"=>$path,
		"for"=>'international_report',
		"agency_id"=>$agency_id,
		"current_wallet_bal"=>number_format($deducted_amt, 2, '.', ''),
		"is_verified"=>$is_verified
		
	];

	$url = get_base_url() . '/new_direct.php';

            // Initialize cURL session
            $ch = curl_init($url);

            // Configure cURL options
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
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
	// echo json_encode($data);
} else {
    // PDF generation failed
    $response['success'] = false;
    $response['message'] = 'Failed to generate PDF.';
}



// if($is_verified=="yes"){

// $update_wallet = "UPDATE `agency_header_all` SET `current_wallet_bal`='$deducted_amt' WHERE `agency_id`='$agency_id'";
// $res_update = mysqli_query($mysqli, $update_wallet);

// $insert_wall="INSERT INTO `wallet_transaction_all` (`agency_id`, `verification_id`, `source_from`, `amount`, `closing_balance`, `date`, `status`, `purpose`) VALUES ('$agency_id', '1', '5', '$total', '$deducted_amt', '$system_date_time', 'success', 'verification')";
// $res_wall=mysqli_query($mysqli, $insert_wall);
// }
$insert_pan_payment = "INSERT INTO `direct_verification_details_all` (`direct_id`, `application_id`, `agency_id`, `verification_id`, `initiated_on`, `completed_on`, `activity_status`, `linked_table`, `deducted_base_amount`, `sgst_amount`, `cgst_amount`, `report_url`, `source_from`) VALUES ('$direct_id', '$application_id', '$agency_id', '$verification_id', '$system_date_time', '$system_date_time', '$status', 'direct_international_passport_details_all', '$base_amount', '$sgst_amt', '$cgst_amt', '$path', '$source_from')
";
$res_pan = mysqli_query($mysqli, $insert_pan_payment);

// Insert into `direct_pan_details_all`
  $insert_aadhar_detail = "INSERT INTO `direct_international_passport_details_all` (`id`, `direct_id`, `application_id`, `agency_id`, `admin_id`, `initiated_on`, `completed_on`, `activity_status`, `passport_number`, `surname`, `name`, `gender`, `dob`, `place_of_birth`, `father_name`, `mother_name`, `spouse_name`, `address`, `republic_of_india`, `date_of_issue`, `date_of_expiry`, `place_of_issue`, `country_code`, `nationality`, `passport_type`, `file_number`, `cover_photo`, `user_photo`, `front_photo`, `back_photo`, `visa_photo`, `landing_date`, `visa_validity`, `country`, `visa_type`) VALUES (NULL, '$direct_id', '$application_id', '$agency_id', '$admin_id', '$system_date_time', '$system_date_time', '$status', '$passport_no', '$surname', '$given_name', '', '$dob', '', '$father_name', '', '', '$address', '', '', '$expiry_date_save', '', '', '', '', '$file_number', '$cover_img', '$user_img', '$front_img', '$back_img', '$visa_img', '$landing_date_save', '$visa_validity_save', '$country', '$visa_type');
";
$res_pan_detail = mysqli_query($mysqli, $insert_aadhar_detail);

// Insert into `edited_direct_pan_details_all` if edited
if ($is_edited == "yes") {
    $insert_edited_pan_detail = "INSERT INTO `edited_direct_international_passport_details_all` (`id`, `direct_id`, `application_id`, `agency_id`, `admin_id`, `initiated_on`, `completed_on`, `activity_status`, `passport_number`, `surname`, `name`, `gender`, `dob`, `place_of_birth`, `father_name`, `mother_name`, `spouse_name`, `address`, `republic_of_india`, `date_of_issue`, `date_of_expiry`, `place_of_issue`, `country_code`, `nationality`, `passport_type`, `file_number`, `cover_photo`, `user_photo`, `front_detail_photo`, `back_detail_photo`, `visa_photo`, `landing_date`, `visa_validity`, `country`, `visa_type`) VALUES (NULL, '$direct_id', '$application_id', '$agency_id', '$admin_id', '$system_date_time', '$system_date_time', '$status', '$edited_passport_no', '$edited_surname', '$edited_given_name', '', '$edited_dob_save', '', '$edited_father_name', '', '', '$edited_address', '', '', '$edited_expiry_date_save', '', '', '', '', '$edited_file_numbers', '$cover_img', '$user_img', '$front_img', '$back_img', '$visa_img', '$edited_landing_date_save', '$edited_visa_validity_save', '$edited_country', '$edited_visa_type');
    ";
    $res_edited_pan_detail = mysqli_query($mysqli, $insert_edited_pan_detail);
}
        
function savePDF1($output_pdf, $direct_id, $agency_id)
{
	// FTP server credentials
	$ftp_server = '199.79.62.21';
	$ftp_username = 'centralwp@mounarchtech.com';
	$ftp_password = 'k=Y#oBK{h}OU';

	// Remote directory path
	$remote_base_dir = "/verification_data/voco_xp/";

	// Nested directory structure to be created
	$new_directory_path = "$agency_id/$direct_id/verification_report/";

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
	$file_name = $direct_id . '.pdf';

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
	$base_url = "https://mounarchtech.com/central_wp/verification_data/voco_xp/$agency_id/$direct_id/verification_report";
	$path = $base_url . '/' . $file_name;
	return $path;
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
function save_doc_photo($output_pdf, $direct_id, $agency_id)
{
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
	$base_url = "https://mounarchtech.com/central_wp/verification_data/voco_xp/$agency_id/$direct_id/doc_photo";
	$path = $base_url . '/' . $file_name;
	return $path;
}
?>
