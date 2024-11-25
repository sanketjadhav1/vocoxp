<?php
include 'connection.php';
//Application Vocoxp databese connection//
$connection = connection::getInstance();
$mysqli = $connection->getConnection();
//Central Database Connection//
$connection1 = database::getInstance();
$mysqli1 = $connection1->getConnection();


date_default_timezone_set('Asia/kolkata');

$verification_id = 'DVF-00004';
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

$fetch_details = "SELECT `id`, `agency_id`, `bulk_id`, `upload_id`, `name`, `mobile`, `email_id` FROM `bulk_end_user_transaction_all` WHERE `end_user_id`='$end_user_id'";
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
    <title>Driving License Form</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <!-- jQuery (necessary for Toastr) -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

    <!-- Toastr JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <link rel="stylesheet" href="css/style_1.css" />
</head>
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

        <h2>Driving License Application Form</h2>
        <form name="licenseForm" id="licenseForm" method="post" enctype="multipart/form-data" novalidate>
            <div class="form-group">
                <label for="driving_licence_no" class="col-lg-6 col-md-6 col-sm-6 col-12 padd-0 form-label">License Number<span class="label-required">*</span></label>
                <div class="col-lg-12 col-md-12 col-sm-12 col-12 padd-0">
                    <input type="text" onchange="copylicence()" class="form-control" id="driving_licence_no" name="driving_licence_no" required title="License number should start with 2 letters (state code), followed by 2 digits (year), and 4-7 digits (unique number)">
                    <input type="hidden" class="form-control" id="edited_driving_licence_no" name="edited_driving_licence_no">
                </div>
            </div>
            <div class="form-group">
                <label for="name" class="col-lg-6 col-md-6 col-sm-6 col-12 padd-0 form-label">Full Name</label>
                <div class="col-lg-12 col-md-12 col-sm-12 col-12 padd-0">
                    <input type="text" onchange="copyText()" class="form-control" id="name" name="name" value="<?PHP echo $fetch_arr["name"]; ?>" readonly style="text-transform: uppercase;">
                    <input type="hidden" id="edited_name" name="edited_name">
                    <input type="hidden" value="<?php echo $application_id; ?>" id="application_id" name="application_id">
                    <input type="hidden" value="get_aadhar_detail" id="mode" name="mode">
                    <input type="hidden" value="5" id="source_from" name="source_from">
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
                </div>
            </div>
            <div class="form-group">
                <label for="father_name" class="col-lg-6 col-md-6 col-sm-6 col-12 padd-0 form-label">S/D/W of<span class="label-required">*</span></label>
                <div class="col-lg-12 col-md-12 col-sm-12 col-12 padd-0">
                    <input type="text" onchange="copyfather()" class="form-control" id="father_name" name="father_name" placeholder="Enter Father's Name" required>
                    <input type="hidden" class="form-control" id="edited_father_name" name="edited_father_name">
                </div>
            </div>
            <div class="form-group">
                <label for="address" class="col-lg-6 col-md-6 col-sm-6 col-12 padd-0 form-label">Address<span class="label-required">*</span></label>
                <div class="col-lg-12 col-md-12 col-sm-12 col-12 padd-0">
                    <textarea onchange="copyaddress()" class="form-control" id="address" name="address" rows="3" required></textarea>
                    <input type="hidden" class="form-control" id="edited_address" name="edited_address">
                </div>
            </div>
            <div class="form-group">
                <label for="date_of_birth" class="col-lg-6 col-md-6 col-sm-6 col-12 padd-0 form-label">Date of Birth<span class="label-required">*</span></label>
                <div class="col-lg-12 col-md-12 col-sm-12 col-12 padd-0">
                    <input type="date" onchange="copydob()" class="form-control" id="date_of_birth" name="date_of_birth" required>
                    <input type="hidden" class="form-control" id="edited_date_of_birth" name="edited_date_of_birth">
                </div>
            </div>
            <div class="form-group">
                <label for="date_of_issue" class="col-lg-6 col-md-6 col-sm-6 col-12 padd-0 form-label">Date of Issue<span class="label-required">*</span></label>
                <div class="col-lg-12 col-md-12 col-sm-12 col-12 padd-0">
                    <input type="date" onchange="copydoisuue()" class="form-control" id="date_of_issue" name="date_of_issue" required>
                    <input type="hidden" class="form-control" id="edited_date_of_issue" name="edited_date_of_issue">
                </div>
            </div>
            <div class="form-group">
                <label for="valid_till" class="col-lg-6 col-md-6 col-sm-6 col-12 padd-0 form-label">Valid Till<span class="label-required">*</span></label>
                <div class="col-lg-12 col-md-12 col-sm-12 col-12 padd-0">
                    <input type="date" onchange="copyvalid()" class="form-control" id="valid_till" name="valid_till" required>
                    <input type="hidden" class="form-control" id="edited_valid_till" name="edited_valid_till">
                    <input type="hidden" class="form-control" id="date_of_expiry" name="date_of_expiry">
                    <input type="hidden" class="form-control" id="edited_date_of_expiry" name="edited_date_of_expiry">
                </div>
            </div>
            <div class="form-group">
                <label for="vehicle_class" class="col-lg-6 col-md-6 col-sm-6 col-12 padd-0 form-label">Classes of Vehicle<span class="label-required">*</span></label>
                <div class="col-lg-12 col-md-12 col-sm-12 col-12 padd-0">
                    <input type="text" onchange="copyvehicle()" class="form-control" id="vehicle_class" name="vehicle_class" required>
                    <input type="hidden" class="form-control" id="edited_vehicle_class" name="edited_vehicle_class">
                </div>
            </div>
            <div class="form-group">
                <label for="state_name" class="col-lg-6 col-md-6 col-sm-6 col-12 padd-0 form-label">State Name<span class="label-required">*</span></label>
                <div class="col-lg-12 col-md-12 col-sm-12 col-12 padd-0">
                    <input type="text" onchange="copystate()" class="form-control" id="state_name" name="state_name" required>
                    <input type="hidden" class="form-control" id="edited_state_name" name="edited_state_name">
                </div>
            </div>
            <div class="form-group">
                <label for="blood_group" class="col-lg-6 col-md-6 col-sm-6 col-12 padd-0 form-label">Blood Group<span class="label-required">*</span></label>
                <div class="col-lg-12 col-md-12 col-sm-12 col-12 padd-0">
                    <select class="form-control" onchange="copygroup()" id="blood_group" name="blood_group" required>
                        <option value="">Select Blood Group</option>
                        <option value="A+">A+</option>
                        <option value="A-">A-</option>
                        <option value="B+">B+</option>
                        <option value="B-">B-</option>
                        <option value="O+">O+</option>
                        <option value="O-">O-</option>
                        <option value="AB+">AB+</option>
                        <option value="AB-">AB-</option>
                    </select>
                    <input type="hidden" class="form-control" id="edited_blood_group" name="edited_blood_group">
                </div>
            </div>
            <div class="form-group">
                <label for="frontPhoto" class="col-lg-6 col-md-6 col-sm-6 col-12 padd-0 form-label">Front Photo<span class="label-required">*</span></label>
                <div class="col-lg-12 col-md-12 col-sm-12 col-12 padd-0">
                    <input type="file" class="form-control-file" id="front_photo" name="front_photo" accept="image/*" required>
                </div>
            </div>
            <div class="form-group">
                <label for="backPhoto" class="col-lg-6 col-md-6 col-sm-6 col-12 padd-0 form-label">Back Photo<span class="label-required">*</span></label>
                <div class="col-lg-12 col-md-12 col-sm-12 col-12 padd-0">
                    <input type="file" class="form-control-file" id="back_photo" name="back_photo" accept="image/*" required>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
            <div class="overlay" id="loadingOverlay">
                <div class="centered">
                    <div class="loader"></div>
                    <div class="overlay-text">Form Submitting.. please wait...</div>
                </div>
            </div>
        </form>
        <div id="response" class="mt-3"></div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#licenseForm').on('submit', function(event) {
                event.preventDefault();
                document.getElementById('loadingOverlay').style.display = 'flex';

                var formData = new FormData(this);
                $.ajax({
                    url: 'direct_verification_for_dl.php',
                    method: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        console.log(response);
                        var jsonResponse = JSON.parse(response);
                        var status = jsonResponse.status; // Parse the JSON response
                        var error_code = jsonResponse.error_code; // Parse the JSON response

                        if (error_code == '100') {
                            document.getElementById('loadingOverlay').style.display = 'none';

                            toastr.info("Form Submittied Successfully");
                            setTimeout(function() {
                                // window.location.href = 'bulk_welcome_screen.php?enduser_id=<?php echo $end_user_id; ?>';
                            }, 1000);

                        } else if (status == '400') {
                            document.getElementById('loadingOverlay').style.display = 'none';
                            toastr.warning("Cannot do further operation on this transaction. Please Try Later");
                        } else if (error_code == '113') {
                            document.getElementById('loadingOverlay').style.display = 'none';
                            toastr.warning("Due to a technical issue, we are unable to complete your verification at this time. Please contact your agency for further assistance.");
                        } else {
                            document.getElementById('loadingOverlay').style.display = 'none';
                            toastr.error("Upstream source\/Government source internal server error. Please start the process again");

                        }
                    },
                    error: function() {
                        document.getElementById('loadingOverlay').style.display = 'none';
                        toastr.error('An error occurred. Please try again later');
                    }
                });
            });
        });

        function copyText() {
            // Get the text from the source input
            var sourceText = document.getElementById("name").value;

            // Set the text to the target input
            document.getElementById("edited_name").value = sourceText;
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

        function copyfather() {
            // Get the text from the source input
            var sourceText = document.getElementById("father_name").value;

            // Set the text to the target input
            document.getElementById("edited_father_name").value = sourceText;
        }

        function copydoisuue() {
            // Get the text from the source input
            var sourceText = document.getElementById("date_of_issue").value;

            // Set the text to the target input
            document.getElementById("edited_date_of_issue").value = sourceText;
        }

        function copyvalid() {
            // Get the text from the source input
            var sourceText = document.getElementById("valid_till").value;

            // Set the text to the target input
            document.getElementById("edited_valid_till").value = sourceText;
            document.getElementById("date_of_expiry").value = sourceText;
            document.getElementById("edited_date_of_expiry").value = sourceText;
        }

        function copyvehicle() {
            // Get the text from the source input
            var sourceText = document.getElementById("vehicle_class").value;

            // Set the text to the target input
            document.getElementById("edited_vehicle_class").value = sourceText;
        }

        function copylicence() {
            // Get the text from the source input
            var sourceText = document.getElementById("driving_licence_no").value;

            // Set the text to the target input
            document.getElementById("edited_driving_licence_no").value = sourceText;
        }

        function copygroup() {
            // Get the text from the source input
            var sourceText = document.getElementById("blood_group").value;

            // Set the text to the target input
            document.getElementById("edited_blood_group").value = sourceText;
        }

        function copystate() {
            // Get the text from the source input
            var sourceText = document.getElementById("state_name").value;

            // Set the text to the target input
            document.getElementById("edited_state_name").value = sourceText;
        }
        // Function to show the "Thank You" message
        function showThankYouMessage() {
            // You can use a modal, alert, or simple message on the page
            alert('Thank you for your submission! Redirecting...');
            // Alternatively, you could use a modal or display a message in a specific element
        }
    </script>
</body>

</html>