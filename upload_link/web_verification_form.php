 
<?php
error_reporting(1);
include_once '../connection.php';
$connection = connection::getInstance();

$mysqli = $connection->getConnection();



$connection1 = database::getInstance();

$mysqli1 = $connection1->getConnection();

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    
    $enduser_id = $_GET['enduser_id'];
     $select_end_details = "SELECT  agency_id,bulk_id, name, mobile,email_id, verification_report, verification_details, weblink_opened_on,payment_from FROM bulk_end_user_transaction_all WHERE end_user_id = '$enduser_id'";
    $result = $mysqli->query($select_end_details);
    $select_array = mysqli_fetch_assoc($result);

    $fetch_agency = "SELECT `company_name`, `agency_logo`, `address`, `mobile_no` FROM `agency_header_all` WHERE `agency_id`='".$select_array["agency_id"]."'";
    $res_agency = mysqli_query($mysqli, $fetch_agency);
    $arr_agency = mysqli_fetch_assoc($res_agency);
    $name=$select_array['name'];
    $mobile=$select_array['mobile'];
    $email_id=$select_array['email_id'];
    $agency_id=$select_array['agency_id'];
    $weblink_opened_on=$select_array['weblink_opened_on'];
    $payment_done_by = $select_array['payment_from'];
    $bulk_id = $select_array['bulk_id'];
 
    $otp = rand(10000, 99999);
   
}
    $query_delete = "DELETE FROM `bulk_end_user_transaction_all` WHERE `bulk_id`='$bulk_id' AND `name`='' OR `scheduled_verifications`=''";
    $res_query_delete = mysqli_query($mysqli, $query_delete);

     $select_total_count = "SELECT COUNT(id) as count  FROM `bulk_end_user_transaction_all` WHERE `bulk_id`='$bulk_id'";
    $result_select_count = $mysqli->query($select_total_count);
    $arr_select_count = mysqli_fetch_assoc($result_select_count);
    $total_count=$arr_select_count['count']; //total count

    $select_partial_count = "SELECT COUNT(id) as partial_count  FROM `bulk_end_user_transaction_all` WHERE `bulk_id`='$bulk_id' AND `status` = '2'";
    $result_select_partial_count = $mysqli->query($select_partial_count);
    $arr_select_partial_count = mysqli_fetch_assoc($result_select_partial_count);
    $partial_count=$arr_select_partial_count['partial_count']; //partial count

    $select_complete_count = "SELECT COUNT(id) as complete_count  FROM `bulk_end_user_transaction_all` WHERE `bulk_id`='$bulk_id' AND `status` = '3'";
    $result_select_complete_count = $mysqli->query($select_complete_count);
    $arr_select_complete_count = mysqli_fetch_assoc($result_select_complete_count);
    $complete_count=$arr_select_complete_count['complete_count']; //complete count

    $query = "UPDATE `bulk_upload_file_information_all` 
          SET `total_end_user`='$total_count',`total_partial_done`='$partial_count',`total_completed`='$complete_count' 
          WHERE `bulk_id` = '$bulk_id' 
          AND `agency_id` = '$agency_id'";
          
        $res_query = mysqli_query($mysqli, $query);
 
function maskNumber($number)
{
    $numStr = strval($number);
    $maskedNum = str_repeat('X', strlen($numStr) - 3) . substr($numStr, -3);
    return $maskedNum;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Registration</title> 
    <!-- Bootstrap CDN Links -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap CDN Links -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
     
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <!-- jQuery (necessary for Toastr) -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Toastr JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <style>
        .container {
            padding: 20px;
            border-radius: 10px;
            border: 1px solid #ced4da;
            /* Bootstrap form-control border color */
            box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
            /* Bootstrap form-control box shadow */
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        #otp-section {
            width: 20vw;
            margin: auto;
            padding: 15px;
            border-radius: 0.25rem;
            /* Bootstrap form-control border radius */
            background-color: #f8f9fa;
            /* Light gray background */
            border: 1px solid #ced4da;
            /* Bootstrap form-control border color */
            box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
            /* Bootstrap form-control box shadow */
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;

        }

        .file-upload-section {
            width: 40vw;
            margin: auto;
        }

        .otp-section,
        .file-upload-section {
            display: none;
        }

        .centered {
            text-align: center;
        }

        .mt-3 {
            margin-top: 1rem;
        }

        .countdown {
            font-size: 0.9rem;
            color: gray;
        }

        .logo {
            width: 10vw;
            margin: auto;
        }

        #otp-section {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            min-height: 200px;
            /* Adjust as needed */
        }

        /* Center the elements inside the mb-3 div */
        .mb-3 {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        /* Set margin-top for spacing between elements */
        .otp-input,
        .otp-button,
        #requestOtp,
        #verifytxt {
            margin-top: 15px;
        }

        .progress-container {
            width: 100%;

        }

        #progressBarContainer {
            width: 100%;
        }

        #upload-excel {
            width: 100%;
        }

        #progressBar {
            text-align: center;
        }

        #family-section {
            width: 60vw;
            margin: auto;
        }
         #payment-section {
            width: 60vw;
            margin: auto;
        }
         #verification-section {
            width: 60vw;
            margin: auto;
        }
    </style>
      <style>
        
        .captcha-container {
            background-color: red;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .captcha-images {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }
        .captcha-images img {
            margin: 5px;
            cursor: pointer;
            border: 2px solid transparent;
            border-radius: 5px;
            width: 100px;
            height: 100px;
        }
        .captcha-images img.selected {
            border-color: #00f;
        }
        .message {
            margin-top: 10px;
            color: green;
        }
        .error {
            color: red;
        }
:root {
    --primary-color: #4EA685;
    --secondary-color: #57B894;
    --black: #000000;
    --white: #ffffff;
    --gray: #efefef;
    --gray-2: #757575;

    --facebook-color: #4267B2;
    --google-color: #DB4437;
    --twitter-color: #1DA1F2;
    --insta-color: #E1306C;
}
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600&display=swap');

* {
    font-family: 'Poppins', sans-serif;
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}
.form-wrapper {
    width: 100%;
    max-width: 28rem;
}
 

.input-group {
    position: relative;
    width: 100%;
    margin: 1rem 0;
}

.input-group i {
    position: absolute;
    top: 50%;
    left: 1rem;
    transform: translateY(-50%);
    font-size: 1.4rem;
    color: var(--gray-2);
}

.input-group input {
    width: 100%;
    padding: 1rem 3rem;
    font-size: 1rem;
    background-color: var(--gray);
    border-radius: .5rem;
    border: 0.125rem solid var(--white);
     
}

.input-group input:focus {
    border: 0.125rem solid var(--primary-color);
}
.form button {
    cursor: pointer;
    width: 100%;
    padding: .6rem 0;
    border-radius: .5rem;
    border: none;
    background-color: var(--primary-color);
    color: var(--white);
    font-size: 1.2rem;
    outline: none;
}
 
.form p {
    margin: 1rem 0;
    font-size: .7rem;
}

.flex-col {
    flex-direction: column;
}
.text {
    margin: 4rem;
    color: var(--white);
}

.text h2 {
    font-size: 3.5rem;
    font-weight: 800;
    margin: 2rem 0;
    transition: 1s ease-in-out;
}

.text p {
    font-weight: 600;
    transition: 1s ease-in-out;
    transition-delay: .2s;
}

.img img {
    width: 30vw;
    transition: 1s ease-in-out;
    transition-delay: .4s;
}
.text.sign-in h2,
.text.sign-in p,
.img.sign-in img {
    transform: translateX(-250%);
}

.text.sign-up h2,
.text.sign-up p,
.img.sign-up img {
    transform: translateX(250%);
}
.social-list {
    margin: 2rem 0;
    padding: 1rem;
    border-radius: 1.5rem;
    width: 100%;
    box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
    transform: scale(0);
    transition: .5s ease-in-out;
    transition-delay: 1.2s;
}

.social-list>div {
    color: var(--white);
    margin: 0 .5rem;
    padding: .7rem;
    cursor: pointer;
    border-radius: .5rem;
    cursor: pointer;
    transform: scale(0);
    transition: .5s ease-in-out;
}

.social-list>div:nth-child(1) {
    transition-delay: 1.4s;
}

.social-list>div:nth-child(2) {
    transition-delay: 1.6s;
}

.social-list>div:nth-child(3) {
    transition-delay: 1.8s;
}

.social-list>div:nth-child(4) {
    transition-delay: 2s;
}

.social-list>div>i {
    font-size: 1.5rem;
    transition: .4s ease-in-out;
}

.social-list>div:hover i {
    transform: scale(1.5);
}

.facebook-bg {
    background-color: var(--facebook-color);
}

.google-bg {
    background-color: var(--google-color);
}

.twitter-bg {
    background-color: var(--twitter-color);
}

.insta-bg {
    background-color: var(--insta-color);
}

.pointer {
    cursor: pointer;
}
    </style>
    
</head>

<div class="container mt-5 centered" id="container">

    <img src="<?php echo $arr_agency['agency_logo']; ?> " alt="Company Logo" class="logo">
    <h4><?php echo $arr_agency['address'] ?></h4>
    <input type="hidden" name="agency_id" id="agency_id" value="<?php echo $agency_id; ?>">
    <input type="hidden" name="gst_number" id="gst_number" value="<?php echo $arr_agency["gst_number"]; ?>">
    <input type="hidden" name="end_user_id" id="end_user_id" value="<?php echo $enduser_id; ?>">
    <input type="hidden" name="bulk_id" id="bulk_id" value="<?php echo $bulk_id; ?>">
    <input type="hidden" name="mobile" id="mobile" value="<?php echo $mobile; ?>">
    <input type="hidden" name="email" id="email" value="<?php echo $email_id; ?>">
    <input type="hidden" name="name" id="name" value="<?php echo $name; ?>">
    <div class="row">
       <div class="col-sm-4"></div>
 <div class="col align-items-center flex-col sign-in" id="captch">
                <div class="form-wrapper align-items-center">
                    <div class="form sign-in">
                        <h1>Human Verification</h1>
                        <p>Before we move forward, kindly follow the instructions given below </p>
                       
                        
                        <div id="captchaQuestion"></div>
                        <div class="captcha-images" id="captchaImages" style="display:none;">
                        </div>
                        <div id="alphanumericCaptcha" style="display:none;">
                        <div class="input-group">
                            <input type="text" id="captchaInput">
                        </div>
                            <div style="display: none;"  id="captchaCodeDisplay" style="margin-top: 10px; margin-bottom:10px;"></div>
                            <div style="display:flex;justify-content: center;">
                            <canvas id="canvas" width="300" height="50"></canvas>       
                            <input type="image" id="refreshCaptcha" style="width: 40px;height:30px;" src="../images/refresh.jpeg" alt="Submit">
                            </div>
                        </div>
                        <button class="pointer" id="submitCaptcha">
                            Verify
                        </button>
                         <input type="hidden" id="otp" name="otp" value="<?php echo $otp;?>">
                        <input type="hidden" id="mobile" name="mobile" value="<?php echo $mobile;?>">
                        <div id="message" class="message"></div>
                      
                    </div>
                    
                </div>
                <div class="form-wrapper">
        
                </div>
            
         </div>
    </div>
    <div id="otp-section" class="centered mt-3 sign-up" style="display:none;">
        <div class="mb-3 text-center">
            <h4> <?php echo maskNumber($arr_agency['mobile_no']); ?></h4>
                       <button type="button" value="send_otp" class="otp-button btn btn-primary form-control" id="submitOtp_01" style="max-width: 300px;" onclick="sendOTP()">Generate OTP</button>
            <input type="text" class="otp-input form-control mx-auto mt-3" id="userOtp" placeholder="Enter OTP" style="max-width: 300px;">
            <button type="button" class="otp-button btn btn-primary mt-3 form-control" id="verifyOtp" style="max-width: 300px;" onclick="verifyOTP()">Validate OTP</button>
            <span id="requestOtp" style="display:none;color:linear-gray; cursor: pointer;" onclick="sendOTP()">Request OTP</span>
            <span id="verifytxt" style="display: none; margin-top: 10px;">OTP Verified</span>
        </div>
    </div>


    <div id="family-section" class="card" style="display:none;">
            <div class="card-header bg-warning text-white">Family Members</div>
                <div class="card-body">
                        <?php 
                            $a = 1;

                            // Main query to get all users based on agency and bulk_id
                            $end_user_main = "SELECT * FROM `bulk_end_user_transaction_all` WHERE `agency_id`='" . $select_array["agency_id"] . "' AND `end_user_id`='" . $enduser_id . "'  AND `bulk_id`='" . $bulk_id . "'";
                            
                            $end_user_main_result = $mysqli->query($end_user_main);
                            
                            while ($end_user_main_array = mysqli_fetch_assoc($end_user_main_result)) {
                                
                                // Create the array of ref_enduser_id and enduser_id
                                $array = $end_user_main_array["ref_enduser_id"] . "," . $enduser_id;
                                // print_r($array);
                                $values_array = explode(",", $array);
                                
                                // Clean up the array by trimming spaces and removing empty values
                                $values_array = array_map('trim', $values_array);
                                $values_array = array_filter($values_array); // Remove empty elements
                                
                                // Loop through each user id in the array
                                foreach ($values_array as $enduser_id_value) {
                                    
                                    // Secondary query for each end_user_id
                                     $end_user_main1 = "SELECT * FROM `bulk_end_user_transaction_all` WHERE `end_user_id`='" . trim($enduser_id_value) . "'";
                                    $end_user_main1_result = $mysqli->query($end_user_main1);
                                     if ($end_user_main1_result->num_rows > 0) {
                                    $end_user_main_array1 = mysqli_fetch_assoc($end_user_main1_result);
                                    
                                    // Get the object number and bulk_id
                                    $bulk_id = $end_user_main_array['bulk_id'];
                                    $obj_no = "obj_" . $end_user_main_array1["obj_no"];
                                    
                                    if (!empty($obj_no)) {
                                        
                                        // Get additional fields based on the object number
                                        $obj_name = $end_user_main_array1["obj_name"];
                                        $verifications = $obj_no . "_verifications";
                                        $mi_amt = $obj_no . "_mi_amt";
                                        $addon_amount = $obj_no . "_addon_amount";
                                        
                                        $amount_query = "SELECT $obj_no, $verifications, $mi_amt, $addon_amount FROM `bulk_weblink_request_all` WHERE `bulk_id` = '" . $bulk_id . "' AND `status`='3'";
                                        $amount_result = $mysqli->query($amount_query);
                                        $amount_row = mysqli_fetch_assoc($amount_result);
                                        
                                        // Check if the object matches the expected name
                                        if ($amount_row[$obj_no] == $obj_name) {
                                            $obj_role = $amount_row[$obj_no];
                                            $verificationss = $amount_row[$verifications];
                                            $mi_amts = $amount_row[$mi_amt];
                                            $addon_amounts = $amount_row[$addon_amount];
                                        }
                                    }
                                    
                                    // Output user information in HTML
                                    ?>
                                    <div class="row">
                                        <div class="col-sm-2">
                                            <span><?php echo $a; ?></span>
                                        </div>
                                        <div class="col-sm-4">
                                            <label><?php echo $end_user_main_array1["name"]; ?></label>
                                        </div>
                                        <div class="col-sm-4">
                                            <label><?php echo $end_user_main_array1["mobile"]; ?></label>
                                        </div>
                                        <div class="col-sm-2">
                                            <label><?php echo isset($obj_role) ? $obj_role : ''; ?></label>
                                        </div>
                                    </div><hr>
                                    <?php
                                    $a++;
                                }
                             }
                            } 
                        ?>

         
                <div class="row text-center text-white">
                    <div class="col-sm-3"></div>
                    <div class="col-sm-2">
                        <a href="#payment-section"  class="btn btn-success">Payment</a> 
                    </div>
                    <div class="col-sm-2">

                        <!-- <a href="#fillbtn" class="btn btn-warning">Fill Details</a>  -->
                    </div>
                    <div class="col-sm-2">

                        <a href="#verification-section" class="btn btn-primary">Verification</a>
                    </div>
                </div> 
            </div>
    </div><br>
     <div id="payment-section" class="card" style="display:block;">
            <div class="card-header bg-success text-white">Payment</div>
                <div class="card-body">
                       <?php 
                           $a=1;
                        $count_unpaid=0;
                        $count_paid=0;
                         $badge ="";
                         $p_status="";
                         $pdf_url="";
                            // Prepare and execute the main SQL query
                              $end_user_main = "SELECT * FROM `bulk_end_user_transaction_all` WHERE `agency_id`='".$select_array["agency_id"]."' AND `end_user_id`='".$enduser_id."' OR  `end_user_id`='".$enduser_id."' AND `bulk_id`='".$bulk_id."'";
                            $end_user_main_result = $mysqli->query($end_user_main);

                            while ($end_user_main_array = mysqli_fetch_assoc($end_user_main_result)) {
                                // Create a comma-separated array of user IDs
                                $array =  $end_user_main_array["ref_enduser_id"]. "," .$enduser_id ;
                               
                                $count_ids = count($values_array);
                                 $values_array = explode(",", $array);
                         
                                $values_array = array_map('trim', $values_array);

                                // Remove any empty elements (in case of extra commas)
                                $values_array = array_filter($values_array);
                                 

                                // Loop through each value in the array
                                foreach ($values_array as $value) {
                                    // Trim the value in case there are any spaces
                                    $enduser_id_value = trim($value);

                                    // Prepare and execute the secondary SQL query
                                    $end_user_main1 = "SELECT * FROM `bulk_end_user_transaction_all` WHERE `end_user_id`='".$enduser_id_value."'";
                                    $end_user_main1_result = $mysqli->query($end_user_main1);
                                if ($end_user_main1_result->num_rows > 0) {
                                    $end_user_main_array1 = mysqli_fetch_assoc($end_user_main1_result);
                                     

                                    // Query to get the latest payment record for the given end user and bulk ID
                                            $payment_query = "SELECT * FROM `end_user_payment_transaction_all`  
                                                              WHERE FIND_IN_SET('$enduser_id_value', REPLACE(`end_user_id`, ' ', '')) > 0 
                                                              AND `bulk_id` = '$bulk_id' ORDER BY `id` DESC LIMIT 1";

                                            $res_payment = $mysqli->query($payment_query);

                                            // Check if there's any payment record found
                                            if ($res_payment && $res_payment->num_rows > 0) {
                                                // Fetch the payment record details
                                                $payment_array = mysqli_fetch_assoc($res_payment);

                                                // Extract and clean up the list of paid end_user_id values
                                                $paid_by_array1 = array_filter(array_map('trim', explode(',', $payment_array['end_user_id'])));

                                                // Check if the current end_user_id_value is in the paid list
                                                if (in_array($enduser_id_value, $paid_by_array1)) {
                                                    $badge = '<span class="badge bg-success">P</span>';  // Paid
                                                    $p_status = "p";
                                                    $count_paid++;
                                                } else {
                                                    $badge = '<span class="badge bg-danger">N</span>';  // Not Paid
                                                    $p_status = "n";
                                                    $count_unpaid++;
                                                }

                                                // Store the PDF URL for the invoice
                                                $pdf_url = $payment_array["invoice_url"];
                                            } else {
                                                // If no payment record found, mark as Not Paid
                                                $badge = '<span class="badge bg-danger">N</span>';  // Not Paid
                                                $p_status = "n";
                                                $count_unpaid++;
                                                $pdf_url = "";  // No URL if no payment record
                                            }

                                     $bulk_id = $end_user_main_array['bulk_id'];

                                     $obj_no = "obj_" .$end_user_main_array1["obj_no"];
                                      if (!empty($obj_no))
                                       {
                                             $obj_name = $end_user_main_array1["obj_name"];
                                            

                                            $verifications = $obj_no . "_verifications";
                                            $mi_amt = $obj_no . "_mi_amt";
                                            $addon_amount = $obj_no . "_addon_amount";
                                            $verificationss ="";
                                            $mi_amts = "";
                                            $addon_amounts = "";

                                            $amount_query = "SELECT  $obj_no,$verifications,$mi_amt,$addon_amount FROM bulk_weblink_request_all WHERE bulk_id = '" . $bulk_id . "' ";
                                            $amount_result = $mysqli->query($amount_query);
                                            $amount_row = mysqli_fetch_assoc($amount_result);

                                            if($amount_row[$obj_no]==$obj_name)
                                            {
                                               $obj_role= $amount_row[$obj_no];
                                               $verificationss=  $amount_row[$verifications];
                                               $mi_amts= $amount_row[$mi_amt];
                                               $addon_amounts= $amount_row[$addon_amount];
                                            }
                                    
                                            // echo"miamt->".$mi_amts;
                                            // echo"<br>addon_amount->".$addon_amounts;

                                            $components = explode(',', $addon_amounts);

                                                $total_amount = 0;
                                                $total_amount_tax = 0;
                                                $total_amount1 = 0;
                                                $total_amount2 = 0;
                                                foreach ($components as $component) {

                                                    // print_r($component);
                                                    // Split by '=' to separate the key and the amount-part
                                                    $parts = explode('=', $component); 
                                                    // print_r($parts);
                                                    // Split the amount-part by '-' to get the amount
                                                    $amount_part = explode('-', $parts[1]); 
                                                    $amount_part_tax = explode('+', $parts[1]); 
                                                    // print_r($amount_part_tax[1]);

                                                    // Add the amount to the total
                                                    $total_amount += (float)$amount_part[1]; 
                                                    $total_amount_tax += (float)$amount_part_tax[1]; 
                                                }
                                                    // echo"<br>tAmt".$total_amount_tax;
                                                $components1 = explode(',', $mi_amts);

                                                $total_amount_client = 0;
                                                $total_amount_client_tax = 0;
                                                $total_amount1 = 0;
                                                $total_amount2 = 0;

                                                foreach ($components1 as $component) {

                                                    // print_r($component);
                                                    // Split by '=' to separate the key and the amount-part
                                                    $parts = explode('=', $component); 

                                                    // Split the amount-part by '-' to get the amount
                                                    $amount_part = explode('+', $parts[1]); 
                                                    // print_r($amount_part[1]);

                                                    // Add the amount to the total
                                                    $total_amount_client += (float)$amount_part[0]; 
                                                    $total_amount_client_tax += (float)$amount_part[1]; 
                                                }
                                                // echo"<br>mi".$total_amount_client_tax;
                                                $tax=$total_amount_tax+$total_amount_client_tax;
                                                 // $total_amount= $total_amount_client+$total_amount+$total_amount_tax+$total_amount_client_tax;
                                                 $total_amount= $total_amount_client+$total_amount;
                                                    
                                        }
                                    ?>
                                    <div class="row">
                                        <div class="col-sm-1">
                                          <?php
                                        if($p_status=="p")
                                        {
                                             
                                            ?>
                                         

                                        <?PHP 
                                        }
                                        else
                                        { 
                                         ?>

                                           <input type="checkbox" class="user-checkbox" name="user-checkbox" id="" data-tax="<?PHP echo$tax; ?>" data-objno="<?PHP echo$obj_no; ?>" data-role="<?PHP echo$obj_role; ?>" data-amount="<?php echo $total_amount; ?>" onchange="calculateTotals()" value="<?php echo $end_user_main_array1["end_user_id"]; ?>" data-endid="<?php echo $end_user_main_array1["end_user_id"]; ?>">
                                    <?PHP
                                           
                                        }
                                    ?>
                                        </div>
                                         <div class="col-sm-1">
                                            <?php echo$badge; ?> 
                                        </div>
                                        <div class="col-sm-3">
                                            <label><?php echo htmlspecialchars($end_user_main_array1["name"]); ?></label>
                                        </div>
                                        <div class="col-sm-3">
                                            <label><?php echo htmlspecialchars($end_user_main_array1["mobile"]); ?></label>
                                        </div>
                                        <div class="col-sm-1">
                                            <label><?php echo htmlspecialchars($obj_role); ?></label> <!-- Display the last obj_role -->
                                        </div>
                                         <div class="col-sm-2">
                                            <label><i class="fa fa-inr" style="font-size:14px" aria-hidden="true"></i><?php echo number_format($total_amount,2); ?>/- </label> <!-- Display the last obj_role -->
                                        </div>
                                        <div class="col-sm-1" id="download_row">
                                             <a href="<?PHP echo$pdf_url; ?>" class="btn btn-xs bg-warning"> <i style="font-size:14px" class="fa">&#xf1c1;</i> </a>
                                        </div>
                                    </div><hr> 
                                    
                                    <?php
                                    $a++;
                                }
                            }
                              
                            }
                            ?>
                           
                   <div class="row" id="total_row">
                            <input type="hidden" name="obj_no" id="obj_no"  >
                    <input type="hidden" name="parent_count" id="parent_count" >
                    <input type="hidden" name="count_unpaid" id="count_unpaid" value="<?PHP echo$count_unpaid;?>">
                    <input type="hidden" name="count_paid" id="count_paid"  value="<?PHP echo$count_paid;?>">
                        <div class="col-sm-3"></div>
                        <div class="col-sm-3">
                            <label class="pull-right" id="totalabel"><b>Total Amount (Rs.)</b></label>
                        </div>
                        <div class="col-sm-3">
                            <span id="totalAmountLabel">  </span> <!-- Total amount initialized with main user amount -->
                        </div>
                    </div>

                    <div class="row" id="tax_row">
                        <div class="col-sm-3"></div>
                        <div class="col-sm-3">
                            <span class="pull-right"><b>Tax Amount (Rs.)</b></span>
                        </div>
                        <div class="col-sm-3">
                            <span id="taxAmountLabel">   </span>
                        </div>
                    </div>

                    <div class="row" id="grd_row">
                        <div class="col-sm-3"></div>
                        <div class="col-sm-3">
                            <label class="pull-right"><b>Grand Total Amount (Rs.) </b></label>
                        </div>
                        <div class="col-sm-3">
                            <span id="grandTotalLabel">      </span>
                            <!-- <span id="grandTotalLabel1">  1    </span> -->
                             <input type="hidden" value="<?PHP echo$string;?>" id="user_ids">
                            <input type="hidden" value="" id="user_idss">

                        </div>
                    </div>

                    <hr id="pay_hr">  
               
         
                <div class="row text-center" id="pay_row">
                    <div class="col-sm-4"></div>
                    <div class="col-sm-4">
                        <button id="payment_initiate" class="btn btn-success"  onclick="sendPaymentLink();">Initiate The Payment</button> 
                        <span id="error_pay" style="color:#e93408;"></span>
                    </div>
                    
                </div> 
            </div>
    </div><br>
     <div id="verification-section" class="card" style="display:block;">
    <div class="card-header bg-primary text-white">Verification</div>
    <div class="card-body">
        <?php 
        $a = 1;
$all_complete = true;
        // Main query to get transactions for the end user
        $end_user_main = "SELECT * FROM `bulk_end_user_transaction_all` 
                          WHERE `agency_id` = '".$mysqli->real_escape_string($select_array['agency_id'])."' 
                          AND (`end_user_id` = '".$mysqli->real_escape_string($enduser_id)."' OR `end_user_id` = '".$mysqli->real_escape_string($enduser_id)."') 
                          AND `bulk_id` = '".$mysqli->real_escape_string($bulk_id)."'";

        $end_user_main_result = $mysqli->query($end_user_main);
        
        while ($end_user_main_array = mysqli_fetch_assoc($end_user_main_result)) {
            // Create an array of end user IDs
            $array = $end_user_main_array["ref_enduser_id"] . "," . $enduser_id;
            $values_array = explode(",", $array);
            $values_array = array_map('trim', $values_array); // Trim all values

            // Loop through each value in the array
            foreach ($values_array as $enduser_id_value) {
                $enduser_id_value = $mysqli->real_escape_string($enduser_id_value);

                // Secondary query for each end user
                $end_user_main1 = "SELECT * FROM `bulk_end_user_transaction_all` 
                                   WHERE `end_user_id` = '".$enduser_id_value."'";

                $end_user_main1_result = $mysqli->query($end_user_main1);

                if ($end_user_main1_result->num_rows > 0) {
                    $end_user_main_array1 = mysqli_fetch_assoc($end_user_main1_result);

                    // Get scheduled and completed verifications
                    $scheduled_verifications = $end_user_main_array1['scheduled_verifications']; 
                    $verification_done = $end_user_main_array1['verification_done'];

                    $array_schedule = explode(",", $scheduled_verifications);
                    $array_verification_done = explode(",", $verification_done);

                    // Payment status query
                     $payment_query = "SELECT * FROM `end_user_payment_transaction_all`  
                                                              WHERE FIND_IN_SET('$enduser_id_value', REPLACE(`end_user_id`, ' ', '')) > 0 
                                                              AND `bulk_id` = '$bulk_id' ORDER BY `id` DESC LIMIT 1";
                    $res_payment = $mysqli->query($payment_query);

                   // Check if there's any payment record found
                                            if ($res_payment && $res_payment->num_rows > 0) {
                                                // Fetch the payment record details
                                                $payment_array = mysqli_fetch_assoc($res_payment);

                                                // Extract and clean up the list of paid end_user_id values
                                                $paid_by_array1 = array_filter(array_map('trim', explode(',', $payment_array['end_user_id'])));

                                                // Check if the current end_user_id_value is in the paid list
                                                if (in_array($enduser_id_value, $paid_by_array1)) {
                                                    $badge = '<span class="badge bg-success">P</span>';  // Paid
                                                    $p_status = "p";
                                                    $count_paid++;
                                                } else {
                                                    $badge = '<span class="badge bg-danger">N</span>';  // Not Paid
                                                    $p_status = "n";
                                                    $count_unpaid++;
                                                }

                                                // Store the PDF URL for the invoice
                                                $pdf_url = $payment_array["invoice_url"];
                                            } else {
                                                // If no payment record found, mark as Not Paid
                                                $badge = '<span class="badge bg-danger">N</span>';  // Not Paid
                                                $p_status = "n";
                                                $count_unpaid++;
                                                $pdf_url = "";  // No URL if no payment record
                                            }
                     $bulk_id = $end_user_main_array['bulk_id'];

                                     $obj_no = "obj_" .$end_user_main_array1["obj_no"];
                    if (!empty($obj_no)) {
                        $obj_name = $end_user_main_array1["obj_name"];

                        // Get amounts and verifications
                        $verifications = $obj_no . "_verifications";
                        $mi_amt = $obj_no . "_mi_amt";
                        $addon_amount = $obj_no . "_addon_amount";

                        $amount_query = "SELECT $obj_no, $verifications, $mi_amt, $addon_amount 
                                         FROM `bulk_weblink_request_all` 
                                         WHERE `bulk_id` = '".$bulk_id."' AND `status` = '3'";

                        $amount_result = $mysqli->query($amount_query);
                        $amount_row = mysqli_fetch_assoc($amount_result);

                        if ($amount_row[$obj_no] == $obj_name) {
                            $obj_role = $amount_row[$obj_no];
                            $verificationss = $amount_row[$verifications];
                            $mi_amts = $amount_row[$mi_amt];
                            $addon_amounts = $amount_row[$addon_amount];
                        }
                    }
                    ?>
                    <div class="row">
                        <div class="col-sm-3">
                            <label><?php echo htmlspecialchars($end_user_main_array1["name"]); ?></label>
                        </div>
                        <div class="col-sm-3">
                            <label><?php echo htmlspecialchars($end_user_main_array1["mobile"]); ?></label>
                        </div>
                        <div class="col-sm-2">
                            <label><?php echo htmlspecialchars($obj_role); ?></label>
                        </div>
                        <div class="col-sm-2">
                            <?php 
                            $verify_badge = "";

                            if ($p_status == "p") {
                                // Check verification status
                                if (empty($verification_done)) {
                                    echo "<span class='badge bg-warning'>Not Initiated</span>";
                                    $verify_badge = '<a href="https://mounarchtech.com/vocoxp/verification.php?end_user_id=' . $enduser_id_value . '">
                                                     <span class="badge bg-warning"><i style="font-size:24px" class="fas fa-chevron-circle-right"></i></span></a>';
                                                     $all_complete = false; // Mark as incomplete
                                } else {
                                    // Count the number of scheduled and done verifications
                                    $schedule_count = count($array_schedule);
                                    $done_count = count(array_intersect($array_schedule, $array_verification_done));

                                    if ($done_count == $schedule_count) {
                                        echo "<span class='badge bg-success'>Complete</span>";
                                        $verify_badge = '<span class="badge bg-success"><i style="font-size:24px" class="fa fa-smile"></i></span>';
                                    } else {
                                        echo "<span class='badge bg-warning'>Partial Completed</span>";
                                        $verify_badge = '<a href="https://mounarchtech.com/vocoxp/verification.php?end_user_id=' . $enduser_id_value . '">
                                                         <span class="badge bg-warning"><i style="font-size:24px" class="fas fa-chevron-circle-right"></i></span></a>';
                                                         $all_complete = false; // Mark as incomplete
                                    }
                                }
                            } else {
                                echo "<span class='badge bg-danger'>Not Paid</span>";
                                $all_complete = false; // Mark as incomplete
                            }
                            ?>
                        </div>
                        <div class="col-sm-1">
                            <?php echo $verify_badge; ?>
                        </div>
                    </div>
                    <hr>
                    <?php
                    $a++;
                }
            }
        }

        // Display final message based on all_complete status
if ($all_complete) {
    $check_status = "SELECT * FROM `bulk_end_user_transaction_all` WHERE `bulk_id`='$bulk_id' AND `status`!='3'";
    $amount_result = $mysqli->query($check_status);

    if ($amount_result) {
        $row_count = $amount_result->num_rows;
        if ($row_count == 0) {
            // Update the table with status 5 if no rows are found
            $sql_query=mysqli_query($mysqli,"UPDATE `bulk_weblink_request_all` SET status='5' WHERE `bulk_id`='$bulk_id'");
        } else {
            // Do nothing if the count is not zero       
        }
    } else {
        echo "Error: " . $mysqli->error;
    }

} 
        ?>
    </div>
</div>


</div>



</div>

<!-- modal for showing excel file validation error  -->
<div class="modal fade" id="payModal" tabindex="-1"   aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
 <script>

    $(document).ready(function() {
       

        //   $('#paymentbtn').click(function() {

        //     $('#family-section').hide();
        //     $('#payment-section').show();
        //     $('#verification-section').hide();
        //     $('#fill-section').hide();
        // });
        //     $('#fillbtn').click(function() {

        //     $('#family-section').hide();
        //     $('#fill-section').show();
        //     $('#verification-section').hide();
        //     $('#payment-section').hide();
        // });
        //     $('#verificationbtn').click(function() {

        //     $('#family-section').hide();
            // $('#payment-section').hide();
        //     $('#verification-section').show();
        //     $('#fill-section').hide();
        // });
            let count_unpaid=$("#count_unpaid").val();
            let count_paid=$("#count_paid").val();
            // alert(count_unpaid);
            if(count_paid > 0 )
            {
                   $("#payment_initiate").hide();
                   $("#payment_initiate").hide();
                   $("#total_row").hide();
                   $("#tax_row").hide();
                   $("#grd_row").hide();
                   $("#pay_hr").hide();
                   $("#pay_row").hide();
                   $('#family-section').show();
                   $('#verification-section').show();
                   $('#otp-section').hide(); 
                
            }
            else
            {
               $('#download_row').hide();
               $('#verification-section').hide();
                $('#payment-section').show();
                $('#otp-section').hide();

            }
    // Function to calculate totals
    function calculateTotals() {
        let tax = 0;
        let totalAmount = 0;
        let student_count=0;
        let parent_count=0;
       var checkedValues = [];
       var obj_no = [];
         // $('input[name="user-checkbox"]').first().prop('checked', true);
        const checkedCount = $('.user-checkbox:checked').length;
        // Loop through checkboxes and sum up `data-amount` of checked boxes
        $('.user-checkbox').each(function() {
             
            if (checkedCount > 0) 
            {
                    $('#payment_initiate').show();
                    $("#error_pay").text("  ");
                   
                if ($(this).is(':checked')) 
                {
                     checkedValues.push($(this).val());
                     obj_no.push($(this).data('objno'));
                    tax += parseFloat($(this).data('tax'));
                    totalAmount += parseFloat($(this).data('amount'));
                    let role=$(this).data('role');
                     
                     // Get data-amount and add to total
                     $("#payment_initiate").show();
                $("#pay_hr").show();
                $("#pay_row").show();
                 $("#total_row").show();
                   $("#tax_row").show();
                   $("#grd_row").show();
                }
            }
            else
            {

                     // student_count++; 
                     // parent_count++; 
                    
                    $("#error_pay").text("Please select atleast one checkbox");
                    $('#payment_initiate').hide();
            }
        });

             var result = checkedValues.join(', ');
              $('#user_idss').val(result);
                var result_obj_no = obj_no.join(', ');
              $('#obj_no').val(result_obj_no);
        // Update total amount in the HTML
        // $('#user_idss').val(end_user_id);
        $('#totalAmountLabel').text(totalAmount.toFixed(2));

        // Calculate tax (18%) and grand total
        let taxAmount =tax;
        let grandTotal = totalAmount + taxAmount;

        // Update tax and grand total in the HTML
        $('#totalAmountLabel').text(totalAmount.toFixed(2));
        $('#taxAmountLabel').text(taxAmount.toFixed(2));
        $('#grandTotalLabel').text(grandTotal.toFixed(2));
         
    }

    // Calculate totals on page load
    calculateTotals();

    // Recalculate when any checkbox is checked/unchecked
    $('.user-checkbox').change(function() {
        calculateTotals();
    });
});

 
</script>

 
 <script type="text/javascript">
        

         // Hide the div when the hide button is clicked
            document.querySelector('#payment-section').style.display = 'none';
  
     function sendOTP() {
        event.preventDefault();
        let mobileNo = "<?php echo $arr_agency['mobile_no']; ?>";
        var button = document.getElementById('submitOtp_01');


        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'ctrlsendotp.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    let serverOtp = xhr.responseText.trim();

                    console.log('OTP Sent: ', serverOtp);

                    window.receivedOtp = serverOtp;

                    startCountdown(15);
                } else {
                    console.error('An error occurred:', xhr.statusText);
                }
            }
        };

        xhr.send('mobile_no=' + encodeURIComponent(mobileNo) + '&action=send_otp');
    }

       function startCountdown(seconds) {
        var requestOtp = document.getElementById('requestOtp');
        var countdown = seconds;
        requestOtp.style.display = 'inline-block';
        requestOtp.textContent = `Request OTP in ${countdown} seconds`;

        var interval = setInterval(function() {
            countdown--;
            if (countdown > 0) {
                requestOtp.textContent = `Request OTP in ${countdown} seconds`;
            } else {
                clearInterval(interval);
                requestOtp.textContent = 'Request OTP';
                requestOtp.style.cursor = 'pointer';
                requestOtp.onclick = sendOTP;
            }
        }, 1000);
    }
    function verifyOTP() {
        event.preventDefault();
        let userOtp = document.getElementById('userOtp').value.trim();

        if (userOtp === window.receivedOtp) {
            var button = document.getElementById('submitOtp_01');
            button.style.display = 'block';
            button.style.margin = '0 auto';
            document.getElementById('otp-section').style.display = 'none';
            document.querySelector('#family-section').style.display = 'block';
            document.querySelector('#payment-section').style.display = 'block';
            document.querySelector('#verification-section').style.display = 'block';

        } else {
            alert("invalid otp")
            document.getElementById('requestOtp').style.display = 'block';
        }
    }




    function sendPaymentLink() {
        // alert('test');
        let element = document.getElementById('grandTotalLabel');
        let mobile = document.getElementById('mobile').value;
        let email = document.getElementById('email').value;
        let name = document.getElementById('name').value;
        let end_user_id = document.getElementById('end_user_id').value;
        let bulk_id = document.getElementById('bulk_id').value;
        let gst_number = document.getElementById('gst_number').value;
        let agency_id = document.getElementById('agency_id').value;
        let user_ids = document.getElementById('user_idss').value;
        let obj_no = document.getElementById('obj_no').value; 
        let text = element.innerText;
        let amount = text.match(/\d+/)[0];
        
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'send_link_parent.php', true);
        // xhr.open('POST', './test_payment.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            let response = JSON.parse(xhr.responseText);
            if (xhr.status == 200) {
                console.log(response);
               window.location.href = response['short_url'];
                // $('#decideProject').css('display', 'block');
          var myModal = new bootstrap.Modal(document.getElementById("decideProject"), {});
            document.onreadystatechange = function () {
              myModal.show();
            }; 
            } else {
                console.error('Error fetching data: ' + xhr.status);
            }
        };
        var params = 'amount=' + encodeURIComponent(amount) + '&mobile=' + encodeURIComponent(mobile) + '&email=' + encodeURIComponent(email) + '&name=' + encodeURIComponent(name) + '&end_user_id=' + encodeURIComponent(end_user_id) + '&bulk_id=' + encodeURIComponent(bulk_id) + '&gst_number=' + encodeURIComponent(gst_number) + '&agency_id=' + encodeURIComponent(agency_id) + '&user_ids=' + encodeURIComponent(user_ids) + '&obj_no=' + encodeURIComponent(obj_no);

        xhr.send(params);
    }
 </script>
   <script type="text/javascript">
       window.onload = function () {
    // Check if CAPTCHA is already verified using localStorage
    if (localStorage.getItem('captcha_verified') === 'true') {
        $("#otp-section").hide();  // Show OTP section if CAPTCHA is verified
        $("#captch").hide(); 
        $('#family-section').show();
        $('#verification-section').show();
                $('#payment-section').show();
              // Hide CAPTCHA section
    } else {
        loadCaptcha();             // Load CAPTCHA if not verified
        $('#family-section').hide();
        $('#verification-section').hide();
                $('#payment-section').hide();
    }
};

// Function to load CAPTCHA (image or alphanumeric)
function loadCaptcha() {
    fetch('../captcha.php')
        .then(response => response.json())
        .then(data => {
            const captchaQuestion = document.getElementById('captchaQuestion');
            const imagesContainer = document.getElementById('captchaImages');
            const alphanumericContainer = document.getElementById('alphanumericCaptcha');

            // Hide both sections initially
            imagesContainer.style.display = 'none';
            alphanumericContainer.style.display = 'none';

            if (data.type === 'image') {
                captchaQuestion.innerHTML = data.question;
                imagesContainer.innerHTML = '';
                data.images.forEach((image) => {
                    const img = document.createElement('img');
                    img.src = "../" + image.url;
                    img.dataset.id = image.id;
                    img.onclick = function () {
                        this.classList.toggle('selected');
                    };
                    imagesContainer.appendChild(img);
                });
                imagesContainer.style.display = 'block'; // Show the image CAPTCHA section
            } else if (data.type === 'alphanumeric') {
                captchaQuestion.innerText = data.question;
                document.getElementById('captchaInput').value = '';
                const canvas = document.getElementById('canvas');
                const ctx = canvas.getContext('2d');

                // Set canvas properties
                ctx.fillStyle = 'white'; // Background color
                ctx.fillRect(0, 0, canvas.width, canvas.height);

                ctx.fillStyle = 'black'; // Text color
                ctx.font = '25px Agency FB'; // Font size and type
                ctx.textBaseline = 'top'; // Text alignment
                const text = data.code;
                const x = 15;
                const y = 15;
                ctx.fillText(text, x, y);
                document.getElementById('captchaCodeDisplay').innerText = data.code; // Display CAPTCHA code
                alphanumericContainer.style.display = 'block'; // Show the alphanumeric CAPTCHA section
            }
        });
}

// Function to handle CAPTCHA submission
document.getElementById('submitCaptcha').addEventListener('click', function () {
    const captchaType = document.getElementById('captchaQuestion').innerText.includes('code') ? 'alphanumeric' : 'image';

    if (captchaType === 'image') {
        const selectedImages = [];
        document.querySelectorAll('.captcha-images img.selected').forEach(img => {
            selectedImages.push(img.dataset.id);
        });

        fetch('../verify.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                type: 'image',
                selectedImages: selectedImages
            })
        })
        .then(response => response.json())
        .then(data => {
            const messageElement = document.getElementById('message');
            if (data.success) {
                messageElement.classList.remove('error');
                messageElement.textContent = 'Verification successful!';
                localStorage.setItem('captcha_verified', 'true'); // Store verification status in localStorage
                $("#otp-section").show();
                $("#captch").hide();
                sendSMS();  // Call function to send SMS
            } else {
                messageElement.classList.add('error');
                messageElement.textContent = 'Verification failed. Please try again.';
                loadCaptcha();
            }
        });
    } else if (captchaType === 'alphanumeric') {
        const captchaInput = document.getElementById('captchaInput').value;

        fetch('../verify.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                type: 'alphanumeric',
                captchaCode: captchaInput
            })
        })
        .then(response => response.json())
        .then(data => {
            const messageElement = document.getElementById('message');
            if (data.success) {
                messageElement.classList.remove('error');
                messageElement.textContent = 'Verification successful!';
                localStorage.setItem('captcha_verified', 'true'); // Store verification status in localStorage
                $("#otp-section").show();
                $("#captch").hide();
                sendSMS(); // Call function to send SMS
            } else {
                messageElement.classList.add('error');
                messageElement.textContent = 'Verification failed. Please try again.';
                loadCaptcha();
            }
        });
    }
});

// Function to refresh the alphanumeric CAPTCHA
document.getElementById('refreshCaptcha').addEventListener('click', function () {
    refreshAlphanumericCaptcha();
});

// Function to toggle between sign-in and sign-up
function toggle() {
    let container = document.getElementById('container');
    container.classList.toggle('sign-in');
    container.classList.toggle('sign-up');
}

// Automatically toggle on load
setTimeout(() => {
    let container = document.getElementById('container');
    container.classList.add('sign-in');
}, 100);

   </script>

</body>

</html>

<?php
session_start(); // Start session at the beginning of your PHP files

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'verify_captcha') {
    // Assuming you've processed the CAPTCHA verification and it's successful:
    $_SESSION['captcha_verified'] = true; // Set session variable after CAPTCHA is successfully verified
}
?>