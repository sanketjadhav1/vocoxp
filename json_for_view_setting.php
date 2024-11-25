<?php
include 'connection.php';
$connection = connection::getInstance();
$mysqli = $connection->getConnection();

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    $bulk_id = $_GET['bulk_id'];
    $agency_id = $_GET['agency_id'];

    // Prepare and execute the query
    $stmt = $mysqli->prepare("SELECT * FROM `bulk_upload_file_information_all` WHERE `agency_id`=? AND `bulk_id`=?");
    $stmt->bind_param("ss", $agency_id, $bulk_id);
    $stmt->execute();
    $res_setting = $stmt->get_result();
    $arr_setting = $res_setting->fetch_assoc();
    $stmt->close();

    if ($arr_setting) {
        // Mapping values to descriptions
        $reminder_mapping = [
            '1' => 'Daily',
            '2' => 'Every alternate day',
            '0' => 'No reminder'
        ];
        $paid_by_mapping = [
            '1' => 'Wallet',
            '2' => 'End User'
        ];

        $arr_setting['reminder_sms'] = $reminder_mapping[$arr_setting['reminder_sms']] ?? 'Unknown';
        $arr_setting['reminder_email'] = $reminder_mapping[$arr_setting['reminder_email']] ?? 'Unknown';
        $arr_setting['paid_by'] = $paid_by_mapping[$arr_setting['paid_by']] ?? 'Unknown';

        // Format dates
        $date_fields = [
            'uploaded_datetime',
            'weblink_generated',
            'weblink_activated_from',
            'weblink_valid_till'
        ];

        foreach ($date_fields as $field) {
            if (!empty($arr_setting[$field])) {
                 $arr_setting[$field] = (new DateTime($arr_setting[$field]))->format('d-m-Y H:i:s');
                $dateTimeParts = explode(' ', $arr_setting[$field]);
 $datePart = $dateTimeParts[0];
 $timePart = $dateTimeParts[1];
if ($datePart === '30-11--0001') {
    $arr_setting[$field] = "-"; // Display only the date
} else if ($timePart === '00:00:00') {
    $arr_setting[$field] = $datePart; // Display only the date
} else {
    $arr_setting[$field] = $arr_setting[$field]; // Display the full date and time
}
// echo $arr_setting[$field]."<br/>";
            }
        }

        // Wrap the data inside an array
        // $data = [$arr_setting];

        $res = ["error_code" => 100, "message" => "success", "data" => $arr_setting];
    } else {
        $res = ["error_code" => 109, "message" => "failed"];
    }

    echo json_encode($res);
}
?>
