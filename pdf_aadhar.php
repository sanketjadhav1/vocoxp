<?php
// Suppress deprecation notices
// error_reporting(E_ALL & ~E_DEPRECATED);
// ini_set('display_errors', 1);
include 'connection.php';

// ini_set('display_errors', 1);
$connection = connection::getInstance();
$mysqli = $connection->getConnection();
///////////////////////////////////////////////
$connection1 = database::getInstance();
$mysqli1 = $connection1->getConnection();

require_once __DIR__ . '/vendor/autoload.php';


date_default_timezone_set('Asia/kolkata');
    $system_date = date("y-m-d");
    $system_date_time = date("Y-m-d H:i:s");
    
// Assuming these values are defined elsewhere in your code
$request_id = 'R55202775';
$application_id = 'APP-00001';
$person_id = 'MEM-00007';
$specification_id = 'DVF-00001';
$agency_id = 'AGN-00002';

$name_match = 'match'; 
$birth_date_match = 'match';
$pincode_match = 'match';
$name = 'Rishabh Shinde';
$contact_no = '7028858653';
$name_as_per_document = 'Rishabh Shinde';
$aadhaar_no = '454072005087';
$birthdate = '12-12-1998';
$pincode = '411041';
$uploaded_documents = $_POST['uploaded_documents'];
$backend_picture = $_POST['backend_picture'];
$aadhar_name = $_POST['aadhar_name'];
$aadhar_birth_date = $_POST['aadhar_birth_date'];
$aadhar_pincode = $_POST['aadhar_pincode'];

($name_match=='Match')? $color='green': $color='red';
($birth_date_match=='Match')? $color1='green': $color1='red';
($pincode_match=='Match')? $color2='green': $color2='red';


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
      <td scope="col"><span style="font-weight: bold;">Member Name:</span> '.$name.'</td>
      <td scope="col"><span style="font-weight: bold;">Contact Number: </span>'.$contact_no.'</td>
    </tr> 
    <tr>
      <td scope="col" colspan="2">
      <hr>
      <p style="font-weight: bold;">Provided Information</p>
      </td>
    </tr>
    <tr>
    <td scope="col"><span style="font-weight: bold;">Name as per Document: </span>'.$name_as_per_document.'</td>

    <td scope="col"><span style="font-weight: bold;">Aadhar Number: </span>'.$aadhaar_no.'</td>
  </tr>
    <tr>
    <td scope="col"><span style="font-weight: bold;">Date Of Birth:</span> '.date("d-m-Y",strtotime($birthdate)).'</td>

    <td scope="col"><span style="font-weight: bold;">Pin Code:</span> '.$pincode.'</td>
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
    <img src="'.$uploaded_documents.'" alt="Placeholder image" width="30%" />
    </td>
    <td scope="col" align="center">
    <p style="text-align: center; font-size:15px;">Back Image</p>
    <br>

    <img src="'.$backend_picture.'" alt="Placeholder image" width="30%" />

    </td>
  </tr>
 
</table>
<br><br>


<h1 id="first" ><u>Result</u></h1>

<p style="font-weight: bold;">Report Summary</p>

<span style="font-weight: bold;">This Report Information Genrated Against Aadhar Number: </span>'.$aadhaar_no.' 



<table style=" width:100%; border-collapse: collapse;" id="table">
<tr>
<td scope="col" style="width:50%"><span style="font-weight: bold;">Aadhar Number:</span> '.$aadhaar_no.'</td>

<td scope="col" style="width:50%"><span style="font-weight: bold;">Member Name:</span> '.strtoupper($aadhar_name).'<br><span style="color:'.$color.';">'.$name_match.'</span></td>
</tr>
<tr>
<td scope="col" style="width:50%"><span style="font-weight: bold;">Dare Of Birth:</span> '.date("d-m-Y", strtotime($aadhar_birth_date)).'<br><span style="color:'.$color1.';">'.$birth_date_match.'</span></td>
<td scope="col" style="width:50%"><span style="font-weight: bold;">Pin Code:</span> '.$aadhar_pincode.'<br><span style="color:'.$color2.';">'.$pincode_match.'</span></td>
</tr>

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
$path = savePDF1($output_pdf, $person_id, $agency_id, "aadhar");
// Define response array


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
              AND `person_id` = '$person_id' 
              AND `verification_id` = '$specification_id'";
    $res_sql1 = mysqli_query($mysqli1, $sql1);


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
}

function savePDF1($output_pdf, $person_id, $agency_id, $pdf_for)
{
    // FTP server credentials
    $ftp_server = '199.79.62.21';
    $ftp_username = 'centralwp@mounarchtech.com';
    $ftp_password = 'k=Y#oBK{h}OU';

    // Remote directory path
    $remote_base_dir = "/verification_data/$pdf_for/voco_xp/";

    // Nested directory structure to be created
    $new_directory_path = "$agency_id/$person_id/";

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
    ));

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
    $base_url = "https://mounarchtech.com/central_wp/verification_data/$pdf_for/voco_xp/$agency_id/$person_id";
    $path = $base_url . '/' . $file_name;
    return $path;
  
}
?>
