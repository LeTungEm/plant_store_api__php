<?php
class OrderDetail extends Db
{
    public function getAll()
    {
        $sql = "SELECT * FROM `order`";
        return $this->select($sql);
    }

    public function getByOrderId($orderId)
    {
        $sql = "SELECT plant_set.image, CASE WHEN plant_set.plant_id = 1 THEN plant_set.tool_quantity ELSE CASE WHEN plants.quantity >= plant_set.tool_quantity THEN plant_set.tool_quantity ELSE plants.quantity END END as max_quantity, CASE WHEN plant_set.plant_id = 1 THEN tools.slug ELSE plants.slug END as slug, CASE WHEN plant_set.plant_id = 1 THEN tools.name ELSE plants.name END as name, CASE WHEN plant_set.plant_id = 1 THEN CONCAT(colors.name,' / ', sizes.name) ELSE CONCAT (tools.name,' / ', colors.name,' / ', sizes.name) END as variant_name, order_detail.price, order_detail.plant_set_id as plantSetId, order_detail.quantity, order_detail.total FROM `order_detail` INNER JOIN plant_set on order_detail.plant_set_id = plant_set.plant_set_id INNER JOIN plants on plant_set.plant_id = plants.plant_id INNER JOIN tools on plant_set.tool_id = tools.tool_id INNER JOIN colors on plant_set.tool_color_id = colors.color_id INNER JOIN sizes on plant_set.tool_size_id = sizes.size_id WHERE order_detail.order_id = ? GROUP BY plant_set.plant_set_id";
        return $this->select($sql, array($orderId));
    }

    public function insertOrderDetail($orderId, $orderDetail)
    {
        $orderDetail = json_decode($orderDetail);
        $arrValueForm = array();
        $arrValue = array();
        foreach($orderDetail as $value){
            $arrValue[] = $value->quantity;
            $arrValue[] = $value->price;
            $arrValue[] = $value->price*$value->quantity;
            $arrValue[] = $orderId;
            $arrValue[] = $value->plantSetId;
            $arrValueForm[] = '(?,?,?,?,?)';
        }
        $sql = "INSERT INTO `order_detail`(`quantity`, `price`, `total`, `order_id`, `plant_set_id`) VALUES ".implode(", ", $arrValueForm).';';
        $data = $this->insert($sql, $arrValue);
        if ($data['rowCount'] > 0) {
            return ['message' => true, 'rowCount' => $data['rowCount']];
        } else {
            return ['message' => false];
        }
    }
}
?>