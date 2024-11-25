<?php
 include 'connection.php';
 //Application Vocoxp databese connection//
 $connection = connection::getInstance();
 $mysqli = $connection->getConnection();
 //Central Database Connection//
 $connection1 = database::getInstance();
 $mysqli1 = $connection1->getConnection();

// print_r($_POST);
$system_date_time=date("Y-d-m H:i:s");
 $name=$_POST['name'];
 $date_of_birth=$_POST['date_of_birth'];
 $address=$_POST['address'];
 $gender=$_POST['gender'];
 $application_id=$_POST['application_id'];
 $base_amount=$_POST['base_amount'];
 $cgst_amount=$_POST['cgst_amount'];
 $sgst_amount=$_POST['sgst_amount'];
 $verification_id=$_POST['verification_id'];
 $admin_id=$_POST['admin_id'];
  $agency_id=$_POST['agency_id'];
 $aadhar_number=$_POST['aadhar_number'];
 $date_of_birth=$_POST['date_of_birth'];
 $address=$_POST['address'];
 $gender=$_POST['gender'];
 $front_img=$_FILES['front_photo'];
 $back_img=$_FILES['back_photo'];
 $direct_id = unique_id_genrate('DIR', 'direct_verification_details_all', $mysqli);

 $insert_pan_payment = "INSERT INTO `direct_verification_details_all` (`direct_id`, `application_id`, `agency_id`, `verification_id`, `initiated_on`, `completed_on`, `activity_status`, `linked_table`, `deducted_base_amount`, `sgst_amount`, `cgst_amount`, `report_url`, `source_from`) VALUES ('$direct_id', '$application_id', '$agency_id', '$verification_id', '$system_date_time', '$system_date_time', '2', 'direct_aadhar_details_all', '$base_amount', '$sgst_amt', '$cgst_amt', '$path', '$source_from' )
";
$res_pan = mysqli_query($mysqli, $insert_pan_payment);

// Insert into `direct_pan_details_all`
 $insert_aadhar_detail = "INSERT INTO `direct_aadhar_details_all` (`direct_id`, `application_id`, `agency_id`, `initiated_on`, `completed_on`, `activity_status`, `aadhar_number`, `name`, `dob`, `gender`, `address`, `user_photo`, `front_photo`, `back_photo`, `admin_id`) VALUES ('$direct_id', '$application_id', '$agency_id', '$system_date_time', '$system_date_time', '2', '$aadhar_number', '$name', '$dob1', '$gender', '$address', '$user_img', '$front_img', '$back_img', '$admin_id')
";
$res_pan_detail = mysqli_query($mysqli, $insert_aadhar_detail);

if($res_pan && $res_pan_detail){
    $res=["error_code"=>100, "message"=>"success"];
    echo json_encode($res);
    return;
}
?>