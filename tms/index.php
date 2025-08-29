<?php
session_start();
require_once 'config/database.php';
require_once 'includes/auth.php';

// Simple routing
$page = $_GET['page'] ?? 'dashboard';
$action = $_GET['action'] ?? '';
$allowed_pages = ['dashboard', 'tests', 'test_details', 'questions', 'analytics', 'vendors', 'students', 'settings', 'login'];

if (!in_array($page, $allowed_pages)) {
    $page = 'dashboard';
}

// Check if this is an AJAX request to questions page
$ajax_actions = ['bulk_duplicate', 'bulk_delete', 'process_upload', 'process_text', 'import_questions'];
$is_ajax_request = ($page === 'questions' && in_array($action, $ajax_actions) && $_SERVER['REQUEST_METHOD'] === 'POST');

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

if ($page !== 'login') {
    include 'includes/header.php';
}

switch($page) {
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
    default:
        include 'pages/dashboard.php';
}

if ($page !== 'login') {
    include 'includes/footer.php';
}
?>