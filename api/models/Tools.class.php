<?php
include("../../function/function.php");

class Tools extends Db
{
    public function getAll()
    {
        $sql = "SELECT * FROM `tools`";
        return $this->select($sql);
    }

    public function detail($toolSlug)
    {
        $sql = "SELECT plant_set.plant_set_id, plant_set.plant_id, tools.name, tools.slug, plant_set.tool_quantity, tools.description,tools.score, tools.supplier_id, plant_set.image,plant_set.price,plant_set.is_sale,plant_set.sale_price, plant_set.tool_id, plant_set.tool_color_id, colors.name as color, colors.code as color_code, plant_set.tool_size_id, sizes.name as size FROM `plant_set` INNER JOIN `tools` on plant_set.tool_id = tools.tool_id INNER JOIN colors on colors.color_id = plant_set.tool_color_id INNER JOIN sizes on sizes.size_id = plant_set.tool_size_id WHERE tools.status = 1 and plant_set.plant_id = 1 and tools.slug = ?";
        $data = $this->select($sql, array($toolSlug));
        if (count($data) > 0) {
            return ['message' => true, 'data' => $data];
        } else {
            return ['message' => false];
        }
    }

    public function getByStatus()
    {
        $sql = "SELECT tools_categories.category_ids,tools.tool_id , tools.name, tools.slug, tools.score, GROUP_CONCAT(plant_set.tool_color_id) as tool_color, GROUP_CONCAT(colors.code) as color_code, GROUP_CONCAT(plant_set.tool_size_id) as tool_size, tools.image, COALESCE(max(plant_set.price),0) as max_price, COALESCE(min(plant_set.price),0) as min_price, `is_sale`, COALESCE(max(plant_set.sale_price),0) as max_sale_price, COALESCE(min(plant_set.sale_price),0) as min_sale_price FROM `plant_set` INNER JOIN tools on plant_set.tool_id = tools.tool_id INNER JOIN colors on colors.color_id = plant_set.tool_color_id INNER JOIN (SELECT tools.tool_id, GROUP_CONCAT(tools_categories.category_id) as category_ids FROM tools_categories INNER JOIN tools on tools.tool_id = tools_categories.tool_id GROUP BY tools.tool_id) as tools_categories on plant_set.tool_id = tools_categories.tool_id WHERE tools.status = 1 and plant_set.plant_id = 1 and plant_set.status = 1 GROUP BY plant_set.tool_id;";
        return $this->select($sql);
    }

    public function search($search)
    {
        $search = '%'.$search.'%';
        $sql = "SELECT tools_categories.category_ids,tools.tool_id , tools.name, tools.slug, tools.score, GROUP_CONCAT(plant_set.tool_color_id) as tool_color, GROUP_CONCAT(colors.code) as color_code, GROUP_CONCAT(plant_set.tool_size_id) as tool_size, tools.image, COALESCE(max(plant_set.price),0) as max_price, COALESCE(min(plant_set.price),0) as min_price, `is_sale`, COALESCE(max(plant_set.sale_price),0) as max_sale_price, COALESCE(min(plant_set.sale_price),0) as min_sale_price FROM `plant_set` INNER JOIN tools on plant_set.tool_id = tools.tool_id INNER JOIN colors on colors.color_id = plant_set.tool_color_id INNER JOIN (SELECT tools.tool_id, GROUP_CONCAT(tools_categories.category_id) as category_ids FROM tools_categories INNER JOIN tools on tools.tool_id = tools_categories.tool_id GROUP BY tools.tool_id) as tools_categories on plant_set.tool_id = tools_categories.tool_id WHERE tools.status = 1 and plant_set.plant_id = 1 and plant_set.status = 1 and tools.name like ? GROUP BY plant_set.tool_id;";
        return $this->select($sql, array($search));
    }

    // public function insertTool($name, $price, $isSale, $salePrice, $slug, $shortDescription, $description, $funFact, $status, $images, $light, $petFriendly, $water, $sadPlantSigns, $supplierId)
    // {
    //     $sql = "INSERT INTO `plants`(`name`, `price`, `is_sale`, `sale_price`, `slug`, `short_description`, `description`, `fun_fact`, `status`, `images`, `light`, `pet_friendly`, `water`, `sad_plant_signs`, `supplier_id`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
    //     $result = $this->insert($sql, array($name, $price, $isSale, $salePrice, $slug, $shortDescription, $description, $funFact, $status, $images, $light, $petFriendly, $water, $sadPlantSigns, $supplierId));
    //     if ($result['rowCount'] > 0) {
    //         return ['message' => true];
    //     } else {
    //         return ['message' => false];
    //     }
    // }

    // public function deleteAccount($email)
    // {
    //     $sql = "UPDATE `accounts` SET `status`= 0 WHERE `email` = ?";
    //     $result = $this->delete($sql, array($email));
    //     if ($result > 0) {
    //         return ['message' => true];
    //     } else {
    //         return ['message' => false];
    //     }
    // }

    // public function updateAccount($name, $address, $phone, $gender, $birthday, $email)
    // {
    //     $sql = "UPDATE `accounts` SET `name`= ?,`address`= ?,`phone`= ?,`gender`= ?,`birthday`= ? WHERE `email` = ?";
    //     $result = $this->update($sql, array($name, $address, $phone, $gender, $birthday, $email));
    //     if ($result > 0) {
    //         return ['message' => true];
    //     } else {
    //         return ['message' => false];
    //     }
    // }
}
?>