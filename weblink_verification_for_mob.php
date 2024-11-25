<?php
// Suppress deprecation notices
error_reporting(E_ALL & ~E_DEPRECATED);
ini_set('display_errors',0);

require_once __DIR__ . '/vendor/autoload.php'; 

include 'connection.php';

// ini_set('display_errors', 1);
$connection = connection::getInstance();
$mysqli = $connection->getConnection();
///////////////////////////////////////////////
$connection1 = database::getInstance();
$mysqli1 = $connection1->getConnection();

date_default_timezone_set('Asia/kolkata');
    $system_date = date("y-m-d");
    $system_date_time = date("Y-m-d H:i:s");
 
// var_dump($_POST);
// die();

$mobile_number=$_POST['mobile_number'] ?? '';
$agency_id=$_POST['agency_id'] ?? '';
$bulk_id=$_POST["bulk_id"];
$end_user_id=$_POST["end_user_id"];
$base_amount=$_POST['base_amount'] ?? '';
$sgst_amount=$_POST['sgst_amount'] ?? '';
$cgst_amount=$_POST['cgst_amount'] ?? '';
$verification_id=$_POST['verification_id'] ?? '';
$application_id=$_POST['application_id'] ?? '';

$fetch_wallet = "SELECT `current_wallet_bal` FROM `agency_header_all` WHERE `agency_id`='$agency_id'";
$res_wallet = mysqli_query($mysqli, $fetch_wallet);
$arr_wallet = mysqli_fetch_assoc($res_wallet);
    
$html = '<html>
                <head>
                   <meta charset="utf-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1">
                  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

                  <style>
                  #firstDiv{
                    padding:15px;
                    background: #889dd1;
                  
                  }
                 
                  #header{
                      margin-top:20px !important;
                      font-weight: bold;
                  font-size:20px;

                  }
                  #first{
                  font-size:23px;

                }
                  #second{
                  font-size:23px;
                }
                  #table{
                    margin-top:10px;
                  }
                  th,td{
                    line-height: 24px;
                  }
                  </style>
                </head>
                <body>

                <div id="firstDiv">
                    <table style="width:100%; border-collapse: collapse;">
                        <thead>  
                        </thead>
                        <tbody>
                            <tr>
                                <td width="70%">
                               <h3 id="header"> MOBILE VERIFICATION REPORT</h3>
                                </td>
                                <td width="30%" align="left">
                                <img src="vendor/microintegrated_logo.png" alt="Placeholder image" width="20%" />
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <table style="width:100%; border-collapse: collapse;" id="table">
                    <tr>
                      
                      
                    </tr>
                    <tr>
                      <td scope="col" colspan="2">
                      
                      <p style="font-weight: bold;">Provided Information</p>
                      </td>
                    </tr>
                    <tr>
                    <td scope="col"><span style="font-weight: bold;">Mobile Number: </span>'.$mobile_number.'</td>
                    
                  </tr>
                  
                </table>
                <hr>

                
               
                <br><br>


                <h1 id="first" ><u>Result</u></h1>

                <p style="font-weight: bold">Report Summary</p>
                <span style="font-weight: bold;">This Report Information Generated Against Mobile Number: </span> '.$mobile_number.'
                <table style=" width:100%; border-collapse: collapse;" id="table">
                
                <tr>
                <td scope="col" colspan="2" style=" width:100%;"><span style="font-weight: bold;">Mobile number: </span> '.$mobile_number.'<br><span style="color:green;">Verified</span></td>


                </tr>
                </table>

                <hr>
                <p style="margin-top:0px;"><span style="color:red;">*</span>Please note that the contents shown above is provided in good faith, we do not warrant that the information will be kept up to date, be true, accurate and not misleading.</p>

                    <!-- Optional JavaScript; choose one of the two! -->

                    <!-- Option 1: Bootstrap Bundle with Popper -->
                    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

                   
                  </body>
                </html>';
   


// Initialize mPDF
$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4']);


 // Write HTML content to mPDF
$mpdf->WriteHTML($html);

// Output PDF to a variable
$output_pdf = $mpdf->Output('', 'S');

// Save PDF to a file
  $path = savePDF2($output_pdf, $end_user_id, $agency_id,$bulk_id);
  
    
// Define response array
$response = array();

// Check if the path is valid
if ($path !== false) {
    // PDF generated successfully
    
    $response['error_code'] = 100;
    $response['message'] = 'PDF generated successfully.';
    
    $response['pdf_url'] = $path;
    $data = [
        "path"=>$response['pdf_url'],
        "for"=>'mob_report',
        "agency_id"=>$agency_id,
        "current_wallet_bal"=>number_format($deducted_amt, 2, '.', ''),
        "is_verified"=>'bulkyes' 
    ];

    $url = get_base_url() . '/new_direct.php';

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
} else {
    // PDF generation failed
    $response['success'] = false;
    $response['message'] = 'Failed to generate PDF.';
    echo json_encode($response);
}

 $query="INSERT INTO `end_user_verification_transaction_all`(`id`, `end_user_id`, `mobile_number`,`weblink_id`, `agency_id`, `document_type`, `initiated_on`, `completed_on`, `activity_status`,`report_url`) VALUES ('','$end_user_id', '$mobile_number','$bulk_id','$agency_id','$verification_id','$system_date_time','$system_date_time','$atvy_status','$path')";
 $res=mysqli_query($mysqli,$query);

  
    $pan_fetch_sql="SELECT * FROM `bulk_end_user_transaction_all` WHERE  `end_user_id`='".$end_user_id."' AND `bulk_id`='".$bulk_id."'";
    $pan_fetch_res=mysqli_query($mysqli,$pan_fetch_sql);
    $pan_fetch_array = mysqli_fetch_assoc($pan_fetch_res);
    $verification_done=$pan_fetch_array["verification_done"];
    $value="DVF-00008";
    if (!empty($verification_done)) {
    $verification_done_array = explode(",", $verification_done);

    // Only append if "2" is not already in the array
    if (!in_array($value, $verification_done_array)) {
        $verification_done .= "," . $value;
    }
} else {
    $verification_done = $value; // Set to "2" if empty
}
    $pan_res1="UPDATE `bulk_end_user_transaction_all` SET `verification_done`='".$verification_done."',`status`='".$atvy_status."' WHERE  `end_user_id`='".$end_user_id."' AND `bulk_id`='".$bulk_id."'";
    $pan_query=mysqli_query($mysqli,$pan_res1);
    // SELECT * FROM `bulk_end_user_transaction_all`
    $fetch_trns_check = "SELECT * FROM `bulk_end_user_transaction_all` WHERE `end_user_id`='".$end_user_id."' AND `bulk_id`='".$bulk_id."'";
    $res_trns_check = mysqli_query($mysqli, $fetch_trns_check);
    $arr_trns_check = mysqli_fetch_assoc($res_trns_check);
    if($arr_trns_check["payment_from"]==1)
    {
         if ($base_amount != '') 
           {
                $total_amount = $base_amount + $sgst_amount + $cgst_amount;
            } 
            else 
            {
                $total_amount = '0';
            }
                   // tentative_amount - total amount  bulk_weblink_request_all  update
                $fetch_wallet_weblink_tev = "SELECT `tentative_amount` FROM `bulk_weblink_request_all` WHERE `agency_id`='$agency_id' AND `bulk_id`='$bulk_id'";
                $res_wallet_weblink_tev = mysqli_query($mysqli, $fetch_wallet_weblink_tev);
                $arr_wallet_weblink_tev = mysqli_fetch_assoc($res_wallet_weblink_tev);
                if($arr_wallet_weblink_tev["tentative_amount"]!=0)
                {
                    $total_amount=$arr_wallet_weblink_tev["tentative_amount"]-$total_amount;

                    $tev_update="UPDATE `bulk_weblink_request_all` SET `tentative_amount`='$total_amount'  WHERE `agency_id`='$agency_id' AND `bulk_id`='$bulk_id'";
                     mysqli_query($mysqli,$tev_update);
                }

                // tentative_amount - total amount  agency_header_all update
                    $fetch_wallet = "SELECT `tentative_amount` FROM `agency_header_all` WHERE `agency_id`='$agency_id'";
                    $res_wallet = mysqli_query($mysqli, $fetch_wallet);
                    $arr_wallet = mysqli_fetch_assoc($res_wallet);
                    if($arr_wallet["tentative_amount"]!=0)
                    {
                        $total_amount=$arr_wallet["tentative_amount"]-$total_amount;

                        $tev_update="UPDATE `agency_header_all` SET `tentative_amount`='$total_amount'  WHERE `agency_id`='$agency_id'";
                         mysqli_query($mysqli,$tev_update);
                    }
                $deducted_amt=$arr_wallet['current_wallet_bal'] - $total_amount;
                $update_wallet = "UPDATE `agency_header_all` SET `current_wallet_bal`='$deducted_amt' WHERE `agency_id`='$agency_id'";
                $res_update = mysqli_query($mysqli, $update_wallet); 
                $transaction_id = 'txn_' . time() . random_int(100000, 999999);
                $wallet_sql="INSERT INTO `wallet_payment_transaction_all` (`agency_id`, `user_id`, `requested_from`, `purchase_type`, `base_amount`, `cgst_amount`, `sgst_amount`, `transaction_on`, `transaction_id`, `line_type`, `ref_transaction_id`,`verification_id`) VALUES ('$agency_id','$end_user_id','3','1','$total_amount','$cgst_amount','$sgst_amount','$system_date_time','$transaction_id','1','$bulk_id','$verification_id')";
                 $wallet_query=mysqli_query($mysqli,$wallet_sql);
                  $user_pay_sql="INSERT INTO `end_user_payment_transaction_all`  (`end_user_id`,`paid_transaction_id`,`bulk_id`,`verification_id`,`paid_amount`, `cgst_amount`, `sgst_amount`,`payment_type`,`paid_by`) VALUES ('$end_user_id','$transaction_id','$bulk_id','$verification_id','$total_amount','$cgst_amount','$sgst_amount','1','$agency_id')";
                 $user_pay_query=mysqli_query($mysqli,$user_pay_sql);
                 $ag_query = "SELECT * FROM agency_header_all WHERE agency_id='$agency_id'";
                $res_ag = mysqli_query($mysqli, $ag_query);
                $arr_ag = mysqli_fetch_assoc($res_ag);
                  $end_user_paidby = "SELECT * FROM `bulk_end_user_transaction_all` WHERE `end_user_id`='".$end_user_id."' AND bulk_id='".$bulk_id."'";
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
                                    <p style="text-align: center; font-size: 20px;"><b>PAYMENT INVOICE ('.date("d-m-Y",strtotime($system_date)).')</b></p><br>
                                    <p style="font-family: Calibri; font-size: 13pt; color: #0d68a5; text-align: center;">MICRO INTEGRATED SEMI CONDUCTOR SYSTEMS PVT. LTD </p>
                                    <p style="margin-top: 2px; margin-left: 5px; text-align: center; line-height: 2; font-family: Calibri; font-size: 11pt;">154, G-2, Gulmohar Colony, Bhopal, Madhya Pradesh, 462039.</p>    
                                    <p style="margin-top: 2px; margin-left: 5px; text-align: center; line-height: 1; font-family: Calibri; font-size: 11pt;"> www.microintegrated.in</p>
                                    <p style="margin-top: 2px; margin-left: 5px; text-align: center; line-height: 2; font-family: Calibri; font-size: 11pt;"><b>GST Number:-23AAECM0658D2ZV</b></p>

                                    <br>
                                    <div style="padding: 4px; background-color: #0d68a5;"></div>
                                    <table style="width:100%; border-collapse: collapse;  background-color:#e5e5e5;">
                                      <thead>
                                        <tr><th style="text-align: left;">';
                                        if(!empty($payment_id))
                                        {
                                         $html .=' Payment ID:  '.$payment_id;
                                          }

                                         $html .=' </th><th style="text-align: right;">
                                          Invoice Date: '.date("d-m-Y", strtotime($system_date)).'</th>
                                        </tr>
                                      </thead>
                                    </table>
                                    <br>
                                    <section>
                                      <div>
                                        <p style="font-size: 13px; margin-left:10px; font-weight:700;"><b>BILL TO</b></p>
                                        <p style="font-size: 12px; margin-left:10px; margin-top:5px;"><b>Paid By :-</b>'.$arr_ag["name"].'</p> ';
                                        if(!empty($arr_ag["mobile_no"]))
                                        {
                                           $html .=' <p style="font-size: 12px; margin-left:10px; margin-top:5px;"><b>Mobile No :-</b> '.$arr_ag["mobile_no"].'</p> ';
                                        }
                                       $html .=' <p style="font-size: 12px; margin-left:10px; margin-top:5px;"><b>Transaction ID :-</b>'.$transaction_id.' </p> 
                                        <p style="font-size: 12px; margin-left:10px; margin-top:5px;"><b>Status :-</b>Paid </p> 
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
                                          $values_array = explode(",", $end_user_id);
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
                                       <p style="font-size: 11px;"><b>Payment Via:- Wallet </b></p>
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
                                $path = savePDF1Weblink($output_pdf, $enduser_id, $agency_id,$bulk_id);


                                     $update_wallet = "UPDATE `end_user_payment_transaction_all` SET `invoice_url`='$path' WHERE   `bulk_id`='$bulk_id' AND `end_user_id`='$end_user_id' AND `paid_by`='$agency_id'";
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
                                        "enduser_id" => $end_user_id

                                    ];

                                    $url = get_base_url() . '/upload_link/payment_new_pdf.php';

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
    }
     else
    {

                    $fetch_wallet = "SELECT * FROM `agency_header_all` WHERE `agency_id`='$agency_id'";
                    $res_wallet = mysqli_query($mysqli, $fetch_wallet);
                    $arr_wallet = mysqli_fetch_assoc($res_wallet);
                    $deducted_amt=$arr_wallet['current_wallet_bal'];
    }
    
    

function savePDF2($output_pdf, $direct_id, $agency_id,$bulk_id)
{
    // FTP server credentials
    // $ftp_server = '103.180.120.27';
    // $ftp_username = 'vocoxpco_vocoxp';
    // $ftp_password = 'Micro!@12345678';
// FTP server credentials
    $ftp_server = '199.79.62.21';
    $ftp_username = 'centralwp@mounarchtech.com';
    $ftp_password = 'k=Y#oBK{h}OU';
    // Remote directory path
    $remote_base_dir = "/verification_data/voco_xp/";

    // Nested directory structure to be created
    $new_directory_path = "$agency_id/Weblink/$bulk_id/$direct_id/mob_report/";

    // Initialize cURL session for FTP
    $curl = curl_init();

    // Set cURL options for FTP
    curl_setopt_array($curl, array(
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
    $file_name = $direct_id . '.pdf';

    // Construct the full file path on the remote server
    $file_path = $remote_dir_path . $file_name;

    // Save the PDF to a temporary file
    $temp_file = tempnam(sys_get_temp_dir(), 'pdf');
    file_put_contents($temp_file, $output_pdf);

    // Initialize cURL session for file upload
    $curl_upload = curl_init();

    // Set cURL options for file upload
    curl_setopt_array($curl_upload, array(
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
    $base_url = "https://mounarchtech.com/central_wp/verification_data/voco_xp/$agency_id/Weblink/$bulk_id/$direct_id/mob_report";
    $path = $base_url . '/' . $file_name;
    return $path;
}


    function savePDF1Weblink($output_pdf, $direct_id, $agency_id,$bulk_id)
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
    $base_url = "https://mounarchtech.com/central_wp/verification_data/voco_xp/$agency_id/Weblink/$bulk_id/$end_user_id/invoice";
    $path = $base_url . '/' . $file_name;
    return $path;
}
    
?>
