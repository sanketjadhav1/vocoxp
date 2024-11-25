<?php
include_once '../connection.php';
require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

error_reporting(E_ALL & ~E_DEPRECATED);
ini_set('display_errors', 1);

$connection = connection::getInstance();
$mysqli = $connection->getConnection();

$system_datetime = date("Y-m-d H:i:s");
$fileName = $_FILES['file_name']['name'];
$file_ext = pathinfo($fileName, PATHINFO_EXTENSION);
$allowed_ext = ['xls', 'csv', 'xlsx'];

if (!in_array($file_ext, $allowed_ext)) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid file type.']);
    exit();
}


$inputFileNamePath = $_FILES['file_name']['tmp_name'];

try {
    $agency_id = $_POST['agency_id'] ?? '';
    $operation = $_POST['operation'] ?? '';
    // Load the Excel file
    $spreadsheet = IOFactory::load($inputFileNamePath);
    $data = $spreadsheet->getActiveSheet()->toArray();
    $type = $data[1][5] ?? '';
    // $type = $data[1][5] ?? '';
    $excel_no = $data[2][5] ?? '';
    $headerRow = $data[5] ?? [];
    $nameIndices = [];
    $emailIndices = [];
    $mobileIndices = [];
    $AprovalIndices = [];
    $VisitorIndices = [];
    $DepartmentIndices = [];
    $DesignationIndices = [];
    $PaidByIndices = [];
    $EmpExIdIndices = [];

    foreach ($headerRow as $index => $header) {
        if (stripos($header, 'Name') !== false) {
            $nameIndices[] = $index;
        }
        if (stripos($header, 'Email') !== false) {
            $emailIndices[] = $index;
        }
        if (stripos($header, 'Mobile') !== false) {
            $mobileIndices[] = $index;
        }
        if (stripos($header, 'Approval is mandatory') !== false) {
            $AprovalIndices[] = $index;
        }
        if (stripos($header, 'visiting charges') !== false) {
            $VisitorIndices[] = $index;
        }
        if (stripos($header, 'Department') !== false) {
            $DepartmentIndices[] = $index;
        }
        if (stripos($header, 'Designation') !== false) {
            $DesignationIndices[] = $index;
        }
        if (stripos($header, 'Verification Paid By') !== false) {
            $PaidByIndices[] = $index;
        }

        if (stripos($header, 'Employee No / ID') !== false) {
            $EmpExIdIndices[] = $index;
        }
    }
    if (empty($nameIndices) || empty($emailIndices) || empty($mobileIndices)) {
        echo json_encode(['status' => 'error', 'message' => 'Missing required columns (Name, Email, or Mobile) in the Excel file.']);
        exit();
    }


    foreach ($data as $index => $row) {
        if ($index < 6) continue;

        $name = '';
        foreach ($nameIndices as $nameIndex) {
            if (!empty($row[$nameIndex])) {
                $name = trim($row[$nameIndex]);
                break;
            }
        }
        $email = '';
        foreach ($emailIndices as $emailIndex) {
            $email = trim($row[$emailIndex]);
            break;
        }
        $mobile = '';
        foreach ($mobileIndices as $mobileIndex) {
            $mobile =  trim($row[$mobileIndex]);
            break;
        }
        $Approval = '';
        foreach ($AprovalIndices as $approvalIndex) {
            if (!empty($row[$approvalIndex])) {
                $trimmedValue = trim($row[$approvalIndex]);
                $Approval = ($trimmedValue == 'Y') ? 1 : 0;
                break; 
            }
        }

        $visiting_charges = '';
        foreach ($VisitorIndices as $VisitorIndex) {
            if (!empty($row[$VisitorIndex])) {
                $trimmedValue = trim($row[$VisitorIndex]);
                $visiting_charges = ($trimmedValue == 'Y') ? 1 : 0;
                break; 
            }
        }

        $department = '';
        foreach ($DepartmentIndices as $DepartmentIndex) {
            if (!empty($row[$DepartmentIndex])) {
                $department = trim($row[$DepartmentIndex]);
                break; 
            }
        }

        $designation = '';
        foreach ($DesignationIndices as $DesignationIndex) {
            if (!empty($row[$DesignationIndex])) {
                $designation = trim($row[$DesignationIndex]);
                break; 
            }
        }

        $paid_by = '';
        foreach ($PaidByIndices as $PaidByIndex) {
            if (!empty($row[$PaidByIndex])) {
                $paid_by = trim($row[$PaidByIndex]);
                break; 
            }
        }
        // $emp_id = '';
        // foreach($EmpIdIndices as $EmpIdIndex){
        //     if(!empty($row[$EmpIdIndex])){
        //         $emp_id = trim($row[$EmpIdIndex]);
        //     }

        // }

        if ($operation == '1') {
            if (!empty($name)) {
                $emp_id = unique_id_generate_bulk('VIEMP', 'employee_header_all', $mysqli, "emp_id");
                $insert_employee = "INSERT INTO `employee_header_all` 
                        (`agency_id`, `emp_id`,`name`, `contact`, `email_id`,`visitor_approval_required`,`visiting_charges`,`department`,`designation`,`verification_paid_by`) 
                            VALUES ('$agency_id',  '$emp_id','$name', '$mobile', '$email','$Approval','$visiting_charges','$department','$designation','$paid_by')";
                $res_employee = mysqli_query($mysqli,     $insert_employee);
                if ($res_employee) {
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Failed to insert end user data: ' . mysqli_error($mysqli)]);
                    exit();
                }
            }
        }
        if ($operation == '2') {
            if (!empty($email)) {
                $update_employee = "UPDATE `employee_header_all` 
                                    SET `name` = '$name', 
                                        `contact` = '$mobile', 
                                        `visitor_approval_required` = '$Approval',
                                        `visiting_charges` = '$visiting_charges', 
                                        `department` = '$department',
                                        `designation` = '$designation', 
                                        `verification_paid_by` = '$paid_by'
                                    WHERE `email_id` = '$email' AND `agency_id` = '$agency_id'";

                $res_update = mysqli_query($mysqli, $update_employee);

                if ($res_update) {
                    echo json_encode(['status' => 'success', 'message' => 'Record updated successfully.']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Failed to update record: ' . mysqli_error($mysqli)]);
                    exit();
                }
            }
        }
        if($operation == '3'){
            // echo $operation;exit();
            if (!empty($email)) {
                $update_employee = "UPDATE `employee_header_all` 
                                    SET `status` = '0'
                                    WHERE `email_id` = '$email' AND `agency_id` = '$agency_id'";

                $res_update = mysqli_query($mysqli, $update_employee);

                if ($res_update) {
                    echo json_encode([ 'message' => 'Record updated successfully.']);
                } else {
                    echo json_encode(['message' => 'Failed to update record: ' . mysqli_error($mysqli)]);
                    exit();
                }
            }

        }
    }
} catch (Exception $e) {
    echo json_encode([ 'message' => 'Error processing file: ' . $e->getMessage()]);
    exit();
}



function unique_id_generate_bulk($id_prefix, $table_name, $mysqli, $id_for)
{
    date_default_timezone_set('Asia/Kolkata');
    $system_date_time = date("Y-m-d H:i:s");

    $unique_header_query = "SELECT `prefix`, `last_id`, `id_for` FROM `unique_id_header_all` WHERE `table_name`='$table_name' AND `id_for`='$id_for'";
    $unique_header_res = $mysqli->query($unique_header_query);
    $unique_header_arr = $unique_header_res->fetch_assoc();

    if (empty($unique_header_arr)) {
        $initial_id = $id_prefix . '-' . str_pad(1, 5, '0', STR_PAD_LEFT);
        $insert_query = "INSERT INTO `unique_id_header_all` (`table_name`, `id_for`, `prefix`, `last_id`, `created_on`) 
                       VALUES ('$table_name', '$id_for', '$id_prefix', '$initial_id', '$system_date_time')";
        $mysqli->query($insert_query);
        return $initial_id;
    } else {
        $last_id = $unique_header_arr['last_id'];
        $last_digit = explode('-', $last_id);
        $last_id_number = $last_digit[1];

        if (strlen($last_id_number) > 5) {
            return 'ID cannot be more than 5 characters';
        }

        $digits = preg_replace('/[^0-9]/', '', $last_id_number);

        if ($digits === str_repeat('9', strlen($digits))) {
            return 'You have reached the last ID string';
        }

        $next_id = str_pad((intval($digits) + 1), strlen($digits), '0', STR_PAD_LEFT);
        $unique_id = $id_prefix . "-" . $next_id;

        $update_unique_header_query = "UPDATE `unique_id_header_all` SET `last_id`='$unique_id', `modified_on`='$system_date_time' WHERE `table_name`='$table_name' AND `id_for`='$id_for'";
        $mysqli->query($update_unique_header_query);

        return $unique_id;
    }
}
