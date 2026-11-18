<?php 
include resource_path('views/layouts/header.php');

$avatarInitial = strtoupper(substr($customer->name, 0, 1));
$statusClass = match($customer->status) {
    'Active' => 'status-active',
    'Lead' => 'status-lead',
    default => 'status-inactive',
};
?>

<div class="page-title-row">
    <div class="page-title-group" style="display: flex; align-items: center; gap: 16px;">
        <div class="customer-avatar-badge" style="width: 48px; height: 48px; font-size: 18px; border-radius: var(--radius-lg);">
            <?php echo htmlspecialchars($avatarInitial, ENT_QUOTES, 'UTF-8'); ?>
        </div>
        <div>
            <h1><?php echo htmlspecialchars($customer->name, ENT_QUOTES, 'UTF-8'); ?></h1>
            <div style="display: flex; align-items: center; gap: 10px; margin-top: 4px;">
                <span class="status-pill <?php echo $statusClass; ?>">
                    <span class="status-dot"></span>
                    <span><?php echo htmlspecialchars($customer->status, ENT_QUOTES, 'UTF-8'); ?></span>
                </span>
                <span style="font-size: 13px; color: var(--color-text-muted);">
                    ID #<?php echo $customer->id; ?>
                </span>
                <?php if ($customer->company): ?>
                    <span style="font-size: 13px; color: var(--color-text-muted);">&bull;</span>
                    <span style="font-size: 13px; font-weight: 500; color: var(--color-text-secondary);">
                        <?php echo htmlspecialchars($customer->company, ENT_QUOTES, 'UTF-8'); ?>
                    </span>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="table-actions-group">
        <a href="<?php echo route('customers.edit', $customer->id); ?>" class="btn btn-primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
            </svg>
            Edit Profile
        </a>

        <?php if (auth()->user()->isAdmin() || auth()->user()->id === $customer->created_by): ?>
            <form 
                method="POST" 
                action="<?php echo route('customers.destroy', $customer->id); ?>" 
                style="display: inline-block; margin: 0;"
                onsubmit="return confirm('Are you sure you want to permanently delete customer &quot;<?php echo htmlspecialchars($customer->name, ENT_QUOTES, 'UTF-8'); ?>&quot;?');"
            >
                <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                <input type="hidden" name="_method" value="DELETE">
                <button type="submit" class="btn btn-danger" title="Delete customer">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="3 6 5 6 21 6"></polyline>
                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                    </svg>
                    Delete
                </button>
            </form>
        <?php endif; ?>

        <a href="<?php echo route('customers.index'); ?>" class="btn btn-secondary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Back to List
        </a>
    </div>
</div>

<div class="detail-layout-columns">
    <div>
        <div class="saas-card">
            <div class="card-header-bar">
                <div>
                    <h2 class="card-header-title">Contact &amp; Account Details</h2>
                    <p class="card-header-desc">Direct communications and corporate details</p>
                </div>
            </div>
            <div class="card-inner-body">
                <div class="detail-info-grid">
                    <div class="detail-data-item">
                        <span class="detail-data-label">Full Contact Name</span>
                        <span class="detail-data-value"><?php echo htmlspecialchars($customer->name, ENT_QUOTES, 'UTF-8'); ?></span>
                    </div>

                    <div class="detail-data-item">
                        <span class="detail-data-label">Corporate Email</span>
                        <span class="detail-data-value">
                            <a href="mailto:<?php echo htmlspecialchars($customer->email, ENT_QUOTES, 'UTF-8'); ?>">
                                <?php echo htmlspecialchars($customer->email, ENT_QUOTES, 'UTF-8'); ?>
                            </a>
                        </span>
                    </div>

                    <div class="detail-data-item">
                        <span class="detail-data-label">Phone Number</span>
                        <span class="detail-data-value">
                            <?php if ($customer->phone): ?>
                                <a href="tel:<?php echo htmlspecialchars($customer->phone, ENT_QUOTES, 'UTF-8'); ?>">
                                    <?php echo htmlspecialchars($customer->phone, ENT_QUOTES, 'UTF-8'); ?>
                                </a>
                            <?php else: ?>
                                <span style="color: var(--color-text-muted); font-style: italic;">Not provided</span>
                            <?php endif; ?>
                        </span>
                    </div>

                    <div class="detail-data-item">
                        <span class="detail-data-label">Company / Organization</span>
                        <span class="detail-data-value">
                            <?php echo htmlspecialchars($customer->company ?: 'Not provided', ENT_QUOTES, 'UTF-8'); ?>
                        </span>
                    </div>
                </div>

                <div class="detail-notes-container">
                    <span class="detail-data-label">Internal Account Notes</span>
                    <?php if (!empty($customer->notes)): ?>
                        <div class="notes-formatted-box"><?php echo htmlspecialchars($customer->notes, ENT_QUOTES, 'UTF-8'); ?></div>
                    <?php else: ?>
                        <div class="notes-formatted-box" style="color: var(--color-text-muted); font-style: italic;">
                            No internal notes recorded for this customer.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div>
        <div class="saas-card">
            <div class="card-header-bar">
                <h2 class="card-header-title">System Audit Log</h2>
            </div>
            <div class="card-inner-body">
                <div class="meta-timeline-list">
                    <div class="meta-timeline-row">
                        <span class="detail-data-label">Account Owner / Created By</span>
                        <span class="detail-data-value">
                            <?php echo htmlspecialchars($customer->creator->name ?? 'System', ENT_QUOTES, 'UTF-8'); ?>
                            <?php if ($customer->creator): ?>
                                <span class="user-role-tag" style="margin-left: 6px;">
                                    <?php echo htmlspecialchars($customer->creator->role, ENT_QUOTES, 'UTF-8'); ?>
                                </span>
                            <?php endif; ?>
                        </span>
                    </div>

                    <div class="meta-timeline-row">
                        <span class="detail-data-label">Date Created</span>
                        <span class="detail-data-value" style="font-size: 13.5px; color: var(--color-text-secondary);">
                            <?php echo $customer->created_at ? $customer->created_at->format('M d, Y &bull; H:i') : '—'; ?>
                        </span>
                    </div>

                    <div class="meta-timeline-row">
                        <span class="detail-data-label">Last Activity / Updated</span>
                        <span class="detail-data-value" style="font-size: 13.5px; color: var(--color-text-secondary);">
                            <?php echo $customer->updated_at ? $customer->updated_at->format('M d, Y &bull; H:i') : '—'; ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include resource_path('views/layouts/footer.php'); ?>
