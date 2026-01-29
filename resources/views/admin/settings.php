<?php
$page = 'settings';
$pageTitle = 'System Settings';
$pageSubtitle = 'Configure system-wide preferences and integrations';
include VIEWS_PATH . '/layouts/dashboard-header.php';
?>

<form method="POST" action="/admin/settings">
    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token ?? ''; ?>">
    
    <!-- General Settings -->
    <div class="card">
        <div class="card-header">
            <h4 style="margin: 0;"><i class="bi bi-gear-fill"></i> General Settings</h4>
        </div>
        <div class="card-body">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                <div class="form-group">
                    <label class="form-label" for="app_name">Application Name</label>
                    <input type="text" 
                           id="app_name" 
                           name="app_name" 
                           class="form-control" 
                           value="<?php echo htmlspecialchars($settings['app_name'] ?? 'Shena Companion Welfare'); ?>" 
                           required>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="admin_email">Admin Email</label>
                    <input type="email" 
                           id="admin_email" 
                           name="admin_email" 
                           class="form-control" 
                           value="<?php echo htmlspecialchars($settings['admin_email'] ?? ''); ?>" 
                           required>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="contact_phone">Contact Phone</label>
                    <input type="tel" 
                           id="contact_phone" 
                           name="contact_phone" 
                           class="form-control" 
                           value="<?php echo htmlspecialchars($settings['contact_phone'] ?? ''); ?>">
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="session_timeout">Session Timeout (minutes)</label>
                    <input type="number" 
                           id="session_timeout" 
                           name="session_timeout" 
                           class="form-control" 
                           value="<?php echo ($settings['session_timeout'] ?? 3600) / 60; ?>" 
                           min="5" 
                           max="1440" 
                           required>
                    <small style="color: var(--medium-grey); font-size: 0.75rem;">Min: 5 minutes, Max: 24 hours</small>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Feature Toggles -->
    <div class="card" style="margin-top: 2rem;">
        <div class="card-header">
            <h4 style="margin: 0;"><i class="bi bi-toggles"></i> Feature Settings</h4>
        </div>
        <div class="card-body">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                <div>
                    <div class="form-check" style="margin-bottom: 1.5rem;">
                        <input type="checkbox" 
                               id="email_enabled" 
                               name="email_enabled" 
                               class="form-check-input" 
                               <?php echo !empty($settings['email_enabled']) ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="email_enabled">
                            <strong style="color: var(--secondary-violet); display: block; margin-bottom: 0.25rem;">Email Notifications</strong>
                            <span style="font-size: 0.875rem; color: var(--medium-grey);">Send automated email notifications to members and admins</span>
                        </label>
                    </div>
                    
                    <div class="form-check" style="margin-bottom: 1.5rem;">
                        <input type="checkbox" 
                               id="sms_enabled" 
                               name="sms_enabled" 
                               class="form-check-input" 
                               <?php echo !empty($settings['sms_enabled']) ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="sms_enabled">
                            <strong style="color: var(--secondary-violet); display: block; margin-bottom: 0.25rem;">SMS Notifications</strong>
                            <span style="font-size: 0.875rem; color: var(--medium-grey);">Enable SMS notifications via Twilio integration</span>
                        </label>
                    </div>
                    
                    <div class="form-check">
                        <input type="checkbox" 
                               id="mpesa_enabled" 
                               name="mpesa_enabled" 
                               class="form-check-input" 
                               <?php echo !empty($settings['mpesa_enabled']) ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="mpesa_enabled">
                            <strong style="color: var(--secondary-violet); display: block; margin-bottom: 0.25rem;">M-Pesa Integration</strong>
                            <span style="font-size: 0.875rem; color: var(--medium-grey);">Process payments through Safaricom M-Pesa</span>
                        </label>
                    </div>
                </div>
                
                <div>
                    <div class="form-check" style="margin-bottom: 1.5rem;">
                        <input type="checkbox" 
                               id="agent_registration" 
                               name="agent_registration" 
                               class="form-check-input" 
                               <?php echo !empty($settings['agent_registration']) ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="agent_registration">
                            <strong style="color: var(--secondary-violet); display: block; margin-bottom: 0.25rem;">Agent Registration</strong>
                            <span style="font-size: 0.875rem; color: var(--medium-grey);">Allow new agent registrations and recruitment</span>
                        </label>
                    </div>
                    
                    <div class="form-check" style="margin-bottom: 1.5rem;">
                        <input type="checkbox" 
                               id="public_registration" 
                               name="public_registration" 
                               class="form-check-input" 
                               <?php echo !empty($settings['public_registration']) ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="public_registration">
                            <strong style="color: var(--secondary-violet); display: block; margin-bottom: 0.25rem;">Public Registration</strong>
                            <span style="font-size: 0.875rem; color: var(--medium-grey);">Allow members to self-register online</span>
                        </label>
                    </div>
                    
                    <div class="form-check">
                        <input type="checkbox" 
                               id="maintenance_mode" 
                               name="maintenance_mode" 
                               class="form-check-input" 
                               <?php echo !empty($settings['maintenance_mode']) ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="maintenance_mode">
                            <strong style="color: var(--danger-red); display: block; margin-bottom: 0.25rem;">Maintenance Mode</strong>
                            <span style="font-size: 0.875rem; color: var(--medium-grey);">Disable public access for system maintenance</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Payment Settings -->
    <div class="card" style="margin-top: 2rem;">
        <div class="card-header">
            <h4 style="margin: 0;"><i class="bi bi-credit-card-fill"></i> Payment Settings</h4>
        </div>
        <div class="card-body">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                <div class="form-group">
                    <label class="form-label" for="mpesa_paybill">M-Pesa Paybill Number</label>
                    <input type="text" 
                           id="mpesa_paybill" 
                           name="mpesa_paybill" 
                           class="form-control" 
                           value="<?php echo htmlspecialchars($settings['mpesa_paybill'] ?? '4163987'); ?>">
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="mpesa_shortcode">M-Pesa Shortcode</label>
                    <input type="text" 
                           id="mpesa_shortcode" 
                           name="mpesa_shortcode" 
                           class="form-control" 
                           value="<?php echo htmlspecialchars($settings['mpesa_shortcode'] ?? ''); ?>">
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="payment_grace_period">Payment Grace Period (days)</label>
                    <input type="number" 
                           id="payment_grace_period" 
                           name="payment_grace_period" 
                           class="form-control" 
                           value="<?php echo $settings['payment_grace_period'] ?? 7; ?>" 
                           min="0" 
                           max="30">
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="agent_commission_rate">Agent Commission Rate (%)</label>
                    <input type="number" 
                           id="agent_commission_rate" 
                           name="agent_commission_rate" 
                           class="form-control" 
                           value="<?php echo $settings['agent_commission_rate'] ?? 10; ?>" 
                           min="0" 
                           max="100" 
                           step="0.1">
                </div>
            </div>
        </div>
    </div>
    
    <!-- Notification Settings -->
    <div class="card" style="margin-top: 2rem;">
        <div class="card-header">
            <h4 style="margin: 0;"><i class="bi bi-bell-fill"></i> Notification Settings</h4>
        </div>
        <div class="card-body">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                <div class="form-group">
                    <label class="form-label" for="smtp_host">SMTP Host</label>
                    <input type="text" 
                           id="smtp_host" 
                           name="smtp_host" 
                           class="form-control" 
                           value="<?php echo htmlspecialchars($settings['smtp_host'] ?? ''); ?>">
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="smtp_port">SMTP Port</label>
                    <input type="number" 
                           id="smtp_port" 
                           name="smtp_port" 
                           class="form-control" 
                           value="<?php echo $settings['smtp_port'] ?? 587; ?>">
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="smtp_username">SMTP Username</label>
                    <input type="text" 
                           id="smtp_username" 
                           name="smtp_username" 
                           class="form-control" 
                           value="<?php echo htmlspecialchars($settings['smtp_username'] ?? ''); ?>">
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="twilio_phone">Twilio Phone Number</label>
                    <input type="tel" 
                           id="twilio_phone" 
                           name="twilio_phone" 
                           class="form-control" 
                           value="<?php echo htmlspecialchars($settings['twilio_phone'] ?? ''); ?>">
                </div>
            </div>
        </div>
    </div>
    
    <!-- Save Button -->
    <div style="margin-top: 2rem; display: flex; justify-content: flex-end; gap: 1rem;">
        <button type="reset" class="btn btn-outline">
            <i class="bi bi-x-circle"></i> Reset
        </button>
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-check-circle-fill"></i> Save Settings
        </button>
    </div>
</form>

<!-- System Information -->
<div class="card" style="margin-top: 2rem;">
    <div class="card-header">
        <h4 style="margin: 0;"><i class="bi bi-info-circle-fill"></i> System Information</h4>
    </div>
    <div class="card-body">
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 2rem;">
            <div>
                <label style="font-size: 0.75rem; font-weight: 600; color: var(--medium-grey); text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 0.5rem;">PHP Version:</label>
                <span style="color: var(--secondary-violet); font-weight: 600;"><?php echo phpversion(); ?></span>
            </div>
            <div>
                <label style="font-size: 0.75rem; font-weight: 600; color: var(--medium-grey); text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 0.5rem;">Server Software:</label>
                <span style="color: var(--secondary-violet); font-weight: 600;"><?php echo $_SERVER['SERVER_SOFTWARE'] ?? 'N/A'; ?></span>
            </div>
            <div>
                <label style="font-size: 0.75rem; font-weight: 600; color: var(--medium-grey); text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 0.5rem;">Application Version:</label>
                <span style="color: var(--secondary-violet); font-weight: 600;">1.0.0</span>
            </div>
        </div>
    </div>
</div>

<?php include VIEWS_PATH . '/layouts/dashboard-footer.php'; ?>
