<?php

header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');
date_default_timezone_set('Asia/Kolkata');

include 'connection.php';
$connection = connection::getInstance();
$mysqli = $connection->getConnection();

if ($_SERVER['REQUEST_METHOD'] != "POST") {
    $response[] = array("error_code" => 102, "message" => "Request Method Is Not POST");
    echo json_encode($response);
    exit;
}
// $visitor_location_id  $location_name $location_name
$agency_id = $_POST['agency_id'];
$visitor_location_id = isset($_POST['visitor_location_id']) ? $_POST['visitor_location_id']:'';
$location_name = isset($_POST['location_name']) ? $_POST['location_name'] : '';
$operational_from = isset($_POST['operational_from']) ? $_POST['operational_from'] : '';
$location_admins = isset($_POST['location_admins']) ? $_POST['location_admins'] : '';
$location_state = isset($_POST['location_state']) ? $_POST['location_state'] : '';
$location_city = isset($_POST['location_city']) ? $_POST['location_city'] : '';
$location_pincode = isset($_POST['location_pincode']) ? $_POST['location_pincode'] : '';
$location_radius = isset($_POST['location_radius']) ? $_POST['location_radius'] : '';
$location_coordinates = isset($_POST['location_coordinates']) ? $_POST['location_coordinates'] : '';
$type = isset($_POST['type']) ? $_POST['type'] : '';
$visiting_hours = isset($_POST['visiting_hours']) ? $_POST['visiting_hours'] : '';
$weekoffs = isset($_POST['weekoffs']) ? $_POST['weekoffs'] : '';
$mode = isset($_POST['mode']) ? $_POST['mode'] : '';
if (!empty($operational_from)) {
    // Since you're receiving 'YYYY/DD/MM', you need to convert this format
    $date = DateTime::createFromFormat('d/m/Y', $operational_from); // Adjust the format here
    if ($date) {
        // Now convert it to MySQL acceptable format
        $operational_from = $date->format('Y-m-d'); // Format to MySQL's 'YYYY-MM-DD'
    } else {
        // Handle the case where the format is invalid
        $operational_from = null;
    }
} else {
    // If the date is empty, set it to null so MySQL can use the default value
    $operational_from = null;
}

$check_error = check_error($agency_id, $mode, $visitor_location_id, $location_name, $operational_from,  $location_state, $location_city, $location_pincode, $location_radius, $location_coordinates, $type);
if ($check_error == 1) {
    $check_agncy_id_query = "SELECT `agency_id` FROM `agency_header_all` WHERE `agency_id` = '$agency_id'";
    $check_agncy_id_res = $mysqli->query($check_agncy_id_query);
    $check_agncy_id_row = $check_agncy_id_res->num_rows;

    if ($check_agncy_id_row != 1) {
        $response[] = array("error_code" => 124, "message" => "Invalid 'agency_id'");
        echo json_encode($response);
        exit;
    }
    if ($mode == "ADD") {

        $visitor_location_id = unique_id_generate_bulk('VIS', 'agency_visitor_location_header_all', $mysqli, 'visitor_location_id');
        // Start the transaction
        $mysqli->begin_transaction();

        try {
            // First INSERT query
            $insert_query = "INSERT INTO agency_visitor_location_header_all 
        (agency_id, visitor_location_id, location_name, operational_from, location_admins, location_state, location_city, location_pincode, location_radius, location_coordinates,status)
        VALUES
        ('$agency_id','$visitor_location_id', '$location_name', '$operational_from', '$location_admins', '$location_state', '$location_city', '$location_pincode','$location_radius','$location_coordinates',1)";
            $sqlquery = $mysqli->query($insert_query);

            if (!$sqlquery) {
                throw new Exception("Failed to insert into agency_visitor_location_header_all");
            }
            $insert_query2 = "INSERT INTO visitor_location_setting_details_all
        (agency_id, visitor_location_id,type, visiting_hours, weekoffs)
        VALUES ('$agency_id','$visitor_location_id','$type','$visiting_hours','$weekoffs')";
            $sqlquery1 = $mysqli->query($insert_query2);

            if (!$sqlquery1) {
                throw new Exception("Failed to insert into visitor_location_setting_details_all");
            }
            $mysqli->commit();
            $response[] = array("error_code" => 100, "message" => "Records Inserted successfully");
        } catch (Exception $e) {
            // Rollback the transaction if any query fails
            $mysqli->rollback();
            $response[] = array("error_code" => 103, "message" => $e->getMessage());
        }
        echo json_encode($response);
    } else if ($mode == "update_mode") {
        $check_visitior_location_id = "SELECT `visitor_location_id` FROM `agency_visitor_location_header_all` WHERE `visitor_location_id` = '$visitor_location_id'";
        $check_agncy_id_res = $mysqli->query($check_visitior_location_id);
        $check_agncy_id_row = $check_agncy_id_res->num_rows;

        if ($check_agncy_id_row != 1) {
            $response[] = array("error_code" => 124, "message" => "Invalid visitor_location_id");
            echo json_encode($response);
            exit;
        }
        $mysqli->begin_transaction();
        try {
            $updateQuery = "UPDATE `agency_visitor_location_header_all` SET  
        agency_id = '$agency_id', location_name = '$location_name', operational_from = '$operational_from', location_admins = '$location_admins', location_state = '$location_state', location_city = '$location_city', location_pincode = '$location_pincode', location_radius = '$location_radius', location_coordinates = '$location_coordinates'
        WHERE `visitor_location_id` = '$visitor_location_id' ";
            $updatesqlQuery =  $mysqli->query($updateQuery);
            if (!$updatesqlQuery) {
                throw new Exception("Failed to Update into agency_visitor_location_header_all");
            }
            $updateQuery2 = "UPDATE `visitor_location_setting_details_all` SET agency_id='$agency_id',type='$type',visiting_hours='$visiting_hours',weekoffs='$weekoffs' WHERE `visitor_location_id` = '$visitor_location_id' ";
            $updatesqlQuery2 = $mysqli->query($updateQuery2);
            if (!$updatesqlQuery2) {
                throw new Exception("Failed to Update into visitor_location_setting_details_all");
            }
            $mysqli->commit();
            $response[] = array("error_code" => 100, "message" => "Records Updated successfully");
        } catch (Exception $e) {
            $mysqli->rollback();
            $response[] = array("error_code" => 103, "message" => $e->getMessage());
        }
        echo json_encode($response);
    }else if($mode == "delete_mode"){
        $deleteQuery = "UPDATE agency_visitor_location_header_all SET status = 2 where   agency_id = '$agency_id' AND
    visitor_location_id = '$visitor_location_id'";
    $deleteQuery = $mysqli->query($deleteQuery);
    if($deleteQuery){
        $response[] = array("error_code"=>100, "message"=>"record deleted successfully");
        echo json_encode($response);
    }
    }

}

function check_error($agency_id, $mode, $visitor_location_id, $location_name, $operational_from,  $location_state, $location_city, $location_pincode, $location_radius, $location_coordinates, $type)
{
    if ($_SERVER["REQUEST_METHOD"] != "POST") {
        $response[] = array("error_code" => 103, "message" => "Please change the request method to POST");
        echo json_encode($response);
        exit;
    }

    if (empty($agency_id)) {
        $response[] = array("error_code" => 103, "message" => "The parameter 'agency_id' is required and cannot be empty");
        echo json_encode($response);
        exit;
    }

    if (empty($mode)) {
        $response[] = array("error_code" => 103, "message" => "The parameter 'mode' is required and cannot be empty");
        echo json_encode($response);
        exit;
    }
    if (empty($location_name)) {
        $response[] = array("error_code" => 103, "message" => "The parameter 'location_name' is required and cannot be empty");
        echo json_encode($response);
        exit;
    }
    if (empty($operational_from)) {
        $response[] = array("error_code" => 103, "message" => "The parameter 'operational_from' is required and cannot be empty");
        echo json_encode($response);
        exit;
    }
   
   

    // if (empty($location_admins)) {
    //     $response[] = array("error_code" => 103, "message" => "The parameter 'location_admins' is required and cannot be empty");
    //     echo json_encode($response);
    //     exit;
    // }

    if (empty($location_state)) {
        $response[] = array("error_code" => 103, "message" => "The parameter 'location_state' is required and cannot be empty");
        echo json_encode($response);
        exit;
    }

    if (empty($location_city)) {
        $response[] = array("error_code" => 103, "message" => "The parameter 'location_city' is required and cannot be empty");
        echo json_encode($response);
        exit;
    }

    if (empty($location_pincode)) {
        $response[] = array("error_code" => 103, "message" => "The parameter 'location_pincode' is required and cannot be empty");
        echo json_encode($response);
        exit;
    } elseif (!is_numeric($location_pincode)) {
        $response[] = array("error_code" => 104, "message" => "The parameter 'location_pincode' must be a numeric value");
        echo json_encode($response);
        exit;
    }
    if (empty($location_radius)) {
        $response[] = array("error_code" => 103, "message" => "The parameter 'location_radius' is required and cannot be empty");
        echo json_encode($response);
        exit;
    } elseif (!is_numeric($location_radius)) {
        $response[] = array("error_code" => 104, "message" => "The parameter 'location_radius' must be a numeric value");
        echo json_encode($response);
        exit;
    }


    if (empty($location_coordinates)) {
        $response[] = array("error_code" => 103, "message" => "The parameter 'location_coordinates' is required and cannot be empty");
        echo json_encode($response);
        exit;
    }

    if (empty($type)) {
        $response[] = array("error_code" => 103, "message" => "The parameter 'type' is required and cannot be empty");
        echo json_encode($response);
        exit;
    } elseif (!is_numeric($type) || !in_array($type, [1, 2, 3])) {
        $response[] = array("error_code" => 104, "message" => "The parameter 'type' must be a numeric value and one of the allowed values: 1, 2, or 3");
        echo json_encode($response);
        exit;
    }

    // if (empty($visiting_hours)) {
    //     $response[] = array("error_code" => 103, "message" => "The parameter 'visiting_hours' is required and cannot be empty");
    //     echo json_encode($response);
    //     exit;
    // }

    // if (empty($weekoffs)) {
    //     $response[] = array("error_code" => 103, "message" => "The parameter 'weekoffs' is required and cannot be empty");
    //     echo json_encode($response);
    //     exit;
    // }


    return 1;
}



function unique_id_generate_bulk($id_prefix, $table_name, $mysqli, $id_for)
{
    date_default_timezone_set('Asia/Kolkata');
    $system_date_time = date("Y-m-d H:i:s");

    $unique_header_query = "SELECT `prefix`, `last_id`, `id_for` FROM `unique_id_header_all` WHERE `table_name`='$table_name' AND `id_for`='$id_for'";
    $unique_header_res = $mysqli->query($unique_header_query);
    $unique_header_arr = $unique_header_res->fetch_assoc();

    $last_id = $unique_header_arr['last_id'];
    // $id_for = $unique_header_arr['id_for'];

    if (empty($unique_header_arr)) {
        $initial_id = $id_prefix . '-' . str_pad(1, 5, '0', STR_PAD_LEFT);
        $insert_query = "INSERT INTO `unique_id_header_all` (`table_name`, `id_for`, `prefix`, `last_id`, `created_on`) 
                          VALUES ('$table_name', '$id_for', '$id_prefix', '$initial_id', '$system_date_time')";
        $mysqli->query($insert_query);
        return $initial_id;
    } else {
        $last_digit = explode('-', $last_id);
        $last_id_number = $last_digit[1];

        if (strlen($last_id_number) > 5) {
            return 'ID cannot be more than 5 characters';
        }

        $digits = preg_replace('/[^0-9]/', '', $last_id_number);

        if ($digits === str_repeat('9', strlen($digits))) {
            return 'You have reached the last ID string';
        }

        $next_id = str_pad((intval($digits) + 1), strlen($digits), '0', STR_PAD_LEFT);
        $unique_id = $id_prefix . "-" . $next_id;

        $update_unique_header_query = "UPDATE `unique_id_header_all` SET `last_id`='$unique_id', `modified_on`='$system_date_time' WHERE `table_name`='$table_name' AND `id_for`='$id_for'";
        $mysqli->query($update_unique_header_query);

        return $unique_id;
    }
}
