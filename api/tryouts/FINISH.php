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
    
    // Finish dan hitung score
    $result = $queries->finishTryoutSession($session_id);
    
    // Get leaderboard
    $sql = "SELECT tryout_id FROM tryout_sessions WHERE session_id = :session_id";
    $stmt = $db->prepare($sql);
    $stmt->execute(['session_id' => $session_id]);
    $tryout_id = $stmt->fetchColumn();
    
    $leaderboard = $queries->getTryoutLeaderboard($tryout_id, 10);
    
    jsonResponse(true, 'Tryout selesai!', [
        'result' => $result,
        'leaderboard' => $leaderboard
    ]);
    
} catch (Exception $e) {
    jsonResponse(false, $e->getMessage());
}
?>