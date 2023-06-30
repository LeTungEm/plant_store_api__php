<?php
class ShippingProviders extends Db
{
    public function getAll()
    {
        $sql = "SELECT shipping_provider_id, name FROM `shipping_providers` where status = 1";
        return $this->select($sql);
    }
}
?>