<?php
include_once 'connection.php';
// error_reporting(E_ALL & ~E_DEPRECATED);
// ini_set('display_errors', 1);
$connection = connection::getInstance();
$mysqli = $connection->getConnection();
$connection1 = database::getInstance();
$mysqli1 = $connection1->getConnection();
apponoff($mysqli);
logout($_SERVER['HTTP_AUTH_KEY'], $mysqli);
$check_error = check_error($mysqli, $_POST['agency_id']);

if ($check_error == 1) {
    $agency_id = $_POST['agency_id'];
$sort_date = $_POST['sort_date'];
$sort_by = $_POST['sort_by'];
$payment_status = $_POST['payment_status'];

$where = "";
$where1 = "";

if ($sort_date != "") {
    $explode_date = explode("@", $sort_date);
    $from_date = date("Y-m-d", strtotime($explode_date[0]));
    $to_date = date("Y-m-d", strtotime($explode_date[1]));
    $where = "AND DATE(`transaction_on`) BETWEEN '$from_date' AND '$to_date'";
    $where1 = "AND DATE(`transaction_date`) BETWEEN '$from_date' AND '$to_date'";
}

// Validate agency ID and status
$stmt = $mysqli->prepare("SELECT `agency_id`, `status`, `current_wallet_bal` FROM `agency_header_all` WHERE `agency_id` = ?");
$stmt->bind_param("s", $agency_id);
$stmt->execute();
$result_agency = $stmt->get_result();
$arr = $result_agency->fetch_assoc();

if (!$arr) {
    $response[] = array("error_code" => 101, "message" => "Invalid agency ID. Please provide a valid agency ID");
    echo json_encode($response);
    return;
} elseif ($arr['status'] != "1") {
    $response[] = array("error_code" => 102, "message" => "The agency name is currently inactive");
    echo json_encode($response);
    return;
}

$fetch_factory="SELECT `first_offer_image`, `first_offer_amount`, `second_offer_amount`, `second_offer_image` FROM `factory_setting_header_all`";
$result_factory = $mysqli->query($fetch_factory);
$factory = $result_factory->fetch_assoc();

$all_debit = [];
$all_credit = [];
$failed_transactions = [];

// Fetch debit transactions
if($payment_status==1 || $payment_status==""){
$fetch_data_debit = "SELECT `user_id`, `base_amount`, `purchase_type`, `cgst_amount`, `sgst_amount`, `transaction_on`, `verification_id`, 
                            (`base_amount` + `sgst_amount` + `cgst_amount`) AS amount 
                     FROM `wallet_payment_transaction_all` 
                     WHERE `agency_id` = ? $where";
$stmt_debit = $mysqli->prepare($fetch_data_debit);
$stmt_debit->bind_param("s", $agency_id);
$stmt_debit->execute();
$res_debit = $stmt_debit->get_result();

while ($arr_debit = $res_debit->fetch_assoc()) {
    $arr_debit['purpose'] = ($arr_debit['purchase_type'] == 1) ? "verification" : "smart Watch";
    $arr_debit['type'] = "dr";
    $arr_debit['status'] = "success";
    $all_debit[] = $arr_debit;
}

// Fetch credit transactions
$fetch_credit = "SELECT `initial_wallet_balance`, `added_amount` AS `amount`, `final_blnce`, `payment_gateway_id`, `transaction_date` AS `transaction_on` 
                 FROM `wallet_recharge_transaction_all` 
                 WHERE `agency_id` = ? $where1";
$stmt_credit = $mysqli->prepare($fetch_credit);
$stmt_credit->bind_param("s", $agency_id);
$stmt_credit->execute();
$res_credit = $stmt_credit->get_result();

while ($arr_credit = $res_credit->fetch_assoc()) {
    $arr_credit['purpose'] = "wallet recharge";
    $arr_credit['type'] = "cr";
    $arr_credit['status'] = "success";
    $all_credit[] = $arr_credit;
}
}elseif($payment_status==0 || $payment_status==""){
// Fetch failed transactions
$fetch_failed = "SELECT `initial_wallet_balance`, `added_amount` AS `amount`, `final_blnce`, `payment_gateway_id`, `transaction_date` AS `transaction_on` 
                 FROM `wallet_recharge_failed_transaction_all` 
                 WHERE `agency_id` = ? $where1";
$stmt_failed = $mysqli->prepare($fetch_failed);
$stmt_failed->bind_param("s", $agency_id);
$stmt_failed->execute();
$res_failed = $stmt_failed->get_result();

while ($arr_failed = $res_failed->fetch_assoc()) {
    $arr_failed['purpose'] = "wallet recharge";
    $arr_failed['type'] = "cr";
    $arr_failed['status'] = "failed";
    $failed_transactions[] = $arr_failed;
}
}
// Merge successful and failed credit transactions
$all_credit = array_merge($all_credit, $failed_transactions);

// Merge and sort records based on sort_by
if ($sort_by == "all") {
    $all_record = array_merge($all_debit, $all_credit);
} elseif ($sort_by == "recharge") {
    $all_record = $all_credit;
} else {
    $all_record = $all_debit;
}

// Check if payment_status is posted




// Initialize transaction array and daily balance calculations
$transaction_array = [];
$daily_expenses = [];
$daily_credits = [];
$running_balance = 0;

foreach ($all_record as $wallet) {
    $arr_name = [];
    $arr_veri_name = [];

    if (isset($wallet['user_id'])) {
        $user_id = $wallet['user_id'];
        $user_pre = explode("-", $wallet['user_id']);
        $verification_id = $wallet['verification_id'];

        $stmt_veri_name = $mysqli1->prepare("SELECT `name` FROM `verification_header_all` WHERE `verification_id` = ?");
        $stmt_veri_name->bind_param("s", $verification_id);
        $stmt_veri_name->execute();
        $res_name_veri = $stmt_veri_name->get_result();
        $arr_veri_name = $res_name_veri->fetch_assoc();

        if ($user_pre[0] == "MEM") {
            $stmt_member = $mysqli->prepare("SELECT `name` FROM `member_header_all` WHERE `member_id` = ? AND `registration_id` = ?");
            $stmt_member->bind_param("ss", $user_id, $agency_id);
            $stmt_member->execute();
            $res_name = $stmt_member->get_result();
            $arr_name = $res_name->fetch_assoc();
        } else {
            $stmt_direct_id = $mysqli->prepare("SELECT `linked_table` FROM `direct_verification_details_all` WHERE `direct_id` = ?");
            $stmt_direct_id->bind_param("s", $user_id);
            $stmt_direct_id->execute();
            $res_table = $stmt_direct_id->get_result();

            if ($res_table) {
                while ($arr_table = $res_table->fetch_assoc()) {
                    $table_name = $arr_table['linked_table'];
                    $stmt_member = $mysqli->prepare("SELECT `name` FROM $table_name WHERE `direct_id` = ?");
                    $stmt_member->bind_param("s", $user_id);
                    $stmt_member->execute();
                    $res_name = $stmt_member->get_result();
                    $arr_name = $res_name->fetch_assoc();
                }
            }
        }
    }

    $time = date('h:i A', strtotime($wallet['transaction_on']));
    $date_time = date('d-m-Y h:i A', strtotime($wallet['transaction_on']));
    $date = date('d-m-Y', strtotime($wallet['transaction_on']));
    $new_amount = $wallet['amount'];
    
    // Calculate daily expenses and credits
    if ($wallet['type'] == "dr") {
        if (!isset($daily_expenses[$date])) {
            $daily_expenses[$date] = 0;
        }
        $daily_expenses[$date] += $new_amount;
    } else {
        if (!isset($daily_credits[$date])) {
            $daily_credits[$date] = 0;
        }
        $daily_credits[$date] += $new_amount;
    }
$tax_amt=$wallet['cgst_amount']+$wallet['sgst_amount'];
    $transaction_array[$date][] = [
        "transaction_id" => $wallet['payment_gateway_id'] ?? "",
        "purpose" => $wallet['purpose'],
        "type" => $wallet['type'],
        "base_amount" => format_indian_currency($wallet['base_amount']),
        "tax_amount" => format_indian_currency($tax_amt),
        "amount" => format_indian_currency($new_amount),
        "date" => $date_time,
        "name" => $arr_name['name'] ?? "",
        "status" => $wallet['status'],
        "member_id" => $wallet['user_id'] ?? "",
        "time" => $time,
        "verification_name" => $arr_veri_name['name'] ?? ""
    ];
}

// Custom sort function
function customDateSort($a, $b) {
    $aYear = date('Y', strtotime($a));
    $bYear = date('Y', strtotime($b));

    if ($aYear != $bYear) {
        return $bYear - $aYear;
    } else {
        $aMonth = date('n', strtotime($a));
        $bMonth = date('n', strtotime($b));

        if ($aMonth != $bMonth) {
            return $bMonth - $aMonth;
        } else {
            return strtotime($b) - strtotime($a);
        }
    }
}

uksort($transaction_array, 'customDateSort');

$data = [];
foreach ($transaction_array as $date => $transactions) {
    if (!empty($transactions)) {
        usort($transactions, function ($a, $b) {
            return strtotime($b['date']) - strtotime($a['date']);
        });

        $daily_expense = $daily_expenses[$date] ?? 0;
        $daily_credit = $daily_credits[$date] ?? 0;
        $running_balance += ($daily_credit - $daily_expense);

        $data[] = [
            "date" => $date,
            "transactions" => $transactions,
            "total_expenses" => format_indian_currency($daily_expense),
            "remaining_balance" => format_indian_currency($arr['current_wallet_bal'])
        ];
    }
}
$offer = [
    [
        "offer_image" => "https://mounarchtech.com/vocoxp/offer1.jpeg",
        "offer_amount" => $factory['first_offer_amount']
    ],
    [
        "offer_image" => "https://mounarchtech.com/vocoxp/offer2.jpeg",
        "offer_amount" => $factory['second_offer_amount']
    ]
];

if (!empty($data)) {
    $member_data[] = [
        "error_code" => 100,
        "data" => $data,
        "offer_data"=>$offer
    ];
} else {
    $member_data[] = [
        "error_code" => 100,
        "message" => "Data not found",
        "current_wallet_bal" => format_indian_currency($arr['current_wallet_bal']),
        "offer_data"=>$offer
    ];
}

echo json_encode($member_data);
return;
    
    
}

function check_error($mysqli, $agency_id) {
    $check_error = 1;
    if (!$mysqli) {
        $response[] = ["error" => 103, "message" => "Unable to proceed your request, please try again after some time"];
        echo json_encode($response);
        return;
    }
    if ($_SERVER["REQUEST_METHOD"] != "POST") {
        $response[] = ["error_code" => 106, "message" => "please change request method to POST"];
        echo json_encode($response);
        return;
    }
    if (!isset($agency_id)) {
        $response[] = ["error" => 104, "message" => "Please provide the parameter - agency_id"];
        echo json_encode($response);
        return;
    } else {
        if ($agency_id == "") {
            $response[] = ["error" => 105, "message" => "Value of 'agency_id' cannot be empty"];
            echo json_encode($response);
            return;
        }
    }
    return $check_error;
}

function format_indian_currency($number) {
    $number = number_format($number, 2, '.', '');
    $number_parts = explode('.', $number);
    $integer_part = $number_parts[0];
    $decimal_part = isset($number_parts[1]) ? '.' . $number_parts[1] : '.00';

    $last_three_digits = substr($integer_part, -3);
    $remaining_digits = substr($integer_part, 0, -3);
    if ($remaining_digits != '') {
        $last_three_digits = ',' . $last_three_digits;
    }

    $formatted_number = preg_replace('/\B(?=(\d{2})+(?!\d))/', ',', $remaining_digits) . $last_three_digits . $decimal_part;
    return $formatted_number;
}
function replaceNullWithEmptyString($value) {
    return is_null($value) ? '' : $value;
}
?>
