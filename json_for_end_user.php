<?php
include 'connection.php';
$connection = connection::getInstance();
$mysqli = $connection->getConnection();

$connection1 = database::getInstance();
$mysqli1 = $connection1->getConnection();

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    $bulk_id = $_GET['bulk_id'];
    $agency_id = $_GET['agency_id'];
    $query_delete = "DELETE FROM `bulk_end_user_transaction_all` WHERE `agency_id`='$agency_id' AND `bulk_id`='$bulk_id' AND `name`=''";
    $res_query_delete = mysqli_query($mysqli, $query_delete);
                     
    $fetch_setting = "SELECT 
    b.*
FROM 
    bulk_end_user_transaction_all b
LEFT JOIN 
    (
        -- Get the primary end_user_id for each email based on payment status
        SELECT 
            e.email_id,
            e.end_user_id AS primary_end_user_id
        FROM 
            bulk_end_user_transaction_all e
        INNER JOIN 
            end_user_payment_transaction_all p
        ON 
            e.end_user_id = p.end_user_id
        WHERE 
            e.email_id != ''
            AND p.status = 'success' -- Adjust based on your payment success condition
        GROUP BY 
            e.email_id
    ) p
ON 
    b.email_id = p.email_id
WHERE 
    (p.primary_end_user_id IS NULL OR b.end_user_id = p.primary_end_user_id)
    AND b.bulk_id = '$bulk_id'
    AND b.agency_id = '$agency_id'
GROUP BY 
    CASE 
        WHEN b.email_id = '' THEN b.end_user_id 
        ELSE b.email_id 
    END;

";
    $res_setting = mysqli_query($mysqli, $fetch_setting);

    $data = array();

    if ($res_setting) {
        $verification_map = [
            'DVF-00001' => 'Aadhar Card',
            'DVF-00002' => 'PAN Card',
            'DVF-00003' => 'Voter',
            'DVF-00004' => 'Driving Licence',
            'DVF-00008' => 'Mobile Verification',
            'DVF-00005' => 'E-Crime'
        ];

        $status_map = [
            '0' => 'not initiated',
            '1' => 'opened',
            '2' => 'partial verifications',
            '3' => 'completed'
        ];

        $paid_map = [
            '1' => 'Agency Wallet',
            '2' => 'End User'
        ];

        while ($arr_setting = mysqli_fetch_assoc($res_setting)) {
            // Mapping scheduled_verifications to their descriptions
            $verifications = explode(',', $arr_setting['scheduled_verifications']);
            $arr_setting['scheduled_verifications'] = array_map(function($v) use ($verification_map) {
                return $verification_map[$v] ?? $v;
            }, $verifications);

            // Converting back to a comma-separated string
            $arr_setting['scheduled_verifications'] = implode(',', $arr_setting['scheduled_verifications']);

            // Mapping status to its description
            $arr_setting['status'] = $status_map[$arr_setting['status']] ?? $arr_setting['status'];

            // Mapping paid_by to its description
            $arr_setting['payment_from'] = $paid_map[$arr_setting['payment_from']] ?? $arr_setting['payment_done_by'];
 $end_user_id  = $arr_setting['end_user_id'];
 $fetch_url = "SELECT * FROM `end_user_verification_transaction_all` WHERE `end_user_id`='$end_user_id'";
    $res_fetch_url = mysqli_query($mysqli, $fetch_url);

$arr_setting['verification_report'] = ""; // Initialize the verification report
if ($res_fetch_url && $res_fetch_url->num_rows > 0) {
    // Loop through each row in the result set
        while ($rowdd = mysqli_fetch_assoc($res_fetch_url)) {

        $document_type = $rowdd['document_type'];
        $report_url = $rowdd['report_url'];

        // Append to the verification report based on the document type
        if ($document_type == 'DVF-00001') {
            $arr_setting['verification_report'] .= "aadhar@".$report_url.",";
        }
        if ($document_type == 'DVF-00002') {
            $arr_setting['verification_report'] .= "pan@".$report_url.",";
        }
        if ($document_type == 'DVF-00003') {
            $arr_setting['verification_report'] .= "voter@".$report_url.",";
        }
        if ($document_type == 'DVF-00004') {
            $arr_setting['verification_report'] .= "dl@".$report_url.",";
        }
        if ($document_type == 'DVF-00005') {
            $arr_setting['verification_report'] .= "e_crime@".$report_url.",";
        }
        
    }
    // Remove the trailing comma, if necessary
    $arr_setting['verification_report'] = rtrim($arr_setting['verification_report'], ',');
} else {
    $arr_setting['verification_report'] = "NA";
}

            

            $data[] = $arr_setting;
        }
// die();
        if (!empty($data)) {
            $res = ["error_code" => 100, "message" => "success", "data" => $data];
        } else {
            $res = ["error_code" => 109, "message" => "data not found"];
        }
    } else {
        $res = ["error_code" => 109, "message" => "failed"];
    }

    echo json_encode($res);
    return;
}
?>
