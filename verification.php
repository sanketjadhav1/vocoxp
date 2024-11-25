<?php
include 'connection.php';
//Application Vocoxp databese connection//
$connection = connection::getInstance();
$mysqli = $connection->getConnection();
//Central Database Connection//
$connection1 = database::getInstance();
$mysqli1 = $connection1->getConnection();


date_default_timezone_set('Asia/kolkata');
$current_date = date("Y-m-d");
// die();
$system_date = date("d-m-Y");
$system_date_time = date("Y-m-d H:i:s"); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome Page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/brands.min.css">
    <link rel="stylesheet" href="web_style.css" />

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/js/all.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/js/brands.min.js"></script>

</head>

<body>


    <?php


    $enduser_id = $_GET['end_user_id'];
    $select_end_details = "SELECT `id`, `agency_id`, `bulk_id`, `upload_id`, `excel_no`, `name`, `mobile`, `email_id`, `sms_sent`, `email_sent`, `status`, `verification_report`, `verification_details`, `scheduled_verifications`,`verification_done`, `weblink_opened_on`,`reminder_email`, `reminder_sms`  FROM `bulk_end_user_transaction_all` WHERE `end_user_id` = '$enduser_id'";
    $result = $mysqli->query($select_end_details);
    $select_array = mysqli_fetch_assoc($result);
    $mobile = $select_array['mobile'];
    $agency_id = $select_array['agency_id'];
    $scheduled_verifications = $select_array['scheduled_verifications'];

    $verification_done = $select_array['verification_done'];
    // Use explode to split the string into an array
    $array = explode(",", $scheduled_verifications);
    $array_verification_done = explode(",", $verification_done);
    // Define a mapping of numbers to descriptions
    // $mapping = [
    //     1 => "<button class='pointer' id='aadhar_button'> Aadhar </button><br/><br/>",
    //     2 => "<button class='pointer' id='pan_button'> Pan </button><br/><br/>",
    //     3 => "<button class='pointer' id='voter_button'> Voter </button><br/><br/>",
    //     4 => "<button class='pointer' id='dl_button'> DL </button><br/><br/>",
    //     5 => "<button class='pointer' id='indpass_button' > Indian Passport </button><br/><br/>",
    //     6 => "<button class='pointer' id='intpass_button' > International Passport </button><br/><br/>",
    //     7 => "<button class='pointer' id='cc_button'> Crime check </button><br/><br/>"
    // ];
    $mapping = [
        "DVF-00001" => "Aadhar",
        "DVF-00002" => "Pan",
        "DVF-00004" => "DL",
        "DVF-00005" => "Voter",
        "DVF-00008" => "Mobile Verification"
    ];
    $agency_details = "SELECT `company_name`,`agency_logo` FROM `agency_header_all` WHERE `agency_id` = '$agency_id'";
    $agency_result = $mysqli->query($agency_details);
    $agency_array = mysqli_fetch_assoc($agency_result);
    $company_name = $agency_array['company_name'];
    $agency_logo = $agency_array['agency_logo'];
    // Check if all verifications are completed
    $all_done = !array_diff($array, $array_verification_done);

    $upload_details = "SELECT * FROM `bulk_upload_file_information_all` WHERE `agency_id` = '$agency_id' AND `bulk_id` = '" . $select_array["bulk_id"] . "'";
    $upload_result = $mysqli->query($upload_details);
    $upload_array = mysqli_fetch_assoc($upload_result);

    // Example PHP Code to handle logic and output SweetAlert

    // Assuming you've already fetched and validated the data from the database
    $weblink_activated_from = date("Y-m-d", strtotime($upload_array["weblink_activated_from"])); // Example date
    $weblink_valid_till = date("Y-m-d", strtotime($upload_array["weblink_valid_till"]));     // Example date
    $current_date = date('Y-m-d');          // Current date


    // Check if the current date is within the range
    if ($current_date >= $weblink_activated_from && $current_date <= $weblink_valid_till) {
        echo "
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
            Swal.fire({
                title: 'Weblink verification completed',
                text: 'The weblink is valid. You will be redirected shortly.',
                icon: 'success',
                allowOutsideClick: false, // Disable closing on background click
                allowEscapeKey: false,   // Disable closing on ESC key
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redirect after clicking OK
                   // window.location.href = 'https://mounarchtech.com/vocoxp/upload_link/thankyou.php';
                }
            });
        </script>
    ";
    } elseif ($current_date == $weblink_activated_from) {
        echo "
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
       
     
     <script>
            Swal.fire({
                title: 'Weblink Expired',
                text: 'The weblink is valid. You will be redirected shortly.',
                icon: 'error',
                allowOutsideClick: false, // Disable closing on background click
                allowEscapeKey: false,   // Disable closing on ESC key
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redirect after clicking OK
                    window.location.href = 'https://mounarchtech.com/vocoxp/upload_link/thankyou.php';
                }
            });
        </script>
    ";

        // <script>
        //        Swal.fire({
        //            title: 'Weblink Expired',
        //            text: 'The weblink is no longer valid.',
        //            icon: 'error',
        //            allowOutsideClick: false,
        //            allowEscapeKey: false,
        //            confirmButtonText: 'OK'
        //        });
        //    </script>
    }
    ?>

    <div id="container" class="container">
        <div class="row">
            <div class="col align-items-center flex-col sign-up">

            </div>

            <div class="col   align-items-center flex-col requested_varification">
                <div class="form-wrapper align-items-center">

                    <div class="form requested_varification" id="verification">
                        <h2>Verification(s) you are requested to do :</h2>
                        <?php

                        // Iterate through the array and print the corresponding description
                        // foreach ($array as $value) {
                        //     if (isset($mapping[$value])) {

                        //          echo $mapping[$value];

                        //     }
                        // }

                        foreach ($array as $value) {
                            if (isset($mapping[$value])) {
                                // Check if this verification is done

                                if (in_array($value, $array_verification_done)) {
                                    // Button should be disabled if verification is done
                                    echo "<button class='pointer' id='{$mapping[$value]}_button' disabled>{$mapping[$value]} ( Done ) </button><br/><br/>";
                                } else {
                                    // Button should be active (enabled) if verification is not done
                                    // echo "<a style='background:#DB4437' onclick='redirect({$mapping[$value]},$enduser_id)' class='pointer' id='{$mapping[$value]}_button' >{$mapping[$value]}  ( Not Initiated )</a><br/><br/>";
                                    echo "<button style='background:#DB4437' onclick='redirect(\"{$mapping[$value]}\", \"$enduser_id\")' class='pointer' id='{$mapping[$value]}_button'>{$mapping[$value]}  ( Not Initiated )</button><br/><br/>";
                                }
                            }
                        }
                        ?>

                        <button type="button" class="btn btn-secondary" style="background-color: gray;" onclick="reloadPage()">Back to home
                        </button>


                        <script>
                            function reloadPage() {
                                window.location.href = "https://mounarchtech.com/vocoxp/upload_link/web_verification_form.php?enduser_id=<?php echo $enduser_id . '&t=' . time(); ?>&reset_captcha=true";
                            }
                        </script>

                    </div>
                </div>
            </div>
        </div>
        <div class="row content-row">
            <div class="col align-items-center flex-col">
                <div class="text requested_varification" id="divhtml">
                    <div id="agency_logo">
                        <img src="<?php echo $agency_logo; ?>" alt="Company Logo" class="logo" width="45%" height="45%">
                    </div>
                    <h2>
                        Welcome to <br> <?php echo $company_name; ?>
                    </h2>
                </div>
            </div>
        </div>
    </div>


    <script>
        function redirect(val, enduser_id) {
            if (val == "Aadhar") {
                window.location.href = "https://mounarchtech.com/vocoxp/web_aadhar_verification.php?end_user_id=" + enduser_id + '&t=' + new Date().getTime();
            } else if (val == "Pan") {
                window.location.href = "https://mounarchtech.com/vocoxp/web_pan_verification.php?end_user_id=" + enduser_id + '&t=' + new Date().getTime();
            } else if (val == "Voter") {
                window.location.href = "https://mounarchtech.com/vocoxp/web_voter_verification.php?end_user_id=" + enduser_id + '&t=' + new Date().getTime();
            } else if (val == "DL") {
                window.location.href = "https://mounarchtech.com/vocoxp/web_dl_verification.php?end_user_id=" + enduser_id + '&t=' + new Date().getTime();
            } else if (val == "Mobile Verification") {
                window.location.href = "https://mounarchtech.com/vocoxp/web_mob_verification.php?end_user_id=" + enduser_id + '&t=' + new Date().getTime();
            }
        }
        // Event listener for the Verify OTP button

        //         document.getElementById('Aadhar_button').addEventListener('click', function(event) {
        //         event.preventDefault();
        //         var end_id = "<?php echo $enduser_id; ?>";
        //         window.location.href = "https://mounarchtech.com/vocoxp/web_aadhar_verification.php?end_user_id=" + end_id;
        // });     
        //     document.getElementById('Pan_button').addEventListener('click', function(event) {
        //         event.preventDefault();
        //         var end_id = "<?php echo $enduser_id; ?>";
        //         window.location.href = "https://mounarchtech.com/vocoxp/web_pan_verification.php?end_user_id=" + end_id;
        // });  
        //     document.getElementById('Voter_button').addEventListener('click', function(event) {
        //         event.preventDefault();
        //         var end_id = "<?php echo $enduser_id; ?>";
        //         window.location.href = "https://mounarchtech.com/vocoxp/web_voter_verification.php?end_user_id=" + end_id;
        // });  

        //          document.getElementById('DL_button').addEventListener('click', function(event) {
        //         event.preventDefault();
        //         var end_id = "<?php echo $enduser_id; ?>";
        //         window.location.href = "https://mounarchtech.com/vocoxp/web_voter_verification.php?end_user_id=" + end_id;
        // });  
        //     document.getElementById('DL_button').addEventListener('click', function(event) {
        //         event.preventDefault();
        //         alert();
        //         var end_id = "<?php echo $enduser_id; ?>";
        //         window.location.href = "https://mounarchtech.com/vocoxp/web_dl_verification.php?end_user_id=" + end_id;
        // });  
    </script>
    <script type="text/javascript">
        let container = document.getElementById('container');
        setTimeout(() => {
            container.classList.add('requested_varification');
        }, 100)
    </script>
    <script>
        <?php if ($all_done): ?>
            // Update status using PHP
            <?php
            $query_sql = mysqli_query($mysqli, "UPDATE `bulk_end_user_transaction_all` SET `status`='3' WHERE `end_user_id`='$enduser_id'");
            ?>

            // Redirect using JavaScript
            // window.location.href = "https://mounarchtech.com/vocoxp/upload_link/web_verification_form.php?end_user_id=<?php echo $enduser_id; ?>";
            window.location.href = "https://mounarchtech.com/vocoxp/upload_link/web_verification_form.php?enduser_id=<?php echo $enduser_id; ?>" + '&t=' + new Date().getTime() + "&reset_captcha=true";
        <?php endif; ?>
    </script>

</body>

</html>