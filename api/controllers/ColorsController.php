<?php
header("Access-Control-Allow-Origin: *");
// header("Content-Type: application/json;");
// header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS");
// header("Access-Control-Max-Age: 3600");
// header("Access-Control-Allow-Headers: Content-Type,
//     Access-Control-Allow-Headers, Authorization, X-Requested-With"); 
// header("Access-Control-Allow-Origin: http://localhost:8080");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

include("../Config/config.php");
include('../Models/Db.class.php');
include('../Models/Colors.class.php');

$action = isset($_POST["action"]) ? $_POST["action"] : '';
$colors = new Colors();
$message = array();
switch ($action) {
    case "getAll":
        $message = $colors->getAll();
        break;
    case "getForManager":
        $message = $colors->getForManager();
        break;
    case "getUsedByPlants":
        $message = $colors->getUsedByPlants();
        break;
    case "getUsedByTools":
        $message = $colors->getUsedByTools();
        break;
    case "createColor":
        $name = $_POST["name"];
        $description = $_POST["description"];
        $code = $_POST["code"];
        $message = $colors->createColor($name, $description, $code);
        break;
    case "isNameExist":
        $name = $_POST["name"];
        $message = $colors->isNameExist($name);
        break;
    case "deleteColor":
        $colorId = $_POST["colorId"];
        $message = $colors->deleteColor($colorId);
        break;
    default:
        $message = "action is not found";
        break;

}

header('Content-Type: application/json; charset=utf-8');
// ob_clean();
echo json_encode($message, JSON_NUMERIC_CHECK);
?>