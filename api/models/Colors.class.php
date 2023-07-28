<?php
class Colors extends Db
{
    public function getAll()
    {
        $sql = "SELECT color_id,`code`,`name` FROM `colors` where color_id <> 1";
        return $this->select($sql);
    }

    public function getForManager()
    {
        $sql = "SELECT * FROM `colors` where color_id <> 1";
        return $this->select($sql);
    }

    public function deleteColor($colorId)
    {
        $sql = "DELETE FROM `colors` WHERE `color_id` = ?";
        $result = $this->delete($sql, array($colorId));
        if ($result['rowCount'] > 0) {
            return ['message' => true];
        } else {
            return ['message' => false];
        }
    }

    public function isNameExist($name)
    {
        $sql = "SELECT count(*) as nameCount FROM `colors` where name = ?";
        $data = $this->select($sql, array($name));
        if ($data[0]['nameCount'] > 0) {
            return ['message' => true];
        } else {
            return ['message' => false];
        }
    }

    public function createColor($name, $description, $code)
    {
        $sql = "INSERT INTO `colors`(`name`,`description`, `code`) VALUES (?,?,?)";
        $result = $this->insert($sql, array($name, $description, $code));
        if ($result['rowCount'] > 0) {
            return ['message' => true, 'color_id' => $result['lastInsertId']];
        } else {
            return ['message' => false];
        }
    }

    public function getUsedByPlants()
    {
        $sql = "SELECT DISTINCT color_id,`code`,`name` FROM `colors` INNER JOIN plant_set on colors.color_id = plant_set.tool_color_id WHERE code is NOT null and plant_set.plant_id != 1";
        return $this->select($sql);
    }

    public function getUsedByTools()
    {
        $sql = "SELECT DISTINCT color_id,`code`,`name` FROM `colors` INNER JOIN plant_set on colors.color_id = plant_set.tool_color_id WHERE plant_set.plant_id = 1 and code is NOT null";
        return $this->select($sql);
    }
}
?>