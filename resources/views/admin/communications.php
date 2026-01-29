<?php
$page = 'communications';
$pageTitle = 'Communications';
$pageSubtitle = 'Send notifications and manage member communications';
include VIEWS_PATH . '/layouts/dashboard-header.php';

// Calculate stats
$email_count = count(array_filter($communications ?? [], fn($c) => $c['type'] === 'email'));
$sms_count = count(array_filter($communications ?? [], fn($c) => $c['type'] === 'sms'));
$this_month = array_filter($communications ?? [], fn($c) => 
    date('Y-m', strtotime($c['sent_at'])) === date('Y-m')
);
?>

<!-- Stats Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon" style="background: var(--gradient-primary);">
            <i class="bi bi-envelope-fill"></i>
        </div>
        <div class="stat-details">
            <div class="stat-value"><?php echo number_format($email_count); ?></div>
            <div class="stat-label">Emails Sent</div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon" style="background: var(--gradient-success);">
            <i class="bi bi-chat-dots-fill"></i>
        </div>
        <div class="stat-details">
            <div class="stat-value"><?php echo number_format($sms_count); ?></div>
            <div class="stat-label">SMS Sent</div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon" style="background: var(--gradient-info);">
            <i class="bi bi-calendar-month-fill"></i>
        </div>
        <div class="stat-details">
            <div class="stat-value"><?php echo number_format(count($this_month)); ?></div>
            <div class="stat-label">This Month</div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon" style="background: var(--gradient-warning);">
            <i class="bi bi-megaphone-fill"></i>
        </div>
        <div class="stat-details">
            <div class="stat-value"><?php echo number_format(count($communications ?? [])); ?></div>
            <div class="stat-label">Total Sent</div>
        </div>
    </div>
</div>

<!-- Action Buttons -->
<div style="display: flex; justify-content: flex-end; gap: 1rem; margin: 2rem 0 1.5rem;">
    <button class="btn btn-success" onclick="openModal('sendSMSModal')">
        <i class="bi bi-chat-dots-fill"></i> Send SMS
    </button>
    <button class="btn btn-primary" onclick="openModal('sendEmailModal')">
        <i class="bi bi-envelope-fill"></i> Send Email
    </button>
</div>

<!-- Communications History -->
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h4 style="margin: 0;"><i class="bi bi-clock-history"></i> Communication History</h4>
            <div style="display: flex; gap: 0.5rem;">
                <select class="form-select" style="width: 150px;" onchange="filterCommunications(this.value)">
                    <option value="">All Types</option>
                    <option value="email">Email Only</option>
                    <option value="sms">SMS Only</option>
                </select>
            </div>
        </div>
    </div>
    <div class="card-body">
        <?php if (!empty($communications)): ?>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Recipient(s)</th>
                        <th>Subject/Message</th>
                        <th>Status</th>
                        <th>Sent Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($communications as $comm): ?>
                    <tr data-type="<?php echo $comm['type']; ?>">
                        <td>
                            <?php if ($comm['type'] === 'email'): ?>
                            <span class="badge badge-primary">
                                <i class="bi bi-envelope-fill"></i> Email
                            </span>
                            <?php else: ?>
                            <span class="badge badge-success">
                                <i class="bi bi-chat-dots-fill"></i> SMS
                            </span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div style="font-weight: 600; color: var(--secondary-violet);">
                                <?php echo htmlspecialchars($comm['recipient_name'] ?? 'Bulk Message'); ?>
                            </div>
                            <?php if (!empty($comm['recipient_count']) && $comm['recipient_count'] > 1): ?>
                            <div style="font-size: 0.75rem; color: var(--medium-grey);">
                                + <?php echo $comm['recipient_count'] - 1; ?> others
                            </div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div style="max-width: 300px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                <?php echo htmlspecialchars($comm['subject'] ?? $comm['message']); ?>
                            </div>
                        </td>
                        <td>
                            <?php
                            $statusClass = match($comm['status'] ?? 'sent') {
                                'sent' => 'badge-success',
                                'pending' => 'badge-warning',
                                'failed' => 'badge-danger',
                                default => 'badge-secondary'
                            };
                            ?>
                            <span class="badge <?php echo $statusClass; ?>">
                                <?php echo ucfirst($comm['status'] ?? 'Sent'); ?>
                            </span>
                        </td>
                        <td>
                            <div style="font-size: 0.875rem;">
                                <div><?php echo date('M d, Y', strtotime($comm['sent_at'])); ?></div>
                                <div style="color: var(--medium-grey); font-size: 0.75rem;">
                                    <?php echo date('h:i A', strtotime($comm['sent_at'])); ?>
                                </div>
                            </div>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-info" 
                                    onclick="viewCommunication(<?php echo $comm['id']; ?>)"
                                    title="View Details">
                                <i class="bi bi-eye-fill"></i>
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <div class="empty-state">
            <i class="bi bi-chat-square-text" style="font-size: 4rem; color: var(--light-grey); margin-bottom: 1rem;"></i>
            <h3 style="color: var(--medium-grey); margin-bottom: 0.5rem;">No Communications Yet</h3>
            <p style="color: var(--medium-grey);">Start sending emails or SMS to your members.</p>
            <div style="margin-top: 1rem; display: flex; gap: 1rem; justify-content: center;">
                <button class="btn btn-primary" onclick="openModal('sendEmailModal')">
                    <i class="bi bi-envelope-fill"></i> Send Email
                </button>
                <button class="btn btn-success" onclick="openModal('sendSMSModal')">
                    <i class="bi bi-chat-dots-fill"></i> Send SMS
                </button>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Send Email Modal -->
<div class="modal" id="sendEmailModal">
    <div class="modal-content" style="max-width: 700px;">
        <div class="modal-header">
            <h3 style="margin: 0;"><i class="bi bi-envelope-fill"></i> Send Email</h3>
            <button class="modal-close" onclick="closeModal('sendEmailModal')">&times;</button>
        </div>
        <form method="POST" action="/admin/communications/send-email">
            <div class="modal-body">
                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token ?? ''; ?>">
                
                <div class="form-group">
                    <label class="form-label" for="email_recipients">Recipients</label>
                    <select id="email_recipients" name="recipients" class="form-select" required>
                        <option value="all">All Active Members</option>
                        <option value="pending">Pending Members</option>
                        <option value="agents">All Agents</option>
                        <option value="custom">Custom List</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="email_subject">Subject</label>
                    <input type="text" 
                           id="email_subject" 
                           name="subject" 
                           class="form-control" 
                           required>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="email_message">Message</label>
                    <textarea id="email_message" 
                              name="message" 
                              class="form-control" 
                              rows="8" 
                              required></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="closeModal('sendEmailModal')">Cancel</button>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-send-fill"></i> Send Email
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Send SMS Modal -->
<div class="modal" id="sendSMSModal">
    <div class="modal-content" style="max-width: 600px;">
        <div class="modal-header">
            <h3 style="margin: 0;"><i class="bi bi-chat-dots-fill"></i> Send SMS</h3>
            <button class="modal-close" onclick="closeModal('sendSMSModal')">&times;</button>
        </div>
        <form method="POST" action="/admin/communications/send-sms">
            <div class="modal-body">
                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token ?? ''; ?>">
                
                <div class="form-group">
                    <label class="form-label" for="sms_recipients">Recipients</label>
                    <select id="sms_recipients" name="recipients" class="form-select" required>
                        <option value="all">All Active Members</option>
                        <option value="pending">Pending Members</option>
                        <option value="agents">All Agents</option>
                        <option value="custom">Custom Numbers</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="sms_message">Message</label>
                    <textarea id="sms_message" 
                              name="message" 
                              class="form-control" 
                              rows="4" 
                              maxlength="160" 
                              required 
                              oninput="updateCharCount(this)"></textarea>
                    <small style="color: var(--medium-grey); font-size: 0.75rem;">
                        <span id="charCount">0</span>/160 characters
                    </small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="closeModal('sendSMSModal')">Cancel</button>
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-send-fill"></i> Send SMS
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function filterCommunications(type) {
    const rows = document.querySelectorAll('.data-table tbody tr');
    rows.forEach(row => {
        if (type === '' || row.dataset.type === type) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

function updateCharCount(textarea) {
    document.getElementById('charCount').textContent = textarea.value.length;
}

function viewCommunication(id) {
    // Implement view details functionality
    console.log('View communication:', id);
}
</script>

<?php include VIEWS_PATH . '/layouts/dashboard-footer.php'; ?>
