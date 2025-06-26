<?php
$user = getCurrentUser();
$action = $_GET['action'] ?? 'list';
$perPage = 10;
$pageNum = intval($_GET['page_num'] ?? 1);
$offset = ($pageNum - 1) * $perPage;

// ✅ Role Flags
$isSuperAdmin = ($user['role'] === 'super_admin');
$isAdminOrSuperAdmin = in_array($user['role'], ['admin', 'super_admin']);

// ===================== 1. Pagination - Test Listing Based on Role =====================
$tests = [];
$totalRows = 0;

if ($isAdminOrSuperAdmin) {
    $totalRows = $db->query("SELECT COUNT(*) FROM test_packs")->fetchColumn();
    $stmt = $db->prepare("SELECT * FROM test_packs ORDER BY id DESC LIMIT ? OFFSET ?");
    $stmt->bindValue(1, $perPage, PDO::PARAM_INT);
    $stmt->bindValue(2, $offset, PDO::PARAM_INT);
    $stmt->execute();
    $tests = $stmt->fetchAll(PDO::FETCH_ASSOC);
} elseif ($user['role'] === 'vendor') {
    $totalStmt = $db->prepare("SELECT COUNT(*) FROM test_packs WHERE institute_id = ?");
    $totalStmt->execute([$user['id']]);
    $totalRows = $totalStmt->fetchColumn();

    $stmt = $db->prepare("SELECT * FROM test_packs WHERE institute_id = ? ORDER BY id DESC LIMIT ? OFFSET ?");
    $stmt->bindValue(1, $user['id'], PDO::PARAM_INT);
    $stmt->bindValue(2, $perPage, PDO::PARAM_INT);
    $stmt->bindValue(3, $offset, PDO::PARAM_INT);
    $stmt->execute();
    $tests = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $totalRows = $db->query("SELECT COUNT(*) FROM test_packs WHERE is_active = 1")->fetchColumn();
    $stmt = $db->prepare("SELECT * FROM test_packs WHERE is_active = 1 ORDER BY id DESC LIMIT ? OFFSET ?");
    $stmt->bindValue(1, $perPage, PDO::PARAM_INT);
    $stmt->bindValue(2, $offset, PDO::PARAM_INT);
    $stmt->execute();
    $tests = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// ===================== 2. Handle Create / Edit Form Submission =====================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $title = $_POST['title'] ?? '';
        $description = $_POST['description'] ?? '';
        $price = floatval($_POST['price'] ?? 0);
        $mrp = floatval($_POST['mrp'] ?? 0);
        $test_type = $_POST['test_type'] ?? 'mock';
        $timer_type = $_POST['timer_type'] ?? 'full_test';
        $duration = intval($_POST['duration_minutes'] ?? 60);
        $is_active = isset($_POST['is_active']) ? 1 : 0;
        $cover_image_path = null;

        // ✅ Handle Cover Image Upload
        if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'assets/images/';
            if (!file_exists($uploadDir)) mkdir($uploadDir, 0777, true);
            $filename = time() . '_' . basename($_FILES['cover_image']['name']);
            $targetFile = $uploadDir . $filename;

            if (move_uploaded_file($_FILES['cover_image']['tmp_name'], $targetFile)) {
                $cover_image_path = $targetFile;
            }
        }

        // ✅ Create Test Pack
        if ($action === 'create') {
            $created_at = date('Y-m-d H:i:s');
            $stmt = $db->prepare("INSERT INTO test_packs (title, description, price, mrp, test_type, timer_type, duration_minutes, institute_id, is_active, created_at, cover_image) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $title, $description, $price, $mrp, $test_type, $timer_type, $duration, $user['id'], $is_active, $created_at, $cover_image_path
            ]);
            $success = "Test pack created successfully!";
            $action = 'list';
        }

        // ✅ Edit Test Pack
        if ($action === 'edit') {
            $test_id = intval($_GET['id'] ?? 0);
            if ($test_id > 0) {
                // Role-based fetch for security
                if ($isAdminOrSuperAdmin) {
                    $stmt = $db->prepare("SELECT * FROM test_packs WHERE id = ?");
                    $stmt->execute([$test_id]);
                } else {
                    $stmt = $db->prepare("SELECT * FROM test_packs WHERE id = ? AND institute_id = ?");
                    $stmt->execute([$test_id, $user['id']]);
                }

                $existingTest = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($existingTest) {
                    if (!$cover_image_path) {
                        $cover_image_path = $existingTest['cover_image'];
                    } else {
                        if (!empty($existingTest['cover_image']) && file_exists($existingTest['cover_image'])) unlink($existingTest['cover_image']);
                    }

                    $stmt = $db->prepare("UPDATE test_packs SET title = ?, description = ?, price = ?, mrp = ?, test_type = ?, timer_type = ?, duration_minutes = ?, is_active = ?, cover_image = ? WHERE id = ?");
                    $stmt->execute([
                        $title, $description, $price, $mrp, $test_type, $timer_type, $duration, $is_active, $cover_image_path, $test_id
                    ]);

                    $success = "Test pack updated successfully!";
                    $action = 'list';
                } else {
                    $error = "Test pack not found or unauthorized.";
                    $action = 'list';
                }
            }
        }
    } catch (Exception $e) {
        $error = "Error: " . $e->getMessage();
    }
}

// ===================== 3. Fetch Test for Edit Prefill =====================
$editTest = null;
if ($action === 'edit' && isset($_GET['id'])) {
    $test_id = intval($_GET['id']);

    if ($isAdminOrSuperAdmin) {
        $stmt = $db->prepare("SELECT * FROM test_packs WHERE id = ?");
        $stmt->execute([$test_id]);
    } else {
        $stmt = $db->prepare("SELECT * FROM test_packs WHERE id = ? AND institute_id = ?");
        $stmt->execute([$test_id, $user['id']]);
    }

    $editTest = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$editTest) {
        $error = "Test pack not found.";
        $action = 'list';
    }
}

// ===================== 4. Handle Delete =====================
if ($action === 'delete' && isset($_GET['id'])) {
    $test_id = intval($_GET['id']);

    if ($isAdminOrSuperAdmin) {
        $stmt = $db->prepare("SELECT * FROM test_packs WHERE id = ?");
        $stmt->execute([$test_id]);
    } else {
        $stmt = $db->prepare("SELECT * FROM test_packs WHERE id = ? AND institute_id = ?");
        $stmt->execute([$test_id, $user['id']]);
    }

    $testToDelete = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($testToDelete) {
        if (!empty($testToDelete['cover_image']) && file_exists($testToDelete['cover_image'])) unlink($testToDelete['cover_image']);

        if ($isAdminOrSuperAdmin) {
            $stmt = $db->prepare("DELETE FROM test_packs WHERE id = ?");
            $stmt->execute([$test_id]);
        } else {
            $stmt = $db->prepare("DELETE FROM test_packs WHERE id = ? AND institute_id = ?");
            $stmt->execute([$test_id, $user['id']]);
        }

        $success = "Test pack deleted successfully!";
        $action = 'list';
    } else {
        $error = "Test pack not found or unauthorized.";
        $action = 'list';
    }
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
                <button class="btn btn-success me-2"
                    onclick="window.location.href='index.php?page=tests&action=instant'">
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
                        <th>Cover</th>
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
                            <?php if (!empty($test['cover_image'])): ?>
                            <img src="<?= htmlspecialchars($test['cover_image']) ?>" alt="Cover Image"
                                style="height: 50px; width: auto; border-radius: 5px;">
                            <?php else: ?>
                            <span class="text-muted">No Image</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div>
                                <strong><?= htmlspecialchars($test['title']) ?></strong>
                                <?php if ($test['description']): ?>
                                <br><small
                                    class="text-muted"><?= htmlspecialchars(substr($test['description'], 0, 50)) ?>...</small>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td>
                            <span
                                class="badge bg-<?= $test['test_type'] === 'mock' ? 'primary' : ($test['test_type'] === 'real' ? 'danger' : 'warning') ?>">
                                <?= ucfirst($test['test_type']) ?>
                            </span>
                        </td>
                        <td><?= $test['duration_minutes'] ?> min</td>
                        <td>
                            ₹<?= number_format($test['price']) ?>
                            <?php if ($test['mrp'] > $test['price']): ?>
                            <br><small
                                class="text-muted text-decoration-line-through">₹<?= number_format($test['mrp']) ?></small>
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
                                <button class="btn btn-outline-primary" title="Edit"
                                    onclick="window.location.href='index.php?page=tests&action=edit&id=<?= $test['id'] ?>'">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-outline-success" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-outline-info" title="Copy Link">
                                    <i class="fas fa-link"></i>
                                </button>
                                <button class="btn btn-outline-danger" title="Delete"
                                    onclick="if(confirm('Are you sure you want to delete this test pack?')) { window.location.href='index.php?page=tests&action=delete&id=<?= $test['id'] ?>'; }">
                                    <i class="fas fa-trash"></i>
                                </button>

                            </div>

                        </td>
                    </tr>
                    <?php endforeach; ?>

                    <?php if (empty($tests)): ?>
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <!-- Adjust colspan -->
                            <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No tests found</h5>
                            <p class="text-muted">Create your first test pack to get started.</p>
                            <button class="btn btn-primary"
                                onclick="window.location.href='index.php?page=tests&action=create'">
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

<?php
$totalPages = ceil($totalRows / $perPage);
if ($totalPages > 1): ?>
<nav class="mt-4">
    <ul class="pagination justify-content-center">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <li class="page-item <?= ($i == $pageNum) ? 'active' : '' ?>">
            <a class="page-link" href="index.php?page=tests&page_num=<?= $i ?>">
                <?= $i ?>
            </a>
        </li>
        <?php endfor; ?>
    </ul>
</nav>
<?php endif; ?>


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
                <form method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
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

                    <!-- ✅ New Cover Image Upload Field -->
                    <div class="mb-3">
                        <label for="cover_image" class="form-label">Cover Image (optional)</label>
                        <input type="file" class="form-control" id="cover_image" name="cover_image" accept="image/*">
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
                                <input type="number" class="form-control" id="duration_minutes" name="duration_minutes"
                                    value="60" min="1">
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
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                        checked>
                                    <label class="form-check-label" for="is_active">
                                        Active (Visible to students)
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary"
                            onclick="window.location.href='index.php?page=tests'">
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

<?php elseif ($action === 'edit'): ?>
<!-- Edit Test Pack Form -->
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-edit me-2"></i>Edit Test Pack
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>

                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="title" class="form-label">Test Pack Title</label>
                                <input type="text" class="form-control" id="title" name="title" required
                                    value="<?= htmlspecialchars($editTest['title'] ?? '') ?>">
                                <div class="invalid-feedback">Please provide a test title.</div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="test_type" class="form-label">Test Type</label>
                                <select class="form-select" id="test_type" name="test_type" required>
                                    <option value="mock"
                                        <?= (isset($editTest) && $editTest['test_type'] == 'mock') ? 'selected' : '' ?>>
                                        Mock Test</option>
                                    <option value="real"
                                        <?= (isset($editTest) && $editTest['test_type'] == 'real') ? 'selected' : '' ?>>
                                        Proctored Test</option>
                                    <option value="instant"
                                        <?= (isset($editTest) && $editTest['test_type'] == 'instant') ? 'selected' : '' ?>>
                                        Instant Test</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description"
                            rows="3"><?= htmlspecialchars($editTest['description'] ?? '') ?></textarea>
                    </div>

                    <!-- ✅ Current Cover Image Display -->
                    <?php if (!empty($editTest['cover_image'])): ?>
                    <div class="mb-3">
                        <label class="form-label">Current Cover Image:</label><br>
                        <img src="<?= htmlspecialchars($editTest['cover_image']) ?>" alt="Cover Image"
                            style="height: 80px; border-radius: 5px;">
                    </div>
                    <?php endif; ?>

                    <!-- ✅ Upload New Cover Image -->
                    <div class="mb-3">
                        <label for="cover_image" class="form-label">Upload New Cover Image (optional)</label>
                        <input type="file" class="form-control" id="cover_image" name="cover_image" accept="image/*">
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="price" class="form-label">Selling Price (₹)</label>
                                <input type="number" class="form-control" id="price" name="price" min="0" step="0.01"
                                    value="<?= htmlspecialchars($editTest['price'] ?? '') ?>">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="mrp" class="form-label">MRP (₹)</label>
                                <input type="number" class="form-control" id="mrp" name="mrp" min="0" step="0.01"
                                    value="<?= htmlspecialchars($editTest['mrp'] ?? '') ?>">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="duration_minutes" class="form-label">Duration (Minutes)</label>
                                <input type="number" class="form-control" id="duration_minutes" name="duration_minutes"
                                    min="1" value="<?= htmlspecialchars($editTest['duration_minutes'] ?? 60) ?>">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="timer_type" class="form-label">Timer Type</label>
                                <select class="form-select" id="timer_type" name="timer_type">
                                    <option value="full_test"
                                        <?= (isset($editTest) && $editTest['timer_type'] == 'full_test') ? 'selected' : '' ?>>
                                        Full Test Timer</option>
                                    <option value="per_question"
                                        <?= (isset($editTest) && $editTest['timer_type'] == 'per_question') ? 'selected' : '' ?>>
                                        Per Question Timer</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="form-check form-switch mt-4">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                        <?= (!empty($editTest['is_active'])) ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="is_active">
                                        Active (Visible to students)
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary"
                            onclick="window.location.href='index.php?page=tests'">
                            <i class="fas fa-arrow-left me-2"></i>Back to Tests
                        </button>
                        <div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-check me-2"></i>Update Test Pack
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