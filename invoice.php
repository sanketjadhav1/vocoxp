<?php
require_once __DIR__ . '/vendor/autoload.php';
// echo "check";
// Enable error reporting
// error_reporting(E_ALL & ~E_DEPRECATED);
// ini_set('display_errors', 1);
include 'connection.php';

// ini_set('display_errors', 1);
$connection = connection::getInstance();
$mysqli = $connection->getConnection();
///////////////////////////////////////////////
$connection1 = database::getInstance();
$mysqli1 = $connection1->getConnection();
///////////////////////////////////////

date_default_timezone_set('Asia/kolkata');
    $system_date = date("y-m-d");
    $system_date_time = date("Y-m-d H:i:s");

$speci_id = $_POST['specification_id'];
// $speci_id = 'DVF-00001';

$speci_id_array = explode(',', $speci_id);
$speci_id_array = array_map(function ($item) {
  return str_replace("'", "", $item);
}, $speci_id_array);

$req_id = $_POST['req_id'];
// $req_id = 'REQ-00026';


$ver_query = "SELECT `verification_payment_transaction_all`.*
FROM `verification_payment_transaction_all` 
WHERE `verification_payment_transaction_all`.`request_id` = '$req_id';
";
$ver_result = $mysqli1->query($ver_query);
$ver_array = mysqli_fetch_assoc($ver_result);

 $agency_id = $ver_array['agency_id'];
 $member_id = $ver_array['person_id'];
 $created_on = date("d-m-Y", strtotime($ver_array['created_on']));

$mem_query = "SELECT `name` FROM  `member_header_all` WHERE `member_id`='$member_id'";
$mem_result = $mysqli->query($mem_query);
$mem_array = mysqli_fetch_assoc($mem_result);

// echo "name:=".$mem_array['name'];
$mem_name=$mem_array['name'];

 $agency_details_query = "SELECT `name`, `address`, `mobile_no`, `agency_gst_no`, `email_id` FROM `agency_header_all` WHERE	`agency_id`='$agency_id'";
$agency_details__result = $mysqli->query($agency_details_query);
$agency_details_array = mysqli_fetch_assoc($agency_details__result);


$name=$agency_details_array['name'];
$email=$agency_details_array['email_id'];
// print_r($ver_array);


$rand=rand(1111,9999);

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
      font-size: 11px;
    }
    th, td{
      white-space: nowrap;
    }
   
  </style>
</head>
<body>
  <div>
    <p style="text-align: center; font-size: 20px;"><b>TAX INVOICE</b></p><br>
    <p style="font-family: Calibri; font-size: 20pt; color: #0d68a5; text-align: center;">Micro Integrated Semi Conductor Systems Pvt. Ltd.</p>
    <p style="margin-top: 2px; margin-left: 5px; text-align: center; line-height: 1; font-family: Calibri; font-size: 11pt;">2nd Floor, Cybernex IT Park, 399, Shankar Seth Road, Swargate, Pune - 411037, Maharashtra.</p>
    
    <p style="margin-top: 2px; margin-left: 5px; text-align: center; line-height: 2; font-family: Calibri; font-size: 11pt;"> www.mounarchtech.com</p>
    <p style="margin-top: 2px; margin-left: 5px; text-align: center; line-height: 2; font-family: Calibri; font-size: 11pt;"> feedback@mounarch.tech</p>
    <p style="margin-top: 2px; margin-left: 5px; text-align: center; line-height: 2; font-family: Calibri; font-size: 11pt;"><b>GST Number:-</b></p>

    <br>
    <div style="padding: 4px; background-color: #0d68a5;"></div>
    <table style="width:100%; border-collapse: collapse;  background-color:#e5e5e5;">
      <thead>
        <tr>
          <th style="text-align: left;">Invoice No: '.$rand.'</th>
          <th></th>
          <th style="text-align: right;">
          Invoice Date: '.$created_on.'</th>
        </tr>
      </thead>
    </table>
    <br>
    <section>
      <div>
        <p style="font-size: 13px; margin-left:10px; font-weight:700;"><b>BILL TO</b></p>
        <p style="font-size: 11px; margin-left:10px; margin-top:5px;">Agency Name:- ' . $agency_details_array['name'] . '</p>
        <p style="font-size: 11px; margin-left:10px; margin-top:5px;">Address:- ' . $agency_details_array['address'] . '</p>
        <p style="font-size: 11px; margin-left:10px; margin-top:5px;">Mobile No :- ' . $agency_details_array['mobile_no'] . '</p>
        <p style="font-size: 11px; margin-left:10px; margin-top:5px;"><b>GST Number :- ' . $agency_details_array['agency_gst_no'] . '</b></p>

        
        

      </div>
    </section>
    <br>
    <section>
      <div>
        <table style="width:100%; border-collapse: collapse;">
          <thead>
            <tr>
              <th style="width:40%; border-top: 1px solid #0d68a5; border-bottom: 1px solid #0d68a5; text-align: left;">ITEM / SERVICE</th>
              <th style="width:15%; border-top: 1px solid #0d68a5; border-bottom: 1px solid #0d68a5;">QTY.</th>
              <th style="width:15%; border-top: 1px solid #0d68a5; border-bottom: 1px solid #0d68a5;">RATE</th>
              <th style="width:15%; border-top: 1px solid #0d68a5; border-bottom: 1px solid #0d68a5;">TAX </th>
              <th style="width:15%; border-top: 1px solid #0d68a5; border-bottom: 1px solid #0d68a5; text-align: right;">AMOUNT </th>
            </tr>
          </thead>
          <tbody>';
$arr = array();
$price_arr = array();
$price_amt;
$par_amt;
$prict_total; 
$sub_total; 

foreach ($speci_id_array as $speci_id) {
  // Fetch abbreviations
  $varification_query = "SELECT `abbreviations` FROM `verification_header_all` WHERE `verification_id`='$speci_id'";
  $varification_result = $mysqli1->query($varification_query);
  $varification_array = mysqli_fetch_assoc($varification_result);

  // Fetch rate, sgst, and cgst percentages
  $varification_deta_query = "SELECT `rate`, `sgst_percentage`, `cgst_percentage` FROM `verification_configuration_all` WHERE `verification_id`='$speci_id'";
  $varification_deta_result = $mysqli1->query($varification_deta_query);
  $varification_deta_array = mysqli_fetch_assoc($varification_deta_result);

  $fetch_table_name="SELECT `name` FROM `verification_header_all` WHERE `verification_id`='$speci_id'";
  $res_table_name=mysqli_query($mysqli1, $fetch_table_name);
  $arr_table_name=mysqli_fetch_assoc($res_table_name);
  // Collect abbreviations
  array_push($arr, $varification_array['abbreviations']);

  // Accumulate the rate (sub_total) and calculate sgst and cgst for each item
  $rate = $varification_deta_array['rate'];
  $sgst = $rate * $varification_deta_array['sgst_percentage'] / 100;
  $cgst = $rate * $varification_deta_array['cgst_percentage'] / 100;

  // Add to the totals
  $sub_total += $rate;
  $sgst_total += $sgst;
  $cgst_total += $cgst;
}

// Calculate the grand total
$grand_total = $sub_total + $sgst_total + $cgst_total;

// Optionally, format the totals
$price_amt = number_format($sub_total, 2, '.', '');
$sgst = number_format($sgst_total, 2, '.', '');
$cgst = number_format($cgst_total, 2, '.', '');
$amount = number_format($grand_total, 2, '.', '');

$html .= '
            <tr>
              <td style="border-bottom: 0.5px solid #dee2e6; text-align: left; line-height: 3;">verification (' . $mem_array['name'] . ')</td>
              <td style="border-bottom: 0.5px solid #dee2e6;">
              ' . implode(", ", $arr) . '
              </td>
              <td style="border-bottom: 0.5px solid #dee2e6;">' . number_format($price_amt, 2, '.', '') . '</td>
              <td style="border-bottom: 0.5px solid #dee2e6;">' .number_format($sgst+ $cgst, 2, '.', '') . '<br>( CGST + SGST )</td>
              <td style="border-bottom: 0.5px solid #dee2e6; text-align: right;">' . number_format($amount, 2, '.', '') . '</td>
            </tr>';




$html .= '
          <tr>
            <th style="border-top: 1px solid #0d68a5; border-bottom: 1px solid #0d68a5; text-align: left;">Sub Total</th>
            <th style="border-top: 1px solid #0d68a5; border-bottom: 1px solid #0d68a5;">' . count($speci_id_array) . '</th>
            <th style="border-top: 1px solid #0d68a5; border-bottom: 1px solid #0d68a5;">₹ ' . number_format($price_amt, 2, '.', '') . '</th>
            <th style="border-top: 1px solid #0d68a5; border-bottom: 1px solid #0d68a5;">₹ ' . number_format($sgst+ $cgst, 2, '.', '') . '</th>
            <th style="border-top: 1px solid #0d68a5; border-bottom: 1px solid #0d68a5; text-align: right;">₹ ' . number_format($amount, 2, '.', '') . '</th>

          </tr>
          <tr>
            <td></td>
            <td></td>
            <td></td>
            <td style="border-top: 0.5px solid #dee2e6; text-align: right; line-height: 2;">TOTAL AMOUNT </td>
            <td style="border-top: 0.5px solid #dee2e6; text-align: right; line-height: 2;">₹ ' . number_format($price_amt, 2, '.', '') . '</td>
          </tr>
          <tr>
            <td></td>
            <td></td>
            <td></td>
            <td style="text-align: right; line-height: 2;">SGST</td>
            <td style="text-align: right; line-height: 2;">₹ ' . number_format($sgst, 2, '.', '') . '</td>
          </tr>
          <tr>
            <td></td>
            <td></td>
            <td></td>
            <td style="text-align: right; line-height: 2;">CGST</td>
            <td style="text-align: right; line-height: 2;">₹ ' . number_format($cgst, 2, '.', '') . '</td>
          </tr>
          <tr>
            <th></th>
            <th></th>
            <th></th>
            <th style="border-top: 0.5px solid #dee2e6; text-align: right;">GRAND TOTAL</th>
            <th style="border-top: 0.5px solid #dee2e6; text-align: right;">₹ ' . number_format($amount, 2, '.', '') . '</th>
          </tr>
          
        </tbody>
      </table>
    </div>
    <br>
    <div style="text-align: right;">
      <p style="font-size: 11px;"><b>Payment Via:- ' . $ver_array['payment_via'] . '</b></p>
    </div>
    <br>
    <br>
    <br>
    <br>
    <br>
    <div>
      <P><b>TERMS AND CONDITIONS:</b></P>
      <p style="font-size: 11px; float:right;margin-left:10px; margin-top:5px;">1. Any dispute should be resolved within 30 days..</p>
      <p style="font-size: 11px; float:right;margin-left:10px; margin-top:5px;">2. All payments should be made in Indian Rupees (INR).</p>
      <p style="font-size: 11px; float:right;margin-left:10px; margin-top:5px;">3. Any legal matters will be covered in Pune jurisdiction only.</p>
      
    </div>
    <br>
    <br>
    <br>
    <div>
      <P><b>Notes:</b></P>
      <P style="font-size: 11px; margin-left:10px; margin-top:5px;">VC:- Voter Card verification</P>
      <P style="font-size: 11px; margin-left:10px; margin-top:5px;">CR:- Criminal verification</P>
      <P style="font-size: 11px; margin-left:10px; margin-top:5px;">DL:- Driving Licence verification</P>
      <P style="font-size: 11px; margin-left:10px; margin-top:5px;">AAV:- Aadhar Card verification</P>
      <P style="font-size: 11px; margin-left:10px; margin-top:5px;">PC:- Pan Card verification</P>
      <P style="font-size: 11px; margin-left:10px; margin-top:5px;">PV:- Passport verification</P>
    </div>
   
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

// Specify the target directory to save the PDF
$target_dir_profile = __DIR__ . "/active_folder/agency/invoice/verifications/" . $member_id; // Use the __DIR__ constant to get the absolute path

// Create the target directory if it doesn't exist
if (!file_exists($target_dir_profile)) {
  if (!mkdir($target_dir_profile, 0777, true)) {
    die('Failed to create directory...');
  } else {
    $file_name = 'invoice_' . date("YmdHis") . '_1.pdf';

  }
} else {
  $file_count = count(scandir($target_dir_profile)) - 2;

  $new_file_count = $file_count + 1;
  $file_name = 'invoice_' . date("YmdHis") . '_' . $new_file_count . '.pdf';
}

// $file_name = 'invoice_' . date("YmdHis") . '.pdf';

// Save the PDF file to the specified directory
file_put_contents($target_dir_profile . '/' . $file_name, $output_pdf);

// Construct the base URL with the correct extension
  $base_url = "https://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/active_folder/agency/invoice/verifications/$member_id/$file_name";

// Update the database with the PDF URL
 $url_up_query = "UPDATE `verification_payment_transaction_all` SET invoice_url='$base_url' WHERE `request_id` = '$req_id' and `person_id`='$member_id'";
$agency_result = $mysqli1->query($url_up_query);


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

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
  /*  $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true  // This should be true for allowing self-signed certificates
        )
    );*/

    // Recipients
        //$mail->setFrom('info@microintegrated.in', 'Micro Integrated Semi Conductor Systems Pvt. Ltd.');
    $mail->addAddress($email);

    // Content
    $mail->isHTML(true);


$mail->Subject = 'Payment for Verification - Invoice No: 7099 from VocoXp';

    // HTML email content
    $html = '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment for Verification - Invoice No: 7099 from VocoXp</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            padding: 20px;
            border: 1px solid #dddddd;
        }
        .email-header {
            text-align: center;
            margin-bottom: 20px;
        }
        .email-header h1 {
            margin: 0;
            color: #333333;
        }
        .email-body {
            font-size: 16px;
            color: #555555;
            line-height: 1.5;
        }
        .email-body p {
            margin: 10px 0;
        }
        .email-body ul {
            padding-left: 20px;
        }
        .email-footer {
            margin-top: 20px;
            text-align: center;
            font-size: 14px;
            color: #777777;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h1>Payment for Verification - Invoice No: 7099 from VocoXp</h1>
        </div>
        <div class="email-body">
            <p>Dear '.$name.',</p>
            <p>Thank you for your recent verification with VocoXp!</p>
            <p><strong>Invoice Details:</strong></p>
            <ul>
              <li>Invoice No:- '.$rand.'</li>
            <li>Invoice Date:- '.date("d-m-Y H:i:s", strtotime($system_date_time)).'</li>
            <li>Date of Verification:- '.date("d-m-Y", strtotime($system_date)).'</li>
            <li>Total Amount paid: Rs. '.number_format($amount, 2, '.', '').' /-</li>
            </ul>
            <p>Please find the attached invoice for your reference.</p>
            <p>If you have any questions or require assistance, please don\'t hesitate to reach out to our support team at <a href="mailto:support@microintegrated.in">support@microintegrated.in</a>.</p>
            <p>Thank you for choosing VocoXp! We look forward to serving you again soon.</p>
        </div>
        <div class="email-footer">
            <p>Best Regards,</p>
            <p>Micro Integrated Semi Conductor Systems Pvt. Ltd.</p>
        </div>
    </div>
</body>
</html>';
$mail->Body = $html;
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

?>
