<?php
session_start();
require_once 'config/database.php';
require_once 'includes/auth.php';

// Simple routing
$page = $_GET['page'] ?? 'dashboard';
$action = $_GET['action'] ?? '';
$allowed_pages = ['dashboard', 'tests', 'test_details', 'questions', 'analytics', 'vendors', 'students', 'settings', 'login', 'my_tests', 'test_results', 'practice', 'take_test'];

if (!in_array($page, $allowed_pages)) {
    $page = 'dashboard';
}

// Check if this is an AJAX request to questions page
$ajax_actions = ['bulk_duplicate', 'bulk_delete', 'process_upload', 'process_text', 'import_questions'];
$is_ajax_request = ($page === 'questions' && in_array($action, $ajax_actions) && $_SERVER['REQUEST_METHOD'] === 'POST');

// If this is an AJAX request for the questions page, delegate to the AJAX
// controller immediately. The controller will emit JSON and exit. This avoids
// loading the full page HTML and prevents the frontend receiving non-JSON
// responses (which previously caused "Network error" on uploads).
if ($is_ajax_request && $page === 'questions') {
    $ajaxHandler = __DIR__ . '/pages/questions/controllers/ajax.php';
    if (file_exists($ajaxHandler)) {
        require_once $ajaxHandler;
    }
    // ajax controller should exit after handling; ensure we stop here
    exit();
}

// Check authentication for protected pages
if ($page !== 'login' && !isLoggedIn()) {
    if ($is_ajax_request) {
        // For AJAX requests, return JSON error instead of redirecting
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => 'Authentication required. Please log in again.']);
        exit();
    } else {
        // For regular requests, redirect to login
        header('Location: index.php?page=login');
        exit();
    }
}

// Include header only for regular (non-AJAX) pages to avoid sending HTML before AJAX handlers
if ($page !== 'login' && !$is_ajax_request) {
    // Ensure Questions server-side form handlers (which may use header redirects)
    // are included before any HTML is emitted. This prevents "headers already sent"
    // errors when those handlers call header().
    if ($page === 'questions') {
        $formsHandler = __DIR__ . '/pages/questions/controllers/forms.php';
        if (file_exists($formsHandler)) {
            require_once $formsHandler;
        }
    }

    include 'includes/header.php';
}

switch ($page) {
    case 'login':
        include 'pages/login.php';
        break;
    case 'dashboard':
        include 'pages/dashboard.php';
        break;
    case 'tests':
        include 'pages/tests.php';
        break;
    case 'test_details':
        include 'pages/test_details.php';
        break;
    case 'questions':
        include 'pages/questions.php';
        break;
    case 'analytics':
        include 'pages/analytics.php';
        break;
    case 'vendors':
        include 'pages/vendors.php';
        break;
    case 'students':
        include 'pages/students.php';
        break;
    case 'settings':
        include 'pages/settings.php';
        break;
    case 'my_tests':
        include 'pages/my_tests.php';
        break;
    case 'test_results':
        include 'pages/test_results.php';
        break;
    case 'practice':
        include 'pages/practice.php';
        break;
    case 'take_test':
        include 'pages/take_test.php';
        break;
    default:
        include 'pages/dashboard.php';
}

// Include footer only for regular (non-AJAX) pages
if ($page !== 'login' && !$is_ajax_request) {
    include 'includes/footer.php';
}
?>