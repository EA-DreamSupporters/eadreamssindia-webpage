<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'config/database.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not authenticated']);
    exit;
}

$testId = intval($_GET['id'] ?? 0);

if (!$testId) {
    echo json_encode(['success' => false, 'message' => 'Invalid test ID']);
    exit;
}

try {
    // Fetch test details with question count
    $stmt = $db->prepare("
        SELECT tp.*,
               i.name AS institute_name,
               (SELECT COUNT(*) FROM test_questions WHERE test_id = tp.id) as total_questions
        FROM test_packs tp
        LEFT JOIN institutions i ON i.id = tp.institute_id
        WHERE tp.id = ?
    ");
    $stmt->execute([$testId]);
    $test = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$test) {
        echo json_encode(['success' => false, 'message' => 'Test not found']);
        exit;
    }
    
    // Check if student has access
    $userRole = $_SESSION['role'] ?? 'student';
    if ($userRole === 'student') {
        if ($test['is_active'] != 1 || $test['is_visible_to_students'] != 1) {
            echo json_encode(['success' => false, 'message' => 'This test is not available']);
            exit;
        }
    }
    
    echo json_encode([
        'success' => true,
        'test' => $test
    ]);
    
} catch (Exception $e) {
    error_log("Get test details error: " . $e->getMessage());
    echo json_encode([
        'success' => false, 
        'message' => 'An error occurred',
        'error' => $e->getMessage(),
        'trace' => $e->getTraceAsString()
    ]);
}
