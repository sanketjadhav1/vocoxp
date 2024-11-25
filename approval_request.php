<?PHP
error_reporting(0);
error_reporting(E_ALL & ~E_DEPRECATED);
ini_set('display_errors', 0);

include_once 'connection.php';
// Connection setup
$connection = connection::getInstance();
$mysqli = $connection->getConnection();

$connection1 = database::getInstance();
$mysqli1 = $connection1->getConnection();

$system_date = date("Y-m-d H:i:s");
$visitor_id = $_GET['visitor_id'] ?? "";
$meeting_with = $_GET['emp_id'] ?? "";

// Fetch visitor details
 $bulk_req_query = "SELECT * FROM `visitor_temp_activity_detail_all` WHERE `visitor_id`='$visitor_id' AND `meeting_with`='$meeting_with'";
$weblink_req_fetch_res = $mysqli->query($bulk_req_query);
$weblink_req_fetch_array = $weblink_req_fetch_res->fetch_assoc();

$agency_id = $weblink_req_fetch_array["agency_id"];
$visitor_email = $weblink_req_fetch_array["visitor_email"];
$visitor_name = $weblink_req_fetch_array["visitor_name"];
$people_with_visitor = $weblink_req_fetch_array["people_with_visitor"];

// Fetch employee details
$emp_query = "SELECT * FROM `employee_header_all` WHERE `emp_id`='$meeting_with' AND `agency_id`='$agency_id'";
$emp_fetch_res = $mysqli->query($emp_query);
$emp_fetch_array = $emp_fetch_res->fetch_assoc();
?>

<!DOCTYPE HTML>
<html lang="en-AU">
<head>
    <!-- Required Meta Tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CDN - Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <!-- Title -->
    <title>Approval Request</title>
</head>

<body>      

<?PHP

if ($weblink_req_fetch_array["meeting_status"] == 2) {
    // Redirect if meeting_with equals 2
    echo"<script>window.location.href='https://mounarchtech.com/vocoxp/upload_link/thankyou.php';</script>";
    
    exit(); // Ensure script stops executing after the redirect
} else {
?>
    <!-- Vertically Centered Cookies Modal -->
    <div class="modal fade" id="onload" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    Mr./Ms. <?php
                    $emp_name = $emp_fetch_array["name"];
                    $emp_mobile = $emp_fetch_array["contact"];
                    $emp_email = $emp_fetch_array["email_id"];
                    echo "$emp_name ( $emp_mobile ) ($emp_email)";
                    ?> with <?php echo $people_with_visitor; ?> members, want to visit you. Do you want to approve the request?
                </div>
                <div class="modal-footer text-center">
                    <a href="#" class="btn btn-success" id="approveBtn">Yes</a>
                    <a href="#" class="btn btn-danger" id="rejectBtn">No</a>
                </div>
            </div>
        </div>
    </div>
<?php
}
?>

<!-- Bootstrap Bundle with Popper -->
<!-- import jquery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

<!--Modal JS Script -->
 
    <script type="text/javascript">

    window.onload = () => {
        $('#onload').modal('show');
    }

    // Handle "Yes" (approve) button click
    $('#approveBtn').on('click', function() {
        sendEmailResponse('approve');
    });

    // Handle "No" (reject) button click
    $('#rejectBtn').on('click', function() {
        sendEmailResponse('reject');
    });

    // AJAX function to send email approval/rejection
    function sendEmailResponse(action) {
        $.ajax({
            url: 'email_approval_request.php', // Create this PHP file to handle approval/rejection logic
            type: 'POST',
            data: {
                action: action, 
                visitor_email: '<?php echo $visitor_email; ?>',
                visitor_name: '<?php echo $visitor_name; ?>',
                people_with_visitor: '<?php echo $people_with_visitor; ?>',
                visitor_id: '<?php echo $visitor_id; ?>',
                meeting_with: '<?php echo $meeting_with; ?>',
                emp_name: '<?php echo $emp_name; ?>',
                emp_email: '<?php echo $emp_email; ?>',
                web_link: '<?php echo $web_link; ?>',
                agency_id: '<?php echo $agency_id; ?>'
            },
            success: function(response) {
                // alert(response); // Display success or error message
                if(response=="Email")
                {
                    window.location.href="https://mounarchtech.com/vocoxp/upload_link/thankyou.php";
                }
                $('#onload').modal('hide'); // Hide modal after action
            },
            error: function(xhr, status, error) {
                alert('Error: ' + xhr.responseText);
            }
        });
    }
</script>
</body>
</html>
