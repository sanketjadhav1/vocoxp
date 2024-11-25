<?php
include 'connection.php';
//Application Vocoxp databese connection//
$connection = connection::getInstance();
$mysqli = $connection->getConnection();
//Central Database Connection//
$connection1 = database::getInstance();
$mysqli1 = $connection1->getConnection();


date_default_timezone_set('Asia/kolkata');

$verification_id = 'DVF-00001';
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

$fetch_details = "SELECT `id`, `agency_id`, `bulk_id`, `upload_id`,  `name`, `mobile`, `email_id` FROM `bulk_end_user_transaction_all` WHERE `end_user_id`='$end_user_id'";
$fetch_result = $mysqli->query($fetch_details);

$fetch_arr = mysqli_fetch_assoc($fetch_result);
$bulk_id = $fetch_arr["bulk_id"];
$endusename = $fetch_arr["name"];

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
    <title>Aadhar Details Form</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="css/style_1.css" />
    <style>
        .error {
            color: red;
        }

        .modal-content {
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .modal-header {
            border-bottom: none;
            background-color: #f8f9fa;
        }

        .modal-body {
            padding: 20px;
        }

        #verifyOtpBtn {
            margin-top: 1rem;
        }

        #countdown {
            margin-top: 1rem;
        }

        #resendOtpBtn {
            margin-top: 1rem;
        }

        .condiv {
            border: 2px;
            border-radius: 20px;
        }

        #otpdiv input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
    </style>
    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <!-- jQuery (necessary for Toastr) -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

    <!-- Toastr JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <!-- Load Bootstrap JS -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Load string-similarity library -->
    <script src="https://cdn.jsdelivr.net/npm/string-similarity@4.0.4/index.min.js"></script>
    <link rel="stylesheet" href="css/style_1.css" />
    <style>
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
    <div class="modal fade" id="aadhaarModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Aadhaar Verification</h5>
                </div>
                <div class="modal-body">
                    <form id="aadhaarForm">
                        <div class="form-group">
                            <label for="aadhaarNumber" class="form-label">Enter Aadhaar Number of <?php echo $endusename; ?><span class="label-required">*</span> </label>
                            <input type="text" class="form-control" onchange="copyadhar()" id="aadhaarNumber" name="aadhaarNumber" maxlength="12" required>
                        </div>
                        <p id="message"></p>
                        <button type="button" class="btn btn-primary" id="getOtpBtn">Get OTP</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="container condiv" style="padding-top:2%">
        <div class="row" id="company_details" style="display:none">
            <div class="col-lg-12 col-md-12 col-sm-12 col-12 padd-0"
                style="text-align: center;">
                <img src="<?php echo $logo_path
                            ?>" alt="Company Logo" class="logo">
                <div class="agency-name animated-text">
                    <?php echo (isset($agency_header_arr['company_name']) && $agency_header_arr['company_name'] != '') ? $agency_header_arr['company_name'] : ''; ?>
                </div>
            </div>
        </div>
        <form name="aadharForm" id="aadharForm" method="post" enctype="multipart/form-data" novalidate>
            <div id="otpdiv" style="display:none;">

                <div class="card mt-3">
                    <div class="card-header">
                        <h2 style="color:#0069d9">Aadhar OTP Verification</h2>
                    </div>
                    <div class="card-body">
                        <label for="otp" class="form-label">Enter Aadhaar OTP<span class="label-required">*</span></label>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-12 padd-0">
                            <input type="number" class="form-control" id="otp" name="otp">
                            <button type="button" class="btn btn-primary" id="verifyOtpBtn">Verify OTP</button>
                            <p id="countdown" style="display:none;">Request OTP in <span id="timer">30</span> seconds.</p>
                            <button class="btn btn-primary" type="button" id="resendOtpBtn" style="display:none;">Request OTP</button>
                        </div>
                    </div>
                </div>


            </div>
            <div class="container" id="maindiv" style="display: none;">
                <div class="card mt-5">
                    <div class="card-header">
                        <div style="display:flex;justify-content: space-between;">
                            <div>
                                <h2 class="text-primary mb-2">Aadhar Details Form</h2>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                <label for="aadhar_number" class="form-label">Aadhar Number:</label>
                                <input type="text" class="form-control" readonly id="aadhar_number" name="aadhar_number">
                                <input type="hidden" class="form-control" id="transaction_id" name="transaction_id">
                                <input type="hidden" class="form-control" id="edited_aadhar_number" name="edited_aadhar_number">
                                <input type="hidden" class="form-control" id="ver_status" name="ver_status">
                                <input type="hidden" class="form-control" id="ver_data" name="ver_data">
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                <label for="name" class="form-label">Full Name:</label>
                                <input onchange="copyText()" type="text" class="form-control" id="name" name="name" value="<?PHP echo $fetch_arr["name"]; ?>" readonly style="text-transform: uppercase;">
                                <input type="hidden" id="edited_name" name="edited_name">
                                <input type="hidden" value="<?php echo $application_id; ?>" id="application_id" name="application_id">
                                <input type="hidden" value="get_aadhar_detail" id="mode" name="mode">
                                <input type="hidden" value="5" id="source_from" name="source_from">
                                <input type="hidden" value="" id="create_worker" name="create_worker">
                                <input type="hidden" value="" id="is_edited" name="is_edited">
                                <input type="hidden" value="" id="site_id" name="site_id">
                                <input type="hidden" value="" id="data_fetch_through_ocr" name="data_fetch_through_ocr">
                                <input type="hidden" value="bulkyes" id="is_verified" name="is_verified">
                                <input type="hidden" value="<?php echo $base_amount; ?>" id="base_amount" name="base_amount">
                                <input type="hidden" value="<?php echo $cgst_amount; ?>" id="cgst_amount" name="cgst_amount">
                                <input type="hidden" value="<?php echo $sgst_amount; ?>" id="sgst_amount" name="sgst_amount">
                                <input type="hidden" value="<?php echo $verification_id; ?>" id="verification_id" name="verification_id">
                                <input type="hidden" value="<?php echo $fetch_arr['agency_id']; ?>" id="agency_id" name="agency_id">
                                <input type="hidden" value="<?php echo $fetch_arr['bulk_id']; ?>" id="bulk_id" name="bulk_id">
                                <input type="hidden" value="<?php echo $end_user_id; ?>" id="admin_id" name="admin_id">
                                <div class="error" id="nameError"></div>
                            </div>

                            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                <label for="date_of_births" class="form-label">Date of Birth:<span class="label-required">*</span></label>
                                <input type="date" onchange="copydob()" class="form-control" id="date_of_birth" name="date_of_birth" required>
                                <input type="hidden" class="form-control" id="edited_date_of_birth" name="edited_date_of_birth">
                                <div class="error" id="date_of_birthError"></div>
                            </div>

                            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                <label for="gender" class="form-label">Gender:<span class="label-required">*</span></label>
                                <select onselect="copygender()" class="form-control" id="gender" name="gender" required>
                                    <option value="">Select</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                    <option value="other">Other</option>
                                </select>
                                <input type="hidden" class="form-control" id="edited_gender" name="edited_gender">
                                <div class="error" id="genderError"></div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                <label for="front_photos" class="form-label">Aadhar Front Photo:<span class="label-required">*</span></label>
                                <input type="file" class="form-control-file" id="front_photo" name="front_photo" accept="image/*" required>
                                <div class="error" id="front_photoError"></div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                <label for="back_photos" class="form-label">Aadhar Back Photo:<span class="label-required">*</span></label>
                                <input type="file" class="form-control-file" id="back_photo" name="back_photo" accept="image/*" required>
                                <div class="error" id="back_photoError"></div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                <label for="address" class="form-label">Address:<span class="label-required">*</span></label>
                                <textarea onchange="copyaddress()" class="form-control" id="address" name="address" rows="3" required></textarea>
                                <input type="hidden" class="form-control" id="edited_address" name="edited_address">
                                <div class="error" id="addressError"></div>
                            </div>
                            <div class="col-md-12 mt-3">
                                <button type="submit" class="btn btn-primary">Verify</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- <div id="loadingOverlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 9999; justify-content: center; align-items: center;">
        <div class="spinner" style="border: 4px solid #f3f3f3; border-top: 4px solid #3498db; border-radius: 50%; width: 50px; height: 50px; animation: spin 1s linear infinite;"></div>
    </div> -->
            <div class="overlay" id="loadingOverlay">
                <div class="centered">
                    <div class="loader"></div>
                    <div class="overlay-text">Form Submitting..Please Wait...</div>
                </div>
            </div>
            <div class="overlay" id="WaitloadingOverlay">
                <div class="centered">
                    <div class="loader"></div>
                    <div class="overlay-text append-loader-text"></div>
                </div>
            </div>

            <style>
                /* @keyframes spin {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(360deg);
        }
    }*/
            </style>

        </form> <!-- Correctly closed form tag here -->
    </div>

    <script>
        $j(document).ready(function() {
            $j('#aadhaarModal').modal({
                backdrop: 'static',
                keyboard: false
            });

            //   function convertDateFormat(dateString) {
            //     // Split the input date by the dash (-) separator
            //     const [day, month, year] = dateString.split('-');

            //     // Return the date in the desired format with slashes
            //     return `${day}/${month}/${year}`;
            // }
            // const datef=document.getElementById('date_of_birth1').value=formattedDate;

            // const formattedDate = convertDateFormat(datef);
            // document.getElementById('date_of_birth').value=formattedDate;

            $j('#getOtpBtn').on('click', function() {
                var aadhaarNumber = $j('#aadhaarNumber').val();
                if (aadhaarNumber.length === 12) {
                    // $j('#aadhaarModal').modal('hide');
                    // toastr.info("Otp Has been sent successfully its valid for 10 minits")
                    // Simulate sending OTP (actual implementation would involve server-side logic)
                    var agency_id = <?php echo "'" . $end_user_id . "'"; ?>;
                    var application_id = <?php echo "'" . $application_id . "'"; ?>;
                    var verification_id = <?php echo "'" . $verification_id . "'"; ?>;
                    var bulk_id = <?php echo "'" . $bulk_id . "'"; ?>;
                    var formData = new FormData();
                    formData.append('name', '');
                    formData.append('bulk_id', bulk_id);
                    formData.append('edited_name', '');
                    formData.append('aadhar_number', aadhaarNumber);
                    formData.append('edited_aadhar_number', aadhaarNumber);
                    formData.append('date_of_birth', '');
                    formData.append('edited_date_of_birth', '');
                    formData.append('gender', '');
                    formData.append('edited_gender', '');
                    formData.append('address', '');
                    formData.append('edited_address', '');
                    formData.append('application_id', application_id);
                    formData.append('agency_id', agency_id);
                    formData.append('admin_id', '');
                    formData.append('base_amount', '');
                    formData.append('sgst_amount', '');
                    formData.append('cgst_amount', '');
                    formData.append('mode', 'get_aadhar_otp');
                    formData.append('verification_id', verification_id);
                    formData.append('front_photo', new Blob(), '');
                    formData.append('back_photo', new Blob(), '');
                    formData.append('otp', '');
                    formData.append('transaction_id', '');
                    formData.append('source_from', '');
                    formData.append('site_id', '');
                    formData.append('create_worker', '');
                    formData.append('is_verified', 'bulkyes');
                    formData.append('is_edited', '');
                    formData.append('data_fetch_through_ocr', '');

                    $j.ajax({
                        url: 'direct_verification_for_aadhar.php',
                        type: 'POST',
                        data: formData,
                        contentType: false,
                        processData: false,
                        beforeSend: function() {
                            $('.append-loader-text').text('Please wait...');
                            document.getElementById('WaitloadingOverlay').style.display = 'flex';
                        },
                        success: function(response) {
                            document.getElementById('WaitloadingOverlay').style.display = 'none';
                            var jsonResponse = JSON.parse(response);
                            var transactionId = jsonResponse.transaction_id;
                            var error_code = jsonResponse.error_code;
                            var message = jsonResponse.message;
                            $("#transaction_id").val(transactionId);
                            if (error_code === 100) {
                                toastr.info(message);
                                window.transactionId = transactionId; // Store the transaction ID globally
                                document.getElementById("otpdiv").style.display = "block";
                                $j('#aadhaarModal').modal('hide');
                                document.getElementById("getOtpBtn").style.display = "none";
                                document.getElementById("company_details").style.display = "block";
                            } else if (error_code === 199) {
                                toastr.error(message);
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            document.getElementById('WaitloadingOverlay').style.display = 'none';
                            console.error("Error: " + textStatus + " - " + errorThrown);
                        }
                    });
                } else {
                    toastr.warning("Please enter a valid 12-digit Aadhaar number.");
                }
            });
            var countdownTimer;
            var countdownDuration = 60; // Countdown duration in seconds

            function startCountdown() {
                var timeLeft = countdownDuration;
                $j('#countdown').show();
                $j('#verifyOtpBtn').hide();
                $j('#resendOtpBtn').hide();

                countdownTimer = setInterval(function() {
                    timeLeft--;
                    $j('#timer').text(timeLeft);
                    if (timeLeft <= 0) {
                        clearInterval(countdownTimer);
                        $j('#countdown').hide();
                        $j('#resendOtpBtn').show();
                    }
                }, 1000);
            }

            $j('#verifyOtpBtn').on('click', function() {
                var agency_id = <?php echo "'" . $end_user_id . "'"; ?>;
                var application_id = <?php echo "'" . $application_id . "'"; ?>;
                var verification_id = <?php echo "'" . $verification_id . "'"; ?>;
                var bulk_id = <?php echo "'" . $bulk_id . "'"; ?>;
                var name = $("#name").val();
                // alert(name);
                var otp = $j('#otp').val();
                var transactionId = window.transactionId;
                console.log(transactionId);
                console.log("ok");


                if (!otp) {
                    toastr.warning("Please enter the OTP.");
                    return;
                }

                var formData = new FormData();
                formData.append('mode', 'verify_aadhar_otp');
                formData.append('transaction_id', transactionId);
                formData.append('application_id', application_id);
                formData.append('verification_id', verification_id);
                formData.append('agency_id', agency_id);
                formData.append('bulk_id', bulk_id);
                formData.append('is_verified', 'bulkyes');
                formData.append('name', name);

                formData.append('otp', otp);

                $j.ajax({
                    url: 'direct_verification_for_aadhar.php',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        document.getElementById('WaitloadingOverlay').style.display = 'flex';
                    },
                    success: function(response) {
                        document.getElementById('WaitloadingOverlay').style.display = 'none';
                        console.log(response)
                        var jsonResponse = JSON.parse(response);
                        var error_code = jsonResponse.error_code;
                        var message = jsonResponse.message;
                        var aadhaar_data = JSON.stringify(jsonResponse.data);
                        var code = JSON.stringify(jsonResponse.data.code);
                        var document_type = JSON.stringify(jsonResponse.data.aadhaar_data.document_type);
                        var name = JSON.stringify(jsonResponse.data.aadhaar_data.name);
                        var date_of_birth = JSON.stringify(jsonResponse.data.aadhaar_data.date_of_birth);
                        var gender = JSON.stringify(jsonResponse.data.aadhaar_data.gender);
                        var mobile = JSON.stringify(jsonResponse.data.aadhaar_data.mobile);
                        var care_of = JSON.stringify(jsonResponse.data.aadhaar_data.care_of);
                        var district = JSON.stringify(jsonResponse.data.aadhaar_data.district);
                        var locality = JSON.stringify(jsonResponse.data.aadhaar_data.locality);
                        var state = JSON.stringify(jsonResponse.data.aadhaar_data.state);
                        var pincode = JSON.stringify(jsonResponse.data.aadhaar_data.pincode);
                        var country = JSON.stringify(jsonResponse.data.aadhaar_data.country);
                        var vtc_name = JSON.stringify(jsonResponse.data.aadhaar_data.vtc_name);
                        var status = jsonResponse.status;
                        // alert(aadhaar_data);
                        // var details=
                        if (error_code === 100) {
                            toastr.info("OTP verified successfully: " + message);
                            document.getElementById("otpdiv").style.display = "none";
                            document.getElementById("maindiv").style.display = "block";
                            $("#ver_status").val(status);
                            $("#ver_data").val('{"status":' + status + ',"data":' + aadhaar_data + '}');
                            // window.data = jsonResponse.data;
                        } else if (error_code === 199) {
                            toastr.error("Invalid OTP");
                            startCountdown();
                            $j('#otp').val(''); // Clear the OTP input field
                            // Reset or re-fetch the transaction ID if needed
                            window.transactionId = jsonResponse.transaction_id; // Reset if provided by server
                            // Reset if provided by server
                        } else {
                            toastr.error("An unexpected error occurred. Please try again.");
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        document.getElementById('WaitloadingOverlay').style.display = 'none';
                        console.error("Error: " + textStatus + " - " + errorThrown);
                    }
                });
            });

            $j('#resendOtpBtn').on('click', function() {
                // Logic to resend OTP...
                $j('#resendOtpBtn').hide();
                $j('#verifyOtpBtn').show();
                $j('#otp').val(''); // Clear the OTP input field

                // Call the resend OTP logic
                $j('#getOtpBtn').click(); // Simulate clicking the get OTP button
            });


        });


        function copyText() {
            // Get the text from the source input
            var sourceText = document.getElementById("name").value;

            // Set the text to the target input
            document.getElementById("edited_name").value = sourceText;
        }

        function copyadhar() {
            // Get the text from the source input
            var sourceText = document.getElementById("aadhaarNumber").value;

            // Set the text to the target input
            document.getElementById("aadhar_number").value = sourceText;
            document.getElementById("edited_aadhar_number").value = sourceText;
        }

        function copygender() {
            // Get the text from the source input
            var sourceText = document.getElementById("gender").value;

            // Set the text to the target input
            document.getElementById("edited_gender").value = sourceText;
        }

        function copyaddress() {
            // Get the text from the source input
            var sourceText = document.getElementById("address").value;

            // Set the text to the target input
            document.getElementById("edited_address").value = sourceText;
        }

        function copydob() {
            // Get the text from the source input
            var sourceText = document.getElementById("date_of_birth").value;

            // Set the text to the target input
            document.getElementById("edited_date_of_birth").value = sourceText;
        }
        // Validate the form fields and show error messages using Toastr and hide them after 6 seconds

        function validateAadharForm() {
            let isValid = true;
            let errors = [];

            // Clear previous error messages
            document.querySelectorAll('.error').forEach(function(errorElement) {
                errorElement.textContent = '';
            });

            // Full Name Validation
            const name = document.getElementById('name').value;
            if (!name) {
                document.getElementById('nameError').textContent = 'Full Name is required.';
                errors.push('Full Name is required.');
                isValid = false;
            }

            // Aadhar Number Validation
            const aadhar_number = document.getElementById('aadhar_number').value;
            const aadhar_numberPattern = /^\d{12}$/;
            if (!aadhar_numberPattern.test(aadhar_number)) {
                document.getElementById('aadhar_numberError').textContent = 'Aadhar Number must be 12 digits.';
                errors.push('Aadhar Number must be 12 digits.');
                isValid = false;
            }

            // Date of Birth Validation
            const date_of_birth = document.getElementById('date_of_birth').value;
            if (!date_of_birth) {
                document.getElementById('date_of_birthError').textContent = 'Date of Birth is required.';
                errors.push('Date of Birth is required.');
                isValid = false;
            }

            // Address Validation
            const address = document.getElementById('address').value;
            if (!address) {
                document.getElementById('addressError').textContent = 'Address is required.';
                errors.push('Address is required.');
                isValid = false;
            }

            // Gender Validation
            const gender = document.getElementById('gender').value;
            if (!gender) {
                document.getElementById('genderError').textContent = 'Gender is required.';
                errors.push('Gender is required.');
                isValid = false;
            }

            // Front Photo Validation
            const front_photo = document.getElementById('front_photo').files[0];
            if (!front_photo) {
                document.getElementById('front_photoError').textContent = 'Front photo is required.';
                errors.push('Front photo is required.');
                isValid = false;
            }

            // Back Photo Validation
            const back_photo = document.getElementById('back_photo').files[0];
            if (!back_photo) {
                document.getElementById('back_photoError').textContent = 'Back photo is required.';
                errors.push('Back photo is required.');
                isValid = false;
            }

            if (!isValid) {
                // Show general error message with Toastr
                toastr.error('Please fill the all fields', 'All Fields required', {
                    timeOut: 6000
                });
            }

            // Hide all error messages after 6 seconds
            setTimeout(() => {
                document.querySelectorAll('.error').forEach(function(errorElement) {
                    errorElement.textContent = '';
                });
            }, 6000);

            return isValid;
        }

        // Handle form submission
        $j('#aadharForm').submit(function(event) {
            event.preventDefault(); // Prevent default form submission

            if (!validateAadharForm()) {
                return; // If form is not valid, stop further execution
            }

            var formData = new FormData(this);

            // Optional: Log formData to verify it's including all inputs
            for (var pair of formData.entries()) {
                console.log(pair[0] + ', ' + pair[1]);
            }

            // Append the front and back photos
            var frontPhotoFile = $('#front_photo')[0].files[0];
            if (frontPhotoFile) {
                formData.append('front_photo', frontPhotoFile);
            }

            var backPhotoFile = $('#back_photo')[0].files[0];
            if (backPhotoFile) {
                formData.append('back_photo', backPhotoFile);
            }

            // Show the loader
            document.getElementById('loadingOverlay').style.display = 'flex';

            $j.ajax({
                url: 'direct_verification_for_aadhar.php',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    // alert(response);
                    // Hide the loader
                    document.getElementById('loadingOverlay').style.display = 'none';

                    // Extract response details
                    var responses = JSON.parse(response);
                    var error_code = responses.error_code;
                    var message = responses.message;

                    if (error_code === 100) {
                        // Show success notification
                        toastr.success('Your submission was successful!');

                        // Redirect to verification page with end user ID
                        var endUserId = $j('#admin_id').val();
                        // window.location.href = 'verification.php?end_user_id=' + endUserId; 
                        // window.location.href="https://mounarchtech.com/vocoxp/verification.php?end_user_id=" + endUserId+"&"+ new Date().getTime();
                        window.location.href = 'verification.php?end_user_id=' + $j('#admin_id').val() + '&t=' + new Date().getTime();

                    } else if (error_code === 199 || error_code === 400 || error_code === 113) {
                        // Show specific error message
                        toastr.error(message);
                    }
                },
                error: function(xhr, status, error) {
                    // Hide the loader on error
                    document.getElementById('loadingOverlay').style.display = 'none';

                    console.error('AJAX Error:', status, error);
                    toastr.error('An error occurred. Please try again.', '', {
                        timeOut: 6000
                    });
                }
            });
        });
    </script>


</body>

</html>