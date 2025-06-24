<?php
$user = getCurrentUser();
$action = $_GET['action'] ?? 'list';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'create' || $action === 'edit') {
        try {
            $title = $_POST['title'] ?? '';
            $description = $_POST['description'] ?? '';
            $price = floatval($_POST['price'] ?? 0);
            $mrp = floatval($_POST['mrp'] ?? 0);
            $test_type = $_POST['test_type'] ?? 'mock';
            $timer_type = $_POST['timer_type'] ?? 'full_test';
            $duration = intval($_POST['duration_minutes'] ?? 60);
            $is_active = isset($_POST['is_active']) ? 1 : 0;
            
            if ($action === 'create') {
                $stmt = $db->prepare("INSERT INTO test_packs (title, description, price, mrp, test_type, timer_type, duration_minutes, institute_id, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$title, $description, $price, $mrp, $test_type, $timer_type, $duration, $user['institute_id'], $is_active]);
                $success = "Test pack created successfully!";
            }
            
        } catch (Exception $e) {
            $error = "Error: " . $e->getMessage();
        }
    }
}

// Fetch test packs
$tests = [];
try {
    $stmt = $db->prepare("SELECT * FROM test_packs WHERE institute_id = ? OR ? = 'super_admin' ORDER BY created_at DESC");
    $stmt->execute([$user['institute_id'], $user['role']]);
    $tests = $stmt->fetchAll();
} catch (Exception $e) {
    error_log("Tests fetch error: " . $e->getMessage());
}
?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="text-gradient mb-0">Test Management</h1>
                <p class="text-muted">Create and manage test packs, mock tests, and proctored exams</p>
            </div>
            <div>
                <button class="btn btn-success me-2" onclick="window.location.href='index.php?page=tests&action=instant'">
                    <i class="fas fa-lightning-bolt me-2"></i>Instant Test Builder
                </button>
                <button class="btn btn-primary" onclick="window.location.href='index.php?page=tests&action=create'">
                    <i class="fas fa-plus me-2"></i>Create Test Pack
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
    <i class="fas fa-exclamation-triangle me-2"></i><?= htmlspecialchars($error) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<?php if ($action === 'list'): ?>
<!-- Test Management Dashboard -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-clipboard-list fa-3x text-primary mb-3"></i>
                <h5>Mock Tests</h5>
                <p class="text-muted">Practice tests for students</p>
                <button class="btn btn-outline-primary">Manage</button>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-video fa-3x text-danger mb-3"></i>
                <h5>Proctored Tests</h5>
                <p class="text-muted">Real-time monitored exams</p>
                <button class="btn btn-outline-danger">Monitor</button>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-bolt fa-3x text-warning mb-3"></i>
                <h5>Instant Tests</h5>
                <p class="text-muted">Quick test generation</p>
                <button class="btn btn-outline-warning">Build</button>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-print fa-3x text-success mb-3"></i>
                <h5>Print Tests</h5>
                <p class="text-muted">Downloadable test papers</p>
                <button class="btn btn-outline-success">Generate</button>
            </div>
        </div>
    </div>
</div>

<!-- Tests List -->
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-list me-2"></i>All Test Packs
            </h5>
            <div>
                <input type="text" class="form-control" placeholder="Search tests..." data-search=".test-row">
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Test Name</th>
                        <th>Type</th>
                        <th>Duration</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tests as $test): ?>
                    <tr class="test-row">
                        <td>
                            <div>
                                <strong><?= htmlspecialchars($test['title']) ?></strong>
                                <?php if ($test['description']): ?>
                                <br><small class="text-muted"><?= htmlspecialchars(substr($test['description'], 0, 50)) ?>...</small>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-<?= $test['test_type'] === 'mock' ? 'primary' : ($test['test_type'] === 'real' ? 'danger' : 'warning') ?>">
                                <?= ucfirst($test['test_type']) ?>
                            </span>
                        </td>
                        <td><?= $test['duration_minutes'] ?> min</td>
                        <td>
                            ₹<?= number_format($test['price']) ?>
                            <?php if ($test['mrp'] > $test['price']): ?>
                            <br><small class="text-muted text-decoration-line-through">₹<?= number_format($test['mrp']) ?></small>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="badge bg-<?= $test['is_active'] ? 'success' : 'secondary' ?>">
                                <?= $test['is_active'] ? 'Active' : 'Inactive' ?>
                            </span>
                        </td>
                        <td><?= date('M d, Y', strtotime($test['created_at'])) ?></td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-outline-primary" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-outline-success" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-outline-info" title="Copy Link">
                                    <i class="fas fa-link"></i>
                                </button>
                                <button class="btn btn-outline-danger" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    
                    <?php if (empty($tests)): ?>
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No tests found</h5>
                            <p class="text-muted">Create your first test pack to get started.</p>
                            <button class="btn btn-primary" onclick="window.location.href='index.php?page=tests&action=create'">
                                <i class="fas fa-plus me-2"></i>Create Test Pack
                            </button>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php elseif ($action === 'create'): ?>
<!-- Create Test Pack Form -->
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-plus me-2"></i>Create New Test Pack
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" class="needs-validation" novalidate>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="title" class="form-label">Test Pack Title</label>
                                <input type="text" class="form-control" id="title" name="title" required>
                                <div class="invalid-feedback">Please provide a test title.</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="test_type" class="form-label">Test Type</label>
                                <select class="form-select" id="test_type" name="test_type" required>
                                    <option value="mock">Mock Test</option>
                                    <option value="real">Proctored Test</option>
                                    <option value="instant">Instant Test</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="price" class="form-label">Selling Price (₹)</label>
                                <input type="number" class="form-control" id="price" name="price" min="0" step="0.01">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="mrp" class="form-label">MRP (₹)</label>
                                <input type="number" class="form-control" id="mrp" name="mrp" min="0" step="0.01">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="duration_minutes" class="form-label">Duration (Minutes)</label>
                                <input type="number" class="form-control" id="duration_minutes" name="duration_minutes" value="60" min="1">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="timer_type" class="form-label">Timer Type</label>
                                <select class="form-select" id="timer_type" name="timer_type">
                                    <option value="full_test">Full Test Timer</option>
                                    <option value="per_question">Per Question Timer</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="form-check form-switch mt-4">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" checked>
                                    <label class="form-check-label" for="is_active">
                                        Active (Visible to students)
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary" onclick="window.location.href='index.php?page=tests'">
                            <i class="fas fa-arrow-left me-2"></i>Back to Tests
                        </button>
                        <div>
                            <button type="button" class="btn btn-outline-primary me-2">
                                <i class="fas fa-save me-2"></i>Save as Draft
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-check me-2"></i>Create Test Pack
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php elseif ($action === 'instant'): ?>
<!-- Instant Test Builder -->
<div class="row">
    <div class="col-lg-8">
        <div class="test-builder">
            <h3 class="mb-4">
                <i class="fas fa-lightning-bolt text-warning me-2"></i>
                Instant Test Builder
            </h3>
            
            <!-- Question Selection Panel -->
            <div class="question-selector mb-4">
                <h5>Select Questions</h5>
                
                <div class="row">
                    <div class="col-md-4">
                        <select class="form-select mb-3">
                            <option>Select Subject</option>
                            <option>Mathematics</option>
                            <option>Science</option>
                            <option>English</option>
                            <option>History</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select class="form-select mb-3">
                            <option>Select Topic</option>
                            <option>Algebra</option>
                            <option>Geometry</option>
                            <option>Statistics</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select class="form-select mb-3">
                            <option>Difficulty Level</option>
                            <option>Easy</option>
                            <option>Medium</option>
                            <option>Hard</option>
                        </select>
                    </div>
                </div>
                
                <button class="btn btn-primary">
                    <i class="fas fa-search me-2"></i>Search Questions
                </button>
            </div>
            
            <!-- Selected Questions Area -->
            <div class="selected-questions" id="selected-questions">
                <i class="fas fa-plus-circle fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Selected Questions (<span id="question-count">0</span>)</h5>
                <p class="text-muted">Questions will appear here as you select them</p>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card sticky-top">
            <div class="card-header">
                <h5 class="mb-0">Test Configuration</h5>
            </div>
            <div class="card-body">
                <form>
                    <div class="mb-3">
                        <label class="form-label">Test Title</label>
                        <input type="text" class="form-control" placeholder="Enter test title">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Duration (Minutes)</label>
                        <input type="number" class="form-control" value="60">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Timer Type</label>
                        <select class="form-select">
                            <option>Full Test Timer</option>
                            <option>Per Question Timer</option>
                        </select>
                    </div>
                    
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="randomize">
                        <label class="form-check-label" for="randomize">
                            Randomize Questions
                        </label>
                    </div>
                    
                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" id="show_results">
                        <label class="form-check-label" for="show_results">
                            Show Results Immediately
                        </label>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-success">
                            <i class="fas fa-rocket me-2"></i>Generate Test Link
                        </button>
                        <button type="button" class="btn btn-outline-primary">
                            <i class="fas fa-print me-2"></i>Generate PDF
                        </button>
                        <button type="button" class="btn btn-outline-secondary">
                            <i class="fas fa-save me-2"></i>Save as Template
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php endif; ?>