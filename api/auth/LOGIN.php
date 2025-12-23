<?php
require_once '../../config/database.php';
require_once '../../classes/Auth.php';
require_once '../../includes/session.php';
require_once '../../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(false, 'Method not allowed');
}

$db = Database::getInstance()->getConnection();
$auth = new AuthQueries($db);

try {
    $identifier = sanitizeInput($_POST['identifier']);
    $password = $_POST['password'];
    
    $user = $auth->login($identifier, $password);
    
    // Set session
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['full_name'] = $user['full_name'];
    $_SESSION['role'] = $user['role'];
    
    jsonResponse(true, 'Login berhasil!', $user);
    
} catch (Exception $e) {
    jsonResponse(false, $e->getMessage());
}
?>