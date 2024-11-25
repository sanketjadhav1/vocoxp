<?php
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $res[]=["error_code"=>100, "message"=>"success"];
    echo json_encode($res);
    return;
}
?>