<?php
// Test Results - Student's test results and performance analytics
$user = getCurrentUser();
if ($user['role'] !== 'student') {
    header('Location: index.php?page=dashboard');
    exit;
}

$studentId = $user['id'];

// Generate demo results data
$results = [
    [
        'test_id' => 1,
        'test_title' => 'TNPSC Group 2 - Mock Test 1',
        'test_type' => 'mock',
        'date_taken' => '2024-11-28',
        'score' => 85,
        'total_marks' => 100,
        'time_taken' => 55,
        'total_time' => 60,
        'correct' => 85,
        'wrong' => 10,
        'unattempted' => 5,
        'rank' => 15,
        'total_students' => 250
    ],
    [
        'test_id' => 2,
        'test_title' => 'UPSC Prelims - History Section',
        'test_type' => 'real',
        'date_taken' => '2024-11-25',
        'score' => 72,
        'total_marks' => 100,
        'time_taken' => 58,
        'total_time' => 60,
        'correct' => 72,
        'wrong' => 18,
        'unattempted' => 10,
        'rank' => 45,
        'total_students' => 300
    ],
    [
        'test_id' => 3,
        'test_title' => 'SSC CGL - Quantitative Aptitude',
        'test_type' => 'mock',
        'date_taken' => '2024-11-20',
        'score' => 90,
        'total_marks' => 100,
        'time_taken' => 45,
        'total_time' => 60,
        'correct' => 90,
        'wrong' => 5,
        'unattempted' => 5,
        'rank' => 8,
        'total_students' => 180
    ]
];

// Calculate overall stats
$totalTests = count($results);
$avgScore = array_sum(array_column($results, 'score')) / $totalTests;
$highestScore = max(array_column($results, 'score'));
$lowestScore = min(array_column($results, 'score'));
?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="text-gradient mb-0"><i class="fas fa-chart-bar me-2"></i>My Results</h1>
                <p class="text-muted">Track your performance and progress</p>
            </div>
            <div>
                <button class="btn btn-outline-primary" onclick="window.print()">
                    <i class="fas fa-print me-2"></i>Print Report
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Overall Statistics -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="stats-card primary">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stats-number"><?= $totalTests ?></div>
                    <div class="stats-label">Tests Taken</div>
                </div>
                <i class="fas fa-clipboard-check fa-2x opacity-75"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card success">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stats-number"><?= round($avgScore) ?>%</div>
                    <div class="stats-label">Average Score</div>
                </div>
                <i class="fas fa-chart-line fa-2x opacity-75"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card warning">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stats-number"><?= $highestScore ?>%</div>
                    <div class="stats-label">Highest Score</div>
                </div>
                <i class="fas fa-trophy fa-2x opacity-75"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card danger">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stats-number"><?= $lowestScore ?>%</div>
                    <div class="stats-label">Lowest Score</div>
                </div>
                <i class="fas fa-chart-pie fa-2x opacity-75"></i>
            </div>
        </div>
    </div>
</div>

<!-- Performance Chart -->
<div class="row mb-4">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-chart-area me-2"></i>Performance Trend</h5>
            </div>
            <div class="card-body">
                <canvas id="performanceChart" height="80"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-bullseye me-2"></i>Accuracy</h5>
            </div>
            <div class="card-body">
                <canvas id="accuracyChart"></canvas>
                <div class="text-center mt-3">
                    <h3 class="text-success mb-0"><?= round($avgScore) ?>%</h3>
                    <small class="text-muted">Overall Accuracy</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Test Results Table -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-list me-2"></i>Detailed Results</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Test Name</th>
                        <th>Date</th>
                        <th>Score</th>
                        <th>Time</th>
                        <th>Rank</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($results as $result): ?>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <span class="badge bg-<?= $result['test_type'] === 'mock' ? 'primary' : 'danger' ?> me-2">
                                    <?= strtoupper($result['test_type']) ?>
                                </span>
                                <strong><?= htmlspecialchars($result['test_title']) ?></strong>
                            </div>
                        </td>
                        <td><?= date('d M Y', strtotime($result['date_taken'])) ?></td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="progress" style="width: 60px; height: 8px; margin-right: 8px;">
                                    <div class="progress-bar bg-<?= $result['score'] >= 80 ? 'success' : ($result['score'] >= 60 ? 'warning' : 'danger') ?>" 
                                         style="width: <?= $result['score'] ?>%"></div>
                                </div>
                                <strong class="text-<?= $result['score'] >= 80 ? 'success' : ($result['score'] >= 60 ? 'warning' : 'danger') ?>">
                                    <?= $result['score'] ?>%
                                </strong>
                            </div>
                        </td>
                        <td>
                            <i class="fas fa-clock me-1"></i><?= $result['time_taken'] ?>/<?= $result['total_time'] ?> min
                        </td>
                        <td>
                            <span class="badge bg-<?= $result['rank'] <= 10 ? 'success' : ($result['rank'] <= 50 ? 'primary' : 'secondary') ?>">
                                #<?= $result['rank'] ?> / <?= $result['total_students'] ?>
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-success">
                                <i class="fas fa-check me-1"></i>Completed
                            </span>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-outline-primary" onclick="viewAnalysis(<?= $result['test_id'] ?>)">
                                    <i class="fas fa-chart-bar"></i>
                                </button>
                                <button class="btn btn-outline-success" onclick="viewSolutions(<?= $result['test_id'] ?>)">
                                    <i class="fas fa-book"></i>
                                </button>
                                <button class="btn btn-outline-info" onclick="retakeTest(<?= $result['test_id'] ?>)">
                                    <i class="fas fa-redo"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Performance Trend Chart
const ctx1 = document.getElementById('performanceChart').getContext('2d');
new Chart(ctx1, {
    type: 'line',
    data: {
        labels: <?= json_encode(array_column($results, 'test_title')) ?>,
        datasets: [{
            label: 'Score %',
            data: <?= json_encode(array_column($results, 'score')) ?>,
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.1)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                max: 100
            }
        }
    }
});

// Accuracy Chart
const ctx2 = document.getElementById('accuracyChart').getContext('2d');
new Chart(ctx2, {
    type: 'doughnut',
    data: {
        labels: ['Correct', 'Wrong', 'Unattempted'],
        datasets: [{
            data: [
                <?= array_sum(array_column($results, 'correct')) ?>,
                <?= array_sum(array_column($results, 'wrong')) ?>,
                <?= array_sum(array_column($results, 'unattempted')) ?>
            ],
            backgroundColor: ['#28a745', '#dc3545', '#6c757d']
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true
    }
});

function viewAnalysis(testId) {
    alert('Detailed analysis for test #' + testId + ' will be shown here');
}

function viewSolutions(testId) {
    alert('Solutions for test #' + testId + ' will be shown here');
}

function retakeTest(testId) {
    if (confirm('Do you want to retake this test?')) {
        window.location.href = 'index.php?page=take_test&id=' + testId;
    }
}
</script>
