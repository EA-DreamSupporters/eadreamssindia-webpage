<?php
// AJAX handlers for the Questions page
// This file expects to be included from `questions.php` after bootstrap (so $db, session and helper functions are available).

// Ensure $action is available
$action = $action ?? ($_GET['action'] ?? $_POST['action'] ?? '');

// Ensure we have a current user helper
if (!function_exists('getCurrentUser')) {
    // fallback - try to require functions if running standalone (best-effort)
    $maybeFunctions = __DIR__ . '/../functions/functions.php';
    if (file_exists($maybeFunctions)) {
        require_once $maybeFunctions;
    }
}

$user = getCurrentUser();

// AJAX bootstrap: ensure session, error handlers, autoload and DB are available
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// logger
$loggerFile = dirname(__DIR__, 2) . '/logs/logger.php';
if (file_exists($loggerFile)) {
    require_once $loggerFile;
}

// Convert uncaught exceptions to JSON for AJAX callers
set_exception_handler(function ($e) {
    if (function_exists('q_log'))
        q_log("ajax.php: Uncaught exception: " . $e->getMessage());
    error_log("Uncaught exception in AJAX controller: " . $e->getMessage() . "\n" . $e->getTraceAsString());
    if (!headers_sent())
        header('Content-Type: application/json; charset=utf-8', true, 500);
    echo json_encode(['success' => false, 'error' => 'Server error: ' . $e->getMessage()]);
    exit;
});

set_error_handler(function ($severity, $message, $file, $line) {
    $msg = "PHP error [{$severity}] {$message} in {$file}:{$line}";
    if (function_exists('q_log'))
        q_log('ajax.php: ' . $msg);
    error_log($msg);
    if (!headers_sent())
        header('Content-Type: application/json; charset=utf-8', true, 500);
    echo json_encode(['success' => false, 'error' => 'Server error: ' . $message]);
    exit;
});

// Compute project root and include composer autoload if available
$projectRoot = dirname(__DIR__, 4);
$autoload = $projectRoot . '/vendor/autoload.php';
if (file_exists($autoload)) {
    require_once $autoload;
}

// Ensure $db is available — try to include project config if needed
if (!isset($db)) {
    $dbConfig = $projectRoot . '/config/database.php';
    if (file_exists($dbConfig)) {
        require_once $dbConfig;
        if (class_exists('Database')) {
            $db = Database::getInstance()->getConnection();
        }
    }
}

// Include parsing/import helpers
$parseImport = __DIR__ . '/../functions/parse_and_import.php';
if (file_exists($parseImport)) {
    require_once $parseImport;
}

// Bulk Duplicate Questions (AJAX)
if ($action === 'bulk_duplicate' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (function_exists('q_log'))
            q_log('ajax.php: bulk_duplicate requested');

        $input = json_decode(file_get_contents('php://input'), true);
        $question_ids = $input['question_ids'] ?? [];

        if (empty($question_ids)) {
            echo json_encode(['success' => false, 'error' => 'No questions selected']);
            exit;
        }

        $question_ids = array_map('intval', $question_ids);
        $question_ids = array_filter($question_ids, function ($id) {
            return $id > 0;
        });

        if (empty($question_ids)) {
            echo json_encode(['success' => false, 'error' => 'Invalid question IDs']);
            exit;
        }

        if (!isset($user) || !$user) {
            $user = getCurrentUser();
        }

        if (!$user || !isset($user['institute_id'])) {
            echo json_encode(['success' => false, 'error' => 'User authentication required']);
            exit;
        }

        $duplicated_count = 0;
        $errors = [];

        // Validate user's institute_id if present
        $user_institute_valid = null;
        if (!empty($user['institute_id'])) {
            try {
                $s = $db->prepare("SELECT id FROM institutions WHERE id = ?");
                $s->execute([$user['institute_id']]);
                if ($s->fetchColumn()) { /* valid */
                }
            } catch (Exception $e) {
                $user_institute_valid = null;
            }
        }

        foreach ($question_ids as $question_id) {
            try {
                $stmt = $db->prepare("SELECT * FROM question_banks WHERE id = ?");
                $stmt->execute([$question_id]);
                $original = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($original) {
                    // Duplicate the row (basic copy, adjust timestamps and institute if required)
                    $new = $original;
                    unset($new['id']);
                    $new['created_at'] = date('Y-m-d H:i:s');
                    $cols = array_keys($new);
                    $placeholders = implode(',', array_fill(0, count($cols), '?'));
                    $sql = "INSERT INTO question_banks (" . implode(',', $cols) . ") VALUES ($placeholders)";
                    $stmtIns = $db->prepare($sql);
                    $stmtIns->execute(array_values($new));
                    $duplicated_count++;
                } else {
                    $errors[] = "Question $question_id not found";
                }
            } catch (Exception $e) {
                $errors[] = "Error duplicating question $question_id: " . $e->getMessage();
                error_log("Error duplicating question $question_id: " . $e->getMessage());
            }
        }

        $message = "Duplicated {$duplicated_count} questions successfully";
        if (!empty($errors)) {
            $message .= ". Errors: " . implode(", ", $errors);
        }

        echo json_encode(['success' => true, 'message' => $message, 'duplicated_count' => $duplicated_count]);
        if (function_exists('q_log'))
            q_log('ajax.php: bulk_duplicate completed: ' . $message);
        exit;
    } catch (Exception $e) {
        if (function_exists('q_log'))
            q_log('ajax.php: bulk_duplicate error: ' . $e->getMessage());
        error_log("Bulk duplicate error (AJAX): " . $e->getMessage());
        echo json_encode(['success' => false, 'error' => 'Failed to duplicate questions: ' . $e->getMessage()]);
        exit;
    }
}

// Bulk Delete Questions (AJAX)
if ($action === 'bulk_delete' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (function_exists('q_log'))
            q_log('ajax.php: bulk_delete requested');

        $input = json_decode(file_get_contents('php://input'), true);
        $question_ids = $input['question_ids'] ?? [];

        if (empty($question_ids)) {
            echo json_encode(['success' => false, 'error' => 'No questions selected']);
            exit;
        }

        $question_ids = array_map('intval', $question_ids);
        $question_ids = array_filter($question_ids, function ($id) {
            return $id > 0;
        });

        if (empty($question_ids)) {
            echo json_encode(['success' => false, 'error' => 'Invalid question IDs']);
            exit;
        }

        $placeholders = str_repeat('?,', count($question_ids) - 1) . '?';
        $stmt = $db->prepare("DELETE FROM question_banks WHERE id IN ($placeholders)");
        $stmt->execute($question_ids);

        $deleted_count = $stmt->rowCount();

        error_log("Deleted {$deleted_count} questions successfully (AJAX)");
        if (function_exists('q_log'))
            q_log('ajax.php: bulk_delete completed: deleted_count=' . $deleted_count);
        echo json_encode(['success' => true, 'message' => "Deleted {$deleted_count} questions successfully"]);
        exit;
    } catch (Exception $e) {
        if (function_exists('q_log'))
            q_log('ajax.php: bulk_delete error: ' . $e->getMessage());
        error_log("Bulk delete error (AJAX): " . $e->getMessage());
        echo json_encode(['success' => false, 'error' => 'Failed to delete questions: ' . $e->getMessage()]);
        exit;
    }
}

// Handle File Upload Processing (AJAX)
if ($action === 'process_upload' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    // Clear any previous output
    if (function_exists('ob_clean')) {
        @ob_clean();
    }

    try {
        if (function_exists('q_log'))
            q_log('ajax.php: process_upload started');
        if (!isset($_FILES['upload_file']) || $_FILES['upload_file']['error'] !== UPLOAD_ERR_OK) {
            throw new Exception('No file uploaded or upload error occurred. Error code: ' . ($_FILES['upload_file']['error'] ?? 'unknown'));
        }

        $uploadedFile = $_FILES['upload_file'];
        $fileExtension = strtolower(pathinfo($uploadedFile['name'], PATHINFO_EXTENSION));

        if (!in_array($fileExtension, ['xlsx', 'csv'])) {
            throw new Exception('Only Excel (.xlsx) and CSV (.csv) files are supported');
        }

        if ($uploadedFile['size'] > 10 * 1024 * 1024) { // 10MB limit
            throw new Exception('File size exceeds 10MB limit');
        }

        // Parse the uploaded file
        if ($fileExtension === 'csv') {
            $questions = parseSimpleCSV($uploadedFile['tmp_name']);
        } elseif ($fileExtension === 'xlsx') {
            if (!class_exists('PhpOffice\PhpSpreadsheet\IOFactory')) {
                throw new Exception('PhpSpreadsheet library not found. Please install it or use CSV format instead.');
            }
            $questions = parseExcelFile($uploadedFile['tmp_name']);
        } else {
            $questions = [];
        }

        if (empty($questions)) {
            throw new Exception('No valid questions found in the uploaded file');
        }

        // Store parsed questions in session for preview
        $_SESSION['bulk_upload_questions'] = $questions;
        if (function_exists('q_log'))
            q_log('ajax.php: process_upload parsed ' . count($questions) . ' questions');

        if (!headers_sent())
            header('Content-Type: application/json; charset=utf-8');

        echo json_encode([
            'success' => true,
            'message' => 'File parsed successfully',
            'question_count' => count($questions),
            'redirect' => 'index.php?page=questions&action=upload&step=preview'
        ]);
        exit;

    } catch (Exception $e) {
        if (function_exists('q_log'))
            q_log('ajax.php: process_upload error: ' . $e->getMessage());
        if (!headers_sent())
            header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        exit;
    }
}

// Handle Text Import Processing (AJAX)
if ($action === 'process_text' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $textContent = $_POST['text_content'] ?? '';
        if (empty(trim($textContent))) {
            throw new Exception('No text content provided');
        }

        // Temporarily not supported
        throw new Exception('Text import functionality is temporarily disabled. Please use CSV or Excel file upload instead.');
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        exit;
    }
}

// Handle Bulk Import (AJAX)
if ($action === 'import_questions' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (function_exists('q_log'))
            q_log('ajax.php: import_questions called. Session present? ' . (isset($_SESSION) ? 'yes' : 'no'));
        $sessCount = isset($_SESSION['bulk_upload_questions']) && is_array($_SESSION['bulk_upload_questions']) ? count($_SESSION['bulk_upload_questions']) : 0;
        if (function_exists('q_log'))
            q_log('ajax.php: import_questions session count=' . $sessCount);
        if ($sessCount === 0) {
            throw new Exception('No questions to import. Please upload a file first.');
        }

        $questions = $_SESSION['bulk_upload_questions'];
        $importResult = importQuestionsToDatabase($questions, $db);

        // Clear session data
        unset($_SESSION['bulk_upload_questions']);

        if (!headers_sent())
            header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'success' => true,
            'message' => "Successfully imported {$importResult['success_count']} questions",
            'success_count' => $importResult['success_count'],
            'error_count' => $importResult['error_count'],
            'errors' => $importResult['errors'],
            'redirect' => 'index.php?page=questions&success=bulk_imported&message=' . urlencode("Imported {$importResult['success_count']} questions successfully")
        ]);
        exit;

    } catch (Exception $e) {
        if (!headers_sent())
            header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        exit;
    }
}

// End of ajax handlers
