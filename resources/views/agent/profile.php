<?php $page = 'profile'; include __DIR__ . '/../layouts/agent-header.php'; ?>

<div class="page-header">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h2 class="mb-0">
                <i class="fas fa-user-edit text-primary"></i> My Profile
            </h2>
            <p class="text-muted mb-0">Manage your agent profile information</p>
        </div>
    </div>
</div>

<div class="row">
    <!-- Profile Information Card -->
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-id-card"></i> Agent Information
            </div>
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="fas fa-user-circle fa-5x text-primary"></i>
                </div>
                <h4 class="mb-1"><?php echo htmlspecialchars($agent['first_name'] . ' ' . $agent['last_name']); ?></h4>
                <p class="text-muted mb-2">Agent ID: <strong><?php echo htmlspecialchars($agent['agent_number']); ?></strong></p>
                <span class="badge bg-<?php echo $agent['status'] === 'active' ? 'success' : 'warning'; ?> mb-3">
                    <?php echo ucfirst($agent['status']); ?>
                </span>
                <hr>
                <div class="text-start">
                    <p class="mb-2">
                        <i class="fas fa-envelope text-muted"></i>
                        <small><?php echo htmlspecialchars($agent['email']); ?></small>
                    </p>
                    <p class="mb-2">
                        <i class="fas fa-phone text-muted"></i>
                        <small><?php echo htmlspecialchars($agent['phone']); ?></small>
                    </p>
                    <p class="mb-2">
                        <i class="fas fa-id-badge text-muted"></i>
                        <small><?php echo htmlspecialchars($agent['national_id']); ?></small>
                    </p>
                    <p class="mb-2">
                        <i class="fas fa-calendar text-muted"></i>
                        <small>Joined: <?php echo date('M d, Y', strtotime($agent['registration_date'])); ?></small>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Profile Form -->
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-edit"></i> Edit Profile
            </div>
            <div class="card-body">
                <form method="POST" action="/agent/profile/update">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="first_name" name="first_name" 
                                   value="<?php echo htmlspecialchars($agent['first_name']); ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="last_name" name="last_name" 
                                   value="<?php echo htmlspecialchars($agent['last_name']); ?>" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">Phone Number <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control" id="phone" name="phone" 
                                   value="<?php echo htmlspecialchars($agent['phone']); ?>" required>
                            <small class="form-text text-muted">Format: +254712345678</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="<?php echo htmlspecialchars($agent['email']); ?>" readonly disabled>
                            <small class="form-text text-muted">Email cannot be changed</small>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control" id="address" name="address" rows="2"><?php echo htmlspecialchars($agent['address'] ?? ''); ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="county" class="form-label">County</label>
                        <select class="form-select" id="county" name="county">
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

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="button" class="btn btn-secondary" onclick="window.location.href='/agent/dashboard'">
                            <i class="fas fa-times"></i> Cancel
                        </button>
                        <button type="submit" class="btn btn-agent-primary">
                            <i class="fas fa-save"></i> Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Change Password Card -->
        <div class="card mt-4">
            <div class="card-header">
                <i class="fas fa-lock"></i> Change Password
            </div>
            <div class="card-body">
                <form method="POST" action="/agent/password/update">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                    
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Current Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="current_password" name="current_password" required>
                    </div>

                    <div class="mb-3">
                        <label for="new_password" class="form-label">New Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="new_password" name="new_password" required minlength="8">
                        <small class="form-text text-muted">Minimum 8 characters</small>
                    </div>

                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Confirm New Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-key"></i> Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/agent-footer.php'; ?>
