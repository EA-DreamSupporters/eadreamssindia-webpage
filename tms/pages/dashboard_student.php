<?php
// Student Dashboard - Test Purchase and Online Test Taking Interface
// Fetch student statistics and available tests

$studentId = $user['id'];
$instituteId = $user['institute_id'] ?? 0;

// Get student statistics
try {
    // Count enrolled tests
    $enrolledStmt = $db->prepare("SELECT COUNT(*) FROM student_enrollments WHERE student_id = ? AND payment_status = 'completed'");
    $enrolledStmt->execute([$studentId]);
    $myTestsCount = $enrolledStmt->fetchColumn();
    
    // Count completed tests
    $completedStmt = $db->prepare("SELECT COUNT(*) FROM test_sessions WHERE student_id = ? AND status = 'completed'");
    $completedStmt->execute([$studentId]);
    $completedTests = $completedStmt->fetchColumn();
    
    // Get average score
    $avgScoreStmt = $db->prepare("SELECT AVG(score) FROM test_sessions WHERE student_id = ? AND status = 'completed' AND score IS NOT NULL");
    $avgScoreStmt->execute([$studentId]);
    $avgScore = round($avgScoreStmt->fetchColumn() ?? 0, 2);
    
    // Get available tests with enrollment status
    $availableTestsStmt = $db->prepare("
        SELECT tp.*,
               (SELECT COUNT(*) FROM student_enrollments WHERE test_pack_id = tp.id) as enrolled_students,
               (SELECT COUNT(*) FROM student_enrollments WHERE test_pack_id = tp.id AND student_id = ?) as is_enrolled,
               (SELECT COUNT(*) FROM test_questions WHERE test_id = tp.id) as total_questions
        FROM test_packs tp
        WHERE tp.is_active = 1 
        AND tp.is_visible_to_students = 1 
        AND (tp.institute_id = ? OR tp.institute_id = 0 OR tp.institute_id IS NULL)
        ORDER BY tp.created_at DESC
        LIMIT 20
    ");
    $availableTestsStmt->execute([$studentId, $instituteId]);
    $availableTests = $availableTestsStmt->fetchAll(PDO::FETCH_ASSOC);
    
    $availableTestsCount = count($availableTests);
    
} catch (Exception $e) {
    error_log("Student dashboard error: " . $e->getMessage());
    $availableTests = [];
    $availableTestsCount = 0;
}

// Get test categories/subjects
$subjects = [];
foreach ($availableTests as $test) {
    $subject = $test['test_category'] ?? 'General';
    if (!in_array($subject, $subjects)) {
        $subjects[] = $subject;
    }
}
?>

<style>
.test-card {
    transition: all 0.3s ease;
    border: 1px solid rgba(255, 255, 255, 0.1);
    background: rgba(255, 255, 255, 0.8);
    backdrop-filter: blur(10px);
    width: 300px;
    height: auto;
    display: flex;
    flex-direction: column;
}

.test-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
    border-color: rgba(var(--bs-primary-rgb), 0.5);
}

.test-badge {
    padding: 0.2rem 0.6rem;
    border-radius: 16px;
    font-size: 0.7rem;
    font-weight: 600;
}

.test-price {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--bs-success);
}

.test-meta {
    font-size: 0.75rem;
    color: rgba(49, 49, 49, 0.9);
}

.test-card .card-title {
    color: #292929;
    font-size: 0.95rem;
    margin-bottom: 0.5rem !important;
    line-height: 1.3;
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
}

.test-card .card-body {
    color: #333333;
    padding: 0.75rem;
    flex: 1;
    display: flex;
    flex-direction: column;
}

.test-card .card-img-top {
    height: 180px;
    object-fit: cover;
    border-top-left-radius: inherit;
    border-top-right-radius: inherit;
}

.test-card .card-body .btn {
    padding: 0.3rem 0.5rem;
    font-size: 0.8rem;
}

.test-card .card-body>div:last-child {
    margin-top: auto;
}

#testsContainer {
    margin-right: 900px;
}

.category-chip {
    display: inline-block;
    padding: 0.5rem 1rem;
    margin: 0.25rem;
    border-radius: 20px;
    border: 2px solid rgba(255, 255, 255, 0.2);
    background: rgba(255, 255, 255, 0.05);
    cursor: pointer;
    transition: all 0.3s;
}

.category-chip:hover,
.category-chip.active {
    background: var(--bs-primary);
    border-color: var(--bs-primary);
}

.hero-section {
    background: linear-gradient(135deg, rgba(var(--bs-primary-rgb), 0.2) 0%, rgba(var(--bs-info-rgb), 0.2) 100%);
    border-radius: 15px;
    padding: 2rem;
    margin-bottom: 2rem;
}

.hero-section .h2,
.hero-section small {
    color: #ffffff;
    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
}

.progress-ring-modern {
    width: 160px;
    height: 160px;
    position: relative;
    display: inline-block;
}

.progress-circle {
    transition: stroke-dashoffset 1s ease-in-out;
    filter: drop-shadow(0 0 8px rgba(16, 185, 129, 0.4));
}

.pulse-ring {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 140px;
    height: 140px;
    border-radius: 50%;
    border: 2px solid rgba(16, 185, 129, 0.3);
    animation: pulse 2s infinite;
    pointer-events: none;
}

@keyframes pulse {

    0%,
    100% {
        transform: translate(-50%, -50%) scale(1);
        opacity: 1;
    }

    50% {
        transform: translate(-50%, -50%) scale(1.1);
        opacity: 0.5;
    }
}
</style>

<!-- Hero Section -->
<div class="hero-section">
    <div class="row align-items-center">
        <div class="col-lg-8">
            <h1 class="display-5 fw-bold text-gradient mb-3">
                <i class="fas fa-graduation-cap me-2"></i>Welcome, <?= htmlspecialchars($user['username']) ?>!
            </h1>
            <p class="lead mb-4">Ready to ace your exams? Choose from our expert-designed test packs.</p>
            <div class="d-flex gap-2">
                <a href="#available-tests" class="btn btn-primary btn-lg">
                    <i class="fas fa-shopping-cart me-2"></i>Browse Tests
                </a>
                <a href="#my-tests" class="btn btn-outline-light btn-lg">
                    <i class="fas fa-book me-2"></i>My Tests
                </a>
            </div>
        </div>
        <div class="col-lg-4 text-center">
            <div class="progress-ring-modern mx-auto position-relative">
                <svg width="160" height="160" viewBox="0 0 160 160">
                    <!-- Background circle -->
                    <circle cx="80" cy="80" r="70" stroke="rgba(255,255,255,0.1)" stroke-width="12" fill="none" />

                    <!-- Progress circle -->
                    <circle cx="80" cy="80" r="70" stroke="url(#gradient)" stroke-width="12" fill="none"
                        stroke-dasharray="440" stroke-dashoffset="<?= 440 - (440 * $avgScore / 100) ?>"
                        transform="rotate(-90 80 80)" stroke-linecap="round" class="progress-circle" />

                    <!-- Gradient definition -->
                    <defs>
                        <linearGradient id="gradient" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" style="stop-color:#10b981;stop-opacity:1" />
                            <stop offset="100%" style="stop-color:#3b82f6;stop-opacity:1" />
                        </linearGradient>
                    </defs>

                    <!-- Center content -->
                    <text x="80" y="70" text-anchor="middle" font-size="32" font-weight="bold" fill="#ffffff">
                        <?= round($avgScore) ?>%
                    </text>
                    <text x="80" y="90" text-anchor="middle" font-size="12" fill="rgba(255,255,255,0.8)">
                        Avg Score
                    </text>
                    <text x="80" y="105" text-anchor="middle" font-size="10" fill="rgba(255,255,255,0.6)">
                        <?= $completedTests ?> test<?= $completedTests != 1 ? 's' : '' ?> completed
                    </text>
                </svg>

                <!-- Animated pulse effect -->
                <div class="pulse-ring"></div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Stats -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="stats-card primary">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stats-number"><?= $myTestsCount ?></div>
                    <div class="stats-label">My Tests</div>
                </div>
                <i class="fas fa-book-open fa-2x opacity-75"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card success">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stats-number"><?= $completedTests ?></div>
                    <div class="stats-label">Completed</div>
                </div>
                <i class="fas fa-check-circle fa-2x opacity-75"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card info">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stats-number"><?= $avgScore ?>%</div>
                    <div class="stats-label">Avg Score</div>
                </div>
                <i class="fas fa-chart-line fa-2x opacity-75"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card warning">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stats-number"><?= count($availableTests) ?></div>
                    <div class="stats-label">Available Tests</div>
                </div>
                <i class="fas fa-shopping-bag fa-2x opacity-75"></i>
            </div>
        </div>
    </div>
</div>

<!-- Category Filter -->
<div class="card mb-4">
    <div class="card-body">
        <h6 class="mb-3"><i class="fas fa-filter me-2"></i>Filter by Category</h6>
        <div class="d-flex flex-wrap">
            <span class="category-chip active" data-category="all">
                <i class="fas fa-th me-1"></i>All Tests
            </span>
            <?php foreach ($subjects as $subject): ?>
            <span class="category-chip" data-category="<?= htmlspecialchars($subject) ?>">
                <?= htmlspecialchars($subject) ?>
            </span>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- Available Tests -->
<div id="available-tests">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3><i class="fas fa-fire me-2 text-danger"></i>Available Test Packs</h3>
        <div class="input-group" style="max-width: 300px;">
            <span class="input-group-text"><i class="fas fa-search"></i></span>
            <input type="text" class="form-control" id="searchTests" placeholder="Search tests...">
        </div>
    </div>

    <div class="row g-2" id="testsContainer">
        <?php if (empty($availableTests)): ?>
        <div class="col-12">
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>No tests available at the moment. Please check back later!
            </div>
        </div>
        <?php else: ?>
        <?php foreach ($availableTests as $test): ?>
        <div class="col-lg-4 col-md-6 test-item"
            data-category="<?= htmlspecialchars($test['test_category'] ?? 'General') ?>">
            <div class="card test-card h-100">
                <!-- Cover Image -->
                <?php if (!empty($test['cover_image']) && file_exists($test['cover_image'])): ?>
                <img src="<?= htmlspecialchars($test['cover_image']) ?>" class="card-img-top"
                    alt="<?= htmlspecialchars($test['title']) ?>" style=" object-fit: cover;">
                <?php else: ?>
                <div class="card-img-top bg-gradient"
                    style="height: 200px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-graduation-cap fa-4x text-white opacity-50"></i>
                </div>
                <?php endif; ?>

                <div class="card-body">
                    <!-- Test Type Badge -->
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <span
                            class="test-badge bg-<?= $test['test_type'] === 'mock' ? 'primary' : ($test['test_type'] === 'real' ? 'danger' : 'info') ?>">
                            <i
                                class="fas fa-<?= $test['test_type'] === 'mock' ? 'flask' : ($test['test_type'] === 'real' ? 'award' : 'bolt') ?> me-1"></i>
                            <?= strtoupper($test['test_type'] ?? 'TEST') ?>
                        </span>
                        <?php if (!empty($test['is_proctored'])): ?>
                        <span class="test-badge bg-warning">
                            <i class="fas fa-video me-1"></i>PROCTORED
                        </span>
                        <?php endif; ?>
                    </div>

                    <!-- Test Title -->
                    <h5 class="card-title mb-3">
                        <?= htmlspecialchars($test['title']) ?>
                    </h5>

                    <!-- Test Meta Info -->
                    <div class="test-meta mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span><i class="fas fa-question-circle me-1"></i><?= $test['total_questions'] ?? 0 ?>
                                Question<?= ($test['total_questions'] ?? 0) != 1 ? 's' : '' ?></span>
                            <span><i class="fas fa-clock me-1"></i><?= $test['duration_minutes'] ?? 60 ?> mins</span>
                        </div>
                        <div class="mb-2">
                            <i class="fas fa-book me-1"></i><?= htmlspecialchars($test['test_category'] ?? 'General') ?>
                        </div>
                        <?php if (!empty($test['description'])): ?>
                        <p class="small text-muted mb-2">
                            <?= htmlspecialchars(substr($test['description'], 0, 100)) ?>...</p>
                        <?php endif; ?>
                    </div>

                    <!-- Price and Action -->
                    <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                        <div class="test-price">
                            <?php if (($test['price'] ?? 0) > 0): ?>
                            ₹<?= number_format($test['price']) ?>
                            <?php else: ?>
                            <span class="text-success">FREE</span>
                            <?php endif; ?>
                        </div>
                        <div>
                            <button class="btn btn-outline-primary btn-sm me-1"
                                onclick="showTestDetails(<?= $test['id'] ?>)">
                                <i class="fas fa-info-circle"></i>
                            </button>
                            <?php if ($test['is_enrolled'] > 0): ?>
                            <a href="index.php?page=my_tests" class="btn btn-success btn-sm">
                                <i class="fas fa-check-circle me-1"></i>Enrolled
                            </a>
                            <?php elseif (($test['price'] ?? 0) > 0): ?>
                            <button class="btn btn-primary btn-sm"
                                onclick="buyTest(<?= $test['id'] ?>, '<?= addslashes($test['title']) ?>', <?= $test['price'] ?>)">
                                <i class="fas fa-shopping-cart me-1"></i>Buy Now
                            </button>
                            <?php else: ?>
                            <button class="btn btn-success btn-sm"
                                onclick="enrollTest(<?= $test['id'] ?>, '<?= addslashes($test['title']) ?>')">
                                <i class="fas fa-check me-1"></i>Enroll Free
                            </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<!-- My Tests Section (placeholder) -->
<div id="my-tests" class="mt-5">
    <h3 class="mb-4"><i class="fas fa-book-reader me-2 text-primary"></i>My Enrolled Tests</h3>
    <div class="alert alert-info">
        <i class="fas fa-info-circle me-2"></i>You haven't enrolled in any tests yet. Browse available tests above to
        get started!
    </div>
</div>

<!-- Test Details Modal -->
<div class="modal fade" id="testDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-info-circle me-2"></i>Test Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="testDetailsContent">
                <div class="text-center py-5">
                    <i class="fas fa-spinner fa-spin fa-3x text-primary"></i>
                    <p class="mt-3">Loading test details...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Close
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Buy Test Modal -->
<div class="modal fade" id="buyTestModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-shopping-cart me-2"></i>Purchase Test
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <i class="fas fa-shopping-cart text-primary fa-4x mb-3"></i>
                    <h4 id="testTitle"></h4>
                    <p class="text-muted">Reserve this test pack</p>
                </div>
                <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded mb-3">
                    <span>Test Price:</span>
                    <span class="h4 mb-0 text-success">₹<span id="testPrice">0</span></span>
                </div>
                <div class="alert alert-warning">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Payment Collection:</strong> Payment will be collected later. You can enroll now and the
                    test will be available in your "My Tests" section.
                </div>
                <div class="alert alert-info">
                    <i class="fas fa-book-open me-2"></i>
                    After enrollment, this test will be immediately available for you to take.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="processPurchase()">
                    <i class="fas fa-check me-2"></i>Enroll Now (Pay Later)
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Show test details in modal
function showTestDetails(testId) {
    const modal = new bootstrap.Modal(document.getElementById('testDetailsModal'));
    modal.show();

    // Fetch test details
    fetch('get_test_details.php?id=' + testId)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const test = data.test;
                let html = '';

                // Cover image
                if (test.cover_image) {
                    html +=
                        `<img src="${test.cover_image}" alt="Cover" class="img-fluid mb-3 rounded" style="width: 100%; object-fit: contain;">`;
                }

                // Test info
                html += `
                    <h4 class="mb-3">${test.title}</h4>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong><i class="fas fa-tag me-2"></i>Type:</strong> 
                                <span class="badge bg-${test.test_type === 'mock' ? 'primary' : (test.test_type === 'real' ? 'danger' : 'info')}">
                                    ${test.test_type.toUpperCase()}
                                </span>
                            </p>
                            <p><strong><i class="fas fa-clock me-2"></i>Duration:</strong> ${test.duration_minutes} minutes</p>
                            <p><strong><i class="fas fa-question-circle me-2"></i>Questions:</strong> ${test.total_questions || 0}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong><i class="fas fa-rupee-sign me-2"></i>Price:</strong> 
                                <span class="text-success fw-bold">₹${test.price}</span>
                                ${test.mrp > test.price ? `<small class="text-muted text-decoration-line-through ms-2">₹${test.mrp}</small>` : ''}
                            </p>
                            <p><strong><i class="fas fa-book me-2"></i>Category:</strong> ${test.test_category || 'General'}</p>
                            ${test.is_proctored ? '<p><span class="badge bg-warning"><i class="fas fa-video me-1"></i>PROCTORED</span></p>' : ''}
                        </div>
                    </div>
                `;

                // Description
                if (test.description) {
                    html += `
                        <hr>
                        <h6 class="mb-2"><i class="fas fa-align-left me-2"></i>Description</h6>
                        <p class="text-muted">${test.description.replace(/\n/g, '<br>')}</p>
                    `;
                }

                // Instructions
                if (test.instructions) {
                    html += `
                        <hr>
                        <h6 class="mb-2"><i class="fas fa-list-ul me-2"></i>Instructions</h6>
                        <p class="text-muted">${test.instructions.replace(/\n/g, '<br>')}</p>
                    `;
                }

                document.getElementById('testDetailsContent').innerHTML = html;
            } else {
                document.getElementById('testDetailsContent').innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>${data.message || 'Failed to load test details'}
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('testDetailsContent').innerHTML = `
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>An error occurred while loading test details.
                    <br><small>Error: ${error.message}</small>
                    <br><small>Please check the browser console for more details.</small>
                </div>
            `;
        });
}

// Test filtering
document.querySelectorAll('.category-chip').forEach(chip => {
    chip.addEventListener('click', function() {
        // Remove active from all
        document.querySelectorAll('.category-chip').forEach(c => c.classList.remove('active'));
        // Add active to clicked
        this.classList.add('active');

        const category = this.dataset.category;
        document.querySelectorAll('.test-item').forEach(item => {
            if (category === 'all' || item.dataset.category === category) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    });
});

// Search functionality
document.getElementById('searchTests').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    document.querySelectorAll('.test-item').forEach(item => {
        const title = item.querySelector('.card-title').textContent.toLowerCase();
        const category = item.dataset.category.toLowerCase();
        if (title.includes(searchTerm) || category.includes(searchTerm)) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });
});

// Buy test
let selectedTestId = null;
let selectedTestPrice = 0;

function buyTest(testId, title, price) {
    selectedTestId = testId;
    selectedTestPrice = price;
    document.getElementById('testTitle').textContent = title;
    document.getElementById('testPrice').textContent = price;
    const modal = new bootstrap.Modal(document.getElementById('buyTestModal'));
    modal.show();
}

// Enroll free test
function enrollTest(testId, title) {
    if (confirm('Enroll in "' + title + '" for free?')) {
        const formData = new FormData();
        formData.append('action', 'enroll_free');
        formData.append('test_id', testId);

        fetch('process_enrollment.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                console.log('Response status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                if (data.success) {
                    alert(data.message);
                    if (data.redirect) {
                        window.location.href = data.redirect;
                    } else {
                        location.reload();
                    }
                } else {
                    alert('Error: ' + data.message + (data.trace ? '\n\nDetails in console' : ''));
                    if (data.trace) console.error('Error trace:', data.trace);
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
                alert('An error occurred: ' + error.message + '\n\nCheck browser console for details.');
            });
    }
}

// Process purchase
function processPurchase() {
    if (!selectedTestId) return;

    // Show loading
    const btn = event.target;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Enrolling...';

    const formData = new FormData();
    formData.append('action', 'buy_test');
    formData.append('test_id', selectedTestId);

    fetch('process_enrollment.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            console.log('Purchase response status:', response.status);
            return response.json();
        })
        .then(data => {
            console.log('Purchase response data:', data);
            if (data.success) {
                // Close modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('buyTestModal'));
                modal.hide();

                // Show success message
                alert(
                    '✓ Successfully enrolled!\n\nTest has been added to your account. Payment will be collected later.\nYou can now access this test from "My Tests" section.'
                );

                // Redirect or reload
                if (data.redirect) {
                    window.location.href = data.redirect;
                } else {
                    location.reload();
                }
            } else {
                alert('Error: ' + data.message + (data.trace ? '\n\nDetails in console' : ''));
                if (data.trace) console.error('Error trace:', data.trace);
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-check me-2"></i>Enroll Now (Pay Later)';
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            alert('An error occurred: ' + error.message + '\n\nCheck browser console for details.');
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-check me-2"></i>Enroll Now (Pay Later)';
        });
}
</script>