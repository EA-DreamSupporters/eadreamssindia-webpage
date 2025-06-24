<?php
session_start();
require_once 'config/database.php';
require_once 'includes/auth.php';

// Simple routing
$page = $_GET['page'] ?? 'dashboard';
$allowed_pages = ['dashboard', 'tests', 'questions', 'analytics', 'vendors', 'students', 'settings', 'login'];

if (!in_array($page, $allowed_pages)) {
    $page = 'dashboard';
}

// Check authentication for protected pages
if ($page !== 'login' && !isLoggedIn()) {
    header('Location: index.php?page=login');
    exit();
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