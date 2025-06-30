<?php
require_once 'config/database.php';
require_once 'includes/auth.php';

$user = getCurrentUser();
$testId = intval($_GET['id'] ?? 0);

if (!$testId) {
    echo "<div class='alert alert-danger'>Invalid Test ID.</div>";
    exit;
}

// Fetch Test with Institute Info
$stmt = $db->prepare("
    SELECT tp.*, i.name AS institute_name
    FROM test_packs tp
    LEFT JOIN institutions i ON i.id = tp.institute_id
    WHERE tp.id = ?
");
$stmt->execute([$testId]);
$test = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$test) {
    echo "<div class='alert alert-warning'>Test not found.</div>";
    exit;
}

// Permission Check: Students can only see Active + Visible tests
if ($user['role'] === 'student') {
    if ($test['is_active'] != 1 || $test['is_visible_to_students'] != 1) {
        echo "<div class='alert alert-danger'>You are not allowed to view this test.</div>";
        exit;
    }
}

// Now render Test Details Page
?>

<div class="card">
    <div class="card-header">
        <h3 class="fw-bold"><?= htmlspecialchars($test['title']) ?></h3>
        <p class="mb-0 fw-light">Created on <?= date('M d, Y', strtotime($test['created_at'])) ?> by
            <?= htmlspecialchars($test['institute_name'] ?? 'EA Dream Supporters') ?></p>
    </div>
    <div class="card-body">
        <?php if (!empty($test['cover_image'])): ?>
        <img src="<?= htmlspecialchars($test['cover_image']) ?>" alt="Cover Image" class="img-fluid mb-3"
            style="max-height: 300px; border-radius: 10px;">
        <?php endif; ?>

        <p><strong>Type:</strong> <?= ucfirst($test['test_type']) ?></p>
        <p><strong>Duration:</strong> <?= intval($test['duration_minutes']) ?> minutes</p>
        <p><strong>Price:</strong> ₹<?= number_format($test['price']) ?>
            <?php if ($test['mrp'] > $test['price']): ?>
            <small class="text-muted text-decoration-line-through">₹<?= number_format($test['mrp']) ?></small>
            <?php endif; ?>
        </p>
        <p><strong>Status:</strong>
            <span class="badge bg-<?= $test['is_active'] ? 'success' : 'secondary' ?>">
                <?= $test['is_active'] ? 'Active' : 'Inactive' ?>
            </span>
        </p>

        <hr>
        <p><?= nl2br(htmlspecialchars($test['description'])) ?></p>

        <?php if ($user['role'] === 'super_admin' || $user['role'] === 'admin' || $user['role'] === 'vendor'): ?>
        <hr>
        <div>
            <a href="index.php?page=tests&action=edit&id=<?= $test['id'] ?>" class="btn btn-primary">
                <i class="fas fa-edit me-1"></i> Edit Test
            </a>
            <button class="btn btn-outline-info" onclick="copyLink(<?= $test['id'] ?>)">
                <i class="fas fa-link me-1"></i> Copy Link
            </button>
        </div>
        <script>
        function copyLink(testId) {
            const url = window.location.origin + "/index.php?page=test_details&id=" + testId;
            navigator.clipboard.writeText(url).then(function() {
                alert('Link copied: ' + url);
            });
        }
        </script>
        <?php endif; ?>
    </div>
</div>