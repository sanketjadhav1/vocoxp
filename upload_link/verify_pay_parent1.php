    <?php
    include '../vendor/autoload.php';
include '../connection.php';
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
 $parent_count = $_GET['parent_count'];
 $student_count = $_GET['student_count'];
 
 $weblink_id = $_GET['bulk_id'];
 $gst_number = $_GET['gst_number'];
 $agency_id = $_GET['agency_id'];
 $user_ids = $_GET['user_ids'];
 
if ($status == 'paid') {
    
     $pay_check = "SELECT * FROM `end_user_payment_transaction_all` WHERE `end_user_id` = '$enduser_id' AND `gateway_transaction_id` = '$payment_id'";
        $result_paychk = $mysqli->query($pay_check);
        $select_array = mysqli_fetch_assoc($result_paychk);
            $fetch_amount="SELECT * FROM `bulk_weblink_request_all` WHERE `agency_id`='$agency_id' AND `bulk_id`='$weblink_id'";
            $res_amount=mysqli_query($mysqli, $fetch_amount);
            $arr_amount=mysqli_fetch_assoc($res_amount);
            // $generated_for=$arr_amount['generated_for'];
            // $client_addon_amt=$arr_amount['client_addon_amt'];
            // $amount=$arr_amount['amount']; 
            // $child_amount=$arr_amount['child_amount'];
            // $child_client_addon_amt=$arr_amount['child_client_addon_amt'];


        if (mysqli_num_rows($result_paychk) < 0) 
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
                                  <a href="https://mounarchtech.com/vocoxp/verification.php?end_user_id=<?Php echo$enduser_id;?>" class="btn btn-success">Go To Dashboard</a>
                            </div>
                            
                            
                        </div> 
                    </div>
            </div>
           
        <?PHP 
        }

        else 
        {
           
              // Split the string by commas to get each component
            $components = explode(',', $client_addon_amt);
            $components_amount = explode(',', $amount);
            $components_child_amount = explode(',', $child_amount);
            $components_child_client_addon_amt = explode(',', $child_client_addon_amt);
            
            $total_clientamount = 0;
            $total_clienttax = 0;
            $total_miamount = 0;
            $total_mitax = 0;

           for ($i = 0; $i < $parent_count; $i++) {
                // Assuming $components is an array
                foreach ($components as $component) {
                    // Split by '-' to separate the key and amount-part
                    $parts = explode('-', $component);

                    // Split the amount-part by '+' to get the amount and tax
                    $clamount_part = explode('+', $parts[0]);

                    // Add the amount and tax to the total
                    $total_clientamount += (float)$parts[1];
                    $total_clienttax += (float)$clamount_part[1];
                }
                 foreach ($components_amount as $component_amount) 
                        {
                            // Split by '=' to separate the key and the amount-part
                            $miparts = explode('=', $component_amount);

                            // Split the amount-part by '-' to get the amount
                           $amount_part = explode('+', $miparts[1]);
                        // print_r($amount_part);
                            // Add the amount to the total
                            $total_miamount += (float)$amount_part[0];
                            $total_mitax += (float)$amount_part[1];
                        }
            }
            for ($i = 0; $i < $student_count; $i++) {

                foreach ($components_child_client_addon_amt as $component_child_client_addon_amt) {
                    // Split by '=' to separate the key and the amount-part
                    $parts_child_client_addon_am = explode('-', $component_child_client_addon_amt);
                    
                    // Split the amount-part by '-' to get the amount
                   $child_client_addon_part = explode('+', $parts_child_client_addon_am[0]);
                // print_r($child_client_addon_part);

                    // Add the amount to the total
                    $total_clientamount += (float)$parts_child_client_addon_am[1];
                    $total_clienttax += (float)$child_client_addon_part[1];
                }

        foreach ($components_child_amount as $component_child_amount) {
            // Split by '=' to separate the key and the amount-part
            $parts_child_amount = explode('=', $component_child_amount);
            // Split the amount-part by '-' to get the amount
           $clamount_part_child_amount = explode('+', $parts_child_amount[1]);
        // print_r($clamount_part_child_amount);

            // Add the amount to the total
            $total_miamount += (float)$clamount_part_child_amount[0];
            $total_mitax += (float)$clamount_part_child_amount[1];
        }
    }        
            $total_amount = ($total_clientamount*1)+($total_clienttax*1)+($total_miamount*1)+($total_mitax);
            $paid_amt_gst=round(($total_amount*18)/100,2);
            $sgst_cgst_paid=($paid_amt_gst/2);

            //mi gst amount 
            $sgst_cgst_mitax=($total_mitax/2);

            //client gst amount
            $sgst_cgst_clienttax=($total_clienttax/2);
            
    // die();        
             $stmt = "INSERT INTO `end_user_payment_transaction_all`( `end_user_id`, `paid_transaction_id`, `paid_by`, `gateway_transaction_id`, `status`, `gst_number`, `paid_amount`, `cgst_amount`, `sgst_amount`, `mi_amount`, `mi_cgst_amount`, `mi_sgst_amount`, `agency_amount`, `agency_cgst_amount`, `agency_sgst_amount`, `inserted_on`, `weblink_id`) VALUES ('$user_ids','$transaction_id','$enduser_id','$payment_id','$status','$gst_number','$paid_amount','$sgst_cgst_paid','$sgst_cgst_paid','$total_miamount','$sgst_cgst_mitax','$sgst_cgst_mitax','$total_clientamount','$sgst_cgst_clienttax','$sgst_cgst_clienttax','$system_date_time','$weblink_id')";

  
    // Execute the statement
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
                $path = savePDF1($output_pdf, $enduser_id, $agency_id);


                    $update_wallet = "UPDATE `end_user_payment_transaction_all` SET `invoice_url`='$path' WHERE   `weblink_id`='$weblink_id' AND `end_user_id`='$user_ids' AND `paid_by`='$enduser_id'";
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
                                      <a href="https://mounarchtech.com/vocoxp/verification.php?end_user_id=<?Php echo$enduser_id;?>" class="btn btn-success">Now verify your details</a>
                                </div>
                                
                                
                            </div> 
                      
                     
                    </div>
            </div>
               <?php
                
                }
                 else
                  {
                    echo"someting error";
            }
     }
} 
else 
{
    echo json_encode(['status' => 'error', 'message' => 'Payment not successful.']);
}
?>
<!-- <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">Payment Verify</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                   Payment Successfully verify
                </div>
                <div class="modal-footer">
                    <a href="https://mounarchtech.com/vocoxp/verification.php?end_user_id=END-00190" class="btn btn-success">Go To Dashboard</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div> -->
    <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">Modal Title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Modal body content goes here.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</body>

</html>  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <?php

    function savePDF1($output_pdf, $direct_id, $agency_id)
{
    // FTP server credentials
    $ftp_server = '199.79.62.21';
    $ftp_username = 'centralwp@mounarchtech.com';
    $ftp_password = 'k=Y#oBK{h}OU';

    // Remote directory path
    $remote_base_dir = "/verification_data/voco_xp/";

    // Nested directory structure to be created
    $new_directory_path = "$agency_id/$direct_id/invoice/";

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
    $base_url = "https://mounarchtech.com/central_wp/verification_data/voco_xp/$agency_id/$direct_id/invoice";
    $path = $base_url . '/' . $file_name;
    return $path;
}
    ?>
