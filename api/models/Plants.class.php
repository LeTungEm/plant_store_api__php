<?php
include("../../function/function.php");

class Plants extends Db
{
    public function getAll()
    {
        $sql = "SELECT * FROM plants";
        return $this->select($sql);
    }

    public function detail($plantSlug)
    {
        $sql = "SELECT plant_set.plant_set_id, plants.plant_id, plants.name, plants.slug, plants.quantity as plant_quantity, plant_set.tool_quantity, plants.description,plants.score,plants.fun_fact,plants.light,plants.pet_friendly,plants.water,plants.sad_plant_signs,plants.supplier_id, plant_set.image,plant_set.price,plant_set.is_sale,plant_set.sale_price, plant_set.tool_id, tools.name as tool, plant_set.tool_color_id, colors.name as color, colors.code as color_code, plant_set.tool_size_id, sizes.name as size FROM `plants` INNER JOIN plant_set on plant_set.plant_id = plants.plant_id INNER JOIN tools on tools.tool_id = plant_set.tool_id INNER JOIN colors on colors.color_id = plant_set.tool_color_id INNER JOIN sizes on sizes.size_id = plant_set.tool_size_id WHERE plants.status = 1 and plants.slug = ?";
        $data = $this->select($sql, array($plantSlug));
        if (count($data) > 0) {
            return ['message' => true, 'data' => $data];
        } else {
            return ['message' => false];
        }
    }

    public function getByStatus()
    {
        $sql = "SELECT plants_categories.category_ids, plants.plant_id, plants.name, plants.slug, plants.score, GROUP_CONCAT(plant_set.tool_color_id) as tool_color, GROUP_CONCAT(colors.code) as color_code, GROUP_CONCAT(plant_set.tool_size_id) as tool_size, plants.image, COALESCE(max(plant_set.price),0) as max_price, COALESCE(min(plant_set.price),0) as min_price, `is_sale`, COALESCE(max(plant_set.sale_price),0) as max_sale_price, COALESCE(min(plant_set.sale_price),0) as min_sale_price FROM `plant_set` INNER JOIN plants on plant_set.plant_id = plants.plant_id INNER JOIN colors on colors.color_id = plant_set.tool_color_id INNER JOIN (SELECT plants.plant_id, GROUP_CONCAT(plants_categories.category_id) as category_ids FROM plants_categories INNER JOIN plants on plants.plant_id = plants_categories.plant_id GROUP BY plants.plant_id) as plants_categories on plant_set.plant_id = plants_categories.plant_id WHERE plants.status = 1 and plant_set.status = 1 GROUP BY plant_set.plant_id;";
        return $this->select($sql);
    }

    public function search($search)
    {
        $search = '%'.$search.'%';
        $sql = "SELECT plants_categories.category_ids, plants.plant_id, plants.name, plants.slug, plants.score, GROUP_CONCAT(plant_set.tool_color_id) as tool_color, GROUP_CONCAT(colors.code) as color_code, GROUP_CONCAT(plant_set.tool_size_id) as tool_size, plants.image, COALESCE(max(plant_set.price),0) as max_price, COALESCE(min(plant_set.price),0) as min_price, `is_sale`, COALESCE(max(plant_set.sale_price),0) as max_sale_price, COALESCE(min(plant_set.sale_price),0) as min_sale_price FROM `plant_set` INNER JOIN plants on plant_set.plant_id = plants.plant_id INNER JOIN colors on colors.color_id = plant_set.tool_color_id INNER JOIN (SELECT plants.plant_id, GROUP_CONCAT(plants_categories.category_id) as category_ids FROM plants_categories INNER JOIN plants on plants.plant_id = plants_categories.plant_id GROUP BY plants.plant_id) as plants_categories on plant_set.plant_id = plants_categories.plant_id WHERE plants.status = 1 and plant_set.status = 1 and plants.name like ? GROUP BY plant_set.plant_id;";
        return $this->select($sql, array($search));
    }

    public function insertPlant($name, $price, $isSale, $salePrice, $slug, $shortDescription, $description, $funFact, $status, $images, $light, $petFriendly, $water, $sadPlantSigns, $supplierId)
    {
        $sql = "INSERT INTO `plants`(`name`, `price`, `is_sale`, `sale_price`, `slug`, `short_description`, `description`, `fun_fact`, `status`, `images`, `light`, `pet_friendly`, `water`, `sad_plant_signs`, `supplier_id`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $result = $this->insert($sql, array($name, $price, $isSale, $salePrice, $slug, $shortDescription, $description, $funFact, $status, $images, $light, $petFriendly, $water, $sadPlantSigns, $supplierId));
        if ($result['rowCount'] > 0) {
            return ['message' => true];
        } else {
            return ['message' => false];
        }
    }

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