<?php
// Guard to avoid double-inclusion
if (defined('QUESTIONS_FORMS_INCLUDED')) {
    return;
}
define('QUESTIONS_FORMS_INCLUDED', true);

// Reuse database, session and helpers from bootstrap (index.php already started the session)
$action = $_GET['action'] ?? 'list';

// For AJAX actions, set JSON content type
$ajax_actions = ['bulk_duplicate', 'bulk_delete', 'process_upload', 'process_text', 'import_questions'];
if (in_array($action, $ajax_actions) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
}

// Ensure bulk upload helper functions are available before any AJAX handlers execute
// bulk_upload_simple.php is located at tms/bulk_upload_simple.php
require_once dirname(__DIR__, 3) . '/bulk_upload_simple.php';

// Get current user (authentication already handled in index.php)
$user = getCurrentUser();

// Ensure session started (defensive; index.php typically starts session)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Helper redirect using header() with a safe fallback when headers already sent
function form_redirect($url)
{
    if (!headers_sent()) {
        header('Location: ' . $url);
        exit;
    }

    $escaped = htmlspecialchars($url, ENT_QUOTES | ENT_SUBSTITUTE);
    echo '<!doctype html><html><head>';
    echo '<meta http-equiv="refresh" content="0;url=' . $escaped . '">';
    echo '<script>window.location.href = "' . $escaped . '";</script>';
    echo '</head><body>Redirecting... <a href="' . $escaped . '">' . $escaped . '</a></body></html>';
    exit;
}

// Small helper to validate & move uploaded files safely. Returns relative path (e.g. 'images/xxx.jpg') or false.
function handle_upload(array $file, string $uploadDir, string $relPrefix = 'images/', string $prefix = 'file_', array $allowedExt = [])
{
    if (empty($file['name']) || ($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
        return false;
    }

    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!empty($allowedExt) && !in_array($ext, $allowedExt, true)) {
        return false;
    }

    if (!is_dir($uploadDir)) {
        @mkdir($uploadDir, 0755, true);
    }

    // sanitize and create filename
    $filename = $prefix . uniqid() . '.' . $ext;
    $dst = rtrim($uploadDir, '/\\') . DIRECTORY_SEPARATOR . $filename;

    if (move_uploaded_file($file['tmp_name'], $dst)) {
        return rtrim($relPrefix, '/\\') . '/' . $filename;
    }

    return false;
}

// Bulk Duplicate Questions
if ($action === 'bulk_duplicate' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
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

        foreach ($question_ids as $question_id) {
            try {
                $stmt = $db->prepare("SELECT * FROM question_banks WHERE id = ?");
                $stmt->execute([$question_id]);
                $original = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($original) {
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
    try {
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
        echo "question_text,option_a,option_b,option_c,option_d,correct_answer,subject,topic,exam_year,source\n";
        echo 'Sample question text,Option A,Option B,Option C,Option D,A,General Knowledge,Sample Topic,' . date('Y') . ',Sample Exam\n';
        exit;
    }
}

// Handle Bulk Import
if ($action === 'import_questions' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (!isset($_SESSION['bulk_upload_questions']) || empty($_SESSION['bulk_upload_questions'])) {
            throw new Exception('No questions to import. Please upload a file first.');
        }

        $questions = $_SESSION['bulk_upload_questions'];
        $importResult = importQuestionsToDatabase($questions, $db);

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

// Delete Action (GET)
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
        $question_ids_array = array_filter($question_ids_array, function ($id) {
            return $id > 0;
        });

        if (empty($question_ids_array)) {
            header("Location: index.php?page=questions&error=invalid_data");
            exit;
        }

        $added_count = 0;
        $skipped_count = 0;

        foreach ($question_ids_array as $question_id) {
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

// This file is intended to be required early from questions.php before any HTML output.
// Additional moved handlers (create, edit, bulk-upload)
// These were migrated from the view file pages/questions.php so they run before
// any HTML output and can safely use header() redirects.
// ============================================================================

// Ensure helpers (parsers, etc.) are available when forms.php runs early
$functionsFile = dirname(__DIR__) . '/functions/functions.php';
if (file_exists($functionsFile)) {
    require_once $functionsFile;
}

// Create question handler
// Trigger when POST contains no question_id and either the POST 'action' explicitly says create OR the current $action is 'create'
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['question_id']) && ((isset($_POST['action']) && $_POST['action'] === 'create') || ($action === 'create'))) {
    try {
        if (function_exists('q_log'))
            q_log('forms.php: create question requested');
        $subject = trim($_POST['subject'] ?? '');
        $new_subject = trim($_POST['new_subject'] ?? '');
        if (!empty($new_subject))
            $subject = $new_subject;
        if (empty($subject))
            throw new Exception("Please select or enter a subject.");

        $topic = $_POST['topic'] ?? '';
        $subtopic = $_POST['subtopic'] ?? '';
        $question_text = $_POST['question_text_en'] ?? $_POST['question_text'] ?? '';
        $question_type = $_POST['question_type'] ?? 'text_mcq';
        $passage = $_POST['passage'] ?? '';
        $difficulty = $_POST['difficulty'] ?? 'medium';
        $exam_year = intval($_POST['exam_year'] ?? date('Y'));
        $source = $_POST['source'] ?? '';
        $explanation = $_POST['explanation'] ?? '';
        $is_public = isset($_POST['is_public']) ? 1 : 0;

        // Handle correct answer based on question type
        $correct_answer = '';
        if ($question_type === 'multi_correct') {
            $correct_answers = $_POST['correct_answers'] ?? [];
            $correct_answer = implode(',', $correct_answers);
        } elseif ($question_type === 'fill_blank') {
            $correct_answer = $_POST['blanks'] ?? '';
        } elseif ($question_type === 'true_false') {
            $correct_answer = $_POST['correct_answer'] ?? '';
        } elseif ($question_type === 'numerical') {
            $correct_answer = $_POST['correct_answer'] ?? '';
        } else {
            $correct_answer = $_POST['correct_answer'] ?? '';
        }

        // Build options array with enhanced structure
        $options_arr = [
            'type' => $question_type
        ];

        // Handle passage if present
        if (!empty($passage)) {
            $options_arr['passage'] = $passage;
        }

        // Handle match pairs if present
        if ($question_type === 'match') {
            $pairs = [];
            $left_options = $_POST['match_left'] ?? [];
            $right_options = $_POST['match_right'] ?? [];
            for ($i = 0; $i < count($left_options); $i++) {
                if (!empty($left_options[$i]) && !empty($right_options[$i])) {
                    $pairs[] = [
                        'left' => $left_options[$i],
                        'right' => $right_options[$i]
                    ];
                }
            }
            $options_arr['pairs'] = $pairs;
        }

        // Handle fill blanks answers
        if ($question_type === 'fill_blank') {
            $blanks = explode('|', $_POST['blanks'] ?? '');
            $options_arr['blanks'] = array_filter(array_map('trim', $blanks));
        }

        // Handle media uploads (image / audio) - store public relative path in options
        $uploadDir = dirname(__DIR__) . '/images/';
        if (!is_dir($uploadDir)) {
            @mkdir($uploadDir, 0755, true);
        }

        if (!empty($_FILES['image']['name'])) {
            $res = handle_upload($_FILES['image'], $uploadDir, 'images/', 'qimg_', ['jpg', 'jpeg', 'png', 'gif', 'webp']);
            if ($res)
                $options_arr['image'] = $res;
        }

        if (!empty($_FILES['audio']['name'])) {
            $res = handle_upload($_FILES['audio'], $uploadDir, 'images/', 'qaud_', ['mp3', 'wav', 'm4a', 'ogg']);
            if ($res)
                $options_arr['audio'] = $res;
        }

        // Per-option image uploads (option A..E)
        $optionImageMap = [
            'option_a_image' => 'A_image',
            'option_b_image' => 'B_image',
            'option_c_image' => 'C_image',
            'option_d_image' => 'D_image',
            'option_e_image' => 'E_image',
        ];

        foreach ($optionImageMap as $inputName => $optKey) {
            if (!empty($_FILES[$inputName]['name'])) {
                $res = handle_upload($_FILES[$inputName], $uploadDir, 'images/', 'optimg_', ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                if ($res)
                    $options_arr[$optKey] = $res;
            }
        }

        // Multilingual support: collect per-language fields and store under options->i18n
        $langs = ['en' => 'English', 'ta' => 'Tamil', 'hi' => 'Hindi'];
        $i18n = [];
        foreach ($langs as $code => $label) {
            $qt_name = 'question_text_' . $code;
            $i18n[$code] = [
                'question_text' => $_POST[$qt_name] ?? ''
            ];
            foreach (['A', 'B', 'C', 'D', 'E'] as $k) {
                $opt_name = 'option_' . strtolower($k) . '_' . $code;
                $i18n[$code][$k] = $_POST[$opt_name] ?? '';
            }
        }
        $options_arr['i18n'] = $i18n;

        // Prepare final options JSON
        $options_json = json_encode($options_arr, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        if ($options_json === false) {
            throw new Exception('Failed to encode options JSON: ' . json_last_error_msg());
        }

        $stmt = $db->prepare("INSERT INTO question_banks (title, subject, topic, subtopic, question_text, options, correct_answer, explanation, difficulty, exam_year, source, image, is_public, institute_id, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            '',
            $subject,
            $topic,
            $subtopic,
            $question_text,
            $options_json,
            $correct_answer,
            $explanation,
            $difficulty,
            $exam_year,
            $source,
            $options_arr['image'] ?? null,
            $is_public,
            null,
            date('Y-m-d H:i:s')
        ]);

        // Check if "Save & Add Another" was clicked
        $save_and_add_another = isset($_POST['save_and_add_another']) && $_POST['save_and_add_another'] === '1';
        if ($save_and_add_another) {
            form_redirect('index.php?page=questions&action=create&success=created_continue');
        } else {
            form_redirect('index.php?page=questions&success=created');
        }
    } catch (Exception $e) {
        if (function_exists('q_log'))
            q_log('forms.php: create error: ' . $e->getMessage());
        // Preserve behavior: redirect back with an error message
        form_redirect('index.php?page=questions&error=' . urlencode($e->getMessage()));
    }
}

// Bulk upload handler (file input 'bulk_file')
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['bulk_file'])) {
    try {
        $uploadedFile = $_FILES['bulk_file'];
        $fileExtension = strtolower(pathinfo($uploadedFile['name'], PATHINFO_EXTENSION));

        if (!in_array($fileExtension, ['csv', 'xlsx'])) {
            throw new Exception('Invalid file type. Please upload CSV or Excel file.');
        }

        // Parse the uploaded file
        if ($fileExtension === 'csv') {
            $questions = parseSimpleCSV($uploadedFile['tmp_name']);
        } elseif ($fileExtension === 'xlsx') {
            $questions = parseExcelFile($uploadedFile['tmp_name']);
        } else {
            $questions = [];
        }

        if (empty($questions)) {
            throw new Exception('No valid questions found in the uploaded file.');
        }

        // Use central import function which preserves i18n and other option shapes
        if (!function_exists('importQuestionsToDatabase')) {
            // Ensure parser/import helpers are available
            $parseImportFile = dirname(__DIR__) . '/functions/parse_and_import.php';
            if (file_exists($parseImportFile))
                require_once $parseImportFile;
        }

        if (function_exists('importQuestionsToDatabase')) {
            $importResult = importQuestionsToDatabase($questions, $db);
            $message = "Successfully added {$importResult['success_count']} questions";
            if (!empty($importResult['errors'])) {
                $message .= ". Errors: " . implode('; ', $importResult['errors']);
            }
            form_redirect('index.php?page=questions&success=bulk_added&message=' . urlencode($message));
        } else {
            // Fallback: redirect with a generic message
            form_redirect('index.php?page=questions&error=bulk_add_failed');
        }
    } catch (Exception $e) {
        if (function_exists('q_log'))
            q_log('forms.php: bulk upload error: ' . $e->getMessage());
        form_redirect('index.php?page=questions&error=bulk_add_failed');
    }
}

// Edit question handler
// Trigger when POST contains question_id (edit form). Accept either explicit POST 'action' === 'edit' or presence of question_id.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['question_id'])) {
    try {
        if (function_exists('q_log'))
            q_log('forms.php: edit question requested for id=' . ($_POST['question_id'] ?? ''));
        $question_id = intval($_POST['question_id']);
        $subject = trim($_POST['subject'] ?? '');
        $new_subject = trim($_POST['new_subject'] ?? '');
        if (!empty($new_subject))
            $subject = $new_subject;
        if (empty($subject))
            throw new Exception("Please select or enter a subject.");

        $topic = $_POST['topic'] ?? '';
        $subtopic = $_POST['subtopic'] ?? '';
        $question_text = $_POST['question_text_en'] ?? $_POST['question_text'] ?? '';
        $question_type = $_POST['question_type'] ?? 'text_mcq';
        $passage = $_POST['passage'] ?? '';
        $difficulty = $_POST['difficulty'] ?? 'medium';
        $exam_year = intval($_POST['exam_year'] ?? date('Y'));
        $source = $_POST['source'] ?? '';
        $explanation = $_POST['explanation'] ?? '';
        $is_public = isset($_POST['is_public']) ? 1 : 0;

        // Get existing question data for comparison
        $stmt = $db->prepare("SELECT * FROM question_banks WHERE id = ?");
        $stmt->execute([$question_id]);
        $existingQuestion = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$existingQuestion) {
            throw new Exception("Question not found.");
        }

        // Parse existing options
        $options = json_decode($existingQuestion['options'], true) ?: [];

        // Handle correct answer based on question type
        $correct_answer = '';
        if ($question_type === 'multi_correct') {
            $correct_answer = implode(',', $_POST['correct_answers'] ?? []);
        } elseif ($question_type === 'fill_blank') {
            $correct_answer = $_POST['blanks'] ?? '';
        } elseif ($question_type === 'match') {
            $correct_answer = json_encode([
                'left' => $_POST['match_left'] ?? [],
                'right' => $_POST['match_right'] ?? []
            ]);
        } else {
            $correct_answer = $_POST['correct_answer'] ?? '';
        }

        // Handle media uploads (image / audio)
        $uploadDir = dirname(__DIR__) . '/images/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $options_arr = $options; // Start with existing options

        if (!empty($_FILES['image']['name'])) {
            $res = handle_upload($_FILES['image'], $uploadDir, 'images/', 'qimg_', ['jpg', 'jpeg', 'png', 'gif', 'webp']);
            if ($res)
                $options_arr['image'] = $res;
        } elseif (!empty($existingQuestion['image'])) {
            // Keep existing image if no new image uploaded
            $options_arr['image'] = $existingQuestion['image'];
        }

        if (!empty($_FILES['audio']['name'])) {
            $res = handle_upload($_FILES['audio'], $uploadDir, 'images/', 'qaud_', ['mp3', 'wav', 'm4a', 'ogg']);
            if ($res)
                $options_arr['audio'] = $res;
        } elseif (!empty($options['audio'])) {
            // Keep existing audio if no new audio uploaded
            $options_arr['audio'] = $options['audio'];
        }

        // Per-option image uploads (option A..E)
        $optionImageMap = [
            'option_a_image' => 'A_image',
            'option_b_image' => 'B_image',
            'option_c_image' => 'C_image',
            'option_d_image' => 'D_image',
            'option_e_image' => 'E_image'
        ];

        foreach ($optionImageMap as $inputName => $optKey) {
            if (!empty($_FILES[$inputName]['name'])) {
                $res = handle_upload($_FILES[$inputName], $uploadDir, 'images/', 'optimg_', ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                if ($res)
                    $options_arr[$optKey] = $res;
            } elseif (!empty($options[$optKey])) {
                // Keep existing option image if no new image uploaded
                $options_arr[$optKey] = $options[$optKey];
            }
        }

        // Multilingual support: collect per-language fields and store under options->i18n
        // Merge with existing i18n to avoid overwriting translations that are not present in the form.
        $langs = ['en' => 'English', 'ta' => 'Tamil', 'hi' => 'Hindi'];
        $new_i18n = [];
        foreach ($langs as $code => $label) {
            $qt_name = 'question_text_' . $code;
            if (isset($_POST[$qt_name]) && !empty(trim($_POST[$qt_name]))) {
                $new_i18n[$code]['question_text'] = trim($_POST[$qt_name]);
            }

            // Per-option multilingual text - store as keys 'A','B','C'... to match create handler and common preview shapes
            foreach (['a', 'b', 'c', 'd', 'e'] as $opt) {
                $opt_name = 'option_' . $opt . '_' . $code;
                if (isset($_POST[$opt_name]) && !empty(trim($_POST[$opt_name]))) {
                    $new_i18n[$code][strtoupper($opt)] = trim($_POST[$opt_name]);
                }
            }
        }

        if (!empty($new_i18n)) {
            // Merge with existing i18n if present so we don't drop existing translations for languages/options not submitted in the form
            $existing_i18n = [];
            if (!empty($options['i18n']) && is_array($options['i18n'])) {
                $existing_i18n = $options['i18n'];
            }
            $options_arr['i18n'] = array_replace_recursive($existing_i18n, $new_i18n);
        }

        // Store traditional options A-E (using English as default)
        // Only overwrite top-level A-E when the form provided non-empty English option text.
        foreach (['A', 'B', 'C', 'D', 'E'] as $key) {
            $field_name = 'option_' . strtolower($key) . '_en';
            if (isset($_POST[$field_name]) && !empty(trim($_POST[$field_name]))) {
                $options_arr[$key] = trim($_POST[$field_name]);
            } else {
                // keep existing value if present (do not unset)
                if (isset($options[$key]) && !isset($options_arr[$key])) {
                    $options_arr[$key] = $options[$key];
                }
            }
        }

        $options_json = json_encode($options_arr, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        if ($options_json === false) {
            throw new Exception('Failed to encode options JSON: ' . json_last_error_msg());
        }

        // Update question in database
        $stmt = $db->prepare("UPDATE question_banks SET 
                subject = ?, topic = ?, subtopic = ?, question_text = ?, options = ?, 
                correct_answer = ?, explanation = ?, difficulty = ?, exam_year = ?, 
                source = ?, image = ?, is_public = ? WHERE id = ?");

        $stmt->execute([
            $subject,
            $topic,
            $subtopic,
            $question_text,
            $options_json,
            $correct_answer,
            $explanation,
            $difficulty,
            $exam_year,
            $source,
            $options_arr['image'] ?? null,
            $is_public,
            $question_id
        ]);

        form_redirect('index.php?page=questions&success=updated');
    } catch (Exception $e) {
        form_redirect('index.php?page=questions&error=' . urlencode($e->getMessage()));
    }
}
