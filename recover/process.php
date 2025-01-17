<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once "./phpmailer/src/Exception.php";
require_once "./phpmailer/src/PHPMailer.php";
require_once "./phpmailer/src/SMTP.php";
require_once "./mailchecker.php";

if (isset($_POST["send"])) {

    $result = mailcheck($_POST["email"]);

    if ($result["status"]) {
        $mail = new PHPMailer(true);

        $mail->isSMTP();
        $mail->Host = "smtp.gmail.com";
        $mail->SMTPAuth = true;
        $mail->Username = "chatlink.helper@gmail.com";
        $mail->Password = "adzhxhulxefgokob";
        $mail->SMTPSecure = "ssl";
        $mail->Port = 465;

        $mail->setFrom("chatlink.helper@gmail.com");
        $mail->addAddress($_POST["email"]);

        $mail->isHTML(true);

        $mail->Subject = "Verification Code";
        $mail->Body = "Verification code to recover your password - {$result["code"]}";

        if ($mail->send()) {
            echo "
            <script>
                alert('Mail sent successfully Check your email for later instructions.');
                document.location.href = 'verify.php?email={$_POST["email"]}';
            </script>
        ";
        } else {
            echo "
            <script>
                alert('Error');
            </script>
        ";
        }
    } else {
    }
} else if (isset($_POST["verify"])) {
    $result = verificationCheck($_POST["email"], $_POST["code"]);

    if ($result) {
        echo "
        <script>
            document.location.href = 'reset-password.php?email={$_POST["email"]}';
        </script>
    ";
    } else {
        echo "
        <script>
            alert('Error');
        </script>
    ";
    }
} elseif (isset($_POST["confirm"])) {
    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm-password"];

    if ($password === $confirm_password) {
        $res = updatePassword($email, $password);
        if ($res) {
            echo "
                <script>
                    alert('Password reset successfully.');
                    document.location.href = '/chatlink/php/signin.php';
                </script>
            ";
        } else {
            echo "
                <script>
                    alert('Error');
                </script>
            ";
        }
    } else {
        echo "
            <script>
                alert(`Passwords doesn't match.`);
                document.location.href = 'reset-password.php';
            </script>
        ";
    }
}
