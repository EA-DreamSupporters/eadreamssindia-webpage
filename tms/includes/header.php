<?php if ($_GET['page'] ?? '' !== 'login'): ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TMS Pro - Test Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="index.php">
                <i class="fas fa-graduation-cap me-2"></i>TMS Pro
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link <?= ($_GET['page'] ?? 'dashboard') === 'dashboard' ? 'active' : '' ?>"
                            href="index.php?page=dashboard">
                            <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= ($_GET['page'] ?? '') === 'tests' ? 'active' : '' ?>"
                            href="index.php?page=tests">
                            <i class="fas fa-clipboard-list me-1"></i>Tests
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= ($_GET['page'] ?? '') === 'questions' ? 'active' : '' ?>"
                            href="index.php?page=questions">
                            <i class="fas fa-question-circle me-1"></i>Questions
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= ($_GET['page'] ?? '') === 'analytics' ? 'active' : '' ?>"
                            href="index.php?page=analytics">
                            <i class="fas fa-chart-line me-1"></i>Analytics
                        </a>
                    </li>
                    <?php if (hasRole('super_admin')): ?>
                    <li class="nav-item">
                        <a class="nav-link <?= ($_GET['page'] ?? '') === 'vendors' ? 'active' : '' ?>"
                            href="index.php?page=vendors">
                            <i class="fas fa-building me-1"></i>Vendors
                        </a>
                    </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <a class="nav-link <?= ($_GET['page'] ?? '') === 'students' ? 'active' : '' ?>"
                            href="index.php?page=students">
                            <i class="fas fa-users me-1"></i>Students
                        </a>
                    </li>
                </ul>

                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-1"></i>
                            <?= htmlspecialchars(getCurrentUser()['username'] ?? 'User') ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="index.php?page=settings"><i
                                        class="fas fa-cog me-2"></i>Settings</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="index.php?action=logout"><i
                                        class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container-fluid" style="margin-top: 76px;">
        <?php endif; ?>




        <?php 
        /*
        // Include the database configuration
        include("../config/database.php");

        // Set $activePage to the current page if not already set
        if (!isset($activePage)) {
            $activePage = basename($_SERVER['PHP_SELF'], ".php");
        }
        ?>

        <!-- Sidebar Navigation Styles and Scripts -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
        <link rel="stylesheet" href="../scss/components/menu.css">
        <script src=" https://code.iconify.design/iconify-icon/3.0.0/iconify-icon.min.js"></script>
        <script src="../dashboard_new/script/dashmenu.js"></script>

        <!-- Google Fonts: Outfit -->
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">

        <!-- Sidebar White Background Layer -->
        <div class="sidebar-hover-zone-bg"></div>
        <!-- Sidebar Hover Zone (for smooth hover/collapse interaction) -->
        <div class="sidebar-hover-zone"></div>
        <!-- Sidebar Content -->
        <div class="sidebar d-flex flex-column align-items-start">

            <!-- Lock Button absolutely above sidebar, centered at expanded edge -->
            <button class="sidebar-lock-btn" id="sidebar-lock-btn" title="Lock sidebar">
                <div class="outer-circle">
                    <div class="inner-circle">
                        <iconify-icon id="sidebar-lock-icon" icon="bx:chevron-right" width="18" height="18"
                            style="color: #fff;"></iconify-icon>
                    </div>
                </div>
            </button>
            <!-- Logo Switcher -->
            <a href="dashboard.php" class="sidebar-logo-link">
                <img src="../images/favicon.png" alt="Logo" class="sidebar-logo-collapsed" />
                <img src="../images/logo2.png" alt="Logo" class="sidebar-logo-expanded" />
            </a>

            <!-- Nav Tabs -->
            <!-- Dashboard Link -->
            <a class="nav-link d-flex align-items-center <?= ($_GET['page'] ?? 'dashboard') === 'dashboard' ? 'active' : '' ?>"
                href="index.php?page=dashboard" title="Dashboard" style="gap:8px;">
                <iconify-icon icon="bx:home-smile"></iconify-icon>
                <span style="font-size:16px;">Dashboard</span>
            </a>

            <!-- Tests Link -->
            <a class="nav-link d-flex align-items-center <?= ($_GET['page'] ?? '') === 'tests' ? 'active' : '' ?>"
                href="index.php?page=tests" style="gap:8px;">
                <iconify-icon icon="bx:notepad"></iconify-icon>
                <span style="font-size:16px;">Tests</span>
            </a>

            <!-- Questions Link -->
            <a class="nav-link d-flex align-items-center <?= ($_GET['page'] ?? '') === 'questions' ? 'active' : '' ?>"
                href="index.php?page=questions" title="Questions" style="gap:8px;">
                <iconify-icon icon="bx:archive"></iconify-icon>
                <span style="font-size:16px;">Questions</span>
            </a>

            <!-- Analytics Link -->
            <a class="nav-link d-flex align-items-center <?= ($_GET['page'] ?? '') === 'analytics' ? 'active' : '' ?>"
                href="index.php?page=analytics" title="Analytics" style="gap:8px;">
                <iconify-icon icon="bx:network-chart"></iconify-icon>
                <span style="font-size:16px;">Analytics</span>
            </a>

            <!-- Vendors Link (Visible only to super_admin) -->
            <?php if (hasRole('super_admin')): ?>
            <a class="nav-link d-flex align-items-center <?= ($_GET['page'] ?? '') === 'vendors' ? 'active' : '' ?>"
                href="index.php?page=vendors" title="Vendors" style="gap:8px;">
                <iconify-icon icon="bx:sitemap"></iconify-icon>
                <span style="font-size:16px;">Vendors</span>
            </a>
            <?php endif; ?>

            <!-- Students Link -->
            <a class="nav-link d-flex align-items-center <?= ($_GET['page'] ?? '') === 'students' ? 'active' : '' ?>"
                href="index.php?page=students" title="Students" style="gap:8px;">
                <iconify-icon icon="bx:id-card"></iconify-icon>
                <span style="font-size:16px;">Students</span>
            </a>

            <!-- Separator Example (Commented Out)
            <div class="separator">
            <hr class="sep-hr-left">
            <span>Advance</span>
            <hr class="sep-hr-right">
            </div>
            -->

        </div>
        */
        ?>