<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

$files = isset($_FILES["files"]) ? $_FILES["files"] : '';
$imageNames = isset($_POST["names"]) ? $_POST["names"] : '';
$path = '../../uploads/';

foreach ($files['tmp_name'] as $index => $tmp_name) {
    $tmp = $tmp_name;
    $imageName = $imageNames[$index];
    if (move_uploaded_file($tmp, $path . $imageName)) {
        echo json_encode(['message' => true]);
    } else {
        echo json_encode(['message' => false]);
    }
}

?>