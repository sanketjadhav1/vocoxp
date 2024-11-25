
<?php
error_reporting(E_ALL & ~E_DEPRECATED);
ini_set('display_errors',0);


// header('Content-Type: application/json; charset=UTF-8');
// header('Access-Contorl-Allow-Origin: *');
date_default_timezone_set('Asia/kolkata');
include 'connection.php';
$connection = connection::getInstance();
$mysqli = $connection->getConnection();

$connection1 = database::getInstance();
$mysqli1 = $connection1->getConnection();
 
$system_date = date("y-m-d");
$system_date_time = date("Y-m-d H:i:s");
$output = $responce = array();

//data from user
$user_name = $_POST['name'] ?? '';
$edited_name = $_POST['edited_name'] ?? '';
$user_pan_number = $_POST['pan_number'] ?? '';
$edited_pan_no = $_POST['edited_pan_number'] ?? '';
$user_date_of_birth = $_POST['date_of_birth'] ?? '';
$edited_dob = $_POST['edited_dob'] ?? '';
$user_father_husband_name = $_POST['father_husband_name'] ?? '';
$edited_father_name = $_POST['edited_father_name'] ?? '';
$application_id = $_POST['application_id'];
$agency_id = $_POST['agency_id'];
$admin_id = $_POST['admin_id'] ?? '';
$verification_id = $_POST['verification_id'] ?? '';
$base_amount = $_POST['base_amount'] ?? '';
$sgst_amount = $_POST['sgst_amount'] ?? '';
$cgst_amount = $_POST['cgst_amount'] ?? '';
$front_photo = $_FILES['front_photo'] ?? '';
$source_from = $_POST['source_from'] ?? '';
$user_photo = $_FILES['user_photo'] ?? '';
$is_verified = $_POST['is_verified'] ?? '';
$is_edited = $_POST['is_edited'] ?? '';
$data_fetch_through_ocr = $_POST['data_fetch_through_ocr'] ?? '';
$site_id = $_POST['site_id'] ?? '';
$create_worker = $_POST['create_worker'] ?? '';
$reason=$_POST['reason'] ?? '';
if ($is_edited == "yes") {
    $pan = $edited_pan_no;
} else {
    $pan = $user_pan_number;
}
if (empty($pan)) {
    $responce = ["error_code" => 199, "message" => "Pan number cannot be empty"];
    echo json_encode($responce);
    return;
}
if ($create_worker == 1) {
    $worker_id = unique_id_genrate('WOR', 'construction_site_worker_header_all', $mysqli);
    $name = $_POST['name'];
    $contact = $_POST['contact'];
    $login_code = $_POST['login_code'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];
    $status = $_POST['status'];
    $insert_worker = "INSERT INTO `construction_site_worker_header_all` (`agency_id`, `site_id`, `worker_id`, `name`, `contact`, `login_code`, `gender`, `address`, `status`, `inserted_on`) VALUES ('$agency_id', '$site_id', '$worker_id', '$name', '$contact', '$login_code', '$gender', '$address', '$status', '$system_datetime')";
    $res_worker = mysqli_query($mysqli, $insert_worker);
}

$total_amount = $base_amount + $sgst_amount + $cgst_amount;


$direct_id = unique_id_genrate('DIR', 'direct_verification_details_all', $mysqli);
   


$check_error_res = check_error($mysqli, $mysqli1, $application_id, $verification_id, $agency_id);

if ($check_error_res == 1) {
    if ($is_verified == "bulkyes") 
    {
        $bulk_id=$_POST['bulk_id'];
        $admin_id=$_POST['admin_id'];
        $fetch_wallet = "SELECT `current_wallet_bal` FROM `agency_header_all` WHERE `agency_id`='$agency_id'";
            $res_wallet = mysqli_query($mysqli, $fetch_wallet);
            $arr_wallet = mysqli_fetch_assoc($res_wallet);
            if ($arr_wallet['current_wallet_bal'] < $total_amount) {
                $responce = ["error_code" => 113, "message" => "Due to a technical issue, we are unable to complete your verification at this time. Please contact your agency for further assistance."];
                            echo json_encode($responce);
                            return;
                        } 
                 // die();
             }
             else
             {
                $message = $verification_data['error']['message'];
                $response = [
                    "error_code" => 199,
                    "message" => $message,
                    "transaction_id" => $verification_data['transaction_id']
                ];
                echo json_encode($response);
                return;
             }
        $verification_data = json_decode(verify_pan($pan, $reason), true);
        // print_r($verification_data);
        // die();
        if ($verification_data['code'] == 200 ) {
             $percentage = 0;
        $cnt = 0;
        $j = 0;
             $adhar_name_arr = explode(" ", strtoupper($verification_data['data']['full_name']));
            // print_r($adhar_name_arr);
            // die();
            $person_name_arr = explode(" ", strtoupper(trim($user_name, " ")));
            //print_r($person_name_arr);
            if (sizeof($adhar_name_arr) > sizeof($person_name_arr)) 
            {
                $cnt = sizeof($adhar_name_arr);
                foreach ($adhar_name_arr as $adhar) 
                {
                    if (in_array($adhar, $person_name_arr)) {
                        $j++;
                    }
                }
            } 
            else
             {
                $cnt = sizeof($person_name_arr);
                foreach ($adhar_name_arr as $adhar) {
                    if (in_array($adhar, $person_name_arr)) {
                        $j++;
                    }
                }
            }

                if ($j == $cnt) 
                {
                    $is_name_match = "Match"; 
                } 
                else
                {
                    // $mismatch_data = 'Name';
                    $is_name_match = 'Not Match';
                }
        }
           
        }
        else
        {
            $responce = ["error_code" => 199, "message" => "Pan number is invalid. Please provide the valid pan number"];
            echo json_encode($responce);
            return;
        }
    if ($is_verified == "yes") {
        $fetch_wallet = "SELECT `current_wallet_bal` FROM `agency_header_all` WHERE `agency_id`='$agency_id'";
        $res_wallet = mysqli_query($mysqli, $fetch_wallet);
        $arr_wallet = mysqli_fetch_assoc($res_wallet);
        if ($arr_wallet['current_wallet_bal'] < $total_amount) {
            $responce = ["error_code" => 113, "message" => "Your wallet balance is too Low To proceed. Please recharge your wallet."];
            echo json_encode($responce);
            return;
        }
    }
  function VerifyNames($verifyname, $uname) {
                    
                    if (strlen($verifyname)==0 || strlen($uname)==0) {
                        return 0;
                    }

                    
                     
                    $s1clean = preg_replace("/[^A-Za-z0-9-]/", ' ', $verifyname);
                    $s2clean = preg_replace("/[^A-Za-z0-9-]/", ' ', $uname);

                    //remove double spaces
                    while (strpos($s1clean, "  ")!==false) {
                        $s1clean = str_replace("  ", " ", $s1clean);
                    }
                    while (strpos($s2clean, "  ")!==false) {
                        $s2clean = str_replace("  ", " ", $s2clean);
                    }

                    //create arrays
                    $ar1 = explode(" ",$s1clean);
                    $ar2 = explode(" ",$s2clean);
                    $l1 = count($ar1);
                    $l2 = count($ar2);

                    
                    if ($l2>$l1) {
                        $t = $ar2;
                        $ar2 = $ar1;
                        $ar1 = $t;
                    }

                    
                    $ar2 = array_flip($ar2);


                    $maxwords = max($l1, $l2);
                    $matches = 0;

                    
                    foreach($ar1 as $word) {
                        if (array_key_exists($word, $ar2))
                            $matches++;
                    }

                    return ($matches / $maxwords) * 100;    
                }
                $verification_data = json_decode(verify_pan($pan, $reason), true);
                // print_r($verification_data);
                $user_img="";
          if ($user_photo != "") {
         $user_img = save_doc_photo($user_photo, $direct_id, $agency_id);
    }
    $front_img="";
     if ($front_photo != "") {
         $front_img = save_doc_photo($front_photo, $direct_id, $agency_id);
    }
   
                

    if ($is_verified == "yes") 
    {
         
        if ($verification_data['data']['status'] == "INVALID")
         {
            $responce = ["error_code" => 199, "message" => "Pan number is invalid. Please provide the valid pan number"];
            echo json_encode($responce);
            return;
        }
        elseif ($verification_data['code'] == 200) {
        $percentage = 0;
        $cnt = 0;
        $j = 0;

        if ($is_edited == "yes") {
            $adhar_name_arr = explode(" ", strtoupper($verification_data['data']['full_name']));
            //print_r($adhar_name_arr);
            $person_name_arr = explode(" ", strtoupper(trim($edited_name, " ")));
            //print_r($person_name_arr);
            if (sizeof($adhar_name_arr) > sizeof($person_name_arr)) {
                $cnt = sizeof($adhar_name_arr);
                foreach ($adhar_name_arr as $adhar) {
                    if (in_array($adhar, $person_name_arr)) {
                        $j++;
                    }
                }
            } else {
                $cnt = sizeof($person_name_arr);
                foreach ($adhar_name_arr as $adhar) {
                    if (in_array($adhar, $person_name_arr)) {
                        $j++;
                    }
                }
            }
        }
        else
        {
            $adhar_name_arr = explode(" ", strtoupper($verification_data['data']['full_name']));
            //print_r($adhar_name_arr);
            $person_name_arr = explode(" ", strtoupper(trim($user_name, " ")));
            //print_r($person_name_arr);
            if (sizeof($adhar_name_arr) > sizeof($person_name_arr)) 
            {
                $cnt = sizeof($adhar_name_arr);
                foreach ($adhar_name_arr as $adhar) 
                {
                    if (in_array($adhar, $person_name_arr)) {
                        $j++;
                    }
                }
            } 
            else
             {
                $cnt = sizeof($person_name_arr);
                foreach ($adhar_name_arr as $adhar) {
                    if (in_array($adhar, $person_name_arr)) {
                        $j++;
                    }
                }
            }
        }
    } 
    else 
    {
        $adhar_name_arr = explode(" ", strtoupper($verification_data['data']['full_name']));
        //print_r($adhar_name_arr);
        $person_name_arr = explode(" ", strtoupper(trim($user_name, " ")));
        //print_r($person_name_arr);
        if (sizeof($adhar_name_arr) > sizeof($person_name_arr)) {
            $cnt = sizeof($adhar_name_arr);
            foreach ($adhar_name_arr as $adhar) 
            {
                if (in_array($adhar, $person_name_arr)) {
                    $j++;
                }
            }
        } 
        else
         {
            $cnt = sizeof($person_name_arr);
            foreach ($adhar_name_arr as $adhar) {
                if (in_array($adhar, $person_name_arr)) {
                    $j++;
                }
            }
        }
    }

    if ($j == $cnt) 
    {
        $is_name_match = "Match"; 
    } 
    else
    {
        // $mismatch_data = 'Name';
        $is_name_match = 'Not Match';
    }
    // VerifyNames($verification_data['data']['full_name'], strtoupper($user_name))
            $is_name = round(VerifyNames($verification_data['data']['full_name'], strtoupper($user_name)),2);

        if($verification_data['data']['pan']==$pan) 
        {
             $is_pan_match = "Match"; 
        }
        else
        {
            // $mismatch_data = 'Name';
            $is_pan_match = 'Name Not Match';
        }
             
        // if($is_dob_match==)
        // {

        // }
        // else
        // {

        // }
             
             $url = get_base_url() . 'pan_pdf_direct.php';
              $postdata1 = array(
                    'name' => $user_name,
                    'is_name_match' => $is_name_match,
                    'is_pan_match' => $is_pan_match,
                    'edited_name' => $edited_name,
                    'pan_number' => $user_pan_number,
                    'edited_pan_no' => $edited_pan_no,
                    'date_of_birth' => $user_date_of_birth,
                    'edited_dob' => $edited_dob,
                    'father_husband_name' => $user_father_husband_name,
                    'edited_father_name' => $edited_father_name,
                    'application_id' => $application_id,
                    'agency_id' => $agency_id,
                    'admin_id' => $admin_id,
                    'verification_id' => $verification_id,
                    'base_amount' => $base_amount,
                    'sgst_amount' => $sgst_amount,
                    'cgst_amount' => $cgst_amount,
                    'is_verified' => $is_verified,
                    'is_edited' => $is_edited,
                    'data_fetch_through_ocr' => $data_fetch_through_ocr,
                    'uploaded_documents' =>  $front_img,
                    'user_photo' =>  $user_img,
                    'verification_name' => $verification_data['data']['full_name'],
                    'verification_category' => $verification_data['data']['category'],
                    'verification_pan' => $verification_data['data']['pan'],
                    "direct_id"=>$direct_id,
                    "front_img"=>$front_img,
                    "user_img"=>$user_img,
                    "bulk_id"=>$bulk_id,
                    "source_from"=>$source_from
                );
                // print_r($postdata);

                // Initialize cURL session
                $ch = curl_init($url);

                // Configure cURL options
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postdata1));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                 
                // Execute the POST request
                $response1 = curl_exec($ch);

                // Check for errors
                if (curl_errno($ch)) {
                    // Print the error
                    echo 'cURL error: ' . curl_error($ch);
                } 
                else
                 {
                    // Print the response
                     //print_r($response1);
                    $dataArray = json_decode($response1, true);
                   if (json_last_error() !== JSON_ERROR_NONE) {
                                echo 'JSON decode error: ' . json_last_error_msg();
                                exit;
                            }

                            // Extract the PDF URL
                            $pdfUrl = $dataArray['data'][0]['pdf_url'];
                            $current_wallet_bal = $dataArray['data'][0]['current_wallet_bal'];

                            // Output the PDF URL
                            //echo 'PDF URL: ' . $pdfUrl;
                     
                }

                // Close the cURL session
                curl_close($ch);
   
                $postdata[] = array(
                // 'ocr_name' => $user_name,
                // 'verification_name' => $verification_data['data']['full_name'],
                
                // 'pancard_no' => $verification_data['data']['pan'],
                // 'verification_category' => $verification_data['data']['category'],
                // 'agency_id' => $agency_id,
                // 'specification_id' => $verification_id,
                // 'application_id' => $application_id,
                'pdf_url'=> $pdfUrl,
                'current_wallet_bal'=> $current_wallet_bal
            );
            $data = ["error_code" => 100, "message" => "data fetch successfully", "data" => $postdata];
             echo json_encode($data);
    }
    else
    {
            // $direct_id = unique_id_genrate('DIR', 'direct_verification_details_all', $mysqli);
            if($verification_data['data']['pan']==$pan) 
            {
                 $is_pan_match = "Match"; 
            }
            else
            {
                // $mismatch_data = 'Name';
                $is_pan_match = 'Name Not Match';
            } 
             $url = get_base_url() . 'pan_pdf_direct.php';
              $postdata1 = array(
                    'name' => $user_name,
                    'is_name_match' => $is_name_match,
                    'is_pan_match' => $is_pan_match,
                    'edited_name' => $edited_name,
                    'pan_number' => $user_pan_number,
                    'edited_pan_no' => $edited_pan_no,
                    'date_of_birth' => $user_date_of_birth,
                    'edited_dob' => $edited_dob,
                    'father_husband_name' => $user_father_husband_name,
                    'edited_father_name' => $edited_father_name,
                    'application_id' => $application_id,
                    'agency_id' => $agency_id,
                    'admin_id' => $admin_id,
                    'verification_id' => $verification_id,
                    'base_amount' => $base_amount,
                    'sgst_amount' => $sgst_amount,
                    'cgst_amount' => $cgst_amount,
                    'is_verified' => $is_verified,
                    'is_edited' => $is_edited,
                    'data_fetch_through_ocr' => $data_fetch_through_ocr,
                    'uploaded_documents' => $front_img,
                    'user_photo' => $user_img,
                    'verification_name' => $verification_data['data']['full_name'],
                    'verification_category' => $verification_data['data']['category'],
                    "direct_id"=>$direct_id,
                    "front_img"=>$front_img,
                    "user_img"=>$user_img,
                    "bulk_id"=>$bulk_id,
                    "source_from"=>$source_from
                );
                // print_r($postdata);

                // Initialize cURL session
                $ch = curl_init($url);

                // Configure cURL options
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postdata1));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                 
                // Execute the POST request
                $response1 = curl_exec($ch);

                // Check for errors
                if (curl_errno($ch)) {
                    // Print the error
                    echo 'cURL error: ' . curl_error($ch);
                } 
                else
                 {
                    // Print the response
                    // echo $response1;
                    $dataArray = json_decode($response1, true);

                   if (json_last_error() !== JSON_ERROR_NONE) {
                                echo 'JSON decode error: ' . json_last_error_msg();
                                exit;
                            }

                            // Extract the PDF URL
                            $pdfUrl = $dataArray['data'][0]['pdf_url'];
                            $current_wallet_bal = $dataArray['data'][0]['current_wallet_bal'];

                            // Output the PDF URL
                            //echo 'PDF URL: ' . $pdfUrl;
                     
                }

                // Close the cURL session
                curl_close($ch);
   
                $postdata[] = array(
                // 'ocr_name' => $user_name,
                // 'verification_name' => $verification_data['data']['full_name'],
                // 'is_name_match' => $is_name_match,
                // 'pancard_no' => $verification_data['data']['pan'],
                // 'verification_category' => $verification_data['data']['category'],
                // 'agency_id' => $agency_id,
                // 'specification_id' => $verification_id,
                // 'application_id' => $application_id,
                'pdf_url'=> $pdfUrl,
                'current_wallet_bal'=> $current_wallet_bal
            );
            $data = ["error_code" => 100, "message" => "data fetch successfully", "data" => $postdata];
             echo json_encode($data);

    }



   


    // save pan img
    // echo $verification_data['data']['category'];
    // exit;
    // Define the URL
    

    // Data to send
   
 


function check_error($mysqli, $mysqli1, $application_id, $verification_id, $agency_id)
{

    $check_error = 1;
    if (!$mysqli) {
        $response = ["error_code" => 101, "message" => "Unable to proceed your request, please try again after some time"];
        echo json_encode($response);
        return;
    }

    if ($_SERVER['REQUEST_METHOD'] != "POST") {
        $response = array("error_code" => 102, "message" => "Request Method is not Post");
        echo json_encode($response);
        return;
    }

    if (!isset($application_id)) {
        $response = ["error_code" => 103, "message" => "Please pass the parameter of application_id"];
        echo json_encode($response);
        return;
    }
    if (empty($application_id)) {
        $response = ["error_code" => 104, "message" => "application_id can not be empty"];
        echo json_encode($response);
        return;
    }

    if (!isset($verification_id)) {
        $response = ["error_code" => 103, "message" => "Please pass the parameter of verification_id"];
        echo json_encode($response);
        return;
    }
    if (empty($verification_id)) {
        $response = ["error_code" => 104, "message" => "verification_id can not be empty"];
        echo json_encode($response);
        return;
    }

    if (!isset($agency_id)) {
        $response = ["error_code" => 105, "message" => "Please pass the parameter of agency_id"];
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


function verify_pan($pancard_no, $reason)
{
    $curl = curl_init();
    $token = json_decode(get_token());
    //echo $token;exit;
    curl_setopt_array($curl, [
        CURLOPT_URL => "https://api.sandbox.co.in/pans/" . $pancard_no . "/verify?consent=y&reason=For%20KYC%20of%20User",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 180,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => [
            "Authorization: " . $token->access_token,
            "accept: application/json",
            "x-api-key: key_live_R7mjeIgaSUvT54Kdny3AhSuaQVeBkj37",
            "x-api-version: 1.0"
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


function get_token()
{
    $curl = curl_init();
    //echo "in fun";
    curl_setopt_array($curl, [
        CURLOPT_URL => "https://api.sandbox.co.in/authenticate",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_HTTPHEADER => [
            "accept: application/json",
            "x-api-key: key_live_R7mjeIgaSUvT54Kdny3AhSuaQVeBkj37",
            "x-api-secret: secret_live_qZ1nAgKIPosx1LnZamiafPS6z2aq0mJk",
            "x-api-version: 1.0"
        ],
    ]);

    $response = curl_exec($curl);
    //print_r ($response);
    //  die;
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        return $err;
    } else {
        return $response;
    }
}

function isValidPAN($pan)
{
    // Regular expression to match PAN format
    $pattern = '/^[A-Z]{5}[0-9]{4}[A-Z]$/';

    // Check if the PAN matches the pattern
    if (preg_match($pattern, $pan)) {
        return 1; // PAN is valid
    } else {
        return 0; // PAN is invalid
    }
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

function save_doc_photo($upload_file, $direct_id, $agency_id)
{
    // Ensure $upload_file is an array and has the necessary keys
    if (!is_array($upload_file) || !isset($upload_file['name']) || !isset($upload_file['tmp_name'])) {
        throw new Exception('Invalid file upload array.');
    }

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
    curl_setopt_array($curl, array(
        CURLOPT_URL => "ftp://$ftp_server/",
        CURLOPT_USERPWD => "$ftp_username:$ftp_password",
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_FTP_CREATE_MISSING_DIRS => true, // Create missing directories
    ));

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
    if ($response_mkdir === false) {
        $error_message_mkdir = curl_error($curl);
        // It's possible the directory already exists, so we can ignore this error in some cases
    }

    // Get file extension
    $ext = strtolower(pathinfo($upload_file['name'], PATHINFO_EXTENSION));

    // Generate a unique file name
    $file_name = uniqid();

    // Path to save the uploaded file on the remote server
    $file_path = $remote_dir_path . "docs-" . $file_name . "." . $ext;

    // Create an image resource from the uploaded file
    switch ($ext) {
        case 'jpeg':
        case 'jpg':
            $image = imagecreatefromjpeg($upload_file['tmp_name']);
            break;
        case 'png':
            $image = imagecreatefrompng($upload_file['tmp_name']);
            break;
        case 'gif':
            $image = imagecreatefromgif($upload_file['tmp_name']);
            break;
        default:
            throw new Exception('Unsupported file format');
    }

    // Get original dimensions
    list($width, $height) = getimagesize($upload_file['tmp_name']);

    // Desired maximum size in bytes
    $maxSize = 20 * 1024; // 20 KB

    // Resize image dimensions (e.g., reduce to 20% of original size)
    $new_width = $width * 0.2;
    $new_height = $height * 0.2;
    $resized_image = imagecreatetruecolor($new_width, $new_height);

    // Preserve transparency for PNG and GIF
    if ($ext === 'png' || $ext === 'gif') {
        imagecolortransparent($resized_image, imagecolorallocatealpha($resized_image, 0, 0, 0, 127));
        imagealphablending($resized_image, false);
        imagesavealpha($resized_image, true);
    }

    // Copy and resize the original image into the resized image
    imagecopyresampled($resized_image, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

    // Start with lower quality
    $quality = 50; // Adjust the quality setting

    // Temporary output buffer
    ob_start();
    switch ($ext) {
        case 'jpeg':
        case 'jpg':
            imagejpeg($resized_image, null, $quality);
            break;
        case 'png':
            imagepng($resized_image, null, (int)($quality / 10 - 1));
            break;
        case 'gif':
            imagegif($resized_image, null);
            break;
    }
    $imageData = ob_get_clean();
    $fileSize = strlen($imageData);

    // Decrease quality until file size is within desired range
    while ($fileSize > $maxSize && $quality > 10) {
        $quality -= 5;
        ob_start();
        switch ($ext) {
            case 'jpeg':
            case 'jpg':
                imagejpeg($resized_image, null, $quality);
                break;
            case 'png':
                imagepng($resized_image, null, (int)($quality / 10 - 1));
                break;
            case 'gif':
                imagegif($resized_image, null);
                break;
        }
        $imageData = ob_get_clean();
        $fileSize = strlen($imageData);
    }

    // Save the resized image to a temporary file
    $temp_file = tempnam(sys_get_temp_dir(), 'img');
    file_put_contents($temp_file, $imageData);

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
    ));

    // Execute cURL session for file upload
    ob_start(); // Start output buffering
    $response_upload = curl_exec($curl_upload);
    ob_end_clean(); // Discard output buffer

    // Check for errors in file upload
    if ($response_upload === false) {
        $error_message_upload = curl_error($curl_upload);
        die("Failed to upload image file: $error_message_upload");
    }

    // Close cURL sessions
    curl_close($curl);
    curl_close($curl_upload);

    // Clean up temporary files
    imagedestroy($image);
    imagedestroy($resized_image);
    unlink($temp_file);

    // Construct the full URL of the uploaded file
    $base_url = "https://mounarchtech.com/central_wp/verification_data/voco_xp/$agency_id/$direct_id/doc_photo";
    $emp_imageURL = $base_url . '/docs-' . $file_name . '.' . $ext;

    // Return the URL of the uploaded file
    return $emp_imageURL;
}
 

?>