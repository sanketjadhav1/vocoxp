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

$decoded_token = decodeTokenFromGET();
$explod_token = explode("|", $decoded_token);

$token_member_id=$explod_token[0];
$update_status="UPDATE `member_header_all` SET `web_link_status`='1' WHERE `member_id`='$token_member_id'";
$res_status=mysqli_query($mysqli, $update_status);
$fetch_mem_data = "SELECT * FROM `member_header_all` WHERE `member_id`='$token_member_id' AND `web_link_date` >= DATE_SUB(NOW(), INTERVAL 24 HOUR)";
$res_mem_data = mysqli_query($mysqli, $fetch_mem_data);

if ($res_mem_data) {
    if (mysqli_num_rows($res_mem_data) > 0) {
        
        $arr_mem = mysqli_fetch_assoc($res_mem_data);
        
    } else {
        echo '<script>alert(" The link is expired.");</script>';
                exit;
       
    }
}
if($arr_mem['web_link_verifications']!=""){
    $verification=explode(",",$arr_mem['web_link_verifications']);
    foreach($verification as $veri){
        if($veri==1){
            $veri_name="Aadhar";
        }elseif($veri==2){
            $veri_name="Pan";
        }elseif($veri==3){
            $veri_name="Voter ID";
        }elseif($veri==4){
            $veri_name="Driving License";
        }elseif($veri==5){
            $veri_name="Domastic Passport";
        }elseif($veri==6){
            $veri_name="International Passport";
        }elseif($veri==7){
            $veri_name="E-Crime";
        }
        $var_name[]=$veri_name;
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

    if (!empty($fname) && !empty($contact) && !empty($email) && !empty($doj) && !empty($city) && !empty($address)) {
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
    } else {
        echo '<script>alert("Input fields cannot contain only spaces.");</script>';
    }
}

?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="logo.svg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script defer src="https://cdn.jsdelivr.net/npm/@vladmandic/face-api/dist/face-api.js"></script>
    <title>Member Management</title>
    <style>
        .allcard {
            width: 50%;
            margin: auto;
            border-radius: 10px
        }

        section {
            padding: 4%
        }

        .forminput {
            width: 100%
        }

        @media (max-width: 460px) {
            .allcard {
                width: 100%;
                margin: auto;
                border-radius: 10px
            }

            .mainrow {
                align-items: center !important
            }

            .forminput {
                width: 100%
            }

            .submitbutton {
                width: 24% !important;
                margin-left: 0% !important;
                border: none
            }
        }

        .submitbutton {
            width: 10%;
            margin-left: 25%;
            border: none
        }
    </style>
    <script>
        // Function to validate Date of Birth input
       

        // Load face-api.js models
        Promise.all([
            faceapi.nets.tinyFaceDetector.loadFromUri('/models'),
            faceapi.nets.faceLandmark68Net.loadFromUri('/models'),
            faceapi.nets.faceRecognitionNet.loadFromUri('/models'),
            faceapi.nets.faceExpressionNet.loadFromUri('/models')
        ]).then(start).catch(error => console.error("Error loading face-api.js models:", error));

        function start() {
            const input = document.getElementById('profile_img');
            input.addEventListener('change', handleImageUpload);
        }

        async function handleImageUpload(event) {
            try {
                const image = await loadImage(event.target.files[0]);
                if (!image) {
                    alert("Failed to load image");
                    return;
                }

                const canvas = document.getElementById('overlay');
                const displaySize = {
                    width: image.width,
                    height: image.height
                };

                faceapi.matchDimensions(canvas, displaySize);
                const detections = await faceapi.detectAllFaces(image, new faceapi.TinyFaceDetectorOptions())
                    .withFaceLandmarks()
                    .withFaceExpressions();
                const resizedDetections = faceapi.resizeResults(detections, displaySize);

                canvas.getContext('2d').clearRect(0, 0, canvas.width, canvas.height);
                faceapi.draw.drawDetections(canvas, resizedDetections);
                faceapi.draw.drawFaceLandmarks(canvas, resizedDetections);
                faceapi.draw.drawFaceExpressions(canvas, resizedDetections);

                // Display image and overlay
                document.getElementById('profile_image').src = image.src;
                canvas.style.display = 'block';
                canvas.style.left = image.offsetLeft + 'px';
                canvas.style.top = image.offsetTop + 'px';

                // Check if any face was detected
                if (detections.length === 0) {
                    alert("No face detected. Please upload an image with a clear view of your face.");
                    document.getElementById('profile_img').value = ""; // Clear the file input
                    return;
                }
            } catch (error) {
                console.error("Error processing image:", error);
                alert("An error occurred while processing the image. Please try again.");
            }
        }

        function loadImage(file) {
            return new Promise((resolve, reject) => {
                const reader = new FileReader();
                reader.onload = () => {
                    const img = new Image();
                    img.src = reader.result;
                    img.onload = () => resolve(img);
                    img.onerror = reject;
                };
                reader.onerror = reject;
                reader.readAsDataURL(file);
            });
        }
    </script>
</head>

<body style="background-color:#A5A9C7;">
    <section class="">
        <div class="container">
            <div class="row mainrow">
                <form id="profile_form" action="member_self_data_entry_form.php" method="POST" enctype="multipart/form-data">
                    <div class="card p-3 allcard">
                        <h3>Hello <?php echo htmlspecialchars($arr_mem['name']); ?></h3>
                        <p>Please fill your form</p>
                        <p style="color:red">* Required</p>
                    </div>
                    <div class="card p-3 mt-2 allcard">
                        <h6>Upload your profile picture <span style="color:red">*</span></h6>
                        <br>
                        <?php if (isset($arr_mem['profile_image']) && !empty($arr_mem['profile_image'])) : ?>
                            <img id="profile_image" src="<?php echo htmlspecialchars($arr_mem['profile_image']); ?>" alt="Profile Image" width="50%">
                            <br>
                            <input type="file" class="form-control forminput" id="profile_img" name="profile_img" required>
                        <?php else : ?>
                            <input type="file" class="form-control forminput" id="profile_img" name="profile_img" required>
                        <?php endif; ?>
                    </div>
                    <div class="card p-3 mt-2 allcard">
                        <h6>Full Name <span style="color:red">*</span></h6>
                        <input type="text" class="form-control forminput" name="fname" value="<?php echo htmlspecialchars($arr_mem['name']); ?>" pattern=".*\S.*" required>
                    </div>
                    <div class="card p-3 mt-2 allcard">
                        <h6>Contact No <span style="color:red">*</span></h6>
                        <input type="text" class="form-control forminput" name="contact" value="<?php echo htmlspecialchars($arr_mem['contact_no']); ?>" required readonly>
                    </div>
                    <div class="card p-3 mt-2 allcard">
                        <h6>Email ID <span style="color:red">*</span></h6>
                        <input type="email" class="form-control forminput" name="email" value="<?php echo htmlspecialchars($arr_mem['email_id']); ?>" required readonly>
                    </div>
                    <?php if ($arr_mem['type'] == 0) { ?>
                        <div class="card p-3 mt-2 allcard">
                            <h6>Designation</h6>
                            <input type="text" class="form-control forminput" name="designation" value="<?php if (isset($arr_mem['designation'])) echo htmlspecialchars($arr_mem['designation']); ?>">
                        </div>
                    <?php } ?>
                    <div class="card p-3 mt-2 allcard">
                        <h6><?php echo $arr_mem['type'] == 1 ? "Date of Birth" : "Date of Joining"; ?> <span style="color:red">*</span></h6>
                        <input type="date" class="form-control forminput" name="doj" value="<?php if (isset($arr_mem['dob_or_doj'])) echo htmlspecialchars($arr_mem['dob_or_doj']); ?>" required>
                    </div>
                    <?php if ($arr_mem['type'] == 1) { ?>
                        <div class="card p-3 mt-2 allcard">
                            <h6>Select Gender <span style="color:red">*</span></h6>
                            <div class="row">
                                <div class="col-md-3">
                                    <input type="radio" name="gender" value="Male" <?php if ($arr_mem['gender'] == 'Male') echo 'checked'; ?> required> <label for="">Male</label>
                                </div>
                                <div class="col-md-4">
                                    <input type="radio" name="gender" value="Female" <?php if ($arr_mem['gender'] == 'Female') echo 'checked'; ?> required> <label for="">Female</label>
                                </div>
                                <div class="col-md-5">
                                    <input type="radio" name="gender" value="Prefer not to mention" <?php if ($arr_mem['gender'] == 'Prefer not to mention') echo 'checked'; ?> required> <label for="">Prefer not to mention</label>
                                </div>
                            </div>
                        </div>

                        <div class="card p-3 mt-2 allcard">
                            <h6>Relation <span style="color:red">*</span></h6>
                            <select name="relation" id="" class="form-control" required>
                                <option value="">Select Relation</option>
                                <option value="Friend" <?php if ($arr_mem['relation'] == 'Friend') echo 'selected'; ?>>Friend</option>
                                <option value="Family" <?php if ($arr_mem['relation'] == 'Family') echo 'selected'; ?>>Family</option>
                            </select>
                        </div>
                    <?php } ?>
                    <div class="card p-3 mt-2 allcard">
                        <h6>City <span style="color:red">*</span></h6>
                        <input type="text" class="form-control forminput" name="city" value="<?php if (isset($arr_mem['city'])) echo htmlspecialchars($arr_mem['city']); ?>" pattern=".*\S.*" required>
                    </div>
                    <div class="card p-3 mt-2 allcard">
                        <h6>Address <span style="color:red">*</span></h6>
                        <input type="text" class="form-control forminput" name="address" value="<?php if (isset($arr_mem['address'])) echo htmlspecialchars($arr_mem['address']); ?>" pattern=".*\S.*" required>
                    </div>
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
                    <input type="hidden" value="<?php echo htmlspecialchars($explod_token[0]); ?>" name="mem_id">
                    <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token']); ?>">
                    <input type="submit" class="form-control mt-2 bg-primary text-white submitbutton">
                </form>
                <canvas id="overlay" style="display:none; position:absolute;"></canvas>
            </div>
        </div>
    </section>
</body>

</html>
