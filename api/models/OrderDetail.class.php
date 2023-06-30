<?php
class OrderDetail extends Db
{
    public function getAll()
    {
        $sql = "SELECT * FROM `order`";
        return $this->select($sql);
    }

    public function insertOrderDetail($orderId, $orderDetail)
    {
        $arrValueForm = array();
        $arrValue = array();
        foreach($orderDetail as $value){
            $arrValue[] = $value['quantity'];
            $arrValue[] = $value['price'];
            $arrValue[] = $value['price * product.quantity'];
            $arrValue[] = $value['orderId'];
            $arrValue[] = $value['plantSetId'];
            $arrValueForm[] = '(?,?,?,?,?)';
        }
        $sql = "INSERT INTO `order_detail`(`quantity`, `price`, `total`, `order_id`, `plant_set_id`) VALUES ".implode(", ", $arrValueForm).';';
        $data = $this->select($sql, $arrValue);
        if ($data['rowCount'] > 0) {
            return ['message' => true, 'rowCount' => $data['rowCount']];
        } else {
            return ['message' => false];
        }
    }
}
?>