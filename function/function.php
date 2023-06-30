<?php
function hashPassWord($password)
{
    $salt = bin2hex(random_bytes(16));
    $hashedPassword = password_hash($password . $salt, PASSWORD_BCRYPT);
    return ['hashedPassword' => $hashedPassword, 'salt' => $salt];
}
function verifyPassWord($enteredPassword, $salt, $hashedPassword)
{
    return password_verify($enteredPassword . $salt, $hashedPassword);
}
?>