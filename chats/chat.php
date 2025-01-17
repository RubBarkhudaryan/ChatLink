<?php
session_start();
require_once "../php/connect.php";

if (!isset($_SESSION['user_id'])) {
  header("location: ../php/signin.php");
  exit();
}

$user_id = mysqli_real_escape_string($connection, $_GET['user_id']);
$sql = mysqli_query($connection, "SELECT * FROM users WHERE `user_id` = {$user_id}");

if (mysqli_num_rows($sql) <= 0) {
  header("location: ./users.php");
  exit();
}

$row = mysqli_fetch_assoc($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="shortcut icon" href="/chatlink/img/logo.ico" type="image/x-icon">
  <title>ChatLine - <?php echo $row["username"]; ?></title>

  <script src="../js/checkTheme.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/js/all.min.js"></script>
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link rel="stylesheet" href="./style.css">
</head>

<body>
  <div class="wrapper">
    <section class="chat-area">
      <header>
        <a href="users.php" class="back-icon"><i class="fas fa-arrow-left"></i></a>
        <img src="<?php echo str_replace("..", "/chatlink", $row["profile_image"]) ?>" alt="">
        <div class="details">
          <span><?php echo $row['name'] . " " . $row['surname'] ?></span>
        </div>
      </header>
      <div class="chat-box"></div>
      <form action="#" class="typing-area">
        <input type="text" class="incoming_id" name="incoming_id" value="<?php echo $_GET["user_id"]; ?>" hidden>
        <button id="emojis"><i class="fa-solid fa-icons"></i></button>
        <div id="emoji-menu" style="display: none;">
        </div>
        <input type="text" id="input-field" name="message" class="input-field" placeholder="Type a message here..." autocomplete="off">
        <button id="send-btn"><i class="fab fa-telegram-plane"></i></button>
      </form>
    </section>
  </div>

  <script src="./chat.js"></script>

</body>

</html>