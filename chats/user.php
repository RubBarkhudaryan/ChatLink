
<?php
session_start();
require_once "../php/connect.php";
$outgoing_id = $_SESSION['user_id'];
$sql = "
    SELECT u.* 
    FROM users u
    LEFT JOIN followers f1 ON u.user_id = f1.following_id AND f1.follower_id = {$outgoing_id} AND f1.status IS NULL
    LEFT JOIN followers f2 ON u.user_id = f2.follower_id AND f2.following_id = {$outgoing_id} AND f2.status IS NULL
    WHERE (f1.follower_id IS NOT NULL OR f2.following_id IS NOT NULL) 
      AND u.user_id != {$outgoing_id}
    ORDER BY u.user_id DESC
";


$query = mysqli_query($connection, $sql);
$output = "";
if (mysqli_num_rows($query) == 0) {
    $output .= "No users are available to chat";
} elseif (mysqli_num_rows($query) > 0) {
    require_once "./data.php";
}
echo $output;
?>

