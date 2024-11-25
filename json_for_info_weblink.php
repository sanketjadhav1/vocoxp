<?php
include 'connection.php';
$connection = connection::getInstance();
$mysqli = $connection->getConnection();

$connection1 = database::getInstance();
$mysqli1 = $connection1->getConnection();
// error_reporting(E_ALL & ~E_DEPRECATED);
// ini_set('display_errors', 1);

$system_date = date("d-m-Y H:i:s");

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    $agency_id = $_GET['agency_id'];
    $bulk_id = $_GET['bulk_id'];
    $request_no = $_GET['request_no'];

    $fetch_weblink_status = "SELECT `status`, `upload_weblink_generated_on`, `request_email_sent` FROM `bulk_weblink_request_all` WHERE `agency_id`='$agency_id' AND `bulk_id`='$bulk_id' AND `request_no`='$request_no'";
    $res_weblink_status = mysqli_query($mysqli, $fetch_weblink_status);
    $arr_weblink_status = mysqli_fetch_assoc($res_weblink_status);

    $fetch_count = "SELECT `total_partial_done`, `total_completed`, `total_end_user` FROM `bulk_upload_file_information_all` WHERE `agency_id`='$agency_id' AND `bulk_id`='$bulk_id'";
    $res_count = mysqli_query($mysqli, $fetch_count);
    $arr_count = mysqli_fetch_assoc($res_count);

    if ($arr_weblink_status || $arr_count) {
        if($arr_weblink_status['status']==1){
            $arr_weblink_status['status']='Generated';
        }elseif($arr_weblink_status['status']==2){
            $arr_weblink_status['status']='Uploaded';
        }elseif($arr_weblink_status['status']==3){
            $arr_weblink_status['status']='Weblink Generated';
        }elseif($arr_weblink_status['status']==4){
            $arr_weblink_status['status']='Force Closed';
        }elseif($arr_weblink_status['status']==5){
            $arr_weblink_status['status']='Completed';
        }
        $data[] = [
            
            "weblink_status" => $arr_weblink_status['status'], 
            "generated_on" => date("d-m-Y H:i:s", strtotime($arr_weblink_status['upload_weblink_generated_on'])), 
            "request_on" => $arr_weblink_status['request_email_sent'], 
            "total_member" => $arr_count['total_end_user'], 
            "partial" => $arr_count['total_partial_done'], 
            "complete" => $arr_count['total_completed']
        ];
        $res = ["error_code" => 100, "message" => "success", "data" => $data];
    } else {
        $res = ["error_code" => 101, "message" => "data not found"];
    }

    echo json_encode($res);
    return;
}
?>
