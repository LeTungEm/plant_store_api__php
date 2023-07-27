<?php
class Sizes extends Db
{
    public function getAll()
    {
        $sql = "SELECT size_id, name FROM `sizes` where size_id <> 1";
        return $this->select($sql);
    }
}
?>