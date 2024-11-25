<?PHP

include_once '../connection.php';

// error_reporting(E_ALL & ~E_DEPRECATED);

// ini_set('display_errors', 1);

// Now you can use the connection class

$connection = connection::getInstance();

$mysqli = $connection->getConnection();



$connection1 = database::getInstance();

$mysqli1 = $connection1->getConnection();

 $fetch_verification = "SELECT * FROM `verification_configuration_all` WHERE `ver_type`='1' AND `operational_status` = '1'";
    $res_verificaton = mysqli_query($mysqli1, $fetch_verification);
    while ($arr_verification = mysqli_fetch_assoc($res_verificaton)) {

        $arr_veri[] = $arr_verification;
    }
    $jsonArrVeri = json_encode($arr_veri);


?>