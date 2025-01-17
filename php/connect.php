<?php

$host = 'localhost';
$user = 'root';
$password = '';
$db = 'chatlink';

$connection = mysqli_connect($host, $user, $password, $db);

if (!$connection) {
    die("error " . mysqli_connect_error());
}

$connection->set_charset("utf8mb4");
