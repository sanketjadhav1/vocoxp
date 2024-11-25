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
    $system_date = date("y-m-d");
    $system_date_time = date("Y-m-d H:i:s");
    
// Assuming these values are defined elsewhere in your code





        //  $response[] = ["error_code" => 101, "message" => "Empolyee_id cannot be empty"];
        //     echo json_encode($response);
        //     exit;
        
    // $mode = $_POST['mode'];
    


        

        //  $response[] = ["error_code" => 101, "message" => "Empolyee_id cannot be empty"];
        //     echo json_encode($response);
        //     exit;
        
        // $agency_id=$_POST['agency_id'];
        
    //     $sql_011 = "select project_name from project_header_all where project_id='$project_id' ";
    // $result_011 = mysqli_query($conn, $sql_011);
    // $row_011 = mysqli_fetch_assoc($result_011);
    // $project_name=$row_011['project_name'];

    $sql_01 = "select * from help_and_support_videos_all where status='1' order by inserted_on ";
    $result_01 = mysqli_query($mysqli, $sql_01);
    if ($mysqli->affected_rows > 0) {
        while ($row_01 = mysqli_fetch_assoc($result_01)) {
            
            // $bm_id=$row_01['BM_id'];
            // $row_01['project_name']=$project_name;
            // $working_pipelines=$row_01['working_pipelines'];
           
                    // $row_01["wp_data"]=[];
                
            // $empp=get_emp_name($row_01['handled_by'],$conn);
            // $a1=explode("@",$empp);
            // $row_01['handled_by_name']=$a1[1];
            $data[]=$row_01;
            
            
  
            
                         
            
                         
        }
        
         $responce = ["error_code" => 100, "message" => "help videos data fetched","data" => $data];
        echo json_encode($responce);
        return;
    }else{
          $response = ["error_code" => 101, "message" => "No help_and_support_videos_all data found."];
            echo json_encode($response);
            exit;
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



