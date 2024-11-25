<?PHP
require_once __DIR__ . '/vendor/autoload.php';  // Load PhpSpreadsheet

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
error_reporting(1);
header('Content-Type: application/json; charset=UTF-8');
header('Access-Contorl-Allow-Origin: *');
date_default_timezone_set('Asia/kolkata');
include 'connection.php';
$connection = connection::getInstance();
$mysqli = $connection->getConnection();

$connection1 = database::getInstance();
$mysqli1 = $connection1->getConnection();

// apponoff($mysqli);  
// logout($_SERVER['HTTP_AUTH_KEY'], $mysqli);
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
        
        $total_ambiguity = 0;
        $aadhar_ambiguity = 0;
        $pan_ambiguity = 0;
        $voter_ambiguity = 0;
        $dl_ambiguity = 0;
        $passport_ambiguity = 0;

        // Retrieve the requested document types from POST request
        $requested_document_types = isset($_POST['document_types']) ? $_POST['document_types'] : [];

        if (!empty($requested_document_types) && is_array($requested_document_types)) {
            // Establish database connection
            // Assuming $mysqli is your mysqli connection object

            // Fetch all verification IDs
             $verification_query = "SELECT verification_id FROM verification_header_all";
            $verification_result = $mysqli1->query($verification_query);

            // Prepare an array to store fetched data
            $document_columns = [];

            if ($verification_result->num_rows > 0) {
                // Loop through each verification ID and store in the $document_columns array
                while ($verification_row = $verification_result->fetch_assoc()) {
                    $verification_id = $verification_row['verification_id'];
                    // Dynamically assign columns to each verification_id
                    $document_columns[$verification_id] = [
                        "aadhar" => "aadhar_ambiguity", // replace with actual column names
                        "pan" => "pan_ambiguity",
                        "dl" => "dl_ambiguity",
                        "voter" => "voter_ambiguity",
                        // Add more document types if needed
                    ];
                }
            }

            $data = [];

            // Fetch user data
             $user_query = "SELECT * FROM `bulk_end_user_transaction_all` WHERE `bulk_id` = '$bulk_id'";
            $user_fetch_res = $mysqli->query($user_query);
            
            if ($user_fetch_res && $user_fetch_res->num_rows > 0) {
                while ($user_fetch_array = $user_fetch_res->fetch_assoc()) {
                    $enduserid_array = $user_fetch_array["end_user_id"] . "," . $user_fetch_array["ref_enduser_id"];
                    $values_array = array_filter(array_map('trim', explode(",", $enduserid_array))); // Remove empty elements

                    // Initialize user data array
                    $user_data = [
                        "Id" => $user_fetch_array["end_user_id"],
                        "name" => $user_fetch_array["name"] ?? 'Unknown',
                        "type" => $user_fetch_array["obj_name"] ?? 'Unknown',
                        "mobile" => $user_fetch_array["mobile"] ?? 'Unknown',
                        "ambiguity_found_in" => [],
                        "aadhar_ambiguity" => "",
                        "pan_ambiguity" => "",
                        "dl_ambiguity" => "",
                        "voter_ambiguity" => ""
                    ];

                    // Loop through each requested document type
                    foreach ($requested_document_types as $requested_document_type) {
                        if (isset($document_columns[$requested_document_type])) {
                            $columns = $document_columns[$requested_document_type];
                            $columns_to_fetch = implode(',', $columns);

                            $endid = $user_fetch_array["end_user_id"];
                           echo$query = "SELECT $columns_to_fetch 
                                      FROM `end_user_verification_transaction_all` 
                                      WHERE document_type = '$document_columns[$requested_document_type]' 
                                      AND weblink_id='$bulk_id' 
                                      AND agency_id='$agency_id'
                                      AND end_user_id='$endid'";

                            $result = $mysqli->query($query);

                            if ($result && $result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    foreach ($columns as $col_key => $col_value) {
                                        if (strpos($row[$col_value], "ok=all") === false && !empty($row[$col_value])) {
                                            $user_data[$col_key . "_ambiguity"] = $row[$col_value];
                                            $user_data["ambiguity_found_in"][] = ucfirst($col_key);  // Add ambiguity type
                                            
                                            // Dynamically increment the ambiguity count
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
                                           
                                        }
                                    } 
                                    $total_ambiguity++;
                                }
                            }
                        }
                    }

                    // Final ambiguity counts for each user
                    $user_data["aadhar_ambiguity_count"] = $aadhar_ambiguity;
                    $user_data["pan_ambiguity_count"] = $pan_ambiguity;
                    $user_data["dl_ambiguity_count"] = $dl_ambiguity;
                    $user_data["voter_ambiguity_count"] = $voter_ambiguity;
                    $user_data["passport_ambiguity_count"] = $passport_ambiguity;
                    $user_data["ambiguity_found_in"] = implode(", ", array_unique($user_data["ambiguity_found_in"]));

                    $data[] = $user_data;  // Add each user's data to $data array
                }

                // Now you can generate your report or output $data as needed
               echo json_encode($data);
                 // Fetch agency details
                $fetch_agency = "SELECT `company_name`, `address` FROM `agency_header_all` WHERE `agency_id` = ?";
                if ($stmt = $mysqli->prepare($fetch_agency)) {
                    $stmt->bind_param("s", $agency_id);
                    $stmt->execute();
                    $res_agency = $stmt->get_result();
                    $arr_agency = $res_agency->fetch_assoc();
                }

                 // Fetch bulk request details
                $fetch_req_all = "SELECT * FROM bulk_weblink_request_all WHERE `bulk_id` = ?";
                if ($stmt = $mysqli->prepare($fetch_req_all)) {
                    $stmt->bind_param("s", $bulk_id);
                    $stmt->execute();
                    $res_req_all = $stmt->get_result();
                    $arr_req_all = $res_req_all->fetch_assoc();
                }

                 // Now, let's generate the Excel file
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

                // Populate data like in your screenshot
                $row_num = 9;  // Start from the 9th row for content
                $object_num = 1;
                foreach ($data as $row) {
                    // Object header
                    $sheet->setCellValue("B{$row_num}", $row['type'] );
                    $row_num++;

                    // Verification types and ambiguity values
                    $sheet->setCellValue("B" . ($row_num + 1), "Verifications");
                    $sheet->setCellValue("D" . ($row_num + 1), "No of Ambiguity found");

                    // Apply bold formatting to these verification type cells
                    $sheet->getStyle("B" . ($row_num + 1) . ":B" . ($row_num + 1))->getFont()->setBold(true);

                    // You can also bold specific data points, for example the values under "No of Ambiguity found"
                    $sheet->getStyle("D" . ($row_num + 1) . ":D" . ($row_num + 1))->getFont()->setBold(true);

                    // Aadhar
                    $sheet->setCellValue("B" . ($row_num + 2), "Aadhar");
                    $sheet->setCellValue("D" . ($row_num + 2), $row["aadhar_ambiguity_count"]);
                    // PAN
                    $sheet->setCellValue("B" . ($row_num + 3), "Pan");
                    $sheet->setCellValue("D" . ($row_num + 3), $row["pan_ambiguity_count"]);
                    // Voter
                    $sheet->setCellValue("B" . ($row_num + 4), "Voter");
                    $sheet->setCellValue("D" . ($row_num + 4), $row["voter_ambiguity_count"]);
                    // Driving License
                    $sheet->setCellValue("B" . ($row_num + 5), "Driving License");
                    $sheet->setCellValue("D" . ($row_num + 5), $row["dl_ambiguity_count"]);

                    // Increment for next object
                    $row_num += 7;
                    $object_num++;
                }
                
                // ====== Second Sheet: Details ======
                $detailsSheet = $spreadsheet->createSheet();
                $detailsSheet->setTitle('Details');

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
                // Header Row
                $detailsSheet->setCellValue('A7', 'Sr. No.');
                $detailsSheet->setCellValue('B7', 'Prime Object Name');
                $detailsSheet->setCellValue('C7', 'Contact Details');
                $detailsSheet->setCellValue('D7', 'Ambiguity Found in (With Details)');
                $detailsSheet->setCellValue('E7', 'First Link Object Name');
                $detailsSheet->setCellValue('F7', 'Contact Details');
                $detailsSheet->setCellValue('G7', 'Ambiguity Found in (With Details)');
                $detailsSheet->setCellValue('H7', 'Second Link Object Name');
                $detailsSheet->setCellValue('I7', 'Contact Details');
                $detailsSheet->setCellValue('J7', 'Ambiguity Found in (With Details)');

                // Apply bold formatting to the header row
                $detailsSheet->getStyle('A7:J7')->getFont()->setBold(true);

                // Data Rows - Start from row 2
                
                $object_num = 1; 
                 $row_num = 8;
                foreach ($data as $row) {
                    $detailsSheet->setCellValue("A{$row_num}",  $object_num);
                    $detailsSheet->setCellValue("B{$row_num}", $row['name']);
                    $detailsSheet->setCellValue("C{$row_num}", $row['mobile']);
                    // $detailsSheet->setCellValue("3{$row_num}", $aadhar_ambiguity);
                    $detailsSheet->setCellValue("D{$row_num}", $row['pan_ambiguity']);
                    $detailsSheet->setCellValue("E{$row_num}",  $row['dl_ambiguity']);
                    $detailsSheet->setCellValue("F{$row_num}",  $row['voter_ambiguity']);
                    $detailsSheet->setCellValue("G{$row_num}",  $row['total_ambiguity']);
                    $row_num++;
                    $object_num++;

                }
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

                // Call the function to save the spreadsheet to the FTP server
                $resultMessage = saveSpreadsheetToFtp($spreadsheet, $ftpDetails, $filename, $agency_id,$bulk_id);

                echo json_encode([
                    "error_code" => 100,
                    "message" => $resultMessage
                ]);
                exit;
            }
        }

 

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
        $ftp_directory = "/verification_data/voco_xp/{$agency_id}/weblink/{$bulk_id}/ambiguity_report/";
        
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