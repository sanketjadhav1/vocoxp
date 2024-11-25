<?php

include_once '../connection.php';
$connection = connection::getInstance();

$mysqli = $connection->getConnection();



$connection1 = database::getInstance();

$mysqli1 = $connection1->getConnection();

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $agency_id = $_GET['agency_id'];
    $request_no = $_GET['request_no'];
    $bulk_id = $_GET['bulk_id'];
    $fetch_agency = "SELECT `company_name`, `agency_logo`, `address`, `mobile_no` FROM `agency_header_all` WHERE `agency_id`='$agency_id'";
    $res_agency = mysqli_query($mysqli, $fetch_agency);
    $arr_agency = mysqli_fetch_assoc($res_agency);
    $otp = rand(10000, 99999);
    $weblink_details = "SELECT `verifications` FROM `bulk_weblink_request_all` WHERE `bulk_id` = '$bulk_id'";
    $weblink_result = $mysqli->query($weblink_details);
    $weblink_array = mysqli_fetch_assoc($weblink_result);
    $verifications = $weblink_array['verifications'];
    $update_end_details = "UPDATE `bulk_end_user_transaction_all` SET `scheduled_verifications`='$verifications' WHERE `bulk_id`='$bulk_id'";
    $res_update = mysqli_query($mysqli, $update_end_details);
    $fetch_data = "SELECT * FROM `bulk_weblink_request_all` WHERE `agency_id`='$agency_id'";
    $res_data = mysqli_query($mysqli, $fetch_data);
    while ($arr_data = mysqli_fetch_assoc($res_data)) {
        $arr_all[] = $arr_data;
    }
}
$fetch_verification = "SELECT * FROM `verification_configuration_all` WHERE `ver_type`='1'";
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
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Registration</title>
    <!-- Bootstrap CDN Links -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <style>
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
    </style>
</head>

<body>

    <div class="container mt-5 centered">
        <img src="<?php echo $arr_agency['agency_logo']; ?>" alt="Company Logo" class="logo">
        <h4><?php echo $arr_agency['address'] ?></h4>
        <div id="otp-section" class="centered mt-3">
            <div class="mb-3 text-center">
                <h4> <?php echo maskNumber($arr_agency['mobile_no']); ?></h4>
                <button type="button" value="send_otp" class="otp-button btn btn-primary form-control" id="submitOtp_01" style="max-width: 300px;" onclick="sendOTP()">Generate OTP</button>
                <input type="text" class="otp-input form-control mx-auto mt-3" id="userOtp" placeholder="Enter OTP" style="max-width: 300px;">
                <button type="button" class="otp-button btn btn-primary mt-3 form-control" id="verifyOtp" style="max-width: 300px;" onclick="verifyOTP()">Validate OTP</button>
                <span id="requestOtp" style="display:none;color:linear-gray; cursor: pointer;" onclick="sendOTP()">Request OTP</span>
                <span id="verifytxt" style="display: none; margin-top: 10px;">OTP Verified</span>
            </div>
        </div>

        <div id="file-upload-section" class="file-upload-section mt-5">
            <div class="mb-3">
                <label for="fileInput" class="form-label">Upload Excel File:</label>
                <input type="file" id="fileInput" class="form-control" accept=".xlsx,.xls">
            </div>
            <div class="mb-3">
                <button id="validateBtn" class="btn btn-warning">Validate</button>
            </div>
        </div>

        <div id="dropdown-section" class="file-upload-section mt-3">
            <div class="mb-3">
                <label for="validatedRecords" class="form-label">Check Validated Records:</label>
                <select id="validatedRecords" class="form-select" style="max-width: 300px; margin: 0 auto;">
                    <option value="">Select Record</option>
                    <option value="1">Record 1</option>
                    <option value="2">Record 2</option>
                    <option value="3">Record 3</option>
                </select>
            </div>
            <button id="submitBtn" class="btn btn-primary">Submit</button>
        </div>
    </div>


    <script>
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
                // var verifytxt = document.getElementById('verifytxt');
                // verifytxt.style.display = 'block';
                // verifytxt.style.margin = '0 auto';
                var button = document.getElementById('submitOtp_01');
                button.style.display = 'block';
                button.style.margin = '0 auto';
                document.getElementById('otp-section').style.display = 'none';
                document.querySelector('.file-upload-section').style.display = 'block';

            } else {
                alert("invalid otp")
                document.getElementById('requestOtp').style.display = 'block';
            }
        }
    </script>

</body>

</html>