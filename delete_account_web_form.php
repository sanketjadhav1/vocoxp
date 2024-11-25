<?php
include_once "connection.php";
$connection = connection::getInstance();
$mysqli = $connection->getConnection();

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $mobile_no = $_POST['mobile_no'];
    $email = $_POST['email'];

      $fetch_agency = "SELECT `agency_id` FROM `agency_header_all` WHERE `mobile_no`='$mobile_no' AND  `email_id`='$email'";
    
    $res_agency = mysqli_query($mysqli, $fetch_agency);
    if (mysqli_num_rows($res_agency)>0) {
        echo '<script>alert("Your request is Accepted. Please wait 48 hours our team is contact you.");</script>';
        exit;
    } else {
         echo '<script>alert("Mobile no or email id is not found. Please re-enter your credentials");</script>';
        
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

    <title>Mounarch</title>
    <script>
        function checkInput(ob) {
            const invalidChars = /[^0-9]/gi;
            if (invalidChars.test(ob.value)) {
                ob.value = ob.value.replace(invalidChars, "");
            }
        }
    </script>
    <style>
        html,
        body {
            height: 100%;
            margin: 0;
        }

        .center-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .p-2 {
            padding: 2rem;
            /* Adjust padding as needed */
            text-align: center;
            /* Center the content inside the div */
        }

        @media (max-width: 460px) {
            .maincard {
                width: 100% !important;
                margin: auto;
                box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
            }
        }

        .maincard {
            width: 40%;
            margin: auto;
            box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
        }
    </style>



</head>

<body>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <section>
        <div class="container">
           <br>
            <form action="delete_account_web_form.php" method="POST">
                <div class="card p-3 maincard">
                <div class="p-2" style="margin: auto;">
                <img src="circle.svg" alt="" width="100%">
            </div><br>
                    <!-- <img src="logo.svg" alt="" width="60%"> -->
                    <h6><span class="text-danger">*</span> Please enter your mobile number to request data and account deletion</h6>
                    <label for="mobile_no" class="mt-3">Mobile No</label>
                    <input class="input form-control " id="mobile_no" maxlength="10" onChange="checkInput(this)" onKeyup="checkInput(this)" type="text" autocomplete="off" name="mobile_no" required/>
                    <small class="form-text text-muted">Please enter a 10-digit mobile number.</small>



                    <label for="" class="mt-3">Email ID</label>
                    <input type="email" class="form-control " name="email" pattern=".*\S.*" required>
                    <input type="submit" class="form-control mt-3 bg-primary text-white submitbutton">

                </div>
            </form><br>
            <div class="p-2" style="margin: auto;">
                <img src="logo.svg" alt="" width="70px"><br>
                <p>Mounarch Tech Solutions & Systems Pvt. Ltd. <br> (Miscos Technologies Pvt. Ltd)</p>
            </div>
           
        </div>
    </section>
</body>

</html>