
<?PHP 
error_reporting(0);
error_reporting(E_ALL & ~E_DEPRECATED);
 ini_set('display_errors', 0);
 include_once 'connection.php';
 //connection 
$connection = connection::getInstance();
$mysqli = $connection->getConnection();

$connection1 = database::getInstance();
$mysqli1 = $connection1->getConnection();
// Include PHPMailer and connection files
use PHPMailer\PHPMailer\PHPMailer;
require 'vendor/autoload.php';  // Ensure PHPMailer is loaded

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action']; // "approve" or "reject"
    $visitor_id = $_POST['visitor_id'];
    $meeting_with = $_POST['meeting_with'];
    $emp_name = $_POST['emp_name'];
    $emp_email = $_POST['emp_email'];
    $web_link = $_POST['web_link'];
    $agency_id = $_POST['agency_id'];
    $visitor_email = $_POST['visitor_email'];
    $visitor_name = $_POST['visitor_name'];
    $people_with_visitor = $_POST['people_with_visitor'];

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'mail.mounarchtech.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'transactions@mounarchtech.com';
        $mail->Password = 'Mtech!@12345678'; // Secure this value
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;
        $mail->setFrom('transactions@mounarchtech.com', 'Micro Integrated Semi Conductor Systems Pvt. Ltd.');
        $mail->isHTML(true);

        // Email subject and body depending on action
        if ($action === 'approve') {
            $mail->Subject = ' Visiting request approved';
            $mail->Body = "<!DOCTYPE html>
                           <html>
                           <head>
                               <style>
                                   .email-body {
                                       font-family: Arial, sans-serif;
                                       line-height: 1.6;
                                       color: #333;
                                   }
                                   .button {
                                       display: inline-block;
                                       padding: 10px 20px;
                                       margin: 20px 0;
                                       font-size: 16px;
                                       color: white;
                                       background-color: #007BFF;
                                       text-decoration: none;
                                       border-radius: 5px;
                                   }
                               </style>
                           </head>
                           <body>
                               <div class='email-body'>
                                <p>Dear $visitor_name,</p>
                           <p>Your visit request with $people_with_visitor members has been <strong>approved</strong>. Please complete further procedure to visit.</p>
                           <p>Best regards,</p>
                            <p>Micro Integrated Semi Conductor Systems Pvt. Ltd.</p>
                             </div>
                           </body>
                           </html>";
        } else if ($action === 'reject') {
            $mail->Subject = 'Visiting request rejected';
            $mail->Body = "<!DOCTYPE html>
                           <html>
                           <head>
                               <style>
                                   .email-body {
                                       font-family: Arial, sans-serif;
                                       line-height: 1.6;
                                       color: #333;
                                   }
                                   .button {
                                       display: inline-block;
                                       padding: 10px 20px;
                                       margin: 20px 0;
                                       font-size: 16px;
                                       color: white;
                                       background-color: #007BFF;
                                       text-decoration: none;
                                       border-radius: 5px;
                                   }
                               </style>
                           </head>
                           <body>
                               <div class='email-body'>
                               <p>Dear $visitor_name,</p>
                           <p>Your visit request with $people_with_visitor members has been <strong>rejected</strong>.</p>
                           <p>Thank you.</p>
                            <p>Best regards,</p>
                            <p>Micro Integrated Semi Conductor Systems Pvt. Ltd.</p>
                             </div>
                           </body>
                           </html>";
        }

        // Send email to the employee
        $mail->addAddress($emp_email);

        // Check if email is sent
        if ($mail->send()) {
            // Update database with status and link
            $status = ($action === 'approve') ? 'approved' : 'rejected';
            $email_status = 1;

            // Assuming $mysqli is your database connection
             $update_query = "UPDATE visitor_temp_activity_detail_all 
                             SET  meeting_status  = '2' 
                             WHERE visitor_id = '$visitor_id' AND agency_id = '$agency_id' AND meeting_with = '$meeting_with'";

            if ($mysqli->query($update_query)) {
                echo"Email";
            } else {
                echo"Database update failed.";
            }
        } else {
            echo"Failedemail.";
        }
    } catch (Exception $e) {
        echo "Mail could not be sent. Error";
    }
} else {
    echo "Invalid request method.";
}
?>
