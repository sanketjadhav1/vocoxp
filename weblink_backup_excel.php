<?php
require_once __DIR__ . '/vendor/autoload.php';
include 'connection.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
date_default_timezone_set('Asia/Kolkata');

// Database configuration
$connection = connection::getInstance();
$mysqli = $connection->getConnection();

$connection1 = database::getInstance();
$mysqli1 = $connection1->getConnection();

function saveSpreadsheetToFtp($spreadsheet, $ftpDetails, $filename, $agency_id,$bulk_id) {
    $ftpServer = $ftpDetails['server'];
    $ftpUser = $ftpDetails['username'];
    $ftpPass = $ftpDetails['password'];

    $remote_base_dir = 'verification_data/voco_xp/';
    $new_directory_path = "$agency_id/weblink/$bulk_id/";
    $remoteDirectory = $remote_base_dir . $new_directory_path;

    $ftpConnection = ftp_connect($ftpServer);
    if (!$ftpConnection) {
        return "Could not connect to FTP server $ftpServer.\n";
    }

    $login = ftp_login($ftpConnection, $ftpUser, $ftpPass);
    if (!$login) {
        ftp_close($ftpConnection);
        return "Could not log in to FTP server with username $ftpUser.\n";
    }

    // Ensure remote directory exists
    if (!ftp_mkdir_recursive($ftpConnection, $remoteDirectory)) {
        ftp_close($ftpConnection);
        return "Could not create directory $remoteDirectory on FTP server.\n";
    }

    $localFilePath = sys_get_temp_dir() . '/' . $filename;

    try {
        $writer = new Xlsx($spreadsheet);
        $writer->save($localFilePath);
    } catch (Exception $e) {
        ftp_close($ftpConnection);
        return "Error saving Excel file locally: " . $e->getMessage() . "\n";
    }

    $remoteFilePath = $remoteDirectory . '/' . $filename;
    $upload = ftp_put($ftpConnection, $remoteFilePath, $localFilePath, FTP_BINARY);
    if (!$upload) {
        ftp_close($ftpConnection);
        return "Error uploading Excel file to FTP server.\n";
    }

    ftp_close($ftpConnection);
    unlink($localFilePath);

    return "Excel file has been uploaded successfully to $remoteFilePath.\n";
}


// Query to fetch distinct agency_id
$ver_query = "SELECT DISTINCT `agency_id` FROM `direct_verification_details_all`";
$ver_result = $mysqli->query($ver_query);

if ($ver_result) {
    while ($agency_row = $ver_result->fetch_assoc()) {
        // $agency_id = 'AGN-00005';
        
        // Query for Aadhar data
        $aadhar_query = "SELECT * FROM `bulk_weblink_request_all` WHERE `status`='4' OR `status`='5'";
        $aadhar_result = $mysqli->query($aadhar_query);

        // Process Aadhar data
        if ($aadhar_result) {
            $rowNum = 1;
            while ($crow = $aadhar_result->fetch_assoc()) {
                $agency_id = $crow['agency_id'];
                $bulk_id = $crow['bulk_id'];
                $spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();
                $sheet->setTitle('Weblink Data');

                // Write headers
                $headers = array_keys($crow);
                $sheet->fromArray($headers, NULL, 'A1');

                // Write data
                $sheet->fromArray(array_values($crow), NULL, 'A2');

                // Define FTP details, directory path, and filename
                $ftpDetails = [
                    'server' => '199.79.62.21',
                    'username' => 'centralwp@mounarchtech.com',
                    'password' => 'k=Y#oBK{h}OU'
                ];
                $filename =  "closer_data/weblink_data_excel".$rowNum . '_' . date("Y-m-d") . '.xlsx';

                // Call the function to save the spreadsheet to the FTP server
                $resultMessage = saveSpreadsheetToFtp($spreadsheet, $ftpDetails, $filename, $agency_id,$bulk_id);
                echo $resultMessage;

                $rowNum++;
            }
        } else {
            echo "Error fetching Aadhar data: " . $mysqli->error . "\n";
        }
    }
} else {
    echo "Error fetching agency ids: " . $mysqli->error . "\n";
}
?>
