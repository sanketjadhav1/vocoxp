<?php

 //error_reporting(E_ALL & ~E_DEPRECATED);
 //ini_set('display_errors', 0);
require_once __DIR__ . '/vendor/autoload.php'; 
header('Content-Type: application/json; charset=UTF-8');
header('Access-Contorl-Allow-Origin: *');
date_default_timezone_set('Asia/kolkata');
include 'connection.php';
$connection = connection::getInstance();
$mysqli = $connection->getConnection();

$connection1 = database::getInstance();
$mysqli1 = $connection1->getConnection();
apponoff($mysqli);  
logout($_SERVER['HTTP_AUTH_KEY'], $mysqli);

date_default_timezone_set('Asia/kolkata');
$system_date = date("y-m-d");
$system_date_time = date("Y-m-d H:i:s");

$direct_id  = $direct_id = unique_id_genrate('DIR', 'direct_verification_details_all', $mysqli);
$application_id  = $_POST['application_id'] ?? '';
$initiated_on  = $system_date;
$completed_on = $system_date;
$agency_id = $_POST['agency_id'] ?? '';
$activity_status = $_POST['activity_status'] ?? '';
 
$name = $_POST['name'] ?? '';
$dob1 = $_POST['dob'] ?? '';
$gender = $_POST['gender'] ?? '';
$address = $_POST['address'] ?? '';
$user_photo = $_POST['user_photo'] ?? '';
  
$verification_id = $_POST['verification_id'] ?? '';
$user_voter_number = $_POST['voter_number']; 
//verification for direct or visitor
$verification_for = $_POST['verification_for'] ?? '';
$base_amount = $_POST['base_amount'] ?? '';
$sgst_amount = $_POST['sgst_amount'] ?? '';
$cgst_amount = $_POST['cgst_amount'] ?? '';
$source_from = $_POST['source_from'] ?? '';
$status = $_POST['status'] ?? '';
//mode for create or update
$mode=$_POST['mode'] ?? '';
//authenticate for yes or no
// if($mode == "update"){
//     $direct_id = $_POST['direct_id'];
//     $authneticate=$_POST['authneticate'] ?? '';
//     $query = "UPDATE `direct_voter_details_all` 
//                   SET `is_athenticate` = '$authneticate' 
//                   WHERE `direct_id` = '$direct_id'";

//         // Prepare and execute the query
//         $res_query = mysqli_query($mysqli, $query);
// }

$admin_id=$_POST['admin_id'];
if($admin_id!=""){
    $admin_id = $_POST['admin_id'] ?? '';
}else{
    $admin_id = $_POST['agency_id'] ?? '';
}
$reason="";

$check_error_res = check_error($mysqli, $mysqli1, $application_id, $verification_id, $agency_id);
if ($check_error_res == 1) {
                
                 // print_r($verification_data);
             if($verification_for=="direct")
            {
                if($mode=="create")
                {   
                    if(isValidVoter($user_voter_number)==1)
                    {
                        $verification_data = json_decode(verify_voter_id($user_voter_number), true);
                    
                        if($verification_data['data']['code'] == 1000 && $verification_data['status'] == 200) 
                        {
                                $name=$verification_data['data']['voter_data']['name'];
                                $document_type=$verification_data['data']['voter_data']['document_type'];
                                $document_id=$verification_data['data']['voter_data']['document_id'];
                                $father_name=$verification_data['data']['voter_data']['father_name'];
                                $gender=$verification_data['data']['voter_data']['gender'];
                                $age=$verification_data['data']['voter_data']['age'];
                                $district=$verification_data['data']['voter_data']['district'];
                                $state=$verification_data['data']['voter_data']['state'];
                                $assembly_constituency_number=$verification_data['data']['voter_data']['assembly_constituency_number']; 
                                $parliamentary_constituency_number=$verification_data['data']['voter_data']['parliamentary_constituency_number'];
                                $parliamentary_constituency_name=$verification_data['data']['voter_data']['parliamentary_constituency_name'];
                                $part_number=$verification_data['data']['voter_data']['part_number'];
                                $part_name=$verification_data['data']['voter_data']['part_name'];
                                $serial_number=$verification_data['data']['voter_data']['serial_number'];
                                $polling_station=$verification_data['data']['voter_data']['polling_station'];

                                $fetch_wallet = "SELECT `current_wallet_bal` FROM `agency_header_all` WHERE `agency_id`='$agency_id'";
                                        $res_wallet = mysqli_query($mysqli, $fetch_wallet);
                                        $arr_wallet = mysqli_fetch_assoc($res_wallet);
                                        $gst = $sgst_amount + $cgst_amount;
                                        $sgst_amt = $base_amount * $sgst_amount / 100;
                                        $cgst_amt = $base_amount * $cgst_amount / 100;
                                        $grand_total = $base_amount * $gst / 100;
                                        $grand_total1=$grand_total+$base_amount;
                                       
                         if ($arr_wallet['current_wallet_bal'] < $grand_total1) 
                         {
                            $responce = ["error_code" => 113, "message" => "Your wallet balance is too Low   To proceed. Please recharge your wallet."];
                            echo json_encode($responce);
                            return;
                        } 
                        else
                        {
                                $deducted_amt=$arr_wallet['current_wallet_bal'] - $grand_total- $base_amount;
                                $total=$base_amount+$sgst_amt+$cgst_amt;

                                if ($user_photo != "")
                               {
                                     $user_img = save_doc_photo($user_photo, $direct_id, $agency_id);
                                }
                                else
                                {
                                    $user_img ="";
                                }


                                $update_wallet = "UPDATE `agency_header_all` SET `current_wallet_bal`='$deducted_amt' WHERE `agency_id`='$agency_id'";
                                $res_update = mysqli_query($mysqli, $update_wallet);

                                $insert_wall="INSERT INTO `wallet_payment_transaction_all` (`id`, `agency_id`, `user_id`, `requested_from`, `purchase_type`, `verification_id`, `base_amount`, `cgst_amount`, `sgst_amount`, `transaction_on`, `transaction_id`, `line_type`, `quantity`, `settled_for`) VALUES (NULL, '$agency_id', '$direct_id', '2', '1', '$verification_id', '$base_amount', '$cgst_amt', '$sgst_amt', '$system_date_time', '', '1', '', '')";
                                $res_wall=mysqli_query($mysqli, $insert_wall);
                                 
                                $insert_pan_payment = "INSERT INTO `direct_verification_details_all` (`direct_id`, `application_id`, `agency_id`, `verification_id`, `initiated_on`, `completed_on`, `activity_status`, `linked_table`, `deducted_base_amount`, `sgst_amount`, `cgst_amount`,  `source_from`,`generated_by`) VALUES ('$direct_id', '$application_id', '$agency_id', '$verification_id', '$system_date_time', '$system_date_time', '$status', 'direct_voter_details_all', '$base_amount', '$sgst_amt', '$cgst_amt',  '$source_from','2') ";
                                $res_pan = mysqli_query($mysqli, $insert_pan_payment);

                                // Insert into `direct_pan_details_all`

                                $insert_pan_detail = "INSERT INTO `direct_voter_details_all` (`id`, `direct_id`, `application_id`, `agency_id`, `admin_id`, `initiated_on`, `completed_on`, `activity_status`, `voter_number`, `name`, `gender`, `father_name`, `address`, `polling_details`, `user_photo`,`generated_by`) VALUES (NULL, '$direct_id', '$application_id', '$agency_id', '$admin_id', '$system_date_time', '$system_date_time', '1', '$user_voter_number', '$name',  '$gender', '$father_name', '$part_name', '$polling_station', '$user_img','2') ";
                                $res_pan_detail = mysqli_query($mysqli, $insert_pan_detail);
                                $postdata[] = array( 
                                   
                                     'direct_id' => $direct_id ,
                                     'name' => $name ,
                                     'document_type' => $document_type ,
                                     'document_id' => $document_id ,
                                     'father_name' => $father_name ,
                                     'gender' => $gender ,
                                     'age' => $age ,
                                     'district' => $district ,
                                     'state' => $state ,
                                     'assembly_constituency_number' => $assembly_constituency_number , 
                                     'parliamentary_constituency_number' => $parliamentary_constituency_number ,
                                     'parliamentary_constituency_name' => $parliamentary_constituency_name ,
                                     'part_number' => $part_number ,
                                     'part_name' => $part_name ,
                                     'serial_number' => $serial_number ,
                                     'polling_station' => $polling_station ,
                                      
                                     'current_wallet_bal' => number_format($deducted_amt, 2, '.', ''), 
                                      'user_photo' =>$user_img
                                  );
                                 $response = [ "error_code" => 100, "message" => "data fetch successfully.","data"=>$postdata];
                             echo json_encode($response);
                          }
                        }
                        else
                        {
                            $responce = ["error_code" => 199, "message" => "voter number is invalid. Please provide the valid voter number"];
                                echo json_encode($responce);
                                return;
                        }
                    }
                    else
                    {
                        $responce = ["error_code" => 199, "message" => "voter number is invalid. Please provide the valid voter number"];
                            echo json_encode($responce);
                            return;
                    }
                    
                }
                else
                {
                          if($mode == "update")
                         {
                            $direct_id = $_POST['direct_id'];
                            $authneticate=$_POST['authneticate'] ?? '';
                            $query = "UPDATE `direct_voter_details_all`   SET `is_athenticate` = '$authneticate'  WHERE `direct_id` = '$direct_id' AND `agency_id` = '$agency_id' ";

                                // Prepare and execute the query
                                $res_query = mysqli_query($mysqli, $query);
                                $response = ["error_code" => 100, "message" => "passport number details successfully updated"];
                    echo json_encode($response);
                    return;

                            }
                }

            }
            elseif ($verification_for=="visitor") {
                // code...
            }
            
}


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
function isValidVoter($pan)
{
    // Regular expression to match PAN format
    $pattern = '/^[A-Z]{3}[0-9]{7}$/';

    // Check if the PAN matches the pattern
    if (preg_match($pattern, $pan)) {
        return 1; // PAN is valid
    } else {
        return 0; // PAN is invalid
    }
}
function verify_voter_id($voter_id_no) {
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

// function isValidVoter($pan)
// {
//     // Regular expression to match PAN format
//     $pattern = '/(^[0-9]{4}[0-9]{4}[0-9]{4}$)|(^[0-9]{4}\s[0-9]{4}\s[0-9]{4}$)|(^[0-9]{4}-[0-9]{4}-[0-9]{4}$)/';

//     // Check if the PAN matches the pattern
//     if (preg_match($pattern, $pan)) {
//         return 1; // PAN is valid
//     } else {
//         return 0; // PAN is invalid
//     }
// }

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