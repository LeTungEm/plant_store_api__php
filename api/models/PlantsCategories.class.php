<?php
class PlantsCategories extends Db
{
    public function getByCategoriesSlug($categorySlug)
    {
        $sql = "SELECT plants.plant_id, GROUP_CONCAT(colors.color_id) as tool_color, GROUP_CONCAT(colors.code) as color_code, plants.name, plants.slug, plants.score, plants.image, COALESCE(max(plant_set.price),0) as max_price, COALESCE(min(plant_set.price),0) as min_price, `is_sale`, COALESCE(max(plant_set.sale_price),0) as max_sale_price, COALESCE(min(plant_set.sale_price),0) as min_sale_price FROM `plant_set` INNER JOIN plants on plant_set.plant_id = plants.plant_id INNER JOIN plants_categories on plants_categories.plant_id = plants.plant_id INNER JOIN colors on  colors.color_id = plant_set.tool_color_id INNER JOIN categories on plants_categories.category_id = categories.category_id WHERE plants.status = 1 and categories.slug = ? GROUP BY plant_set.plant_id;";
        return $this->select($sql, array($categorySlug));
    }

    public function insertPlantCategories($plantId, $listCategory)
    {
        $listCategory = json_decode($listCategory);
        $arrValueForm = array();
        $arrValue = array();
        if (count($listCategory) > 0) {
            foreach ($listCategory as $value) {
                $arrValue[] = $value;
                $arrValue[] = $plantId;
                $arrValueForm[] = '(?,?)';
            }

            $sql = "INSERT INTO `plants_categories`(`category_id`, `plant_id`) VALUES " . implode(", ", $arrValueForm) . ';';
            $result = $this->insert($sql, $arrValue);
            if ($result['rowCount'] > 0) {
                return ['message' => true, 'rowCount' => $result['rowCount']];
            } else {
                return ['message' => false];
            }
        } else {
            return ['message' => false];
        }

    }

    public function deletePlantCategoriesByPlantId($plantId)
    {
        $sql = "DELETE FROM `plants_categories` WHERE `plant_id` = ?";
        $result = $this->delete($sql, array($plantId));
        if ($result['rowCount'] > 0) {
            return ['message' => true];
        } else {
            return ['message' => false];
        }
    }

    public function updatePlantCategoriesByPlantId($plantId, $listCategory){
        $this->deletePlantCategoriesByPlantId($plantId);
        $result = $this->insertPlantCategories($plantId, $listCategory);
        if($result['message']){
            return $result;
        }
        return ['message' => false];
    }
}
?>