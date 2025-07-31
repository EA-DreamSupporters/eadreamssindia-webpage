<?php

$user = getCurrentUser();
$action = $_GET['action'] ?? 'list';

// Handle AJAX actions first (before any HTML output)
// Bulk Duplicate Questions
if ($action === 'bulk_duplicate' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    
    try {
        // Debug: Log the request
        error_log("Bulk duplicate request received");
        
        $input = json_decode(file_get_contents('php://input'), true);
        $question_ids = $input['question_ids'] ?? [];
        
        // Debug: Log the input
        error_log("Question IDs received: " . print_r($question_ids, true));
        
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
        
        // Ensure we have user information
        if (!isset($user) || !$user) {
            $user = getCurrentUser();
        }
        
        if (!$user || !isset($user['institute_id'])) {
            echo json_encode(['success' => false, 'error' => 'User authentication required']);
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
                        $user['institute_id'],
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
                error_log("Error duplicating question $question_id: " . $e->getMessage());
            }
        }
        
        $message = "Duplicated {$duplicated_count} questions successfully";
        if (!empty($errors)) {
            $message .= ". Errors: " . implode(", ", $errors);
        }
        
        echo json_encode(['success' => true, 'message' => $message, 'duplicated_count' => $duplicated_count]);
        exit;
    } catch (Exception $e) {
        error_log("Bulk duplicate error: " . $e->getMessage());
        echo json_encode(['success' => false, 'error' => 'Failed to duplicate questions: ' . $e->getMessage()]);
        exit;
    }
}

// Bulk Delete Questions
if ($action === 'bulk_delete' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    
    try {
        // Debug: Log the request
        error_log("Bulk delete request received");
        
        $input = json_decode(file_get_contents('php://input'), true);
        $question_ids = $input['question_ids'] ?? [];
        
        // Debug: Log the input
        error_log("Question IDs to delete: " . print_r($question_ids, true));
        
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
        
        error_log("Deleted {$deleted_count} questions successfully");
        echo json_encode(['success' => true, 'message' => "Deleted {$deleted_count} questions successfully"]);
        exit;
    } catch (Exception $e) {
        error_log("Bulk delete error: " . $e->getMessage());
        echo json_encode(['success' => false, 'error' => 'Failed to delete questions: ' . $e->getMessage()]);
        exit;
    }
}

// Handle Template Download
if ($action === 'download_template') {
    $format = $_GET['format'] ?? 'excel';
    
    if ($format === 'csv') {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="competitive_exam_questions_template.csv"');
        header('Cache-Control: max-age=0');
        header('Pragma: public');
        
        // Create CSV file content
        $csvContent = createCSVTemplate();
        echo $csvContent;
        exit;
    } else {
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="competitive_exam_questions_template.xlsx"');
        header('Cache-Control: max-age=0');
        header('Pragma: public');
        
        // Create Excel file content
        $excelContent = createExcelTemplate();
        echo $excelContent;
        exit;
    }
}

// Handle File Upload Processing
if ($action === 'process_upload' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    
    try {
        if (!isset($_FILES['upload_file']) || $_FILES['upload_file']['error'] !== UPLOAD_ERR_OK) {
            throw new Exception('No file uploaded or upload error occurred');
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
        $questions = parseUploadedFile($uploadedFile, $fileExtension);
        
        if (empty($questions)) {
            throw new Exception('No valid questions found in the uploaded file');
        }
        
        // Store parsed questions in session for preview
        $_SESSION['bulk_upload_questions'] = $questions;
        
        echo json_encode([
            'success' => true, 
            'message' => 'File parsed successfully', 
            'question_count' => count($questions),
            'redirect' => 'index.php?page=questions&action=upload&step=preview'
        ]);
        exit;
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        exit;
    }
}

// Handle Text Import Processing
if ($action === 'process_text' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    
    try {
        $textContent = $_POST['text_content'] ?? '';
        if (empty(trim($textContent))) {
            throw new Exception('No text content provided');
        }
        
        // Parse the text content
        $questions = parseTextContent($textContent);
        
        if (empty($questions)) {
            throw new Exception('No valid questions found in the text content');
        }
        
        // Store parsed questions in session for preview
        $_SESSION['bulk_upload_questions'] = $questions;
        
        echo json_encode([
            'success' => true, 
            'message' => 'Text parsed successfully', 
            'question_count' => count($questions),
            'redirect' => 'index.php?page=questions&action=upload&step=preview'
        ]);
        exit;
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        exit;
    }
}

// Handle Bulk Import
if ($action === 'import_questions' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    
    try {
        if (!isset($_SESSION['bulk_upload_questions']) || empty($_SESSION['bulk_upload_questions'])) {
            throw new Exception('No questions to import. Please upload a file first.');
        }
        
        $questions = $_SESSION['bulk_upload_questions'];
        $importResult = importQuestionsToDatabase($questions, $db);
        
        // Clear session data
        unset($_SESSION['bulk_upload_questions']);
        
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
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        exit;
    }
}

// Regular page logic continues below...

if (isset($_GET['success'])) {
    if ($_GET['success'] === 'created') $success = "Question added successfully!";
    elseif ($_GET['success'] === 'created_continue') $success = "Question added successfully! You can add another question below.";
    elseif ($_GET['success'] === 'updated') $success = "Question updated successfully!";
    elseif ($_GET['success'] === 'deleted') $success = "Question deleted successfully!";
    elseif ($_GET['success'] === 'added_to_test') $success = "Question successfully added to selected test pack!";
    elseif ($_GET['success'] === 'bulk_added') $success = $_GET['message'] ?? "Questions added to test successfully!";
    elseif ($_GET['success'] === 'bulk_imported') $success = $_GET['message'] ?? "Questions imported successfully!";
}

if (isset($_GET['error'])) {
    if ($_GET['error'] === 'exists') $error = "This question is already added to that test pack.";
    elseif ($_GET['error'] === 'bulk_add_failed') $error = "Failed to add questions to test pack.";
    elseif ($_GET['error'] === 'invalid_data') $error = "Invalid data provided.";
}

// Delete Action
if ($action === 'delete' && isset($_GET['id'])) {
    $q_id = intval($_GET['id']);
    $stmt = $db->prepare("DELETE FROM question_banks WHERE id = ?");
    $stmt->execute([$q_id]);
    header("Location: index.php?page=questions&success=deleted");
    exit;
}

// Add Question to Test Pack
if ($action === 'add_question_to_test' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $test_id = intval($_POST['test_id'] ?? 0);
    $question_id = intval($_POST['question_id'] ?? 0);

    if ($test_id <= 0 || $question_id <= 0) {
        header("Location: index.php?page=questions&error=invalid_data");
        exit;
    }

    try {
        $stmt = $db->prepare("SELECT COUNT(*) FROM test_questions WHERE test_id = ? AND question_id = ?");
        $stmt->execute([$test_id, $question_id]);
        $exists = $stmt->fetchColumn();

        if ($exists) {
            header("Location: index.php?page=questions&error=exists");
            exit;
        }

        $stmt = $db->prepare("INSERT INTO test_questions (test_id, question_id) VALUES (?, ?)");
        $stmt->execute([$test_id, $question_id]);

        header("Location: index.php?page=questions&success=added_to_test");
        exit;
    } catch (Exception $e) {
        error_log("Add question to test error: " . $e->getMessage());
        $error = "Failed to add to test: " . $e->getMessage();
    }
}

// Bulk Add Questions to Test Pack
if ($action === 'bulk_add_to_test' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $test_id = intval($_POST['test_id'] ?? 0);
    $question_ids = $_POST['question_ids'] ?? '';
    
    if ($test_id <= 0 || empty($question_ids)) {
        header("Location: index.php?page=questions&error=invalid_data");
        exit;
    }
    
    try {
        $question_ids_array = explode(',', $question_ids);
        $question_ids_array = array_map('intval', $question_ids_array);
        $question_ids_array = array_filter($question_ids_array, function($id) { return $id > 0; });
        
        if (empty($question_ids_array)) {
            header("Location: index.php?page=questions&error=invalid_data");
            exit;
        }
        
        $added_count = 0;
        $skipped_count = 0;
        
        foreach ($question_ids_array as $question_id) {
            // Check if already exists
            $stmt = $db->prepare("SELECT COUNT(*) FROM test_questions WHERE test_id = ? AND question_id = ?");
            $stmt->execute([$test_id, $question_id]);
            $exists = $stmt->fetchColumn();
            
            if (!$exists) {
                $stmt = $db->prepare("INSERT INTO test_questions (test_id, question_id) VALUES (?, ?)");
                $stmt->execute([$test_id, $question_id]);
                $added_count++;
            } else {
                $skipped_count++;
            }
        }
        
        $message = "Added {$added_count} questions to test.";
        if ($skipped_count > 0) {
            $message .= " ({$skipped_count} were already in the test)";
        }
        
        header("Location: index.php?page=questions&success=bulk_added&message=" . urlencode($message));
        exit;
    } catch (Exception $e) {
        error_log("Bulk add to test error: " . $e->getMessage());
        header("Location: index.php?page=questions&error=bulk_add_failed");
        exit;
    }
}

// Fetch question for editing
if ($action === 'edit' && isset($_GET['id'])) {
    $question_id = intval($_GET['id']);
    $stmt = $db->prepare("SELECT * FROM question_banks WHERE id = ?");
    $stmt->execute([$question_id]);
    $editQuestion = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$editQuestion) {
        echo '<div class="alert alert-danger">Question not found.</div>';
        exit;
    }
    $options = json_decode($editQuestion['options'], true);
}

// Create question
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'create') {
    try {
        $subject = trim($_POST['subject'] ?? '');
        $new_subject = trim($_POST['new_subject'] ?? '');
        if (!empty($new_subject)) $subject = $new_subject;
        if (empty($subject)) throw new Exception("Please select or enter a subject.");

        $topic = $_POST['topic'] ?? '';
        $subtopic = $_POST['subtopic'] ?? '';
        $question_text = $_POST['question_text'] ?? '';
        $options = json_encode([
            'A' => $_POST['option_a'] ?? '',
            'B' => $_POST['option_b'] ?? '',
            'C' => $_POST['option_c'] ?? '',
            'D' => $_POST['option_d'] ?? ''
        ]);
        $correct_answer = $_POST['correct_answer'] ?? '';
        $difficulty = $_POST['difficulty'] ?? 'medium';
        $exam_year = intval($_POST['exam_year'] ?? 2024);
        $source = $_POST['source'] ?? '';
        $explanation = $_POST['explanation'] ?? '';
        $is_public = isset($_POST['is_public']) ? 1 : 0;
        $created_at = date('Y-m-d H:i:s');

        $stmt = $db->prepare("INSERT INTO question_banks (title, subject, topic, subtopic, question_text, options, correct_answer, explanation, difficulty, exam_year, source, is_public, institute_id, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            '', $subject, $topic, $subtopic, $question_text, $options, $correct_answer, $explanation, $difficulty, $exam_year, $source, $is_public, null, $created_at
        ]);

        // Check if "Save & Add Another" was clicked
        $save_and_add_another = isset($_POST['save_and_add_another']) && $_POST['save_and_add_another'] === '1';
        
        if ($save_and_add_another) {
            header("Location: index.php?page=questions&action=create&success=created_continue");
        } else {
            header("Location: index.php?page=questions&success=created");
        }
        exit;
    } catch (Exception $e) {
        $error = "Error: " . $e->getMessage();
    }
}

// Update question
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'edit' && isset($_GET['id'])) {
    try {
        $question_id = intval($_GET['id']);
        $subject = trim($_POST['subject'] ?? '');
        $new_subject = trim($_POST['new_subject'] ?? '');
        if (!empty($new_subject)) $subject = $new_subject;
        if (empty($subject)) throw new Exception("Please select or enter a subject.");

        $topic = $_POST['topic'] ?? '';
        $subtopic = $_POST['subtopic'] ?? '';
        $question_text = $_POST['question_text'] ?? '';
        $options = json_encode([
            'A' => $_POST['option_a'] ?? '',
            'B' => $_POST['option_b'] ?? '',
            'C' => $_POST['option_c'] ?? '',
            'D' => $_POST['option_d'] ?? ''
        ]);
        $correct_answer = $_POST['correct_answer'] ?? '';
        $difficulty = $_POST['difficulty'] ?? 'medium';
        $exam_year = intval($_POST['exam_year'] ?? 2024);
        $source = $_POST['source'] ?? '';
        $explanation = $_POST['explanation'] ?? '';
        $is_public = isset($_POST['is_public']) ? 1 : 0;

        $stmt = $db->prepare("UPDATE question_banks SET subject = ?, topic = ?, subtopic = ?, question_text = ?, options = ?, correct_answer = ?, explanation = ?, difficulty = ?, exam_year = ?, source = ?, is_public = ? WHERE id = ?");
        $stmt->execute([
            $subject,
            $topic,
            $subtopic,
            $question_text,
            $options,
            $correct_answer,
            $explanation,
            $difficulty,
            $exam_year,
            $source,
            $is_public,
            $question_id
        ]);

        header("Location: index.php?page=questions&success=updated");
        exit;

    } catch (Exception $e) {
        $error = "Error updating: " . $e->getMessage();
    }
}

// Fetch subjects and topics
$allSubjects = $db->query("SELECT DISTINCT subject FROM question_banks WHERE subject IS NOT NULL AND subject != ''")->fetchAll(PDO::FETCH_COLUMN);
$allTopics = $db->query("SELECT DISTINCT topic FROM question_banks WHERE topic IS NOT NULL AND topic != ''")->fetchAll(PDO::FETCH_COLUMN);
$allSources = $db->query("SELECT DISTINCT source FROM question_banks WHERE source IS NOT NULL AND source != ''")->fetchAll(PDO::FETCH_COLUMN);

// Fetch questions
if ($user['role'] === 'super_admin') {
    $stmt = $db->query("SELECT * FROM question_banks ORDER BY created_at DESC LIMIT 50");
    $questions = $stmt->fetchAll();
} elseif ($user['role'] === 'vendor') {
    $stmt = $db->prepare("SELECT * FROM question_banks WHERE institute_id = ? OR is_public = 1 ORDER BY created_at DESC LIMIT 50");
    $stmt->execute([$user['institute_id']]);
    $questions = $stmt->fetchAll();
} else {
    $questions = [];
}

// Helper Functions for Bulk Upload

// Include simple bulk upload functions
require_once dirname(__DIR__) . '/bulk_upload_simple.php';

// Use the simple functions
function createExcelTemplate() {
    return createSimpleExcelTemplate();
}

function createCSVTemplate() {
    return createSimpleCSVTemplate();
}

function parseUploadedFile($uploadedFile, $fileExtension) {
    $questions = [];
    if ($fileExtension === 'csv') {
        $questions = parseSimpleCSV($uploadedFile['tmp_name']);
    } elseif ($fileExtension === 'xlsx') {
        // For now, Excel support is limited - suggest using CSV format
        throw new Exception('Excel (.xlsx) support is currently limited. Please convert your file to CSV format and try again.');
    }
    return $questions;
}

function parseTextContent($textContent) {
    $questions = [];
    $lines = explode("\n", $textContent);
    $currentQuestion = null;
    $collectingQuestion = false;
    $questionNumber = 0;

    foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line)) continue;

        // Check if this is a new question (starts with number)
        if (preg_match('/^(\d+[\.\)]\s*|Q\d*[\.\)]\s*)/i', $line)) {
            // Save previous question if exists
            if ($currentQuestion && !empty($currentQuestion['question_text'])) {
                $questions[] = $currentQuestion;
            }

            $questionNumber++;
            $currentQuestion = [
                'subject' => 'General',
                'topic' => 'General',
                'subtopic' => '',
                'question_text' => preg_replace('/^(\d+[\.\)]\s*|Q\d*[\.\)]\s*)/i', '', $line),
                'option_a' => '',
                'option_b' => '',
                'option_c' => '',
                'option_d' => '',
                'correct_answer' => '',
                'explanation' => '',
                'difficulty' => 'medium',
                'exam_year' => intval(date('Y')),
                'source' => 'Text Import',
                'is_public' => 1,
                'row_number' => $questionNumber
            ];
            $collectingQuestion = true;
        }
        // Check if this is an option line
        elseif (preg_match('/^([A-D][\.\)]\s*)/i', $line) && $currentQuestion) {
            $optionLetter = strtoupper(substr($line, 0, 1));
            $optionText = trim(preg_replace('/^[A-D][\.\)]\s*/i', '', $line));

            switch ($optionLetter) {
                case 'A': $currentQuestion['option_a'] = $optionText; break;
                case 'B': $currentQuestion['option_b'] = $optionText; break;
                case 'C': $currentQuestion['option_c'] = $optionText; break;
                case 'D': $currentQuestion['option_d'] = $optionText; break;
            }
            $collectingQuestion = false;
        }
        // Check if this is an answer line
        elseif (preg_match('/^(Answer|Ans|Correct)[\s\:]*([A-D])/i', $line) && $currentQuestion) {
            preg_match('/([A-D])/i', $line, $matches);
            if (isset($matches[1])) {
                $currentQuestion['correct_answer'] = strtoupper($matches[1]);
            }
            $collectingQuestion = false;
        }
        // Check if this is an explanation line
        elseif (preg_match('/^(Explanation|Exp)[\s\:]/i', $line) && $currentQuestion) {
            $currentQuestion['explanation'] = trim(preg_replace('/^(Explanation|Exp)[\s\:]*/i', '', $line));
            $collectingQuestion = false;
        }
        // If we're still collecting question text and this is not an option/answer line
        elseif ($collectingQuestion && $currentQuestion && !preg_match('/^[A-D][\.\)]/i', $line) &&
                !preg_match('/^(Answer|Explanation)/i', $line)) {
            // Continue building the question text for multi-line questions
            $currentQuestion['question_text'] .= "\n" . $line;
        }
    }

    // Add the last question
    if ($currentQuestion && !empty($currentQuestion['question_text'])) {
        $questions[] = $currentQuestion;
    }

    return $questions;
}

// Removed problematic parsing functions - using simple ones from bulk_upload_simple.php

// End of parsing functions

?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="text-gradient mb-0">Question Bank</h1>
                <p class="text-muted">Manage your comprehensive question database</p>
            </div>
            <div>
                <button class="btn btn-success me-2"
                    onclick="window.location.href='index.php?page=questions&action=upload'">
                    <i class="fas fa-upload me-2"></i>Bulk Upload
                </button>
                <button class="btn btn-primary" onclick="window.location.href='index.php?page=questions&action=create'">
                    <i class="fas fa-plus me-2"></i>Add Question
                </button>
            </div>
        </div>
    </div>
</div>

<?php if (isset($success)): ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fas fa-check-circle me-2"></i><?= htmlspecialchars($success) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<?php if (isset($error)): ?>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="fas fa-exclamation-circle me-2"></i><?= htmlspecialchars($error) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<?php if ($action === 'list'): ?>
<!-- Question Bank Dashboard -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="stats-card primary">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stats-number"><?= count($questions) ?></div>
                    <div class="stats-label">Total Questions</div>
                </div>
                <i class="fas fa-question-circle fa-2x opacity-75"></i>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stats-card success">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stats-number">
                        <?= count(array_filter($questions, fn($q) => $q['is_public'])) ?></div>
                    <div class="stats-label">Public Questions</div>
                </div>
                <i class="fas fa-globe fa-2x opacity-75"></i>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stats-card warning">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stats-number">
                        <?= count(array_unique(array_column($questions, 'subject'))) ?></div>
                    <div class="stats-label">Subjects</div>
                </div>
                <i class="fas fa-book fa-2x opacity-75"></i>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stats-card danger">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stats-number">
                        <?= count(array_unique(array_column($questions, 'topic'))) ?></div>
                    <div class="stats-label">Topics</div>
                </div>
                <i class="fas fa-tags fa-2x opacity-75"></i>
            </div>
        </div>
    </div>
</div>

<!-- Filters and Search -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="index.php">
            <input type="hidden" name="page" value="questions">
            <div class="row align-items-end">

                <div class="col-md-2">
                    <label class="form-label">Exam (Source)</label>
                    <select class="form-select" name="source">
                        <option value="">All Exams</option>
                        <?php foreach ($allSources as $source): ?>
                        <option value="<?= htmlspecialchars($source) ?>"
                            <?= ($_GET['source'] ?? '') == $source ? 'selected' : '' ?>>
                            <?= htmlspecialchars($source) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Subject</label>
                    <select class="form-select" name="subject">
                        <option value="">All Subjects</option>
                        <?php foreach ($allSubjects as $subject): ?>
                        <option value="<?= htmlspecialchars($subject) ?>"
                            <?= ($_GET['subject'] ?? '') == $subject ? 'selected' : '' ?>>
                            <?= htmlspecialchars($subject) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Topic</label>
                    <select class="form-select" name="topic">
                        <option value="">All Topics</option>
                        <?php foreach ($allTopics as $topic): ?>
                        <option value="<?= htmlspecialchars($topic) ?>"
                            <?= ($_GET['topic'] ?? '') == $topic ? 'selected' : '' ?>>
                            <?= htmlspecialchars($topic) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>


                <div class="col-md-2">
                    <label class="form-label">Difficulty</label>
                    <select class="form-select" name="difficulty">
                        <option value="">All Levels</option>
                        <option value="easy" <?= ($_GET['difficulty'] ?? '') == 'easy' ? 'selected' : '' ?>>Easy
                        </option>
                        <option value="medium" <?= ($_GET['difficulty'] ?? '') == 'medium' ? 'selected' : '' ?>>Medium
                        </option>
                        <option value="hard" <?= ($_GET['difficulty'] ?? '') == 'hard' ? 'selected' : '' ?>>Hard
                        </option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Search Text</label>
                    <input type="text" class="form-control" name="search_text"
                        value="<?= htmlspecialchars($_GET['search_text'] ?? '') ?>" placeholder="Search questions...">
                </div>

                <div class="col-md-1">
                    <button class="btn btn-primary w-100" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>



<!-- Questions List -->
<div class="row">
    <?php foreach ($questions as $question): ?>
    <div class="col-12 mb-3">
        <div class="question-card card <?= $question['difficulty'] ?>">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="d-flex align-items-start mb-2">
                            <div class="me-3">
                                <span class="badge bg-primary"><?= htmlspecialchars($question['subject']) ?></span>
                                <span class="badge bg-secondary"><?= htmlspecialchars($question['topic']) ?></span>
                                <span
                                    class="badge bg-<?= $question['difficulty'] === 'easy' ? 'success' : ($question['difficulty'] === 'medium' ? 'warning' : 'danger') ?>">
                                    <?= ucfirst($question['difficulty']) ?>
                                </span>
                            </div>
                        </div>

                        <h6 class="question-text">
                            <?= htmlspecialchars($question['question_text']) ?></h6>

                        <div class="question-meta mt-2">
                            <small class="text-muted">
                                <i class="fas fa-calendar me-1"></i><?= $question['exam_year'] ?>
                                <i class="fas fa-building ms-3 me-1"></i><?= htmlspecialchars($question['source']) ?>
                                <?php if ($question['is_public']): ?>
                                <i class="fas fa-globe ms-3 me-1"></i>Public
                                <?php endif; ?>
                            </small>
                        </div>
                    </div>

                    <div class="col-md-4 text-end">
                        <div class="d-flex justify-content-end align-items-center gap-2">

                            <!-- Selection Checkbox -->
                            <div class="form-check mb-0">
                                <label class="form-check-label" for="select_<?= $question['id'] ?>">Select</label>
                                <input class="form-check-input" type="checkbox" value="<?= $question['id'] ?>"
                                    id="select_<?= $question['id'] ?>">
                            </div>

                            <!-- Preview -->
                            <button class="btn btn-sm btn-outline-success view-details-btn" title="View Details"
                                data-id="<?= $question['id'] ?>"
                                data-subject="<?= htmlspecialchars($question['subject']) ?>"
                                data-topic="<?= htmlspecialchars($question['topic']) ?>"
                                data-subtopic="<?= htmlspecialchars($question['subtopic']) ?>"
                                data-question_text="<?= htmlspecialchars($question['question_text']) ?>"
                                data-options="<?= htmlspecialchars($question['options']) ?>"
                                data-correct_answer="<?= htmlspecialchars($question['correct_answer']) ?>"
                                data-explanation="<?= htmlspecialchars($question['explanation']) ?>"
                                data-difficulty="<?= htmlspecialchars($question['difficulty']) ?>"
                                data-exam_year="<?= htmlspecialchars($question['exam_year']) ?>"
                                data-source="<?= htmlspecialchars($question['source']) ?>"
                                data-is_public="<?= $question['is_public'] ? 'Yes' : 'No' ?>">
                                <i class="fas fa-eye"></i>
                            </button>

                            <!-- Edit -->
                            <button class="btn btn-sm btn-outline-primary" title="Edit"
                                onclick="window.location.href='index.php?page=questions&action=edit&id=<?= $question['id'] ?>'">
                                <i class="fas fa-edit"></i>
                            </button>

                            <!-- Add to Test -->
                            <button class="btn btn-sm btn-outline-info add-to-test-btn" title="Add to Test Pack"
                                data-question-id="<?= $question['id'] ?>"
                                data-question-label="<?= htmlspecialchars($question['question_text']) ?>"
                                data-bs-toggle="modal" data-bs-target="#addToTestModal">
                                <i class="fas fa-plus"></i>
                            </button>

                            <!-- Delete -->
                            <button class="btn btn-sm btn-outline-danger" title="Delete"
                                onclick="if(confirm('Are you sure?')) { window.location.href='index.php?page=questions&action=delete&id=<?= $question['id'] ?>' }">
                                <i class="fas fa-trash"></i>
                            </button>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<!-- Modal: Add Question to Test -->
<div class="modal fade" id="addToTestModal" tabindex="-1" aria-labelledby="addToTestModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="index.php?page=questions&action=add_question_to_test">
            <input type="hidden" name="question_id" id="modalQuestionId">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Question to Test Pack</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="fw-bold" id="modalQuestionText"></p>
                    <div class="mb-3">
                        <label class="form-label">Select Test Pack</label>
                        <select class="form-select" name="test_id" required>
                            <?php
              $packs = $db->query("SELECT id, title FROM test_packs WHERE is_active = 1 AND is_visible_to_students = 1 ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
              foreach ($packs as $pack) {
                  echo "<option value=\"{$pack['id']}\">" . htmlspecialchars($pack['title']) . "</option>";
              }
              ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary">Add to Test</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Question Preview Modal -->
<div class="modal fade" id="questionPreviewModal" tabindex="-1" aria-labelledby="questionPreviewModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="questionPreviewModalLabel">Question Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="preview-question-content">
                    <!-- Content will be loaded by JS -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
// Helper to decode options JSON if present
function renderOptions(optionsJson) {
    let html = '';
    try {
        const options = JSON.parse(optionsJson);
        html += '<ul class="list-group mb-2">';
        for (const key of ['A', 'B', 'C', 'D']) {
            if (options[key]) {
                html += `<li class="list-group-item"><strong>${key}.</strong> ${options[key]}</li>`;
            }
        }
        html += '</ul>';
    } catch (e) {}
    return html;
}

// Preview button click handler (converted to vanilla JavaScript)
document.addEventListener('click', function(e) {
    if (e.target.closest('.preview-btn')) {
        const btn = e.target.closest('.preview-btn');
        // This button uses data attributes, so we can use the view-details-btn logic
        // Since preview-btn and view-details-btn should work the same way
        const questionData = {
            subject: btn.getAttribute('data-subject'),
            topic: btn.getAttribute('data-topic'),
            subtopic: btn.getAttribute('data-subtopic'),
            question_text: btn.getAttribute('data-question_text'),
            options: btn.getAttribute('data-options'),
            correct_answer: btn.getAttribute('data-correct_answer'),
            explanation: btn.getAttribute('data-explanation'),
            difficulty: btn.getAttribute('data-difficulty'),
            exam_year: btn.getAttribute('data-exam_year'),
            source: btn.getAttribute('data-source'),
            is_public: btn.getAttribute('data-is_public')
        };

        let html = '';
        html +=
            `<div><span class='badge bg-primary'>${questionData.subject}</span> <span class='badge bg-secondary'>${questionData.topic}</span> <span class='badge bg-${questionData.difficulty === 'easy' ? 'success' : (questionData.difficulty === 'medium' ? 'warning' : 'danger')}'>${questionData.difficulty.charAt(0).toUpperCase() + questionData.difficulty.slice(1)}</span></div>`;
        html += `<h5 class='mt-3'>${questionData.question_text}</h5>`;
        html += renderOptions(questionData.options);
        html +=
            `<div class='mb-2'><strong>Correct Answer:</strong> ${questionData.correct_answer}</div>`;
        html +=
            `<div class='mb-2'><strong>Explanation:</strong> ${questionData.explanation || '-'}</div>`;
        html +=
            `<div class='mb-2'><strong>Exam Year:</strong> ${questionData.exam_year} <strong>Source:</strong> ${questionData.source}</div>`;
        html +=
            `<div class='mb-2'><strong>Subtopic:</strong> ${questionData.subtopic || '-'}</div>`;
        html +=
            `<div class='mb-2'><strong>Public:</strong> ${questionData.is_public}</div>`;

        document.getElementById('preview-question-content').innerHTML = html;
        var modal = new bootstrap.Modal(document.getElementById('questionPreviewModal'));
        modal.show();
    }
});
</script>



<!-- Bulk Actions -->
<div class="card mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <div class="form-check d-inline-block me-3">
                    <input class="form-check-input" type="checkbox" id="select-all">
                    <label class="form-check-label" for="select-all">Select All</label>
                </div>
                <span class="text-muted">Selected: <span id="selected-count">0</span>
                    questions</span>
            </div>
            <div>
                <button class="btn btn-outline-primary me-2" id="bulk-duplicate-btn" type="button">
                    <i class="fas fa-copy me-2"></i>Duplicate Selected
                </button>
                <button class="btn btn-outline-success me-2" id="bulk-add-to-test-btn" type="button"
                    data-bs-toggle="modal" data-bs-target="#bulkAddToTestModal">
                    <i class="fas fa-plus me-2"></i>Add to Test
                </button>
                <button class="btn btn-outline-danger" id="bulk-delete-btn" type="button">
                    <i class="fas fa-trash me-2"></i>Delete Selected
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Add to Test Modal -->
<div class="modal fade" id="bulkAddToTestModal" tabindex="-1" aria-labelledby="bulkAddToTestModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="index.php?page=questions&action=bulk_add_to_test" id="bulk-add-to-test-form">
            <input type="hidden" name="question_ids" id="bulkQuestionIds">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Selected Questions to Test Pack</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Select Test Pack</label>
                        <select class="form-select" name="test_id" required>
                            <?php
                            $packs = $db->query("SELECT id, title FROM test_packs WHERE is_active = 1 AND is_visible_to_students = 1 ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
                            foreach ($packs as $pack) {
                                echo "<option value=\"{$pack['id']}\">" . htmlspecialchars($pack['title']) . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div>
                        <small class="text-muted">You are adding <span id="bulk-selected-count">0</span>
                            questions.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary">Add to Test</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
// Track selected checkboxes
function getSelectedQuestionIds() {
    const selected = Array.from(document.querySelectorAll(
            '.form-check-input[type="checkbox"]:checked:not(#select-all)'))
        .map(cb => cb.value);
    console.log('Selected question IDs:', selected);
    return selected;
}

function updateSelectedCount() {
    const count = getSelectedQuestionIds().length;
    document.getElementById('selected-count').textContent = count;

    // Update select-all checkbox state
    const totalCheckboxes = document.querySelectorAll(
        '.form-check-input[type="checkbox"]:not(#select-all)').length;
    const selectAllCheckbox = document.getElementById('select-all');
    if (count === 0) {
        selectAllCheckbox.indeterminate = false;
        selectAllCheckbox.checked = false;
    } else if (count === totalCheckboxes) {
        selectAllCheckbox.indeterminate = false;
        selectAllCheckbox.checked = true;
    } else {
        selectAllCheckbox.indeterminate = true;
        selectAllCheckbox.checked = false;
    }
}

// Select All functionality
document.getElementById('select-all').addEventListener('change', function() {
    const isChecked = this.checked;
    document.querySelectorAll('.form-check-input[type="checkbox"]:not(#select-all)')
        .forEach(cb => {
            cb.checked = isChecked;
        });
    updateSelectedCount();
});

// Update count on checkbox change
document.querySelectorAll('.form-check-input[type="checkbox"]:not(#select-all)').forEach(cb => {
    cb.addEventListener('change', updateSelectedCount);
});

// Bulk Duplicate
document.getElementById('bulk-duplicate-btn').addEventListener('click', function() {
    console.log('Duplicate button clicked');
    const ids = getSelectedQuestionIds();
    console.log('Selected IDs for duplication:', ids);

    if (ids.length === 0) {
        alert('Please select at least one question to duplicate.');
        return;
    }
    if (!confirm('Duplicate selected questions?')) return;

    console.log('Sending duplicate request with IDs:', ids);

    // Show loading state
    this.disabled = true;
    this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Duplicating...';

    // Send AJAX request to duplicate
    fetch('ajax/duplicate_questions.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                question_ids: ids
            })
        })
        .then(res => {
            console.log('Response status:', res.status);
            if (!res.ok) {
                throw new Error(`HTTP error! status: ${res.status}`);
            }
            return res.json();
        })
        .then(data => {
            console.log('Response data:', data);
            if (data.success) {
                alert('Questions duplicated successfully!');
                window.location.reload();
            } else {
                alert('Error: ' + (data.error || 'Could not duplicate.'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Network error: ' + error.message);
        })
        .finally(() => {
            // Reset button state
            this.disabled = false;
            this.innerHTML = '<i class="fas fa-copy me-2"></i>Duplicate Selected';
        });
});

// Bulk Add to Test
document.getElementById('bulk-add-to-test-btn').addEventListener('click', function(e) {
    const ids = getSelectedQuestionIds();
    if (ids.length === 0) {
        e.preventDefault();
        e.stopPropagation();
        alert('Please select at least one question to add to test.');
        return false;
    }
    document.getElementById('bulkQuestionIds').value = ids.join(',');
    document.getElementById('bulk-selected-count').textContent = ids.length;
});

// Bulk Delete
document.getElementById('bulk-delete-btn').addEventListener('click', function() {
    console.log('Bulk delete button clicked');
    const ids = getSelectedQuestionIds();
    console.log('Selected question IDs:', ids);
    if (ids.length === 0) {
        alert('Please select at least one question to delete.');
        return;
    }
    if (!confirm('Are you sure you want to delete selected questions?')) return;

    console.log('Sending delete request...');

    // Show loading state
    this.disabled = true;
    this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Deleting...';

    // Send AJAX request to dedicated endpoint
    fetch('ajax/delete_questions.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                question_ids: ids
            })
        })
        .then(res => {
            console.log('Delete response status:', res.status);
            if (!res.ok) {
                throw new Error(`HTTP error! status: ${res.status}`);
            }
            return res.json();
        })
        .then(data => {
            console.log('Delete parsed JSON:', data);
            if (data.success) {
                alert('Questions deleted successfully!');
                window.location.reload();
            } else {
                alert('Error: ' + (data.error || 'Could not delete.'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Network error: ' + error.message);
        })
        .finally(() => {
            // Reset button state
            this.disabled = false;
            this.innerHTML = '<i class="fas fa-trash me-2"></i>Delete Selected';
        });
});
</script>

<?php elseif ($action === 'create'): ?>
<!-- Create Question Form -->
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-plus me-2"></i>Add New Question
                </h5>
            </div>
            <div class="card-body">

                <form method="POST" class="needs-validation" novalidate id="question-form">
                    <input type="hidden" name="save_and_add_another" id="save_and_add_another" value="0">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="subject" class="form-label">Select Subject</label>
                                <select class="form-select" id="subject" name="subject">
                                    <option value="">-- Select Existing Subject --</option>
                                    <?php foreach ($allSubjects as $s): ?>
                                    <option value="<?= htmlspecialchars($s) ?>">
                                        <?= htmlspecialchars($s) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="new_subject" class="form-label">Or Add New
                                    Subject</label>
                                <input type="text" class="form-control" id="new_subject" name="new_subject"
                                    placeholder="Enter new subject name">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="topic" class="form-label">Topic</label>
                                <input type="text" class="form-control" id="topic" name="topic" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="subtopic" class="form-label">Subtopic</label>
                                <input type="text" class="form-control" id="subtopic" name="subtopic">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="question_text" class="form-label">Question</label>
                        <textarea class="form-control" id="question_text" name="question_text" rows="4"
                            required></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="option_a" class="form-label">Option A</label>
                                <input type="text" class="form-control" id="option_a" name="option_a">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="option_b" class="form-label">Option B</label>
                                <input type="text" class="form-control" id="option_b" name="option_b">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="option_c" class="form-label">Option C</label>
                                <input type="text" class="form-control" id="option_c" name="option_c">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="option_d" class="form-label">Option D</label>
                                <input type="text" class="form-control" id="option_d" name="option_d">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="correct_answer" class="form-label">Correct
                                    Answer</label>
                                <select class="form-select" id="correct_answer" name="correct_answer">
                                    <option value="A">A</option>
                                    <option value="B">B</option>
                                    <option value="C">C</option>
                                    <option value="D">D</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="difficulty" class="form-label">Difficulty</label>
                                <select class="form-select" id="difficulty" name="difficulty">
                                    <option value="easy">Easy</option>
                                    <option value="medium" selected>Medium</option>
                                    <option value="hard">Hard</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="exam_year" class="form-label">Exam Year</label>
                                <input type="number" class="form-control" id="exam_year" name="exam_year" min="2000"
                                    max="2030" value="2024">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="source" class="form-label">Exam</label>
                                <input type="text" class="form-control" id="source" name="source"
                                    placeholder="e.g., TNPSC, UPSC">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="explanation" class="form-label">Explanation (Optional)</label>
                        <textarea class="form-control" id="explanation" name="explanation" rows="3"></textarea>
                    </div>

                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" id="is_public" name="is_public" checked>
                        <label class="form-check-label" for="is_public">
                            Make this question public (visible to all institutes)
                        </label>
                    </div>

                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary"
                            onclick="window.location.href='index.php?page=questions'">
                            <i class="fas fa-arrow-left me-2"></i>Back to Questions
                        </button>
                        <div>
                            <button type="button" class="btn btn-outline-primary me-2" id="save-and-add-another-btn">
                                <i class="fas fa-save me-2"></i>Save & Add Another
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-check me-2"></i>Add Question
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Save & Add Another functionality
document.getElementById('save-and-add-another-btn').addEventListener('click', function() {
    // Set the hidden input to indicate "Save & Add Another" was clicked
    document.getElementById('save_and_add_another').value = '1';

    // Show loading state
    this.disabled = true;
    this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Saving...';

    // Submit the form
    document.getElementById('question-form').submit();
});
</script>

<?php elseif ($action === 'edit'): ?>
<!-- Edit Question Form -->
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-edit me-2"></i>Edit Question
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" class="needs-validation" novalidate>
                    <input type="hidden" name="question_id" value="<?= $editQuestion['id'] ?>">

                    <div class="row">
                        <div class="col-md-4">
                            <!-- Subject Dropdown -->
                            <div class="mb-3">
                                <label for="subject" class="form-label">Select Subject</label>
                                <select class="form-select" id="subject" name="subject">
                                    <option value="">-- Select Existing Subject --</option>
                                    <?php foreach ($allSubjects as $s): ?>
                                    <option value="<?= htmlspecialchars($s) ?>"
                                        <?= ($editQuestion['subject'] == $s) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($s) ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- New Subject -->
                            <div class="mb-3">
                                <label for="new_subject" class="form-label">Or Add New
                                    Subject</label>
                                <input type="text" class="form-control" id="new_subject" name="new_subject"
                                    placeholder="Enter new subject name">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="topic" class="form-label">Topic</label>
                                <input type="text" class="form-control" id="topic" name="topic"
                                    value="<?= htmlspecialchars($editQuestion['topic']) ?>" required>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="subtopic" class="form-label">Subtopic</label>
                                <input type="text" class="form-control" id="subtopic" name="subtopic"
                                    value="<?= htmlspecialchars($editQuestion['subtopic']) ?>">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="question_text" class="form-label">Question</label>
                        <textarea class="form-control" id="question_text" name="question_text" rows="4"
                            required><?= htmlspecialchars($editQuestion['question_text']) ?></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="option_a" class="form-label">Option A</label>
                                <input type="text" class="form-control" id="option_a" name="option_a"
                                    value="<?= htmlspecialchars($options['A'] ?? '') ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="option_b" class="form-label">Option B</label>
                                <input type="text" class="form-control" id="option_b" name="option_b"
                                    value="<?= htmlspecialchars($options['B'] ?? '') ?>">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="option_c" class="form-label">Option C</label>
                                <input type="text" class="form-control" id="option_c" name="option_c"
                                    value="<?= htmlspecialchars($options['C'] ?? '') ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="option_d" class="form-label">Option D</label>
                                <input type="text" class="form-control" id="option_d" name="option_d"
                                    value="<?= htmlspecialchars($options['D'] ?? '') ?>">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="correct_answer" class="form-label">Correct
                                    Answer</label>
                                <select class="form-select" id="correct_answer" name="correct_answer">
                                    <?php foreach (['A', 'B', 'C', 'D'] as $opt): ?>
                                    <option value="<?= $opt ?>"
                                        <?= ($editQuestion['correct_answer'] == $opt) ? 'selected' : '' ?>>
                                        <?= $opt ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="difficulty" class="form-label">Difficulty</label>
                                <select class="form-select" id="difficulty" name="difficulty">
                                    <?php foreach (['easy', 'medium', 'hard'] as $diff): ?>
                                    <option value="<?= $diff ?>"
                                        <?= ($editQuestion['difficulty'] == $diff) ? 'selected' : '' ?>>
                                        <?= ucfirst($diff) ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="exam_year" class="form-label">Exam Year</label>
                                <input type="number" class="form-control" id="exam_year" name="exam_year" min="2000"
                                    max="2030" value="<?= htmlspecialchars($editQuestion['exam_year']) ?>">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="source" class="form-label">Exam</label>
                                <input type="text" class="form-control" id="source" name="source"
                                    value="<?= htmlspecialchars($editQuestion['source']) ?>">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="explanation" class="form-label">Explanation (Optional)</label>
                        <textarea class="form-control" id="explanation" name="explanation"
                            rows="3"><?= htmlspecialchars($editQuestion['explanation']) ?></textarea>
                    </div>

                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" id="is_public" name="is_public"
                            <?= $editQuestion['is_public'] ? 'checked' : '' ?>>
                        <label class="form-check-label" for="is_public">
                            Make this question public (visible to all institutes)
                        </label>
                    </div>

                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary"
                            onclick="window.location.href='index.php?page=questions'">
                            <i class="fas fa-arrow-left me-2"></i>Back to Questions
                        </button>
                        <div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-check me-2"></i>Update Question
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php elseif ($action === 'upload'): ?>
<!-- Bulk Upload -->
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-upload me-2"></i>Bulk Upload Questions
                </h5>
            </div>
            <div class="card-body">

                <?php if (($_GET['step'] ?? '') === 'preview' && isset($_SESSION['bulk_upload_questions'])): ?>
                <!-- Preview Step -->
                <div class="mb-4">
                    <h6>Preview Questions Before Import</h6>
                    <p class="text-muted">Review the parsed questions below and click "Import All"
                        to add them to your
                        question bank.</p>
                </div>

                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="badge bg-info">Total Questions:
                            <?= count($_SESSION['bulk_upload_questions']) ?></span>
                        <div>
                            <button class="btn btn-secondary me-2"
                                onclick="window.location.href='index.php?page=questions&action=upload'">
                                <i class="fas fa-arrow-left me-2"></i>Back to Upload
                            </button>
                            <button class="btn btn-success" id="import-questions-btn">
                                <i class="fas fa-check me-2"></i>Import All Questions
                            </button>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Subject</th>
                                <th>Topic</th>
                                <th>Question</th>
                                <th>Options</th>
                                <th>Answer</th>
                                <th>Difficulty</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($_SESSION['bulk_upload_questions'] as $index => $question): ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td><?= htmlspecialchars($question['subject']) ?></td>
                                <td><?= htmlspecialchars($question['topic']) ?></td>
                                <td><?= htmlspecialchars(substr($question['question_text'], 0, 100)) ?><?= strlen($question['question_text']) > 100 ? '...' : '' ?>
                                </td>
                                <td>
                                    <small>
                                        A:
                                        <?= htmlspecialchars(substr($question['option_a'], 0, 30)) ?><br>
                                        B:
                                        <?= htmlspecialchars(substr($question['option_b'], 0, 30)) ?><br>
                                        C:
                                        <?= htmlspecialchars(substr($question['option_c'], 0, 30)) ?><br>
                                        D:
                                        <?= htmlspecialchars(substr($question['option_d'], 0, 30)) ?>
                                    </small>
                                </td>
                                <td><span class="badge bg-primary"><?= $question['correct_answer'] ?></span>
                                </td>
                                <td><span
                                        class="badge bg-<?= $question['difficulty'] === 'easy' ? 'success' : ($question['difficulty'] === 'medium' ? 'warning' : 'danger') ?>"><?= ucfirst($question['difficulty']) ?></span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <script>
                document.getElementById('import-questions-btn').addEventListener('click',
                    function() {
                        if (!confirm('Are you sure you want to import all these questions?'))
                            return;

                        this.disabled = true;
                        this.innerHTML =
                            '<i class="fas fa-spinner fa-spin me-2"></i>Importing...';

                        fetch('index.php?page=questions&action=import_questions', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    alert(data.message);
                                    if (data.redirect) {
                                        window.location.href = data.redirect;
                                    }
                                } else {
                                    alert('Error: ' + data.error);
                                }
                            })
                            .catch(error => {
                                alert('Network error: ' + error.message);
                            })
                            .finally(() => {
                                this.disabled = false;
                                this.innerHTML =
                                    '<i class="fas fa-check me-2"></i>Import All Questions';
                            });
                    });
                </script>

                <?php else: ?>
                <!-- Upload Step -->
                <div class="row">
                    <div class="col-md-6">
                        <h6>Upload Methods</h6>
                        <div class="list-group mb-4">
                            <div class="list-group-item d-flex justify-content-between align-items-start">
                                <div class="ms-2 me-auto">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Excel/CSV Upload</h6>
                                        <small>Recommended</small>
                                    </div>
                                    <p class="mb-1">Upload .xlsx or .csv files with question data
                                    </p>
                                    <small>Best for large datasets</small>
                                </div>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-start">
                                <div class="ms-2 me-auto">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Text Import</h6>
                                        <small>Manual</small>
                                    </div>
                                    <p class="mb-1">Copy-paste questions in structured format</p>
                                    <small>For quick manual entry</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <h6>Upload Area</h6>
                        <div class="border border-dashed border-primary rounded p-5 text-center" id="upload-area">
                            <i class="fas fa-cloud-upload-alt fa-3x text-primary mb-3"></i>
                            <h5>Drag & Drop Files Here</h5>
                            <p class="text-muted">or click to browse</p>
                            <input type="file" id="file-input" accept=".xlsx,.csv" style="display: none;">
                            <button class="btn btn-primary" onclick="document.getElementById('file-input').click()">
                                <i class="fas fa-file me-2"></i>Choose Files
                            </button>
                        </div>

                        <div class="mt-3">
                            <small class="text-muted">
                                Supported formats: .xlsx, .csv<br>
                                Maximum file size: 10MB
                            </small>
                        </div>
                    </div>
                </div>

                <hr>

                <!-- Template Download -->
                <div class="row">
                    <div class="col-md-12">
                        <h6>Template & Instructions</h6>
                        <div class="bg-light p-3 rounded">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>Excel Template (.xlsx)</strong>
                                            <br><small class="text-muted">Professional format with
                                                company header & serial numbers</small>
                                        </div>
                                        <button class="btn btn-success btn-sm"
                                            onclick="window.location.href='index.php?page=questions&action=download_template&format=excel'">
                                            <i class="fas fa-file-excel me-2"></i>Excel
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>CSV Template (.csv)</strong>
                                            <br><small class="text-muted">Simple format compatible
                                                with all spreadsheet apps</small>
                                        </div>
                                        <button class="btn btn-outline-success btn-sm"
                                            onclick="window.location.href='index.php?page=questions&action=download_template&format=csv'">
                                            <i class="fas fa-file-csv me-2"></i>CSV
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3 p-2 bg-warning bg-opacity-10 rounded">
                                <small class="text-warning">
                                    <i class="fas fa-info-circle me-1"></i>
                                    <strong>New Format:</strong> Templates now include a company
                                    name row and S.No column for better organization. Both old and
                                    new formats are supported for upload.
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                <!-- Text Import -->
                <div class="row">
                    <div class="col-md-12">
                        <h6>Text Import</h6>
                        <p class="text-muted">Paste questions in the following format:</p>
                        <div class="bg-light p-3 rounded mb-3">
                            <small>
                                <strong>Example format:</strong><br>
                                1. With reference to the writs issued by the Courts in India,
                                consider the following
                                statements:<br><br>
                                1. Mandamus will not lie against a private organization unless it is
                                entrusted with a
                                public duty.<br>
                                2. Mandamus will not lie against a Company even though it may be a
                                Government
                                Company.<br>
                                3. Any public minded person can be a petitioner to move the Court to
                                obtain the writ of
                                Quo Warranto.<br><br>
                                Which of the statements given above are correct?<br><br>
                                A) 1 and 2 only<br>
                                B) 2 and 3 only<br>
                                C) 1 and 3 only<br>
                                D) 1, 2 and 3<br>
                                Answer: C<br>
                                Explanation: Statement 1 is correct, Statement 2 is incorrect,
                                Statement 3 is
                                correct<br><br>

                                2. In the series: 2, 6, 12, 20, 30, ? What comes next?<br>
                                A) 42<br>
                                B) 40<br>
                                C) 36<br>
                                D) 48<br>
                                Answer: A<br>
                            </small>
                        </div>

                        <form id="text-import-form">
                            <div class="mb-3">
                                <label for="text-content" class="form-label">Paste Questions
                                    Here</label>
                                <textarea class="form-control" id="text-content" name="text_content" rows="10"
                                    placeholder="Paste your questions here in the format shown above..."></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-upload me-2"></i>Import Text
                            </button>
                        </form>
                    </div>
                </div>

                <script>
                // File upload handling
                const fileInput = document.getElementById('file-input');
                const uploadArea = document.getElementById('upload-area');

                // Drag and drop handlers
                uploadArea.addEventListener('dragover', function(e) {
                    e.preventDefault();
                    uploadArea.classList.add('border-success');
                });

                uploadArea.addEventListener('dragleave', function(e) {
                    e.preventDefault();
                    uploadArea.classList.remove('border-success');
                });

                uploadArea.addEventListener('drop', function(e) {
                    e.preventDefault();
                    uploadArea.classList.remove('border-success');

                    const files = e.dataTransfer.files;
                    if (files.length > 0) {
                        handleFileUpload(files[0]);
                    }
                });

                fileInput.addEventListener('change', function(e) {
                    if (e.target.files.length > 0) {
                        handleFileUpload(e.target.files[0]);
                    }
                });

                function handleFileUpload(file) {
                    // Validate file
                    const allowedTypes = [
                        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                        'text/csv'
                    ];
                    if (!allowedTypes.includes(file.type) && !file.name.match(/\.(xlsx|csv)$/i)) {
                        alert('Please upload only Excel (.xlsx) or CSV (.csv) files');
                        return;
                    }

                    if (file.size > 10 * 1024 * 1024) {
                        alert('File size must be less than 10MB');
                        return;
                    }

                    // Show upload progress
                    uploadArea.innerHTML =
                        '<i class="fas fa-spinner fa-spin fa-2x text-primary mb-3"></i><h5>Processing file...</h5><p class="text-muted">Please wait while we parse your file</p>';

                    // Create form data and upload
                    const formData = new FormData();
                    formData.append('upload_file', file);

                    fetch('index.php?page=questions&action=process_upload', {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert(data.message + '. Found ' + data.question_count +
                                    ' questions.');
                                if (data.redirect) {
                                    window.location.href = data.redirect;
                                }
                            } else {
                                alert('Error: ' + data.error);
                                resetUploadArea();
                            }
                        })
                        .catch(error => {
                            alert('Network error: ' + error.message);
                            resetUploadArea();
                        });
                }

                function resetUploadArea() {
                    uploadArea.innerHTML =
                        '<i class="fas fa-cloud-upload-alt fa-3x text-primary mb-3"></i><h5>Drag & Drop Files Here</h5><p class="text-muted">or click to browse</p><button class="btn btn-primary" onclick="document.getElementById(\'file-input\').click()"><i class="fas fa-file me-2"></i>Choose Files</button>';
                }

                // Text import handling
                document.getElementById('text-import-form').addEventListener('submit', function(e) {
                    e.preventDefault();

                    const textContent = document.getElementById('text-content').value
                        .trim();
                    if (!textContent) {
                        alert('Please enter some text content');
                        return;
                    }

                    const submitBtn = this.querySelector('button[type="submit"]');
                    submitBtn.disabled = true;
                    submitBtn.innerHTML =
                        '<i class="fas fa-spinner fa-spin me-2"></i>Processing...';

                    const formData = new FormData();
                    formData.append('text_content', textContent);

                    fetch('index.php?page=questions&action=process_text', {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert(data.message + '. Found ' + data.question_count +
                                    ' questions.');
                                if (data.redirect) {
                                    window.location.href = data.redirect;
                                }
                            } else {
                                alert('Error: ' + data.error);
                            }
                        })
                        .catch(error => {
                            alert('Network error: ' + error.message);
                        })
                        .finally(() => {
                            submitBtn.disabled = false;
                            submitBtn.innerHTML =
                                '<i class="fas fa-upload me-2"></i>Import Text';
                        });
                });
                </script>

                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php endif; ?>

<script>
document.querySelectorAll('.view-details-btn').forEach(function(button) {
    button.addEventListener('click', function() {
        let subject = this.getAttribute('data-subject');
        let topic = this.getAttribute('data-topic');
        let subtopic = this.getAttribute('data-subtopic');
        let question_text = this.getAttribute('data-question_text');
        let options = this.getAttribute('data-options');
        let correct_answer = this.getAttribute('data-correct_answer');
        let explanation = this.getAttribute('data-explanation');
        let difficulty = this.getAttribute('data-difficulty');
        let exam_year = this.getAttribute('data-exam_year');
        let source = this.getAttribute('data-source');
        let is_public = this.getAttribute('data-is_public');

        let optionsHtml = '';
        try {
            let opts = JSON.parse(options);
            optionsHtml += '<ul class="list-group mb-2">';
            ['A', 'B', 'C', 'D'].forEach(function(key) {
                if (opts[key]) {
                    optionsHtml +=
                        `<li class="list-group-item"><strong>${key}.</strong> ${opts[key]}</li>`;
                }
            });
            optionsHtml += '</ul>';
        } catch (e) {}

        let html = '';
        html +=
            `<div><span class='badge bg-primary'>${subject}</span> <span class='badge bg-secondary'>${topic}</span> <span class='badge bg-${difficulty === 'easy' ? 'success' : (difficulty === 'medium' ? 'warning' : 'danger')}'>${difficulty.charAt(0).toUpperCase() + difficulty.slice(1)}</span></div>`;
        html += `<h5 class='mt-3'>${question_text}</h5>`;
        html += optionsHtml;
        html +=
            `<div class='mb-2'><strong>Correct Answer:</strong> ${correct_answer}</div>`;
        html +=
            `<div class='mb-2'><strong>Explanation:</strong> ${explanation || '-'}</div>`;
        html +=
            `<div class='mb-2'><strong>Exam Year:</strong> ${exam_year} <strong>Source:</strong> ${source}</div>`;
        html +=
            `<div class='mb-2'><strong>Subtopic:</strong> ${subtopic || '-'}</div>`;
        html += `<div class='mb-2'><strong>Public:</strong> ${is_public}</div>`;

        // Use modal if exists, else fallback to alert
        if (document.getElementById('questionPreviewModal')) {
            document.getElementById('preview-question-content').innerHTML = html;
            let modal = new bootstrap.Modal(document.getElementById(
                'questionPreviewModal'));
            modal.show();
        } else {
            alert(subject + '\n' + topic + '\n' + question_text);
        }
    });
});
</script>

<script>
document.querySelectorAll('.add-to-test-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const questionId = this.dataset.questionId;
        const questionLabel = this.dataset.questionLabel;

        document.getElementById('modalQuestionId').value = questionId;
        document.getElementById('modalQuestionText').textContent = questionLabel;
    });
});
</script>