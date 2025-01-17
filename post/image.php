<?php

require_once "../php/connect.php";

$result = [];

if (isset($_GET["post_id"])) {
    $sql = "SELECT * 
            FROM `posts`
            WHERE `post_id` = " . mysqli_real_escape_string($connection, $_GET["post_id"]);

    $query = mysqli_query($connection, $sql);

    $result = mysqli_fetch_assoc($query);

    $imageURL = str_replace("..", "/chatlink", $result["image_url"]);
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="shortcut icon" href="/chatlink/img/logo.ico" type="image/x-icon">

    <title>ChatLine - Upload Image Post</title>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.js"></script>
    <script src="/chatlink/js/checkTheme.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/js/all.min.js" integrity="sha512-u3fPA7V8qQmhBPNT5quvaXVa1mnnLSXUep5PS1qo5NRzHwG19aHmNJnj1Q8hpA/nBWZtZD4r4AX6YOt5ynLN2g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" href="https://bootswatch.com/4/flatly/bootstrap.min.css">
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/menu.css">
    <link rel="stylesheet" href="./style.css">
</head>

<body class="body">

    <div class="container">
        <p style="display: none; text-align: center;" id="redirect">Redirecting to main page in <span id="countdown">3</span> seconds...</p>

        <div class="row">
            <div class="col-md-8 m-auto">
                <a href="./index.html" class="return-link"><i class="fa-solid fa-arrow-left"></i> Back to main page</a>
                <div class="custom-file mb-3">
                    <form id="upload-form" enctype="multipart/form-data">
                        <?php
                        if (isset($_GET["post_id"])) {
                        ?>
                            <div class="image">
                                <img id="img" src="<?php echo $imageURL; ?>">
                            </div>
                        <?php
                        } else {
                        ?>
                            <input type="file" name="image" class="custom-file-input" accept="*/*" id="upload-file" required>
                            <label class="custom-file-label" for="upload-file">Select a mediafile:</label>
                        <?php } ?>
                        <br>
                        <canvas id="canvas" data-post-id="<?php echo $_GET["post_id"] ?>"></canvas>
                        <br>
                        <textarea name="caption" id="caption" rows="4" placeholder="Caption" required><?php if (count($result)) {
                                                                                                            echo $result["caption"];
                                                                                                        } ?></textarea>
                        <br>
                        <button type="button" id="upload-btn" class="btn btn-primary">Upload Post</button>

                        <input type="checkbox" name="download" id="download">
                        <label for="download">Allow downloading for this post.</label>

                    </form>
                </div>

                <h4 class="text-center my-3">Filters</h4>

                <div class="row my-4 text-center">
                    <div class="col-md-3">
                        <div class="btn-group btn-group-sm">
                            <button class="filter-btn brightness-remove btn btn-info">-</button>
                            <button class="btn btn-secondary btn-disabled" disabled>Brightness</button>
                            <button class="filter-btn brightness-add btn btn-info">+</button>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="btn-group btn-group-sm">
                            <button class="filter-btn contrast-remove btn btn-info">-</button>
                            <button class="btn btn-secondary btn-disabled" disabled>Contrast</button>
                            <button class="filter-btn contrast-add btn btn-info">+</button>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="btn-group btn-group-sm">
                            <button class="filter-btn saturation-remove btn btn-info">-</button>
                            <button class="btn btn-secondary btn-disabled" disabled>Saturation</button>
                            <button class="filter-btn saturation-add btn btn-info">+</button>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="btn-group btn-group-sm">
                            <button class="filter-btn vibrance-remove btn btn-info">-</button>
                            <button class="btn btn-secondary btn-disabled" disabled>Vibrance</button>
                            <button class="filter-btn vibrance-add btn btn-info">+</button>
                        </div>
                    </div>
                </div>
                <!-- ./row -->

                <h4 class="text-center my-3">Effects</h4>

                <div class="row mb-3">
                    <div class="col-md-3">
                        <button class="filter-btn vintage-add btn btn-dark btn-block">Vintage</button>
                    </div>
                    <div class="col-md-3">
                        <button class="filter-btn lomo-add btn btn-dark btn-block">Lomo</button>
                    </div>
                    <div class="col-md-3">
                        <button class="filter-btn clarity-add btn btn-dark btn-block">Clarity</button>
                    </div>
                    <div class="col-md-3">
                        <button class="filter-btn sincity-add btn btn-dark btn-block">Sin City</button>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <button class="filter-btn crossprocess-add btn btn-dark btn-block">Cross Process</button>
                    </div>
                    <div class="col-md-3">
                        <button class="filter-btn pinhole-add btn btn-dark btn-block">Pinhole</button>
                    </div>
                    <div class="col-md-3">
                        <button class="filter-btn nostalgia-add btn btn-dark btn-block">Nostalgia</button>
                    </div>
                    <div class="col-md-3">
                        <button class="filter-btn hermajesty-add btn btn-dark btn-block">Her Majesty</button>
                    </div>
                </div>

                <div class="row mt-5">
                    <div class="col-md-6">
                        <button id="download-btn" class="btn btn-primary btn-block">Download Image</button>
                    </div>
                    <div class="col-md-6">
                        <button id="revert-btn" class="btn btn-danger btn-block">Remove Filters</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- partial -->
    <script src='https://cdnjs.cloudflare.com/ajax/libs/camanjs/4.1.2/caman.full.min.js'></script>
    <?php
    if (isset($_GET["post_id"])) {
        echo '<script src="./edit.js"></script>';
    } else {
        echo '<script src="./script.js"></script>';
    }
    ?>
</body>

</html>