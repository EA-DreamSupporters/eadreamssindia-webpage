<?php
session_start();
require_once '../config/database.php';
require_once '../includes/auth.php';

// Set JSON header immediately
header('Content-Type: application/json');

try {
    // Check if user is authenticated
    $user = getCurrentUser();
    if (!$user) {
        echo json_encode(['success' => false, 'error' => 'User not authenticated']);
        exit;
    }

    // Check if it's a POST request
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['success' => false, 'error' => 'Only POST requests allowed']);
        exit;
    }

    // Get the input
    $input = json_decode(file_get_contents('php://input'), true);
    if (!$input) {
        echo json_encode(['success' => false, 'error' => 'No JSON input received']);
        exit;
    }

    $question_ids = $input['question_ids'] ?? [];
    
    if (empty($question_ids)) {
        echo json_encode(['success' => false, 'error' => 'No questions selected']);
        exit;
    }

    $question_ids = array_map('intval', $question_ids);
    $question_ids = array_filter($question_ids, function($id) { return $id > 0; });
    
    if (empty($question_ids)) {
        echo json_encode(['success' => false, 'error' => 'Invalid question IDs']);
        exit;
    }

    // Delete questions (also removes from test_questions due to foreign key constraints)
    $placeholders = str_repeat('?,', count($question_ids) - 1) . '?';
    $stmt = $db->prepare("DELETE FROM question_banks WHERE id IN ($placeholders)");
    $stmt->execute($question_ids);
    
    $deleted_count = $stmt->rowCount();
    
    echo json_encode([
        'success' => true, 
        'message' => "Deleted {$deleted_count} questions successfully",
        'deleted_count' => $deleted_count
    ]);
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => 'Server error: ' . $e->getMessage()]);
}
?>