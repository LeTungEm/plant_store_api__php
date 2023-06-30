<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

$imgURL = isset($_GET["imgURL"]) ? $_GET["imgURL"] : '';
$message = array();
if ($imgURL == '') {
    exit;
}
$path = '../../uploads/';
$filePath = $path . $imgURL;
if (file_exists($filePath)) {
    // ob_clean();
    $mime = mime_content_type($filePath);
    header('Content-Type: '.$mime);
    header('Content-Disposition: inline; filename="' . basename($filePath) . '"');
    header('Content-Length: ' . filesize($filePath));
    readfile($filePath);
}else{
    $filePath = $path.'default.jpg';
    $mime = mime_content_type($filePath);
    header('Content-Type: '.$mime);
    header('Content-Disposition: inline; filename="' . basename($filePath) . '"');
    header('Content-Length: ' . filesize($filePath));
    readfile($filePath);
}

?>