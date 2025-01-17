<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="/chatlink/img/logo.ico" type="image/x-icon">
    <title>ChatLine - Upload Video Post</title>
    <script src="/Chatlink/js/checkTheme.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/js/all.min.js" integrity="sha512-u3fPA7V8qQmhBPNT5quvaXVa1mnnLSXUep5PS1qo5NRzHwG19aHmNJnj1Q8hpA/nBWZtZD4r4AX6YOt5ynLN2g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="./style.css">
</head>

<body class="upload-container">

    <h1 class="page-title">Upload a Post</h1><br>
    <form action="./video_.php" method="post" enctype="multipart/form-data" class="form">
        <label for="image">Select a mediafile:</label>
        <input type="file" name="image" id="image" accept="*/*" required>
        <br>
        <textarea name="caption" id="caption" rows="4" placeholder="Caption" required></textarea>
        <br>
        <div>
            <input type="checkbox" name="download" id="download">
            <label for="download">Allow downloading for this post.</label>
        </div>
        <input type="submit" name="submit" value="Upload Post">

        <a href="./index.html" class="return-link"><i class="fa-solid fa-arrow-left"></i> Back to main page</a>
    </form>

</body>

</html>