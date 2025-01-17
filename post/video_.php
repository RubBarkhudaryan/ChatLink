<?php

require_once '../php/functions.php';

$user_id = checkUser();

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (isset($_FILES["image"]) && $_FILES["image"]["error"] === 0) {
        $image = $_FILES["image"];
        $caption = $_POST["caption"];
        $download = 0;

        if (isset($_POST["download"])) {
            $status = $_POST["download"];
            if (htmlspecialchars($status) == "on") {
                $download = 1;
            }
        }
        
        savePost($user_id, $image, $caption, $download);
    } else {
        echo "Error uploading image.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="/chatlink/js/checkTheme.js"></script>
    <link rel="stylesheet" href="./style.css">
    <title>Post</title>
</head>

<body class="upload-container">
    <p>Redirecting to main page in <span id="countdown">3</span> seconds...</p>

    <script>
        // Function to redirect to index.php after 3 seconds
        function redirect() {
            let countdown = 3; // Initial countdown time in seconds

            const countdownElement = document.getElementById("countdown");

            // Update the countdown every second
            const countdownInterval = setInterval(function() {
                countdown--;
                countdownElement.textContent = countdown;

                if (countdown <= 0) {
                    clearInterval(countdownInterval);
                    window.location.href = "./index.html";
                }
            }, 1000); // 1000 milliseconds (1 second)
        }

        window.onload = redirect();
    </script>

</body>

</html>

<?php

function savePost($user_id, $image, $caption, $download)
{
    global $connection;

    // Create user-specific folder if it doesn't exist
    $userFolderPath = "../uploads/user-" . $user_id . "/";

    if (!file_exists($userFolderPath)) {
        mkdir($userFolderPath, 0777, true);
    }

    // Upload image and get the uploaded file path
    $upload = uploadMedia($image, $user_id);

    $image_url = $upload[0];
    $uploadOk = $upload[1];

    if ($uploadOk) {
        $stmt = $connection->prepare(
            "INSERT INTO posts (`user_id`, `image_url`, `caption`, `timestamp`, `download`) VALUES (?, ?, ?, NOW(), ?)"
        );

        $stmt->bind_param("issi", $user_id, $image_url, $caption, $download);

        if ($stmt->execute() && $uploadOk == 1) {
            echo "Post saved successfully.<br>";
        } else {
            echo "Upload error. <br>";
        }

        $stmt->close();
    }
    $connection->close();
}

function uploadMedia($media, $user_id)
{
    // Set the target directory
    $uploadDir =  "../uploads/user-" . $user_id . "/";

    // Ensure the target directory exists, create it if not
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Get the original file name and extension
    $originalFileName = $media["name"];
    $fileExtension = pathinfo($originalFileName, PATHINFO_EXTENSION);

    // Sanitize the file name (excluding the extension)
    $sanitizedFileName = sanitizeFileName(pathinfo($originalFileName, PATHINFO_FILENAME));

    // Combine the sanitized file name with the original extension
    $targetFile = $uploadDir . $sanitizedFileName . '.' . $fileExtension;

    // Check if the file already exists in the target directory
    if (file_exists($targetFile)) {
        // Append a unique identifier (e.g., timestamp) to make the name unique
        $timestamp = time();
        $sanitizedFileName = "unique_" . $timestamp . "_" . $sanitizedFileName;
        $targetFile = $uploadDir . $sanitizedFileName . '.' . $fileExtension;
    }

    // Check file size
    $maxFileSize = min((int) ini_get('upload_max_filesize'), (int) ini_get('post_max_size'));
    $maxFileSizeInBytes = $maxFileSize * 1024 * 1024;

    if ($media["size"] > $maxFileSizeInBytes) {
        error_log("File size exceeds the maximum allowed size: " . $originalFileName);
        echo "Sorry, your file exceeds the maximum allowed size. <br>";
        return array($targetFile, 0);
    }

    // Check file type using extension
    $allowedExtensions = array("gif", "mp4", "avi", "mov");

    if (!in_array(strtolower($fileExtension), $allowedExtensions)) {
        error_log("Invalid file type: " . $originalFileName);
        echo "Sorry, only GIF, MP4, AVI, and MOV files are allowed. <br>";
        return array($targetFile, 0);
    }

    // Move the uploaded file to the target directory
    if (move_uploaded_file($media["tmp_name"], $targetFile)) {
        return array($targetFile, 1);
    } else {
        error_log("Error uploading file: " . $originalFileName);
        echo "Sorry, there was an error uploading your file. <br>";
        return array($targetFile, 0);
    }
}

?>