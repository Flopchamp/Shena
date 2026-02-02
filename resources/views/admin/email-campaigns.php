<?php include_once __DIR__ . '/../layouts/admin-header.php'; ?>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-envelope-open-text mr-2"></i>Email Campaigns
        </h1>
        <div class="btn-group">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createCampaignModal">
                <i class="fas fa-plus mr-2"></i>Create Campaign
            </button>
            <a href="/admin/communications" class="btn btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i>Back to Communications
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Active Campaigns
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo $stats['active_campaigns'] ?? 0; ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-paper-plane fa-2x text-gray-300"></i>
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
                                Sent Today
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo number_format($stats['sent_today'] ?? 0); ?>
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
                                Total Sent
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo number_format($stats['total_sent'] ?? 0); ?>
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
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Failed
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo number_format($stats['failed_count'] ?? 0); ?>
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

    <!-- Campaigns List -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-list mr-2"></i>Email Campaigns
            </h6>
        </div>
        <div class="card-body">
            <?php if (!empty($campaigns)): ?>
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%">
                        <thead>
                            <tr>
                                <th>Campaign</th>
                                <th>Audience</th>
                                <th>Recipients</th>
                                <th>Sent/Failed</th>
                                <th>Status</th>
                                <th>Scheduled</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($campaigns as $campaign): ?>
                            <tr>
                                <td>
                                    <strong><?php echo htmlspecialchars($campaign['title']); ?></strong>
                                    <br><small class="text-muted"><?php echo date('M j, Y', strtotime($campaign['created_at'])); ?></small>
                                </td>
                                <td><?php echo ucwords(str_replace('_', ' ', $campaign['target_audience'])); ?></td>
                                <td><?php echo $campaign['total_recipients']; ?></td>
                                <td>
                                    <span class="badge badge-success"><?php echo $campaign['sent_count']; ?></span> /
                                    <span class="badge badge-danger"><?php echo $campaign['failed_count']; ?></span>
                                </td>
                                <td>
                                    <?php
                                    $statusColors = [
                                        'draft' => 'secondary',
                                        'scheduled' => 'warning',
                                        'sending' => 'info',
                                        'completed' => 'success',
                                        'cancelled' => 'danger'
                                    ];
                                    $color = $statusColors[$campaign['status']] ?? 'secondary';
                                    ?>
                                    <span class="badge badge-<?php echo $color; ?>">
                                        <?php echo ucfirst($campaign['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <?php 
                                    if ($campaign['scheduled_at']) {
                                        echo date('M j, Y H:i', strtotime($campaign['scheduled_at']));
                                    } else {
                                        echo '<span class="text-muted">-</span>';
                                    }
                                    ?>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="/admin/email-campaigns/campaign/<?php echo $campaign['id']; ?>" class="btn btn-info btn-sm" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <?php if ($campaign['status'] === 'draft' || $campaign['status'] === 'scheduled'): ?>
                                            <button type="button" class="btn btn-success btn-sm" onclick="sendCampaign(<?php echo $campaign['id']; ?>)" title="Send Now">
                                                <i class="fas fa-paper-plane"></i>
                                            </button>
                                            <button type="button" class="btn btn-warning btn-sm" onclick="cancelCampaign(<?php echo $campaign['id']; ?>)" title="Cancel">
                                                <i class="fas fa-ban"></i>
                                            </button>
                                        <?php endif; ?>
                                        <?php if ($campaign['status'] === 'completed' && $campaign['failed_count'] > 0): ?>
                                            <button type="button" class="btn btn-primary btn-sm" onclick="retryFailed(<?php echo $campaign['id']; ?>)" title="Retry Failed">
                                                <i class="fas fa-redo"></i>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-envelope-open-text fa-3x text-gray-300 mb-3"></i>
                    <h5 class="text-gray-600">No email campaigns yet</h5>
                    <p class="text-gray-500">Create your first email campaign to get started.</p>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createCampaignModal">
                        <i class="fas fa-plus mr-2"></i>Create Campaign
                    </button>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Create Campaign Modal -->
<div class="modal fade" id="createCampaignModal" tabindex="-1" aria-labelledby="createCampaignModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createCampaignModalLabel">Create Email Campaign</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="createCampaignForm" method="POST" action="/admin/email-campaigns/create">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="campaignTitle" class="form-label">Campaign Title *</label>
                                <input type="text" class="form-control" id="campaignTitle" name="title" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="emailSubject" class="form-label">Email Subject *</label>
                                <input type="text" class="form-control" id="emailSubject" name="subject" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="emailMessage" class="form-label">Email Message *</label>
                                <textarea class="form-control" id="emailMessage" name="message" rows="10" required></textarea>
                                <small class="form-text text-muted">
                                    Available placeholders: {name}, {first_name}, {last_name}, {member_number}, {email}
                                </small>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="targetAudience" class="form-label">Target Audience *</label>
                                <select class="form-control" id="targetAudience" name="target_audience" required>
                                    <option value="all_members">All Members</option>
                                    <option value="active">Active Members Only</option>
                                    <option value="grace_period">Members in Grace Period</option>
                                    <option value="defaulted">Defaulted Members</option>
                                    <option value="new_members">New Members (Last 30 Days)</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="scheduledAt" class="form-label">Schedule (Optional)</label>
                                <input type="datetime-local" class="form-control" id="scheduledAt" name="scheduled_at">
                                <small class="form-text text-muted">Leave empty to send immediately</small>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Templates</label>
                                <select class="form-control" id="templateSelect" onchange="loadTemplate()">
                                    <option value="">-- Select Template --</option>
                                    <?php if (!empty($templates)): ?>
                                        <?php foreach ($templates as $template): ?>
                                            <option value="<?php echo htmlspecialchars($template['template']); ?>">
                                                <?php echo htmlspecialchars($template['name']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="sendNow" name="send_now" value="1">
                                <label class="form-check-label" for="sendNow">
                                    Send Immediately
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div id="campaignResult"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Create Campaign</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function loadTemplate() {
    const templateText = document.getElementById('templateSelect').value;
    if (templateText) {
        document.getElementById('emailMessage').value = templateText;
    }
}

document.getElementById('createCampaignForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const resultDiv = document.getElementById('campaignResult');
    resultDiv.innerHTML = '<div class="alert alert-info">Creating campaign...</div>';
    
    const formData = new FormData(this);
    
    fetch('/admin/email-campaigns/create', {
        method: 'POST',
        body: new URLSearchParams(formData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            resultDiv.innerHTML = '<div class="alert alert-success">' + data.message + '</div>';
            setTimeout(() => window.location.reload(), 1500);
        } else {
            resultDiv.innerHTML = '<div class="alert alert-danger">' + (data.error || 'Failed to create campaign') + '</div>';
        }
    })
    .catch(error => {
        resultDiv.innerHTML = '<div class="alert alert-danger">Request failed. Please try again.</div>';
    });
});

function sendCampaign(campaignId) {
    if (!confirm('Send this campaign now?')) return;
    
    fetch('/admin/email-campaigns/send', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: new URLSearchParams({campaign_id: campaignId})
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message || 'Campaign sent successfully');
            window.location.reload();
        } else {
            alert('Error: ' + (data.error || 'Failed to send campaign'));
        }
    });
}

function cancelCampaign(campaignId) {
    if (!confirm('Cancel this campaign?')) return;
    
    fetch('/admin/email-campaigns/cancel', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: new URLSearchParams({campaign_id: campaignId})
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Campaign cancelled');
            window.location.reload();
        } else {
            alert('Error: ' + (data.error || 'Failed to cancel'));
        }
    });
}

function retryFailed(campaignId) {
    if (!confirm('Retry sending to all failed recipients?')) return;
    
    fetch('/admin/email-campaigns/retry-failed', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: new URLSearchParams({campaign_id: campaignId})
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message || 'Retry completed');
            window.location.reload();
        } else {
            alert('Error: ' + (data.error || 'Failed to retry'));
        }
    });
}
</script>

<?php include_once __DIR__ . '/../layouts/admin-footer.php'; ?>
