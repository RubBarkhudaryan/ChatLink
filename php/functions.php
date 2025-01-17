<?php

require_once 'connect.php';



function checkRegistration($username, $phone_number)
{
    global $connection;

    $sql_check_reg = "SELECT `phone` FROM `users` WHERE `phone` = '$phone_number'  AND `username` = '$username' ";

    $phone_numbers = mysqli_query($connection, $sql_check_reg);
    $numbers_count = count(mysqli_fetch_all($phone_numbers));

    if ($numbers_count > 0) {
        return false;
    } else {
        return true;
    }
}





function signUp($name, $surname, $username, $password, $age, $email, $phone, $gender)
{

    global $connection;

    $name_escape =  mysqli_real_escape_string($connection, $name);
    $surname_escape =  mysqli_real_escape_string($connection, $surname);
    $username_escape =  mysqli_real_escape_string($connection, $username);
    $password_hash = password_hash($password, PASSWORD_BCRYPT);
    $age = intval($age);
    $gender = mysqli_real_escape_string($connection, $gender);
    $email_escape = mysqli_real_escape_string($connection, $email);
    $phone_number_escape = mysqli_real_escape_string($connection, $phone);
    $phone_number_escape = mysqli_real_escape_string($connection, $phone);

    $response = array("status" => "", "msg" => "");


    if ($age > 14 && checkRegistration($username_escape, $phone_number_escape)) {
        $gender_ = 1;

        if ($gender == "male") {
            $gender_ = 1;
        } else if ($gender == "female") {
            $gender_ = 0;
        }

        $query = "INSERT INTO users(name, surname, age, gender, username, phone, email, password, registration_date)
        
        VALUES('$name_escape', '$surname_escape', $age,  $gender_, '$username_escape', '$phone_number_escape', '$email_escape', '$password_hash', NOW())";

        try {
            mysqli_query($connection, $query);

            $sql = "SELECT `user_id`
                    FROM users 
                    WHERE `username` = '$username_escape'";
            $query = mysqli_query($connection, $sql);

            if ($query) {
                $res = mysqli_fetch_assoc($query);
                createProfileImg(intval($res["user_id"]));
            }

            $response['status'] = 'ok';
            $response['msg'] = 'Registered';
            return json_encode($response);
        } catch (Exception $e) {
            $response['status'] = 'duplicate';
            $response['msg'] = 'You already used this data.';
            return json_encode($response);
        }
    } else if ($age < 14) {
        $response['status'] = 'error';
        $response['msg'] = "Your age isn't enough to register.";
        return json_encode($response);
    } else {
        $response['status'] = 'duplicate';
        $response['msg'] = 'You already used this data.';
        return json_encode($response);
    }
}





function signIn($username, $password)
{
    global $connection;
    $userName = strval($username);
    $password = strval($password);
    $response = array();

    // Retrieve the hashed password from the database
    $sql_username = "SELECT `user_id`, `name`, `password` FROM `users` WHERE `username` = '$userName'";
    $username_query = mysqli_query($connection, $sql_username);
    $fetched = mysqli_fetch_assoc($username_query);

    if ($fetched) {
        $hashed_password = $fetched['password'];

        // Verify the provided password with the hashed password
        if (password_verify($password, $hashed_password)) {
            $response['status'] = 'ok';
            $response['id'] = $fetched['user_id'];
            $response['name'] = $fetched['name'];

            $sql = "UPDATE `users` SET `last_login` = CURRENT_TIMESTAMP() WHERE `user_id` = '{$response['id']}'";
            $query = mysqli_query($connection, $sql);

            if ($query) {
                session_start();
                $_SESSION['user_id'] = $fetched['user_id'];
            } else {
                $response['status'] = 'error';
                $response['msg'] = 'Internal server error';
                $response['id'] = false;
                $response['name'] = false;
            }
        } else {
            $response['status'] = 'error';
            $response['msg'] = 'Wrong password';
            $response['id'] = false;
            $response['name'] = false;
        }
    } else {
        $response['status'] = 'error';
        $response['msg'] = 'Wrong username';
        $response['id'] = false;
        $response['name'] = false;
    }

    return json_encode($response);
}





function signOut()
{
    session_start();

    $_SESSION = array();

    session_destroy();

    return json_encode(["status" => true]);
    exit;
}





function checkUser()
{
    session_start();

    if (!isset($_SESSION['user_id'])) {
        $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI']; // Store the requested URL
        header('Location: ./php/signin.php'); // Redirect to the sign-in page
        exit;
    } else {
        $current_user = $_SESSION['user_id'];
        return $current_user;
    }
}





function addLike($user_id, $post_id)
{
    global $connection;

    // Check if the user has already liked the post
    $checkSql = "SELECT * FROM likes WHERE user_id = ? AND post_id = ?";
    $checkStmt = $connection->prepare($checkSql);
    $checkStmt->bind_param("ii", $user_id, $post_id);
    $checkStmt->execute();
    $result = $checkStmt->get_result();

    if ($result->num_rows > 0) {
        return removeLike($user_id, $post_id); // User has already liked the post
    }

    // Insert the like into the Likes table
    $insertSql = "INSERT INTO likes (user_id, post_id, timestamp) VALUES (?, ?, NOW())";
    $insertStmt = $connection->prepare($insertSql);
    $insertStmt->bind_param("ii", $user_id, $post_id);


    if ($insertStmt->execute()) {
        mysqli_query($connection, "UPDATE posts SET likes_count = likes_count+1 WHERE post_id = $post_id");
        $result = mysqli_query($connection, "SELECT likes_count FROM posts WHERE post_id = $post_id");
        $fetched = mysqli_fetch_assoc($result);
        $likes_count = $fetched["likes_count"];
        return json_encode(["status" => "success", "count" => $likes_count, "liked" => true]); // Successfully added the like
    } else {
        return json_encode(["status" => "error"]); // Error occurred while adding the like
    }
}





function removeLike($user_id, $post_id)
{

    global $connection;

    // Check if the user has liked the post
    $checkSql = "SELECT * FROM likes WHERE user_id = ? AND post_id = ?";
    $checkStmt = $connection->prepare($checkSql);
    $checkStmt->bind_param("ii", $user_id, $post_id);
    $checkStmt->execute();
    $result = $checkStmt->get_result();

    if ($result->num_rows === 0) {
        return json_encode(["status" => "not_liked"]); // User hasn't liked the post
    }

    // Remove the like from the Likes table
    $removeSql = "DELETE FROM likes WHERE user_id = ? AND post_id = ?";
    $removeStmt = $connection->prepare($removeSql);
    $removeStmt->bind_param("ii", $user_id, $post_id);

    if ($removeStmt->execute()) {
        mysqli_query($connection, "UPDATE posts SET likes_count = likes_count-1 WHERE post_id = $post_id");
        $result = mysqli_query($connection, "SELECT likes_count FROM posts WHERE post_id = $post_id");
        $fetched = mysqli_fetch_assoc($result);
        $likes_count = $fetched["likes_count"];
        return json_encode(["status" => "success", "count" => $likes_count, "liked" => false]); // Successfully removed the like
    } else {
        return json_encode(["status" => "error"]); // Error occurred while removing the like
    }
}





function sanitizeFileName($filename)
{
    // Remove special characters, spaces, and symbols (excluding letters from other languages)
    $filename = preg_replace('/[^\p{L}a-zA-Z0-9-_\.]/u', '_', $filename);

    // Limit the length of the filename
    $maxFilenameLength = 50; // Adjust as needed
    $filename = mb_substr($filename, 0, $maxFilenameLength);

    // Normalize case (convert to lowercase)
    $filename = mb_strtolower($filename);

    // Check for duplicates and make the name unique if necessary
    $uniqueFilename = $filename;
    $counter = 1;
    while (file_exists($uniqueFilename)) {
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        $basename = pathinfo($filename, PATHINFO_FILENAME);
        $uniqueFilename = $basename . '_' . $counter . '.' . $extension;
        $counter++;
    }

    return $uniqueFilename;
}


function checkLike($user_id, $post_id)
{
    global $connection;

    $sql = "SELECT user_id, post_id
            FROM likes
            WHERE user_id = $user_id AND post_id = $post_id";

    $res = mysqli_query($connection, $sql);

    $result = mysqli_fetch_assoc($res);

    if ($result > 0) {
        return json_encode(["status" => "liked"]);
    } else {
        return json_encode(["status" => "not_liked"]);
    }
}


function getPosts($user_id)
{
    global $connection;

    $followers = getRelationships($user_id);
    $followers = json_decode($followers);

    if (count($followers) > 0) {
        $followers_ids = [];

        foreach ($followers as $follow) {
            $followers_ids[] = $follow->user_id;
        }

        $posts = [];
        $sql = "SELECT `users`.`username`, `users`.`profile_image`, `posts`.* 
        FROM `users`, `posts` 
        WHERE (users.user_id = posts.user_id AND posts.user_id IN (" . implode(",", $followers_ids) . ") OR posts.user_id = $user_id AND users.user_id = $user_id)
        ORDER BY `post_id` DESC";

        $stmt = $connection->prepare($sql);

        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $posts[] = $row;
        }

        return $posts;
    } else {
        $posts = [];
        $sql = "SELECT `users`.`username`, `users`.`profile_image`, `posts`.* 
        FROM `users`, `posts` 
        WHERE posts.user_id = $user_id AND users.user_id = $user_id
        ORDER BY `posts`.`timestamp` DESC";

        $stmt = $connection->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $posts[] = $row;
        }

        if (count($posts) > 0) {
            return $posts;
        } else {
            return false;
        }
    }
}



function getCurrentUsersPosts($user_id)
{

    global $connection;

    $user_id = intval(mysqli_real_escape_string($connection, $user_id));

    $sql = "SELECT * 
            FROM `posts`
            WHERE `user_id` = $user_id 
            ORDER BY `post_id` DESC";

    $query = mysqli_query($connection, $sql);

    if ($query) {
        $result = mysqli_fetch_all($query, MYSQLI_ASSOC);
        return $result;
    } else {
        return false;
    }
}



function addComment($post_id, $user_id, $content)
{
    global $connection;

    $stmt = $connection->prepare("INSERT INTO comments (user_id, post_id, content, timestamp) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("iis", $user_id, $post_id, $content);
    $execute = $stmt->execute();

    $comment_id = mysqli_insert_id($connection);

    if ($execute) {
        // Update the comments count in Posts table
        mysqli_query($connection, "UPDATE posts SET comments_count = comments_count + 1 WHERE post_id = $post_id");

        return json_encode(["status" => "success", "commentID" => $comment_id]);
    } else {
        return json_encode(["status" => "error"]);
    }
    $stmt->close();
    $connection->close();
}


function getComments($post_id)
{
    global $connection;

    $comments = [];

    $sql = "SELECT comments.*, `users`.username 
            FROM comments, users 
            WHERE post_id = ? and comments.user_id = users.user_id
            ORDER BY timestamp DESC;";

    $stmt = $connection->prepare($sql);
    $stmt->bind_param("i", $post_id);
    $stmt->execute();

    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $comments[] = $row["content"];
        $comments[] = $row["username"];
        $comments[] = $row["user_id"];
        $comments[] = $row["comment_id"];
    }

    return json_encode($comments);
}





function deleteComment($comment_id)
{
    global $connection;

    $sql = "SELECT `post_id`
            FROM `comments` 
            WHERE `comment_id` = ?";

    $stmt = $connection->prepare($sql);
    $stmt->bind_param("i", $comment_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $post_id = $result->fetch_assoc();

    $sql = "DELETE FROM `comments` WHERE `comment_id` = ?";

    $stmt = $connection->prepare($sql);
    $stmt->bind_param("i", $comment_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $sql = "UPDATE `posts` SET `comments_count` = `comments_count` - 1 WHERE `post_id` = ? ";

    $stmt = $connection->prepare($sql);
    $stmt->bind_param("i", $post_id['post_id']);
    $stmt->execute();
    $result = $stmt->get_result();


    $sql = "SELECT SUM(if(`comment_id` = ?, true,false)) as result
            FROM `comments`;";

    $stmt = $connection->prepare($sql);
    $stmt->bind_param("i", $comment_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row["result"] == 1) {
        return false;
    } else {
        return true;
    }
}





function recommendUsers($user_id)
{
    global $connection;

    $users = [];
    $sql = "SELECT * FROM users 
            WHERE `user_id` != $user_id
            ORDER BY RAND() LIMIT 5;";

    $stmt = $connection->prepare($sql);

    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }

    return $users;
}





function followRequest($follower_id, $following_id)
{
    global $connection;

    $sql_request = "SELECT true as request
    FROM `followers`
    WHERE follower_id = ? AND following_id = ?";

    $stmt = $connection->prepare($sql_request);
    $stmt->bind_param("ii", $follower_id, $following_id);
    $execute = $stmt->execute();

    $following = false;

    if ($execute) {
        $result = $stmt->get_result();
        $result = mysqli_fetch_assoc($result);

        if (isset($result["request"])) {
            $following = true;
        } else {
            $following = false;
        }
    }

    if (!$following) {
        $sql_request = "INSERT INTO followers (`follower_id`, `following_id`) VALUES (?, ?)";
        $stmt = $connection->prepare($sql_request);
        $stmt->bind_param("ii", $follower_id, $following_id);
        $execute = $stmt->execute();

        if ($execute) {
            updateFollowCount(1, $following_id, $follower_id);

            if ($execute) {
                return json_encode(["status" => "following"]);
            } else {
                return json_encode(["status" => "error"]);
            }
        } else {
            return json_encode(["status" => "error"]);
        }
    } else {

        $sql_request = "DELETE FROM `followers` 
                        WHERE follower_id = ? AND following_id = ?";
        $stmt = $connection->prepare($sql_request);
        $stmt->bind_param("ii", $follower_id, $following_id);
        $execute = $stmt->execute();

        if ($execute) {

            updateFollowCount(-1, $following_id, $follower_id);

            if ($execute) {
                return json_encode(["status" => "not following"]);
            } else {
                return json_encode(["status" => "error"]);
            }
        } else {
            return json_encode(["status" => "error"]);
        }
    }
}




function updateFollowCount($change, $following_id, $follower_id)
{
    global $connection;

    $sql_update = "UPDATE users SET followers_count = followers_count + ? Where user_id = ?";
    $stmt = $connection->prepare($sql_update);
    $stmt->bind_param("ii", $change, $following_id);
    $stmt->execute();

    $sql_update = "UPDATE users SET followings_count = followings_count + ? Where user_id = ?";
    $stmt = $connection->prepare($sql_update);
    $stmt->bind_param("ii", $change, $follower_id);
    $stmt->execute();
}


function blockUser($user_id, $blocked_user_id)
{
    global $connection;

    $following = checkFollowStatus($user_id, $blocked_user_id);
    $followed = checkFollowStatus($blocked_user_id, $user_id);

    if ($following || $followed) {
        $status = 'blocked';

        $sql_request = "UPDATE followers 
                        SET status = ? 
                        WHERE (follower_id = ? AND following_id = ?) OR (follower_id = ? AND following_id = ?)";

        $stmt = $connection->prepare($sql_request);
        $stmt->bind_param("siiii", $status, $user_id, $blocked_user_id, $blocked_user_id, $user_id);
        $execute = $stmt->execute();

        if ($execute) {
            return json_encode(["status" => "blocked"]);
        } else {
            return json_encode(["status" => "not blocked", "message" => $stmt->error]);
        }
    } else {
        return json_encode(["status" => "not blocked"]);
    }
}


function getBlockedUsers($user_id)
{
    global $connection;
    $sql = "SELECT DISTINCT user_id
            FROM (
                SELECT follower_id AS user_id
                FROM followers
                WHERE following_id = $user_id AND status = 'blocked'

                UNION

                SELECT following_id AS user_id
                FROM followers
                WHERE follower_id = $user_id AND status = 'blocked'
            ) AS blocked_users";

    $query = mysqli_query($connection, $sql);
    $result = [];

    if ($query) {
        $result = mysqli_fetch_all($query, MYSQLI_ASSOC);
    }
    return $result;
}


function checkFollowStatus($follower_id, $following_id)
{
    global $connection;

    $sql_request = "SELECT true as request
                    FROM `followers`
                    WHERE follower_id = ? AND following_id = ? AND ISNULL(status)";

    $stmt = $connection->prepare($sql_request);
    $stmt->bind_param("ii", $follower_id, $following_id);
    $execute = $stmt->execute();

    if ($execute) {
        $result = $stmt->get_result();
        $row = mysqli_fetch_assoc($result);
        return isset($row["request"]);
    } else {
        return false;
    }
}



function unblockUser($user_id, $blocked_user_id)
{
    global $connection;

    if (is_string($blocked_user_id)) {
        $blocked_user_id = getCurrentUser(0, $blocked_user_id);
        $blocked_user_id = $blocked_user_id["user_id"];
    }

    $following = checkBlockedStatus($user_id, $blocked_user_id);
    $followed = checkBlockedStatus($blocked_user_id, $user_id);

    if ($following || $followed) {
        $status = null;

        $sql_request = "UPDATE followers 
                        SET status = ? 
                        WHERE (follower_id = ? AND following_id = ?) OR (follower_id = ? AND following_id = ?)";

        $stmt = $connection->prepare($sql_request);
        $stmt->bind_param("siiii", $status, $user_id, $blocked_user_id, $blocked_user_id, $user_id);
        $execute = $stmt->execute();

        if ($execute) {
            return json_encode(["status" => "unblocked"]);
        } else {
            return json_encode(["status" => "not unblocked", "message" => $stmt->error]);
        }
    } else {
        return json_encode(["status" => "not unblocked"]);
    }
}

function checkBlockedStatus($follower_id, $following_id)
{
    global $connection;

    $sql_request = "SELECT true as request
                    FROM `followers`
                    WHERE follower_id = ? AND following_id = ? AND status = 'blocked'";

    $stmt = $connection->prepare($sql_request);
    $stmt->bind_param("ii", $follower_id, $following_id);
    $execute = $stmt->execute();

    if ($execute) {
        $result = $stmt->get_result();
        $row = mysqli_fetch_assoc($result);
        return isset($row["request"]);
    } else {
        return false;
    }
}



function getFollowCount($user_id)
{
    global $connection;

    $sql_request = "SELECT followers_count as followers, followings_count as followings
                    FROM users 
                    WHERE user_id = ?";
    $stmt = $connection->prepare($sql_request);
    $stmt->bind_param("i", $user_id);

    $execute =  $stmt->execute();

    if ($execute) {
        $result = $stmt->get_result();
        $row = mysqli_fetch_assoc($result);

        return json_encode($row);
    } else {
        return false;
    }
}



function getRelationships($user_id)
{
    global $connection;

    $sql = "SELECT users.user_id, users.name, users.surname, users.username, users.profile_image  
            from users, followers
            WHERE (users.user_id = followers.follower_id AND followers.following_id = ? AND ISNULL(followers.status)) 
            OR    (users.user_id = followers.following_id AND followers.follower_id = ? AND ISNULL(followers.status))";

    $stmt = $connection->prepare($sql);
    $stmt->bind_param("ii", $user_id, $user_id);
    $execute = $stmt->execute();


    if ($execute) {
        $res = [];
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $res[] = $row;
        }
        return json_encode($res);
    } else {
        return false;
    }
}


function getCurrentUser($id = 0, $username = "")
{
    global $connection;

    $result = [];
    $query = "";
    $sql = "";

    if ($id != 0) {
        $id = intval(mysqli_real_escape_string($connection, $id));
        $sql = "SELECT `name`, `surname`, `username`, `bio`, `profile_image` , `gender`
                FROM users 
                WHERE `user_id` = $id";
    } else if ($username != "") {
        $username = mysqli_real_escape_string($connection, $username);
        $sql = "SELECT `users`.`user_id`, `name`, `surname`, `username`, `profile_image`, `bio`, `followers_count`, `followings_count`, count(`posts`.`user_id`) as postsCount
                FROM `users` left join `posts` ON `users`.`user_id` = `posts`.`user_id`
                WHERE `users`.`username` = '$username'";
    }

    $query = mysqli_query($connection, $sql);

    if ($query) {
        $result = mysqli_fetch_assoc($query);
        return $result;
    } else {
        return false;
    }
}


function updateData($id, $name, $surname, $username, $bio, $gender)
{
    global $connection;

    $id_ = intval($id);
    $name_ =  mysqli_real_escape_string($connection, $name);
    $surname_ =  mysqli_real_escape_string($connection, $surname);
    $username_ =  mysqli_real_escape_string($connection, $username);
    $bio_ = mysqli_real_escape_string($connection, $bio);
    $gender_ = mysqli_real_escape_string($connection, $gender);
    $gnd = 0;

    if ($gender_ == "male") {
        $gnd = 1;
    } else if ($gender_ == "female") {
        $gnd = 0;
    }

    $sql = "SELECT username
            FROM `users`
            WHERE `user_id` = $id_";

    $query = mysqli_query($connection, $sql);
    $res = mysqli_fetch_assoc($query);
    $validUsername = true;

    if ($res["username"] != $username_) {
        $sql = "SELECT username
        FROM `users`";

        $query = mysqli_query($connection, $sql);

        $result = mysqli_fetch_all($query, MYSQLI_ASSOC);

        foreach ($result as $usernames) {
            if ($usernames["username"] == $username_) {
                $validUsername = false;
                break;
            }
        }
    }

    $res = [];

    if (!$validUsername) {
        $res["status"] = "error";
        $res["message"] = "Username already exists.";
        return json_encode($res);
    } else {
        $sql = "UPDATE users 
                SET `name` = '$name_', `surname` = '$surname_', `username` = '$username_', `bio` = \"$bio_\", `gender` = $gnd 
                WHERE `user_id` = $id_";
        $query = mysqli_query($connection, $sql);

        if ($query) {
            $res["status"] = "ok";
            $res["message"] = "Data updated successfully.";
            return json_encode($res);
        } else {
            $res["status"] = "error";
            $res["message"] = "An error occured while updating data. Please try later.";
            return json_encode($res);
        }
    }
}



function createProfileImg($id)
{

    global $connection;

    $uploads = "../uploads";
    $userFolder = "user-$id";
    $profileImg = "profile-img";
    $fileName = "profile-img.png";

    if (!file_exists($uploads)) {
        mkdir($uploads);
    }

    $userFolderPath = $uploads . "/" . $userFolder;

    if (!file_exists($userFolderPath)) {
        mkdir($userFolderPath);
    }
    $profileImgPath = $userFolderPath . "/" . $profileImg;
    if (!file_exists($profileImgPath)) {
        mkdir($profileImgPath);
    }

    $filePath = "../img/" . $fileName;

    $destinationPath = $profileImgPath . "/" . $fileName;

    if (file_exists($filePath)) {
        if (copy($filePath, $destinationPath)) {
            $sql = "UPDATE users 
                    SET `profile_image` = \"$destinationPath\"
                    WHERE `user_id` = $id";
            $query = mysqli_query($connection, $sql);

            if ($query) {
                return true;
            } else {
                false;
            }
        }
    }
}

function getProfileImg($id)
{
    global $connection;

    $sql = "SELECT `profile_image` as img
            FROM `users` 
            WHERE `user_id` = $id";
    $query = mysqli_query($connection, $sql);

    if ($query) {
        $res = mysqli_fetch_assoc($query);
        return $res["img"];
    }
}


function updateProfileImg($id, $image)
{
    global $connection;

    $oldProfielImage = getProfileImg($id);

    if (file_exists($oldProfielImage)) {
        unlink($oldProfielImage);
    }
    $upload = uploadProfileImage($id, $image);

    if ($upload[1]) {
        $sql = "UPDATE `users`
                SET `profile_image` = '$upload[0]'
                WHERE `user_id` = $id";
        $query = mysqli_query($connection, $sql);
        if ($query) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}




function uploadProfileImage($id, $media)
{
    // Set the target directory
    $uploadDir =  "../uploads/user-$id/profile-img/";

    // Get the original file name and extension
    $originalFileName = $media["name"];
    $fileExtension = pathinfo($originalFileName, PATHINFO_EXTENSION);

    // Combine the sanitized file name with the original extension
    $targetFile = $uploadDir . $originalFileName;

    // Check if the file already exists in the target directory
    if (!file_exists($targetFile)) {
        $sanitizedFileName = "profile-img." . $fileExtension;
        $targetFile = $uploadDir . $sanitizedFileName;
    }

    // Check file size
    $maxFileSize = min((int) ini_get('upload_max_filesize'), (int) ini_get('post_max_size'));
    $maxFileSizeInBytes = $maxFileSize * 1024 * 1024;

    if ($media["size"] > $maxFileSizeInBytes) {
        echo "
        <script>
            alert('Sorry, your file exceeds the maximum allowed size. ');
        </script>
        ";
        return array($targetFile, 0);
    }

    // Check file type using extension
    $allowedExtensions = array("jpg", "jpeg", "png");

    if (!in_array(strtolower($fileExtension), $allowedExtensions)) {
        echo "
        <script>
            alert('Sorry, only JPG, JPEG and PNG files are allowed.');
        </script>
        ";

        return array($targetFile, 0);
    }

    // Move the uploaded file to the target directory
    if (move_uploaded_file($media["tmp_name"], $targetFile)) {
        return array($targetFile, 1);
    } else {
        echo "
        <script>
            alert('Sorry, there was an error uploading your file.');
        </script>
        ";

        return array($targetFile, 0);
    }
}


function getMessages($receiver_id, $sender_id)
{
    global $connection;

    $sql = "SELECT * 
            FROM messages
            WHERE (receiver_id = ? AND sender_id = ?) OR (receiver_id = ? AND sender_id = ?)
            ORDER BY id ASC";

    $stmt = $connection->prepare($sql);
    $stmt->bind_param("iiii", $receiver_id, $sender_id, $sender_id, $receiver_id);
    $execute = $stmt->execute();

    if ($execute) {
        $res = [];
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $res[] = $row;
        }
        return json_encode($res);
    } else {
        return [];
    }
}


function deletePost($post_id)
{

    global $connection;

    $post_id = mysqli_real_escape_string($connection, $post_id);

    $sql = "SELECT * 
            FROM `posts` 
            WHERE `post_id` = $post_id";

    $query = mysqli_query($connection, $sql);

    $post = mysqli_fetch_assoc($query);

    if (file_exists($post["image_url"])) {

        if (unlink($post["image_url"])) {
            $sql = "DELETE FROM `posts` WHERE `post_id` = $post_id";
            $query = mysqli_query($connection, $sql);
            if ($query) {
                return json_encode(["status" => true, "message" => "Post deleted successfully."]);
            } else {
                return json_encode(["status" => false, "message" => "Internal server error."]);
            }
        } else {
            return json_encode(["status" => false, "message" => "Unable to delete the post."]);
        }
    } else {
        return json_encode(["status" => false, "message" => "File does not exist."]);
    }
}


function deleteAccount($user_id)
{
    global $connection;

    $user_id = mysqli_real_escape_string($connection, $user_id);

    $sql = "SELECT DISTINCT following_id 
            FROM followers
            WHERE following_id != $user_id AND follower_id = $user_id";

    $query = mysqli_query($connection, $sql);

    if ($query) {

        $followings = [];
        $followers = [];

        $followings = mysqli_fetch_all($query, MYSQLI_ASSOC);

        $fol_i = "";

        $sql = "SELECT DISTINCT follower_id 
                FROM followers
                WHERE follower_id != $user_id AND following_id = $user_id";

        $query = mysqli_query($connection, $sql);

        if ($query) {

            $followers = mysqli_fetch_all($query, MYSQLI_ASSOC);

            $fol_r = "";

            if (count($followings)) {

                foreach ($followings as $f) {
                    $fol_i .= strval($f["following_id"]) . ',';
                }

                $fol_i = rtrim($fol_i, ',');

                $sql = "UPDATE users SET followings_count = followings_count - 1 WHERE `user_id` IN(" . $fol_i . ")";

                $query = mysqli_query($connection, $sql);
            }

            if (count($followers)) {

                foreach ($followers as $f) {
                    $fol_r .= strval($f["follower_id"]) . ',';
                }

                $fol_r = rtrim($fol_r, ',');

                $sql = "UPDATE users SET followers_count = followers_count - 1 WHERE `user_id` IN(" . $fol_r . ")";

                $query = mysqli_query($connection, $sql);
            }

            $sql = "DELETE FROM users WHERE `user_id` = $user_id";

            $query = mysqli_query($connection, $sql);

            if ($query) {

                $userFolder = '../uploads/user-' . $user_id . '/';
                try {
                    if (deleteDirectory($userFolder)) {
                        return json_encode(["status" => true, "message" => "Your account deleted successfully."]);
                    } else {
                        return json_encode(["status" => false, "message" => "Failed to delete your data."]);
                    }
                } catch (InvalidArgumentException $e) {
                    return  json_encode(["status" => false, "message" => $e->getMessage()]);
                }
            }
        }
    }
}


function deleteDirectory($dirPath)
{
    if (!is_dir($dirPath)) {
        throw new InvalidArgumentException("$dirPath must be a directory");
    }

    $files = array_diff(scandir($dirPath), array('.', '..'));
    foreach ($files as $file) {
        $filePath = $dirPath . DIRECTORY_SEPARATOR . $file;
        if (is_dir($filePath)) {
            deleteDirectory($filePath);
        } else {
            unlink($filePath);
        }
    }

    return rmdir($dirPath);
}


function checkFollow($user_id, $follow_id)
{
    global $connection;

    $sql = "SELECT follower_id, following_id
            FROM followers
            WHERE follower_id = $user_id AND following_id = $follow_id";

    $res = mysqli_query($connection, $sql);

    $result = mysqli_fetch_assoc($res);

    if ($result) {
        return json_encode(["status" => "following"]);
    } else {
        return json_encode(["status" => "not following"]);
    }
}



function checkBlock($user_id, $blocked_id)
{
    global $connection;

    $sql = "SELECT follower_id, following_id
            FROM followers
            WHERE (follower_id = $user_id AND following_id = $blocked_id AND `status` = 'blocked') 
            OR (following_id = $user_id AND follower_id = $blocked_id AND `status` = 'blocked') ";

    $res = mysqli_query($connection, $sql);

    $result = mysqli_fetch_assoc($res);

    if ($result) {
        return json_encode(["status" => "blocked"]);
    } else {
        return json_encode(["status" => "not blocked"]);
    }
}

function getCurrentPost($post_id)
{
    global $connection;
    $post_id = mysqli_real_escape_string($connection, $post_id);

    $sql = "SELECT `posts`.*, `users`.`user_id`, `users`.`username` as `username`,`users`.`profile_image` as `profile` 
            FROM `posts`, `users`
            WHERE `posts`.`user_id` = `users`.`user_id` AND `post_id` = $post_id;";

    $query = mysqli_query($connection, $sql);

    $result = mysqli_fetch_assoc($query);

    return json_encode($result);
}
