<?php include_once __DIR__ . '/../layouts/admin-header.php'; ?>

<style>
    /* Page Header */
    .page-header {
        margin-bottom: 24px;
    }

    .page-title {
        font-family: 'Playfair Display', serif;
        font-size: 28px;
        font-weight: 700;
        color: #1F2937;
        margin: 0 0 4px 0;
    }

    .page-subtitle {
        font-size: 13px;
        color: #9CA3AF;
        margin: 0;
    }

    /* Settings Layout */
    .settings-layout {
        display: grid;
        grid-template-columns: 1.5fr 1fr;
        gap: 24px;
        margin-bottom: 30px;
    }

    /* Settings Card */
    .settings-card {
        background: white;
        border-radius: 12px;
        padding: 24px;
        border: 1px solid #E5E7EB;
        margin-bottom: 24px;
    }

    .settings-card-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 20px;
        padding-bottom: 16px;
        border-bottom: 2px solid #F3F4F6;
    }

    .settings-card-icon {
        width: 40px;
        height: 40px;
        background: #EDE9FE;
        color: #7F3D9E;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }

    .settings-card-title {
        font-size: 18px;
        font-weight: 700;
        color: #1F2937;
    }

    /* Form Elements */
    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        font-size: 13px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 8px;
        display: block;
    }

    .form-input {
        width: 100%;
        padding: 10px 16px;
        border: 1px solid #E5E7EB;
        border-radius: 8px;
        font-size: 14px;
        color: #1F2937;
        transition: all 0.2s;
    }

    .form-input:focus {
        outline: none;
        border-color: #7F3D9E;
        box-shadow: 0 0 0 3px rgba(127, 61, 158, 0.1);
    }

    .form-help {
        font-size: 12px;
        color: #9CA3AF;
        margin-top: 4px;
    }

    /* Toggle Switch */
    .toggle-group {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 16px;
        background: #F9FAFB;
        border-radius: 10px;
        margin-bottom: 12px;
        transition: all 0.2s;
    }

    .toggle-group:hover {
        background: #F3F4F6;
    }

    .toggle-info {
        flex: 1;
    }

    .toggle-label {
        font-size: 14px;
        font-weight: 600;
        color: #1F2937;
        margin-bottom: 4px;
    }

    .toggle-description {
        font-size: 12px;
        color: #6B7280;
    }

    .toggle-switch {
        position: relative;
        width: 48px;
        height: 24px;
    }

    .toggle-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .toggle-slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #D1D5DB;
        transition: .4s;
        border-radius: 24px;
    }

    .toggle-slider:before {
        position: absolute;
        content: "";
        height: 18px;
        width: 18px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }

    .toggle-switch input:checked + .toggle-slider {
        background-color: #7F3D9E;
    }

    .toggle-switch input:checked + .toggle-slider:before {
        transform: translateX(24px);
    }

    /* Quick Links */
    .quick-links-card {
        background: white;
        border-radius: 12px;
        padding: 24px;
        border: 1px solid #E5E7EB;
        margin-bottom: 24px;
    }

    .quick-link-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 16px;
        background: #F9FAFB;
        border-radius: 10px;
        margin-bottom: 12px;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        color: #1F2937;
    }

    .quick-link-item:hover {
        background: #8B5CF6;
        color: white;
        transform: translateX(4px);
    }

    .quick-link-content {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .quick-link-icon {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        transition: all 0.2s;
    }

    .quick-link-icon.success {
        background: #D1FAE5;
        color: #10B981;
    }

    .quick-link-icon.warning {
        background: #FEF3C7;
        color: #F59E0B;
    }

    .quick-link-icon.info {
        background: #DBEAFE;
        color: #3B82F6;
    }

    .quick-link-icon.primary {
        background: #EDE9FE;
        color: #8B5CF6;
    }

    .quick-link-item:hover .quick-link-icon {
        background: rgba(255, 255, 255, 0.2);
        color: white;
    }

    .quick-link-arrow {
        color: #9CA3AF;
        transition: all 0.2s;
    }

    .quick-link-item:hover .quick-link-arrow {
        color: white;
    }

    /* Status Card */
    .status-card {
        background: linear-gradient(135deg, #7F3D9E 0%, #7F3D9E 100%);
        border-radius: 12px;
        padding: 24px;
        color: white;
        margin-bottom: 24px;
    }

    .status-title {
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 20px;
    }

    .status-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .status-item:last-child {
        border-bottom: none;
    }

    .status-label {
        font-size: 14px;
        opacity: 0.9;
    }

    .status-badge {
        padding: 4px 12px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
    }

    .status-badge.active {
        background: rgba(16, 185, 129, 0.2);
        color: #D1FAE5;
    }

    .status-badge.inactive {
        background: rgba(255, 255, 255, 0.2);
        color: rgba(255, 255, 255, 0.8);
    }

    .status-badge.warning {
        background: rgba(245, 158, 11, 0.2);
        color: #FEF3C7;
    }

    /* Help Card */
    .help-card {
        background: white;
        border-radius: 12px;
        padding: 24px;
        border: 1px solid #E5E7EB;
        margin-bottom: 24px;
    }

    .help-section {
        margin-bottom: 20px;
    }

    .help-section:last-child {
        margin-bottom: 0;
    }

    .help-heading {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        font-weight: 700;
        color: #1F2937;
        margin-bottom: 8px;
    }

    .help-text {
        font-size: 13px;
        color: #6B7280;
        line-height: 1.6;
    }

    .help-alert {
        background: #FEF3C7;
        border-left: 4px solid #F59E0B;
        padding: 12px 16px;
        border-radius: 8px;
        margin-top: 16px;
    }

    .help-alert-content {
        display: flex;
        gap: 12px;
    }

    .help-alert-icon {
        color: #F59E0B;
        font-size: 18px;
    }

    .help-alert-text {
        flex: 1;
    }

    .help-alert-title {
        font-size: 13px;
        font-weight: 700;
        color: #92400E;
        margin-bottom: 4px;
    }

    .help-alert-description {
        font-size: 12px;
        color: #92400E;
    }

    /* Save Button */
    .save-actions {
        background: white;
        border-radius: 12px;
        padding: 24px;
        border: 1px solid #E5E7EB;
        text-align: center;
        margin-bottom: 24px;
    }

    .btn-save {
        background: #7F3D9E;
        color: white;
        border: none;
        padding: 12px 32px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-save:hover {
        background: #7F3D9E;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(127, 61, 158, 0.3);
    }

    .btn-reset {
        background: #F3F4F6;
        color: #6B7280;
        border: none;
        padding: 12px 32px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-left: 12px;
    }

    .btn-reset:hover {
        background: #E5E7EB;
    }

    @media (max-width: 1200px) {
        .settings-layout {
            grid-template-columns: 1fr;
        }
    }
</style>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h1 class="h3 mb-0"><i class="fas fa-cog me-2"></i>System Settings</h1>
        <p class="text-muted small mb-0">Configure system-wide settings and preferences</p>
    </div>
    <button type="submit" form="settingsForm" class="btn btn-primary btn-sm">
        <i class="fas fa-save me-2"></i>Save Settings
    </button>
</div>

<!-- Settings Tabs -->
<ul class="nav nav-tabs mb-4" id="settingsTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button" role="tab">
            <i class="fas fa-sliders-h"></i> General
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="email-tab" data-bs-toggle="tab" data-bs-target="#email" type="button" role="tab">
            <i class="fas fa-envelope"></i> Email Configuration
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="sms-tab" data-bs-toggle="tab" data-bs-target="#sms" type="button" role="tab">
            <i class="fas fa-comment"></i> SMS Configuration
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="payment-tab" data-bs-toggle="tab" data-bs-target="#payment" type="button" role="tab">
            <i class="fas fa-credit-card"></i> Payment Settings
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="notification-tab" data-bs-toggle="tab" data-bs-target="#notification" type="button" role="tab">
            <i class="fas fa-bell"></i> Notifications
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="security-tab" data-bs-toggle="tab" data-bs-target="#security" type="button" role="tab">
            <i class="fas fa-shield-alt"></i> Security
        </button>
    </li>
</ul>

<!-- Settings Form -->
<form method="POST" action="/admin/settings" id="settingsForm">
    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
    
    <!-- Tab Content -->
    <div class="tab-content" id="settingsTabContent">
        
        <!-- General Tab -->
        <div class="tab-pane fade show active" id="general" role="tabpanel">
            <!-- General Settings -->
            <div class="settings-card">
                <div class="settings-card-header">
                    <div class="settings-card-icon">
                        <i class="fas fa-cog"></i>
                    </div>
                    <div class="settings-card-title">General Settings</div>
                </div>
                
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
                    <div class="form-group">
                        <label class="form-label" for="app_name">Application Name</label>
                        <input type="text" class="form-input" id="app_name" name="app_name" 
                               value="<?php echo htmlspecialchars($settings['app_name'] ?? 'Shena Companion Welfare'); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="admin_email">Admin Email</label>
                        <input type="email" class="form-input" id="admin_email" name="admin_email" 
                               value="<?php echo htmlspecialchars($settings['admin_email'] ?? ''); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="session_timeout">Session Timeout (seconds)</label>
                        <input type="number" class="form-input" id="session_timeout" name="session_timeout" 
                               value="<?php echo $settings['session_timeout'] ?? 3600; ?>" min="300" max="86400" required>
                        <div class="form-help">Minimum: 300 (5 minutes), Maximum: 86400 (24 hours)</div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="max_upload_size">Max Upload Size</label>
                        <select class="form-input" id="max_upload_size" name="max_upload_size">
                            <option value="1MB" <?php echo ($settings['max_upload_size'] ?? '2MB') === '1MB' ? 'selected' : ''; ?>>1 MB</option>
                            <option value="2MB" <?php echo ($settings['max_upload_size'] ?? '2MB') === '2MB' ? 'selected' : ''; ?>>2 MB</option>
                            <option value="5MB" <?php echo ($settings['max_upload_size'] ?? '2MB') === '5MB' ? 'selected' : ''; ?>>5 MB</option>
                            <option value="10MB" <?php echo ($settings['max_upload_size'] ?? '2MB') === '10MB' ? 'selected' : ''; ?>>10 MB</option>
                            <option value="20MB" <?php echo ($settings['max_upload_size'] ?? '2MB') === '20MB' ? 'selected' : ''; ?>>20 MB</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Feature Settings -->
            <div class="settings-card">
                <div class="settings-card-header">
                    <div class="settings-card-icon">
                        <i class="fas fa-toggle-on"></i>
                    </div>
                    <div class="settings-card-title">Feature Settings</div>
                </div>
                
                <div class="toggle-group">
                    <div class="toggle-info">
                        <div class="toggle-label">Email Notifications</div>
                        <div class="toggle-description">Enable email notifications for members and admins</div>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" id="email_enabled" name="email_enabled" 
                               <?php echo !empty($settings['email_enabled']) ? 'checked' : ''; ?>>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
                
                <div class="toggle-group">
                    <div class="toggle-info">
                        <div class="toggle-label">SMS Notifications</div>
                        <div class="toggle-description">Enable SMS notifications via Twilio</div>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" id="sms_enabled" name="sms_enabled" 
                               <?php echo !empty($settings['sms_enabled']) ? 'checked' : ''; ?>>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
                
                <div class="toggle-group">
                    <div class="toggle-info">
                        <div class="toggle-label">M-Pesa Integration</div>
                        <div class="toggle-description">Enable M-Pesa payment processing</div>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" id="mpesa_enabled" name="mpesa_enabled" 
                               <?php echo !empty($settings['mpesa_enabled']) ? 'checked' : ''; ?>>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
                
                <div class="toggle-group">
                    <div class="toggle-info">
                        <div class="toggle-label">Maintenance Mode</div>
                        <div class="toggle-description" style="color: #F59E0B;">⚠️ Disable public access to the website</div>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" id="maintenance_mode" name="maintenance_mode" 
                               <?php echo !empty($settings['maintenance_mode']) ? 'checked' : ''; ?>>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
            </div>

            <!-- Membership Settings -->
            <div class="settings-card">
                <div class="settings-card-header">
                    <div class="settings-card-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="settings-card-title">Membership Settings</div>
                </div>
                
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
                    <div class="form-group">
                        <label class="form-label" for="default_package">Default Package</label>
                        <select class="form-input" id="default_package" name="default_package">
                            <option value="individual" <?php echo ($settings['default_package'] ?? 'individual') === 'individual' ? 'selected' : ''; ?>>Individual</option>
                            <option value="family" <?php echo ($settings['default_package'] ?? 'individual') === 'family' ? 'selected' : ''; ?>>Family</option>
                            <option value="premium" <?php echo ($settings['default_package'] ?? 'individual') === 'premium' ? 'selected' : ''; ?>>Premium</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="base_contribution">Base Monthly Contribution (KES)</label>
                        <input type="number" class="form-input" id="base_contribution" name="base_contribution" 
                               value="<?php echo $settings['base_contribution'] ?? 500; ?>" min="100" max="10000" required>
                        <div class="form-help">Base amount before age and package adjustments</div>
                    </div>
                </div>
            </div>

            <!-- Save Actions -->
            <div class="save-actions">
                <button type="submit" class="btn-save">
                    <i class="fas fa-save"></i>
                    <span>Save Settings</span>
                </button>
                <button type="reset" class="btn-reset">
                    <i class="fas fa-undo"></i>
                    <span>Reset</span>
                </button>
            </div>
        </div>
        <!-- End General Tab -->
        
        <!-- Email Configuration Tab -->
        <div class="tab-pane fade" id="email" role="tabpanel">
            <div class="settings-card">
                <div class="settings-card-header">
                    <div class="settings-card-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="settings-card-title">Email Configuration</div>
                </div>
                
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
                    <div class="form-group">
                        <label class="form-label" for="smtp_host">SMTP Host</label>
                        <input type="text" class="form-input" id="smtp_host" name="smtp_host" 
                               value="<?php echo htmlspecialchars($settings['smtp_host'] ?? ''); ?>" placeholder="smtp.gmail.com">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="smtp_port">SMTP Port</label>
                        <input type="number" class="form-input" id="smtp_port" name="smtp_port" 
                               value="<?php echo $settings['smtp_port'] ?? '587'; ?>" placeholder="587">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="smtp_username">SMTP Username</label>
                        <input type="text" class="form-input" id="smtp_username" name="smtp_username" 
                               value="<?php echo htmlspecialchars($settings['smtp_username'] ?? ''); ?>" placeholder="your-email@gmail.com">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="smtp_password">SMTP Password</label>
                        <input type="password" class="form-input" id="smtp_password" name="smtp_password" 
                               value="<?php echo htmlspecialchars($settings['smtp_password'] ?? ''); ?>" placeholder="Enter SMTP password">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="smtp_encryption">Encryption</label>
                        <select class="form-input" id="smtp_encryption" name="smtp_encryption">
                            <option value="tls" <?php echo ($settings['smtp_encryption'] ?? 'tls') === 'tls' ? 'selected' : ''; ?>>TLS</option>
                            <option value="ssl" <?php echo ($settings['smtp_encryption'] ?? 'tls') === 'ssl' ? 'selected' : ''; ?>>SSL</option>
                            <option value="none" <?php echo ($settings['smtp_encryption'] ?? 'tls') === 'none' ? 'selected' : ''; ?>>None</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="from_email">From Email</label>
                        <input type="email" class="form-input" id="from_email" name="from_email" 
                               value="<?php echo htmlspecialchars($settings['from_email'] ?? ''); ?>" placeholder="noreply@example.com">
                    </div>
                    
                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label class="form-label" for="from_name">From Name</label>
                        <input type="text" class="form-input" id="from_name" name="from_name" 
                               value="<?php echo htmlspecialchars($settings['from_name'] ?? 'Shena Companion Welfare'); ?>" placeholder="Shena Companion Welfare">
                    </div>
                </div>
                
                <div class="save-actions">
                    <button type="submit" class="btn-save">
                        <i class="fas fa-save"></i>
                        <span>Save Email Settings</span>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- SMS Configuration Tab -->
        <div class="tab-pane fade" id="sms" role="tabpanel">
            <div class="settings-card">
                <div class="settings-card-header">
                    <div class="settings-card-icon">
                        <i class="fas fa-comment"></i>
                    </div>
                    <div class="settings-card-title">SMS Configuration (Twilio)</div>
                </div>
                
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
                    <div class="form-group">
                        <label class="form-label" for="twilio_account_sid">Twilio Account SID</label>
                        <input type="text" class="form-input" id="twilio_account_sid" name="twilio_account_sid" 
                               value="<?php echo htmlspecialchars($settings['twilio_account_sid'] ?? ''); ?>" placeholder="ACxxxxxxxxxxxxxxxxxxxxxxxxxxxxx">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="twilio_auth_token">Twilio Auth Token</label>
                        <input type="password" class="form-input" id="twilio_auth_token" name="twilio_auth_token" 
                               value="<?php echo htmlspecialchars($settings['twilio_auth_token'] ?? ''); ?>" placeholder="Enter auth token">
                    </div>
                    
                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label class="form-label" for="twilio_phone_number">Twilio Phone Number</label>
                        <input type="text" class="form-input" id="twilio_phone_number" name="twilio_phone_number" 
                               value="<?php echo htmlspecialchars($settings['twilio_phone_number'] ?? ''); ?>" placeholder="+1234567890">
                        <div class="form-help">Use the format +[country code][number]</div>
                    </div>
                </div>
                
                <div class="save-actions">
                    <button type="submit" class="btn-save">
                        <i class="fas fa-save"></i>
                        <span>Save SMS Settings</span>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Payment Settings Tab -->
        <div class="tab-pane fade" id="payment" role="tabpanel">
            <div class="settings-card">
                <div class="settings-card-header">
                    <div class="settings-card-icon">
                        <i class="fas fa-credit-card"></i>
                    </div>
                    <div class="settings-card-title">Payment Settings</div>
                </div>
                
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
                    <div class="form-group">
                        <label class="form-label" for="payment_deadline_days">Payment Grace Period (Days)</label>
                        <input type="number" class="form-input" id="payment_deadline_days" name="payment_deadline_days" 
                               value="<?php echo $settings['payment_deadline_days'] ?? 7; ?>" min="1" max="30">
                        <div class="form-help">Days after due date before marking payment as overdue</div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="late_payment_penalty">Late Payment Penalty (%)</label>
                        <input type="number" class="form-input" id="late_payment_penalty" name="late_payment_penalty" 
                               value="<?php echo $settings['late_payment_penalty'] ?? 0; ?>" min="0" max="50" step="0.5">
                        <div class="form-help">Percentage penalty for late payments</div>
                    </div>
                    
                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label class="form-label" for="payment_methods">Accepted Payment Methods</label>
                        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; margin-top: 10px;">
                            <div class="toggle-group" style="border: none; padding: 12px; background: #F9FAFB; border-radius: 8px;">
                                <div class="toggle-info">
                                    <div class="toggle-label" style="font-size: 14px;">M-Pesa</div>
                                </div>
                                <label class="toggle-switch" style="transform: scale(0.8);">
                                    <input type="checkbox" name="payment_methods[]" value="mpesa" 
                                           <?php echo in_array('mpesa', $settings['payment_methods'] ?? ['mpesa']) ? 'checked' : ''; ?>>
                                    <span class="toggle-slider"></span>
                                </label>
                            </div>
                            <div class="toggle-group" style="border: none; padding: 12px; background: #F9FAFB; border-radius: 8px;">
                                <div class="toggle-info">
                                    <div class="toggle-label" style="font-size: 14px;">Bank Transfer</div>
                                </div>
                                <label class="toggle-switch" style="transform: scale(0.8);">
                                    <input type="checkbox" name="payment_methods[]" value="bank" 
                                           <?php echo in_array('bank', $settings['payment_methods'] ?? ['bank']) ? 'checked' : ''; ?>>
                                    <span class="toggle-slider"></span>
                                </label>
                            </div>
                            <div class="toggle-group" style="border: none; padding: 12px; background: #F9FAFB; border-radius: 8px;">
                                <div class="toggle-info">
                                    <div class="toggle-label" style="font-size: 14px;">Cash</div>
                                </div>
                                <label class="toggle-switch" style="transform: scale(0.8);">
                                    <input type="checkbox" name="payment_methods[]" value="cash" 
                                           <?php echo in_array('cash', $settings['payment_methods'] ?? ['cash']) ? 'checked' : ''; ?>>
                                    <span class="toggle-slider"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="save-actions">
                    <button type="submit" class="btn-save">
                        <i class="fas fa-save"></i>
                        <span>Save Payment Settings</span>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Notifications Tab -->
        <div class="tab-pane fade" id="notification" role="tabpanel">
            <div class="settings-card">
                <div class="settings-card-header">
                    <div class="settings-card-icon">
                        <i class="fas fa-bell"></i>
                    </div>
                    <div class="settings-card-title">Notification Preferences</div>
                </div>
                
                <div class="toggle-group">
                    <div class="toggle-info">
                        <div class="toggle-label">New Member Registration</div>
                        <div class="toggle-description">Notify admins when a new member registers</div>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" name="notify_new_member" 
                               <?php echo !empty($settings['notify_new_member']) ? 'checked' : ''; ?>>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
                
                <div class="toggle-group">
                    <div class="toggle-info">
                        <div class="toggle-label">Payment Received</div>
                        <div class="toggle-description">Notify member when payment is received</div>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" name="notify_payment_received" 
                               <?php echo !empty($settings['notify_payment_received']) ? 'checked' : ''; ?>>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
                
                <div class="toggle-group">
                    <div class="toggle-info">
                        <div class="toggle-label">Payment Reminder</div>
                        <div class="toggle-description">Send payment reminders before due date</div>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" name="notify_payment_reminder" 
                               <?php echo !empty($settings['notify_payment_reminder']) ? 'checked' : ''; ?>>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
                
                <div class="toggle-group">
                    <div class="toggle-info">
                        <div class="toggle-label">Claim Submitted</div>
                        <div class="toggle-description">Notify admins when a claim is submitted</div>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" name="notify_claim_submitted" 
                               <?php echo !empty($settings['notify_claim_submitted']) ? 'checked' : ''; ?>>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
                
                <div class="toggle-group">
                    <div class="toggle-info">
                        <div class="toggle-label">Claim Status Update</div>
                        <div class="toggle-description">Notify member when claim status changes</div>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" name="notify_claim_status" 
                               <?php echo !empty($settings['notify_claim_status']) ? 'checked' : ''; ?>>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
                
                <div class="form-group" style="margin-top: 20px;">
                    <label class="form-label" for="reminder_days_before">Reminder Days Before Due Date</label>
                    <input type="number" class="form-input" id="reminder_days_before" name="reminder_days_before" 
                           value="<?php echo $settings['reminder_days_before'] ?? 3; ?>" min="1" max="14">
                    <div class="form-help">Number of days before payment due date to send reminder</div>
                </div>
                
                <div class="save-actions">
                    <button type="submit" class="btn-save">
                        <i class="fas fa-save"></i>
                        <span>Save Notification Settings</span>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Security Tab -->
        <div class="tab-pane fade" id="security" role="tabpanel">
            <div class="settings-card">
                <div class="settings-card-header">
                    <div class="settings-card-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <div class="settings-card-title">Security Settings</div>
                </div>
                
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
                    <div class="form-group">
                        <label class="form-label" for="max_login_attempts">Max Login Attempts</label>
                        <input type="number" class="form-input" id="max_login_attempts" name="max_login_attempts" 
                               value="<?php echo $settings['max_login_attempts'] ?? 5; ?>" min="3" max="10">
                        <div class="form-help">Number of failed login attempts before account lockout</div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="lockout_duration">Lockout Duration (minutes)</label>
                        <input type="number" class="form-input" id="lockout_duration" name="lockout_duration" 
                               value="<?php echo $settings['lockout_duration'] ?? 30; ?>" min="5" max="120">
                        <div class="form-help">Duration to lock account after max attempts</div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="password_min_length">Minimum Password Length</label>
                        <input type="number" class="form-input" id="password_min_length" name="password_min_length" 
                               value="<?php echo $settings['password_min_length'] ?? 8; ?>" min="6" max="20">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="password_expiry_days">Password Expiry (days)</label>
                        <input type="number" class="form-input" id="password_expiry_days" name="password_expiry_days" 
                               value="<?php echo $settings['password_expiry_days'] ?? 90; ?>" min="30" max="365">
                        <div class="form-help">0 = never expire</div>
                    </div>
                </div>
                
                <div class="toggle-group">
                    <div class="toggle-info">
                        <div class="toggle-label">Require Strong Passwords</div>
                        <div class="toggle-description">Enforce uppercase, lowercase, numbers, and special characters</div>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" name="require_strong_password" 
                               <?php echo !empty($settings['require_strong_password']) ? 'checked' : ''; ?>>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
                
                <div class="toggle-group">
                    <div class="toggle-info">
                        <div class="toggle-label">Two-Factor Authentication</div>
                        <div class="toggle-description">Require 2FA for admin accounts</div>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" name="require_2fa" 
                               <?php echo !empty($settings['require_2fa']) ? 'checked' : ''; ?>>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
                
                <div class="toggle-group">
                    <div class="toggle-info">
                        <div class="toggle-label">IP Whitelisting</div>
                        <div class="toggle-description">Restrict admin access to specific IP addresses</div>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" name="enable_ip_whitelist" 
                               <?php echo !empty($settings['enable_ip_whitelist']) ? 'checked' : ''; ?>>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
                
                <div class="form-group" style="margin-top: 20px;">
                    <label class="form-label" for="allowed_ips">Allowed IP Addresses</label>
                    <textarea class="form-input" id="allowed_ips" name="allowed_ips" rows="3" 
                              placeholder="Enter IP addresses, one per line"><?php echo htmlspecialchars($settings['allowed_ips'] ?? ''); ?></textarea>
                    <div class="form-help">Leave empty to allow all IPs</div>
                </div>
                
                <div class="save-actions">
                    <button type="submit" class="btn-save">
                        <i class="fas fa-save"></i>
                        <span>Save Security Settings</span>
                    </button>
                </div>
            </div>
        </div>
        
        </form>
    </div>
    
    <!-- Right Column: Quick Links & Status -->
    <div>
        <!-- Quick Configuration -->
        <div class="quick-links-card">
            <div class="settings-card-header">
                <div class="settings-card-icon">
                    <i class="fas fa-bolt"></i>
                </div>
                <div class="settings-card-title">Quick Configuration</div>
            </div>
            
            <a href="/admin/mpesa-config" class="quick-link-item">
                <div class="quick-link-content">
                    <div class="quick-link-icon success">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <span>M-Pesa Configuration</span>
                </div>
                <i class="fas fa-chevron-right quick-link-arrow"></i>
            </a>
            
            <a href="/admin/notification-settings" class="quick-link-item">
                <div class="quick-link-content">
                    <div class="quick-link-icon warning">
                        <i class="fas fa-bell"></i>
                    </div>
                    <span>Notification Settings</span>
                </div>
                <i class="fas fa-chevron-right quick-link-arrow"></i>
            </a>
            
            <a href="/admin/email-campaigns" class="quick-link-item">
                <div class="quick-link-content">
                    <div class="quick-link-icon info">
                        <i class="fas fa-mail-bulk"></i>
                    </div>
                    <span>Email Campaigns</span>
                </div>
                <i class="fas fa-chevron-right quick-link-arrow"></i>
            </a>
            
            <a href="/admin/bulk-sms" class="quick-link-item">
                <div class="quick-link-content">
                    <div class="quick-link-icon primary">
                        <i class="fas fa-sms"></i>
                    </div>
                    <span>SMS Campaigns</span>
                </div>
                <i class="fas fa-chevron-right quick-link-arrow"></i>
            </a>
        </div>
        
        <!-- Current System Status -->
        <div class="status-card">
            <div class="status-title">Current System Status</div>
            
            <div class="status-item">
                <span class="status-label">Email Service</span>
                <span class="status-badge <?php echo !empty($settings['email_enabled']) ? 'active' : 'inactive'; ?>">
                    <?php echo !empty($settings['email_enabled']) ? 'Enabled' : 'Disabled'; ?>
                </span>
            </div>
            
            <div class="status-item">
                <span class="status-label">SMS Service</span>
                <span class="status-badge <?php echo !empty($settings['sms_enabled']) ? 'active' : 'inactive'; ?>">
                    <?php echo !empty($settings['sms_enabled']) ? 'Enabled' : 'Disabled'; ?>
                </span>
            </div>
            
            <div class="status-item">
                <span class="status-label">M-Pesa Integration</span>
                <span class="status-badge <?php echo !empty($settings['mpesa_enabled']) ? 'active' : 'inactive'; ?>">
                    <?php echo !empty($settings['mpesa_enabled']) ? 'Enabled' : 'Disabled'; ?>
                </span>
            </div>
            
            <div class="status-item">
                <span class="status-label">Maintenance Mode</span>
                <span class="status-badge <?php echo !empty($settings['maintenance_mode']) ? 'warning' : 'active'; ?>">
                    <?php echo !empty($settings['maintenance_mode']) ? 'Active' : 'Inactive'; ?>
                </span>
            </div>
        </div>
        
        <!-- Settings Help -->
        <div class="help-card">
            <div class="settings-card-header">
                <div class="settings-card-icon">
                    <i class="fas fa-question-circle"></i>
                </div>
                <div class="settings-card-title">Settings Help</div>
            </div>
            
            <div class="help-section">
                <div class="help-heading">
                    <i class="fas fa-info-circle" style="color: #3B82F6;"></i>
                    <span>General Settings</span>
                </div>
                <p class="help-text">
                    Configure basic application settings like name, admin email, and session timeouts.
                </p>
            </div>
            
            <div class="help-section">
                <div class="help-heading">
                    <i class="fas fa-toggle-on" style="color: #10B981;"></i>
                    <span>Feature Settings</span>
                </div>
                <p class="help-text">
                    Enable or disable specific features like email notifications, SMS, M-Pesa payments, and maintenance mode.
                </p>
            </div>
            
            <div class="help-section">
                <div class="help-heading">
                    <i class="fas fa-users" style="color: #8B5CF6;"></i>
                    <span>Membership Settings</span>
                </div>
                <p class="help-text">
                    Set default membership package and base contribution amounts for new members.
                </p>
            </div>
            
            <div class="help-alert">
                <div class="help-alert-content">
                    <div class="help-alert-icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div class="help-alert-text">
                        <div class="help-alert-title">Important</div>
                        <div class="help-alert-description">Some settings may require server restart to take full effect.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once __DIR__ . '/../layouts/admin-footer.php'; ?>
