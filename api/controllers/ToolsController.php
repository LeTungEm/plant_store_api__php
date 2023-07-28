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
include('../Models/Tools.class.php');

$action = isset($_POST["action"]) ? $_POST["action"] : '';
$tools = new Tools();
$message = array();
switch ($action) {
    case "getAll":
        $message = $tools->getAll();
        break;
    case "getBriefInfo":
        $message = $tools->getBriefInfo();
        break;
    case "detail":
        $toolSlug = $_POST["toolSlug"];
        $message = $tools->detail($toolSlug);
        break;
    case "getByStatus":
        $message = $tools->getByStatus();
        break;
    case "getVariantsByIds":
        $arrId = $_POST["arrId"];
        $message = $tools->getVariantsByIds($arrId);
        break;
    case "search":
        $search = $_POST["search"];
        $message = $tools->search($search);
        break;
    case "setToolStatus":
        $status = $_POST["status"];
        $toolId = $_POST["toolId"];
        $message = $tools->setToolStatus($status, $toolId);
        break;
    case "isSlugExist":
        $toolSlug = $_POST["toolSlug"];
        $message = $tools->isSlugExist($toolSlug);
        break;
    case "getBySlug":
        $toolSlug = $_POST["toolSlug"];
        $message = $tools->getBySlug($toolSlug);
        break;
    case "getVariantByToolId":
        $toolId = $_POST["toolId"];
        $message = $tools->getVariantByToolId($toolId);
        break;
    case "insertTool":
        $name = $_POST["name"];
        $slug = $_POST["slug"];
        $description = $_POST["description"];
        $status = $_POST["status"];
        $image = $_POST["image"];
        $supplier_id = $_POST["supplier_id"];
        $message = $tools->insertTool($name, $slug, $description, $status, $image, $supplier_id);
        break;
    default:
        $message = "action is not found";
        break;

}

header('Content-Type: application/json; charset=utf-8');
// ob_clean();
echo json_encode($message, JSON_NUMERIC_CHECK);
?>