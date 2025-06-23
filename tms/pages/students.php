<?php
$user = getCurrentUser();
$action = $_GET['action'] ?? 'list';

// Fetch students
$students = [];
try {
    $stmt = $db->prepare("SELECT * FROM users WHERE role = 'student' AND (institute_id = ? OR ? = 'super_admin') ORDER BY created_at DESC");
    $stmt->execute([$user['institute_id'], $user['role']]);
    $students = $stmt->fetchAll();
} catch (Exception $e) {
    error_log("Students fetch error: " . $e->getMessage());
}

// Sample data for demo
if (empty($students)) {
    $students = [
        [
            'id' => 1,
            'username' => 'john_doe',
            'email' => 'john@example.com',
            'created_at' => '2024-01-15 10:30:00',
            'institute_id' => 1
        ],
        [
            'id' => 2,
            'username' => 'jane_smith',
            'email' => 'jane@example.com',
            'created_at' => '2024-01-20 14:45:00',
            'institute_id' => 1
        ]
    ];
}
?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="text-gradient mb-0">Student Management</h1>
                <p class="text-muted">Manage student accounts, performance, and test assignments</p>
            </div>
            <div>
                <button class="btn btn-success me-2">
                    <i class="fas fa-upload me-2"></i>Bulk Import
                </button>
                <button class="btn btn-primary">
                    <i class="fas fa-user-plus me-2"></i>Add Student
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Student Statistics -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="stats-card primary">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stats-number"><?= count($students) ?></div>
                    <div class="stats-label">Total Students</div>
                </div>
                <i class="fas fa-users fa-2x opacity-75"></i>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="stats-card success">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stats-number"><?= rand(60, 90) ?>%</div>
                    <div class="stats-label">Active Students</div>
                </div>
                <i class="fas fa-user-check fa-2x opacity-75"></i>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="stats-card warning">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stats-number"><?= rand(70, 85) ?>%</div>
                    <div class="stats-label">Avg. Score</div>
                </div>
                <i class="fas fa-chart-line fa-2x opacity-75"></i>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="stats-card danger">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stats-number"><?= rand(150, 300) ?></div>
                    <div class="stats-label">Tests Taken</div>
                </div>
                <i class="fas fa-clipboard-check fa-2x opacity-75"></i>
            </div>
        </div>
    </div>
</div>

<!-- Students List -->
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-users me-2"></i>All Students
            </h5>
            <div class="d-flex gap-2">
                <select class="form-select form-select-sm">
                    <option>All Status</option>
                    <option>Active</option>
                    <option>Inactive</option>
                </select>
                <input type="text" class="form-control form-control-sm" placeholder="Search students...">
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Email</th>
                        <th>Tests Taken</th>
                        <th>Avg. Score</th>
                        <th>Last Activity</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($students as $student): ?>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="student-avatar me-3">
                                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <span class="text-white fw-bold"><?= strtoupper(substr($student['username'], 0, 2)) ?></span>
                                    </div>
                                </div>
                                <div>
                                    <strong><?= htmlspecialchars($student['username']) ?></strong>
                                    <br><small class="text-muted">ID: <?= $student['id'] ?></small>
                                </div>
                            </div>
                        </td>
                        <td><?= htmlspecialchars($student['email']) ?></td>
                        <td>
                            <span class="badge bg-primary"><?= rand(5, 25) ?></span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <?php $score = rand(60, 95); ?>
                                <span class="me-2"><?= $score ?>%</span>
                                <div class="progress flex-grow-1" style="height: 6px;">
                                    <div class="progress-bar bg-<?= $score >= 80 ? 'success' : ($score >= 60 ? 'warning' : 'danger') ?>" style="width: <?= $score ?>%"></div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <small class="text-muted"><?= date('M d, Y', strtotime($student['created_at'])) ?></small>
                        </td>
                        <td>
                            <span class="badge bg-success">Active</span>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-outline-primary" title="View Profile">
                                    <i class="fas fa-user"></i>
                                </button>
                                <button class="btn btn-outline-success" title="Performance">
                                    <i class="fas fa-chart-bar"></i>
                                </button>
                                <button class="btn btn-outline-info" title="Assign Test">
                                    <i class="fas fa-clipboard-list"></i>
                                </button>
                                <button class="btn btn-outline-warning" title="Send Message">
                                    <i class="fas fa-envelope"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    
                    <?php if (empty($students)): ?>
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No students found</h5>
                            <p class="text-muted">Add students to start managing their test performance.</p>
                            <button class="btn btn-primary">
                                <i class="fas fa-user-plus me-2"></i>Add First Student
                            </button>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Student Performance Overview -->
<div class="row mt-4">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-area me-2"></i>Performance Overview
                </h5>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="performanceChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-trophy me-2"></i>Top Performers
                </h5>
            </div>
            <div class="card-body">
                <div class="top-performer mb-3 d-flex align-items-center">
                    <div class="rank-badge me-3">
                        <span class="badge bg-warning rounded-pill">1</span>
                    </div>
                    <div class="flex-grow-1">
                        <strong>John Doe</strong>
                        <br><small class="text-muted">Average: 94%</small>
                    </div>
                    <div class="score">
                        <i class="fas fa-trophy text-warning"></i>
                    </div>
                </div>
                
                <div class="top-performer mb-3 d-flex align-items-center">
                    <div class="rank-badge me-3">
                        <span class="badge bg-secondary rounded-pill">2</span>
                    </div>
                    <div class="flex-grow-1">
                        <strong>Jane Smith</strong>
                        <br><small class="text-muted">Average: 91%</small>
                    </div>
                    <div class="score">
                        <i class="fas fa-medal text-secondary"></i>
                    </div>
                </div>
                
                <div class="top-performer mb-3 d-flex align-items-center">
                    <div class="rank-badge me-3">
                        <span class="badge bg-warning rounded-pill">3</span>
                    </div>
                    <div class="flex-grow-1">
                        <strong>Mike Johnson</strong>
                        <br><small class="text-muted">Average: 88%</small>
                    </div>
                    <div class="score">
                        <i class="fas fa-award text-warning"></i>
                    </div>
                </div>
                
                <div class="text-center mt-3">
                    <button class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-list me-1"></i>View Full Leaderboard
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mt-4">
    <div class="col-md-3">
        <div class="card text-center hover-lift">
            <div class="card-body">
                <i class="fas fa-user-plus fa-3x text-primary mb-3"></i>
                <h6>Add Students</h6>
                <p class="text-muted small">Register new students individually or in bulk</p>
                <button class="btn btn-outline-primary btn-sm">Add Now</button>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card text-center hover-lift">
            <div class="card-body">
                <i class="fas fa-clipboard-list fa-3x text-success mb-3"></i>
                <h6>Assign Tests</h6>
                <p class="text-muted small">Assign specific tests to students or groups</p>
                <button class="btn btn-outline-success btn-sm">Assign</button>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card text-center hover-lift">
            <div class="card-body">
                <i class="fas fa-chart-line fa-3x text-warning mb-3"></i>
                <h6>Performance Reports</h6>
                <p class="text-muted small">Generate detailed performance analytics</p>
                <button class="btn btn-outline-warning btn-sm">Generate</button>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card text-center hover-lift">
            <div class="card-body">
                <i class="fas fa-envelope fa-3x text-info mb-3"></i>
                <h6>Send Notifications</h6>
                <p class="text-muted small">Communicate with students via email/SMS</p>
                <button class="btn btn-outline-info btn-sm">Send</button>
            </div>
        </div>
    </div>
</div>

<script>
// Initialize performance chart
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('performanceChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4', 'Week 5', 'Week 6'],
                datasets: [{
                    label: 'Average Score',
                    data: [72, 75, 78, 76, 82, 85],
                    borderColor: '#0066CC',
                    backgroundColor: 'rgba(0, 102, 204, 0.1)',
                    tension: 0.4,
                    fill: true
                }, {
                    label: 'Participation Rate',
                    data: [85, 88, 82, 90, 87, 92],
                    borderColor: '#28A745',
                    backgroundColor: 'rgba(40, 167, 69, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100
                    }
                }
            }
        });
    }
});
</script>