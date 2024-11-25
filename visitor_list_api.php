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
            http_response_code(200); // Bad Request
            echo json_encode([
                "error_code" => 404,
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
        // Check and apply filters only if the parameter is not empty
if (!empty($_GET['visitor_location_id'])) {
    $combinedData = array_filter($combinedData, function($item) {
        return $item['visitor_location_id'] === $_GET['visitor_location_id'];
    });
}
if (!empty($_GET['visitor_mobile'])) {
    $combinedData = array_filter($combinedData, function($item) {
        return $item['visitor_mobile'] === $_GET['visitor_mobile'];
    });
}
if (!empty($_GET['visitor_name'])) {
    $combinedData = array_filter($combinedData, function($item) {
        return stripos($item['visitor_name'], $_GET['visitor_name']) !== false;
    });
}
if (!empty($_GET['meeting_with'])) {
    $combinedData = array_filter($combinedData, function($item) {
        return $item['meeting_with'] === $_GET['meeting_with'];
    });
}
if (!empty($_GET['date_between'])) {  //between date filter
    $combinedData = array_filter($combinedData, function($item) {
        $date_between = explode('@', $_GET['date_between']);   /*date format = 22/05/2024@29/10/2024*/
        $date_from = date('Y-m-d', strtotime(str_replace('/', '-', $date_between[0])));
        $date_to = date('Y-m-d', strtotime(str_replace('/', '-', $date_between[1])));
        
        $date = (isset($item['inserted_on'])) ? date('Y-m-d', strtotime($item['inserted_on'])) : (isset($item['requested_on']) ? date('Y-m-d', strtotime($item['requested_on'])) : '');
      
        return ($date != '') ? ($date >= $date_from && $date <= $date_to) :  false;
    });
}


        // Total records after filtering
        $totalFilteredRecords = count($combinedData);

        // If no data found, return a 404 error
        if (empty($combinedData)) {
            http_response_code(200); // Not Found
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

         // Define the mapping for meeting_status and final_status
        // Define mappings for meeting_status and final_status
$meetingStatusMapping = [
    1 => "Requested",
    2 => "Approved",
    5 => "Exit",
    4 => "Not opened",
    3 => "Rejected"
];
$finalStatusMapping = [    
    0 => "Nothing",
    6 => "Run away" 
];

// Adjust `status` in combined data based on conditions
foreach ($combinedData as &$record) {
    // Check if this record is from `$headerData` or `$tempData`
    if (isset($record["status"])) {
        // This record is from `$headerData`, use `status` directly
        $record["status"] = $meetingStatusMapping[$record["status"]] ?? $record["status"];
    } elseif (isset($record["meeting_status"])) {
        // This record is from `$tempData`
        if (isset($record["final_status"]) && $record["final_status"] == 6) {
            // If final_status is 6, use it for `status`
            $record["status"] = $finalStatusMapping[$record["final_status"]] ?? $record["final_status"];
        } else {
            // Otherwise, use `meeting_status` for `status`
            $record["status"] = $meetingStatusMapping[$record["meeting_status"]] ?? $record["meeting_status"];
        }
    } else {
        // Default if neither `status` nor `meeting_status` exists
        $record["status"] = "unknown";
    }

    // Optionally, remove `meeting_status` and `final_status` if not needed in the final output
    unset($record["meeting_status"], $record["final_status"]);
}

// Paginate the modified combined data
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
