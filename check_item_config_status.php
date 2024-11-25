<?php
include_once "connection.php";
$connection1 = database::getInstance();
$mysqli1 = $connection1->getConnection();

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $check_error=check_error($mysqli1, $_GET['item_no']);
    if($check_error==1){
        $item_no=$_GET['item_no'];

         $fetch_product_item="SELECT `display_diagnostic_tool`, `is_item_configuration`, `item_no`, `item_id` FROM product_item_header_all WHERE `item_no`='$item_no' AND status_operational=1";
        $res_product_item=mysqli_query($mysqli1, $fetch_product_item);
        if(mysqli_num_rows($res_product_item)>0){
            $arr_product_item=mysqli_fetch_assoc($res_product_item);
        $data[]=["display_diagnostic_tool"=>$arr_product_item['display_diagnostic_tool']==1?"Yes":"No", "is_item_configuration"=>$arr_product_item['is_item_configuration']==1?"Yes":"No",];
        $res[]=["error_code"=>100, "message"=>"success", "item_id"=>$arr_product_item['item_id']];
        echo json_encode($res);
        return;
        }else{
            $res[]=["error_code"=>109, "message"=>"data not found"];
            echo json_encode($res);
            return;
        }
        
    }
}
function check_error($mysqli1, $item_no){
    $check_error=1;
    if (!$mysqli1) {
        $response[] = array("error_code" => 101, "message" => "An unexpected server error occurred while processing your request. Please try again later.");
        echo json_encode($response);
        return;
    }
    if ($_SERVER["REQUEST_METHOD"] != "GET") {
        $response[] = array("error_code" => 102, "message" => "Please change request method to POST");
        echo json_encode($response);
        return; 
    }

    if (!isset($item_no)) {
        $response[] = array("error_code" => 103, "message" => "Please provide the parameter - item_no");
        echo json_encode($response);
        return;
    } else {
        if ($item_no == "") {
            $response[] = array("error_code" => 104, "message" => "Value of 'item_no' cannot be empty");
            echo json_encode($response);
            return;
        }
    }
    return $check_error;
}
?>