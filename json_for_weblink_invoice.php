<?php
error_reporting(E_ALL & ~E_DEPRECATED);
ini_set('display_errors', 1);

include __DIR__ . '/vendor/autoload.php';
include_once 'connection.php';

header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');

$connection = connection::getInstance();
$mysqli = $connection->getConnection();

$connection1 = database::getInstance();
$mysqli1 = $connection1->getConnection();

$agency_id = $_GET['agency_id'] ?? "";
$request_no = $_GET['request_no'] ?? "";
$bulk_id = $_GET['bulk_id'] ?? "";

// Input and connection validation
$check_error_res = check_error($mysqli, $mysqli1, $agency_id, $request_no, $bulk_id);
if ($check_error_res === 1) {
    $bulk_req_query = "SELECT * FROM `bulk_weblink_request_all` WHERE `bulk_id` = '$bulk_id' AND agency_id='$agency_id' AND request_no='$request_no'";
    $weblink_req_fetch_res = $mysqli->query($bulk_req_query);

    if ($weblink_req_fetch_res && $weblink_req_fetch_res->num_rows > 0) {
        $weblink_req_fetch_array = $weblink_req_fetch_res->fetch_assoc();
        $data = []; // Array to hold the response data 

$duplicate = "SELECT 
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
        // $user_duplicate = $mysqli->query($duplicate);

        $user_query = "SELECT * FROM `bulk_end_user_transaction_all` WHERE `bulk_id` = '$bulk_id' AND `agency_id` = '$agency_id' AND `name` != '' GROUP BY CASE WHEN `email_id` = '' THEN `id` ELSE `email_id` END";
        $user_fetch_res = $mysqli->query($duplicate);

        if ($user_fetch_res && $user_fetch_res->num_rows > 0) {
            while ($user_fetch_array = $user_fetch_res->fetch_assoc()) {
                $verificationss = $user_fetch_array["scheduled_verifications"];
                $invoice_data = [];
                $document_types = [];

                // Fetch payment details
                $enduser_id_value = $user_fetch_array["end_user_id"];
                $pay_query = "SELECT * FROM `end_user_payment_transaction_all` WHERE FIND_IN_SET('$enduser_id_value', REPLACE(`end_user_id`, ' ', '')) > 0 AND `bulk_id` = '".$weblink_req_fetch_array["bulk_id"]."'";
                $pay_fetch_res = $mysqli->query($pay_query);

                if ($pay_fetch_res && $pay_fetch_res->num_rows > 0) {
                    while ($pay_fetch_array = $pay_fetch_res->fetch_assoc()) {
                        $invoice_id = $pay_fetch_array["paid_transaction_id"];
                        $invoice_url = !empty($pay_fetch_array["invoice_url"]) ? $pay_fetch_array["invoice_url"] : "";

                        $invoice_data[] = [
                            "id" => $invoice_id,
                            "url" => $invoice_url
                        ];
                    }
                } else {
                    // No invoices found, include empty structure
                    $invoice_data[] = [
                        "id" => "",
                        "url" => ""
                    ];
                }

                // Fetch verification names
                $verificationss1 = explode(',', $verificationss);
                if (!empty($verificationss1)) {
                    foreach ($verificationss1 as $verify_id) {
                        $verification_query = "SELECT * FROM `verification_header_all` WHERE `verification_id` = '".$verify_id."'";
                        $verification_query_result = $mysqli1->query($verification_query);

                        $document_name = "";
                        if ($verification_query_result && $verification_query_row = $verification_query_result->fetch_assoc()) {
                            $document_name = $verification_query_row["name"];
                        }

                        $document_types[] = [
                            "doc_name" => $document_name
                        ];
                    }
                } else {
                    // No document types found, include empty structure
                    $document_types[] = [
                        "doc_name" => ""
                    ];
                }

                // Compile user data
                $user_data = [
                    "end_user_id" => $user_fetch_array["end_user_id"],
                    "type" => $user_fetch_array["obj_name"],
                    "name" => $user_fetch_array["name"],
                    "mobile" => $user_fetch_array["mobile"],
                    "email_id" => $user_fetch_array["email_id"],
                    "invoice_id" => array_map(fn($invoice) => ["id" => $invoice["id"]], $invoice_data),
                    "invoice_url" => array_map(fn($invoice) => ["url" => $invoice["url"]], $invoice_data),
                    "document_type" => $document_types,
                    "payment_type" => $user_fetch_array["payment_from"] == 1 ? "Wallet" : "Online",
                ];

                $data[] = $user_data;
            }

            // Successful response
            echo json_encode([
                "error_code" => 100,
                "message" => "End users successfully fetched.",
                "data" => $data,
            ]);
        } else {
            echo json_encode([
                "error_code" => 101,
                "message" => "No end user transactions found.",
                "data" => []
            ]);
        }
    } else {
        echo json_encode([
            "error_code" => 102,
            "message" => "Bulk request not found.",
            "data" => []
        ]);
    }
    exit;
}

function check_error($mysqli, $mysqli1, $agency_id, $request_no, $bulk_id) {
    if (!$mysqli || !$mysqli1) {
        echo json_encode(["error_code" => 101, "message" => "Database connection failed."]);
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] != "GET") {
        echo json_encode(["error_code" => 102, "message" => "Invalid request method. Only GET is allowed."]);
        exit;
    }

    if (empty($agency_id) || empty($request_no) || empty($bulk_id)) {
        echo json_encode(["error_code" => 106, "message" => "Missing required parameters."]);
        exit;
    }

    return 1;
}
?>
