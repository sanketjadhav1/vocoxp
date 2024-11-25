    <?php
    include '../vendor/autoload.php';
include '../connection.php';
//Application Vocoxp databese connection//
$connection = connection::getInstance();
$mysqli = $connection->getConnection();
//Central Database Connection//
$connection1 = database::getInstance();
$mysqli1 = $connection1->getConnection();

error_reporting(1);
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
        $select_array = mysqli_fetch_assoc($result_paychk);
         
                        
                        
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
                                  <a href="https://mounarchtech.com/vocoxp/upload_link/web_verification_form.php?enduser_id=<?Php echo$enduser_id;?>" class="btn btn-success">Go To Dashboard</a>
                            </div>
                            
                            
                        </div> 
                    </div>
            </div>
           
        <?PHP 
        }

        else 
        {
            

              $stmt = "INSERT INTO `end_user_payment_transaction_all`( `end_user_id`, `paid_transaction_id`, `paid_by`, `gateway_transaction_id`, `status`, `gst_number`, `paid_amount`, `cgst_amount`, `sgst_amount`, `mi_amount`, `mi_cgst_amount`, `mi_sgst_amount`, `agency_amount`, `agency_cgst_amount`, `agency_sgst_amount`, `inserted_on`, `bulk_id`) VALUES ('$user_ids','$transaction_id','$enduser_id','$payment_id','$status','$gst_number','$paid_amount','$sgst_cgst_paid','$sgst_cgst_paid','$total_miamount','$sgst_cgst_mitax','$sgst_cgst_mitax','$total_clientamount','$sgst_cgst_clienttax','$sgst_cgst_clienttax','$system_date_time','$weblink_id')";

                if (mysqli_query($mysqli,$stmt))
                 {

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
                                               <h3 id="header"> PAYMENT INVOICE</h3>
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
                                      
                                      <p style="font-weight: bold;">Payment Information</p>
                                      </td>
                                    </tr>
                                    <tr>
                                    <td scope="col"><span style="font-weight: bold;"> Invoice Date :</span> ' . date("d-m-Y H:i:s",strtotime($system_date_time)). '</td>
                                    
                                  </tr>
                                    <tr>
                                     <td scope="col"><span style="font-weight: bold;">Name/ID: </span>' .$enduser_id . '</td>

                                    
                                  </tr>
                                  <tr>
                                    <td scope="col"><span style="font-weight: bold;">Payment-ID:</span> ' .$payment_id. '</td> 
                                  </tr>
                                   <tr>
                                    <td scope="col"><span style="font-weight: bold;">Transaction-ID:</span> ' .$transaction_id. '</td> 
                                  </tr>
                                   <tr>
                                    <td scope="col"><span style="font-weight: bold;">Status:</span> ' .$status. '</td> 
                                  </tr>

                                </table> 

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
                                                      <a href="https://mounarchtech.com/vocoxp/upload_link/web_verification_form.php?enduser_id=<?Php echo$enduser_id;?>" class="btn btn-success">Now verify your details</a>
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
    $base_url = "https://mounarchtech.com/central_wp/verification_data/voco_xp/$agency_id/Weblink/$bulk_id/$direct_id/invoice";
    $path = $base_url . '/' . $file_name;
    return $path;
}
    ?>

 