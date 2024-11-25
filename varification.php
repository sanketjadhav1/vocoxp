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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/js/all.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/js/brands.min.js"></script>
    <style>
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

        .container.requested_varification .form.requested_varification,
        .container.requested_varification .social-list.requested_varification,
        .container.requested_varification .social-list.requested_varification>div
        {
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

        .text.requested_varification h2,
        .text.requested_varification p,
        .img.requested_varification img {
            transform: translateX(-250%);
        }

        .text.sign-up h2,
        .text.sign-up p,
        .img.sign-up img {
            transform: translateX(250%);
        }

        .container.requested_varification .text.requested_varification h2,
        .container.requested_varification .text.requested_varification p,
        .container.requested_varification .img.requested_varification img,
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

        .container.requested_varification::before {
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
            .container.requested_varification::before,
            .container.sign-up::before {
                height: 100vh;
                border-bottom-right-radius: 0;
                border-top-left-radius: 0;
                z-index: 0;
                transform: none;
                right: 0;
            }
            .container.requested_varification .col.requested_varification,
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
$select_end_details = "SELECT  `agency_id`, `name`, `mobile`, `verification_report`, `verification_details`, `weblink_opened_on`,`payment_done_by` FROM `bulk_end_user_transaction_all` WHERE `end_user_id` = '$enduser_id'";
$result = $mysqli->query($select_end_details);
$select_array = mysqli_fetch_assoc($result);

$mobile=$select_array['mobile'];
$agency_id=$select_array['agency_id'];
$weblink_opened_on=$select_array['weblink_opened_on'];
$payment_done_by=$select_array['payment_done_by'];

   
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
        <div class="row">
            <div class="col align-items-center flex-col sign-up">
               
            </div>
          
            <div class="col align-items-center flex-col requested_varification">
                <div class="form-wrapper align-items-center">
        
                    <div class="form requested_varification" id="verification">
                        <h1>verification(s) you are requested to do :</h1>
                        <?php

                        // Iterate through the array and print the corresponding description
                        foreach ($array as $value) {
                            if (isset($mapping[$value])) {

                                echo $mapping[$value];
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="row content-row">
            <div class="col align-items-center flex-col">
                <div class="text requested_varification" id="divhtml">
                    <h2>
                        Welcome to <?php echo $company_name; ?>
                    </h2>
                </div>   
            </div>
        </div>
    </div>
    <script>
  

        // Event listener for the Verify OTP button
   
        document.getElementById('aadhar_button').addEventListener('click', function(event) {
            event.preventDefault();
            var end_id = "<?php echo $enduser_id; ?>";
            console.log(end_id)
            // window.location.href = "https://mounarchtech.com/vocoxp/web_aadhar_verification.php?end_user_id=" + end_id;

            window.location.href = "web_aadhar_verification.php?end_user_id="+encodeURIComponent(end_id);
        });
    </script>
    <script type="text/javascript">
        let container = document.getElementById('container')
        setTimeout(() => {
            container.classList.add('requested_varification')
        }, 100)

        
    </script>
</body>

</html>