<?php
require_once __DIR__ . '/vendor/autoload.php';
include 'connection.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
date_default_timezone_set('Asia/Kolkata');

// Database configuration
//Application Vocoxp databese connection//
$connection = connection::getInstance();
$mysqli = $connection->getConnection();
//Central Database Connection//
$connection1 = database::getInstance();
$mysqli1 = $connection1->getConnection();


function saveSpreadsheetToFtp($spreadsheet, $ftpDetails, $filename, $agency_id) {
    $ftpServer = $ftpDetails['server'];
    $ftpUser = $ftpDetails['username'];
    $ftpPass = $ftpDetails['password'];

    $remote_base_dir = '/verification_data/voco_xp/';
    $new_directory_path = "$agency_id/excel".date("Y-m-d")."/";
    $remoteDirectory = $remote_base_dir . $new_directory_path;

    // Set up a connection to the FTP server
    $ftpConnection = ftp_connect($ftpServer);
    if (!$ftpConnection) {
        return "Could not connect to FTP server $ftpServer.\n";
    }

    // Log in to the FTP server
    $login = ftp_login($ftpConnection, $ftpUser, $ftpPass);
    if (!$login) {
        ftp_close($ftpConnection);
        return "Could not log in to FTP server with username $ftpUser.\n";
    }

    // Check if the remote directory exists, if not create it
    if (!@ftp_chdir($ftpConnection, $remoteDirectory)) {
        if (!ftp_mkdir($ftpConnection, $remoteDirectory)) {
            ftp_close($ftpConnection);
            return "Could not create directory $remoteDirectory on FTP server.\n";
        }
        ftp_chdir($ftpConnection, $remoteDirectory);
    }

    // Generate the local file path to save the spreadsheet temporarily
    $localFilePath = sys_get_temp_dir() . '/' . $filename;

    // Save the spreadsheet to the local file
    try {
        $writer = new Xlsx($spreadsheet);
        $writer->save($localFilePath);
    } catch (Exception $e) {
        ftp_close($ftpConnection);
        return "Error saving Excel file locally: " . $e->getMessage() . "\n";
    }

    // Upload the local file to the FTP server
    $remoteFilePath = $remoteDirectory . '/' . $filename;
    $upload = ftp_put($ftpConnection, $remoteFilePath, $localFilePath, FTP_BINARY);
    if (!$upload) {
        ftp_close($ftpConnection);
        return "Error uploading Excel file to FTP server.\n";
    }

    // Clean up: close the FTP connection and delete the local file
    ftp_close($ftpConnection);
    unlink($localFilePath);

    return "Excel file has been uploaded successfully to $remoteFilePath.\n";
}

// Query to fetch distinct agency_id
$ver_query = "SELECT DISTINCT `agency_id` FROM `direct_verification_details_all`";
$ver_result = $mysqli->query($ver_query);

if ($ver_result) {
    while ($agency_row = $ver_result->fetch_assoc()) {
        $agency_id = $agency_row['agency_id'];
        
    // Query for Aadhar data
    $aadhar_query = "SELECT * FROM `direct_aadhar_details_all` WHERE `agency_id`='$agency_id'";
    $edited_aadhar_query = "SELECT * FROM `edited_direct_aadhar_details_all` WHERE `agency_id`='$agency_id'";
    
    // Query for DL data
    $dl_query = "SELECT * FROM `direct_dl_details_all` WHERE `agency_id`='$agency_id'";
    $edited_dl_query = "SELECT * FROM `edited_direct_dl_details_all` WHERE `agency_id`='$agency_id'";
   
    // Query for Voter data
    $voter_query = "SELECT * FROM `direct_voter_details_all` WHERE `agency_id`='$agency_id'";
    $edited_voter_query = "SELECT * FROM `edited_direct_voter_details_all` WHERE `agency_id`='$agency_id'";
    
    // Query for International data
    $international_query = "SELECT * FROM `direct_international_passport_details_all` WHERE `agency_id`='$agency_id'";
    $edited_international_query = "SELECT * FROM `edited_direct_international_passport_details_all` WHERE `agency_id`='$agency_id'";
    
    // Query for Pan data
    $pan_query = "SELECT * FROM `direct_pan_details_all` WHERE `agency_id`='$agency_id'";
    $edited_pan_query = "SELECT * FROM `edited_direct_pan_details_all` WHERE `agency_id`='$agency_id'";
   
    // Query for Passport data
    $passport_query = "SELECT * FROM `direct_passport_details_all` WHERE `agency_id`='$agency_id'";
    $edited_passport_query = "SELECT * FROM `edited_direct_passport_details_all` WHERE `agency_id`='$agency_id'";
   
    $aadhar_result = $mysqli->query($aadhar_query);        
    $edited_aadhar_result = $mysqli->query($edited_aadhar_query);        
    
    $dl_result = $mysqli->query($dl_query);
    $edited_dl_result = $mysqli->query($edited_dl_query);
   
    $voter_result = $mysqli->query($voter_query);
    $edited_voter_result = $mysqli->query($edited_voter_query);
   
    $international_result = $mysqli->query($international_query);
    $edited_international_result = $mysqli->query($edited_international_query);
   
    $pan_result = $mysqli->query($pan_query);
    $edited_pan_result = $mysqli->query($edited_pan_query);
   
    $passport_result = $mysqli->query($passport_query);
    $edited_passport_result = $mysqli->query($edited_passport_query);



        // Process Aadhar data
        if ($aadhar_result && $edited_aadhar_result) {
            $aadharData = [];
            $editedaadharData = [];
            while ($crow = $aadhar_result->fetch_assoc()) {
                $aadharData[] = $crow;
            }
             while ($erow = $edited_aadhar_result->fetch_assoc()) {
                $editedaadharData[] = $erow;
            }
                $spreadsheet = new Spreadsheet();

            if (!empty($aadharData)) {
                $sheet = $spreadsheet->getActiveSheet();
                $sheet->setTitle('Aadhar Data');
                
                // Write headers
                $headers = array_keys($aadharData[0]);
                $sheet->fromArray($headers, NULL, 'A1');

                // Write data
                $rowNum = 2;
                foreach ($aadharData as $row) {
                    $sheet->fromArray(array_values($row), NULL, 'A' . $rowNum++);
                }               
            }
if (!empty($editedaadharData)) {
        $sheet = $spreadsheet->createSheet();
        $sheet->setTitle('Edited Aadhar Data');
        
        // Write headers
        $headers = array_keys($editedaadharData[0]);
        $sheet->fromArray($headers, NULL, 'A1');

        // Write data
        $rowNum = 2;
        foreach ($editedaadharData as $row) {
            $sheet->fromArray(array_values($row), NULL, 'A' . $rowNum++);
        }
    }

 // Define FTP details, directory path, and filename
            $ftpDetails = [
                'server' => '199.79.62.21',
                'username' => 'centralwp@mounarchtech.com',
                'password' => 'k=Y#oBK{h}OU'
            ];
            $filename = 'aadhar_data_' . date("Y-m-d") . '.xlsx';

            // Call the function to save the spreadsheet to the FTP server
            $resultMessage = saveSpreadsheetToFtp($spreadsheet, $ftpDetails, $filename, $agency_id);
            echo $resultMessage;

        } else {
            echo "Error fetching Aadhar data: " . $mysqli->error . "\n";
        }

        // Process DL data
        if ($dl_result && $edited_dl_result) {
            $dlData = [];
            $edited_dlData = [];
            while ($dlrow = $dl_result->fetch_assoc()) {
                $dlData[] = $dlrow;
            }
            while ($edlrow = $edited_dl_result->fetch_assoc()) {
                $edited_dlData[] = $edlrow;
            }
                $spreadsheet = new Spreadsheet();

            if (!empty($dlData)) {
                $sheet = $spreadsheet->getActiveSheet();
                $sheet->setTitle('DL Data');
                
                // Write headers
                $headers = array_keys($dlData[0]);
                $sheet->fromArray($headers, NULL, 'A1');

                // Write data
                $rowNum = 2;
                foreach ($dlData as $row) {
                    $sheet->fromArray(array_values($row), NULL, 'A' . $rowNum++);
                }                
            }
            if (!empty($edited_dlData)) {
        $sheet = $spreadsheet->createSheet();
        $sheet->setTitle('Edited DL Data');
        
        // Write headers
        $headers = array_keys($edited_dlData[0]);
        $sheet->fromArray($headers, NULL, 'A1');

        // Write data
        $rowNum = 2;
        foreach ($edited_dlData as $row) {
            $sheet->fromArray(array_values($row), NULL, 'A' . $rowNum++);
        }
    }
           // Define FTP details, directory path, and filename
            $ftpDetails = [
                'server' => '199.79.62.21',
                'username' => 'centralwp@mounarchtech.com',
                'password' => 'k=Y#oBK{h}OU'
            ];
            $filename = 'dl_data_' . date("Y-m-d") . '.xlsx';

            // Call the function to save the spreadsheet to the FTP server
            $resultMessage = saveSpreadsheetToFtp($spreadsheet, $ftpDetails, $filename, $agency_id);
            echo $resultMessage;

        } else {
            echo "Error fetching DL data: " . $mysqli->error . "\n";
        }


        // Process Voter data
        if ($voter_result && $edited_voter_result) {
            $voterData = [];
            $edited_voterData = [];
            while ($voterrow = $voter_result->fetch_assoc()) {
                $voterData[] = $voterrow;
            }
            while ($evoterrow = $edited_voter_result->fetch_assoc()) {
                $edited_voterData[] = $evoterrow;
            }
                $spreadsheet = new Spreadsheet();

            if (!empty($voterData)) {
                $sheet = $spreadsheet->getActiveSheet();
                $sheet->setTitle('Voter Data');
                
                // Write headers
                $headers = array_keys($voterData[0]);
                $sheet->fromArray($headers, NULL, 'A1');

                // Write data
                $rowNum = 2;
                foreach ($voterData as $row) {
                    $sheet->fromArray(array_values($row), NULL, 'A' . $rowNum++);
                }               
            }

 
            if (!empty($edited_voterData)) {
                $sheet = $spreadsheet->createSheet();
                $sheet->setTitle('Edited Voter Data');
                
                // Write headers
                $headers = array_keys($edited_voterData[0]);
                $sheet->fromArray($headers, NULL, 'A1');

                // Write data
                $rowNum = 2;
                foreach ($edited_voterData as $row) {
                    $sheet->fromArray(array_values($row), NULL, 'A' . $rowNum++);
                }               
            }
              // Define FTP details, directory path, and filename
            $ftpDetails = [
                'server' => '199.79.62.21',
                'username' => 'centralwp@mounarchtech.com',
                'password' => 'k=Y#oBK{h}OU'
            ];
            $filename = 'voter_data_' . date("Y-m-d") . '.xlsx';

            // Call the function to save the spreadsheet to the FTP server
            $resultMessage = saveSpreadsheetToFtp($spreadsheet, $ftpDetails, $filename, $agency_id);
            echo $resultMessage;

        } else {
            echo "Error fetching Voter data: " . $mysqli->error . "\n";
        }


        // Process International data
        if ($international_result && $edited_international_result) {
            $internationalData = [];
            $edited_internationalData = [];
            while ($internationalrow = $international_result->fetch_assoc()) {
                $internationalData[] = $internationalrow;
            }
            while ($einternationalrow = $edited_international_result->fetch_assoc()) {
                $edited_internationalData[] = $einternationalrow;
            }
                $spreadsheet = new Spreadsheet();

            if (!empty($internationalData)) {
                $sheet = $spreadsheet->getActiveSheet();
                $sheet->setTitle('International Passport Data');
                
                // Write headers
                $headers = array_keys($internationalData[0]);
                $sheet->fromArray($headers, NULL, 'A1');

                // Write data
                $rowNum = 2;
                foreach ($internationalData as $row) {
                    $sheet->fromArray(array_values($row), NULL, 'A' . $rowNum++);
                }                
            }

             if (!empty($edited_internationalData)) {
                $sheet = $spreadsheet->createSheet();
                $sheet->setTitle('Edited International Passport Data');
                
                // Write headers
                $headers = array_keys($edited_internationalData[0]);
                $sheet->fromArray($headers, NULL, 'A1');

                // Write data
                $rowNum = 2;
                foreach ($edited_internationalData as $row) {
                    $sheet->fromArray(array_values($row), NULL, 'A' . $rowNum++);
                }                
            }
             // Define FTP details, directory path, and filename
            $ftpDetails = [
                'server' => '199.79.62.21',
                'username' => 'centralwp@mounarchtech.com',
                'password' => 'k=Y#oBK{h}OU'
            ];
            $filename = 'international_data_' . date("Y-m-d") . '.xlsx';

            // Call the function to save the spreadsheet to the FTP server
            $resultMessage = saveSpreadsheetToFtp($spreadsheet, $ftpDetails, $filename, $agency_id);
            echo $resultMessage;

        } else {
            echo "Error fetching International Passport data: " . $mysqli->error . "\n";
        }


        // Process Pan data
        if ($pan_result && $edited_pan_result) {
            $panData = [];
            $edited_panData = [];
            while ($panrow = $pan_result->fetch_assoc()) {
                $panData[] = $panrow;
            }
            while ($epanrow = $edited_pan_result->fetch_assoc()) {
                $edited_panData[] = $epanrow;
            }
                $spreadsheet = new Spreadsheet();

            if (!empty($panData)) {
                $sheet = $spreadsheet->getActiveSheet();
                $sheet->setTitle('Pan Data');
                
                // Write headers
                $headers = array_keys($panData[0]);
                $sheet->fromArray($headers, NULL, 'A1');

                // Write data
                $rowNum = 2;
                foreach ($panData as $row) {
                    $sheet->fromArray(array_values($row), NULL, 'A' . $rowNum++);
                }               
            }

            if (!empty($edited_panData)) {
                $sheet = $spreadsheet->createSheet();
                $sheet->setTitle('Edited Pan Data');
                
                // Write headers
                $headers = array_keys($edited_panData[0]);
                $sheet->fromArray($headers, NULL, 'A1');

                // Write data
                $rowNum = 2;
                foreach ($edited_panData as $row) {
                    $sheet->fromArray(array_values($row), NULL, 'A' . $rowNum++);
                }               
            }
            // Define FTP details, directory path, and filename
            $ftpDetails = [
                'server' => '199.79.62.21',
                'username' => 'centralwp@mounarchtech.com',
                'password' => 'k=Y#oBK{h}OU'
            ];
            $filename = 'pan_data_' . date("Y-m-d") . '.xlsx';

            // Call the function to save the spreadsheet to the FTP server
            $resultMessage = saveSpreadsheetToFtp($spreadsheet, $ftpDetails, $filename, $agency_id);
            echo $resultMessage;

        } else {
            echo "Error fetching Pan data: " . $mysqli->error . "\n";
        } 
        // Process Passport data
        if ($passport_result && $edited_passport_result) {
            $passportData = [];
            $edited_passportData = [];
            while ($passportrow = $passport_result->fetch_assoc()) {
                $passportData[] = $passportrow;
            }
            while ($epassportrow = $edited_passport_result->fetch_assoc()) {
                $edited_passportData[] = $epassportrow;
            }
                $spreadsheet = new Spreadsheet();

            if (!empty($passportData)) {
                $sheet = $spreadsheet->getActiveSheet();
                $sheet->setTitle('Passport Data');
                
                // Write headers
                $headers = array_keys($passportData[0]);
                $sheet->fromArray($headers, NULL, 'A1');

                // Write data
                $rowNum = 2;
                foreach ($passportData as $row) {
                    $sheet->fromArray(array_values($row), NULL, 'A' . $rowNum++);
                }                
            }
            if (!empty($edited_passportData)) {
                $sheet = $spreadsheet->createSheet();
                $sheet->setTitle('Edited Passport Data');
                
                // Write headers
                $headers = array_keys($edited_passportData[0]);
                $sheet->fromArray($headers, NULL, 'A1');

                // Write data
                $rowNum = 2;
                foreach ($edited_passportData as $row) {
                    $sheet->fromArray(array_values($row), NULL, 'A' . $rowNum++);
                }                
            }
             // Define FTP details, directory path, and filename
            $ftpDetails = [
                'server' => '199.79.62.21',
                'username' => 'centralwp@mounarchtech.com',
                'password' => 'k=Y#oBK{h}OU'
            ];
            $filename = 'passport_data_' . date("Y-m-d") . '.xlsx';

            // Call the function to save the spreadsheet to the FTP server
            $resultMessage = saveSpreadsheetToFtp($spreadsheet, $ftpDetails, $filename, $agency_id);
            echo $resultMessage;

        } else {
            echo "Error fetching Passport data: " . $mysqli->error . "\n";
        } 
    }
} else {
    echo "Error fetching agency ids: " . $mysqli->error . "\n";
}



?>