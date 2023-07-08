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
include('../Models/Plants.class.php');

$action = isset($_POST["action"]) ? $_POST["action"] : '';
$plants = new Plants();
$message = array();
switch ($action) {
    case "getAll":
        $message = $plants->getAll();
        break;
    case "getBySlug":
        $plantSlug = $_POST["plantSlug"];
        $message = $plants->getBySlug($plantSlug);
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
        $slug = $_POST["slug"];
        $price = $_POST["price"];
        $description = $_POST["description"];
        $score = $_POST["score"];
        $fun_fact = $_POST["fun_fact"];
        $status = $_POST["status"];
        $image = $_POST["image"];
        $light = $_POST["light"];
        $pet_friendly = $_POST["pet_friendly"];
        $water = $_POST["water"];
        $sad_plant_signs = $_POST["sad_plant_signs"];
        $supplier_id = $_POST["supplier_id"];
        $quantity = $_POST["quantity"];
        $message = $plants->insertPlant($name, $slug, $price, $description, $score, $fun_fact, $status, $image, $light, $pet_friendly, $water, $sad_plant_signs, $supplier_id, $quantity);
        break;
    case "setPlantStatus":
        $status = $_POST["status"];
        $plantId = $_POST["plantId"];
        $message = $plants->setPlantStatus($status, $plantId);
        break;
    case "deletePlant":
        $plantId = $_POST["plantId"];
        $message = $plants->deletePlant($plantId);
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