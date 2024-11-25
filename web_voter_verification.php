<?php
include 'connection.php';
//Application Vocoxp databese connection//
$connection = connection::getInstance();
$mysqli = $connection->getConnection();
//Central Database Connection//
$connection1 = database::getInstance();
$mysqli1 = $connection1->getConnection();


date_default_timezone_set('Asia/kolkata');

$verification_id = 'DVF-00005';
$application_id = 'APP-00001';
$verification_details = "SELECT * FROM `verification_configuration_all` WHERE `ver_type`='1' AND `verification_id`='$verification_id' AND `operational_status`='1' AND `application_id` = '$application_id'";
$details_result = $mysqli1->query($verification_details);

$ver_array = mysqli_fetch_assoc($details_result);
$base_amount = $ver_array['rate'];
$sgst_percentage = $ver_array['sgst_percentage'];
$cgst_percentage = $ver_array['cgst_percentage'];
$cgst_amount = ($base_amount * $cgst_percentage) / 100;
$sgst_amount = ($base_amount * $sgst_percentage) / 100;
$end_user_id = $_GET['end_user_id'];

$fetch_details = "SELECT `id`, `agency_id`, `bulk_id`, `upload_id`,  `name`, `mobile`, `email_id` FROM `bulk_end_user_transaction_all` WHERE `end_user_id`='$end_user_id'";
// die();
$fetch_result = $mysqli->query($fetch_details);
$fetch_arr = mysqli_fetch_assoc($fetch_result);
$update_end_details = "UPDATE `bulk_end_user_transaction_all` SET  `status`='2' WHERE `end_user_id`='$end_user_id'";
$res_update = mysqli_query($mysqli, $update_end_details);

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
    <title>Voter Verification Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <!-- jQuery (necessary for Toastr) -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

    <!-- Toastr JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <!-- <link rel="stylesheet" href="css/style.css" /> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>

    <!-- Bootstrap js  -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="css/style_1.css" />
    <style>
        body,
        html {
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .error {
            color: red;
            font-size: 0.875em;
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
        <div id="form_response" class="mt-3"></div>
        <h2>Voter Verification Form</h2>
        <form id="voterVerificationForm" enctype="multipart/form-data">
            <div class="form-group">
                <label for="voter_number" class="col-lg-6 col-md-6 col-sm-6 col-12 padd-0 form-label">Voter Number<span class="label-required">*</span></label>
                <div class="col-lg-12 col-md-12 col-sm-12 col-12 padd-0">
                    <input type="text" onchange="copyvoter()" class="form-control" id="voter_number" name="voter_number" required>
                    <input type="hidden" id="edited_voter_number" name="edited_voter_number">
                    <div class="error" id="voter_number_error"></div>
                </div>
            </div>
            <div class="form-group">
                <label for="name" class="col-lg-6 col-md-6 col-sm-6 col-12 padd-0 form-label">Name</label>
                <div class="col-lg-12 col-md-12 col-sm-12 col-12 padd-0">
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
            </div>
            <div class="form-group">
                <label for="date_of_birth" class="col-lg-6 col-md-6 col-sm-6 col-12 padd-0 form-label">Date of Birth<span class="label-required">*</span></label>
                <div class="col-lg-12 col-md-12 col-sm-12 col-12 padd-0">
                    <input type="date" onchange="copydob()" class="form-control" id="date_of_birth" name="date_of_birth" required>
                    <input type="hidden" class="form-control" id="edited_date_of_birth" name="edited_date_of_birth">
                    <div class="error" id="date_of_birth_error"></div>
                </div>
            </div>
            <div class="form-group">
                <label for="gender" class="col-lg-6 col-md-6 col-sm-6 col-12 padd-0 form-label">Gender<span class="label-required">*</span></label>
                <div class="col-lg-12 col-md-12 col-sm-12 col-12 padd-0">
                    <select onselect="copygender()" class="form-control" id="gender" name="gender" required>
                        <option value="">Select</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="other">Other</option>
                    </select>
                    <input type="hidden" class="form-control" id="edited_gender" name="edited_gender">

                    <div class="error" id="genderError"></div>
                </div>
            </div>
            <div class="form-group">
                <label for="father_guardian_name" class="col-lg-6 col-md-6 col-sm-6 col-12 padd-0 form-label">Father's Name<span class="label-required">*</span></label>
                <div class="col-lg-12 col-md-12 col-sm-12 col-12 padd-0">
                    <input type="text" onchange="copyfather()" class="form-control" id="father_guardian_name" name="father_guardian_name" required>
                    <input type="hidden" class="form-control" id="edited_father_guardian_name" name="edited_father_guardian_name">
                    <div class="error" id="father_guardian_name_error"></div>
                </div>
            </div>
            <div class="form-group">
                <label for="address" class="col-lg-6 col-md-6 col-sm-6 col-12 padd-0 form-label">Address<span class="label-required">*</span></label>
                <div class="col-lg-12 col-md-12 col-sm-12 col-12 padd-0">
                    <textarea onchange="copyaddress()" class="form-control" id="address" name="address" rows="3" required></textarea>
                    <input type="hidden" class="form-control" id="edited_address" name="edited_address">
                    <div class="error" id="addressError"></div>
                </div>
            </div>
            <div class="form-group">
                <label for="polling_details" class="col-lg-6 col-md-6 col-sm-6 col-12 padd-0 form-label">Polling Details<span class="label-required">*</span></label>
                <div class="col-lg-12 col-md-12 col-sm-12 col-12 padd-0">
                    <textarea onchange="copypoll()" class="form-control" id="polling_details" name="polling_details" required></textarea>
                    <input type="hidden" class="form-control" id="edited_polling_details" name="edited_polling_details">
                    <div class="error" id="polling_details_error"></div>
                </div>
            </div>
            <div class="form-group">
                <label for="front_photo" class="col-lg-6 col-md-6 col-sm-6 col-12 padd-0 form-label">Front Photo<span class="label-required">*</span></label>
                <div class="col-lg-12 col-md-12 col-sm-12 col-12 padd-0">
                    <input type="file" class="form-control" id="front_photo" name="front_photo" accept="image/*" required>
                    <div class="error" id="front_photo_error"></div>
                </div>
            </div>
            <div class="form-group">
                <label for="back_photo" class="col-lg-6 col-md-6 col-sm-6 col-12 padd-0 form-label">Back Photo<span class="label-required">*</span></label>
                <div class="col-lg-12 col-md-12 col-sm-12 col-12 padd-0">
                    <input type="file" class="form-control" id="back_photo" name="back_photo" accept="image/*" required>
                    <div class="error" id="back_photo_error"></div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary mt-3">Submit</button>
            <div class="overlay" id="loadingOverlay">
                <div class="centered">
                    <div class="loader"></div>
                    <div class="overlay-text">Form Submitting.. please wait...</div>
                </div>
            </div>
        </form>

    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#voterVerificationForm').on('submit', function(event) {
                event.preventDefault();
                document.getElementById('loadingOverlay').style.display = 'flex';

                let isValid = true;
                $('input, textarea, select').each(function() {
                    if ($(this).val() === '' && $(this).prop('required')) {
                        isValid = false;
                        $(this).next('.error').text('This field is required');
                    } else {
                        $(this).next('.error').text('');
                    }
                });

                if (!isValid) {
                    return;
                }

                var formData = new FormData(this);
                $.ajax({
                    url: 'direct_verification_for_voter.php',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: "json",
                    success: function(response) {
                        document.getElementById('loadingOverlay').style.display = 'none';

                        console.log(response);
                        var error_code = response.error_code;

                        if (error_code == '100') {

                            document.getElementById('loadingOverlay').style.display = 'none';

                            toastr.info("Form Submittied Successfully");
                            setTimeout(function() {
                                window.location.href = "verification.php?end_user_id=<?php echo $end_user_id; ?>"; // Replace with your target URL
                            }, 1000);
                        } else if (error_code == '113') {
                            document.getElementById('loadingOverlay').style.display = 'none';
                            toastr.warning("Due to a technical issue, we are unable to complete your verification at this time. Please contact your agency for further assistance.");
                        }
                    },
                    error: function() {
                        document.getElementById('loadingOverlay').style.display = 'none';
                        toastr.error("There was an error submitting the form. Please try again later");

                        $('#form_response').html('<div class="alert alert-danger">There was an error submitting the form. Please try again later.</div>');
                    }
                });
            });
        });


        function copyvoter() {
            var sourceText = document.getElementById("voter_number").value;
            document.getElementById("edited_voter_number").value = sourceText;
        }

        function copyText() {
            var sourceText = document.getElementById("name").value;
            document.getElementById("edited_name").value = sourceText;
        }

        function copygender() {
            var sourceText = document.getElementById("gender").value;
            document.getElementById("edited_gender").value = sourceText;
        }

        function copyaddress() {
            var sourceText = document.getElementById("address").value;
            document.getElementById("edited_address").value = sourceText;
        }

        function copydob() {
            var sourceText = document.getElementById("date_of_birth").value;
            document.getElementById("edited_date_of_birth").value = sourceText;
        }

        function copypoll() {

            var sourceText = document.getElementById("polling_details").value;

            document.getElementById("edited_polling_details").value = sourceText;
        }

        function copyfather() {
            var sourceText = document.getElementById("father_guardian_name").value;
            document.getElementById("edited_father_guardian_name").value = sourceText;
        }
    </script>
</body>

</html>