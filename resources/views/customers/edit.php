<?php include resource_path('views/layouts/header.php'); ?>

<div class="page-title-row">
    <div class="page-title-group">
        <h1>Edit Customer Profile</h1>
        <p>Update contact information, corporate details, or lifecycle stage for <?php echo htmlspecialchars($customer->name, ENT_QUOTES, 'UTF-8'); ?>.</p>
    </div>
    <div>
        <a href="<?php echo route('customers.show', $customer->id); ?>" class="btn btn-secondary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Back to Customer View
        </a>
    </div>
</div>

<div class="saas-card">
    <div class="card-header-bar">
        <div>
            <h2 class="card-header-title">Update Customer Information</h2>
            <p class="card-header-desc">Record ID #<?php echo $customer->id; ?> &bull; Created by <?php echo htmlspecialchars($customer->creator->name ?? 'System', ENT_QUOTES, 'UTF-8'); ?></p>
        </div>
    </div>

    <div class="card-inner-body">
        <form method="POST" action="<?php echo route('customers.update', $customer->id); ?>" novalidate>
            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
            <input type="hidden" name="_method" value="PUT">

            <div class="form-layout-grid">
                <div class="form-group-item">
                    <label class="form-label-styled" for="name">
                        Full Name <span class="required-mark">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="name" 
                        name="name" 
                        class="form-input-styled" 
                        value="<?php echo htmlspecialchars(old('name', $customer->name), ENT_QUOTES, 'UTF-8'); ?>" 
                        required
                    >
                    <span class="input-helper-text">Primary contact's full name</span>
                </div>

                <div class="form-group-item">
                    <label class="form-label-styled" for="email">
                        Work Email <span class="required-mark">*</span>
                    </label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        class="form-input-styled" 
                        value="<?php echo htmlspecialchars(old('email', $customer->email), ENT_QUOTES, 'UTF-8'); ?>" 
                        required
                    >
                    <span class="input-helper-text">Unique corporate email address</span>
                </div>

                <div class="form-group-item">
                    <label class="form-label-styled" for="phone">
                        Phone Number
                    </label>
                    <input 
                        type="tel" 
                        id="phone" 
                        name="phone" 
                        class="form-input-styled" 
                        value="<?php echo htmlspecialchars(old('phone', $customer->phone ?? ''), ENT_QUOTES, 'UTF-8'); ?>" 
                        placeholder="+1 (555) 234-5678"
                    >
                    <span class="input-helper-text">Direct line or mobile number</span>
                </div>

                <div class="form-group-item">
                    <label class="form-label-styled" for="company">
                        Company Name
                    </label>
                    <input 
                        type="text" 
                        id="company" 
                        name="company" 
                        class="form-input-styled" 
                        value="<?php echo htmlspecialchars(old('company', $customer->company ?? ''), ENT_QUOTES, 'UTF-8'); ?>" 
                        placeholder="Company or organization"
                    >
                    <span class="input-helper-text">Organization or business name</span>
                </div>

                <div class="form-group-item form-full-width">
                    <label class="form-label-styled" for="status">
                        Lifecycle Status <span class="required-mark">*</span>
                    </label>
                    <?php $currentStatus = old('status', $customer->status); ?>
                    <select id="status" name="status" class="form-input-styled" style="max-width: 320px;" required>
                        <option value="Lead" <?php echo $currentStatus === 'Lead' ? 'selected' : ''; ?>>
                            Lead (Prospect in sales pipeline)
                        </option>
                        <option value="Active" <?php echo $currentStatus === 'Active' ? 'selected' : ''; ?>>
                            Active (Active client with ongoing engagement)
                        </option>
                        <option value="Inactive" <?php echo $currentStatus === 'Inactive' ? 'selected' : ''; ?>>
                            Inactive (Archived or paused relationship)
                        </option>
                    </select>
                </div>

                <div class="form-group-item form-full-width">
                    <label class="form-label-styled" for="notes">
                        Internal Notes &amp; Context
                    </label>
                    <textarea 
                        id="notes" 
                        name="notes" 
                        class="form-input-styled" 
                        placeholder="Add background notes, meeting summaries, deal sizes, or special instructions..."
                    ><?php echo htmlspecialchars(old('notes', $customer->notes ?? ''), ENT_QUOTES, 'UTF-8'); ?></textarea>
                    <span class="input-helper-text">Visible only to CRM team members</span>
                </div>
            </div>

            <div class="form-actions-toolbar">
                <button type="submit" class="btn btn-primary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                        <polyline points="17 21 17 13 7 13 7 21"></polyline>
                        <polyline points="7 3 7 8 15 8"></polyline>
                    </svg>
                    Update Customer
                </button>
                <a href="<?php echo route('customers.show', $customer->id); ?>" class="btn btn-secondary">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<?php include resource_path('views/layouts/footer.php'); ?>
