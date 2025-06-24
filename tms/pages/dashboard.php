<?php
$user = getCurrentUser();

// Fetch dashboard statistics
$stats = [
    'total_tests' => 0,
    'active_students' => 0,
    'total_questions' => 0,
    'monthly_revenue' => 0
];

try {
    // Get total tests
    $stmt = $db->prepare("SELECT COUNT(*) FROM test_packs WHERE institute_id = ? OR ? = 'super_admin'");
    $stmt->execute([$user['institute_id'], $user['role']]);
    $stats['total_tests'] = $stmt->fetchColumn();
    
    // Get active students
    $stmt = $db->prepare("SELECT COUNT(*) FROM users WHERE role = 'student' AND (institute_id = ? OR ? = 'super_admin')");
    $stmt->execute([$user['institute_id'], $user['role']]);
    $stats['active_students'] = $stmt->fetchColumn();
    
    // Get total questions
    $stmt = $db->prepare("SELECT COUNT(*) FROM question_banks WHERE institute_id = ? OR ? = 'super_admin'");
    $stmt->execute([$user['institute_id'], $user['role']]);
    $stats['total_questions'] = $stmt->fetchColumn();
    
    // Calculate monthly revenue (mock data)
    $stats['monthly_revenue'] = rand(5000, 25000);
    
} catch (Exception $e) {
    error_log("Dashboard stats error: " . $e->getMessage());
}

// Recent activities
$recent_activities = [
    ['icon' => 'fas fa-plus-circle', 'text' => 'New test pack created: "TNPSC Prelims 2024"', 'time' => '2 hours ago', 'type' => 'success'],
    ['icon' => 'fas fa-users', 'text' => '5 new students registered', 'time' => '4 hours ago', 'type' => 'info'],
    ['icon' => 'fas fa-chart-line', 'text' => 'Analytics report generated', 'time' => '6 hours ago', 'type' => 'warning'],
    ['icon' => 'fas fa-question-circle', 'text' => '50 new questions added to bank', 'time' => '1 day ago', 'type' => 'primary']
];
?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="text-gradient mb-0">Dashboard</h1>
                <p class="text-muted">Welcome back, <?= htmlspecialchars($user['username']) ?>!</p>
            </div>
            <div>
                <button class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#quickActionModal">
                    <i class="fas fa-plus me-2"></i>Quick Action
                </button>
                <button class="btn btn-outline-secondary">
                    <i class="fas fa-download me-2"></i>Export Data
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
        <div class="stats-card primary">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stats-number"><?= number_format($stats['total_tests']) ?></div>
                    <div class="stats-label">Total Tests</div>
                </div>
                <div class="stats-icon">
                    <i class="fas fa-clipboard-list fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
        <div class="stats-card success">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stats-number"><?= number_format($stats['active_students']) ?></div>
                    <div class="stats-label">Active Students</div>
                </div>
                <div class="stats-icon">
                    <i class="fas fa-users fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
        <div class="stats-card warning">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stats-number"><?= number_format($stats['total_questions']) ?></div>
                    <div class="stats-label">Question Bank</div>
                </div>
                <div class="stats-icon">
                    <i class="fas fa-question-circle fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
        <div class="stats-card danger">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stats-number">₹<?= number_format($stats['monthly_revenue']) ?></div>
                    <div class="stats-label">Monthly Revenue</div>
                </div>
                <div class="stats-icon">
                    <i class="fas fa-rupee-sign fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Quick Actions -->
    <div class="col-lg-8 col-md-12 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-bolt me-2"></i>Quick Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <a href="index.php?page=tests&action=create" class="text-decoration-none">
                            <div class="quick-action-card">
                                <div class="text-center p-4 bg-primary bg-opacity-10 rounded hover-lift">
                                    <i class="fas fa-plus-circle fa-3x text-primary mb-3"></i>
                                    <h6 class="mb-2">Create Test Pack</h6>
                                    <p class="text-muted small mb-0">Build new test with questions</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    
                    <div class="col-md-4">
                        <a href="index.php?page=questions&action=instant" class="text-decoration-none">
                            <div class="quick-action-card">
                                <div class="text-center p-4 bg-success bg-opacity-10 rounded hover-lift">
                                    <i class="fas fa-lightning-bolt fa-3x text-success mb-3"></i>
                                    <h6 class="mb-2">Instant Test Builder</h6>
                                    <p class="text-muted small mb-0">Generate test instantly</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    
                    <div class="col-md-4">
                        <a href="index.php?page=questions&action=upload" class="text-decoration-none">
                            <div class="quick-action-card">
                                <div class="text-center p-4 bg-warning bg-opacity-10 rounded hover-lift">
                                    <i class="fas fa-upload fa-3x text-warning mb-3"></i>
                                    <h6 class="mb-2">Upload Questions</h6>
                                    <p class="text-muted small mb-0">Add questions to bank</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    
                    <div class="col-md-4">
                        <a href="index.php?page=analytics" class="text-decoration-none">
                            <div class="quick-action-card">
                                <div class="text-center p-4 bg-info bg-opacity-10 rounded hover-lift">
                                    <i class="fas fa-chart-bar fa-3x text-info mb-3"></i>
                                    <h6 class="mb-2">View Analytics</h6>
                                    <p class="text-muted small mb-0">Performance insights</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    
                    <div class="col-md-4">
                        <a href="index.php?page=students&action=manage" class="text-decoration-none">
                            <div class="quick-action-card">
                                <div class="text-center p-4 bg-secondary bg-opacity-10 rounded hover-lift">
                                    <i class="fas fa-user-graduate fa-3x text-secondary mb-3"></i>
                                    <h6 class="mb-2">Manage Students</h6>
                                    <p class="text-muted small mb-0">Student administration</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    
                    <div class="col-md-4">
                        <a href="index.php?page=tests&action=proctor" class="text-decoration-none">
                            <div class="quick-action-card">
                                <div class="text-center p-4 bg-danger bg-opacity-10 rounded hover-lift">
                                    <i class="fas fa-video fa-3x text-danger mb-3"></i>
                                    <h6 class="mb-2">Proctored Tests</h6>
                                    <p class="text-muted small mb-0">Monitor live tests</p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent Activity -->
    <div class="col-lg-4 col-md-12 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-history me-2"></i>Recent Activity
                </h5>
            </div>
            <div class="card-body">
                <div class="activity-feed">
                    <?php foreach ($recent_activities as $activity): ?>
                    <div class="activity-item d-flex align-items-start mb-3">
                        <div class="activity-icon me-3">
                            <i class="<?= $activity['icon'] ?> text-<?= $activity['type'] ?>"></i>
                        </div>
                        <div class="activity-content flex-grow-1">
                            <p class="mb-1 small"><?= htmlspecialchars($activity['text']) ?></p>
                            <small class="text-muted"><?= htmlspecialchars($activity['time']) ?></small>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="text-center mt-3">
                    <button class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-eye me-1"></i>View All Activity
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Performance Charts -->
<div class="row">
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-pie me-2"></i>Subject Distribution
                </h5>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="subjectChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-line me-2"></i>Performance Trend
                </h5>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="trendChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Action Modal -->
<div class="modal fade" id="quickActionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-bolt me-2"></i>Quick Action
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-6">
                        <button class="btn btn-outline-primary w-100 py-3">
                            <i class="fas fa-plus d-block mb-2"></i>
                            Create Test
                        </button>
                    </div>
                    <div class="col-6">
                        <button class="btn btn-outline-success w-100 py-3">
                            <i class="fas fa-upload d-block mb-2"></i>
                            Upload Questions
                        </button>
                    </div>
                    <div class="col-6">
                        <button class="btn btn-outline-warning w-100 py-3">
                            <i class="fas fa-user-plus d-block mb-2"></i>
                            Add Student
                        </button>
                    </div>
                    <div class="col-6">
                        <button class="btn btn-outline-info w-100 py-3">
                            <i class="fas fa-print d-block mb-2"></i>
                            Generate Report
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>