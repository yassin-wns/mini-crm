<?php
$currentUser = auth()->user();
$activeNav = $activeNav ?? '';
$initial = strtoupper(substr($currentUser->name ?? 'U', 0, 1));
$roleClass = ($currentUser && $currentUser->isAdmin()) ? 'admin' : '';
?>
<aside class="app-sidebar" id="app-sidebar">
    <div class="sidebar-header">
        <a href="<?php echo url('/dashboard'); ?>" class="sidebar-brand">
            <div class="brand-icon-box">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                    <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                </svg>
            </div>
            <div class="brand-name">
                MiniCRM
                <span class="brand-badge">PRO</span>
            </div>
        </a>
        <button type="button" class="sidebar-close-btn" id="sidebar-close-btn" aria-label="Close sidebar">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>
    </div>

    <div class="sidebar-content">
        <div>
            <div class="nav-section-title">Navigation</div>
            <ul class="sidebar-nav-list">
                <li>
                    <a href="<?php echo url('/dashboard'); ?>" class="nav-link <?php echo $activeNav === 'dashboard' ? 'active' : ''; ?>">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="3" width="7" height="7"></rect>
                            <rect x="14" y="3" width="7" height="7"></rect>
                            <rect x="14" y="14" width="7" height="7"></rect>
                            <rect x="3" y="14" width="7" height="7"></rect>
                        </svg>
                        Dashboard
                    </a>
                </li>
                <li>
                    <a href="<?php echo url('/customers'); ?>" class="nav-link <?php echo $activeNav === 'customers' ? 'active' : ''; ?>">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                        Customers
                    </a>
                </li>
                <li>
                    <a href="<?php echo url('/customers/create'); ?>" class="nav-link <?php echo $activeNav === 'create_customer' ? 'active' : ''; ?>">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <line x1="19" y1="8" x2="19" y2="14"></line>
                            <line x1="22" y1="11" x2="16" y2="11"></line>
                        </svg>
                        Add Customer
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <div class="sidebar-footer">
        <div class="user-profile-card">
            <div class="user-avatar-group">
                <div class="user-avatar"><?php echo htmlspecialchars($initial, ENT_QUOTES, 'UTF-8'); ?></div>
                <div class="user-details">
                    <div class="user-name-text" title="<?php echo htmlspecialchars($currentUser->name ?? 'User', ENT_QUOTES, 'UTF-8'); ?>">
                        <?php echo htmlspecialchars($currentUser->name ?? 'User', ENT_QUOTES, 'UTF-8'); ?>
                    </div>
                    <span class="user-role-tag <?php echo $roleClass; ?>">
                        <?php echo htmlspecialchars($currentUser->role ?? 'employee', ENT_QUOTES, 'UTF-8'); ?>
                    </span>
                </div>
            </div>
            <form method="POST" action="<?php echo url('/logout'); ?>" style="margin: 0;">
                <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                <button type="submit" class="btn-sidebar-logout" title="Sign out of CRM">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                        <polyline points="16 17 21 12 16 7"></polyline>
                        <line x1="21" y1="12" x2="9" y2="12"></line>
                    </svg>
                </button>
            </form>
        </div>
    </div>
</aside>
<div class="sidebar-backdrop" id="sidebar-backdrop"></div>
