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
            $sale_price = $is_sale == 1 ? $sale_price : 0;

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

    public function deletePlantSetByPlantId($plantId)
    {
        $sql = "DELETE FROM `plant_set` WHERE `plant_id` = ?";
        $result = $this->delete($sql, array($plantId));
        if ($result['rowCount'] > 0) {
            return ['message' => true];
        } else {
            return ['message' => false];
        }
    }

    public function decreateQuantityWhenBuyPlant($plantSetId, $quantity)
    {
        $sql = "SELECT @plantSetId := ?;
        SELECT @plantId := plants.plant_id, @minQuantity := CASE WHEN plant_set.tool_quantity >= plants.quantity then plants.quantity ELSE plant_set.tool_quantity end as quantity from plant_set INNER JOIN plants on plants.plant_id = plant_set.plant_id WHERE plant_set.plant_set_id = @plantSetId;
        SELECT @saleQuantity := ?;
        UPDATE `plants` SET `quantity` = quantity - @saleQuantity WHERE `plant_id` = @plantId and @minQuantity - @saleQuantity >= 0;
        UPDATE `plant_set` SET plant_set.tool_quantity = plant_set.tool_quantity - @saleQuantity WHERE plant_set.plant_set_id = @plantSetId and @minQuantity - @saleQuantity >= 0;
        ";
        $result = $this->update($sql, array($plantSetId, $quantity));
        if ($result['rowCount'] > 0) {
            return ['message' => true];
        } else {
            return ['message' => false];
        }
    }





    //     SELECT @plantSetId := 7;

    // SELECT @plantId := plants.plant_id, @minQuantity := CASE WHEN plant_set.tool_quantity >= plants.quantity then plants.quantity ELSE plant_set.tool_quantity end as quantity from plant_set INNER JOIN plants on plants.plant_id = plant_set.plant_id WHERE plant_set.plant_set_id = @plantSetId;

    // SELECT @saleQuantity := 2;

    // UPDATE `plants` SET `quantity` = quantity - @saleQuantity WHERE `plant_id` = @plantId and @minQuantity - @saleQuantity >= 0;
// UPDATE `plant_set` SET plant_set.tool_quantity = plant_set.tool_quantity - @saleQuantity WHERE plant_set.plant_set_id = @plantSetId and @minQuantity - @saleQuantity >= 0;



}
?>