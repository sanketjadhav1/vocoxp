<?PHP
use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require_once __DIR__ . '/vendor/autoload.php';  // Load PhpSpreadsheet

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
error_reporting(1);
error_reporting(E_ALL & ~E_DEPRECATED);
header('Content-Type: application/json; charset=UTF-8');
header('Access-Contorl-Allow-Origin: *');
date_default_timezone_set('Asia/kolkata');
include 'connection.php';
$connection = connection::getInstance();
$mysqli = $connection->getConnection();

$connection1 = database::getInstance();
$mysqli1 = $connection1->getConnection();

apponoff($mysqli);  
logout($_SERVER['HTTP_AUTH_KEY'], $mysqli);
date_default_timezone_set('Asia/kolkata');
$system_date = date("y-m-d");
$system_date_time = date("Y-m-d H:i:s");

$agency_id = $_POST['agency_id'] ?? '';
$bulk_id = $_POST['bulk_id'] ?? '';

$mode_type = $_POST['mode_type'] ?? '';

$check_error_res = check_error($mysqli, $mysqli1, $agency_id,$mode_type);
if ($check_error_res == 1) 
{
	if($mode_type=="ambiguity_report")
	{
		 // Define the mapping of document_type to column names
            $document_columns = [
               "DVF-00001" => ['aadhar_ambiguity'],  // For Aadhar
               "DVF-00002" => ['pan_ambiguity'],     // For PAN
               "DVF-00004" => ['dl_ambiguity'],      // For Driving License
                "DVF-00005" => ['voter_ambiguity'],   // For Voter ID
               "DVF-00006" => ['passport_ambiguity'] // For Passport
            ];

            $data = []; // Array to hold the overall results

            // Initialize overall ambiguity counts for each document type
            $total_ambiguity = 0;
            $aadhar_ambiguity = 0;
            $pan_ambiguity = 0;
            $voter_ambiguity = 0;
            $dl_ambiguity = 0;
            $passport_ambiguity = 0;

            // Loop through each document type and perform the query
            foreach ($document_columns as $document_type => $columns) {
                // Dynamically create the column string to fetch
                $columns_to_fetch = implode(',', $columns);
                
                // Create the query for the current document type
                   $query = "SELECT $columns_to_fetch 
                          FROM `end_user_verification_transaction_all` 
                          WHERE document_type = '$document_type' 
                          AND weblink_id='$bulk_id' 
                          AND agency_id='$agency_id'";
                          
                $result = $mysqli->query($query);
                
                // Check if any rows were returned
                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        // Loop through each ambiguity column for this document type
                        foreach ($columns as $col) {
                            if (strpos($row[$col], "ok=all") === false && !empty($row[$col])) {
                                // Increment the corresponding ambiguity count based on document type
                                switch ($document_type) {
                                        case "DVF-00001":
                                            $aadhar_ambiguity++;
                                            break;
                                        case "DVF-00002":
                                             $pan_ambiguity++;
                                            break;
                                        case "DVF-00004":
                                              $dl_ambiguity++;
                                            break;
                                        case "DVF-00005":
                                              $voter_ambiguity++;
                                            break;
                                        case "DVF-00006":
                                            $passport_ambiguity++;
                                            break;
                                        default:
                                            // Handle unknown document types if necessary
                                            // For example: log an error or throw an exception
                                            break;
                                    }
                        $total_ambiguity++; // Increment total ambiguity count
                            }
                            else
                            {
                               
                                
                            } 
                        }
                    }
                }
            }

            // Store the results in the required format
            $data[] = [ 
                "total_ambiguity" => $total_ambiguity,
                "aadhar_ambiguity" => $aadhar_ambiguity,
                "pan_ambiguity" => $pan_ambiguity,
                "voter_ambiguity" => $voter_ambiguity,
                "dl_ambiguity" => $dl_ambiguity,
                "passport_ambiguity" => $passport_ambiguity
            ];

            // Output the results as JSON
            $response = [
                "error_code" => 100,
                "message" => "Ambiguity counts successfully fetched",
                "total_count" => $data
            ];

            echo json_encode($response);


	}
	elseif($mode_type=="document_type_report")
	{
                            
                // Assuming you have already established a connection to the database in $mysqli

                // Retrieve the requested document types from the POST request
                $requested_document_types = isset($_POST['document_types']) ? $_POST['document_types'] : []; // e.g., ["DVF-00001", "DVF-00002"]
              

                // Check if the required POST data is available
                if (!empty($requested_document_types) && is_array($requested_document_types) ) {
                    // Define the mapping of document_type to column names
                    $document_columns = [
                        "DVF-00001" => ['aadhar_ambiguity'],  // For Aadhar
                        "DVF-00002" => ['pan_ambiguity'],     // For PAN
                        "DVF-00004" => ['dl_ambiguity'],      // For Driving License
                        "DVF-00005" => ['voter_ambiguity'],   // For Voter ID
                        "DVF-00006" => ['passport_ambiguity']  // For Passport
                    ];

                    $data = []; // Array to hold the overall results

                    // Fetch data from bulk_end_user_transaction_all using end_user_id and bulk_id
                    $user_query = "SELECT * FROM `bulk_end_user_transaction_all` WHERE `bulk_id` = ?";
                    if ($stmt = $mysqli->prepare($user_query)) {
                        $stmt->bind_param("s", $bulk_id);
                        $stmt->execute();
                        $user_fetch_res = $stmt->get_result();
                       while($user_fetch_array = $user_fetch_res->fetch_assoc()){

                        // Initialize a variable to hold user data
                        $user_data = [
                            "Id" => $user_fetch_array["end_user_id"],
                            "name" => $user_fetch_array["name"] ?? 'Unknown', // Default to 'Unknown' if name not found
                            "type" => $user_fetch_array["obj_name"] ?? 'Unknown',  // Default to 'Unknown' if role not found
                            "ambiguity_found_in" => [],
                            "Aadhar_ambiguity" => "",
                            "pan_ambiguity" => "",
                            "dl_ambiguity" => "",
                            "voter_ambiguity" => ""
                        ];

                        // Loop through each requested document type
                        foreach ($requested_document_types as $requested_document_type) {
                            // Check if the requested document type is valid
                            if (array_key_exists($requested_document_type, $document_columns)) {
                                $columns = $document_columns[$requested_document_type];
                                $columns_to_fetch = implode(',', $columns);

                                // Create the query for the current document type
                                $query = "SELECT $columns_to_fetch 
                                          FROM `end_user_verification_transaction_all` 
                                          WHERE document_type = ? 
                                          AND end_user_id = ? 
                                          AND weblink_id = ? 
                                          AND agency_id = ?";

                                // Prepare the statement to prevent SQL injection
                                if ($stmt = $mysqli->prepare($query)) {
                                    $stmt->bind_param("ssss", $requested_document_type, $user_fetch_array["end_user_id"], $bulk_id, $agency_id);
                                    $stmt->execute();
                                    $result = $stmt->get_result();

                                    // Check if any rows were returned
                                    if ($result && $result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            // Loop through each ambiguity column for this document type
                                            foreach ($columns as $col) {
                                                if (strpos($row[$col], "ok=all") === false && !empty($row[$col])) {
                                                    // Store the ambiguity data in the user_data array
                                                    $user_data[$col] = $row[$col];
                                                    
                                                    // Update ambiguity found in
                                                    switch ($requested_document_type) {
                                                        case "DVF-00001":
                                                            $user_data["Aadhar_ambiguity"] = $row[$col];
                                                            $user_data["ambiguity_found_in"][] = "Aadhar";
                                                            break;
                                                        case "DVF-00002":
                                                            $user_data["pan_ambiguity"] = $row[$col];
                                                            $user_data["ambiguity_found_in"][] = "Pan";
                                                            break;
                                                        case "DVF-00004":
                                                            $user_data["dl_ambiguity"] = $row[$col];
                                                            $user_data["ambiguity_found_in"][] = "Dl";
                                                            break;
                                                        case "DVF-00005":
                                                            $user_data["voter_ambiguity"] = $row[$col];
                                                            $user_data["ambiguity_found_in"][] = "Voter";
                                                            break;
                                                        // Add cases for other document types as needed
                                                    }
                                                }
                                            }
                                        }
                                    }

                                    $stmt->close(); // Close the prepared statement
                                }
                            } else {
                                // Handle the case where the document type is invalid
                                echo json_encode([
                                    "error_code" => 101,
                                    "message" => "Invalid document type: $requested_document_type."
                                ]);
                                exit; // Stop processing further if any type is invalid
                            }
                        }

                        // Trim trailing comma from ambiguity_found_in
                        $user_data["ambiguity_found_in"] = implode(", ", array_unique($user_data["ambiguity_found_in"]));
                        if($user_data["ambiguity_found_in"]!="")
                        {
                             $data[] = $user_data;
                        }
                        // Store the final results in the required format
                       
}
                        // Output the results as JSON
                        $response = [
                            "error_code" => 100,
                            "message" => "data fetch successfully",
                            "data" => $data
                        ];

                        echo json_encode($response);
                    } else {
                        // Handle error in user query
                        echo json_encode([
                            "error_code" => 102,
                            "message" => "Failed to retrieve user data."
                        ]);
                    }
                } else {
                    // Handle the case where required data is not provided
                    echo json_encode([
                        "error_code" => 101,
                        "message" => "Document types and end user ID are required."
                    ]);
                }

                  
	}
    elseif($mode_type=="export_ambiguity_report")
    {
             $email_id = $_POST['email_id'] ?? ""; 
            $ids = explode(",", $email_id);
                $total_ambiguity = 0;
                $aadhar_ambiguity = 0;
                $pan_ambiguity = 0;
                $voter_ambiguity = 0;
                $dl_ambiguity = 0;
                $passport_ambiguity = 0;

               // Retrieve the requested document types from POST request
                $requested_document_types = isset($_POST['document_types']) ? $_POST['document_types'] : [];

                if (!empty($requested_document_types) && is_array($requested_document_types)) {
                        // Assuming $mysqli is your MySQL connection object

                        // Fetch all verification IDs
                        $verification_query = "SELECT verification_id FROM verification_header_all";
                        $verification_result = $mysqli1->query($verification_query);

                        // Prepare an array to store fetched data
                        $document_columns = [];
                        if ($verification_result->num_rows > 0) {
                            while ($verification_row = $verification_result->fetch_assoc()) {
                                $verification_id = $verification_row['verification_id'];
                                $document_columns[$verification_id] = [
                                    "aadhar" => "aadhar_ambiguity",
                                    "pan" => "pan_ambiguity",
                                    "dl" => "dl_ambiguity",
                                    "voter" => "voter_ambiguity",
                                    "passport" => "passport_ambiguity"
                                ];
                            }
                        }

                        // Initialize data structures
                        $data = [];
                        $user_ambiguities = []; 

                        // Fetch user transactions
                        $user_query = "SELECT * FROM `bulk_end_user_transaction_all` WHERE `bulk_id` = '$bulk_id'";
                        $user_fetch_res = $mysqli->query($user_query);

                        while ($user_fetch_row = $user_fetch_res->fetch_assoc()) {
                            $array = $user_fetch_row["ref_enduser_id"] . "," . $user_fetch_row["end_user_id"];
                            $values_array = array_map('trim', explode(",", $array));

                            foreach ($values_array as $enduser_id_value) {
                                $enduser_id_value = $mysqli->real_escape_string($enduser_id_value);

                                $end_user_main1 = "SELECT * FROM bulk_end_user_transaction_all 
                                                   WHERE end_user_id = '$enduser_id_value' AND bulk_id = '$bulk_id'";
                                $end_user_main1_result = $mysqli->query($end_user_main1);

                                if ($end_user_main1_result->num_rows > 0) {
                                    $end_user_main_array1 = $end_user_main1_result->fetch_assoc();

                                    // Fetch verification transactions
                                    $ver_user_query = "SELECT * FROM `end_user_verification_transaction_all` 
                                                       WHERE `weblink_id` = '$bulk_id' AND end_user_id = '$enduser_id_value'";
                                    $ver_user_fetch_res = $mysqli->query($ver_user_query);

                                    if ($ver_user_fetch_res->num_rows > 0) {
                                        $ver_user_main_array = $ver_user_fetch_res->fetch_assoc();

                                        $obj_name = $end_user_main_array1["obj_name"];
                                        $uname = $end_user_main_array1["name"];

                                        // Loop through requested document types
                                        foreach ($requested_document_types as $requested_document_type) {
                                            if (isset($document_columns[$requested_document_type])) {
                                                $columns = $document_columns[$requested_document_type];
                                                $columns_to_fetch = implode(',', array_values($columns));

                                                $end_user_id = $user_fetch_row["end_user_id"];
                                                $query = "SELECT $columns_to_fetch 
                                                          FROM end_user_verification_transaction_all 
                                                          WHERE document_type = '$requested_document_type' 
                                                          AND weblink_id = '$bulk_id' 
                                                          AND agency_id = '$agency_id'
                                                          AND end_user_id = '$end_user_id'";

                                                $result = $mysqli->query($query);

                                                if ($result && $result->num_rows > 0) {
                                                    $user_data = [
                                                        "obj_name" => $obj_name,
                                                        "name" => $uname,
                                                        "aadhar_ambiguity" => 0,
                                                        "pan_ambiguity" => 0,
                                                        "dl_ambiguity" => 0,
                                                        "voter_ambiguity" => 0,
                                                        "passport_ambiguity" => 0,
                                                        "ambiguity_found_in" => []
                                                    ];

                                                    $found_ambiguity_for_type = false;

                                                    while ($row = $result->fetch_assoc()) {
                                                        foreach ($columns as $col_key => $col_value) {
                                                            $ambiguity_key = $end_user_id . '_' . $col_key;

                                                            if (
                                                                strpos($row[$col_value], "ok=all") === false &&
                                                                !empty($row[$col_value]) &&
                                                                !isset($user_ambiguities[$ambiguity_key])
                                                            ) {
                                                                // Track ambiguity details
                                                                $user_data[$col_key . "_ambiguity"] = $row[$col_value];
                                                                $user_data["ambiguity_found_in"][] = ucfirst($col_key);
                                                                $user_ambiguities[$ambiguity_key] = true;

                                                                // Increment counts
                                                                switch ($col_key) {
                                                                    case "aadhar":
                                                                        $aadhar_ambiguity++;
                                                                        break;
                                                                    case "pan":
                                                                        $pan_ambiguity++;
                                                                        break;
                                                                    case "dl":
                                                                        $dl_ambiguity++;
                                                                        break;
                                                                    case "voter":
                                                                        $voter_ambiguity++;
                                                                        break;
                                                                    case "passport":
                                                                        $passport_ambiguity++;
                                                                        break;
                                                                }
                                                                $found_ambiguity_for_type = true;
                                                            }
                                                        }
                                                    }

                                                    // Append user data if ambiguities were found
                                                    if ($found_ambiguity_for_type) {
                                                        $total_ambiguity++;
                                                        $data[] = $user_data;
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }

                        // Output JSON response
                        // echo json_encode([
                        //     "error_code" => 100,
                        //     "data" => $data,
                        //     "total_ambiguity" => $total_ambiguity,
                        //     "aadhar_ambiguity" => $aadhar_ambiguity,
                        //     "pan_ambiguity" => $pan_ambiguity,
                        //     "dl_ambiguity" => $dl_ambiguity,
                        //     "voter_ambiguity" => $voter_ambiguity,
                        //     "passport_ambiguity" => $passport_ambiguity,
                        // ]);
                    }
                 $fetch_req_all = "SELECT * FROM bulk_weblink_request_all WHERE `bulk_id` = ?";
                        if ($stmt = $mysqli->prepare($fetch_req_all)) {
                            $stmt->bind_param("s", $bulk_id);
                            $stmt->execute();
                            $res_req_all = $stmt->get_result();
                            $arr_req_all = $res_req_all->fetch_assoc();
                            
                        }
                        $excel_no = $arr_req_all['excel_no']; // Replace with the desired excel_no value

                    // Ensure the value is properly escaped to prevent SQL injection
                    $excel_no = mysqli_real_escape_string($mysqli, $excel_no);

                    // SQL query to fetch based on excel_no
                    $sql = "SELECT `id`, `excel_name`, `excel_no`, `excel_url`, `user_validation_url`, `type`, 
                            `stake_holder`, `excel_verification_rules_1`, `excel_verification_rules_2`, 
                            `excel_verification_rules_3`, `excel_verification_rules_4`, `excel_verification_rules_5`,
                            `excel_verification_rules_6`, `excel_verification_rules_7`, `excel_verification_rules_8`, 
                            `excel_verification_rules_9`, `excel_verification_rules_10`, `obj_1`, `obj_2`, `obj_3`, 
                            `type_for_excel` 
                            FROM `sample_excel_definations_all` 
                            WHERE `excel_no` = '$excel_no'"; // WHERE condition for excel_no

                    // Execute the query
                    $result = mysqli_query($mysqli, $sql);

                    // Check if any rows are returned
                    if ($result && mysqli_num_rows($result) > 0) {
                        // Process the results
                        while ($row = mysqli_fetch_assoc($result)) {
                            // Access each row's data
                          $verification_type= $row['excel_name'];
                            // Continue echoing other columns as needed
                        }
                    } else {
                        $verification_type="";
                    }
               $fetch_agency = "SELECT `company_name`, `address` FROM `agency_header_all` WHERE `agency_id` = ?";
                if ($stmt = $mysqli->prepare($fetch_agency)) {
                    $stmt->bind_param("s", $agency_id);
                    $stmt->execute();
                    $res_agency = $stmt->get_result();
                    $arr_agency = $res_agency->fetch_assoc();

                }
                  // Generate the Excel file
                $spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();
                $sheet->setTitle('Summary');  // Set the sheet name here

                // Header information (Agency, Location, etc.)
                $sheet->setCellValue('I2', 'Agency Name:');
                $sheet->setCellValue('I3', 'Location:');
                $sheet->setCellValue('I4', 'Weblink Generated on:');
                $sheet->setCellValue('I5', 'Total Ambiguity Found:');

                // Set the actual values in a different column
                $sheet->setCellValue('J2', $arr_agency['company_name']);
                $sheet->setCellValue('J3', $arr_req_all["premises_location"]);
                $sheet->setCellValue('J4', date('d-m-Y', strtotime($arr_req_all["upload_weblink_generated_on"])));
                $sheet->setCellValue('J5', $total_ambiguity);

                // Set the style for these header cells (Bold)
                $sheet->getStyle('I2:I5')->getFont()->setBold(true);

                
                    // Object-wise ambiguity counts (e.g., Student, Father, Mother)
                    $obj_names = ['student', 'father', 'mother']; // Add more objects if necessary
                    $verification_types = ['aadhar', 'pan', 'dl', 'voter', 'passport']; // Common verification types

                    $row_num = 9; // Start from the 9th row for content

                    // Loop through each object type
                    foreach ($obj_names as $obj_name) {
                        // Initialize counts for each verification type
                        $object_ambiguities = array_fill_keys($verification_types, 0);

                        // Loop through the data and calculate ambiguity counts for this object
                        $found_ambiguities = false; // Track if ambiguities are found for this object

                        foreach ($data as $row) {
                            if (isset($row['obj_name']) && strtolower($row['obj_name']) == strtolower($obj_name)) {
                                foreach ($verification_types as $type) {
                                    $ambiguity_key = $type . '_ambiguity';
                                    if (!empty($row[$ambiguity_key])) {
                                        $object_ambiguities[$type]++;
                                        $found_ambiguities = true; // Mark that ambiguities are found
                                    }
                                }
                            }
                        }

                        // Skip if no ambiguities found for this object
                        if (!$found_ambiguities) {
                            continue;
                        }

                        // Display the object name
                        $sheet->setCellValue("B{$row_num}", ucfirst($obj_name)); // Capitalize first letter
                        $sheet->getStyle("B{$row_num}")->getFont()->setBold(true);
                        $row_num++; // Move to the next row

                        // Column headers for Verifications and Ambiguities
                        $sheet->setCellValue("B{$row_num}", "Verification Type");
                        $sheet->setCellValue("D{$row_num}", "No of Ambiguities Found");
                        $sheet->getStyle("B{$row_num}:D{$row_num}")->getFont()->setBold(true);
                        $row_num++;

                        // Write ambiguity counts to the sheet
                        foreach ($object_ambiguities as $type => $count) {
                            if ($count > 0) { // Only print rows with non-zero counts
                                $verification_name = ucfirst($type); // Capitalize the first letter for better display
                                $sheet->setCellValue("B{$row_num}", $verification_name);
                                $sheet->setCellValue("D{$row_num}", $count);
                                $row_num++;
                            }
                        }

                        $row_num++; // Add an empty row before the next object type
                    }



                    // Final total row for ambiguities
                    // $row_num++; // Leave one row for spacing
                    // $sheet->setCellValue("B{$row_num}", "Total Ambiguities");
                    // $sheet->setCellValue("D{$row_num}", $total_ambiguity);
                    // $sheet->getStyle("B{$row_num}:D{$row_num}")->getFont()->setBold(true);

                    // Save or output the file



                // ====== Second Sheet: Details ======
                $detailsSheet = $spreadsheet->createSheet();
                $detailsSheet->setTitle('Details');

                // Header information
                $detailsSheet->setCellValue('I2', 'Agency Name:');
                $detailsSheet->setCellValue('I3', 'Location:');
                $detailsSheet->setCellValue('I4', 'Weblink Generated on:');
                $detailsSheet->setCellValue('I5', 'Total Ambiguity Found:');

                // Set the actual values in a different column
                $detailsSheet->setCellValue('J2', $arr_agency['company_name']);
                $detailsSheet->setCellValue('J3', $arr_req_all["premises_location"]);
                $detailsSheet->setCellValue('J4', date('d-m-Y', strtotime($arr_req_all["upload_weblink_generated_on"])));
                $detailsSheet->setCellValue('J5', $total_ambiguity);

                // Set the style for these header cells (Bold)
                $detailsSheet->getStyle('I2:I5')->getFont()->setBold(true);

                // Header Row for Table
                $detailsSheet->setCellValue('A7', 'Sr. No.');
                $detailsSheet->setCellValue('B7', 'Prime Object Name');
                $detailsSheet->setCellValue('C7', 'Contact Details');
                $detailsSheet->setCellValue('D7', 'Ambiguity Found in (With Details)'); 

                // Apply bold formatting to the header row
                $detailsSheet->getStyle('A7:D7')->getFont()->setBold(true);
                // Set the starting row
                $row_num = 8;

                // Define columns for each object type
                $columns = [
                    'student' => ['B', 'C', 'D'], // Columns for Student
                    'father' => ['F', 'G', 'H'], // Columns for Father
                    'mother' => ['J', 'K', 'L'], // Columns for Mother
                ];

                // Fixed widths for columns
                $fixedWidths = [
                    'B' => 15, 'C' => 20, 'D' => 50, // Student columns
                    'F' => 15, 'G' => 20, 'H' => 50, // Father columns
                    'J' => 15, 'K' => 20, 'L' => 50, // Mother columns
                ];

                // Set fixed column widths and enable text wrapping
                foreach ($fixedWidths as $col => $width) {
                    $detailsSheet->getColumnDimension($col)->setWidth($width);
                    $detailsSheet->getStyle($col)->getAlignment()->setWrapText(true);
                }

                // Example Headers
                $detailsSheet->setCellValue("B7", "Prime Object Name");
                $detailsSheet->setCellValue("C7", "Contact Details");
                $detailsSheet->setCellValue("D7", "Ambiguity Found in (with Details)");

                $detailsSheet->setCellValue("F7", "First Link Object Name");
                $detailsSheet->setCellValue("G7", "Contact Details");
                $detailsSheet->setCellValue("H7", "Ambiguity Found in (with Details)");

                $detailsSheet->setCellValue("J7", "Second Link Object Name");
                $detailsSheet->setCellValue("K7", "Contact Details");
                $detailsSheet->setCellValue("L7", "Ambiguity Found in (with Details)");

                // Enable text wrapping for headers
                $detailsSheet->getStyle("B7:L7")->getAlignment()->setWrapText(true);

                // Styling Headers (Optional)
                $detailsSheet->getStyle("B7:L7")->getFont()->setBold(true);

                // Loop through the data
                foreach ($data as $row) {
                    // Determine the object category (e.g., student, father, mother)
                    $obj_name = strtolower($row['obj_name'] ?? '');

                    // If no data exists for the current object type, skip it
                    if (!isset($columns[$obj_name])) {
                        continue; // Skip to the next row if no valid object type
                    }

                    // Get the column positions for the current object type
                    [$colName, $colMobile, $colAmbiguity] = $columns[$obj_name];

                    // Only write data if the object type matches the current row's data
                    $detailsSheet->setCellValue("{$colName}{$row_num}", ucfirst($row['name'] ?? 'N/A')); // Name
                    $detailsSheet->setCellValue("{$colMobile}{$row_num}", $row['mobile'] ?? ''); // Mobile

                    // Collect ambiguity details
                    $ambiguityDetails = [];
                    if (!empty($row['ambiguity_found_in'])) {
                        foreach ($row['ambiguity_found_in'] as $ambiguity) {
                            $ambiguityKey = strtolower($ambiguity) . '_ambiguity';
                            if (!empty($row[$ambiguityKey])) {
                                $ambiguityDetails[] = ucfirst($ambiguity) . " Ambiguity: " . $row[$ambiguityKey];
                            }
                        }
                    }

                    // Add ambiguity details to the respective column
                    $detailsSheet->setCellValue("{$colAmbiguity}{$row_num}", implode("; ", $ambiguityDetails));

                    $row_num++; // Move to the next row
                }

                // End of script




                // Styling Headers (Optional)
                $detailsSheet->getStyle("B7:L7")->getFont()->setBold(true);


                 // Save the Excel file to the output buffer
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                $datetime = date('YmdHis');  // Generates the current date and time in the format "YYYY-MM-DD_HH-MM-SS"
                $filename = "ambiguity_report_{$datetime}.xlsx";
                header("Content-Disposition: attachment;filename=\"$filename\"");
                header('Cache-Control: max-age=0');

                $ftpDetails = [
                    'server' => '199.79.62.21',
                    'username' => 'centralwp@mounarchtech.com',
                    'password' => 'k=Y#oBK{h}OU'
                ];

               // Ensure the resultMessage is a valid file path
                $resultMessage = saveSpreadsheetToFtp($spreadsheet, $ftpDetails, $filename, $agency_id, $bulk_id);
                $filepath = str_replace(" ", "", $resultMessage); // Remove any spaces

                // Step 1: Download the file locally
                $localFilePath = '/tmp/' . basename($filepath); // Define a temporary local path
                if (!file_put_contents($localFilePath, file_get_contents($filepath))) {
                    echo json_encode([
                        "error_code" => 101,
                        "message" => "Failed to download the file from the URL: $filepath"
                    ]);
                    exit;
                }

                // Step 2: Send email with PHPMailer
                $mail = new PHPMailer(true);

                try {
                    // Configure PHPMailer for SMTP
                    $mail->isSMTP();
                    $mail->Host = 'mail.mounarchtech.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'transactions@mounarchtech.com';
                    $mail->Password = 'Mtech!@12345678';
                    $mail->SMTPSecure = 'ssl';
                    $mail->Port = 465;

                    // Set sender details
                    $mail->setFrom('transactions@mounarchtech.com', 'Micro Integrated Semi Conductor Systems Pvt. Ltd.');
                    $mail->isHTML(true);

                    // Email content
                    $mail->Subject = 'Ambiguity Report';
                    $mail->Body = '
                        <!DOCTYPE html>
                        <html>
                        <head>
                            <style>
                                .email-body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                                .email-header { background-color: #f0f0f0; padding: 20px; text-align: center; }
                                .email-content { padding: 20px; }
                                .button { display: inline-block; padding: 10px 20px; margin: 20px 0; font-size: 16px; color: white; background-color: #007BFF; text-decoration: none; border-radius: 5px; }
                                .email-footer { padding: 20px; text-align: center; font-size: 12px; color: #777; }
                            </style>
                        </head>
                        <body>
                            <div class="email-body">
                                <div class="email-content">
                                    <p>Dear Recipient,</p>
                                    <p>This is to inform you that we have identified some ambiguities in the verification data for your recent weblink submission for '.$verification_type.'.</p>
                                    <p>Please find the attached detailed report for your reference. We kindly request you to review the report and address the noted issues at your earliest convenience.</p>
                                    <p>If you have any questions or require assistance, please reach out to <a href="mailto:support@microintegrated.in">support@microintegrated.in</a>.</p> 
                                    <p>Thank you for your cooperation.</p>
                                    <p><b>Best regards,</b></p>
                                    <p><b>Micro Integrated Semi Conductor Systems Pvt. Ltd.</b></p>
                                </div>
                            </div>
                        </body>
                        </html>';

                    // Attach the downloaded local file
                    $attachmentName = basename($localFilePath); // Extract file name from path
                    $mail->addAttachment($localFilePath, $attachmentName);

                    // Send email to all recipients
                    foreach ($ids as $email) {
                        $trimmedEmail = trim($email); // Trim whitespace from email
                        if (!empty($trimmedEmail) && filter_var($trimmedEmail, FILTER_VALIDATE_EMAIL)) {
                            $mail->addAddress($trimmedEmail);
                            $mail->send();
                            $mail->clearAddresses(); // Clear recipients for next iteration
                        }
                    }

                    // Success response
                    echo json_encode([
                        "error_code" => 100,
                        "message" => "Mail sent to provided email IDs. Please check your inbox."
                    ]);
                } catch (Exception $e) {
                    // Error response
                    echo json_encode([
                        "error_code" => 101,
                        "message" => "Mail could not be sent. Mailer Error: {$mail->ErrorInfo}"
                    ]);
                } finally {
                    // Clean up: Delete the downloaded local file
                    if (file_exists($localFilePath)) {
                        unlink($localFilePath);
                    }
                }


                // echo json_encode([
                //     "error_code" => 100,
                //     "message" => $resultMessage
                // ]);
                // exit;

    }
}


function check_error($mysqli, $mysqli1,   $agency_id,$mode_type)
{

    $check_error = 1;
    if (!$mysqli) {
        $response = ["error_code" => 101, "message" => "Unable to proceed your request, please try again after some time"];
        echo json_encode($response);
        return;
    }

    if ($_SERVER['REQUEST_METHOD'] != "POST") {
        $response = array("error_code" => 102, "message" => "Request Method is not Post");
        echo json_encode($response);
        return;
    }

 

     

    if (!isset($agency_id)) {
        $response = ["error_code" => 105, "message" => "Please pass the parameter of agency_id"];
        echo json_encode($response);
        return;
    }
    if (empty($agency_id)) {
        $response = ["error_code" => 106, "message" => "agency_id can not be empty"];
        echo json_encode($response);
        return;
    }

    if (!isset($mode_type)) {
        $response = ["error_code" => 105, "message" => "Please pass the parameter of mode_type"];
        echo json_encode($response);
        return;
    }
    if (empty($mode_type)) {
        $response = ["error_code" => 106, "message" => "mode_type can not be empty"];
        echo json_encode($response);
        return;
    }

 

    return $check_error;
}
 function saveSpreadsheetToFtp($spreadsheet, $ftpDetails, $filename, $agency_id,$bulk_id) {
    // Generate a local temporary file
    $temp_file = tempnam(sys_get_temp_dir(), 'spreadsheet');
    $writer = new Xlsx($spreadsheet);
    
    try {
        // Save the spreadsheet to the temporary file
        $writer->save($temp_file);
        
        // Connect to the FTP server
        $ftp_server = $ftpDetails['server'];
        $ftp_username = $ftpDetails['username'];
        $ftp_password = $ftpDetails['password'];
        $ftp_connection = ftp_connect($ftp_server);
        
        if (!$ftp_connection) {
            return "Failed to connect to FTP server.";
        }

        // Log in to the FTP server
        $login_result = ftp_login($ftp_connection, $ftp_username, $ftp_password);
        if (!$login_result) {
            return "Failed to login to FTP server.";
        }

        // Specify the path on the FTP server to upload the file
        $ftp_directory ="/verification_data/voco_xp/{$agency_id}/weblink/{$bulk_id}/ambiguity_report/";
        
        // Ensure the directory exists on the FTP server
        ftp_mkdir_recursive($ftp_connection, $ftp_directory);
        
        // Full path to save the file on FTP server
        $remote_file = $ftp_directory . $filename;

        // Upload the file to the FTP server
        if (ftp_put($ftp_connection, $remote_file, $temp_file, FTP_BINARY)) {
            ftp_close($ftp_connection);  // Close FTP connection
            return "https://mounarchtech.com/central_wp{$remote_file}";
        } else {
            ftp_close($ftp_connection);  // Close FTP connection
            return "Failed to upload file.";
        }
    } catch (Exception $e) {
        return "Error saving or uploading the spreadsheet: " . $e->getMessage();
    } finally {
        // Delete the temporary file
        unlink($temp_file);
    }
}

// Recursive function to create directories on the FTP server
function ftp_mkdir_recursive($ftp_connection, $directory) {
    $parts = explode('/', $directory);
    $current_dir = '';
    
    foreach ($parts as $part) {
        if (!empty($part)) {
            $current_dir .= '/' . $part;
            // Check if directory exists, if not create it
            if (!@ftp_chdir($ftp_connection, $current_dir)) {
                @ftp_mkdir($ftp_connection, $current_dir);
                ftp_chdir($ftp_connection, $current_dir);
            }
        }
    }
}
?>