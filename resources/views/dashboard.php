<?php include resource_path('views/layouts/header.php'); ?>

<div class="page-title-row">
    <div class="page-title-group">
        <h1>Dashboard Overview</h1>
        <p>Monitor your sales pipeline, active engagements, and recent client activity in real-time.</p>
    </div>
    <div>
        <a href="<?php echo route('customers.create'); ?>" class="btn btn-primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            Add Customer
        </a>
    </div>
</div>

<div class="metrics-grid">
    <div class="metric-card total">
        <div class="metric-top-row">
            <span class="metric-label-title">Total Customers</span>
            <div class="metric-icon-bubble">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                </svg>
            </div>
        </div>
        <div class="metric-number-value"><?php echo (int) $totalCustomers; ?></div>
        <div class="metric-sub-label">Total portfolio accounts registered</div>
    </div>

    <div class="metric-card active">
        <div class="metric-top-row">
            <span class="metric-label-title">Active Clients</span>
            <div class="metric-icon-bubble">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
            </div>
        </div>
        <div class="metric-number-value" style="color: #059669;"><?php echo (int) $activeCustomers; ?></div>
        <div class="metric-sub-label">Engaged in ongoing contracts</div>
    </div>

    <div class="metric-card lead">
        <div class="metric-top-row">
            <span class="metric-label-title">Leads &amp; Prospects</span>
            <div class="metric-icon-bubble">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <circle cx="12" cy="12" r="6"></circle>
                    <circle cx="12" cy="12" r="2"></circle>
                </svg>
            </div>
        </div>
        <div class="metric-number-value" style="color: #d97706;"><?php echo (int) $leadCustomers; ?></div>
        <div class="metric-sub-label">Prospective opportunities in funnel</div>
    </div>

    <div class="metric-card inactive">
        <div class="metric-top-row">
            <span class="metric-label-title">Inactive Accounts</span>
            <div class="metric-icon-bubble">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="8" y1="12" x2="16" y2="12"></line>
                </svg>
            </div>
        </div>
        <div class="metric-number-value" style="color: #475569;"><?php echo (int) $inactiveCustomers; ?></div>
        <div class="metric-sub-label">Archived or paused accounts</div>
    </div>
</div>

<div class="saas-card">
    <div class="card-header-bar">
        <div>
            <h2 class="card-header-title">Recently Added Customers</h2>
            <p class="card-header-desc">The 5 most recent additions to your CRM system</p>
        </div>
        <a href="<?php echo route('customers.index'); ?>" class="btn btn-secondary btn-sm">
            View All Customers
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="5" y1="12" x2="19" y2="12"></line>
                <polyline points="12 5 19 12 12 19"></polyline>
            </svg>
        </a>
    </div>

    <div class="table-responsive-container">
        <?php if ($recentCustomers->isEmpty()): ?>
            <div class="empty-state-card">
                <div class="empty-state-graphic">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                    </svg>
                </div>
                <div class="empty-state-title">No customers registered yet</div>
                <p class="empty-state-subtitle">Get started by onboarding your first client to the CRM workspace.</p>
                <a href="<?php echo route('customers.create'); ?>" class="btn btn-primary btn-sm">
                    Create Customer
                </a>
            </div>
        <?php else: ?>
            <table class="saas-table">
                <thead>
                    <tr>
                        <th>Customer Name</th>
                        <th>Email</th>
                        <th>Company</th>
                        <th>Status</th>
                        <th>Created By</th>
                        <th>Date Added</th>
                        <th style="text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentCustomers as $customer): ?>
                        <?php 
                        $avatarInitial = strtoupper(substr($customer->name, 0, 1));
                        $statusClass = match($customer->status) {
                            'Active' => 'status-active',
                            'Lead' => 'status-lead',
                            default => 'status-inactive',
                        };
                        ?>
                        <tr>
                            <td>
                                <div class="customer-name-cell">
                                    <div class="customer-avatar-badge"><?php echo htmlspecialchars($avatarInitial, ENT_QUOTES, 'UTF-8'); ?></div>
                                    <div>
                                        <a href="<?php echo route('customers.show', $customer->id); ?>" class="customer-name-link">
                                            <?php echo htmlspecialchars($customer->name, ENT_QUOTES, 'UTF-8'); ?>
                                        </a>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <a href="mailto:<?php echo htmlspecialchars($customer->email, ENT_QUOTES, 'UTF-8'); ?>" style="color: var(--color-text-secondary);">
                                    <?php echo htmlspecialchars($customer->email, ENT_QUOTES, 'UTF-8'); ?>
                                </a>
                            </td>
                            <td>
                                <span style="font-weight: 500; color: var(--color-text-main);">
                                    <?php echo htmlspecialchars($customer->company ?: '—', ENT_QUOTES, 'UTF-8'); ?>
                                </span>
                            </td>
                            <td>
                                <span class="status-pill <?php echo $statusClass; ?>">
                                    <span class="status-dot"></span>
                                    <span><?php echo htmlspecialchars($customer->status, ENT_QUOTES, 'UTF-8'); ?></span>
                                </span>
                            </td>
                            <td>
                                <span style="font-size: 13px; color: var(--color-text-secondary);">
                                    <?php echo htmlspecialchars($customer->creator->name ?? 'System', ENT_QUOTES, 'UTF-8'); ?>
                                </span>
                            </td>
                            <td>
                                <span style="font-size: 13px; color: var(--color-text-muted);">
                                    <?php echo $customer->created_at ? $customer->created_at->format('M d, Y') : '—'; ?>
                                </span>
                            </td>
                            <td style="text-align: right;">
                                <div class="table-actions-group" style="justify-content: flex-end;">
                                    <a href="<?php echo route('customers.show', $customer->id); ?>" class="btn btn-secondary btn-sm" title="View customer profile">
                                        View
                                    </a>
                                    <a href="<?php echo route('customers.edit', $customer->id); ?>" class="btn btn-secondary btn-sm" title="Edit customer">
                                        Edit
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<?php include resource_path('views/layouts/footer.php'); ?>
