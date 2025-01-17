<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="/chatlink/img/logo.ico" type="image/x-icon">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/js/all.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <title>ChatLine - Sign Up</title>
    <script src="../js/checkTheme.js"></script>
    <link rel="stylesheet" href="../css/footer.css">
    <link rel="stylesheet" href="../css/signup.css">
</head>

<body>
    <div class="container">
        <form>
            <h1>Sign Up</h1>

            <div class="content">
                <div class="input-box">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" placeholder="Name">
                </div>

                <div class="input-box">
                    <label for="surname">Surname</label>
                    <input type="text" id="surname" name="surname" placeholder="Surname">
                </div>

                <div class="input-box">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" placeholder="Username">
                </div>

                <div class="input-box">
                    <label for="password">Password</label>
                    <button id="show-pass">
                        <i class="fa-solid fa-eye"></i>
                    </button>
                    <input type="password" id="password" name="password" placeholder="Password">
                </div>

                <div class="input-box">
                    <label for="confirm-password">Confirm Password</label>
                    <button id="show-conf-pass">
                        <i class="fa-solid fa-eye"></i>
                    </button>
                    <input type="password" id="confirm-password" name="confirm-password" placeholder="Confirm Password">
                </div>

                <div class="input-box">
                    <label for="age">Age</label>
                    <input type="number" id="age" name="age" placeholder="Age">
                </div>

                <div class="input-box">
                    <label for="confirm-password">Email</label>
                    <input type="email" id="email" name="email" placeholder="Email">
                </div>

                <div class="input-box">
                    <label for="phone">Phone Number</label>
                    <input type="tel" id="phone" name="phone" placeholder="Phone">
                </div>

                <span class="gender-title">Gender</span>
                <div class="gender-category">
                    <input type="radio" name="gender" id="male" value="1" checked>
                    <label for="male">Male</label>

                    <input type="radio" name="gender" id="female" value="0">
                    <label for="female">Female</label>
                </div>
            </div>

            <div class="alert">
                <p>
                    By clicking Sign Up, you agree to our <a href="#">Terms</a>, <a href="#">Privacy Policy</a> and <a href="#">Cookies Policy</a>. You may receive SMS notifications from us and can opt out at any time.
                </p>
                <div>
                    <p>
                        Have an account? <a href="./signin.php">Sign In.</a>
                    </p>
                    <p id="empty-field">Please make sure that you have filled all fields.</p>
                    <p id="password-error">Passwords doesn't match. Please check passwords again.</p>
                </div>
            </div>


            <div class="button-container">
                <button type="button" id="signUp">Sign Up</button>
            </div>

        </form>

    </div>

    <?php
    require_once "./footer.php";
    ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.js"></script>
    <script src="../js/signup.js"></script>
    <script src="../js/functions.js"></script>
</body>


</html>