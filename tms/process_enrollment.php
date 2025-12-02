<?php
// Process Test Enrollment and Payment
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'config/database.php';

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized. Please login first.']);
    exit;
}

if ($_SESSION['user_role'] !== 'student') {
    echo json_encode(['success' => false, 'message' => 'Only students can enroll in tests.']);
    exit;
}

$userId = $_SESSION['user_id'];

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$action = $_POST['action'] ?? '';
$testId = intval($_POST['test_id'] ?? 0);

if (!$testId) {
    echo json_encode(['success' => false, 'message' => 'Invalid test ID']);
    exit;
}

try {
    // Fetch test details
    $stmt = $db->prepare("SELECT * FROM test_packs WHERE id = ? AND is_active = 1 AND is_visible_to_students = 1");
    $stmt->execute([$testId]);
    $test = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$test) {
        echo json_encode(['success' => false, 'message' => 'Test not found or not available']);
        exit;
    }
    
    // Check if already enrolled
    $checkStmt = $db->prepare("SELECT id FROM student_enrollments WHERE student_id = ? AND test_pack_id = ?");
    $checkStmt->execute([$userId, $testId]);
    if ($checkStmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'Already enrolled in this test']);
        exit;
    }
    
    if ($action === 'enroll_free') {
        // Free enrollment
        if ($test['price'] > 0) {
            echo json_encode(['success' => false, 'message' => 'This test is not free']);
            exit;
        }
        
        $enrollStmt = $db->prepare("
            INSERT INTO student_enrollments 
            (student_id, test_pack_id, enrolled_at, payment_status, amount_paid) 
            VALUES (?, ?, NOW(), 'completed', 0)
        ");
        $enrollStmt->execute([$userId, $testId]);
        
        echo json_encode([
            'success' => true, 
            'message' => 'Successfully enrolled in ' . $test['title'],
            'redirect' => 'index.php?page=my_tests'
        ]);
        
    } elseif ($action === 'buy_test') {
        // Paid enrollment - for now simulate payment
        $paymentId = 'PAY_' . strtoupper(uniqid());
        
        $enrollStmt = $db->prepare("
            INSERT INTO student_enrollments 
            (student_id, test_pack_id, enrolled_at, payment_status, payment_id, amount_paid) 
            VALUES (?, ?, NOW(), 'completed', ?, ?)
        ");
        $enrollStmt->execute([$userId, $testId, $paymentId, $test['price']]);
        
        echo json_encode([
            'success' => true, 
            'message' => 'Payment successful! You are now enrolled in ' . $test['title'],
            'payment_id' => $paymentId,
            'redirect' => 'index.php?page=my_tests'
        ]);
        
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
    }
    
} catch (Exception $e) {
    error_log("Enrollment error: " . $e->getMessage());
    echo json_encode([
        'success' => false, 
        'message' => 'An error occurred: ' . $e->getMessage(),
        'trace' => $e->getTraceAsString()
    ]);
}
