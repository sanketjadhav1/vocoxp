<!-- dl verification api (check data from temp table & match wih original details)
check & update wallet balance according to payment type
save visitor_dl_details_all for ambiguity details(e.g name@original name!temp name)
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

$base_amount = $_POST['base_amount'] ?? 0;
$sgst_amount = $_POST['sgst_amount'] ?? 0;
$cgst_amount = $_POST['cgst_amount'] ?? 0;
$agency_id = $_POST['agency_id'];
$visitor_id  = $_POST['visitor_id'] ?? '';
$dl_number  = $_POST['dl_number'] ?? '';
$dob  = $_POST['dob'] ?? '';

$verification_id = 'DVF-00004';   //drvining liscen
$current_wallet_bal = 0;
$response_arr = array();

$total_amount = $base_amount + $sgst_amount + $cgst_amount;

$check_error_res = check_error($mysqli, $mysqli1, $agency_id, $visitor_id, $dob, $dl_number);


if ($check_error_res == 1) {
    //get visitor details
    $visitor_temp_detail_all = "SELECT * FROM `visitor_temp_activity_detail_all` WHERE `visitor_id` = '$visitor_id'";
    $res_visitor_detail = mysqli_query($mysqli, $visitor_temp_detail_all);
    $visitor_temp_detail_arr = mysqli_fetch_assoc($res_visitor_detail);

    $agency_id = $visitor_temp_detail_arr['agency_id'];
    $emp_id = $visitor_temp_detail_arr['meeting_with'];

    //get payment details of employee
    $employee_header_all = "SELECT `verification_paid_by` FROM `employee_header_all` WHERE `emp_id` = '$emp_id'";
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
            $rate = $verify_row["rate"];
            $gst = $sgst_percentage + $cgst_percentage;
            $total_gst = ($rate * $gst) / 100;
            $total_rate = ($total_gst * 1) + ($rate * 1);
            $sgst_amount = ($rate * $sgst_percentage) / 100;
            $cgst_amount = ($rate * $cgst_percentage) / 100;

            $current_wallet_bal = $arr_wallet['current_wallet_bal'] - $total_rate;

            //update current wallet balance
            $update_wallet = "UPDATE `agency_header_all` SET `current_wallet_bal` = '$current_wallet_bal' WHERE `agency_id` = '$agency_id'";
            $updatesqlQuery =  $mysqli->query($update_wallet);
            if (!$updatesqlQuery) {
                throw new Exception("Failed to Update into agency_header_all");
            }

            //insert wallet payment transaction
            $wallet_trans_query = "INSERT INTO `wallet_payment_transaction_all` (`agency_id`,`user_id`,`requested_from`,`purchase_type`,`verification_id`,`base_amount`,`cgst_amount`,`sgst_amount`,`transaction_on`,`transaction_id`,`line_type`,`quantity`,`settled_for`,`ref_transaction_id`) VALUES ('$agency_id','$visitor_id',4,1,'$verification_id', '$rate', '$cgst_amount','$sgst_amount', '$system_datetime', '',1,0,'','')";

            $insert_wallet_trans =  $mysqli->query($wallet_trans_query);
            if (!$insert_wallet_trans) {
                throw new Exception("Failed to Insert into wallet_payment_transaction_all");
            }
        }

        $dob = date("Y-m-d", strtotime($dob));
        $verification_data = json_decode(verify_dl_id($dl_number, $dob), true);


        if ($verification_data['data']['code'] == 1001) {
            $responce = ["error_code" => 199, "message" => "dl number is invalid. Please provide the valid dl number"];
            echo json_encode($responce);
            return;
        }
        $original_dl_details = $verification_data['data']['driving_license_data'];

        $ver_dl_name_arr = explode(" ", strtoupper($original_dl_details['name']));
        $temp_dl_name_arr = explode(" ", strtoupper(trim($visitor_temp_detail_arr['visitor_name'], " ")));

        $name_arr_cnt = 0;
        $name_match_cnt = 0;
        if (sizeof($ver_dl_name_arr) > sizeof($temp_dl_name_arr)) {
            $name_arr_cnt = sizeof($ver_dl_name_arr);
        } else {
            $name_arr_cnt = sizeof($temp_dl_name_arr);
        }
        foreach ($ver_dl_name_arr as $val) {
            if (in_array($val, $temp_dl_name_arr)) {
                $name_match_cnt++;
            }
        }

        $is_name_match = ($name_match_cnt == $name_arr_cnt) ? "Match" : 'Not Match';
        $is_dob_match = 'Match';
        $is_issue_date_match = 'Match';
        $is_address_match = 'Match';
        $is_expiry_date_match = 'Match';
        $is_blood_group_match = 'Match';

        $ambiguity_details = '';
        $match_all = 1;  //default consider as all details are matched & 0 = any ot them is not matched.
        $activity_status = 1;   //1 = verified  
        $name_color = 'green';
        $last_name_color = 'green';
        $dob_color = 'green';
        $issue_date_color = 'green';
        $address_color = 'green';
        $expiry_date_color = 'green';
        $blood_group_color = 'green';

        if ($is_name_match == 'Not Match') {
            $ambiguity = 'name@' . $original_dl_details['name'] . '!' . $visitor_temp_detail_arr['visitor_name'];
            $ambiguity_details = $ambiguity;
            $match_all = 0;
            $name_color = 'red';
        }
        if ($original_dl_details['date_of_birth'] != $visitor_temp_detail_arr['dob']) {
            $ambiguity = 'dob@' . $original_dl_details['date_of_birth'] . '!' . $visitor_temp_detail_arr['dob'];
            $ambiguity_details .= ($ambiguity_details != '') ? ',' . $ambiguity : $ambiguity;
            $match_all = 0;
            $dob_color = 'red';
            $is_dob_match = 'Not Match';
        }
        if ($original_dl_details['address'] != $visitor_temp_detail_arr['address']) {
            $ambiguity = 'address@' . $original_dl_details['address'] . '!' . $visitor_temp_detail_arr['address'];
            $ambiguity_details .= ($ambiguity_details != '') ? ',' . $ambiguity : $ambiguity;
            $match_all = 0;
            $address_color = 'red';
            $is_address_match = 'Not Match';
        }

        $formated_date_of_issue = date("Y-m-d", strtotime($visitor_temp_detail_arr['date_of_issue']));
        if ($original_dl_details['validity']['non_transport']['issue_date'] != $formated_date_of_issue) {
            $ambiguity = 'date_of_issue@' . $original_dl_details['validity']['non_transport']['issue_date'] . '!' . $formated_date_of_issue;
            $ambiguity_details .= ($ambiguity_details != '') ? ',' . $ambiguity : $ambiguity;
            $match_all = 0;
            $issue_date_color = 'red';
            $is_issue_date_match = 'Not Match';
        }

        $formated_date_of_expiry = date("Y-m-d", strtotime($visitor_temp_detail_arr['date_of_expiry']));
        if ($original_dl_details['validity']['non_transport']['expiry_date'] != $formated_date_of_expiry) {
            $ambiguity = 'date_of_expiry@' . $original_dl_details['validity']['non_transport']['expiry_date'] . '!' . $formated_date_of_expiry;
            $ambiguity_details .= ($ambiguity_details != '') ? ',' . $ambiguity : $ambiguity;
            $match_all = 0;
            $expiry_date_color = 'red';
            $is_expiry_date_match = 'Not Match';
        }
        if ($original_dl_details['blood_group'] != $visitor_temp_detail_arr['blood_group']) {
            $ambiguity = 'blood_group@' . $original_dl_details['blood_group'] . '!' . $visitor_temp_detail_arr['blood_group'];
            $ambiguity_details .= ($ambiguity_details != '') ? ',' . $ambiguity : $ambiguity;
            $match_all = 0;
            $blood_group_color = 'red';
            $is_blood_group_match = 'Not Match';
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
                    <h3 id="header"> DL VERIFICATION REPORT</h3>
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
        <td scope="col"><span style="font-weight: bold;">DL Number: </span>' . $visitor_temp_detail_arr['dl_number'] . '</td>
        <td scope="col"><span style="font-weight: bold;"> Name:</span> ' . $visitor_temp_detail_arr['visitor_name']  . '
        </td>
    </tr>
    <tr>
        <td scope="col"><span style="font-weight: bold;">Date Of Birth:</span> ' . $visitor_temp_detail_arr['dob'] . '</td>
         <td scope="col"><span style="font-weight: bold;">Address: </span>' . $visitor_temp_detail_arr['address'] . '</td>
  </tr> 
  <tr>
        <td scope="col"><span style="font-weight: bold;">Date Of Issue:</span> ' . date("d-m-Y H:i:s", strtotime($visitor_temp_detail_arr['date_of_issue'])) . '</td>
         <td scope="col"><span style="font-weight: bold;">Date Of Expiry: </span>' . date("d-m-Y H:i:s", strtotime($visitor_temp_detail_arr['date_of_expiry'])) . '</td>
  </tr> 
  <tr>
        <td scope="col"><span style="font-weight: bold;">Blood Group:</span> ' . $visitor_temp_detail_arr['blood_group'] . '</td>
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
            <p style="text-align: center; font-size:15px;">Front Image</p>
            <br>
            <img src="' . $visitor_temp_detail_arr['back_photo'] . '" alt="Placeholder image" width="30%" />
        </td>
    </tr>
 
</table>
<br><br>

<h1 id="first" ><u>Result</u></h1>

<p style="font-weight: bold;">Report Summary</p>

<span style="font-weight: bold;">This Report Information Genrated Against DL Number: </span>' . $original_dl_details['document_id'] . ' 


<table style=" width:100%; border-collapse: collapse;" id="table">
<tr>
    <td scope="col" style="width:50%"><span style="font-weight: bold;">DL Number:</span> ' .  $original_dl_details['document_id'] . '</td>

    <td scope="col" style="width:50%"><span style="font-weight: bold;"> Name:</span> ' . strtoupper($original_dl_details['name']) . '<br><span style="color:' . $name_color .     ';">' . $is_name_match . '</span></td>
</tr>
<tr>
    <td scope="col" style="width:50%"><span style="font-weight: bold;">Date Of Birth:</span> ' .    $original_dl_details['date_of_birth'] . '<br><span style="color:' . $dob_color . ';">' .     $is_dob_match . '</span></td>

     <td scope="col" style="width:50%"><span style="font-weight: bold;">Address:</span> ' .    $original_dl_details['address'] . '<br><span style="color:' . $address_color . ';">' .     $is_address_match . '</span></td>
</tr>
<tr>
    <td scope="col" style="width:50%"><span style="font-weight: bold;">Date Of Issue:</span> ' .    $original_dl_details['validity']['non_transport']['issue_date'] . '<br><span style="color:' . $issue_date_color . ';">' .    $is_issue_date_match . '</span></td>

     <td scope="col" style="width:50%"><span style="font-weight: bold;"> Date Of Expiry:</span> ' .    $original_dl_details['validity']['non_transport']['expiry_date'] . '<br><span style="color:' . $expiry_date_color . ';">' .    $is_expiry_date_match . '</span></td>
</tr>
<tr>
    <td scope="col" style="width:50%"><span style="font-weight: bold;">Blood Group:</span> ' .    $original_dl_details['blood_group'] . '<br><span style="color:' . $blood_group_color . ';">' .    $is_blood_group_match . '</span></td>

     
</table>

<hr>
<p style="margin-top:10px;"><span style="color:red;">*</span>Please note that the contents shown above is provided in good faith, we do not warrant that the information will be kept up to date, be true, accurate and not misleading.</p>

    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

  </body>
</html>';
        function saveDLPDF($output_pdf, $visitor_id, $agency_id, $emp_id)
        {
            // FTP server credentials
            $ftp_server = '199.79.62.21';
            $ftp_username = 'centralwp@mounarchtech.com';
            $ftp_password = 'k=Y#oBK{h}OU';

            // Remote directory path
            $remote_base_dir = "/verification_data/voco_xp/";

            // Nested directory structure to be created
            $new_directory_path = "$agency_id/visitor/$emp_id/dl_report/";

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
            $base_url = "https://mounarchtech.com/central_wp/verification_data/voco_xp/$agency_id/visitor/$emp_id/dl_report";
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
        $pdf_path = saveDLPDF($output_pdf, $visitor_id, $agency_id, $emp_id);
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
                "for" => 'dl_report',
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

        $insert_dl_query = "INSERT INTO `visitor_dl_details_all` (`visitor_id`,`agency_id`,`initiated_on`,`completed_on`,`activity_status`,`dl_number`,`name`,`father_name`,`address`,`dob`,`date_of_expiry`,`date_of_issue`,`classes_of_vehicle`,`state_name`,`blood_group`,`user_photo`,`front_photo`,`back_photo`,`generated_by`,`is_athenticate`,`dl_ambiguity`,`report_url`) VALUES ('$visitor_id','$agency_id','$system_datetime','$system_datetime','$activity_status','{$visitor_temp_detail_arr['dl_number']}', '{$visitor_temp_detail_arr['visitor_name']}','{$visitor_temp_detail_arr['father_name']}','{$visitor_temp_detail_arr['address']}','{$visitor_temp_detail_arr['dob']}','{$visitor_temp_detail_arr['date_of_expiry']}','{$visitor_temp_detail_arr['date_of_issue']}','{$visitor_temp_detail_arr['classes_of_vehicle']}','{$visitor_temp_detail_arr['state_name']}','{$visitor_temp_detail_arr['blood_group']}','{$visitor_temp_detail_arr['user_photo']}','{$visitor_temp_detail_arr['front_photo']}','{$visitor_temp_detail_arr['back_photo']}','{$visitor_temp_detail_arr['mode']}','1','$ambiguity_details','$pdf_path')";

        $insert_dl_detail =  $mysqli->query($insert_dl_query);
        if (!$insert_dl_detail) {
            throw new Exception("Failed to Insert into visitor_dl_details_all");
        }


        $mysqli->commit();
        $response_arr[] = array("error_code" => 100, "message" => "Records Inserted successfully");
    } catch (Exception $e) {
        $mysqli->rollback();
        $response_arr[] = array("error_code" => 103, "message" => $e->getMessage());
    }
}


/* check errors*/
function check_error($mysqli, $mysqli1, $agency_id, $visitor_id, $dob, $dl_number)
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
    if (empty($agency_id)) {
        $response = ["error_code" => 106, "message" => "agency_id can not be empty"];
        echo json_encode($response);
        return;
    }
    if (empty($visitor_id)) {
        $response = ["error_code" => 106, "message" => "visitor_id can not be empty"];
        echo json_encode($response);
        return;
    }
    if (empty($dob)) {
        $response = ["error_code" => 106, "message" => "dob can not be empty"];
        echo json_encode($response);
        return;
    }
    if (empty($dl_number)) {
        $response = ["error_code" => 106, "message" => "dl_number can not be empty"];
        echo json_encode($response);
        return;
    }

    return $check_error;
}


/* get dl id details*/
function verify_dl_id($driving_licence_no, $date_of_birth)
{
    // Format date of birth
    $date_of_birth = date('Y-m-d', strtotime($date_of_birth));

    // Initialize cURL session
    $curl = curl_init();

    // Set cURL options
    curl_setopt_array($curl, [
        CURLOPT_URL => "https://api.gridlines.io/dl-api/fetch",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode([
            'driving_license_number' => $driving_licence_no,
            'date_of_birth' => $date_of_birth,
            'consent' => 'Y'
        ]),
        CURLOPT_HTTPHEADER => [
            "Accept: application/json",
            "Content-Type: application/json",
            "X-API-Key: C8EbVBaNqR4g3vhBAiPXdt8cLPkNLJoL",
            "X-Auth-Type: API-Key"
        ],
    ]);

    // Execute cURL session
    $response = curl_exec($curl);
    $err = curl_error($curl);

    // Close cURL session
    curl_close($curl);

    // Check for cURL errors
    if ($err) {
        return json_encode(['error' => 'API error', 'message' => $err]);
    } else {
        // Decode the JSON response
        $result = json_decode($response, true);

        // Check if the API response indicates success

        // Driving license is valid
        return json_encode($result);

        // Driving license is invalid
        // return json_encode(['error' => 'Invalid driving license', 'message' => $result]);

    }
}
