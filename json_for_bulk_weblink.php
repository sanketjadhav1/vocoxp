<?php
include_once 'connection.php';
// error_reporting(E_ALL & ~E_DEPRECATED);
// ini_set('display_errors', 1);
// Now you can use the connection class
$connection = connection::getInstance();
$mysqli = $connection->getConnection();

$connection1 = database::getInstance();
$mysqli1 = $connection1->getConnection();

$agency_id = $_GET['agency_id'];
$request_no = $_GET['request_no'];
$bulk_id = $_GET['bulk_id'];

$fetch_agency = "SELECT `company_name`, `address` FROM `agency_header_all` WHERE `agency_id`='$agency_id'";
$res_agency = mysqli_query($mysqli, $fetch_agency);
$arr_agency = mysqli_fetch_assoc($res_agency);

$fetch_data = "SELECT * FROM `bulk_weblink_request_all` WHERE `agency_id`='$agency_id' AND `request_no`='$request_no' AND `bulk_id`='$bulk_id'";
$res_data = mysqli_query($mysqli, $fetch_data);
$arr_data = mysqli_fetch_assoc($res_data);
$verification = explode(",", $arr_data['verifications']);
// if ($_SERVER["REQUEST_METHOD"] == "POST") {

// }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Form for the Agency Owner VOCOxP ver 2.1</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            width: 60%;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
        }

        h1,
        h2 {
            text-align: center;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
        }

        .form-control {
            width: 50%;
            margin: 0 auto 10px auto;
            text-align: center;
        }

        .form-group button {
            width: 40%;
            background-color: #007BFF;
            color: white;
            border: none;
            cursor: pointer;
            margin: 0 auto;
            display: inline-block;
        }

        .form-group button:hover {
            background-color: #0056b3;
        }

        .option-group {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .option-group div {
            margin-right: 20px;
        }

        .slide-container {
            display: flex;
            transition: transform 0.5s ease-in-out;
            width: 200%;
            /* Adjust width for slide effect */
        }

        .slide {
            flex: 1 0 50%;
            /* Adjust width for each slide */
            box-sizing: border-box;
        }

        .verification-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
            gap: 10px;
            justify-items: center;
            margin-top: 10px;
        }

        .verification-grid div {
            background-color: #f1f1f1;
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
            line-height: 1.5;
            font-size: 16px;
            border-radius: 5px;
            box-shadow: rgba(0, 0, 0, 0.25) 0px 54px 55px, rgba(0, 0, 0, 0.12) 0px -12px 30px, rgba(0, 0, 0, 0.12) 0px 4px 6px, rgba(0, 0, 0, 0.17) 0px 12px 13px, rgba(0, 0, 0, 0.09) 0px -3px 5px;
        }

        .grid-container {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            grid-template-rows: repeat(4, 1fr);
            gap: 10px;
            margin-top: 20px;
            display: none;
            /* Hide initially */
        }

        .grid-item {
            background-color: #f1f1f1;
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
            line-height: 1.5;
            font-size: 16px;
            border-radius: 5px;
            box-shadow: rgba(0, 0, 0, 0.25) 0px 4px 6px;
        }
    </style>
</head>

<body>
    <div class="container" style="box-shadow: rgba(0, 0, 0, 0.19) 0px 10px 20px, rgba(0, 0, 0, 0.23) 0px 6px 6px;">
        <div class="slide-container text-center" id="slide-container">
            <div class="slide">
                <h2>Welcome</h2>
                <form id="uploadForm" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="agency-name"><b>Agency Name: <?php echo $arr_agency['company_name'] ?></b></label>
                    </div>
                    <div class="form-group">
                        <label for="address"><b>Address: <?php echo $arr_agency['address'] ?></b></label>
                    </div>
                    <div class="form-group">
                        <label for="request-no"><b>Request No:- </b><?php echo $arr_data['request_no'] ?></label>
                    </div>
                    <div class="form-group">
                        <label for="verification-for"><b>Verification For:- </b><?php echo $arr_data['generated_for'] ?></label>
                    </div>
                    <div class="form-group">
                        <label for="verification-items">Verification's</label>
                        <div class="verification-grid">
                            <?php foreach ($verification as $veri) {
                                if ($veri == 1) {
                                    $veri_name = "AADHAR";
                                } elseif ($veri == 2) {
                                    $veri_name = "PAN";
                                } elseif ($veri == 3) {
                                    $veri_name = "VOTER ID";
                                } elseif ($veri == 4) {
                                    $veri_name = "Driving Licence";
                                } elseif ($veri == 5) {
                                    $veri_name = "E-CRIME";
                                }
                            ?>
                                <div><?php echo $veri_name; ?></div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="upload-excel">Upload Excel file</label>
                        <input type="file" id="upload-excel" class="form-control" name="upload_excel" onchange="updateFileName()" required>
                    </div>
                    <div class="form-group">
                        <button type="button" class="btn btn-primary" onclick="nextSlide()">Next</button>
                    </div>

            </div>
            <div class="slide">
                <div class="form-group">
                    <label for="uploaded-file">Uploaded Excel File:</label>
                    <input type="text" id="uploaded-file" class="form-control" name="uploaded-file" value="No file chosen" readonly>
                    <button type="submit" class="btn btn-primary">Start Analyze</button>
                    <input type="hidden" name="agency_id" value="<?php echo $agency_id ?>">
                    <input type="hidden" name="bulk_id" value="<?php echo $bulk_id ?>">
                </div>
                </form>
                <div id="response" class="alert" style="display: none;"></div>

                <!-- <h2>Processing</h2> -->

                <h2>Amount to be paid by:</h2>
                <div class="option-group">
                    <div>
                        <label for="agency-wallet">Agency Wallet</label>
                        <input type="radio" id="agency-wallet" name="payment-method" value="agency-wallet" onclick="toggleGridVisibility()">
                    </div>
                    <div>
                        <label for="end-user">By End User</label>
                        <input type="radio" id="end-user" name="payment-method" value="end-user" onclick="toggleGridVisibility1()">
                    </div>
                </div>

                <h2>Weblink Activated from:</h2>
                <div class="form-group">
                    <input type="date" id="weblink-activated-from" class="form-control" name="weblink-activated-from" value="2024-07-11">
                </div>
                <h2>Weblink valid Till:</h2>
                <div class="form-group">
                    <input type="date" id="weblink-valid-till" class="form-control" name="weblink-valid-till" value="2024-07-11">
                </div>
                <h2>Reminder SMS</h2>
                <div class="form-group">
                    <select id="reminder-sms" class="form-control" name="reminder-sms">
                        <option value="none">None</option>
                        <option value="every-day">In every Day</option>
                        <option value="alternate-day">In Alternate Day</option>
                        <option value="after-3-days">In after 03 Days</option>
                    </select>
                </div>
                <h2>Reminder Emails</h2>
                <div class="form-group">
                    <select id="reminder-emails" class="form-control" name="reminder-emails">
                        <option value="none">None</option>
                        <option value="every-day">In every Day</option>
                        <option value="alternate-day">In Alternate Day</option>
                        <option value="after-3-days">In after 03 Days</option>
                    </select>
                </div>
                <div class="form-group">
                    <button type="button" class="btn btn-secondary" onclick="prevSlide()">Back</button>
                    <button type="button" class="btn btn-primary" onclick="nextSlide()">Next</button>
                </div>
            </div>
            <div class="slide">
                <table class="table table-bordered" id="table2" style="display: none;">
                    <thead>
                        <tr>
                            <th>Verifications </th>
                            <th>Enter Your Rate </th>
                            <th>Reserve Rate Rs.</th>
                            <th>Total </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($verification as $veri) {
                            if ($veri == 1) {
                                $veri_name = "AADHAR";
                            } elseif ($veri == 2) {
                                $veri_name = "PAN";
                            } elseif ($veri == 3) {
                                $veri_name = "VOTER ID";
                            } elseif ($veri == 4) {
                                $veri_name = "Driving Licence";
                            } elseif ($veri == 5) {
                                $veri_name = "E-CRIME";
                            }
                        ?>
                            <tr>
                                <td><?php echo $veri_name; ?></td>
                                <td><input type="text"></td>
                                <td><input type="text"></td>
                                <td><input type="text"></td>

                            </tr>

                        <?php } ?>

                    </tbody>
                    <table class="table table-bordered" id="table2" style="display: none;">
                        <thead>
                            <tr>
                                <th>Verifications </th>
                                <th>Enter Your Rate </th>
                                <th>Reserve Rate Rs.</th>
                                <th>Total </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($verification as $veri) {
                                if ($veri == 1) {
                                    $veri_name = "AADHAR";
                                } elseif ($veri == 2) {
                                    $veri_name = "PAN";
                                } elseif ($veri == 3) {
                                    $veri_name = "VOTER ID";
                                } elseif ($veri == 4) {
                                    $veri_name = "Driving Licence";
                                } elseif ($veri == 5) {
                                    $veri_name = "E-CRIME";
                                }
                            ?>
                                <tr>
                                    <td><?php echo $veri_name; ?></td>
                                    <td><input type="text"></td>
                                    <td><input type="text"></td>
                                    <td><input type="text"></td>

                                </tr>

                            <?php } ?>

                        </tbody>

                    </table>
                    <button type="button" class="btn btn-secondary" onclick="prevSlide()">Back</button>
            </div>
        </div>
    </div>


    <script>
        function updateFileName() {
            const fileInput = document.getElementById('upload-excel');
            const fileName = fileInput.files.length > 0 ? fileInput.files[0].name : 'No file chosen';
            document.getElementById('uploaded-file').value = fileName;
        }
        let currentSlide = 0;

        function nextSlide() {
            const fileInput = document.getElementById('upload-excel');
        if (!fileInput.files.length) {
            alert('Please select an Excel file before proceeding.');
            return;
        }
            const slides = document.querySelectorAll('.slide');
            if (currentSlide < slides.length - 1) {
                currentSlide++;
                document.getElementById('slide-container').style.transform = `translateX(-${currentSlide * 50}%)`;
            }
        }

        function prevSlide() {
            const slides = document.querySelectorAll('.slide');
            if (currentSlide > 0) {
                currentSlide--;
                document.getElementById('slide-container').style.transform = `translateX(-${currentSlide * 50}%)`;
            }
        }
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function toggleGridVisibility() {
            $('#table1').css('display', '');
        }

        function toggleGridVisibility1() {
            $('#table2').css('display', '');
        }
        $(document).ready(function() {
            $('#uploadForm').on('submit', function(e) {
                e.preventDefault();

                var formData = new FormData(this);

                $.ajax({
                    url: 'https://mounarchtech.com/vocoxp/ctrlcode.php',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        console.log(response); // Log response for debugging
                        var result = JSON.parse(response);
                        var responseDiv = $('#response');
                        responseDiv.removeClass('alert-success alert-danger');
                        if (result.status === 'success') {
                            responseDiv.addClass('alert-success');
                            responseDiv.html(result.message);
                        } else {
                            responseDiv.addClass('alert-danger');
                            responseDiv.html(result.message);
                        }
                        responseDiv.show();
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error('AJAX error:', textStatus, errorThrown); // Log error for debugging
                        var responseDiv = $('#response');
                        responseDiv.removeClass('alert-success');
                        responseDiv.addClass('alert-danger');
                        responseDiv.html('Error: ' + textStatus + ' - ' + errorThrown);
                        responseDiv.show();
                    }
                });

            });
        });
    </script>

</body>

</html>