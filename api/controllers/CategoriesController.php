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
include('../Models/Categories.class.php');

$action = isset($_POST["action"]) ? $_POST["action"] : '';
$categories = new Categories();
$message = array();
switch ($action) {
    case "getAll":
        $message = $categories->getAll();
        break;
    // case "detail":
    //     $email = $_POST["email"];
    //     $result = $Accounts->detail($email);
    //     if ($result['message']) {
    //         $message = $result['data'];
    //     }
    //     break;
    case "getByStatus":
        $message = $categories->getByStatus();
        break;
    case "getByParentSlug":
        $parentSlug = $_POST["parentSlug"];
        $message = $categories->getByParentSlug($parentSlug);
        break;
    case "getDisplayCategories":
        $message = $categories->getDisplayCategories();
        break;
    case "getSpecialCategories":
        $message = $categories->getSpecialCategories();
        break;
    // case "deleteAccount":
    //     $email = $_POST["email"];
    //     $message = $Accounts->deleteAccount($email) > 0;
    //     break;
    // case "updateAccount":
    //     $name = $_POST["name"];
    //     $address = $_POST["address"];
    //     $phone = $_POST["phone"];
    //     $gender = $_POST["gender"];
    //     $birthday = $_POST["birthday"];
    //     $email = $_POST["email"];
    //     $message = $Accounts->updateAccount($name, $address, $phone, $gender, $birthday, $email);
    //     break;
    default:
        $message = "action is not found";
        break;

}

header('Content-Type: application/json; charset=utf-8');
// ob_clean();
echo json_encode($message, JSON_NUMERIC_CHECK);
?>