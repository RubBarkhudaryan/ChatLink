<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="/chatlink/img/logo.ico" type="image/x-icon">
    <title>ChatLine - Contact Us</title>
    <script src="script.js"></script>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <header>
        <h1>Contact Us</h1>
        <nav>
            <ul>
                <div class="logo-box">
                    <a href="/chatlink/index.php" class="link-logo">
                        ChatLine
                    </a>
                </div>
                <li><a href="about.php">About Us</a></li>
                <li><a href="help.php">Help</a></li>
                <li><a href="terms.php">Terms of Service</a></li>
                <li><a href="privacy.php">Privacy Policy</a></li>
                <li><a href="cookies.php">Cookie Policy</a></li>
                <li><a href="locations.php">Our Locations</a></li>
                <li><a href="#">Contact Us</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <section>

            <div class="header">
                <h2>Get in Touch</h2>
                <p>Have questions? Reach out to us through our contact form.</p>
            </div>

            <form class="contact-form" action="process.php" method="POST">
                <h2 style="color: white;">Contact Us</h2>
                <label for="fullname">Full Name</label>
                <input type="text" id="fullname" name="fullname" required>

                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>

                <label for="subject">Subject</label>
                <input type="text" id="subject" name="subject" required>

                <label for="message">Message</label>
                <textarea id="message" name="message" rows="5" required></textarea>

                <button type="submit" name="question">Send Message</button>
            </form>
        </section>
    </main>
    <?php require_once "footer.php"; ?>
</body>

</html>