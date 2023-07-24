<?php
class Order extends Db
{
    public function getAll()
    {
        $sql = "SELECT `order_id`, accounts.name as user_name,name_receiver,phone_receiver,address_receiver, `transport_fee`,`is_pay`,`note`,`order`.`create_date`,`order`.`update_date`,`confirm_date`, payment_methods.name as payment_method,shipping_providers.name as shipping, `order`.`status`, `total` FROM `order` INNER JOIN shipping_providers on shipping_providers.shipping_provider_id = `order`.`shipping_provider_id` INNER JOIN payment_methods on payment_methods.payment_method_id = `order`.payment_method_id INNER JOIN accounts on accounts.account_id = `order`.`account_id`";
        return $this->select($sql);
    }

    public function getByOrderId($orderId)
    {
        $sql = "SELECT name_receiver,phone_receiver,address_receiver,`transport_fee`,`is_pay`,`order`.`status`,`note`,`order`.`create_date`,payment_methods.name as payment_method,shipping_providers.name as shipping FROM `order` INNER JOIN shipping_providers on shipping_providers.shipping_provider_id = `order`.`shipping_provider_id` INNER JOIN payment_methods on payment_methods.payment_method_id = `order`.payment_method_id INNER JOIN accounts on accounts.account_id = `order`.`account_id` WHERE `order`.`order_id` = ?";
        $data = $this->select($sql, array($orderId));
        if(count($data) > 0){
            return ['message' => true, 'data' => $data[0]];
        }
        return ['message' => false];
    }

    public function getAllByAccountId($accountId)
    {
        $sql = "SELECT `order`.`order_id`,GROUP_CONCAT(DISTINCT CASE WHEN plant_set.plant_id = 1 then tools.name ELSE plants.name END) as product_name, `is_pay`, `order`.status,`order`.create_date as buy_date, `order`.`total` FROM `order` INNER JOIN shipping_providers on shipping_providers.shipping_provider_id = `order`.`shipping_provider_id` INNER JOIN payment_methods on payment_methods.payment_method_id = `order`.payment_method_id INNER JOIN order_detail on order_detail.order_id = `order`.`order_id` INNER JOIN plant_set on plant_set.plant_set_id = order_detail.plant_set_id INNER JOIN tools on plant_set.tool_id = tools.tool_id INNER JOIN plants on plant_set.plant_id = plants.plant_id WHERE `account_id` = ? GROUP by `order`.`order_id`";
        return $this->select($sql, array($accountId));
    }

    public function cancelOrder($orderId)
    {
        $sql = "UPDATE `order` SET `status` = 5 WHERE `order_id` = ?";
        $data = $this->update($sql, array($orderId));
        if ($data['rowCount'] > 0) {
            return ['message' => true];
        } else {
            return ['message' => false];
        }
    }

    public function insertOrder($transportFee, $nameReceiver, $phoneReceiver, $addressReceiver, $isPay, $note, $deleteReason, $payDate, $accountId, $couponId, $shippingProviderId, $paymentMethodId, $total)
    {
        $sql = "INSERT INTO `order`(`transport_fee`, `name_receiver`, `phone_receiver`, `address_receiver`, `is_pay`, `note`, `delete_reason`, `pay_date`, `account_id`, `coupon_id`, `shipping_provider_id`, `payment_method_id`, `total`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $data = $this->insert($sql, array($transportFee, $nameReceiver, $phoneReceiver, $addressReceiver, $isPay, $note, $deleteReason, $payDate, $accountId, $couponId, $shippingProviderId, $paymentMethodId, $total));
        // message: true, orderId: response.insertId
        if ($data['rowCount'] > 0) {
            return ['message' => true, 'orderId' => $data['lastInsertId']];
        } else {
            return ['message' => false];
        }
    }
}
?>