<?php
// error_reporting(E_ALL);
date_default_timezone_set("Asia/Calcutta");
include 'connection.php';
$connection = connection::getInstance();
$mysqli = $connection->getConnection();

function decodeTokenFromGET()
{
    if (isset($_GET['token'])) {
        $encoded_token = $_GET['token'];
        return base64_decode($encoded_token);
    }
    return null;
}

function extractMemberIdAndTimestamp($decoded_token)
{
    $member_id_length = 16;
    $timestamp_length = 16;
    $member_id = substr($decoded_token, 0, $member_id_length);
    $timestamp = substr($decoded_token, $member_id_length, $timestamp_length);
    return array('member_id' => $member_id, 'timestamp' => $timestamp);
}
if ($_SERVER["REQUEST_METHOD"] == "GET"){
$decoded_token = decodeTokenFromGET();
$explod_token = explode("|", $decoded_token);

$token_member_id = $explod_token[0];
$update_status = "UPDATE `member_header_all` SET `web_link_status`='1' WHERE `member_id`='$token_member_id'";
$res_status = mysqli_query($mysqli, $update_status);
$fetch_mem_data = "SELECT * FROM `member_header_all` WHERE `member_id`='$token_member_id' AND `web_link_date` >= DATE_SUB(NOW(), INTERVAL 24 HOUR)";
$res_mem_data = mysqli_query($mysqli, $fetch_mem_data);

if ($res_mem_data) {
    if (mysqli_num_rows($res_mem_data) > 0) {
        $arr_mem = mysqli_fetch_assoc($res_mem_data);
    } else {
        echo '<script>alert("The link is expired.");</script>';
        exit;
    }
} else {
    echo '<script>alert("Query failed.");</script>';
    exit;
}
}
if ($arr_mem['web_link_verifications'] != "") {
    $verification = explode(",", $arr_mem['web_link_verifications']);
    foreach ($verification as $veri) {
        if ($veri == 1) {
            $veri_name = "Aadhar";
        } elseif ($veri == 2) {
            $veri_name = "Pan";
        } elseif ($veri == 3) {
            $veri_name = "Voter ID";
        } elseif ($veri == 4) {
            $veri_name = "Driving License";
        } elseif ($veri == 5) {
            $veri_name = "Domastic Passport";
        } elseif ($veri == 6) {
            $veri_name = "International Passport";
        } elseif ($veri == 7) {
            $veri_name = "E-Crime";
        }
        $var_name[] = $veri_name;
    }
}



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fname = trim($_POST['fname']);
    $contact = trim($_POST['contact']);
    $email = trim($_POST['email']);
    $designation = trim($_POST['designation']);
    $doj = trim($_POST['doj']);
    $city = trim($_POST['city']);
    $address = trim($_POST['address']);
    $relation = trim($_POST['relation']);
    $gender = trim($_POST['gender']);
    $mem_id = trim($_POST['mem_id']);
    $curr_date = date('Y-m-d H:i:s');

    
        if ($_FILES["profile_img"]["name"] != "") {
            if ($_FILES['profile_img']['error'] === UPLOAD_ERR_OK) {
                $base_url = 'https://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
                $tmp_name = $_FILES["profile_img"]["tmp_name"];
                $file_name = basename($_FILES["profile_img"]["name"]);
                $file_name = preg_replace("/[^a-zA-Z0-9\._-]/", "", $file_name);
                $target_dir_profile = "/active_folder/agency/member/profile_picture/";
                $target_path = __DIR__ . $target_dir_profile;

                if (!is_dir($target_path)) {
                    mkdir($target_path, 0777, true);
                }

                $file_path = $target_path . $file_name;

                if (move_uploaded_file($tmp_name, $file_path)) {
                    $profile_img = $base_url . $target_dir_profile . $file_name;
                    $update_mem = "UPDATE `member_header_all` SET  `profile_image`='$profile_img' WHERE `member_id`='$mem_id'";
                    $res_mem = mysqli_query($mysqli, $update_mem);
                } else {
                    echo '<script>alert("Failed to upload profile picture.");</script>';
                    exit;
                }
            } else {
                echo '<script>alert("Error during file upload: ' . $_FILES['profile_img']['error'] . '");</script>';
                exit;
            }
        }

        $update_mem = "UPDATE `member_header_all` SET `name`='$fname', `contact_no`='$contact', `email_id`='$email', `dob_or_doj`='$doj', `city`='$city', `address`='$address', `designation`='$designation', `updated_on`='$curr_date', `gender`='$gender', `relation`='$relation', `web_link_status`='2' WHERE `member_id`='$mem_id'";
        $res_mem = mysqli_query($mysqli, $update_mem);
        if ($res_mem) {
            echo '<script>alert("Your profile updated successfully!");</script>';
            exit;
        } else {
            echo '<script>alert("Error updating record.");</script>';
        }
    
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Registration</title>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        .register {
            background: -webkit-linear-gradient(left, #3931af, #00c6ff);
            margin-top: 3%;
            padding: 3%;
        }

        .register-left {
            text-align: center;
            color: #fff;
            margin-top: 4%;
        }

        .register-left .profile-image-container {
            position: relative;
            width: 150px;
            height: 150px;
            margin: 0 auto 15%;
        }

        .register-left .profile-image-container img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
        }

        .register-left .profile-image-container .edit-button {
            position: absolute;
            bottom: 0;
            right: 0;
            background-color: #0062cc;
            color: #fff;
            border: none;
            border-radius: 50%;
            padding: 5px;
            cursor: pointer;
        }

        .register-left input {
            border: none;
            border-radius: 1.5rem;
            padding: 2%;
            width: 60%;
            background: #f8f9fa;
            font-weight: bold;
            color: #383d41;
            margin-top: 30%;
            margin-bottom: 3%;
            cursor: pointer;
        }

        .register-right {
            background: #f8f9fa;
            border-top-left-radius: 10% 50%;
            border-bottom-left-radius: 10% 50%;
        }

        .register-left img {
            margin-top: 15%;
            margin-bottom: 5%;
            width: 25%;
            /* -webkit-animation: mover 2s infinite alternate; */
            /* animation: mover 1s infinite alternate; */
        }

        @-webkit-keyframes mover {
            0% {
                transform: translateY(0);
            }

            100% {
                transform: translateY(-20px);
            }
        }

        @keyframes mover {
            0% {
                transform: translateY(0);
            }

            100% {
                transform: translateY(-20px);
            }
        }

        .register-left p {
            font-weight: lighter;
            padding: 2%;
            /* margin-top: -9%; */
        }

        .register .register-form {
            padding: 10%;
            margin-top: 10%;
        }

        .btnRegister {
            float: right;
            margin-top: 10%;
            border: none;
            border-radius: 1.5rem;
            padding: 2%;
            background: #0062cc;
            color: #fff;
            font-weight: 600;
            width: 50%;
            cursor: pointer;
        }

        .register .nav-tabs {
            margin-top: 3%;
            border: none;
            background: #0062cc;
            border-radius: 1.5rem;
            width: 28%;
            float: right;
        }

        .register .nav-tabs .nav-link {
            padding: 2%;
            height: 34px;
            font-weight: 600;
            color: #fff;
            border-top-right-radius: 1.5rem;
            border-bottom-right-radius: 1.5rem;
        }

        .register .nav-tabs .nav-link:hover {
            border: none;
        }

        .register .nav-tabs .nav-link.active {
            width: 100px;
            color: #0062cc;
            border: 2px solid #0062cc;
            border-top-left-radius: 1.5rem;
            border-bottom-left-radius: 1.5rem;
        }

        .register-heading {
            text-align: center;
            margin-top: 8%;
            margin-bottom: -15%;
            color: #495057;
        }
    </style>
</head>

<body>

    <!------ Include the above in your HEAD tag ---------->

    <div class="container register">
        <div class="row">
            <div class="col-md-3 register-left">
            <form id="profile_form" action="member_self_data_entry_form.php" method="POST" enctype="multipart/form-data">
                <div class="profile-image-container">
                    <?php if (isset($arr_mem['profile_image']) && !empty($arr_mem['profile_image'])) { ?>
                        <img id="profile_img" src="<?php echo htmlspecialchars($arr_mem['profile_image']); ?>" alt="Profile Image" />
                        <button class="edit-button"><i class="fa fa-edit"></i></button>
                    <?php } else { ?>
                        <img src="https://mounarchtech.com/vocoxp/blank_pro.webp" alt="Profile Image" />
                        <!-- <input type="file" class="edit-button" id="profile_img_input" name="profile_img" required> -->
                    <?php } ?>
                </div>
                <h3>Welcome <?php echo htmlspecialchars($arr_mem['name']); ?></h3>
                <p><i class="fa-solid fa-phone"></i> <?php echo htmlspecialchars($arr_mem['contact_no']); ?></p>
                <p><i class="fa-regular fa-envelope"></i> <?php echo htmlspecialchars($arr_mem['email_id']); ?></p>
            </div>
            <div class="col-md-9 register-right">
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <h3 class="register-heading">Member Registration Form</h3>
                        <div class="row register-form">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <h6>Full Name <span style="color:red">*</span></h6>
                                    <input type="text" class="form-control forminput" name="fname" value="<?php echo htmlspecialchars($arr_mem['name']); ?>" pattern=".*\S.*" placeholder="First Name" required>
                                </div>
                                <div class="form-group">
                                    <h6>Designation</h6>
                                    <input type="text" class="form-control forminput" name="designation" value="<?php if (isset($arr_mem['designation'])) echo htmlspecialchars($arr_mem['designation']); ?> " placeholder="Designation">
                                </div>
                                <div class="form-group">
                                    <h6>City <span style="color:red">*</span></h6>
                                    <input type="text" class="form-control forminput" name="city" value="<?php if (isset($arr_mem['city'])) echo htmlspecialchars($arr_mem['city']); ?>" pattern=".*\S.*" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <h6><?php echo $arr_mem['type'] == 1 ? "Date of Birth" : "Date of Joining"; ?> <span style="color:red">*</span></h6>
                                    <input type="date" class="form-control forminput" name="doj" value="<?php if (isset($arr_mem['dob_or_doj'])) echo htmlspecialchars($arr_mem['dob_or_doj']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <h6>Address <span style="color:red">*</span></h6>
                                    <input type="text" class="form-control forminput" name="address" value="<?php if (isset($arr_mem['address'])) echo htmlspecialchars($arr_mem['address']); ?>" pattern=".*\S.*" required>
                                </div>
                                <div class="form-group">
                                    <?php
                                    if ($arr_mem['web_link_type'] == 2) {
                                        echo '<div class="card p-3 mt-2 allcard">';
                                        echo '<h6>Verifications <span style="color:red">*</span></h6>';

                                        // Generate checkboxes for each verification name
                                        foreach ($var_name as $name) {
                                            echo '<div class="form-check">';
                                            echo '<input class="form-check-input" type="checkbox" name="verifications[]" value="' . htmlspecialchars($name) . '">';
                                            echo '<label class="form-check-label">' . htmlspecialchars($name) . '</label>';
                                            echo '</div>';
                                        }

                                        echo '</div>';
                                    }
                                    ?>
                                </div>
                                <input type="hidden" value="<?php echo htmlspecialchars($explod_token[0]); ?>" name="mem_id">
                                <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token']); ?>">
                                <input type="submit" class="btnRegister">
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade show" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        <h3 class="register-heading">Apply as a Hirer</h3>
                        <div class="row register-form">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="First Name *" value="" />
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Last Name *" value="" />
                                </div>
                                <div class="form-group">
                                    <input type="email" class="form-control" placeholder="Email *" value="" />
                                </div>
                                <div class="form-group">
                                    <input type="text" maxlength="10" minlength="10" class="form-control" placeholder="Phone *" value="" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="password" class="form-control" placeholder="Password *" value="" />
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control" placeholder="Confirm Password *" value="" />
                                </div>
                                <div class="form-group">
                                    <select class="form-control">
                                        <option class="hidden" selected disabled>Please select your Security Question</option>
                                        <option>What is your Birthdate?</option>
                                        <option>What is Your old Phone Number</option>
                                        <option>What is your Pet Name?</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Answer *" value="" />
                                </div>
                                <input type="submit" class="btnRegister" value="Register" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
      </form>

    </div>
</body>

</html>
