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
                                <th>Service Type</th>
                                <th>Status</th>
                                <th>Progress</th>
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
                                            <?php echo !empty($claim['relationship_to_deceased']) ? ucfirst($claim['relationship_to_deceased']) : 'N/A'; ?>
                                        </small>
                                    </div>
                                </td>
                                <td>
                                    <?php if ($claim['service_delivery_type'] === 'cash_alternative'): ?>
                                        <span class="badge badge-warning">
                                            <i class="fas fa-money-bill"></i> Cash (KES 20,000)
                                        </span>
                                    <?php else: ?>
                                        <span class="badge badge-success">
                                            <i class="fas fa-hands-helping"></i> Standard Services
                                        </span>
                                    <?php endif; ?>
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
                                        <?php echo ucfirst(str_replace('_', ' ', $claim['status'])); ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if (in_array($claim['status'], ['approved', 'services_in_progress'])): ?>
                                        <?php
                                        $completed_services = 0;
                                        $total_services = 5;
                                        if ($claim['mortuary_bill_settled']) $completed_services++;
                                        if ($claim['body_dressing_completed']) $completed_services++;
                                        if ($claim['coffin_delivered']) $completed_services++;
                                        if ($claim['transportation_arranged']) $completed_services++;
                                        if ($claim['equipment_delivered']) $completed_services++;
                                        $progress = ($completed_services / $total_services) * 100;
                                        ?>
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar bg-<?php echo $progress == 100 ? 'success' : 'info'; ?>" 
                                                 style="width: <?php echo $progress; ?>%">
                                                <?php echo $completed_services; ?>/<?php echo $total_services; ?>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-muted">N/A</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo date('M j, Y', strtotime($claim['created_at'])); ?></td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#claimModal<?php echo $claim['id']; ?>">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <?php if ($claim['status'] === 'pending'): ?>
                                        <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#approveModal<?php echo $claim['id']; ?>">
                                            <i class="fas fa-check"></i> Approve
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm" onclick="rejectClaim(<?php echo $claim['id']; ?>)">
                                            <i class="fas fa-times"></i>
                                        </button>
                                        <?php endif; ?>
                                        <?php if (in_array($claim['status'], ['approved', 'services_in_progress'])): ?>
                                        <a href="/admin/claims/track/<?php echo $claim['id']; ?>" class="btn btn-primary btn-sm">
                                            <i class="fas fa-tasks"></i> Track Services
                                        </a>
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
                                                    <p><strong>Service Type:</strong> 
                                                        <?php if ($claim['service_delivery_type'] === 'cash_alternative'): ?>
                                                            <span class="badge badge-warning">Cash Alternative (KES 20,000)</span>
                                                        <?php else: ?>
                                                            <span class="badge badge-success">Standard Services</span>
                                                        <?php endif; ?>
                                                    </p>
                                                    <p><strong>Status:</strong> <span class="badge badge-<?php echo $claim['status'] === 'approved' ? 'success' : 'warning'; ?>"><?php echo ucfirst(str_replace('_', ' ', $claim['status'])); ?></span></p>
                                                    <p><strong>Date Submitted:</strong> <?php echo date('M j, Y H:i', strtotime($claim['created_at'])); ?></p>
                                                    <?php if (!empty($claim['services_delivery_date'])): ?>
                                                    <p><strong>Service Delivery Date:</strong> <?php echo date('M j, Y', strtotime($claim['services_delivery_date'])); ?></p>
                                                    <?php endif; ?>
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
                                                            <?php if (!empty($claim['deceased_id_number'])): ?>
                                                            <p><strong>ID Number:</strong> <?php echo htmlspecialchars($claim['deceased_id_number']); ?></p>
                                                            <?php endif; ?>
                                                            <p><strong>Relationship:</strong> <?php echo ucfirst($claim['relationship_to_deceased'] ?? 'N/A'); ?></p>
                                                            <p><strong>Date of Death:</strong> <?php echo date('M j, Y', strtotime($claim['date_of_death'])); ?></p>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <p><strong>Place of Death:</strong> <?php echo htmlspecialchars($claim['place_of_death'] ?? 'N/A'); ?></p>
                                                            <p><strong>Cause of Death:</strong> <?php echo htmlspecialchars($claim['cause_of_death'] ?? 'N/A'); ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <hr>
                                            
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <h6>Mortuary Details</h6>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <p><strong>Mortuary Name:</strong> <?php echo htmlspecialchars($claim['mortuary_name'] ?? 'N/A'); ?></p>
                                                            <p><strong>Days in Mortuary:</strong> <?php echo $claim['mortuary_days_count'] ?? 0; ?> days</p>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <?php if (!empty($claim['mortuary_bill_amount'])): ?>
                                                            <p><strong>Bill Amount:</strong> KES <?php echo number_format($claim['mortuary_bill_amount'], 2); ?></p>
                                                            <?php endif; ?>
                                                            <?php if (!empty($claim['mortuary_bill_reference'])): ?>
                                                            <p><strong>Bill Reference:</strong> <?php echo htmlspecialchars($claim['mortuary_bill_reference']); ?></p>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <?php if ($claim['service_delivery_type'] === 'standard_services'): ?>
                                            <hr>
                                            <h6>Service Delivery Checklist</h6>
                                            <ul class="list-unstyled">
                                                <li><i class="fas fa-<?php echo $claim['mortuary_bill_settled'] ? 'check-circle text-success' : 'circle text-muted'; ?>"></i> Mortuary Bill Payment</li>
                                                <li><i class="fas fa-<?php echo $claim['body_dressing_completed'] ? 'check-circle text-success' : 'circle text-muted'; ?>"></i> Body Dressing</li>
                                                <li><i class="fas fa-<?php echo $claim['coffin_delivered'] ? 'check-circle text-success' : 'circle text-muted'; ?>"></i> Executive Coffin</li>
                                                <li><i class="fas fa-<?php echo $claim['transportation_arranged'] ? 'check-circle text-success' : 'circle text-muted'; ?>"></i> Transportation</li>
                                                <li><i class="fas fa-<?php echo $claim['equipment_delivered'] ? 'check-circle text-success' : 'circle text-muted'; ?>"></i> Equipment (Lowering gear, trolley, gazebo, 100 chairs)</li>
                                            </ul>
                                            <?php endif; ?>
                                            
                                            <?php if ($claim['service_delivery_type'] === 'cash_alternative' && !empty($claim['cash_alternative_reason'])): ?>
                                            <hr>
                                            <h6>Cash Alternative Details</h6>
                                            <div class="alert alert-warning">
                                                <strong>Reason:</strong> <?php echo nl2br(htmlspecialchars($claim['cash_alternative_reason'])); ?><br>
                                                <strong>Amount:</strong> KES 20,000.00
                                            </div>
                                            <?php endif; ?>

                                            <?php if (!empty($claim['supporting_documents'])): ?>
                                            <hr>
                                            <h6>Supporting Documents</h6>
                                            <p><a href="/uploads/<?php echo htmlspecialchars($claim['supporting_documents']); ?>" target="_blank" class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-download mr-2"></i>View Documents
                                            </a></p>
                                            <?php endif; ?>

                                            <?php if (!empty($claim['admin_notes']) || !empty($claim['processing_notes'])): ?>
                                            <hr>
                                            <h6>Admin Notes</h6>
                                            <div class="alert alert-info">
                                                <?php echo nl2br(htmlspecialchars($claim['admin_notes'] ?? $claim['processing_notes'] ?? '')); ?>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Approve Claim Modal -->
                            <?php if ($claim['status'] === 'pending'): ?>
                            <div class="modal fade" id="approveModal<?php echo $claim['id']; ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header bg-success text-white">
                                            <h5 class="modal-title">
                                                <i class="fas fa-check-circle"></i> Approve Claim #<?php echo str_pad($claim['id'], 4, '0', STR_PAD_LEFT); ?>
                                            </h5>
                                            <button type="button" class="close text-white" data-dismiss="modal">
                                                <span>&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="alert alert-info">
                                                <strong>Member:</strong> <?php echo htmlspecialchars($claim['first_name'] . ' ' . $claim['last_name']); ?><br>
                                                <strong>Deceased:</strong> <?php echo htmlspecialchars($claim['deceased_name']); ?><br>
                                                <strong>Mortuary Days:</strong> <?php echo $claim['mortuary_days_count'] ?? 14; ?> days
                                            </div>
                                            
                                            <h6 class="font-weight-bold">Select Service Delivery Type:</h6>
                                            
                                            <!-- Standard Services Option -->
                                            <div class="card mb-3 border-success">
                                                <div class="card-body">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="approval_type<?php echo $claim['id']; ?>" 
                                                               id="standardServices<?php echo $claim['id']; ?>" value="standard" checked>
                                                        <label class="form-check-label font-weight-bold" for="standardServices<?php echo $claim['id']; ?>">
                                                            <i class="fas fa-hands-helping text-success"></i> Standard Services
                                                        </label>
                                                    </div>
                                                    <small class="text-muted d-block mt-2">
                                                        Per SHENA Policy Section 3, provide:
                                                        <ul class="mb-0">
                                                            <li>Mortuary bill payment (up to 14 days)</li>
                                                            <li>Body dressing</li>
                                                            <li>Executive coffin</li>
                                                            <li>Transportation</li>
                                                            <li>Equipment (lowering gear, trolley, gazebo, 100 chairs)</li>
                                                        </ul>
                                                    </small>
                                                </div>
                                            </div>
                                            
                                            <!-- Cash Alternative Option -->
                                            <div class="card border-warning">
                                                <div class="card-body">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="approval_type<?php echo $claim['id']; ?>" 
                                                               id="cashAlternative<?php echo $claim['id']; ?>" value="cash">
                                                        <label class="form-check-label font-weight-bold" for="cashAlternative<?php echo $claim['id']; ?>">
                                                            <i class="fas fa-money-bill text-warning"></i> Cash Alternative (KES 20,000)
                                                        </label>
                                                    </div>
                                                    <small class="text-muted d-block mt-2">
                                                        Per Policy Section 12, only for exceptional circumstances
                                                    </small>
                                                    
                                                    <div id="cashReasonSection<?php echo $claim['id']; ?>" class="mt-3" style="display: none;">
                                                        <label class="font-weight-bold">Reason for Cash Alternative: *</label>
                                                        <select class="form-control" id="cashReason<?php echo $claim['id']; ?>">
                                                            <option value="">Select reason...</option>
                                                            <option value="member_preference">Member's explicit preference</option>
                                                            <option value="remote_location">Remote location - services unavailable</option>
                                                            <option value="cultural_religious">Cultural/religious requirements</option>
                                                            <option value="urgent_burial">Urgent burial needed</option>
                                                            <option value="other">Other exceptional circumstances</option>
                                                        </select>
                                                        
                                                        <div id="cashReasonOther<?php echo $claim['id']; ?>" class="mt-2" style="display: none;">
                                                            <textarea class="form-control" placeholder="Please specify the reason..." rows="2"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="form-group mt-3">
                                                <label class="font-weight-bold">Admin Notes:</label>
                                                <textarea class="form-control" id="adminNotes<?php echo $claim['id']; ?>" rows="2" 
                                                          placeholder="Add any notes about this approval..."></textarea>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label class="font-weight-bold">Service Delivery Date:</label>
                                                <input type="date" class="form-control" id="deliveryDate<?php echo $claim['id']; ?>" 
                                                       min="<?php echo date('Y-m-d'); ?>" value="<?php echo date('Y-m-d'); ?>">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                            <button type="button" class="btn btn-success" onclick="submitApproval(<?php echo $claim['id']; ?>)">
                                                <i class="fas fa-check"></i> Approve Claim
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                            
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
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> <strong>Service-Based Claims:</strong> Claims default to standard funeral services. Cash alternative (KES 20,000) only for exceptional circumstances per policy.
                    </div>
                    
                    <h6 class="font-weight-bold">Member Selection</h6>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Member <span class="text-danger">*</span></label>
                                <select name="member_id" class="form-control" id="memberSelect" required>
                                    <option value="">Select Member</option>
                                    <?php if (isset($members)): ?>
                                        <?php foreach ($members as $m): ?>
                                            <option value="<?php echo $m['id']; ?>" 
                                                    data-name="<?php echo htmlspecialchars($m['first_name'] . ' ' . $m['last_name']); ?>"
                                                    data-number="<?php echo htmlspecialchars($m['member_number']); ?>">
                                                <?php echo htmlspecialchars($m['member_number'] . ' - ' . $m['first_name'] . ' ' . $m['last_name']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    <h6 class="font-weight-bold">Deceased Information</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Full Name <span class="text-danger">*</span></label>
                                <input type="text" name="deceased_name" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>ID/Birth Certificate Number <span class="text-danger">*</span></label>
                                <input type="text" name="deceased_id_number" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Relationship to Deceased <span class="text-danger">*</span></label>
                                <select name="relationship_to_deceased" class="form-control" required>
                                    <option value="">Select Relationship</option>
                                    <option value="self">Self</option>
                                    <option value="spouse">Spouse</option>
                                    <option value="parent">Parent</option>
                                    <option value="child">Child</option>
                                    <option value="sibling">Sibling</option>
                                    <option value="dependent">Registered Dependent</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Date of Birth</label>
                                <input type="date" name="date_of_birth" class="form-control">
                                <small class="form-text text-muted">For dependent age verification</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Date of Death <span class="text-danger">*</span></label>
                                <input type="date" name="date_of_death" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Place of Death <span class="text-danger">*</span></label>
                                <input type="text" name="place_of_death" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Cause of Death <span class="text-danger">*</span></label>
                        <textarea name="cause_of_death" class="form-control" rows="2" required></textarea>
                        <small class="form-text text-muted">Required for policy exclusion verification</small>
                    </div>
                    
                    <hr>
                    <h6 class="font-weight-bold">Mortuary & Service Details</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Mortuary Name <span class="text-danger">*</span></label>
                                <input type="text" name="mortuary_name" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Days in Mortuary <span class="text-danger">*</span></label>
                                <input type="number" name="mortuary_days_count" class="form-control" min="0" max="14" value="14" required>
                                <small class="form-text text-muted">Maximum 14 days per policy</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Mortuary Bill Amount</label>
                                <input type="number" name="mortuary_bill_amount" class="form-control" step="0.01">
                                <small class="form-text text-muted">For reference and verification</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Bill Reference/Invoice #</label>
                                <input type="text" name="mortuary_bill_reference" class="form-control">
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    <h6 class="font-weight-bold">Required Documents</h6>
                    <div class="form-group">
                        <label>Supporting Documents</label>
                        <input type="file" name="supporting_documents" class="form-control-file" accept=".pdf,.jpg,.jpeg,.png" multiple>
                        <small class="form-text text-muted">ID/Birth Certificate, Chief's Letter, Mortuary Invoice, Death Certificate (optional)</small>
                    </div>
                    
                    <div class="form-group">
                        <label>Admin Notes</label>
                        <textarea name="admin_notes" class="form-control" rows="3" placeholder="Additional notes about this claim..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Create Claim
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Handle cash alternative radio button
document.addEventListener('DOMContentLoaded', function() {
    const radios = document.querySelectorAll('input[name^="approval_type"]');
    radios.forEach(radio => {
        radio.addEventListener('change', function() {
            const claimId = this.name.replace('approval_type', '');
            const cashReasonSection = document.getElementById('cashReasonSection' + claimId);
            
            if (this.value === 'cash' && this.checked) {
                cashReasonSection.style.display = 'block';
            } else {
                cashReasonSection.style.display = 'none';
            }
        });
    });
    
    // Handle "Other" reason selection
    const reasonSelects = document.querySelectorAll('select[id^="cashReason"]');
    reasonSelects.forEach(select => {
        select.addEventListener('change', function() {
            const claimId = this.id.replace('cashReason', '');
            const otherSection = document.getElementById('cashReasonOther' + claimId);
            
            if (this.value === 'other') {
                otherSection.style.display = 'block';
            } else {
                otherSection.style.display = 'none';
            }
        });
    });
});

function submitApproval(claimId) {
    const approvalType = document.querySelector(`input[name="approval_type${claimId}"]:checked`).value;
    const adminNotes = document.getElementById(`adminNotes${claimId}`).value;
    const deliveryDate = document.getElementById(`deliveryDate${claimId}`).value;
    
    let url, formData;
    
    if (approvalType === 'cash') {
        // Cash alternative approval
        const cashReason = document.getElementById(`cashReason${claimId}`).value;
        
        if (!cashReason) {
            alert('Please select a reason for cash alternative');
            return;
        }
        
        let reasonText = cashReason;
        if (cashReason === 'other') {
            const otherTextarea = document.querySelector(`#cashReasonOther${claimId} textarea`);
            if (!otherTextarea.value.trim()) {
                alert('Please specify the reason for cash alternative');
                return;
            }
            reasonText = otherTextarea.value.trim();
        }
        
        if (!confirm('Approve this claim for KES 20,000 cash alternative?\n\nReason: ' + reasonText)) {
            return;
        }
        
        url = '/admin/claims/approve-cash';
        formData = new FormData();
        formData.append('claim_id', claimId);
        formData.append('cash_alternative_reason', reasonText);
        formData.append('admin_notes', adminNotes);
        formData.append('services_delivery_date', deliveryDate);
        
    } else {
        // Standard services approval
        if (!confirm('Approve this claim for standard service delivery?')) {
            return;
        }
        
        url = '/admin/claims/approve';
        formData = new FormData();
        formData.append('claim_id', claimId);
        formData.append('admin_notes', adminNotes);
        formData.append('services_delivery_date', deliveryDate);
    }
    
    // Submit the form
    fetch(url, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message || 'Claim approved successfully!');
            location.reload();
        } else {
            alert(data.message || 'Error approving claim');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while processing the approval');
    });
}

function rejectClaim(claimId) {
    const reason = prompt('Reason for rejection:');
    if (reason) {
        if (confirm('Reject this claim?')) {
            // Implementation for rejecting claim
            window.location.href = `/admin/claims/reject/${claimId}?reason=${encodeURIComponent(reason)}`;
        }
    }
}
</script>

<?php include_once 'admin-footer.php'; ?>
