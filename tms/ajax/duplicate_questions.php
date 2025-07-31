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

    $duplicated_count = 0;
    $errors = [];
    
    foreach ($question_ids as $question_id) {
        try {
            // Fetch original question
            $stmt = $db->prepare("SELECT * FROM question_banks WHERE id = ?");
            $stmt->execute([$question_id]);
            $original = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($original) {
                // For duplicated questions, we'll set institute_id to NULL to avoid foreign key issues
                // In a production system, you'd want to handle institute associations properly
                
                // Create duplicate
                $stmt = $db->prepare("INSERT INTO question_banks (title, subject, topic, subtopic, question_text, options, correct_answer, explanation, difficulty, exam_year, source, is_public, institute_id, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $result = $stmt->execute([
                    $original['title'] . ' (Copy)',
                    $original['subject'],
                    $original['topic'],
                    $original['subtopic'],
                    $original['question_text'],
                    $original['options'],
                    $original['correct_answer'],
                    $original['explanation'],
                    $original['difficulty'],
                    $original['exam_year'],
                    $original['source'],
                    $original['is_public'],
                    null, // Set to NULL to avoid foreign key constraint issues
                    date('Y-m-d H:i:s')
                ]);
                
                if ($result) {
                    $duplicated_count++;
                } else {
                    $errors[] = "Failed to duplicate question ID: $question_id";
                }
            } else {
                $errors[] = "Question not found: $question_id";
            }
        } catch (Exception $e) {
            $errors[] = "Error duplicating question $question_id: " . $e->getMessage();
        }
    }
    
    $message = "Duplicated {$duplicated_count} questions successfully";
    if (!empty($errors)) {
        $message .= ". Errors: " . implode(", ", $errors);
    }
    
    echo json_encode([
        'success' => true, 
        'message' => $message, 
        'duplicated_count' => $duplicated_count,
        'errors' => $errors
    ]);
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => 'Server error: ' . $e->getMessage()]);
}
?>