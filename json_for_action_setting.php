<?php

include_once 'connection.php';

// Now you can use the connection class
$connection = connection::getInstance();
$mysqli = $connection->getConnection();
apponoff($mysqli);
logout($_SERVER['HTTP_AUTH_KEY'], $mysqli);
$systemdatetime=date("Y-m-d H:i:s");

$agency_id=$_POST['agency_id'];
$add_family_member = $_POST['add_family_member'];
$send_invoice_app=$_POST['send_invoice_app'];
$send_invoice_weblink=$_POST['send_invoice_weblink'];
$send_invoice_direct=$_POST['send_invoice_direct'];
$send_ver_report_app=$_POST['send_ver_report_app'];
$send_ver_report_weblink=$_POST['send_ver_report_weblink'];
$send_ver_report_direct=$_POST['send_ver_report_direct'];
$app_setting = $_POST['app_setting'];
$inapp_email = $_POST['inapp_email'];
$weblink_email = $_POST['weblink_email'];
$direct_email = $_POST['direct_email'];
$update_email = $_POST['update_email'];

if($update_email==1){
    $update_email_agency="UPDATE `agency_setting_all` SET `email_for_direct`='$direct_email', `email_for_inapp`='$inapp_email', `email_for_weblink`='$weblink_email' WHERE `agency_id`='$agency_id'";
    $res_email=mysqli_query($mysqli, $update_email_agency);
}

$update_setting_agency="UPDATE `agency_header_all` SET `direct_invoice`='$send_invoice_direct', `in_app_invoice`='$send_invoice_app', `web_link_invoice`='$send_invoice_weblink', `direct_report_generate`='$send_ver_report_direct', `in_app_report_generation`='$send_ver_report_app', `web_link_report_generation`='$send_ver_report_weblink' WHERE `agency_id`='$agency_id'";
$res_setting_agency=mysqli_query($mysqli, $update_setting_agency);

$update_setting="UPDATE `agency_setting_all` SET `add_family_member`='$add_family_member', `app_setting`='$app_setting', `modified_on`='$systemdatetime' WHERE `agency_id`='$agency_id'";
$res_setting=mysqli_query($mysqli, $update_setting);

if($res_setting_agency==true && $res_setting==true){
    $response[] = array("error_code" => 100, "message" => "Setting upload successfully");
    echo json_encode($response);
    return;
}
?>