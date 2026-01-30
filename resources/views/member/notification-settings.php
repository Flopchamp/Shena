<?php
/**
 * Member Notification Settings View
 * Allow members to manage their notification preferences
 */
$pageTitle = 'Notification Settings - ' . SITE_NAME;
include 'resources/views/layouts/member-header.php';
?>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-bell mr-2"></i>Notification Settings
        </h1>
        <a href="/dashboard" class="btn btn-secondary">
            <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
        </a>
    </div>

    <!-- Alert Messages -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    <?php endif; ?>

    <div class="row">
        <!-- Notification Preferences Card -->
        <div class="col-lg-8 mx-auto">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-cog mr-2"></i>Manage Your Notification Preferences
                    </h6>
                </div>
                <div class="card-body">
                    <form action="/member/notification-settings" method="POST">
                        <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                        
                        <!-- Email Notifications -->
                        <div class="card mb-3">
                            <div class="card-header bg-light">
                                <h6 class="mb-0"><i class="fas fa-envelope mr-2"></i>Email Notifications</h6>
                            </div>
                            <div class="card-body">
                                <div class="form-check mb-3">
                                    <input type="checkbox" class="form-check-input" id="email_payment_reminders" 
                                           name="email_payment_reminders" value="1"
                                           <?php echo ($preferences['email_payment_reminders'] ?? 1) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="email_payment_reminders">
                                        <strong>Payment Reminders</strong>
                                        <p class="text-muted small mb-0">Receive reminders about upcoming payments</p>
                                    </label>
                                </div>
                                
                                <div class="form-check mb-3">
                                    <input type="checkbox" class="form-check-input" id="email_payment_confirmations" 
                                           name="email_payment_confirmations" value="1"
                                           <?php echo ($preferences['email_payment_confirmations'] ?? 1) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="email_payment_confirmations">
                                        <strong>Payment Confirmations</strong>
                                        <p class="text-muted small mb-0">Get notified when your payments are received</p>
                                    </label>
                                </div>
                                
                                <div class="form-check mb-3">
                                    <input type="checkbox" class="form-check-input" id="email_claim_updates" 
                                           name="email_claim_updates" value="1"
                                           <?php echo ($preferences['email_claim_updates'] ?? 1) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="email_claim_updates">
                                        <strong>Claim Updates</strong>
                                        <p class="text-muted small mb-0">Receive updates about your claim submissions and approvals</p>
                                    </label>
                                </div>
                                
                                <div class="form-check mb-3">
                                    <input type="checkbox" class="form-check-input" id="email_newsletters" 
                                           name="email_newsletters" value="1"
                                           <?php echo ($preferences['email_newsletters'] ?? 1) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="email_newsletters">
                                        <strong>Newsletters & Updates</strong>
                                        <p class="text-muted small mb-0">Stay informed with our latest news and updates</p>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- SMS Notifications -->
                        <div class="card mb-3">
                            <div class="card-header bg-light">
                                <h6 class="mb-0"><i class="fas fa-sms mr-2"></i>SMS Notifications</h6>
                            </div>
                            <div class="card-body">
                                <div class="form-check mb-3">
                                    <input type="checkbox" class="form-check-input" id="sms_payment_reminders" 
                                           name="sms_payment_reminders" value="1"
                                           <?php echo ($preferences['sms_payment_reminders'] ?? 1) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="sms_payment_reminders">
                                        <strong>Payment Reminders</strong>
                                        <p class="text-muted small mb-0">Get SMS reminders before payments are due</p>
                                    </label>
                                </div>
                                
                                <div class="form-check mb-3">
                                    <input type="checkbox" class="form-check-input" id="sms_payment_confirmations" 
                                           name="sms_payment_confirmations" value="1"
                                           <?php echo ($preferences['sms_payment_confirmations'] ?? 1) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="sms_payment_confirmations">
                                        <strong>Payment Confirmations</strong>
                                        <p class="text-muted small mb-0">Instant SMS confirmation when payments are received</p>
                                    </label>
                                </div>
                                
                                <div class="form-check mb-3">
                                    <input type="checkbox" class="form-check-input" id="sms_claim_updates" 
                                           name="sms_claim_updates" value="1"
                                           <?php echo ($preferences['sms_claim_updates'] ?? 1) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="sms_claim_updates">
                                        <strong>Claim Updates</strong>
                                        <p class="text-muted small mb-0">Get SMS notifications about claim status changes</p>
                                    </label>
                                </div>
                                
                                <div class="form-check mb-3">
                                    <input type="checkbox" class="form-check-input" id="sms_important_alerts" 
                                           name="sms_important_alerts" value="1"
                                           <?php echo ($preferences['sms_important_alerts'] ?? 1) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="sms_important_alerts">
                                        <strong>Important Alerts</strong>
                                        <p class="text-muted small mb-0">Critical account notifications (cannot be disabled)</p>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- General Settings -->
                        <div class="card mb-3">
                            <div class="card-header bg-light">
                                <h6 class="mb-0"><i class="fas fa-sliders-h mr-2"></i>General Settings</h6>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="notification_frequency">Notification Frequency</label>
                                    <select class="form-control" id="notification_frequency" name="notification_frequency">
                                        <option value="immediate" <?php echo ($preferences['notification_frequency'] ?? 'immediate') === 'immediate' ? 'selected' : ''; ?>>Immediate</option>
                                        <option value="daily_digest" <?php echo ($preferences['notification_frequency'] ?? '') === 'daily_digest' ? 'selected' : ''; ?>>Daily Digest</option>
                                        <option value="weekly_digest" <?php echo ($preferences['notification_frequency'] ?? '') === 'weekly_digest' ? 'selected' : ''; ?>>Weekly Digest</option>
                                    </select>
                                    <small class="form-text text-muted">Choose how often you want to receive non-urgent notifications</small>
                                </div>
                                
                                <div class="form-check mb-3">
                                    <input type="checkbox" class="form-check-input" id="marketing_communications" 
                                           name="marketing_communications" value="1"
                                           <?php echo ($preferences['marketing_communications'] ?? 0) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="marketing_communications">
                                        <strong>Marketing Communications</strong>
                                        <p class="text-muted small mb-0">Receive promotional offers and partnership opportunities</p>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-save mr-2"></i>Save Preferences
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Contact Information Card -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-address-card mr-2"></i>Contact Information
                    </h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle mr-2"></i>
                        <strong>Current Email:</strong> <?php echo htmlspecialchars($member['email']); ?><br>
                        <strong>Current Phone:</strong> <?php echo htmlspecialchars($member['phone']); ?>
                    </div>
                    <p class="text-muted mb-0">
                        To update your email or phone number, please go to your 
                        <a href="/profile">Profile Settings</a>.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'resources/views/layouts/member-footer.php'; ?>
