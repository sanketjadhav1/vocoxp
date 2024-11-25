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
    
   $fetch_enduser = "SELECT `agency_id`, `bulk_id`, `mobile` FROM `bulk_end_user_transaction_all` WHERE `end_user_id` = '$enduser_id'";
 $agency_result = $mysqli->query($fetch_enduser);
$agency_array = mysqli_fetch_assoc($agency_result);
$agency_id=$agency_array['agency_id'];
$bulk_id=$agency_array['bulk_id'];
$mobile=$agency_array['mobile'];

 $fetch_amount="SELECT `client_addon_amt` FROM `bulk_weblink_request_all` WHERE `agency_id`='$agency_id' AND `bulk_id`='BUL-00126'";
$res_amount=mysqli_query($mysqli, $fetch_amount);
$arr_amount=mysqli_fetch_assoc($res_amount);
$client_addon_amt=$arr_amount['client_addon_amt'];

// Split the string by commas to get each component
$components = explode(',', $client_addon_amt);

$total_amount = 0;

foreach ($components as $component) {
    // Split by '=' to separate the key and the amount-part
    $parts = explode('=', $component);

    // Split the amount-part by '-' to get the amount
    $amount_part = explode('-', $parts[1]);

    // Add the amount to the total
    $total_amount += (float)$amount_part[0];
}

$agency_details = "SELECT `company_name`,`agency_logo` FROM `agency_header_all` WHERE `agency_id` = '$agency_id'";
 $agency_result = $mysqli->query($agency_details);
$agency_array = mysqli_fetch_assoc($agency_result);
$company_name=$agency_array['company_name'];
$agency_logo=$agency_array['agency_logo'];
    ?>

    <div id="container" class="container">
        <div class="row">
            <div class="col align-items-center flex-col sign-up">
               
            </div>
          
            <div class="col align-items-center flex-col requested_varification">
                <div class="form-wrapper align-items-center">
        
                    <div class="form requested_varification" id="verification">
                        <br>
                        <h1>Verification Payment</h1><br>
                        <h4>Phone Number</h4>
                        <div class="input-group">
                            <i class='bx bxs-user'></i>
                            <input type="text" id="userOtp" value="<?php echo $mobile; ?>">
                        </div>
                      <h4>Rs. <?php echo $total_amount; ?> /-</h4><br>
                      <input type="submit" value="Pay Now"><br>
                    </div>
                </div>
            </div>
        </div>
        <div class="row content-row">z
            <div class="col align-items-center flex-col">
                <div class="text requested_varification" id="divhtml">
                    <div id="agency_logo">
                <img src="<?php echo $agency_logo; ?>"  alt="Company Logo" class="logo" width="45%" height ="45%">
  </div>          
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
           
            window.location.href = "web_aadhar_verification.php?end_user_id="+encodeURIComponent(end_id);
        });
    </script>
    <script type="text/javascript">
        let container = document.getElementById('container');
        setTimeout(() => {
            container.classList.add('requested_varification');
        }, 100)

        
    </script>
</body>

</html>