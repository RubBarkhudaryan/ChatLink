<?php
require_once "../php/functions.php";

header('Content-Type: application/json');

$response = [];

try {
    // Check if edit action is requested
    if (isset($_POST['edit']) && $_POST['edit'] === "editImage") {
        // Get user ID
        $user_id = checkUser();

        // Extract image data, caption, and post ID
        $imageData = $_POST['image'];
        $caption = trim($_POST['caption']);
        $post_id = $_POST['post_id'];

        // Ensure user directory exists
        $directory = '../uploads/user-' . $user_id . '/';
        if (!is_dir($directory) && !mkdir($directory, 0777, true)) {
            throw new Exception('Failed to create directory.');
        }

        // Generate filename for the edited image
        $filename = $directory . uniqid() . '.jpg';

        // Save the edited image
        if (!file_put_contents($filename, base64_decode(explode(',', $imageData)[1]))) {
            throw new Exception('Failed to save edited image.');
        }

        // Retrieve old image URL from the database
        $oldImageQuery = $connection->prepare("SELECT `image_url` FROM posts WHERE `post_id` = ? LIMIT 1");
        $oldImageQuery->bind_param("i", $post_id);
        $oldImageQuery->execute();
        $oldImageResult = $oldImageQuery->get_result();

        if ($oldImageResult->num_rows > 0) {
            $oldImageData = $oldImageResult->fetch_assoc();
            $oldImageUrl = $oldImageData['image_url'];

            // Delete the old image file
            if (file_exists($oldImageUrl)) {
                unlink($oldImageUrl);
            }
        }

        // Update the post with the edited image
        $updateStmt = $connection->prepare("UPDATE posts SET `image_url` = ?, `caption` = ?, `timestamp` = NOW() WHERE `post_id` = ?");
        $updateStmt->bind_param("ssi", $filename, $caption, $post_id);
        if (!$updateStmt->execute()) {
            throw new Exception('Failed to update post.');
        }

        // Success message
        $response['success'] = 'Post updated successfully.';

        // Close prepared statements
        $updateStmt->close();
        $oldImageQuery->close();
    } else {
        throw new Exception('Invalid request.');
    }
} catch (Exception $e) {
    // Error response
    http_response_code(500);
    $response['error'] = 'Server error: ' . $e->getMessage();
}

// Output JSON response
echo json_encode($response);
