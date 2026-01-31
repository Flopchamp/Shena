<?php include_once 'admin-header.php'; ?>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-comments mr-2"></i>SMS Campaign Manager
        </h1>
        <div class="btn-group">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createCampaignModal">
                <i class="fas fa-plus mr-2"></i>Create Campaign
            </button>
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#quickSMSModal">
                <i class="fas fa-sms mr-2"></i>Quick SMS
            </button>
            <a href="/admin/communications/templates" class="btn btn-info">
                <i class="fas fa-file-alt mr-2"></i>Templates
            </a>
            <a href="/admin/notification-settings" class="btn btn-secondary">
                <i class="fas fa-cog mr-2"></i>Notification Settings
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
                            <i class="fas fa-broadcast-tower fa-2x text-gray-300"></i>
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
                                Messages Sent Today
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
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Queue Pending
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo number_format($stats['queue_pending'] ?? 0); ?>
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
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                SMS Credits
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo number_format($stats['sms_credits'] ?? 0); ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-coins fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item">
            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#campaigns" type="button" role="tab">
                <i class="fas fa-megaphone mr-1"></i>Campaigns
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#queue" type="button" role="tab">
                <i class="fas fa-list mr-1"></i>Queue Monitor
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#scheduled" type="button" role="tab">
                <i class="fas fa-calendar-alt mr-1"></i>Scheduled
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#history" type="button" role="tab">
                <i class="fas fa-history mr-1"></i>History
            </button>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content">
        <!-- Campaigns Tab -->
        <div class="tab-pane fade show active" id="campaigns">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">SMS Campaigns</h6>
                </div>
                <div class="card-body">
                    <?php if (!empty($campaigns)): ?>
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%">
                                <thead>
                                    <tr>
                                        <th>Campaign</th>
                                        <th>Target</th>
                                        <th>Status</th>
                                        <th>Progress</th>
                                        <th>Scheduled</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($campaigns as $campaign): ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo htmlspecialchars($campaign['title']); ?></strong><br>
                                            <small class="text-muted"><?php echo htmlspecialchars(substr($campaign['message'], 0, 50)) . '...'; ?></small>
                                        </td>
                                        <td>
                                            <span class="badge badge-secondary"><?php echo ucwords(str_replace('_', ' ', $campaign['target_audience'])); ?></span><br>
                                            <small><?php echo number_format($campaign['total_recipients']); ?> recipients</small>
                                        </td>
                                        <td>
                                            <span class="badge badge-<?php 
                                                echo match($campaign['status']) {
                                                    'draft' => 'secondary',
                                                    'scheduled' => 'info',
                                                    'sending' => 'warning',
                                                    'completed' => 'success',
                                                    'cancelled' => 'danger',
                                                    default => 'secondary'
                                                };
                                            ?>"><?php echo ucfirst($campaign['status']); ?></span>
                                        </td>
                                        <td>
                                            <?php if ($campaign['total_recipients'] > 0): ?>
                                                <?php $percent = ($campaign['sent_count'] / $campaign['total_recipients']) * 100; ?>
                                                <div class="progress" style="height: 20px;">
                                                    <div class="progress-bar bg-success" style="width: <?php echo $percent; ?>%">
                                                        <?php echo number_format($percent, 1); ?>%
                                                    </div>
                                                </div>
                                                <small class="text-muted">
                                                    <?php echo $campaign['sent_count']; ?>/<?php echo $campaign['total_recipients']; ?> sent
                                                    <?php if ($campaign['failed_count'] > 0): ?>
                                                        | <span class="text-danger"><?php echo $campaign['failed_count']; ?> failed</span>
                                                    <?php endif; ?>
                                                </small>
                                            <?php else: ?>
                                                <small class="text-muted">Not started</small>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($campaign['scheduled_at']): ?>
                                                <?php echo date('M j, Y H:i', strtotime($campaign['scheduled_at'])); ?>
                                            <?php else: ?>
                                                <span class="text-muted">Immediate</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <button class="btn btn-info" onclick="viewCampaign(<?php echo $campaign['id']; ?>)">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <?php if ($campaign['status'] === 'draft' || $campaign['status'] === 'scheduled'): ?>
                                                    <button class="btn btn-success" onclick="sendCampaign(<?php echo $campaign['id']; ?>)">
                                                        <i class="fas fa-paper-plane"></i>
                                                    </button>
                                                    <button class="btn btn-warning" onclick="editCampaign(<?php echo $campaign['id']; ?>)">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button class="btn btn-danger" onclick="cancelCampaign(<?php echo $campaign['id']; ?>)">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                <?php endif; ?>
                                                <?php if ($campaign['status'] === 'sending'): ?>
                                                    <button class="btn btn-warning" onclick="pauseCampaign(<?php echo $campaign['id']; ?>)">
                                                        <i class="fas fa-pause"></i>
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
                            <i class="fas fa-megaphone fa-3x text-gray-300 mb-3"></i>
                            <h5 class="text-gray-600">No campaigns yet</h5>
                            <p class="text-gray-500">Create your first SMS campaign to get started.</p>
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createCampaignModal">
                                <i class="fas fa-plus mr-2"></i>Create Campaign
                            </button>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Queue Monitor Tab -->
        <div class="tab-pane fade" id="queue">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">SMS Queue Status</h6>
                    <button class="btn btn-sm btn-success" onclick="processQueue()">
                        <i class="fas fa-play mr-1"></i>Process Queue
                    </button>
                </div>
                <div class="card-body">
                    <?php if (!empty($queue_items)): ?>
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%">
                                <thead>
                                    <tr>
                                        <th>Phone</th>
                                        <th>Message Preview</th>
                                        <th>Priority</th>
                                        <th>Status</th>
                                        <th>Scheduled</th>
                                        <th>Retry</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($queue_items as $item): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($item['phone_number']); ?></td>
                                        <td><small><?php echo htmlspecialchars(substr($item['message'], 0, 40)) . '...'; ?></small></td>
                                        <td>
                                            <span class="badge badge-<?php 
                                                echo match($item['priority']) {
                                                    'urgent' => 'danger',
                                                    'high' => 'warning',
                                                    'normal' => 'primary',
                                                    'low' => 'secondary',
                                                    default => 'secondary'
                                                };
                                            ?>"><?php echo ucfirst($item['priority']); ?></span>
                                        </td>
                                        <td>
                                            <span class="badge badge-<?php 
                                                echo match($item['status']) {
                                                    'pending' => 'warning',
                                                    'processing' => 'info',
                                                    'sent' => 'success',
                                                    'failed' => 'danger',
                                                    default => 'secondary'
                                                };
                                            ?>"><?php echo ucfirst($item['status']); ?></span>
                                        </td>
                                        <td>
                                            <?php if ($item['scheduled_at']): ?>
                                                <?php echo date('M j, H:i', strtotime($item['scheduled_at'])); ?>
                                            <?php else: ?>
                                                <span class="text-muted">Now</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo $item['retry_count']; ?>/<?php echo $item['max_retries']; ?></td>
                                        <td>
                                            <?php if ($item['status'] === 'pending'): ?>
                                                <button class="btn btn-sm btn-success" onclick="sendQueueItem(<?php echo $item['id']; ?>)">
                                                    <i class="fas fa-paper-plane"></i>
                                                </button>
                                            <?php endif; ?>
                                            <?php if ($item['status'] === 'failed'): ?>
                                                <button class="btn btn-sm btn-warning" onclick="retryQueueItem(<?php echo $item['id']; ?>)">
                                                    <i class="fas fa-redo"></i>
                                                </button>
                                            <?php endif; ?>
                                            <button class="btn btn-sm btn-danger" onclick="deleteQueueItem(<?php echo $item['id']; ?>)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                            <h5 class="text-gray-600">Queue is empty</h5>
                            <p class="text-gray-500">All messages have been processed.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Scheduled Tab -->
        <div class="tab-pane fade" id="scheduled">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Scheduled Messages</h6>
                </div>
                <div class="card-body">
                    <?php 
                    $scheduled_campaigns = array_filter($campaigns ?? [], fn($c) => $c['status'] === 'scheduled');
                    if (!empty($scheduled_campaigns)): 
                    ?>
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%">
                                <thead>
                                    <tr>
                                        <th>Campaign</th>
                                        <th>Recipients</th>
                                        <th>Scheduled For</th>
                                        <th>Time Until Send</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($scheduled_campaigns as $campaign): ?>
                                    <?php 
                                    $scheduled_time = strtotime($campaign['scheduled_at']);
                                    $time_diff = $scheduled_time - time();
                                    $hours_until = floor($time_diff / 3600);
                                    ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo htmlspecialchars($campaign['title']); ?></strong><br>
                                            <small class="text-muted"><?php echo htmlspecialchars(substr($campaign['message'], 0, 50)) . '...'; ?></small>
                                        </td>
                                        <td><?php echo number_format($campaign['total_recipients']); ?> recipients</td>
                                        <td><?php echo date('M j, Y H:i', $scheduled_time); ?></td>
                                        <td>
                                            <?php if ($time_diff > 0): ?>
                                                <span class="badge badge-info">
                                                    <?php if ($hours_until > 24): ?>
                                                        <?php echo floor($hours_until / 24); ?> days
                                                    <?php else: ?>
                                                        <?php echo $hours_until; ?> hours
                                                    <?php endif; ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="badge badge-warning">Overdue</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-success" onclick="sendNow(<?php echo $campaign['id']; ?>)">
                                                <i class="fas fa-paper-plane mr-1"></i>Send Now
                                            </button>
                                            <button class="btn btn-sm btn-warning" onclick="reschedule(<?php echo $campaign['id']; ?>)">
                                                <i class="fas fa-clock mr-1"></i>Reschedule
                                            </button>
                                            <button class="btn btn-sm btn-danger" onclick="cancelCampaign(<?php echo $campaign['id']; ?>)">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-alt fa-3x text-gray-300 mb-3"></i>
                            <h5 class="text-gray-600">No scheduled messages</h5>
                            <p class="text-gray-500">Schedule a campaign to send messages at a specific time.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- History Tab -->
        <div class="tab-pane fade" id="history">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Communication History</h6>
                </div>
                <div class="card-body">
                    <?php 
                    $completed_campaigns = array_filter($campaigns ?? [], fn($c) => $c['status'] === 'completed');
                    if (!empty($completed_campaigns)): 
                    ?>
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%">
                                <thead>
                                    <tr>
                                        <th>Campaign</th>
                                        <th>Recipients</th>
                                        <th>Sent/Failed</th>
                                        <th>Success Rate</th>
                                        <th>Completed</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($completed_campaigns as $campaign): ?>
                                    <?php $success_rate = $campaign['total_recipients'] > 0 ? ($campaign['sent_count'] / $campaign['total_recipients']) * 100 : 0; ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo htmlspecialchars($campaign['title']); ?></strong><br>
                                            <small class="text-muted"><?php echo htmlspecialchars(substr($campaign['message'], 0, 50)) . '...'; ?></small>
                                        </td>
                                        <td><?php echo number_format($campaign['total_recipients']); ?></td>
                                        <td>
                                            <span class="text-success"><?php echo $campaign['sent_count']; ?> sent</span> /
                                            <span class="text-danger"><?php echo $campaign['failed_count']; ?> failed</span>
                                        </td>
                                        <td>
                                            <div class="progress" style="height: 20px;">
                                                <div class="progress-bar <?php echo $success_rate >= 90 ? 'bg-success' : ($success_rate >= 70 ? 'bg-warning' : 'bg-danger'); ?>" 
                                                     style="width: <?php echo $success_rate; ?>%">
                                                    <?php echo number_format($success_rate, 1); ?>%
                                                </div>
                                            </div>
                                        </td>
                                        <td><?php echo date('M j, Y H:i', strtotime($campaign['completed_at'])); ?></td>
                                        <td>
                                            <button class="btn btn-sm btn-info" onclick="viewCampaign(<?php echo $campaign['id']; ?>)">
                                                <i class="fas fa-eye"></i> View Report
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-history fa-3x text-gray-300 mb-3"></i>
                            <h5 class="text-gray-600">No completed campaigns</h5>
                            <p class="text-gray-500">Completed campaigns will appear here.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Campaign Modal -->
<div class="modal fade" id="createCampaignModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create SMS Campaign</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="/admin/communications/create-campaign" id="campaignForm">
                <?php if (isset($_SESSION['csrf_token'])): ?>
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <?php endif; ?>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Campaign Title *</label>
                        <input type="text" name="title" class="form-control" required placeholder="e.g., Monthly Payment Reminder">
                    </div>

                    <div class="form-group">
                        <label>Select Template (Optional)</label>
                        <select name="template_id" class="form-control" id="templateSelect">
                            <option value="">-- Start from scratch --</option>
                            <?php if (!empty($templates)): ?>
                                <?php foreach ($templates as $template): ?>
                                    <option value="<?php echo $template['id']; ?>" 
                                            data-message="<?php echo htmlspecialchars($template['template']); ?>">
                                        <?php echo htmlspecialchars($template['name']); ?> 
                                        (<?php echo ucfirst($template['category']); ?>)
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Target Audience *</label>
                        <select name="target_audience" class="form-control" required id="targetAudience">
                            <option value="">Select Target Audience</option>
                            <option value="all_members">All Members</option>
                            <option value="active">Active Members Only</option>
                            <option value="grace_period">Members in Grace Period</option>
                            <option value="defaulted">Defaulted Members</option>
                            <option value="custom">Custom Filter...</option>
                        </select>
                    </div>

                    <div id="customFilters" style="display: none;">
                        <div class="card bg-light p-3 mb-3">
                            <h6>Custom Filters</h6>
                            <div class="form-row">
                                <div class="col-md-6">
                                    <label>Package Type</label>
                                    <select name="filter_package" class="form-control">
                                        <option value="">All Packages</option>
                                        <option value="individual">Individual</option>
                                        <option value="couple">Couple</option>
                                        <option value="family">Family</option>
                                        <option value="executive">Executive</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label>Status</label>
                                    <select name="filter_status" class="form-control">
                                        <option value="">All Statuses</option>
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                        <option value="grace_period">Grace Period</option>
                                        <option value="defaulted">Defaulted</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-row mt-2">
                                <div class="col-md-6">
                                    <label>Joined After</label>
                                    <input type="date" name="filter_joined_after" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label>Joined Before</label>
                                    <input type="date" name="filter_joined_before" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Message *</label>
                        <textarea name="message" class="form-control" rows="5" required 
                                  placeholder="Enter your SMS message here..." maxlength="160" id="messageText"></textarea>
                        <small class="form-text text-muted">
                            Maximum 160 characters. Use {name}, {member_number}, {amount} for personalization.
                        </small>
                        <div class="text-right">
                            <small id="charCounter" class="text-muted">0/160</small>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Send Time</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="send_time" id="sendNow" value="now" checked>
                            <label class="form-check-label" for="sendNow">
                                Send Immediately
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="send_time" id="sendScheduled" value="scheduled">
                            <label class="form-check-label" for="sendScheduled">
                                Schedule for later
                            </label>
                        </div>
                    </div>

                    <div class="form-group" id="scheduleFields" style="display: none;">
                        <label>Schedule Date & Time *</label>
                        <input type="datetime-local" name="scheduled_at" class="form-control">
                        <small class="form-text text-muted">Campaign will be sent automatically at the specified time.</small>
                    </div>

                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="queueMessages" name="use_queue" value="1" checked>
                            <label class="custom-control-label" for="queueMessages">
                                Use queue for sending (Recommended for large campaigns)
                            </label>
                        </div>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle mr-2"></i>
                        <strong>Note:</strong> SMS charges apply. Estimated cost: <span id="estimatedCost">Calculate...</span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="action" value="draft" class="btn btn-outline-primary">
                        <i class="fas fa-save mr-2"></i>Save as Draft
                    </button>
                    <button type="submit" name="action" value="send" class="btn btn-success">
                        <i class="fas fa-paper-plane mr-2"></i>Create & Send
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Quick SMS Modal -->
<div class="modal fade" id="quickSMSModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Quick SMS</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="/admin/communications/quick-sms">
                <?php if (isset($_SESSION['csrf_token'])): ?>
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <?php endif; ?>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Phone Number *</label>
                        <input type="text" name="phone" class="form-control" required 
                               placeholder="0712345678" pattern="[0-9]{10}">
                        <small class="form-text text-muted">Enter 10-digit phone number</small>
                    </div>
                    
                    <div class="form-group">
                        <label>Message *</label>
                        <textarea name="message" class="form-control" rows="4" required 
                                  placeholder="Your message here..." maxlength="160" id="quickSmsMessage"></textarea>
                        <div class="text-right">
                            <small id="quickSmsCounter" class="text-muted">0/160</small>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Priority</label>
                        <select name="priority" class="form-control">
                            <option value="normal">Normal</option>
                            <option value="high">High</option>
                            <option value="urgent">Urgent</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-paper-plane mr-2"></i>Send SMS
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Character counter for campaign message
    $('#messageText').on('input', function() {
        const length = $(this).val().length;
        $('#charCounter').text(length + '/160');
        if (length > 160) {
            $('#charCounter').addClass('text-danger');
        } else {
            $('#charCounter').removeClass('text-danger');
        }
    });

    // Character counter for quick SMS
    $('#quickSmsMessage').on('input', function() {
        const length = $(this).val().length;
        $('#quickSmsCounter').text(length + '/160');
        if (length > 160) {
            $('#quickSmsCounter').addClass('text-danger');
        } else {
            $('#quickSmsCounter').removeClass('text-danger');
        }
    });

    // Template selection
    $('#templateSelect').on('change', function() {
        const selectedOption = $(this).find('option:selected');
        const message = selectedOption.data('message');
        if (message) {
            $('#messageText').val(message).trigger('input');
        }
    });

    // Show/hide custom filters
    $('#targetAudience').on('change', function() {
        if ($(this).val() === 'custom') {
            $('#customFilters').slideDown();
        } else {
            $('#customFilters').slideUp();
        }
    });

    // Show/hide schedule fields
    $('input[name="send_time"]').on('change', function() {
        if ($(this).val() === 'scheduled') {
            $('#scheduleFields').slideDown();
        } else {
            $('#scheduleFields').slideUp();
        }
    });
});

function viewCampaign(id) {
    window.location.href = '/admin/communications/campaign/' + id;
}

function sendCampaign(id) {
    if (confirm('Start sending this campaign now?')) {
        fetch('/admin/communications/send-campaign', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ campaign_id: id })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Campaign sending started!');
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Network error: ' + error.message);
        });
    }
}

function cancelCampaign(id) {
    if (confirm('Cancel this campaign? This action cannot be undone.')) {
        fetch('/admin/communications/cancel-campaign', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ campaign_id: id })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Campaign cancelled successfully');
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Network error: ' + error.message);
        });
    }
}

function processQueue() {
    if (confirm('Process all pending messages in the queue?')) {
        fetch('/admin/communications/process-queue', {
            method: 'POST'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(`Processed: ${data.sent_count} sent, ${data.failed_count} failed`);
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Network error: ' + error.message);
        });
    }
}

function sendNow(id) {
    if (confirm('Send this scheduled campaign immediately?')) {
        fetch('/admin/communications/send-now', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ campaign_id: id })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Campaign is being sent now!');
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Network error: ' + error.message);
        });
    }
}

function editCampaign(id) {
    alert('Edit feature coming soon. Please create a new campaign instead.');
}

function pauseCampaign(id) {
    if (confirm('Pause this campaign?')) {
        fetch('/admin/communications/pause-campaign', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ campaign_id: id })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Campaign paused successfully');
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Network error: ' + error.message);
        });
    }
}

function reschedule(id) {
    const newDateTime = prompt('Enter new date and time (YYYY-MM-DD HH:MM:SS):');
    if (newDateTime) {
        fetch('/admin/communications/reschedule', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ 
                campaign_id: id,
                scheduled_at: newDateTime
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Campaign rescheduled successfully');
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Network error: ' + error.message);
        });
    }
}

function sendQueueItem(id) {
    if (confirm('Send this message now?')) {
        fetch('/admin/communications/send-queue-item', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ item_id: id })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Message sent successfully');
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Network error: ' + error.message);
        });
    }
}

function retryQueueItem(id) {
    if (confirm('Retry sending this message?')) {
        fetch('/admin/communications/retry-queue-item', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ item_id: id })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Message queued for retry');
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Network error: ' + error.message);
        });
    }
}

function deleteQueueItem(id) {
    if (confirm('Delete this queue item?')) {
        fetch('/admin/communications/delete-queue-item', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ item_id: id })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Queue item deleted');
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Network error: ' + error.message);
        });
    }
}
</script>

<?php include_once 'admin-footer.php'; ?>
