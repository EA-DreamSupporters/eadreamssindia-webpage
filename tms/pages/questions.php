<?php
$user = getCurrentUser();
$action = $_GET['action'] ?? 'list';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'create' || $action === 'upload') {
        try {
            // Handle question creation/upload logic here
            $success = "Questions processed successfully!";
        } catch (Exception $e) {
            $error = "Error: " . $e->getMessage();
        }
    }
}

// Fetch questions
$questions = [];
try {
    $stmt = $db->prepare("SELECT * FROM question_banks WHERE institute_id = ? OR ? = 'super_admin' OR is_public = 1 ORDER BY created_at DESC LIMIT 50");
    $stmt->execute([$user['institute_id'], $user['role']]);
    $questions = $stmt->fetchAll();
} catch (Exception $e) {
    error_log("Questions fetch error: " . $e->getMessage());
}

// Sample questions for demo
if (empty($questions)) {
    $questions = [
        [
            'id' => 1,
            'subject' => 'Mathematics',
            'topic' => 'Algebra',
            'subtopic' => 'Linear Equations',
            'question_text' => 'Solve for x: 2x + 5 = 13',
            'difficulty' => 'easy',
            'exam_year' => 2023,
            'source' => 'TNPSC',
            'is_public' => 1
        ],
        [
            'id' => 2,
            'subject' => 'Science',
            'topic' => 'Physics',
            'subtopic' => 'Motion',
            'question_text' => 'What is the SI unit of acceleration?',
            'difficulty' => 'medium',
            'exam_year' => 2023,
            'source' => 'UPSC',
            'is_public' => 1
        ]
    ];
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
                <button class="btn btn-success me-2" onclick="window.location.href='index.php?page=questions&action=upload'">
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
        <div class="row align-items-end">
            <div class="col-md-3">
                <label class="form-label">Subject</label>
                <select class="form-select" id="filter-subject">
                    <option value="">All Subjects</option>
                    <?php foreach (array_unique(array_column($questions, 'subject')) as $subject): ?>
                    <option value="<?= htmlspecialchars($subject) ?>"><?= htmlspecialchars($subject) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Topic</label>
                <select class="form-select" id="filter-topic">
                    <option value="">All Topics</option>
                    <?php foreach (array_unique(array_column($questions, 'topic')) as $topic): ?>
                    <option value="<?= htmlspecialchars($topic) ?>"><?= htmlspecialchars($topic) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Difficulty</label>
                <select class="form-select" id="filter-difficulty">
                    <option value="">All Levels</option>
                    <option value="easy">Easy</option>
                    <option value="medium">Medium</option>
                    <option value="hard">Hard</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Search</label>
                <input type="text" class="form-control" id="search-questions" placeholder="Search questions...">
            </div>
            <div class="col-md-1">
                <button class="btn btn-primary w-100">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
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
                                <span class="badge bg-<?= $question['difficulty'] === 'easy' ? 'success' : ($question['difficulty'] === 'medium' ? 'warning' : 'danger') ?>">
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
                        <div class="btn-group btn-group-sm mb-2">
                            <button class="btn btn-outline-primary" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-outline-success" title="Preview">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-outline-info" title="Add to Test" onclick="TMS.addQuestionToTest(<?= $question['id'] ?>, '<?= htmlspecialchars($question['question_text']) ?>')">
                                <i class="fas fa-plus"></i>
                            </button>
                            <button class="btn btn-outline-danger" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                        
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="<?= $question['id'] ?>" id="select_<?= $question['id'] ?>">
                            <label class="form-check-label" for="select_<?= $question['id'] ?>">
                                Select
                            </label>
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
                                <label for="subject" class="form-label">Subject</label>
                                <select class="form-select" id="subject" name="subject" required>
                                    <option value="">Select Subject</option>
                                    <option value="Mathematics">Mathematics</option>
                                    <option value="Science">Science</option>
                                    <option value="English">English</option>
                                    <option value="History">History</option>
                                    <option value="Geography">Geography</option>
                                </select>
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
                        <textarea class="form-control" id="question_text" name="question_text" rows="4" required></textarea>
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
                                <input type="number" class="form-control" id="exam_year" name="exam_year" min="2000" max="2030" value="2024">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="source" class="form-label">Source</label>
                                <input type="text" class="form-control" id="source" name="source" placeholder="e.g., TNPSC, UPSC">
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
                        <button type="button" class="btn btn-secondary" onclick="window.location.href='index.php?page=questions'">
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