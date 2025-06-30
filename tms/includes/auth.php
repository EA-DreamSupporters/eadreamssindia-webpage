<?php
// Always include database.php before using this file
if (!isset($db)) {
    require_once __DIR__ . '/../config/database.php';
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function getCurrentUser() {
    if (!isLoggedIn()) {
        return null;
    }
    global $db;
    $stmt = $db->prepare("SELECT * FROM users WHERE id = ? LIMIT 1");
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetch();
}

function hasRole($role) {
    $user = getCurrentUser();
    return $user && $user['role'] === $role;
}

function login($username, $password) {
    global $db;
    $stmt = $db->prepare("SELECT * FROM users WHERE username = ? OR email = ? LIMIT 1");
    $stmt->execute([$username, $username]);
    $user = $stmt->fetch();

    if ($user && isset($user['password'])) {
        $dbPassword = $user['password'];

        // ✅ Password check for bcrypt (password_hash)
        if (password_verify($password, $dbPassword)) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_role'] = $user['role'];
            if (isset($user['institute_id'])) $_SESSION['institute_id'] = $user['institute_id'];
            return true;
        }

        // ✅ Password check for MD5
        if ($dbPassword === md5($password)) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_role'] = $user['role'];
            if (isset($user['institute_id'])) $_SESSION['institute_id'] = $user['institute_id'];
            return true;
        }

        // ✅ Password check for SHA256 (for your super admin case)
        if ($dbPassword === hash('sha256', $password)) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_role'] = $user['role'];
            if (isset($user['institute_id'])) $_SESSION['institute_id'] = $user['institute_id'];
            return true;
        }
    }

    return false;
}

function logout() {
    session_destroy();
    header('Location: index.php?page=login');
    exit();
}

// Handle logout
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    logout();
}
?>