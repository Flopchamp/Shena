<?php $page = 'members'; include __DIR__ . '/../layouts/agent-header.php'; ?>

<div class="page-header">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h2 class="mb-0">
                <i class="fas fa-users text-primary"></i> My Members
            </h2>
            <p class="text-muted mb-0">Members you have registered</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="/agent/register-member" class="btn btn-agent-primary">
                <i class="fas fa-user-plus"></i> Register New Member
            </a>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <i class="fas fa-list"></i> Member List
    </div>
    <div class="card-body">
        <?php if (empty($members)): ?>
            <div class="text-center py-5">
                <i class="fas fa-users fa-4x text-muted mb-3"></i>
                <h5 class="text-muted">No members registered yet</h5>
                <p class="text-muted">Start registering members to build your client base</p>
                <a href="/agent/register-member" class="btn btn-agent-primary mt-3">
                    <i class="fas fa-user-plus"></i> Register Your First Member
                </a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover" id="membersTable">
                    <thead>
                        <tr>
                            <th>Member Number</th>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Package</th>
                            <th>Status</th>
                            <th>Registered</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($members as $member): ?>
                        <tr>
                            <td>
                                <strong><?php echo htmlspecialchars($member['member_number']); ?></strong>
                            </td>
                            <td>
                                <i class="fas fa-user-circle text-muted"></i>
                                <?php echo htmlspecialchars($member['first_name'] . ' ' . $member['last_name']); ?>
                            </td>
                            <td>
                                <i class="fas fa-phone text-muted"></i>
                                <?php echo htmlspecialchars($member['phone']); ?>
                            </td>
                            <td>
                                <i class="fas fa-envelope text-muted"></i>
                                <?php echo htmlspecialchars($member['email']); ?>
                            </td>
                            <td>
                                <span class="badge bg-info">
                                    <?php echo ucfirst($member['package']); ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-<?php 
                                    echo $member['status'] === 'active' ? 'success' : 
                                         ($member['status'] === 'inactive' ? 'secondary' : 
                                         ($member['status'] === 'grace_period' ? 'warning' : 'danger')); 
                                ?>">
                                    <?php echo ucfirst(str_replace('_', ' ', $member['status'])); ?>
                                </span>
                            </td>
                            <td>
                                <small><?php echo date('M d, Y', strtotime($member['created_at'])); ?></small>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/../layouts/agent-footer.php'; ?>
