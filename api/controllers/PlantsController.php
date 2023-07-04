<?php
// header("Access-Control-Allow-Origin: *");
// header("Content-Type: application/json;");
// header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS");
// header("Access-Control-Max-Age: 3600");
// header("Access-Control-Allow-Headers: Content-Type,
//     Access-Control-Allow-Headers, Authorization, X-Requested-With"); 
header("Access-Control-Allow-Origin: http://localhost:8080");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

include("../Config/config.php");
include('../Models/Db.class.php');
include('../Models/Plants.class.php');

$action = isset($_POST["action"]) ? $_POST["action"] : '';
$plants = new Plants();
$message = array();
switch ($action) {
    case "getAll":
        $message = $plants->getAll();
        break;
    case "detail":
        $plantSlug = $_POST["plantSlug"];
        $message = $plants->detail($plantSlug);
        break;
    case "getVariantsById":
        $plantId = $_POST["plantId"];
        $message = $plants->getVariantsById($plantId);
        break;
    case "isSlugExist":
        $plantSlug = $_POST["plantSlug"];
        $message = $plants->isSlugExist($plantSlug);
        break;
    case "getByStatus":
        $message = $plants->getByStatus();
        break;
    case "search":
        $search = $_POST["search"];
        $message = $plants->search($search);
        break;
    case "insertPlant":
        $name = $_POST["name"];
        $price = $_POST["price"];
        $isSale = $_POST["isSale"];
        $salePrice = $_POST["salePrice"];
        $slug = $_POST["slug"];
        $shortDescription = $_POST["shortDescription"];
        $description = $_POST["description"];
        $funFact = $_POST["funFact"];
        $status = $_POST["status"];
        $images = $_POST["images"];
        $light = $_POST["light"];
        $petFriendly = $_POST["petFriendly"];
        $water = $_POST["water"];
        $sadPlantSigns = $_POST["sadPlantSigns"];
        $supplierId = $_POST["supplierId"];
        
        $message = $plants->insertPlant($name, $price, $isSale, $salePrice, $slug, $shortDescription, $description, $funFact, $status, $images, $light, $petFriendly, $water, $sadPlantSigns, $supplierId);
        break;
    case "setPlantStatus":
        $status = $_POST["status"];
        $plantId = $_POST["plantId"];
        $message = $plants->setPlantStatus($status, $plantId);
        break;
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