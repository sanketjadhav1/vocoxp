<?php
// Suppress deprecation notices
// error_reporting(E_ALL & ~E_DEPRECATED);
// ini_set('display_errors', 1);
include 'connection.php';

// ini_set('display_errors', 1);
$connection = connection::getInstance();
$mysqli = $connection->getConnection();
///////////////////////////////////////////////
$connection1 = database::getInstance();
$mysqli1 = $connection1->getConnection();

// require_once __DIR__ . '/vendor/autoload.php';


date_default_timezone_set('Asia/kolkata');
    $system_date = date("Y-m-d");
    $system_date_time = date("Y-m-d H:i:s");
    $system_time = date("H:i:s");
    
// Assuming these values are defined elsewhere in your code





        //  $response[] = ["error_code" => 101, "message" => "Empolyee_id cannot be empty"];
        //     echo json_encode($response);
        //     exit;
        
    $mode = $_POST['mode'];
                 $agency_id=$_POST['agency_id'];
             $site_id=$_POST['site_id'];
            $admin_id=$_POST['admin_id'];
    
    if($mode=='get_in_worker'){
        
        

    $sql_01 = "select * from construction_site_worker_header_all where agency_id='$agency_id' and site_id='$site_id'";
    $result_01 = mysqli_query($mysqli, $sql_01);
    $r=0;
    if ($mysqli->affected_rows > 0) {
        while ($row_01 = mysqli_fetch_assoc($result_01)) {
            $worker_id=$row_01['worker_id'];
            $sql_02 = "select * from worker_attendance_transaction_all where agency_id='$agency_id' and site_id='$site_id' and worker_id='$worker_id' and date='$system_date' and sign_in_time!='' and sign_out_time='' ";
    $result_02 = mysqli_query($mysqli, $sql_02);
    if ($mysqli->affected_rows > 0) {
        // $row_02 = mysqli_fetch_assoc($result_02);
        // $row_02['sign_in_time'];
        
    }else{
        $r++;
        $data[]=$row_01;
    }
            
            
            
            
        }  
        if($r>0){
           $responce[] = ["error_code" => 100, "message" => "signin data fetched Successfully","data" => $data];
            echo json_encode($responce);
            return; 
        }else{
            $responce[] = ["error_code" => 101, "message" => "no worker found"];
            echo json_encode($responce);
            return;
        }
        
    }        
            
        
        
        if ($mysqli->affected_rows > 0) {
            $responce[] = ["error_code" => 100, "message" => "Site added Successfully"];
            echo json_encode($responce);
            return;
        }else{
            $responce[] = ["error_code" => 101, "message" => "Internal server error please retry"];
            echo json_encode($responce);
            return;
        }
        
    
    }else if($mode=='get_out_worker'){
        
        

    $sql_01 = "select * from construction_site_worker_header_all where agency_id='$agency_id' and site_id='$site_id'";
    $result_01 = mysqli_query($mysqli, $sql_01);
    $r=0;
    if ($mysqli->affected_rows > 0) {
        while ($row_01 = mysqli_fetch_assoc($result_01)) {
            $worker_id=$row_01['worker_id'];
            $sql_02 = "select * from worker_attendance_transaction_all where agency_id='$agency_id' and site_id='$site_id' and worker_id='$worker_id' and date='$system_date' and sign_in_time!='' and sign_out_time!='' ";
    $result_02 = mysqli_query($mysqli, $sql_02);
    if ($mysqli->affected_rows > 0) {
        // $row_02 = mysqli_fetch_assoc($result_02);
        // $row_02['sign_in_time'];
        
    }else{
        $r++;
        $data[]=$row_01;
    }
            
            
            
            
        }  
        if($r>0){
           $responce[] = ["error_code" => 100, "message" => "signin data fetched Successfully","data" => $data];
            echo json_encode($responce);
            return; 
        }else{
            $responce[] = ["error_code" => 101, "message" => "no worker found"];
            echo json_encode($responce);
            return;
        }
        
    }        
            
        
        
        if ($mysqli->affected_rows > 0) {
            $responce[] = ["error_code" => 100, "message" => "Site added Successfully"];
            echo json_encode($responce);
            return;
        }else{
            $responce[] = ["error_code" => 101, "message" => "Internal server error please retry"];
            echo json_encode($responce);
            return;
        }
        
    
    }else if($mode=='signin'){
        $worker_id=$_POST['worker_id'];
        
       $sql_02 = "Insert into worker_attendance_transaction_all (`agency_id`,`site_id`,`worker_id`,`date`,`admin_id`,`sign_in_time`,`sign_out_time`) VALUES ('$agency_id','$site_id','$worker_id','$system_date','$admin_id','$system_time','')"; 
        $result_02 = mysqli_query($mysqli, $sql_02);
        if ($mysqli->affected_rows > 0) {
            $responce[] = ["error_code" => 100, "message" => "Signin Successfully"];
            echo json_encode($responce);
            return;
        }else{
            $responce[] = ["error_code" => 101, "message" => "Please try again"];
            echo json_encode($responce);
            return;
        }    
    
    }else if($mode=='signout'){
        
        
    
        $worker_id=$_POST['worker_id'];
    $sql_02 = "update worker_attendance_transaction_all set sign_out_time='$system_time' where agency_id='$agency_id' and site_id='$site_id' and worker_id='$worker_id' and sign_in_time!=''"; 
        $result_02 = mysqli_query($mysqli, $sql_02);
        if ($mysqli->affected_rows > 0) {
            $responce[] = ["error_code" => 100, "message" => "Signout Successfully"];
            echo json_encode($responce);
            return;
        }else{
            $responce[] = ["error_code" => 101, "message" => "Please retry"];
            echo json_encode($responce);
            return;
        }
        
    
    }
    
    
    
    
    
    
    
    

    // die;
    // else if($type=="edit"){
    //     //update
    //     $sql_02="update temporary_project_header_all set updated_on='$system_date_time',project_name='$project_name',client_type_id='$client_type_id',client_array_updated='$client_array_updated',selectedClientId='$selectedClientId',project_status='$project_status',project_start_date='$project_start_date',project_comp_time='$project_comp_time',sales_person_details='$sales_person_details',project_type_id='$project_type_id' where project_id='$project_id_new'";
    //     $result_02 = mysqli_query($conn, $sql_02);
    //     if ($conn->affected_rows > 0) {
    //         $responce[] = ["error_code" => 100, "message" => "Project screen 1 data saved Successfully","project_id"=>$project_id_new];
    //         echo json_encode($responce);
    //         return;
    //     }else{
    //         $responce[] = ["error_code" => 101, "message" => "Internal server error please retry"];
    //         echo json_encode($responce);
    //         return;
    //     }
    // }
    
        
        
// die;
            
            

            
            
    
        
    














       

// function common_chk_error($mysqli)
// {


//     $common_chk_error_res = 1;
//     if (!$mysqli) {        // connection check
//         logError("Active Project", "DB Connection", 111, "There was an issue connecting to the database.", "");
//         $response[] = ["error_code" => 111, "message" => "There was an issue connecting to the database. Please try again later."];
//         echo json_encode($response);
//         return 0;
//     }

//     if ($_SERVER["REQUEST_METHOD"] != "POST") {     // check requesed method
//         logError("Active Project", "Request Method", 112, "Please use POST method", "");
//         $response[] = ["error_code" => 112, "message" => "Please use POST method"];
//         echo json_encode($response);
//         return 0;
//     }

//     return $common_chk_error_res;
// }






/// function  for logerror
function logError($moduleName, $processName, $errorCode, $errorMessage, $urlOfFiles) {
    global $mysqli;
    mysqli_query($mysqli, "INSERT INTO error_log_transaction_all (module_name, process_name, error_code, erro_msg, url_of_files, status, created_on) VALUES ('$moduleName', '$processName', '$errorCode', '$errorMessage', '$urlOfFiles', 'open', NOW())");
}


?>



