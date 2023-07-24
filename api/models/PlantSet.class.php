<?php
class PlantSet extends Db
{

    public function getAll($price, $colorId, $productType, $name, $selectedId)
    {
        $name = "'%" . $name . "%'";
        $queryProductType = '';
        $priceQuery = '';
        $distinctQuery = '';
        $colorQuery = '';
        if($colorId != 0){
            $colorQuery = " AND plant_set.tool_color_id = " . $colorId;
        }
        if ($productType == 1) {
            $queryProductType = "plant_set.plant_id = 1";
        } else {
            $queryProductType = "plant_set.plant_id <> 1";
        }
        if ($price < 3000000) {
            $priceQuery = "t_price.price > " . $price . " AND t_price.price <= " . intval($price + 200000);
        } else {
            $priceQuery = "t_price.price > " . $price;
        }

        $encodeSelectedId = json_decode($selectedId);
        if (count($encodeSelectedId) > 0) {
            $selectedId = "(" . implode(',', $encodeSelectedId) . ")";
            $distinctQuery = " AND plant_set.plant_set_id not in " . $selectedId;
        }
        $sql = "SELECT CASE WHEN plant_set.plant_id = 1 THEN plant_set.tool_quantity ELSE CASE WHEN plants.quantity >= plant_set.tool_quantity THEN plant_set.tool_quantity ELSE plants.quantity END END as max_quantity, CASE WHEN plant_set.plant_id = 1 THEN GROUP_CONCAT(CONCAT(tools.name, ' / ', colors.name, ' / ', sizes.name)) ELSE GROUP_CONCAT(CONCAT(plants.name, ' / ' , tools.name, ' / ', colors.name, ' / ', sizes.name)) END AS name,t_price.price, plant_set.`plant_set_id`, plant_set.`image` FROM (SELECT plant_set_id, CASE WHEN is_sale = 1 THEN sale_price ELSE price end as price FROM `plant_set`) AS t_price INNER JOIN plant_set ON plant_set.plant_set_id = t_price.plant_set_id INNER JOIN plants ON plants.plant_id = plant_set.plant_id INNER JOIN tools ON tools.tool_id = plant_set.tool_id INNER JOIN colors ON colors.color_id = plant_set.tool_color_id INNER JOIN sizes ON sizes.size_id = plant_set.tool_size_id WHERE " . $priceQuery . " AND " . $queryProductType . " ".$colorQuery ." ".$distinctQuery. " GROUP BY plant_set.plant_set_id HAVING name like " . $name;
        return $this->select($sql);
    }

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

    public function setStatusByToolId($status, $toolId)
    {
        $sql = "UPDATE `plant_set` SET `status`= ? WHERE `tool_id` = ?";
        $result = $this->update($sql, array($status, $toolId));
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
            $sql = "UPDATE `plant_set` SET `status`= 0 WHERE `plant_set_id` in " . $planSetIdArr;
            $result = $this->delete($sql);
            if ($result['rowCount'] > 0) {
                return ['message' => true];
            } else {
                return ['message' => false];
            }
        } else {
            return ['message' => false, 'err' => true];
        }
    }

    public function updatePlantSet($listVariant, $plantPrice)
    {
        $planSets = json_decode($listVariant);
        $planSetIdArr = [];
        $arrImageForm = '';
        $arrIsSale = '';
        $arrSalePrice = '';
        $arrPrice = '';
        if (count($planSets) > 0) {
            foreach ($planSets as $value) {
                $arrImageForm .= " WHEN `plant_set_id` = " . $value->plant_set_id . " THEN '" . $value->image . "'";
                $arrIsSale .= " WHEN `plant_set_id` = " . $value->plant_set_id . " THEN " . $value->is_sale;
                $arrSalePrice .= " WHEN `plant_set_id` = " . $value->plant_set_id . " THEN " . ($value->sale_price == 0 ? 'null' : $value->sale_price);
                $arrPrice .= " WHEN `plant_set_id` = " . $value->plant_set_id . " THEN " . ($value->tool_price + $plantPrice);
                $planSetIdArr[] = $value->plant_set_id;
            }

            $planSetIdArr = "(" . implode(", ", $planSetIdArr) . ")";

            $sql = "UPDATE `plant_set` SET `price` = CASE " . $arrPrice . " END,`image` = CASE " . $arrImageForm . " END, `is_sale` = CASE " . $arrIsSale . " END, `sale_price` = CASE " . $arrSalePrice . " END, `status` = 1 WHERE `plant_set_id` in " . $planSetIdArr;
            echo $sql;
            $result = $this->update($sql);
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