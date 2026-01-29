<?php include_once VIEWS_PATH . '/admin/admin-header.php'; ?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">System Settings</h1>
    <small class="text-muted">Configure system-wide settings and preferences</small>
</div>

<div class="row">
    <div class="col-lg-8">
        <form method="POST" action="/admin/settings">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
            
            <!-- General Settings -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">General Settings</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="app_name" class="form-label">Application Name</label>
                                <input type="text" class="form-control" id="app_name" name="app_name" 
                                       value="<?php echo htmlspecialchars($settings['app_name'] ?? 'Shena Companion Welfare'); ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="admin_email" class="form-label">Admin Email</label>
                                <input type="email" class="form-control" id="admin_email" name="admin_email" 
                                       value="<?php echo htmlspecialchars($settings['admin_email'] ?? ''); ?>" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="session_timeout" class="form-label">Session Timeout (seconds)</label>
                                <input type="number" class="form-control" id="session_timeout" name="session_timeout" 
                                       value="<?php echo $settings['session_timeout'] ?? 3600; ?>" min="300" max="86400" required>
                                <div class="form-text">Minimum: 300 (5 minutes), Maximum: 86400 (24 hours)</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="max_upload_size" class="form-label">Max Upload Size</label>
                                <select class="form-control" id="max_upload_size" name="max_upload_size">
                                    <option value="1MB" <?php echo ($settings['max_upload_size'] ?? '2MB') === '1MB' ? 'selected' : ''; ?>>1 MB</option>
                                    <option value="2MB" <?php echo ($settings['max_upload_size'] ?? '2MB') === '2MB' ? 'selected' : ''; ?>>2 MB</option>
                                    <option value="5MB" <?php echo ($settings['max_upload_size'] ?? '2MB') === '5MB' ? 'selected' : ''; ?>>5 MB</option>
                                    <option value="10MB" <?php echo ($settings['max_upload_size'] ?? '2MB') === '10MB' ? 'selected' : ''; ?>>10 MB</option>
                                    <option value="20MB" <?php echo ($settings['max_upload_size'] ?? '2MB') === '20MB' ? 'selected' : ''; ?>>20 MB</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Feature Settings -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Feature Settings</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="email_enabled" name="email_enabled" 
                                       <?php echo !empty($settings['email_enabled']) ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="email_enabled">
                                    <strong>Email Notifications</strong>
                                    <div class="form-text">Enable email notifications for members and admins</div>
                                </label>
                            </div>
                            
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="sms_enabled" name="sms_enabled" 
                                       <?php echo !empty($settings['sms_enabled']) ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="sms_enabled">
                                    <strong>SMS Notifications</strong>
                                    <div class="form-text">Enable SMS notifications via Twilio</div>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="mpesa_enabled" name="mpesa_enabled" 
                                       <?php echo !empty($settings['mpesa_enabled']) ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="mpesa_enabled">
                                    <strong>M-Pesa Integration</strong>
                                    <div class="form-text">Enable M-Pesa payment processing</div>
                                </label>
                            </div>
                            
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="maintenance_mode" name="maintenance_mode" 
                                       <?php echo !empty($settings['maintenance_mode']) ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="maintenance_mode">
                                    <strong>Maintenance Mode</strong>
                                    <div class="form-text text-warning">⚠️ Disable public access to the website</div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Membership Settings -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Membership Settings</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="default_package" class="form-label">Default Package</label>
                                <select class="form-control" id="default_package" name="default_package">
                                    <option value="individual" <?php echo ($settings['default_package'] ?? 'individual') === 'individual' ? 'selected' : ''; ?>>Individual</option>
                                    <option value="family" <?php echo ($settings['default_package'] ?? 'individual') === 'family' ? 'selected' : ''; ?>>Family</option>
                                    <option value="premium" <?php echo ($settings['default_package'] ?? 'individual') === 'premium' ? 'selected' : ''; ?>>Premium</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="base_contribution" class="form-label">Base Monthly Contribution (KES)</label>
                                <input type="number" class="form-control" id="base_contribution" name="base_contribution" 
                                       value="<?php echo $settings['base_contribution'] ?? 500; ?>" min="100" max="10000" required>
                                <div class="form-text">Base amount before age and package adjustments</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Save Settings -->
            <div class="card shadow mb-4">
                <div class="card-body text-center">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-save me-2"></i>Save Settings
                    </button>
                    <button type="reset" class="btn btn-secondary btn-lg ms-2">
                        <i class="fas fa-undo me-2"></i>Reset
                    </button>
                </div>
            </div>
        </form>
    </div>
    
    <!-- Settings Help -->
    <div class="col-lg-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Settings Help</h6>
            </div>
            <div class="card-body">
                <h6><i class="fas fa-info-circle text-info me-2"></i>General Settings</h6>
                <p class="small text-muted mb-3">
                    Configure basic application settings like name, admin email, and session timeouts.
                </p>
                
                <h6><i class="fas fa-toggle-on text-success me-2"></i>Feature Settings</h6>
                <p class="small text-muted mb-3">
                    Enable or disable specific features like email notifications, SMS, M-Pesa payments, and maintenance mode.
                </p>
                
                <h6><i class="fas fa-users text-primary me-2"></i>Membership Settings</h6>
                <p class="small text-muted mb-3">
                    Set default membership package and base contribution amounts for new members.
                </p>
                
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Important:</strong> Some settings may require server restart to take full effect.
                </div>
            </div>
        </div>
        
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Current System Status</h6>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span>Email Service:</span>
                    <span class="badge bg-<?php echo $settings['email_enabled'] ? 'success' : 'secondary'; ?>">
                        <?php echo $settings['email_enabled'] ? 'Enabled' : 'Disabled'; ?>
                    </span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span>SMS Service:</span>
                    <span class="badge bg-<?php echo $settings['sms_enabled'] ? 'success' : 'secondary'; ?>">
                        <?php echo $settings['sms_enabled'] ? 'Enabled' : 'Disabled'; ?>
                    </span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span>M-Pesa:</span>
                    <span class="badge bg-<?php echo $settings['mpesa_enabled'] ? 'success' : 'secondary'; ?>">
                        <?php echo $settings['mpesa_enabled'] ? 'Enabled' : 'Disabled'; ?>
                    </span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span>Maintenance:</span>
                    <span class="badge bg-<?php echo $settings['maintenance_mode'] ? 'warning' : 'success'; ?>">
                        <?php echo $settings['maintenance_mode'] ? 'Active' : 'Inactive'; ?>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once VIEWS_PATH . '/admin/admin-footer.php'; ?>
