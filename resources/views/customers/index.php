<?php include resource_path('views/layouts/header.php'); ?>

<div class="page-title-row">
    <div class="page-title-group">
        <h1>Customer Directory</h1>
        <p>Comprehensive list of client accounts, prospect statuses, and company relationships.</p>
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

<form method="GET" action="<?php echo route('customers.index'); ?>" class="filter-toolbar-box">
    <div class="search-field-container">
        <span class="search-icon-adornment">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
        </span>
        <input 
            type="text" 
            name="search" 
            class="form-input-styled" 
            placeholder="Search by name, email or company..." 
            value="<?php echo htmlspecialchars($search, ENT_QUOTES, 'UTF-8'); ?>"
        >
    </div>

    <div class="filter-select-wrap">
        <select name="status" class="form-input-styled">
            <option value="">All Statuses</option>
            <option value="Lead" <?php echo $status === 'Lead' ? 'selected' : ''; ?>>Lead (Prospect)</option>
            <option value="Active" <?php echo $status === 'Active' ? 'selected' : ''; ?>>Active Client</option>
            <option value="Inactive" <?php echo $status === 'Inactive' ? 'selected' : ''; ?>>Inactive</option>
        </select>
    </div>

    <button type="submit" class="btn btn-primary">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="11" cy="11" r="8"></circle>
            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
        </svg>
        Filter
    </button>

    <?php if ($search !== '' || $status !== ''): ?>
        <a href="<?php echo route('customers.index'); ?>" class="btn btn-secondary">
            Reset
        </a>
    <?php endif; ?>
</form>

<div class="saas-card">
    <div class="card-header-bar">
        <div>
            <h2 class="card-header-title">
                Customer Database
            </h2>
            <p class="card-header-desc">
                Showing <?php echo $customers->total(); ?> total customer record<?php echo $customers->total() === 1 ? '' : 's'; ?>
                <?php if ($search !== ''): ?>
                    matching "<?php echo htmlspecialchars($search, ENT_QUOTES, 'UTF-8'); ?>"
                <?php endif; ?>
            </p>
        </div>
    </div>

    <div class="table-responsive-container">
        <?php if ($customers->isEmpty()): ?>
            <div class="empty-state-card">
                <div class="empty-state-graphic">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                </div>
                <div class="empty-state-title">No customers match your criteria</div>
                <p class="empty-state-subtitle">Try refining your search keyword or clearing the status filter.</p>
                <?php if ($search !== '' || $status !== ''): ?>
                    <a href="<?php echo route('customers.index'); ?>" class="btn btn-secondary btn-sm">
                        Clear Search &amp; Filters
                    </a>
                <?php else: ?>
                    <a href="<?php echo route('customers.create'); ?>" class="btn btn-primary btn-sm">
                        Add First Customer
                    </a>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <table class="saas-table">
                <thead>
                    <tr>
                        <th style="width: 60px;">ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Company</th>
                        <th>Status</th>
                        <th>Created By</th>
                        <th style="text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($customers as $customer): ?>
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
                                <span style="font-family: monospace; font-size: 12px; color: var(--color-text-muted);">
                                    #<?php echo $customer->id; ?>
                                </span>
                            </td>
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
                                <span style="font-size: 13px; color: var(--color-text-secondary);">
                                    <?php echo htmlspecialchars($customer->phone ?: '—', ENT_QUOTES, 'UTF-8'); ?>
                                </span>
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
                            <td style="text-align: right;">
                                <div class="table-actions-group" style="justify-content: flex-end;">
                                    <a href="<?php echo route('customers.show', $customer->id); ?>" class="btn btn-secondary btn-sm" title="View Profile">
                                        View
                                    </a>
                                    <a href="<?php echo route('customers.edit', $customer->id); ?>" class="btn btn-secondary btn-sm" title="Edit Customer">
                                        Edit
                                    </a>

                                    <?php if (auth()->user()->isAdmin() || auth()->user()->id === $customer->created_by): ?>
                                        <form 
                                            method="POST" 
                                            action="<?php echo route('customers.destroy', $customer->id); ?>" 
                                            style="display: inline-block; margin: 0;"
                                            onsubmit="return confirm('Are you sure you want to delete customer &quot;<?php echo htmlspecialchars($customer->name, ENT_QUOTES, 'UTF-8'); ?>&quot;?');"
                                        >
                                            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                                            <input type="hidden" name="_method" value="DELETE">
                                            <button type="submit" class="btn btn-danger btn-sm" title="Delete Customer">
                                                Delete
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <?php if ($customers->hasPages()): ?>
        <div class="card-footer-bar">
            <div style="font-size: 13px; color: var(--color-text-muted);">
                Page <strong><?php echo $customers->currentPage(); ?></strong> of <strong><?php echo $customers->lastPage(); ?></strong>
            </div>
            <div style="display: flex; gap: 8px;">
                <?php if ($customers->onFirstPage()): ?>
                    <span class="btn btn-secondary btn-sm" style="opacity: 0.5; cursor: not-allowed;">Previous</span>
                <?php else: ?>
                    <a href="<?php echo $customers->previousPageUrl(); ?>" class="btn btn-secondary btn-sm">Previous</a>
                <?php endif; ?>

                <?php if ($customers->hasMorePages()): ?>
                    <a href="<?php echo $customers->nextPageUrl(); ?>" class="btn btn-secondary btn-sm">Next</a>
                <?php else: ?>
                    <span class="btn btn-secondary btn-sm" style="opacity: 0.5; cursor: not-allowed;">Next</span>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include resource_path('views/layouts/footer.php'); ?>
