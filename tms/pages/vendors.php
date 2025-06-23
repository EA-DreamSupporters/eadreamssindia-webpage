<?php
if (!hasRole('super_admin')) {
    header('Location: index.php?page=dashboard');
    exit();
}

$action = $_GET['action'] ?? 'list';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'create') {
        try {
            $name = $_POST['name'] ?? '';
            $subscription_plan = $_POST['subscription_plan'] ?? 'basic';
            
            $stmt = $db->prepare("INSERT INTO institutions (name, subscription_plan) VALUES (?, ?)");
            $stmt->execute([$name, $subscription_plan]);
            $success = "Institution created successfully!";
        } catch (Exception $e) {
            $error = "Error: " . $e->getMessage();
        }
    }
}

// Fetch institutions
$institutions = [];
try {
    $stmt = $db->prepare("SELECT i.*, COUNT(u.id) as user_count FROM institutions i LEFT JOIN users u ON i.id = u.institute_id GROUP BY i.id ORDER BY i.created_at DESC");
    $stmt->execute();
    $institutions = $stmt->fetchAll();
} catch (Exception $e) {
    error_log("Institutions fetch error: " . $e->getMessage());
}
?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="text-gradient mb-0">Vendor Management</h1>
                <p class="text-muted">Manage institutions, white-labeling, and multi-tenant configurations</p>
            </div>
            <button class="btn btn-primary" onclick="window.location.href='index.php?page=vendors&action=create'">
                <i class="fas fa-plus me-2"></i>Add Institution
            </button>
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
<!-- Vendor Dashboard -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="stats-card primary">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stats-number"><?= count($institutions) ?></div>
                    <div class="stats-label">Total Institutions</div>
                </div>
                <i class="fas fa-building fa-2x opacity-75"></i>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="stats-card success">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stats-number"><?= count(array_filter($institutions, fn($i) => $i['subscription_plan'] === 'premium')) ?></div>
                    <div class="stats-label">Premium Plans</div>
                </div>
                <i class="fas fa-crown fa-2x opacity-75"></i>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="stats-card warning">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stats-number">₹<?= number_format(rand(50000, 200000)) ?></div>
                    <div class="stats-label">Monthly Revenue</div>
                </div>
                <i class="fas fa-rupee-sign fa-2x opacity-75"></i>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="stats-card danger">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stats-number"><?= array_sum(array_column($institutions, 'user_count')) ?></div>
                    <div class="stats-label">Total Users</div>
                </div>
                <i class="fas fa-users fa-2x opacity-75"></i>
            </div>
        </div>
    </div>
</div>

<!-- Institutions List -->
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-building me-2"></i>All Institutions
            </h5>
            <input type="text" class="form-control w-25" placeholder="Search institutions...">
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Institution</th>
                        <th>Plan</th>
                        <th>Users</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($institutions as $institution): ?>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="institution-logo me-3">
                                    <?php if ($institution['logo']): ?>
                                    <img src="<?= htmlspecialchars($institution['logo']) ?>" alt="Logo" class="rounded" width="40" height="40">
                                    <?php else: ?>
                                    <div class="bg-primary rounded d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <i class="fas fa-building text-white"></i>
                                    </div>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <strong><?= htmlspecialchars($institution['name']) ?></strong>
                                    <br><small class="text-muted">ID: <?= $institution['id'] ?></small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-<?= $institution['subscription_plan'] === 'enterprise' ? 'danger' : ($institution['subscription_plan'] === 'premium' ? 'warning' : 'primary') ?>">
                                <?= ucfirst($institution['subscription_plan']) ?>
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-secondary"><?= $institution['user_count'] ?> users</span>
                        </td>
                        <td>
                            <span class="badge bg-success">Active</span>
                        </td>
                        <td><?= date('M d, Y', strtotime($institution['created_at'])) ?></td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-outline-primary" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-outline-success" title="White-label Config">
                                    <i class="fas fa-palette"></i>
                                </button>
                                <button class="btn btn-outline-info" title="Analytics">
                                    <i class="fas fa-chart-bar"></i>
                                </button>
                                <button class="btn btn-outline-warning" title="Manage Users">
                                    <i class="fas fa-users"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    
                    <?php if (empty($institutions)): ?>
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <i class="fas fa-building fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No institutions found</h5>
                            <p class="text-muted">Add your first institution to get started with multi-tenant features.</p>
                            <button class="btn btn-primary" onclick="window.location.href='index.php?page=vendors&action=create'">
                                <i class="fas fa-plus me-2"></i>Add Institution
                            </button>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- White-labeling Features -->
<div class="row mt-4">
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-palette fa-3x text-primary mb-3"></i>
                <h5>Custom Branding</h5>
                <p class="text-muted">Logo, colors, and theme customization for each institution</p>
                <button class="btn btn-outline-primary">Configure</button>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-cog fa-3x text-success mb-3"></i>
                <h5>Multi-tenant Setup</h5>
                <p class="text-muted">Isolated data and configurations per institution</p>
                <button class="btn btn-outline-success">Manage</button>
            </div>
        </div>
    </div>
    
    
    
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-chart-line fa-3x text-warning mb-3"></i>
                <h5>Usage Analytics</h5>
                <p class="text-muted">Per-institution usage statistics and billing</p>
                <button class="btn btn-outline-warning">View Reports</button>
            </div>
        </div>
    </div>
</div>

<?php elseif ($action === 'create'): ?>
<!-- Create Institution Form -->
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-plus me-2"></i>Add New Institution
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" class="needs-validation" novalidate>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="name" class="form-label">Institution Name</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                                <div class="invalid-feedback">Please provide an institution name.</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="subscription_plan" class="form-label">Subscription Plan</label>
                                <select class="form-select" id="subscription_plan" name="subscription_plan" required>
                                    <option value="basic">Basic - ₹999/month</option>
                                    <option value="premium">Premium - ₹2999/month</option>
                                    <option value="enterprise">Enterprise - ₹9999/month</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="logo" class="form-label">Institution Logo</label>
                        <input type="file" class="form-control" id="logo" name="logo" accept="image/*">
                        <div class="form-text">Upload logo for white-labeling (optional)</div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="primary_color" class="form-label">Primary Color</label>
                                <input type="color" class="form-control form-control-color" id="primary_color" name="primary_color" value="#0066CC">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="secondary_color" class="form-label">Secondary Color</label>
                                <input type="color" class="form-control form-control-color" id="secondary_color" name="secondary_color" value="#17A2B8">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <h6>Features Included</h6>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="feature_tests" checked disabled>
                                    <label class="form-check-label" for="feature_tests">Test Management</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="feature_questions" checked disabled>
                                    <label class="form-check-label" for="feature_questions">Question Bank</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="feature_analytics">
                                    <label class="form-check-label" for="feature_analytics">Advanced Analytics</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="feature_proctoring">
                                    <label class="form-check-label" for="feature_proctoring">Proctored Tests</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="feature_whitelabel">
                                    <label class="form-check-label" for="feature_whitelabel">White-labeling</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="feature_api">
                                    <label class="form-check-label" for="feature_api">API Access</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary" onclick="window.location.href='index.php?page=vendors'">
                            <i class="fas fa-arrow-left me-2"></i>Back to Vendors
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-check me-2"></i>Create Institution
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php endif; ?>