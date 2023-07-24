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
include('../Models/Order.class.php');

$action = isset($_POST["action"]) ? $_POST["action"] : '';
$order = new Order();
$message = array();
switch ($action) {
    case "getAll":
        $message = $order->getAll();
        break;
    case "getByOrderId":
        $orderId = $_POST["orderId"];
        $message = $order->getByOrderId($orderId);
        break;
    case "getAllByAccountId":
        $accountId = $_POST["accountId"];
        $message = $order->getAllByAccountId($accountId);
        break;
    case "cancelOrder":
        $orderId = $_POST["orderId"];
        $message = $order->cancelOrder($orderId);
        break;
    case "insertOrder":
        $transportFee = $_POST["transportFee"];
        $nameReceiver = $_POST["nameReceiver"];
        $phoneReceiver = $_POST["phoneReceiver"];
        $addressReceiver = $_POST["addressReceiver"];
        $isPay = $_POST["isPay"];
        $note = $_POST["note"];
        $deleteReason = $_POST["deleteReason"];
        $payDate = $_POST["payDate"];
        $accountId = $_POST["accountId"];
        $couponId = $_POST["couponId"];
        $shippingProviderId = $_POST["shippingProviderId"];
        $paymentMethodId = $_POST["paymentMethodId"];
        $total = $_POST["total"];

        $message = $order->insertOrder($transportFee, $nameReceiver, $phoneReceiver, $addressReceiver, $isPay, $note, $deleteReason, $payDate, $accountId, $couponId, $shippingProviderId, $paymentMethodId, $total);
        break;
    default:
        $message = "action is not found";
        break;

}

header('Content-Type: application/json; charset=utf-8');
// ob_clean();
echo json_encode($message, JSON_NUMERIC_CHECK);
?>