<?php

include_once '../connection.php';
$connection = connection::getInstance();

$mysqli = $connection->getConnection();



$connection1 = database::getInstance();

$mysqli1 = $connection1->getConnection();

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $agency_id = $_GET['agency_id'];
    // $request_no = $_GET['request_no'];
    $emp_id = $_GET['emp_id'];
    $fetch_agency = "SELECT `company_name`, `agency_logo`, `address`, `mobile_no` FROM `agency_header_all` WHERE `agency_id`='$agency_id'";
    $res_agency = mysqli_query($mysqli, $fetch_agency);
    $arr_agency = mysqli_fetch_assoc($res_agency);
    $otp = rand(10000, 99999);
    // $weblink_details = "SELECT `verifications` FROM `bulk_weblink_request_all` WHERE `bulk_id` = '$bulk_id'";
    // $weblink_result = $mysqli->query($weblink_details);
    // $weblink_array = mysqli_fetch_assoc($weblink_result);
    // $verifications = $weblink_array['verifications'];
    // $update_end_details = "UPDATE `bulk_end_user_transaction_all` SET `scheduled_verifications`='$verifications' WHERE `bulk_id`='$bulk_id'";
    // $res_update = mysqli_query($mysqli, $update_end_details);
    // $fetch_data = "SELECT * FROM `bulk_weblink_request_all` WHERE `agency_id`='$agency_id'";
    // $res_data = mysqli_query($mysqli, $fetch_data);
    // while ($arr_data = mysqli_fetch_assoc($res_data)) {
    //     $arr_all[] = $arr_data;
    // }
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap CDN Links -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
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

        #action-section {
            width: 60vw;
            margin: auto;
        }
    </style>
</head>
<div class="container mt-5 centered">
    <img src="<?php echo $arr_agency['agency_logo']; ?>" alt="Company Logo" class="logo">
    <h4><?php echo $arr_agency['address'] ?></h4>
    <input type="hidden" name="agency_id" id="agency_id" value="<?php echo $agency_id; ?>">

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
            <input type="file" id="upload-excel" name="upload_excel" class="form-control" accept=".xlsx,.xls">
        </div>

        <div class="mb-3">
            <button id="validateBtn" class="btn btn-primary">Start Analyzing</button>
        </div>

        <!-- Container for Progress Bar and Status -->
        <div class="progress-container mt-3">
            <div class="progress" style="height: 20px; display: none;" id="progressBarContainer">
                <div id="progressBar" class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
            </div>
            <div id="statusMessage" class="mt-2" style="display: none; text-align: center;"></div>
        </div>
    </div>

    <div id="action-section" style="display:none;">
        <div style="display:flex;justify-content: center;">
            <div class="mb-3" style="width: 20vw;">
                <label for="operation" class="form-label">Uploaded Employee's will be:</label>
            </div>
            <div class="mb-3" style="width: 20vw;">
                <select id="operation" class="form-control">
                    <option value="0" disabled selected>Select Operation</option>
                    <option value="1">APPEND</option>
                    <option value="2">REPLACE</option>
                    <option value="3">REMOVE</option>
                </select>
            </div>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="confirmCheckbox">
            <label class="form-check-label" for="confirmCheckbox">
                I am Confirming that I am completely aware the concern & Results of the above selection options
            </label>
        </div>

        <div class="mb-3">
            <button id="confirmBtn" class="btn btn-success">Confirm This action</button>
        </div>
    </div>
</div>



</div>

<!-- modal for showing excel file validation error  -->
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
            document.querySelector('.file-upload-section').style.display = 'block';

        } else {
            alert("invalid otp")
            document.getElementById('requestOtp').style.display = 'block';
        }
    }
</script>
<script>
    $(document).ready(function() {
        function test1() {
            const fileInput = $('#upload-excel')[0].files[0];

            if (!fileInput) {
                alert("Please select a file.");
                return;
            }

            const formData = new FormData();
            formData.append('upload-excel', fileInput);

            // Show progress bar and reset it
            $('#progressBarContainer').css('display', 'block');
            $('#progressBar').css('width', '0%').text('0%');
            $('#statusMessage').hide();

            $.ajax({
                url: 'https://mounarchtech.com/vocoxp/analyze.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                xhr: function() {
                    var xhr = new XMLHttpRequest();

                    // Upload progress
                    xhr.upload.addEventListener('progress', function(event) {
                        if (event.lengthComputable) {
                            let percentComplete = Math.round((event.loaded / event.total) * 100);
                            $('#progressBar').css('width', percentComplete + '%').text(percentComplete + '%');
                        }
                    }, false);

                    return xhr;
                },
                success: function(response) {
                    console.log(response);
                    const data = JSON.parse(response);

                    // Show appropriate status message
                    if (data.status === 'success') {
                        $('#statusMessage').text('Upload Successful').css('color', 'green').show();
                        $('#confighide').css('display', 'block');

                        $('#action-section').show();
                        toastr.success(data.message);
                    } else if (data.status === 'warning') {
                        $('#statusMessage').text('Processing Failed. Please Reupload the File').css('color', 'orange').show();
                        const errors = data.message.split('</br>');
                        let errorRows = '';
                        errors.forEach((error, index) => {
                            if (error.trim() !== '') {
                                errorRows += `<tr><td>${index + 1}</td><td>${error}</td></tr>`;
                            }
                        });
                        $('#errorTableBody').html(errorRows);
                        $('#warningModal').modal('show');
                    } else {
                        $('#statusMessage').text('Upload Failed').css('color', 'red').show();
                    }
                },
                error: function(xhr, status, error) {
                    console.error('An error occurred:', error);
                    $('#statusMessage').text('An error occurred while processing the request.').css('color', 'red').show();
                }
            });
        }

        // Assign the test1 function to the button click event
        $('#validateBtn').click(test1);


        $(document).on('click', '#confirmBtn', function(e) {
            e.preventDefault();

            // Check if the "I Agree to all terms and conditions" checkbox is checked
            if (!$('#confirmCheckbox').is(':checked')) {
                toastr.error('Please agree to all terms and conditions before submitting.');
                return;
            }
            var formData = new FormData();
            var operation = $('#operation').val();
            if (operation) {
                formData.append('operation', operation);
            } else {
                toastr.error('Please select an operation before submitting.');
                return;
            }

            // Append file input value if a file is selected
            var fileInput = $('#upload-excel')[0].files[0];
            if (fileInput) {
                formData.append('file_name', fileInput);
            }
            formData.append('agency_id', $('#agency_id').val());
            $.ajax({
                url: 'https://mounarchtech.com/vocoxp/upload_link/employee_operations.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    console.log(response);
                    toastr.success('Form submitted successfully');
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
    });
</script>
</body>

</html>