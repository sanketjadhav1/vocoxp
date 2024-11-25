<?php

include 'connection.php';
// error_reporting(E_ALL & ~E_DEPRECATED);
// ini_set('display_errors', 1);

// ini_set('display_errors', 1);
$connection = connection::getInstance();
$mysqli = $connection->getConnection();
///////////////////////////////////////////////
$connection1 = database::getInstance();
$mysqli1 = $connection1->getConnection();

date_default_timezone_set('Asia/kolkata');
$system_date = date("y-m-d");
$system_date_time = date("Y-m-d H:i:s");

if ($_SERVER["REQUEST_METHOD"] != "POST" && $_SERVER["REQUEST_METHOD"] != "PUT") {
    $response[] = array("error_code" => 102, "message" => "Please use the 'POST' method to add data or the 'PUT' method to update data");
    echo json_encode($response);
    exit;
}




$json_data = file_get_contents("php://input");
// Decode JSON data into PHP associative array
 $data_array = json_decode($json_data, true);


$mic = $data_array['mic'];
$audio = $data_array['audio'];
$heart_rate = $data_array['heart_rate'];
$blood_pressure = $data_array['blood_pressure'];
$body_temperature = $data_array['body_temperature'];
$sp_o2 = $data_array['sp_o2'];
$bluetooth = $data_array['bluetooth'];
$wifi = $data_array['wifi'];
$sim = $data_array['sim'];
 $item_id = $data_array['item_id'];





$common_check_error = common_check_error($mode, $mic, $audio, $heart_rate, $blood_pressure, $body_temperature, $sp_o2, $bluetooth, $wifi, $sim, $item_id, $mysqli);
if ($common_check_error == 1) {



     if ($_SERVER['REQUEST_METHOD'] == "POST") {
           
       $diagnosis_id = unique_id_genrate('DGN', 'item_diagnostic_report_header_all', $mysqli1);

       $insert_diagnostic_report_query = "INSERT INTO `item_diagnostic_report_header_all`(`diagnosis_id`, `mic`, `audio`, `heart_rate`, `blood_pressure`,`body_temperature`, `sp_o2`, `bluetooth`, `wifi`, `sim`, `item_id`, `created_on`) VALUES ('$diagnosis_id', '$mic', '$audio', '$heart_rate','$blood_pressure', '$body_temperature', '$sp_o2', '$bluetooth', '$wifi', '$sim', '$item_id','$system_date_time')";
       $insert_diagnostic_report_res = $mysqli1->query($insert_diagnostic_report_query);

        if ($insert_diagnostic_report_res == true) {
            $response[] = array("error_code" => 100, "message" => "Diagnostic report add successfully.");
        } else {
            $response[] = array("error_code" => 109, "message" => "Diagnostic report failed to add.");
        }
        echo json_encode($response);
        exit;


    }



    if ($_SERVER['REQUEST_METHOD'] == "PUT") {
    

     $update_diagnostic_report_query = "UPDATE `item_diagnostic_report_header_all`
       SET 
           `mic` = '$mic',
           `audio` = '$audio',
           `heart_rate` = '$heart_rate',
           `blood_pressure` = '$blood_pressure',
           `body_temperature` = '$body_temperature',
           `sp_o2` = '$sp_o2',
           `bluetooth` = '$bluetooth',
           `wifi` = '$wifi',
           `sim` = '$sim',
           `modified_on` = '$system_date_time'
       WHERE 
           `item_id` = '$item_id';
       ";
    $update_diagnostic_report_res = $mysqli1->query($update_diagnostic_report_query);
    if ($update_diagnostic_report_res == true) {
        $response[] = array("error_code" => 100, "message" => "Diagnostic report update successfully.");
    } else {
        $response[] = array("error_code" => 109, "message" => "Diagnostic report failed to update.");
    }
    echo json_encode($response);
    exit;

}


}


function common_check_error($mode, $mic, $audio, $heart_rate, $blood_pressure, $body_temperature, $sp_o2, $bluetooth, $wifi, $sim, $item_id, $mysqli)
{
    $common_check = 1;

    // if (!isset($mode)) {
    //     $response[] = array("error_code" => 107, "message" => "Please pass the parameter of mode");
    //     echo json_encode($response);
    //     exit;

    // }
    // if ($mode == "") {
    //     $response[] = array("error_code" => 108, "message" => "value of mode  can not be empty");
    //     echo json_encode($response);
    //     exit;

    // }
    // if ($mode != "add" && $mode != "update") {
    //     $response[] = array(
    //         "error_code" => 109,
    //         "message" => "value of mode  is not correct. value of mode 'add' or
    //     'update'"
    //     );
    //     echo json_encode($response);
    //     exit;

    // }

    if (!isset($item_id)) {
        $response[] = array("error_code" => 110, "message" => "Please pass the parameter of item_id");
        echo json_encode($response);
        exit;

    }
    if ($item_id == "") {
        $response[] = array("error_code" => 111, "message" => "value of item_id can not be empty");
        echo json_encode($response);
        exit;

    }




    if (!isset($mic)) {
        $response[] = array("error_code" => 112, "message" => "Please pass the parameter of mic");
        echo json_encode($response);
        exit;

    }
    if ($mic == "") {
        $response[] = array("error_code" => 113, "message" => "value of mic  can not be empty");
        echo json_encode($response);
        exit;

    }
    if (!isset($audio)) {
        $response[] = array("error_code" => 114, "message" => "Please pass the parameter of audio");
        echo json_encode($response);
        exit;

    }
    if ($audio == "") {
        $response[] = array("error_code" => 115, "message" => "value of audio can not be empty");
        echo json_encode($response);
        exit;

    }
    if (!isset($heart_rate)) {
        $response[] = array("error_code" => 116, "message" => "Please pass the parameter of heart_rate");
        echo json_encode($response);
        exit;

    }
    if ($heart_rate == "") {
        $response[] = array("error_code" => 117, "message" => "value of heart_rate can not be empty");
        echo json_encode($response);
        exit;

    }
    if (!isset($blood_pressure)) {
        $response[] = array("error_code" => 118, "message" => "Please pass the parameter of blood_pressure");
        echo json_encode($response);
        exit;

    }
    if ($blood_pressure == "") {
        $response[] = array("error_code" => 119, "message" => "value of blood_pressure can not be empty");
        echo json_encode($response);
        exit;

    }
    if (!isset($body_temperature)) {
        $response[] = array("error_code" => 120, "message" => "Please pass the parameter of body_temperature");
        echo json_encode($response);
        exit;

    }
    if ($body_temperature == "") {
        $response[] = array("error_code" => 121, "message" => "value of body_temperature can not be empty");
        echo json_encode($response);
        exit;

    }
    if (!isset($sp_o2)) {
        $response[] = array("error_code" => 122, "message" => "Please pass the parameter of sp_o2");
        echo json_encode($response);
        exit;

    }
    if ($sp_o2 == "") {
        $response[] = array("error_code" => 123, "message" => "value of sp_o2  can not be empty");
        echo json_encode($response);
        exit;

    }
    if (!isset($bluetooth)) {
        $response[] = array("error_code" => 124, "message" => "Please pass the parameter of bluetooth");
        echo json_encode($response);
        exit;

    }
    if ($bluetooth == "") {
        $response[] = array("error_code" => 125, "message" => "value of bluetooth  can not be empty");
        echo json_encode($response);
        exit;

    }
    if (!isset($wifi)) {
        $response[] = array("error_code" => 126, "message" => "Please pass the parameter of wifi");
        echo json_encode($response);
        exit;

    }
    if ($wifi == "") {
        $response[] = array("error_code" => 127, "message" => "value of wifi  can not be empty");
        echo json_encode($response);
        exit;

    }
    if (!isset($sim)) {
        $response[] = array("error_code" => 128, "message" => "Please pass the parameter of sim");
        echo json_encode($response);
        exit;

    }
    if ($sim == "") {
        $response[] = array("error_code" => 129, "message" => "value of sim can not be empty");
        echo json_encode($response);
        exit;

    }




    if (!$mysqli) {
        $response[] = array(
            "error_code" => 101,
            "message" => "An unexpected server error occurred while
            processing your request. Please try after sometime"
        );
        echo json_encode($response);
        exit;

    }

    return $common_check;
}



?>