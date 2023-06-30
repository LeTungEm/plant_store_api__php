<?php
class PaymentMethods extends Db
{
    public function getAll()
    {
        $sql = "SELECT payment_method_id, name FROM `payment_methods` where status = 1";
        return $this->select($sql);
    }
}
?>