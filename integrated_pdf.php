<?php

// Disable displaying errors to the browser
// ini_set('display_errors', 0);

// Set error reporting level to hide warnings
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
require_once __DIR__ . '/vendor/autoload.php';
include_once "connection.php";

// ini_set('display_errors', 1);
$connection = connection::getInstance();
$mysqli = $connection->getConnection();
///////////////////////////////////////////////
$connection1 = database::getInstance();
$mysqli1 = $connection1->getConnection();



$agency_id = $_POST['agency_id'];
$member_id = $_POST['member_id'];
$path = $_POST['path'];

// $agency_id = 'AGN-00011';
// $member_id = 'MEM-00105';
// $path = 'https://mounarchtech.com/vocoxp/active_folder/agency/invoice/verifications/MEM-00095/invoice_20240621161431_3.pdf';



use Mpdf\Mpdf;

// Fetch the URL of the old PDF from the database
$fetch_member = "SELECT `universal_report` FROM `member_header_all` WHERE `member_id`='$member_id' AND `registration_id`='$agency_id'";
$res_member = mysqli_query($mysqli, $fetch_member);
$arr_member = mysqli_fetch_assoc($res_member);


$get_det_query = "SELECT `name`, `created_on` FROM `member_header_all`  WHERE `member_id`='$member_id'";
$res_ = mysqli_query($mysqli, $get_det_query);

$details_array = mysqli_fetch_assoc($res_);

$fetch_agency = "SELECT `agency_gst_no`, `mobile_no`, `email_id`, `company_name` FROM `agency_header_all` WHERE `agency_id`='$agency_id'";
$res_agency = mysqli_query($mysqli, $fetch_agency);
$arr_agency = mysqli_fetch_assoc($res_agency);
// print_r($details_array);




if (empty($arr_member['universal_report'])) {
    // Define the target directory
    $target_dir_profile = __DIR__ . "/verification/universal_pdf";

    // Create the target directory if it doesn't exist
    if (!file_exists($target_dir_profile)) {
        if (!mkdir($target_dir_profile, 0777, true)) {
            die('Failed to create directory.');
        }
    }

    // Define file paths
    $pdfFile1 = $target_dir_profile . '/pdf1.pdf'; // Local file path for the old PDF
    $mergedFilePath = $target_dir_profile . '/integrated_' . date("YmdHis") . '.pdf'; // Path for the merged PDF

    // Download the old PDF file
    $default_pdf_url = $path;

    // $default_pdf_url = 'https://slicedinvoices.com/pdf/wordpress-pdf-invoice-plugin-sample.pdf';
    $oldPdfContent = file_get_contents($default_pdf_url, false, stream_context_create(['ssl' => ['verify_peer' => false, 'verify_peer_name' => false]]));
    if ($oldPdfContent === false) {
        die('Failed to download old PDF file.');
    }
    file_put_contents($pdfFile1, $oldPdfContent);

    // Initialize mpdf
    // require_once 'vendor/autoload.php'; // Adjust this path according to your mpdf setup
    // $mpdf = new Mpdf(['setAutoTopMargin' => 'stretch', 'setAutoBottomMargin' => 'stretch']);

    // $mpdf = new Mpdf([
    //     'setAutoTopMargin' => 'stretch',
    //     'setAutoBottomMargin' => 'stretch',
    //     'format' => [640, 1000] // Custom format: 640mm width and 1000mm height
    // ]);

    $mpdf = new Mpdf(['mode' => 'utf-8', 'format' => 'A4']);




    // Get dynamic content from database
    $get_spec = "SELECT `table_name` FROM `verification_header_all` WHERE `status`='1'";
    $res_spec = mysqli_query($mysqli1, $get_spec);

    $tital_p;
    while ($arr = mysqli_fetch_assoc($res_spec)) {
        $table = $arr['table_name'];
        $query = "SELECT sum(`price`) as `total_price`, `created_on` FROM `$table` WHERE `agency_id`='$agency_id' and `person_id`='$member_id' and `verification_status`='2'";
        $res = mysqli_query($mysqli1, $query);
        $array_ = mysqli_fetch_assoc($res);
        $tital_p += $array_['total_price'];
    }
    $sgst = $tital_p * 9 / 100;
    $cgst = $tital_p * 9 / 100;
    $final_total = $tital_p + $sgst + $cgst;
    // Start HTML content

    $output = '<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    
    <style>
    th, td{
        font-size:15px;
        line-height: 1.5;
    }
    #firstDiv{
        padding:15px;
        background: #889dd1;
      
      }
      </style>
    </head><body>
    <div>
    <img src="vendor/microintegrated_logo.png" alt="Placeholder image" width="8%" style="float: right;"/>
    <h4 style="text-align: center; font-size:20px;"><u>INTEGRATED VERIFICATION REPORT</u></h4>
    </div>
    <div>

    <br>
    
    
    <br><br>
    <table width="100%">
 
    <tr>
    <td align="left">Agency Name: ' . $arr_agency['company_name'] . '</td>
    <td align="left">Member Name: ' . $details_array['name'] . '</td>
    </tr>
    <tr>
    <td align="left">Company GST No: ' . $arr_agency['agency_gst_no'] . '</td>
    <td align="left">Agency Mobile no: ' . $arr_agency['mobile_no'] . '</td>
    </tr>
    <tr>
    
    <td align="left">Agency Email ID: ' . $arr_agency['email_id'] . '</td>
    <td align="left">Date of Member Registration: ' . date("d-m-Y", strtotime($details_array['created_on'])) . '</td>
    </tr>
      


    </table>
    
<hr>





<h4 style="text-align: center; font-size:20px;"><u>INDEX</u></h4>

    <table style="width:100%; border-collapse: collapse;">
    <thead>
    <tr>
    <th style="width:15%; border: 1px solid #0d68a5; border-bottom: 1px solid #0d68a5; text-align: center;">Sr NO</th>
    <th style="width:50%; border: 1px solid #0d68a5;text-align: center;">Verification Name</th>
    <th style="width:20%; border: 1px solid #0d68a5; text-align: center;">Date</th>

    <th style="width:15%; border: 1px solid #0d68a5;text-align: center;">Amount (INR)</th>
    <th style="width:15%; border: 1px solid #0d68a5;text-align: center;">CGST</th>
    <th style="width:15%; border: 1px solid #0d68a5;text-align: center;">SGST</th>
    <th style="width:15%; border: 1px solid #0d68a5;text-align: center;">Total Amount (INR)</th>
    <th style="width:15%; border: 1px solid #0d68a5;text-align: center;">Details Page No</th>
    </tr>
    </thead>';

    $sr = 1; // Initialize the counter
    $page = 2; // Initialize the counter
    $get_spec = "SELECT `verification_header_all`.`table_name`, `verification_configuration_all`.`sgst_percentage`, `verification_configuration_all`.`cgst_percentage` FROM `verification_header_all` INNER JOIN `verification_configuration_all` ON `verification_configuration_all`.`verification_id`=`verification_header_all`.`verification_id` WHERE `verification_header_all`.`status`='1' AND `verification_configuration_all`.`ver_type`=2";
    $res_spec = mysqli_query($mysqli1, $get_spec);
    while ($arr = mysqli_fetch_assoc($res_spec)) {
        $table = $arr['table_name'];
         $query = "SELECT sum(`price`) as `total_price`, `created_on` FROM `$table` WHERE `agency_id`='$agency_id' AND `person_id`='$member_id' and `verification_status`='2'";
        $res = mysqli_query($mysqli1, $query);
        $array = mysqli_fetch_assoc($res);
        $row_1 = mysqli_num_rows($res);
        $title = "";
        $total_gst = $arr['sgst_percentage'] + $arr['cgst_percentage'];
        $grand_total = $array['total_price'] * $total_gst / 100;
        $g_total = $array['total_price'] + $grand_total;
        // Set the title based on the table
        switch ($table) {
            case "aadhar_transaction_all":
                $title = "Aadhar Verification";
                break;
            case "pan_transaction_all":
                $title = "Pan Verification";
                break;
            case "ecrime_transaction_all":
                $title = "Criminal Record Verification";
                break;
            case "v_cibil_active_transaction_all":
                $title = "CIBIL Verification";
                break;
            case "driving_license_transaction_all":
                $title = "Driving license Verification";
                break;
            case "voter_transaction_all":
                $title = "Voter ID Verification";
                break;
            
        }

        // If there are rows in the result, add to HTML output
        if ($row_1 > 0 && $array['total_price'] != "") {
            $output .= '<tbody>
            <tr>
            <td style="border: 0.5px solid #dee2e6; text-align: center; ">' . $sr++ . ' </td>
            <td style="border: 0.5px solid #dee2e6;text-align: center; ">' . $title . ' </td>
            
            <td style="border: 0.5px solid #dee2e6;text-align: center; ">  ' . date("d-m-Y", strtotime($array['created_on'])) . ' </td>
            <td style="border: 0.5px solid #dee2e6;text-align: center; ">    ' . number_format($array['total_price'], 2, '.', '') . '   </td>
            <td style="border: 0.5px solid #dee2e6;text-align: center; ">    ' . $arr['sgst_percentage'] . ' %  </td>
            <td style="border: 0.5px solid #dee2e6;text-align: center; ">    ' . $arr['cgst_percentage'] . '  % </td>
            <td style="border: 0.5px solid #dee2e6;text-align: center; ">    ' . number_format($g_total, 2, '.', '') . '   </td>
            <td style="border: 0.5px solid #dee2e6;text-align: center; ">    ' . $page++ . '   </td>
            </tr></tbody>';
        }
    }

    $output .= '</table></div><pagebreak /></body></html>';

    // Write dynamic content to the PDF

    $mpdf->SetHTMLFooter('
    <table width="100%">
        <tr>
            <td align="center"></td>
        </tr>
    </table>
');

    // Write dynamic content to the PDF
    $mpdf->WriteHTML($output);

    // Import the old PDF as a template
    $pages1 = $mpdf->setSourceFile($pdfFile1);

    // Add pages from the old PDF to the new one
    for ($i = 1; $i <= $pages1; $i++) {
        $tplIdx = $mpdf->importPage($i);
        $mpdf->UseTemplate($tplIdx);
        if ($i < $pages1) {
            $mpdf->AddPage();
        }
    }
    
    // Output the merged PDF
    $output_pdf = $mpdf->Output('', 'S');
    //  $output_pdf = $mpdf->Output();

    // echo $output;
    print_r(savePDf($output_pdf, $member_id, $agency_id, $mysqli));
} else {
    // Path to store the downloaded PDF files
    $pdfFile1 = 'verification/universal_pdf/pdf1.pdf'; // Local file path for the old PDF

    // Download the old PDF file
    $oldPath = $arr_member['universal_report'];
    $oldPdfContent = file_get_contents($oldPath, false, stream_context_create(['ssl' => ['verify_peer' => false, 'verify_peer_name' => false]]));
    if ($oldPdfContent === false) {
        die('Failed to download old PDF file.');
    }
    file_put_contents($pdfFile1, $oldPdfContent);

    // Path to the new PDF file (static URL)
    $newPath = $path;
    // $newPath = 'https://slicedinvoices.com/pdf/wordpress-pdf-invoice-plugin-sample.pdf';

    // Path to store the downloaded PDF file
    $pdfFile2 = 'verification/universal_pdf/pdf2.pdf'; // Local file path for the new PDF

    // Download the new PDF file
    $newPdfContent = file_get_contents($newPath, false, stream_context_create(['ssl' => ['verify_peer' => false, 'verify_peer_name' => false]]));
    if ($newPdfContent === false) {
        die('Failed to download new PDF file.');
    }
    file_put_contents($pdfFile2, $newPdfContent);

    // Initialize mPDF
    // $mpdf = new Mpdf(['setAutoTopMargin' => 'stretch', 'setAutoBottomMargin' => 'stretch',]);
    // ['format' => [670, 1000]

    // $mpdf = new Mpdf([
    //     'setAutoTopMargin' => 'stretch',
    //     'setAutoBottomMargin' => 'stretch',
    //     'format' => [640, 1000] // Custom format: 640mm width and 1000mm height
    // ]);

    $mpdf = new Mpdf(['mode' => 'utf-8', 'format' => 'A4']);

    // Generate dynamic content
    $get_spec = "SELECT `verification_header_all`.`table_name`, `verification_configuration_all`.`sgst_percentage`, `verification_configuration_all`.`cgst_percentage` FROM `verification_header_all` INNER JOIN `verification_configuration_all` ON `verification_configuration_all`.`verification_id`=`verification_header_all`.`verification_id` WHERE `verification_header_all`.`status`='1' AND `verification_configuration_all`.`ver_type`=2";
    $res_spec = mysqli_query($mysqli1, $get_spec);

    $tital_p = 0; // Initialize $tital_p to 0
    $des_array = array();

    while ($arr = mysqli_fetch_assoc($res_spec)) {
        $table = $arr['table_name'];
        $query = "SELECT  `price` as `total_price`, `created_on`, `verification_report`, `agency_id` FROM `$table` WHERE `agency_id`='$agency_id' AND `person_id`='$member_id' AND `verification_status`='2'";
        $res = mysqli_query($mysqli1, $query);
        $row = mysqli_num_rows($res);

        $total_gst = $arr['sgst_percentage'] + $arr['cgst_percentage'];

        while ($array_ = mysqli_fetch_assoc($res)) {
            if ($array_['created_on'] != '') {
                $grand_total = $array_['total_price'] * $total_gst / 100;
                $g_total = $array_['total_price'] + $grand_total;
                $parts = explode("/", $array_['verification_report']);
                // print_r($parts);
                // Get the last part containing the date and file extension
                $dateWithExtension = end($parts);
                // Remove ".pdf" from the end to get only the date
                $dateString = preg_replace("/[^0-9]/", "", $dateWithExtension);
                // Convert the date string to a DateTime object
                $date = DateTime::createFromFormat('YmdHis', $dateString);
                // print_r($date);


                // Format the DateTime object to the desired format
                $val = $date->format('Y-m-d H:i:s'); // Directly format the DateTime object

                $tital_p += $array_['total_price'];
                // Organize created dates from each table into the array
                $des_array[] = array('table' => $table, 'created_date' => $val, "total_price" => $array_['total_price'], 'total_gst' => $total_gst, 'g_total' => $g_total, 'sgst' => $arr['sgst_percentage'], 'cgst' => $arr['cgst_percentage']);
            }
        }
    }
    $sgst = $tital_p * 9 / 100;
    $cgst = $tital_p * 9 / 100;
    $final_total = $tital_p + $sgst + $cgst;
    // print_r($des_array);
    // echo "<pre>";
    // print_r($des_array);
    $des_array = array_filter($des_array);
    // Sort the array by the 'created_on' field in ascending order
    usort($des_array, function ($a, $b) {
        return strtotime($a['created_date']) - strtotime($b['created_date']);
    });
    // Convert string dates to desired format
    foreach ($des_array as &$value) {
        $value['created_date'] = date("d-m-Y H:i:s", strtotime($value['created_date']));
    }
    // print_r($des_array);
    // exit;
    // Start HTML content
    $output = '<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
  th, td{
    font-size:15px;
    line-height: 1.5;
  }
  #firstDiv{
    padding:15px;
    background: #889dd1;
  
  }
    </style>
    </head><body>
    <div>
    <img src="vendor/microintegrated_logo.png" alt="Placeholder image" width="8%" style="float: right;"/>
    <h4 style="text-align: center; font-size:20px;"><u>INTEGRATED VERIFICATION REPORT</u></h4>
    </div>
    
    <div>
    <br>
    
    
    <br><br>
    <table width="100%">
 
    <tr>
    <td align="left">Agency Name: ' . $arr_agency['company_name'] . '</td>
    <td align="left">Member Name: ' . $details_array['name'] . '</td>
    </tr>
    <tr>
    <td align="left">Company GST No: ' . $arr_agency['agency_gst_no'] . '</td>
    <td align="left">Agency Mobile no: ' . $arr_agency['mobile_no'] . '</td>
    </tr>
    <tr>
    
    <td align="left">Agency Email ID: ' . $arr_agency['email_id'] . '</td>
    <td align="left">Date of Member Registration: ' . date("d-m-Y", strtotime($details_array['created_on'])) . '</td>
    </tr>
    </table>
    
    <hr>


    
    <h4 style="text-align: center; font-size:20px"><u>INDEX</u></h4>

    <table style="width:100%; border-collapse: collapse;">
    <thead>
    <tr>
    <th style="width:15%; border: 1px solid #0d68a5; border-bottom: 1px solid #0d68a5; text-align: center;">Sr NO</th>
    <th style="width:50%; border: 1px solid #0d68a5;text-align: center;">Verification Name</th>
    <th style="width:20%; border: 1px solid #0d68a5; text-align: center;">Date</th>

    <th style="width:15%; border: 1px solid #0d68a5;text-align: center;">Amount (INR)</th>
    <th style="width:15%; border: 1px solid #0d68a5;text-align: center;">CGST</th>
    <th style="width:15%; border: 1px solid #0d68a5;text-align: center;">SGST</th>
    <th style="width:15%; border: 1px solid #0d68a5;text-align: center;">Total Amount (INR)</th>
    <th style="width:15%; border: 1px solid #0d68a5;text-align: center;">Details Page No</th>
    
    </tr>
    </thead>';

    $sr = 1; // Initialize the counter
    $page = 2; // Initialize the counter
    foreach ($des_array as $array) {
        // Get the title from the mapping
        // $title = $mergedData[$table] ?? '';
        $tableTitles = "";

        if ($array['table'] == "aadhar_transaction_all") {
            $tableTitles = "Aadhar Verification";
        }
        if ($array['table'] == "pan_transaction_all") {
            $tableTitles = "Pan Verification";
        }
        if ($array['table'] == "ecrime_transaction_all") {
            $tableTitles = "Criminal Record Verification";
        }
        if ($array['table'] == "v_cibil_active_transaction_all") {
            $tableTitles = "CIBIL Verification";
        }
        if ($array['table'] == "driving_license_transaction_all") {
            $tableTitles = "Driving License Verification";
        }
        if ($array['table'] == "voter_transaction_all") {
            $tableTitles = "Voter ID Verification";
        }


        // If there are rows in the result, add to HTML output

        $output .= '<tbody><tr> <td style="border: 0.5px solid #dee2e6; text-align: center; ">' . $sr++ . ' </td>
        <td style="border: 0.5px solid #dee2e6;text-align: center; ">' . $tableTitles . ' </td>
        
        <td style="border: 0.5px solid #dee2e6;text-align: center; ">  ' .  date("d-m-Y", strtotime($array['created_date'])) . ' </td>
        <td style="border: 0.5px solid #dee2e6;text-align: center; ">    ' . number_format($array['total_price'], 2, '.', '') . '   </td>
        <td style="border: 0.5px solid #dee2e6;text-align: center; ">    ' . $array['sgst'] . ' %   </td>
        <td style="border: 0.5px solid #dee2e6;text-align: center; ">    ' . $array['cgst'] . ' % </td>
        <td style="border: 0.5px solid #dee2e6;text-align: center; ">    ' . number_format($array['g_total'], 2, '.', '') . '   </td>
        <td style="border: 0.5px solid #dee2e6;text-align: center; ">    ' . $page++ . '   </td>
        
        </tr></tbody>';
    }



    $output .= '</table></div></body></html>';

    // Write the dynamic content to the PDF
    // $mpdf = new Mpdf(['setAutoTopMargin' => 'stretch', 'setAutoBottomMargin' => 'stretch']);

    // Define the header and footer HTML with page numbers <td align="right">{PAGENO}/{nbpg}</td>
    $mpdf->SetHTMLHeader('
    <table width="100%">
    
        <tr>
            <td align="right">Page {PAGENO}</td>'

        . '</tr>
    </table>
');

    $mpdf->SetHTMLFooter('
        <table width="100%">
            <tr>
                <td align="center"></td>
            </tr>
        </table>
    ');

    // Write dynamic content to the PDF
    $mpdf->WriteHTML($output);


    // Import the first PDF file (dynamic content)
    $pages1 = $mpdf->setSourceFile($pdfFile1);
    for ($i = 2; $i <= $pages1; $i++) {
        $tplIdx = $mpdf->importPage($i);
        $mpdf->addPage();
        $mpdf->useTemplate($tplIdx);
    }

    // Import the second PDF file (static content)
    $pages2 = $mpdf->setSourceFile($pdfFile2);
    for ($i = 1; $i <= $pages2; $i++) {
        $tplIdx = $mpdf->importPage($i);
        $mpdf->addPage();
        $mpdf->useTemplate($tplIdx);
    }

    // Output the merged PDF
    $output_pdf = $mpdf->Output('', 'S');
    // echo $output;
    print_r(savePDf($output_pdf, $member_id, $agency_id, $mysqli));
}

function savePDF($output_pdf, $member_id, $agency_id, $mysqli)
{
    // FTP server credentials
    $ftp_server = '199.79.62.21';
    $ftp_username = 'centralwp@mounarchtech.com';
    $ftp_password = 'k=Y#oBK{h}OU';

    // Remote directory path
    $remote_base_dir = "/verification_data/integrated/voco_xp/";

    // Nested directory structure to be created
    $new_directory_path = "$agency_id/$member_id/";

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
    $file_name = 'integrated_' . date("YmdHis") . '.pdf';

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
    $base_url = "https://mounarchtech.com/central_wp/verification_data/integrated/voco_xp/$agency_id/$member_id";
    $path = $base_url . '/' . $file_name;
    $update_pdf = "UPDATE `member_header_all` SET `universal_report`='$path' WHERE `member_id`='$member_id' AND `registration_id`='$agency_id'";
    $res_member = mysqli_query($mysqli, $update_pdf);

    // Define response array
    $response = array();

    // Check if the path is valid
    if ($path !== false) {
        // PDF generated successfully
        $response['success'] = true;
        $response['message'] = 'Integreated PDF generated successfully.';
        $response['path'] = $path;
    } else {
        // PDF generation failed
        $response['success'] = false;
        $response['message'] = 'Failed to generate Integreated PDF.';
    }

    return $response;
}



// function savePDf($output_pdf, $member_id, $agency_id, $mysqli)
// {
//     // error_reporting(E_ALL);
//     // ini_set('display_errors', 1);

//     // FTP server credentials
//     $ftp_server = '199.79.62.21';
//     $ftp_username = 'centralwp@mounarchtech.com';
//     $ftp_password = 'k=Y#oBK{h}OU';

//     // Remote directory path
//     $remote_base_dir = "/verification_data/integrated/voco_xp/";

//     // Nested directory structure to be created
//     $new_directory_path = "$agency_id/$member_id/";

//     // Initialize cURL session for FTP
//     $curl = curl_init();

//     // Set cURL options for FTP
//     curl_setopt_array($curl, array(
//         CURLOPT_URL => "ftp://$ftp_server/",
//         CURLOPT_USERPWD => "$ftp_username:$ftp_password",
//         CURLOPT_SSL_VERIFYPEER => false,
//         // Set to true if using FTPS (FTP over SSL)
//         CURLOPT_FTP_CREATE_MISSING_DIRS => true, // Create missing directories
//     )
//     );

//     // Execute the cURL session for FTP
//     $response_ftp = curl_exec($curl);

//     // Check for errors in FTP request
//     if ($response_ftp === false) {
//         $error_message_ftp = curl_error($curl);
//         die("Failed to connect to FTP server: $error_message_ftp");
//     } else {
//         // echo "Connected to FTP server\n";
//     }

//     // Set the target directory
//     $remote_dir_path = $remote_base_dir . $new_directory_path;

//     // Create directory recursively
//     curl_setopt($curl, CURLOPT_URL, "ftp://$ftp_server/$remote_dir_path");
//     curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'MKD');
//     $response_mkdir = curl_exec($curl);

//     // Check for errors in directory creation
//     if ($response_mkdir == false) {
//         $error_message_mkdir = curl_error($curl);
//         // echo "Failed to create directory: $error_message_mkdir";
//     } else {
//         // echo "Directory created successfully\n";
//     }

//     // Generate a unique file name for the merged PDF
//     $file_name = 'integrated_' . date("YmdHis") . '.pdf';

//     // Construct the full file path on the remote server
//     $file_path = $remote_dir_path . $file_name;

//     $temp_file = tempnam(sys_get_temp_dir(), 'pdf');
//     file_put_contents($temp_file, $output_pdf);

//     // Initialize cURL session
//     $curl_upload = curl_init();

//     // Set cURL options for file upload
//     curl_setopt_array($curl_upload, array(
//         CURLOPT_URL => "ftp://$ftp_server/$file_path",
//         CURLOPT_USERPWD => "$ftp_username:$ftp_password",
//         CURLOPT_SSL_VERIFYPEER => false,
//         // Set to true if using FTPS (FTP over SSL)
//         CURLOPT_UPLOAD => true,
//         CURLOPT_INFILE => fopen($temp_file, 'r'),
//         // Open temporary file for reading
//         CURLOPT_INFILESIZE => filesize($temp_file),
//     )
//     );

//     // Execute cURL session for file upload
//     $response_upload = curl_exec($curl_upload);

//     // Check for errors in file upload
//     if ($response_upload === false) {
//         $error_message_upload = curl_error($curl_upload);
//         die("Failed to save merged PDF file: $error_message_upload");
//     } else {
//         // echo "Merged PDF file uploaded successfully\n";
//     }


//     // Close cURL sessions
//     curl_close($curl);
//     curl_close($curl_upload);



//     // Generate a unique file name for the merged PDF
//     //     $file_name = 'integrated_' . date("YmdHis") . '.pdf';
//     //    echo $file_path = "https://mounarchtech.com/central_wp/$remote_dir_path/$file_name";

//     //     // Save the merged PDF file
//     //     if (!file_put_contents($file_path, $output_pdf)) {
//     //         die('Failed to save merged PDF file.');
//     //     }

//     // Update the database with the path to the merged PDF file
//     $base_url = "https://mounarchtech.com/central_wp/verification_data/integrated/voco_xp/$agency_id/$member_id";
//     // $base_url = "https://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/verification/universal_pdf";
//     $path = $base_url . '/' . $file_name;

//      $update_pdf = "UPDATE member_header_all SET universal_report='$path' WHERE member_id='$member_id' AND registration_id='$agency_id'";
//     $res_member = mysqli_query($mysqli, $update_pdf);
// }
