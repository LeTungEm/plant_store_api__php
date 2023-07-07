<?php
include("../../function/function.php");

class Plants extends Db
{
    public function getAll()
    {
        $sql = "SELECT plants.plant_id, plants.name, plants.slug, plants.price, plants.description, plants.score, plants.fun_fact, plants.light,plants.pet_friendly,plants.water,plants.sad_plant_signs,plants.create_date,plants.update_date, plants.image, plants.quantity, plants.status, suppliers.name as supplier_name, GROUP_CONCAT(DISTINCT CONCAT(' ', tools.name)) as tool, cat_names.category_names FROM `plants` LEFT JOIN plant_set on plants.plant_id = plant_set.plant_id LEFT JOIN tools on tools.tool_id = plant_set.tool_id LEFT JOIN suppliers on plants.supplier_id = suppliers.supplier_id LEFT JOIN (select plants.plant_id, GROUP_CONCAT(DISTINCT CONCAT(' ', categories.name)) as category_names from plants INNER JOIN plants_categories on plants.plant_id = plants_categories.plant_id INNER JOIN categories on plants_categories.category_id = categories.category_id GROUP by plants.plant_id) as cat_names on plants.plant_id = cat_names.plant_id where plants.plant_id <> 1 GROUP BY plants.plant_id;";
        return $this->select($sql);
    }

    public function getBySlug($plantSlug)
    {
        $sql = "SELECT plants.plant_id, plants.name, plants.slug, plants.price, plants.description, plants.score, plants.fun_fact, plants.light,plants.pet_friendly,plants.water,plants.sad_plant_signs,plants.create_date,plants.update_date, plants.image, plants.quantity, plants.status, suppliers.name as supplier_name, GROUP_CONCAT(DISTINCT CONCAT(' ', tools.name)) as tool, cat_names.category_names FROM `plants` LEFT JOIN plant_set on plants.plant_id = plant_set.plant_id LEFT JOIN tools on tools.tool_id = plant_set.tool_id LEFT JOIN suppliers on plants.supplier_id = suppliers.supplier_id LEFT JOIN (select plants.plant_id, GROUP_CONCAT(DISTINCT CONCAT(' ', categories.name)) as category_names from plants INNER JOIN plants_categories on plants.plant_id = plants_categories.plant_id INNER JOIN categories on plants_categories.category_id = categories.category_id GROUP by plants.plant_id) as cat_names on plants.plant_id = cat_names.plant_id where plants.plant_id <> 1 and plants.slug = ? GROUP BY plant_set.plant_id;";
        $data = $this->select($sql, array($plantSlug));
        if (count($data) > 0) {
            return ['message' => true, 'data' => $data[0]];
        } else {
            return ['message' => false];
        }
    }

    public function getVariantsById($plantId)
    {
        $sql = "SELECT plant_set.plant_set_id, plant_set.plant_id, tools.tool_id, tools.name, plant_set.image, plant_set.tool_quantity as quantity, plant_set.price, plant_set.is_sale, plant_set.sale_price, colors.color_id, colors.name as color_name, sizes.size_id, sizes.name as size_name, t1.price as tool_price FROM `plant_set` INNER JOIN tools on plant_set.tool_id = tools.tool_id INNER JOIN colors on colors.color_id = plant_set.tool_color_id INNER JOIN sizes on sizes.size_id = plant_set.tool_size_id INNER JOIN (SELECT DISTINCT `tool_id`,`tool_color_id`,`tool_size_id`,case WHEN `is_sale` = 1 THEN `sale_price` ELSE `price` END as price FROM plant_set WHERE plant_id = 1 and status = 1) as t1 on plant_set.tool_id = t1.tool_id and plant_set.tool_color_id = t1.tool_color_id and plant_set.tool_size_id = t1.tool_size_id WHERE plant_set.status = 1 and plant_set.plant_id = ?;";
        return $this->select($sql, array($plantId));
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

    public function isSlugExist($plantSlug)
    {
        $sql = "SELECT count(*) as slugCount from `plants` where plants.slug = ?";
        $data = $this->select($sql, array($plantSlug));
        if ($data[0]['slugCount'] > 0) {
            return ['message' => true];
        } else {
            return ['message' => false];
        }
    }

    public function getByStatus()
    {
        $sql = "SELECT COALESCE(plants_categories.category_ids, '') as category_ids, plants.plant_id, plants.name, plants.slug, plants.score, GROUP_CONCAT(plant_set.tool_color_id) as tool_color, GROUP_CONCAT(colors.code) as color_code, GROUP_CONCAT(plant_set.tool_size_id) as tool_size, plants.image, COALESCE(max(plant_set.price),0) as max_price, COALESCE(min(plant_set.price),0) as min_price, `is_sale`, COALESCE(max(plant_set.sale_price),0) as max_sale_price, COALESCE(min(plant_set.sale_price),0) as min_sale_price FROM `plant_set` INNER JOIN plants on plant_set.plant_id = plants.plant_id INNER JOIN colors on colors.color_id = plant_set.tool_color_id LEFT JOIN (SELECT plants.plant_id, GROUP_CONCAT(plants_categories.category_id) as category_ids FROM plants_categories INNER JOIN plants on plants.plant_id = plants_categories.plant_id GROUP BY plants.plant_id) as plants_categories on plant_set.plant_id = plants_categories.plant_id WHERE plants.status = 1 and plant_set.status = 1 GROUP BY plant_set.plant_id;";
        return $this->select($sql);
    }

    public function search($search)
    {
        if ($search == '') {
            return [];
        }
        $search = '%' . $search . '%';
        $sql = "SELECT COALESCE(plants_categories.category_ids, '') as category_ids, plants.plant_id, plants.name, plants.slug, plants.score, GROUP_CONCAT(plant_set.tool_color_id) as tool_color, GROUP_CONCAT(colors.code) as color_code, GROUP_CONCAT(plant_set.tool_size_id) as tool_size, plants.image, COALESCE(max(plant_set.price),0) as max_price, COALESCE(min(plant_set.price),0) as min_price, `is_sale`, COALESCE(max(plant_set.sale_price),0) as max_sale_price, COALESCE(min(plant_set.sale_price),0) as min_sale_price FROM `plant_set` INNER JOIN plants on plant_set.plant_id = plants.plant_id INNER JOIN colors on colors.color_id = plant_set.tool_color_id LEFT JOIN (SELECT plants.plant_id, GROUP_CONCAT(plants_categories.category_id) as category_ids FROM plants_categories INNER JOIN plants on plants.plant_id = plants_categories.plant_id GROUP BY plants.plant_id) as plants_categories on plant_set.plant_id = plants_categories.plant_id WHERE plants.status = 1 and plant_set.status = 1 and plants.name like ? GROUP BY plant_set.plant_id;";
        return $this->select($sql, array($search));
    }

    public function insertPlant($name, $slug, $price, $description, $score, $fun_fact, $status, $image, $light, $pet_friendly, $water, $sad_plant_signs, $supplier_id, $quantity)
    {
        $sql = "INSERT INTO `plants`(`name`, `slug`, `price`, `description`, `score`, `fun_fact`, `status`, `image`, `light`, `pet_friendly`, `water`, `sad_plant_signs`, `supplier_id`, `quantity`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $result = $this->insert($sql, array($name, $slug, $price, $description, $score, $fun_fact, $status, $image, $light, $pet_friendly, $water, $sad_plant_signs, $supplier_id, $quantity));
        if ($result['rowCount'] > 0) {
            return ['message' => true, 'plant_id' => $result['lastInsertId']];
        } else {
            return ['message' => false];
        }
    }

    public function setPlantStatus($status, $plantId)
    {
        $sql = "UPDATE `plants` SET `status`= ? WHERE `plant_id` = ?";
        $result = $this->update($sql, array($status, $plantId));
        if ($result['rowCount'] > 0) {
            return ['message' => true];
        } else {
            return ['message' => false];
        }
    }

    public function deletePlant($plantId)
    {
        $sql = "DELETE FROM `plants` WHERE `plant_id` = ?";
        $result = $this->delete($sql, array($plantId));
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