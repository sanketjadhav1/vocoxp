<?php
require_once __DIR__ . '/vendor/autoload.php';

use Mpdf\Mpdf;

include_once "connection.php";
$connection = connection::getInstance();
$mysqli = $connection->getConnection();

$fetch_member = "select universal_report from member_header_all where member_id='$member_id' and registration_id='$agency_id'";
$res_member = mysqli_query($mysqli, $fetch_member);
$arr_member = mysqli_fetch_assoc($res_member);


if ($arr_member['universal_report'] == "" || $arr_member['universal_report'] == null) {
    $update_pdf = "update member_header_all set universal_report='$path' where member_id='$member_id' and registration_id='$agency_id'";
    $res_member = mysqli_query($mysqli, $update_pdf);
} else {

// Define $old_pdf and $new_pdf before using them
$old_pdf = $arr_member['universal_report'];
$new_pdf = $path;

// Paths to the PDF files you want to merge
$pdfFile1 = 'verification/universal_pdf/' . $agency_id . '/old.pdf'; // Local file path
$pdfFile2 = 'verification/universal_pdf/' . $agency_id . '/new.pdf'; // Local file path



// Initialize mPDF
$mpdf = new Mpdf(['format' => [640, 1000]]);

// Import the first PDF file
$pages1 = $mpdf->setSourceFile($pdfFile1);
for ($i = 1; $i <= $pages1; $i++) {
    $tplIdx = $mpdf->importPage($i);
    $mpdf->addPage();
    $mpdf->useTemplate($tplIdx);
}

// Import the second PDF file
$pages2 = $mpdf->setSourceFile($pdfFile2);
for ($i = 1; $i <= $pages2; $i++) {
    $tplIdx = $mpdf->importPage($i);
    $mpdf->addPage();
    $mpdf->useTemplate($tplIdx);
}

// Output the merged PDF
$output_pdf = $mpdf->Output('', 'S');

// Create the target directory if it doesn't exist
$target_dir_profile = __DIR__ . "/verification/universal_pdf/$agency_id";
if (!file_exists($target_dir_profile)) {
    if (!mkdir($target_dir_profile, 0777, true)) {
        die('Failed to create directory.');
    }
}

// Save the merged PDF file
$file_name = 'integrated_' . date("YmdHis") . '.pdf';
$file_path = $target_dir_profile . '/' . $file_name;
if (!file_put_contents($file_path, $output_pdf)) {
    die('Failed to save merged PDF file.');
}




// Update the database with the path to the merged PDF file
$base_url = "https://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/verification/universal_pdf/$agency_id";
$path = $base_url . '/' . $file_name;


// Download PDF files from URLs and save them locally
if (!$path) {
    die('Failed to save old PDF file.');
}






$update_pdf = "UPDATE member_header_all SET universal_report='$path' WHERE member_id='$member_id' AND registration_id='$agency_id'";
$res_member = mysqli_query($mysqli, $update_pdf);

}
