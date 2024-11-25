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
        // Check if agency_id is provided in the query string
        if (!isset($_GET['agency_id']) || empty($_GET['agency_id'])) {
            http_response_code(400); // Bad Request
            echo json_encode([
                "error_code" => 400,
                "message" => "Missing or invalid parameter: agency_id is required."
            ]);
            exit;
        }

        $agency_id = $_GET['agency_id'];

        // Set default pagination values
        $limit = isset($_GET['limit']) ? intval($_GET['limit']) : 20; // Default limit is 20
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1; // Default page is 1
        $offset = ($page - 1) * $limit;

        // Prepare base queries for header and temp data
        $headerQuery = "SELECT * FROM `visitor_header_all` WHERE agency_id = ?";
        $tempQuery = "SELECT * FROM `visitor_temp_activity_detail_all` WHERE agency_id = ?";

        // Prepare statements
        $headerStmt = mysqli_prepare($connection, $headerQuery);
        $tempStmt = mysqli_prepare($connection, $tempQuery);

        // Bind parameters for header statement
        mysqli_stmt_bind_param($headerStmt, 's', $agency_id);

        // Execute header query
        mysqli_stmt_execute($headerStmt);
        $headerResult = mysqli_stmt_get_result($headerStmt);

        // Fetch data from the header query
        $headerData = mysqli_fetch_all($headerResult, MYSQLI_ASSOC);

        // Now bind parameters for temp statement
        mysqli_stmt_bind_param($tempStmt, 's', $agency_id);

        // Execute temp query
        mysqli_stmt_execute($tempStmt);
        $tempResult = mysqli_stmt_get_result($tempStmt);

        // Fetch data from the temp query
        $tempData = mysqli_fetch_all($tempResult, MYSQLI_ASSOC);

        // Combine results (before pagination)
        $combinedData = array_merge($headerData, $tempData);

        // Filter data based on optional query parameters
        if (isset($_GET['visitor_location_id'])) {
            $combinedData = array_filter($combinedData, function($item) {
                return $item['visitor_location_id'] === $_GET['visitor_location_id'];
            });
        }
        if (isset($_GET['visitor_mobile'])) {
            $combinedData = array_filter($combinedData, function($item) {
                return $item['visitor_mobile'] === $_GET['visitor_mobile'];
            });
        }
        if (isset($_GET['visitor_name'])) {
            $combinedData = array_filter($combinedData, function($item) {
                return stripos($item['visitor_name'], $_GET['visitor_name']) !== false; // Case-insensitive search
            });
        }
        if (isset($_GET['meeting_with'])) {
            $combinedData = array_filter($combinedData, function($item) {
                return $item['meeting_with'] === $_GET['meeting_with'];
            });
        }

        // Total records after filtering
        $totalFilteredRecords = count($combinedData);

        // If no data found, return a 404 error
        if (empty($combinedData)) {
            http_response_code(404); // Not Found
            echo json_encode([
                "error_code" => 404,
                "message" => "No records found for the provided criteria."
            ]);
            exit;
        }

        // Paginate the data
        $paginatedData = array_slice($combinedData, $offset, $limit);

        // Calculate total pages
        $totalPages = ceil($totalFilteredRecords / $limit);

        // Successful response with pagination
        echo json_encode([
            "error_code" => 100,
            "message" => "Data fetched successfully.",
            "data" => array_values($paginatedData), // Re-index the array after pagination
            "pagination" => [
                "total_records" => $totalFilteredRecords,
                "current_page" => $page,
                "total_pages" => $totalPages,
                "limit" => $limit
            ]
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
