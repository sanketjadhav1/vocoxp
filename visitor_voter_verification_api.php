
<?php
// voter verification api (check data from temp table & match wih original details)
// check & update wallet balance according to payment type
// save visitor_voter_details_all for ambiguity details(e.g name@original name!temp name)
// // create report pdf & save it in pdf_path in report_url
ini_set('display_errors', 1);
date_default_timezone_set('Asia/kolkata');

require_once __DIR__ . '/vendor/autoload.php';

include 'connection.php';

$connection = connection::getInstance();
$mysqli = $connection->getConnection();

$connection1 = database::getInstance();
$mysqli1 = $connection1->getConnection();


date_default_timezone_set('Asia/kolkata');
$system_date = date("y-m-d");
$system_datetime = date('Y-m-d H:i:s');

$visitor_id  = $_POST['visitor_id'] ?? '';
$agency_id  = $_POST['agency_id'] ?? '';
$current_wallet_bal = 0;
$response_arr = array();

$check_error_res = check_error($mysqli, $mysqli1, $visitor_id, $agency_id);


if ($check_error_res == 1) {
    //get visitor details
    $visitor_temp_detail_all = "SELECT * FROM `visitor_temp_activity_detail_all` WHERE `visitor_id` = '$visitor_id' AND `agency_id` = '$agency_id'";
    $res_visitor_detail = mysqli_query($mysqli, $visitor_temp_detail_all);
    $visitor_temp_detail_arr = mysqli_fetch_assoc($res_visitor_detail);

    $agency_id = $visitor_temp_detail_arr['agency_id'];
    $emp_id = $visitor_temp_detail_arr['meeting_with'];
    $voter_number = $visitor_temp_detail_arr['voter_number'];
    $visitor_location_id = $visitor_temp_detail_arr['visitor_location_id'];
    $verification_id = $visitor_temp_detail_arr['verification_type']; //'DVF-00005'-voter ID


    if (empty($voter_number)) {
        $response = ["error_code" => 108, "message" => "voter_number can not be empty"];
        echo json_encode($response);
        return;
    }

    //get payment details of employee
    $employee_header_all = "SELECT `verification_paid_by` FROM `employee_header_all` WHERE `emp_id` = '$emp_id' AND `agency_id` = '$agency_id'";
    $res_employee_detail = mysqli_query($mysqli, $employee_header_all);
    $employee_detail_arr = mysqli_fetch_assoc($res_employee_detail);


    try {
        if (!empty($employee_detail_arr) && $employee_detail_arr['verification_paid_by'] == 'W') {  //check current wallet balance if payment from wallet and update it
            $fetch_wallet = "SELECT `current_wallet_bal` FROM `agency_header_all` WHERE `agency_id`='$agency_id'";
            $res_wallet = mysqli_query($mysqli, $fetch_wallet);
            $arr_wallet = mysqli_fetch_assoc($res_wallet);
           
            //get total verification rate & gst 
            $verify_query = "SELECT * FROM verification_configuration_all WHERE verification_id='$verification_id' AND ver_type='1' AND operational_status='1'";
            $verify_res = $mysqli1->query($verify_query);
            $verify_row = $verify_res->fetch_assoc();

            $sgst_percentage = $verify_row["sgst_percentage"];
            $cgst_percentage = $verify_row["cgst_percentage"];
            $verify_rate = $verify_row["rate"];
            $total_gst = $sgst_percentage + $cgst_percentage;
            $total_verify_rate = ($total_gst) + ($verify_rate);
            $verify_sgst_amount = ($verify_rate * $sgst_percentage) / 100;
            $verify_cgst_amount = ($verify_rate * $cgst_percentage) / 100;

            //get total verification rate according to location
            $location_setting_query = "SELECT `verification_amt` FROM visitor_location_setting_details_all WHERE agency_id='$agency_id' AND visitor_location_id= $visitor_location_id";
            $location_setting_res = $mysqli1->query($location_setting_query);
            $location_setting_row = $location_setting_res->fetch_assoc();

            $location_ver_amt = 0;
            $loc_sgst_amount = 0;
            $loc_cgst_amount = 0;
            $total_loc_rate = 0;
            if (isset($location_setting_row['verification_amt']) && $location_setting_row['verification_amt'] != '') {
                $location_ver_amt = $location_setting_row['verification_amt'];
                $total_loc_rate = $total_gst + $location_ver_amt;
                $loc_sgst_amount = ($location_ver_amt * $sgst_percentage) / 100;
                $loc_cgst_amount = ($location_ver_amt * $cgst_percentage) / 100;
            }

            $total_sgst_amount = $verify_sgst_amount + $loc_sgst_amount;
            $total_cgst_amount = $verify_cgst_amount + $loc_cgst_amount;
            $total_amount = $total_verify_rate + $total_loc_rate;
            $base_amount = $verify_rate + $location_ver_amt;

            if ($arr_wallet['current_wallet_bal'] < $total_amount) {
                $responce = ["error_code" => 113, "message" => "Your wallet balance is too Low To proceed. Please recharge your wallet."];
                echo json_encode($responce);
                return;
            }

            $current_wallet_bal = $arr_wallet['current_wallet_bal'] - $total_amount;

            //update current wallet balance
            $update_wallet = "UPDATE `agency_header_all` SET `current_wallet_bal` = '$current_wallet_bal' WHERE `agency_id` = '$agency_id'";
            $updatesqlQuery =  $mysqli->query($update_wallet);
            if (!$updatesqlQuery) {
                throw new Exception("Failed to Update into agency_header_all");
            }

            //insert wallet payment transaction
            $wallet_trans_query = "INSERT INTO `wallet_payment_transaction_all` (`agency_id`,`user_id`,`requested_from`,`purchase_type`,`verification_id`,`base_amount`,`cgst_amount`,`sgst_amount`,`transaction_on`,`transaction_id`,`line_type`,`quantity`,`settled_for`,`ref_transaction_id`) VALUES ('$agency_id','$visitor_id',4,1,'$verification_id', '$base_amount', '$total_cgst_amount','$total_sgst_amount', '$system_datetime', '',1,0,'','')";

            $insert_wallet_trans =  $mysqli->query($wallet_trans_query);
            if (!$insert_wallet_trans) {
                throw new Exception("Failed to Insert into wallet_payment_transaction_all");
            }
        }

        //get voter id card data for verification
        $verification_data = json_decode(verify_voter_id($voter_number), true);

        if ($verification_data['data']['code'] != 1000 && $verification_data['status'] != 200) {
            $responce = ["error_code" => 199, "message" => "Voter number is invalid. Please provide the valid voter number"];
            echo json_encode($responce);
            return;
        }
        $original_voter_details = $verification_data['data']['voter_data'];
        if (empty($original_voter_details)) {
            $responce = ["error_code" => 404, "message" => "Original voter id data is not found."];
            echo json_encode($responce);
            return;
        }

        $ver_voter_name_arr = explode(" ", strtoupper($original_voter_details['name']));
        $temp_voter_name_arr = explode(" ", strtoupper(trim($visitor_temp_detail_arr['visitor_name'], " ")));

        $name_arr_cnt = 0;
        $name_match_cnt = 0;
        if (sizeof($ver_voter_name_arr) > sizeof($temp_voter_name_arr)) {
            $name_arr_cnt = sizeof($ver_voter_name_arr);
        } else {
            $name_arr_cnt = sizeof($temp_voter_name_arr);
        }
        foreach ($ver_voter_name_arr as $val) {
            if (in_array($val, $temp_voter_name_arr)) {
                $name_match_cnt++;
            }
        }

        $is_name_match = ($name_match_cnt == $name_arr_cnt) ? "Match" : 'Not Match';
        $is_father_name_match = 'Match';
        $is_gender_match = 'Match';
        $is_state_match = 'Match';

        $ambiguity_details = '';
        $match_all = 1;  //default consider as all details are matched & 0 = any ot them is not matched.
        $activity_status = 1;   //1 = verified  
        $color = 'green';
        $father_name_color = 'green';
        $gender_color = 'green';
        $state_color = 'green';

        if ($is_name_match == 'Not Match') {
            $ambiguity = 'name@' . $original_voter_details['name'] . '!' . $visitor_temp_detail_arr['visitor_name'];
            $ambiguity_details = $ambiguity;
            $match_all = 0;
            $color = 'red';
        }
        if ($original_voter_details['father_name'] != $visitor_temp_detail_arr['father_name']) {
            $ambiguity = 'father_name@' . $original_voter_details['father_name'] . '!' . $visitor_temp_detail_arr['father_name'];
            $ambiguity_details .= ($ambiguity_details != '') ? ',' . $ambiguity : $ambiguity;
            $match_all = 0;
            $father_name_color = 'red';
            $is_father_name_match = 'Not Match';
        }
        if ($original_voter_details['gender'] != $visitor_temp_detail_arr['gender']) {
            $ambiguity = 'gender@' . $original_voter_details['gender'] . '!' . $visitor_temp_detail_arr['gender'];
            $ambiguity_details .= ($ambiguity_details != '') ? ',' . $ambiguity : $ambiguity;
            $match_all = 0;
            $gender_color = 'red';
            $is_gender_match = 'Not Match';
        }

        if ($original_voter_details['state'] != $visitor_temp_detail_arr['state_name']) {
            $ambiguity = 'state_name@' . $original_voter_details['state'] . '!' . $visitor_temp_detail_arr['state_name'];
            $ambiguity_details .= ($ambiguity_details != '') ? ',' . $ambiguity : $ambiguity;
            $match_all = 0;
            $state_color = 'red';
            $is_state_match = 'Not Match';
        }

        if ($match_all == 1) {    //if all details are matched
            $activity_status = 2;   // not all verrified
            $ambiguity_details = "ok=all";   //all details are matched
        }


        /* create & save pdf start */
        $html_pdf = '<html>
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
                    <h3 id="header">VOTER ID VERIFICATION REPORT</h3>
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
        <td scope="col"><span style="font-weight: bold;">Voter Number: </span>' . $visitor_temp_detail_arr['voter_number'] . '</td>
        <td scope="col"><span style="font-weight: bold;"> Name:</span> ' . $visitor_temp_detail_arr['visitor_name'] . '
        </td>
    </tr>
    <tr>
        <td scope="col"><span style="font-weight: bold;">Father Name: </span>' . $visitor_temp_detail_arr['father_name'] . '</td>
        <td scope="col"><span style="font-weight: bold;"> Gender:</span> ' . $visitor_temp_detail_arr['gender'] . '
        </td>
    </tr>
    <tr>
        <td scope="col"><span style="font-weight: bold;">State:</span> ' . $visitor_temp_detail_arr['state_name'] . '</td>
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
            <img src="' . $visitor_temp_detail_arr['front_photo'] . '" alt="Placeholder image" width="30%" />
        </td>
        <td scope="col" align="center">
            <p style="text-align: center; font-size:15px;">Back Image</p>
            <br>
            <img src="' . $visitor_temp_detail_arr['back_photo'] . '" alt="Placeholder image" width="30%" />
        </td>
    </tr>
 
</table>
<br><br>

<h1 id="first" ><u>Result</u></h1>

<p style="font-weight: bold;">Report Summary</p>

<span style="font-weight: bold;">This Report Information Genrated Against Voter Number: </span>' . $original_voter_details['document_id'] . '  


<table style=" width:100%; border-collapse: collapse;" id="table">
<tr>
    <td scope="col" style="width:50%"><span style="font-weight: bold;">Voter Number:</span> ' .     $original_voter_details['document_id'] . '</td>
        
    <td scope="col" style="width:50%"><span style="font-weight: bold;"> Name:</span> ' . strtoupper($original_voter_details['name']) . '<br><span style="color:' . $color . ';">' .     $is_name_match . '</span></td>
    </tr>
<tr>
<tr>
   <td scope="col" style="width:50%"><span style="font-weight: bold;"> Father Name:</span> ' . strtoupper($original_voter_details['father_name']) . '<br><span style="color:' . $father_name_color . ';">' .     $is_father_name_match . '</span></td>
        
    <td scope="col" style="width:50%"><span style="font-weight: bold;"> Gender:</span> ' . strtoupper($original_voter_details['gender']) . '<br><span style="color:' . $gender_color . ';">' .     $is_gender_match . '</span></td>
    </tr>
<tr>
<tr>
   <td scope="col" style="width:50%"><span style="font-weight: bold;"> State:</span> ' . strtoupper($original_voter_details['state']) . '<br><span style="color:' . $state_color . ';">' .     $is_state_match . '</span></td>
    </tr>
<tr>
</table>

<hr>
<p style="margin-top:10px;"><span style="color:red;">*</span>Please note that the contents shown above is provided in good faith, we do not warrant that the information will be kept up to date, be true, accurate and not misleading.</p>

    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

  </body>
</html>';

        function saveVoterIdCardPDF($output_pdf, $visitor_id, $agency_id, $emp_id)
        {
            // FTP server credentials
            $ftp_server = '199.79.62.21';
            $ftp_username = 'centralwp@mounarchtech.com';
            $ftp_password = 'k=Y#oBK{h}OU';

            // Remote directory path
            $remote_base_dir = "/verification_data/voco_xp/";

            // Nested directory structure to be created
            $new_directory_path = "$agency_id/visitor/$emp_id/voter_id_card_report/";

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
            $file_name = $visitor_id . '.pdf';

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
            $base_url = "https://mounarchtech.com/central_wp/verification_data/voco_xp/$agency_id/visitor/$emp_id/voter_id_card_report";
            $path = $base_url . '/' . $file_name;
            return $path;
        }

        // Initialize mPDF
        $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4']);
        // Write HTML content to mPDF
        $mpdf->WriteHTML($html_pdf);
        // Output PDF to a variable
        $output_pdf = $mpdf->Output('', 'S');

        // Save PDF to a file
        $pdf_path = saveVoterIdCardPDF($output_pdf, $visitor_id, $agency_id, $emp_id);
        // Define response array
        $response = array();

        // Check if the path is valid
        if ($pdf_path !== false) {
            // PDF generated successfully
            $response['error_code'] = 100;
            $response['message'] = 'PDF generated successfully.';
            $response['pdf_url'] = $pdf_path;
            $data = [
                "path" => $pdf_path,
                "for" => 'voter_id_card_report',
                "agency_id" => $agency_id,
                "visitor_id" => $visitor_id,
                "emp_id" => $emp_id,
                "current_wallet_bal" => number_format($current_wallet_bal, 2, '.', '')
            ];

            $url = get_base_url() . '/new_visitor.php';

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
        } else {
            // PDF generation failed
            $response['success'] = false;
            $response['message'] = 'Failed to generate PDF.';
        }

        /* create & save pdf end */

        $insert_voter_query = "INSERT INTO `visitor_voter_details_all` (`visitor_id`,`agency_id`,`initiated_on`,`completed_on`,`activity_status`,`voter_number`,`name`,`dob`,`gender`,`father_name`,`address`,`polling_details`,`user_photo`,`front_photo`,`back_photo`,`generated_by`,`is_athenticate`,`voter_ambiguity`,`report_url`) VALUES ('$visitor_id','$agency_id','$system_datetime','$system_datetime','$activity_status','{$visitor_temp_detail_arr['voter_number']}', '{$visitor_temp_detail_arr['visitor_name']}','{$visitor_temp_detail_arr['dob']}','{$visitor_temp_detail_arr['gender']}','{$visitor_temp_detail_arr['father_name']}','{$visitor_temp_detail_arr['address']}','{$visitor_temp_detail_arr['polling_details']}','{$visitor_temp_detail_arr['user_photo']}','{$visitor_temp_detail_arr['front_photo']}','{$visitor_temp_detail_arr['back_photo']}','{$visitor_temp_detail_arr['mode']}','1','$ambiguity_details','$pdf_path')";

        $insert_voter_detail =  $mysqli->query($insert_voter_query);
        if (!$insert_voter_detail) {
            throw new Exception("Failed to Insert into visitor_voter_details_all");
        }


        $mysqli->commit();
        $response_arr[] = array("error_code" => 100, "message" => "Records Inserted successfully");
    } catch (Exception $e) {
        $mysqli->rollback();
        $response_arr[] = array("error_code" => 103, "message" => $e->getMessage());
    }
}



/* check errors*/
function check_error($mysqli, $mysqli1, $visitor_id, $agency_id)
{
    $check_error = 1;
    if (!$mysqli || !$mysqli1) {
        $response = ["error_code" => 101, "message" => "Unable to proceed your request, please try again after some time"];
        echo json_encode($response);
        return;
    }
    if ($_SERVER['REQUEST_METHOD'] != "POST") {
        $response = array("error_code" => 102, "message" => "Request Method is not Post");
        echo json_encode($response);
        return;
    }
    if (empty($visitor_id)) {
        $response = ["error_code" => 106, "message" => "visitor_id can not be empty"];
        echo json_encode($response);
        return;
    }
    if (empty($agency_id)) {
        $response = ["error_code" => 106, "message" => "agency_id can not be empty"];
        echo json_encode($response);
        return;
    }

    return $check_error;
}


/* get voter id card details on pan no. */
function verify_voter_id($voter_id_no)
{
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => "https://api.gridlines.io/voter-api/boson/fetch",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode([
            'voter_id' => $voter_id_no,
            'consent' => 'Y'
        ]),
        CURLOPT_HTTPHEADER => [
            "Accept: application/json",
            "Content-Type: application/json",
            "X-API-Key: C8EbVBaNqR4g3vhBAiPXdt8cLPkNLJoL",
            "X-Auth-Type: API-Key"
        ],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        return $err;
    } else {
        return $response;
    }
}
