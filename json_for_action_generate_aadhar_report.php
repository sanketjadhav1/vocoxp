
<?php


error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
ini_set('display_errors', 0);

// require_once("../individual_connection.php");
// require_once("../verification_api/functions.php");
/*Prepared by: Sahil Chavan
Name of API: : json_for_action_generate_aadhar_report.
Method: POST
Category: Action
Description:
 This API is to use generate aadhar details.

Developed by: Akshay Patil
Note: Multi mode
mode: register_employee*/


// this json is used to add family member of resident.
header('Content-Type: application/json; charset=UTF-8');
header('Access-Contorl-Allow-Origin: *');
date_default_timezone_set('Asia/kolkata');
include 'connection.php';
// Now you can use the connection class 
$connection = connection::getInstance();
$mysqli = $connection->getConnection();

$connection1 = database::getInstance();
  $mysqli1 = $connection1->getConnection();
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


$common_chk_error_res=common_chk_error($mysqli,$_POST['mode'],$_POST['agency_id'],$_POST['member_id'],$_POST['aadhaar_no'],$_POST['person_id'],$_POST['specification_id'],$_POST['application_id'],$_POST['$request_id']);
if($common_chk_error_res==1) 
{
	$mode=$_POST['mode'];
	$agency_id=$_POST['agency_id'];
	$member_id=$_POST['member_id'];
	$aadhaar_no = $_POST['aadhaar_no'];
	$person_id = $_POST['person_id'];
	$specification_id= $_POST['specification_id'];
	//$type_id= $_POST['type_id'];
	$application_id= $_POST['application_id'];
	$request_id= $_POST['request_id'];

	//
	

 
if($mode=='generate_aadhaar_otp') 
{
	
	
	if(!isset($person_id))
	{
		$response[]=["error_code"=>105,"message"=>"person_id parameter is missing"];
		echo json_encode($response);
		return;
	}
     if(empty($person_id))
	{
		$response[]=["error_code"=>106,"message"=>"person_id parameter is empty"];
		echo json_encode($response);
		return;
	}
	if(!isset($specification_id))
	{
		$response[]=["error_code"=>107,"message"=>"specification_id parameter is missing"];
		echo json_encode($response);
		return;
	}
     if(empty($specification_id))
	{
		$response[]=["error_code"=>108,"message"=>"specification_id parameter is empty"];
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
		$response[]=["error_code"=>110,"message"=>"application_id parameter is empty"];
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
		$response[]=["error_code"=>1112,"message"=>"request_id parameter is empty"];
		echo json_encode($response);
		return;
	}

		
	$verification_data = json_decode(generate_aadhaar_otp($aadhaar_no),true);
	// print_r($verification_data);
	if(!empty($verification_data))
	{
		
		if($verification_data['status'] == '200') {
				
					if($verification_data['data']['code']=='1001')
					{
						$data['transaction_id']=$verification_data['data']['transaction_id'];
						$data['message']=$verification_data['data']['message'];
						//$data['transaction_id']='1234';
						//$data['otp']='123456';
						$response[]=["error_code"=>100,"message"=>"OTP has been successfully sent on aadhar link mobile	no" ,"transaction_id"=>$data['transaction_id']];
		                echo json_encode($response);
						return;
					}
					else
					{
						$data['transaction_id']='';
						$data['message'] = $verification_data['data']['message'];
						//$data['transaction_id']='1234';
						//$data['otp']='123456';
						$response[]=["error_code"=>100,"message"=>"Please enter correct aadhar no"];
		                echo json_encode($response);
						return;
					}
				

		} 
		else if($verification_data['error_code'] == 'SPC-326') {
			$message = $verification_data['message'];
			$responce[]= ["error_code" => 199, "message" => $message];
			echo json_encode($responce);
			return;

			
		} else {
			$message = $verification_data['error']['message'];
			$responce[]= ["error_code" => 199, "message" => $message];
			echo json_encode($responce);
			return;
		}
	}
	else
	{
		$message='UIDAI server is down.Try after some time.';
		$responce[]= ["error_code" => 199, "message" => $message];
		echo json_encode($responce);
		return;
	}
}


 
// updated member err





if($mode=='get_aadhar_details')
  {
	 
	

	//
	$person_id = $_POST['person_id'];
	$otp = $_POST['otp'];
	$transaction_id = $_POST['transaction_id'];
	$birthdate = $_POST['birthdate'];
	$pincode= $_POST['pincode'];
	$specification_id= $_POST['specification_id'];
   // $type_id= $_POST['type_id'];
	$agency_id= $_POST['agency_id'];
	$application_id= $_POST['application_id'];
	$request_id= $_POST['request_id'];
	$adhar_no=$_POST['adhar_no'];
	$name_as_per_document=$_POST['name_as_per_document'];
	$client_order_id = '123465';
	$aadhaar_front_picture=$_FILES['aadhaar_front_picture']['name'];
	$aadhaar_backend_picture=$_FILES['aadhaar_backend_picture']['name'];


	
	if(!isset($person_id))
	{
		$response[]=["error_code"=>102,"message"=>"person_id parameter is missing"];
		echo json_encode($response);
		return;
	}
     if(empty($person_id))
	{
		$response[]=["error_code"=>103,"message"=>"person_id parameter is empty"];
		echo json_encode($response);
		return;
	}


	if(!isset($transaction_id))
	{
		$response[]=["error_code"=>104,"message"=>"transaction_id parameter is missing"];
		echo json_encode($response);
		return;
	}
     if(empty($transaction_id))
	{
		$response[]=["error_code"=>105,"message"=>"transaction_id parameter is empty"];
		echo json_encode($response);
		return;
	}


	
	if(!isset($otp))
	{
		$response[]=["error_code"=>106,"message"=>"otp parameter is missing"];
		echo json_encode($response);
		return;
	}
     if(empty($otp))
	{
		$response[]=["error_code"=>107,"message"=>"otp parameter is empty"];
		echo json_encode($response);
		return;
	}

	if(!isset($specification_id))
	{
		$response[]=["error_code"=>108,"message"=>"specification_id parameter is missing"];
		echo json_encode($response);
		return;
	}
     if(empty($specification_id))
	{
		$response[]=["error_code"=>109,"message"=>"specification_id parameter is empty"];
		echo json_encode($response);
		return;
	}

	
	if(!isset($pincode))
	{
		$response[]=["error_code"=>110,"message"=>"pincode parameter is missing"];
		echo json_encode($response);
		return;
	}
     if(empty($pincode))
	{
		$response[]=["error_code"=>111,"message"=>"pincode parameter is empty"];
		echo json_encode($response);
		return;
	}


	if(!isset($birthdate))
	{
		$response[]=["error_code"=>112,"message"=>"birthdate parameter is missing"];
		echo json_encode($response);
		return;
	}
     if(empty($birthdate))
	{
		$response[]=["error_code"=>113,"message"=>"birthdate parameter is empty"];
		echo json_encode($response);
		return;
	}
    if(!isset($birthdate))
	{
		$response[]=["error_code"=>114,"message"=>"birthdate parameter is missing"];
		echo json_encode($response);
		return;
	}
     if(empty($application_id))
	{
		$response[]=["error_code"=>115,"message"=>"application_id parameter is empty"];
		echo json_encode($response);
		return;
	}
	
	if(!isset($application_id))
	{
		$response[]=["error_code"=>116,"message"=>"application_id parameter is missing"];
		echo json_encode($response);
		return;
	}
     if(empty($request_id))
	{
		$response[]=["error_code"=>117,"message"=>"request_id parameter is empty"];
		echo json_encode($response);
		return;
	}

	// if(!isset($aadhaar_front_picture))
	// {
	// 	$response[]=["error_code"=>118,"message"=>"aadhaar_front_picture parameter is missing"];
	// 	echo json_encode($response);
	// 	return;
	// }
	// if(empty($aadhaar_front_picture))
	// {
	// 	$response[]=["error_code"=>119,"message"=>"aadhaar_front_picture parameter is empty"];
	// 	echo json_encode($response);
	// 	return;
	// }
	// if(!isset($aadhaar_backend_picture))
	// {
	// 	$response[]=["error_code"=>120,"message"=>"aadhaar_backend_picture parameter is missing"];
	// 	echo json_encode($response);
	// 	return;
	// }
	// if(empty($aadhaar_backend_picture))
	// {
	// 	$response[]=["error_code"=>121,"message"=>"aadhaar_backend_picture parameter is empty"];
	// 	echo json_encode($response);
	// 	return;
	// }
	

	  
	  
	   $sql_submit="SELECT `name` FROM `verification_header_all` WHERE `verification_id` = '$specification_id'";
	  $result_submit=mysqli_query($mysqli1,$sql_submit);
	  if(mysqli_num_rows($result_submit)>0) {
		  $person = $result_submit->fetch_assoc();
		  $uploaded_documents='';
			$backend_picture='';


			if(isset($_FILES['aadhaar_front_picture']) && !empty($_FILES['aadhaar_front_picture']['name'])) {
				$uploaded_documents = upload_document($_FILES['aadhaar_front_picture']);
				 $uploaded_documents;
		}
		  
		  if(isset($_FILES['aadhaar_backend_picture'])&&!empty($_FILES['aadhaar_backend_picture']['name'])) {
		

			  //$old_image = (isset($_POST['old_profile_pic']) && !empty($_POST['old_profile_pic'])) ? $_POST['old_profile_pic'] : '';
				// $backend_picture = upload_image($person_id,"aadhaar_backend_picture");

				 $backend_picture = upload_document($_FILES['aadhaar_backend_picture']);
				


			  $verified_data['uploaded_documents'] = ($uploaded_documents == '') ? $backend_picture : $uploaded_documents.','.$backend_picture;
		  }
			  
 
		 
			$verification_data = json_decode(verify_aadhaar_otp($otp,$transaction_id),true);
			// print_r($verification_data);
			  if($verification_data['status'] == '200') {
			  
			  $person_data="SELECT `name`, `contact_no`  FROM `member_header_all` WHERE `member_id`='$person_id'";
			  $res_person_data=mysqli_query($mysqli,$person_data);
			  $row_person_data=mysqli_fetch_assoc($res_person_data);
			  
			  $person_name = '';
			  if($name_as_per_document!='')
			  {
				  $person_name=$name_as_per_document;
			  }
			  else
			  {
				  $person_name = (isset($row_person_data['name'])) ? $row_person_data['name'] : '';
			  }
			  $mismatch_data = '';
			  $percentage = 0;
			  $cnt = 0;
			  $j=0;

			  
				$aadhaar_data = $verification_data['data']['aadhaar_data'];
				// print_r($aadhaar_data);
			  if($aadhaar_data['name']!='')
			  {
				  $adhar_name_arr=explode(" ",strtoupper($aadhaar_data['name']));
				  //print_r($adhar_name_arr);
				  $person_name_arr=explode(" ",strtoupper(trim($person_name," ")));
				  //print_r($person_name_arr);
				  if(sizeof($adhar_name_arr)>sizeof($person_name_arr))
				  {
					  $cnt=sizeof($adhar_name_arr);
					  foreach($adhar_name_arr as $adhar)
					  {
						  if (in_array($adhar, $person_name_arr))
						  {
							  $j++;
						  }
					  }
				  }
				  else
				  {
					  $cnt=sizeof($person_name_arr);
					  foreach($adhar_name_arr as $adhar)
					  {
						  if (in_array($adhar, $person_name_arr))
						  {
							  $j++;
						  }
					  }
				  }
			  }
			  //echo "%%%".$j;
			  //echo "%%%".$cnt;
			  //if(ucfirst($aadhaar_data['name'])== ucfirst($person_name)) {
			  //if($j==$adhar_arr_cnt) {
			  if($j==$cnt) {
				  
				  $percentage = 50;
				  $name_match='Match';
			  } else {
				  $mismatch_data = 'Name';
				  $name_match='Not Match';
			  }
			  
			  $birthdate2=date('Y-m-d',strtotime($birthdate));
			  $birthdate1=date('Y-m-d',strtotime($aadhaar_data['date_of_birth']));
			  if(strtotime($birthdate2) == strtotime($birthdate1)) {
				  $mismatch_data = $mismatch_data;
				  $percentage = $percentage + 33.33;
				  $birth_date_match='Match';
			  } else {
				  $mismatch_data = ($mismatch_data == '') ? 'Birthdate' : $mismatch_data.',Birthdate';
				  $percentage = $percentage + 0;
				  $birth_date_match='Not Match';
			  }
			  if($pincode == $aadhaar_data['pincode']) {
				  $mismatch_data = $mismatch_data;
				  $percentage = $percentage + 33.33;
				  $pincode_match='Match';
			  } else {
				  $mismatch_data = ($mismatch_data == '') ? 'Pincode' : $mismatch_data.',Pincode';
				  $percentage = $percentage + 0;
				  $pincode_match='Not Match';
			  }
			  
			  $data['id']=$person_id;
			  $data['name']=$row_person_data['name'];
			  $data['service_name']=$person['name'];
			  $data['member_name']=$person_name;
			  $data['adhar_no']=((empty($aadhaar_data['adhar_no'])) || ($aadhaar_data['adhar_no']==Null) || (!array_key_exists("adhar_no",$aadhaar_data))) ? "" : $aadhaar_data['adhar_no'] ;
			  $data['birthdate']=date('d-m-Y',strtotime($birthdate));
			  $data['pincode']=$pincode;
			  $data['front_image']=$uploaded_documents;
			  $data['backend_picture']=$backend_picture;
			  $data['name_match']=$name_match;
			  $data['adhar_match']='match';
			  $data['birth_date_match']=$birth_date_match;
			  $data['pincode_match']=$pincode_match;
			  $data["adhar_name"]= ((empty($aadhaar_data['name'])) || ($aadhaar_data['name']==Null) || (!array_key_exists("name",$aadhaar_data))) ? "" : $aadhaar_data['name'] ;
			  $data["adhar_birthdate"]= ((empty($aadhaar_data['date_of_birth'])) || ($aadhaar_data['date_of_birth']==Null) || (!array_key_exists("date_of_birth",$aadhaar_data))) ? "" : $aadhaar_data['date_of_birth'] ;
			  $data["adhar_pincode"]= ((empty($aadhaar_data['pincode'])) || ($aadhaar_data['pincode']==Null) || (!array_key_exists("pincode",$aadhaar_data))) ? "" : $aadhaar_data['pincode'] ;
			   
			  if(array_key_exists("care_of",$aadhaar_data))
			  {
				  $data["adhar_father_name"]= $aadhaar_data['care_of'];
			  }
			  else
			  {
				  $data["adhar_father_name"]= '';
			  }
			  $house=((empty($aadhaar_data['house'])) || ($aadhaar_data['house']==Null) || (!array_key_exists("house",$aadhaar_data))) ? "" : $aadhaar_data['house'] ;
			  $street=((empty($aadhaar_data['street'])) || ($aadhaar_data['street']==Null) || (!array_key_exists("street",$aadhaar_data))) ? "" : $aadhaar_data['street'] ;
			  $vtc_name=((empty($aadhaar_data['vtc_name'])) || ($aadhaar_data['vtc_name']==Null) || (!array_key_exists("vtc_name",$aadhaar_data))) ? "" : $aadhaar_data['vtc_name'] ;
			  $post_office_name=((empty($aadhaar_data['post_office_name'])) || ($aadhaar_data['post_office_name']==Null) || (!array_key_exists("post_office_name",$aadhaar_data))) ? "" : $aadhaar_data['post_office_name'] ;
			  $sub_district=((empty($aadhaar_data['sub_district'])) || ($aadhaar_data['sub_district']==Null) || (!array_key_exists("sub_district",$aadhaar_data))) ? "" : $aadhaar_data['sub_district'] ;
			  $district=((empty($aadhaar_data['district'])) || ($aadhaar_data['district']==Null) || (!array_key_exists("district",$aadhaar_data))) ? "" : $aadhaar_data['district'] ;
			  $state=((empty($aadhaar_data['state'])) || ($aadhaar_data['state']==Null) || (!array_key_exists("state",$aadhaar_data))) ? "" : $aadhaar_data['state'] ;
			  $pincode=((empty($aadhaar_data['pincode'])) || ($aadhaar_data['pincode']==Null) || (!array_key_exists("pincode",$aadhaar_data))) ? "" : $aadhaar_data['pincode'] ;
			  $adhar_no=((empty($aadhaar_data['adhar_no'])) || ($aadhaar_data['adhar_no']==Null) || (!array_key_exists("adhar_no",$aadhaar_data))) ? "" : $aadhaar_data['adhar_no'] ;

			  
			  $data["adhar_birthdate"]= $aadhaar_data['date_of_birth'];
			  
			  $address=$house." ".$street." ".$vtc_name." ".$post_office_name." ".$sub_district." ".$district." ".$state." ".$pincode."".$adhar_no;
			  $data["adhar_address"]= $address;
			  /*$data["id"]= "M-14948";
			  $data["name"]= "Akshay Patil ";
			  $data["service_name"]= "Aadhar Verification"; 
			  $data["member_name"]= "Akshay Patil ";            
			  $data["adhar_no"]= "657213722127";            
			  $data["birthdate"]= "01-10-1998";            
			  $data["pincode"]= "425502";            
			  $data["front_image"]= "img_adhar.jpeg";            
			  $data["back_image"]= "img_pan.jpeg";            
			  $data["name_match"]= "not match";            
			  $data["adhar_match"]= "match";            
			  $data["birth_date_match"]= 'match';            
			  $data["pincode_match"]= "match";
			  $data["adhar_name"]= "Akshay Vijay Patil";
			  $data["adhar_father_name"]= "Akshay Vijay Patil"; 
			  $data["adhar_birthdate"]= "19-02-1995";
				$data["adhar_address"]= "sdsdsdd";*/
	


// include_once "aadhar_verification_pdf.php";
$url = 'https://mounarchtech.com/vocoxp/aadhar_verification_pdf.php';

// Data to send
$postdata = array(
	'name_match' => $name_match,
	'birth_date_match' => $birth_date_match,
	'pincode_match' => $pincode_match,
	'name_as_per_document' => $name_as_per_document,
	'aadhaar_no' => $aadhaar_no,
	'birthdate' => $birthdate,
	'pincode' => $pincode,
	'uploaded_documents' => $uploaded_documents,
	'backend_picture' => $backend_picture,
	'aadhar_name' => $aadhaar_data['name'],
	'aadhar_birth_date' => $aadhaar_data['date_of_birth'],
	'aadhar_pincode' => $aadhaar_data['pincode'],
	 'name' => $row_person_data['name'],
	 'contact_no' => $row_person_data['contact_no'],
	 'request_id' => $request_id,
	 'application_id' => $application_id,
	 'person_id' => $person_id,
	 'specification_id' => $specification_id,
	 'agency_id' => $agency_id
);

// print_r($postdata);
// Initialize cURL session
$ch = curl_init();

// Set cURL options
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postdata)); // Use $postdata instead of $data
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Execute cURL request
$response = curl_exec($ch);

// Check for errors
if($response === false) {
    echo 'Curl error: ' . curl_error($ch);
} else {
    // Decode JSON response
    $response_data = json_decode($response, true);

    // Check if JSON decoding was successful
    if ($response_data !== null) {
        // Handle the response data
        $path = $response_data['path'];
    } 
}

// Close cURL session
curl_close($ch);

// $data['pdf_url']=$path; 
// 				$data_arr[]=$data;
				
			  if(!empty($path)) 			{			 
					
				// $responce[]= ["error_code" => 100, "data" => $data_arr];
				// echo json_encode($responce);
				
				$path_data[]=array("pdf_url"=>$path);     
				$responce[]= ["error_code" => 100, "data" =>$path_data];
				echo json_encode($responce);
		
// include_once "./integrated_pdf.php";
$integrated_url = 'https://mounarchtech.com/vocoxp/integrated_pdf.php';
// Data to send
$integreat_data = array(
	'agency_id' => $agency_id,
	'member_id' => $member_id,
	'path' => $path,
);
// Initialize cURL session
$ch = curl_init();

// Set cURL options
curl_setopt($ch, CURLOPT_URL, $integrated_url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($integreat_data)); // Use $postdata instead of $data
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// Execute cURL request
$integreat_response = curl_exec($ch);

// Check for errors
if($integreat_response === false) {
    echo 'Curl error: ' . curl_error($ch);
} else {
    // Decode JSON response
    $response_data = json_decode($integreat_response, true);

    // Check if JSON decoding was successful
    if ($response_data !== null) {
        // Handle the response data
        $path_ = $response_data['path'];
    } 
}
// Close cURL session
curl_close($ch);



				return;
			  } else {
				//   echo response($error_code=108,$data=(object)[]);            
				//   return;
				  $responce[]= ["error_code" => 199, "data" => $data];
				  echo json_encode($responce);
				  return;
			  }   
		  } else {
			  $message = $verification_data['error']['message'];
			  if($message==null)
			  {
				  $responce[]= ["error_code" => 199, "message" => "There was a problem fetching your details from the UIDI server. Please try again after some time."];
				  echo json_encode($responce);
				  return;
			  }
			  else
			  {
				  $responce[]= ["error_code" => 199, "message" => $message];
				  echo json_encode($responce);
				  return;
			  }
			  
		  }
	  } else {

		$responce[]= ["error_code" => 109, "data" => $data];
		  //echo response($error_code=109,$data=(object)[]);
		  echo json_encode($responce);
		  return;
	  }
  } 
  

}

function common_chk_error($mysqli,$mode,$agency_id,$member_id,$aadhaar_no)
{
	$common_chk_error=1;
	
	if(!isset($mode))
	{
		$response[]=["error_code"=>102,"message"=>"Please add mode"];
		echo json_encode($response);
		return;
	}
	else if(empty($mode))
	{
		$response[]=["error_code"=>103,"message"=>"mode is empty"];
		echo json_encode($response);
		return;
	}

    else if($mode!='generate_aadhaar_otp' && $mode!='get_aadhar_details')
	{
		$response[]=["error_code"=>104,"message"=>"Paramter 'mode' value is invalid. please enter value either generate_aadhaar_otp or get_aadhar_details. "];
		echo json_encode($response);
		return;
	}
	
    if(!isset($agency_id))
	{
		$response[]=["error_code"=>105,"message"=>"agency_id parameter is missing"];
		echo json_encode($response);
		return;
	}
     if(empty($agency_id))
	{
		$response[]=["error_code"=>106,"message"=>"agency_id parameter is empty"];
		echo json_encode($response);
		return;
	}
	$sql="SELECT `status` FROM `agency_header_all` WHERE agency_id='$agency_id'";
	$res_sql=mysqli_query($mysqli,$sql);
	$agency_data=mysqli_fetch_assoc($res_sql);
	
	if(mysqli_num_rows($res_sql)==0)
	{
		$response[] = ["error_code" => 107, "message" => "agency_id is not valid"];  //agency id not valid
		echo json_encode($response);
		return;   
	}
	else if($agency_data['status']!='1') // 	check agency_id is active
	{
		$response []= ["error_code" => 108, "message" => "agency_id is not active"]; 
		echo json_encode($response);
		return; 
	}
    if(!isset($member_id))
	{
		$response[]=["error_code"=>109,"message"=>"member_id parameter is missing"];
		echo json_encode($response);
		return;
	}
     if(empty($member_id))
	{
		$response[]=["error_code"=>110,"message"=>"member_id parameter is empty"];
		echo json_encode($response);
		return;
	}
	$sql="SELECT `status` FROM `member_header_all` WHERE member_id='$member_id'";
	$res_sql=mysqli_query($mysqli,$sql);
	$agency_data=mysqli_fetch_assoc($res_sql);
	
	if(mysqli_num_rows($res_sql)==0)
	{
		$response []= ["error_code" => 111, "message" => "member_id is not valid"];  //agency id not valid
		echo json_encode($response);
		return;   
	}

	// elseif($agency_data['status']!='active') // 	check agency_id is active
	// {
	// 	$response[] = ["error_code" => 107, "message" => "member_id is not active"]; 
	// 	echo json_encode($response);
	// 	return;  ///agency is not active
	// }

    if(!isset($aadhaar_no))
	{
		$response[]=["error_code"=>112,"message"=>"aadhaar_no parameter is missing"];
		echo json_encode($response);
		return;
	}
     if(empty($aadhaar_no))
	{
		$response[]=["error_code"=>113,"message"=>"aadhaar_no parameter is empty"];
		echo json_encode($response);
		return;
	}

return $common_chk_error;
}
	

// function upload_document($upload_file){
// 	// Target directory for saving uploaded files
// 	$target_dir_profile = __DIR__ . "/active_folder/agency/member/verifications/document_image/";

// 	// Get file extension
// 	$ext = pathinfo($upload_file['name'], PATHINFO_EXTENSION);

// 	// Generate a unique file name
// 	$file_name = uniqid();

// 	// Path to save the uploaded file
// 	$file_path = $target_dir_profile . "docs-" . $file_name . "." . $ext;

// 	// Move the uploaded file to the target directory
// 	move_uploaded_file($upload_file['tmp_name'], $file_path);

// 	// Define the base URL
// 	$base_url = 'https://mounarchtech.com/vocoxp/active_folder/agency/member/verifications/document_image/';

// 	// Construct the full URL of the uploaded file
// 	$emp_imageURL = $base_url . "docs-" . $file_name . "." . $ext;

// 	// Return the URL of the uploaded file
// 	return $emp_imageURL;
// }




function upload_image($person_id,$doc_name,$old_image = '')
{
	$target_dir_profile = "person_documents/".$person_id;
	if(!empty($old_image) && file_exists($target_dir_profile.'/'.$old_image)) {
		unlink($target_dir_profile.'/'.$old_image); 
	}
    if(isset($_FILES[$doc_name]['name'])&&!empty($_FILES[$doc_name]['name'])){
        // $rid=rand(1000,9999);
        $new_name_profile = "img_".$_FILES[$doc_name]['name'];
        if (!file_exists('person_documents')) {
            mkdir('person_documents', 0777, true);
        }
		if (!file_exists($target_dir_profile)) {
            mkdir($target_dir_profile, 0777, true);
        }
        $target_file_profile = $target_dir_profile."/".$new_name_profile;
        if(move_uploaded_file($_FILES[$doc_name]["tmp_name"],$target_file_profile)) {
            $new_name_profile;
        } else {
            $new_name_profile='';
        }
      }else {
        $new_name_profile='';
    }
    return $new_name_profile;
    // exit;
}	
		
function verification_aadhaar($aadhaar_no) {
	$client_order_id = str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);
	$agent_code = str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);
	$curl = curl_init();
	curl_setopt_array($curl, [
		CURLOPT_URL => "https://g2c.softpayapi.com/api/aadhar_verify/aadhar_verify?api_key=2965NI38GW4YRVC5JC0ZBP980X17T2K34EAQFSALD1U6ODM7HB&agent_code=".$agent_code."&client_order_id=".$client_order_id,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 30,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "POST",
		CURLOPT_POSTFIELDS => "{
		  \"adhaarNumber\": \"$aadhaar_no\",
		  \"captureResponse\": {
			\"ci\": \"20221021\",
			\"Data\": \"MjAyMS0wNS0xMlQxMTo0MzoyMIPol6lNJqrs7N6s4hkq7jrjLT8nezh9JvjjDYRZ5GpgH2iyi3jnw7fpJNewRAXUmTi828gMCZuhBHAeZ2Qf8xwzYLxYmTx6m5oMoEA4QS0zOS91apr6ergmjMZJmEzx8SPp9Y4L3Mw0GDFLcHrYmqKcASdLmNF8pv6fJF4G1ep7meJ3lgCHYechVjwmVh56uRNAj/4mLSGUbbSamm5g4EiduJUzJ73/1zNOgSsnFtktXntGl7adjwe8obIwuRNeOIMschIEm43jiBTJruDAOywzofjXDXKPNIVbpsMAy83K5TneR1HFANvX0PRdRXVBDw3UEYTnbkZaOwTB57FrY8PGl20jowlyhhJWLu5pV2WaVQKy3ZT8/0jSsWrbnfBo4nt8atpn7wHmbsFyWnEYpaAiefknOhHIjpa7pveg1hau1tS6nEHieAvL+tj3WA9A7T7UPNJ/h5jmSK4sz97N9RSmdjn+gYVC4cWA2o+jMrj/sS7eEd//JuaOGNEyZ8g8zC6aOGzl0C/g9v2vTX567N8F/OO4wcgf2g+rNjN2Rmex/e150gip7S2oMSWY0SQwexBfWtWeOCN3FbgQ5oMNdXTC1M66wvGFYhAluHv5PjoOWVUqryJD0Z3JSPyxvixeyg9u7AoOOe44mTTZNasPR75xTMMgpCXPfo2Cn+av2FQtKEhyawTOKvVRyOD0e0UsnS5QonqGtgdiKUcQ0Hb12LOb8sJbeDWDLvLdKodxu9rxJrgmUnujIlvIHxTbMoXYbxsouCyLV+YUH7T2+YLO76ZOSVnMlLQwe/b8u4/UYzmOll3hszozqwx+xFUpOA\",
			\"dc\": \"66c37bc1-d9ca-4801-9f35-c4419d2b83ae\",
			\"dpID\": \"MANTRA.MSIPL\",
			\"errCode\": \"0\",
			\"errInfo\": \"Success\",
			\"fCount\": \"1\",
			\"fType\": \"0\",
			\"hmac\": \"6DP3BKQSHVzMPuGS4wWTSjmtxNoyooX5DrbBITj/MFQacVLALa3W3A6RlFyKjY3h\",
			\"iCount\": \"0\",
			\"iType\": \"0\",
			\"mc\": \"MIIEGDCCAwCgAwIBAgIEA0c7wDANBgkqhkiG9w0BAQsFADCB6jEqMCgGA1UEAxMhRFMgTWFudHJhIFNvZnRlY2ggSW5kaWEgUHZ0IEx0ZCA3MUMwQQYDVQQzEzpCIDIwMyBTaGFwYXRoIEhleGEgb3Bwb3NpdGUgR3VqYXJhdCBIaWdoIENvdXJ0IFMgRyBIaWdod2F5MRIwEAYDVQQJEwlBaG1lZGFiYWQxEDAOBgNVBAgTB0d1amFyYXQxHTAbBgNVBAsTFFRlY2huaWNhbCBEZXBhcnRtZW50MSUwIwYDVQQKExxNYW50cmEgU29mdGVjaCBJbmRpYSBQdnQgTHRkMQswCQYDVQQGEwJJTjAeFw0yMTA1MDYwNzI0NTVaFw0yMTA2MDUwNzQzNTVaMIGwMSUwIwYDVQQDExxNYW50cmEgU29mdGVjaCBJbmRpYSBQdnQgTHRkMR4wHAYDVQQLExVCaW9tZXRyaWMgTWFudWZhY3R1cmUxDjAMBgNVBAoTBU1TSVBMMRIwEAYDVQQHEwlBSE1FREFCQUQxEDAOBgNVBAgTB0dVSkFSQVQxCzAJBgNVBAYTAklOMSQwIgYJKoZIhvcNAQkBFhVzdXBwb3J0QG1hbnRyYXRlYy5jb20wggEiMA0GCSqGSIb3DQEBAQUAA4IBDwAwggEKAoIBAQC9GD+fY9kjYgQ5czHadMiC1y9rytz4Taetg+yH5zkymWpdYW7Fq8Bv2J0sKm0t3eWLw7SKL4A1UPjIEG+mHOau4gLslvQ18Eja+S03Pca5PuSI1YnYgwIm8oZAWT7MrRCEyWDAbCDF/egZ8XXx5GKpNzCchPhWaiaLVQAu+MhQPYT29OAAR4tdE4A/s2P3wQPoAUCEAaD7c1rhR65RDYnxFiwlOKrZJqU6wKnepDKirH44kg/fkrUMJok38q2zzptJXryopcv40xfgPlCci4d8tLIhq6AAgKzX1UfTQrzxpGg+BQd2+H/meMu6UDUjLDW0G4oOnKLBG1zypbI0dc07AgMBAAEwDQYJKoZIhvcNAQELBQADggEBAD2U86p6P+tM5YqEaXojrpwRl6ihTVd83LUO7joDmbKkSZjE/sbioOdWz3lsYGZOFraiDB/AzI+X3hHNh3cM9Cs2ceXR1XKOKdyzGoisNcABCxiF5YFdy2IDZNZ87xU6S6FP9llLI7vPRakDAZF+3+CI577cYkJZruGkYZ1e/stSDtxEGnyJgdkQg2Tla2hu2OoJv2Xpp+zOtR/4HWLGx/UqXQ7ik6HETMIxdvC4Hg/cdHfMKmEP2++17PfB1ZA2gyJkvgvYdUxZbVIhifD/hAUk2BisbpWuOv2EtKKnGIhZ/p/7/btrxs4PImykoteI3Di9cabkLT34LKxuUXhjIV8=\",
			\"mi\": \"MFS100\",
			\"nmPoints\": \"44\",
			\"pCount\": \"0\",
			\"pType\": \"0\",
			\"qScore\": \"70\",
			\"rdsID\": \"MANTRA.WIN.001\",
			\"rdsVer\": \"1.0.2\",
			\"Skey\": \"YfmB+ZX7UBMeyGSBJCR5C7ZA8ruAb5EB1ViIpztxZG9mIW9qdejDZ0KR5m7tyF28sPXEXqb8q08liva7VRwdXJ5/DK2FQyLSH3EiOA+S4Fs/4ZWf+jKjsdMD+41JGgEHMyQ6CIzz0FouVYPRKFlr6MaN41rM2ucK7d+ep7jfXYYXDUQ9pfjpDVGwxfKnBXpVC4TFizaAXpuNKNImsNpFGRISB/wBtxsTv0XTUr6nZYhaxbrCbJ8AvoORIgIRDgBURmcevpBaVFh93LjHT5qVkZqmTjTm9q5L3kqrrHeGIJGk3idMFi+c3xko+qnOVHm9Xg5fHXvZEYoFKf7LvU1Etg==\",
			\"Type\": \"X\",
			\"additional_info\": [
			  {
				\"name\": \"srno\",
				\"value\": 2539887
			  },
			  {
				\"name\": \"sysid\",
				\"value\": \"6C3FEBFC2E40CAAFBFF0\"
			  },
			  {
				\"name\": \"ts\",
				\"value\": \"2021-05-12T11:43:20+05:30\"
			  }
			]
		  }
		}",
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



function generate_aadhaar_otp($aadhaar_no) {
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
		CURLOPT_POSTFIELDS => "{\n  \"aadhaar_number\": \"$aadhaar_no\",\n  \"consent\": \"Y\"\n}",
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


function verify_aadhaar_otp($otp,$transaction_id) {
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
		//echo "cURL Error #:" . $err;
	  return "cURL Error #:" . $err;
	} else {
		//echo $response;
		return $response;
	}
}

    
?>
