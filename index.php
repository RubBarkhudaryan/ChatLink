<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="/chatlink/img/logo.ico" type="image/x-icon">

    <title>ChatLine - Home Page</title>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/js/all.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="/chatlink/js/checkTheme.js"></script>
    <link rel="stylesheet" href="./css/footer.css">
    <link rel="stylesheet" href="./css/menu.css">
    <link rel="stylesheet" href="./css/style.css">
</head>

<body class="body">

    <?php

    require_once './php/functions.php';
    require_once "./postCreator.php";

    $current_user_id = checkUser();
    $recomended_users = recommendUsers($current_user_id);
    $posts = getPosts($current_user_id);

    ?>

    <div class="main"> <!-- onclick="checkDropDown()" -->

        <?php
        if ($posts) {

            foreach ($posts as $post) :
                displayPost($post, $current_user_id);
            endforeach;
        } else {
            echo "
                <div class='no-post'>
                    <i class='fa-solid fa-camera'></i>
                    <p> No posts available yet. </p>
                </div>";
        }
        ?>

        <div class="recomended-users-list">
            <h3 class="recomended-users-title">Suggested For You</h3>
            <?php
            foreach ($recomended_users as $user) :
                createRecomendedUsers($user);
            endforeach;
            ?>
        </div>
        <?php
        require_once "./php/footer.php";
        ?>
    </div>


    <?php require_once "./php/menu.php";
    ?>


    <script src="./js/script.js"></script>
    <script src="./js/functions.js"></script>
    <script>
        likeCheck();
        followCheck();

        function limitText(limitField, limitCount, limitNum) {
            if (limitField.value.length > limitNum) {
                limitField.value = limitField.value.substring(0, limitNum);
            } else {
                limitCount.value = limitField.value.length;
            }
        }
    </script>

</body>

</html>