<?php
include("../../function/function.php");

class Tools extends Db
{
    public function getAll()
    {
        $sql = "SELECT tools.tool_id, tools.name, tools.slug, GROUP_CONCAT(DISTINCT CONCAT(' ', plant_set.price)) as tool_prices, tools.description, tools.score, tools.create_date, tools.update_date, tools.image, plant_set.tool_quantity, tools.status, suppliers.name as supplier_name, cat_names.category_names, GROUP_CONCAT(DISTINCT CONCAT(' ', colors.name)) as tool_colors, GROUP_CONCAT(DISTINCT CONCAT(' ', sizes.name)) as tool_sizes FROM `tools` LEFT JOIN plant_set on tools.tool_id = plant_set.tool_id LEFT JOIN suppliers on tools.supplier_id = suppliers.supplier_id LEFT JOIN (select tools.tool_id, GROUP_CONCAT(DISTINCT CONCAT(' ', categories.name)) as category_names from tools INNER JOIN tools_categories on tools.tool_id = tools_categories.tool_id INNER JOIN categories on tools_categories.category_id = categories.category_id GROUP by tools.tool_id) as cat_names on tools.tool_id = cat_names.tool_id LEFT JOIN colors ON plant_set.tool_color_id = colors.color_id LEFT JOIN sizes on plant_set.tool_size_id = sizes.size_id where tools.tool_id <> 1 and plant_set.plant_id = 1 GROUP BY tools.tool_id;";
        return $this->select($sql);
    }

    public function getBriefInfo()
    {
        $sql = "SELECT name, image, tool_id FROM `tools` where tools.tool_id <> 1 and tools.status = 1";
        return $this->select($sql);
    }

    public function getVariantsByIds($arrId)
    {
        $arrId = json_decode($arrId);
        if (count($arrId) > 0) {
            $stringIds = implode(', ', $arrId);
            $stringIds = '(' . $stringIds . ');';
            $sql = "SELECT tools.tool_id, tools.name, plant_set.status,plant_set.image as tool_image, plant_set.tool_quantity as quantity, CASE WHEN plant_set.is_sale = 1 THEN plant_set.sale_price ELSE plant_set.price END as price, colors.color_id, colors.name as color_name, sizes.size_id, sizes.name as size_name FROM `plant_set` INNER JOIN tools on plant_set.tool_id = tools.tool_id INNER JOIN colors on colors.color_id = plant_set.tool_color_id INNER JOIN sizes on sizes.size_id = plant_set.tool_size_id WHERE plant_set.status = 1 and plant_set.plant_id = 1 and plant_set.tool_id in " . $stringIds;
            return $this->select($sql);
        }
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
        $sql = "SELECT max(plant_set.tool_quantity) as max_quantity, COALESCE(tools_categories.category_ids, '') as category_ids, tools.tool_id , tools.name, tools.slug, tools.score, GROUP_CONCAT(plant_set.tool_color_id) as tool_color, GROUP_CONCAT(colors.code) as color_code, GROUP_CONCAT(plant_set.tool_size_id) as tool_size, tools.image, COALESCE(max(plant_set.price),0) as max_price, COALESCE(min(plant_set.price),0) as min_price, SUM(`is_sale`) as is_sale, COALESCE(max(plant_set.sale_price),0) as max_sale_price, COALESCE(min(plant_set.sale_price),0) as min_sale_price FROM `plant_set` INNER JOIN tools on plant_set.tool_id = tools.tool_id INNER JOIN colors on colors.color_id = plant_set.tool_color_id LEFT JOIN (SELECT tools.tool_id, GROUP_CONCAT(tools_categories.category_id) as category_ids FROM tools_categories INNER JOIN tools on tools.tool_id = tools_categories.tool_id GROUP BY tools.tool_id) as tools_categories on plant_set.tool_id = tools_categories.tool_id WHERE tools.status = 1 and plant_set.plant_id = 1 and plant_set.status = 1 GROUP BY plant_set.tool_id;";
        return $this->select($sql);
    }

    public function search($search)
    {
        if ($search == '') {
            return [];
        }
        $search = '%' . $search . '%';
        $sql = "SELECT max(plant_set.tool_quantity) as max_quantity, COALESCE(tools_categories.category_ids, '') as category_ids, tools.tool_id , tools.name, tools.slug, tools.score, GROUP_CONCAT(plant_set.tool_color_id) as tool_color, GROUP_CONCAT(colors.code) as color_code, GROUP_CONCAT(plant_set.tool_size_id) as tool_size, tools.image, COALESCE(max(plant_set.price),0) as max_price, COALESCE(min(plant_set.price),0) as min_price, SUM(`is_sale`) as is_sale, COALESCE(max(plant_set.sale_price),0) as max_sale_price, COALESCE(min(plant_set.sale_price),0) as min_sale_price FROM `plant_set` INNER JOIN tools on plant_set.tool_id = tools.tool_id INNER JOIN colors on colors.color_id = plant_set.tool_color_id LEFT JOIN (SELECT tools.tool_id, GROUP_CONCAT(tools_categories.category_id) as category_ids FROM tools_categories INNER JOIN tools on tools.tool_id = tools_categories.tool_id GROUP BY tools.tool_id) as tools_categories on plant_set.tool_id = tools_categories.tool_id WHERE tools.status = 1 and plant_set.plant_id = 1 and plant_set.status = 1 and tools.name like ? GROUP BY plant_set.tool_id;";
        return $this->select($sql, array($search));
    }

    public function setToolStatus($status, $toolId)
    {
        $sql = "UPDATE `tools` SET `status`= ? WHERE `tool_id` = ?";
        $result = $this->update($sql, array($status, $toolId));
        if ($result['rowCount'] > 0) {
            return ['message' => true];
        } else {
            return ['message' => false];
        }
    }

    public function insertTool($name, $slug, $description, $status, $image, $supplier_id)
    {
        $sql = "INSERT INTO `tools`(`name`, `slug`, `description`,`status`, `image`, `supplier_id`) VALUES (?,?,?,?,?,?)";
        $result = $this->insert($sql, array($name, $slug, $description, $status, $image, $supplier_id));
        if ($result['rowCount'] > 0) {
            return ['message' => true, 'tool_id' => $result['lastInsertId']];
        } else {
            return ['message' => false];
        }
    }

    public function isSlugExist($toolSlug)
    {
        $sql = "SELECT count(*) as slugCount from `tools` where tools.slug = ?";
        $data = $this->select($sql, array($toolSlug));
        if ($data[0]['slugCount'] > 0) {
            return ['message' => true];
        } else {
            return ['message' => false];
        }
    }

    public function getBySlug($toolSlug)
    {
        $sql = "SELECT category_ids.category_ids, tools.`tool_id`,`name`,tools.`slug`,`description`,tools.`status`,tools.`image`,`supplier_id`, max(plant_set.price) as price, max(plant_set.tool_quantity) as quantity  FROM `tools` INNER JOIN plant_set on plant_set.tool_id = tools.tool_id LEFT JOIN (SELECT tools_categories.tool_id, GROUP_CONCAT(categories.category_id) as category_ids FROM tools_categories INNER JOIN categories on tools_categories.category_id = categories.category_id INNER JOIN tools on tools.tool_id = tools_categories.tool_id WHERE tools.slug = ?) as category_ids on category_ids.tool_id = tools.tool_id WHERE tools.`slug` = ? AND plant_set.plant_id = 1;";
        $data = $this->select($sql, array($toolSlug, $toolSlug));
        if (count($data) > 0) {
            return ['message' => true, 'data' => $data[0]];
        } else {
            return ['message' => false];
        }
    }

    public function getVariantByToolId($toolId)
    {
        $sql = "SELECT DISTINCT sizes.size_id, sizes.name, variant.variants as selfColors from plant_set INNER JOIN sizes on sizes.size_id = plant_set.tool_size_id INNER JOIN (SELECT plant_set.tool_size_id, GROUP_CONCAT(CONCAT('{','\"plantSetId\":',plant_set.plant_set_id,',','\"color_id\":',colors.color_id,',\"name\":\"',colors.name,'\",\"code\":\"',colors.code,'\",\"toolPrice\":',plant_set.price,',\"toolQuantity\":',plant_set.tool_quantity,',\"image\":\"',plant_set.image,'\"}')) as variants FROM plant_set INNER JOIN colors on colors.color_id = plant_set.tool_color_id WHERE plant_set.tool_id = ? AND plant_set.plant_id = 1 GROUP BY plant_set.tool_size_id) as variant on variant.tool_size_id = sizes.size_id WHERE plant_set.tool_id = ? and plant_set.plant_id = 1;";
        $data = $this->select($sql, array($toolId, $toolId));
        if (count($data) > 0) {
            return ['message' => true, 'data' => $data];
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


    // SELECT DISTINCT sizes.size_id, sizes.name, variant.variants as selfColors from plant_set INNER JOIN sizes on sizes.size_id = plant_set.tool_size_id INNER JOIN (SELECT plant_set.tool_size_id, GROUP_CONCAT(CONCAT('{color_id:',colors.color_id,',name:',colors.name,', code:',colors.code,',toolPrice:',plant_set.price,',toolQuantity:',plant_set.tool_quantity,',image:',plant_set.image,'}')) as variants FROM plant_set INNER JOIN colors on colors.color_id = plant_set.tool_color_id WHERE plant_set.tool_id = ? AND plant_set.plant_id = 1 GROUP BY plant_set.tool_size_id) as variant on variant.tool_size_id = sizes.size_id WHERE plant_set.tool_id = ? and plant_set.plant_id = 1;


}
?>