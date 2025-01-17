<?php

require_once "./functions.php";

if (isset($_POST['postId'])) {
    echo getCurrentPost($_POST['postId']);
} else {
    echo false;
}
