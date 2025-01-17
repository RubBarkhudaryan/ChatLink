<?php
session_start();
require_once "../php/connect.php";
if (!isset($_SESSION['user_id'])) {
  header("location: ../php/signin.php");
}
?>
<?php include_once "header.php"; ?>

<body>
  <div class="wrapper">
    <section class="users">
      <header>
        <div class="content">
          <?php
          $sql = mysqli_query($connection, "SELECT * FROM users WHERE `user_id` = {$_SESSION['user_id']}");
          if (mysqli_num_rows($sql) > 0) {
            $row = mysqli_fetch_assoc($sql);
          }
          ?>
          <img src="<?php echo str_replace("..", "/chatlink", $row["profile_image"]); ?>" alt="">
          <div class="details">
            <span><?php echo $row['name'] . " " . $row['surname'] ?></span>
          </div>
          <span><a href="../index.php" style="color: var(--primary-text); font-weight:bold; font-size:large">Back to home page</a></span>
        </div>
      </header>
      <div class="search">
        <span class="text">Select an user to start chat</span>
        <input type="text" placeholder="Enter name to search...">
        <button><i class="fas fa-search"></i></button>
      </div>
      <div class="users-list">
      </div>
    </section>
  </div>

  <script src="./users.js"></script>

</body>

</html>