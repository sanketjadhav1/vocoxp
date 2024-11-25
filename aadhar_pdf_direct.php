<?php
require_once __DIR__ . '/vendor/autoload.php';

include 'connection.php';

ini_set('display_errors', 0);
$connection = connection::getInstance();
$mysqli = $connection->getConnection();
///////////////////////////////////////////////
$connection1 = database::getInstance();
$mysqli1 = $connection1->getConnection();

date_default_timezone_set('Asia/kolkata');
$system_date = date("y-m-d");
$system_date_time = date("Y-m-d H:i:s");


$name = $_POST['name'];

$edited_name = $_POST['edited_name'];
$aadhar_number = $_POST['aadhar_number'];
$edited_aadhar_no = $_POST['edited_aadhar_no'];


$edited_dob = $_POST['edited_dob'];
$dob = date("d-m-Y", strtotime($_POST['date_of_birth']));
$dob_ocr = date("d-m-Y", strtotime($_POST['dob_ocr']));

$dob1 = date("Y-m-d", strtotime($_POST['date_of_birth']));

$address = $_POST['address'];
$address1 = $_POST['address_aadhar'];
$edited_address = $_POST['edited_address'];
$gender = $_POST['gender'];
$edited_gender = $_POST['edited_gender'];
$application_id = $_POST['application_id'];
$agency_id = $_POST['agency_id'];
$admin_id = $_POST['admin_id'] ? $_POST['admin_id'] : $_POST['agency_id'];

$verification_id = $_POST['verification_id'];
$base_amount = $_POST['base_amount'];
$sgst_amount = $_POST['sgst_amount'];
$cgst_amount = $_POST['cgst_amount'];
$is_verified = $_POST['is_verified'];
$is_edited = $_POST['is_edited'];
$data_fetch_through_ocr = $_POST['data_fetch_through_ocr'];
$front_photo = $_POST['front_img'];
$back_photo = $_POST['back_img'];
$user_photo = $_POST['user_img'];
$verification_name = $_POST['verification_name'];
$verification_dob = $_POST['verification_dob'];
$verification_category = $_POST['verification_category'];
 $name_match = $_POST['name_match'];
 $birth_date_match = $_POST['birth_date_match'];
$pincode_match = $_POST['pincode_match'];
$percentage = $_POST['percentage'];
$source_from = $_POST['source_from'];
$fetch_wallet = "SELECT current_wallet_bal FROM agency_header_all WHERE agency_id='$agency_id'";
$res_wallet = mysqli_query($mysqli, $fetch_wallet);
$arr_wallet = mysqli_fetch_assoc($res_wallet);

$direct_id = $_POST['direct_id'];
$front_img = $_POST['front_img'];
$back_img = $_POST['back_img'];
$user_img = $_POST['user_img'];
if ($is_verified == "yes") {
	$status = 1;
	$base_amount = $_POST['base_amount'];
	$sgst_amount = $_POST['sgst_amount'];
	$cgst_amount = $_POST['cgst_amount'];
	$gst = $sgst_amount + $cgst_amount;
	$sgst_amt = $base_amount * $sgst_amount / 100;
	$cgst_amt = $base_amount * $cgst_amount / 100;
	$grand_total = $base_amount * $gst / 100;
	$deducted_amt = $arr_wallet['current_wallet_bal'] - $grand_total - $base_amount;
	$total = $base_amount + $sgst_amt + $cgst_amt;
} else {
	$status = 2;
}
if ($name_match == 'Match') {
	$color = 'green';
} else {
	$color = 'red';
}
if ($birth_date_match == 'Match') {
	$color1 = 'green';
} else {
	$color1 = 'red';
}
if ($is_edited != "yes") {
	$name_show = $name;
	$dateOfBirth = $dob_ocr;
} else {
	$name_show = $edited_name;
	$dateOfBirth = $edited_dob;
}
$show_dob = $dob ? date('d-m-Y', strtotime($dob)) : date('d-m-Y', strtotime($edited_dob));
// HTML content with embedded image
 if($address==$address1)
 {
    $address_match="Match";
 }
 else
 {
    $address_match="Not Match";

 }
$ismatch="";
if($name_match =="Match" && $birth_date_match=="Match" && $address_match=="Match")
{
  $ismatch="ok=all";
  $atvy_status="1";
}
else
{
        if($name_match=="Match")
        {
             
        }
        else
        {
            $ismatch1="name@".$verification_name.'!'.$name;
        }
        if($birth_date_match=="Match")
        {
             
        }
        else
        {
            $ismatch2="dob@".$verification_dob.'!'.$show_dob;
        }
        if($address_match=="Match")
        {
             
        }
        else
        {
            $ismatch3="address@".$address1.'!'.$address;
        }

   $ismatch=$ismatch1.",".$ismatch2.",".$ismatch3;
  $atvy_status="2";
}


if($is_verified=="bulkyes")
{
    $name = $_POST['name'];
    $admin_id=$_POST["admin_id"];
    $agency_id=$_POST["agency_id"];
    $bulk_id=$_POST["bulk_id"];
    $base_amount=$_POST['base_amount'] ?? '';
   $sgst_amount=$_POST['sgst_amount'] ?? '';
   $cgst_amount=$_POST['cgst_amount'] ?? '';
    if($is_verified=="bulkyes")  {
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
                line-height: 20px;
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
                           <h3 id="header"> AADHAR VERIFICATION REPORT</h3>
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
                <td scope="col"><span style="font-weight: bold;"> Name:</span> ' . $name. '</td>
                <td scope="col"><span style="font-weight: bold;">Aadhar Number: </span>' . $aadhar_number . '</td>
              </tr>
                <tr>
                <td scope="col"><span style="font-weight: bold;">Date Of Birth:</span> ' .$dob_ocr. '</td>

                
              </tr> 
            </table>
            <hr>
            <table style=" width:100%; border-collapse: collapse;">
               
                <tr>
                  <td scope="col" colspan="2">
                  <h1 id="second"><u>Provided Document</u></h1>
                  <br>
                  </td>
                </tr>
                <tr>
                <td scope="col" align="center">
                <p style="text-align: center; font-size:15px;">Front Image</p>
            <br>
                <img src="' . $front_img . '" alt="Placeholder image" width="30%" />
                </td>
                <td scope="col" align="center">
                <p style="text-align: center; font-size:15px;">Back Image</p>
                <br>

                <img src="' . $back_img . '" alt="Placeholder image" width="30%" />

                </td>
              </tr>
             
            </table>
            <br><br>


            <h1 id="first" ><u>Result</u></h1>

            <p style="font-weight: bold;">Report Summary</p>

            <span style="font-weight: bold;">This Report Information Genrated Against Aadhar Number: </span>' . $aadhar_number . ' 



            <table style=" width:100%; border-collapse: collapse;" id="table">
            <tr>
            <td scope="col" style="width:50%"><span style="font-weight: bold;">Aadhar Number:</span> ' . $aadhar_number . '</td>

            <td scope="col" style="width:50%"><span style="font-weight: bold;"> Name:</span> ' . strtoupper($verification_name) . '<br><span style="color:' . $color . ';">' . $name_match . '</span></td>
            </tr>
            <tr>
            <td scope="col" style="width:50%"><span style="font-weight: bold;">Date Of Birth:</span> ' . date("d-m-Y", strtotime($verification_dob)) . '<br><span style="color:' . $color1 . ';">' . $birth_date_match . '</span></td>


            </table>

            <hr>
            <p style="margin-top:10px;"><span style="color:red;">*</span>Please note that the contents shown above is provided in good faith, we do not warrant that the information will be kept up to date, be true, accurate and not misleading.</p>

                <!-- Optional JavaScript; choose one of the two! -->

                <!-- Option 1: Bootstrap Bundle with Popper -->
                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

               
              </body>
            </html>';
            } else {
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
                line-height: 20px;
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
                           <h3 id="header"> AADHAR VERIFICATION REPORT</h3>
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
                <td scope="col"><span style="font-weight: bold;"> Name:</span> ' . $name_show . '</td>
                <td scope="col"><span style="font-weight: bold;">Aadhar Number: </span>' . $aadhar_number . '</td>
              </tr>
                <tr>
                <td scope="col"><span style="font-weight: bold;">Date Of Birth:</span> ' . $dob . '</td>

                
              </tr> 
            </table>
            <hr>
            <table style=" width:100%; border-collapse: collapse;">
               
                <tr>
                  <td scope="col" colspan="2">
                  <h1 id="second"><u>Provided Document</u></h1>
                  <br>
                  </td>
                </tr>
                <tr>
                <td scope="col" align="center">
                <p style="text-align: center; font-size:15px;">Front Image</p>
            <br>
                <img src="' .$front_img. '" alt="Placeholder image" width="30%" />
                </td>
                <td scope="col" align="center">
                <p style="text-align: center; font-size:15px;">Back Image</p>
                <br>

                <img src="' .$back_img. '" alt="Placeholder image" width="30%" />

                </td>
              </tr>
             
            </table>
            <br><br>


            <h1 id="first" ><u>Result</u></h1>

            <p style="font-weight: bold;">Report Summary</p>

            <span style="font-weight: bold;">This Report Information Genrated Against Aadhar Number: </span>' . $aadhar_number . ' 



            <table style=" width:100%; border-collapse: collapse;" id="table">
            <tr>
            <td scope="col" style="width:50%"><span style="font-weight: bold;">Aadhar Number:</span> ' . $aadhar_number . '</td>

            <td scope="col" style="width:50%"><span style="font-weight: bold;"> Name:</span> ' . strtoupper($name) . '</td>
            </tr>
            <tr>
            <td scope="col" style="width:50%"><span style="font-weight: bold;">Gender:</span> ' . $gender . '</td>

            <td scope="col" style="width:50%"><span style="font-weight: bold;"> Edited Gender:</span> ' . $edited_gender . '</td>
            </tr>
            <tr>
            <td scope="col" style="width:50%"><span style="font-weight: bold;">Date Of Birth:</span> ' . $dob . '</td>
            <td scope="col" style="width:50%"><span style="font-weight: bold;">Edited Date Of Birth:</span> ' . date('d-m-Y', strtotime($edited_dob)) . '</td>
            </tr>
            <tr>
            <td scope="col" style="width:50%"><span style="font-weight: bold;">Address:</span> ' . $address . '</td>
            <td scope="col" style="width:50%"><span style="font-weight: bold;">Edited Address:</span> ' . $edited_address . '</td>
            </tr>


            </table>

            <hr>
            <p style="margin-top:10px;"><span style="color:red;">*</span>Please note that the contents shown above is provided in good faith, we do not warrant that the information will be kept up to date, be true, accurate and not misleading.</p>

                <!-- Optional JavaScript; choose one of the two! -->

                <!-- Option 1: Bootstrap Bundle with Popper -->
                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

               
              </body>
            </html>';
            }
if($is_verified=="bulkyes")
{
    $fetch_trns_check = "SELECT * FROM `bulk_end_user_transaction_all` WHERE `end_user_id`='".$admin_id."' AND `bulk_id`='".$bulk_id."'";
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
                $wallet_sql="INSERT INTO `wallet_payment_transaction_all` (`agency_id`, `user_id`, `requested_from`, `purchase_type`, `base_amount`, `cgst_amount`, `sgst_amount`, `transaction_on`, `transaction_id`, `line_type`, `ref_transaction_id`,`verification_id`) VALUES ('$agency_id','$admin_id','3','1','$total_amount','$cgst_amount','$sgst_amount','$system_date_time','$transaction_id','1','$bulk_id','$verification_id')";
                 $wallet_query=mysqli_query($mysqli,$wallet_sql);
                  $user_pay_sql="INSERT INTO `end_user_payment_transaction_all`  (`end_user_id`,`paid_transaction_id`,`bulk_id`,`verification_id`,`paid_amount`, `cgst_amount`, `sgst_amount`,`payment_type`,`paid_by`) VALUES ('$admin_id','$transaction_id','$bulk_id','$verification_id','$total_amount','$cgst_amount','$sgst_amount','1','$agency_id')";
                 $user_pay_query=mysqli_query($mysqli,$user_pay_sql);
                 $ag_query = "SELECT * FROM agency_header_all WHERE agency_id='$agency_id'";
                $res_ag = mysqli_query($mysqli, $ag_query);
                $arr_ag = mysqli_fetch_assoc($res_ag);
                  $end_user_paidby = "SELECT * FROM `bulk_end_user_transaction_all` WHERE `end_user_id`='".$admin_id."' AND bulk_id='".$bulk_id."'";
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
                                          $values_array = explode(",", $admin_id);
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
                                $path_weblink = savePDF1Weblink($output_pdf, $enduser_id, $agency_id,$bulk_id);


                                     $update_wallet = "UPDATE `end_user_payment_transaction_all` SET `invoice_url`='$path_weblink' WHERE   `bulk_id`='$bulk_id' AND `end_user_id`='$admin_id' AND `paid_by`='$agency_id'";
                                    $res_update = mysqli_query($mysqli, $update_wallet);

                                // Define response array
                                $response = array();

                                // Check if the path is valid
                                if ($path_weblink !== false) {
                                    // PDF generated successfully
                                    $response['error_code'] = 100;
                                    $response['message'] = 'PDF generated successfully.';

                                    $response['pdf_url'] = $path_weblink;
                                    $data = [
                                        "path" => $path_weblink,
                                        "for" => 'invoice',
                                        "agency_id" => $agency_id,
                                        "bulk_id" => $bulk_id,
                                        "enduser_id" => $admin_id

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
}

            // Initialize mPDF
$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4']);


// Write HTML content to mPDF
$mpdf->WriteHTML($html);

// Output PDF to a variable
$output_pdf = $mpdf->Output('', 'S');

// Save PDF to a file
$path2 = savePDF2($output_pdf, $admin_id, $agency_id,$bulk_id);



// Define response array
$response = array();

// Check if the path is valid
if ($path2 !== false) {
    // PDF generated successfully
    $response['error_code'] = 100;
    $response['message'] = 'PDF generated successfully.';

    $response['pdf_url'] = $path2;
    $data = [
        "path" => $path2,
        "for" => 'aadhar_report',
        "agency_id" => $agency_id,
        "current_wallet_bal" => number_format($deducted_amt, 2, '.', ''),
        "is_verified" => $is_verified

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
    // $res[]=["pdf_url"=>$path, 'current_wallet_bal'=>number_format($deducted_amt, 2, '.', '')];

    // $data=["error_code"=>100, "message"=>"PDF generated successfully.", "data"=>$res];
    // echo json_encode($data);
} else {
    // PDF generation failed
    $response['success'] = false;
    $response['message'] = 'Failed to generate PDF.';
}

  $query="INSERT INTO `end_user_verification_transaction_all`(`id`, `end_user_id`, `aadhar_number`, `first_name`, `last_name`, `pan_number`, `dl_number`, `voter_number`, `passport_number`, `aadhar_ambiguity`, `pan_ambiguity`, `dl_ambiguity`, `voter_ambiguity`, `passport_ambiguity`, `weblink_id`, `agency_id`, `document_type`, `father_name`, `mother_name`, `spouse_name`, `gender`, `dob`, `blood_group`, `address`, `country_code`, `state_name`, `nationality`, `front_photo`, `back_photo`, `user_photo`, `cover_photo`, `date_of_issue`, `date_of_expiry`, `place_of_issue`, `classes_of_vehicle`, `polling_details`, `republic_of_india`, `passport_type`, `file_number`, `initiated_on`, `completed_on`, `activity_status`,`report_url`) VALUES ('','$admin_id','$aadhar_number','$name','','','','','','$ismatch','','','','','$bulk_id','$agency_id','$verification_id','','','','','$show_dob','','','','','','','','','','','','','','','','','','$system_date_time','$system_date_time','$atvy_status','$path2')";
 $res=mysqli_query($mysqli,$query);

// seelct query bulk_end_user_transaction_all verification_done `end_user_id`='$admin_id' AND `weblink_id`='$bulk_id' / empty hoga to 2 aage not empty implode (,)
    $pan_fetch_sql="SELECT * FROM `bulk_end_user_transaction_all` WHERE  `end_user_id`='".$admin_id."' AND `bulk_id`='".$bulk_id."'";
    $pan_fetch_res=mysqli_query($mysqli,$pan_fetch_sql);
    $pan_fetch_array = mysqli_fetch_assoc($pan_fetch_res);
    $verification_done= $pan_fetch_array["verification_done"];
    $value="DVF-00001";
    if (!empty($verification_done)) {
    $verification_done_array = explode(",", $verification_done);

    // Only append if "2" is not already in the array
    if (!in_array($value, $verification_done_array)) {
        $verification_done .= "," . $value;
    }
} else {
    $verification_done = $value; // Set to "2" if empty
}
    $pan_res1="UPDATE `bulk_end_user_transaction_all` SET `verification_done`='".$verification_done."' ,`status`='".$atvy_status."' WHERE  `end_user_id`='".$admin_id."' AND `bulk_id`='".$bulk_id."'";
    $pan_query=mysqli_query($mysqli,$pan_res1);
    // SELECT * FROM `bulk_end_user_transaction_all`
    
}

if ($is_verified == "yes") {
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
    line-height: 20px;
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
               <h3 id="header"> AADHAR VERIFICATION REPORT</h3>
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
    <td scope="col"><span style="font-weight: bold;"> Name:</span> ' . $name. '</td>
    <td scope="col"><span style="font-weight: bold;">Aadhar Number: </span>' . $aadhar_number . '</td>
  </tr>
    <tr>
    <td scope="col"><span style="font-weight: bold;">Date Of Birth:</span> ' .$dob_ocr. '</td>

    
  </tr> 
</table>
<hr>
<table style=" width:100%; border-collapse: collapse;">
   
    <tr>
      <td scope="col" colspan="2">
      <h1 id="second"><u>Provided Document</u></h1>
      <br>
      </td>
    </tr>
    <tr>
    <td scope="col" align="center">
    <p style="text-align: center; font-size:15px;">Front Image</p>
<br>
    <img src="' . $front_img . '" alt="Placeholder image" width="30%" />
    </td>
    <td scope="col" align="center">
    <p style="text-align: center; font-size:15px;">Back Image</p>
    <br>

    <img src="' . $back_img . '" alt="Placeholder image" width="30%" />

    </td>
  </tr>
 
</table>
<br><br>


<h1 id="first" ><u>Result</u></h1>

<p style="font-weight: bold;">Report Summary</p>

<span style="font-weight: bold;">This Report Information Genrated Against Aadhar Number: </span>' . $aadhar_number . ' 



<table style=" width:100%; border-collapse: collapse;" id="table">
<tr>
<td scope="col" style="width:50%"><span style="font-weight: bold;">Aadhar Number:</span> ' . $aadhar_number . '</td>

<td scope="col" style="width:50%"><span style="font-weight: bold;"> Name:</span> ' . strtoupper($verification_name) . '<br><span style="color:' . $color . ';">' . $name_match . '</span></td>
</tr>
<tr>
<td scope="col" style="width:50%"><span style="font-weight: bold;">Date Of Birth:</span> ' . date("d-m-Y", strtotime($verification_dob)) . '<br><span style="color:' . $color1 . ';">' . $birth_date_match . '</span></td>


</table>

<hr>
<p style="margin-top:10px;"><span style="color:red;">*</span>Please note that the contents shown above is provided in good faith, we do not warrant that the information will be kept up to date, be true, accurate and not misleading.</p>

    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

   
  </body>
</html>';
} else {
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
    line-height: 20px;
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
               <h3 id="header"> AADHAR VERIFICATION REPORT</h3>
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
    <td scope="col"><span style="font-weight: bold;"> Name:</span> ' . $name_show . '</td>
    <td scope="col"><span style="font-weight: bold;">Aadhar Number: </span>' . $aadhar_number . '</td>
  </tr>
    <tr>
    <td scope="col"><span style="font-weight: bold;">Date Of Birth:</span> ' . $dob . '</td>

    
  </tr> 
</table>
<hr>
<table style=" width:100%; border-collapse: collapse;">
   
    <tr>
      <td scope="col" colspan="2">
      <h1 id="second"><u>Provided Document</u></h1>
      <br>
      </td>
    </tr>
    <tr>
    <td scope="col" align="center">
    <p style="text-align: center; font-size:15px;">Front Image</p>
<br>
    <img src="' .$front_img. '" alt="Placeholder image" width="30%" />
    </td>
    <td scope="col" align="center">
    <p style="text-align: center; font-size:15px;">Back Image</p>
    <br>

    <img src="' .$back_img. '" alt="Placeholder image" width="30%" />

    </td>
  </tr>
 
</table>
<br><br>


<h1 id="first" ><u>Result</u></h1>

<p style="font-weight: bold;">Report Summary</p>

<span style="font-weight: bold;">This Report Information Genrated Against Aadhar Number: </span>' . $aadhar_number . ' 



<table style=" width:100%; border-collapse: collapse;" id="table">
<tr>
<td scope="col" style="width:50%"><span style="font-weight: bold;">Aadhar Number:</span> ' . $aadhar_number . '</td>

<td scope="col" style="width:50%"><span style="font-weight: bold;"> Name:</span> ' . strtoupper($name) . '</td>
</tr>
<tr>
<td scope="col" style="width:50%"><span style="font-weight: bold;">Gender:</span> ' . $gender . '</td>

<td scope="col" style="width:50%"><span style="font-weight: bold;"> Edited Gender:</span> ' . $edited_gender . '</td>
</tr>
<tr>
<td scope="col" style="width:50%"><span style="font-weight: bold;">Date Of Birth:</span> ' . $dob . '</td>
<td scope="col" style="width:50%"><span style="font-weight: bold;">Edited Date Of Birth:</span> ' . date('d-m-Y', strtotime($edited_dob)) . '</td>
</tr>
<tr>
<td scope="col" style="width:50%"><span style="font-weight: bold;">Address:</span> ' . $address . '</td>
<td scope="col" style="width:50%"><span style="font-weight: bold;">Edited Address:</span> ' . $edited_address . '</td>
</tr>


</table>

<hr>
<p style="margin-top:10px;"><span style="color:red;">*</span>Please note that the contents shown above is provided in good faith, we do not warrant that the information will be kept up to date, be true, accurate and not misleading.</p>

    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

   
  </body>
</html>';
}


// Initialize mPDF
$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4']);


// Write HTML content to mPDF
$mpdf->WriteHTML($html);

// Output PDF to a variable
$output_pdf = $mpdf->Output('', 'S');

// Save PDF to a file
$path = savePDF1($output_pdf, $direct_id, $agency_id);



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
		"for" => 'aadhar_report',
		"agency_id" => $agency_id,
		"current_wallet_bal" => number_format($deducted_amt, 2, '.', ''),
		"is_verified" => $is_verified

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
		echo $response;
	}

	// Close the cURL session
	curl_close($ch);
	// $res[]=["pdf_url"=>$path, 'current_wallet_bal'=>number_format($deducted_amt, 2, '.', '')];

	// $data=["error_code"=>100, "message"=>"PDF generated successfully.", "data"=>$res];
	// echo json_encode($data);
} else {
	// PDF generation failed
	$response['success'] = false;
	$response['message'] = 'Failed to generate PDF.';
}



if ($is_verified == "yes") {

	$update_wallet = "UPDATE `agency_header_all` SET `current_wallet_bal`='$deducted_amt' WHERE `agency_id`='$agency_id'";
	$res_update = mysqli_query($mysqli, $update_wallet);


	$insert_wall = "INSERT INTO `wallet_payment_transaction_all` (`id`, `agency_id`, `user_id`, `requested_from`, `purchase_type`, `verification_id`, `base_amount`, `cgst_amount`, `sgst_amount`, `transaction_on`, `transaction_id`, `line_type`, `quantity`, `settled_for`) VALUES (NULL, '$agency_id', '$direct_id', '2', '1', '$verification_id', '$base_amount', '$cgst_amt', '$sgst_amt', '$system_date_time', '', '1', '', '');";
	$res_wall = mysqli_query($mysqli, $insert_wall);
}

$insert_pan_payment = "INSERT INTO `direct_verification_details_all` (`direct_id`, `application_id`, `agency_id`, `verification_id`, `initiated_on`, `completed_on`, `activity_status`, `linked_table`, `deducted_base_amount`, `sgst_amount`, `cgst_amount`, `report_url`, `source_from`,`ambiguity`) VALUES ('$direct_id', '$application_id', '$agency_id', '$verification_id', '$system_date_time', '$system_date_time', '$status', 'direct_aadhar_details_all', '$base_amount', '$sgst_amt', '$cgst_amt', '$path', '$source_from','$ismatch' )
";
$res_pan = mysqli_query($mysqli, $insert_pan_payment);

// Insert into `direct_pan_details_all`
$insert_aadhar_detail = "INSERT INTO `direct_aadhar_details_all` (`direct_id`, `application_id`, `agency_id`, `initiated_on`, `completed_on`, `activity_status`, `aadhar_number`, `name`, `dob`, `gender`, `address`, `user_photo`, `front_photo`, `back_photo`, `admin_id`) VALUES ('$direct_id', '$application_id', '$agency_id', '$system_date_time', '$system_date_time', '$status', '$aadhar_number', '$name', '$dob1', '$gender', '$address', '$user_img', '$front_img', '$back_img', '$admin_id')
";
$res_pan_detail = mysqli_query($mysqli, $insert_aadhar_detail);

// Insert into `edited_direct_pan_details_all` if edited
if ($is_edited == "yes") {
	$insert_edited_pan_detail = "INSERT INTO `edited_direct_aadhar_details_all` (`direct_id`, `application_id`, `agency_id`, `initiated_on`, `completed_on`, `activity_status`, `aadhar_number`, `name`, `dob`, `gender`, `address`, `user_photo`, `front_photo`, `back_photo`, `admin_id`) VALUES ('$direct_id', '$application_id', '$agency_id', '$system_date_time', '$system_date_time', '$status', '$edited_aadhar_no', '$edited_name', '$edited_dob', '$gender', '$edited_address', '$user_img', '$front_img', '$back_img', '$admin_id')
    ";
	$res_edited_pan_detail = mysqli_query($mysqli, $insert_edited_pan_detail);
}

function savePDF1($output_pdf, $direct_id, $agency_id)
{
	// FTP server credentials
	$ftp_server = '199.79.62.21';
	$ftp_username = 'centralwp@mounarchtech.com';
	$ftp_password = 'k=Y#oBK{h}OU';

	// Remote directory path
	$remote_base_dir = "/verification_data/voco_xp/";

	// Nested directory structure to be created
	$new_directory_path = "$agency_id/$direct_id/verification_report/";

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
	$file_name = $direct_id . '.pdf';

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
	$base_url = "https://mounarchtech.com/central_wp/verification_data/voco_xp/$agency_id/$direct_id/verification_report";
	$path = $base_url . '/' . $file_name;
	return $path;
}
function save_user_photo($output_pdf, $direct_id, $agency_id)
{
	// FTP server credentials
	$ftp_server = '199.79.62.21';
	$ftp_username = 'centralwp@mounarchtech.com';
	$ftp_password = 'k=Y#oBK{h}OU';

	// Remote directory path
	$remote_base_dir = "/verification_data/voco_xp/";

	// Nested directory structure to be created
	$new_directory_path = "$agency_id/$direct_id/user_photo/";

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
	$file_name =  $direct_id . '.jpg';

	// Construct the full file path on the remote server
	$file_path = $remote_dir_path . $file_name;

	// Save the PDF to a temporary file
	$temp_file = tempnam(sys_get_temp_dir(), 'jpg');
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
	$base_url = "https://mounarchtech.com/central_wp/verification_data/voco_xp/$agency_id/$direct_id/user_photo";
	$path = $base_url . '/' . $file_name;
	return $path;
}
function save_doc_photo($output_pdf, $direct_id, $agency_id)
{
	// FTP server credentials
	$ftp_server = '199.79.62.21';
	$ftp_username = 'centralwp@mounarchtech.com';
	$ftp_password = 'k=Y#oBK{h}OU';

	// Remote directory path
	$remote_base_dir = "/verification_data/voco_xp/";

	// Nested directory structure to be created
	$new_directory_path = "$agency_id/$direct_id/doc_photo/";

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
	$file_name =  $direct_id . '.jpg';

	// Construct the full file path on the remote server
	$file_path = $remote_dir_path . $file_name;

	// Save the PDF to a temporary file
	$temp_file = tempnam(sys_get_temp_dir(), 'jpg');
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
	$base_url = "https://mounarchtech.com/central_wp/verification_data/voco_xp/$agency_id/$direct_id/doc_photo";
	$path = $base_url . '/' . $file_name;
	return $path;
}

function savePDF2($output_pdf, $direct_id, $agency_id,$bulk_id)
{
    // FTP server credentials
    $ftp_server = '199.79.62.21';
    $ftp_username = 'centralwp@mounarchtech.com';
    $ftp_password = 'k=Y#oBK{h}OU';

    // Remote directory path
    $remote_base_dir = "/verification_data/voco_xp/";

    // Nested directory structure to be created
    $new_directory_path = "$agency_id/Weblink/$bulk_id/$direct_id/aadhar_report/";

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
    $file_name = $direct_id . '.pdf';

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
    $base_url = "https://mounarchtech.com/central_wp/verification_data/voco_xp/$agency_id/Weblink/$bulk_id/$direct_id/aadhar_report/";
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
    $base_url = "https://mounarchtech.com/central_wp/verification_data/voco_xp/$agency_id/Weblink/$bulk_id/$direct_id/invoice";
    $path = $base_url . '/' . $file_name;
    return $path;
}
    
?>