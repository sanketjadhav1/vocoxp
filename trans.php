<?php
/* 
Name : json_for_transaction_history.php
Version of the Requirment Document  : 2.0.1
Purpose :-  This fetch API use to get data of Recharge Offer
Mode :- single mode
Developed By - Rishabh Shinde
*/
// error_reporting(E_ALL & ~E_DEPRECATED);
// ini_set('display_errors', 1);
include_once 'connection.php';

// Now you can use the connection class
$connection = connection::getInstance();
$mysqli = $connection->getConnection();
$connection1 = database::getInstance();
$mysqli1 = $connection1->getConnection();
apponoff($mysqli);
logout($_SERVER['HTTP_AUTH_KEY'], $mysqli);
$check_error = check_error($mysqli, $mysqli1, $_POST['agency_id'], $_POST['application_id'], $_POST['sort'], $_POST['specification_id']);

if ($check_error == 1) {
    $agency_id = $_POST['agency_id'];
    $application_id = $_POST['application_id'];
    $limit_start = $_POST['limit_start'];
    $ver_array = $_POST['specification_id'];
    $arr = explode(",", $ver_array);
   
    $sort1 = isset($_POST['sort']) ? explode(",", $_POST['sort']) : [];

    $count = 0;
    $where = "";
    $where1 = "";
    $where2 = "";
    $sort_where = "";
    $sort_where2 = "";

    
    if (in_array("date", $sort1) || strpos(implode(" ", $sort1), "date") !== false) {
        
        $dateRangeElement = null;
        foreach ($sort1 as $element) {
            if (strpos($element, "date") !== false) {
                $dateRangeElement = $element;
                break;
            }
        }

        if ($dateRangeElement) {
            $dates = explode("@", $dateRangeElement);

            
            if (count($dates) === 2) {
                $dateParts = explode("=", $dates[1]);

                if (count($dateParts) === 2) {
                    $start_date = date('Y-m-d', strtotime($dateParts[0]));
                    $end_date = date('Y-m-d', strtotime($dateParts[1]));

                    $where .= ' AND DATE(created_on) BETWEEN "' . $start_date . '" AND "' . $end_date . '"';
                    $where_direct .= ' AND DATE(completed_on) BETWEEN "' . $start_date . '" AND "' . $end_date . '"';
                    $where1 .= ' AND DATE(`date`) BETWEEN "' . $start_date . '" AND "' . $end_date . '"';

                    $where2 .= ' AND DATE(order_date) BETWEEN "' . $start_date . '" AND "' . $end_date . '"';
                } else {
                    
                    $singleDate = date('Y-m-d', strtotime($dates[1]));

                    $where .= ' AND DATE(completed_on) = "' . $singleDate . '"';
                    $where_direct .= ' AND DATE(created_on) = "' . $singleDate . '"';
                    $where1 .= ' AND DATE(`date`) = "' . $singleDate . '"';
                    $where2 .= ' AND DATE(order_date) = "' . $singleDate . '"';
                }
            }
        }
    }
    if (in_array("online_debit", $sort1) && in_array("wallet_debit", $sort1)) {
        $sort_where = "AND (payment_via='online' OR payment_via='wallet')";
        $sort_where2 = "AND (payment_mode='online' OR payment_via='wallet')";
    } else {
        if (in_array("online_debit", $sort1)) {
            $sort_where = "AND payment_via='online'";
            $sort_where2 = "AND payment_mode='online'";
        }
        if (in_array("wallet_debit", $sort1)) {
            $sort_where = "AND payment_via='wallet'";
            $sort_where2 = "AND payment_mode='wallet'";
        }
    }

    
    $data1 = [];





$fetch_payment = "SELECT `created_on` AS `date`, `net_amount` AS `amount`, `request_id`, `payment_via`, `invoice_url`, `person_id`  
                  FROM `verification_payment_transaction_all` 
                  WHERE `agency_id`='$agency_id' AND `application_id`='$application_id' $where $sort_where";

$res = mysqli_query($mysqli1, $fetch_payment);

$data1 = [];
$count = 0;

while ($row = mysqli_fetch_assoc($res)) {
    $flag = 0;
    $str = [];
    $totalSum = 0;
    
    $fetch_member = "SELECT `name` FROM `member_header_all` WHERE `member_id`='" . $row['person_id'] . "'";
    $res_mem = mysqli_query($mysqli, $fetch_member);
    $arr_mem = mysqli_fetch_assoc($res_mem);

    if (mysqli_num_rows($res_mem) == 0) {
        $sql = "SELECT `name` FROM `member_header_archive_all` WHERE `member_id`='" . $row['person_id'] . "'";
        $res1 = mysqli_query($mysqli, $sql);
        $newname = mysqli_fetch_assoc($res1);
        $arr_mem['name'] = $newname['name'];
    }

    // Assuming $arr is already defined and contains the necessary verification_ids
    foreach ($arr as $value) {
        $sql12 = "SELECT `verification_id`, `name`, `table_name`, `image`, `type_id`, `type`, `status`, `abbreviations` 
                  FROM `verification_header_all` WHERE `verification_id`='$value'";
        $res_sql12 = mysqli_query($mysqli1, $sql12);
        $row12 = mysqli_fetch_assoc($res_sql12);

        if ($row12) {
            $table_name = $row12['table_name'];
            $req = $row['request_id'];

            $fetch_table_tr = "SELECT `id`, `price` FROM $table_name WHERE `request_id`='$req'";
            $res_v = mysqli_query($mysqli1, $fetch_table_tr);

            if (mysqli_num_rows($res_v) > 0) {
                $row_d = mysqli_fetch_assoc($res_v);
                $str[] = $row12['abbreviations'];
                $flag = 1;
                $GST = ($row_d['price'] * 18) / 100;
                $totalSum += $row_d['price'] + $GST;
            }
        }
    }

    if ($flag == 1) {
        $v_str = implode(",", $str);
        $row['abbreviation'] = $v_str;
        $row['type'] = "Dr";
        $row['date'] = date("d-m-Y h:i A", strtotime($row['date']));
        $row['date1'] = date("Y-m-d H:i:s", strtotime($row['date']));
        $row['net_amount'] = number_format($totalSum, 2);
        $row['purpose'] = '';
        $row['name'] = $arr_mem['name'];
        $data1[] = $row;
        $count++;
    }
}

echo $fetch_payment_direct = "SELECT `completed_on` AS `date`, (`deducted_base_amount` + `sgst_amount` + `cgst_amount`) AS `amount`, `direct_id`, `report_url`, `verification_id`, `linked_table` 
                         FROM `direct_verification_details_all` 
                         WHERE `agency_id`='$agency_id' AND `application_id`='$application_id' AND `activity_status`=2 $where_direct";

$res = mysqli_query($mysqli1, $fetch_payment_direct);

while ($row_direct = mysqli_fetch_assoc($res)) {
    $verification_id = $row_direct['verification_id'];

    $sql12 = "SELECT `verification_id`, `name`, `table_name`, `image`, `type_id`, `type`, `status`, `abbreviations` 
              FROM `verification_header_all` 
              WHERE `verification_id`='$verification_id'";
    $res_sql12 = mysqli_query($mysqli1, $sql12);
    $row12 = mysqli_fetch_assoc($res_sql12);

    if ($row12) {
        $table_name = $row_direct['linked_table'];
        $req = $row_direct['direct_id'];

        $fetch_table_tr = "SELECT `id`, `name` FROM $table_name WHERE `direct_id`='$req'";
        $res_v = mysqli_query($mysqli1, $fetch_table_tr);

        if (mysqli_num_rows($res_v) > 0) {
            $row_d = mysqli_fetch_assoc($res_v);

            // Initialize or reset variables for each row
            $str = [];
            $flag = 0;
            $totalSum = 0;

            $str[] = $row12['abbreviations'];
            $flag = 1;
            // $GST = ($row_direct['price'] * 18) / 100;
            $totalSum += $row_direct['amount'] + $GST;

            if ($flag == 1) {
                $v_str = implode(",", $str);
                $row_direct['abbreviation'] = $v_str;
                $row_direct['type'] = "Dr";
                $row_direct['date'] = date("d-m-Y h:i A", strtotime($row_direct['date']));
                $row_direct['date1'] = date("Y-m-d H:i:s", strtotime($row_direct['date']));
                $row_direct['net_amount'] = number_format($totalSum, 2);
                $row_direct['purpose'] = '';
                $row_direct['name'] = $row_d['name'];
                $data1[] = $row_direct;
                $count++;
            }
        }
    }
}

if (in_array("wallet", $sort1)) {
    $sql1 = "SELECT `date`, `amount` AS `amount1`, `offer_add_on_amount` 
             FROM `wallet_transaction_all` 
             WHERE `agency_id`='$agency_id' 
             AND `status` <> 'failed' 
             AND (`purpose`='wallet recharge' OR `purpose`='cancelled order' OR `purpose`='return item') $where1";

    $res1 = mysqli_query($mysqli, $sql1);
    $n1 = mysqli_num_rows($res1);
    $count += $n1;

    while ($row1 = mysqli_fetch_assoc($res1)) {
        $amount = $row1['amount1'] + $row1['offer_add_on_amount'];
        $row1['request_id'] = "";
        $row1['name'] = "Wallet Recharge";
        $row1['abbreviation'] = "";
        $row1['payment_via'] = "";
        $row1['type'] = "Cr";
        $row1['date'] = date("d-m-Y h:i A", strtotime($row1['date']));
        $row1['date1'] = date("Y-m-d H:i:s", strtotime($row1['date']));
        $row1['purpose'] = '';
        $row1['amount'] = $amount;
        $data1[] = $row1;
    }
}

usort($data1, function($a, $b) {
    return strtotime($b['date1']) - strtotime($a['date1']);
});

$data['request_data'] = $data1;

if (!empty($data1)) {
    $response[] = [
        "error_code" => 100, 
        "message" => "data fetch successfully", 
        "data" => $data1, 
        "url" => isset($row['invoice_url']) ? $row['invoice_url'] : ''
    ];
    echo json_encode($response);
} else {
    $response[] = ["error_code" => 100, "message" => "data not found"];
    echo json_encode($response);
}


}


function replaceNullWithEmptyString($value)
{
    return is_null($value) ? '' : $value;
}
function check_error($mysqli, $mysqli1, $agency_id, $application_id, $sort, $specification_id)
{

    $check_error = 1;
    if (!$mysqli) {
        $response[] = ["error_code" => 103, "message" => "Unable to proceed your request, please try again after some time"];
        echo json_encode($response);
        return;
    }
    if ($_SERVER["REQUEST_METHOD"] != "POST") {
        $responce[] = array("error_code" => 106, "message" => "please change request method to POST");
        echo json_encode($responce);
        return;
    }
    if (!isset($agency_id)) {
        $response[] = ["error_code" => 104, "message" => "Please the parameter - agency_id"];
        echo json_encode($response);
        return;
    } else {
        if ($agency_id == "") {
            $response[] = ["error_code" => 105, "message" => "Value of 'agency_id' cannot be empty"];
            echo json_encode($response);
            return;
        }
    }
    if (!isset($application_id)) {
        $response[] = ["error_code" => 107, "message" => "Please the parameter - application_id"];
        echo json_encode($response);
        return;
    } else {
        if ($application_id == "") {
            $response[] = ["error_code" => 108, "message" => "Value of 'application_id' cannot be empty"];
            echo json_encode($response);
            return;
        }
    }
    if (!isset($specification_id)) {
        $response[] = ["error_code" => 109, "message" => "Please the parameter - specification_id"];
        echo json_encode($response);
        return;
    } else {
        if ($specification_id == "") {
            $response[] = ["error_code" => 110, "message" => "Value of 'specification_id' cannot be empty"];
            echo json_encode($response);
            return;
        }
    }

    return $check_error;
}
