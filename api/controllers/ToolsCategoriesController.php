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
include('../Models/ToolsCategories.class.php');

$action = isset($_POST["action"]) ? $_POST["action"] : '';
$toolsCategories = new ToolsCategories();
$message = array();
switch ($action) {
    case "getAll":
        $categoryId = $_POST["categoryId"];
        $message = $toolsCategories->getByCategoriesId($categoryId);
        break;
    case "inserToolCategories":
        $toolId = $_POST["toolId"];
        $listCategory = $_POST["listCategory"];
        $message = $toolsCategories->inserToolCategories($toolId, $listCategory);
        break;
    default:
        $message = "action is not found";
        break;

}

header('Content-Type: application/json; charset=utf-8');
// ob_clean();
echo json_encode($message, JSON_NUMERIC_CHECK);
?>