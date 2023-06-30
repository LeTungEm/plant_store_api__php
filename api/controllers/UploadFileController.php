<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

$file = isset($_FILES["file"]) ? $_FILES["file"] : '';
$imageName = isset($_POST["name"]) ? $_POST["name"] : '';
$path = '../../uploads/';

if (move_uploaded_file($file['tmp_name'], $path . $imageName)) {
    echo json_encode(['message' => true]);
} else {
    echo json_encode(['message' => false]);
}
?>