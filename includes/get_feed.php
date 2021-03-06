<?php
session_start();
require_once "{$_SERVER['DOCUMENT_ROOT']}/includes/db_connect.php";
$username = $_SESSION['username'];
// Select all posts and their respective usernames and profile images
$posts = $conn->query("SELECT * FROM posts INNER JOIN users ON posts.username = users.username ORDER BY pid DESC LIMIT 10");

while ($row = $posts->fetch_assoc()) {

    // Set the $post_id to be the value of post id from fetched row.
    $post_id = $row['pid'];

    // Get the like status of the user (if user liked the image or not)
    $like_status = $conn->query("SELECT * FROM posts_likes WHERE pid = '$post_id' AND username = '$username'");

    // If the liked value is 1 then change the FontAwesome icon to fas
    if ($like_status->fetch_assoc()['liked'] == 1) {
        $liked_icon = "fas";
        $liked = 'unlike';
    } else {
        $liked_icon = "far";
        $liked = 'like';
    }

    // Get the total amount of likes of the specific image
    $like_total = $conn->query("SELECT SUM(liked) FROM posts_likes WHERE pid = '$post_id'");
    $total_likes = $like_total->fetch_assoc()['SUM(liked)'];

    // If the amount of likes is less than 1 then set the total likes to empty string.
    if ($total_likes < 1) {
        $total_likes = '';
    }

    //! Re-use this part of code for Profile pages
    // Check if profile_img is NULL
    if (empty($row['profile_img'])) {

        // If it's NULL then set the profile image to the default one.
        $row['profile_img'] = '../assets/uploads/profile/default.jpg';
    }

    if ($row['img'] == 1) {
        echo
        "
                <div class=\"row feed-row\">
                <div class=\"col-lg-12 feed-post\">
                    <div class=\"feed-user\">
                        <img src=\"{$row['profile_img']}\" class=\"thumbnail\" height=\"40\"/>
                        <h3><a href=\"/public/user/{$row['username']}\">{$row['username']}</a></h3>
                    </div>
                    <div class=\"feed-media\">
                        <img src=\"{$row['media']}\" class=\"feed-img\">
                    </div>
                    <div class=\"feed-reaction\">
                        <div class=\"row\">
                            <div class=\"col-6\">
                                <i class=\"{$liked_icon} fa-fw fa-2x fa-heart {$liked}\" id=\"postid_{$row['pid']}\"></i> <span>{$total_likes}</span>
                            </div>
                            <div class=\"col-6\">
                                <i class=\"far fa-fw fa-2x fa-comment comment\" id=\"postid_{$row['pid']}\"></i>
                            </div>
                        </div>
                    </div>
                    <div class=\"feed-comments\" id=\"postid_{$row['pid']}\">
                    
                    </div>
                </div>
            </div>
                ";
    } else {
        echo
        "
        <div class=\"row feed-row\">
                <div class=\"col-lg-12 feed-post\">
                    <div class=\"feed-user\">
                        <img src=\"{$row['profile_img']}\" class=\"thumbnail\" height=\"40\"/>
                        <h3><a href=\"/public/user/{$row['username']}\">{$row['username']}</a></h3>
                    </div>
                    <div class=\"feed-media\">
                        <video controls class=\"feed-video\">
                            <source src=\"{$row['media']}\" type=\"video/mp4\">
                        </video>
                    </div>
                    <div class=\"feed-reaction\">
                        <div class=\"row\">
                            <div class=\"col-6\">
                                <i class=\"{$liked_icon} fa-fw fa-2x fa-heart {$liked}\" id=\"postid_{$row['pid']}\"></i> <span>{$total_likes}</span>
                            </div>
                            <div class=\"col-6\">
                                <i class=\"far fa-fw fa-2x fa-comment comment\" id=\"postid_{$row['pid']}\"></i>
                            </div>
                        </div>
                    </div>
                    <div class=\"feed-comments\" id=\"postid_{$row['pid']}\">
                    
                    </div>
                </div>
            </div>
        ";
    }



}

?>