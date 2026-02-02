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
/* Profile Page Styles */
.profile-container {
    padding: 30px 30px 40px 25px;
    background: #F8F9FA;
    min-height: calc(100vh - 80px);
}

.profile-header {
    margin-bottom: 32px;
}

.profile-header h1 {
    font-family: 'Playfair Display', serif;
    font-size: 32px;
    font-weight: 700;
    color: #1F2937;
    margin: 0 0 4px 0;
}

.profile-header p {
    font-size: 14px;
    color: #6B7280;
    margin: 0;
}

/* Main Grid Layout */
.profile-main-grid {
    display: grid;
    grid-template-columns: 350px 1fr;
    gap: 24px;
}

/* Profile Sidebar Card */
.profile-sidebar {
    background: white;
    border-radius: 16px;
    padding: 32px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    height: fit-content;
}

.profile-avatar-section {
    text-align: center;
    margin-bottom: 24px;
    padding-bottom: 24px;
    border-bottom: 1px solid #E5E7EB;
}

.profile-avatar {
    width: 120px;
    height: 120px;
    background: linear-gradient(135deg, #7F20B0 0%, #5E2B7A 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: 'Playfair Display', serif;
    font-size: 48px;
    font-weight: 700;
    color: white;
    margin: 0 auto 16px;
    box-shadow: 0 4px 12px rgba(127, 32, 176, 0.3);
}

.profile-name {
    font-family: 'Playfair Display', serif;
    font-size: 24px;
    font-weight: 700;
    color: #1F2937;
    margin: 0 0 4px 0;
}

.profile-agent-id {
    font-size: 13px;
    color: #6B7280;
    margin: 0 0 12px 0;
}

.profile-agent-id strong {
    color: #7F20B0;
    font-weight: 600;
}

.profile-status-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    letter-spacing: 0.3px;
}

.profile-status-badge.active {
    background: #D1FAE5;
    color: #059669;
}

.profile-status-badge.pending {
    background: #FEF3C7;
    color: #D97706;
}

.profile-status-badge i {
    font-size: 8px;
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

/* Form Sections */
.profile-form-section {
    background: white;
    border-radius: 16px;
    padding: 32px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    margin-bottom: 24px;
}

.form-section-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 24px;
    padding-bottom: 16px;
    border-bottom: 1px solid #E5E7EB;
}

.form-section-icon {
    width: 36px;
    height: 36px;
    background: linear-gradient(135deg, #7F20B0 0%, #5E2B7A 100%);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 16px;
}

.form-section-header h3 {
    font-size: 18px;
    font-weight: 700;
    color: #1F2937;
    margin: 0;
}

/* Form Styles */
.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

.form-group-profile {
    margin-bottom: 20px;
}

.form-group-profile.full-width {
    grid-column: 1 / -1;
}

.form-label-profile {
    display: block;
    font-size: 13px;
    font-weight: 600;
    color: #374151;
    margin-bottom: 8px;
}

.form-label-profile .required {
    color: #DC2626;
    margin-left: 2px;
}

.form-input-profile,
.form-select-profile,
.form-textarea-profile {
    width: 100%;
    padding: 12px 14px;
    border: 1px solid #D1D5DB;
    border-radius: 8px;
    font-size: 14px;
    color: #1F2937;
    background: white;
    transition: all 0.2s;
}

.form-input-profile:focus,
.form-select-profile:focus,
.form-textarea-profile:focus {
    outline: none;
    border-color: #7F20B0;
    box-shadow: 0 0 0 3px rgba(127, 32, 176, 0.1);
}

.form-input-profile:disabled,
.form-input-profile:read-only {
    background: #F9FAFB;
    color: #6B7280;
    cursor: not-allowed;
}

.form-textarea-profile {
    resize: vertical;
    min-height: 80px;
}

.form-hint {
    font-size: 12px;
    color: #6B7280;
    margin-top: 6px;
}

/* Form Actions */
.form-actions {
    display: flex;
    gap: 12px;
    justify-content: flex-end;
    margin-top: 24px;
    padding-top: 20px;
    border-top: 1px solid #E5E7EB;
}

.btn-profile-cancel {
    padding: 12px 24px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    border: 1px solid #D1D5DB;
    background: white;
    color: #4B5563;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-profile-cancel:hover {
    background: #F9FAFB;
    border-color: #9CA3AF;
}

.btn-profile-save {
    padding: 12px 24px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    border: none;
    background: linear-gradient(135deg, #7F20B0 0%, #5E2B7A 100%);
    color: white;
    cursor: pointer;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    gap: 8px;
}

.btn-profile-save:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(127, 32, 176, 0.3);
}

.btn-profile-password {
    padding: 12px 24px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    border: none;
    background: #F59E0B;
    color: white;
    cursor: pointer;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    gap: 8px;
    width: 100%;
    justify-content: center;
}

.btn-profile-password:hover {
    background: #D97706;
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
        <h1>My Profile</h1>
        <p>Manage your agent profile information and settings</p>
    </div>

    <div class="profile-main-grid">
        <!-- Profile Sidebar -->
        <div class="profile-sidebar">
            <div class="profile-avatar-section">
                <div class="profile-avatar"><?php echo $initials; ?></div>
                <h2 class="profile-name"><?php echo htmlspecialchars($agent['first_name'] . ' ' . $agent['last_name']); ?></h2>
                <p class="profile-agent-id">Agent ID: <strong><?php echo htmlspecialchars($agent['agent_number']); ?></strong></p>
                <span class="profile-status-badge <?php echo $status_class; ?>">
                    <i class="fas fa-circle"></i>
                    <?php echo $status_text; ?>
                </span>
            </div>

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
                        <i class="fas fa-id-badge"></i>
                    </div>
                    <div class="profile-info-content">
                        <div class="profile-info-label">National ID</div>
                        <div class="profile-info-value"><?php echo htmlspecialchars($agent['national_id']); ?></div>
                    </div>
                </li>

                <li class="profile-info-item">
                    <div class="profile-info-icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="profile-info-content">
                        <div class="profile-info-label">Date Joined</div>
                        <div class="profile-info-value"><?php echo $joined_date; ?></div>
                    </div>
                </li>
            </ul>
        </div>

        <!-- Profile Forms -->
        <div>
            <!-- Edit Profile Form -->
            <div class="profile-form-section">
                <div class="form-section-header">
                    <div class="form-section-icon">
                        <i class="fas fa-user-edit"></i>
                    </div>
                    <h3>Edit Profile Information</h3>
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
                            <div class="form-hint">Email cannot be changed</div>
                        </div>

                        <div class="form-group-profile full-width">
                            <label for="address" class="form-label-profile">Address</label>
                            <textarea class="form-textarea-profile" id="address" name="address" rows="3"><?php echo htmlspecialchars($agent['address'] ?? ''); ?></textarea>
                        </div>

                        <div class="form-group-profile full-width">
                            <label for="county" class="form-label-profile">County</label>
                            <select class="form-select-profile" id="county" name="county">
                                <option value="">Select County</option>
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
                            <i class="fas fa-times"></i> Cancel
                        </button>
                        <button type="submit" class="btn-profile-save">
                            <i class="fas fa-save"></i> Save Changes
                        </button>
                    </div>
                </form>
            </div>

            <!-- Change Password Section -->
            <div class="profile-form-section">
                <div class="form-section-header">
                    <div class="form-section-icon">
                        <i class="fas fa-lock"></i>
                    </div>
                    <h3>Change Password</h3>
                </div>

                <form method="POST" action="/agent/password/update">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                    
                    <div class="form-group-profile">
                        <label for="current_password" class="form-label-profile">
                            Current Password <span class="required">*</span>
                        </label>
                        <input type="password" class="form-input-profile" id="current_password" name="current_password" required>
                    </div>

                    <div class="form-grid">
                        <div class="form-group-profile">
                            <label for="new_password" class="form-label-profile">
                                New Password <span class="required">*</span>
                            </label>
                            <input type="password" class="form-input-profile" id="new_password" name="new_password" required minlength="8">
                            <div class="form-hint">Minimum 8 characters</div>
                        </div>

                        <div class="form-group-profile">
                            <label for="confirm_password" class="form-label-profile">
                                Confirm New Password <span class="required">*</span>
                            </label>
                            <input type="password" class="form-input-profile" id="confirm_password" name="confirm_password" required>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-profile-password">
                            <i class="fas fa-key"></i> Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/agent-footer.php'; ?>
