<!-- passport verification api (check data from temp table & match wih original details)
check & update wallet balance according to payment type
save visitor_passport_details_all for ambiguity details(e.g name@original name!temp name)
create report pdf & save it in pdf_path in report_url -->
<?php
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
    $passport_number = $visitor_temp_detail_arr['passport_number'];
    $first_name = $visitor_temp_detail_arr['first_name'];
    $last_name = $visitor_temp_detail_arr['last_name'];
    $dob = $visitor_temp_detail_arr['dob'];
    $file_number = $visitor_temp_detail_arr['file_number'];
    $visitor_location_id = $visitor_temp_detail_arr['visitor_location_id'];
    $verification_id = $visitor_temp_detail_arr['verification_type']; //'DVF-00006'-passport

    if (empty($passport_number)) {
        $response = ["error_code" => 108, "message" => "passport_number can not be empty"];
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

            if ($arr_wallet['current_wallet_bal'] < $total_amount) {
                $responce = ["error_code" => 113, "message" => "Your wallet balance is too Low To proceed. Please recharge your wallet."];
                echo json_encode($responce);
                return;
            }

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

        $dob = date("Y-m-d", strtotime($dob));
        $verification_data = json_decode(verify_passport_id($passport_number, $last_name, $first_name, $dob, $file_number), true);

        if ($verification_data['data']['code'] == 1005) {
            $responce = ["error_code" => 199, "message" => "Passport number is invalid. Please provide the valid passport number"];
            echo json_encode($responce);
            return;
        }
        $original_passport_details = $verification_data['data']['passport_data'];
        if (empty($original_passport_details)) {
            $responce = ["error_code" => 404, "message" => "Original passport data is not found."];
            echo json_encode($responce);
            return;
        }

        $ver_passport_name_arr = explode(" ", strtoupper($original_passport_details['first_name']));
        $temp_passport_name_arr = explode(" ", strtoupper(trim($visitor_temp_detail_arr['visitor_name'], " ")));

        $name_arr_cnt = 0;
        $name_match_cnt = 0;
        if (sizeof($ver_passport_name_arr) > sizeof($temp_passport_name_arr)) {
            $name_arr_cnt = sizeof($ver_passport_name_arr);
        } else {
            $name_arr_cnt = sizeof($temp_passport_name_arr);
        }
        foreach ($ver_passport_name_arr as $val) {
            if (in_array($val, $temp_passport_name_arr)) {
                $name_match_cnt++;
            }
        }

        $is_first_name_match = 'Match';
        $is_last_name_match = 'Match';
        $is_dob_match = 'Match';
        $is_issue_date_match = 'Match';
        $is_file_number_match = 'Match';

        $ambiguity_details = '';
        $match_all = 1;  //default consider as all details are matched & 0 = any ot them is not matched.
        $activity_status = 1;   //1 = verified  
        $first_name_color = 'green';
        $last_name_color = 'green';
        $dob_color = 'green';
        $issue_date_color = 'green';
        $file_number_color = 'green';

        if ($original_passport_details['first_name'] != $visitor_temp_detail_arr['first_name']) {
            $ambiguity = 'first_name@' . $original_passport_details['first_name'] . '!' . $visitor_temp_detail_arr['first_name'];
            $ambiguity_details = $ambiguity;
            $match_all = 0;
            $first_name_color = 'red';
            $is_first_name_match = 'Not Match';
        }
        if ($original_passport_details['last_name'] != $visitor_temp_detail_arr['last_name']) {
            $ambiguity = 'last_name@' . $original_passport_details['last_name'] . '!' . $visitor_temp_detail_arr['last_name'];
            $ambiguity_details .= ($ambiguity_details != '') ? ',' . $ambiguity : $ambiguity;
            $match_all = 0;
            $last_name_color = 'red';
            $is_last_name_match = 'Not Match';
        }
        if ($original_passport_details['date_of_birth'] != $visitor_temp_detail_arr['dob']) {
            $ambiguity = 'dob@' . $original_passport_details['date_of_birth'] . '!' . $visitor_temp_detail_arr['dob'];
            $ambiguity_details .= ($ambiguity_details != '') ? ',' . $ambiguity : $ambiguity;
            $match_all = 0;
            $dob_color = 'red';
            $is_dob_match = 'Not Match';
        }
        $formated_date_of_issue = date("Y-m-d", strtotime($visitor_temp_detail_arr['date_of_issue']));
        if ($original_passport_details['issue_date'] != $formated_date_of_issue) {
            $ambiguity = 'date_of_issue@' . $original_passport_details['issue_date'] . '!' . $formated_date_of_issue;
            $ambiguity_details .= ($ambiguity_details != '') ? ',' . $ambiguity : $ambiguity;
            $match_all = 0;
            $issue_date_color = 'red';
            $is_issue_date_match = 'Not Match';
        }
        if ($original_passport_details['file_number'] != $visitor_temp_detail_arr['file_number']) {
            $ambiguity = 'file_number@' . $original_passport_details['file_number'] . '!' . $visitor_temp_detail_arr['file_number'];
            $ambiguity_details .= ($ambiguity_details != '') ? ',' . $ambiguity : $ambiguity;
            $match_all = 0;
            $file_number_color = 'red';
            $is_file_number_match = 'Not Match';
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
                    <h3 id="header"> PASSPORT ID VERIFICATION REPORT</h3>
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
        <td scope="col"><span style="font-weight: bold;">Passport Number: </span>' . $visitor_temp_detail_arr['passport_number'] . '</td>
        <td scope="col"><span style="font-weight: bold;"> Name:</span> ' . $visitor_temp_detail_arr['first_name']  . '
        </td>
    </tr>
    <tr>
        <td scope="col"><span style="font-weight: bold;">Date Of Birth:</span> ' . date("d-m-Y", strtotime($visitor_temp_detail_arr['dob'])) . '</td>
        <td scope="col"><span style="font-weight: bold;">Surname:</span> ' . $visitor_temp_detail_arr['last_name']  . '
        </td>
  </tr> 
  <tr>
        <td scope="col"><span style="font-weight: bold;">File Number:</span> ' . $visitor_temp_detail_arr['file_number'] . '</td>
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

<span style="font-weight: bold;">This Report Information Genrated Against Passport Number: </span>' . $original_passport_details['document_id'] . ' 


<table style=" width:100%; border-collapse: collapse;" id="table">
<tr>
    <td scope="col" style="width:50%"><span style="font-weight: bold;">Passport Number:</span> ' .  $original_passport_details['document_id'] . '</td>

    <td scope="col" style="width:50%"><span style="font-weight: bold;"> Name:</span> ' . strtoupper($original_passport_details['first_name']) . '<br><span style="color:' . $first_name_color .     ';">' . $is_first_name_match . '</span></td>
</tr>
<tr>
    <td scope="col" style="width:50%"><span style="font-weight: bold;">Date Of Birth:</span> ' . date("d-m-Y", strtotime($original_passport_details['date_of_birth'])) . '<br><span style="color:' . $dob_color . ';">' .     $is_dob_match . '</span></td>

    <td scope="col" style="width:50%"><span style="font-weight: bold;"> Surname:</span> ' . strtoupper($original_passport_details['last_name']) . '<br><span style="color:' . $last_name_color . ';    ">' . $is_issue_date_match . '</span></td>
</tr>
<tr>
    <td scope="col" style="width:50%"><span style="font-weight: bold;">File Number:</span> ' .    $original_passport_details['file_number'] . '<br><span style="color:' . $file_number_color . ';">' .     $is_file_number_match . '</span></td>

</tr>
</table>

<hr>
<p style="margin-top:10px;"><span style="color:red;">*</span>Please note that the contents shown above is provided in good faith, we do not warrant that the information will be kept up to date, be true, accurate and not misleading.</p>

    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

  </body>
</html>';

        function savePAssportPDF($output_pdf, $visitor_id, $agency_id, $emp_id)
        {
            // FTP server credentials
            $ftp_server = '199.79.62.21';
            $ftp_username = 'centralwp@mounarchtech.com';
            $ftp_password = 'k=Y#oBK{h}OU';

            // Remote directory path
            $remote_base_dir = "/verification_data/voco_xp/";

            // Nested directory structure to be created
            $new_directory_path = "$agency_id/visitor/$emp_id/passport_no_report/";

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
            $base_url = "https://mounarchtech.com/central_wp/verification_data/voco_xp/$agency_id/visitor/$emp_id/passport_no_report";
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
        $pdf_path = savePAssportPDF($output_pdf, $visitor_id, $agency_id, $emp_id);
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
                "for" => 'passport_no_report',
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

        $insert_passport_query = "INSERT INTO `visitor_passport_details_all` (`visitor_id`,`agency_id`,`initiated_on`,`completed_on`,`activity_status`,`passport_number`,`surname`,`name`,`gender`,`dob`,`place_of_birth`,`father_name`,`mother_name`,`spouse_name`,`address`, `republic_of_india`,`date_of_issue`,`date_of_expiry`,`place_of_issue`,`country_code`,`nationality`,`passport_type`,`file_number`,`cover_photo`,`user_photo`,`front_photo`,`back_photo`,`generated_by`,`is_athenticate`,`passport_ambiguity`,`report_url`) VALUES ('$visitor_id','$agency_id','$system_datetime','$system_datetime','$activity_status','{$visitor_temp_detail_arr['passport_number']}', '{$visitor_temp_detail_arr['last_name']}','{$visitor_temp_detail_arr['first_name']}','{$visitor_temp_detail_arr['gender']}','{$visitor_temp_detail_arr['dob']}','','{$visitor_temp_detail_arr['father_name']}','{$visitor_temp_detail_arr['mother_name']}','{$visitor_temp_detail_arr['spouse_name']}','{$visitor_temp_detail_arr['address']}','{$visitor_temp_detail_arr['republic_of_india']}','{$visitor_temp_detail_arr['date_of_issue']}','{$visitor_temp_detail_arr['date_of_expiry']}','{$visitor_temp_detail_arr['place_of_issue']}','{$visitor_temp_detail_arr['country_code']}','{$visitor_temp_detail_arr['nationality']}','{$visitor_temp_detail_arr['passport_type']}','{$visitor_temp_detail_arr['file_number']}','{$visitor_temp_detail_arr['cover_photo']}','{$visitor_temp_detail_arr['user_photo']}','{$visitor_temp_detail_arr['front_photo']}','{$visitor_temp_detail_arr['back_photo']}','{$visitor_temp_detail_arr['mode']}','1','$ambiguity_details','$pdf_path')";

        $insert_passport_detail =  $mysqli->query($insert_passport_query);
        if (!$insert_passport_detail) {
            throw new Exception("Failed to Insert into visitor_passport_details_all");
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
        $response = ["error_code" => 10, "message" => "visitor_id can not be empty"];
        echo json_encode($response);
        return;
    }
    if (empty($agency_id)) {
        $response = ["error_code" => 10, "message" => "agency_id can not be empty"];
        echo json_encode($response);
        return;
    }

    return $check_error;
}


/* get passport id details*/
function verify_passport_id($passport_number, $last_name, $first_name, $dob, $file_number)
{
    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => "https://api.gridlines.io/passport-api/fetch",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode([
            'passport_number' => $passport_number,
            'surname' => $last_name,
            'given_name' => $first_name,
            'file_number' => $file_number,
            'date_of_birth' => $dob,
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
        return "cURL Error #:" . $err;
    } else {
        return $response;
    }
}
