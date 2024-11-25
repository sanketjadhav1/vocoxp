<?php
session_start();
include_once 'connection.php';
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

$connectionInstance = connection::getInstance();
$db = $connectionInstance->getConnection();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['upload-excel'])) {
    $file = $_FILES['upload-excel'];

    if ($file['error'] === UPLOAD_ERR_OK) {
        $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);

        if (in_array($fileExtension, ['xlsx', 'xls'])) {
            $spreadsheet = IOFactory::load($file['tmp_name']);
            $worksheet = $spreadsheet->getActiveSheet();
            $data = $worksheet->toArray();
            $type = $data[1][5] ?? '';
            $excel_no1 = $data[2][5] ?? '';
            $excel_no = $_POST['excel_no'];
            // if ($excel_no1 !== $excel_no) {
            //     echo json_encode(['status' => 'error', 'message' => 'Please upload the correct file.']);
            //     exit;
            // }


            // if (empty($type)) {
            //     echo json_encode(['status' => 'error', 'message' => 'Type not found in the uploaded file.']);
            //     exit;
            // }

            // Fetch validation rules from the database based on the extracted type
            $type = $db->real_escape_string($type);
            $query = "SELECT excel_verification_rules_1, excel_verification_rules_2, 
                             excel_verification_rules_3, excel_verification_rules_4, 
                             excel_verification_rules_5, excel_verification_rules_6, 
                             excel_verification_rules_7, excel_verification_rules_8, 
                             excel_verification_rules_9, excel_verification_rules_10 
                      FROM sample_excel_definations_all 
                      WHERE type = '$type' OR excel_no = '$excel_no1'";

            $result = $db->query($query);

            // if ($result->num_rows > 0) {
            //     $row = $result->fetch_assoc();
            //     $rules = [
            //         $row['excel_verification_rules_1'],
            //         $row['excel_verification_rules_2'],
            //         $row['excel_verification_rules_3'],
            //         $row['excel_verification_rules_4'],
            //         $row['excel_verification_rules_5'],
            //         $row['excel_verification_rules_6'],
            //         $row['excel_verification_rules_7'],
            //         $row['excel_verification_rules_8'],
            //         $row['excel_verification_rules_9'],
            //         $row['excel_verification_rules_10']
            //     ];
            // } else {
            //     echo json_encode(['status' => 'error', 'message' => 'No rules found for the given type.']);
            //     exit;
            // }

            $headerRow = $data[5] ?? [];

            $validationErrors = [];
            $validRows = 0;

            foreach ($data as $index => $row) {
                if ($index < 6) continue; // Skip header rows
                if (count(array_filter($row)) === 0) continue; // Skip completely empty rows
                $isValid = true;

                foreach ($rules as $ruleIndex => $rule) {
                    $columnValue = trim($row[$ruleIndex] ?? '');
                    $header = $headerRow[$ruleIndex] ?? 'Unknown Column';

                    if (empty($rule)) {
                        continue; // Skip columns with no rules
                    }

                    list($mandatory, $validationRule) = explode('>', $rule);

                    // Step 1: Check if the column is required and empty
                    if ($mandatory === 'C' && empty($columnValue)) {
                        $validationErrors[] = "Row " . ($index + 1) . ": Column '$header' is required.";
                        $isValid = false;
                        // Skip further validation for this field
                        continue;
                    }

                    // Step 2: Only check the format if the field is not empty
                    if (!empty($columnValue)) {
                        if ($validationRule === 'DIGITS' && !ctype_digit($columnValue)) {
                            $validationErrors[] = "Row " . ($index + 1) . ": Column '$header' must contain digits only.";
                            $isValid = false;
                        }

                        if ($validationRule === 'ALPHABET' && !ctype_alpha(str_replace(' ', '', $columnValue))) {
                            // Ignore spaces for alphabet validation
                            $validationErrors[] = "Row " . ($index + 1) . ": Column '$header' must contain alphabetic characters only.";
                            $isValid = false;
                        }

                        if ($validationRule === 'MOBILE=IN' && (!ctype_digit($columnValue) || strlen($columnValue) != 10)) {
                            $validationErrors[] = "Row " . ($index + 1) . ": Column '$header' must contain a 10-digit mobile number.";
                            $isValid = false;
                        }

                        if ($validationRule === 'EMAIL' && !filter_var($columnValue, FILTER_VALIDATE_EMAIL)) {
                            $validationErrors[] = "Row " . ($index + 1) . ": Column '$header' must contain a valid email address.";
                            $isValid = false;
                        }
                    }
                }

                if ($isValid) {
                    $validRows++;
                }
            }

            if ($validRows === 0 && empty($validationErrors)) {
                echo json_encode(['status' => 'error', 'message' => 'No valid records found in the Excel sheet.']);
                exit;
            }

            if (count($validationErrors) > 20) {
                echo json_encode(['status' => 'warning', 'message' => implode('</br>', array_slice($validationErrors, 0, 20))]);
                exit;
            }

            if (count($validationErrors) > 0) {
                echo json_encode(['status' => 'warning', 'message' => implode('</br>', $validationErrors)]);
            } else {
                echo json_encode(['status' => 'success', 'message' => "Data validated successfully."]);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid file format. Please upload an Excel file.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'File upload error.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
}
