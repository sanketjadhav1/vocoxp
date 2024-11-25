<?php

 //error_reporting(E_ALL & ~E_DEPRECATED);
 ini_set('display_errors', 0);
require_once __DIR__ . '/vendor/autoload.php'; 
header('Content-Type: application/json; charset=UTF-8');
header('Access-Contorl-Allow-Origin: *');
date_default_timezone_set('Asia/kolkata');
include 'connection.php';
$connection = connection::getInstance();
$mysqli = $connection->getConnection();

$connection1 = database::getInstance();
$mysqli1 = $connection1->getConnection();
// apponoff($mysqli);  
// logout($_SERVER['HTTP_AUTH_KEY'], $mysqli);

date_default_timezone_set('Asia/kolkata');
$system_date = date("y-m-d");
$system_date_time = date("Y-m-d H:i:s");

 
$application_id  = $_POST['application_id'] ?? '';
$initiated_on  = $system_date;
$completed_on = $system_date;
$agency_id = $_POST['agency_id'] ?? '';
$visitor_id = $_POST['visitor_id'] ?? ''; 
  
$verification_id = $_POST['verification_id'] ?? '';
$aadhar_number = $_POST['aadhar_number'] ?? '';
//verification for direct or visitor 
$aadhar_mode = $_POST['aadhar_mode'] ?? '';
 
//mode for create or update
$mode=$_POST['mode'] ?? '';
 

$admin_id=$_POST['admin_id'];
if($admin_id!=""){
    $admin_id = $_POST['admin_id'] ?? '';
}else{
    $admin_id = $_POST['agency_id'] ?? '';
}
$reason="";
$check_error_res =check_error($mysqli, $mysqli1, $application_id, $verification_id, $agency_id);
if ($check_error_res == 1) 
{
      
       
             $verification_data = json_decode(generate_aadhaar_otp($aadhar_number), true); 
            if($aadhar_mode=="get_aadhar_otp")
            {
                 if (!empty($verification_data))  
                {
                      if ($verification_data['status'] == '200') {

                         $postdata[] = array( 
                                "transaction_id" => $verification_data['transaction_id']
                                ); 
                        $response = [
                            "error_code" => 100,
                            "message" => "OTP has been successfully sent on aadhar linked mobile number",
                              "data"=>$postdata
                            
                        ];
                        echo json_encode($response);
                        return;
                      } 
                      else 
                      {
                           $message = $verification_data['error']['message'];
                            $response = [
                                "error_code" => 199,
                                "message" => $message
                            ];
                            echo json_encode($response);
                            return;
                    }
                }
                else
                 {
                    $message = 'Aadhar number is invalid. Please provide the valid aadhar number .';
                    $response = [
                        "error_code" => 199,
                        "message" => $message
                    ];
                    echo json_encode($response);
                    return;
                }    


        }
        elseif ($aadhar_mode == "verify_aadhar_otp")
        {
            
                    $transaction_id = $_POST['transaction_id'];
                    $otp = $_POST['otp'];

                    // Call function to verify OTP
                    $verification_data = json_decode(verify_aadhaar_otp($otp, $transaction_id), true);
                    // print_r($verification_data);
                    if (!empty($verification_data)) 
                    {
                        if ($verification_data['status'] == '200') 
                        { 
                            // return;
                            $aadhaar_data = $verification_data['data']['aadhaar_data']; 
                            if($mode == "create")
                            {
                                    // $verification_data=json_decode($_POST["aadhar_response"],true);
                                    // print_r($verification_data);
                                    if ($verification_data['status'] == '200') 
                                    {
                                          
                                            $visitor_query = "SELECT * FROM `visitor_temp_activity_detail_all`  WHERE `agency_id`='$agency_id' AND `visitor_id`='$visitor_id'";
                                            $res_visitor = mysqli_query($mysqli, $visitor_query);
                                            $arr_vistor = mysqli_fetch_assoc($res_visitor);
                                            $emp_id=$arr_vistor["meeting_with"];
                                            $visitor_location_id=$arr_vistor["visitor_location_id"];
                                            $name = $arr_vistor['first_name'];
                                            $dob1 = $arr_vistor['dob'];
                                            $gender = $arr_vistor['gender'];
                                            $address = $arr_vistor['address'];
                                            $user_img = $arr_vistor['user_photo'];
                                            $front_img = $arr_vistor['front_photo'];
                                            $back_img = $arr_vistor['back_photo'];
                                            $aadhar_number = $arr_vistor['aadhar_number'];

                                            $emp_query = "SELECT * FROM `employee_header_all`  WHERE `agency_id`='$agency_id' AND `emp_id`='$emp_id'";
                                            $res_emp = mysqli_query($mysqli, $emp_query);
                                            $arr_emp = mysqli_fetch_assoc($res_emp);
                                            $emp_id=$arr_emp["emp_id"];

                                    $addess1_aadhar= $aadhaar_data["vtc_name"].",".$aadhaar_data["landmark"].",".$aadhaar_data["locality"].",".$aadhaar_data["district"].",".$aadhaar_data["pincode"];
                                            $fetch_wallet = "SELECT `current_wallet_bal` FROM `agency_header_all` WHERE `agency_id`='$agency_id'";
                                            $res_wallet = mysqli_query($mysqli, $fetch_wallet);
                                            $arr_wallet = mysqli_fetch_assoc($res_wallet);
                                            
                                           
                                            // check verification_paid_by wallet or end user
                                            if($arr_emp["verification_paid_by"]=="W")
                                            {


                                                $visitor_location_query = "SELECT * FROM `visitor_location_setting_details_all`  WHERE `agency_id`='$agency_id' AND `visitor_location_id`='$visitor_id'";
                                                $res_visitor_location = mysqli_query($mysqli, $visitor_location_query);
                                                $arr_vistor_location = mysqli_fetch_assoc($res_visitor_location); 
                                                $verification_amt=$arr_vistor_location["verification_amt"];
                                                
                                                
                                               
                                                $gst = $sgst_amount + $cgst_amount;
                                                $sgst_amt = $verification_amt * $sgst_amount / 100;
                                                $cgst_amt = $verification_amt * $cgst_amount / 100;
                                                $grand_total = $verification_amt * $gst / 100;
                                                $grand_total1=$grand_total+$verification_amt;

                                                if ($arr_wallet['current_wallet_bal'] < $grand_total1) 
                                                {
                                                    $responce = ["error_code" => 113, "message" => "Your wallet balance is too Low   To proceed. Please recharge your wallet."];
                                                    echo json_encode($responce);
                                                    return;
                                                } 
                                                else
                                                {

                                                      $deducted_amt=$arr_wallet['current_wallet_bal'] - $grand_total- $verification_amt;
                                                     $total=$verification_amt+$sgst_amt+$cgst_amt;
                                                    
                                                     
                                                    
                                                      $postdata  = array( 
                                                        "transaction_id" => $verification_data['transaction_id'],
                                                          'visitor_id' => $visitor_id ,
                                                         'emp_id' => $emp_id ,
                                                         'agency_id' => $agency_id ,
                                                         'aadhar_number' => $aadhar_number ,
                                                         'org_aadhar_name' => $aadhaar_data["name"] ,
                                                         'document_type' => $aadhaar_data["document_type"] ,
                                                         'reference_id' => $aadhaar_data["reference_id"] ,
                                                         'org_date_of_birth' => date("d-m-Y",strtotime($aadhaar_data["date_of_birth"])) ,
                                                         'org_gender' => $aadhaar_data["gender"] ,
                                                         'org_mobile' => $aadhaar_data["mobile"] , 
                                                         'org_district' => $aadhaar_data["district"] ,
                                                         'org_locality' => $aadhaar_data["locality"] ,
                                                         'org_landmark' => $aadhaar_data["landmark"] ,
                                                         'org_state' => $aadhaar_data["state"] ,
                                                         'org_pincode' => $aadhaar_data["pincode"] ,
                                                         'org_country' => $aadhaar_data["country"] ,
                                                         'org_vtc_name' => $aadhaar_data["vtc_name"] ,
                                                         'current_wallet_bal' => number_format($deducted_amt, 2, '.', ''), 
                                                         'name' =>$name,
                                                          'dob' =>date("d-m-Y",strtotime($dob1)),
                                                          'gender' =>$gender,
                                                          'address' =>$address,
                                                          'addess1_aadhar' =>$addess1_aadhar,
                                                          'user_photo' =>$user_img,
                                                           'front_img' =>$front_img,
                                                          'back_img' =>$back_img
                                                      );
                                                    //  $response = [
                                                    //     "error_code" => 100,
                                                    //     "message" => "Data successfully fetche.", 
                                                    //     "data"=>$postdata
                                                          
                                                    // ];
                                                    // echo json_encode($response);
                                                }
                                            }  
                                            else
                                            {
                                                 $postdata = array( 
                                                        "transaction_id" => $verification_data['transaction_id'],
                                                         'visitor_id' => $visitor_id ,
                                                         'emp_id' => $emp_id ,
                                                         'agency_id' => $agency_id ,
                                                         'aadhar_number' => $aadhar_number ,
                                                         'org_aadhar_name' => $aadhaar_data["name"] ,
                                                         'document_type' => $aadhaar_data["document_type"] ,
                                                         'reference_id' => $aadhaar_data["reference_id"] ,
                                                         'org_date_of_birth' => date("d-m-Y",strtotime($aadhaar_data["date_of_birth"])) ,
                                                         'org_gender' => $aadhaar_data["gender"] ,
                                                         'org_mobile' => $aadhaar_data["mobile"] , 
                                                         'org_district' => $aadhaar_data["district"] ,
                                                         'org_locality' => $aadhaar_data["locality"] ,
                                                         'org_landmark' => $aadhaar_data["landmark"] ,
                                                         'org_state' => $aadhaar_data["state"] ,
                                                         'org_pincode' => $aadhaar_data["pincode"] ,
                                                         'org_country' => $aadhaar_data["country"] ,
                                                         'org_vtc_name' => $aadhaar_data["vtc_name"] ,
                                                         'current_wallet_bal' => number_format($arr_wallet['current_wallet_bal'], 2, '.', ''), 
                                                          'name' =>$name,
                                                          'dob' =>date("d-m-Y",strtotime($dob1)),
                                                          'gender' =>$gender,
                                                          'address' =>$address,
                                                          'addess1_aadhar' =>$addess1_aadhar,
                                                          'user_photo' =>$user_img,
                                                          'front_img' =>$front_img,
                                                          'back_img' =>$back_img
                                                      );
                                                    //  $response = [
                                                    //     "error_code" => 100,
                                                    //     "message" => "Data successfully fetche.", 
                                                    //     "data"=>$postdata
                                                          
                                                    // ];
                                                    // echo json_encode($response);
                                            }

                                             $url = get_base_url() . '/aadhar_pdf_visitor.php';

                                            // Initialize cURL session
                                            $ch = curl_init($url);

                                            // Configure cURL options
                                            curl_setopt($ch, CURLOPT_POST, true);
                                            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postdata));
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
                                }
                         }
                        else
                        {
                             if($mode == "update")
                                {
                                    $visitor_id = $_POST['visitor_id'];
                                    $authneticate=$_POST['authneticate'] ?? '';
                                    $query = "UPDATE `visitor_aadhar_details_all` 
                                                  SET `is_athenticate` = '$authneticate' 
                                                  WHERE `visitor_id` = '$visitor_id' AND `agency_id` = '$agency_id' ";

                                        // Prepare and execute the query
                                        $res_query = mysqli_query($mysqli, $query);
                                        $responce = ["error_code" => 100, "message" => "Aadhar number details successfully updated"];
                                    echo json_encode($responce);
                                    return;
                                }
                        }
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
                } 
                else
                 {
                    $message = 'Validation server is down. Try after some time.';
                    $response = [
                        "error_code" => 199,
                        "message" => $message
                    ];
                    echo json_encode($response);
                    return;
                }


            
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

function generate_aadhaar_otp($aadhar_number)
{
    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => "https://api.gridlines.io/aadhaar-api/boson/generate-otp",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_VERBOSE => 0,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "{\n  \"aadhaar_number\": \"$aadhar_number\",\n  \"consent\": \"Y\"\n}",
        CURLOPT_HTTPHEADER => [
            "Content-Type: application/json",
            "X-API-Key: C8EbVBaNqR4g3vhBAiPXdt8cLPkNLJoL",
            "X-Auth-Type: API-Key"
        ],
    ]);

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        //echo "cURL Error #:" . $err;
        return "cURL Error #:" . $err;
    } else {
        //echo $response;
        return $response;
    }
}

function verify_aadhaar_otp($otp, $transaction_id)
{
    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => "https://api.gridlines.io/aadhaar-api/boson/submit-otp",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_VERBOSE => 0,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "{\n  \"otp\": $otp,\n  \"include_xml\": false,\n  \"share_code\": \"1234\"\n}",
        CURLOPT_HTTPHEADER => [
            "Content-Type: application/json",
            "X-API-Key: C8EbVBaNqR4g3vhBAiPXdt8cLPkNLJoL",
            "X-Auth-Type: API-Key",
            "X-Transaction-ID: $transaction_id"
        ],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);
    if ($err) {
        return "cURL Error #:" . $err;
    } else {
        return $response;
    }
}

 
function isValidAadhar($pan)
{
    // Regular expression to match PAN format
    $pattern = '/(^[0-9]{4}[0-9]{4}[0-9]{4}$)|(^[0-9]{4}\s[0-9]{4}\s[0-9]{4}$)|(^[0-9]{4}-[0-9]{4}-[0-9]{4}$)/';

    // Check if the PAN matches the pattern
    if (preg_match($pattern, $pan)) {
        return 1; // PAN is valid
    } else {
        return 0; // PAN is invalid
    }
}
  
 
?>