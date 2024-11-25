<?php
require_once __DIR__ . '/vendor/autoload.php';

use Mpdf\Mpdf;
use Mpdf\Output\Destination;

function downloadFile($url, $path) {
    $fileContent = file_get_contents($url);
    if ($fileContent === FALSE) {
        throw new Exception("Could not download file from URL: $url");
    }
    file_put_contents($path, $fileContent);
}

function mergePDFs($pdfFiles) {
    $mpdf = new Mpdf(['mode' => 'utf-8', 'format' => 'A4']);

    foreach ($pdfFiles as $file) {
        $pageCount = $mpdf->setSourceFile($file);
        for ($i = 1; $i <= $pageCount; $i++) {
            $templateId = $mpdf->importPage($i);
            $mpdf->AddPage();
            $mpdf->useTemplate($templateId);
        }
    }

    return $mpdf->Output('', Destination::STRING_RETURN);
}

$oldPdfUrl = 'https://mounarchtech.com/vocoxp/active_folder/agency/invoice/verifications/MEM-00017/invoice_20240703065534_18.pdf';
$newPdfUrl = 'https://mounarchtech.com/vocoxp/active_folder/agency/invoice/verifications/MEM-00017/invoice_20240703065534_18.pdf';

$oldPdfPath = 'old.pdf';
$newPdfPath = 'new.pdf';

try {
    downloadFile($oldPdfUrl, $oldPdfPath);
    downloadFile($newPdfUrl, $newPdfPath);
} catch (Exception $e) {
    echo $e->getMessage();
    exit;
}

$outputPdfContent = '';

if (file_exists($oldPdfPath) && filesize($oldPdfPath) > 0) {
    // Both PDFs are present and old PDF is not empty
    $outputPdfContent = mergePDFs([$oldPdfPath, $newPdfPath]);
} else {
    // Old PDF is empty or does not exist, use new PDF directly
    $outputPdfContent = file_get_contents($newPdfPath);
}

// Output the merged PDF to the browser
header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="merged.pdf"');
echo $outputPdfContent;
?>
