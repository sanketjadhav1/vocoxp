<?php
use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\Exception;
require_once __DIR__ . '/vendor/autoload.php';
// echo "check";
// Enable error reporting
// error_reporting(E_ALL & ~E_DEPRECATED);
// ini_set('display_errors', 1);
include 'connection.php';

//Application Vocoxp databese connection//
$connection = connection::getInstance();
$mysqli = $connection->getConnection();
//Central Database Connection//
$connection1 = database::getInstance();
$mysqli1 = $connection1->getConnection();


date_default_timezone_set('Asia/kolkata');
    $system_date = date("d-m-Y");
    $system_date_time = date("Y-m-d H:i:s");

$rand=rand(1111,9999);

 

$ver_query = "SELECT DISTINCT `agency_id` FROM `direct_verification_details_all`";
$ver_result = $mysqli->query($ver_query);
  if ($ver_result->num_rows > 0) {
    // Fetch the data in a loop
    while ($row = $ver_result->fetch_assoc()) {
         if (!empty($row['agency_id'])) {
            $agency_id = $row['agency_id'];
            $agency_details_query = "SELECT `direct_invoice`,`company_name`,`name`, `address`, `mobile_no`, `agency_gst_no`, `email_id` FROM `agency_header_all` WHERE `agency_id`='$agency_id'";
            $agency_details__result = $mysqli->query($agency_details_query);
            $agency_details_array = mysqli_fetch_assoc($agency_details__result);

            $direct_invoice=$agency_details_array['direct_invoice'];
            $name=$agency_details_array['name'];
            $email=$agency_details_array['email_id'];
            $company_name=$agency_details_array['company_name'];
            $address=$agency_details_array['address'];
            $mobile_no=$agency_details_array['mobile_no'];
            $agency_gst_no=$agency_details_array['agency_gst_no'];

            $html = '<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  
  <style type="text/css">
    body {
      font-family: "Times New Roman";
      font-size: 12pt;
    }
    p {
      margin: 0;
    }
    th{
      text-align: center;
      vertical-align: middle;
      padding: 5px;
      font-size: 13px;
    }
    td{
      text-align: center;
      vertical-align: middle;
      font-size: 12px;
    }
    th, td{
      white-space: nowrap;
    }
   
  </style>
</head>
<body>
  <div>
    <p style="text-align: center; font-size: 20px;"><b>VERIFICATION INVOICE ('.$system_date.')</b></p><br>
    <p style="font-family: Calibri; font-size: 13pt; color: #0d68a5; text-align: center;">MICRO INTEGRATED SEMI CONDUCTOR SYSTEMS PVT. LTD </p>
    <p style="margin-top: 2px; margin-left: 5px; text-align: center; line-height: 2; font-family: Calibri; font-size: 11pt;">154, G-2, Gulmohar Colony, Bhopal, Madhya Pradesh, 462039.</p>    
    <p style="margin-top: 2px; margin-left: 5px; text-align: center; line-height: 1; font-family: Calibri; font-size: 11pt;"> www.microintegrated.in</p>
    <p style="margin-top: 2px; margin-left: 5px; text-align: center; line-height: 2; font-family: Calibri; font-size: 11pt;"><b>GST Number:-23AAECM0658D2ZV</b></p>

    <br>
    <div style="padding: 4px; background-color: #0d68a5;"></div>
    <table style="width:100%; border-collapse: collapse;  background-color:#e5e5e5;">
      <thead>
        <tr>
          <th style="text-align: left;">Invoice No: '.$rand.'</th>
          <th></th>
          <th style="text-align: right;">
          Invoice Date: '.$system_date.'</th>
        </tr>
      </thead>
    </table>
    <br>
    <section>
      <div>
        <p style="font-size: 13px; margin-left:10px; font-weight:700;"><b>BILL TO</b></p>
        <p style="font-size: 12px; margin-left:10px; margin-top:5px;"><b>Agency Name:-</b> '.$company_name.'</p>
        <p style="font-size: 12px; margin-left:10px; margin-top:5px;"><b>Address:-</b> '.$address.'</p>
        <p style="font-size: 12px; margin-left:10px; margin-top:5px;"><b>Mobile No :-</b> '.$mobile_no.'</p>';
if (!empty($agency_gst_no)) {
  $html .='<p style="font-size: 12px; margin-left:10px; margin-top:5px;"><b>GST Number :- '.$agency_gst_no.'</b></p>';
}
         
$html .='</div>
    </section>
    <br>
<section>
      <div>
        <table style="width:100%; border-collapse: collapse;">
          <thead>
            <tr>
              <th style="width:40%; border-top: 1px solid #0d68a5; border-bottom: 1px solid #0d68a5; text-align: left;">Verification Name</th>
              <th style="width:15%; border-top: 1px solid #0d68a5; border-bottom: 1px solid #0d68a5;">Rate(₹)</th>
              <th style="width:15%; border-top: 1px solid #0d68a5; border-bottom: 1px solid #0d68a5;">Quantity(count) </th>
              <th style="width:15%; border-top: 1px solid #0d68a5; border-bottom: 1px solid #0d68a5;">AMOUNT(₹)</th>
              <th style="width:15%; border-top: 1px solid #0d68a5; border-bottom: 1px solid #0d68a5;">CGST(%) - (₹)</th>
              <th style="width:15%; border-top: 1px solid #0d68a5; border-bottom: 1px solid #0d68a5;">SGST(%) - (₹)</th>
              <th style="width:15%; border-top: 1px solid #0d68a5; border-bottom: 1px solid #0d68a5; text-align: right;">Sub Total</th>              
            </tr>
          </thead>
          <tbody>';
          $verification_header = "SELECT `verification_id`, `name` FROM `verification_header_all` WHERE `status`='1'";
          $verification_result = $mysqli1->query($verification_header);
          $total_count = 0;
          $total_amount = 0;
          $total_cgst = 0;
          $total_sgst = 0;
          $cgst_amount = 0;
          $grand_grand_total = 0;
          $sgst_amount = 0;
          $grand_cgst = 0;
          $grand_sgst = 0;

           while ($vrow = $verification_result->fetch_assoc()) 
           {
             $verification_id = $vrow['verification_id'];
            $verification_name = $vrow['name'];        

            $verification_details = "SELECT * FROM `verification_configuration_all` WHERE `ver_type`='1' AND `verification_id`='$verification_id' AND `operational_status`='1'"; 
          $details_result = $mysqli1->query($verification_details);
          if ($details_result->num_rows > 0) {
          $ver_array = mysqli_fetch_assoc($details_result);
           $rate = $ver_array['rate'];
           $sgst_percentage = $ver_array['sgst_percentage'];
           $cgst_percentage = $ver_array['cgst_percentage'];  

  $count_query = "SELECT COUNT(*) as count FROM `direct_verification_details_all` WHERE `agency_id`='$agency_id' AND `verification_id`='$verification_id'";
$count_result = $mysqli->query($count_query);
$count_array = mysqli_fetch_assoc($count_result);

$count=$count_array['count'];


if($count!=0){
  $total_count += $count;
$total_cgst += $cgst_percentage;
$total_sgst += $sgst_percentage;
}

$amount = ($rate * $count);
$cgst_amount = ($amount * $cgst_percentage) / 100;
$sgst_amount = ($amount * $sgst_percentage) / 100;
$grand_total = ($amount * 1) + ($sgst_amount * 1) + ($cgst_amount * 1);
$grand_cgst += $cgst_amount;
$grand_sgst += $sgst_amount;
$grand_grand_total += $amount;
$grand_grand_total1 = ($grand_grand_total*1) + ($grand_cgst*1) + ($grand_sgst*1);
 if(!empty($rate)){
  $html .=' <tr>
              <td style="border-bottom: 0.5px solid #dee2e6; text-align: left; line-height: 3;">'.$verification_name.'</td>
              <td style="border-bottom: 0.5px solid #dee2e6;">'.$rate.'/-</td>
              <td style="border-bottom: 0.5px solid #dee2e6;">'.$count.'</td>
              <td style="border-bottom: 0.5px solid #dee2e6;">'.$amount.'/-</td>
              <td style="border-bottom: 0.5px solid #dee2e6;">'.$cgst_percentage.'% - ('.$cgst_amount.'₹)</td>
              <td style="border-bottom: 0.5px solid #dee2e6;">'.$sgst_percentage.'% - ('.$sgst_amount.'₹)</td>
              <td style="border-bottom: 0.5px solid #dee2e6; text-align: right;">'.$grand_total.'</td>
              
            </tr>';
           
 }    }    

          }
$html .= '
            <tr>
            <td style="border-top: 1px solid #0d68a5; text-align: right; line-height: 2;"></td>
            <td style="border-top: 1px solid #0d68a5; text-align: right; line-height: 2;"></td>
            <td style="border-top: 1px solid #0d68a5; text-align: right; line-height: 2;"></td>
            <td style="border-top: 1px solid #0d68a5; text-align: right; line-height: 2;"></td>
            <td style="border-top: 1px solid #0d68a5; text-align: right; line-height: 2;"></td>
            <td style="border-top: 1px solid #0d68a5; text-align: right; line-height: 2;">TOTAL AMOUNT (₹)</td>
            <td style="border-top: 1px solid #0d68a5; text-align: right; line-height: 2;">'.$grand_grand_total.'/-</td>
          </tr>
          <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td style="text-align: right; line-height: 2;">CGST (₹)</td>
            <td style="text-align: right; line-height: 2;">'.$grand_cgst.'/-</td>
          </tr>
          <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td style="text-align: right; line-height: 2;">SGST (₹)</td>
            <td style="text-align: right; line-height: 2;">'.$grand_sgst.'/-</td>
          </tr>
          <tr>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th style="border-top: 0.5px solid #dee2e6; text-align: right;">GRAND TOTAL</th>
            <th style="border-top: 0.5px solid #dee2e6; text-align: right;">'.$grand_grand_total1.'/-</th>
          </tr>          
        </tbody>
      </table>
    </div>
    <br>
    <div style="text-align: right;">
      <p style="font-size: 11px;"><b>Payment Via:- Wallet </b></p>
    </div>
    <br>
    <br>
    <br>
    <br>
    <br>
    <div>
      <P><b>TERMS AND CONDITIONS:</b></P>
      <p style="font-size: 11px; float:right;margin-left:10px; margin-top:5px;">1. Any dispute should be resolved within 30 days.</p>
      <p style="font-size: 11px; float:right;margin-left:10px; margin-top:5px;">2. All payments should be made in Indian Rupees (INR).</p>
      <p style="font-size: 11px; float:right;margin-left:10px; margin-top:5px;">3. Any legal matters will be covered in Bhopal jurisdiction only.</p>
      
    </div>
    <br>
    <br>
    <br>   
   
  </section>
</div>


</body>
</html>';
require __DIR__ . '/vendor/autoload.php';

$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4']);

// Write HTML content to mPDF
 // Ensure $html is properly defined

$mpdf->WriteHTML($html);

// Define the HTML for the footer
$htmlFooter = '<hr><br><div><p style="text-align: center; font-size: 11px;">This is a Computer Generated invoice</p></div>';

// Set the HTML footer
$mpdf->SetHTMLFooter($htmlFooter);
$output_pdf = $mpdf->Output('', 'S');
  $path = savePDF1($output_pdf, $agency_id);

   
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
      "for"=>'invoice',
      "agency_id"=>$agency_id
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
 
 if($direct_invoice == '1'){
  

  require 'PHPMailer/src/Exception.php';
  require 'PHPMailer/src/PHPMailer.php';
  require 'PHPMailer/src/SMTP.php';

  $mail = new PHPMailer(true);

  try {
      // Server settings
       $mail->isSMTP();
        $mail->Host = 'mail.mounarchtech.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'transactions@mounarchtech.com';
        $mail->Password = 'Mtech!@12345678';  // Ideally, load this from environment variables
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;
        $mail->setFrom('transactions@mounarchtech.com', 'Micro Integrated Semi Conductor Systems Pvt. Ltd.');
        $mail->isHTML(true);

      // Additional SMTP Options
      /*$mail->SMTPOptions = array(
          'ssl' => array(
              'verify_peer' => false,
              'verify_peer_name' => false,
              'allow_self_signed' => true  // This should be true for allowing self-signed certificates
          )
      );*/

      // Recipients
         // $mail->setFrom('info@microintegrated.in', 'Micro Integrated Semi Conductor Systems Pvt. Ltd.');
      $mail->addAddress($email);

      // Content
      $mail->isHTML(true);
      $mail->Subject = "Overall Invoice #".$rand." for Your Today's Verification";

  $mail->Body = '
      <html>
      <head>
          <title>Verification Invoice Report</title>
          <style>
              body {
                  font-family: Arial, sans-serif;
                  margin: 0;
                  padding: 0;
                  background-color: #f4f4f4;
              }
              .container {
                  width: 80%;
                  margin: 0 auto;
                  border: 1px solid #ddd;
                  border-radius: 5px;
                  background-color: #fff;
                  padding: 20px;
              }
              h2 {
                  color: #333;
                  margin-top: 0;
              }
              p {
                  color: #666;
              }
              .footer {
                  margin-top: 20px;
                  text-align: ;
                  color: #888;
              }
          </style>
      </head>
      <body>
          <div class="container">
              <h2>Verification Invoice</h2>
              <p>Dear ' . $name . ',</p>
              <p>Thank you for your verifications from VocoXp!</p>
              
              <p>Please find the attached invoice and reports for your reference.
              
              </p>
              <p>If you have any questions or require further assistance, please get in touch with our support team at feedback@mounarch.tech.</p>
              <p>Thank you for choosing VocoXp! We look forward to serving you again soon.</p>
              <div class="footer">
                  <p>Best Regards,</p>
                  <p>MICRO INTEGRATED SEMI CONDUCTOR SYSTEMS PVT. LTD</p>
              </div>
          </div>
      </body>
      </html>
  ';

      // Attach PDF to the email
      $mail->addStringAttachment($output_pdf, 'invoice.pdf');
     

      // Send email
      if ($mail->send()) {
          echo 'Email sent successfully';
      } else {
          echo 'Error: ' . $mail->ErrorInfo;
      }
  } catch (Exception $e) {
      echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
  }
} else {}    
	  }
    }
} else {
    echo "0 results";
}
//check if agency want once in 24hrs

function savePDF1($output_pdf, $agency_id)
{
  $ftp_server = '199.79.62.21';
  $ftp_username = 'centralwp@mounarchtech.com';
  $ftp_password = 'k=Y#oBK{h}OU';
  // Remote directory path
  $remote_base_dir = "/verification_data/voco_xp/";

  // Nested directory structure to be created
  $new_directory_path = "$agency_id/invoice/".date("Y-m-d")."/";

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
  $file_name = 'Overall_invoice_' . date("Y-m-d") . '.pdf';

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
    die("Failed to save Invoice PDF file: $error_message_upload");
  }

  // Close cURL sessions
  curl_close($curl);
  curl_close($curl_upload);

  // Update the database with the path to the merged PDF file
  $base_url = "https://mounarchtech.com/central_wp/verification_data/voco_xp/$agency_id/invoice";
  $path = $base_url . '/' . $file_name;
  return $path;
}


?>
