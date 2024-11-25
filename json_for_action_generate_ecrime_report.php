
<?php

/*Prepared by: Ashish Khandare
Name of API:  json_for_action_generate_ecrime_report
Method: POST
Category: Action
Description:
This API is to use generate e-crime details
Developed by: Akshay Patil
Note: single mode
mode: register_employee*/
error_reporting(1);

// this json is used to add family member of resident.
header('Content-Type: application/json; charset=UTF-8');
header('Access-Contorl-Allow-Origin: *');
date_default_timezone_set('Asia/kolkata');
include 'connection.php';
// Now you can use the connection class
 $connection = connection::getInstance();
 $mysqli = $connection->getConnection();
date_default_timezone_set('Asia/kolkata');
$system_date = date("y-m-d");
$system_date_time = date("Y-m-d H:i:s");
$output = $responce = array();
apponoff($mysqli);
logout($_SERVER['HTTP_AUTH_KEY'], $mysqli);

    
    
if ($_SERVER["REQUEST_METHOD"] != "POST") 
{
	$response[]=["error_code"=>101,"message"=>"Please use POST method"];
	echo json_encode($response);
	return;
}


$check_error_res=check_error($mysqli,$_POST['agency_id'],$_POST['member_id'],$_POST['aadhar_name'],$_POST['father_name'],$_POST['dob'],$_POST['address'],$_POST['name_as_per_doc'],$_POST['application_id'],$_POST['specification_id'],$_POST['request_id']);
if($check_error_res==1) 
{
	
	$agency_id=$_POST['agency_id'];
	$member_id=$_POST['member_id'];
	$aadhar_name=$_POST['aadhar_name'];
	$father_name=$_POST['father_name'];
	$dob=$_POST['dob'];
	$address=$_POST['address'];
	$name_as_per_doc=$_POST['name_as_per_doc'];
	$application_id=$_POST['application_id'];
	$specification_id=$_POST['specification_id'];
	$request_id=$_POST['request_id'];


	$res=json_decode(get_request_id($aadhar_name,$father_name,$dob,$address,$name_as_per_doc),true);
		
		if(($res['status']=='OK'))
		{
			$e_crime_request_id=$res['requestId'];
		// $sql="UPDATE `v_e_crime_active_transaction_all` SET `e_crime_request_id`='$e_crime_request_id',verification_status='Initiated',`modified_on`='".date('Y-m-d H:i:s')."' WHERE `request_id`='$request_id' and `application_id`='$application_id' and `person_id`='$member_id' and `specification_id`='$specification_id' ";
$curr_date=date('Y-m-d H:i:s');
        $sql = "UPDATE `v_e_crime_active_transaction_all` SET `e_crime_request_id` = '$e_crime_request_id', `verification_status` = 'Generating Report', `modified_on` = '$curr_date', `application_id` = '$application_id', `person_id` = '$member_id', `specification_id` = '$specification_id' WHERE `request_id` = '$request_id'";


			$res_sql=mysqli_query($mysqli,$sql);
			if($res_sql===true)
			{
				$response[]=["error_code"=>100,"message"=>"Verification Details successfully submitted"];
	            echo json_encode($response);
	            return;
			}
			else{
				$response[]=["error_code"=>101,"message"=>"Verification Details not submitted"];
				    echo json_encode($response);
				    return;
			
			}
			
		}
		
	


}	
else{
echo $common_chk_error_res;
}
function check_error($mysqli,$agency_id,$member_id,$aadhar_name,$father_name,$dob,$address,$name_as_per_doc,$application_id,$specification_id,$request_id)

{
	$check_error=1;
	
		
	
    if(!isset($agency_id))
	{
		$response[]=["error_code"=>102,"message"=>"agency_id parameter is missing"];
		echo json_encode($response);
		return;
	}
     if(empty($agency_id))
	{
		$response[]=["error_code"=>102,"message"=>"agency_id parameter is empty"];
		echo json_encode($response);
		return;
	}
	$sql="SELECT `agency_id` FROM `agency_header_all` WHERE `agency_id`='$agency_id'";
	$res_sql=mysqli_query($mysqli,$sql);
	$agency_data=mysqli_fetch_assoc($res_sql);
	
	if(mysqli_num_rows($res_sql)==0)
	{
		$response[] = ["error_code" => 102, "message" => "agency_id is not valid"];  //agency id not valid
		echo json_encode($response);
		return;   
	}
	// else if($agency_data['status']!='active') // 	check agency_id is active
	// {
	// 	$response []= ["error_code" => 107, "message" => "agency_id is not active"]; 
	// 	echo json_encode($response);
	// 	return; 
	// }
    if(!isset($member_id))
	{
		$response[]=["error_code"=>103,"message"=>"member_id parameter is missing"];
		echo json_encode($response);
		return;
	}
     if(empty($member_id))
	{
		$response[]=["error_code"=>103,"message"=>"member_id parameter is empty"];
		echo json_encode($response);
		return;
	}
	$sql="SELECT `member_id` FROM `member_header_all` WHERE `member_id`='$member_id'";
	$res_sql=mysqli_query($mysqli,$sql);
	$agency_data=mysqli_fetch_assoc($res_sql);
	
	if(mysqli_num_rows($res_sql)==0)
	{
		$response []= ["error_code" => 103, "message" => "member_id is not valid"];  //agency id not valid
		echo json_encode($response);
		return;   
	}

	// elseif($agency_data['status']!='active') // 	check agency_id is active
	// {
	// 	$response[] = ["error_code" => 107, "message" => "member_id is not active"]; 
	// 	echo json_encode($response);
	// 	return;  ///agency is not active
	// }

    if(!isset($aadhar_name))
	{
		$response[]=["error_code"=>104,"message"=>"aadhar_name parameter is missing"];
		echo json_encode($response);
		return;
	}
     if(empty($aadhar_name))
	{
		$response[]=["error_code"=>104,"message"=>"aadhar_name parameter is empty"];
		echo json_encode($response);
		return;
	}
    if(!isset($father_name))
	{
		$response[]=["error_code"=>105,"message"=>"father_name parameter is missing"];
		echo json_encode($response);
		return;
	}
     if(empty($father_name))
	{
		$response[]=["error_code"=>105,"message"=>"father_name parameter is empty"];
		echo json_encode($response);
		return;
	}
    if(!isset($dob))
	{
		$response[]=["error_code"=>106,"message"=>"dob parameter is missing"];
		echo json_encode($response);
		return;
	}
     if(empty($dob))
	{
		$response[]=["error_code"=>106,"message"=>"dob parameter is empty"];
		echo json_encode($response);
		return;
	}
    if(!isset($address))
	{
		$response[]=["error_code"=>107,"message"=>"address parameter is missing"];
		echo json_encode($response);
		return;
	}
     if(empty($address))
	{
		$response[]=["error_code"=>107,"message"=>"address parameter is empty"];
		echo json_encode($response);
		return;
	}
    if(!isset($name_as_per_doc))
	{
		$response[]=["error_code"=>108,"message"=>"name_as_per_doc parameter is missing"];
		echo json_encode($response);
		return;
	}
     if(empty($name_as_per_doc))
	{
		$response[]=["error_code"=>108,"message"=>"name_as_per_doc parameter is empty"];
		echo json_encode($response);
		return;
	}

	if(!isset($application_id))
	{
		$response[]=["error_code"=>109,"message"=>"application_id parameter is missing"];
		echo json_encode($response);
		return;
	}
     if(empty($application_id))
	{
		$response[]=["error_code"=>109,"message"=>"application_id parameter is empty"];
		echo json_encode($response);
		return;
	}

	if(!isset($specification_id))
	{
		$response[]=["error_code"=>110,"message"=>"specification_id parameter is missing"];
		echo json_encode($response);
		return;
	}
     if(empty($specification_id))
	{
		$response[]=["error_code"=>110,"message"=>"specification_id parameter is empty"];
		echo json_encode($response);
		return;
	}
	if(!isset($request_id))
	{
		$response[]=["error_code"=>111,"message"=>"request_id parameter is missing"];
		echo json_encode($response);
		return;
	}
     if(empty($request_id))
	{
		$response[]=["error_code"=>111,"message"=>"request_id parameter is empty"];
		echo json_encode($response);
		return;
	}


    

return $check_error;
}
	
	
		


function get_request_id($aadhar_name,$father_name,$dob,$address,$name_as_per_doc)
{
	
	$callback_url="https://collegiate-chatbot.000webhostapp.com/e-crime-test.php/save-print.php";
	
	$ch = curl_init();
	curl_setopt_array($ch, array(
		CURLOPT_URL => "https://crime.getupforchange.com/api/v3/addReport",
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 30,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "POST",
		CURLOPT_POSTFIELDS => "name=".$aadhar_name."&fatherName=".$father_name."&dob=".$dob."&address=".$address."&name_as_per_doc=".$name_as_per_doc."&panNumber=CUWPP7802H&callbackUrl=".$callback_url."&reportMode=realTimeHighAccuracy",
		CURLOPT_HTTPHEADER => array(
		  "content-type: application/x-www-form-urlencoded"
		),
	  ));
	  

	curl_setopt($ch, CURLOPT_USERPWD, "MIscos-production-FgArn" . ":" . "");///live
	   //curl_setopt($ch, CURLOPT_USERPWD, "MiscosInDev-sandbox-WFjmQ" . ":" . "");///testing

	
	$response = curl_exec($ch);
	$err = curl_error($ch);
	
	if ($err) {
		return $err;
	} else {
		return $response;
	}
}


    
?>
