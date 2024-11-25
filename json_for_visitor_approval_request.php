<?php
error_reporting(0);
error_reporting(E_ALL & ~E_DEPRECATED);
 ini_set('display_errors', 0);
 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
 
include __DIR__ . '/vendor/autoload.php'; 
include_once 'connection.php';

header('Content-Type: application/json; charset=UTF-8');
header('Access-Contorl-Allow-Origin: *');

//connection 
$connection = connection::getInstance();
$mysqli = $connection->getConnection();

$connection1 = database::getInstance();
$mysqli1 = $connection1->getConnection();

apponoff($mysqli);  
logout($_SERVER['HTTP_AUTH_KEY'], $mysqli);
$system_date = date("Y-m-d H:i:s");
$agency_id = $_POST['agency_id'] ?? "";
$app_id = $_POST['app_id'] ?? "";
$visitor_id = $_POST['visitor_id'] ?? ""; 
 // $meeting_with = $_POST['meeting_with'] ?? ""; 
// ashishKhandarre@gmail.com

$check_error_res = check_error($mysqli, $mysqli1, $agency_id,$visitor_id);
if ($check_error_res == 1) 
{
	  $data = []; // Array to hold the overall results
     $bulk_req_query = "SELECT *  FROM `visitor_temp_activity_detail_all` WHERE  `visitor_id`='$visitor_id' AND `agency_id`='$agency_id'";
	 $weblink_req_fetch_res = $mysqli->query($bulk_req_query);
	 if ($weblink_req_fetch_res && $weblink_req_fetch_res->num_rows > 0)
	  {
	  	while ($weblink_req_fetch_array = $weblink_req_fetch_res->fetch_assoc()) {
               $meeting_with=$weblink_req_fetch_array["meeting_with"];
                     $emp_query = "SELECT *  FROM `employee_header_all` WHERE  `emp_id`='".$meeting_with."' AND `agency_id`='$agency_id'";
                     $emp_fetch_res = $mysqli->query($emp_query);
                     $emp_fetch_array = $emp_fetch_res->fetch_assoc();
                     $visiter_name=$weblink_req_fetch_array["visitor_name"];
                    $people_with_visitor=$weblink_req_fetch_array["people_with_visitor"];
                     $emp_name=$emp_fetch_array["name"];
	  		   if($emp_fetch_array["visitor_approval_required"]==1)
               {

			          // $data[] = $weblink_req_fetch_array;
                          $email=$emp_fetch_array["email_id"];
                          $web_link="https://mounarchtech.com/vocoxp/approval_request.php?visitor_id=$visitor_id&emp_id=$meeting_with";
                          $mail = new PHPMailer(true);
                            try {
                                $mail->isSMTP();
                                $mail->Host = 'mail.mounarchtech.com';
                                $mail->SMTPAuth = true;
                                $mail->Username = 'transactions@mounarchtech.com';
                                $mail->Password = 'Mtech!@12345678'; // Load this from environment variables in production
                                $mail->SMTPSecure = 'ssl';
                                $mail->Port = 465;
                                $mail->setFrom('transactions@mounarchtech.com', 'Micro Integrated Semi Conductor Systems Pvt. Ltd.');
                                $mail->isHTML(true);
                                $mail->Subject = 'Action Required: Please respond to the request';
                                
                                // Email body
                                $mail->Body = '<!DOCTYPE html>
                                               <html>
                                               <head>
                                                   <style>
                                                       .email-body {
                                                           font-family: Arial, sans-serif;
                                                           line-height: 1.6;
                                                           color: #333;
                                                       }
                                                       .button {
                                                           display: inline-block;
                                                           padding: 10px 20px;
                                                           margin: 20px 0;
                                                           font-size: 16px;
                                                           color: white;
                                                           background-color: #007BFF;
                                                           text-decoration: none;
                                                           border-radius: 5px;
                                                       }
                                                   </style>
                                               </head>
                                               <body>
                                                   <div class="email-body">
                                                       <p>Dear '.$visiter_name.',</p>                                   
                                                       <p>Mr./Ms. '.$emp_name.'&nbsp;want to visit to you with '.$people_with_visitor.' members. Please <a href='.$web_link.'>Click here </a> to respond to the approval request sent by him.</p>
                                                        <p>Thank you.</p>
                                                       <p>Best regards,</p>
                                                       <p>Micro Integrated Semi Conductor Systems Pvt. Ltd.</p>
                                                   </div>
                                               </body>
                                               </html>';
                                
                                // $mail->addAttachment($excel_url);

                                // Send email to each address
                                // foreach ($ids as $email) {
                                    $mail->addAddress($email);
                                   if ($mail->send()) {
                                    // Email successfully sent
                                     $email_status=1;
                                } else {
                                    // Email failed to send
                                     $email_status=0;

                                }
                                    $mail->clearAddresses(); 
                                  $update_query="UPDATE `visitor_temp_activity_detail_all` SET `request_link_url`='$web_link',`sms_status`='1',`email_status`='$email_status' WHERE  `visitor_id`='$visitor_id' AND `agency_id`='$agency_id' AND `meeting_with`='$meeting_with'";
                                     $emp_fetch_res = $mysqli->query($update_query);

                                echo json_encode([
                                    "error_code" => 100,
                                    "message" => "Approval Request Successfully Send."
                                ]);
                                exit;
                                   
                                   
                            } catch (Exception $e) {
                                echo json_encode(["error_code" => 101, "message" => "Mail could not be sent. Mailer Error: {$mail->ErrorInfo}"]);
                            }

                            
                                
               
	           	}
	  	
	  }
  
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



function check_error($mysqli, $mysqli1,   $agency_id,$visitor_id)
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

    if (!isset($visitor_id)) {
        $response = ["error_code" => 105, "message" => "Please pass the parameter of visitor_id"];
        echo json_encode($response);
        return;
    }
    if (empty($visitor_id)) {
        $response = ["error_code" => 106, "message" => "visitor_id can not be empty"];
        echo json_encode($response);
        return;
    }
     

 

    return $check_error;
}

?>
