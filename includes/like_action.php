<?php
/**
 * Created by PhpStorm.
 * User: nick
 * Date: 04/02/2018
 * Time: 17:25
 */
session_start();

require_once "{$_SERVER['DOCUMENT_ROOT']}/includes/db_connect.php";

$unlike_pid = $_POST['pid'];
$username = $_SESSION['username'];

// Check if user liked before or not
function previously_liked($unlike_pid, $username) {
    global $conn;
    $previous_likes = $conn->query("SELECT * FROM posts_likes WHERE pid = '$unlike_pid' AND username = '$username'");

    if ($previous_likes->num_rows > 0) {
        $conn->query("UPDATE posts_likes SET liked = 1 WHERE pid = '$unlike_pid' AND username='$username'");
    } else {
        $conn->query("INSERT INTO posts_likes (pid, username, liked) VALUES ('$unlike_pid', '$username', 1)");
    }
}

previously_liked($unlike_pid, $username);