<?php

function displayPost($post, $current_user_id)
{
?>
    <figure class="post">
        <div class="post-header">
            <a href="./php/profile.php?username=<?php echo $post["username"]; ?>" style="text-decoration:none;">
                <img class='profile-small-img' src='<?php echo str_replace("..", "/chatlink", $post["profile_image"]); ?>' alt='Profile Image' loading='lazy' title='User Profile Image'>
                <span class="poster-username"><?php echo $post["username"]; ?></span>
            </a>
            <button type="button" class="more-menu-button">
                <i class="fa-solid fa-ellipsis"></i>
            </button>
        </div>

        <?php if ($current_user_id != $post["user_id"]) { ?>
            <div class="more-menu-attributes" style="display: none;">
                <ul>
                    <li><button class="follow-button" data-user-id="<?php echo $post["user_id"]; ?>">Follow</button></li>
                    <li><button class="block-user-button" data-user-id="<?php echo $post["user_id"]; ?>">Block User</button></li>
                    <?php
                    if ($post["download"]) {
                    ?>
                        <li><button class="download-post-button" onclick="downloadImage('<?php echo str_replace('..', '/chatlink', $post['image_url']); ?>');">Download Post</button></li>
                    <?php
                    }
                    ?>
                </ul>
            </div>
        <?php } else {
        ?>
            <div class="more-menu-attributes" style="display: none;">
                <ul>
                    <li><button class="delete-post" data-user-id="<?php echo $post["user_id"]; ?>" data-post-id="<?php echo $post["post_id"];  ?>">Delete Post</button></li>
                    <li><button class="download-post-button" onclick="downloadImage('<?php echo str_replace('..', '/chatlink', $post['image_url']); ?>');">Download Post</button></li>
                    <?php
                    $extension = substr($post["image_url"], -3);
                    if ($extension != "mp4" && $extension != "mov" && $extension != "avi" && $extension != "gif") {
                    ?>
                        <li><a class="edit-post" href="./post/image.php?post_id=<?php echo $post["post_id"];  ?>" class="edit-post">Edit Post</a></li>
                    <?php
                    } ?>
                </ul>
            </div>
        <?php
        } ?>

        <?php
        $extension = substr($post["image_url"], -3);
        switch ($extension) {
            case "mp4":
            case "avi":
            case "mov":
                echo '<video controls>
                        <source src="' . str_replace("..", "/chatlink", $post["image_url"]) . '" type="video/' . $extension . '" class="postImg">
                      </video>';
                break;
            default:
                echo '<img src="' . str_replace("..", "/chatlink", $post["image_url"]) . '" alt="' . $post["caption"] . '" class="postImg" loading="lazy" title="' . $post["caption"] . '">';
                break;
        }
        ?>

        <figcaption class="postCaption"><?php echo $post["caption"]; ?></figcaption>

        <div class="buttons">
            <div class="like-container">
                <button type="button" class="like-button" data-post-id="<?php echo $post["post_id"]; ?>" data-user-id="<?php echo $current_user_id; ?>" title="Like">
                    <i class="fa-regular fa-heart"></i>
                </button>
                <br>
                <span class="likes-count"><?php echo ($post["likes_count"] > 1) ? $post["likes_count"] . " Likes" : $post["likes_count"]; ?></span>
            </div>

            <div class="comment-buttons">
                <button type="button" class="comment-button" title="Comments">
                    <i class="fa-regular fa-comment-dots"></i>
                </button>
                <br>
                <span class="comments-count"><?php echo ($post["comments_count"] > 0) ? $post["comments_count"] : $post["comments_count"]; ?></span>
            </div>
        </div>

        <div class="comment-container" style="display: none;">
            <form class="comment-form">
                <input type="hidden" class="post-id" data-post-id="<?php echo $post['post_id']; ?>" value="<?php echo $post['post_id']; ?>">
                <input type="hidden" class="user-id" data-user-id="<?php echo $current_user_id; ?>" value="<?php echo $post['user_id']; ?>">
                <label class="label" for="comment" maxlength="200">
                    <p class="comment-length"><input disabled type="text" name="countdown" size="3" value="0"> / 200</p>
                </label>
                <textarea class="comment-1" name="comment" placeholder="Add a comment..." onKeyDown="limitText(this.form.comment,this.form.countdown,200);" onKeyUp="limitText(this.form.comment,this.form.countdown,200);"></textarea>
                <br>
                <button type="submit" class="submitComment">Post</button>
                <div class="error-message" style="display: none;">
                    <p class="error-message-txt">You can't add an empty comment. Please write a comment before sending.</p>
                </div>
            </form>

            <br>

            <div class="comments-container" style="display: none;">
                <ul class="comments"></ul>
            </div>

            <div class="empty-comments-bar" style="display: none;">
                <p class="empty-comment-txt">There are no comments yet.</p>
            </div>

            <button class="load-comments-button" data-post-id="<?php echo $post['post_id']; ?>" data-comments-loaded="false">Show Comments</button>
            <button class="view-less-button">View Less</button>
        </div>
    </figure>
<?php
}


function createRecomendedUsers($user)
{
?>

    <div class="recomended-user">
        <a href="/chatlink/php/profile.php?username=<?php echo $user["username"]; ?>">
            <img src="<?php echo str_replace("..", "/chatlink", $user["profile_image"]); ?>" alt='Profile Image' class="profile-small-img" loading='lazy' title='<?php echo $user["username"]; ?>'>
            <span class="recomended-user-fullname"> <?php echo " " . $user['name'] . " " . $user['surname']; ?></span>
            <span class="recomended-user-username"> <?php echo $user["username"]; ?></span>
        </a>
    </div>

<?php
}



function createBlockedUser($blockedUser)
{
?>
    <div class="user-profile">
        <div style="display: flex;">
            <div class="profile-image">
                <img src="<?php echo $blockedUser['profile_image']; ?>" alt="">
            </div>
            <div class="user-data">
                <p class="name"> <?php echo $blockedUser['name'] . " " . $blockedUser['surname']; ?> </p>
                <p class="username"><?php echo $blockedUser['username']; ?></p>
            </div>
        </div>
        <div class="button">
            <button class="unblock-user-button">Unblock</button>
        </div>
    </div>
<?php

}
