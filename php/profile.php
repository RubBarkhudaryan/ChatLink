<!DOCTYPE html>
<html lang="en">

<?php
require_once "./functions.php";

$current_user_id = checkUser();

$user = [];
if (isset($_GET["username"])) {
    $user = getCurrentUser(0, $_GET["username"]);
}

?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="/chatlink/img/logo.ico" type="image/x-icon">
    <title>ChatLine - <?php echo $user["username"]; ?></title>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/js/all.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <script src="../js/checkTheme.js"></script>
    <link rel="stylesheet" href="../css/profile.css">
    <link rel="stylesheet" href="../css/menu.css">
    <link rel="stylesheet" href="../css/footer.css">
    <link rel="stylesheet" href="../css/modal.css">
</head>

<body class="body">

    <div class="main">
        <div class="profile-info">
            <div class="username">
                <div class="user-img">
                    <img src="<?php echo $user["profile_image"]; ?>" alt='Profile Image' loading='lazy' title='<?php echo $user["username"]; ?>'>
                </div>
                <div class="data">
                    <p><?php echo $user["name"] . " " . $user["surname"]; ?></p>
                    <p><?php echo $user["username"]; ?></p>
                    <pre><?php echo $user["bio"]; ?></pre>
                </div>
            </div>

            <div class="account-info">
                <div class="followers-count">
                    <p>Posts</p>
                    <p><?php echo $user["postsCount"]; ?></p>
                </div>
                <div class="followers-count">
                    <p>Followers</p>
                    <p><?php echo $user["followers_count"]; ?></p>
                </div>
                <div class="followers-count">
                    <p>Followings</p>
                    <p><?php echo $user["followings_count"]; ?></p>
                </div>
                <div class="profile-buttons">
                    <div class="follow-user">
                        <?php
                        if ($user["user_id"] !== $current_user_id) {
                            echo '<button class="follow-button" data-user-id="' . $user["user_id"] . '">Follow</button> 
                                  <button class="block-user" data-user-id="' . $user["user_id"] . '">Block</button>';
                        } else {
                            echo '<a id="edit-profile" href="/chatlink/php/settings.php">Edit Profile</a>';
                        }
                        ?>
                    </div>
                </div>
            </div>

        </div>

        <div class="posts">
            <?php
            if ($user["postsCount"] == 0) {
                echo "
                    <div class='no-post'>
                        <div class='camera'> 
                            <i class='fa-solid fa-camera'></i>
                        </div>
                        <p> No posts available yet. </p> 
                    </div>";
            } else {
                $posts = getCurrentUsersPosts($user["user_id"]);

                foreach ($posts as $post) {
                    $mediaUrl = $post["image_url"];

                    $isVideo = strpos($mediaUrl, '.mp4') !== false || strpos($mediaUrl, '.mov') !== false || strpos($mediaUrl, '.avi') !== false;

                    if ($isVideo) {
            ?>
                        <div class="mini-post">
                            <div class="media">
                                <img src="/chatlink/img/thumbnail.jpeg" alt='<?php echo $post["caption"]; ?>' loading='lazy' title='<?php echo $post["caption"] . " - video type content"; ?>' onclick="loadProfilePost(<?php echo $post['post_id']; ?>)">
                            </div>
                        </div>
                    <?php
                    } else {
                    ?>
                        <div class="mini-post">
                            <div class="media">
                                <img src="<?php echo $mediaUrl ?>" alt='<?php echo $post["caption"]; ?>' loading='lazy' title='<?php echo $post["caption"]; ?>' onclick="loadProfilePost(<?php echo $post['post_id']; ?>)">
                            </div>
                        </div>
            <?php
                    }
                }
            }
            ?>
            <div id="modalContainer" style="display: none;"></div>
        </div>

        <?php require_once "./menu.php"; ?>
        <?php require_once "./footer.php"; ?>
    </div>
    <script src="/chatlink/js/script.js"></script>
    <script src="/chatlink/js/functions.js"></script>
    <?php

    $user = getCurrentUser(0, $_GET["username"]);

    if ($user["user_id"] != $current_user_id) {
        echo '<script src="/chatlink/js/profile.js"></script>';
    } ?>
</body>

</html>