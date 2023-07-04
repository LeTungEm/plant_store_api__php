<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

$files = isset($_FILES["files"]) ? $_FILES["files"] : '';
$imageNames = isset($_POST["names"]) ? $_POST["names"] : '';
$path = '../../uploads/';

// print_r($files);
foreach ($files['tmp_name'] as $tmp_name) {
    print_r($tmp_name);
}
foreach ($imageNames as $imageName) {
    print_r($imageName);
}


// if (move_uploaded_file($file['tmp_name'], $path . $imageName)) {
//     echo json_encode(['message' => true]);
// } else {
//     echo json_encode(['message' => false]);
// }
?>