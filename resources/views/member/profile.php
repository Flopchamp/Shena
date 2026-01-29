<?php
$page = 'profile';
$pageTitle = 'My Profile';
$pageSubtitle = 'Manage your personal information and settings';
include VIEWS_PATH . '/layouts/member-header.php';
?>

<!-- Profile Overview Card -->
<div class="card" style="margin-bottom: 2rem;">
    <div class="card-body" style="padding: 2rem;">
        <div style="display: flex; align-items: center; gap: 2rem;">
            <div style="width: 100px; height: 100px; background: var(--gradient-primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 2.5rem; font-weight: 700; flex-shrink: 0;">
                <?php echo strtoupper(substr($member->first_name, 0, 1) . substr($member->last_name, 0, 1)); ?>
            </div>
            <div style="flex: 1;">
                <h3 style="margin: 0 0 0.5rem 0; color: var(--secondary-violet);">
                    <?php echo htmlspecialchars($member->first_name . ' ' . $member->last_name); ?>
                </h3>
                <p style="margin: 0; color: var(--medium-grey); font-size: 1.125rem;">
                    Member ID: <strong><?php echo $member->member_id; ?></strong>
                </p>
                <div style="margin-top: 1rem; display: flex; gap: 1rem; align-items: center;">
                    <?php if ($member->status == 'active'): ?>
                        <span class="badge badge-success">Active Member</span>
                    <?php else: ?>
                        <span class="badge badge-warning">Pending Approval</span>
                    <?php endif; ?>
                    <span style="color: var(--medium-grey); font-size: 0.875rem;">
                        <i class="bi bi-calendar-fill"></i> Joined <?php echo date('M d, Y', strtotime($member->created_at)); ?>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Profile Content -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 2rem;">
    
    <!-- Personal Information -->
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h4 style="margin: 0;"><i class="bi bi-person-fill"></i> Personal Information</h4>
            </div>
        </div>
        <div class="card-body">
            <form method="POST" action="/member/profile/update">
                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token ?? ''; ?>">
                
                <div class="form-group">
                    <label class="form-label" for="first_name">First Name</label>
                    <input type="text" 
                           id="first_name" 
                           name="first_name" 
                           class="form-control" 
                           value="<?php echo htmlspecialchars($member->first_name); ?>" 
                           required>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="last_name">Last Name</label>
                    <input type="text" 
                           id="last_name" 
                           name="last_name" 
                           class="form-control" 
                           value="<?php echo htmlspecialchars($member->last_name); ?>" 
                           required>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="email">Email Address</label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           class="form-control" 
                           value="<?php echo htmlspecialchars($member->email); ?>" 
                           required>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="phone">Phone Number</label>
                    <input type="tel" 
                           id="phone" 
                           name="phone" 
                           class="form-control" 
                           value="<?php echo htmlspecialchars($member->phone); ?>" 
                           required>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="address">Physical Address</label>
                    <textarea id="address" 
                              name="address" 
                              class="form-control" 
                              rows="3"><?php echo htmlspecialchars($member->address ?? ''); ?></textarea>
                </div>
                
                <div style="margin-top: 1.5rem;">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle-fill"></i> Update Information
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Next of Kin Information -->
    <div class="card">
        <div class="card-header">
            <h4 style="margin: 0;"><i class="bi bi-people-fill"></i> Next of Kin</h4>
        </div>
        <div class="card-body">
            <form method="POST" action="/member/profile/next-of-kin">
                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token ?? ''; ?>">
                
                <div class="form-group">
                    <label class="form-label" for="next_of_kin">Full Name</label>
                    <input type="text" 
                           id="next_of_kin" 
                           name="next_of_kin" 
                           class="form-control" 
                           value="<?php echo htmlspecialchars($member->next_of_kin ?? ''); ?>">
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="next_of_kin_phone">Phone Number</label>
                    <input type="tel" 
                           id="next_of_kin_phone" 
                           name="next_of_kin_phone" 
                           class="form-control" 
                           value="<?php echo htmlspecialchars($member->next_of_kin_phone ?? ''); ?>">
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="next_of_kin_relationship">Relationship</label>
                    <select id="next_of_kin_relationship" 
                            name="next_of_kin_relationship" 
                            class="form-select">
                        <option value="">Select Relationship</option>
                        <option value="Spouse" <?php echo (($member->next_of_kin_relationship ?? '') == 'Spouse') ? 'selected' : ''; ?>>Spouse</option>
                        <option value="Parent" <?php echo (($member->next_of_kin_relationship ?? '') == 'Parent') ? 'selected' : ''; ?>>Parent</option>
                        <option value="Sibling" <?php echo (($member->next_of_kin_relationship ?? '') == 'Sibling') ? 'selected' : ''; ?>>Sibling</option>
                        <option value="Child" <?php echo (($member->next_of_kin_relationship ?? '') == 'Child') ? 'selected' : ''; ?>>Child</option>
                        <option value="Other" <?php echo (($member->next_of_kin_relationship ?? '') == 'Other') ? 'selected' : ''; ?>>Other</option>
                    </select>
                </div>
                
                <div style="margin-top: 1.5rem;">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle-fill"></i> Update Next of Kin
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Security Settings -->
<div class="card" style="margin-top: 2rem;">
    <div class="card-header">
        <h4 style="margin: 0;"><i class="bi bi-shield-lock-fill"></i> Security Settings</h4>
    </div>
    <div class="card-body">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 2rem;">
            <!-- Change Password -->
            <div>
                <h5 style="margin-bottom: 1rem; color: var(--secondary-violet);">Change Password</h5>
                <form method="POST" action="/member/profile/password">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token ?? ''; ?>">
                    
                    <div class="form-group">
                        <label class="form-label" for="current_password">Current Password</label>
                        <input type="password" 
                               id="current_password" 
                               name="current_password" 
                               class="form-control" 
                               required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="new_password">New Password</label>
                        <input type="password" 
                               id="new_password" 
                               name="new_password" 
                               class="form-control" 
                               minlength="8" 
                               required>
                        <small style="color: var(--medium-grey); font-size: 0.75rem;">Minimum 8 characters</small>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="confirm_password">Confirm New Password</label>
                        <input type="password" 
                               id="confirm_password" 
                               name="confirm_password" 
                               class="form-control" 
                               minlength="8" 
                               required>
                    </div>
                    
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-key-fill"></i> Change Password
                    </button>
                </form>
            </div>
            
            <!-- Account Information -->
            <div>
                <h5 style="margin-bottom: 1rem; color: var(--secondary-violet);">Account Information</h5>
                <div style="background: var(--soft-grey); padding: 1.5rem; border-radius: var(--radius-md);">
                    <div style="margin-bottom: 1rem;">
                        <label style="font-weight: 600; color: var(--secondary-violet); display: block; margin-bottom: 0.25rem;">Package:</label>
                        <span><?php echo htmlspecialchars($member->package ?? 'Individual'); ?></span>
                    </div>
                    
                    <div style="margin-bottom: 1rem;">
                        <label style="font-weight: 600; color: var(--secondary-violet); display: block; margin-bottom: 0.25rem;">Monthly Contribution:</label>
                        <span>KES <?php echo number_format($member->monthly_amount ?? 500); ?></span>
                    </div>
                    
                    <div style="margin-bottom: 1rem;">
                        <label style="font-weight: 600; color: var(--secondary-violet); display: block; margin-bottom: 0.25rem;">Registration Date:</label>
                        <span><?php echo date('F d, Y', strtotime($member->created_at)); ?></span>
                    </div>
                    
                    <div style="margin-bottom: 0;">
                        <label style="font-weight: 600; color: var(--secondary-violet); display: block; margin-bottom: 0.25rem;">Account Status:</label>
                        <?php if ($member->status == 'active'): ?>
                            <span class="badge badge-success">Active</span>
                        <?php else: ?>
                            <span class="badge badge-warning">Pending</span>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div style="margin-top: 1.5rem;">
                    <a href="/member/dashboard" class="btn btn-outline">
                        <i class="bi bi-arrow-left"></i> Back to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include VIEWS_PATH . '/layouts/member-footer.php'; ?>
