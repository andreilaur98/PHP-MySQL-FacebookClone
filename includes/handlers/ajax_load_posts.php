<?php

include 'database/config.php';
include 'classes/User.php';
include 'classes/Post.php';

$limit = 10;

$posts = new Post($con, $_REQUEST['userLoggedIn']);
$posts->IndexPosts($_REQUEST, $limit);

?>