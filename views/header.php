<?php require_once __DIR__ . '/../config/helpers.php'; ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CrowdFix</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/app.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="index.php">CrowdFix</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav ms-auto">
                <?php if (isLoggedIn()): ?>
                    <li class="nav-item"><a class="nav-link" href="index.php?page=dashboard">Issues</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php?page=report">Report</a></li>
                    <?php if (isAdmin()): ?>
                        <li class="nav-item"><a class="nav-link" href="index.php?page=admin">Admin</a></li>
                    <?php endif; ?>
                    <li class="nav-item"><a class="nav-link" href="index.php?page=login&action=logout">Logout</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="index.php?page=login">Login</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php?page=register">Register</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
<div class="container">
    <?php if ($msg = flash('success')): ?>
        <div class="alert alert-success"><?= sanitize($msg); ?></div>
    <?php endif; ?>
    <?php if ($msg = flash('error')): ?>
        <div class="alert alert-danger"><?= sanitize($msg); ?></div>
    <?php endif; ?>

