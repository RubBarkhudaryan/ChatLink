<?php

$host = "localhost";
$username = "root";
$password = "";
$db = "chatlink";

$connect = mysqli_connect($host, $username, $password, $db);

$connect->set_charset("utf8mb4");

function mailcheck($email)
{
    global $connect;

    $email = mysqli_real_escape_string($connect, $email);

    $sql = "SELECT SUM(if(`email` = '$email', true,false)) as result
            FROM `users`;";

    echo $sql;

    $query = mysqli_query($connect, $sql);

    $result = mysqli_fetch_assoc($query);

    if ($result["result"] == 1) {
        $code = rand(999999, 111111);
        $sql = "UPDATE `users` SET `recovery_code` = $code WHERE `email` = '$email'";
        $query = mysqli_query($connect, $sql);

        if ($query) {
            return ["status" => true, "code" => $code];
        }
    } else {
        return ["status" => false];
    }
}

function verificationCheck($email, $code)
{
    global $connect;
    $email = mysqli_real_escape_string($connect, $email);
    $code = mysqli_real_escape_string($connect, $code);

    $sql = "SELECT `recovery_code` 
            FROM `users`
            WHERE `email` = '$email'";

    $query = mysqli_query($connect, $sql);

    $rec_code = mysqli_fetch_assoc($query);

    if ($rec_code["recovery_code"] != 0 && $rec_code["recovery_code"] === $code) {
        return true;
    } else {
        return false;
    }
}


function updatePassword($email, $password)
{
    global $connect;
    $email = mysqli_real_escape_string($connect, $email);
    $password = mysqli_real_escape_string($connect, $password);
    $password = password_hash($password, PASSWORD_BCRYPT);

    $sql = "UPDATE `users` SET `password` = '$password', `recovery_code` = 0 WHERE `email` = '$email'";

    $query = mysqli_query($connect, $sql);

    if ($query) {
        return true;
    } else {
        return false;
    }
}
