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

try {
    $session_id = $_POST['session_id'];
    $question_id = $_POST['question_id'];
    $selected_option_id = $_POST['selected_option_id'];
    $time_spent = $_POST['time_spent'];
    
    $is_correct = $queries->submitAnswer(
        $session_id, 
        $question_id, 
        $selected_option_id, 
        $time_spent
    );
    
    jsonResponse(true, 'Jawaban tersimpan', ['is_correct' => $is_correct]);
    
} catch (Exception $e) {
    jsonResponse(false, $e->getMessage());
}
?>