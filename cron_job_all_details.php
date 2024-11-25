<?php
// Include required files
include_once "connection.php";
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';
require __DIR__ . '/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
date_default_timezone_set('Asia/Kolkata');
// Database connection
$connection = connection::getInstance();
$mysqli = $connection->getConnection();

$connection1 = database::getInstance();
$mysqli1 = $connection1->getConnection();
$curr=date("d-m-Y");
$curr1=date("Y-m-d");
//$curr1=date("2024-06-01");
// Fetch wallet transactions
$fetch_wallet = "SELECT SUM(`amount`) as `total` FROM `wallet_transaction_all` WHERE `status` = 'success' AND `purpose` = 'wallet recharge' AND DATE_FORMAT(`date`, '%m, %d')= DATE_FORMAT('$curr1', '%m, %d');";

$res_wallet=mysqli_query($mysqli, $fetch_wallet);
$arr_wallet=mysqli_fetch_assoc($res_wallet);
$recharge1 = $arr_wallet['total'];


$fetch_verification = "SELECT `net_amount` FROM verification_payment_transaction_all WHERE`payment_via`='online' AND DATE_FORMAT(`created_on`, '%m, %d') = DATE_FORMAT('$curr1', '%m, %d')
";
$res_verification = mysqli_query($mysqli1, $fetch_verification);


while ($arr_verification = mysqli_fetch_assoc($res_verification)) {
    
        
        $online_verification+= $arr_verification['net_amount'];
 
}

//aadhar
$fetch_aadhar_online="SELECT SUM(`price`) + SUM(`cgst_amount`) + SUM(`sgst_amount`) AS `total_amount` FROM `aadhar_transaction_all` WHERE DATE_FORMAT(`created_on`, '%m-%d') = DATE_FORMAT('$curr1', '%m-%d') AND `request_id` IN ( SELECT `request_id` FROM `verification_payment_transaction_all` WHERE `payment_via` = 'online');";
$res_aadhar_online=mysqli_query($mysqli1, $fetch_aadhar_online);
$arr_aadhar_online=mysqli_fetch_assoc($res_aadhar_online);

$fetch_aadhar_wallet="SELECT SUM(`price`) + SUM(`cgst_amount`) + SUM(`sgst_amount`) AS `total_amount` FROM `aadhar_transaction_all` WHERE DATE_FORMAT(`created_on`, '%m-%d') = DATE_FORMAT('$curr1', '%m-%d') AND `request_id` IN ( SELECT `request_id` FROM `verification_payment_transaction_all` WHERE `payment_via` = 'wallet');";
$res_aadhar_wallet=mysqli_query($mysqli1, $fetch_aadhar_wallet);
$arr_aadhar_wallet=mysqli_fetch_assoc($res_aadhar_wallet);

//pan
$fetch_pan_online="SELECT SUM(`price`) + SUM(`cgst_amount`) + SUM(`sgst_amount`) AS `total_amount` FROM `pan_transaction_all` WHERE DATE_FORMAT(`created_on`, '%m-%d') = DATE_FORMAT('$curr1', '%m-%d') AND `request_id` IN ( SELECT `request_id` FROM `verification_payment_transaction_all` WHERE `payment_via` = 'online');";
$res_pan_online=mysqli_query($mysqli1, $fetch_pan_online);
$arr_pan_online=mysqli_fetch_assoc($res_pan_online);

$fetch_pan_wallet="SELECT SUM(`price`) + SUM(`cgst_amount`) + SUM(`sgst_amount`) AS `total_amount` FROM `pan_transaction_all` WHERE DATE_FORMAT(`created_on`, '%m-%d') = DATE_FORMAT('$curr1', '%m-%d') AND request_id IN ( SELECT `request_id` FROM `verification_payment_transaction_all` WHERE `payment_via` = 'wallet');";
$res_pan_wallet=mysqli_query($mysqli1, $fetch_pan_wallet);
$arr_pan_wallet=mysqli_fetch_assoc($res_pan_wallet);

//dl
$fetch_dl_online="SELECT SUM(`price`) + SUM(`cgst_amount`) + SUM(`sgst_amount`) AS `total_amount` FROM `driving_license_transaction_all` WHERE DATE_FORMAT(`created_on`, '%m-%d') = DATE_FORMAT('$curr1', '%m-%d') AND `request_id` IN ( SELECT `request_id` FROM `verification_payment_transaction_all` WHERE `payment_via` = 'online');";
$res_dl_online=mysqli_query($mysqli1, $fetch_dl_online);
$arr_dl_online=mysqli_fetch_assoc($res_dl_online);

$fetch_dl_wallet="SELECT SUM(`price`) + SUM(`cgst_amount`) + SUM(`sgst_amount`) AS `total_amount` FROM `driving_license_transaction_all` WHERE DATE_FORMAT(`created_on`, '%m-%d') = DATE_FORMAT('$curr1', '%m-%d') AND request_id IN ( SELECT `request_id` FROM `verification_payment_transaction_all` WHERE `payment_via` = 'wallet');";
$res_dl_wallet=mysqli_query($mysqli1, $fetch_dl_wallet);
$arr_dl_wallet=mysqli_fetch_assoc($res_dl_wallet);

//voter
$fetch_voter_online="SELECT SUM(`price`) + SUM(`cgst_amount`) + SUM(`sgst_amount`) AS `total_amount` FROM `voter_transaction_all` WHERE DATE_FORMAT(`created_on`, '%m-%d') = DATE_FORMAT('$curr1', '%m-%d') AND `request_id` IN ( SELECT `request_id` FROM `verification_payment_transaction_all` WHERE `payment_via` = 'online');";
$res_voter_online=mysqli_query($mysqli1, $fetch_voter_online);
$arr_voter_online=mysqli_fetch_assoc($res_voter_online);

$fetch_voter_wallet="SELECT SUM(`price`) + SUM(`cgst_amount`) + SUM(`sgst_amount`) AS `total_amount` FROM `voter_transaction_all` WHERE DATE_FORMAT(`created_on`, '%m-%d') = DATE_FORMAT('$curr1', '%m-%d') AND request_id IN ( SELECT `request_id` FROM `verification_payment_transaction_all` WHERE `payment_via` = 'wallet');";
$res_voter_wallet=mysqli_query($mysqli1, $fetch_voter_wallet);
$arr_voter_wallet=mysqli_fetch_assoc($res_voter_wallet);

//crime
$fetch_crime_online="SELECT SUM(`price`) + SUM(`cgst_amount`) + SUM(`sgst_amount`) AS `total_amount` FROM `ecrime_transaction_all` WHERE DATE_FORMAT(`created_on`, '%m-%d') = DATE_FORMAT('$curr1', '%m-%d') AND `request_id` IN ( SELECT `request_id` FROM `verification_payment_transaction_all` WHERE `payment_via` = 'online');";
$res_crime_online=mysqli_query($mysqli1, $fetch_crime_online);
$arr_crime_online=mysqli_fetch_assoc($res_crime_online);

$fetch_crime_wallet="SELECT SUM(`price`) + SUM(`cgst_amount`) + SUM(`sgst_amount`) AS `total_amount` FROM `ecrime_transaction_all` WHERE DATE_FORMAT(`created_on`, '%m-%d') = DATE_FORMAT('$curr1', '%m-%d') AND request_id IN ( SELECT `request_id` FROM `verification_payment_transaction_all` WHERE `payment_via` = 'wallet');";
$res_crime_wallet=mysqli_query($mysqli1, $fetch_crime_wallet);
$arr_crime_wallet=mysqli_fetch_assoc($res_crime_wallet);


$mail1 = new PHPMailer(true);

try {
    // Clear any previous output
    ob_clean();

    // Set mailer to use SMTP
	 $mail1->isSMTP();
        $mail1->Host = 'mail.mounarchtech.com';
        $mail1->SMTPAuth = true;
        $mail1->Username = 'transactions@mounarchtech.com';
        $mail1->Password = 'Mtech!@12345678';  // Ideally, load this from environment variables
        $mail1->SMTPSecure = 'ssl';
        $mail1->Port = 465;
        $mail1->setFrom('transactions@mounarchtech.com', 'Micro Integrated Semi Conductor Systems Pvt. Ltd.');
        $mail1->isHTML(true);

    // Set email subject and sender
    $mail1->setFrom('info@microintegrated.in', 'Micro Integrated Semi Conductor Systems Pvt. Ltd.');

    // Add recipient
    $mail1->addAddress("niraj07.k@gmail.com");
    $mail1->addAddress("namrata.r.shrivas@gmail.com");
    $mail1->addAddress("rishabhshinde1122@gmail.com");
    $mail1->addReplyTo('info@microintegrated.in');

    // Create email body

$messageBody = '<!DOCTYPE html>
<html>
<head>
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans&display=swap" rel="stylesheet">
    <style>
        .email-body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .email-header {
            background-color: #f0f0f0;
            padding: 20px;
            text-align: center;
        }
        .email-content {
            padding: 20px;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            margin: 20px 0;
            font-size: 16px;
            color: white;
            background-color: #007BFF;
            text-decoration: none;
            border-radius: 5px;
        }
        .email-footer {
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="email-body">
        
        <div class="email-content">
            <p>Dear Sir,</p>
            <p>Date of Report:- '.$curr.'</p>
            <p>This mail contains all the information of vocoxp transactions like Wallet Recharge, Online Payment, and Verification Payment (Wallet or Online) etc.</p>
            
            <h4>A. Summary of Amount Collected: </h4>
            <ul>
                <li>Wallet Recharge:- Rs. '. number_format($recharge1, 2, '.', '').' /-</li>
                <li>Online Transaction:- Rs. '. number_format($online_verification, 2, '.', '') .' /-</li>
                
            </ul>
            <h4>B. Details of Online Verification:</h4>
            <p>Aadhar Verification</p>
             <ul>
                
                    <li>Online Amount: Rs. '.number_format($arr_aadhar_online['total_amount'], 2, '.', '').' /-</li>
                    <li>Wallet Amount: Rs. '.number_format($arr_aadhar_wallet['total_amount'], 2, '.', '').' /-</li>
               </ul>
            <p>Pan Verification</p>
             <ul>
                
                    <li>Online Amount: Rs. '.number_format($arr_pan_online['total_amount'], 2, '.', '').' /-</li>
                    <li>Wallet Amount: Rs. '.number_format($arr_pan_wallet['total_amount'], 2, '.', '').' /-</li>
               </ul>
            <p>driving license Verification</p>
             <ul>
                
                    <li>Online Amount: Rs. '.number_format($arr_dl_online['total_amount'], 2, '.', '').' /-</li>
                    <li>Wallet Amount: Rs. '.number_format($arr_dl_wallet['total_amount'], 2, '.', '').' /-</li>
               </ul>
            <p>Voter Verification</p>
             <ul>
                
                    <li>Online Amount: Rs. '.number_format($arr_voter_online['total_amount'], 2, '.', '').' /-</li>
                    <li>Wallet Amount: Rs. '.number_format($arr_voter_wallet['total_amount'], 2, '.', '').' /-</li>
               </ul>
            <p>E-crime Verification</p>
             <ul>
                
                    <li>Online Amount: Rs. '.number_format($arr_crime_online['total_amount'], 2, '.', '').' /-</li>
                    <li>Wallet Amount: Rs. '.number_format($arr_crime_wallet['total_amount'], 2, '.', '').' /-</li>
               </ul>
               <p>If you have any questions or require assistance, please don\'t hesitate to reach out to our support team at <a href="mailto:support@microintegrated.in">support@microintegrated.in</a>.</p>
               <p>Thank you for choosing our service for Identity verification. We value your trust and look forward to continuing to serve you.</p>
            <p><b>Best regards,</b></p>
            <p>Micro Integrated Semi Conductor Systems Pvt. Ltd. </p>
        </div>
        
    </div>
</body> 
</html>';

;

    // Set email format to HTML
    $mail1->isHTML(true);
    $mail1->Subject = "VocoXp Transactions Overview";
    $mail1->Body = $messageBody;

    // Send the email
    $mail1->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail1->ErrorInfo}";
}
?>
