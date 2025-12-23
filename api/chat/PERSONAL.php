<?php
require_once '../../config/database.php';
require_once '../../classes/Queries.php';
require_once '../../includes/session.php';
require_once '../../includes/functions.php';

requireLogin();
$user = getLoggedUser();
$db = Database::getInstance()->getConnection();
$queries = new IRISEducationQueries($db);

// GET - Ambil percakapan
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $conversation_id = $_GET['conversation_id'];
    $messages = $queries->getPersonalConversationMessages($conversation_id, $user['user_id']);
    jsonResponse(true, 'Success', $messages);
}

// POST - Kirim pesan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    
    if ($action === 'start') {
        // Start percakapan baru
        $mentor_id = $_POST['mentor_id'];
        $topic = sanitizeInput($_POST['topic']);
        
        $conversation_id = $queries->startPersonalConversation(
            $user['user_id'], 
            $mentor_id, 
            $topic
        );
        
        jsonResponse(true, 'Percakapan dimulai', ['conversation_id' => $conversation_id]);
    }
    
    if ($action === 'send') {
        // Kirim pesan
        $conversation_id = $_POST['conversation_id'];
        $message = sanitizeInput($_POST['message']);
        
        $message_id = $queries->sendPersonalMessage(
            $conversation_id, 
            $user['user_id'], 
            $message
        );
        
        jsonResponse(true, 'Pesan terkirim', ['message_id' => $message_id]);
    }
}
?>