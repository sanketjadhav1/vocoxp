<?php
/* 
Name : json_for_action_manage_group.php
Version of the Requirment Document  : 2.0.0


Purpose :- This api is to create, update & delete groups
Mode :- multi mode

Developed By - Rishabh Shinde
*/
error_reporting(1);
include_once 'connection.php';

// Now you can use the connection class
$connection = connection::getInstance();
$mysqli = $connection->getConnection();

$check_error=check_error($mysqli, $_POST['agency_id'], $_POST['application_id'], $_POST['mode']);

if($check_error==1){

    $agency_id = $_POST['agency_id'];
    $application_id = $_POST['application_id'];
    $mode = $_POST['mode'];
    $crrdate=date("d-m-Y");
    if($mode=="create_group"){
        $group_name=$_POST['group_name'];
        $member_ids=trim($_POST['member_ids']);
    }
    if($mode=="update_group"){
        $group_id=$_POST['group_id'];
        $member_ids=trim($_POST['member_ids']);
        $remove_member_ids=trim($_POST['remove_member_ids']);
         $group_name=$_POST['group_name'];
    }elseif($mode=="delete_group"){
        $group_id=$_POST['group_id'];
    }
    
    $fetch_agency_id = "SELECT `agency_id` FROM `agency_header_all` WHERE `agency_id`='$agency_id'";
    $result_agency = mysqli_query($mysqli, $fetch_agency_id);
    $arr = mysqli_fetch_assoc($result_agency);
    
    if (!$arr) {
        $response[] = array("error_code" => 102, "message" => "Invalid agency ID. Please provide a valid agency ID");
        echo json_encode($response);
        return;
    } elseif ($arr['status'] != "1") {
        $response[] = array("error_code" => 103, "message" => "The agency name is currently inactive");
        echo json_encode($response);
        return;
    }
    
    if($mode=="create_group"){
        $fetch_group = "SELECT `group_name` 
                FROM `agency_groups_header_all` 
                WHERE `agency_id`='$agency_id' 
                      AND `group_name`='$group_name' 
                      AND `status`='active'";

        $res_group=mysqli_query($mysqli, $fetch_group);
        $arr_group=mysqli_fetch_assoc($res_group);
        if($arr_group){
            $response[] = array("error_code" => 107, "message" => "The group with same name already exits. Please enter different name.");
            echo json_encode($response);
            return;
        }
        $rand=rand(100000,999999);
        $group_id="G-".$rand;

        $mem_id = explode(",", $member_ids);
$final1 = ""; // Initialize $final1 outside the loop to accumulate all members

foreach ($mem_id as $mem) {
    $final1 .= $mem . '=' . $crrdate . ','; // Append each member with the current date
}

// Remove the trailing comma
$final1 = rtrim($final1, ',');

$insert_group = "INSERT INTO `agency_groups_header_all` 
                 (`agency_id`, `group_id`, `group_name`, `group_members`, `old_group_member_ids`, `status`, `created_on`) 
                 VALUES ('$agency_id', '$group_id', '$group_name', '$final1', '', 'active', '$crrdate')";

$res_group = mysqli_query($mysqli, $insert_group);

if ($res_group) {
    $response[] = array("error_code" => 100, "message" => "Group successfully created");
    echo json_encode($response);
    return;
}
    }elseif($mode=="update_group"){
        $fetch_groups = "SELECT `group_name`, `group_members`, `old_group_member_ids`, `group_members`,
        `old_group_member_ids`
        FROM `agency_groups_header_all` 
        WHERE `agency_id`='$agency_id' 
        AND `group_id`='$group_id'";

$res_groups = mysqli_query($mysqli, $fetch_groups);
$arr_groups = mysqli_fetch_assoc($res_groups);
 
if (!$arr_groups) {
    $response[] = array("error_code" => 107, "message" => "Invalid Group Id. Please provide the correct group id.");
    echo json_encode($response);
    return;
}

if ($arr_groups['group_name'] != $group_name) {
    // If the provided group name is different from the existing one
    $fetch_groups = "SELECT `group_name`, `status`, `agency_id` FROM `agency_groups_header_all` WHERE `agency_id`='$agency_id' AND `group_name`='$group_name' AND `status`='active'";
    $res_groups = mysqli_query($mysqli, $fetch_groups);

    if (!$res_groups) {
        // Handle query error
        echo "Error: " . mysqli_error($mysqli);
    } else {
        // Check if there is a group with the same name for the same agency
        while ($row = mysqli_fetch_assoc($res_groups)) {
            // If a group with the same name already exists
            $response[] = array("error_code" => 107, "message" => "The group with the same name already exists. Please enter a different name.");
            echo json_encode($response);
            return;
        }
        
        // Update the group name since it's unique
        $update_groups = "UPDATE `agency_groups_header_all` SET `group_name`='$group_name' WHERE `agency_id`='$agency_id' AND `group_id`='$group_id'";
        $res_update_groups = mysqli_query($mysqli, $update_groups);

        if (!$res_update_groups) {
            // Handle update error
            echo "Error updating group name: " . mysqli_error($mysqli);
        } else {
            // Group name updated successfully
            $response[] = array("error_code" => 100, "message" => "Group name updated successfully.");
            echo json_encode($response);
            return;
        }
    }
}



if ($member_ids != "") {
    $existing_mem = $arr_groups['group_members'];
    $mem_id = explode(",", $member_ids);
    $final1 = "";
    foreach ($mem_id as $mem) {
        if (!empty($mem)) {
            $final1 .= $mem . '=' . $crrdate . ',';
        }
    }
    $final1 = rtrim($final1, ',');
    $new = $existing_mem . ',' . $final1;

    $update_group = "UPDATE `agency_groups_header_all` 
    SET `group_members`='$new' 
    WHERE `agency_id`='$agency_id' 
    AND `group_id`='$group_id'";


    $res_group = mysqli_query($mysqli, $update_group);
    if (!$res_group) {
        die("Update Query Error: " . mysqli_error($mysqli));
    }
}



if ($remove_member_ids != "") {
    // Check if old_group_member_ids is not empty
    if ($arr_groups['old_group_member_ids'] != "") {
        $existing_mem = $arr_groups['group_members'];
        $mem_ex = explode(",", $existing_mem);
$old_id=$arr_groups['old_group_member_ids'];
        // Explode the list of members to remove
        $remove_member_ids = ltrim($remove_member_ids, ',');
        $mem_id = explode(",", $remove_member_ids);

        // Remove members to remove from existing members list
        foreach ($mem_ex as $key => $value) {
            if (in_array($value, $mem_id)) {
                unset($mem_ex[$key]);
            }
        }
        // print_r($mem_ex);
        // Rebuild the group members list
         $old = implode(",", $mem_ex);

        // Prepare the new member list
        $final1 = "";
        foreach ($mem_id as $mem) {
            $final1 .= $mem . '@' . $crrdate . ',';
        }
        // Remove trailing comma
        $new =$old_id. ",".rtrim($final1, ',');
    } else {
        // If old_group_member_ids is empty
         $existing_mem = $arr_groups['group_members'];
        $mem_ex = explode(",", $existing_mem);

        // Explode the list of members to remove
        $remove_member_ids = ltrim($remove_member_ids, ',');
        $mem_id = explode(",", $remove_member_ids);

        // Remove members to remove from existing members list
        foreach ($mem_ex as $key => $value) {
            if (in_array($value, $mem_id)) {
                unset($mem_ex[$key]);
            }
        }
        // print_r($mem_ex);
        // Rebuild the group members list
         $old = implode(",", $mem_ex);

        // Prepare the new member list
        $final1 = "";
        foreach ($mem_id as $mem) {
            $final1 .= $mem . '@' . $crrdate . ',';
        }
        // Remove trailing comma
        $new = rtrim($final1, ',');
    }
  
    // Update the database with the new group members list
    $update_groups = "UPDATE `agency_groups_header_all` 
    SET `group_members`='$old', `old_group_member_ids`='$new' 
    WHERE `agency_id`='$agency_id' 
    AND `group_id`='$group_id'";
    $res_groups = mysqli_query($mysqli, $update_groups);
    if (!$res_groups) {
        // Handle query error
        die("Update Query Error: " . mysqli_error($mysqli));
    }
}



$response[] = array("error_code" => 100, "message" => "Group details successfully updated");
echo json_encode($response);
return;

    }elseif($mode=="delete_group"){
        $ex_id=explode(",",$group_id);
        foreach($ex_id as $grop){
         $delete_group="UPDATE `agency_groups_header_all` SET `status`='deactive' WHERE `agency_id`='$agency_id' AND `group_id`='$grop'";
        $res_groups=mysqli_query($mysqli, $delete_group);
        }
        if($res_groups==true){
            $response[] = array("error_code" => 100, "message" => "Groups successfully deleted");
            echo json_encode($response);
            return;
        }
    }
     
}



function replaceNullWithEmptyString($value) {
    return is_null($value) ? '' : $value;
}


function check_error($mysqli, $agency_id, $application_id, $mode){

    $check_error=1;
    if(!$mysqli){
        $response[]=["error_code" => 101, "message" => "An unexpected server error occurred while
        processing your request. Please try after sometime"];
        echo json_encode($response);
        return;
    }
    if($_SERVER["REQUEST_METHOD"] != "POST"){
        $responce[] = array( "error_code" => 106, "message" => "please change request method to POST");
        echo json_encode($responce);
        return; 
    
            
    }
    if(!isset($agency_id)){
        $response[]=["error_code" => 104, "message" => "Please the parameter - agency_id"];
        echo json_encode($response);
        return;
    }else{
        if($agency_id==""){
            $response[]=["error_code" => 105, "message" => "Value of 'agency_id' cannot be empty"];
            echo json_encode($response);
            return;
        }
    }
    if(!isset($application_id)){
        $response[]=["error_code" => 119, "message" => "Please the parameter - application_id"];
        echo json_encode($response);
        return;
    }else{
        if($application_id==""){
            $response[]=["error_code" => 108, "message" => "Value of 'application_id' cannot be empty"];
            echo json_encode($response);
            return;
        }elseif($application_id!=132){
            $response[]=["error_code" => 109, "message" => "invalid application_id"];
            echo json_encode($response);
            return;
        }
    }

    if(!isset($mode)){
        $response[]=["error_code" => 110, "message" => "Please the parameter - mode"];
        echo json_encode($response);
        return;
    }else{
        if($mode==""){
            $response[]=["error_code" => 111, "message" => "Value of 'mode' cannot be empty"];
            echo json_encode($response);
            return;
        }else{
            if($mode!="create_group" && $mode!="update_group" && $mode!="delete_group"){
                $response[]=["error_code" => 112, "message" => "Please enter valid mode"];
                echo json_encode($response);
                return;
            }elseif($mode=="create_group"){
                if(!isset($_POST['group_name'])){
                    $response[]=["error_code" => 113, "message" => "Please the parameter - group_name"];
                    echo json_encode($response);
                    return; 
                }elseif($_POST['group_name']==""){
                    $response[]=["error_code" => 114, "message" => "Value of 'group_name' cannot be empty"];
                    echo json_encode($response);
                    return; 
                }
                if(!isset($_POST['member_ids'])){
                    $response[]=["error_code" => 115, "message" => "Please the parameter - member_ids"];
                    echo json_encode($response);
                    return; 
                }elseif($_POST['member_ids']==""){
                    $response[]=["error_code" => 116, "message" => "Value of 'member_ids' cannot be empty"];
                    echo json_encode($response);
                    return; 
                }
            }elseif($mode=="update_group"){
                // if(!isset($_POST['member_ids'])){
                //     $response[]=["error_code" => 117, "message" => "Please the parameter - member_ids"];
                //     echo json_encode($response);
                //     return; 
                // }elseif($_POST['member_ids']==""){
                //     $response[]=["error_code" => 118, "message" => "Value of 'member_ids' cannot be empty"];
                //     echo json_encode($response);
                //     return; 
                // }
                if(!isset($_POST['group_id'])){
                    $response[]=["error_code" => 119, "message" => "Please the parameter - group_id"];
                    echo json_encode($response);
                    return; 
                }elseif($_POST['group_id']==""){
                    $response[]=["error_code" => 120, "message" => "Value of 'group_id' cannot be empty"];
                    echo json_encode($response);
                    return; 
                }
                
            }elseif($mode=="delete_group"){
                if(!isset($_POST['group_id'])){
                    $response[]=["error_code" => 121, "message" => "Please the parameter - group_id"];
                    echo json_encode($response);
                    return; 
                }elseif($_POST['group_id']==""){
                    $response[]=["error_code" => 122, "message" => "Value of 'group_id' cannot be empty"];
                    echo json_encode($response);
                    return; 
                }
            }
        }
    }
   
    return $check_error;
}
?>