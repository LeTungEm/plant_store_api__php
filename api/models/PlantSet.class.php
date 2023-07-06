<?php
class PlantSet extends Db
{
    public function insertPlantSet($plantId, $plantPrice, $listTool)
    {

        $listTool = json_decode($listTool);
        $arrValueForm = array();
        $arrValue = array();
        foreach ($listTool as $value) {
            $image = isset($value->image) ? $value->image : 'default';
            $price = $value->price;
            $price = $price + $plantPrice;
            $is_sale = isset($value->is_sale) ? $value->is_sale : 0;
            $sale_price = isset($value->sale_price) ? $value->sale_price : 0;

            $arrValue[] = $image;
            $arrValue[] = $price;
            $arrValue[] = $is_sale;
            $arrValue[] = $sale_price;
            $arrValue[] = 1;
            $arrValue[] = $plantId;
            $arrValue[] = $value->tool_id;
            $arrValue[] = $value->color_id;
            $arrValue[] = $value->size_id;
            $arrValue[] = $value->quantity;
            $arrValueForm[] = '(?,?,?,?,?,?,?,?,?,?)';
        }

        $sql = "INSERT INTO `plant_set`(`image`, `price`, `is_sale`, `sale_price`, `status`, `plant_id`, `tool_id`, `tool_color_id`, `tool_size_id`, `tool_quantity`) VALUES " . implode(", ", $arrValueForm) . ';';
        $result = $this->insert($sql, $arrValue);
        if ($result['rowCount'] > 0) {
            return ['message' => true, 'rowCount' => $result['rowCount']];
        } else {
            return ['message' => false];
        }
    }
}
?>