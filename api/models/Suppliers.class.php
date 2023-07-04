<?php
class Suppliers extends Db
{
    public function getAll()
    {
        $sql = "SELECT supplier_id, name FROM `Suppliers` where status = 1";
        return $this->select($sql);
    }
}
?>