<?php
// Include the connection file
require_once 'connection.php';

// Get the database connection
$connection = connection::getInstance()->getConnection();

// Set the header for JSON response
header('Content-Type: application/json');

// Check the request method
$requestMethod = $_SERVER['REQUEST_METHOD'];

if ($requestMethod === 'GET') {
    // Get parameters from the request
    $agency_id = isset($_GET['agency_id']) ? $_GET['agency_id'] : null;
    $location_id = isset($_GET['visitor_location_id']) ? $_GET['visitor_location_id'] : null;

    // Default to current month and year if not provided
    $month = isset($_GET['month']) ? $_GET['month'] : date('m');
    $year = isset($_GET['year']) ? $_GET['year'] : date('Y');

    // Validate month and year
    if (!checkdate($month, 1, $year)) {
        echo json_encode([
            "error_code" => 400,
            "message" => "Invalid month or year. Please provide valid month (1-12) and year."
        ]);
        exit;
    }

    // Set the date range for the given month and year
    $date_from = "$year-$month-01"; // First day of the month
    $date_to = date("Y-m-t", strtotime($date_from)); // Last day of the month

    // Fetch location settings from visitor_location_setting_details_all table
    $locationQuery = "
        SELECT weekoffs, visiting_charges
        FROM visitor_location_setting_details_all
        WHERE visitor_location_id = ? AND agency_id = ? LIMIT 1
    ";
    $stmt = mysqli_prepare($connection, $locationQuery);
    mysqli_stmt_bind_param($stmt, "ss", $location_id, $agency_id);
    mysqli_stmt_execute($stmt);
    $locationResult = mysqli_stmt_get_result($stmt);
    $locationSettings = mysqli_fetch_assoc($locationResult);

    if (!$locationSettings) {
        echo json_encode([
            "error_code" => 404,
            "message" => "No location settings found for the given visitor location ID and agency ID."
        ]);
        exit;
    }

    // Parse the weekoffs and visiting charges
    $weekoffs = explode(',', $locationSettings['weekoffs']); // CSV of weekoff days (1=sunday, 2=monday, etc.)
    $visiting_charges = $locationSettings['visiting_charges'];

    // Prepare the SQL query to fetch requested visitor data from visitor_temp_activity_detail_all
    $visitorQuery = "
        SELECT DATE(requested_on) as visit_date, 
               DAY(requested_on) as day, 
               DAYNAME(requested_on) as day_of_week, 
               COUNT(*) as visitor_count
        FROM visitor_temp_activity_detail_all
        WHERE agency_id = ? AND visitor_location_id = ? 
        AND DATE(requested_on) BETWEEN ? AND ?
        GROUP BY DATE(requested_on)
    ";
    $stmt = mysqli_prepare($connection, $visitorQuery);
    mysqli_stmt_bind_param($stmt, "ssss", $agency_id, $location_id, $date_from, $date_to);
    mysqli_stmt_execute($stmt);
    $visitorResult = mysqli_stmt_get_result($stmt);

    // Fetch all visitor data
    $visitorData = [];
    $requestedVisitors = 0;

    while ($row = mysqli_fetch_assoc($visitorResult)) {
        // Check if the day is a weekend
        $is_weekend = in_array(date('N', strtotime($row['visit_date'])), $weekoffs) ? 'yes' : 'no';

        // Add weekend status and visitor count
        $row['week_off'] = $is_weekend;
        $row['no_of_visitor'] = $row['visitor_count'];
        unset($row['visitor_count']); // Remove visitor_count as it's renamed

        $visitorData[] = $row;
        $requestedVisitors += $row['no_of_visitor'];
    }

    // Fetch the count of verified visitors from visitor_header_all
    $headerQuery = "
        SELECT 
            COUNT(*) as total_visitor_verified
        FROM visitor_header_all
        WHERE agency_id = ? AND visitor_location_id = ?
        AND YEAR(inserted_on) = ? AND MONTH(inserted_on) = ?
    ";
    $stmt = mysqli_prepare($connection, $headerQuery);
    mysqli_stmt_bind_param($stmt, "ssii", $agency_id, $location_id, $year, $month);
    mysqli_stmt_execute($stmt);
    $headerResult = mysqli_stmt_get_result($stmt);
    $headerData = mysqli_fetch_assoc($headerResult);

    // Total visitors is the sum of verified visitors (from header) and requested visitors (from temp activity)
    $totalVisitors = $requestedVisitors + $headerData['total_visitor_verified'];
// Calculate the total visiting amount
$totalVisitingAmount = $totalVisitors * $visiting_charges;

// Prepare the final response
$response = [
    "error_code" => 100,
    "message" => "Details successfully fetched",
    "data" => $visitorData, // Requested visitor data
    "visitor_count_data" => [
        "total_visitors" => (string)$totalVisitors,
        "total_visitor_requested" => (string)$requestedVisitors,
        "total_visitor_verified" => (string)$headerData['total_visitor_verified'],
        "location_visiting_amount" => number_format((float)$visiting_charges, 2, '.', ''), // Show the visiting charge formatted
        "total_visiting_amount" => number_format((float)$totalVisitingAmount, 2, '.', '') // Show total visiting amount formatted
    ]
];


    // Return the data in JSON format
    echo json_encode($response);
} else {
    // Invalid request method
    http_response_code(405);
    echo json_encode([
        "error_code" => 405,
        "message" => "Method not allowed. Please use GET request."
    ]);
}
?>
