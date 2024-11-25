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
print_r($data);
            // Assuming type and excel_no are stored in specific cells
            echo $type = $data[1][5] ?? '';
            // $excel_no = $data[2][5] ?? '';

            // if (empty($type)) {
            //     echo json_encode(['status' => 'error', 'message' => 'Type not found in the uploaded file.']);
            //     exit;
            // }
            // echo 'a';
            die;

            // Fetch validation rules from the database based on the extracted type
            $type = $db->real_escape_string($type); // Escape for security
            $query = "SELECT excel_verification_rules_1, excel_verification_rules_2, 
                             excel_verification_rules_3, excel_verification_rules_4, 
                             excel_verification_rules_5, excel_verification_rules_6, 
                             excel_verification_rules_7, excel_verification_rules_8, 
                             excel_verification_rules_9, excel_verification_rules_10 
                      FROM sample_excel_definations_all WHERE type = '$type' OR excel_no = '$excel_no'";

            $result = $db->query($query);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $rules = [
                    $row['excel_verification_rules_1'],
                    $row['excel_verification_rules_2'],
                    $row['excel_verification_rules_3'],
                    $row['excel_verification_rules_4'],
                    $row['excel_verification_rules_5'],
                    $row['excel_verification_rules_6'],
                    $row['excel_verification_rules_7'],
                    $row['excel_verification_rules_8'],
                    $row['excel_verification_rules_9'],
                    $row['excel_verification_rules_10']
                ];
            } else {
                echo json_encode(['status' => 'error', 'message' => 'No rules found for the given type.']);
                exit;
            }

            // Identify mobile, email, father's details, and mother's details columns
            $headerRow = $data[5] ?? []; // Assuming headers are in the 6th row (index 5)
            
            $mobileColumns = []; // Mobile columns
            $emailColumns = [];  // Email columns
            $fathersColumns = []; // Father name column
            $mothersColumns = []; //$mother name col
            $fathersMobileColumns = [];
            $fathersEmailColumns = [];
            $mothersMobileColumns = [];
            $mothersEmailColumns = [];


            foreach ($headerRow as $index => $header) {
                if ($header !== null) {
                    if (stripos($header, 'Mobile') !== false && stripos($header, "Father's Mobile No") === false && stripos($header, "Mother's Mobile no") === false) {
                        $mobileColumns[] = $index;
                    }
                    if (stripos($header, 'Email') !== false && stripos($header, "Father's Email") === false && stripos($header, "Mother's Email") === false) {
                        $emailColumns[] = $index;
                    }
                    if (stripos($header, "Father's Complete Name") !== false) {
                        $fathersColumns[] = $index;
                    }
                    if (stripos($header, "Mother's complete name") !== false) {
                        $mothersColumns[] = $index;
                    }
                    if (stripos($header, "Father's Mobile No") !== false) {
                        $fathersMobileColumns[] = $index;
                    }
                    if (stripos($header, "Father's Email") !== false) {
                        $fathersEmailColumns[] = $index;
                    }
                    if (stripos($header, "Mother's Mobile no") !== false) {
                        $mothersMobileColumns[] = $index;
                    }
                    if (stripos($header, "Mother's Email") !== false) {
                        $mothersEmailColumns[] = $index;
                    }
                }
            }

            // Validation process for each row
            $validationErrors = [];
            $validRows = 0;

            foreach ($data as $index => $row) {
                if ($index < 6) continue; // Skip the first 6 rows
                if (count(array_filter($row)) === 0) continue;
                $isValid = true; 
                foreach ($rules as $ruleIndex => $rule) {
                    $columnValue = trim($row[$ruleIndex] ?? '');
                
                    // Skip empty rule or column value
                    if (empty($rule) || empty($columnValue)) {
                        continue;
                    }
                
                    // Apply rule validation
                    if (stripos($rule, 'DIGITS') !== false) {
                        if (!ctype_digit($columnValue)) {
                            $validationErrors[] = "Row " . ($index + 1) . ": Column " . ($ruleIndex + 1) . " must contain digits only.";
                            $isValid = false;
                        }
                    }
                    if (stripos($rule, 'ALPHABET') !== false) {
                        if (!ctype_alpha(str_replace(' ', '', $columnValue))) { // Ignore spaces for alphabet validation
                            $validationErrors[] = "Row " . ($index + 1) . ": Column " . ($ruleIndex + 1) . " must contain alphabetic characters only.";
                            $isValid = false;
                        }
                    }
                    if (stripos($rule, 'MOBILE=IN') !== false) {
                        if (!ctype_digit($columnValue) || strlen($columnValue) != 10) {
                            $validationErrors[] = "Row " . ($index + 1) . ": Column " . ($ruleIndex + 1) . " must be a 10-digit mobile number.";
                            $isValid = false;
                        }
                    }
                    if (stripos($rule, 'EMAIL') !== false) {
                        if (!filter_var($columnValue, FILTER_VALIDATE_EMAIL)) {
                            $validationErrors[] = "Row " . ($index + 1) . ": Column " . ($ruleIndex + 1) . " must be a valid email address.";
                            $isValid = false;
                        }
                    }
                }
                 foreach ($mobileColumns as $i => $mobileIndex) {
                    $emailIndex = $emailColumns[$i] ?? null;

                    $mobileValue = $row[$mobileIndex] ?? '';
                    $emailValue = $emailIndex !== null ? $row[$emailIndex] ?? '' : '';
                    if (empty($mobileValue) && empty($emailValue)) {
                        $validationErrors[] = "Row " . ($index + 1) . ": Both Mobile Number and Email cannot be empty.";
                        $isValid = false;
                    } else {
                        if (!empty($mobileValue)) {
                            if (!ctype_digit($mobileValue) || strlen($mobileValue) != 10) {
                                $validationErrors[] = "Row " . ($index + 1) .": Mobile no  must be exactly 10 digits.";
                                $isValid = false;
                            }
                        }
                        if (!empty($emailValue) && !filter_var($emailValue, FILTER_VALIDATE_EMAIL)) {
                            $validationErrors[] = "Row " . ($index + 1) . " must be a valid email address.";
                            $isValid = false;
                        }
                    }
                }
                foreach ($fathersColumns as $i => $fatheIndex) {
                    $motherIndex = $mothersColumns[$i] ?? null;
                    $fatherNamevalue = $row[$fatheIndex] ?? '';
                    $motherNamevalue = $row[$motherIndex] ?? '';

                    $fatherEmailIndex = $fathersEmailColumns[$i] ?? null;
                    $fathersMobileIndex = $fathersMobileColumns[$i] ?? null;

                    $motherEmailIndex = $mothersEmailColumns[$i] ?? null;
                    $motherMobileIndex = $mothersMobileColumns[$i] ?? null;

                    $fatherEmailValue = $row[$fatherEmailIndex] ?? '';
                    $fatherMobileValue = $row[$fathersMobileIndex] ?? '';
                    $motherEmailValue = $row[$motherEmailIndex] ?? '';
                    $motherMobileValue = $row[$motherMobileIndex] ?? '';

                    if (!empty($fatherNamevalue)) {
                        if (empty($fatherEmailValue) && empty($fatherMobileValue)) {
                            $validationErrors[] = "Row " . ($index + 1) . ": At least one of Father's Email or Mobile must be provided.";
                            $isValid = false;
                        }
                        //  else {
                        //     if (!empty($fatherEmailValue) && !filter_var($fatherEmailValue, FILTER_VALIDATE_EMAIL)) {
                        //         $validationErrors[] = "Row " . ($index + 1) . ": Father's Email must be a valid email address.";
                        //         $isValid = false;
                        //     }
                        //     if (!empty($fatherMobileValue) && (!ctype_digit($fatherMobileValue) || strlen($fatherMobileValue) != 10)) {
                        //         $validationErrors[] = "Row " . ($index + 1) . ": Father's Mobile must be a 10-digit number.";
                        //         $isValid = false;
                        //     }
                        // }
                    }
                    if (!empty($motherNamevalue)) {
                        if (empty($motherEmailValue) && empty($motherMobileValue)) {
                            $validationErrors[] = "Row " . ($index + 1) . ": At least one of Mother's Email or Mobile must be provided.";
                            $isValid = false;
                        } 
                        // else {
                        //     if (!empty($motherEmailValue) && !filter_var($motherEmailValue, FILTER_VALIDATE_EMAIL)) {
                        //         $validationErrors[] = "Row " . ($index + 1) . ": Mother's Email must be a valid email address.";
                        //         $isValid = false;
                        //     }
                        //     if (!empty($motherMobileValue) && (!ctype_digit($motherMobileValue) || strlen($motherMobileValue) != 10)) {
                        //         $validationErrors[] = "Row " . ($index + 1) . ": Mother's Mobile must be a 10-digit number.";
                        //         $isValid = false;
                        //     }
                        // }
                    }
                    if (empty($fatherNamevalue) && empty($motherNamevalue)) {
                        $validationErrors[] = "Row" . ($index + 1) . ": Both Father's Name and Mother's Name Can not be empty.";
                        $isValid = false;
                    }
                }
                if ($isValid) {
                    $validRows++;
                }
            }
            if ($validRows === 0 && empty($validationErrors)) {
                echo json_encode(['status' => 'error', 'message' => 'No record found in the Excel sheet.']);
                exit;
            }

            if (count($validationErrors) > 20) {
                echo json_encode(['status' => 'warning', 'message' => implode('</br>', array_slice($validationErrors, 0, 20))]);
                exit;
            }

            if (count($validationErrors) > 0) {
                echo json_encode(['status' => 'warning', 'message' => implode('</br>', $validationErrors)]);
            } else {
                echo json_encode(['status' => 'success', 'message' => "Data validated successfully. Valid rows: $validRows"]);
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
