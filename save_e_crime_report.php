<?php
// require_once("../libraries/Pdf.php");
include_once 'connection.php';
$connection = connection::getInstance();
$mysqli = $connection->getConnection();
if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
	error_log('Crimecheck URL: Full post object: '.print_r($_POST, true));
	$data = $_POST['data'];
	$data = json_decode($data);
	print_r($data);
	error_log('crimecheck_url: request ID from data: '.$data->requestId);
	error_log('downloadLink ghgghghghguut: '.$data->downloadLink);
	
	 echo $sql1="SELECT `person_id` FROM  `v_e_crime_active_transaction_all`  WHERE `e_crime_request_id`='".$data->requestId."'";
	$res_sql1=mysqli_query($mysqli,$sql1);
	$row=mysqli_fetch_assoc($res_sql1);
	print_r($row);
	$member_id=$row['person_id'];
	//<img  src="../images/miscos.png" alt="logo" class="miscos" style="width:100px;float:left;">
	
	$output='<div style="width:95%;margin: auto;padding:0px 10px 10px 10px;">
	<div style="height:50px;"><h2  align="center" style="height:10px;">MISCOS TECHNOLOGIES PVT.LTD.</h2><p align="right" style="margin:0px;padding:0px;font-weight:bold;font-size:15px;height:20px;margin-top:30px;">Date : '.date('d-m-Y').'</p></div>';
			 $output.='<hr><center><caption><b style="font-size:20px;">E-Crime Verification Report</b></caption></center>';
			 $searchTerm_arr=$data->searchTerm;
			 $output.='<div style="margin-left:20px;">
				<table style="width:100%;"><br>
				<p style="font-size:15px;"><b>Provided Information : </b></p>
					<tr>
						<td style="padding:5px;width:100px;"><b>Name : </b>'.$searchTerm_arr[0]->name.'</td>';
						
						if($searchTerm_arr[0]->fatherName=='')
						{
							$output.='<td style="padding:5px;width:100px;"><b>Father Name : </b>Not mention in your Aadhar</td>';
							//$output.='<td style="padding:5px;width:100px;"><b>Care of : </b>Not mention in your Aadhar</td>';
						}
						else
						{
							if(strpos($searchTerm_arr[0]->fatherName,":")!==false)
							{
								$father_name_arr=explode(":",$searchTerm_arr[0]->fatherName);
								if(sizeof($father_name_arr)>=1)
								{
									$father_name=$father_name_arr[1];
								}
								else
								{
									$father_name='Not mention in your Aadhar';
								}
							}
							else
							{
								$father_name=$searchTerm_arr[0]->fatherName;
							}
							$output.='<td style="padding:5px;width:100px;"><b>Father Name : </b>'.$father_name.'</td>';
							//$output.='<td style="padding:5px;width:100px;"><b>Care of : </b>'.$father_name.'</td>';
						}
						if(($searchTerm_arr[0]->dob=='') || ($searchTerm_arr[0]->dob=='0000-00-00'))
						{
							$dob='Not mention in your Aadhar';
						}
						else
						{
							$dob=date('d-m-Y',strtotime($searchTerm_arr[0]->dob));
						}
					$output.='</tr>
					<tr> 
						<td style="padding:5px;width:100px;"><b>Date of Birth : </b>'.$dob.'</td>
						<td style="padding:5px;width:100px;"><b>Address :  </b>'.$searchTerm_arr[0]->address.'</td>
					</tr>
				</table>
			</div>
			<div style="margin-left:20px;">
				<table style="width:100%;"><br>
				<p style="font-size:15px;"><b>Report Summary : </b></p>
					<tr>
						<td style="padding:5px"><b>Case RefNO : </b>'.$data->requestId.'</td>
						<td style="padding:5px"><b>Verification Date : </b>'.$data->responseTime.'</td>
					</tr>
					<tr>
						<td style="padding:5px"><b>Total cases : </b>'.$data->numberOfCases.'</td>
					</tr>
				</table>
			</div>
			<hr>';
			
			$output .= '<p style="font-size:15px;"><b>Case Details : </b></p><br>
			<table style="font-family: arial, sans-serif;border-collapse: collapse;width: 100%;margin: auto;" border="1">
			  <tr>
				<th style="border: 1px solid black;text-align: center;padding: 8px;width:1%;font-size:14px;">Sr.No.</th>
				<th style="border: 1px solid black;text-align: center;padding: 8px;width:26%;font-size:14px;">Case Details</th>
				<th style="border: 1px solid black;text-align: center;padding: 8px;width:26%;font-size:14px;">Petitioner</th>
				<th style="border: 1px solid black;text-align: center;padding: 8px;width:26%;font-size:14px;">Respondent</th>
				<th style="border: 1px solid black;text-align: center;padding: 8px;width:10%;font-size:14px;">Status</th>
			  </tr>';
			  $len=sizeof($data->caseDetails);
			  $case_detail_arr=$data->caseDetails;
			  if($len>0)
			  {
				for($i=0;$i<$len;$i++)
				  {
					  $output.=' <tr>
						<td style="border: 1px solid black;text-align: center;width:1%;font-size:10px;padding: 2px;">'.$case_detail_arr[$i]->slNo.'</td>
						<td style="border: 1px solid black;width:26%;font-size:10px;padding: 2px;">
						'.$case_detail_arr[$i]->cinNumber.'<br>
						<b>Court : </b>'.$case_detail_arr[$i]->courtName.'<br>
						<b>State : </b>'.$case_detail_arr[$i]->state.'<br>
						<b>Act & Section :</b> '.$case_detail_arr[$i]->section.', '.$case_detail_arr[$i]->underAct.'<br>
						<b>Case Type :</b> '.$case_detail_arr[$i]->caseType.'<br>
						<b>Filling Date :</b> '.$case_detail_arr[$i]->filingDate.'<br>
						</td>
						<td style="border: 1px solid black;width:26%;font-size:10px;padding: 2px;">'.$case_detail_arr[$i]->petitioner.'</td>
						<td style="border: 1px solid black;width:26%;font-size:10px;padding: 2px;">'.$case_detail_arr[$i]->respondent.'<br>
						<b>Name : </b>'.$case_detail_arr[$i]->respondentAddress.'
						</td>
						<td style="border: 1px solid black;width:10 %;font-size:10px;padding: 2px;">'.$case_detail_arr[$i]->caseStatus.'</td>
					  </tr>';
					}  
			  }
			  else
			  {
				 $output.=' <tr>
						
						<td colspan="5" style="text-align:center;font-size:12px;padding: 7px;border: 1px solid black;">No Criminal Record Found</td>
					  </tr>';
			  }				  
	 
			  /*$output.='<tr>
				<th style="border: 1px solid black;text-align: right;padding: 8px;width:50%;">Total :</th>
				<th style="border: 1px solid black;text-align: center;padding: 8px;width:50%;">Rs.  '.$total.'</th>
			  </tr>*/
	  
			$output.='</table>
			<p style="font-size:10px;padding-top:0px;font-family: arial, sans-serif;margin-bottom:50px;text-align:justify;"><b style="color:red;">Disclaimer: </b>'.$data->disclaimer.'</p>
			';
			$output.='<footer><p style="text-align:right;font-size:10px;position: fixed;
                bottom: -60px;">This is system generated report.</p></footer>';
			//echo $output;  die;
			$dompdf = new PDF();
			$dompdf->load_html($output);
			$dompdf->render();
			$output = $dompdf->output();
            $fetch_specification="select specification_id from verification_process_specification_header_all where abbreviations='CR'";
            $res_specification=mysqli_query($mysqli, $fetch_specification);
            $arr_specification=mysqli_fetch_assoc($res_specification);
			$target_dir_profile = "verification/".$arr_specification['specification_id'];
			
			if (!file_exists($target_dir_profile)) {
            mkdir($target_dir_profile, 0777, true);
        }
			//$name1=str_replace(' ', '-', $name);;
			$id=uniqid();
						$file_name=$id.'.pdf';
			file_put_contents('verification/'.$arr_specification['specification_id'].'/'.$file_name, $output);
			
			//$this->pdf->loadHtml($output);
			//$this->pdf->render();
			//file_put_contents("abc".date('Y-m-d').".pdf", $this->output());
		/*$ip=$_SERVER['SERVER_NAME'];
	if($ip=='miscos.in')
	{
		$path = "http://".$ip."/individual_app/verification_api/pancard_report/".$member_id."/".$file_name;
	}
	else
	{*/
		$path = $base_url."verification/".$arr_specification['specification_id']."/".$file_name;
	//}
	$sql="UPDATE `v_e_crime_active_transaction_all` SET `verification_report`='".$path."',verification_status='Report Generated' WHERE `e_crime_request_id`='".$data->requestId."'";
	$res_sql=mysqli_query($mysqli,$sql);
	if($res_sql==true)
	{
		//write a notification code here
		$row_data=mysqli_fetch_assoc(mysqli_query($mysqli,"SELECT `registration_id`,
		`member_id`,
		`name` FROM member_header_all WHERE member_id='".$member_id."'"));
		$row_data1=mysqli_fetch_assoc(mysqli_query($mysqli,"SELECT `token_id`,
		`name` FROM registration_header_all WHERE registration_id='".$row_data['registration_id']."'"));

		$notification_data=[];
			$notification_data["agency_id"]=$row_data['registration_id'];
			$notification_data["member_id"]=$row_data['member_id'];
			$notification_data["device_token"]=$row_data1['token_id'];
			$notification_data["notification_for"]='e_crime';
			$notification_data["show_view_more_btn"]='Y';
			$notification_data["url"]=$path;
			$notification_data["title"]='E-Crime Report has been generated...';
			$agecny_name=$row_data1['name'];
			$member_name=$row_data['name'];
			
			$body="Hello $agecny_name, $member_name e-crime report is generated please check.";
			$notification_data["body"]="Hello $agecny_name,$member_name's e-crime report is generated to check click on view more.";
			
			$res=send_notification($mysqli,$notification_data,$body);
		error_log('success');
	}
	// Query in database for the above request ID, and mark it as complete
	// Save all relevant fields from the JSON, like downloadLink, risk category, case details
	
}

function send_notification($mysqli,$notification_data,$body)
{
	$device_token_1=$notification_data["device_token"];
	$agency_id=$notification_data["agency_id"];
	$member_id=$notification_data["member_id"];
	$customize_data=$notification_data["url"];
	
	$post_data = json_encode(array('title' => $notification_data["title"],'body' => $notification_data["body"],'icon' =>'myIcon','sound' => 'mySound','image' => '','url'=>$notification_data["url"],'show_view_more_btn'=>$notification_data["show_view_more_btn"],'notification_for'=>$notification_data["notification_for"]), JSON_FORCE_OBJECT);
	$fcmUrl = 'https://fcm.googleapis.com/fcm/send';
	
	$extraNotificationData = ["moredata" =>$post_data];	
	
	$fcmNotification = [
		// 'registration_ids' => $device_token_1, //multple token array
		'to'        => $device_token_1, //single token
		'data' => $extraNotificationData
	];

	$headers = [
		'Authorization: key=AAAAfWOCXq0:APA91bF3iFhlxvy1g85umDRXaW0gRDzvHwf1cqkoLTEH-RsVYJI768ocMAaVlASR3zwLyGygBXYuh3_Kek52p2uu1ScErmOgI9TSuklckOPk4Mnfzykcr1mDVco9yXfvOZ9uTMbEUcF-',
		'Content-Type: application/json'
	];
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,$fcmUrl);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
	$result = curl_exec($ch);
	$res=json_decode($result,true);
	//print_r($result);
	//error_log('notification result='.print_r($result));
	//file_put_contents('vm.php', "ashdjasd=".print_r($result));
	//echo "<br>";
	curl_close($ch);
	///in//sert data into notification table+
	 
	$sql="INSERT INTO `notification_header_all`(`agency_id`, `member_id`, `notification_title`, `body`, `customize_data`, `success_cnt`, `failure_cnt`, `created_on`) VALUES ('$agency_id','$member_id','".$notification_data["title"]."','$body','$customize_data','".$res['success']."','".$res['failure']."','".date('Y-m-d H:i:s')."')";
	$res_sql=mysqli_query($mysqli,$sql);
	
	return $res['success'];
				
}
?>