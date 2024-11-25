    <?php
    include '../vendor/autoload.php';
include '../connection.php';
//Application Vocoxp databese connection//
$connection = connection::getInstance();
$mysqli = $connection->getConnection();
//Central Database Connection//
$connection1 = database::getInstance();
$mysqli1 = $connection1->getConnection();

error_reporting(0);
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
            border: 0px solid #ced4da;
            /* Bootstrap form-control border color */
/*            box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);*/
            /* Bootstrap form-control box shadow */
/*            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;*/
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

        #family-section {
            width: 60vw;
            margin: auto;
        }
         #payment-section {
            width: 60vw;
            margin: auto;
        }
    </style>
</head>
<div class="container mt-5 centered">
    <?php
// Your Razorpay API credentials
     $enduser_id = $_GET['end_user_id'];
 $status=$_GET["razorpay_payment_link_status"];
 $payment_id=$_GET["razorpay_payment_id"];
 $transaction_id = 'txn_' . time() . random_int(100000, 999999);
 $paid_amount = $_GET['paid_amount']; 
 $obj_number = $_GET['obj_no'];
 
 $weblink_id = $_GET['bulk_id'];
 $gst_number = $_GET['gst_number'];
 $agency_id = $_GET['agency_id'];
 $user_ids = $_GET['user_ids'];
 
if ($status == 'paid') 
{
    
       $pay_check = "SELECT * FROM `end_user_payment_transaction_all` WHERE `end_user_id` = '$user_ids' AND `gateway_transaction_id` = '$payment_id'";
        $result_paychk = $mysqli->query($pay_check);
       
         
                        
                        
            // Initialize totals for client and MI amounts and taxes
            $total_clientamount = 0;
            $total_clienttax = 0;
            $total_miamount = 0;
            $total_mitax = 0;

            // Total variables
            $total_amount = 0;
            $total_tax = 0;

          $values_array = explode(",", $user_ids);
        foreach ($values_array as $value) {

            $enduser_id_value = trim($value);
            // Object numbers
            $obj_numbers = explode(",", $obj_number); // Add more objects as needed
           
                $end_user_main1 = "SELECT * FROM `bulk_end_user_transaction_all` WHERE `end_user_id`='".$enduser_id_value."'";
                $end_user_main1_result = $mysqli->query($end_user_main1);
                $end_user_main_array1 = mysqli_fetch_assoc($end_user_main1_result);
                $obj_no="obj_".$end_user_main_array1["obj_no"];
                // Dynamic field names based on the object
                $verifications = $obj_no . "_verifications";
                $mi_amt = $obj_no . "_mi_amt";
                $addon_amount = $obj_no . "_addon_amount";
                
                // Query to fetch amounts for the current object
                $amount_query = "SELECT $verifications, $mi_amt, $addon_amount FROM bulk_weblink_request_all WHERE bulk_id = '" . $weblink_id . "' AND status='3'";
                $amount_result = $mysqli->query($amount_query);
                $amount_row = mysqli_fetch_assoc($amount_result);
                
                $verificationss = $amount_row[$verifications];
                $mi_amts = $amount_row[$mi_amt];
                $addon_amounts = $amount_row[$addon_amount];
                
                // Calculate Addon amounts
                $components = explode(',', $addon_amounts);
                foreach ($components as $component) {
                    $parts = explode('=', $component); // Split by '='
                    $amount_part = explode('-', $parts[1]); // Split by '-' for base amount
                    $amount_part_tax = explode('+', $parts[1]); // Split by '+' for tax
                    // Add to total base amount and total tax
                    $total_amount += (float)$amount_part[1]; // Add base amount
                    $total_tax += (float)$amount_part_tax[1]; // Add tax
                }

                // Calculate MI amounts
                $components1 = explode(',', $mi_amts);
                foreach ($components1 as $component) {
                    $parts = explode('=', $component); // Split by '='
                    $amount_part = explode('+', $parts[1]); // Split by '+' for client tax

                    // Add to client total and client tax
                    $total_clientamount += (float)$amount_part[0]; // Base amount
                    $total_clienttax += (float)$amount_part[1]; // Tax
                }
             
        }

            // Calculate total tax and total amount
            $tax = $total_tax + $total_clienttax;
            $total_amount_final = $total_amount + $total_clientamount;
            $total_miamount=$total_clientamount;
            $sgst_cgst_mitax=($total_clienttax/2);
            $total_clientamount=$total_amount;
            $sgst_cgst_clienttax=($total_tax/2);
            $sgst_cgst_paid=($tax/2);
            // Output the results
            // echo "Total Amount (without tax): " . number_format($total_amount_final, 2) . "<br>";
            // echo "Total Tax: " . number_format($tax, 2) . "<br>";
            // echo "Total Amount (with tax): " . number_format($total_amount_final + $tax, 2) . "<br>";
            
                                       
            if (mysqli_num_rows($result_paychk) >0) 
        {?>
             <div id="payment-section" class="card" style="display:block;">
                <div class="card-header bg-success text-white">Payment Details</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-2"> </div>
                           
                            <div class="col-sm-3">
                                <label>Status : </label>
                            </div>
                            <div class="col-sm-3">
                                <span class="badge bg-warning">Already paid</span>
                            </div>
                            
                            
                        </div><hr>
                         <div class="row">
                            <div class="col-sm-4"> </div> 
                            <div class="col-sm-4">
                                  <a href="https://mounarchtech.com/vocoxp/upload_link/web_verification_form.php?enduser_id=<?Php echo$enduser_id. '&t=' . time();?>" class="btn btn-success">Go To Dashboard</a>
                            </div>
                            
                            
                        </div> 
                    </div>
            </div>
           
        <?PHP 
        }

        else 
        {
            
             $select_array = mysqli_fetch_assoc($result_paychk);
              $stmt = "INSERT INTO `end_user_payment_transaction_all`( `end_user_id`, `paid_transaction_id`, `paid_by`, `gateway_transaction_id`, `status`, `gst_number`, `paid_amount`, `cgst_amount`, `sgst_amount`, `mi_amount`, `mi_cgst_amount`, `mi_sgst_amount`, `agency_amount`, `agency_cgst_amount`, `agency_sgst_amount`, `inserted_on`, `bulk_id`) VALUES ('$user_ids','$transaction_id','$enduser_id','$payment_id','$status','$gst_number','$paid_amount','$sgst_cgst_paid','$sgst_cgst_paid','$total_miamount','$sgst_cgst_mitax','$sgst_cgst_mitax','$total_clientamount','$sgst_cgst_clienttax','$sgst_cgst_clienttax','$system_date_time','$weblink_id')";

                if (mysqli_query($mysqli,$stmt))
                 {  
                      $end_user_paidby = "SELECT * FROM `bulk_end_user_transaction_all` WHERE `end_user_id`='".$enduser_id."' AND bulk_id='".$weblink_id."'";
                    $end_user_paidby_result = $mysqli->query($end_user_paidby);
                     $end_user_paidby_array1 = mysqli_fetch_assoc($end_user_paidby_result);
                     $html = '<html>
                                <head>
                                  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                                  
                                  <style type="text/css">
                                    body {
                                      font-family: "Times New Roman";
                                      font-size: 12pt;
                                    }
                                    p {
                                      margin: 0;
                                    }
                                    th{
                                      text-align: center;
                                      vertical-align: middle;
                                      padding: 5px;
                                      font-size: 13px;
                                    }
                                    td{
                                      text-align: center;
                                      vertical-align: middle;
                                      font-size: 12px;
                                    }
                                    th, td{
                                      white-space: nowrap;
                                    }
                                   
                                  </style>
                                </head>
                                <body>
                                    <div>
                                    <p style="text-align: center; font-size: 20px;"><b>PAYMENT INVOICE ('.$system_date.')</b></p><br>
                                    <p style="font-family: Calibri; font-size: 13pt; color: #0d68a5; text-align: center;">MICRO INTEGRATED SEMI CONDUCTOR SYSTEMS PVT. LTD </p>
                                    <p style="margin-top: 2px; margin-left: 5px; text-align: center; line-height: 2; font-family: Calibri; font-size: 11pt;">154, G-2, Gulmohar Colony, Bhopal, Madhya Pradesh, 462039.</p>    
                                    <p style="margin-top: 2px; margin-left: 5px; text-align: center; line-height: 1; font-family: Calibri; font-size: 11pt;"> www.microintegrated.in</p>
                                    <p style="margin-top: 2px; margin-left: 5px; text-align: center; line-height: 2; font-family: Calibri; font-size: 11pt;"><b>GST Number:-23AAECM0658D2ZV</b></p>

                                    <br>
                                    <div style="padding: 4px; background-color: #0d68a5;"></div>
                                    <table style="width:100%; border-collapse: collapse;  background-color:#e5e5e5;">
                                      <thead>
                                        <tr>
                                          <th style="text-align: left;">Payment ID:  '.$payment_id.'</th> 
                                          
                                          <th style="text-align: right;">
                                          Invoice Date: '.$system_date.'</th>
                                        </tr>
                                      </thead>
                                    </table>
                                    <br>
                                    <section>
                                      <div>
                                        <p style="font-size: 13px; margin-left:10px; font-weight:700;"><b>BILL TO</b></p>
                                        <p style="font-size: 12px; margin-left:10px; margin-top:5px;"><b>Paid By :-</b>'.$end_user_paidby_array1["name"].'</p> ';
                                        if(!empty($end_user_paidby_array1["mobile"]))
                                        {
                                           $html .=' <p style="font-size: 12px; margin-left:10px; margin-top:5px;"><b>Mobile No :-</b> '.$end_user_paidby_array1["mobile"].'</p> ';
                                        }
                                       $html .=' <p style="font-size: 12px; margin-left:10px; margin-top:5px;"><b>Transaction ID :-</b>'.$transaction_id.' </p> 
                                        <p style="font-size: 12px; margin-left:10px; margin-top:5px;"><b>Status :-</b>'.strtoupper($status).' </p> 
                                   </div>
                                    </section>
                                    <br>
                                <section>
                                      <div>
                                        <table style="width:100%; border-collapse: collapse;">
                                          <thead>
                                            <tr>
                                              <th style=" width:25%; border-top: 1px solid #0d68a5; border-bottom: 1px solid #0d68a5; text-align: left;">Paid For</th>
                                              <th style=" width:40%; border-top: 1px solid #0d68a5; border-bottom: 1px solid #0d68a5;">Verification Name(₹)</th>  
                                              <th style="width:15%; border-top: 1px solid #0d68a5; border-bottom: 1px solid #0d68a5;">AMOUNT(₹)</th>
                                              <th style="width:15%; border-top: 1px solid #0d68a5; border-bottom: 1px solid #0d68a5;">CGST(%) - (₹)</th>
                                              <th style="width:15%; border-top: 1px solid #0d68a5; border-bottom: 1px solid #0d68a5;">SGST(%) - (₹)</th>
                                              <th style="width:15%; border-top: 1px solid #0d68a5; border-bottom: 1px solid #0d68a5; text-align: right;">Sub Total</th>              
                                            </tr>
                                          </thead>
                                          <tbody> ';
                                            $total_amt=0;
                                            $tax1=0;
                                          $values_array = explode(",", $user_ids);
                                          foreach ($values_array as $value) {
                                            $enduser_id_value = trim($value);
                                                $end_user_main1 = "SELECT * FROM `bulk_end_user_transaction_all` WHERE `end_user_id`='".$enduser_id_value."'";
                                                $end_user_main1_result = $mysqli->query($end_user_main1);
                                            if ($end_user_main1_result->num_rows > 0) {

                                                $end_user_main_array1 = mysqli_fetch_assoc($end_user_main1_result);
                                                $bulk_id = $end_user_main_array1['bulk_id'];

                                                 $obj_no = "obj_" .$end_user_main_array1["obj_no"];
                                                  if (!empty($obj_no))
                                                   {
                                                         $obj_name = $end_user_main_array1["obj_name"];
                                                        

                                                        $verifications = $obj_no . "_verifications";
                                                        $mi_amt = $obj_no . "_mi_amt";
                                                        $addon_amount = $obj_no . "_addon_amount";
                                                        $verificationss ="";
                                                        $mi_amts = "";
                                                        $addon_amounts = "";

                                                        $amount_query = "SELECT  $obj_no,$verifications,$mi_amt,$addon_amount FROM bulk_weblink_request_all WHERE bulk_id = '" . $bulk_id . "' AND status='3'";
                                                        $amount_result = $mysqli->query($amount_query);
                                                        $amount_row = mysqli_fetch_assoc($amount_result);

                                                        if($amount_row[$obj_no]==$obj_name)
                                                        {
                                                           $obj_role= $amount_row[$obj_no];
                                                           $verificationss=  $amount_row[$verifications];
                                                           $mi_amts= $amount_row[$mi_amt];
                                                           $addon_amounts= $amount_row[$addon_amount];
                                                            
                                                        }

                                                       $components = explode(',', $addon_amounts);

                                                $total_amount = 0;
                                                $total_amount_tax = 0;
                                                $total_amount1 = 0;
                                                $total_amount2 = 0;
                                                foreach ($components as $component) {

                                                    // print_r($component);
                                                    // Split by '=' to separate the key and the amount-part
                                                    $parts = explode('=', $component); 
                                                    // print_r($parts);
                                                    // Split the amount-part by '-' to get the amount
                                                    $amount_part = explode('-', $parts[1]); 
                                                    $amount_part_tax = explode('+', $parts[1]); 
                                                    // print_r($amount_part_tax[1]);

                                                    // Add the amount to the total
                                                    $total_amount += (float)$amount_part[1]; 
                                                    $total_amount_tax += (float)$amount_part_tax[1]; 
                                                }
                                                    // echo"<br>tAmt".$total_amount_tax;
                                                $components1 = explode(',', $mi_amts);

                                                $total_amount_client = 0;
                                                $total_amount_client_tax = 0;
                                                $total_amount1 = 0;
                                                $total_amount2 = 0;

                                                foreach ($components1 as $component) {

                                                    // print_r($component);
                                                    // Split by '=' to separate the key and the amount-part
                                                    $parts = explode('=', $component); 

                                                    // Split the amount-part by '-' to get the amount
                                                    $amount_part = explode('+', $parts[1]); 
                                                    // print_r($amount_part[1]);

                                                    // Add the amount to the total
                                                    $total_amount_client += (float)$amount_part[0]; 
                                                    $total_amount_client_tax += (float)$amount_part[1]; 
                                                }
                                                // echo"<br>mi".$total_amount_client_tax;
                                                $tax=$total_amount_tax+$total_amount_client_tax;
                                                 // $total_amount= $total_amount_client+$total_amount+$total_amount_tax+$total_amount_client_tax;
                                                 $total_amount= $total_amount_client+$total_amount;
                                                
                                                
                                                       $sgst_cgst_mitax1=($total_amount_tax+$total_amount_client_tax);
                                                       $sgst_cgst_mitax2=($sgst_cgst_mitax1/2);
                                                       $grand_total=$total_amount+$sgst_cgst_mitax1;

                                                          $verifications_name_array = []; // Initialize an empty array to store the names
                                                    $verificationss1 = explode(',', $verificationss); // Split the input string

                                                    foreach ($verificationss1 as $verify_id) {
                                                        $verification_query = "SELECT * FROM `verification_header_all` WHERE `verification_id`='".$verify_id."'";
                                                        $verification_query_result = $mysqli1->query($verification_query);

                                                        if ($verification_query_row = mysqli_fetch_assoc($verification_query_result)) {
                                                            // Add each verification name to the array
                                                            $verifications_name_array[] = $verification_query_row["name"];
                                                        }
                                                    }

                                                    // Use implode to convert the array of names into a comma-separated string
                                                    $verify_ids_str = implode(",", $verifications_name_array);
                                                    // print_r($verify_ids_str);
                                                          $html .='<tr>
                                                              <td style="border-bottom: 0.5px solid #dee2e6; text-align: left; line-height: 3;">'.$end_user_main_array1["name"].'</td>
                                                              <td style="border-bottom: 0.5px solid #dee2e6;">'.$verify_ids_str.'</td>  
                                                              <td style="border-bottom: 0.5px solid #dee2e6;">'.$total_amount.'/-</td>
                                                              <td style="border-bottom: 0.5px solid #dee2e6;">9% - ('.$sgst_cgst_mitax2.'₹)</td>
                                                              <td style="border-bottom: 0.5px solid #dee2e6;">9% - ('.$sgst_cgst_mitax2.'₹)</td>
                                                              <td style="border-bottom: 0.5px solid #dee2e6; text-align: right;">'.$grand_total.'</td>
                                                              
                                                            </tr> ';
                                                             $total_amt+=$total_amount;
                                                              $tax1 +=$tax;
                                                    }
                                                }
                                            }
                                                       

                                             
                                            $total_amount_final = $total_amt+$tax1;
                                            $total_amount_final1 = $total_amt; 
                                             
                                            
                                            $sgst_cgst_paid=($tax1/2);
                                
                                $html .='<tr>
                                            <td style="border-top: 1px solid #0d68a5; text-align: right; line-height: 2;"></td>
                                            <td style="border-top: 1px solid #0d68a5; text-align: right; line-height: 2;"></td>
                                            <td style="border-top: 1px solid #0d68a5; text-align: right; line-height: 2;"></td>
                                            <td style="border-top: 1px solid #0d68a5; text-align: right; line-height: 2;"></td> 
                                            <td style="border-top: 1px solid #0d68a5; text-align: right; line-height: 2;">TOTAL AMOUNT (₹)</td>
                                            <td style="border-top: 1px solid #0d68a5; text-align: right; line-height: 2;">'.$total_amount_final1.'/-</td>
                                          </tr>
                                          <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td> 
                                            <td style="text-align: right; line-height: 2;">CGST (₹)</td>
                                            <td style="text-align: right; line-height: 2;">'.$sgst_cgst_paid.'/-</td>
                                          </tr>
                                          <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td> 
                                            <td style="text-align: right; line-height: 2;">SGST (₹)</td>
                                            <td style="text-align: right; line-height: 2;">'.$sgst_cgst_paid.'/-</td>
                                          </tr>
                                          <tr>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th> 
                                            <th style="border-top: 0.5px solid #dee2e6; text-align: right;">GRAND TOTAL</th>
                                            <th style="border-top: 0.5px solid #dee2e6; text-align: right;">'.$total_amount_final.'/-</th>
                                          </tr>          
                                        </tbody>
                                      </table>
                                    </div>
                                    <br>
                                    <div style="text-align: right;">
                                       <p style="font-size: 11px;"><b>Payment Via:- Online </b></p>
                                    </div>
                                    <br>
                                    <br>
                                    <br>
                                    <br>
                                    <br>
                                    <div>
                                      <P><b>TERMS AND CONDITIONS:</b></P>
                                      <p style="font-size: 11px; float:right;margin-left:10px; margin-top:5px;">1. Any dispute should be resolved within 30 days.</p>
                                      <p style="font-size: 11px; float:right;margin-left:10px; margin-top:5px;">2. All payments should be made in Indian Rupees (INR).</p>
                                      <p style="font-size: 11px; float:right;margin-left:10px; margin-top:5px;">3. Any legal matters will be covered in Bhopal jurisdiction only.</p>
                                      
                                    </div>
                                    <br>
                                    <br>
                                    <br>   
                                   
                                  </section>
                                </div>


                                </body>
                                </html>';
                                 
                                 // Initialize mPDF
                                $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4']);


                                // Write HTML content to mPDF
                                $mpdf->WriteHTML($html);

                                // Output PDF to a variable
                                $output_pdf = $mpdf->Output('', 'S');

                                // Save PDF to a file
                                $path = savePDF1($output_pdf, $enduser_id, $agency_id,$weblink_id);


                                     $update_wallet = "UPDATE `end_user_payment_transaction_all` SET `invoice_url`='$path' WHERE   `bulk_id`='$weblink_id' AND `end_user_id`='$user_ids' AND `paid_by`='$enduser_id'";
                                    $res_update = mysqli_query($mysqli, $update_wallet);

                                // Define response array
                                $response = array();

                                // Check if the path is valid
                                if ($path !== false) {
                                    // PDF generated successfully
                                    $response['error_code'] = 100;
                                    $response['message'] = 'PDF generated successfully.';

                                    $response['pdf_url'] = $path;
                                    $data = [
                                        "path" => $path,
                                        "for" => 'invoice',
                                        "agency_id" => $agency_id,
                                        "bulk_id" => $bulk_id,
                                        "enduser_id" => $enduser_id

                                    ];

                                    $url = get_base_url() . '/payment_new_pdf.php';

                                    // Initialize cURL session
                                    $ch = curl_init($url);

                                    // Configure cURL options
                                    curl_setopt($ch, CURLOPT_POST, true);
                                    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
                                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                                    // Execute the POST request
                                    $response = curl_exec($ch);

                                    // Check for errors
                                    if (curl_errno($ch)) {
                                        // Print the error
                                        echo 'cURL error: ' . curl_error($ch);
                                    } else {
                                        // Print the response
                                         $response;
                                    }

                                    // Close the cURL session
                                    curl_close($ch);
                                    // $res[]=["pdf_url"=>$path, 'current_wallet_bal'=>number_format($deducted_amt, 2, '.', '')];

                                    // $data=["error_code"=>100, "message"=>"PDF generated successfully.", "data"=>$res];
                                    // echo json_encode($data);
                                } else 
                                {
                                    // PDF generation failed
                                    $response['success'] = false;
                                    $response['message'] = 'Failed to generate PDF.';
                                }
                                ?>
                                <div id="payment-section" class="card" style="display:block;">
                                    <div class="card-header bg-success text-white">Payment Details</div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-2"> </div>
                                               
                                                <div class="col-sm-3">
                                                    <label>Date :  </label>
                                                </div>
                                                <div class="col-sm-3">
                                                    <label><?PHP echo date("d-m-Y H:i:s",strtotime($system_date_time)); ?></label>
                                                </div>
                                                
                                                
                                            </div><hr>
                                              <div class="row">
                                                <div class="col-sm-2"> </div>
                                               
                                                <div class="col-sm-3">
                                                    <label>Payemnt ID : </label>
                                                </div>
                                                <div class="col-sm-3">
                                                    <label><?PHP echo$payment_id; ?></label>
                                                </div>
                                                
                                                
                                            </div><hr>
                                           <div class="row">
                                                <div class="col-sm-2"> </div>
                                               
                                                <div class="col-sm-3">
                                                    <label l for="exampleInput" class="form-label">Transaction  ID : </label>
                                                </div>
                                                <div class="col-sm-3">
                                                    <label><?PHP echo$transaction_id; ?></label>
                                                </div>
                                                
                                                
                                            </div><hr>
                                             <div class="row">
                                                <div class="col-sm-2"> </div>
                                               
                                                <div class="col-sm-3">
                                                    <label>Status : </label>
                                                </div>
                                                <div class="col-sm-3">
                                                    <span class="badge bg-success"><?PHP echo$status; ?></span>
                                                </div>
                                                
                                                
                                            </div><hr>
                                             <div class="row">
                                                <div class="col-sm-4"> </div> 
                                                <div class="col-sm-4">
                                                     <!-- <a href="https://mounarchtech.com/vocoxp/upload_link/web_verification_form.php?enduser_id=<?Php echo$enduser_id;?>" class="btn btn-success">Go To Dashboard</a> -->
                                                      <a href="https://mounarchtech.com/vocoxp/upload_link/web_verification_form.php?enduser_id=<?Php echo$enduser_id. '&t=' . time();?>" class="btn btn-success">Now verify your details</a>
                                                </div>
                                                
                                                
                                            </div> 
                                      
                                     
                                    </div>
                            </div>

             <?php
                 }
                 else
                 {
                   echo json_encode(['status' => 'error', 'message' => 'somthing error.']);
                 }

        }


}
else 
{
    echo json_encode(['status' => 'error', 'message' => 'Payment not successful.']);
}



 

    function savePDF1($output_pdf, $direct_id, $agency_id,$bulk_id)
{
    // FTP server credentials
    $ftp_server = '199.79.62.21';
    $ftp_username = 'centralwp@mounarchtech.com';
    $ftp_password = 'k=Y#oBK{h}OU';

    // Remote directory path
    $remote_base_dir = "/verification_data/voco_xp/";

    // Nested directory structure to be created
    $new_directory_path = "$agency_id/Weblink/$bulk_id/$direct_id/invoice/";

    // Initialize cURL session for FTP
    $curl = curl_init();

    // Set cURL options for FTP
    curl_setopt_array(
        $curl,
        array(
            CURLOPT_URL => "ftp://$ftp_server/",
            CURLOPT_USERPWD => "$ftp_username:$ftp_password",
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_FTP_CREATE_MISSING_DIRS => true, // Create missing directories
        )
    );

    // Execute the cURL session for FTP
    ob_start(); // Start output buffering
    $response_ftp = curl_exec($curl);
    ob_end_clean(); // Discard output buffer

    // Check for errors in FTP request
    if ($response_ftp === false) {
        $error_message_ftp = curl_error($curl);
        die("Failed to connect to FTP server: $error_message_ftp");
    }

    // Set the target directory
    $remote_dir_path = $remote_base_dir . $new_directory_path;

    // Create directory recursively
    curl_setopt($curl, CURLOPT_URL, "ftp://$ftp_server/$remote_dir_path");
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'MKD');
    ob_start(); // Start output buffering
    $response_mkdir = curl_exec($curl);
    ob_end_clean(); // Discard output buffer

    // Check for errors in directory creation
    if ($response_mkdir == false) {
        $error_message_mkdir = curl_error($curl);
    }

    // Generate a unique file name for the merged PDF
    $file_name = $direct_id.'-'.date('YmdHis') . '.pdf';

    // Construct the full file path on the remote server
    $file_path = $remote_dir_path . $file_name;

    // Save the PDF to a temporary file
    $temp_file = tempnam(sys_get_temp_dir(), 'pdf');
    file_put_contents($temp_file, $output_pdf);

    // Initialize cURL session for file upload
    $curl_upload = curl_init();

    // Set cURL options for file upload
    curl_setopt_array(
        $curl_upload,
        array(
            CURLOPT_URL => "ftp://$ftp_server/$file_path",
            CURLOPT_USERPWD => "$ftp_username:$ftp_password",
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_UPLOAD => true,
            CURLOPT_INFILE => fopen($temp_file, 'r'),
            CURLOPT_INFILESIZE => filesize($temp_file),
        )
    );

    // Execute cURL session for file upload
    ob_start(); // Start output buffering
    $response_upload = curl_exec($curl_upload);
    ob_end_clean(); // Discard output buffer

    // Check for errors in file upload
    if ($response_upload === false) {
        $error_message_upload = curl_error($curl_upload);
        die("Failed to save merged PDF file: $error_message_upload");
    }

    // Close cURL sessions
    curl_close($curl);
    curl_close($curl_upload);

    // Update the database with the path to the merged PDF file
    $base_url = "https://mounarchtech.com/central_wp/verification_data/voco_xp/$agency_id/Weblink/$bulk_id/$direct_id/invoice";
    $path = $base_url . '/' . $file_name;
    return $path;
}
    ?>

 