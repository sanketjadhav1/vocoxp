<?php
include 'connection.php';

// Establish database connection
$connection = connection::getInstance();
$mysqli = $connection->getConnection();

$system_date = date("d-m-Y H:i:s");

if ($_SERVER['REQUEST_METHOD'] === "GET") {
    $agency_id = isset($_GET['agency_id']) ? mysqli_real_escape_string($mysqli, $_GET['agency_id']) : '';

    if (!empty($agency_id)) {
        // Fetch bulk weblink requests for the given agency
        $fetch_weblink_status = "SELECT * FROM `bulk_weblink_request_all` WHERE `agency_id` = '$agency_id' ORDER BY `id` DESC";
        $res_weblink_status = mysqli_query($mysqli, $fetch_weblink_status);

        $data = [];
        if ($res_weblink_status && mysqli_num_rows($res_weblink_status) > 0) {
            // Fetch all excel definitions
            $sample_excel_definitions_query = "SELECT excel_no, excel_name FROM `sample_excel_definations_all`";
            $sample_excel_definitions_result = mysqli_query($mysqli, $sample_excel_definitions_query);
            $sample_excel_definitions_all = mysqli_fetch_all($sample_excel_definitions_result, MYSQLI_ASSOC);

            // Map excel_no to excel_name
            $weblink_for_arr = array_column($sample_excel_definitions_all, 'excel_name', 'excel_no');

            // Process each row of weblink request data
            while ($row = mysqli_fetch_assoc($res_weblink_status)) {
                $total_count = 0;
                $total_count_opens = 0;
                $total_count_comp = 0;
                $bulk_id = $row['bulk_id'];

                // Fetch end-user transactions for the given bulk_id
                $fetch_end = "SELECT * FROM `bulk_end_user_transaction_all` WHERE `bulk_id` = '$bulk_id' AND `agency_id` = '$agency_id' AND `name` != '' GROUP BY CASE WHEN `email_id` = '' THEN `id` ELSE `email_id` END";
                $res_end = mysqli_query($mysqli, $fetch_end);

                if ($res_end) {
                    $total_count = mysqli_num_rows($res_end);

                    // Count opened and completed links
                    while ($end_row = mysqli_fetch_assoc($res_end)) {
                         if (!empty($end_row['verification_report']) || $end_row['status'] == 1) {
                                $total_count_opens++;
                            }
                        if (!empty($end_row['verification_report']) || $end_row['status'] == 3) {
                            $total_count_comp++;
                        }
                    }
                }

                // Map status codes to their descriptions
                $status_map = [
                    1 => 'Generated',
                    2 => 'Uploaded',
                    3 => 'Weblink Generated',
                    4 => 'Force Closed',
                    5 => 'Completed',
                ];
                $row['status'] = $status_map[$row['status']] ?? 'Unknown';

                // Format the date
                $row['upload_weblink_generated_on'] = date("d-m-Y h:i A", strtotime($row['upload_weblink_generated_on']));

                // Format and clean URLs
                $url = $row['upload_weblink'];
                $updatedUrl = str_replace(["https://mounarchtech.com/vocoxp/", "https://mounarchtech/vocoxp/"], "", $url);
                $row['upload_weblink'] = $updatedUrl;

                // Add derived fields
                $row['total_web_links'] = $total_count;
                $row['web_link_opened'] = $total_count_opens;
                $row['web_link_comp'] = $total_count_comp;
                $row['weblink_for'] = $weblink_for_arr[$row['excel_no']] ?? '';

                // Add row to data array
                $data[] = $row;
            }

            // Success response
            $res = ["error_code" => 100, "message" => "success", "data" => $data];
        } else {
            // No data found
            $res = ["error_code" => 101, "message" => "data not found"];
        }
    } else {
        // Missing agency_id
        $res = ["error_code" => 102, "message" => "agency_id is required"];
    }

    // Return JSON response
    echo json_encode($res);
    return;
}
?>
