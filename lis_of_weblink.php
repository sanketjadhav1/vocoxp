<?php
include 'connection.php';
$connection = connection::getInstance();
$mysqli = $connection->getConnection();

$system_date = date("d-m-Y H:i:s");

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    $agency_id = isset($_GET['agency_id']) ? mysqli_real_escape_string($mysqli, $_GET['agency_id']) : '';

    if (!empty($agency_id)) {
        $fetch_weblink_status = "SELECT * FROM `bulk_weblink_request_all` WHERE `agency_id`='$agency_id' ORDER BY `id` DESC";
        $res_weblink_status = mysqli_query($mysqli, $fetch_weblink_status);

        $data = [];
        if ($res_weblink_status && mysqli_num_rows($res_weblink_status) > 0) {
            //weblink_for_arr - get all excel names array according to excel no.
            $sample_excel_definations_all = "SELECT excel_no, excel_name FROM `sample_excel_definations_all`";
            $sample_excel_definations_all = mysqli_query($mysqli, $sample_excel_definations_all);
            $sample_excel_definations_all = mysqli_fetch_all($sample_excel_definations_all, MYSQLI_ASSOC);
            $weblink_for_arr = array_column($sample_excel_definations_all, 'excel_name', 'excel_no');

            while ($row = mysqli_fetch_assoc($res_weblink_status)) {
                $total_count = 0;
                $total_count_open = 0;
                $total_count_comp = 0;
                $bulk_id = $row['bulk_id'];
                $obj_1_verifications = [];
                $obj_2_verifications = [];
                $obj_3_verifications = [];


                $fetch_end = "SELECT * FROM `bulk_end_user_transaction_all` WHERE `bulk_id`='$bulk_id' AND `agency_id`='$agency_id' AND `name`!=''";
                $res_end = mysqli_query($mysqli, $fetch_end);

                $total_count = mysqli_num_rows($res_end); // Counting total end-user transactions
                if ($total_count > 0) {
                    $arr_end = mysqli_fetch_assoc($res_end);
                    if ($arr_end['status'] == 1) {
                        $total_count_open++;
                    }
                    if ($arr_end['verification_report'] != "") {
                        $total_count_comp++;
                    }
                }

                // Mapping verification values to their descriptions
                //     if(!empty($row['obj_1_verifications'])){
                //         $obj_1_verifications = explode(',', $row['obj_1_verifications']);
                //     }
                //     if(!empty($row['obj_2_verifications'])){
                //          $obj_2_verifications = explode(',', $row['obj_2_verifications']);
                //     }
                //     if(!empty($row['obj_3_verifications'])){
                //         $obj_3_verifications = explode(',', $row['obj_3_verifications']);
                //     }
                // $verification_map = [
                //     'DVF-00001' => 'Aadhar Card',
                //     'DVF-00002' => 'PAN Card',
                //     'DVF-00003' => 'Criminal Record Verification',
                //     'DVF-00004' => 'Driving license',
                //     'DVF-00005' => 'Voter ID',
                //     'DVF-00006' => 'Domestic Passport',
                //     'DVF-00007' => 'International Passport',
                //     'DVF-00008' => 'Mobile Verification'
                // ];

                // $obj_1_verifications = array_map(function($v) use ($verification_map) {
                //     return $verification_map[$v] ?? $v;
                // }, $obj_1_verifications);
                // $obj_2_verifications = array_map(function($v) use ($verification_map) {
                //     return $verification_map[$v] ?? $v;
                // }, $obj_2_verifications);
                // $obj_3_verifications = array_map(function($v) use ($verification_map) {
                //     return $verification_map[$v] ?? $v;
                // }, $obj_3_verifications);

                // // Converting back to a comma-separated string if needed
                // $row['obj_1_verifications'] = implode(',', $obj_1_verifications);
                // $row['obj_2_verifications'] = implode(',', $obj_2_verifications);
                // $row['obj_3_verifications'] = implode(',', $obj_3_verifications);

                // Mapping status codes to their descriptions
                switch ($row['status']) {
                    case 1:
                        $row['status'] = 'Generated';
                        break;
                    case 2:
                        $row['status'] = 'Uploaded';
                        break;
                    case 3:
                        $row['status'] = 'Weblink Generated';  //End Users Link Generated
                        break;
                    case 4:
                        $row['status'] = 'Force Closed';
                        break;
                    case 5:
                        $row['status'] = 'Completed';
                        break;
                }

                // Formatting date
                $row['upload_weblink_generated_on'] = date("d-m-Y h:i A", strtotime($row['upload_weblink_generated_on']));
                $row['total_web_links'] = $total_count;
                $row['web_link_opened'] = $total_count_open;
                $url = $row['upload_weblink'];
                $updatedUrl = str_replace(["https://mounarchtech.com/vocoxp/", "https://mounarchtech/vocoxp/"], "", $url);
                $row['web_link_comp'] = $total_count_comp;
                $row['upload_weblink'] = $updatedUrl;
                $row['weblink_for'] = (isset($weblink_for_arr[$row['excel_no']])) ? $weblink_for_arr[$row['excel_no']] : '';
                $data[] = $row;
            }

            $res = ["error_code" => 100, "message" => "success", "data" => $data];
        } else {
            $res = ["error_code" => 101, "message" => "data not found"];
        }
    } else {
        $res = ["error_code" => 102, "message" => "agency_id is required"];
    }

    echo json_encode($res);
    return;
}
