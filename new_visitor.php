<?php
require_once __DIR__ . '/vendor/autoload.php';

use Mpdf\Mpdf;
use Mpdf\Output\Destination;

$system_date = date("Ymd");

function downloadFile($url, $path) {
    $fileContent = file_get_contents($url, false, stream_context_create(['ssl' => ['verify_peer' => false, 'verify_peer_name' => false]]));
    if ($fileContent === false) {
        throw new Exception("Could not download file from URL: $url");
    }
    file_put_contents($path, $fileContent);
}

function createFTPDirectory($ftp_conn, $dir) {
    $parts = explode('/', $dir);
    $path = '';
    foreach ($parts as $part) {
        $path .= '/' . $part;
        if (!@ftp_chdir($ftp_conn, $path)) {
            ftp_mkdir($ftp_conn, $path);
        }
    }
    return true;
}

function savePDF($output_pdf, $remote_base_dir, $agency_id, $for, $system_date,$visitor_id,$emp_id) {
    $ftp_server = '199.79.62.21';
    $ftp_username = 'centralwp@mounarchtech.com';
    $ftp_password = 'k=Y#oBK{h}OU';

    $new_directory_path = "$agency_id/$visitor_id/$emp_id/$for";
    $remote_dir_path = $remote_base_dir . $new_directory_path;

    $file_name = $system_date . '.pdf';
    $file_path = $remote_dir_path . $file_name;

    $temp_file = tempnam(sys_get_temp_dir(), 'pdf');
    file_put_contents($temp_file, $output_pdf);

    $ftp_connection = ftp_connect($ftp_server);
    if (!$ftp_connection) {
        throw new Exception("Could not connect to FTP server");
    }

    $login = ftp_login($ftp_connection, $ftp_username, $ftp_password);
    if (!$login) {
        ftp_close($ftp_connection);
        throw new Exception("Could not log in to FTP server");
    }

    createFTPDirectory($ftp_connection, $remote_dir_path);

    $upload = ftp_put($ftp_connection, $file_path, $temp_file, FTP_BINARY);
    if (!$upload) {
        ftp_close($ftp_connection);
        unlink($temp_file);
        throw new Exception("Failed to save merged PDF file");
    }

    ftp_close($ftp_connection);
    unlink($temp_file);

    $base_url = "https://mounarchtech.com/central_wp/verification_data/voco_xp/$agency_id/visitor/$emp_id/$for";
    $path = $base_url . '/' . $file_name;

    $response = [];
    if ($path !== false) {
        $response['success'] = true;
        $response['message'] = 'Integrated PDF generated successfully.';
        $response['path'] = $path;
    } else {
        $response['success'] = false;
        $response['message'] = 'Failed to generate Integrated PDF.';
    }

    return $response;
}

$agency_id = $_POST['agency_id']; 
$visitor_id = $_POST['visitor_id']; 
$emp_id = $_POST['emp_id']; 
$for = $_POST['for'];
$remote_base_dir = '/verification_data/voco_xp/';
$oldPdfUrl = 'https://mounarchtech.com/central_wp/verification_data/voco_xp/' . $agency_id . '/visitor/' .$emp_id. '/' . $for . '/' . $system_date . '.pdf';
$newPdfUrl = $_POST['path'];
 

$mpdf = new Mpdf(['mode' => 'utf-8', 'format' => 'A4']);

try {
    downloadFile($oldPdfUrl, 'pdf1.pdf');
    $pdfFile1 = 'pdf1.pdf';
    $pdfFile2 = 'pdf2.pdf';
    downloadFile($newPdfUrl, $pdfFile2);

    $pages1 = $mpdf->setSourceFile($pdfFile1);
    for ($i = 1; $i <= $pages1; $i++) {
        $tplIdx = $mpdf->importPage($i);
        $mpdf->addPage();
        $mpdf->useTemplate($tplIdx);
    }

    $pages2 = $mpdf->setSourceFile($pdfFile2);
    for ($i = 1; $i <= $pages2; $i++) {
        $tplIdx = $mpdf->importPage($i);
        $mpdf->addPage();
        $mpdf->useTemplate($tplIdx);
    }

    $output_pdf = $mpdf->Output('', 'S');
} catch (Exception $e) {
    $pdfFile1 = 'pdf1.pdf';
    downloadFile($newPdfUrl, $pdfFile1);

    $pages1 = $mpdf->setSourceFile($pdfFile1);
    for ($i = 1; $i <= $pages1; $i++) {
        $tplIdx = $mpdf->importPage($i);
        $mpdf->UseTemplate($tplIdx);
        if ($i < $pages1) {
            $mpdf->AddPage();
        }
    }

    $output_pdf = $mpdf->Output('', 'S');
}

$response = savePDF($output_pdf, $remote_base_dir, $agency_id, $for, $system_date,$visitor_id,$emp_id);
 
    $res[] = ["pdf_url" => $_POST['path'], 'current_wallet_bal' => number_format($_POST['current_wallet_bal'], 2, '.', '')];
    $data = ["error_code" => 100, "message" => "PDF generated successfully.", "data" => $res];
    echo json_encode($data);
    return;
 

?>
