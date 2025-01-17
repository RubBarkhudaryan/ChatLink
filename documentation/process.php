<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once "./phpmailer/src/Exception.php";
require_once "./phpmailer/src/PHPMailer.php";
require_once "./phpmailer/src/SMTP.php";

if (isset($_POST["question"])) {

    $mail = new PHPMailer(true);

    $mail->isSMTP();
    $mail->Host = "smtp.gmail.com";
    $mail->SMTPAuth = true;
    $mail->Username = "chatlink.helper@gmail.com";
    $mail->Password = "adzhxhulxefgokob";
    $mail->SMTPSecure = "ssl";
    $mail->Port = 465;

    $mail->setFrom("chatlink.helper@gmail.com", "Customer Center");
    $mail->addAddress($_POST["email"]);

    $mail->isHTML(true);

    $mail->Subject = $_POST["subject"];
    $mail->Body = "Your message sent to our customers center. Our support team will response as sonn as possible. Thank you for using ChatLine!";

    if ($mail->send()) {

        $mail = new PHPMailer(true);

        $mail->isSMTP();
        $mail->Host = "smtp.gmail.com";
        $mail->SMTPAuth = true;
        $mail->Username = "chatlink.helper@gmail.com";
        $mail->Password = "adzhxhulxefgokob";
        $mail->SMTPSecure = "ssl";
        $mail->Port = 465;

        $mail->setFrom($_POST["email"], $_POST["subject"]);
        $mail->addAddress("chatlink.helper@gmail.com");

        $mail->isHTML(true);

        $mail->Subject = $_POST["subject"];
        $mail->Body = "Dear Admin! {$_POST["fullname"]} - wants to contact with our customer center. Please response as soon as you can. Message of {$_POST["fullname"]} - {$_POST["message"]} ";

        if ($mail->send()) {
            echo "
            <script>
                alert('Mail sent successfully.  Check your email for later instructions.');
                document.location.href='contact-us.php';
            </script>
        ";
        }
    } else {
        echo "
            <script>
                alert('Error');
                document.location.href='contact-us.php';
            </script>
        ";
    }
} else if (isset($_POST["tech-issue"])) {

    $mail = new PHPMailer(true);

    $mail->isSMTP();
    $mail->Host = "smtp.gmail.com";
    $mail->SMTPAuth = true;
    $mail->Username = "chatlink.helper@gmail.com";
    $mail->Password = "adzhxhulxefgokob";
    $mail->SMTPSecure = "ssl";
    $mail->Port = 465;

    $mail->setFrom("chatlink.helper@gmail.com", "Tech Support");
    $mail->addAddress($_POST["email"]);

    $mail->isHTML(true);

    $mail->Subject = $_POST["issue"];
    $mail->Body = "Your message sent to our tech center. Our tech support will response as soon as possible. Thank you for using ChatLine!";

    if ($mail->send()) {

        $mail = new PHPMailer(true);

        $mail->isSMTP();
        $mail->Host = "smtp.gmail.com";
        $mail->SMTPAuth = true;
        $mail->Username = "chatlink.helper@gmail.com";
        $mail->Password = "adzhxhulxefgokob";
        $mail->SMTPSecure = "ssl";
        $mail->Port = 465;

        $mail->setFrom($_POST["email"], $_POST["issue"]);
        $mail->addAddress("chatlink.helper@gmail.com");

        $mail->isHTML(true);

        $mail->Subject = $_POST["issue"];
        $mail->Body = "Dear Admin! {$_POST["fullname"]} - wants to contact with our tech support. Please response as soon as you can. The issue of {$_POST["fullname"]} - is {$_POST["issue"]} - {$_POST["message"]} ";

        if ($mail->send()) {
            echo "
            <script>
                alert('Mail sent successfully. Check your email for later instructions.');
                document.location.href='help.php';
            </script>
        ";
        }
    } else {
        echo "
            <script>
                alert('Error');
                document.location.href='help.php';
            </script>
        ";
    }
}
