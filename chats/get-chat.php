<?php
session_start();
if (isset($_SESSION['user_id'])) {
    require_once "../php/connect.php";
    $outgoing_id = $_SESSION['user_id'];
    $incoming_id = mysqli_real_escape_string($connection, $_POST['incoming_id']);

    $output = "";

    $sql = "SELECT * FROM messages LEFT JOIN users ON users.user_id = messages.sender_id
            WHERE (sender_id = {$outgoing_id} AND receiver_id = {$incoming_id})
            OR (sender_id = {$incoming_id} AND receiver_id = {$outgoing_id}) ORDER BY id";

    $query = mysqli_query($connection, $sql);

    if (mysqli_num_rows($query) > 0) {
        while ($row = mysqli_fetch_assoc($query)) {
            if ($row['sender_id'] === $outgoing_id) {
                $output .= '<div class="chat outgoing">
                                <div class="details">
                                    <p>' . $row['message'] . '</p>
                                </div>
                                </div>';
            } else {
                $output .= '<div class="chat incoming">
                                <img src="' . str_replace("..", "/chatlink", $row["profile_image"]) . '" alt="">
                                <div class="details">
                                    <p>' . $row['message'] . '</p>
                                </div>
                                </div>';
            }
        }
    } else {
        $output .= '<div class="text">No messages are available. Once you send message they will appear here.</div>';
    }
    echo $output;
} else {
    header("location: ../login.php");
}
