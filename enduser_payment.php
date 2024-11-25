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
    <title>Verify Payment</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/brands.min.css">
    <link rel="stylesheet" href="web_style.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/js/all.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/js/brands.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    
</head>

<body>
    <?php

    $enduser_id = $_GET['end_user_id'];
     $fetch_enduser = "SELECT `agency_id`, `name`, `email_id`, `bulk_id`, `mobile` FROM `bulk_end_user_transaction_all` WHERE `end_user_id` = '$enduser_id'";
 $agency_result = $mysqli->query($fetch_enduser);
$agency_array = mysqli_fetch_assoc($agency_result);
$agency_id=$agency_array['agency_id'];
$bulk_id=$agency_array['bulk_id'];
$mobile=$agency_array['mobile'];
$name=$agency_array['name'];
$email=$agency_array['email_id'];

 $fetch_amount="SELECT `generated_for`,`client_addon_amt`,`verifications`,`amount`,`child_verifications`,`child_amount`,`child_client_addon_amt` FROM `bulk_weblink_request_all` WHERE `agency_id`='$agency_id' AND `bulk_id`='$bulk_id'";
$res_amount=mysqli_query($mysqli, $fetch_amount);
$arr_amount=mysqli_fetch_assoc($res_amount);
$generated_for=$arr_amount['generated_for'];
  /* generated_for
        1=School-Students 
        2=School-Parents 
        3=School-Parents & Students 
        4=Institute-Students Above 16yrs 
        5=Employee\'s 
        6=On-boarding 
        7=Hostel-Services(Students) 
        8=Hostel-Services(Students & Parents) 
        9=Campus Counseling 
        10=Custom  */
 $client_addon_amt=$arr_amount['client_addon_amt'];

$verifications=$arr_amount['verifications'];
$amount=$arr_amount['amount'];



// Split the string by commas to get each component
$components = explode(',', $client_addon_amt);
$components_amount = explode(',', $amount);
// print_r($components);
// echo "<br>";
// print_r($components_amount);
$total_clientamount = 0;
$total_clienttax = 0;
$total_miamount = 0;
$total_mitax = 0;

foreach ($components as $component) {
    // Split by '=' to separate the key and the amount-part
    $parts = explode('-', $component);
    // Split the amount-part by '-' to get the amount
   $clamount_part = explode('+', $parts[0]);
// print_r($clamount_part);

    // Add the amount to the total
    $total_clientamount += (float)$parts[1];
    $total_clienttax += (float)$clamount_part[1];
}

foreach ($components_amount as $component_amount) {
    // Split by '=' to separate the key and the amount-part
    $miparts = explode('=', $component_amount);

    // Split the amount-part by '-' to get the amount
   $amount_part = explode('+', $miparts[1]);
// print_r($amount_part);
    // Add the amount to the total
    $total_miamount += (float)$amount_part[0];
    $total_mitax += (float)$amount_part[1];
}
 $total_amount = ($total_clientamount*1)+($total_clienttax*1)+($total_miamount*1)+($total_mitax);
  $miamount = ($total_clientamount*1)+($total_miamount*1);
  $mitax = ($total_clienttax*1)+($total_mitax*1);
// die();
$agency_details = "SELECT `company_name`,`agency_logo`,`agency_gst_no` FROM `agency_header_all` WHERE `agency_id` = '$agency_id'";
 $agency_result = $mysqli->query($agency_details);
$agency_array = mysqli_fetch_assoc($agency_result);
$company_name=$agency_array['company_name'];
$agency_logo=$agency_array['agency_logo'];
$gst_number=$agency_array['agency_gst_no'];

 $select_end_details = "SELECT `id`, `agency_id`, `bulk_id`, `upload_id`, `type`, `name`, `mobile`, `email_id`, `sms_sent`, `email_sent`, `status`, `verification_report`, `verification_details`, `scheduled_verifications`, `weblink_opened_on`, `reminder_email`, `reminder_sms`, `payment_invoice`, `payment_done_by` FROM `bulk_end_user_transaction_all` WHERE `end_user_id` = '$enduser_id'";
    $result = $mysqli->query($select_end_details);
    $select_array = mysqli_fetch_assoc($result);
    $mobile = $select_array['mobile'];
    
    $scheduled_verifications = $select_array['scheduled_verifications'];
    // Use explode to split the string into an array
    $array = explode(",", $scheduled_verifications);
    // Define a mapping of numbers to descriptions
    $mapping = [
        1 => " <span> Aadhar</span> <br/>",
        2 => " Pan <br/>",
        3 => " Voter <br/>",
        4 => " DL <br/>",
        5 => "Indian Passport <br/>",
        6 => " International Passport <br/>",
        7 => " Crime check <br/>"
    ];
   
    ?>
     

    <div id="container" class="container">
        <div class="row">
            <div class="col align-items-center flex-col sign-up">

            </div>

            <div class="col align-items-center flex-col requested_varification">
                <div class="form-wrapper align-items-center">

                    <div class="form requested_varification" id="verification">
                        <br>
                        <h1>Payment for the verification of</h1><br>
                        <h4><?php echo $masked_number = 'XXXXXXXX' . substr($mobile, -4); ?></h4>
                        <?php

                                                    // Iterate through the array and print the corresponding description
                            foreach ($array as $value) {
                                if (isset($mapping[$value])) {
                                    
                                     echo $mapping[$value];
                                
                                }
                            }
                        ?>
                        <input id="agency_id" type="hidden" value="<?php echo $agency_id; ?>"> 
                        <input id="gst_number" type="hidden" value="<?php echo $gst_number; ?>"> 
                        <input id="bulk_id" type="hidden" value="<?php echo $bulk_id; ?>"> 
                        <input id="mobile" type="hidden" value="<?php echo $mobile; ?>"> 
                        <input id="name" type="hidden" value="<?php echo $name; ?>"> 
                        <input id="email" type="hidden" value="<?php echo $email; ?>"> 
                        <input id="end_user_id" type="hidden" value="<?php echo $enduser_id; ?>"> 
                        <input id="miamount" type="text" value="<?php echo $miamount; ?>"> 
                        <input id="mitax" type="text" value="<?php echo $mitax; ?>"> 
                        <h4 id="price">Rs. <?php echo $total_amount; ?>/-</h4><br>
                        <h4 id="price1">1</h4><br>
                        <button class="pointer" onclick="sendPaymentLink();">
                            Pay Now
                        </button>
                        <!-- <input type="submit" class="pointer" value="Pay Now" ><br> -->
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
                       Payment Verification
                    </h2>
                </div>
            </div>
        </div>
    </div>

    <!-- payment link pop up  -->
   <!--  <div id="decideProject" class="modal fade">
        <div class="modal-content modal-content-add">
            <span class="close" onclick="closeModal()">&times;</span>
            <p class="m_heading text-center">Payment Link Send Successfully
            </p>
        </div>
    </div> -->
    <div id="decideProject" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                
                  <span class="close" onclick="closeModal()">&times;</span>
            <p class="m_heading text-center">Payment Link Send Successfully
            </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

   
    <script type="text/javascript">
    let container = document.getElementById('container');
    setTimeout(() => {
        container.classList.add('requested_varification');
    }, 100)

    function sendPaymentLink() {
        // alert('test');
        let element = document.getElementById('price1');
        let mobile = document.getElementById('mobile').value;
        let email = document.getElementById('email').value;
        let name = document.getElementById('name').value;
        let end_user_id = document.getElementById('end_user_id').value;
        let bulk_id = document.getElementById('bulk_id').value;
        let gst_number = document.getElementById('gst_number').value;
        let agency_id = document.getElementById('agency_id').value;
        let text = element.innerText;
        let amount = text.match(/\d+/)[0];
        
        var xhr = new XMLHttpRequest();
        xhr.open('POST', './send_link.php', true);
        // xhr.open('POST', './test_payment.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            let response = JSON.parse(xhr.responseText);
            if (xhr.status == 200) {
                console.log(response);
               window.location.href = response['short_url'];
                // $('#decideProject').css('display', 'block');
          var myModal = new bootstrap.Modal(document.getElementById("decideProject"), {});
            document.onreadystatechange = function () {
              myModal.show();
            }; 
            } else {
                console.error('Error fetching data: ' + xhr.status);
            }
        };
        var params = 'amount=' + encodeURIComponent(amount) + '&mobile=' + encodeURIComponent(mobile) + '&email=' + encodeURIComponent(email) + '&name=' + encodeURIComponent(name) + '&end_user_id=' + encodeURIComponent(end_user_id) + '&bulk_id=' + encodeURIComponent(bulk_id) + '&gst_number=' + encodeURIComponent(gst_number) + '&agency_id=' + encodeURIComponent(agency_id);

        xhr.send(params);
    }

    function closeModal() {
        $('#decideProject').css('display', 'none');
    }
    </script>
</body>

</html>