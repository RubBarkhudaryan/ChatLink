<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="/chatlink/img/logo.ico" type="image/x-icon">
    <title>ChatLine - Settings</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/js/all.min.js"></script>
    <script src="/chatlink/js/checkTheme.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="/chatlink/css/settings.css">
</head>

<?php
require_once "./functions.php";
$current_user_id = checkUser();
?>

<body class="settings-body">
    <?php require_once "./menu.php"; ?>

    <div id="settings-container">
        <div id="all-settings">
            <h1 class="page-title">All Settings</h1>
            <p><a target="_self" onclick="loadPage('./editProfile.php')">Edit Profile</a></p>
            <p><a target="_self" onclick="loadPage('./themeColor.php')">Theme Color</a></p>
            <p><a target="_self" onclick="loadPage('./blockedUsers.php')">Blocked Users</a></p>
            <button id="delete-button">Delete Account</button>
        </div>
        <div id="current-setting">
            <iframe id="settingFrame" src="./editProfile.php" name="setting" height="100%" width="100%"></iframe>
        </div>
    </div>

    <script src="../js/script.js"></script>
    <script src="../js/deleteAccount.js"></script>
</body>

</html>