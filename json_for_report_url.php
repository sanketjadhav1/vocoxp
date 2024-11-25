<?php
include 'connection.php';
$connection = connection::getInstance();
$mysqli = $connection->getConnection();

$connection1 = database::getInstance();
$mysqli1 = $connection1->getConnection();

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    $bulk_id = $_GET['bulk_id'];
    $agency_id = $_GET['agency_id'];

    $fetch_setting = "SELECT * FROM `bulk_end_user_transaction_all` WHERE `agency_id`='$agency_id' AND `bulk_id`='$bulk_id' AND `verification_report` != ''";

    $res_setting = mysqli_query($mysqli, $fetch_setting);

    $data = array();

    if ($res_setting) {
        $status_map = [
            '0' => 'not initiated',
            '1' => 'opened',
            '2' => 'partial verifications',
            '3' => 'completed'
        ];

        $payment_map = [
            '1' => 'agency wallet',
            '2' => 'End user'
        ];

        $types = ['1' => 'aadhar', '2' => 'pan', '3' => 'voter', '4' => 'dl', '5' => 'e-crime'];

        while ($arr_setting = mysqli_fetch_assoc($res_setting)) {
            $scheduled_verifications = explode(',', $arr_setting['scheduled_verifications']);
            $verification_reports = explode(',', $arr_setting['verification_report']);

            foreach ($scheduled_verifications as $index) {
                if (isset($types[$index])) {
                    $new_record = [
                        'end_user_id' => $arr_setting['end_user_id'],
                        'type' => $types[$index],
                        'verification_report' => $verification_reports[$index - 1] ?? '',
                        'name' => $arr_setting['name'],
                        'mobile' => $arr_setting['mobile'],
                        'email_id' => $arr_setting['email_id'],
                        'sms_sent' => $arr_setting['sms_sent'],
                        'email_sent' => $arr_setting['email_sent'],
                        'status' => $status_map[$arr_setting['status']] ?? $arr_setting['status'],
                        'verification_details' => $arr_setting['verification_details'],
                        'weblink_opened_on' => $arr_setting['weblink_opened_on'],
                        'reminder_email' => $arr_setting['reminder_email'],
                        'reminder_sms' => $arr_setting['reminder_sms'],
                        'payment_invoice' => $arr_setting['payment_invoice'],
                        'payment_done_by' => $payment_map[$arr_setting['payment_done_by']] ?? $arr_setting['payment_done_by']
                    ];
                    if($new_record['verification_report']!=""){
                        $data[] = $new_record;
                    }
                    
                }
            }
        }

        if (!empty($data)) {
            $res = ["error_code" => 100, "message" => "success", "data" => $data];
        } else {
            $res = ["error_code" => 109, "message" => "no records found"];
        }
    } else {
        $res = ["error_code" => 109, "message" => "query execution failed"];
    }

    echo json_encode($res);
}
?>
