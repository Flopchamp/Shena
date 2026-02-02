<?php 
$page = 'profile'; 
include __DIR__ . '/../layouts/agent-header.php';

// Generate initials for avatar
$initials = strtoupper(substr($agent['first_name'], 0, 1) . substr($agent['last_name'], 0, 1));

// Format dates
$joined_date = !empty($agent['registration_date']) ? date('M d, Y', strtotime($agent['registration_date'])) : (!empty($agent['created_at']) ? date('M d, Y', strtotime($agent['created_at'])) : 'N/A');

// Status badge
$status_class = $agent['status'] === 'active' ? 'active' : 'pending';
$status_text = !empty($agent['status']) ? ucfirst($agent['status']) : 'Pending';
?>

<style>
/* Profile Page Styles - Enhanced Design */
.profile-container {
    padding: 40px 35px 50px 30px;
    background: linear-gradient(135deg, #F9FAFB 0%, #F3F4F6 100%);
    min-height: calc(100vh - 80px);
}

.profile-header {
    margin-bottom: 36px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.profile-header-left h1 {
    font-family: 'Playfair Display', serif;
    font-size: 36px;
    font-weight: 700;
    color: #1F2937;
    margin: 0 0 6px 0;
    background: linear-gradient(135deg, #7F20B0 0%, #5E2B7A 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.profile-header-left p {
    font-size: 15px;
    color: #6B7280;
    margin: 0;
}

.profile-header-actions {
    display: flex;
    gap: 12px;
}

.btn-profile-action {
    padding: 11px 20px;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 600;
    border: none;
    cursor: pointer;
    transition: all 0.25s ease;
    display: flex;
    align-items: center;
    gap: 8px;
}

.btn-export {
    background: white;
    color: #6B7280;
    border: 2px solid #E5E7EB;
}

.btn-export:hover {
    background: #F9FAFB;
    border-color: #7F20B0;
    color: #7F20B0;
}

.btn-logout {
    background: linear-gradient(135deg, #DC2626 0%, #B91C1C 100%);
    color: white;
}

.btn-logout:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(220, 38, 38, 0.3);
}

/* Main Grid Layout */
.profile-main-grid {
    display: grid;
    grid-template-columns: 380px 1fr;
    gap: 28px;
}

/* Profile Sidebar Card - Enhanced */
.profile-sidebar {
    background: white;
    border-radius: 20px;
    padding: 0;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
    height: fit-content;
    overflow: hidden;
    border: 1px solid #F3F4F6;
}

.profile-banner {
    height: 120px;
    background: linear-gradient(135deg, #7F20B0 0%, #5E2B7A 100%);
    position: relative;
}

.profile-banner::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 50%;
    background: linear-gradient(to bottom, transparent, rgba(0, 0, 0, 0.2));
}

.profile-avatar-section {
    text-align: center;
    padding: 0 32px 28px;
    position: relative;
    margin-top: -60px;
}

.profile-avatar {
    width: 130px;
    height: 130px;
    background: linear-gradient(135deg, #7F20B0 0%, #5E2B7A 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: 'Playfair Display', serif;
    font-size: 52px;
    font-weight: 700;
    color: white;
    margin: 0 auto 20px;
    box-shadow: 0 8px 24px rgba(127, 32, 176, 0.35);
    border: 5px solid white;
    position: relative;
    z-index: 1;
}

.profile-name {
    font-family: 'Playfair Display', serif;
    font-size: 26px;
    font-weight: 700;
    color: #1F2937;
    margin: 0 0 6px 0;
}

.profile-agent-id {
    font-size: 13px;
    color: #6B7280;
    margin: 0 0 14px 0;
    font-family: 'Courier New', monospace;
}

.profile-agent-id strong {
    color: #7F20B0;
    font-weight: 700;
}

.profile-status-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 18px;
    border-radius: 24px;
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 0.5px;
    text-transform: uppercase;
}

.profile-status-badge.active {
    background: linear-gradient(135deg, #D1FAE5 0%, #A7F3D0 100%);
    color: #059669;
    box-shadow: 0 2px 8px rgba(5, 150, 105, 0.2);
}

.profile-status-badge.pending {
    background: linear-gradient(135deg, #FEF3C7 0%, #FDE68A 100%);
    color: #D97706;
    box-shadow: 0 2px 8px rgba(217, 119, 6, 0.2);
}

.profile-status-badge i {
    font-size: 9px;
}

/* Profile Info Section */
.profile-info-section {
    padding: 0 32px 32px;
}

.profile-info-list {
    margin: 0;
    padding: 0;
    list-style: none;
}

.profile-info-item {
    display: flex;
    align-items: flex-start;
    gap: 14px;
    padding: 16px;
    margin-bottom: 10px;
    border-radius: 12px;
    background: #F9FAFB;
    transition: all 0.25s ease;
}

.profile-info-item:hover {
    background: #F3F4F6;
    transform: translateX(4px);
}

.profile-info-icon {
    width: 42px;
    height: 42px;
    background: linear-gradient(135deg, #F3E8FF 0%, #E9D5FF 100%);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #7F20B0;
    font-size: 16px;
    flex-shrink: 0;
    box-shadow: 0 2px 8px rgba(127, 32, 176, 0.15);
}

.profile-info-content {
    flex: 1;
    min-width: 0;
}

.profile-info-label {
    font-size: 11px;
    font-weight: 700;
    color: #9CA3AF;
    text-transform: uppercase;
    letter-spacing: 0.6px;
    margin-bottom: 5px;
}

.profile-info-value {
    font-size: 14px;
    color: #1F2937;
    font-weight: 600;
    word-break: break-word;
}

/* Stats Cards in Sidebar */
.profile-stats-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
    padding: 0 32px 32px;
}

.profile-stat-card {
    background: linear-gradient(135deg, #F9FAFB 0%, #F3F4F6 100%);
    border-radius: 12px;
    padding: 16px;
    text-align: center;
    border: 1px solid #E5E7EB;
    transition: all 0.25s ease;
}

.profile-stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.1);
    border-color: #7F20B0;
}

.profile-stat-value {
    font-family: 'Playfair Display', serif;
    font-size: 28px;
    font-weight: 700;
    color: #7F20B0;
    margin-bottom: 4px;
}

.profile-stat-label {
    font-size: 11px;
    font-weight: 600;
    color: #6B7280;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Profile Info List */
.profile-info-list {
    margin: 0;
    padding: 0;
    list-style: none;
}

.profile-info-item {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 12px 0;
    border-bottom: 1px solid #F3F4F6;
}

.profile-info-item:last-child {
    border-bottom: none;
}

.profile-info-icon {
    width: 36px;
    height: 36px;
    background: #F3E8FF;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #7F20B0;
    font-size: 14px;
    flex-shrink: 0;
}

.profile-info-content {
    flex: 1;
}

.profile-info-label {
    font-size: 11px;
    font-weight: 600;
    color: #9CA3AF;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 4px;
}

.profile-info-value {
    font-size: 14px;
    color: #1F2937;
    font-weight: 500;
    word-break: break-all;
}

/* Form Sections - Enhanced */
.profile-form-section {
    background: white;
    border-radius: 20px;
    padding: 36px;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
    margin-bottom: 28px;
    border: 1px solid #F3F4F6;
    transition: all 0.25s ease;
}

.profile-form-section:hover {
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
    border-color: #E5E7EB;
}

.form-section-header {
    display: flex;
    align-items: center;
    gap: 14px;
    margin-bottom: 28px;
    padding-bottom: 20px;
    border-bottom: 2px solid #F3F4F6;
}

.form-section-icon {
    width: 44px;
    height: 44px;
    background: linear-gradient(135deg, #7F20B0 0%, #5E2B7A 100%);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 18px;
    box-shadow: 0 4px 12px rgba(127, 32, 176, 0.3);
}

.form-section-header h3 {
    font-family: 'Playfair Display', serif;
    font-size: 22px;
    font-weight: 700;
    color: #1F2937;
    margin: 0;
}

/* Form Styles - Modern Inputs */
.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 24px;
}

.form-group-profile {
    margin-bottom: 0;
}

.form-group-profile.full-width {
    grid-column: 1 / -1;
}

.form-label-profile {
    display: block;
    font-size: 13px;
    font-weight: 700;
    color: #374151;
    margin-bottom: 10px;
    letter-spacing: 0.3px;
}

.form-label-profile .required {
    color: #DC2626;
    margin-left: 3px;
    font-size: 14px;
}

.form-input-profile,
.form-select-profile,
.form-textarea-profile {
    width: 100%;
    padding: 13px 16px;
    border: 2px solid #E5E7EB;
    border-radius: 12px;
    font-size: 14px;
    color: #1F2937;
    background: #F9FAFB;
    transition: all 0.3s ease;
    font-weight: 500;
}

.form-input-profile:focus,
.form-select-profile:focus,
.form-textarea-profile:focus {
    outline: none;
    border-color: #7F20B0;
    box-shadow: 0 0 0 4px rgba(127, 32, 176, 0.12);
    background: white;
}

.form-input-profile:disabled,
.form-input-profile:read-only {
    background: #F3F4F6;
    color: #9CA3AF;
    cursor: not-allowed;
    border-color: #E5E7EB;
}

.form-textarea-profile {
    resize: vertical;
    min-height: 100px;
    font-family: inherit;
}

.form-hint {
    font-size: 12px;
    color: #6B7280;
    margin-top: 8px;
    display: flex;
    align-items: center;
    gap: 6px;
}

.form-hint i {
    color: #9CA3AF;
}

/* Form Actions - Enhanced Buttons */
.form-actions {
    display: flex;
    gap: 14px;
    justify-content: flex-end;
    margin-top: 28px;
    padding-top: 24px;
    border-top: 2px solid #F3F4F6;
}

.btn-profile-cancel {
    padding: 13px 28px;
    border-radius: 12px;
    font-size: 14px;
    font-weight: 700;
    border: 2px solid #E5E7EB;
    background: white;
    color: #6B7280;
    cursor: pointer;
    transition: all 0.25s ease;
    display: flex;
    align-items: center;
    gap: 8px;
}

.btn-profile-cancel:hover {
    background: #F9FAFB;
    border-color: #D1D5DB;
    color: #4B5563;
    transform: translateY(-2px);
}

.btn-profile-save {
    padding: 13px 28px;
    border-radius: 12px;
    font-size: 14px;
    font-weight: 700;
    border: none;
    background: linear-gradient(135deg, #7F20B0 0%, #5E2B7A 100%);
    color: white;
    cursor: pointer;
    transition: all 0.25s ease;
    display: flex;
    align-items: center;
    gap: 8px;
    box-shadow: 0 4px 12px rgba(127, 32, 176, 0.3);
}
    align-items: center;
    gap: 8px;
}

.btn-profile-save:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(127, 32, 176, 0.4);
}

.btn-profile-password {
    padding: 13px 28px;
    border-radius: 12px;
    font-size: 14px;
    font-weight: 700;
    border: none;
    background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%);
    color: white;
    cursor: pointer;
    transition: all 0.25s ease;
    display: flex;
    align-items: center;
    gap: 8px;
    width: 100%;
    justify-content: center;
    box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
}

.btn-profile-password:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(245, 158, 11, 0.4);
}
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
}

/* Responsive */
@media (max-width: 1200px) {
    .profile-main-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .profile-container {
        padding: 20px 15px;
    }

    .form-grid {
        grid-template-columns: 1fr;
    }

    .profile-sidebar,
    .profile-form-section {
        padding: 20px;
    }
}
</style>

<div class="profile-container">
    <div class="profile-header">
        <div class="profile-header-left">
            <h1>Agent Profile</h1>
            <p>Manage your personal information and account settings</p>
        </div>
        <div class="profile-header-actions">
            <button class="btn-profile-action btn-export">
                <i class="fas fa-download"></i>
                Export Data
            </button>
            <button class="btn-profile-action btn-logout" onclick="location.href='/agent/logout'">
                <i class="fas fa-sign-out-alt"></i>
                Logout
            </button>
        </div>
    </div>

    <div class="profile-main-grid">
        <!-- Profile Sidebar -->
        <div class="profile-sidebar">
            <div class="profile-banner"></div>
            
            <div class="profile-avatar-section">
                <div class="profile-avatar"><?php echo $initials; ?></div>
                <h2 class="profile-name"><?php echo htmlspecialchars($agent['first_name'] . ' ' . $agent['last_name']); ?></h2>
                <p class="profile-agent-id">ID: <strong><?php echo htmlspecialchars($agent['agent_number']); ?></strong></p>
                <span class="profile-status-badge <?php echo $status_class; ?>">
                    <i class="fas fa-circle"></i>
                    <?php echo $status_text; ?>
                </span>
            </div>

            <div class="profile-stats-grid">
                <div class="profile-stat-card">
                    <div class="profile-stat-value">24</div>
                    <div class="profile-stat-label">Members</div>
                </div>
                <div class="profile-stat-card">
                    <div class="profile-stat-value">89%</div>
                    <div class="profile-stat-label">Success Rate</div>
                </div>
            </div>

            <div class="profile-info-section">
                <ul class="profile-info-list">
                    <li class="profile-info-item">
                        <div class="profile-info-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="profile-info-content">
                            <div class="profile-info-label">Email Address</div>
                            <div class="profile-info-value"><?php echo htmlspecialchars($agent['email']); ?></div>
                        </div>
                    </li>

                    <li class="profile-info-item">
                        <div class="profile-info-icon">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div class="profile-info-content">
                            <div class="profile-info-label">Phone Number</div>
                            <div class="profile-info-value"><?php echo htmlspecialchars($agent['phone']); ?></div>
                        </div>
                    </li>

                    <li class="profile-info-item">
                        <div class="profile-info-icon">
                            <i class="fas fa-id-card"></i>
                        </div>
                        <div class="profile-info-content">
                            <div class="profile-info-label">National ID</div>
                            <div class="profile-info-value"><?php echo htmlspecialchars($agent['national_id']); ?></div>
                        </div>
                    </li>

                    <li class="profile-info-item">
                        <div class="profile-info-icon">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <div class="profile-info-content">
                            <div class="profile-info-label">Joined Date</div>
                            <div class="profile-info-value"><?php echo $joined_date; ?></div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Profile Forms -->
        <div>
            <!-- Edit Profile Form -->
            <div class="profile-form-section">
                <div class="form-section-header">
                    <div class="form-section-icon">
                        <i class="fas fa-user-edit"></i>
                    </div>
                    <h3>Personal Information</h3>
                </div>
                </div>

                <form method="POST" action="/agent/profile/update">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                    
                    <div class="form-grid">
                        <div class="form-group-profile">
                            <label for="first_name" class="form-label-profile">
                                First Name <span class="required">*</span>
                            </label>
                            <input type="text" class="form-input-profile" id="first_name" name="first_name" 
                                   value="<?php echo htmlspecialchars($agent['first_name']); ?>" required>
                        </div>

                        <div class="form-group-profile">
                            <label for="last_name" class="form-label-profile">
                                Last Name <span class="required">*</span>
                            </label>
                            <input type="text" class="form-input-profile" id="last_name" name="last_name" 
                                   value="<?php echo htmlspecialchars($agent['last_name']); ?>" required>
                        </div>

                        <div class="form-group-profile">
                            <label for="phone" class="form-label-profile">
                                Phone Number <span class="required">*</span>
                            </label>
                            <input type="tel" class="form-input-profile" id="phone" name="phone" 
                                   value="<?php echo htmlspecialchars($agent['phone']); ?>" required>
                            <div class="form-hint">Format: +254712345678</div>
                        </div>

                        <div class="form-group-profile">
                            <label for="email" class="form-label-profile">
                                Email Address <span class="required">*</span>
                            </label>
                            <input type="email" class="form-input-profile" id="email" name="email" 
                                   value="<?php echo htmlspecialchars($agent['email']); ?>" readonly disabled>
                            <div class="form-hint">
                                <i class="fas fa-info-circle"></i>
                                Email cannot be changed for security reasons
                            </div>
                        </div>

                        <div class="form-group-profile full-width">
                            <label for="address" class="form-label-profile">Physical Address</label>
                            <textarea class="form-textarea-profile" id="address" name="address" placeholder="Enter your complete physical address..."><?php echo htmlspecialchars($agent['address'] ?? ''); ?></textarea>
                        </div>

                        <div class="form-group-profile full-width">
                            <label for="county" class="form-label-profile">County/Region</label>
                            <select class="form-select-profile" id="county" name="county">
                                <option value="">Select your county...</option>
                                <?php 
                                $counties = ['Nairobi', 'Mombasa', 'Kisumu', 'Nakuru', 'Eldoret', 'Thika', 'Malindi', 'Kitale', 'Garissa', 'Kakamega'];
                                foreach ($counties as $county): 
                                ?>
                                    <option value="<?php echo $county; ?>" <?php echo (isset($agent['county']) && $agent['county'] === $county) ? 'selected' : ''; ?>>
                                        <?php echo $county; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="button" class="btn-profile-cancel" onclick="window.location.href='/agent/dashboard'">
                            <i class="fas fa-times-circle"></i> Cancel
                        </button>
                        <button type="submit" class="btn-profile-save">
                            <i class="fas fa-check-circle"></i> Save Changes
                        </button>
                    </div>
                </form>
            </div>

            <!-- Change Password Section -->
            <div class="profile-form-section">
                <div class="form-section-header">
                    <div class="form-section-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3>Security Settings</h3>
                </div>

                <form method="POST" action="/agent/password/update">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                    
                    <div class="form-group-profile">
                        <label for="current_password" class="form-label-profile">
                            Current Password <span class="required">*</span>
                        </label>
                        <input type="password" class="form-input-profile" id="current_password" name="current_password" placeholder="Enter your current password" required>
                    </div>

                    <div class="form-grid">
                        <div class="form-group-profile">
                            <label for="new_password" class="form-label-profile">
                                New Password <span class="required">*</span>
                            </label>
                            <input type="password" class="form-input-profile" id="new_password" name="new_password" placeholder="Enter new password" required minlength="8">
                            <div class="form-hint">
                                <i class="fas fa-info-circle"></i>
                                Must be at least 8 characters long
                            </div>
                        </div>

                        <div class="form-group-profile">
                            <label for="confirm_password" class="form-label-profile">
                                Confirm New Password <span class="required">*</span>
                            </label>
                            <input type="password" class="form-input-profile" id="confirm_password" name="confirm_password" placeholder="Confirm new password" required>
                            <div class="form-hint">
                                <i class="fas fa-info-circle"></i>
                                Must match the new password
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-profile-password">
                            <i class="fas fa-lock"></i> Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/agent-footer.php'; ?>
