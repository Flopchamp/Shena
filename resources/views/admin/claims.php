<?php include_once 'admin-header.php'; ?>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-file-medical mr-2"></i>Claims Management
        </h1>
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#newClaimModal">
            <i class="fas fa-plus mr-2"></i>New Claim
        </button>
    </div>

    <!-- Claims Statistics -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Pending Claims
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo count(array_filter($claims, fn($c) => $c['status'] === 'pending')); ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Approved Claims
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo count(array_filter($claims, fn($c) => $c['status'] === 'approved')); ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Total Value
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                KES <?php echo number_format(array_sum(array_column($claims, 'claim_amount')), 2); ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Rejected Claims
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo count(array_filter($claims, fn($c) => $c['status'] === 'rejected')); ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Claims Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Insurance Claims</h6>
            <div class="btn-group">
                <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-toggle="dropdown">
                    Filter by Status
                </button>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="/admin/claims">All Claims</a>
                    <a class="dropdown-item" href="/admin/claims?status=pending">Pending</a>
                    <a class="dropdown-item" href="/admin/claims?status=approved">Approved</a>
                    <a class="dropdown-item" href="/admin/claims?status=rejected">Rejected</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <?php if (!empty($claims)): ?>
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Claim ID</th>
                                <th>Member</th>
                                <th>Deceased Name</th>
                                <th>Claim Amount</th>
                                <th>Status</th>
                                <th>Submitted</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($claims as $claim): ?>
                            <tr>
                                <td>#<?php echo str_pad($claim['id'], 4, '0', STR_PAD_LEFT); ?></td>
                                <td>
                                    <div>
                                        <strong><?php echo htmlspecialchars($claim['first_name'] . ' ' . $claim['last_name']); ?></strong><br>
                                        <small class="text-muted"><?php echo htmlspecialchars($claim['member_number']); ?></small>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <strong><?php echo htmlspecialchars($claim['deceased_name']); ?></strong><br>
                                        <small class="text-muted">
                                            <?php echo ucfirst($claim['relationship_to_deceased']); ?>
                                        </small>
                                    </div>
                                </td>
                                <td>
                                    <strong>KES <?php echo number_format($claim['claim_amount'], 2); ?></strong>
                                </td>
                                <td>
                                    <span class="badge badge-<?php 
                                        echo match($claim['status']) {
                                            'pending' => 'warning',
                                            'approved' => 'success',
                                            'rejected' => 'danger',
                                            'processing' => 'info',
                                            default => 'secondary'
                                        };
                                    ?>">
                                        <?php echo ucfirst($claim['status']); ?>
                                    </span>
                                </td>
                                <td><?php echo date('M j, Y', strtotime($claim['created_at'])); ?></td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#claimModal<?php echo $claim['id']; ?>">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <?php if ($claim['status'] === 'pending'): ?>
                                        <button type="button" class="btn btn-success btn-sm" onclick="approveClaim(<?php echo $claim['id']; ?>)">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm" onclick="rejectClaim(<?php echo $claim['id']; ?>)">
                                            <i class="fas fa-times"></i>
                                        </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>

                            <!-- Claim Details Modal -->
                            <div class="modal fade" id="claimModal<?php echo $claim['id']; ?>" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Claim Details - #<?php echo str_pad($claim['id'], 4, '0', STR_PAD_LEFT); ?></h5>
                                            <button type="button" class="close" data-dismiss="modal">
                                                <span>&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <h6>Claim Information</h6>
                                                    <p><strong>Claim Amount:</strong> KES <?php echo number_format($claim['claim_amount'], 2); ?></p>
                                                    <p><strong>Status:</strong> <span class="badge badge-<?php echo $claim['status'] === 'approved' ? 'success' : 'warning'; ?>"><?php echo ucfirst($claim['status']); ?></span></p>
                                                    <p><strong>Date Submitted:</strong> <?php echo date('M j, Y H:i', strtotime($claim['created_at'])); ?></p>
                                                    <?php if (!empty($claim['processed_at'])): ?>
                                                    <p><strong>Date Processed:</strong> <?php echo date('M j, Y H:i', strtotime($claim['processed_at'])); ?></p>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="col-md-6">
                                                    <h6>Member Information</h6>
                                                    <p><strong>Name:</strong> <?php echo htmlspecialchars($claim['first_name'] . ' ' . $claim['last_name']); ?></p>
                                                    <p><strong>Member Number:</strong> <?php echo htmlspecialchars($claim['member_number']); ?></p>
                                                    <p><strong>Phone:</strong> <?php echo htmlspecialchars($claim['phone_number'] ?? 'N/A'); ?></p>
                                                </div>
                                            </div>
                                            
                                            <hr>
                                            
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <h6>Deceased Information</h6>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <p><strong>Full Name:</strong> <?php echo htmlspecialchars($claim['deceased_name']); ?></p>
                                                            <p><strong>Relationship:</strong> <?php echo ucfirst($claim['relationship_to_deceased']); ?></p>
                                                            <p><strong>Date of Death:</strong> <?php echo date('M j, Y', strtotime($claim['date_of_death'])); ?></p>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <p><strong>Place of Death:</strong> <?php echo htmlspecialchars($claim['place_of_death'] ?? 'N/A'); ?></p>
                                                            <p><strong>Cause of Death:</strong> <?php echo htmlspecialchars($claim['cause_of_death'] ?? 'N/A'); ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <?php if (!empty($claim['description'])): ?>
                                            <hr>
                                            <h6>Additional Information</h6>
                                            <p><?php echo nl2br(htmlspecialchars($claim['description'])); ?></p>
                                            <?php endif; ?>

                                            <?php if (!empty($claim['supporting_documents'])): ?>
                                            <hr>
                                            <h6>Supporting Documents</h6>
                                            <p><a href="/uploads/<?php echo htmlspecialchars($claim['supporting_documents']); ?>" target="_blank" class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-download mr-2"></i>View Documents
                                            </a></p>
                                            <?php endif; ?>

                                            <?php if (!empty($claim['admin_notes'])): ?>
                                            <hr>
                                            <h6>Admin Notes</h6>
                                            <div class="alert alert-info">
                                                <?php echo nl2br(htmlspecialchars($claim['admin_notes'])); ?>
                                            </div>
                                            <?php endif; ?>
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
                    <i class="fas fa-file-medical fa-3x text-gray-300 mb-3"></i>
                    <h5 class="text-gray-600">No claims found</h5>
                    <p class="text-gray-500">Insurance claims will appear here when members submit them.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- New Claim Modal -->
<div class="modal fade" id="newClaimModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create New Claim</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form method="POST" action="/admin/claims/create" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Member *</label>
                                <select name="member_id" class="form-control" required>
                                    <option value="">Select Member</option>
                                    <!-- Members will be populated here -->
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Claim Amount *</label>
                                <input type="number" name="claim_amount" class="form-control" step="0.01" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Deceased Name *</label>
                                <input type="text" name="deceased_name" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Relationship to Deceased *</label>
                                <select name="relationship_to_deceased" class="form-control" required>
                                    <option value="">Select Relationship</option>
                                    <option value="spouse">Spouse</option>
                                    <option value="parent">Parent</option>
                                    <option value="child">Child</option>
                                    <option value="sibling">Sibling</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Date of Death *</label>
                                <input type="date" name="date_of_death" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Place of Death</label>
                                <input type="text" name="place_of_death" class="form-control">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Cause of Death</label>
                        <input type="text" name="cause_of_death" class="form-control">
                    </div>
                    
                    <div class="form-group">
                        <label>Supporting Documents</label>
                        <input type="file" name="supporting_documents" class="form-control-file" accept=".pdf,.jpg,.jpeg,.png">
                        <small class="form-text text-muted">Upload death certificate, medical reports, etc. (PDF, JPG, PNG)</small>
                    </div>
                    
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Additional information about the claim..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Claim</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function approveClaim(claimId) {
    const notes = prompt('Admin notes (optional):');
    if (confirm('Approve this claim?')) {
        // Implementation for approving claim
        window.location.href = `/admin/claims/approve/${claimId}?notes=${encodeURIComponent(notes || '')}`;
    }
}

function rejectClaim(claimId) {
    const reason = prompt('Reason for rejection:');
    if (reason) {
        // Implementation for rejecting claim
        window.location.href = `/admin/claims/reject/${claimId}?reason=${encodeURIComponent(reason)}`;
    }
}
</script>

<?php include_once 'admin-footer.php'; ?>
