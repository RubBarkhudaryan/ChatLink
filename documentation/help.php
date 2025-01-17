<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="/chatlink/img/logo.ico" type="image/x-icon">
    <title>ChatLine - Help Center</title>
    <script src="script.js"></script>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <header>
        <h1>Help</h1>
        <nav>
            <ul>
                <div class="logo-box">
                    <a href="/chatlink/index.php" class="link-logo">
                        ChatLine
                    </a>
                </div>
                <li><a href="about.php">About Us</a></li>
                <li><a href="#">Help</a></li>
                <li><a href="terms.php">Terms of Service</a></li>
                <li><a href="privacy.php">Privacy Policy</a></li>
                <li><a href="cookies.php">Cookie Policy</a></li>
                <li><a href="locations.php">Our Locations</a></li>
                <li><a href="contact-us.php">Contact Us</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <section>
            <h2>Welcome to the ChatLine Help Center!</h2>
            <p> We're here to assist you with any questions or issues you may have while using our platform. Below you’ll find answers to frequently asked questions, guides on how to use our features, and information on how to contact our support team.
            </p>

            <h2>Getting Started</h2>
            <h3>How do I create an account?</h3>

            <ol>
                <li>
                    Click on the "Sign Up" button on the homepage.
                </li>
                <li>
                    Complete your profile with additional information and a profile picture.
                </li>
            </ol>

            <h3>
                How do I sign in?
            </h3>

            <ol>
                <li>
                    Click on the "Sign In" button on the homepage.
                </li>
                <li>
                    Enter your registered email address and password.
                </li>
                <li>
                    Click "Log In" to access your account.
                </li>
            </ol>

            <h2>Managing Your Profile</h2>
            <h3>How do I update my profile information?</h3>

            <ol>
                <li>Go to your profile page by clicking your profile picture or username.</li>
                <li>Click the "Edit Profile" button.</li>
                <li>Update your information and click "Save Changes."</li>
            </ol>

            <h3>How do I change my profile picture?</h3>

            <ol>
                <li>Go to your profile page.</li>
                <li>Click on choose file to choose new profile picture.</li>
                <li>Upload a new picture.</li>
                <li>Click "Replace Profile Image."</li>
            </ol>

            <h2>Privacy and Security</h2>
            <h3>How do I block a user?</h3>

            <ol>
                <li>Go to the profile of the user you want to block.</li>
                <li>Click on the three dots (more options) button.</li>
                <li>Select "Block User" to block the user.</li>
            </ol>

            <h3>Using ChatLine Features. How do I write a message?</h3>

            <ol>
                <li>Go to the "Messages" section from the main menu.</li>
                <li>Enter the recipient's name, click on recipient to write your message, and click "Send."</li>
            </ol>

            <h2>Troubleshooting</h2>
            <h3>I forgot my password. What should I do?</h3>

            <ol>
                <li>Click on the "Forgot Password" link on the sign in page.</li>
                <li>Enter your registered email address.</li>
                <li>Follow the instructions in the email to reset your password.</li>
            </ol>

            <h2>I’m experiencing technical issues. How can I get help?</h2>

            <pre>
Visit our Technical Support page for common solutions.
If the issue persists, contact our support team using the form below.
Contact Support
If you need further assistance, please fill out the form below, and our support team will get back to you as soon as possible.
            </pre>


            <form class="contact-form" action="process.php" method="POST">
                <label for="fullname">Full Name</label>
                <input type="text" id="fullname" name="fullname" required>

                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>

                <label for="issue">Issue</label>
                <select id="issue" name="issue" required>
                    <option value="account">Account Issues</option>
                    <option value="privacy">Privacy Concerns</option>
                    <option value="technical">Technical Problems</option>
                    <option value="other">Other</option>
                </select>

                <label for="message">Message</label>
                <textarea id="message" name="message" rows="5" required></textarea>

                <button type="submit" name="tech-issue">Submit</button>
            </form>

        </section>
    </main>
    <?php require_once "footer.php"; ?>
</body>

</html>