<?php
require_once "../php/functions.php";

header('Content-Type: application/json');

$response = [];

try {
    $user_id = checkUser();

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405); // Method Not Allowed
        $response['error'] = 'Invalid request method.';
        echo json_encode($response);
        exit;
    }

    if (empty($_POST['image']) || empty($_POST['caption'])) {
        http_response_code(400); // Bad Request
        $response['error'] = 'Invalid input.';
        echo json_encode($response);
        exit;
    }

    $imageData = $_POST['image'];
    $caption = trim($_POST['caption']);
    $download = intval($_POST['download']);

    if ($caption === "") {
        http_response_code(400); // Bad Request
        $response['error'] = 'Caption cannot be empty.';
        echo json_encode($response);
        exit;
    }

    // Decode the image data
    list($type, $imageData) = explode(';', $imageData);
    list(, $imageData) = explode(',', $imageData);
    $imageData = base64_decode($imageData);

    if ($imageData === false) {
        http_response_code(400); // Bad Request
        $response['error'] = 'Invalid image data.';
        echo json_encode($response);
        exit;
    }

    // Specify the directory where the file will be saved
    $directory = '../uploads/user-' . $user_id . '/';
    if (!is_dir($directory)) {
        if (!mkdir($directory, 0777, true)) {
            http_response_code(500); // Internal Server Error
            $response['error'] = 'Failed to create directory.';
            echo json_encode($response);
            exit;
        }
    }

    // Create a unique filename
    $filename = $directory . uniqid() . '.jpg';

    // Save the file
    if (file_put_contents($filename, $imageData) === false) {
        http_response_code(500); // Internal Server Error
        $response['error'] = 'Failed to save image file.';
        echo json_encode($response);
        exit;
    }

    // Prepare and execute the SQL statement
    if ($stmt = $connection->prepare("INSERT INTO posts (`user_id`, `image_url`, `caption`, `timestamp`, `download`) VALUES (?, ?, ?, NOW(), ?)")) {
        $stmt->bind_param("issi", $user_id, $filename, $caption, $download);
        if ($stmt->execute()) {
            http_response_code(200); // OK
            $response['success'] = 'Post saved successfully.';
        } else {
            http_response_code(500); // Internal Server Error
            $response['error'] = 'Database error: ' . $stmt->error;
        }
        $stmt->close();
        echo json_encode($response);
        exit;
    } else {
        http_response_code(500); // Internal Server Error
        $response['error'] = 'Database error: ' . $connection->error;
    }

    $connection->close();
} catch (Exception $e) {
    http_response_code(500); // Internal Server Error
    $response['error'] = 'Server error: ' . $e->getMessage();
}

echo json_encode($response);
