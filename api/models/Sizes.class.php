<?php
class Sizes extends Db
{
    public function getAll()
    {
        $sql = "SELECT size_id, name FROM `sizes` where size_id <> 1";
        return $this->select($sql);
    }
    public function insertSize($name)
    {
        $sql = "INSERT INTO `sizes`(`name`) VALUES (?);";
        $result = $this->insert($sql, array($name));
        if ($result['rowCount'] > 0) {
            return ['message' => true, 'size_id' => $result['lastInsertId']];
        } else {
            return ['message' => false];
        }
    }

    public function deleteSize($sizeId)
    {
        $sql = "DELETE FROM `sizes` WHERE `size_id` = ?";
        $result = $this->delete($sql, array($sizeId));
        if ($result['rowCount'] > 0) {
            return ['message' => true];
        } else {
            return ['message' => false];
        }
    }

    public function getForManager()
    {
        $sql = "SELECT * FROM `sizes` where size_id <> 1";
        return $this->select($sql);
    }
}
?>