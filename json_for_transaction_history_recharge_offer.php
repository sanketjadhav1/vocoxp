<?php
/* 
Name : json_for_transaction_history_recharge_offer.php
Version of the Requirment Document  : 2.0.1


Purpose :- This fetch API use to get data of Recharge Offer
Mode :- single mode

Developed By - Rishabh Shinde
*/
// error_reporting(1);
include_once 'connection.php';

// Now you can use the connection class
$connection = connection::getInstance();
$mysqli = $connection->getConnection();
apponoff($mysqli);
logout($_SERVER['HTTP_AUTH_KEY'], $mysqli);
$check_error=check_error($mysqli, $_POST['agency_id']);

if($check_error==1){

    $agency_id=$_POST['agency_id'];

    
    $fetch_agency_id = "SELECT `agency_id`, `status` FROM `agency_header_all` WHERE `agency_id`='$agency_id'";
$result_agency = mysqli_query($mysqli, $fetch_agency_id);
$arr_agency = mysqli_fetch_assoc($result_agency);

if (!$arr_agency) {
    $response[] = array("error_code" => 102, "message" => "Please enter the valid agency id");
    echo json_encode($response);
    return;
} elseif ($arr_agency['status'] != "1") {
    $response[] = array("error_code" => 103, "message" => "Please enter active agency id");
    echo json_encode($response);
    return;
}

$fetch_wallet = "SELECT `offer_id`,
`offer_name`,
`recharge_amount`,
`add_on_amount`,
`valid_till` FROM `offer_information_all` WHERE `status`=1 AND CURDATE() BETWEEN `applicable_from` AND `valid_till`";

$result_wallet = mysqli_query($mysqli, $fetch_wallet);

if ($result_wallet) {
    $arr_wallet = array();

    while ($row = mysqli_fetch_assoc($result_wallet)) {
        $arr_wallet[] = $row;
    }

    $response = array(); 
$data = array(); 

foreach ($arr_wallet as $offer) {
    $response[] = array(
        "offer_id" => $offer['offer_id'],
        "offer_name" => $offer['offer_name'],
        "recharge_amount" => $offer['recharge_amount'],
        "cashback_amount" => $offer['add_on_amount'],
        "valid_till" => date('d-m-Y', strtotime($offer['valid_till']))
    );
}

if (!empty($response)) {
    $data[] = ["error_code" => 100, "message" => "Successfully Fetch", "data" => $response];
    echo json_encode($data);
    return;
} else {
    $data[] = ["error_code" => 107, "message" => "Data not found"];
    echo json_encode($data);
    return;
}

 }


}

function check_error($mysqli, $agency_id){

    $check_error=1;
    if(!$mysqli){
        $response[]=["error_code" => 101, "message" => "An unexpected server error occurred while
        processing your request. Please try after sometime"];
        echo json_encode($response);
        return;
    }
    if($_SERVER["REQUEST_METHOD"] != "POST"){
        $responce[] = array( "error_code" => 104, "message" => "please change request method to POST");
        echo json_encode($responce);
        return; 
    
            
    }
    if(!isset($agency_id)){
        $response[]=["error_code" => 105, "message" => "Please the parameter - agency_id"];
        echo json_encode($response);
        return;
    }else{
        if($agency_id==""){
            $response[]=["error_code" => 106, "message" => "Value of 'agency_id' cannot be empty"];
            echo json_encode($response);
            return;
        }
    }
   
    return $check_error;
}
?>