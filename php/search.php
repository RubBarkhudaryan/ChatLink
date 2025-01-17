<?php
session_start();
require_once "./connect.php";
require_once "../postCreator.php";

$searchTerm = mysqli_real_escape_string($connection, $_GET['searchTerm']);
$outgoing_id = $_SESSION['user_id'];

$sql = "SELECT * 
        FROM `users` 
        WHERE NOT `user_id` = {$outgoing_id} AND (`name` LIKE '%{$searchTerm}%' OR `surname` LIKE '%{$searchTerm}%' OR `username` LIKE '%{$searchTerm}%')";

$query = mysqli_query($connection, $sql);

$results = [];

if (mysqli_num_rows($query)) {
    $results = mysqli_fetch_all($query, MYSQLI_ASSOC);
    foreach ($results as $user) {
        createRecomendedUsers($user);
    }
} else {
    echo "There are no users with that username or name";
}
