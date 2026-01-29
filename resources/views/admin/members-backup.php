<?php include_once 'admin-header.php'; ?>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-users mr-2"></i>Members Management
        </h1>
        <div class="btn-group">
            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                <i class="fas fa-filter mr-2"></i>Filter
            </button>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="/admin/members">All Members</a>
                <a class="dropdown-item" href="/admin/members?status=active">Active</a>
                <a class="dropdown-item" href="/admin/members?status=pending">Pending</a>
                <a class="dropdown-item" href="/admin/members?status=inactive">Inactive</a>
            </div>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Search Members</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="/admin/members" class="row">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Search by name, email, or member number" value="<?php echo htmlspecialchars($search); ?>">
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-control">
                        <option value="">All Status</option>
                        <option value="active" <?php echo $status === 'active' ? 'selected' : ''; ?>>Active</option>
                        <option value="pending" <?php echo $status === 'pending' ? 'selected' : ''; ?>>Pending</option>
                        <option value="inactive" <?php echo $status === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                        <option value="suspended" <?php echo $status === 'suspended' ? 'selected' : ''; ?>>Suspended</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-search mr-2"></i>Search
                    </button>
                </div>
                <div class="col-md-3">
                    <a href="/admin/members" class="btn btn-secondary btn-block">
                        <i class="fas fa-times mr-2"></i>Clear
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Members Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                Members List (<?php echo number_format($total_members); ?> total)
            </h6>
        </div>
        <div class="card-body">
            <?php if (!empty($members)): ?>
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Member #</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Package</th>
                                <th>Status</th>
                                <th>Registered</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($members as $member): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($member['member_number']); ?></td>
                                <td><?php echo htmlspecialchars($member['first_name'] . ' ' . $member['last_name']); ?></td>
                                <td><?php echo htmlspecialchars($member['email']); ?></td>
                                <td><?php echo htmlspecialchars($member['phone']); ?></td>
                                <td>
                                    <span class="badge badge-info">
                                        <?php echo ucfirst($member['package'] ?? 'individual'); ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-<?php 
                                        echo match($member['status']) {
                                            'active' => 'success',
                                            'pending' => 'warning',
                                            'inactive' => 'secondary',
                                            'suspended' => 'danger',
                                            default => 'secondary'
                                        };
                                    ?>">
                                        <?php echo ucfirst($member['status']); ?>
                                    </span>
                                </td>
                                <td><?php echo date('M j, Y', strtotime($member['registration_date'] ?? $member['created_at'])); ?></td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <?php if ($member['status'] === 'inactive'): ?>
                                        <form method="POST" action="/admin/member/activate" style="display: inline;">
                                            <input type="hidden" name="member_id" value="<?php echo $member['id']; ?>">
                                            <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Activate this member?')">
                                                <i class="fas fa-check"></i> Activate
                                            </button>
                                        </form>
                                        <?php elseif ($member['status'] === 'active'): ?>
                                        <form method="POST" action="/admin/member/deactivate" style="display: inline;">
                                            <input type="hidden" name="member_id" value="<?php echo $member['id']; ?>">
                                            <button type="submit" class="btn btn-warning btn-sm" onclick="return confirm('Deactivate this member?')">
                                                <i class="fas fa-pause"></i> Deactivate
                                            </button>
                                        </form>
                                        <?php else: ?>
                                        <form method="POST" action="/admin/member/activate" style="display: inline;">
                                            <input type="hidden" name="member_id" value="<?php echo $member['id']; ?>">
                                            <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Activate this member?')">
                                                <i class="fas fa-play"></i> Activate
                                            </button>
                                        </form>
                                        <?php endif; ?>
                                        <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#memberModal<?php echo $member['id']; ?>">
                                            <i class="fas fa-eye"></i> View
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Member Details Modal -->
                            <div class="modal fade" id="memberModal<?php echo $member['id']; ?>" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Member Details - <?php echo htmlspecialchars($member['first_name'] . ' ' . $member['last_name']); ?></h5>
                                            <button type="button" class="close" data-dismiss="modal">
                                                <span>&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <h6>Personal Information</h6>
                                                    <p><strong>Member Number:</strong> <?php echo htmlspecialchars($member['member_number']); ?></p>
                                                    <p><strong>Name:</strong> <?php echo htmlspecialchars($member['first_name'] . ' ' . $member['last_name']); ?></p>
                                                    <p><strong>Email:</strong> <?php echo htmlspecialchars($member['email']); ?></p>
                                                    <p><strong>Phone:</strong> <?php echo htmlspecialchars($member['phone']); ?></p>
                                                    <p><strong>ID Number:</strong> <?php echo htmlspecialchars($member['id_number'] ?? 'N/A'); ?></p>
                                                    <p><strong>Date of Birth:</strong> <?php echo $member['date_of_birth'] ? date('M j, Y', strtotime($member['date_of_birth'])) : 'N/A'; ?></p>
                                                </div>
                                                <div class="col-md-6">
                                                    <h6>Membership Information</h6>
                                                    <p><strong>Package:</strong> <?php echo ucfirst($member['package'] ?? 'individual'); ?></p>
                                                    <p><strong>Status:</strong> <span class="badge badge-<?php echo $member['status'] === 'active' ? 'success' : 'warning'; ?>"><?php echo ucfirst($member['status']); ?></span></p>
                                                    <p><strong>Monthly Contribution:</strong> KES <?php echo number_format($member['monthly_contribution'] ?? 0, 2); ?></p>
                                                    <p><strong>Registered:</strong> <?php echo date('M j, Y', strtotime($member['registration_date'] ?? $member['created_at'])); ?></p>
                                                    <p><strong>Next of Kin:</strong> <?php echo htmlspecialchars($member['next_of_kin'] ?? 'N/A'); ?></p>
                                                    <p><strong>Next of Kin Phone:</strong> <?php echo htmlspecialchars($member['next_of_kin_phone'] ?? 'N/A'); ?></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-users fa-3x text-gray-300 mb-3"></i>
                    <h5 class="text-gray-600">No members found</h5>
                    <p class="text-gray-500">
                        <?php echo !empty($search) ? 'Try adjusting your search criteria.' : 'Members will appear here once they register.'; ?>
                    </p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include_once 'admin-footer.php'; ?>
