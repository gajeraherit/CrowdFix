<?php
session_start();
require_once __DIR__ . '/config/helpers.php';
require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/controllers/IssueController.php';
require_once __DIR__ . '/controllers/AdminController.php';
require_once __DIR__ . '/models/Issue.php';

$page = $_GET['page'] ?? (isLoggedIn() ? 'dashboard' : 'login');

// Handle incoming actions
AuthController::handle();
IssueController::handle();

// Protect certain views
if (in_array($page, ['dashboard', 'report', 'admin'], true)) {
    requireLogin();
}
if ($page === 'admin' && !isAdmin()) {
    redirect('index.php?page=dashboard');
}

include __DIR__ . '/views/header.php';

switch ($page) {
    case 'login':
        include __DIR__ . '/views/login.php';
        break;
    case 'register':
        include __DIR__ . '/views/register.php';
        break;
    case 'report':
        include __DIR__ . '/views/report_issue.php';
        break;
    case 'admin':
        $data = AdminController::dashboardData();
        include __DIR__ . '/views/admin_dashboard.php';
        break;
    default:
        $issues = Issue::getAll();
        include __DIR__ . '/views/dashboard.php';
}

include __DIR__ . '/views/footer.php';

