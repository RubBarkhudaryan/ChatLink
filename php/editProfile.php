<?php
require_once "./functions.php";
$user_id = checkUser();
$currentUser = getCurrentUser($user_id, "");

if (isset($_POST["replaceImage"])) {
    if (isset($_FILES["profile-image"]) && $_FILES["profile-image"]["error"] === 0) {
        $image = $_FILES["profile-image"];
        $upload = updateProfileImg($user_id, $image);
        if ($upload) {
            echo "<script> alert('Profile image updated successfully.');</script>";
        } else {
            echo "<script> alert('An error occured while changing profile image.');</script>";
        }
    } else {
        echo "<script> alert('Image upload error.'); </script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <script src="/chatlink/js/checkTheme.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.js"></script>
    <link rel="stylesheet" href="/chatlink/css/settings.css">
    <link rel="stylesheet" href="/chatlink/css/footer.css">
</head>

<body>
    <h1 class="page-title">Edit Profile</h1>

    <form action="./editProfile.php" method="post" enctype="multipart/form-data">
        <div>
            <img class="profileIMG" src="<?php echo getProfileImg($user_id); ?>" alt="">
            <label class="label" for="profile-image">Profile Image</label>
            <input class="updated-data" type="file" id="profile-image" name="profile-image" accept=".png, .jpg, .jpeg">
        </div>

        <input type="submit" name="replaceImage" class="updated-data updateImage" value="Replace Profile Image">

    </form>


    <form>

        <div>
            <label class="label" for="name">Name</label>
            <input class="updated-data" id="name" type="text" name="name" value="<?php echo $currentUser["name"]; ?>">
        </div>

        <div>
            <label class="label" for="surname">Surname</label>
            <input class="updated-data" id="surname" type="text" name="surname" value="<?php echo $currentUser["surname"]; ?>">
        </div>

        <div>
            <label class="label" for="username">User Name</label>
            <input class="updated-data" id="username" type="text" name="username" value="<?php echo $currentUser["username"]; ?>">
        </div>

        <div>
            <label class="label" for="bio" maxlength="200">Bio<p class="bio-length"><input disabled type="text" name="countdown" size="3" value="0"> / 200</p></label>
            <textarea class="updated-data" id="bio" name="bio" onKeyDown="limitText(this.form.bio,this.form.countdown,200);" onKeyUp="limitText(this.form.bio,this.form.countdown,200);"><?php echo $currentUser["bio"]; ?></textarea>

        </div>


        <span class="label">Gender</span>
        <div>
            <?php
            if ($currentUser["gender"] == 1) {
                echo '
               <input class="updated-data" type="radio" name="gender" id="male" value="1" checked>
               <label for="male">Male</label>

               <input class="updated-data" type="radio" name="gender" id="female" value="0">
               <label for="female">Female</label>
                ';
            } else {
                echo '
                <input class="updated-data" type="radio" name="gender" id="male" value="1">
                <label for="male">Male</label>
 
                <input class="updated-data" type="radio" name="gender" id="female" value="0" checked>
                <label for="female">Female</label>
                 ';
            }
            ?>
        </div>

        <button type="button" id="updateData" class="updated-data">Save Data</button>
    </form>

    <script src="/chatlink/js/editProfile.js"></script>
    <script>
        function limitText(limitField, limitCount, limitNum) {
            if (limitField.value.length > limitNum) {
                limitField.value = limitField.value.substring(0, limitNum);
            } else {
                limitCount.value = limitField.value.length;
            }
        }
    </script>

    <?php require_once "./footer.php"; ?>
</body>

</html>