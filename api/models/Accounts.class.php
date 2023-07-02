<?php
include("../../function/function.php");

class Accounts extends Db
{
    public function getAll()
    {
        $sql = "SELECT * FROM accounts";
        return $this->select($sql);
    }

    public function detail($email)
    {
        $sql = "SELECT account_id, name, email, address, phone, gender, birthday FROM accounts WHERE email = ?";
        $data = $this->select($sql, array($email));
        if (count($data) > 0) {
            return ['message' => true, 'data' => $data[0]];
        } else {
            return ['message' => false];
        }
    }

    public function getByStatus()
    {
        $sql = "SELECT * FROM accounts where status = 1";
        return $this->select($sql);
    }

    public function isEmailExists($email)
    {
        $sql = "SELECT count(*) as existsNumber, role_id FROM `accounts` WHERE email = ?";
        $data = $this->select($sql, array($email));
        if ($data[0]['existsNumber'] > 0) {
            return ['message' => true, 'role_id' => $data[0]['role_id']];
        } else {
            return ['message' => false];
        }
    }

    public function authenticate($email, $password)
    {
        $sql = "SELECT account_id, password, salt, role_id FROM accounts where email = ?";
        $data = $this->select($sql, array($email));
        if (count($data) > 0) {
            $infoLogin = $data[0];
            $password = base64_decode($password);
            $result = verifyPassWord($password, $infoLogin['salt'], $infoLogin['password']);
            return ['message' => $result, 'userId' => $infoLogin['account_id'], 'role_id' => $infoLogin['role_id']];
        } else {
            return ['message' => false];
        }
    }
    
    public function insertAccount($address, $gender, $birthday, $phone, $passWord, $name, $email, $roleId)
    {
        $encodePassword = base64_decode($passWord);
        $hashedPasswordObj = hashPassWord($encodePassword);
        $sql = "INSERT INTO `accounts`(`address`, `gender`, `birthday`, `phone`, `password`, `name`, `email`, `role_id`, `salt`) VALUES (?,?,?,?,?,?,?,?,?)";
        $result = $this->insert($sql, array($address, $gender, $birthday, $phone, $hashedPasswordObj['hashedPassword'], $name, $email, $roleId, $hashedPasswordObj['salt']));
        if ($result['rowCount'] > 0) {
            return ['message' => true];
        } else {
            return ['message' => false];
        }
    }

    public function deleteAccount($email)
    {
        $sql = "UPDATE `accounts` SET `status`= 0 WHERE `email` = ?";
        $result = $this->delete($sql, array($email));
        if ($result > 0) {
            return ['message' => true];
        } else {
            return ['message' => false];
        }
    }

    public function updateAccount($name, $address, $phone, $gender, $birthday, $email)
    {
        $sql = "UPDATE `accounts` SET `name`= ?,`address`= ?,`phone`= ?,`gender`= ?,`birthday`= ? WHERE `email` = ?";
        $result = $this->update($sql, array($name, $address, $phone, $gender, $birthday, $email));
        if ($result > 0) {
            return ['message' => true];
        } else {
            return ['message' => false];
        }
    }
}
?>