<!-- Receipt - Visitor Pass -->
<?php include_once 'connection.php';

include 'libs/phpqrcode/qrlib.php';   // Include the phpqrcode library

$connection = connection::getInstance();

$mysqli = $connection->getConnection();

$visitor_name = '';
$visitor_mob_no = '';
$entry_at = '';
$valid_uo_to = '';
$metting_with = '';
$designation = '';
$department = '';
$person_img = 'images/user.png';   //default user img
$base64_image = '';
$employee_header_all_arr = array();

$visitor_id = isset($_GET['visitor_id']) ? $_GET['visitor_id'] : '';

$visitor_header_all = "SELECT `visitor_name`, `visitor_mobile`, `inserted_on`, `pass_valid_till`, `emp_id` FROM `visitor_header_all` WHERE `visitor_id`='$visitor_id' ";

$visitor_header_details = $mysqli->query($visitor_header_all);
$visitor_header_all_arr = mysqli_fetch_assoc($visitor_header_details);

if (!empty($visitor_header_all_arr)) {
    $visitor_name = $visitor_header_all_arr['visitor_name'];
    $visitor_mob_no = $visitor_header_all_arr['visitor_mobile'];
    $entry_at = ($visitor_header_all_arr['inserted_on'] != '') ? date('d-m-Y h:i A', strtotime($visitor_header_all_arr['inserted_on'])) : '';
    $valid_uo_to = ($visitor_header_all_arr['pass_valid_till'] != '' && $visitor_header_all_arr['pass_valid_till'] != '0000-00-00 00:00:00') ? date('d-m-Y h:i A', strtotime($visitor_header_all_arr['pass_valid_till'])) : ' -- ';

    $emp_id =  $visitor_header_all_arr['emp_id'];

    $employee_header_all = "SELECT `name`, `department`, `designation` FROM `employee_header_all` WHERE `emp_id`= '$emp_id'";

    $employee_header_all_details = $mysqli->query($employee_header_all);
    $employee_header_all_arr = mysqli_fetch_assoc($employee_header_all_details);
}
if (!empty($employee_header_all_arr)) {
    $metting_with = $employee_header_all_arr['name'];
    $designation = $employee_header_all_arr['designation'];
    $department = $employee_header_all_arr['department'];
}


$qr_code_arr = array('visitor_id' => $visitor_id);
$qr_code_json_data = json_encode($qr_code_arr);
$qr_code_encoded_data = base64_encode($qr_code_json_data);

ob_start();    // Start output buffering

// QR code settings
$ecc = 'M';        // Error correction level (L, M, Q, H)
$pixel_Size = 4;   // Size of the QR code 
$frame_Size = 4;   // Padding around the QR code 

// Generate the QR code and output it to the buffer
QRcode::png($qr_code_encoded_data, null, $ecc, $pixel_Size, $frame_Size);

// Get the image data from the buffer and encode it as base64
$image_data = ob_get_contents();
ob_end_clean(); // Clean (flush) the output buffer

// Encode the image data in base64
$base64_image = base64_encode($image_data);
?>


<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <!-- Bootstrap css  -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>Visitor Pass</title>

    <style>
        .row {
            text-align: center;
            font-size: 16px;
            margin: 0px;
        }


        table {
            width: 100%;
            border-collapse: collapse;
        }

        .scanner th,
        .scanner td {
            padding: 7px;
        }

        .padd-top {
            padding-top: 10px;
        }

        .person-img {
            width: 80px;
            height: 80px;
            border-radius: 40px;
            object-fit: cover;
        }
    </style>

</head>

<body>
    <div class="row">
        <table class="scanner">
            <thead>
                <tr>
                    <td>Welcome</td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <img src="<?php echo htmlspecialchars($person_img, ENT_QUOTES, 'UTF-8'); ?>" alt="person img" class="person-img">
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php echo ($base64_image != '') ?  "<img src='data:image/png;base64,$base64_image' alt='QR Code' />" : '';
                        ?>
                    </td>
                </tr>
            </tbody>
        </table>

        <table> <!-- visitor details -->
            <thead>
                <tr>
                    <td class="padd-top">
                        <span><?php echo $visitor_name; ?></span>
                    </td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <span><?php echo 'Mob. No. - ' . $visitor_mob_no; ?></span>
                    </td>
                </tr>
                <tr>
                    <td class="padd-top">
                        <span><?php echo 'Entry At : ' . $entry_at; ?></span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span><?php echo 'Valid Up to : ' . $valid_uo_to; ?></span>
                    </td>
                </tr>
                <tr>
                    <td class="padd-top">
                        <span>Meeting Details</span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span><?php echo 'Metting With - ' . $metting_with; ?></span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span><?php echo 'Designation - ' . $designation; ?></span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span><?php echo 'Department - ' . $department; ?></span>
                    </td>
                </tr>
            </tbody>
        </table>


    </div>

</body>