<?php
error_reporting(E_ALL);
date_default_timezone_set('Asia/Kolkata');

class connection
{

    private $_connection;
    private static $_instance; //The single instance
    /* local */
     private $_host = "localhost";
      private $_username = "root";
      private $_password = "";
      private $_database = "mounac53_vocoxp3.0";   //vocoxp3.0
   /* private $_host = "199.79.62.21";
    // private $_host = "localhost";
    private $_username = "mounac53_vocoxp";
    // private $_username = "root";
    private $_password = 'mX#&V~o_ksOS';
    // private $_password = '';
    private $_database = "mounac53_vocoxp3.0";*/




    public static function getInstance()
    {
        if (!self::$_instance) { // If no instance then make one
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    // Constructor
    private function __construct()
    {
        $this->_connection = new mysqli(
            $this->_host,
            $this->_username,
            $this->_password,
            $this->_database
        );

        // Error handling
        if (mysqli_connect_error()) {
        }
    }

    // Magic method clone is empty to prevent duplication of connection
    private function __clone()
    {
    }

    // Get mysqli connection
    public function getConnection()
    {
        //        if ($this->_connection) {
        return $this->_connection;
        //        } else {
        //            return;
        //        }
    }
}

class database
{

    private $_connection1;
    private static $_instance1; //The single instance
    /* local */
     private $_host = "localhost";
      private $_username = "root";
      private $_password = "";
      private $_database = "mounac53_centraldb";  //central_db
  /*  private $_host = "199.79.62.21";
    // private $_host = "localhost";
    private $_username = "mounac53_centraldb";
    // private $_username = "root";
    private $_password = 'c4nQavOB@j6Z';
    // private $_password = '';
    private $_database = "mounac53_centraldb";*/




    public static function getInstance()
    {
        if (!self::$_instance1) { // If no instance then make one
            self::$_instance1 = new self();
        }
        return self::$_instance1;
    }

    // Constructor
    private function __construct()
    {
        $this->_connection1 = new mysqli(
            $this->_host,
            $this->_username,
            $this->_password,
            $this->_database
        );

        // Error handling
        if (mysqli_connect_error()) {
        }
    }

    // Magic method clone is empty to prevent duplication of connection
    private function __clone()
    {
    }

    // Get mysqli connection
    public function getConnection()
    {
        //        if ($this->_connection) {
        return $this->_connection1;
        //        } else {
        //            return;
        //        }
    }
}


$localIP = $_SERVER['SERVER_NAME'];
if ($localIP == 'miscos.in') {
    $base_url = "http://$localIP/vhss/";
    $base_url1 = "http://$localIP/";               //salesmanager folder url
} else {
    $base_url = "http://$localIP/live/vhss/";
    $base_url1 = "http://$localIP/live/";             //salesmanager folder url
}


function logout($token, $conn)
{
    $query = "SELECT * FROM app_user_token_details_all WHERE SUBSTRING_INDEX(app_device_token, ',', 1) = '$token'";
    // $query = "SELECT `id` FROM `app_user_token_details_all` WHERE `app_device_token` = '$token';";
    $res = mysqli_query($conn, $query);
    $arr = mysqli_fetch_assoc($res);
    $row = mysqli_num_rows($res);

    if ($row == 0) { // Check if $arr is NULL (no rows found)
        $response[] = ["error_code" => 440, "message" => ""];
        echo json_encode($response);
        exit;
    }
}

function pause_signup($mysqli)
{
    $fetch_factory = "SELECT `pause_switch`, `pause_message` FROM factory_setting_header_all WHERE `pause_switch`='1'";
    $res_factory = mysqli_query($mysqli, $fetch_factory);
    $arr_factory = mysqli_fetch_assoc($res_factory);

    if ($arr_factory) {
        $response[] = ["error_code" => 104, "message" => $arr_factory['pause_message']];
        echo json_encode($response);
        exit;
    }
}

function apponoff($conn)
{
    $fetch_app = "SELECT `main_power_switch`, `power_switch_message` FROM `factory_setting_header_all` WHERE `main_power_switch` = 0";
    $res = mysqli_query($conn, $fetch_app);

    $arr = mysqli_fetch_assoc($res);
    if ($arr) { // Check if there are results
        $response[] = ["error_code" => 440, "message" => $arr['power_switch_message']];
        echo json_encode($response);
        exit;
    }
}


function unique_id_genrate($id_prefix, $table_name, $mysqli)
{

    date_default_timezone_set('Asia/kolkata');
    $system_date = date("y-m-d");
    $system_date_time = date("Y-m-d H:i:s");


    $unique_header_query = "SELECT `prefix`, `last_id`, `id_for` FROM `unique_id_header_all` where `table_name`='$table_name'";
    $unique_header_res = $mysqli->query($unique_header_query);
    $unique_header_arr = $unique_header_res->fetch_assoc();

    // print_r($unique_header_arr);

    $last_id = $unique_header_arr['last_id'];
    $id_for = $unique_header_arr['id_for'];
    // $id_prefix=$unique_header_arr['prefix'];
    if ($id_for == "") {
        switch ($table_name) {
            case "agency_header_all":
                $id_for = "agency_id";
                break;
            case "member_header_all":
                $id_for = "member_id";
                break;
            case "admin_header_all":
                $id_for = "admin_id";
                break;
            case "profile_header_all":
                $id_for = "profile_id";
                break;
            case "version_control_details_all":
                $id_for = "version_id";
                break;
            case "bulk_weblink_request_all":
                $id_for = "request_no";
                break;
            case "bulk_weblink_request_all":
                $id_for = "bulk_id";
                break;
            case "bulk_weblink_request_all":
                $id_for = "custom_id";
                break;
        }
        
          
    } else {
        $check_query = "SELECT `$id_for` FROM `$table_name` where `$id_for`='$last_id'";
        $check_res = $mysqli->query($check_query);
        $check_arr = $check_res->fetch_assoc();
    }






    if (empty($unique_header_arr)) {
        $initial_id = $id_prefix .'-'.str_pad(1, 5, '0', STR_PAD_LEFT);
        
       
        // $initial_id = sprintf('%s-%05d', $id_prefix, 00001);
        // $initial_id = $id_prefix.'-' . (str_pad($gen_emp_id, 5, '0', STR_PAD_LEFT));
       
         $insert_query = "INSERT INTO unique_id_header_all 
    (table_name, id_for, prefix, last_id, created_on) 
    VALUES ('$table_name', '$id_for', '$id_prefix', '$initial_id', '$system_date_time')
";


        // Execute the query
        $res = mysqli_query($mysqli, $insert_query);
        return $initial_id;
    } else {
        $old_admin_id = $last_id;

        $last_digit = explode('-', $old_admin_id);
        $lastId = $last_digit[1];
        //    $lastId='A0001';

        if (strlen($lastId) > '5')
            return 'ID cannot be more then 5';
        $alphabets_p = preg_replace('/[^A-Za-z]/', '', $lastId);
        $alphabets = preg_replace('/[^A-Za-z]/', '', $lastId);
        $digits = preg_replace('/[^0-9]/', '', $lastId);

        // Check if the last ID is all 9's
        if ($digits === str_repeat('9', strlen($digits))) {
            if ($alphabets === 'ZZZZZ') {
                return $alphabets = 'You have reached the last ID string';
            } else if (strlen($alphabets) === 1 && $alphabets !== 'Z') {
                $alphabets = chr(ord($alphabets) + 1);
            } elseif ($alphabets === 'Z') {
                $alphabets = 'ZA';
            } elseif (strlen($alphabets) > 1 && substr($alphabets, -1) !== 'Z') {
                $lastChar = substr($alphabets, -1);
                $prefix = substr($alphabets, 0, -1);
                $alphabets = $prefix . chr(ord($lastChar) + 1);
            } elseif (strlen($alphabets) > 1 && substr($alphabets, -1) == 'Z') {
                $alphabets = $alphabets . 'A';
            }

            if (strlen($alphabets) == '5') {
                return $alphabets;
            }
            if ($digits == '99999')
                return $id_prefix . '-A0001';
            // return "You have reached the bottom";
            $digits = str_pad('0', strlen($digits), '0', STR_PAD_LEFT);
            if (substr($alphabets_p, -1) == 'Z') {
                $digits = substr($digits, 0, -1);
            }
        }

        // Increment the last ID by 1
        $nextId = str_pad((intval($digits) + 1), strlen($digits), '0', STR_PAD_LEFT);

        $unique_id = $id_prefix . "-" . $alphabets . $nextId;
        $update_unique_header_query = "UPDATE `unique_id_header_all` SET `last_id`='$unique_id', `modified_on`='$system_date_time'  WHERE `table_name`='$table_name'";

    $update_unique_header_res = $mysqli->query($update_unique_header_query);
    return $unique_id;
    }



     
}


function check_digital_verification($mysqli)
{

    $get_setting_query = "SELECT `digital_verification` FROM `factory_setting_header_all` WHERE `digital_verification`='0' ";
    $get_setting_res = $mysqli->query($get_setting_query);
    $get_setting_arr = mysqli_fetch_assoc($get_setting_res);


    if (!empty($get_setting_arr)) {
        $response[] = ["error_code" => 401, "message" => "The digital verification service is currently not available due to some technical issue. Please try after some time."];
        echo json_encode($response);
        return;
    }
}
function get_base_url()
{
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    $host = $_SERVER['HTTP_HOST'];
    $script = $_SERVER['SCRIPT_NAME'];
    $path = dirname($script);

    $base_url = $protocol . $host . $path;

    if (substr($base_url, -1) != '/') {
        $base_url .= '/';
    }

    return $base_url;
}



function upload_document1($upload_file)
{
    // Target directory for saving uploaded files
    $target_dir_profile = __DIR__ . "/active_folder/agency/member/verifications/document_image/";

    // Get file extension
    $ext = pathinfo($upload_file['name'], PATHINFO_EXTENSION);

    // Generate a unique file name
    $file_name = uniqid();

    // Path to save the uploaded file
    $file_path = $target_dir_profile . "docs-" . $file_name . "." . $ext;

    // Move the uploaded file to the target directory
    move_uploaded_file($upload_file['tmp_name'], $file_path);
    $base_url = get_base_url();
    // Define the base URL
    $base_url = $base_url . '/active_folder/agency/member/verifications/document_image/';

    // Construct the full URL of the uploaded file
    $emp_imageURL = $base_url . "docs-" . $file_name . "." . $ext;

    // Return the URL of the uploaded file
    return $emp_imageURL;
}

function upload_document($upload_file)
{
    // Target directory for saving uploaded files
    $target_dir_profile = __DIR__ . "/active_folder/agency/member/verifications/document_image/";

    // Get file extension
    $ext = strtolower(pathinfo($upload_file['name'], PATHINFO_EXTENSION));

    // Generate a unique file name
    $file_name = uniqid();

    // Path to save the uploaded file
    $file_path = $target_dir_profile . "docs-" . $file_name . "." . $ext;

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

    // Resize image dimensions (e.g., reduce to 10% of original size)
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

    // if ($fileSize > $maxSize) {
    //     imagedestroy($image);
    //     imagedestroy($resized_image);
    //     throw new Exception('Cannot resize the image to be within the desired file size range without sacrificing readability.');
    // }

    // Save the final image to the target path
    file_put_contents($file_path, $imageData);
    imagedestroy($image);
    imagedestroy($resized_image);

    // Define the base URL dynamically
    $base_url = get_base_url();

    // Construct the full URL of the uploaded file
    $emp_imageURL = $base_url . "active_folder/agency/member/verifications/document_image/docs-" . $file_name . "." . $ext;

    // Return the URL of the uploaded file
    return $emp_imageURL;
}
