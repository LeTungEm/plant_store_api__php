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
    // case "insertPlant":
    //     $name = $_POST["name"];
    //     $price = $_POST["price"];
    //     $isSale = $_POST["isSale"];
    //     $salePrice = $_POST["salePrice"];
    //     $slug = $_POST["slug"];
    //     $shortDescription = $_POST["shortDescription"];
    //     $description = $_POST["description"];
    //     $funFact = $_POST["funFact"];
    //     $status = $_POST["status"];
    //     $images = $_POST["images"];
    //     $light = $_POST["light"];
    //     $petFriendly = $_POST["petFriendly"];
    //     $water = $_POST["water"];
    //     $sadPlantSigns = $_POST["sadPlantSigns"];
    //     $supplierId = $_POST["supplierId"];
        
    //     $message = $tools->insertTool($name, $price, $isSale, $salePrice, $slug, $shortDescription, $description, $funFact, $status, $images, $light, $petFriendly, $water, $sadPlantSigns, $supplierId);
    //     break;
    // case "deleteAccount":
    //     $email = $_POST["email"];
    //     $message = $plants->deleteAccount($email) > 0;
    //     break;
    // case "updateAccount":
    //     $name = $_POST["name"];
    //     $address = $_POST["address"];
    //     $phone = $_POST["phone"];
    //     $gender = $_POST["gender"];
    //     $birthday = $_POST["birthday"];
    //     $email = $_POST["email"];
    //     $message = $plants->updateAccount($name, $address, $phone, $gender, $birthday, $email);
    //     break;
    default:
        $message = "action is not found";
        break;

}

header('Content-Type: application/json; charset=utf-8');
// ob_clean();
echo json_encode($message, JSON_NUMERIC_CHECK);
?>