<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="/chatlink/img/logo.ico" type="image/x-icon">
    <title>ChatLine - Search</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/js/all.min.js"></script>
    <script src="/chatlink/js/checkTheme.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="/chatlink/css/search.css">
    <link rel="stylesheet" href="/chatlink/css/style.css">
</head>

<?php require_once "./functions.php";
$current_user_id = checkUser();
?>

<body class="body">
    <div class="main">
        <div class="search-form">
            <h1>Search</h1>
            <input type="text" id="searchInput" placeholder="Search ...">
            <button id="clear">&times;</button>
            <div id="searchResults"></div>
        </div>
        <?php require_once "./footer.php"; ?>
    </div>

    <?php require_once "./menu.php"; ?>
    <script src="../js/search.js"></script>
</body>

</html>