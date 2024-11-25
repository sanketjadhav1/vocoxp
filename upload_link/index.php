<?php

include_once '../connection.php';

// error_reporting(E_ALL & ~E_DEPRECATED);

// ini_set('display_errors', 1);

// Now you can use the connection class

$connection = connection::getInstance();

$mysqli = $connection->getConnection();

$connection1 = database::getInstance();

$mysqli1 = $connection1->getConnection();

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $agency_id = $_GET['agency_id'];

    $bulk_id = $_GET['bulk_id'];

    $fetch_agency = "SELECT `company_name`, `agency_logo`, `address`, `mobile_no`, `name` ,`current_wallet_bal` FROM `agency_header_all` WHERE `agency_id`='$agency_id'";

    $res_agency = mysqli_query($mysqli, $fetch_agency);
    if ($res_agency && mysqli_num_rows($res_agency) > 0) {
        $arr_agency = mysqli_fetch_assoc($res_agency);

        $otp = rand(10000, 99999);
        $weblink_details = "SELECT request_no,obj_1,obj_2,obj_3,upload_weblink,obj_1_verifications,obj_2_verifications,obj_3_verifications,excel_no FROM `bulk_weblink_request_all` WHERE `agency_id`='$agency_id' AND `bulk_id` = '$bulk_id' AND (`status` = '1' OR `status` = '2')";
        $weblink_result = $mysqli->query($weblink_details);

        if ($weblink_result && mysqli_num_rows($weblink_result) > 0) {
            $weblink_array = mysqli_fetch_assoc($weblink_result);
            $request_no = $weblink_array['request_no'];
            $excel_no = $weblink_array['excel_no'];
            $obj_1_verifications = $weblink_array['obj_1_verifications'];
            $obj_2_verifications = $weblink_array['obj_2_verifications'];
            $obj_3_verifications = $weblink_array['obj_3_verifications'];
            // DVF-00001,DVF-00002,DVF-00004,DVF-00005
            $obj_1 = $weblink_array['obj_1'];
            $obj_2 = $weblink_array['obj_2'];
            $obj_3 = $weblink_array['obj_3'];

            $excel_url = $weblink_array['upload_weblink'];
        } else {
            header("Location: https://mounarchtech.com/vocoxp/upload_link/thankyou.php");
        }
        // $array1 = explode(',', $obj_1_verifications);
        // $array2 = explode(',', $obj_2_verifications);
        // $array3 = explode(',', $obj_3_verifications);
        // $combined_array = array_merge($array1, $array2, $array3);
        // $button_text = implode(', ', $unique_values);


        $filename = basename(stripslashes($excel_url));
        $filenameWithoutExtension = pathinfo($filename, PATHINFO_FILENAME);


        // $weblink_details = "SELECT`request_no`FROM `bulk_weblink_request_all` WHERE `agency_id`='$agency_id' AND `bulk_id` = '$bulk_id' AND (`status` = '1' OR `status` = '2')";
        // $weblink_result = $mysqli->query($weblink_details);
        // $weblink_array = mysqli_fetch_assoc($weblink_result);
        // if (is_array($weblink_array) && isset($weblink_array['request_no'])) {
        //     // $verifications = $weblink_array['verifications'];
        //     $request_no = $weblink_array['request_no'];
        //     // $generated_for = $weblink_array['generated_for'];
        // } else {
        //     header("Location: https://mounarchtech.com/vocoxp/upload_link/thankyou.php");
        //     exit();
        // }


        // $update_end_details = "UPDATE `bulk_end_user_transaction_all` SET `scheduled_verifications`='$verifications' WHERE `bulk_id`='$bulk_id'";
        // $res_update = mysqli_query($mysqli, $update_end_details);
    } else {
        header("Location: https://mounarchtech.com/vocoxp/upload_link/thankyou.php");
        exit();
    }



    $fetch_verification = "SELECT * FROM `verification_configuration_all` WHERE `ver_type`='1' AND `operational_status` = '1'";
    $res_verificaton = mysqli_query($mysqli1, $fetch_verification);
    while ($arr_verification = mysqli_fetch_assoc($res_verificaton)) {

        $arr_veri[] = $arr_verification;
    }
    $jsonArrVeri = json_encode($arr_veri);



    function maskNumber($number)
    {
        $numStr = strval($number);
        $maskedNum = str_repeat('X', strlen($numStr) - 3) . substr($numStr, -3);
        return $maskedNum;
    }
}



?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">

    <title>Upload Form </title>

    <!-- Mobile Specific Metas -->

    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <!-- Bootstrap css  -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <!-- jQuery (necessary for Toastr) -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

    <!-- Toastr JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- Font-->
    <link rel="stylesheet" type="text/css" href="css/opensans-font.css">

    <link rel="stylesheet" type="text/css" href="css/roboto-font.css">

    <link rel="stylesheet" type="text/css" href="fonts/material-design-iconic-font/css/material-design-iconic-font.min.css">

    <!-- datepicker -->

    <link rel="stylesheet" type="text/css" href="css/jquery-ui.min.css">

    <!-- Main Style Css -->

    <link rel="stylesheet" href="css/style.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>

    <!-- Bootstrap js  -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var today = new Date().toISOString().split('T')[0];
            document.querySelector('input[type="date"]').setAttribute('min', today);
        });
    </script>


    <style>
        #next_slide {
            display: none !important;
        }

        .wizard-v4-content {
            margin-top: 5vh;
            margin-bottom: 5vh;
        }

        #next_slide a {
            position: absolute;
            font-family: 'Roboto', sans-serif;
            font-size: 16px;
            font-weight: 400;
            margin-left: 18px;
        }

        .error-message {
            color: red;
            font-size: 0.875em;
            margin-top: 5px;
        }

        /* General Styles */
        .inner {
            text-align: center;
        }

        #userOtp,
        #verifyOtp,
        #verifytxt {
            display: block;
            /* Ensure they appear as block-level elements */
            margin: 10px auto;
            /* Center the elements horizontally */
            width: 50%;
            text-align: center;
        }

        #otp-section .form-holder {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        #requestOtp {
            cursor: pointer;
        }

        .leftaligntxt {
            text-align: left !important;
        }

        .datecss {
            width: 17vw;
        }

        /* .selectcss{
    width: 14vw;
    font-size: 18px  !important;
    margin-left: -2vw !important;
} */
        .inner .form-row.form-row-date .form-holder select,
        .inner1 .form-row.form-row-date .form-holder select {

            float: left;
            width: 20.5%;
            margin-right: 25px;
            border: 2px solid #3498db;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .inner .form-row .form-holder select {
            /* changed by randhir  */
            margin-bottom: 0px;
            border-top: none !important;
            border-left: none !important;
            border-right: none !important;
            border-bottom: 2px solid #6bc734;
            /* Set a border color before focus */
            border-radius: 4px;
            /* Optional: For rounded corners */
            background-color: #fff;
            /* Ensure background color is white */
            appearance: unset;
            -moz-appearance: unset;
            -webkit-appearance: unset;
            -o-appearance: unset;
            -ms-appearance: unset;
            outline: none;
            font-family: 'Open Sans', sans-serif;
            font-weight: 400;
            /* box-sizing: border-box; */
        }

        .inner .form-row .form-holder input[type="date"] {
            border-bottom: 2px solid #6bc734;
            /* Default border color */
            border-radius: 4px;
            /* Optional: For rounded corners */
            box-sizing: border-box;
            /* Ensure padding and border are included in the element's total width and height */
            padding: 5px;
            /* Adjust padding as needed */
            font-size: 15px;
            color: #333;
            background-color: #fff;
            appearance: unset;
            -moz-appearance: unset;
            -webkit-appearance: unset;
            -o-appearance: unset;
            -ms-appearance: unset;
            outline: none;
        }


        .inner .form-row .form-holder input {
            font-size: 18px;
            color: #333;
            border-radius: 4px;
            /* Optional: For rounded corners */
        }

        .inner .form-row .form-holder .form-control:focus,
        .inner .form-row .form-holder .form-control:valid {
            border-bottom: 2px solid #6bc734;
        }

        .selectcss {
            width: 17vw;
            font-size: 18px !important;
            border: 2px solid #3498db;
            /* Set the default border color */
            border-radius: 4px;
            /* Optional: For rounded corners */
            padding: 5px;
            background-color: #fff;
            box-sizing: border-box;
        }

        .selectcss:focus {
            border-color: red;
            outline: none;
        }


        .rightmargin {
            margin-right: 5vw;
        }

        .innerdiv {
            display: flex;
            justify-content: center;
        }

        .form-holder {
            width: 100%;
            text-align: center;
        }

        h4 {
            margin-bottom: 10px;
            font-size: 16px;
            text-align: center;
        }



        .table-container {
            margin-bottom: 20px;
        }

        h3 {
            font-size: 1.5em;
            margin-bottom: 10px;
        }

        p {
            margin-bottom: 20px;
            font-size: 1em;
            color: #555;
        }

        /* Table Styles */
        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table-bordered {
            border: 1px solid #ddd;
        }

        .table th,
        .table td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }

        .table th {
            background-color: #f4f4f4;
            font-weight: bold;
        }

        .table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .table tbody tr:hover {
            background-color: #f1f1f1;
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            .table {
                font-size: 0.9em;
            }
        }

        /* Basic Reset */
        .otp-card {
            width: 300px;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            margin: 0 auto;
            background-image: url('images/wizard-v4.jpg');
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            box-shadow: none;
            /* Removed box shadow */
        }

        .otp-container {
            text-align: center;
        }

        .otp-container h2 {
            font-size: 24px;
            margin-bottom: 10px;
            color: #333;
        }

        .otp-container p {
            font-size: 14px;
            color: #666;
            margin-bottom: 20px;
        }

        .otp-input {
            width: 92%;
            padding: 10px;
            font-size: 16px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
            text-align: center;
        }

        .otp-button {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .otp-button:hover {
            background-color: #0056b3;
        }


        .logo {
            margin: 0 auto;
            margin-top: -5vh;
            display: block;
            width: 250px;
            height: 100px;
            object-fit: contain;
            border-style: none;
        }

        .wizard-form .wizard-header .heading {
            color: #946df9;
        }

        #request-no {
            width: 150px;
            height: 30px;
            border-radius: 5px;
        }

        .containers {
            text-align: center;
            margin: 0 auto;
        }

        .containers label {
            display: block;
        }

        .otp-button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            margin: 5px;
        }

        .otp-button:hover {
            background-color: #0056b3;
        }

        .hidden {
            display: none !important;
            /* Ensure it's not displayed */
        }

        /* Make the section visible but unselectable */
        .disabled {
            display: block;
            /* Ensure it's visible */
            opacity: 0.5;
            /* Dim the section to indicate it's disabled */
            pointer-events: none;
            /* Disable all mouse events */
            user-select: none;
            /* Prevent text selection */
        }

        #file-name {
            height: 35px;
            background-color: lightgray;
            border: none;
            width: 20vw;
            margin-top: auto;
        }

        #analyzeButton {
            height: 35px;
            margin-left: 10px;
        }

        .otpdivstyle {
            width: 65vw;
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

<body>
    <?php

    $default_image = '../images/default_agencyLogo.jpg';
    $image_path = !empty($arr_agency['agency_logo']) ? $arr_agency['agency_logo'] : $default_image;

    ?>


    <div class="page-content" style="background-image: url('images/wizard-v4.jpg');">


        <div class="wizard-v4-content">

            <div class="wizard-form">

                <div class="wizard-header">
                    <!-- Logo Image -->
                    <img src="<?php echo htmlspecialchars($image_path, ENT_QUOTES, 'UTF-8'); ?>" alt="Company Logo" class="logo">

                    <h3 class="heading"><b><i><?php echo $arr_agency['company_name']; ?></b></i></h3>
                    <p><?php echo $arr_agency['address']; ?></p>

                    <form id="requestForm" method="post">
                        <label for="request-no"><b>Select Request No:- </b></label>
                        <select name="request_no" id="request-no">
                            <option value="" selected disabled>SELECT REQ No</option>

                            <option value="<?php echo $weblink_array['request_no']; ?>" data-generated-for="<?php echo $weblink_array['generated_for']; ?>">
                                <?php echo $weblink_array['request_no']; ?>
                            </option>
                        </select>
                        <input type="hidden" name="agency_id" id="agency_id" value="<?php echo $agency_id; ?>">
                        <input type="hidden" name="bulk_id" id="bulk_id" value="<?php echo $bulk_id; ?>">


                    </form>
                </div>

                <div class="wizard-body" style="display:none" id="wizard-bodyDiv">
                    <div class="containers">
                        <span for="verification-for"><b>Weblink For: </b><span id="verification-for"><?php echo $filenameWithoutExtension; ?></span></span><br>
                    </div>
                    <form id="uploadForm" class="form-register" method="post" enctype="multipart/form-data">
                        <!--<form class="form-register" action="#" method="post">-->
                        <div id="wizard">
                            <!-- SECTION 1 -->
                            <h2>
                                <span class="step-icon"><i class="zmdi zmdi-phone-msg"></i></span>
                                <span class="step-text">OTP Verification</span>
                            </h2>
                            <section id="otp-section">
                                <div class="inner ">
                                    <div class="form-row">
                                        <div class="form-holder">
                                            <label class="form-row-inner otpdivstyle">
                                                <h4 id="verifytxt">Verify Your Agency mobile number : <?php echo maskNumber($arr_agency['mobile_no']); ?></h4>
                                                <button type="button" value="send_otp" class="otp-button" id="submitOtp_01" onclick="sendOTP()" style="width: 20%;text-align:center;">Send OTP</button>
                                                <input type="text" class="otp-input" id="userOtp" placeholder="Enter OTP" style="display:none; margin-top: 10px; width: 20%;">
                                                <button type="button" class="otp-button" id="verifyOtp" style="display:none; width: 20%;" onclick="verifyOTP(event)">Verify OTP</button>

                                                <!-- <button type="button" class="otp-button" id="verifyOtp" style="display:none; width: 20%;" onclick="verifyOTP()">Verify OTP</button> -->
                                                <div class="overlay" id="loadingOverlay">
                                                    <div class="centered">
                                                        <div class="loader"></div>
                                                        <div class="overlay-text">Otp Verifying.. please wait...</div>
                                                    </div>
                                                </div>
                                                <span id="requestOtp" style="display:none;color:linear-gray;" onclick="sendOTP()">Request OTP</span>

                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </section>
                            <!-- SECTION 2 -->
                            <h2 id="upload-section-heading">
                                <span class="step-icon"><i class="zmdi zmdi-upload"></i></span>
                                <span class="step-text">Upload</span>
                            </h2>
                            <section id="upload-section">
                                <div class="inner">
                                    <h4>Verification's</h4>
                                    <div class="row" id="card_01"></div>
                                    <h4 id="students" style="display: none;">Verification's for students</h4>
                                    <div class="row" id="card_02"></div>
                                    <br />
                                    <h4>Upload Excel File</h4>
                                    <br />
                                    <div class="form-row">
                                        <div class="form-holder">
                                            <label class="form-row-inner">
                                                <form id="Analizeform" enctype="multipart/form-data">
                                                    <input type="file" id="upload-excel" name="upload_excel" onchange="updateFileName()">
                                            </label><br>

                                        </div>
                                    </div>
                                </div>
                            </section>
                            <!--SECTION 3 -->
                            <h2 id="configurations-section-heading">
                                <span class="step-icon"><i class="zmdi zmdi-settings"></i></span>
                                <span class="step-text">Configurations</span>
                            </h2>
                            <section id="configurations-section">
                                <div class="inner">
                                    <!-- <form id="Analizeform" enctype="multipart/form-data" style="display:none;"> -->
                                    <h4>Uploaded Excel File</h4>
                                    <br />
                                    <div style="display: flex;justify-content: center;">
                                        <div>
                                            <input type="text" id="file-name" readonly>
                                        </div>
                                        <div>
                                            <input type="hidden" name="excel_no" id="excel_no" value="<?php echo $excel_no; ?>">
                                            <input type="button" id="analyzeButton" value="Start analyzing" onclick="test1();" class="btn btn-primary">
                                            <div class="overlay" id="loadingOverlay1">
                                                <div class="centered">
                                                    <div class="loader"></div>
                                                    <div class="overlay-text">Analyzing.. please wait...</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- <input type="text" id="file-name"  readonly>
                                    <input type="button" id="analyzeButton" value="Start Analyze" onclick="test1();" class="btn btn-primary mt-2"> -->
                                    <input type="hidden" name="agency_id" id="agency_id" value="<?php echo $agency_id; ?>">
                                    <input type="hidden" name="bulk_id" id="bulk_id1" value="<?php echo $bulk_id; ?>">

                    </form>
                    <div id="response" class="alert" style="display: none;"></div>
                    <br>
                    <input type="hidden" id="file_name_01" name="upload" class="form-control" placeholder="Enter file name" readonly>
                    <div id="confighide" style="display: none;">
                        <h4>Settings</h4>

                        <h4 style="margin-top: 10px;">Amount to be paid by : <span style="color:red">&nbsp; *</span> </h4>
                        <div id="radio">
                            <input type="radio" name="amount" id="1" value="agency_wallet" onclick="toggleGridVisibility()"> <label for="1">Agency Wallet </label>
                            <input type="radio" name="amount" id="2" value="end_user" onclick="toggleGridVisibility1()"> <label for="2">By End User</label>
                        </div>
                        <br>
                        <div class="innerdiv">
                            <div class="form-row">
                                <div class="form-holder form-holder-2 rightmargin">
                                    <label class="form-row-inner">
                                        <h4 class="leftaligntxt">Weblink Activated From <span style="color:red">&nbsp; *</span></h4>
                                        <!-- <input type="text" class="form-control" id="from"> -->
                                        <input class="datecss" type="date" id="activate_date" class="form-control">
                                    </label>
                                    <br>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-holder form-holder-2">
                                    <label class="form-row-inner">
                                        <h4 class="leftaligntxt">Weblink Valid Till <span style="color:red">&nbsp; *</span></h4>
                                        <!-- <input type="text" class="form-control" id="to"> -->

                                        <input class="datecss" type="date" id="valid_till" class="form-control">
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="innerdiv">
                            <div class="form-row">
                                <div class="form-holder rightmargin">
                                    <label class="form-row-inner">
                                        <h4 class="datecss leftaligntxt"> Reminder SMS <span style="color:red">&nbsp; *</span></h4>

                                        <select id="reminder_sms" class="selectcss" required>
                                            <option value="0">none</option>
                                            <option value="1">in every day</option>
                                            <option value="2">in alternate day</option>
                                            <option value="3">in after 03 days</option>
                                        </select>
                                    </label>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-holder">
                                    <label class="form-row-inner">
                                        <h4 class="leftaligntxt datecss"> Reminder Email <span style="color:red">&nbsp; *</span></h4>
                                        <select id="reminder_email" class="selectcss" onchange="test();" required>
                                            <option value="0">none</option>
                                            <option value="1">in every day</option>
                                            <option value="2">in alternate day</option>
                                            <option value="3">in after 03 days</option>
                                        </select>
                                    </label>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="overlay" id="loadingOverlay2">
                        <div class="centered">
                            <div class="loader"></div>
                            <div class="overlay-text">Getting response . please wait...</div>
                        </div>
                    </div>

                </div>
                </section>

                <!-- SECTION 4 -->
                <h2 id="pricing-section-heading">
                    <span class="step-icon"><i class="zmdi zmdi-receipt"></i></span>
                    <span class="step-text">Pricing</span>
                </h2>
                <section id="pricing-section">
                    <div class="inner">
                        <input type="text" id="validRows">
                        <?php $current_wallet_bal = $arr_agency['current_wallet_bal'] ?>
                        <input type="hidden" name="current_wallet_bal" id="current_wallet_bal" value="<?php echo $current_wallet_bal; ?>">
                        <div id="table1" class="table-container" style="display: none;">
                            <h3>Verification Amount (Agency Wallet)</h3>
                            <p>Verification amount (Included GST)</p>
                            <div class="form-row">
                                <div class="form-holder">
                                    <label class="form-row-inner">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Verification</th>
                                                    <th colspan="2">Reserve Rate Rs. (inc. tax)</th>
                                                    <th>Total (Rs.)</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr></tr>
                                            </tbody>
                                        </table>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div id="table_1" class="table-container" style="display: none;">
                            <h3>Verification Amount</h3>
                            <p>Verification amount (Included GST)</p>
                            <p>Your Current Vallent Balance is <span id="vallet_balance"><?php echo $current_wallet_bal; ?> </span> </p>
                            <p class="text-danger" style="display:none;" id="low_balance">Your Wallet Balance is slightly low. it’s suggested that Kindly recharge the
                                wallet for seamless process at end user verifications</p>
                            <div class="form-row">
                                <div class="form-holder">
                                    <label class="form-row-inner">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Verification</th>
                                                    <th>No of User</th>
                                                    <th>Reserve Rate Rs.</th>
                                                    <th>Total (Rs.)</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr></tr>
                                            </tbody>
                                        </table>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div id="table2" class="table-container" style="display: none;">
                            <h3>Verification Amount (End User)</h3>
                            <p>Enter Your additional Amount which will be added in every verification (Included GST)</p>
                            <div class="form-row">
                                <div class="form-holder">
                                    <label class="form-row-inner">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Verifications</th>
                                                    <th>Enter Your Share<br />(inc. 18% GST)</th>
                                                    <th colspan="2">Reserve Rate Rs. (inc. tax)</th>
                                                    <th>Total Enduser Chargeable Amount(Rs.)</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr></tr>
                                            </tbody>
                                        </table>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div id="table_2" class="table-container" style="display:none;">
                            <h3>Total Revenue Share</h3>
                            <p>Total Revenue Share will be received to you</p>
                            <div class="form-row">
                                <div class="form-holder">
                                    <label class="form-row-inner">
                                        <table class="table table-bordered">
                                            <thead>
                                                <th>Verifications</th>
                                                <th>No of User</th>
                                                <th>Total (Rs.)</th>
                                            </thead>
                                            <tbody>
                                                <tr></tr>
                                            </tbody>
                                        </table>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- SECTION 5 -->
                <h2 id="publish-section-heading">
                    <span class="step-icon"><i class="zmdi zmdi-check"></i></span>
                    <span class="step-text">Publish</span>
                </h2>
                <section id="publish-section" style="display:none;">
                    <div class="inner">
                        <div class="form-row">
                            <div style="margin-left:7vw;margin-top:5vh;margin-bottom:5vh;">
                                <input type="checkbox" id="agree-terms"><a href="#">I Agree to all terms and conditions</a>

                            </div>
                        </div>
                    </div>
                </section>

            </div>
            </form>

        </div>

    </div>

    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- showing excel data error  -->
    <div class="modal fade" id="warningModal" tabindex="-1" aria-labelledby="warningModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Sr.No</th>
                                <th>Error Message</th>
                            </tr>
                        </thead>
                        <tbody id="errorTableBody">

                        </tbody>
                    </table>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>




    <script>
        function sendOTP() {
            event.preventDefault();

            let mobileNo = "<?php echo $arr_agency['mobile_no']; ?>";
            var button = document.getElementById('submitOtp_01');

            // Hide the "Send OTP" button
            button.style.display = 'none';

            // Show the OTP input field and "Verify OTP" button
            document.getElementById('userOtp').style.display = 'block';
            document.getElementById('verifyOtp').style.display = 'block';

            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'ctrlsendotp.php', true);
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
                    document.getElementById('verifytxt').style.display = 'none';
                    document.getElementById('submitOtp_01').style.display = 'none';
                    document.getElementById('userOtp').style.display = 'none';
                    document.getElementById('verifyOtp').style.display = 'none';
                    document.getElementById('requestOtp').style.display = 'none';
                    var verifytxt = document.getElementById('verifytxt');
                    verifytxt.style.display = 'block';
                    verifytxt.style.margin = '0 auto';
                    var button = document.getElementById('submitOtp_01');
                    button.style.display = 'block';
                    button.style.margin = '0 auto';
                    toastr.info('OTP Verified');
                    $('#wizard').steps('next');
                } else {
                    toastr.warning('Incorrect OTP. Please Enter Valid OTP.');
                    document.getElementById('requestOtp').style.display = 'block';
                }

                // Hide the loader and re-enable the button after the response
                document.getElementById('loadingOverlay').style.display = 'none';
                document.getElementById('verifyOtp').disabled = false;
            }, 3000); // Simulate a 3-second delay (use real response time)
        }

        $(document).ready(function() {
            $('#warningModal').modal({
                backdrop: 'static',
                keyboard: false
            });
            const today = new Date().toISOString().split('T')[0];
            $('#valid_till').attr('min', today);

            $(document).on('click', '#next_slide a[href$="#next"]', function(e) {
                // Get the values of the input fields
                var req_no = $('#request-no').val().trim();
                var upload_excel = $('#upload-excel').val().trim();

                // Debugging output
                console.log('Request No:', req_no);
                console.log('Upload Excel:', upload_excel);

                $('#error-message').hide();

                // Initialize a flag to determine if any field is empty
                var hasError = false;

                // Check if each field is empty
                if (!req_no) {
                    hasError = true;
                    $('#request-no').addClass('error');
                } else {
                    $('#request-no').removeClass('error');
                }

                if (!upload_excel) {
                    hasError = true;
                    $('#upload-excel').addClass('error');
                } else {
                    $('#upload-excel').removeClass('error');
                }

                // If any field is empty, prevent the default action and show the error message
                if (hasError) {
                    e.preventDefault();
                    $('#error-message').show();
                }
            });
        });


        function toggleGridVisibility() {
            $('#table1').css('display', '');
            $('#table_1').css('display', '');
            $('#table2').css('display', 'none');
            $('#table_2').css('display', 'none');

        }

        function toggleGridVisibility1() {
            $('#table1').css('display', 'none');
            $('#table_1').css('display', 'none');
            $('#table2').css('display', '');
            $('#table_2').css('display', '');

        }

        function updateFileName() {
            const fileInput = document.getElementById('upload-excel');
            const fileName = fileInput.files[0]?.name;

            if (fileName) {
                const fileExtension = fileName.split('.').pop().toLowerCase();

                if (fileExtension !== 'xls' && fileExtension !== 'xlsx') {
                    toastr.warning('Invalid file format. Please upload an Excel file.');
                    fileInput.value = ''; // Clear the file input
                    document.getElementById('file-name').value = ''; // Clear the file name display
                    document.getElementById('file_name_01').value = ''; // Clear the hidden file name
                    return;
                }

                // Update file name fields
                document.getElementById('file-name').value = fileName;
                document.getElementById('file_name_01').value = fileName;
                document.getElementById('uploadForm').style.display = 'block';
                $('#wizard').steps('next');
            } else {
                toastr.error('No file selected.');
            }
        }

        function test1() {
            var excel_noElement = document.getElementById('excel_no');
            var excel_no = excel_noElement.value;
            var requestNoElement = document.getElementById('request-no');
            var requestNo = requestNoElement.value; // Retrieve the selected request number
            var agencyIdElement = document.getElementById('agency_id');
            var agencyId = agencyIdElement.value; // Retrieve the agency ID

            // Check if the agency_id is valid
            if (!agencyId) {
                alert("Agency ID is missing.");
                return;
            }

            // Get the bulk_id from the hidden input field
            var bulkIdElement = document.getElementById('bulk_id');
            var bulkId = bulkIdElement.value;

            // Check if the bulk_id is valid
            if (!bulkId) {
                alert("Bulk ID is missing.");
                return;
            }

            // Check if the user selected a valid request number
            if (!requestNo) {
                alert("Please select a valid Request No.");
                return;
            }
            var current_wallet_balElement = document.getElementById('current_wallet_bal');
            var current_wallet_bal = current_wallet_balElement.value;
            if (!current_wallet_bal) {
                alert("current balance missing");
                return;
            }
            const fileInput = $('#upload-excel')[0].files[0];

            if (!fileInput) {
                toastr.error('Please select a file.');
                return;
            }

            const formData = new FormData();
            formData.append('upload-excel', fileInput);
            formData.append('request_no', requestNo);
            formData.append('excel_no', excel_no);
            // document.getElementById('loadingOverlay1').style.display = 'flex';

            $.ajax({
                url: 'https://mounarchtech.com/vocoxp/analyze.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    console.log(response);
                    const data = JSON.parse(response);
                    if (data.status === 'success') {
                        toastr.success(data.message);
                        $('#confighide').css('display', 'block');
                    } else if (data.status === 'warning') {
                        const errors = data.message.split('</br>');
                        let errorRows = '';
                        errors.forEach((error, index) => {
                            if (error.trim() !== '') { // Ignore empty rows
                                errorRows += `<tr><td>${index + 1}</td><td>${error}</td></tr>`;
                            }
                        });
                        $('#errorTableBody').html(errorRows);

                        // Show the modal
                        $('#warningModal').modal('show');
                        toastr.warning('Please Reupload the Excel Data File');

                    } else {
                        toastr.error(data.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('An error occurred:', error);
                    toastr.error('An error occurred while processing the request.');
                },
                // complete: function() {
                //     document.getElementById('loadingOverlay1').style.display = 'none';
                // }
            });
            setTimeout(function() {
                analyzeExcelData(); // Call the analyzeExcelData function

            }, 1000);
            setTimeout(function() {

                fetchData(requestNo, agencyId, bulkId); // Call the fetchData function
            }, 3000);
        }
        let totalRecords;
        let obj_1;
        let obj_2;
        let obj_3;

        function analyzeExcelData() {
            var requestNoElement = document.getElementById('request-no');
            var requestNo = requestNoElement.value;
            var formData = new FormData();
            formData.append('request_no', requestNo);
            var fileInput = $('#upload-excel')[0].files[0];
            if (fileInput) {
                formData.append('file_name', fileInput);
            }
            var reminderSmsValue = $('#reminder_sms').val();
            var reminderSmsDate = new Date(); // Current date

            if (reminderSmsValue == '1') {
                reminderSmsDate.setDate(reminderSmsDate.getDate() + 1);
            } else if (reminderSmsValue == '2') {
                reminderSmsDate.setDate(reminderSmsDate.getDate() + 2);
            } else if (reminderSmsValue == '3') {
                reminderSmsDate.setDate(reminderSmsDate.getDate() + 3);
            } else {
                reminderSmsDate = '';
            }

            var formattedReminderSmsDate = reminderSmsDate ? reminderSmsDate.toISOString().split('T')[0] : '';
            formData.append('reminder_sms', formattedReminderSmsDate);
            var reminderEmailValue = $('#reminder_email').val();
            var reminderEmailDate = new Date();

            if (reminderEmailValue == '1') {
                reminderEmailDate.setDate(reminderEmailDate.getDate() + 1);
            } else if (reminderEmailValue == '2') {
                reminderEmailDate.setDate(reminderEmailDate.getDate() + 2);
            } else if (reminderEmailValue == '3') {
                reminderEmailDate.setDate(reminderEmailDate.getDate() + 3);
            } else {
                reminderEmailDate = '';
            }
            var formattedReminderEmailDate = reminderEmailDate ? reminderEmailDate.toISOString().split('T')[0] : '';
            formData.append('reminder_email', formattedReminderEmailDate);
            formData.append('agency_id', $('#agency_id').val());
            formData.append('bulk_id', $('#bulk_id1').val());

            $.ajax({
                url: 'https://mounarchtech.com/vocoxp/upload_link/excel_count_records.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    // console.log(response);

                    // Assuming the response is a JSON object
                    const data = typeof response === 'string' ? JSON.parse(response) : response;

                    // Check if response contains "Total records"
                    if (data.status === "success" && data["Total records"] !== undefined) {
                        totalRecords = parseInt(data["Total records"], 10); // Store only the integer value
                        $('#validRows').val(totalRecords); // Store in the hidden input
                        obj_1 = parseInt(data["First name count"], 10);
                        obj_2 = parseInt(data["Second name count"], 10);
                        obj_3 = parseInt(data["Third name count"], 10);
                        // alert(obj_1);



                        // alert(totalRecords)
                    } else {
                        console.error('Unexpected response format:', data);
                    }

                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                    toastr.error('An error occurred while submitting the form.');
                }
            });
        }

        function fetchData(requestNo, agencyId, bulkId) {
            let current_wallet_bal = parseFloat($('#vallet_balance').text()) || 0;
            let container = document.getElementById('card_01');
            let containerer = document.getElementById('card_02');
            let tbody1 = document.querySelector('#table1 tbody');

            let tbody_1 = document.querySelector('#table_1 tbody');
            let tbody2 = document.querySelector('#table2 tbody');
            let tbody_2 = document.querySelector('#table_2 tbody');

            // Clear previous content
            container.innerHTML = '';
            containerer.innerHTML = '';
            tbody1.innerHTML = '';

            tbody_1.innerHTML = '';
            tbody2.innerHTML = '';
            tbody_2.innerHTML = '';

            var requestNo = $('#request-no').val();
            var agencyId = $('#agency_id').val();
            var bulkId = $('#bulk_id').val();

            $.ajax({
                url: 'crtlcontroller.php', // Change to your PHP file path
                type: 'POST',
                data: {
                    request_no: requestNo,
                    agency_id: agencyId,
                    bulk_id: bulkId
                },
                dataType: 'json',
                success: function(response) {
                    if (response.error) {
                        toastr.error(response.error);
                        // alert(response.error);
                    } else {
                        console.log(response);
                        // alert(response.obj_1);
                        $('#bulk_id1').val(response.bulk_id);
                        var verifications = response.obj_1_verifications.split(',')
                            .concat(response.obj_2_verifications.split(','))
                            .concat(response.obj_3_verifications.split(','));


                        console.log(verifications);

                        var arrVeri = <?php echo $jsonArrVeri; ?>;
                        console.log(arrVeri);

                        // Store rates and amounts
                        let rateMap = {};

                        arrVeri.forEach(function(element) {

                            rateMap[element.verification_id] = {
                                rate: parseFloat(element.rate),
                                cgstPercentage: parseFloat(element.cgst_percentage),
                                sgstPercentage: parseFloat(element.sgst_percentage),
                                reserveRate: parseFloat(element.reserve_rate) // Assuming reserve_rate exists
                            };

                            let cgstAmount = (rateMap[element.verification_id].rate * rateMap[element.verification_id].cgstPercentage) / 100;
                            let sgstAmount = (rateMap[element.verification_id].rate * rateMap[element.verification_id].sgstPercentage) / 100;
                            rateMap[element.verification_id].totalAmount = rateMap[element.verification_id].rate
                            rateMap[element.verification_id].taxAmount = cgstAmount + sgstAmount;
                        });

                        console.log(rateMap);

                        const obj_11 = response.obj_1_verifications.split(',').length;
                        const obj_22 = response.obj_2_verifications.split(',').length;
                        const obj_33 = response.obj_3_verifications.split(',').length;

                        verifications.forEach(function(element, index) {
 if (!rateMap[element]) {
        // Skip this element if it has no entry in rateMap or is invalid
        return;
    }
                            let verificationType = '';
                            let reserveRate = '';
                            let totalAmount = '';
                            let taxAmount = '';
                            let grandAmount = '';
                            let object = '';
                            let obj_no = '';
                            let totalRevenueSum = 0;

                            let totalRecords = 0;
                            if (index < obj_11) {
                                totalRecords = obj_1;
                                object = response.obj_1;
                                obj_no = 'obj_1';
                            } else if (index < obj_11 + obj_22) {
                                totalRecords = obj_2;
                                obj_no = 'obj_2';
                                object = response.obj_2;
                            } else {
                                totalRecords = obj_3;
                                obj_no = 'obj_3';
                                object = response.obj_3;

                            }


                            switch (element) {
                                case 'DVF-00001':
                                    verificationType = 'Adhaar';
                                    reserveRate = rateMap["DVF-00001"].reserveRate.toFixed(2);
                                    totalAmount = rateMap["DVF-00001"].totalAmount.toFixed(2);
                                    taxAmount = rateMap["DVF-00001"].taxAmount.toFixed(2);
                                    grandAmount = (totalAmount * 1) + (taxAmount * 1);
                                    break;
                                case 'DVF-00002':
                                    verificationType = 'Pan Card';
                                    reserveRate = rateMap["DVF-00002"].reserveRate.toFixed(2);
                                    totalAmount = rateMap["DVF-00002"].totalAmount.toFixed(2);
                                    taxAmount = rateMap["DVF-00002"].taxAmount.toFixed(2);
                                    grandAmount = (totalAmount * 1) + (taxAmount * 1);
                                    break;
                                case 'DVF-00003':
                                    verificationType = 'Voter';
                                    reserveRate = rateMap["DVF-00003"].reserveRate.toFixed(2);
                                    totalAmount = rateMap["DVF-00003"].totalAmount.toFixed(2);
                                    taxAmount = rateMap["DVF-00003"].taxAmount.toFixed(2);
                                    grandAmount = (totalAmount * 1) + (taxAmount * 1);
                                    break;
                                case 'DVF-00004':
                                    verificationType = 'DL';
                                    reserveRate = rateMap["DVF-00004"].reserveRate.toFixed(2);
                                    totalAmount = rateMap["DVF-00004"].totalAmount.toFixed(2);
                                    taxAmount = rateMap["DVF-00004"].taxAmount.toFixed(2);
                                    grandAmount = (totalAmount * 1) + (taxAmount * 1);
                                    break;
                                case 'DVF-00005':
                                    verificationType = 'E-Crime';
                                    reserveRate = rateMap["DVF-00005"].reserveRate.toFixed(2);
                                    totalAmount = rateMap["DVF-00005"].totalAmount.toFixed(2);
                                    taxAmount = rateMap["DVF-00005"].taxAmount.toFixed(2);
                                    grandAmount = (totalAmount * 1) + (taxAmount * 1);
                                    break;
                                case 'DVF-00008':
                                    verificationType = 'Mobile Verification';
                                    reserveRate = rateMap["DVF-00008"].reserveRate.toFixed(2);
                                    totalAmount = rateMap["DVF-00008"].totalAmount.toFixed(2);
                                    taxAmount = rateMap["DVF-00008"].taxAmount.toFixed(2);
                                    grandAmount = (totalAmount * 1) + (taxAmount * 1);
                                    break;
                                default:
            // Skip this element if no match
            return;
                            }

                            // Add to card_01
                            let divElement = document.createElement('div');
                            divElement.className = 'col-md-4';

                            // divElement.innerHTML = '<button type="button" class="otp-button">' + verificationType + '</button>';

                            container.appendChild(divElement);

                            // Add to table1
                            let newRow1 = document.createElement('tr');
                            newRow1.innerHTML = `
                            <td>${object}-${verificationType}<label style="display:none">@${obj_no}</label></td>
                            <td>${totalAmount}</td>
                            <td>${taxAmount}</td>
                            <td class="total-amount">${grandAmount}</td>`;

                            let amount_total = totalRecords * grandAmount;
                            let newRow_1 = document.createElement('tr');
                            newRow_1.innerHTML = `
                                 <td>${object}-${verificationType}<label style="display:none">@${obj_no}</label></td>
                                 <td class="users">${totalRecords}</td>
                                 <td>${grandAmount.toFixed(2)}</td>
                                  <td class="amount_total">${amount_total.toFixed(2)}</td>`;
                            tbody_1.appendChild(newRow_1);

                            // Function to update the total row at the end
                            function updateTotalRow() {
                                let totalAmount = 0;

                                // Loop through each row in tbody_1 and sum the amount
                                const rows = tbody_1.querySelectorAll('tr');
                                rows.forEach(row => {
                                    const amountCell = row.querySelector('.amount_total');
                                    if (amountCell) {
                                        const amountValue = parseFloat(amountCell.innerText);
                                        if (!isNaN(amountValue)) {
                                            totalAmount += amountValue;
                                        }
                                    }
                                });

                                // Remove existing all_total row if it exists
                                let allTotalRow = tbody_1.querySelector('tr.all_total');
                                if (allTotalRow) {
                                    allTotalRow.remove();
                                }
                                allTotalRow = document.createElement('tr');
                                allTotalRow.className = 'all_total';
                                allTotalRow.innerHTML = `
                                    <td colspan="3" style="text-align: end;">all_total</td>
                                    <td class="bg-primary text-light">${totalAmount.toFixed(2)}</td> `;

                                tbody_1.appendChild(allTotalRow);

                            }
                            updateTotalRow();
                            tbody1.appendChild(newRow1);


                            // Add to table2
                            let newRow2 = document.createElement('tr');
                            newRow2.innerHTML = `
                                <td>${object}-${verificationType}<label style="display:none">@${obj_no}</label></td>
                                <td><input type="text" name="amount_${element}" class="input-amount" data-reserve-rate="${reserveRate}" onchange="updateLastTotal(this)" /></td>
                                <td>${totalAmount}</td>
                                 <td>${taxAmount}</td>
                                <td class="total-amount">${grandAmount}</td>`;
                            tbody2.appendChild(newRow2);

                         
                            let revenuetotal = totalRecords * grandAmount;
                            totalRevenueSum += revenuetotal;
                            let newRow_2 = document.createElement('tr');
                            newRow_2.innerHTML = `
                            <td>${object}-${verificationType}<label style="display:none">@${obj_no}</label></td>
                             <td class="users">${totalRecords}</td>
                              <td class="totalrevenu">${revenuetotal}</td>`;
                            tbody_2.appendChild(newRow_2);
                            let totalRow = document.getElementById('totalRow');

                            // If the total row doesn't exist, create it
                            if (!totalRow) {
                                totalRow = document.createElement('tr');
                                totalRow.id = 'totalRow';
                                totalRow.innerHTML = `
        <td colspan="2"><strong>Total Revenue</strong></td>
        <td class="totalrevenu"><strong>${totalRevenueSum.toFixed(2)}</strong></td>`;
                                tbody_2.appendChild(totalRow);
                            } else {
                                totalRow.querySelector('.totalrevenu').innerHTML = `<strong>${totalRevenueSum.toFixed(2)}</strong>`;
                                tbody_2.appendChild(totalRow);
                            }
                        });




                        $('#table1').show();
                        $('#table2').show();
                    }
                },

            });
        }

        $(document).ready(function() {



            $(document).on('click', '#final_submit a[href$="#finish"]', function(e) {
                e.preventDefault();

                if (!$('#agree-terms').is(':checked')) {
                    toastr.error('Please agree to all terms and conditions before submitting.');
                    return;
                }
                var formData = new FormData();

                var fileInput = $('#upload-excel')[0].files[0];
                if (fileInput) {
                    formData.append('file_name', fileInput);
                }
                // Handle reminder_sms
                var reminderSmsValue = $('#reminder_sms').val();
                var reminderSmsDate = new Date(); // Current date

                if (reminderSmsValue == '1') {

                    reminderSmsDate.setDate(reminderSmsDate.getDate() + 1);
                } else if (reminderSmsValue == '2') {

                    reminderSmsDate.setDate(reminderSmsDate.getDate() + 2);
                } else if (reminderSmsValue == '3') {

                    reminderSmsDate.setDate(reminderSmsDate.getDate() + 3);
                } else {

                    reminderSmsDate = '';
                }

                // Format reminder_sms date as YYYY-MM-DD
                if (reminderSmsDate !== '') {
                    var formattedReminderSmsDate = reminderSmsDate.toISOString().split('T')[0];
                } else {
                    var formattedReminderSmsDate = ''; // Empty for "none" option
                }

                formData.append('reminder_sms', formattedReminderSmsDate);

                var reminderEmailValue = $('#reminder_email').val();
                var reminderEmailDate = new Date();

                if (reminderEmailValue == '1') {
                    reminderEmailDate.setDate(reminderEmailDate.getDate() + 1);
                } else if (reminderEmailValue == '2') {
                    reminderEmailDate.setDate(reminderEmailDate.getDate() + 2);
                } else if (reminderEmailValue == '3') {
                    reminderEmailDate.setDate(reminderEmailDate.getDate() + 3);
                } else {
                    reminderEmailDate = '';
                }

                if (reminderEmailDate !== '') {
                    var formattedReminderEmailDate = reminderEmailDate.toISOString().split('T')[0];
                } else {
                    var formattedReminderEmailDate = ''; // Empty for "none" option
                }

                formData.append('reminder_email', formattedReminderEmailDate);


                // Append other form fields
                formData.append('agency_id', $('#agency_id').val());
                formData.append('bulk_id', $('#bulk_id1').val());
                formData.append('amount', $('input[name="amount"]:checked').val());
                formData.append('activate_date', $('#activate_date').val());
                formData.append('valid_till', $('#valid_till').val());
                // formData.append('reminder_sms', $('#reminder_sms').val());
                // formData.append('reminder_email', $('#reminder_email').val());

                // Append table data
                formData.append('table1', JSON.stringify(collectTableData('#table1')));
                formData.append('table2', JSON.stringify(collectTableData('#table2')));

                // Send the form data using AJAX
                $.ajax({
                    // url: 'https://mounarchtech.com/vocoxp/upload_link/ctrlallinsert.php',
                    url: 'https://mounarchtech.com/vocoxp/upload_link/newCtrlInsert.php',

                    type: 'POST',
                    data: formData,
                    processData: false, // Prevent jQuery from automatically transforming the data into a query string
                    contentType: false, // Prevent jQuery from setting the Content-Type header
                    success: function(response) {
                        // console.log(response);
                        // Handle the response from the server
                        // console.log(response);
                        toastr.success('Form submitted successfully');
                        // header("Location: https://mounarchtech.com/vocoxp/upload_link/thankyou.php");
 window.location.href = "https://mounarchtech.com/vocoxp/upload_link/thankyou.php";
   
                        setTimeout(function() {
                            window.location.reload();
                        }, 1000);
                    },
                    error: function(xhr, status, error) {
                        // Handle any errors
                        console.error('Error:', error);
                        toastr.error('An error occurred while submitting the form.');
                    }
                });
            });

            // Function to collect table data
            function collectTableData(tableId) {
                var data = [];
                $(tableId + ' tbody tr').each(function() {
                    var row = {};
                    $(this).find('td').each(function(index) {
                        row['column' + index] = $(this).text(); // Collect data from each cell
                    });
                    if (!$.isEmptyObject(row)) {
                        data.push(row);
                    }
                });
                return data;
            }
        });




        function updateLastTotal(inputElement) {

            const userAmount = parseFloat(inputElement.value) || 0;

            const row = inputElement.closest('tr');

            const thirdColumnValue = parseFloat(row.cells[2].innerText) || 0;
            const fourthColumnValue = parseFloat(row.cells[3].innerText) || 0;

            const newTotalAmount = userAmount + thirdColumnValue + fourthColumnValue;
            row.querySelector('.total-amount').innerText = newTotalAmount.toFixed(2);
        }

        //code for showing request not with upload exel file 
        document.getElementById('request-no').addEventListener('change', function() {
            var selectedOption = this.options[this.selectedIndex];
            var requestNo = selectedOption.value;

            // if (requestNo) {
            //     document.getElementById('upload-info').textContent = '' + requestNo;
            // } else {
            //     document.getElementById('upload-info').textContent = 'Upload Excel file of';
            // }
        });

        document.addEventListener('DOMContentLoaded', function() {

            var requestSelect = document.getElementById('request-no');
            var myDiv = document.getElementById('wizard-bodyDiv');
            var reminder_email = document.getElementById('reminder_email');
            var next_slide = document.getElementById('next_slide');
            if (requestSelect) {
                requestSelect.addEventListener('change', function() {
                    if (this.value) {
                        myDiv.style.display = 'block';
                        // toastr.info('Request Select changed to: ' + this.options[this.selectedIndex].text)
                        // alert('Request Select changed to: ' + this.options[this.selectedIndex].text);
                    } else {
                        myDiv.style.display = 'none';
                    }
                });
            } else {
                console.error('Request Select element not found');
            }
        });

        function test() {
            // Show the loader
            document.getElementById('loadingOverlay2').style.display = 'flex';

            var reminderSms = $('#reminder_sms').val();
            var reminderEmail = $('#reminder_email').val();
            var activateDate = $('#activate_date').val();
            var validTill = $('#valid_till').val();
            var amount = $('input[name="amount"]:checked').val();
            var next_slide = $('#next_slide'); // Corrected the selector to use jQuery
            var isValid = true;

            // Clear previous error messages
            $('.error-message').remove();

            // Check Reminder SMS
            if (reminderSms === '0') {
                $('#reminder_sms').after('<div class="error-message">Reminder SMS is required.</div>');
                isValid = false;
            }

            // Check Reminder Email
            if (reminderEmail === '0') {
                $('#reminder_email').after('<div class="error-message">Reminder Email is required.</div>');
                isValid = false;
            }

            // Check Activate Date
            if (!activateDate) {
                $('#activate_date').after('<div class="error-message">Activation date is required.</div>');
                isValid = false;
            }

            // Check Valid Till Date
            if (!validTill) {
                $('#valid_till').after('<div class="error-message">Valid till date is required.</div>');
                isValid = false;
            }

            // Check Amount
            if (!amount) {
                $('#radio').after('<div class="error-message">Amount to be paid by is required.</div>');
                isValid = false;
            }

            // Handle results after validation
            if (isValid) {
                $('#next_slide').attr('style', 'display: block !important');
                $('#wizard').steps('next');
            } else {
                toastr.warning('All Fields are required');
                $('#reminder_sms').val('');
                $('#reminder_email').val('');
                $('#activate_date').val('');
                $('#valid_till').val('');
                $('input[name="amount"]').prop('checked', false);
            }

            // Hide the loader after processing
            setTimeout(function() {
                document.getElementById('loadingOverlay2').style.display = 'none';
            }, 1000); // Adjust the delay as necessary
        }
    </script>



    <script src="js/jquery.steps.js"></script>

    <script src="js/jquery-ui.min.js"></script>

    <script src="js/main.js"></script>

</body>

</html>