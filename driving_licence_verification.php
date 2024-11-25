<?php
// Suppress deprecation notices
// error_reporting(E_ALL & ~E_DEPRECATED);
// ini_set('display_errors', 1); 

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

$is_name_match=$_POST['is_name_match'];
$is_date_of_birth_match=$_POST['is_date_of_birth_match'];
// $is_name_match=$_POST['is_name_match'];
// $is_date_of_birth_match=$_POST['is_date_of_birth_match'];
$name=$_POST['name'];
$contact_no=$_POST['contact_no'];
$as_per_doc_name=$_POST['as_per_doc_name'];
$driving_licence_no=$_POST['driving_licence_no'];
$date_of_birth=$_POST['date_of_birth'];
$driving_licence_picture=$_POST['driving_licence_picture'];
$category=$_POST['category'];
$docs_name=$_POST['docs_name']; 

$transport_issued_on_date = $_POST['transport_issued_on_date'] != '' ? date("d-m-Y", strtotime($_POST['transport_issued_on_date'])) : "NA";

$transport_valid_till_date=$_POST['transport_valid_till_date']!=''?date("d-m-Y", strtotime($_POST['transport_issued_on_date'])):"NA";

$doc_date_of_birth= $_POST['doc_date_of_birth']!=$date_of_birth?date("d-m-Y", strtotime($date_of_birth)):$_POST['doc_date_of_birth'];

$non_transport_issued_on_date=$_POST['non_transport_issued_on_date']!=''? date("d-m-Y", strtotime($_POST['non_transport_issued_on_date'])):"NA";

$non_transport_valid_till_date=$_POST['non_transport_valid_till_date']!=''?date("d-m-Y", strtotime($_POST['non_transport_valid_till_date'])):"NA";

$agency_id=$_POST['agency_id'];
$member_id=$_POST['member_id'];
$specification_id=$_POST['specification_id'];
$request_id=$_POST['request_id'];
$application_id=$_POST['application_id'];




($is_name_match=='Yes')? $color='green': $color='red';
($is_date_of_birth_match=='Yes')? $color1='green': $color1='red';
($is_name_match=='Yes')? $Match='Match': $Match='Not Match';
($is_date_of_birth_match=='Yes')? $Match1='Match': $Match1='Not Match';


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
  margin-top:-10px;

}
  #table{
    margin-top:15px;
  }
  th,td{
    line-height: 24px;
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
               <h3 id="header"> DRIVING LICENCE VERIFICATION REPORT</h3>
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
      <td scope="col"><span style="font-weight: bold;">Member Name: </span>'.$name.'</td>
      <td scope="col"><span style="font-weight: bold;">Contact Number: </span>'.$contact_no.'</td>
    </tr>
    <tr>
      <td scope="col" colspan="2">
      <hr>
      <p style="font-weight: bold;">Provided Information</p>
      </td>
    </tr>
    <tr>
    <td scope="col"><span style="font-weight: bold;">Name as per Document: </span>'.$as_per_doc_name.'</td>
    <td scope="col"><span style="font-weight: bold;">Driving Licence Number:</span> '.$driving_licence_no.'</td>
  </tr>
  <tr>
  <td scope="col" colspan="2"><span style="font-weight: bold;">Date Of Birth:</span> '. $date_of_birth.'</td>
 
</tr>
</table>
<hr>

<br>

<table style=" width:100%; border-collapse: collapse;">
   
    <tr>
      <td scope="col" colspan="2">
      <h1 id="second"><u>Provided Document</u></h1>
      <br><br>
      </td>
    </tr>
    <tr>
    <td scope="col" colspan="2" align="center">
 
    <img src="'.$driving_licence_picture.'" alt="Placeholder image" width="20%" />
    </td>
    
  </tr>
 
</table> 
<h1 id="first" ><u>Result</u></h1> 

<p style="font-weight: bold">Report Summary</p>
<span style="font-weight: bold;">This Report Information Genrated Against Driving Licence Number: </span> '.$driving_licence_no.' 
<table style="width:100%; border-collapse: collapse;" id="table">
    <tr>
        <td scope="col" style="width:50%"><span style="font-weight: bold;">Driving Licence Number:</span>  '.$driving_licence_no.'</td>
        <td scope="col" style="width:50%"><span style="font-weight: bold;">Category:</span> '. $category.'</td>
    </tr>
    <tr>
        <td scope="col" style="width:50%"><span style="font-weight: bold;">Member Name:</span> '. $docs_name.'<br><span style="color:'. $color.'"> '.$Match.'</span></td>
        <td scope="col" style="width:50%"><span style="font-weight: bold;">Transport Issued On:</span>      '.$transport_issued_on_date.'<br><span style="font-weight: bold;">Valid Till:</span> '. $transport_valid_till_date.'</td>
    </tr>
    <tr>
        <td scope="col" style="width:50%"><span style="font-weight: bold;">Date Of Birth:</span> '. $doc_date_of_birth.'<br><span style="color:'. $color1.'"> '. $Match1.'</span></td>
        <td scope="col" style="width:50%"><span style="font-weight: bold;">Non-Transport Issued On:</span> '.$non_transport_issued_on_date.'<br><span style="font-weight: bold;">Valid Till:</span> '. $non_transport_valid_till_date.'</td>
    </tr>
</table>

<hr>
<p><span style="color:red;">*</span> Please note that the contents shown above are provided in good faith. We do not warrant that the information will be kept up to date, be true, accurate, and not misleading.</p>

    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

   
  </body>
</html>



';

// Initialize mPDF
$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4']);


 // Write HTML content to mPDF
$mpdf->WriteHTML($html);

// Output PDF to a variable
$output_pdf = $mpdf->Output('', 'S');

// Save PDF to a file
   $path = savePDF1($output_pdf, $member_id, $agency_id, "dl");
     // Define response array
$response = array();

// Check if the path is valid
if ($path !== false) {
    // PDF generated successfully
    $response['success'] = true;
    $response['message'] = 'PDF generated successfully.';
    $response['path'] = $path;
} else {
    // PDF generation failed
    $response['success'] = false;
    $response['message'] = 'Failed to generate PDF.';
}

// Echo JSON response
echo json_encode($response);


  $sql = "SELECT `table_name` FROM `verification_header_all` WHERE `verification_id`='$specification_id'";
      $res_sql = mysqli_query($mysqli1, $sql);
      if (mysqli_num_rows($res_sql) > 0) {
        $row = mysqli_fetch_assoc($res_sql);
        $table_name = $row['table_name'];

   $sql1 = "UPDATE `$table_name` 
                   SET `verification_report` = '$path',
                  `verification_status` = '2',
                  `modified_on` = '" . $system_date_time . "'
                  WHERE `request_id` = '$request_id' 
                  AND `application_id` = '$application_id' 
                  AND `person_id` = '$member_id' 
                  AND `verification_id` = '$specification_id'";
        $res_sql1 = mysqli_query($mysqli1, $sql1);

      }



function savePDF1($output_pdf, $member_id, $agency_id, $pdf_for)
{
	// FTP server credentials
	$ftp_server = '199.79.62.21';
	$ftp_username = 'centralwp@mounarchtech.com';
	$ftp_password = 'k=Y#oBK{h}OU';

	// Remote directory path
	$remote_base_dir = "/verification_data/$pdf_for/voco_xp/";

	// Nested directory structure to be created
	$new_directory_path = "$agency_id/$member_id/";

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
	$file_name = $pdf_for . date("YmdHis") . '.pdf';

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
	$base_url = "https://mounarchtech.com/central_wp/verification_data/$pdf_for/voco_xp/$agency_id/$member_id";
	$path = $base_url . '/' . $file_name;
	return $path;
}
?>