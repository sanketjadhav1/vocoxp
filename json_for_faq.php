<?php

//this json is used for get advertisement image
include __DIR__ . '/vendor/autoload.php';
include_once 'connection.php';
// error_reporting(E_ALL & ~E_DEPRECATED);
// ini_set('display_errors', 1);
// Now you can use the connection class
$connection = connection::getInstance();
$mysqli = $connection->getConnection();

$connection1 = database::getInstance();
$mysqli1 = $connection1->getConnection();

$mrow_gen=array();
$mrow_issue=array();
$mrow_cat=array();
date_default_timezone_set('Asia/kolkata');
$system_date = date("y-m-d");
$system_date_time = date("Y-m-d H:i:s");
$output = $responce = array();
header('Content-Type: application/json');
 $data_application=[];
 $maindata_application=[];
if ($_SERVER["REQUEST_METHOD"] == "GET")
{
	if(!isset($_GET['mode']))
	{
		$responce[] = ["error_code" => 101, "message" => "Parameter missing"];
	}
	else if(empty($_GET['mode']))
	{
		$responce[] = ["error_code" => 101, "message" => "mode is empty"];
	}
	else
	{
		$mode = mysqli_real_escape_string($mysqli, $_GET['mode']);
		//if($mode =="Prem_1408")
		//{
			//$data=array();
		$sql = "SELECT `application_id`,`application_name`,`status` FROM help_and_support_category_header_all WHERE  row_for='application' and status='0' and application_id='$mode'";
			$res = mysqli_query($mysqli, $sql);
			if(mysqli_num_rows($res)>0)
			{
				while($row=mysqli_fetch_assoc($res))
				{
					$applicationId=$row['application_id'];
					$data['application_id']=$row['application_id'];
					$data['application_name']=$row['application_name'];
					$data['status']=$row['status'];
					
					$data2=array();
					$data3=array();
					$cat_data['issues']=[];
					//$data6=array();
					//$data3['generalissues']=[];
					$querya="SELECT `cat_id`,`category_name`,`status`,`create_on`,`row_for` FROM help_and_support_category_header_all WHERE application_id='$applicationId' and  row_for='category' and status='0'";
					$res1 = mysqli_query($mysqli, $querya);
					if(mysqli_num_rows($res1)>0)
					{
						$data3=array();
						$cat_data['issues']=[];
						//$data6=array();
						//$data3['generalissues']=[];
						while($row2=mysqli_fetch_assoc($res1))
						{
							$cat_data['categoryId']=$row2['cat_id'];
							$cat_data['category_name']=$row2['category_name'];
							$cat_data['status']=$row2['status'];
							$cat_data['create_on']=date('d-m-Y',strtotime($row2['create_on']));
							
							
							$data3=array();
							$cat_data['issues']=[];
							//$data6=array();
							//$data3['generalissues']=[];
							$query11= "SELECT `issue_id`,`issue_name`,`status`,`created_on` FROM   help_and_support_issue_header_all WHERE application_id='$applicationId' and cat_id='".$row2['cat_id']."' and status='0' and row_for='issue'";
							$res11 = mysqli_query($mysqli, $query11);
							if(mysqli_num_rows($res11)>0)
							{
								while($row3=mysqli_fetch_assoc($res11))
								{
									$issue_data['issueid']=$row3['issue_id'];
									$issue_data['issue_name']=$row3['issue_name'];
									$issue_data['status']=$row3['status'];
									$issue_data['create_on']=date('d-m-Y',strtotime($row3['created_on']));
									
									
									$data6=array();
									$issue_data['generalissues']=[];
									$queryb = "SELECT `general_issues` FROM   help_and_support_issue_header_all WHERE issue_id='".$row3['issue_id']."' and application_id='$applicationId' and cat_id='".$row2['cat_id']."' and status='0' and row_for='general_answer'";
									$res_sqlb=mysqli_query($mysqli,$queryb);
									if(mysqli_num_rows($res_sqlb)>0)
									{
										while($row5=mysqli_fetch_assoc($res_sqlb))
										{
											$general_data['general_issue']=$row5['general_issues'];
											//$general_data['general_issue']='Other';
											$data6[]=$general_data;
										}
										$data6[]=[
											'general_issue'=>'other',
										];
										$issue_data['generalissues']=$data6;
									}
									//$issue_data['generalissues']=$data6;
									$data3[]=$issue_data;
								}
								$data3[]=[
									'issueid'=>'',
									'issue_name'=>'Other',
									'status'=>'0',
									'create_on'=>''
								];
								$cat_data['issues']=$data3;
							}
							$data2[]=$cat_data;
						}
						$data2[]=[
							'categoryId'=>'',
							'category_name'=>'Other',
							'status'=>'0',
							'create_on'=>''	
							];
					}
					$data['categories']=$data2;
					$data4[]=$data;
				}
				if(!empty($data4))
				{
					$responce[] = ["successcode" => 100, "message" => "Success.", "data" => $data4];
				}
				else
				{
					$responce[] = ["error_code" => 101, "message" => "Data not avaliable."];
				}
				//print_r($data4);
			}
			else
			{
				$responce[] = ["error_code" => 101, "message" => "Data not avaliable."];
			}
		//}
		/*else
		{
			$responce[] = ["error_code" => 101, "message" => "Invalid mode"];
		}*/
	
echo json_encode($responce);
}

}
?>