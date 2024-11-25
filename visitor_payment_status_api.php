
<?php
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);


include 'connection.php';
$connection = connection::getInstance();
$mysqli = $connection->getConnection();

$visitor_id = $_POST['visitor_id'] ?? '';
$agency_id = $_POST['agency_id'] ?? '';


//Check for errors
$check_error = check_error($mysqli, $visitor_id, $agency_id);

if ($check_error == 1) {
    //get visitor payment details
    $visitor_pay_detail = "SELECT `payment_status` FROM `visitor_payment_transaction_all` WHERE `agency_id` = '$agency_id' AND `visitor_id` = '$visitor_id' ORDER BY `inserted_on` DESC LIMIT 1";

    $res_visitor_pay_detail = mysqli_query($mysqli, $visitor_pay_detail);
    $visitor_pay_detail_arr = mysqli_fetch_assoc($res_visitor_pay_detail);

    if (!empty($visitor_pay_detail_arr)) {  //pass payment status
        $response[] = json_encode(['error_code' => 200, 'visitor_id' => $visitor_id, 'payment_status' => $visitor_pay_detail_arr['payment_status']]);
    } else {                                //if visitor payment details not found
        $response[] = json_encode(['error_code' => 400, 'visitor_id' => $visitor_id, 'error' => 'Visitor payment details not found.']);
    }


    echo json_encode($response);
}



function check_error($mysqli, $visitor_id,  $agency_id)
{
    if (!$mysqli) {
        $error_response = array("error_code" => 101, "message" => "An unexpected server error occurred while processing your request. Please try again later.");
        echo json_encode($error_response);
        return 0;
    }
    if ($_SERVER["REQUEST_METHOD"] != "POST") {
        $error_response = array("error_code" => 102, "message" => "Please change the request method to POST");
        echo json_encode($error_response);
        return 0;
    }
    if (empty($visitor_id)) {
        $error_response = array("error_code" => 103, "message" => "The parameter 'visitor_id' is required and cannot be empty");
        echo json_encode($error_response);
        return 0;
    }
    if (empty($agency_id)) {
        $error_response = array("error_code" => 104, "message" => "The parameter 'agency_id' is required and cannot be empty");
        echo json_encode($error_response);
        return 0;
    }

    return 1;
}
