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
$system_date_time = date("Y-m-d H:i:s");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome Page</title>
    
    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <!-- jQuery (necessary for Toastr) -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

    <!-- Toastr JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

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
    </style>
    <style>
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

html,
body {
    height: 100vh;
    overflow: hidden;
}

.container {
    position: relative;
    min-height: 100vh;
    overflow: hidden;
}

.row {
    display: flex;
    flex-wrap: wrap;
    height: 100vh;
}

.col {
    width: 50%;
}

.align-items-center {
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
}

.form-wrapper {
    width: 100%;
    max-width: 28rem;
}

.form {
    padding: 1rem;
    background-color: var(--white);
    border-radius: 1.5rem;
    width: 100%;
    box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
    transform: scale(0);
    transition: .5s ease-in-out;
    transition-delay: 1s;
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
    outline: none;
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

.container.sign-in .form.sign-in,
.container.sign-in .social-list.sign-in,
.container.sign-in .social-list.sign-in>div,
.container.sign-up .form.sign-up,
.container.sign-up .social-list.sign-up,
.container.sign-up .social-list.sign-up>div {
    transform: scale(1);
}

.content-row {
    position: absolute;
    top: 0;
    left: 0;
    pointer-events: none;
    z-index: 6;
    width: 100%;
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

.container.sign-in .text.sign-in h2,
.container.sign-in .text.sign-in p,
.container.sign-in .img.sign-in img,
.container.sign-up .text.sign-up h2,
.container.sign-up .text.sign-up p,
.container.sign-up .img.sign-up img {
    transform: translateX(0);
}

/* BACKGROUND */

.container::before {
    content: "";
    position: absolute;
    top: 0;
    right: 0;
    height: 100vh;
    width: 300vw;
    transform: translate(35%, 0);
    background-image: linear-gradient(-45deg, var(--primary-color) 0%, var(--secondary-color) 100%);
    transition: 1s ease-in-out;
    z-index: 6;
    box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
    border-bottom-right-radius: max(50vw, 50vh);
    border-top-left-radius: max(50vw, 50vh);
}

.container.sign-in::before {
    transform: translate(0, 0);
    right: 50%;
}

.container.sign-up::before {
    transform: translate(100%, 0);
    right: 50%;
}

/* RESPONSIVE */

@media only screen and (max-width: 425px) {

    .container::before,
    .container.sign-in::before,
    .container.sign-up::before {
        height: 100vh;
        border-bottom-right-radius: 0;
        border-top-left-radius: 0;
        z-index: 0;
        transform: none;
        right: 0;
    }

    /* .container.sign-in .col.sign-up {
        transform: translateY(100%);
    } */

    .container.sign-in .col.sign-in,
    .container.sign-up .col.sign-up {
        transform: translateY(0);
    }

    .content-row {
        align-items: flex-start !important;
    }

    .content-row .col {
        transform: translateY(0);
        background-color: unset;
    }

    .col {
        width: 100%;
        position: absolute;
        padding: 2rem;
        background-color: var(--white);
        border-top-left-radius: 2rem;
        border-top-right-radius: 2rem;
        transform: translateY(100%);
        transition: 1s ease-in-out;
    }

    .row {
        align-items: flex-end;
        justify-content: flex-end;
    }
    
     .logo {
            display: block;
            margin: 0 auto;
            width: 100px; /* Set the desired width */
            height: 100px; /* Set the desired height */
            border-radius: 50%; /* Make the image circular */
            object-fit: cover; /* Ensure the image covers the entire area */
            vertical-align: middle;
    border-style: none;
        }   

    .form,
    .social-list {
        box-shadow: none;
        margin: 0;
        padding: 0;
    }

    .text {
        margin: 0;
    }

    .text p {
        display: none;
    }

    .text h2 {
        margin: .5rem;
        font-size: 2rem;
    }
}
</style>
</head>
<body>
    <?php

$enduser_id = $_GET['enduser_id'];
$select_end_details = "SELECT  `agency_id`, `name`, `mobile`, `verification_report`, `verification_details`, `weblink_opened_on`,`payment_from` FROM `bulk_end_user_transaction_all` WHERE `end_user_id` = '$enduser_id'";
$result = $mysqli->query($select_end_details);
$select_array = mysqli_fetch_assoc($result);

$mobile=$select_array['mobile'];
$agency_id=$select_array['agency_id'];
$weblink_opened_on=$select_array['weblink_opened_on'];
$payment_done_by = $select_array['payment_from'];

   
// Check if the time is '00:00:00'
if ($weblink_opened_on === '0000-00-00 00:00:00') {
   $update_end_details = "UPDATE `bulk_end_user_transaction_all` SET `weblink_opened_on`='$system_date_time', `status`='1' WHERE `end_user_id`='$enduser_id'";
    $res_update = mysqli_query($mysqli, $update_end_details); 
} else {
    
}


 $agency_details = "SELECT `company_name`,`agency_logo` FROM `agency_header_all` WHERE `agency_id` = '$agency_id'";
 $agency_result = $mysqli->query($agency_details);
$agency_array = mysqli_fetch_assoc($agency_result);
$company_name=$agency_array['company_name'];
$agency_logo=$agency_array['agency_logo'];
$otp = rand(100000, 999999);
?>
<script>
     window.received = <?php echo json_encode($otp); ?>;
</script>
<?php
//sms_helper_accept($mobile, $otp);
 if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'send_sms') {
    $enduser_id = $_POST['end_user_id'];
    $otp = $_POST['otp'];

    // Retrieve mobile number from database
    $select_end_details = "SELECT `mobile` FROM `bulk_end_user_transaction_all` WHERE `end_user_id` = '$enduser_id'";
    $result = $mysqli->query($select_end_details);
    $select_array = mysqli_fetch_assoc($result);
    $mobile = $select_array['mobile'];

    // Call the function to send SMS
    sms_helper_accept($mobile, $otp);
    echo json_encode(['success' => true]);
    exit;
}
 
function sms_helper_accept($contact, $otp) {
    date_default_timezone_set("Asia/Kolkata");
    
    $message = urlencode("OTP for Document Verification is $otp. Developed by Micro Integrated");
    
    $route = "4";
    $mobile_number = "91" . $contact;
    
    $postData = [
        "authkey" => "362180AunaHgulfCm6698af66P1",
        "mobiles" => $mobile_number,
        "message" => $message,
        "sender" => "VOCOxP",
        "route" => $route,
        "response" => "json",
        "DLT_TE_ID" => "1707172128178664516"
    ];

    $url = "http://api.msg91.com/api/sendhttp.php";

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $postData,
        CURLOPT_SSL_VERIFYHOST => 0,
        CURLOPT_SSL_VERIFYPEER => 0
    ]);

    $output = curl_exec($ch);

    if (curl_errno($ch)) {
        echo 'cURL Error: ' . curl_error($ch);
    } else {
        //echo 'API Response: ' . $output;
    }

    curl_close($ch);
}
function maskNumber($number) {
    $numStr = strval($number);
    $maskedNum = str_repeat('X', strlen($numStr) - 3) . substr($numStr, -3);
    return $maskedNum;
} 
 
    ?>
    <div id="container" class="container">
        <!-- FORM SECTION -->
        <div class="row">
            <!-- SIGN UP -->
            <div class="col align-items-center flex-col sign-up">
                <div class="form-wrapper align-items-center">
                    <div class="form sign-up">
                           <h1>Contact Verification</h1>
        <p>We have sent you an OTP in you contact number <b><u><?php echo maskNumber($mobile); ?></u></b> Please enter the code below </p>
                        <div class="input-group">
                            <i class='bx bxs-user'></i>
                            <input type="text" id="userOtp" >
                        </div>
                       
                        <button id="verifyOtp">
                            Verify
                        </button>
                       
                    </div>
                </div>
            
            </div>
            <!-- END SIGN UP -->
            <!-- SIGN IN -->
            <div class="col align-items-center flex-col sign-in">
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
                            <input type="image" id="refreshCaptcha" style="width: 40px;height:30px;" src="images/refresh.jpeg" alt="Submit">
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
            <!-- END SIGN IN -->
        </div>
        <!-- END FORM SECTION -->
        <!-- CONTENT SECTION -->
        <div class="row content-row">
            <!-- SIGN IN CONTENT -->
            <div class="col align-items-center flex-col">
                <div class="text sign-in">
                    <div id="agency_logo">
                <img src="<?php echo $agency_logo; ?>"  alt="Company Logo" class="logo" width="45%" height ="45%">
  </div>                  
                    <h2>
                        Welcome to <?php echo $company_name;?>
                    </h2>    
                </div>
                <div class="img sign-in">
        
                </div>
            </div>
            <!-- END SIGN IN CONTENT -->
            <!-- SIGN UP CONTENT -->
            <div class="col align-items-center flex-col">
                <div class="img sign-up">
                
                </div>
                <div class="text sign-up">
                    <h2>
                        Let us know you
                    </h2>
    
                </div>
            </div>
            <!-- END SIGN UP CONTENT -->
        </div>
        <!-- END CONTENT SECTION -->
    </div>
    


    <script>
         function refreshAlphanumericCaptcha() {
    fetch('captcha.php')
        .then(response => response.json())
        .then(data => {
            if (data.type === 'alphanumeric') {
                const canvas = document.getElementById('canvas');
        const ctx = canvas.getContext('2d');

        // Set canvas properties
        ctx.fillStyle = 'white'; // Background color
        ctx.fillRect(0, 0, canvas.width, canvas.height);

        ctx.fillStyle = 'black'; // Text color
        ctx.font = '25px Agency FB'; // Font size and type
        ctx.textBaseline = 'top'; // Text alignment
        const text = data.code;
        const x = 10;
        const y = 10;
        ctx.fillText(text, x, y);

                document.getElementById('captchaCodeDisplay').innerText = data.code; // Update the CAPTCHA code
            }
        });
}

        function loadCaptcha() {
            fetch('captcha.php')
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
                        data.images.forEach((image, index) => {
                            const img = document.createElement('img');
                            img.src = image.url;
                            img.dataset.id = image.id;
                            img.onclick = function() {
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
        const x = 10;
        const y = 10;
        ctx.fillText(text, x, y);
                        document.getElementById('captchaCodeDisplay').innerText = data.code; // Display the CAPTCHA code
                        alphanumericContainer.style.display = 'block'; // Show the alphanumeric CAPTCHA section
                    }
                });
        }

        document.getElementById('submitCaptcha').addEventListener('click', function() {
            const captchaType = document.getElementById('captchaQuestion').innerText.includes('code') ? 'alphanumeric' : 'image';

            if (captchaType === 'image') {
                const selectedImages = [];
                document.querySelectorAll('.captcha-images img.selected').forEach(img => {
                    selectedImages.push(img.dataset.id);
                });

                fetch('verify.php', {
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
                            var agency_logo = document.getElementById("agency_logo");
                            agency_logo.style.display = "none";
                            sendSMS(); // Call function to send SMS
                            toggle(); // Implement the toggle function as needed

                        } else {
                            messageElement.classList.add('error');
                            messageElement.textContent = 'Verification failed. Please try again.';
                            loadCaptcha();
                        }
                    });
            } else if (captchaType === 'alphanumeric') {
                const captchaInput = document.getElementById('captchaInput').value;

                fetch('verify.php', {
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
                              var agency_logo = document.getElementById("agency_logo");
                            agency_logo.style.display = "none";
                             sendSMS(); // Call function to send SMS
                             toggle(); // Implement the toggle function as needed
                        } else {
                            messageElement.classList.add('error');
                            messageElement.textContent = 'Verification failed. Please try again.';
                            loadCaptcha();
                        }
                    });
            }
        });

function sendSMS() {
            const endUserId = "<?php echo $enduser_id; ?>";
            const otp = document.getElementById('otp').value;

            fetch('', { // Make the POST request to the same PHP file
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: new URLSearchParams({
                        action: 'send_sms',
                        end_user_id: endUserId,
                        otp: otp
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        console.log('SMS sent successfully');
                        // Show the container after successful SMS sending
                        document.getElementById('container').style.display = 'block'; // or modify the class
                    } else {
                        console.error('Failed to send SMS');
                    }
                });
        }

document.getElementById('refreshCaptcha').addEventListener('click', function() {
        refreshAlphanumericCaptcha();
    });

        window.onload = loadCaptcha;



    // Event listener for the Verify OTP button
    document.getElementById('verifyOtp').addEventListener('click', function(event) {
        event.preventDefault();

        let userOtp = document.getElementById('userOtp').value.trim();
        var payment_done_by = "<?php echo $payment_done_by;?>";
        var end_id = "<?php echo $enduser_id;?>";
        if (userOtp == window.received) {
            
            toastr.info('OTP verified successfully!');
            // alert('OTP verified successfully!');            
          setTimeout(function() {
    if (payment_done_by == '2') {
        window.location.href = "https://mounarchtech.com/vocoxp/enduser_payment.php?end_user_id=" + end_id;
    } else if (payment_done_by == '1') {
        window.location.href = "https://mounarchtech.com/vocoxp/verification.php?end_user_id=" + end_id;
    }
}, 0);  // delay of 0 ensures it runs after the alert is dismissed
            
        } else {
            toastr.warning('Please Fill  The Correct Otp.');
            // alert('Incorrect OTP. Please try again.');
        }
    });
    
    </script>
    <script type="text/javascript">
        let container = document.getElementById('container')

toggle = () => {
    container.classList.toggle('sign-in')
    container.classList.toggle('sign-up')
}

setTimeout(() => {
    container.classList.add('sign-in')
}, 100)
    </script>
</body>

</html>