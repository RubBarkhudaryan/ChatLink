<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.js"></script>
    <script src="/chatlink/js/checkTheme.js"></script>
    <title>Blocked Users</title>
    <link rel="stylesheet" href="/chatlink/css/settings.css">
    <link rel="stylesheet" href="/chatlink/css/footer.css">

</head>

<?php require_once "./functions.php"; ?>
<?php require_once "../postCreator.php"; ?>

<body class="body">
    <div class="settings-main">
        <h1 class="page-title">Blocked Users</h1>

        <?php
        $user_id = checkUser();
        $users = getBlockedUsers($user_id);

        if (count($users)) {
            foreach ($users as $user) {
                $blockedUser = getCurrentUser($user["user_id"], "");
                createBlockedUser($blockedUser);
            }
        } else {
            echo '<p class="no-users"> There are no blocked users yet. </p>';
        }
        ?>

    </div>
    <?php require_once "./footer.php"; ?>
    <script src="../js/blockUsers.js"></script>
</body>

</html>