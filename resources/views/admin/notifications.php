<?php include_once __DIR__ . '/../layouts/admin-header.php'; ?>

<style>
    /* Page Header */
    .page-header {
        margin-bottom: 24px;
    }

    .page-title {
        font-family: 'Playfair Display', serif;
        font-size: 28px;
        font-weight: 700;
        color: #1F2937;
        margin: 0 0 4px 0;
    }

    .page-subtitle {
        font-size: 13px;
        color: #9CA3AF;
        margin: 0;
    }

    /* Filter Bar */
    .filter-bar {
        background: white;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 24px;
        border: 1px solid #E5E7EB;
        display: flex;
        gap: 16px;
        flex-wrap: wrap;
    }

    .filter-item {
        flex: 1;
        min-width: 200px;
    }

    .filter-label {
        display: block;
        font-size: 11px;
        color: #9CA3AF;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }

    .filter-select {
        width: 100%;
        padding: 10px 16px;
        border: 1px solid #D1D5DB;
        border-radius: 8px;
        font-size: 14px;
        color: #1F2937;
        background: white;
        cursor: pointer;
    }

    .filter-select:focus {
        outline: none;
        border-color: #D1D5DB;
    }

    /* Notification List */
    .notification-list {
        background: white;
        border-radius: 12px;
        border: 1px solid #E5E7EB;
        overflow: hidden;
    }

    .notification-item {
        padding: 20px 24px;
        border-bottom: 1px solid #F3F4F6;
        display: flex;
        align-items: flex-start;
        gap: 16px;
        transition: all 0.2s;
        cursor: pointer;
    }

    .notification-item:hover {
        background: #F9FAFB;
    }

    .notification-item:last-child {
        border-bottom: none;
    }

    .notification-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
    }

    .notification-icon.info {
        background: #DBEAFE;
        color: #3B82F6;
    }

    .notification-icon.success {
        background: #D1FAE5;
        color: #10B981;
    }

    .notification-icon.warning {
        background: #FED7AA;
        color: #F97316;
    }

    .notification-icon.error {
        background: #FEE2E2;
        color: #EF4444;
    }

    .notification-content {
        flex: 1;
    }

    .notification-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 8px;
    }

    .notification-title {
        font-size: 15px;
        font-weight: 600;
        color: #1F2937;
    }

    .notification-time {
        font-size: 12px;
        color: #9CA3AF;
    }

    .notification-message {
        font-size: 13px;
        color: #6B7280;
        line-height: 1.6;
        margin-bottom: 8px;
    }

    .notification-meta {
        display: flex;
        gap: 16px;
        font-size: 12px;
        color: #9CA3AF;
    }

    .notification-meta i {
        margin-right: 4px;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 80px 20px;
    }

    .empty-state i {
        font-size: 64px;
        margin-bottom: 16px;
        opacity: 0.3;
        color: #9CA3AF;
    }

    .empty-state h3 {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 8px;
        color: #6B7280;
    }

    .empty-state p {
        font-size: 13px;
        color: #9CA3AF;
        margin: 0;
    }
</style>

<!-- Page Header -->
<div class="page-header">
    <h1 class="page-title">System Notifications</h1>
    <p class="page-subtitle">Track communication logs, system events, and administrative alerts</p>
</div>

    <!-- Filter Bar -->
    <div class="filter-bar">
        <div class="filter-item">
            <label class="filter-label">Category</label>
            <select class="filter-select" id="categoryFilter">
                <option value="all">All Categories</option>
                <option value="membership">Membership</option>
                <option value="payment">Payment</option>
                <option value="claims">Claims</option>
                <option value="email">Email</option>
                <option value="sms">SMS</option>
                <option value="system">System</option>
            </select>
        </div>
        <div class="filter-item">
            <label class="filter-label">Type</label>
            <select class="filter-select" id="typeFilter">
                <option value="all">All Types</option>
                <option value="success">Success</option>
                <option value="warning">Warning</option>
                <option value="info">Info</option>
                <option value="error">Error</option>
            </select>
        </div>
        <div class="filter-item">
            <label class="filter-label">Date Range</label>
            <select class="filter-select" id="dateFilter">
                <option value="today">Today</option>
                <option value="week">Last 7 Days</option>
                <option value="month">Last 30 Days</option>
                <option value="all">All Time</option>
            </select>
        </div>
    </div>

    <!-- Notification List -->
    <div class="notification-list">
        <?php if (!empty($notifications)): ?>
            <?php foreach ($notifications as $notification): ?>
                <div class="notification-item">
                    <div class="notification-icon <?= $notification['type'] ?? 'info' ?>">
                        <i class="fas fa-<?= $notification['icon'] ?? 'bell' ?>"></i>
                    </div>
                    <div class="notification-content">
                        <div class="notification-header">
                            <div class="notification-title"><?= htmlspecialchars($notification['title']) ?></div>
                            <div class="notification-time"><?= $notification['time'] ?></div>
                        </div>
                        <div class="notification-message"><?= htmlspecialchars($notification['message']) ?></div>
                        <div class="notification-meta">
                            <span><i class="fas fa-user"></i><?= $notification['recipient'] ?? 'N/A' ?></span>
                            <span><i class="fas fa-tag"></i><?= $notification['category'] ?? 'General' ?></span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-bell"></i>
                <h3>No Notifications Yet</h3>
                <p>System notifications will appear here when activity occurs</p>
            </div>
        <?php endif; ?>
    </div>

<script>
// Filter functionality
document.getElementById('categoryFilter').addEventListener('change', filterNotifications);
document.getElementById('typeFilter').addEventListener('change', filterNotifications);
document.getElementById('dateFilter').addEventListener('change', filterNotifications);

function filterNotifications() {
    const category = document.getElementById('categoryFilter').value;
    const type = document.getElementById('typeFilter').value;
    const date = document.getElementById('dateFilter').value;
    
    // Reload page with filters
    const params = new URLSearchParams();
    if (category !== 'all') params.append('category', category);
    if (type !== 'all') params.append('type', type);
    if (date !== 'all') params.append('date', date);
    
    const queryString = params.toString();
    window.location.href = '/admin/notifications' + (queryString ? '?' + queryString : '');
}
</script>

<?php include_once __DIR__ . '/../layouts/admin-footer.php'; ?>
