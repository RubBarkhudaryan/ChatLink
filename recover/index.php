<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="/chatlink/img/logo.ico" type="image/x-icon">
    <title>ChatLine - Reset Password</title>
    <script src="../js/checkTheme.js"></script>
    <link rel="stylesheet" href="./style.css">
    <link rel="stylesheet" href="../css/footer.css">
</head>

<body>

    <div class="container">
        <form action="./process.php" method="post">
            <h1>Reset Password</h1>
            <div class="content">
                <div class="input-box">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="Enter email">
                </div>
            </div>
            <div class="button-container">
                <input type="submit" value="Check Email" name="send">
            </div>
        </form>
    </div>

    <?php
    require_once "../php/footer.php";
    ?>
</body>

</html>