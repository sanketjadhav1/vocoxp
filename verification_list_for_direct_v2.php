<?php
include 'connection.php';

function getConnection($instanceClass)
{
    $connection = $instanceClass::getInstance();
    return $connection->getConnection();
}

$mysqli = getConnection('connection');
$mysqli1 = getConnection('database');

if (!check_error($mysqli, $mysqli1, $_POST['agency_id'], $_POST['application_id'], $_POST['mode'])) {
    return;
}

$agency_id = $_POST['agency_id'];
$application_id = $_POST['application_id'];
$mode = $_POST['mode']; // 'weblink' or 'direct'

// Fetch agency details
$fetch_agency = "SELECT `agency_id`, `current_wallet_bal` FROM `agency_header_all` WHERE `agency_id` = ?";
$stmt_agency = $mysqli->prepare($fetch_agency);
$stmt_agency->bind_param("s", $agency_id);
$stmt_agency->execute();
$res_agency = $stmt_agency->get_result();
$arr_agency = $res_agency->fetch_assoc();

if ($res_agency->num_rows == 0) {
    send_response(107, "agency_id is not valid. Please enter a correct agency_id.");
    return;
}

// Fetch application details
$fetch_application = "SELECT `application_id` FROM `application_header_all` WHERE `application_name` = 'VOCOxP'";
$res_application = $mysqli1->query($fetch_application);

if ($res_application->num_rows == 0) {
    send_response(108, "application_id is not valid. Please enter a correct application_id.");
    return;
}

// Fetch verification details based on mode
$ver_type = ($mode === "weblink") ? 3 : 1;
$fetch_verification = "
    SELECT 
        vc.`verification_id`, vc.`rate`, vc.`sgst_percentage`, vc.`cgst_percentage`, 
        vh.`name`, vh.`image`, vh.`status`
    FROM 
        `verification_configuration_all` vc
    INNER JOIN 
        `verification_header_all` vh ON vh.`verification_id` = vc.`verification_id`
    WHERE 
        vc.`operational_status` = 1 AND vh.`status` = 1 AND vc.`ver_type` = ?";
$stmt_verification = $mysqli1->prepare($fetch_verification);
$stmt_verification->bind_param("i", $ver_type);
$stmt_verification->execute();
$res_verification = $stmt_verification->get_result();

$data = [];
while ($arr = $res_verification->fetch_assoc()) {
    $data[] = [
        "verification_name" => $arr['name'],
        "image" => $arr['image'],
        "status" => (string)$arr['status'],
        "verification_id" => $arr['verification_id'],
        "price" => (string)$arr['rate'],
        "sgst_per" => (string)$arr['sgst_percentage'],
        "cgst_per" => (string)$arr['cgst_percentage']
    ];
}

// Adding 'Individual Weblink' as a default option
$data[] = [
    "verification_name" => 'Individual Weblink',
    "image" => 'https://mounarchtech.com/vocoxp/web_link_logo.png',
    "status" => '1',
    "verification_id" => 'DVF-00009',
    "price" => '20',
    "sgst_per" => '9',
    "cgst_per" => '9'
];

$number = $arr_agency['current_wallet_bal'];
$rounded = number_format($number, 2, '.', '');

send_response(100, "Data fetched", [
    "data" => $data,
    "current_wallet_bal" => $rounded
]);

function send_response($error_code, $message, $additional_data = [])
{
    $response = array_merge([
        "error_code" => $error_code,
        "message" => $message
    ], $additional_data);
    echo json_encode([$response]);
}

function check_error($mysqli, $mysqli1, $agency_id, $application_id, $mode)
{
    if (!$mysqli || !$mysqli1) {
        send_response(101, "Unable to proceed with your request, please try again later");
        return false;
    }

    if ($_SERVER['REQUEST_METHOD'] !== "POST") {
        send_response(102, "Request Method is not POST");
        return false;
    }

    if (!validate_parameter($agency_id, "agency_id") || !validate_parameter($application_id, "application_id") || !validate_parameter($mode, "mode")) {
        return false;
    }

    return true;
}

function validate_parameter($param, $name)
{
    if (!isset($param)) {
        send_response(103, "Please pass the parameter of $name");
        return false;
    }
    if (empty($param)) {
        send_response(104, "$name cannot be empty");
        return false;
    }
    return true;
}
