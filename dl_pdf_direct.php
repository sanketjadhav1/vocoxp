<?php
require_once __DIR__ . '/vendor/autoload.php'; 
include 'connection.php';

// Database connections
$connection = connection::getInstance();
$mysqli = $connection->getConnection();

$connection1 = database::getInstance();
$mysqli1 = $connection1->getConnection();

date_default_timezone_set('Asia/Kolkata');
$system_date_time = date("Y-m-d H:i:s");

// Get POST parameters
$direct_id = $_POST['direct_id'];
$application_id = $_POST['application_id'];
$agency_id = $_POST['agency_id'];
$admin_id = $_POST['admin_id'] ?: $_POST['agency_id'];

$driving_licence_no = $_POST['driving_licence_no'];
$edited_driving_licence_no = $_POST['edited_driving_licence_no'];
$name = $_POST['name'];
$edited_name = $_POST['edited_name'];
$date_of_birth = date("d-m-Y", strtotime($_POST['date_of_birth']));
$dob = date("Y-m-d", strtotime($_POST['date_of_birth']));
$edited_date_of_birth = date("d-m-Y", strtotime($_POST['edited_date_of_birth']));
$edited_date_of_birth_save = date("Y-m-d", strtotime($_POST['edited_date_of_birth']));
$father_name = $_POST['father_name'];
$dependentName = $_POST['dependentName'];
$edited_father_name = $_POST['edited_father_name'];
$address = $_POST['address'];
$address1 = $_POST['address1'];
$edited_address = $_POST['edited_address'];
$valid_till = $_POST['valid_till'];
$edited_valid_till = $_POST['edited_valid_till'];
$date_of_issue = date("Y-m-d", strtotime($_POST['date_of_issue']));
$edited_date_of_issue = date("Y-m-d", strtotime($_POST['edited_date_of_issue']));
$date_of_expiry = date("Y-m-d", strtotime($_POST['date_of_expiry']));
$edited_date_of_expiry = date("Y-m-d", strtotime($_POST['edited_date_of_expiry']));
$vehicle_class = $_POST['vehicle_class'];
$edited_vehicle_class = $_POST['edited_vehicle_class'];
$state_name = $_POST['state_name'];
$edited_state_name = $_POST['edited_state_name'];
$blood_group = $_POST['blood_group'];
$edited_blood_group = $_POST['edited_blood_group'];
$non_transport_issued_on_date = date('d-m-Y', strtotime($_POST['non_transport_issued_on_date']));
$non_transport_valid_till_date = date('d-m-Y', strtotime($_POST['non_transport_valid_till_date']));
$transport_issued_on_date = date('d-m-Y', strtotime($_POST['transport_issued_on_date']));
$transport_valid_till_date = date('d-m-Y', strtotime($_POST['transport_valid_till_date']));

$category = $_POST['category'];
$verification_id = $_POST['verification_id'];
$base_amount = $_POST['base_amount'];
$sgst_amount = $_POST['sgst_amount'];
$cgst_amount = $_POST['cgst_amount'];
$front_img = $_POST['front_img'];
$back_img = $_POST['back_img'];
$user_img = $_POST['user_img'];

$is_verified = $_POST['is_verified'];
$is_edited = $_POST['is_edited'];
$name_match = $_POST['name_match'];
$dob_match = $_POST['dob_match'];
$valid_match = $_POST['valid_match'];
$valid_match_i = $_POST['valid_match_i'];
$vehicle_match = $_POST['vehicle_match'];
$father_match = $_POST['father_match'];
$source_from = $_POST['source_from'];
$res_name = $_POST['docs_name'];
$res_dob = $_POST['res_dob'];

// Fetch wallet balance
$fetch_wallet = "SELECT `current_wallet_bal` FROM `agency_header_all` WHERE `agency_id`='$agency_id'";
$res_wallet = mysqli_query($mysqli, $fetch_wallet);
$arr_wallet = mysqli_fetch_assoc($res_wallet);

if ($is_verified == "yes") {
    $status = 1;
    $gst = $sgst_amount + $cgst_amount;
    $sgst_amt = $base_amount * $sgst_amount / 100;
    $cgst_amt = $base_amount * $cgst_amount / 100;
    $grand_total = $base_amount * $gst / 100;
    $deducted_amt = $arr_wallet['current_wallet_bal'] - $grand_total - $base_amount;
    $total = $base_amount + $sgst_amt + $cgst_amt;
} else {
    $status = 2;
}

$color = $name_match == 'Match' ? 'green' : 'red';
$color1 = $dob_match == 'Match' ? 'green' : 'red';
$color2 = $valid_match == 'Match' ? 'green' : 'red';
$color3 = $vehicle_match == 'Match' ? 'green' : 'red';
$show_name = $is_edited == "yes" ? $edited_name : $name;
$show_dob = $is_edited == "yes" ? $edited_date_of_birth : $date_of_birth;

if($non_transport_valid_till_date==$valid_till)
{
   $valid_match1="Match";
}
else
{
   $valid_match1="Not Match";

}
$ismatch="";
if($name_match=="Match" && $dob_match=="Match" && $valid_match1=="Match" && $vehicle_match=="Match"  && $address_match=="Match" && $father_match=="Match" && $valid_match_i=="Match")
{
  $ismatch="ok=all";
  $atvy_status="1";
}
else{
       if($name_match=="Match")
        {
             
        }
        else
        {
            $ismatch1="name@".$res_name.'!'.$show_name;
        }

        if($dob_match=="Match")
        {
             
        }
        else
        {
            $ismatch2="dob@".$res_dob.'!'.$show_dob;
        }

          if($valid_match1=="Match")
        {
             
        }
        else
        {
            $ismatch3="date_of_expiry@".$non_transport_valid_till_date.'!'.$valid_till;
        }
          if($vehicle_match=="Match")
        {
             
        }
        else
        {
            $ismatch4"vehicle_class@".$category.'!'.$vehicle_class;
        }
           if($address_match=="Match")
        {
             
        }
        else
        {
            $ismatch5"address@".$address1.'!'.$address;
        }
            if($father_match=="Match")
        {
             
        }
        else
        {
            $ismatch5"father_name@".$dependentName.'!'.$father_name;
        }
         if($valid_match_i=="Match")
        {
             
        }
        else
        {
            $ismatch6"date_of_issue@".$non_transport_issued_on_date.'!'.$date_of_issue;
        }
         $ismatch=$ismatch1.",".$ismatch2.",".$ismatch3.",".$ismatch4.",".$ismatch5.",".$ismatch6;

  // $ismatch="name=".$name_match.","."dob=".$dob_match.","."valid=".$valid_match.","."vehicle=".$vehicle_match;
  $atvy_status="2";
}

if($is_verified=="bulkyes")
{
   $admin_id=$_POST["admin_id"];
    $agency_id=$_POST["agency_id"];
    $bulk_id=$_POST["bulk_id"];
    $base_amount=$_POST['base_amount'] ?? '';
   $sgst_amount=$_POST['sgst_amount'] ?? '';
   $cgst_amount=$_POST['cgst_amount'] ?? '';

   // HTML content with embedded image
$html = '<html>
<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
   <style>
      #firstDiv {
         padding: 15px;
         background: #889dd1;
      }
      #header {
         margin-top: 20px !important;
         font-weight: bold;
         font-size: 20px;
      }
      #first, #second {
         font-size: 23px;
         margin-top: -10px;
      }
      #table {
         margin-top: 15px;
      }
      th, td {
         line-height: 24px;
      }
   </style>
</head>
<body>
<div id="firstDiv">
   <table style="width:100%; border-collapse: collapse;">
      <tr>
         <td width="70%">
            <h3 id="header">DRIVING LICENCE VERIFICATION REPORT</h3>
         </td>
         <td width="30%" align="left">
            <img src="vendor/microintegrated_logo.png" alt="Logo" width="20%" />
         </td>
      </tr>
   </table>
</div>
<table style="width:100%; border-collapse: collapse;" id="table">
   <tr>
      <td colspan="2">
         <p style="font-weight: bold;">Provided Information</p>
      </td>
   </tr>
   <tr>
      <td><span style="font-weight: bold;">Name:</span> ' . $show_name . '</td>
      <td><span style="font-weight: bold;">Driving Licence Number:</span> ' . $driving_licence_no . '</td>
   </tr>
   <tr>
      <td colspan="2"><span style="font-weight: bold;">Date Of Birth:</span> ' . $show_dob . '</td>
   </tr>
</table>
<hr>
<br>
<table style="width:100%; border-collapse: collapse;">
   <tr>
      <td colspan="2">
         <h1 id="second"><u>Provided Document</u></h1><br><br>
      </td>
   </tr>
   <tr>
      <td colspan="2" align="center">
         <img src="' . $front_img . '" alt="Front Image" width="20%" />
      </td>
   </tr>
</table> 
<h1 id="first"><u>Result</u></h1> 
<p style="font-weight: bold">Report Summary</p>
<span style="font-weight: bold;">This Report Information Generated Against Driving Licence Number:</span> ' . $driving_licence_no . ' 
<table style="width:100%; border-collapse: collapse;" id="table">
   <tr>
      <td style="width:50%"><span style="font-weight: bold;">Driving Licence Number:</span>  ' . $driving_licence_no . '</td>
      <td style="width:50%"><span style="font-weight: bold;">Category:</span> ' . $category . '</td>
   </tr>
   <tr>
      <td style="width:50%"><span style="font-weight: bold;">Name:</span> ' . $res_name . '<br><span style="color:' . $color . '"> ' . $name_match . '</span></td>
      <td style="width:50%"><span style="font-weight: bold;">Transport Issued On:</span> ' . $transport_issued_on_date . '<br><span style="font-weight: bold;">Valid Till:</span> ' . $valid_till . '</td>
   </tr>
   <tr>
      <td style="width:50%"><span style="font-weight: bold;">Date Of Birth:</span> ' . $res_dob . '<br><span style="color:' . $color1 . '"> ' . $dob_match . '</span></td>
      <td style="width:50%"><span style="font-weight: bold;">Non-Transport Issued On:</span> ' . $non_transport_issued_on_date . '<br><span style="font-weight: bold;">Valid Till:</span> ' . $non_transport_valid_till_date . '</td>
   </tr>
</table>
</body>
</html>';



// Initialize mPDF
$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4']);


 // Write HTML content to mPDF
$mpdf->WriteHTML($html);

// Output PDF to a variable
$output_pdf = $mpdf->Output('', 'S');

// Save PDF to a file
  $path = savePDF2($output_pdf, $admin_id, $agency_id,$bulk_id);
  
    
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
      "for"=>'dl_report',
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
                    $response;
              }
  
              // Close the cURL session
              curl_close($ch); 
   
} else {
    // PDF generation failed
    $response['success'] = false;
    $response['message'] = 'Failed to generate PDF.';
   echo json_encode($response);
}



 $query="INSERT INTO `end_user_verification_transaction_all`(`id`, `end_user_id`, `aadhar_number`, `first_name`, `last_name`, `pan_number`, `dl_number`, `voter_number`, `passport_number`, `aadhar_ambiguity`, `pan_ambiguity`, `dl_ambiguity`, `voter_ambiguity`, `passport_ambiguity`, `weblink_id`, `agency_id`, `document_type`, `father_name`, `mother_name`, `spouse_name`, `gender`, `dob`, `blood_group`, `address`, `country_code`, `state_name`, `nationality`, `front_photo`, `back_photo`, `user_photo`, `cover_photo`, `date_of_issue`, `date_of_expiry`, `place_of_issue`, `classes_of_vehicle`, `polling_details`, `republic_of_india`, `passport_type`, `file_number`, `initiated_on`, `completed_on`, `activity_status`,`report_url`) VALUES ('','$admin_id','','$name','','','$driving_licence_no','','','','','$ismatch','','','$bulk_id','$agency_id','$verification_id','','','','','$dob','','','','','','','','','','','$date_of_expiry','','$vehicle_class','','','','','$system_date_time','$system_date_time','$atvy_status','$path')";
 $res=mysqli_query($mysqli,$query);
// seelct query bulk_end_user_transaction_all verification_done `end_user_id`='$admin_id' AND `weblink_id`='$bulk_id' / empty hoga to 2 aage not empty implode (,)
   $pan_fetch_sql="SELECT * FROM `bulk_end_user_transaction_all` WHERE  `end_user_id`='".$admin_id."' AND `bulk_id`='".$bulk_id."'";
   $pan_fetch_res=mysqli_query($mysqli,$pan_fetch_sql);
   $pan_fetch_array = mysqli_fetch_assoc($pan_fetch_res);
  $verification_done= $pan_fetch_array["verification_done"];
    $value="DVF-00004";
  if (!empty($verification_done)) {
    $verification_done_array = explode(",", $verification_done);

    // Only append if "2" is not already in the array
    if (!in_array($value, $verification_done_array)) {
        $verification_done .= "," . $value;
    }
} else {
    $verification_done = $value; // Set to "2" if empty
}
    $pan_res1="UPDATE `bulk_end_user_transaction_all` SET `verification_done`='".$verification_done."' WHERE  `end_user_id`='".$admin_id."' AND `bulk_id`='".$bulk_id."'";
    $pan_query=mysqli_query($mysqli,$pan_res1);
    // SELECT * FROM `bulk_end_user_transaction_all`
    $fetch_trns_check = "SELECT * FROM `bulk_end_user_transaction_all` WHERE `end_user_id`='".$admin_id."' AND `bulk_id`='".$bulk_id."'";
    $res_trns_check = mysqli_query($mysqli, $fetch_trns_check);
    $arr_trns_check = mysqli_fetch_assoc($res_trns_check);
    if($arr_trns_check["payment_from"]==1)
    {
      $base_amount=($sgst_amount*1)+($cgst_amount*1)+($base_amount*1);
      $deducted_amt=$arr_wallet['current_wallet_bal'] - $base_amount;
       $update_wallet = "UPDATE `agency_header_all` SET `current_wallet_bal`='$deducted_amt' WHERE `agency_id`='$agency_id'";
       $res_update = mysqli_query($mysqli, $update_wallet); 
       $transaction_id = 'txn_' . time() . random_int(100000, 999999);
        $wallet_sql="INSERT INTO `wallet_payment_transaction_all` (`agency_id`, `user_id`, `requested_from`, `purchase_type`, `base_amount`, `cgst_amount`, `sgst_amount`, `transaction_on`, `transaction_id`, `line_type`, `ref_transaction_id`,`verification_id`) VALUES ('$agency_id','$admin_id','3','1','$base_amount','$cgst_amount','$sgst_amount','$system_date_time','$transaction_id','1','$bulk_id','$verification_id')";
         $wallet_query=mysqli_query($mysqli,$wallet_sql);
    }

}
// HTML content with embedded image
$html = '<html>
<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
   <style>
      #firstDiv {
         padding: 15px;
         background: #889dd1;
      }
      #header {
         margin-top: 20px !important;
         font-weight: bold;
         font-size: 20px;
      }
      #first, #second {
         font-size: 23px;
         margin-top: -10px;
      }
      #table {
         margin-top: 15px;
      }
      th, td {
         line-height: 24px;
      }
   </style>
</head>
<body>
<div id="firstDiv">
   <table style="width:100%; border-collapse: collapse;">
      <tr>
         <td width="70%">
            <h3 id="header">DRIVING LICENCE VERIFICATION REPORT</h3>
         </td>
         <td width="30%" align="left">
            <img src="vendor/microintegrated_logo.png" alt="Logo" width="20%" />
         </td>
      </tr>
   </table>
</div>
<table style="width:100%; border-collapse: collapse;" id="table">
   <tr>
      <td colspan="2">
         <p style="font-weight: bold;">Provided Information</p>
      </td>
   </tr>
   <tr>
      <td><span style="font-weight: bold;">Name:</span> ' . $show_name . '</td>
      <td><span style="font-weight: bold;">Driving Licence Number:</span> ' . $driving_licence_no . '</td>
   </tr>
   <tr>
      <td colspan="2"><span style="font-weight: bold;">Date Of Birth:</span> ' . $show_dob . '</td>
   </tr>
</table>
<hr>
<br>
<table style="width:100%; border-collapse: collapse;">
   <tr>
      <td colspan="2">
         <h1 id="second"><u>Provided Document</u></h1><br><br>
      </td>
   </tr>
   <tr>
      <td colspan="2" align="center">
         <img src="' . $front_img . '" alt="Front Image" width="20%" />
      </td>
   </tr>
</table> 
<h1 id="first"><u>Result</u></h1> 
<p style="font-weight: bold">Report Summary</p>
<span style="font-weight: bold;">This Report Information Generated Against Driving Licence Number:</span> ' . $driving_licence_no . ' 
<table style="width:100%; border-collapse: collapse;" id="table">
   <tr>
      <td style="width:50%"><span style="font-weight: bold;">Driving Licence Number:</span>  ' . $driving_licence_no . '</td>
      <td style="width:50%"><span style="font-weight: bold;">Category:</span> ' . $category . '</td>
   </tr>
   <tr>
      <td style="width:50%"><span style="font-weight: bold;">Name:</span> ' . $res_name . '<br><span style="color:' . $color . '"> ' . $name_match . '</span></td>
      <td style="width:50%"><span style="font-weight: bold;">Transport Issued On:</span> ' . $transport_issued_on_date . '<br><span style="font-weight: bold;">Valid Till:</span> ' . $valid_till . '</td>
   </tr>
   <tr>
      <td style="width:50%"><span style="font-weight: bold;">Date Of Birth:</span> ' . $res_dob . '<br><span style="color:' . $color1 . '"> ' . $dob_match . '</span></td>
      <td style="width:50%"><span style="font-weight: bold;">Non-Transport Issued On:</span> ' . $non_transport_issued_on_date . '<br><span style="font-weight: bold;">Valid Till:</span> ' . $non_transport_valid_till_date . '</td>
   </tr>
</table>
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
      "for"=>'dl_report',
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
	
} else {
    // PDF generation failed
    $response['success'] = false;
    $response['message'] = 'Failed to generate PDF.';
	echo json_encode($response);
}

// Echo JSON response


if($is_verified=="yes"){

$update_wallet = "UPDATE `agency_header_all` SET `current_wallet_bal`='$deducted_amt' WHERE `agency_id`='$agency_id'";
$res_update = mysqli_query($mysqli, $update_wallet);

$insert_wall="INSERT INTO `wallet_payment_transaction_all` (`id`, `agency_id`, `user_id`, `requested_from`, `purchase_type`, `verification_id`, `base_amount`, `cgst_amount`, `sgst_amount`, `transaction_on`, `transaction_id`, `line_type`, `quantity`, `settled_for`) VALUES (NULL, '$agency_id', '$direct_id', '2', '1', '$verification_id', '$base_amount', '$cgst_amt', '$sgst_amt', '$system_date_time', '', '1', '', '');";
$res_wall=mysqli_query($mysqli, $insert_wall);
}
$insert_pan_payment = "INSERT INTO `direct_verification_details_all` (`direct_id`, `application_id`, `agency_id`, `verification_id`, `initiated_on`, `completed_on`, `activity_status`, `linked_table`, `deducted_base_amount`, `sgst_amount`, `cgst_amount`, `report_url`, `source_from`,`ambiguity`) VALUES ('$direct_id', '$application_id', '$agency_id', '$verification_id', '$system_date_time', '$system_date_time', '$status', 'direct_dl_details_all', '$base_amount', '$sgst_amt', '$cgst_amt', '$path', '$source_from','$ismatch')
";
$res_pan = mysqli_query($mysqli, $insert_pan_payment);

// Insert into `direct_pan_details_all`

 $insert_pan_detail = "INSERT INTO `direct_dl_details_all` (`id`, `direct_id`, `application_id`, `agency_id`, `admin_id`, `initiated_on`, `completed_on`, `activity_status`, `dl_number`, `name`, `father_name`, `address`, `dob`, `date_of_expiry`, `date_of_issue`, `classes_of_vehicle`, `state_name`, `blood_group`, `user_photo`, `front_photo`, `back_photo`) VALUES (NULL, '$direct_id', '$application_id', '$agency_id', '$admin_id', '$system_date_time', '$system_date_time', '1', '$driving_licence_no', '$name', '$father_name', '$address', '$dob', '$date_of_expiry', '$date_of_issue', '$vehicle_class', '$state_name', '$blood_group', '$user_img', '$front_img', '$back_img');
";

$res_pan_detail = mysqli_query($mysqli, $insert_pan_detail);

// Insert into `edited_direct_pan_details_all` if edited
if ($is_edited == "yes") {
    $insert_edited_pan_detail = "INSERT INTO `edited_direct_dl_details_all` (`id`, `direct_id`, `application_id`, `agency_id`, `admin_id`, `initiated_on`, `completed_on`, `activity_status`, `dl_number`, `name`, `father_name`, `address`, `dob`, `date_of_expiry`, `date_of_issue`, `classes_of_vehicle`, `state_name`, `blood_group`, `user_photo`, `front_photo`, `back_photo`) VALUES (NULL, '$direct_id', '$application_id', '$agency_id', '$admin_id', '$system_date_time', '$system_date_time', '1', '$edited_driving_licence_no', '$edited_name', '$edited_father_name', '$edited_address', '$edited_date_of_birth_save', '$edited_date_of_expiry_save', '$edited_date_of_issue', '$edited_vehicle_class', '$edited_state_name', '$edited_blood_group', '$user_img', '$front_img', '$back_img');
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

function savePDF2($output_pdf, $direct_id, $agency_id,$bulk_id)
{
   // FTP server credentials
   $ftp_server = '199.79.62.21';
   $ftp_username = 'centralwp@mounarchtech.com';
   $ftp_password = 'k=Y#oBK{h}OU';

   // Remote directory path
   $remote_base_dir = "/verification_data/voco_xp/";

   // Nested directory structure to be created
   $new_directory_path = "$agency_id/Weblink/$bulk_id/$direct_id/dl_report/";

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
   $base_url = "https://mounarchtech.com/central_wp/verification_data/voco_xp/$agency_id/Weblink/$bulk_id/$direct_id/dl_report";
   $path = $base_url . '/' . $file_name;
   return $path;
}


?>
