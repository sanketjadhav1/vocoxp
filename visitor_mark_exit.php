<?php
// Include the connection file
require_once 'connection.php';

// Get the database connection
$connection = connection::getInstance()->getConnection();

// Set the header for JSON response
header('Content-Type: application/json');

// Check the request method
$requestMethod = $_SERVER['REQUEST_METHOD'];

switch ($requestMethod) {
    case 'POST':
        // Get the input data from POST request
        $inputData = json_decode(file_get_contents('php://input'), true);

        // Check if required fields are provided
        if (!isset($inputData['agency_id']) || empty($inputData['agency_id']) ||
            !isset($inputData['visitor_id']) || empty($inputData['visitor_id'])) {
             
            echo json_encode([
                "error_code" => 200,
                "message" => "Missing or invalid parameters. 'agency_id' and 'visitor_id' are required."
            ]);
            exit;
        }

        // Get the required fields from input data
        $agency_id = $inputData['agency_id'];
        $visitor_id = $inputData['visitor_id'];

        // Check if the visitor has already exited (status = 3)
        $statusCheckQuery = "SELECT `status` FROM `visitor_header_all` WHERE `agency_id` = ? AND `visitor_id` = ?";
        $statusStmt = mysqli_prepare($connection, $statusCheckQuery);
        mysqli_stmt_bind_param($statusStmt, 'ss', $agency_id, $visitor_id);
        mysqli_stmt_execute($statusStmt);
        $statusResult = mysqli_stmt_get_result($statusStmt);

        // If no matching record is found
        if (mysqli_num_rows($statusResult) == 0) {
            echo json_encode([
                "error_code" => 200,
                "message" => "No matching record found for the provided agency_id and visitor_id."
            ]);
            exit;
        }

        $statusRow = mysqli_fetch_assoc($statusResult);

        // If the visitor has already exited (status = 3)
        if ($statusRow['status'] == '3') {
            echo json_encode([
                "error_code" => 201,
                "message" => "The visitor has already exited."
            ]);
            exit;
        }

        // Manually set status to 3 (exit)
        $status = "3";

        // Prepare the update query
        $updateQuery = "UPDATE `visitor_header_all` 
                        SET `status` = ? 
                        WHERE `agency_id` = ? AND `visitor_id` = ?";

        // Prepare the statement
        $stmt = mysqli_prepare($connection, $updateQuery);

        // Bind the parameters
        mysqli_stmt_bind_param($stmt, 'sss', $status, $agency_id, $visitor_id);

        // Execute the query
        if (mysqli_stmt_execute($stmt)) {
            // Check if any row was updated
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                // Fetch the updated visitor data (optional)
                $visitorQuery = "SELECT `visitor_name`, `emp_id`, `visitor_mobile` 
                                 FROM `visitor_header_all` 
                                 WHERE `agency_id` = ? AND `visitor_id` = ?";
                $visitorStmt = mysqli_prepare($connection, $visitorQuery);
                mysqli_stmt_bind_param($visitorStmt, 'ss', $agency_id, $visitor_id);
                mysqli_stmt_execute($visitorStmt);
                $visitorResult = mysqli_stmt_get_result($visitorStmt);
                $visitorData = mysqli_fetch_assoc($visitorResult);

                $data = [
                    "visitor_id" => $visitor_id,
                    "visitor_name" => $visitorData['visitor_name'],
                    "emp_id" => $visitorData['emp_id'], // Adjust this based on your field names
                    "visitor_mobile" => $visitorData['visitor_mobile']
                ];

                $response = [
                    "error_code" => 100,
                    "message" => "Status updated successfully.",
                    "data" => [$data]
                ];
            } else {
                // No matching record found (this is unlikely due to earlier check)
                $response = [
                    "error_code" => 201,
                    "message" => "No matching record found for the provided agency_id and visitor_id."
                ];
            }
        } else {
            // Query execution error
            $response = [
                "error_code" => 500,
                "message" => "Failed to update the status. Please try again."
            ];
        }

        // Close the statement
        mysqli_stmt_close($stmt);
        echo json_encode($response);
        break;

    default: 
        echo json_encode([
            "error_code" => 405,
            "message" => "Method not allowed."
        ]);
        break;
}
?>
