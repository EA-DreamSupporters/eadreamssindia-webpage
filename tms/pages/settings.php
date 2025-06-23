<?php
$user = getCurrentUser();
$success = '';
$error = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'update_profile') {
        try {
            $username = $_POST['username'] ?? '';
            $email = $_POST['email'] ?? '';
            
            $stmt = $db->prepare("UPDATE users SET username = ?, email = ? WHERE id = ?");
            $stmt->execute([$username, $email, $user['id']]);
            $success = "Profile updated successfully!";
            
            // Refresh user data
            $user = getCurrentUser();
        } catch (Exception $e) {
            $error = "Error updating profile: " . $e->getMessage();
        }
    }
    
    if ($action === 'change_password') {
        try {
            $current_password = $_POST['current_password'] ?? '';
            $new_password = $_POST['new_password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';
            
            if ($new_password !== $confirm_password) {
                throw new Exception("New passwords do not match");
            }
            
            if (!password_verify($current_password, $user['password'])) {
                throw new Exception("Current password is incorrect");
            }
            
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $db->prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmt->execute([$hashed_password, $user['id']]);
            $success = "Password changed successfully!";
        } catch (Exception $e) {
            $error = "Error changing password: " . $e->getMessage();
        }
    }
}
?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="text-gradient mb-0">Settings</h1>
                <p class="text-muted">Manage your account and system preferences</p>
            </div>
        </div>
    </div>
</div>

<?php if ($success): ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fas fa-check-circle me-2"></i><?= htmlspecialchars($success) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<?php if ($error): ?>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="fas fa-exclamation-triangle me-2"></i><?= htmlspecialchars($error) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<div class="row">
    <!-- Settings Navigation -->
    <div class="col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="nav flex-column nav-pills" id="settings-tab" role="tablist">
                    <button class="nav-link active" id="profile-tab" data-bs-toggle="pill" data-bs-target="#profile" type="button" role="tab">
                        <i class="fas fa-user me-2"></i>Profile
                    </button>
                    <button class="nav-link" id="security-tab" data-bs-toggle="pill" data-bs-target="#security" type="button" role="tab">
                        <i class="fas fa-shield-alt me-2"></i>Security
                    </button>
                    <button class="nav-link" id="notifications-tab" data-bs-toggle="pill" data-bs-target="#notifications" type="button" role="tab">
                        <i class="fas fa-bell me-2"></i>Notifications
                    </button>
                    <button class="nav-link" id="system-tab" data-bs-toggle="pill" data-bs-target="#system" type="button" role="tab">
                        <i class="fas fa-cog me-2"></i>System
                    </button>
                    <?php if (hasRole('super_admin')): ?>
                    <button class="nav-link" id="advanced-tab" data-bs-toggle="pill" data-bs-target="#advanced" type="button" role="tab">
                        <i class="fas fa-tools me-2"></i>Advanced
                    </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Settings Content -->
    <div class="col-lg-9">
        <div class="tab-content" id="settings-tabContent">
            <!-- Profile Settings -->
            <div class="tab-pane fade show active" id="profile" role="tabpanel">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-user me-2"></i>Profile Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" class="needs-validation" novalidate>
                            <input type="hidden" name="action" value="update_profile">
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="username" class="form-label">Username</label>
                                        <input type="text" class="form-control" id="username" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email Address</label>
                                        <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="role" class="form-label">Role</label>
                                        <input type="text" class="form-control" id="role" value="<?= ucfirst(str_replace('_', ' ', $user['role'])) ?>" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="created_at" class="form-label">Member Since</label>
                                        <input type="text" class="form-control" id="created_at" value="<?= date('F j, Y', strtotime($user['created_at'])) ?>" readonly>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Update Profile
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Security Settings -->
            <div class="tab-pane fade" id="security" role="tabpanel">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-shield-alt me-2"></i>Security Settings
                        </h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" class="needs-validation" novalidate>
                            <input type="hidden" name="action" value="change_password">
                            
                            <div class="mb-3">
                                <label for="current_password" class="form-label">Current Password</label>
                                <input type="password" class="form-control" id="current_password" name="current_password" required>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="new_password" class="form-label">New Password</label>
                                        <input type="password" class="form-control" id="new_password" name="new_password" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="confirm_password" class="form-label">Confirm New Password</label>
                                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-warning">
                                    <i class="fas fa-key me-2"></i>Change Password
                                </button>
                            </div>
                        </form>
                        
                        <hr>
                        
                        <h6>Two-Factor Authentication</h6>
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="mb-0">Add an extra layer of security to your account</p>
                                <small class="text-muted">Status: <span class="text-danger">Disabled</span></small>
                            </div>
                            <button class="btn btn-outline-success">
                                <i class="fas fa-mobile-alt me-2"></i>Enable 2FA
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Notifications Settings -->
            <div class="tab-pane fade" id="notifications" role="tabpanel">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-bell me-2"></i>Notification Preferences
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Email Notifications</h6>
                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input" type="checkbox" id="email_new_students" checked>
                                    <label class="form-check-label" for="email_new_students">New student registrations</label>
                                </div>
                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input" type="checkbox" id="email_test_completed" checked>
                                    <label class="form-check-label" for="email_test_completed">Test completions</label>
                                </div>
                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input" type="checkbox" id="email_low_scores">
                                    <label class="form-check-label" for="email_low_scores">Low score alerts</label>
                                </div>
                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input" type="checkbox" id="email_system_updates" checked>
                                    <label class="form-check-label" for="email_system_updates">System updates</label>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <h6>Dashboard Notifications</h6>
                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input" type="checkbox" id="dash_real_time" checked>
                                    <label class="form-check-label" for="dash_real_time">Real-time alerts</label>
                                </div>
                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input" type="checkbox" id="dash_weekly_reports" checked>
                                    <label class="form-check-label" for="dash_weekly_reports">Weekly reports</label>
                                </div>
                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input" type="checkbox" id="dash_maintenance">
                                    <label class="form-check-label" for="dash_maintenance">Maintenance notices</label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-end mt-3">
                            <button class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Save Preferences
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- System Settings -->
            <div class="tab-pane fade" id="system" role="tabpanel">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-cog me-2"></i>System Preferences
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="timezone" class="form-label">Timezone</label>
                                    <select class="form-select" id="timezone">
                                        <option value="Asia/Kolkata" selected>Asia/Kolkata (IST)</option>
                                        <option value="UTC">UTC</option>
                                        <option value="America/New_York">America/New_York (EST)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="language" class="form-label">Language</label>
                                    <select class="form-select" id="language">
                                        <option value="en" selected>English</option>
                                        <option value="hi">Hindi</option>
                                        <option value="ta">Tamil</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="date_format" class="form-label">Date Format</label>
                                    <select class="form-select" id="date_format">
                                        <option value="DD/MM/YYYY" selected>DD/MM/YYYY</option>
                                        <option value="MM/DD/YYYY">MM/DD/YYYY</option>
                                        <option value="YYYY-MM-DD">YYYY-MM-DD</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="items_per_page" class="form-label">Items per Page</label>
                                    <select class="form-select" id="items_per_page">
                                        <option value="10">10</option>
                                        <option value="25" selected>25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="dark_mode">
                            <label class="form-check-label" for="dark_mode">Enable Dark Mode</label>
                        </div>
                        
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="auto_save" checked>
                            <label class="form-check-label" for="auto_save">Auto-save forms</label>
                        </div>
                        
                        <div class="d-flex justify-content-end">
                            <button class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Save Settings
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <?php if (hasRole('super_admin')): ?>
            <!-- Advanced Settings -->
            <div class="tab-pane fade" id="advanced" role="tabpanel">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-tools me-2"></i>Advanced System Settings
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Warning:</strong> These settings affect the entire system. Please proceed with caution.
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Database Management</h6>
                                <div class="d-grid gap-2 mb-3">
                                    <button class="btn btn-outline-info">
                                        <i class="fas fa-database me-2"></i>Backup Database
                                    </button>
                                    <button class="btn btn-outline-warning">
                                        <i class="fas fa-broom me-2"></i>Clean Temporary Data
                                    </button>
                                    <button class="btn btn-outline-success">
                                        <i class="fas fa-chart-bar me-2"></i>Database Statistics
                                    </button>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <h6>System Maintenance</h6>
                                <div class="d-grid gap-2 mb-3">
                                    <button class="btn btn-outline-primary">
                                        <i class="fas fa-sync me-2"></i>Clear Cache
                                    </button>
                                    <button class="btn btn-outline-secondary">
                                        <i class="fas fa-file-alt me-2"></i>View System Logs
                                    </button>
                                    <button class="btn btn-outline-danger">
                                        <i class="fas fa-power-off me-2"></i>Maintenance Mode
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <h6>System Information</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-sm">
                                    <tr>
                                        <td><strong>PHP Version:</strong></td>
                                        <td><?= phpversion() ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Server:</strong></td>
                                        <td><?= $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown' ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Database:</strong></td>
                                        <td>MySQL <?= $db->query('SELECT VERSION()')->fetchColumn() ?></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-sm">
                                    <tr>
                                        <td><strong>Memory Limit:</strong></td>
                                        <td><?= ini_get('memory_limit') ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Upload Max:</strong></td>
                                        <td><?= ini_get('upload_max_filesize') ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Timezone:</strong></td>
                                        <td><?= date_default_timezone_get() ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>