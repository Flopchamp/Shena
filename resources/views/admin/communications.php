<?php include_once __DIR__ . '/../layouts/admin-header.php'; ?>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0"><i class="fas fa-bell me-2"></i>System Notifications</h1>
</div>

<!-- Communication Tabs -->
<ul class="nav nav-tabs mb-4" id="communicationTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="notifications-tab" data-bs-toggle="tab" data-bs-target="#notifications" type="button" role="tab">
            <i class="fas fa-bell"></i> Active Notifications
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="history-tab" data-bs-toggle="tab" data-bs-target="#history" type="button" role="tab">
            <i class="fas fa-history"></i> Notification History
        </button>
    </li>
</ul>

<!-- Tab Content -->
<div class="tab-content" id="communicationTabContent">
    
    <!-- System Notifications Tab -->
    <div class="tab-pane fade show active" id="notifications" role="tabpanel">
        <div class="modern-card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <div>
                    <h2 style="margin: 0; font-family: 'Playfair Display', serif; color: #1f2937; font-size: 1.5rem;">
                        System Notifications
                    </h2>
                    <p style="color: #6b7280; margin: 0.5rem 0 0 0;">Automated notifications sent by the system</p>
                </div>
            </div>
            
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="stat-card primary">
                        <div class="icon-wrapper">
                            <i class="fas fa-bell"></i>
                        </div>
                        <p class="stat-value">24</p>
                        <p class="stat-label">Payment Reminders</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card success">
                        <div class="icon-wrapper">
                            <i class="fas fa-user-check"></i>
                        </div>
                        <p class="stat-value">12</p>
                        <p class="stat-label">Welcome Messages</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card info">
                        <div class="icon-wrapper">
                            <i class="fas fa-file-invoice"></i>
                        </div>
                        <p class="stat-value">8</p>
                        <p class="stat-label">Claim Updates</p>
                    </div>
                </div>
            </div>
            
            <div class="table-responsive">
                <table class="modern-table">
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>Recipient</th>
                            <th>Subject</th>
                            <th>Status</th>
                            <th>Sent At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><span class="type-badge email"><i class="fas fa-bell"></i> REMINDER</span></td>
                            <td><strong>John Doe</strong><br><small style="color: #6b7280;">+254712345678</small></td>
                            <td>Monthly Contribution Reminder</td>
                            <td><span class="status-badge sent">SENT</span></td>
                            <td>Feb 04, 2026 10:30</td>
                            <td>
                                <button class="action-btn" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Communication History Tab -->
    <div class="tab-pane fade" id="history" role="tabpanel">
        <div class="modern-card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <h2 style="margin: 0; font-family: 'Playfair Display', serif; color: #1f2937; font-size: 1.5rem;">
                    Notification History
                </h2>
                <div class="filter-group">
                    <select class="select-control" onchange="filterByStatus(this.value)">
                        <option value="all">All Status</option>
                        <option value="sent">Delivered</option>
                        <option value="failed">Failed</option>
                        <option value="pending">Pending</option>
                    </select>
                </div>
            </div>

            <?php if (empty($notifications)): ?>
                <div class="empty-state">
                    <i class="fas fa-bell"></i>
                    <h3>No Notification History</h3>
                    <p>System notifications will appear here once they are sent</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="modern-table">
                        <thead>
                            <tr>
                                <th>Type</th>
                                <th>Recipients</th>
                                <th>Subject/Message</th>
                                <th>Status</th>
                                <th>Sent Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($notifications as $notif): ?>
                            <tr data-status="<?php echo $notif['status'] ?? 'sent'; ?>">
                                <td>
                                    <span class="type-badge notification">
                                        <i class="fas fa-bell"></i>
                                        <?php echo strtoupper($notif['notification_type'] ?? 'SYSTEM'); ?>
                                    </span>
                                </td>
                                <td>
                                    <div>
                                        <strong>
                                            <?php 
                                                echo htmlspecialchars($notif['recipient_name'] ?? 'Member');
                                            ?>
                                        </strong>
                                        <br>
                                        <small style="color: #6b7280;"><?php echo htmlspecialchars($notif['recipient_email'] ?? ''); ?></small>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <strong><?php echo htmlspecialchars($notif['subject'] ?? 'No Subject'); ?></strong><br>
                                        <small style="color: #6b7280;">
                                            <?php echo htmlspecialchars(substr($notif['message'] ?? '', 0, 50) . '...'); ?>
                                        </small>
                                    </div>
                                </td>
                                <td>
                                    <span class="status-badge <?php echo $notif['status'] ?? 'sent'; ?>">
                                        <?php echo ucfirst($notif['status'] ?? 'Sent'); ?>
                                    </span>
                                </td>
                                <td><?php echo date('M j, Y H:i', strtotime($notif['sent_at'] ?? 'now')); ?></td>
                                <td>
                                    <button class="action-btn" onclick="viewNotification(<?php echo $notif['id']; ?>)" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
</div>

<style>
    .page-header {
        background: linear-gradient(135deg, #7F3D9E 0%, #7F3D9E 100%);
        border-radius: 12px;
        padding: 2rem;
        margin-bottom: 2rem;
        color: white;
        box-shadow: 0 4px 6px rgba(139, 92, 246, 0.1);
    }

    .page-header h1 {
        font-family: 'Playfair Display', serif;
        font-size: 2rem;
        font-weight: 700;
        margin: 0 0 0.5rem 0;
    }

    .page-header p {
        margin: 0;
        opacity: 0.9;
        font-size: 0.95rem;
    }

    .modern-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        margin-bottom: 1.5rem;
        border: 1px solid #f3f4f6;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        border: 1px solid #f3f4f6;
        height: 100%;
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        box-shadow: 0 4px 8px rgba(127, 61, 158, 0.1);
        transform: translateY(-2px);
    }

    .stat-card .icon-wrapper {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
    }

    .stat-card .icon-wrapper i {
        font-size: 24px;
    }

    .stat-card.primary .icon-wrapper {
        background: rgba(127, 61, 158, 0.1);
        color: #7F3D9E;
    }

    .stat-card.success .icon-wrapper {
        background: rgba(16, 185, 129, 0.1);
        color: #10B981;
    }

    .stat-card.info .icon-wrapper {
        background: rgba(59, 130, 246, 0.1);
        color: #3B82F6;
    }

    .stat-card.warning .icon-wrapper {
        background: rgba(245, 158, 11, 0.1);
        color: #F59E0B;
    }

    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: #1f2937;
        margin: 0;
    }

    .stat-label {
        color: #6b7280;
        font-size: 0.875rem;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .modern-tabs {
        display: flex;
        gap: 1rem;
        margin-bottom: 2rem;
        border-bottom: 2px solid #f3f4f6;
        padding: 0;
        list-style: none;
    }

    .modern-tabs .tab-item {
        padding: 1rem 1.5rem;
        cursor: pointer;
        color: #6b7280;
        font-weight: 600;
        border-bottom: 3px solid transparent;
        transition: all 0.3s ease;
        margin-bottom: -2px;
    }

    .modern-tabs .tab-item:hover {
        color: #7F3D9E;
    }

    .modern-tabs .tab-item.active {
        color: #7F3D9E;
        border-bottom-color: #7F3D9E;
    }

    .tab-content-panel {
        display: none;
    }

    .tab-content-panel.active {
        display: block;
    }

    .modern-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .modern-table thead {
        background: #f9fafb;
    }

    .modern-table th {
        padding: 1rem;
        text-align: left;
        font-weight: 600;
        color: #374151;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #e5e7eb;
    }

    .modern-table td {
        padding: 1rem;
        border-bottom: 1px solid #f3f4f6;
        color: #1f2937;
    }

    .modern-table tbody tr:hover {
        background: #f9fafb;
    }

    .status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: inline-block;
    }

    .status-badge.sent {
        background: rgba(16, 185, 129, 0.1);
        color: #10B981;
    }

    .status-badge.failed {
        background: rgba(239, 68, 68, 0.1);
        color: #EF4444;
    }

    .status-badge.pending {
        background: rgba(245, 158, 11, 0.1);
        color: #F59E0B;
    }

    .type-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        display: inline-block;
    }

    .type-badge.email {
        background: rgba(127, 61, 158, 0.1);
        color: #7F3D9E;
    }

    .type-badge.sms {
        background: rgba(16, 185, 129, 0.1);
        color: #10B981;
    }

    .action-btn {
        background: none;
        border: none;
        width: 32px;
        height: 32px;
        border-radius: 8px;
        cursor: pointer;
        color: #6b7280;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .action-btn:hover {
        background: rgba(127, 61, 158, 0.1);
        color: #7F3D9E;
    }

    .modern-btn {
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 0.875rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .modern-btn.primary {
        background: #7F3D9E;
        color: white;
    }

    .modern-btn.primary:hover {
        background: #7F3D9E;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(127, 61, 158, 0.3);
    }

    .modern-btn.success {
        background: #10B981;
        color: white;
    }

    .modern-btn.success:hover {
        background: #059669;
    }

    .modern-btn.secondary {
        background: #f3f4f6;
        color: #374151;
    }

    .modern-btn.secondary:hover {
        background: #e5e7eb;
    }

    .modern-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        z-index: 9999;
        align-items: center;
        justify-content: center;
        padding: 2rem;
    }

    .modern-modal.active {
        display: flex;
    }

    .modal-content-modern {
        background: white;
        border-radius: 12px;
        max-width: 600px;
        width: 100%;
        max-height: 90vh;
        overflow-y: auto;
        animation: modalSlideIn 0.3s ease;
    }

    @keyframes modalSlideIn {
        from {
            opacity: 0;
            transform: translateY(-50px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .modal-header-modern {
        background: linear-gradient(135deg, #7F3D9E 0%, #7F3D9E 100%);
        color: white;
        padding: 1.5rem;
        border-radius: 12px 12px 0 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-header-modern h3 {
        margin: 0;
        font-family: 'Playfair Display', serif;
        font-size: 1.5rem;
    }

    .modal-close {
        background: none;
        border: none;
        color: white;
        font-size: 1.5rem;
        cursor: pointer;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        transition: background 0.2s;
    }

    .modal-close:hover {
        background: rgba(255, 255, 255, 0.1);
    }

    .modal-body-modern {
        padding: 2rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 600;
        color: #374151;
    }

    .form-control {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 0.875rem;
        transition: all 0.2s;
    }

    .form-control:focus {
        outline: none;
        border-color: #8B5CF6;
        box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1);
    }

    textarea.form-control {
        resize: vertical;
        min-height: 100px;
    }

    .empty-state {
        text-align: center;
        padding: 3rem 2rem;
    }

    .empty-state i {
        font-size: 4rem;
        color: #d1d5db;
        margin-bottom: 1rem;
    }

    .empty-state h3 {
        color: #6b7280;
        margin-bottom: 0.5rem;
    }

    .empty-state p {
        color: #9ca3af;
    }

    .filter-group {
        display: flex;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .select-control {
        padding: 0.5rem 1rem;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        background: white;
        cursor: pointer;
        font-size: 0.875rem;
        transition: all 0.2s;
    }

    .select-control:hover {
        border-color: #7F3D9E;
    }

    .select-control:focus {
        outline: none;
        border-color: #7F3D9E;
        box-shadow: 0 0 0 3px rgba(127, 61, 158, 0.1);
    }
</style>

<!-- Quick Email Modal -->
<div class="modern-modal" id="emailModal">
    <div class="modal-content-modern">
        <div class="modal-header-modern">
            <h3><i class="fas fa-envelope"></i> Send Quick Email</h3>
            <button class="modal-close" onclick="closeModal('emailModal')">&times;</button>
        </div>
        <div class="modal-body-modern">
            <form action="/admin/communications/send-email" method="POST" id="emailForm">
                <div class="form-group">
                    <label for="email-recipient-type">Recipients</label>
                    <select class="form-control" id="email-recipient-type" name="recipient_type" required>
                        <option value="">Select recipient type...</option>
                        <option value="all">All Members</option>
                        <option value="group">Member Group</option>
                        <option value="individual">Individual Member</option>
                    </select>
                </div>

                <div class="form-group" id="email-group-field" style="display: none;">
                    <label for="email-recipient-group">Member Group</label>
                    <select class="form-control" id="email-recipient-group" name="recipient_group">
                        <option value="active">Active Members</option>
                        <option value="inactive">Inactive Members</option>
                        <option value="pending">Pending Members</option>
                    </select>
                </div>

                <div class="form-group" id="email-individual-field" style="display: none;">
                    <label for="email-recipient-id">Select Member</label>
                    <select class="form-control" id="email-recipient-id" name="recipient_id">
                        <!-- Populated via JavaScript -->
                    </select>
                </div>

                <div class="form-group">
                    <label for="email-subject">Subject</label>
                    <input type="text" class="form-control" id="email-subject" name="subject" required placeholder="Enter email subject">
                </div>

                <div class="form-group">
                    <label for="email-message">Message</label>
                    <textarea class="form-control" id="email-message" name="message" required placeholder="Enter your message here..." rows="6"></textarea>
                </div>

                <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                    <button type="button" class="modern-btn secondary" onclick="closeModal('emailModal')">Cancel</button>
                    <button type="submit" class="modern-btn primary">
                        <i class="fas fa-paper-plane"></i> Send Email
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Quick SMS Modal -->
<div class="modern-modal" id="smsModal">
    <div class="modal-content-modern">
        <div class="modal-header-modern">
            <h3><i class="fas fa-sms"></i> Send Quick SMS</h3>
            <button class="modal-close" onclick="closeModal('smsModal')">&times;</button>
        </div>
        <div class="modal-body-modern">
            <form action="/admin/communications/send-sms" method="POST" id="smsForm">
                <div class="form-group">
                    <label for="sms-recipient-type">Recipients</label>
                    <select class="form-control" id="sms-recipient-type" name="recipient_type" required>
                        <option value="">Select recipient type...</option>
                        <option value="all">All Members</option>
                        <option value="group">Member Group</option>
                        <option value="individual">Individual Member</option>
                    </select>
                </div>

                <div class="form-group" id="sms-group-field" style="display: none;">
                    <label for="sms-recipient-group">Member Group</label>
                    <select class="form-control" id="sms-recipient-group" name="recipient_group">
                        <option value="active">Active Members</option>
                        <option value="inactive">Inactive Members</option>
                        <option value="pending">Pending Members</option>
                    </select>
                </div>

                <div class="form-group" id="sms-individual-field" style="display: none;">
                    <label for="sms-recipient-id">Select Member</label>
                    <select class="form-control" id="sms-recipient-id" name="recipient_id">
                        <!-- Populated via JavaScript -->
                    </select>
                </div>

                <div class="form-group">
                    <label for="sms-message">Message</label>
                    <textarea class="form-control" id="sms-message" name="message" required placeholder="Enter your SMS message..." rows="4" maxlength="160"></textarea>
                    <small style="color: #6b7280; display: block; margin-top: 0.5rem;">
                        <span id="sms-counter">0</span>/160 characters
                    </small>
                </div>

                <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                    <button type="button" class="modern-btn secondary" onclick="closeModal('smsModal')">Cancel</button>
                    <button type="submit" class="modern-btn success">
                        <i class="fas fa-paper-plane"></i> Send SMS
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Modal functions
function openModal(modalId) {
    document.getElementById(modalId).classList.add('active');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.remove('active');
}

// Close modal on outside click
document.querySelectorAll('.modern-modal').forEach(modal => {
    modal.addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal(this.id);
        }
    });
});

// Close modal on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        document.querySelectorAll('.modern-modal.active').forEach(modal => {
            closeModal(modal.id);
        });
    }
});

// Email recipient type handler
document.getElementById('email-recipient-type')?.addEventListener('change', function() {
    const groupField = document.getElementById('email-group-field');
    const individualField = document.getElementById('email-individual-field');
    
    groupField.style.display = this.value === 'group' ? 'block' : 'none';
    individualField.style.display = this.value === 'individual' ? 'block' : 'none';
});

// SMS recipient type handler
document.getElementById('sms-recipient-type')?.addEventListener('change', function() {
    const groupField = document.getElementById('sms-group-field');
    const individualField = document.getElementById('sms-individual-field');
    
    groupField.style.display = this.value === 'group' ? 'block' : 'none';
    individualField.style.display = this.value === 'individual' ? 'block' : 'none';
});

// SMS character counter
document.getElementById('sms-message')?.addEventListener('input', function() {
    document.getElementById('sms-counter').textContent = this.value.length;
});

// Filter communications by type
function filterCommunications(type) {
    const rows = document.querySelectorAll('.modern-table tbody tr');
    rows.forEach(row => {
        if (type === 'all' || row.dataset.type === type) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

// Filter communications by status
function filterByStatus(status) {
    const rows = document.querySelectorAll('.modern-table tbody tr');
    rows.forEach(row => {
        if (status === 'all' || row.dataset.status === status) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

// View communication details (placeholder)
function viewCommunication(id) {
    alert('View communication details for ID: ' + id);
}

// Form submission handlers
document.getElementById('emailForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('/admin/communications/send-email', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Email sent successfully!');
            closeModal('emailModal');
            location.reload();
        } else {
            alert('Failed to send email: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Network error occurred');
    });
});

document.getElementById('smsForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('/admin/communications/send-sms', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('SMS sent successfully!');
            closeModal('smsModal');
            location.reload();
        } else {
            alert('Failed to send SMS: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Network error occurred');
    });
});
</script>

<?php include_once __DIR__ . '/../layouts/admin-footer.php'; ?>
