<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="/chatlink/img/logo.ico" type="image/x-icon">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/js/all.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <title>ChatLine - Sign In</title>
    <script src="../js/checkTheme.js"></script>
    <link rel="stylesheet" href="../css/signin.css">
    <link rel="stylesheet" href="../css/footer.css">
</head>

<body>

    <div class="container">
        <form>
            <h1>Sign In</h1>
            <div class="content">
                <div class="input-box">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" placeholder="Enter username">
                </div>
                <div class="input-box">
                    <label for="password">Password</label>
                    <button id="show-pass">
                        <i class="fa-solid fa-eye"></i>
                    </button>
                    <input type="password" id="password" name="password" placeholder="Enter password">
                </div>
                <div class="alert">
                    <p>
                        Don't have an account? <a href="./signup.php">Sign Up.</a>
                    <p>
                        <a href="/chatlink/recover/">Forgot password?</a>
                    </p>
                    </p>

                    <p id="empty-field" style="display:none; color:white;">Please make sure that you have filled all fields.</p>
                    <p id="error" style="display:none; color:white;">Please make sure that you inserted right password.</p>
                </div>
            </div>
            <div class="button-container">
                <button type="button" id="signIn">Sign In</button>
            </div>
        </form>
    </div>

    <?php
    require_once "./footer.php";
    ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.js"></script>
    <script src="../js/signin.js"></script>
    <script src="../js/functions.js"></script>

</body>

</html>