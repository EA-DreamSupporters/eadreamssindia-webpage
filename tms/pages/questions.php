<?php
$user = getCurrentUser();
$action = $_GET['action'] ?? 'list';
if (isset($_GET['success'])) {
    if ($_GET['success'] === 'created') $success = "Question added successfully!";
    elseif ($_GET['success'] === 'updated') $success = "Question updated successfully!";
    elseif ($_GET['success'] === 'deleted') $success = "Question deleted successfully!";
}


// Delete Action
if ($action === 'delete' && isset($_GET['id'])) {
    $q_id = intval($_GET['id']);
    $stmt = $db->prepare("DELETE FROM question_banks WHERE id = ?");
    $stmt->execute([$q_id]);
    header("Location: index.php?page=questions&success=deleted");
    exit;
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
            '', $subject, $topic, $subtopic, $question_text, $options, $correct_answer, $explanation, $difficulty, $exam_year, $source, $is_public, $user['institute_id'], $created_at
        ]);

        header("Location: index.php?page=questions&success=1");
        exit;
    } catch (Exception $e) {
        $error = "Error: " . $e->getMessage();
    }
}

// Update question on edit
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

        // Redirect after update to prevent resubmission
        header("Location: index.php?page=questions&success=updated");
        exit;

    } catch (Exception $e) {
        $error = "Error updating: " . $e->getMessage();
    }
}


// Fetch subjects and topics for filters
$allSubjects = $db->query("SELECT DISTINCT subject FROM question_banks WHERE subject IS NOT NULL AND subject != ''")->fetchAll(PDO::FETCH_COLUMN);
$allTopics = $db->query("SELECT DISTINCT topic FROM question_banks WHERE topic IS NOT NULL AND topic != ''")->fetchAll(PDO::FETCH_COLUMN);
$allSources = $db->query("SELECT DISTINCT source FROM question_banks WHERE source IS NOT NULL AND source != ''")->fetchAll(PDO::FETCH_COLUMN);


// Fetch questions for list
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
                    <div class="stats-number"><?= count(array_filter($questions, fn($q) => $q['is_public'])) ?></div>
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
                    <div class="stats-number"><?= count(array_unique(array_column($questions, 'subject'))) ?></div>
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
                    <div class="stats-number"><?= count(array_unique(array_column($questions, 'topic'))) ?></div>
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

                        <h6 class="question-text"><?= htmlspecialchars($question['question_text']) ?></h6>

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
                            <button class="btn btn-sm btn-outline-success" title="Preview">
                                <i class="fas fa-eye"></i>
                            </button>

                            <!-- Edit -->
                            <button class="btn btn-sm btn-outline-primary" title="Edit"
                                onclick="window.location.href='index.php?page=questions&action=edit&id=<?= $question['id'] ?>'">
                                <i class="fas fa-edit"></i>
                            </button>

                            <!-- Add to Test -->
                            <button class="btn btn-sm btn-outline-info" title="Add to Test"
                                onclick="TMS.addQuestionToTest(<?= $question['id'] ?>, '<?= htmlspecialchars($question['question_text']) ?>')">
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

<!-- Bulk Actions -->
<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <span class="text-muted">Selected: <span id="selected-count">0</span> questions</span>
            </div>
            <div>
                <button class="btn btn-outline-primary me-2">
                    <i class="fas fa-copy me-2"></i>Duplicate Selected
                </button>
                <button class="btn btn-outline-success me-2">
                    <i class="fas fa-plus me-2"></i>Add to Test
                </button>
                <button class="btn btn-outline-danger">
                    <i class="fas fa-trash me-2"></i>Delete Selected
                </button>
            </div>
        </div>
    </div>
</div>

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

                <form method="POST" class="needs-validation" novalidate>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="subject" class="form-label">Select Subject</label>
                                <select class="form-select" id="subject" name="subject">
                                    <option value="">-- Select Existing Subject --</option>
                                    <?php foreach ($allSubjects as $s): ?>
                                    <option value="<?= htmlspecialchars($s) ?>"><?= htmlspecialchars($s) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="new_subject" class="form-label">Or Add New Subject</label>
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
                                <label for="correct_answer" class="form-label">Correct Answer</label>
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
                            <button type="button" class="btn btn-outline-primary me-2">
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
                                <label for="new_subject" class="form-label">Or Add New Subject</label>
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
                                <label for="correct_answer" class="form-label">Correct Answer</label>
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
                    <i class="fas fa-upload me-2"></i>Bulk Question Upload
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Upload Methods</h6>
                        <div class="list-group">
                            <div class="list-group-item">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">Excel/CSV Upload</h6>
                                    <small class="text-success">Recommended</small>
                                </div>
                                <p class="mb-1">Upload questions using our Excel template</p>
                                <small>Supports bulk upload with proper formatting</small>
                            </div>

                            <div class="list-group-item">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">Question Paper Upload</h6>
                                    <small class="text-info">AI Powered</small>
                                </div>
                                <p class="mb-1">Upload PDF question papers for auto-extraction</p>
                                <small>AI will extract and categorize questions</small>
                            </div>

                            <div class="list-group-item">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">Text Import</h6>
                                    <small>Manual</small>
                                </div>
                                <p class="mb-1">Copy-paste questions in structured format</p>
                                <small>For quick manual entry</small>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <h6>Upload Area</h6>
                        <div class="border border-dashed border-primary rounded p-5 text-center">
                            <i class="fas fa-cloud-upload-alt fa-3x text-primary mb-3"></i>
                            <h5>Drag & Drop Files Here</h5>
                            <p class="text-muted">or click to browse</p>
                            <button class="btn btn-primary">
                                <i class="fas fa-file me-2"></i>Choose Files
                            </button>
                        </div>

                        <div class="mt-3">
                            <small class="text-muted">
                                Supported formats: .xlsx, .csv, .pdf, .txt<br>
                                Maximum file size: 10MB
                            </small>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-12">
                        <h6>Template & Instructions</h6>
                        <div class="bg-light p-3 rounded">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>Download Excel Template</strong>
                                    <br><small class="text-muted">Pre-formatted template with sample questions</small>
                                </div>
                                <button class="btn btn-success">
                                    <i class="fas fa-download me-2"></i>Download Template
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php endif; ?>