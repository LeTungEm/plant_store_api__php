<?php
include("../../function/function.php");

class ToolsCategories extends Db
{
    public function getByCategoriesId($categoryId)
    {
        $sql = "SELECT GROUP_CONCAT(colors.color_id) as tool_color, GROUP_CONCAT(colors.code) as color_code, tools.name, tools.slug, tools.score, tools.image, COALESCE(max(plant_set.price),0) as max_price, COALESCE(min(plant_set.price),0) as min_price, `is_sale`, COALESCE(max(plant_set.sale_price),0) as max_sale_price, COALESCE(min(plant_set.sale_price),0) as min_sale_price FROM `plant_set` INNER JOIN tools on plant_set.tool_id = tools.tool_id INNER JOIN tools_categories on tools_categories.tool_id = tools.tool_id INNER JOIN colors on colors.color_id = plant_set.tool_color_id WHERE tools.status = 1 and plant_set.plant_id = 1 and tools_categories.category_id = ? GROUP BY plant_set.tool_id;";
        return $this->select($sql, array($categoryId));
    }
}
?>