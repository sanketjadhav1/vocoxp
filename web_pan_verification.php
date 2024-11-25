<?php
include 'connection.php';
//Application Vocoxp databese connection//
$connection = connection::getInstance();
$mysqli = $connection->getConnection();
//Central Database Connection//
$connection1 = database::getInstance();
$mysqli1 = $connection1->getConnection();


date_default_timezone_set('Asia/kolkata');

$verification_id = 'DVF-00002';
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
    <title>PAN Details Form</title>
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
        <h2 class="mb-4">PAN Details Form</h2>
        <form id="panForm" novalidate>
            <div class="form-group">
                <label for="pan_number" class="col-lg-6 col-md-6 col-sm-6 col-12 padd-0 form-label">PAN Number<span class="label-required">*</span></label>
                <div class="col-lg-12 col-md-12 col-sm-12 col-12 padd-0">
                    <input type="text" class="form-control" id="pan_number" name="pan_number" onchange="copyPan()" style="text-transform: uppercase;" placeholder="Enter PAN Number" required pattern="[A-Za-z]{5}[0-9]{4}[A-Za-z]{1}">
                    <input type="hidden" class="form-control" id="edited_pan_number" name="edited_pan_number">
                    <div class="invalid-feedback">
                        Please enter a valid PAN number (e.g., ABCDE1234F).
                    </div>
                </div>
            </div>
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
                    <input type="hidden" value="<?php echo $end_user_id; ?>" id="admin_id" name="admin_id">
                    <input type="hidden" value="5" id="source_from" name="source_from">

                    <div class="invalid-feedback">
                        Please enter your full name.
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="date_of_birth" class="col-lg-6 col-md-6 col-sm-6 col-12 padd-0 form-label">Date of Birth<span class="label-required">*</span></label>
                <div class="col-lg-12 col-md-12 col-sm-12 col-12 padd-0">
                    <input type="date" onchange="copydob()" class="form-control" id="date_of_birth" name="date_of_birth" required>
                    <input type="hidden" class="form-control" id="edited_dob" name="edited_dob">
                    <div class="invalid-feedback">
                        Please enter your date of birth.
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="father_husband_name" class="col-lg-6 col-md-6 col-sm-6 col-12 padd-0 form-label">Father's Name<span class="label-required">*</span></label>
                <div class="col-lg-12 col-md-12 col-sm-12 col-12 padd-0">
                    <input type="text" class="form-control" id="father_husband_name" name="father_husband_name" placeholder="Enter Father's Name" required>
                    <input type="hidden" class="form-control" id="edited_father_name" name="edited_father_name">
                    <div class="invalid-feedback">
                        Please enter your father's name.
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="front_photos" class="col-lg-6 col-md-6 col-sm-6 col-12 padd-0 form-label">Pan Front Photo<span class="label-required">*</span></label>
                <div class="col-lg-12 col-md-12 col-sm-12 col-12 padd-0">
                    <input type="file" class="form-control-file" id="front_photo" name="front_photo" accept="image/*" required>
                    <div class="error" id="front_photoError"></div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Verify</button>
            <div class="overlay" id="loadingOverlay">
                <div class="centered">
                    <div class="loader"></div>
                    <div class="overlay-text">Form Submitting..Please Wait...</div>
                </div>
            </div>
        </form>
    </div>


    <script>
        function copyText() {
            // Get the text from the source input
            var sourceText = document.getElementById("name").value;

            // Set the text to the target input
            document.getElementById("edited_name").value = sourceText;
        }

        function copyPan() {
            // Get the text from the source input
            var sourceText = document.getElementById("pan_number").value;

            // Set the text to the target input
            document.getElementById("edited_pan_number").value = sourceText;
        }

        function copydob() {
            // Get the text from the source input
            var sourceText = document.getElementById("date_of_birth").value;

            // Set the text to the target input
            document.getElementById("edited_dob").value = sourceText;
        }

        function copyfather_husband_name() {
            // Get the text from the source input
            var sourceText = document.getElementById("father_husband_name").value;

            // Set the text to the target input
            document.getElementById("edited_father_name").value = sourceText;
        }

        (function() {
            'use strict';
            window.addEventListener('load', function() {
                var form = document.getElementById('panForm');
                form.addEventListener('submit', function(event) {
                    event.preventDefault();
                    //document.getElementById('loadingOverlay').style.display = 'flex';

                    event.stopPropagation();

                    if (form.checkValidity() === true) {
                        var formData = new FormData(form);
                        for (var pair of formData.entries()) {
                            console.log(pair[0] + ', ' + pair[1]);
                        }
                        // Show the loading overlay
                        document.getElementById('loadingOverlay').style.display = 'flex';

                        // Append the back photo

                        $j.ajax({
                            url: 'direct_verification_for_pan.php', // Replace with your server endpoint
                            type: 'POST',
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function(response) {
                                // console.log(response);
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
                    }
                    form.classList.add('was-validated');
                }, false);
            }, false);
        })();
    </script>
</body>

</html>