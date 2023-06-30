<?php
class Order extends Db
{
    public function getAll()
    {
        $sql = "SELECT * FROM `order`";
        return $this->select($sql);
    }

    public function insertOrder($transportFee, $nameReceiver, $phoneReceiver, $addressReceiver, $isPay, $note, $deleteReason, $payDate, $accountId, $couponId, $shippingProviderId, $paymentMethodId, $total)
    {
        $sql = "INSERT INTO `order`(`transport_fee`, `name_receiver`, `phone_receiver`, `address_receiver`, `is_pay`, `note`, `delete_reason`, `pay_date`, `account_id`, `coupon_id`, `shipping_provider_id`, `payment_method_id`, `total`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $data = $this->select($sql, array($transportFee, $nameReceiver, $phoneReceiver, $addressReceiver, $isPay, $note, $deleteReason, $payDate, $accountId, $couponId, $shippingProviderId, $paymentMethodId, $total));
        // message: true, orderId: response.insertId
        if ($data['rowCount'] > 0) {
            return ['message' => true, 'orderId' => $data['lastInsertId']];
        } else {
            return ['message' => false];
        }
    }
}
?>