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
    case 'GET':
        // Check if required parameters are provided
        if (!isset($_GET['agency_id']) || empty($_GET['agency_id']) || 
            !isset($_GET['visitor_id']) || empty($_GET['visitor_id'])) {
            http_response_code(400); // Bad Request
            echo json_encode([
                "error_code" => 404,
                "message" => "Missing or invalid parameters: agency_id and visitor_id are required."
            ]);
            exit;
        }

        $agency_id = $_GET['agency_id'];
        $visitor_id = $_GET['visitor_id'];

        // Initialize an array to hold the fetched data
        $data = [];

        // Query to fetch user details based on the verification type and number
        $query = "SELECT aadhar_number, pan_number,verification_type, dl_number, voter_number, passport_number, 
                  visitor_name AS name, dob, gender, address, front_photo, back_photo, user_photo 
                  FROM visitor_temp_activity_detail_all 
                  WHERE agency_id = ? AND visitor_id = ?";

        // Prepare statement
        $stmt = mysqli_prepare($connection, $query);

        // Bind parameters
        mysqli_stmt_bind_param($stmt, 'ss', $agency_id, $visitor_id);

        // Execute the query
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        // Fetch the data
        if ($row = mysqli_fetch_assoc($result)) {
            $dobFormatted = (new DateTime($row['dob']))->format('d-m-Y');
            // Check each verification type
            if (!empty($row['aadhar_number'])) {
                $data = [
                    "name" => $row['name'],
                    "dob" => $dobFormatted,
                    "gender" => $row['gender'],
                    "address" => $row['address'],
                    "front_photo" => $row['front_photo'],
                    "back_photo" => $row['back_photo'],
                    "user_photo" => $row['user_photo'],
                    "verification_type" =>$row['verification_type'],
                    "aadhar_number" => $row['aadhar_number']
                ];
            } elseif (!empty($row['pan_number'])) {
                $data = [
                    "name" => $row['name'],
                    "dob" => $dobFormatted,
                    "gender" => $row['gender'],
                    "address" => $row['address'],
                    "front_photo" => $row['front_photo'],
                    "back_photo" => $row['back_photo'],
                    "user_photo" => $row['user_photo'],
                    "verification_type" =>$row['verification_type'],
                    "pan_number" => $row['pan_number']
                ];
            } elseif (!empty($row['dl_number'])) {
                $data = [
                    "name" => $row['name'],
                    "dob" => $dobFormatted,
                    "gender" => $row['gender'],
                    "address" => $row['address'],
                    "front_photo" => $row['front_photo'],
                    "back_photo" => $row['back_photo'],
                    "user_photo" => $row['user_photo'],
                    "verification_type" =>$row['verification_type'],
                    "dl_number" => $row['dl_number']
                ];
            } elseif (!empty($row['voter_number'])) {
                $data = [
                    "name" => $row['name'],
                    "dob" => $dobFormatted,
                    "gender" => $row['gender'],
                    "address" => $row['address'],
                    "front_photo" => $row['front_photo'],
                    "back_photo" => $row['back_photo'],
                    "user_photo" => $row['user_photo'],
                    "verification_type" =>$row['verification_type'],
                    "voter_number" => $row['voter_number']
                ];
            } elseif (!empty($row['passport_number'])) {
                $data = [
                    "name" => $row['name'],
                    "dob" => $dobFormatted,
                    "gender" => $row['gender'],
                    "address" => $row['address'],
                    "front_photo" => $row['front_photo'],
                    "back_photo" => $row['back_photo'],
                    "user_photo" => $row['user_photo'],
                    "verification_type" =>$row['verification_type'],
                    "passport_number" => $row['passport_number']
                ];
            }
        }

        // Check if any data found
        if (empty($data)) {
            http_response_code(200); // Not Found
            echo json_encode([
                "error_code" => 201,
                "message" => "No records found for the provided agency_id and visitor_id."
            ]);
            exit;
        }

        // Successful response with the required fields
        echo json_encode([
            "error_code" => 100,
            "message" => "Data fetched successfully.",
            "data" => $data
        ]);

        break;

    default:
        http_response_code(405); // Method Not Allowed
        echo json_encode([
            "error_code" => 405,
            "message" => "Method not allowed."
        ]);
        break;
}
?>
