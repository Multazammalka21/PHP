<?php
require_once '../../config/database.php';
require_once '../../classes/Auth.php';
require_once '../../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(false, 'Method not allowed');
}

$db = Database::getInstance()->getConnection();
$auth = new AuthQueries($db);

try {
    $data = [
        'email' => sanitizeInput($_POST['email']),
        'username' => sanitizeInput($_POST['username']),
        'password' => $_POST['password'],
        'full_name' => sanitizeInput($_POST['full_name']),
        'phone_number' => sanitizeInput($_POST['phone_number'] ?? null),
        'school_name' => sanitizeInput($_POST['school_name'] ?? null),
        'graduation_year' => $_POST['graduation_year'] ?? null,
        'role' => 'student'
    ];
    
    // Validasi
    if (strlen($data['password']) < 6) {
        throw new Exception('Password minimal 6 karakter');
    }
    
    $user_id = $auth->register($data);
    
    jsonResponse(true, 'Registrasi berhasil!', ['user_id' => $user_id]);
    
} catch (Exception $e) {
    jsonResponse(false, $e->getMessage());
}
?>