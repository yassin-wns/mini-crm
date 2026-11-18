<?php
$title = isset($pageTitle) ? htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') . ' — Mini CRM' : 'Mini CRM';
$currentUser = auth()->user();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" href="<?php echo asset('css/style.css'); ?>">
</head>
<body>
<div class="app-layout">
    <?php include resource_path('views/layouts/sidebar.php'); ?>

    <div class="main-wrapper">
        <header class="top-header">
            <div class="header-left">
                <button type="button" class="mobile-menu-btn" id="mobile-sidebar-toggle" aria-label="Open navigation menu">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="3" y1="12" x2="21" y2="12"></line>
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <line x1="3" y1="18" x2="21" y2="18"></line>
                    </svg>
                </button>
                <div class="topbar-breadcrumbs">
                    <span class="breadcrumb-root">CRM</span>
                    <span class="breadcrumb-separator">/</span>
                    <span class="breadcrumb-current"><?php echo htmlspecialchars($pageTitle ?? 'Dashboard', ENT_QUOTES, 'UTF-8'); ?></span>
                </div>
            </div>

            <div class="header-right">
                <div class="system-status-indicator" title="All backend services and API nodes are operational">
                    <span class="status-dot-pulse"></span>
                    <span>Systems Operational</span>
                </div>
                <div class="header-divider"></div>
                <div class="header-user-pill">
                    <div class="header-user-avatar"><?php echo htmlspecialchars(strtoupper(substr($currentUser->name ?? 'U', 0, 1)), ENT_QUOTES, 'UTF-8'); ?></div>
                    <span class="header-user-name"><?php echo htmlspecialchars($currentUser->name ?? 'User', ENT_QUOTES, 'UTF-8'); ?></span>
                </div>
            </div>
        </header>

        <main class="page-content">
            <?php if (session('success')): ?>
                <div class="alert-banner alert-success alert">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                    <div class="alert-body">
                        <?php echo htmlspecialchars(session('success'), ENT_QUOTES, 'UTF-8'); ?>
                    </div>
                    <button type="button" class="alert-close-btn alert-close" aria-label="Dismiss notification">&times;</button>
                </div>
            <?php endif; ?>

            <?php if (session('error')): ?>
                <div class="alert-banner alert-danger alert">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                    <div class="alert-body">
                        <?php echo htmlspecialchars(session('error'), ENT_QUOTES, 'UTF-8'); ?>
                    </div>
                    <button type="button" class="alert-close-btn alert-close" aria-label="Dismiss notification">&times;</button>
                </div>
            <?php endif; ?>

            <?php if (isset($errors) && $errors->any()): ?>
                <div class="alert-banner alert-danger alert">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                    <div class="alert-body">
                        <strong>Please review the required fields:</strong>
                        <ul>
                            <?php foreach ($errors->all() as $err): ?>
                                <li><?php echo htmlspecialchars($err, ENT_QUOTES, 'UTF-8'); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <button type="button" class="alert-close-btn alert-close" aria-label="Dismiss notification">&times;</button>
                </div>
            <?php endif; ?>
