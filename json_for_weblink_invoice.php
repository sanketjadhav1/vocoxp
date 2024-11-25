<?php
error_reporting(0);
use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

include __DIR__ . '/vendor/autoload.php';
include_once 'connection.php';
error_reporting(E_ALL & ~E_DEPRECATED);
 ini_set('display_errors', 0);
header('Content-Type: application/json; charset=UTF-8');
header('Access-Contorl-Allow-Origin: *');
$connection = connection::getInstance();
$mysqli = $connection->getConnection();

$connection1 = database::getInstance();
$mysqli1 = $connection1->getConnection();
apponoff($mysqli);  
logout($_SERVER['HTTP_AUTH_KEY'], $mysqli);
$system_date = date("Y-m-d H:i:s");
$agency_id = $_GET['agency_id'] ?? "";
$app_id = $_GET['app_id'] ?? "";
$request_no = $_GET['request_no'] ?? "";
$bulk_id = $_GET['bulk_id'] ?? "";


//bulk_end_user_trasaction_all talbe me check karna hai ->all bulk id
//end_user_payment_trasaction_all end_user_id againset//paid_by wallet 
//wallet paymmet trasaction -userid 

$check_error_res = check_error($mysqli, $mysqli1, $agency_id,$request_no,$bulk_id);
if ($check_error_res == 1) 
{
	 $bulk_req_query = "SELECT bulk_id FROM `bulk_weblink_request_all` WHERE `bulk_id` = '$bulk_id' AND agency_id='$agency_id' AND request_no='$request_no'";
	 $weblink_req_fetch_res = $mysqli->query($bulk_req_query);
	 if ($weblink_req_fetch_res && $weblink_req_fetch_res->num_rows > 0)
	  {
			$weblink_req_fetch_array = $weblink_req_fetch_res->fetch_assoc();
			 $data = []; // Array to hold the overall results
			  $user_query = "SELECT * FROM `bulk_end_user_transaction_all` WHERE `bulk_id` = '".$weblink_req_fetch_array["bulk_id"]."'";
			  $user_fetch_res = $mysqli->query($user_query);
			  if ($user_fetch_res && $user_fetch_res->num_rows > 0)
			  {
			  	 while ($user_fetch_array = $user_fetch_res->fetch_assoc()) 
			  	 {	
			  	 	$invoice_id=""; 
				  	$invoice_url="";

			  	 	if($user_fetch_array["payment_from"]==1)
			  	 	{
			  	 		$enduser_id_value=$user_fetch_array["end_user_id"];
				  	 	$wallet_query = "SELECT * FROM `wallet_payment_transaction_all` WHERE FIND_IN_SET('$enduser_id_value', `user_id`) > 0 AND `agency_id` = '".$weblink_req_fetch_array["agency_id"]."'  ";
				  		$wallet_fetch_res = $mysqli->query($wallet_query);
				  		

				  	 	if ($wallet_fetch_res && $wallet_fetch_res->num_rows > 0)
				  		{
				  			$wallet_fetch_array = $wallet_fetch_res->fetch_assoc();
				  			$invoice_id=$wallet_fetch_array["transaction_id"]; 
					  		$invoice_url="";
				  		}
				  		else
				  		{
				  			$invoice_id=""; 
					  		$invoice_url="";
				  		}
			  	 	}
			  	 	elseif ($user_fetch_array["payment_from"]==2) 
			  	 	{  
				  	 	$enduser_id_value=$user_fetch_array["end_user_id"];
				  	 	$pay_query = "SELECT * FROM `end_user_payment_transaction_all` WHERE FIND_IN_SET('$enduser_id_value', `end_user_id`) > 0 AND `bulk_id` = '".$weblink_req_fetch_array["bulk_id"]."'  ";
				  		$pay_fetch_res = $mysqli->query($pay_query);
				  		

				  	 	if ($pay_fetch_res && $pay_fetch_res->num_rows > 0)
				  		{
				  			$pay_fetch_array = $pay_fetch_res->fetch_assoc();
				  			$invoice_id=$pay_fetch_array["paid_transaction_id"]; 
					  		$invoice_url=$pay_fetch_array["invoice_url"];
				  		}
				  		else
				  		{
				  			$invoice_id=""; 
					  		$invoice_url="";
				  		}
			  		}
			  	 	 $user_data = [
			                        "end_user_id" => $user_fetch_array["end_user_id"],
			                        "type" => $user_fetch_array["obj_name"],
			                        "name" => $user_fetch_array["name"],
			                        "mobile" => $user_fetch_array["mobile"],
			                        "email_id" => $user_fetch_array["email_id"],
			                        "invoice_id" => $invoice_id, 
			                        "invoice_url" => $invoice_url
			                    ];
			  		
			       $data[] = $user_data;
			  	 }
			echo json_encode([
	                "error_code" => 100,
	                "message" => "End users successfully fetched.",
	                "data" => $data
	            ]);
	            exit;
			}
			
	      }
	      else
	      {
	      	echo json_encode([
	                            "error_code" => 101,
	                            "message" => "record not found againest this id  $agency_id,$bulk_id,$request_no."
	                        ]);
	                        exit;
	      }
	 }

 
function check_error($mysqli, $mysqli1,   $agency_id,$request_no,$bulk_id)
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

    if (!isset($request_no)) {
        $response = ["error_code" => 105, "message" => "Please pass the parameter of request_no"];
        echo json_encode($response);
        return;
    }
    if (empty($request_no)) {
        $response = ["error_code" => 106, "message" => "request_no can not be empty"];
        echo json_encode($response);
        return;
    }
     if (!isset($bulk_id)) {
        $response = ["error_code" => 105, "message" => "Please pass the parameter of bulk_id"];
        echo json_encode($response);
        return;
    }
    if (empty($bulk_id)) {
        $response = ["error_code" => 106, "message" => "bulk_id can not be empty"];
        echo json_encode($response);
        return;
    }

 

    return $check_error;
}

?>