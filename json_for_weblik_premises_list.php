<?php
error_reporting(0);
error_reporting(E_ALL & ~E_DEPRECATED);
 ini_set('display_errors', 0);

include __DIR__ . '/vendor/autoload.php';
include_once 'connection.php';

header('Content-Type: application/json; charset=UTF-8');
header('Access-Contorl-Allow-Origin: *');

//connection 
$connection = connection::getInstance();
$mysqli = $connection->getConnection();

$connection1 = database::getInstance();
$mysqli1 = $connection1->getConnection();

// apponoff($mysqli);  
// logout($_SERVER['HTTP_AUTH_KEY'], $mysqli);
$system_date = date("Y-m-d H:i:s");
$agency_id = $_GET['agency_id'] ?? "";
$app_id = $_GET['app_id'] ?? "";
$request_no = $_GET['request_no'] ?? "";
$bulk_id = $_GET['bulk_id'] ?? "";


$check_error_res = check_error($mysqli, $mysqli1, $agency_id);
if ($check_error_res == 1) 
{
	 $data = []; // Array to hold the overall results
     $bulk_req_query = "SELECT DISTINCT premises_location  FROM `bulk_weblink_request_all` WHERE `premises_location`!=''  AND `agency_id`='$agency_id'";
	 $weblink_req_fetch_res = $mysqli->query($bulk_req_query);
	 if ($weblink_req_fetch_res && $weblink_req_fetch_res->num_rows > 0)
	  {
	  	while ($weblink_req_fetch_array = $weblink_req_fetch_res->fetch_assoc()) {
	  		 
			       $data[] = $weblink_req_fetch_array;
	  	}
	  	echo json_encode([
	                "error_code" => 100,
	                "message" => " Details Successfully Fetched.",
	                "data" => $data
	            ]);
	            exit;
	  }
   else
      {
      	echo json_encode([
                            "error_code" => 101,
                            "message" => "record not found  ."
                        ]);
                        exit;
      }
}




function check_error($mysqli, $mysqli1,   $agency_id)
{

    $check_error = 1;
    if (!$mysqli) {
        $response = ["error_code" => 101, "message" => "Unable to proceed your request, please try again after some time"];
        echo json_encode($response);
        return;
    }

    if ($_SERVER['REQUEST_METHOD'] != "GET") {
        $response = array("error_code" => 102, "message" => "Request Method is not Post");
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

?>
