<?php
/* 
Name : json_for_info_verification_report.php
Version of the Requirment Document  : 2.0.1


Purpose :- This api is to use fetch verification report details
Mode :- single mode

Developed By - Rishabh Shinde
*/
// error_reporting(E_ALL & ~E_DEPRECATED);
// ini_set('display_errors', 1);
// error_reporting(1);
include_once 'connection.php';

// Now you can use the connection class
$connection = connection::getInstance();
$mysqli = $connection->getConnection();

$connection1 = database::getInstance();
$mysqli1 = $connection1->getConnection();
apponoff($mysqli);

logout($_SERVER['HTTP_AUTH_KEY'], $mysqli);

$check_error = check_error($mysqli, $_POST['agency_id'], $_POST['search_type']);

if ($check_error == 1) {

    $agency_id = $_POST['agency_id'];
    $admin_id = $_POST['admin_id'];
    $search_type = $_POST['search_type'];
    $show_only = $_POST['show_only'];
    $show_date = $_POST['show_date'];
    $member_id = $_POST['member_id'];
    if($search_type==2 || $search_type==3){
        if($show_only==1){
            $where="AND `activity_status` IN (1, 2)";
        }elseif($show_only==2){
            $where="AND `activity_status`=1";
        }else{
            $where="AND `activity_status`=2";
        }
       
    }
    if($admin_id!=""){
        $where_admin=" AND `admin_id` = '$admin_id'";
    }
    // if($search_type==2){
    //     $where_nbfc="AND `source_from`='4'";
    // }else{
    //     $where_nbfc="AND `source_from`='1'";
    // }
    if($show_date!=""){
        $explod_date=explode("@", $show_date);
        $from=date('Y-m-d',strtotime($explod_date[0]));
        $to=date('Y-m-d',strtotime($explod_date[1]));
        $date="AND DATE(`completed_on`) BETWEEN '$from' AND '$to'";
        $date1="AND DATE(`modified_on`) BETWEEN '$from' AND '$to'";
    }
    if (isset($_POST['doc_type_id'])) {
        $doc_type_id = $_POST['doc_type_id'];
    }
    $fetch_agency_id = "SELECT `agency_id`, `status` FROM `agency_header_all` WHERE `agency_id`='$agency_id'";
    $result_agency = mysqli_query($mysqli, $fetch_agency_id);
    $arr = mysqli_fetch_assoc($result_agency);

    if (!$arr) {
        $response[] = array("error_code" => "102", "message" => "Invalid agency ID. Please provide a valid agency ID");
        echo json_encode($response);
        return;
    } elseif ($arr['status'] != "1") {
        $response[] = array("error_code" => "103", "message" => "The agency name is currently inactive");
        echo json_encode($response);
        return;
    }
    $speci_id = explode(",", $doc_type_id);
    
    if($search_type!=0){
       $fetch_direct = "SELECT `direct_id`, `verification_id`, `agency_id`, `completed_on`, `linked_table`, `report_url`, `activity_status` FROM `direct_verification_details_all` WHERE `agency_id`='$agency_id' AND `source_from`='$search_type' AND `verification_id` IN ('" . implode("','", $speci_id) . "') $where $date $where_admin ";
    $res_direct = mysqli_query($mysqli, $fetch_direct);
    
    $response = array();
    
    while ($arr_direct = mysqli_fetch_assoc($res_direct)) {
        $link_table = $arr_direct['linked_table'];
        $direct_id = $arr_direct['direct_id'];
        $fetch_name = "SELECT `name` FROM $link_table WHERE `agency_id`='$agency_id' AND `direct_id`='$direct_id'";
        $res_name = mysqli_query($mysqli, $fetch_name);
        $arr_name = mysqli_fetch_assoc($res_name);

        if($arr_direct['verification_id']=="DVF-00001"){
            $doc_name="Aadhar Verification";
        }elseif($arr_direct['verification_id']=="DVF-00002"){
            $doc_name="Pan Verification";
        }elseif($arr_direct['verification_id']=="DVF-00004"){
            $doc_name="DL Verification";
        }elseif($arr_direct['verification_id']=="DVF-00005"){
            $doc_name="Voter ID Verification";
        }elseif($arr_direct['verification_id']=="DVF-00006"){
            $doc_name="Passport Verification";
        }
    
        $response[] = array(
            'name' => replaceNullWithEmptyString($arr_name['name']),
            'document_name' => replaceNullWithEmptyString($doc_name),
            'report_url' => replaceNullWithEmptyString($arr_direct['report_url']),
            'is_verified' => $arr_direct['activity_status'],
            'created_on' => replaceNullWithEmptyString(date('d-m-Y h:i A', strtotime($arr_direct['completed_on'])))
        );
    }
}else{
    // Fetch table names from verification_header_all
    $sql_outer = "SELECT `table_name` FROM `verification_header_all` WHERE `status`='1'";
    $result_outer = mysqli_query($mysqli1, $sql_outer);
    
    
    if ($result_outer) {
        while ($row_outer = mysqli_fetch_assoc($result_outer)) {
            //not empty khayum mujawar
            if($row_outer['table_name'])
            {
                $table_name = $row_outer['table_name'];
            }
            
    
            if ($search_type == "1") {
                if ($member_id == "") {
                    $fetch_from_table = "SELECT `agency_id`, `verification_status`, `modified_on`, `created_on`, `verification_id`, `person_id`, `verification_report` FROM $table_name WHERE agency_id='$agency_id' AND `verification_id` IN ('" . implode("','", $speci_id) . "') AND `verification_status`=2 $date1";
                } else {
                    $fetch_from_table = "SELECT `agency_id`, `verification_status`, `modified_on`, `created_on`, `verification_id`, `person_id`, `verification_report` FROM $table_name WHERE agency_id='$agency_id' AND `verification_id` IN ('" . implode("','", $speci_id) . "') AND `person_id`='$member_id' AND `verification_status`=2 $date1";
                }
            } else {
                if ($member_id == "") {
                    $fetch_from_table = "SELECT `agency_id`, `verification_status`, `modified_on`, `created_on`, `verification_id`, `person_id`, `verification_report` FROM $table_name WHERE agency_id='$agency_id' AND `verification_id` IN ('" . implode("','", $speci_id) . "') AND `verification_status`=2 $date1";
                } else {
                    $fetch_from_table = "SELECT `agency_id`, `verification_status`, `modified_on`, `created_on`, `verification_id`, `person_id`, `verification_report` FROM $table_name WHERE agency_id='$agency_id' AND `verification_id` IN ('" . implode("','", $speci_id) . "') AND `person_id`='$member_id' AND `verification_status`=2 $date1";
                }
            }
    
            // Execute the query
            $result_inner = mysqli_query($mysqli1, $fetch_from_table);
    
            // Check for errors in the query execution
            if (!$result_inner) {
                die("Error in SQL query: " . mysqli_error($mysqli1));
            }
    
            // Process the result set
            while ($arr_inner = mysqli_fetch_assoc($result_inner)) {
                $in_agency = $arr_inner['agency_id'];
                $in_member = $arr_inner['person_id'];
    
                // Fetch member details
                $sql_member = "SELECT `name`, `type` FROM member_header_all WHERE `member_id`='$in_member' AND `registration_id`='$in_agency'";
                $res_member = mysqli_query($mysqli, $sql_member);
                $arr_member = mysqli_fetch_assoc($res_member);
    
                // Fetch archived member name if not found in member_header_all
                $sql = "SELECT `name` FROM `member_header_archive_all` WHERE `member_id`='$in_member' AND `registration_id`='$in_agency'";
                $res1 = mysqli_query($mysqli, $sql);
                $newname = mysqli_fetch_assoc($res1);
    
                $date_new = ($arr_inner['verification_status'] == 2) ? date('d-m-Y h:i A', strtotime($arr_inner['modified_on'])) : date('d-m-Y h:i A', strtotime($arr_inner['created_on']));
                $specification_id = $arr_inner['verification_id'];
    
                // Fetch document name from verification_header_all
                $fetch_doc_name = "SELECT `abbreviations`, `name` FROM verification_header_all WHERE verification_id='$specification_id'";
                $result_spec = mysqli_query($mysqli1, $fetch_doc_name);
                $arr_inner_spec = mysqli_fetch_assoc($result_spec);
    
                $response[] = array(
                    'name' => $arr_member['name'] == "" ? $newname['name'] : $arr_member['name'],
                    'member_id' => replaceNullWithEmptyString($arr_inner['person_id']),
                    'document_name' => replaceNullWithEmptyString($arr_inner_spec['name']),
                    'report_url' => replaceNullWithEmptyString($arr_inner['verification_report']),
                    'created_on' => replaceNullWithEmptyString($date_new),
                    'is_verified' => '1',
                    'type' => $arr_member['type'] == 1 ? "member" : "employee"
                );
            }
        }
    
        // Custom sorting function to sort by type and then by created_on field in descending order
       
    } 
}
if($response!=""){
usort($response, function ($a, $b) {
    return strtotime($b['created_on']) - strtotime($a['created_on']);
});
}

if (!empty($response)) {
    $data[] = ["error_code" => 100, "message" => "Successfully Fetched", "data" => $response];
} else {
    $data[] = ["error_code" => 110, "message" => "Data not found"];
}  
    // Encode the response array as JSON
    $json_response = json_encode($data, JSON_PRETTY_PRINT);
    
    // Output the JSON
    echo $json_response;
    
    // Function to replace null values with an empty string
    
    
    
    }

   






function check_error($mysqli, $agency_id, $show_only)
{

    $check_error = 1;
    if (!$mysqli) {
        $response[] = ["error_code" => "101", "message" => "An unexpected server error occurred while
        processing your request. Please try after sometime"];
        echo json_encode($response);
        return;
    }
    if ($_SERVER["REQUEST_METHOD"] != "POST") {
        $responce[] = array("error_code" => "106", "message" => "please change request method to POST");
        echo json_encode($responce);
        return;
    }
    if (!isset($agency_id)) {
        $response[] = ["error_code" => "104", "message" => "Please the parameter - agency_id"];
        echo json_encode($response);
        return;
    } else {
        if ($agency_id == "") {
            $response[] = ["error_code" => "105", "message" => "Value of 'agency_id' cannot be empty"];
            echo json_encode($response);
            return;
        }
    }
    // if (!isset($show_only)) {
    //     $response[] = ["error_code" => "107", "message" => "Please the parameter - type"];
    //     echo json_encode($response);
    //     return;
    // } else {
    //     if ($show_only == "") {
    //         $response[] = ["error_code" => "108", "message" => "Value of 'type' cannot be empty"];
    //         echo json_encode($response);
    //         return;
    //     } elseif ($show_only != "1" && $show_only != "2" && $show_only != "3") {
    //         $response[] = ["error_code" => "109", "message" => "Value of 'type' must be employee or family"];
    //         echo json_encode($response);
    //         return;
    //     }
    // }
    return $check_error;
}
function replaceNullWithEmptyString($value) {
    return $value === null ? "" : $value;
}