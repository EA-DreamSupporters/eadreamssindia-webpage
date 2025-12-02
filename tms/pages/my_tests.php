<?php
// My Tests - Student's enrolled/purchased tests
$user = getCurrentUser();
if ($user['role'] !== 'student') {
    header('Location: index.php?page=dashboard');
    exit;
}

$studentId = $user['id'];

// Fetch enrolled tests with session status
try {
    $stmt = $db->prepare("
        SELECT 
            tp.*,
            se.enrolled_at,
            se.payment_status,
            se.amount_paid,
            ts.id as session_id,
            ts.status as session_status,
            ts.score,
            ts.start_time,
            ts.end_time,
            (SELECT COUNT(*) FROM test_questions WHERE test_id = tp.id) as total_questions
        FROM student_enrollments se
        INNER JOIN test_packs tp ON se.test_pack_id = tp.id
        LEFT JOIN test_sessions ts ON ts.test_pack_id = tp.id AND ts.student_id = se.student_id
        WHERE se.student_id = ? AND se.payment_status = 'completed'
        ORDER BY se.enrolled_at DESC
    ");
    $stmt->execute([$studentId]);
    $myTests = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Count tests by status
    $totalTests = count($myTests);
    $notStarted = 0;
    $inProgress = 0;
    $completed = 0;
    
    foreach ($myTests as $test) {
        if (!$test['session_id']) {
            $notStarted++;
        } elseif ($test['session_status'] === 'completed') {
            $completed++;
        } elseif ($test['session_status'] === 'in_progress') {
            $inProgress++;
        } else {
            $notStarted++;
        }
    }
    
} catch (Exception $e) {
    error_log("My tests error: " . $e->getMessage());
    $myTests = [];
    $totalTests = 0;
    $notStarted = 0;
    $inProgress = 0;
    $completed = 0;
}
?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="text-gradient mb-0"><i class="fas fa-book-open me-2"></i>My Tests</h1>
                <p class="text-muted">Access and take your enrolled tests</p>
            </div>
            <a href="index.php?page=dashboard" class="btn btn-outline-primary">
                <i class="fas fa-shopping-cart me-2"></i>Browse More Tests
            </a>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="stats-card primary">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stats-number"><?= $totalTests ?></div>
                    <div class="stats-label">Total Enrolled</div>
                </div>
                <i class="fas fa-book fa-2x opacity-75"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stats-card warning">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stats-number"><?= $inProgress ?></div>
                    <div class="stats-label">In Progress</div>
                </div>
                <i class="fas fa-hourglass-half fa-2x opacity-75"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stats-card success">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stats-number"><?= $completed ?></div>
                    <div class="stats-label">Completed</div>
                </div>
                <i class="fas fa-check-circle fa-2x opacity-75"></i>
            </div>
        </div>
    </div>
</div>

<!-- Tabs -->
<ul class="nav nav-tabs mb-4" id="myTab" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all" type="button">
            <i class="fas fa-list me-1"></i>All Tests
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending" type="button">
            <i class="fas fa-clock me-1"></i>Not Started
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="progress-tab" data-bs-toggle="tab" data-bs-target="#progress" type="button">
            <i class="fas fa-spinner me-1"></i>In Progress
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="completed-tab" data-bs-toggle="tab" data-bs-target="#completed" type="button">
            <i class="fas fa-check me-1"></i>Completed
        </button>
    </li>
</ul>

<div class="tab-content" id="myTabContent">
    <!-- All Tests -->
    <div class="tab-pane fade show active" id="all">
        <?php if (empty($myTests)): ?>
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i>You haven't enrolled in any tests yet.
            <a href="index.php?page=dashboard" class="alert-link">Browse available tests</a>
        </div>
        <?php else: ?>
        <div class="row">
            <?php foreach ($myTests as $test): ?>
            <?php
            // Determine test status
            if (!$test['session_id']) {
                $status = 'not_started';
                $progress = 0;
            } elseif ($test['session_status'] === 'completed') {
                $status = 'completed';
                $progress = 100;
            } elseif ($test['session_status'] === 'in_progress') {
                $status = 'in_progress';
                $progress = 50; // Can calculate based on answered questions
            } else {
                $status = 'not_started';
                $progress = 0;
            }
            
            $statusBadge = [
                'not_started' => '<span class="badge bg-secondary">Not Started</span>',
                'in_progress' => '<span class="badge bg-warning">In Progress</span>',
                'completed' => '<span class="badge bg-success">Completed</span>'
            ][$status];
            ?>
            <div class="col-lg-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <span
                                    class="badge bg-<?= $test['test_type'] === 'mock' ? 'primary' : ($test['test_type'] === 'real' ? 'danger' : 'info') ?> me-2">
                                    <?= strtoupper($test['test_type'] ?? 'TEST') ?>
                                </span>
                                <?php if (!empty($test['is_proctored'])): ?>
                                <span class="badge bg-warning">PROCTORED</span>
                                <?php endif; ?>
                            </div>
                            <?= $statusBadge ?>
                        </div>

                        <h5 class="card-title mb-3"><?= htmlspecialchars($test['title']) ?></h5>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-2">
                                <small class="text-muted"><i
                                        class="fas fa-question-circle me-1"></i><?= $test['total_questions'] ?? 50 ?>
                                    Questions</small>
                                <small class="text-muted"><i
                                        class="fas fa-clock me-1"></i><?= $test['duration_minutes'] ?? 60 ?>
                                    mins</small>
                            </div>
                            <?php if ($progress > 0): ?>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-<?= $status === 'completed' ? 'success' : 'warning' ?>"
                                    style="width: <?= $progress ?>%"></div>
                            </div>
                            <small class="text-muted"><?= $progress ?>% Complete</small>
                            <?php endif; ?>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <a href="index.php?page=test_details&id=<?= $test['id'] ?>"
                                class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-info-circle me-1"></i>Details
                            </a>
                            <?php if ($status === 'completed'): ?>
                            <a href="index.php?page=test_results&test_id=<?= $test['id'] ?>"
                                class="btn btn-success btn-sm">
                                <i class="fas fa-chart-bar me-1"></i>View Results
                            </a>
                            <?php elseif ($status === 'in_progress'): ?>
                            <a href="index.php?page=take_test&id=<?= $test['id'] ?>" class="btn btn-warning btn-sm">
                                <i class="fas fa-play me-1"></i>Resume Test
                            </a>
                            <?php else: ?>
                            <a href="index.php?page=take_test&id=<?= $test['id'] ?>" class="btn btn-primary btn-sm">
                                <i class="fas fa-play me-1"></i>Start Test
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>

    <!-- Not Started -->
    <div class="tab-pane fade" id="pending">
        <div class="alert alert-info">
            <i class="fas fa-clock me-2"></i>Tests you haven't started yet will appear here.
        </div>
    </div>

    <!-- In Progress -->
    <div class="tab-pane fade" id="progress">
        <div class="alert alert-warning">
            <i class="fas fa-spinner me-2"></i>Tests you've started but not completed will appear here.
        </div>
    </div>

    <!-- Completed -->
    <div class="tab-pane fade" id="completed">
        <div class="alert alert-success">
            <i class="fas fa-check me-2"></i>Your completed tests will appear here.
        </div>
    </div>
</div>