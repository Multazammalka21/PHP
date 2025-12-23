<?php
require_once '../../config/database.php';
require_once '../../classes/Queries.php';
require_once '../../includes/session.php';
require_once '../../includes/functions.php';

requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(false, 'Method not allowed');
}

$db = Database::getInstance()->getConnection();
$queries = new IRISEducationQueries($db);
$user = getLoggedUser();

try {
    $tryout_id = $_POST['tryout_id'];
    
    // Start session
    $session_id = $queries->startTryoutSession($tryout_id, $user['user_id']);
    
    // Get tryout dengan soal
    $tryout_data = $queries->getTryoutWithQuestions($tryout_id, true);
    
    jsonResponse(true, 'Tryout dimulai!', [
        'session_id' => $session_id,
        'tryout' => $tryout_data
    ]);
    
} catch (Exception $e) {
    jsonResponse(false, $e->getMessage());
}
?>