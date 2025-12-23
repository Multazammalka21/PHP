<?php
require_once '../../config/database.php';
require_once '../../classes/Queries.php';
require_once '../../includes/session.php';
require_once '../../includes/functions.php';

requireLogin();
$user = getLoggedUser();
$db = Database::getInstance()->getConnection();
$queries = new IRISEducationQueries($db);

// GET - List posts
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $page = $_GET['page'] ?? 1;
    $category = $_GET['category'] ?? null;
    $search = $_GET['search'] ?? null;
    
    $posts = $queries->getPosts($page, 20, $category, null, $search);
    jsonResponse(true, 'Success', $posts);
}

// POST - Create post
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = sanitizeInput($_POST['title']);
    $content = sanitizeInput($_POST['content']);
    $post_type = $_POST['post_type'];
    $category = $_POST['category'];
    $tags = isset($_POST['tags']) ? explode(',', $_POST['tags']) : null;
    
    $post_id = $queries->createPost(
        $user['user_id'], 
        $title, 
        $content, 
        $post_type, 
        $category, 
        $tags
    );
    
    jsonResponse(true, 'Post berhasil dibuat', ['post_id' => $post_id]);
}
?>