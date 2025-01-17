<?php
session_start();
include_once "../php/connect.php";

$outgoing_id = $_SESSION['user_id'];
$searchTerm = mysqli_real_escape_string($connection, $_POST['searchTerm']);

$sql = "SELECT * FROM users WHERE NOT `user_id` = {$outgoing_id} AND (`name` LIKE '%{$searchTerm}%' OR `surname` LIKE '%{$searchTerm}%') ";
$output = "";
$query = mysqli_query($connection, $sql);
if (mysqli_num_rows($query) > 0) {
    require_once "./data.php";
} else {
    $output .= 'No user found related to your search term';
}
echo $output;
