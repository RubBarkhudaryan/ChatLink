<?php
while ($row = mysqli_fetch_assoc($query)) {

    $sql2 = "SELECT * FROM messages 
            WHERE (receiver_id = {$row['user_id']} AND sender_id = {$outgoing_id}) 
            OR (receiver_id = {$outgoing_id} AND sender_id = {$row['user_id']}) 
            ORDER BY id DESC LIMIT 1";

    $query2 = mysqli_query($connection, $sql2);
    $row2 = mysqli_fetch_assoc($query2);

    (mysqli_num_rows($query2) > 0) ? $result = $row2['message'] : $result = "No message available";

    (strlen($result) > 28) ? $msg =  substr($result, 0, 28) . '...' : $msg = $result;

    if (isset($row2['sender_id'])) {
        ($outgoing_id == $row2['sender_id']) ? $you = "You: " : $you = "";
    } else {
        $you = "";
    }

    ($outgoing_id == $row['user_id']) ? $hid_me = "hide" : $hid_me = "";

    $output .= '<a href="./chat.php?user_id=' . $row['user_id'] . '">
                    <div class="content">
                    <img src="' . str_replace("..", "/chatlink", $row["profile_image"]) . '" alt="">
                    <div class="details">
                        <span>' . $row['name'] . " " . $row['surname'] . '</span>
                        <p>' . $you . $msg . '</p>
                    </div>
                    </div>
                </a>';
}
