<?php
session_start();
require_once '../config/database.php';
require_once '../includes/auth.php';

header('Content-Type: application/json');

try {
    $testId = isset($_GET['test_id']) ? (int) $_GET['test_id'] : 0;

    if ($testId <= 0) {
        echo json_encode(['error' => 'Invalid test ID']);
        exit;
    }

    $stmt = $db->prepare("
        SELECT qb.id, qb.question_text, qb.options, qb.correct_answer, qb.explanation, qb.subject, qb.topic, qb.difficulty
        FROM test_questions tq
        JOIN question_banks qb ON tq.question_id = qb.id
        WHERE tq.test_id = ?
        ORDER BY qb.id
    ");
    $stmt->execute([$testId]);

    $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($questions as &$q) {
        $q['options'] = json_decode($q['options'], true);
        if (!$q['options']) {
            $q['options'] = ['A' => '', 'B' => '', 'C' => '', 'D' => ''];
        }
    }

    echo json_encode(['success' => true, 'questions' => $questions, 'count' => count($questions)]);
    exit;
} catch (Exception $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    exit;
}
?>