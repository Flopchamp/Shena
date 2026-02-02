<?php include_once __DIR__ . '/../layouts/admin-header.php'; ?>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-comments mr-2"></i>Communications
        </h1>
        <div class="btn-group">
            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#sendEmailModal">
                <i class="fas fa-envelope mr-2"></i>Quick Email
            </button>
            <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#sendSMSModal">
                <i class="fas fa-sms mr-2"></i>Quick SMS
            </button>
        </div>
    </div>
    
    <!-- Navigation Tabs -->
    <ul class="nav nav-tabs mb-4" id="communicationTabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="history-tab" data-toggle="tab" href="#history" role="tab">
                <i class="fas fa-history mr-2"></i>History
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="email-campaigns-tab" data-toggle="tab" href="#email-campaigns" role="tab">
                <i class="fas fa-envelope-open-text mr-2"></i>Email Campaigns
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="sms-campaigns-tab" data-toggle="tab" href="#sms-campaigns" role="tab">
                <i class="fas fa-comment-dots mr-2"></i>SMS Campaigns
            </a>
        </li>
    </ul>
    
    <!-- Tab Content -->
    <div class="tab-content" id="communicationTabsContent">
        <!-- History Tab -->
        <div class="tab-pane fade show active" id="history" role="tabpanel">
    <!-- Communication Statistics -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Emails Sent
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo count(array_filter($communications, fn($c) => $c['type'] === 'email')); ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-envelope fa-2x text-gray-300"></i>
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
                                SMS Sent
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo count(array_filter($communications, fn($c) => $c['type'] === 'sms')); ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-sms fa-2x text-gray-300"></i>
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
                                This Month
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php 
                                    $thisMonth = array_filter($communications, fn($c) => 
                                        date('Y-m', strtotime($c['sent_at'])) === date('Y-m')
                                    );
                                    echo count($thisMonth);
                                ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Failed
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo count(array_filter($communications, fn($c) => $c['status'] === 'failed')); ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Communications History -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Communication History</h6>
            <div class="btn-group">
                <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-toggle="dropdown">
                    Filter
                </button>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="/admin/communications">All Communications</a>
                    <a class="dropdown-item" href="/admin/communications?type=email">Emails Only</a>
                    <a class="dropdown-item" href="/admin/communications?type=sms">SMS Only</a>
                    <a class="dropdown-item" href="/admin/communications?status=failed">Failed Only</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <?php if (!empty($communications)): ?>
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Type</th>
                                <th>Recipients</th>
                                <th>Subject</th>
                                <th>Status</th>
                                <th>Sent Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($communications as $comm): ?>
                            <tr>
                                <td>
                                    <span class="badge badge-<?php echo $comm['type'] === 'email' ? 'primary' : 'success'; ?>">
                                        <i class="fas fa-<?php echo $comm['type'] === 'email' ? 'envelope' : 'sms'; ?> mr-1"></i>
                                        <?php echo strtoupper($comm['type']); ?>
                                    </span>
                                </td>
                                <td>
                                    <div>
                                        <?php if ($comm['recipient_type'] === 'all'): ?>
                                            <strong>All Members</strong>
                                        <?php elseif ($comm['recipient_type'] === 'group'): ?>
                                            <strong><?php echo ucfirst($comm['recipient_group']); ?> Members</strong>
                                        <?php else: ?>
                                            <?php echo htmlspecialchars($comm['recipient_name'] ?? 'Individual Member'); ?>
                                        <?php endif; ?>
                                        <br>
                                        <small class="text-muted"><?php echo $comm['recipient_count'] ?? 1; ?> recipient(s)</small>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <strong><?php echo htmlspecialchars($comm['subject']); ?></strong><br>
                                        <small class="text-muted">
                                            <?php echo htmlspecialchars(substr($comm['message'], 0, 50) . '...'); ?>
                                        </small>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-<?php 
                                        echo match($comm['status']) {
                                            'sent' => 'success',
                                            'failed' => 'danger',
                                            'pending' => 'warning',
                                            default => 'secondary'
                                        };
                                    ?>">
                                        <?php echo ucfirst($comm['status']); ?>
                                    </span>
                                </td>
                                <td><?php echo date('M j, Y H:i', strtotime($comm['sent_at'])); ?></td>
                                <td>
                                    <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#commModal<?php echo $comm['id']; ?>">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                            </tr>

                            <!-- Communication Details Modal -->
                            <div class="modal fade" id="commModal<?php echo $comm['id']; ?>" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Communication Details</h5>
                                            <button type="button" class="close" data-dismiss="modal">
                                                <span>&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <h6>Communication Info</h6>
                                                    <p><strong>Type:</strong> <?php echo !empty($comm['type']) ? strtoupper($comm['type']) : 'N/A'; ?></p>
                                                    <p><strong>Status:</strong> <span class="badge badge-<?php echo $comm['status'] === 'sent' ? 'success' : 'danger'; ?>"><?php echo !empty($comm['status']) ? ucfirst($comm['status']) : 'Pending'; ?></span></p>
                                                    <p><strong>Sent Date:</strong> <?php echo !empty($comm['sent_at']) ? date('M j, Y H:i:s', strtotime($comm['sent_at'])) : 'Not sent yet'; ?></p>
                                                </div>
                                                <div class="col-md-6">
                                                    <h6>Recipients</h6>
                                                    <p><strong>Type:</strong> <?php echo !empty($comm['recipient_type']) ? ucfirst($comm['recipient_type']) : 'N/A'; ?></p>
                                                    <p><strong>Count:</strong> <?php echo $comm['recipient_count'] ?? 1; ?> recipient(s)</p>
                                                    <?php if (!empty($comm['recipient_group'])): ?>
                                                    <p><strong>Group:</strong> <?php echo ucfirst($comm['recipient_group']); ?></p>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            
                                            <h6>Subject</h6>
                                            <p><?php echo htmlspecialchars($comm['subject'] ?? ''); ?></p>
                                            
                                            <h6>Message</h6>
                                            <div class="border p-3 bg-light">
                                                <?php echo nl2br(htmlspecialchars($comm['message'] ?? '')); ?>
                                            </div>

                                            <?php if (!empty($comm['error_message'])): ?>
                                            <h6 class="mt-3">Error Details</h6>
                                            <div class="alert alert-danger">
                                                <?php echo htmlspecialchars($comm['error_message']); ?>
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
                    <i class="fas fa-comments fa-3x text-gray-300 mb-3"></i>
                    <h5 class="text-gray-600">No communications yet</h5>
                    <p class="text-gray-500">Email and SMS communications will appear here.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
        </div>
        <!-- End History Tab -->
        
        <!-- Email Campaigns Tab -->
        <div class="tab-pane fade" id="email-campaigns" role="tabpanel">
            <iframe src="/admin/email-campaigns" style="width:100%; height:800px; border:none;" id="emailCampaignsFrame"></iframe>
        </div>
        
        <!-- SMS Campaigns Tab -->
        <div class="tab-pane fade" id="sms-campaigns" role="tabpanel">
            <div class="alert alert-info">
                <i class="fas fa-info-circle mr-2"></i>
                SMS Campaigns functionality is managed through the Bulk SMS system below.
            </div>
        </div>
    </div>
    <!-- End Tab Content -->
</div>

<!-- Send Email Modal -->
<div class="modal fade" id="sendEmailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Send Email</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form method="POST" action="/admin/communications/send-email">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Recipients *</label>
                        <select name="recipients" class="form-control" required>
                            <option value="">Select Recipients</option>
                            <option value="all">All Members</option>
                            <option value="active">Active Members Only</option>
                            <option value="inactive">Inactive Members Only</option>
                            <option value="recent">Recently Joined Members</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Subject *</label>
                        <input type="text" name="subject" class="form-control" required placeholder="Email subject">
                    </div>
                    
                    <div class="form-group">
                        <label>Message *</label>
                        <textarea name="message" class="form-control" rows="8" required placeholder="Email message content..."></textarea>
                        <small class="form-text text-muted">You can use HTML formatting in your message.</small>
                    </div>
                    
                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="sendCopy" name="send_copy" value="1">
                            <label class="custom-control-label" for="sendCopy">Send a copy to my email</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane mr-2"></i>Send Email
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Send SMS Modal -->
<div class="modal fade" id="sendSMSModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Send SMS</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form method="POST" action="/admin/communications/send-sms">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Recipients *</label>
                        <select name="recipients" class="form-control" required>
                            <option value="">Select Recipients</option>
                            <option value="all">All Members</option>
                            <option value="active">Active Members Only</option>
                            <option value="inactive">Inactive Members Only</option>
                            <option value="recent">Recently Joined Members</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Message *</label>
                        <textarea name="message" class="form-control" rows="4" required placeholder="SMS message content..." maxlength="160"></textarea>
                        <small class="form-text text-muted">Maximum 160 characters for SMS messages.</small>
                        <div class="text-right">
                            <small id="smsCounter" class="text-muted">0/160</small>
                        </div>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle mr-2"></i>
                        <strong>Note:</strong> SMS charges apply. Please ensure you have sufficient SMS credits before sending.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-sms mr-2"></i>Send SMS
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// SMS character counter
$(document).ready(function() {
    $('textarea[name="message"]').on('input', function() {
        const length = $(this).val().length;
        $('#smsCounter').text(length + '/160');
        
        if (length > 160) {
            $('#smsCounter').addClass('text-danger');
        } else {
            $('#smsCounter').removeClass('text-danger');
        }
    });
});
</script>

<?php include_once __DIR__ . '/../layouts/admin-footer.php'; ?>
