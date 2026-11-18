<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sign In — MiniCRM Enterprise</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body class="login-viewport">

<div class="login-auth-card">
    <div class="login-brand-header">
        <div class="login-brand-icon-wrap">
            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="width: 26px; height: 26px; max-width: 26px;">
                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                <circle cx="9" cy="7" r="4"></circle>
                <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
            </svg>
        </div>
        <h1 class="login-brand-title">MiniCRM</h1>
        <p class="login-brand-subtitle">Sign in to your account</p>
    </div>

    <?php if (session('success')): ?>
        <div class="alert-banner alert-success alert">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                <polyline points="22 4 12 14.01 9 11.01"></polyline>
            </svg>
            <div class="alert-body">
                <?php echo htmlspecialchars(session('success'), ENT_QUOTES, 'UTF-8'); ?>
            </div>
            <button type="button" class="alert-close-btn alert-close">&times;</button>
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
            <button type="button" class="alert-close-btn alert-close">&times;</button>
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
                <ul>
                    <?php foreach ($errors->all() as $err): ?>
                        <li><?php echo htmlspecialchars($err, ENT_QUOTES, 'UTF-8'); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <button type="button" class="alert-close-btn alert-close">&times;</button>
        </div>
    <?php endif; ?>

    <form method="POST" action="<?php echo route('login'); ?>" novalidate>
        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

        <div class="form-group-item" style="margin-bottom: 18px;">
            <label class="form-label-styled" for="email">
                Work Email <span class="required-mark">*</span>
            </label>
            <input 
                type="email" 
                id="email" 
                name="email" 
                class="form-input-styled" 
                value="<?php echo htmlspecialchars(old('email', ''), ENT_QUOTES, 'UTF-8'); ?>" 
                placeholder="name@company.com" 
                autocomplete="email"
                required 
                autofocus
            >
        </div>

        <div class="form-group-item" style="margin-bottom: 8px;">
            <label class="form-label-styled" for="password">
                Password <span class="required-mark">*</span>
            </label>
            <div class="password-input-wrapper">
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    class="form-input-styled" 
                    placeholder="Enter your password"
                    autocomplete="current-password"
                    required
                >
                <button type="button" class="password-toggle-trigger" id="toggle-password-btn" aria-label="Toggle password visibility">
                    <svg class="icon-eye" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                    <svg class="icon-eye-closed" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: none;">
                        <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                        <line x1="1" y1="1" x2="23" y2="23"></line>
                    </svg>
                </button>
            </div>
        </div>

        <div class="remember-checkbox-group">
            <input type="checkbox" id="remember" name="remember" value="1">
            <label for="remember">Keep me signed in for 30 days</label>
        </div>

        <button type="submit" class="btn btn-primary" style="width: 100%; height: 42px; font-weight: 600;">
            Sign In to Dashboard
        </button>
    </form>
</div>

<script src="/js/app.js"></script>
</body>
</html>
