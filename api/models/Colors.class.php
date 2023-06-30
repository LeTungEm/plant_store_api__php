<?php
class Colors extends Db
{
    public function getAll()
    {
        $sql = "SELECT * FROM `colors`";
        return $this->select($sql);
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