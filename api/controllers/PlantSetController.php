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
include('../Models/PlantSet.class.php');

$action = isset($_POST["action"]) ? $_POST["action"] : '';
$plantSet = new PlantSet();
$message = array();
switch ($action) {
    case "getAll":
        $price =  $_POST["price"];
        $colorId =  $_POST["colorId"];
        $productType =  $_POST["productType"];
        $name =  $_POST["name"];
        $selectedId =  $_POST["selectedId"];
        $message = $plantSet->getAll($price, $colorId, $productType, $name, $selectedId);
        break;
    case "insertPlantSet":
        $plantId =  $_POST["plantId"];
        $plantPrice =  $_POST["plantPrice"];
        $listTool =  $_POST["listTool"];
        $message = $plantSet->insertPlantSet($plantId, $plantPrice, $listTool);
        break;
    case "deletePlantSetByPlantId":
        $plantId =  $_POST["plantId"];
        $message = $plantSet->deletePlantSetByPlantId($plantId);
        break;
    case "deletePlantSetByPlantSetId":
        $listPlantSetRemoveId =  $_POST["listPlantSetRemoveId"];
        $message = $plantSet->deletePlantSetByPlantSetId($listPlantSetRemoveId);
        break;
    case "updatePlantSet":
        $listVariant =  $_POST["listVariant"];
        $plantPrice =  $_POST["plantPrice"];
        $message = $plantSet->updatePlantSet($listVariant, $plantPrice);
        break;
    case "getAvailableQuantity":
        $listPlantSetId =  $_POST["listPlantSetId"];
        $message = $plantSet->getAvailableQuantity($listPlantSetId);
        break;
    case "setStatusByPlantId":
        $status =  $_POST["status"];
        $plantId =  $_POST["plantId"];
        $message = $plantSet->setStatusByPlantId($status, $plantId);
        break;
    case "setStatusByToolId":
        $status =  $_POST["status"];
        $toolId =  $_POST["toolId"];
        $message = $plantSet->setStatusByToolId($status, $toolId);
        break;
    case "decreateQuantityWhenBuyPlant":
        $plantSetId =  $_POST["plantSetId"];
        $quantity =  $_POST["quantity"];
        $message = $plantSet->decreateQuantityWhenBuyPlant($plantSetId, $quantity);
        break;
    default:
        $message = "action is not found";
        break;

}

header('Content-Type: application/json; charset=utf-8');
// ob_clean();
echo json_encode($message, JSON_NUMERIC_CHECK);
?>