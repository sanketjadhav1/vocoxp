<?php
include_once 'connection.php';
// error_reporting(E_ALL & ~E_DEPRECATED);
// ini_set('display_errors', 1);
// Now you can use the connection class
$connection = connection::getInstance();
$mysqli = $connection->getConnection();
apponoff($mysqli);
logout($_SERVER['HTTP_AUTH_KEY'], $mysqli);
$systemdatetime = date("Y-m-d H:i:s");
$agency_id=$_POST['agency_id'];
 $fetch_setting = "SELECT 
    `direct_invoice`, 
    `in_app_invoice`, 
    `web_link_invoice`, 
    `direct_report_generate`, 
    `in_app_report_generation`, 
    `web_link_report_generation` 
FROM 
    `agency_header_all` 
WHERE 
    `agency_id`='$agency_id';
";

$res_setting = mysqli_query($mysqli, $fetch_setting);
$arr_setting = mysqli_fetch_assoc($res_setting);

 $sql_setting = "SELECT 
    `add_family_member`, 
    `app_setting`, 
    `modified_on` , `email_for_direct`, `email_for_inapp`, `email_for_weblink`
FROM 
    `agency_setting_all` 
WHERE 
    `agency_id`='$agency_id';
";

$res_sql = mysqli_query($mysqli, $sql_setting);
$arr_sql = mysqli_fetch_assoc($res_sql);
// print_r($arr_sql);
// print_r($arr_setting);
// Merge arrays with null check

$all_record[] = array_merge($arr_sql, $arr_setting);

$res[]=["error_code"=>100, "message"=>"Data fetch", "data"=>$all_record];
echo json_encode($res);
return;
?>
