<?php
require_once __DIR__ . '/vendor/autoload.php';

include 'connection.php';

ini_set('display_errors', 0);
$connection = connection::getInstance();
$mysqli = $connection->getConnection();
///////////////////////////////////////////////
$connection1 = database::getInstance();
$mysqli1 = $connection1->getConnection();

date_default_timezone_set('Asia/kolkata');
$system_date = date("y-m-d");
$system_date_time = date("Y-m-d H:i:s");


$visitor_id = $_POST['visitor_id'];
$emp_id = $_POST['emp_id'];
$agency_id = $_POST['agency_id'];
$front_img = $_POST['front_img'];
$back_img = $_POST['back_img'];

$name = $_POST['name'];
$dob = $_POST['dob'];
$address = $_POST['address'];
$gender = $_POST['gender'];
$addess1 = $_POST['addess1_aadhar'];
$aadhar_number = $_POST['aadhar_number']; 

$org_aadhar_name = $_POST['org_aadhar_name']; 
$org_date_of_birth = $_POST['org_date_of_birth']; 
$org_gender = $_POST['org_gender']; 
$org_pincode = $_POST['org_pincode']; 
 
 if($name==$org_aadhar_name)
 {
     $color = 'green';
    $name_match="match";
 }
 else
 {
     $color = 'red';
    $name_match="not match"; 
 }
 
 if($dob==$org_date_of_birth)
 {
     $color = 'green';
    $birth_date_match="match";
 }
 else
 {
     $color = 'red';
    $birth_date_match="not match"; 
 }

 if($gender==$org_gender)
 {
     $color = 'green';
    $gender_match="match";
 }
 else
 {
     $color = 'red';
    $gender_match="not match"; 
 }
if($address==$addess1)
 {
    $address_match="Match";
 }
 else
 {
    $address_match="Not Match";

 }
// HTML content with embedded image
 
$ismatch="";
if($name_match =="Match" && $birth_date_match=="Match" && $gender_match=="Match" && $address_match=="Match")
{
  $ismatch="ok=all";
  $atvy_status="1";
}
else
{
        if($name_match=="Match")
        {
             $color = 'green';
        }
        else
        {
            $color = 'red';
            $ismatch1="name@".$org_aadhar_name.'!'.$name;
        }
        if($birth_date_match=="Match")
        {
             $color = 'green';
        }
        else
        {
            $color = 'red';
            $ismatch2="dob@".$org_date_of_birth.'!'.$dob;
        }
        if($gender_match=="Match")
        {
             $color = 'green';
        }
        else
        {
            $color = 'red';
            $ismatch3="dob@".$org_gender.'!'.$gender;
        }

         if($address_match=="Match")
        {
             
        }
        else
        {
            $ismatch4="address@".$addess1.'!'.$address;
        }

       $ismatch=$ismatch1.",".$ismatch2.",".$ismatch3.",".$ismatch4;
       $atvy_status="2";
}
if($dob=="")
{
    $dob="NA";
}
else
{
    $dob=$dob;
}
 
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
  th,td{
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
               <h3 id="header"> AADHAR VERIFICATION REPORT</h3>
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
      
      <p style="font-weight: bold;">Provided Information</p>
      </td>
    </tr>
    <tr>
    <td scope="col"><span style="font-weight: bold;"> Name:</span> ' . $name. '</td>
    <td scope="col"><span style="font-weight: bold;">Aadhar Number: </span>' . $aadhar_number . '</td>
  </tr>
    <tr>
    <td scope="col"><span style="font-weight: bold;">Date Of Birth:</span> ' .$dob. '</td>

    
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
    <tr>
    <td scope="col" align="center">
    <p style="text-align: center; font-size:15px;">Front Image</p>
<br>
    <img src="' . $front_img . '" alt="Placeholder image" width="30%" />
    </td>
    <td scope="col" align="center">
    <p style="text-align: center; font-size:15px;">Back Image</p>
    <br>

    <img src="' . $back_img . '" alt="Placeholder image" width="30%" />

    </td>
  </tr>
 
</table>
<br><br>


<h1 id="first" ><u>Result</u></h1>

<p style="font-weight: bold;">Report Summary</p>

<span style="font-weight: bold;">This Report Information Genrated Against Aadhar Number: </span>' . $aadhar_number . ' 



<table style=" width:100%; border-collapse: collapse;" id="table">
<tr>
<td scope="col" style="width:50%"><span style="font-weight: bold;">Aadhar Number:</span> ' . $aadhar_number . '</td>

<td scope="col" style="width:50%"><span style="font-weight: bold;"> Name:</span> ' . strtoupper($org_aadhar_name) . '<br><span style="color:' . $color . ';">' . $name_match . '</span></td>
</tr>
<tr>
<td scope="col" style="width:50%"><span style="font-weight: bold;">Date Of Birth:</span> ' . date("d-m-Y", strtotime($org_date_of_birth)) . '<br><span style="color:' . $color1 . ';">' . $birth_date_match . '</span></td>


</table>

<hr>
<p style="margin-top:10px;"><span style="color:red;">*</span>Please note that the contents shown above is provided in good faith, we do not warrant that the information will be kept up to date, be true, accurate and not misleading.</p>

    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
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
 $path = savePDF1($output_pdf, $visitor_id, $agency_id,$emp_id);

$deducted_amt=$_POST['current_wallet_bal'];

// Define response array
$response = array();

// Check if the path is valid
if ($path !== false) {
	// PDF generated successfully
	$response['error_code'] = 100;
	$response['message'] = 'PDF generated successfully.';

	$response['pdf_url'] = $path;
	$data = [
		"path" => $path,
		"for" => 'aadhar_report',
        "agency_id" => $agency_id,
        "visitor_id" => $visitor_id,
		"emp_id" => $emp_id,
		"current_wallet_bal" => number_format($deducted_amt, 2, '.', '')

	];

	$url = get_base_url() . '/new_visitor.php';

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
	// $res[]=["pdf_url"=>$path, 'current_wallet_bal'=>number_format($deducted_amt, 2, '.', '')];

	// $data=["error_code"=>100, "message"=>"PDF generated successfully.", "data"=>$res];
	// echo json_encode($data);
} else {
	// PDF generation failed
	$response['success'] = false;
	$response['message'] = 'Failed to generate PDF.';
}



// if ($is_verified == "yes") {

// 	$update_wallet = "UPDATE `agency_header_all` SET `current_wallet_bal`='$deducted_amt' WHERE `agency_id`='$agency_id'";
// 	$res_update = mysqli_query($mysqli, $update_wallet);


// 	$insert_wall = "INSERT INTO `wallet_payment_transaction_all` (`id`, `agency_id`, `user_id`, `requested_from`, `purchase_type`, `verification_id`, `base_amount`, `cgst_amount`, `sgst_amount`, `transaction_on`, `transaction_id`, `line_type`, `quantity`, `settled_for`) VALUES (NULL, '$agency_id', '$direct_id', '2', '1', '$verification_id', '$base_amount', '$cgst_amt', '$sgst_amt', '$system_date_time', '', '1', '', '');";
// 	$res_wall = mysqli_query($mysqli, $insert_wall);
// }
 
// Insert into `direct_pan_details_all`
$insert_aadhar_detail = "INSERT INTO `visitor_aadhar_details_all` (`visitor_id`, `agency_id`, `agency_id`, `initiated_on`, `completed_on`, `activity_status`, `aadhar_number`, `name`, `dob`, `gender`, `address`, `front_photo`, `back_photo`, `user_photo`, `is_athenticate`, `aadhar_ambiguity`, `report_url`) VALUES ('$direct_id', '$application_id', '$agency_id', '$system_date_time', '$system_date_time', '$status', '$aadhar_number', '$name', '$dob1', '$gender', '$address', '$front_img', '$back_img', '$user_img', '$ismatch', '$ismatch')";
$res_pan_detail = mysqli_query($mysqli, $insert_aadhar_detail);

 

function savePDF1($output_pdf, $visitor_id, $agency_id,$emp_id)
{
	// FTP server credentials
	$ftp_server = '199.79.62.21';
	$ftp_username = 'centralwp@mounarchtech.com';
	$ftp_password = 'k=Y#oBK{h}OU';

	// Remote directory path
	$remote_base_dir = "/verification_data/voco_xp/";

	// Nested directory structure to be created
	$new_directory_path = "$agency_id/visitor/$emp_id/aadhar_report/";

	// Initialize cURL session for FTP
	$curl = curl_init();

	// Set cURL options for FTP
	curl_setopt_array(
		$curl,
		array(
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
	$file_name = $visitor_id . '.pdf';

	// Construct the full file path on the remote server
	$file_path = $remote_dir_path . $file_name;

	// Save the PDF to a temporary file
	$temp_file = tempnam(sys_get_temp_dir(), 'pdf');
	file_put_contents($temp_file, $output_pdf);

	// Initialize cURL session for file upload
	$curl_upload = curl_init();

	// Set cURL options for file upload
	curl_setopt_array(
		$curl_upload,
		array(
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
	$base_url = "https://mounarchtech.com/central_wp/verification_data/voco_xp/$agency_id/visitor/$emp_id/aadhar_report";
	$path = $base_url . '/' . $file_name;
	return $path;
}
function save_user_photo($output_pdf, $visitor_id, $agency_id)
{
	// FTP server credentials
	$ftp_server = '199.79.62.21';
	$ftp_username = 'centralwp@mounarchtech.com';
	$ftp_password = 'k=Y#oBK{h}OU';

	// Remote directory path
	$remote_base_dir = "/verification_data/voco_xp/";

	// Nested directory structure to be created
	$new_directory_path = "$agency_id/$visitor_id/user_photo/";

	// Initialize cURL session for FTP
	$curl = curl_init();

	// Set cURL options for FTP
	curl_setopt_array(
		$curl,
		array(
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
	$file_name =  $visitor_id . '.jpg';

	// Construct the full file path on the remote server
	$file_path = $remote_dir_path . $file_name;

	// Save the PDF to a temporary file
	$temp_file = tempnam(sys_get_temp_dir(), 'jpg');
	file_put_contents($temp_file, $output_pdf);

	// Initialize cURL session for file upload
	$curl_upload = curl_init();

	// Set cURL options for file upload
	curl_setopt_array(
		$curl_upload,
		array(
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
	$base_url = "https://mounarchtech.com/central_wp/verification_data/voco_xp/$agency_id/visitor/$emp_id/user_photo";
	$path = $base_url . '/' . $file_name;
	return $path;
}
function save_doc_photo($output_pdf, $visitor_id, $agency_id,$emp_id)
{
	// FTP server credentials
	$ftp_server = '199.79.62.21';
	$ftp_username = 'centralwp@mounarchtech.com';
	$ftp_password = 'k=Y#oBK{h}OU';

	// Remote directory path
	$remote_base_dir = "/verification_data/voco_xp/";

	// Nested directory structure to be created
	$new_directory_path = "$agency_id/$visitor_id/$emp_id/doc_photo/";

	// Initialize cURL session for FTP
	$curl = curl_init();

	// Set cURL options for FTP
	curl_setopt_array(
		$curl,
		array(
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
	$file_name =  $visitor_id . '.jpg';

	// Construct the full file path on the remote server
	$file_path = $remote_dir_path . $file_name;

	// Save the PDF to a temporary file
	$temp_file = tempnam(sys_get_temp_dir(), 'jpg');
	file_put_contents($temp_file, $output_pdf);

	// Initialize cURL session for file upload
	$curl_upload = curl_init();

	// Set cURL options for file upload
	curl_setopt_array(
		$curl_upload,
		array(
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
	$base_url = "https://mounarchtech.com/central_wp/verification_data/voco_xp/$agency_id/visitor/$emp_id/doc_photo";
	$path = $base_url . '/' . $file_name;
	return $path;
}

 

?>