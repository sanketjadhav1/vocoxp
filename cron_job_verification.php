<?php
// Include required files
include_once "connection.php";

date_default_timezone_set('Asia/Kolkata');
// Database connection
$connection = connection::getInstance();
$mysqli = $connection->getConnection();

$connection1 = database::getInstance();
$mysqli1 = $connection1->getConnection();
$curr=date("d-m-Y");
$curr1=date("Y-m-d");
//$curr1=date("2024-06-01");
// Fetch wallet transactions Aadhar = 1
$fetch_aadhar_wallet = "SELECT SUM(`amount`) as `total` FROM `wallet_transaction_all` WHERE `verification_id` = '1' AND `source_from` = '1' AND `status` = 'success' AND `purpose` = 'verification' AND DATE_FORMAT(`date`, '%m, %d')= DATE_FORMAT('$curr1', '%m, %d')";

// Fetch wallet transactions 2=Pan
$fetch_pan_wallet = "SELECT SUM(`amount`) as `total` FROM `wallet_transaction_all` WHERE `verification_id` = '2' AND `source_from` = '1' AND `status` = 'success' AND `purpose` = 'verification' AND DATE_FORMAT(`date`, '%m, %d')= DATE_FORMAT('$curr1', '%m, %d')";

// Fetch wallet transactions 3=DL
$fetch_dl_wallet = "SELECT SUM(`amount`) as `total` FROM `wallet_transaction_all` WHERE `verification_id` = '3' AND `source_from` = '1' AND `status` = 'success' AND `purpose` = 'verification' AND DATE_FORMAT(`date`, '%m, %d')= DATE_FORMAT('$curr1', '%m, %d')";

// Fetch wallet transactions 4=Voter
$fetch_voter_wallet = "SELECT SUM(`amount`) as `total` FROM `wallet_transaction_all` WHERE `verification_id` = '4' AND `source_from` = '1' AND `status` = 'success' AND `purpose` = 'verification' AND DATE_FORMAT(`date`, '%m, %d')= DATE_FORMAT('$curr1', '%m, %d')";

// Fetch wallet transactions 5=Passport
$fetch_passport_wallet = "SELECT SUM(`amount`) as `total` FROM `wallet_transaction_all` WHERE `verification_id` = '5' AND `source_from` = '1' AND `status` = 'success' AND `purpose` = 'verification' AND DATE_FORMAT(`date`, '%m, %d')= DATE_FORMAT('$curr1', '%m, %d')";

$aadhar_wallet=mysqli_query($mysqli, $fetch_aadhar_wallet);
$pan_wallet=mysqli_query($mysqli, $fetch_pan_wallet);
$dl_wallet=mysqli_query($mysqli, $fetch_dl_wallet);
$voter_wallet=mysqli_query($mysqli, $fetch_voter_wallet);
$passport_wallet=mysqli_query($mysqli, $fetch_passport_wallet);

$arr_aadhar_wallet=mysqli_fetch_assoc($aadhar_wallet);
$arr_pan_wallet=mysqli_fetch_assoc($pan_wallet);
$arr_dl_wallet=mysqli_fetch_assoc($dl_wallet);
$arr_voter_wallet=mysqli_fetch_assoc($voter_wallet);
$arr_passport_wallet=mysqli_fetch_assoc($passport_wallet);

$aadhar = $arr_aadhar_wallet['total'];
$pan = $arr_pan_wallet['total'];
$dl = $arr_dl_wallet['total'];
$voter = $arr_voter_wallet['total'];
$passport = $arr_passport_wallet['total'];

$mail = new PHPMailer(true);

try {
    // Clear any previous output
    ob_clean();

    // Set mailer to use SMTP
     $mail->isSMTP();
        $mail->Host = 'mail.mounarchtech.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'transactions@mounarchtech.com';
        $mail->Password = 'Mtech!@12345678';  // Ideally, load this from environment variables
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;
        $mail->setFrom('transactions@mounarchtech.com', 'Micro Integrated Semi Conductor Systems Pvt. Ltd.');
        $mail->isHTML(true);

    // Add recipient
    // $mail1->addAddress("niraj07.k@gmail.com");
    $mail->addAddress("namrata.r.shrivas@gmail.com");
    // $mail1->addAddress("adesh.tripathi2010@gmail.com");

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
            <p>Invoice for all verification done today</p>
            
            <h4>Amount collected in Aadhar Verification : Rs. '. number_format($aadhar, 2, '.', '').' /-</h4>
            <h4>Amount collected in Pan Verification : Rs. '. number_format($pan, 2, '.', '').' /-</h4>
            <h4>Amount collected in DL Verification : Rs. '. number_format($dl, 2, '.', '').' /-</h4>
            <h4>Amount collected in Voter Verification : Rs. '. number_format($voter, 2, '.', '').' /-</h4>
            <h4>Amount collected in Passport Verification : Rs. '. number_format($passport, 2, '.', '').' /-</h4>
            
            <p><b>Best regards,</b></p>
            <p>Mounarch Tech Solutions & System Pvt. Ltd.</p>
        </div>
        <div class="email-header">
            <img src="https://mounarchtech.com/vocoxp/newLogo.png" alt="Company Logo" style="width:150px; height:auto;">
        </div>
    </div>
</body> 
</html>';

;

    // Set email format to HTML
    $mail->isHTML(true);
    $mail->Subject = "Amount Collected Today" .$curr."";
    $mail->Body = $messageBody;

    // Send the email
    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
?>
