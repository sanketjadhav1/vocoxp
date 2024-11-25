
<?php



error_reporting(1);
// require_once("../libraries/Pdf.php");

// require_once("../individual_connection.php");
// require_once("../verification_api/functions.php");
/*Prepared by:Rohan Akolkar
Name of API: :  json_for_action_generate_voter_id_report
Method: POST
Category: Action
Description:
This API is to use generate voter id details
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
apponoff($mysqli);
logout($_SERVER['HTTP_AUTH_KEY'], $mysqli);
$system_date = date("y-m-d");
$system_date_time = date("Y-m-d H:i:s");
$output = $responce = array();
// logout($_SERVER['HTTP_AUTH_KEY'], $mysqli);

    
    
if ($_SERVER["REQUEST_METHOD"] != "POST") 
{
	$response[]=["error_code"=>101,"message"=>"Please use POST method"];
	echo json_encode($response);
	return;
}


$chk_error_res=chk_error($mysqli,$_POST['agency_id'],$_POST['member_id'],$_POST['voter_id_no'],$_POST['person_id'],$_POST['specification_id'],$_POST['application_id'],$_POST['$request_id']);
if($chk_error_res==1) 
{
	$voter_id_no = $_POST['voter_id_no'];
	$agency_id= $_POST['agency_id'];
	$member_id= $_POST['member_id'];
	$person_id = $_POST['person_id'];
	$specification_id=$_POST['specification_id'];
	$type_id= $_POST['type_id'];
	$application_id= $_POST['application_id'];
	$request_id= $_POST['request_id'];
	$name_as_per_document= $_POST['name_as_per_document'];
	
	


	if(!isset($specification_id))
	{
		$response[]=["error_code"=>104,"message"=>"specification_id parameter is missing"];
		echo json_encode($response);
		return;
	}
     if(empty($specification_id))
	{
		$response[]=["error_code"=>105,"message"=>"specification_id parameter is empty"];
		echo json_encode($response);
		return;
	}


	
	if(!isset($application_id))
	{
		$response[]=["error_code"=>106,"message"=>"application_id parameter is missing"];
		echo json_encode($response);
		return;
	}
     if(empty($application_id))
	{
		$response[]=["error_code"=>107,"message"=>"application_id parameter is empty"];
		echo json_encode($response);
		return;
	}

	if(!isset($request_id))
	{
		$response[]=["error_code"=>108,"message"=>"request_id parameter is missing"];
		echo json_encode($response);
		return;
	}
     if(empty($request_id))
	{
		$response[]=["error_code"=>109,"message"=>"request_id parameter is empty"];
		echo json_encode($response);
		return;
	}

	if(!isset($_FILES['voter_front_picture']))
	{
		$response[]=["error_code"=>112,"message"=>"voter_front_picture parameter is missing"];
		echo json_encode($response);
		return;
	}
	
     if(empty($_FILES['voter_front_picture']))
	{
		$response[]=["error_code"=>113,"message"=>"voter_front_picture parameter is empty"];
		echo json_encode($response);
		return;
	}
	if(!isset($_FILES['voter_back_picture']))
	{
		$response[]=["error_code"=>113,"message"=>"voter_back_picture parameter is missing"];
		echo json_encode($response);
		return;
	}

     if(empty($_FILES['voter_back_picture']))
	{
		$response[]=["error_code"=>114,"message"=>"voter_back_picture parameter is empty"];
		echo json_encode($response);
		return;
	}



	$sql_submit="SELECT `verification_id` FROM `verification_header_all` where `verification_id` = '$specification_id'";
	$result_submit=mysqli_query($mysqli1,$sql_submit);
	if(mysqli_num_rows($result_submit)>0) {
		$person = $result_submit->fetch_assoc();	
		$verification_data = json_decode(verify_voter_id($voter_id_no),true);
		// print_r($verification_data);
		// exit;
		$frontend_picture='';
		$backend_picture='';
		
		// if(isset($_FILES['voter_front_picture']['name'])&&!empty($_FILES['voter_front_picture']['name'])) {
		// 	$frontend_picture=upload_image($member_id,"voter_front_picture");
		// }
		
		// if(isset($_FILES['voter_backend_picture']['name'])&&!empty($_FILES['voter_backend_picture']['name'])) {
		// 	//$old_image = (isset($_POST['old_profile_pic']) && !empty($_POST['old_profile_pic'])) ? $_POST['old_profile_pic'] : '';
		// 	$backend_picture = upload_image($member_id,"voter_backend_picture");
		// }



		if(isset($_FILES['voter_front_picture']) && !empty($_FILES['voter_front_picture']['name'])) {
			$frontend_picture = upload_document($_FILES['voter_front_picture']);
	   }

		if(isset($_FILES['voter_back_picture']) && !empty($_FILES['voter_back_picture']['name'])) {
				$backend_picture = upload_document($_FILES['voter_back_picture']);
	   }

		if(!empty($verification_data))
		{
			if($verification_data['status'] == '200' && $verification_data['data']['code'] == '1000') {
				$person_name = '';
				$person_data="SELECT `name`, `contact_no`  FROM `member_header_all` WHERE `member_id`='$member_id'";
				$res_person_data=mysqli_query($mysqli,$person_data);
				if(mysqli_num_rows($res_person_data)>0) {
					$row_person_data=mysqli_fetch_assoc($res_person_data);
					$mem_name=(isset($row_person_data['name'])) ? $row_person_data['name'] : '';
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
					$voter_data = $verification_data['data']['voter_data'];
					
			// print_r($voter_data);
					//$a1=rtrim($voter_data['name'],' ');
					//$a2 = decbin(ord($a1));
					//$a3 = decbin(ord($person_name));
					if($voter_data['name']!='')
					{
						$adhar_name_arr=explode(" ",strtoupper($voter_data['name']));
						//print_r($adhar_name_arr);
						$person_name_arr=explode(" ",strtoupper(trim($person_name," ")));
						//print_r($person_name_arr);
						/*if(sizeof($adhar_name_arr)>sizeof($person_name_arr))
						{*/
							$cnt=sizeof($adhar_name_arr);
							foreach($adhar_name_arr as $adhar)
							{
								if (in_array($adhar, $person_name_arr))
								{
									$j++;
								}
							}
						/*}
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
						}*/
						
					}
//echo ">>".$j;
//echo ">>".$cnt;
					//if($a2 == $a3) {
					if($j==$cnt) {
						$percentage = 50;
						$name_match='Match';
					} else {
						$mismatch_data = 'Name';
						$name_match='Not Match';
					}



				
					$voter_name=((empty($voter_data['name'])) || ($voter_data['name']==Null) || (!array_key_exists("name",$voter_data))) ? "" : $voter_data['name'] ;
					$father_name=((empty($voter_data['father_name'])) || ($voter_data['father_name']==Null) || (!array_key_exists("father_name",$voter_data))) ? "" : $voter_data['father_name'] ;
					$age=((empty($voter_data['age'])) || ($voter_data['age']==Null) || (!array_key_exists("age",$voter_data))) ? "" : $voter_data['age'] ;
					$state=((empty($voter_data['state'])) || ($voter_data['state']==Null) || (!array_key_exists("state",$voter_data))) ? "" : $voter_data['state'] ;
					$district=((empty($voter_data['district'])) || ($voter_data['district']==Null) || (!array_key_exists("district",$voter_data))) ? "" : $voter_data['district'] ;
					$assembly_constituency_number=((empty($voter_data['assembly_constituency_number'])) || ($voter_data['assembly_constituency_number']==Null) || (!array_key_exists("assembly_constituency_number",$voter_data))) ? "" : $voter_data['assembly_constituency_number'] ;
					$assembly_constituency_name=((empty($voter_data['assembly_constituency_name'])) || ($voter_data['assembly_constituency_name']==Null) || (!array_key_exists("assembly_constituency_name",$voter_data))) ? "" : $voter_data['assembly_constituency_name'] ;
					$parliamentary_constituency_name=((empty($voter_data['parliamentary_constituency_name'])) || ($voter_data['parliamentary_constituency_name']==Null) || (!array_key_exists("parliamentary_constituency_name",$voter_data))) ? "" : $voter_data['parliamentary_constituency_name'] ;
					$part_number=((empty($voter_data['part_number'])) || ($voter_data['part_number']==Null) || (!array_key_exists("part_number",$voter_data))) ? "" : $voter_data['part_number'] ;
					$part_name=((empty($voter_data['part_name'])) || ($voter_data['part_name']==Null) || (!array_key_exists("part_name",$voter_data))) ? "" : $voter_data['part_name'] ;
					$serial_number=((empty($voter_data['serial_number'])) || ($voter_data['serial_number']==Null) || (!array_key_exists("serial_number",$voter_data))) ? "" : $voter_data['serial_number'] ;
					$polling_station=((empty($voter_data['polling_station'])) || ($voter_data['polling_station']==Null) || (!array_key_exists("polling_station",$voter_data))) ? "" : $voter_data['polling_station'] ;

					$data['id']=$member_id;
					$data['voter_id']=$voter_id_no;
					$data['name']=$mem_name;
					$data['member_name']=$person_name;
					$data['voter_name']=$voter_name;
					$data['father_name'] = $father_name;
					
					$data['age']=$age;
					$data['district']=$district;
					$data['state']=$state;
					$data['assembly_constituency_number']=$assembly_constituency_number;
					$data['assembly_constituency_name']=$assembly_constituency_name;
					$data['parliamentary_constituency_name']=$parliamentary_constituency_name;
					$data['part_number']=$part_number;
					$data['serial_number']=$serial_number;
					$data['polling_station'] = $polling_station;
					$data['name_match']=$name_match;					
					$data['front_image']=$frontend_picture;
					$data['back_image']=$backend_picture;
					// echo response($error_code=100,$data_arr);
					// return;
					// include_once "voter_verification_pdf.php";
					$data['pdf_url']=$path;
					$data_arr[] = $data;
					$url = 'https://mounarchtech.com/vocoxp/voter_verification_pdf.php';

					// Data to send
					$postdata = array(
						'name_match' => $name_match,
						'name' => $row_person_data['name'],
						'contact_no' => $row_person_data['contact_no'],
						'name_as_per_document' => $name_as_per_document,
						'voter_id_no' => $voter_id_no,
						'frontend_picture' => $frontend_picture,
						'backend_picture' => $backend_picture,
						'assembly_constituency_number' => $data['assembly_constituency_number'],
						'member_name' => $data['member_name'],
						'assembly_constituency_name' => $data['assembly_constituency_name'],
						'father_name' => $data['father_name'],
						'husband_name'=>$voter_data['husband_name'],
						'parliamentary_constituency_name' =>$data['parliamentary_constituency_name'],
						'age' => $data['age'],
						'part_number' => $data['part_number'],
						'district' => $data['district'],
						'serial_number' => $data['serial_number'],
						'state' => $data['state'],
						'polling_station' => $data['polling_station'],
						'voter_name' => $data['voter_name'],
		
						'agency_id' => $agency_id,
						'member_id' => $member_id,
						'request_id' => $request_id,
						'application_id' => $application_id,
						'specification_id' => $specification_id,
					);
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



					// $responce[]= ["error_code" => 100, "data" => $data_arr];
					// echo json_encode($responce);
					$path_data[]=array("pdf_url"=>$path);
					$responce[]= ["error_code" => 100, "data" =>$path_data];
					echo json_encode($responce);
					// exit;

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
					$responce[]= ["error_code" => 109, "message" => 'Member not found'];
					echo json_encode($responce);
					return;
				}
			} 
			else if(($verification_data['status'] == '200') && (isset($verification_data['data'])) && ($verification_data['data']['code'] != '1000')) 
			{
				$message = $verification_data['data']['message'];
				$responce[]= ["error_code" => 199, "message" => $message];
				echo json_encode($responce);
				return;
			} 
			else 
			{
				$message = $verification_data['data']['message'];
				if($message!=null || $message!='')
				{
					$responce[]= ["error_code" => 199, "message" => $message];
					echo json_encode($responce);
					return;
				}
				else
				{
					$message='Upstream source/Government source timed out. Please start the process again.';
					$responce[]= ["error_code" => 199, "message" => $message];
					echo json_encode($responce);
					return;
				}
				
			}
		} else {
			$message='server is down.Try after some time.';
			$responce[]= ["error_code" => 199, "message" => $message];
			echo json_encode($responce);
			return;
		}
	} else {
		//echo response($error_code=109,$data=[]);
		//return;

		$responce[]= ["error_code" => 199, "data" => $data];
		echo json_encode($responce);
		return;
	}
} 
else {
	return $chk_error_res;
}

 

 



 

  



function chk_error($mysqli,$agency_id,$member_id,$voter_id_no)
{
	$chk_error=1;
	
	

 
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
	$sql="SELECT `agency_id`, `status` FROM `agency_header_all` WHERE `agency_id`='$agency_id'";
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
	$sql="SELECT `member_id` FROM `member_header_all` WHERE `member_id`='$member_id'";
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

	if(!isset($voter_id_no))
	{
		$response[]=["error_code"=>105,"message"=>"voter_id_no parameter is missing"];
		echo json_encode($response);
		return;
	}
     if(empty($voter_id_no))
	{
		$response[]=["error_code"=>106,"message"=>"voter_id_no parameter is empty"];
		echo json_encode($response);
		return;
	}

	// if(!isset($specification_id))
	// {
	// 	$response[]=["error_code"=>107,"message"=>"specification_id parameter is missing"];
	// 	echo json_encode($response);
	// 	return;
	// }
    //  if(empty($specification_id))
	// {
	// 	$response[]=["error_code"=>107,"message"=>"specification_id parameter is empty"];
	// 	echo json_encode($response);
	// 	return;
	// }

	

	// if(!isset($application_id))
	// {
	// 	$response[]=["error_code"=>105,"message"=>"application_id parameter is missing"];
	// 	echo json_encode($response);
	// 	return;
	// }
    //  if(empty($application_id))
	// {
	// 	$response[]=["error_code"=>106,"message"=>"application_id parameter is empty"];
	// 	echo json_encode($response);
	// 	return;
	// }
	// if(!isset($request_id))
	// {
	// 	$response[]=["error_code"=>105,"message"=>"request_id parameter is missing"];
	// 	echo json_encode($response);
	// 	return;
	// }
    //  if(empty($request_id))
	// {
	// 	$response[]=["error_code"=>106,"message"=>"request_id parameter is empty"];
	// 	echo json_encode($response);
	// 	return;
	// }
return $chk_error;
}
	
// function upload_image($person_id,$doc_name,$old_image = '')
// {
// 	$target_dir_profile = "person_documents/".$person_id;
// 	if(!empty($old_image) && file_exists($target_dir_profile.'/'.$old_image)) {
// 		unlink($target_dir_profile.'/'.$old_image); 
// 	}
//     if(isset($_FILES[$doc_name]['name'])&&!empty($_FILES[$doc_name]['name'])){
//         // $rid=rand(1000,9999);
//         $new_name_profile = "img_".$_FILES[$doc_name]['name'];
//         if (!file_exists('person_documents')) {
//             mkdir('person_documents', 0777, true);
//         }
// 		if (!file_exists($target_dir_profile)) {
//             mkdir($target_dir_profile, 0777, true);
//         }
//         $target_file_profile = $target_dir_profile."/".$new_name_profile;
//         if(move_uploaded_file($_FILES[$doc_name]["tmp_name"],$target_file_profile)) {
//             $new_name_profile;
//         } else {
//             $new_name_profile='';
//         }
//       }else {
//         $new_name_profile='';
//     }
//     return $new_name_profile;
//     // exit;
// }

function verify_voter_id($voter_id_no) {
	$curl = curl_init();
	curl_setopt_array($curl, [
			CURLOPT_URL => "https://api.gridlines.io/voter-api/boson/fetch",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => json_encode([
				'voter_id' => $voter_id_no,
				'consent' => 'Y'
			]),
		CURLOPT_HTTPHEADER => [
			"Accept: application/json",
			"Content-Type: application/json",
			"X-API-Key: C8EbVBaNqR4g3vhBAiPXdt8cLPkNLJoL",
			"X-Auth-Type: API-Key"
		],
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




    
?>
