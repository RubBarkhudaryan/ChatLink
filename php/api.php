<?php

require_once 'functions.php';

$requestBody = file_get_contents("php://input");

if ($requestBody) {
    $requestData = json_decode($requestBody);

    if (isset($requestData->action)) {
        switch ($requestData->action) {
            case "signUp":
                echo signUp($requestData->name, $requestData->surname, $requestData->username, $requestData->password, $requestData->age, $requestData->email, $requestData->phone, $requestData->gender);
                break;
            case "signIn":
                echo signIn($requestData->username, $requestData->password);
                break;
            case "signOut":
                echo signOut();
                break;
            case "addLike":
                echo addLike($requestData->user_id, $requestData->post_id);
                break;
            case "addComment":
                echo addComment($requestData->post_id, $requestData->user_id, $requestData->content);
                break;
            case "getComments":
                echo getComments($requestData->post_id);
                break;
            case "deleteComment":
                echo deleteComment($requestData->comment_id);
                break;
            case "followButton":
                echo followRequest($requestData->follower_id, $requestData->following_id);
                break;
            case "blockUser":
                echo blockUser($requestData->user_id, $requestData->block_user_id);
                break;
            case "unblockUser":
                echo unblockUser($requestData->user_id, $requestData->block_user_id);
                break;
            case "checkLike":
                echo checkLike($requestData->user_id, $requestData->post_id);
                break;
            case "updateData":
                echo updateData($requestData->id, $requestData->name, $requestData->surname, $requestData->username, $requestData->bio, $requestData->gender);
                break;
            case "getMessages":
                echo getMessages($requestData->receiver_id, $requestData->sender_id);
                break;
            case "deletePost":
                echo deletePost($requestData->post_id);
                break;
            case "deleteAccount":
                echo deleteAccount($requestData->user_id);
                break;
            case "checkFollow":
                echo checkFollow($requestData->user_id, $requestData->following_id);
                break;
            case "checkBlock":
                echo checkBlock($requestData->user_id, $requestData->blocked_id);
                break;
        }
    }
}
