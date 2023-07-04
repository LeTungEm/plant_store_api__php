<?php
class Categories extends Db
{
    public function getAll()
    {
        $sql = "SELECT category_id, name FROM `categories` where categories.slug not in ('cay','chau') and status = 1";
        return $this->select($sql);
    }

    public function getByStatus()
    {
        $sql = "SELECT * FROM `categories` where status = 1";
        return $this->select($sql);
    }

    public function getByParentSlug($parentSlug)
    {
        $sql = "SELECT t2.category_id, t2.name,t2.slug,t2.image FROM categories as t1 INNER JOIN categories as t2 on t1.category_id = t2.parent_id WHERE t1.slug = ? and t2.status  = 1";
        return $this->select($sql, array($parentSlug));
    }

    public function getDisplayCategories()
    {
        $sql = "SELECT category_id, name, slug FROM `categories` where display = 1 and status = 1";
        return $this->select($sql);
    }

    public function getSpecialCategories()
    {
        $sql = "SELECT t2.slug as parent_slug, t1.category_id, t1.name, t1.slug, t1.image FROM `categories` as t1 inner join `categories` as t2 on t1.parent_id = t2.category_id where t1.special = 1 and t1.status = 1";
        return $this->select($sql);
    }
}
?>