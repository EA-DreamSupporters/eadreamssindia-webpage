<?php
// Practice - Quick practice sessions without time limits
$user = getCurrentUser();
if ($user['role'] !== 'student') {
    header('Location: index.php?page=dashboard');
    exit;
}

// Get available subjects
$subjects = ['General Knowledge', 'Mathematics', 'English', 'Reasoning', 'Current Affairs', 'History', 'Geography', 'Science'];
$difficulties = ['easy' => 'Easy', 'medium' => 'Medium', 'hard' => 'Hard'];
?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="text-gradient mb-0"><i class="fas fa-pencil-alt me-2"></i>Practice Mode</h1>
                <p class="text-muted">Practice anytime, anywhere - No time limits</p>
            </div>
        </div>
    </div>
</div>

<!-- Quick Practice Stats -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="stats-card primary">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stats-number"><?= rand(50, 200) ?></div>
                    <div class="stats-label">Questions Practiced</div>
                </div>
                <i class="fas fa-question-circle fa-2x opacity-75"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stats-card success">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stats-number"><?= rand(5, 15) ?></div>
                    <div class="stats-label">Practice Sessions</div>
                </div>
                <i class="fas fa-history fa-2x opacity-75"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stats-card warning">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stats-number"><?= rand(70, 90) ?>%</div>
                    <div class="stats-label">Accuracy</div>
                </div>
                <i class="fas fa-bullseye fa-2x opacity-75"></i>
            </div>
        </div>
    </div>
</div>

<!-- Practice Options -->
<div class="row">
    <!-- Quick Practice -->
    <div class="col-lg-6 mb-4">
        <div class="card h-100">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Quick Practice</h5>
            </div>
            <div class="card-body">
                <form id="quickPracticeForm">
                    <div class="mb-3">
                        <label class="form-label">Subject</label>
                        <select class="form-select" name="subject" required>
                            <option value="">Select Subject</option>
                            <?php foreach ($subjects as $subject): ?>
                            <option value="<?= htmlspecialchars($subject) ?>"><?= htmlspecialchars($subject) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Difficulty Level</label>
                        <select class="form-select" name="difficulty" required>
                            <option value="">Select Difficulty</option>
                            <?php foreach ($difficulties as $key => $label): ?>
                            <option value="<?= $key ?>"><?= $label ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Number of Questions</label>
                        <select class="form-select" name="num_questions" required>
                            <option value="10">10 Questions</option>
                            <option value="20" selected>20 Questions</option>
                            <option value="30">30 Questions</option>
                            <option value="50">50 Questions</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-play me-2"></i>Start Practice
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Topic-wise Practice -->
    <div class="col-lg-6 mb-4">
        <div class="card h-100">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-book me-2"></i>Topic-wise Practice</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <input type="text" class="form-control" placeholder="Search topics..." id="searchTopics">
                </div>
                <div class="list-group" style="max-height: 400px; overflow-y: auto;">
                    <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" 
                       onclick="startTopicPractice('Algebra')">
                        <div>
                            <i class="fas fa-square-root-alt me-2 text-primary"></i>
                            <strong>Algebra</strong>
                            <br><small class="text-muted">50 questions available</small>
                        </div>
                        <span class="badge bg-primary rounded-pill">75%</span>
                    </a>
                    <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
                       onclick="startTopicPractice('Geometry')">
                        <div>
                            <i class="fas fa-shapes me-2 text-success"></i>
                            <strong>Geometry</strong>
                            <br><small class="text-muted">45 questions available</small>
                        </div>
                        <span class="badge bg-success rounded-pill">82%</span>
                    </a>
                    <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
                       onclick="startTopicPractice('English Grammar')">
                        <div>
                            <i class="fas fa-pen me-2 text-info"></i>
                            <strong>English Grammar</strong>
                            <br><small class="text-muted">60 questions available</small>
                        </div>
                        <span class="badge bg-info rounded-pill">68%</span>
                    </a>
                    <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
                       onclick="startTopicPractice('Logical Reasoning')">
                        <div>
                            <i class="fas fa-brain me-2 text-warning"></i>
                            <strong>Logical Reasoning</strong>
                            <br><small class="text-muted">55 questions available</small>
                        </div>
                        <span class="badge bg-warning rounded-pill">79%</span>
                    </a>
                    <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
                       onclick="startTopicPractice('Current Affairs')">
                        <div>
                            <i class="fas fa-newspaper me-2 text-danger"></i>
                            <strong>Current Affairs</strong>
                            <br><small class="text-muted">80 questions available</small>
                        </div>
                        <span class="badge bg-danger rounded-pill">71%</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Practice Sessions -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-history me-2"></i>Recent Practice Sessions</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Subject/Topic</th>
                        <th>Date</th>
                        <th>Questions</th>
                        <th>Correct</th>
                        <th>Accuracy</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>Mathematics - Algebra</strong></td>
                        <td><?= date('d M Y', strtotime('-1 day')) ?></td>
                        <td>20</td>
                        <td>16</td>
                        <td><span class="badge bg-success">80%</span></td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary" onclick="reviewSession(1)">
                                <i class="fas fa-eye"></i> Review
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>English - Grammar</strong></td>
                        <td><?= date('d M Y', strtotime('-2 days')) ?></td>
                        <td>30</td>
                        <td>21</td>
                        <td><span class="badge bg-warning">70%</span></td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary" onclick="reviewSession(2)">
                                <i class="fas fa-eye"></i> Review
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>General Knowledge</strong></td>
                        <td><?= date('d M Y', strtotime('-3 days')) ?></td>
                        <td>10</td>
                        <td>9</td>
                        <td><span class="badge bg-success">90%</span></td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary" onclick="reviewSession(3)">
                                <i class="fas fa-eye"></i> Review
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
document.getElementById('quickPracticeForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const params = new URLSearchParams(formData);
    alert('Starting practice session with:\n' + 
          'Subject: ' + formData.get('subject') + '\n' +
          'Difficulty: ' + formData.get('difficulty') + '\n' +
          'Questions: ' + formData.get('num_questions'));
    // TODO: Navigate to practice session page
    // window.location.href = 'index.php?page=take_test&mode=practice&' + params.toString();
});

function startTopicPractice(topic) {
    alert('Starting practice for topic: ' + topic);
    // TODO: Navigate to practice session page
    // window.location.href = 'index.php?page=take_test&mode=practice&topic=' + encodeURIComponent(topic);
}

function reviewSession(sessionId) {
    alert('Reviewing practice session #' + sessionId);
    // TODO: Show session review page
}

// Search topics
document.getElementById('searchTopics').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    document.querySelectorAll('.list-group-item').forEach(item => {
        const text = item.textContent.toLowerCase();
        item.style.display = text.includes(searchTerm) ? 'flex' : 'none';
    });
});
</script>
