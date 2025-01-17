<?php
session_start();
if (isset($_SESSION['user_id'])) {
    include_once "../php/connect.php";
    $outgoing_id = $_SESSION['user_id'];
    $incoming_id = mysqli_real_escape_string($connection, $_POST['incoming_id']);
    $message = mysqli_real_escape_string($connection, $_POST['message']);
    if (!empty($message)) {
        $sql = mysqli_query($connection, "INSERT INTO messages (sender_id, receiver_id, `message`)
                                        VALUES ({$outgoing_id}, {$incoming_id}, '{$message}')") or die();
    }
} else {
    header("location: ../php/signin.php");
}
