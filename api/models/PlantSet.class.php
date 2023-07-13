<?php
class PlantSet extends Db
{
    public function insertPlantSet($plantId, $plantPrice, $listTool)
    {

        $listTool = json_decode($listTool);
        $arrValueForm = array();
        $arrValue = array();
        if (count($listTool) > 0) {
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

    public function getAvailableQuantity($listPlantSetId)
    {
        $planSetArr = json_decode($listPlantSetId);
        if (count($planSetArr) > 0) {
            $planSetArr = '(' . implode(", ", $planSetArr) . ')';
            $sql = "SELECT plant_set.plant_set_id, CASE WHEN plant_set.plant_id = 1 THEN plant_set.tool_quantity ELSE CASE WHEN plant_set.tool_quantity >= plants.quantity THEN plants.quantity ELSE plant_set.tool_quantity END END as available_quantity FROM plant_set INNER JOIN plants on plant_set.plant_id = plants.plant_id WHERE plant_set.status = 1 and plant_set.plant_set_id in " . $planSetArr;
            return $this->select($sql);
        }
    }

    public function setStatusByPlantId($status, $plantId)
    {
        $sql = "UPDATE `plant_set` SET `status`= ? WHERE `plant_id` = ?";
        $result = $this->update($sql, array($status, $plantId));
        if ($result['rowCount'] > 0) {
            return ['message' => true];
        } else {
            return ['message' => false];
        }
    }

    public function deletePlantSetByPlantSetId($listPlantSetRemoveId)
    {
        $planSetIdArr = json_decode($listPlantSetRemoveId);
        if (count($planSetIdArr) > 0) {
            $planSetIdArr = '(' . implode(", ", $planSetIdArr) . ')';
            $sql = "DELETE FROM `plant_set` WHERE `plant_set_id` in " . $planSetIdArr;
            $result = $this->delete($sql);
            if ($result['rowCount'] > 0) {
                return ['message' => true];
            } else {
                return ['message' => false];
            }
        } else {
            return ['message' => false];
        }
    }

    public function updatePlantSet($listVariant)
    {
        $planSets = json_decode($listVariant);
        $planSetIdArr = [];
        $arrImageForm = '';
        $arrIsSale = '';
        $arrSalePrice = '';
        if (count($planSets) > 0) {
            foreach ($planSets as $value) {
                $arrImageForm .= " WHEN `plant_set_id` = " . $value->plant_set_id . " THEN '" . $value->image."'";
                $arrIsSale .= " WHEN `plant_set_id` =".$value->plant_set_id." THEN ".$value->is_sale;
                $arrSalePrice .= " WHEN `plant_set_id` =".$value->plant_set_id." THEN ".$value->sale_price;
                $planSetIdArr[] = $value->plant_set_id;
            }

            $planSetIdArr = "(".implode(", ", $planSetIdArr).")";

            $sql = "UPDATE `plant_set` SET `image` = CASE ".$arrImageForm." END, `is_sale` = CASE ".$arrIsSale." END, `sale_price` = CASE ".$arrSalePrice." END WHERE `plant_set_id` in " . $planSetIdArr;
            $result = $this->delete($sql);
            if ($result['rowCount'] > 0) {
                return ['message' => true];
            } else {
                return ['message' => false];
            }


        } else {
            return ['message' => false];
        }
    }



    public function decreateQuantityWhenBuyPlant($plantSetId, $quantity)
    {

        $arr = array(1, 2, 3, 4, 5);
        $arrValue = array();
        $sql = "        
        UPDATE `plant_set` SET plant_set.tool_quantity = plant_set.tool_quantity - ? WHERE plant_set.plant_set_id = ? and (SELECT @minQuantity := CASE WHEN plant_set.tool_quantity >= plants.quantity then plants.quantity ELSE plant_set.tool_quantity end as quantity from plant_set INNER JOIN plants on plants.plant_id = plant_set.plant_id WHERE plant_set.plant_set_id = ?) - ? >= 0;
                
        UPDATE `plants` SET `quantity` = quantity - ? WHERE `plant_id` = (SELECT plant_set.plant_id FROM plant_set WHERE plant_set.plant_set_id = ?) and @minQuantity - ? >= 0;
        ";
        $stringSql = '';

        if (count($arr) > 0) {
            foreach ($arr as $value) {
                // $arrValue[] = $value->quantity;
                // $arrValue[] = $value->plantSetId;
                // $arrValue[] = $value->plantSetId;
                // $arrValue[] = $value->quantity;
                // $arrValue[] = $value->quantity;
                // $arrValue[] = $value->plantSetId;
                // $arrValue[] = $value->quantity;
                $stringSql = $stringSql . $sql;
            }

        }

        echo $stringSql;
        var_dump($arrValue);

        // Thêm transaction 


        // $result = $this->update($stringSql, $arrValue);
        // if ($result['rowCount'] > 0) {
        //     return ['message' => true, 'rowCount' => $result['rowCount']];
        // } else {
        return ['message' => false];
        // }

    }


}
?>