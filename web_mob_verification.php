<?php
include 'connection.php';
//Application Vocoxp databese connection//
$connection = connection::getInstance();
$mysqli = $connection->getConnection();
//Central Database Connection//
$connection1 = database::getInstance();
$mysqli1 = $connection1->getConnection();


date_default_timezone_set('Asia/kolkata');

$verification_id = 'DVF-00008';
$application_id = 'APP-00001';
$verification_details = "SELECT * FROM `verification_configuration_all` WHERE `ver_type`='3' AND `verification_id`='$verification_id' AND `operational_status`='1' AND `application_id` = '$application_id'";
$details_result = $mysqli1->query($verification_details);

$ver_array = mysqli_fetch_assoc($details_result);
$base_amount = $ver_array['rate'];
$sgst_percentage = $ver_array['sgst_percentage'];
$cgst_percentage = $ver_array['cgst_percentage'];
$cgst_amount = ($base_amount * $cgst_percentage) / 100;
$sgst_amount = ($base_amount * $sgst_percentage) / 100;
$end_user_id = $_GET['end_user_id'];

$fetch_details = "SELECT `id`, `agency_id`, `bulk_id`, `upload_id`, `name`, `mobile`, `email_id` FROM `bulk_end_user_transaction_all` WHERE `end_user_id`='$end_user_id'";
// die();
$fetch_result = $mysqli->query($fetch_details);
$fetch_arr = mysqli_fetch_assoc($fetch_result);

$agency_id = $fetch_arr['agency_id'];

$agency_details = "SELECT `agency_logo`, `company_name` FROM `agency_header_all` WHERE `agency_id`='$agency_id'";
$agency_result = $mysqli->query($agency_details);
$agency_header_arr = mysqli_fetch_assoc($agency_result);
$logo_path = (isset($agency_header_arr['agency_logo']) && $agency_header_arr['agency_logo'] != '') ? $agency_header_arr['agency_logo'] : '';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mobile Verification Form</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <!-- jQuery (necessary for Toastr) -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

    <!-- Toastr JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <!-- <link rel="stylesheet" href="css/style.css" /> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <!-- Load Bootstrap JS -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Load string-similarity library -->
    <script src="https://cdn.jsdelivr.net/npm/string-similarity@4.0.4/index.min.js"></script>

    <link rel="stylesheet" href="css/style_1.css" />
    <style>
        body,
        html {
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }

        .loader {
            border: 4px solid rgba(255, 255, 255, 0.3);
            border-top: 4px solid #3498db;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto;
            position: relative;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Full screen overlay */
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            /* Transparent black */
            display: none;
            /* Hidden initially */
            z-index: 9999;
            justify-content: center;
            align-items: center;
        }

        .overlay-text {
            color: white;
            font-size: 18px;
            margin-top: 10px;
        }

        .centered {
            text-align: center;
        }
    </style>

    <script>
        // Ensure noConflict mode in case of conflicts with other libraries
        var $j = jQuery.noConflict();
    </script>
</head>

<body class="body-background">
    <header>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-12 padd-0"
                style="text-align: center;">
                <img src="<?php echo $logo_path
                            ?>" alt="Company Logo" class="logo">
                <div class="agency-name animated-text">
                    <?php echo (isset($agency_header_arr['company_name']) && $agency_header_arr['company_name'] != '') ? $agency_header_arr['company_name'] : ''; ?>
                </div>
            </div>
        </div>
    </header>

    <div class="container">
        <h2 class="mb-4">Mobile verification form</h2>
        <form id="mobForm" novalidate>
            
            <div class="form-group">
                <label for="name" class="col-lg-6 col-md-6 col-sm-6 col-12 padd-0 form-label">Full Name</label>
                <div class="col-lg-12 col-md-12 col-sm-12 col-12 padd-0">
                    <input type="text" onchange="copyText()" class="form-control" id="name" name="name" placeholder="Enter Full Name" value="<?PHP echo $fetch_arr["name"]; ?>" readonly style="text-transform: uppercase;">
                    <input type="hidden" id="edited_name" name="edited_name">
                    <input type="hidden" value="<?php echo $application_id; ?>" id="application_id" name="application_id">
                    <input type="hidden" value="" id="create_worker" name="create_worker">
                    <input type="hidden" value="" id="is_edited" name="is_edited">
                    <input type="hidden" value="" id="site_id" name="site_id">
                    <input type="hidden" value="" id="data_fetch_through_ocr" name="data_fetch_through_ocr">
                    <input type="hidden" value="bulkyes" id="is_verified" name="is_verified">
                    <input type="hidden" value="<?php echo $base_amount; ?>" id="base_amount" name="base_amount">
                    <input type="hidden" value="<?php echo $cgst_amount; ?>" id="cgst_amount" name="cgst_amount">
                    <input type="hidden" value="<?php echo $sgst_amount; ?>" id="sgst_amount" name="sgst_amount">
                    <input type="hidden" value="<?php echo $verification_id; ?>" id="verification_id" name="verification_id">
                    <input type="hidden" value="<?php echo $agency_id; ?>" id="agency_id" name="agency_id">
                    <input type="hidden" value="<?php echo $fetch_arr['bulk_id']; ?>" id="bulk_id" name="bulk_id">
                    <input type="hidden" value="<?php echo $end_user_id; ?>" id="end_user_id" name="end_user_id">
                    <input type="hidden" value="5" id="source_from" name="source_from">

                    <div class="invalid-feedback">
                        Please enter your full name.
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="mobile_number" class="col-lg-6 col-md-6 col-sm-6 col-12 padd-0 form-label">Enter Mobile Number<span class="label-required">*</span></label>
                <div class="col-lg-12 col-md-12 col-sm-12 col-12 padd-0">
                    <input type="text" class="form-control" id="mobile_number" value="<?php echo $fetch_arr['mobile']?>" name="mobile_number" onchange="copyPan()" style="text-transform: uppercase;" placeholder="Enter Mobile Number" required pattern="^\+91[-\s]?[789]\d{9}$">
                    <div class="invalid-feedback">
                        Please enter a valid mobile number
                    </div>
                </div>
            </div>
            <button type="button" value="send_otp" class="btn btn-primary" id="submitOtp_01" onclick="sendOTP()" style="text-align:center;">Send OTP</button>
            <div class="form-group">

            <input type="text" class="otp-input form-control" id="userOtp" placeholder="Enter OTP" style="display:none; margin-top: 10px;">
        </div>
            <button type="button" class="otp-button btn btn-primary" id="verifyOtp" style="display:none;" onclick="verifyOTP(event)">Verify OTP</button>
             <div class="overlay" id="loadingOverlay">
                                                    <div class="centered">
                                                        <div class="loader"></div>
                                                        <div class="overlay-text">Otp Verifying.. please wait...</div>
                                                    </div>
                                                </div>
                                                <span id="requestOtp" style="display:none;color:linear-gray;" onclick="sendOTP()">Request OTP</span>
        </form>
    </div>


    <script>
        function sendOTP() {
            event.preventDefault();

            var mobileNo = document.getElementById('mobile_number');
            var button = document.getElementById('submitOtp_01');

            // Hide the "Send OTP" button
            button.style.display = 'none';

            // Show the OTP input field and "Verify OTP" button
            document.getElementById('userOtp').style.display = 'block';
            document.getElementById('verifyOtp').style.display = 'block';

            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'upload_link/ctrlsendotp.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        let serverOtp = xhr.responseText.trim(); // OTP from the server

                        console.log('OTP Sent: ', serverOtp);

                        // Store the OTP in a variable for later verification
                        window.receivedOtp = serverOtp;

                        // Start the countdown for the "Request OTP" span
                        startCountdown(30); // 15-second countdown
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

            // Initially hide the span and set its text
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
                    requestOtp.onclick = sendOTP; // Re-enable the "Request OTP" span as a clickable element
                }
            }, 1000); // 1000 milliseconds = 1 second
        }


        // Event listener for the Verify OTP button
        function verifyOTP(event) {
            event.preventDefault();
            document.getElementById('loadingOverlay').style.display = 'flex';
            document.getElementById('verifyOtp').disabled = true;

            let userOtp = document.getElementById('userOtp').value.trim();

            // Simulating an async OTP verification process (replace with real logic)
            setTimeout(() => {
                if (userOtp === window.receivedOtp) {
                    // document.getElementById('verifytxt').style.display = 'none';
                    document.getElementById('submitOtp_01').style.display = 'none';
                    document.getElementById('userOtp').style.display = 'none';
                    document.getElementById('verifyOtp').style.display = 'none';
                    document.getElementById('requestOtp').style.display = 'none';
                    var verifytxt = document.getElementById('mobile_number');
                    verifytxt.style.display = 'block';
                    verifytxt.style.margin = '0 auto';
                    var button = document.getElementById('submitOtp_01');
                    button.style.display = 'block';
                    button.style.margin = '0 auto';
                    toastr.info('OTP Verified');
                    mob_verification();
                } else {
                    toastr.warning('Incorrect OTP. Please Enter Valid OTP.');
                    document.getElementById('requestOtp').style.display = 'block';
                }

                // Hide the loader and re-enable the button after the response
                document.getElementById('loadingOverlay').style.display = 'none';
                document.getElementById('verifyOtp').disabled = false;
            }, 3000); // Simulate a 3-second delay (use real response time)
        }
        

        function mob_verification() {
            // window.addEventListener('load', function() {
                var form = document.getElementById('mobForm');
               
                        var formData = new FormData(form);
                      
                        // Show the loading overlay
                        document.getElementById('loadingOverlay').style.display = 'flex';

                        // Append the back photo

                        $j.ajax({
                            url: 'weblink_verification_for_mob.php', 
                            type: 'POST',
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function(response) {
                                console.log(response);
                                document.getElementById('loadingOverlay').style.display = 'none';
                                var response1 = JSON.parse(response);
                                var error_code = response1.error_code;
                                console.log(error_code);

                                if (error_code === 100) {
                                    toastr.success("Form Submittied Successfully");
                                    setTimeout(function() {

                                        window.location.href = "https://mounarchtech.com/vocoxp/verification.php?end_user_id=<?php echo $end_user_id; ?>" + '&t=' + new Date().getTime();; // Replace with your target URL
                                    }, 1000);
                                    // console.log(response);
                                }
                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                // document.getElementById('loadingOverlay').style.display = 'none';
                                toastr.danger('Failed to submit form: ' + errorThrown);
                            }

                        });
                   
               
            // }, false);
        };
    </script>
</body>

</html>