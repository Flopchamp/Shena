<?php
$page = 'notifications';
include __DIR__ . '/../layouts/agent-header.php';

// Sample notification data - will be passed from controller
$notifications = $notifications ?? [
    [
        'id' => 1,
        'type' => 'registration',
        'icon' => 'fa-user-plus',
        'color' => '#10B981',
        'title' => 'New Member Registered',
        'message' => 'John Kamau has been successfully registered. Member #SH-98456',
        'time' => '1 hour ago',
        'read' => false,
        'action_url' => '/agent/members',
        'action_text' => 'View Member'
    ],
    [
        'id' => 2,
        'type' => 'commission',
        'icon' => 'fa-money-bill-wave',
        'color' => '#059669',
        'title' => 'Commission Earned',
        'message' => 'You earned KES 300 commission from John Kamau\'s registration.',
        'time' => '1 hour ago',
        'read' => false,
        'action_url' => '/agent/payouts',
        'action_text' => 'View Commissions'
    ],
    [
        'id' => 3,
        'type' => 'payout',
        'icon' => 'fa-check-circle',
        'color' => '#3B82F6',
        'title' => 'Payout Processed',
        'message' => 'Your payout request of KES 12,500 has been approved and processed.',
        'time' => '3 hours ago',
        'read' => false,
        'action_url' => '/agent/payouts',
        'action_text' => 'View Details'
    ],
    [
        'id' => 4,
        'type' => 'payment',
        'icon' => 'fa-credit-card',
        'color' => '#8B5CF6',
        'title' => 'Member Payment Confirmed',
        'message' => 'Mary Akinyi (SH-98234) has paid their monthly contribution.',
        'time' => '5 hours ago',
        'read' => true,
        'action_url' => '/agent/members',
        'action_text' => 'View Member'
    ],
    [
        'id' => 5,
        'type' => 'alert',
        'icon' => 'fa-exclamation-triangle',
        'color' => '#F59E0B',
        'title' => 'Member Defaulted',
        'message' => 'Peter Ochieng (SH-97812) has missed 2 consecutive payments.',
        'time' => '1 day ago',
        'read' => true,
        'action_url' => '/agent/members',
        'action_text' => 'Follow Up'
    ],
    [
        'id' => 6,
        'type' => 'system',
        'icon' => 'fa-megaphone',
        'color' => '#06B6D4',
        'title' => 'System Announcement',
        'message' => 'New training resources available in the Resources section.',
        'time' => '2 days ago',
        'read' => true,
        'action_url' => '/agent/resources',
        'action_text' => 'View Resources'
    ],
    [
        'id' => 7,
        'type' => 'milestone',
        'icon' => 'fa-trophy',
        'color' => '#EF4444',
        'title' => 'Milestone Achieved!',
        'message' => 'Congratulations! You\'ve registered 50 members this month.',
        'time' => '3 days ago',
        'read' => true,
        'action_url' => '/agent/dashboard',
        'action_text' => 'View Stats'
    ],
    [
        'id' => 8,
        'type' => 'training',
        'icon' => 'fa-graduation-cap',
        'color' => '#6366F1',
        'title' => 'New Training Available',
        'message' => 'Complete the "Advanced Claims Processing" course to boost your skills.',
        'time' => '1 week ago',
        'read' => true,
        'action_url' => '/agent/resources',
        'action_text' => 'Start Training'
    ]
];

$unread_count = count(array_filter($notifications, fn($n) => !$n['read']));
$total_count = count($notifications);
?>

<style>
.agent-notifications-container {
    padding: 30px 30px 40px 25px;
    background: #F8F9FA;
    min-height: calc(100vh - 80px);
}

.page-header {
    margin-bottom: 32px;
}

.page-header-top {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 8px;
}

.page-header h1 {
    font-family: 'Playfair Display', serif;
    font-size: 32px;
    font-weight: 700;
    color: #1F2937;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 12px;
}

.header-actions {
    display: flex;
    gap: 12px;
}

.mark-all-btn {
    background: white;
    color: #6B7280;
    border: 1px solid #E5E7EB;
    padding: 10px 20px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    gap: 8px;
}

.mark-all-btn:hover {
    background: #F9FAFB;
    border-color: #7F20B0;
    color: #7F20B0;
}

.clear-all-btn {
    background: #FEE2E2;
    color: #DC2626;
    border: none;
    padding: 10px 20px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    gap: 8px;
}

.clear-all-btn:hover {
    background: #FECACA;
}

.page-header p {
    font-size: 14px;
    color: #6B7280;
    margin: 0;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 24px;
    margin-bottom: 32px;
}

.stat-card-notifications {
    background: white;
    border-radius: 16px;
    padding: 24px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    display: flex;
    align-items: center;
    gap: 16px;
    transition: transform 0.2s, box-shadow 0.2s;
}

.stat-card-notifications:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.stat-icon-box {
    width: 56px;
    height: 56px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    flex-shrink: 0;
}

.stat-icon-box.unread {
    background: linear-gradient(135deg, #3B82F6, #1E40AF);
    color: white;
}

.stat-icon-box.total {
    background: linear-gradient(135deg, #8B5CF6, #6D28D9);
    color: white;
}

.stat-icon-box.today {
    background: linear-gradient(135deg, #10B981, #059669);
    color: white;
}

.stat-icon-box.week {
    background: linear-gradient(135deg, #F59E0B, #D97706);
    color: white;
}

.stat-details h3 {
    font-size: 32px;
    font-weight: 700;
    color: #1F2937;
    margin: 0 0 4px 0;
}

.stat-details p {
    font-size: 13px;
    color: #6B7280;
    margin: 0;
    font-weight: 600;
}

.notifications-layout {
    display: grid;
    grid-template-columns: 1fr 320px;
    gap: 24px;
}

.notifications-main {
    min-width: 0;
}

.notifications-tabs {
    display: flex;
    gap: 12px;
    margin-bottom: 24px;
    border-bottom: 2px solid #E5E7EB;
    padding-bottom: 0;
    flex-wrap: wrap;
}

.tab-btn {
    background: none;
    border: none;
    padding: 12px 20px;
    font-size: 14px;
    font-weight: 600;
    color: #6B7280;
    cursor: pointer;
    border-bottom: 3px solid transparent;
    margin-bottom: -2px;
    transition: all 0.2s;
    white-space: nowrap;
}

.tab-btn.active {
    color: #7F20B0;
    border-bottom-color: #7F20B0;
}

.tab-btn:hover {
    color: #7F20B0;
}

.tab-badge {
    background: #EF4444;
    color: white;
    padding: 2px 8px;
    border-radius: 10px;
    font-size: 11px;
    font-weight: 700;
    margin-left: 6px;
}

.notifications-list {
    background: white;
    border-radius: 16px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.notification-item {
    display: flex;
    gap: 20px;
    padding: 20px 24px;
    border-bottom: 1px solid #F3F4F6;
    transition: all 0.2s;
    cursor: pointer;
    position: relative;
}

.notification-item:last-child {
    border-bottom: none;
}

.notification-item:hover {
    background: #F9FAFB;
}

.notification-item.unread {
    background: #F0F9FF;
}

.notification-item.unread::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 4px;
    background: linear-gradient(180deg, #3B82F6, #1E40AF);
}

.notification-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    font-size: 20px;
    color: white;
}

.notification-content {
    flex: 1;
}

.notification-header {
    display: flex;
    justify-content: space-between;
    align-items: start;
    margin-bottom: 6px;
}

.notification-title {
    font-size: 15px;
    font-weight: 700;
    color: #1F2937;
    margin: 0;
}

.notification-time {
    font-size: 12px;
    color: #9CA3AF;
    white-space: nowrap;
    margin-left: 12px;
}

.notification-message {
    font-size: 14px;
    color: #6B7280;
    line-height: 1.5;
    margin: 0 0 12px 0;
}

.notification-action {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    color: #7F20B0;
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.2s;
}

.notification-action:hover {
    color: #5E2B7A;
    gap: 8px;
}

.notification-actions {
    display: flex;
    gap: 8px;
    align-items: center;
}

.action-icon-btn {
    width: 32px;
    height: 32px;
    border-radius: 6px;
    border: 1px solid #E5E7EB;
    background: white;
    color: #6B7280;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s;
}

.action-icon-btn:hover {
    background: #F3F4F6;
    color: #7F20B0;
    border-color: #7F20B0;
}

.notifications-sidebar {
    position: sticky;
    top: 20px;
}

.sidebar-card {
    background: white;
    border-radius: 16px;
    padding: 24px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    margin-bottom: 20px;
}

.sidebar-card h3 {
    font-size: 13px;
    font-weight: 700;
    color: #9CA3AF;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin: 0 0 16px 0;
}

.filter-option {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px 0;
    border-bottom: 1px solid #F3F4F6;
    cursor: pointer;
    transition: all 0.2s;
}

.filter-option:last-child {
    border-bottom: none;
}

.filter-option:hover {
    color: #7F20B0;
}

.filter-option span {
    font-size: 14px;
    color: #4B5563;
    font-weight: 500;
}

.filter-option .count {
    background: #F3F4F6;
    color: #6B7280;
    padding: 2px 8px;
    border-radius: 10px;
    font-size: 12px;
    font-weight: 700;
}

.quick-actions-card {
    background: linear-gradient(135deg, #7F20B0, #5E2B7A);
    border-radius: 16px;
    padding: 24px;
    color: white;
}

.quick-actions-card h3 {
    font-size: 16px;
    font-weight: 700;
    margin: 0 0 16px 0;
    color: white;
}

.quick-action-btn {
    width: 100%;
    background: rgba(255, 255, 255, 0.2);
    border: 1px solid rgba(255, 255, 255, 0.3);
    color: white;
    padding: 12px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    margin-bottom: 12px;
}

.quick-action-btn:last-child {
    margin-bottom: 0;
}

.quick-action-btn:hover {
    background: rgba(255, 255, 255, 0.3);
}

.empty-state {
    text-align: center;
    padding: 80px 20px;
}

.empty-state-icon {
    width: 100px;
    height: 100px;
    margin: 0 auto 24px;
    background: #F3F4F6;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 40px;
    color: #9CA3AF;
}

.empty-state h3 {
    font-family: 'Playfair Display', serif;
    font-size: 22px;
    font-weight: 600;
    color: #4B5563;
    margin: 0 0 8px 0;
}

.empty-state p {
    font-size: 14px;
    color: #9CA3AF;
    margin: 0;
}

@media (max-width: 1200px) {
    .notifications-layout {
        grid-template-columns: 1fr;
    }

    .notifications-sidebar {
        position: static;
    }
}

@media (max-width: 768px) {
    .agent-notifications-container {
        padding: 20px 15px;
    }

    .page-header-top {
        flex-direction: column;
        align-items: flex-start;
        gap: 16px;
    }

    .header-actions {
        width: 100%;
    }

    .mark-all-btn,
    .clear-all-btn {
        flex: 1;
        justify-content: center;
    }

    .stats-grid {
        grid-template-columns: 1fr;
    }

    .notification-item {
        flex-direction: column;
        gap: 12px;
    }

    .notification-header {
        flex-direction: column;
        gap: 4px;
    }

    .notification-time {
        margin-left: 0;
    }
}
</style>

<div class="agent-notifications-container">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-top">
            <h1>
                <i class="fas fa-bell"></i>
                Notifications Center
            </h1>
            <div class="header-actions">
                <button class="mark-all-btn" onclick="markAllAsRead()">
                    <i class="fas fa-check-double"></i> Mark All Read
                </button>
                <button class="clear-all-btn" onclick="clearAllNotifications()">
                    <i class="fas fa-trash-alt"></i> Clear All
                </button>
            </div>
        </div>
        <p>Stay informed about your agent activities, commissions, and member updates</p>
    </div>

    <!-- Stats Grid -->
    <div class="stats-grid">
        <div class="stat-card-notifications">
            <div class="stat-icon-box unread">
                <i class="fas fa-envelope"></i>
            </div>
            <div class="stat-details">
                <h3><?php echo $unread_count; ?></h3>
                <p>Unread Notifications</p>
            </div>
        </div>
        <div class="stat-card-notifications">
            <div class="stat-icon-box total">
                <i class="fas fa-list"></i>
            </div>
            <div class="stat-details">
                <h3><?php echo $total_count; ?></h3>
                <p>Total Notifications</p>
            </div>
        </div>
        <div class="stat-card-notifications">
            <div class="stat-icon-box today">
                <i class="fas fa-calendar-day"></i>
            </div>
            <div class="stat-details">
                <h3><?php echo count(array_filter($notifications, fn($n) => strpos($n['time'], 'hour') !== false)); ?></h3>
                <p>Today</p>
            </div>
        </div>
        <div class="stat-card-notifications">
            <div class="stat-icon-box week">
                <i class="fas fa-calendar-week"></i>
            </div>
            <div class="stat-details">
                <h3><?php echo count(array_filter($notifications, fn($n) => strpos($n['time'], 'day') !== false || strpos($n['time'], 'hour') !== false)); ?></h3>
                <p>This Week</p>
            </div>
        </div>
    </div>

    <!-- Main Layout -->
    <div class="notifications-layout">
        <!-- Notifications Main -->
        <div class="notifications-main">
            <!-- Tabs -->
            <div class="notifications-tabs">
                <button class="tab-btn active" data-tab="all">
                    All
                </button>
                <button class="tab-btn" data-tab="unread">
                    Unread
                    <?php if ($unread_count > 0): ?>
                        <span class="tab-badge"><?php echo $unread_count; ?></span>
                    <?php endif; ?>
                </button>
                <button class="tab-btn" data-tab="registration">
                    Registrations
                </button>
                <button class="tab-btn" data-tab="commission">
                    Commissions
                </button>
                <button class="tab-btn" data-tab="payment">
                    Payments
                </button>
                <button class="tab-btn" data-tab="alert">
                    Alerts
                </button>
                <button class="tab-btn" data-tab="system">
                    System
                </button>
            </div>

            <!-- Notifications List -->
            <div class="notifications-list">
                <?php if (empty($notifications)): ?>
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="fas fa-bell-slash"></i>
                        </div>
                        <h3>No Notifications</h3>
                        <p>You're all caught up! Check back later for updates.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($notifications as $notification): ?>
                        <div class="notification-item <?php echo !$notification['read'] ? 'unread' : ''; ?>" 
                             data-id="<?php echo $notification['id']; ?>"
                             data-type="<?php echo $notification['type']; ?>">
                            <div class="notification-icon" style="background: <?php echo $notification['color']; ?>">
                                <i class="fas <?php echo $notification['icon']; ?>"></i>
                            </div>
                            <div class="notification-content">
                                <div class="notification-header">
                                    <h3 class="notification-title"><?php echo htmlspecialchars($notification['title']); ?></h3>
                                    <span class="notification-time"><?php echo htmlspecialchars($notification['time']); ?></span>
                                </div>
                                <p class="notification-message"><?php echo htmlspecialchars($notification['message']); ?></p>
                                <a href="<?php echo htmlspecialchars($notification['action_url']); ?>" class="notification-action">
                                    <?php echo htmlspecialchars($notification['action_text']); ?>
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                            <div class="notification-actions">
                                <?php if (!$notification['read']): ?>
                                    <button class="action-icon-btn" onclick="markAsRead(<?php echo $notification['id']; ?>)" title="Mark as read">
                                        <i class="fas fa-check"></i>
                                    </button>
                                <?php endif; ?>
                                <button class="action-icon-btn" onclick="deleteNotification(<?php echo $notification['id']; ?>)" title="Delete">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="notifications-sidebar">
            <!-- Filter Card -->
            <div class="sidebar-card">
                <h3>Filter by Type</h3>
                <div class="filter-option" data-filter="all">
                    <span>All Notifications</span>
                    <span class="count"><?php echo $total_count; ?></span>
                </div>
                <div class="filter-option" data-filter="registration">
                    <span>Registrations</span>
                    <span class="count"><?php echo count(array_filter($notifications, fn($n) => $n['type'] === 'registration')); ?></span>
                </div>
                <div class="filter-option" data-filter="commission">
                    <span>Commissions</span>
                    <span class="count"><?php echo count(array_filter($notifications, fn($n) => $n['type'] === 'commission')); ?></span>
                </div>
                <div class="filter-option" data-filter="payment">
                    <span>Payments</span>
                    <span class="count"><?php echo count(array_filter($notifications, fn($n) => $n['type'] === 'payment')); ?></span>
                </div>
                <div class="filter-option" data-filter="alert">
                    <span>Alerts</span>
                    <span class="count"><?php echo count(array_filter($notifications, fn($n) => $n['type'] === 'alert')); ?></span>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="quick-actions-card">
                <h3><i class="fas fa-bolt"></i> Quick Actions</h3>
                <button class="quick-action-btn" onclick="window.location.href='/agent/register-member'">
                    <i class="fas fa-user-plus"></i> Register New Member
                </button>
                <button class="quick-action-btn" onclick="window.location.href='/agent/members'">
                    <i class="fas fa-users"></i> View All Members
                </button>
                <button class="quick-action-btn" onclick="window.location.href='/agent/payouts'">
                    <i class="fas fa-money-check-alt"></i> Request Payout
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Tab Filtering
document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        // Update active tab
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        
        const tab = this.dataset.tab;
        filterNotifications(tab);
    });
});

// Sidebar Filter
document.querySelectorAll('.filter-option').forEach(option => {
    option.addEventListener('click', function() {
        const filter = this.dataset.filter;
        
        // Update tab
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        const targetTab = document.querySelector(`.tab-btn[data-tab="${filter}"]`);
        if (targetTab) {
            targetTab.classList.add('active');
        }
        
        filterNotifications(filter);
    });
});

function filterNotifications(filter) {
    const items = document.querySelectorAll('.notification-item');
    
    items.forEach(item => {
        if (filter === 'all') {
            item.style.display = 'flex';
        } else if (filter === 'unread') {
            item.style.display = item.classList.contains('unread') ? 'flex' : 'none';
        } else {
            item.style.display = item.dataset.type === filter ? 'flex' : 'none';
        }
    });
}

// Mark as Read
function markAsRead(id) {
    // AJAX call to mark as read
    console.log('Marking notification ' + id + ' as read');
    
    // Update UI
    const item = document.querySelector(`.notification-item[data-id="${id}"]`);
    if (item) {
        item.classList.remove('unread');
        const actionBtn = item.querySelector('.action-icon-btn[onclick*="markAsRead"]');
        if (actionBtn) {
            actionBtn.remove();
        }
    }
}

// Mark All as Read
function markAllAsRead() {
    const proceed = () => {
        // AJAX call to mark all as read
        console.log('Marking all notifications as read');

        // Update UI
        document.querySelectorAll('.notification-item.unread').forEach(item => {
            item.classList.remove('unread');
        });

        // Update unread count
        document.querySelectorAll('.tab-badge').forEach(badge => badge.remove());
    };

    if (window.ShenaApp && typeof ShenaApp.confirmAction === 'function') {
        ShenaApp.confirmAction(
            'Mark all notifications as read?',
            proceed,
            null,
            { type: 'warning', title: 'Mark All as Read', confirmText: 'Mark All' }
        );
        return;
    }

    if (!confirm('Mark all notifications as read?')) return;
    proceed();
}

// Delete Notification
function deleteNotification(id) {
    const proceed = () => {
        // AJAX call to delete
        console.log('Deleting notification ' + id);

        // Update UI
        const item = document.querySelector(`.notification-item[data-id="${id}"]`);
        if (item) {
            item.style.opacity = '0';
            setTimeout(() => item.remove(), 300);
        }
    };

    if (window.ShenaApp && typeof ShenaApp.confirmAction === 'function') {
        ShenaApp.confirmAction(
            'Delete this notification?',
            proceed,
            null,
            { type: 'danger', title: 'Delete Notification', confirmText: 'Delete' }
        );
        return;
    }

    if (!confirm('Delete this notification?')) return;
    proceed();
}

// Clear All Notifications
function clearAllNotifications() {
    const proceed = () => {
        // AJAX call to clear all
        console.log('Clearing all notifications');

        // Update UI
        document.querySelectorAll('.notification-item').forEach(item => {
            item.style.opacity = '0';
        });

        setTimeout(() => {
            document.querySelector('.notifications-list').innerHTML = `
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="fas fa-bell-slash"></i>
                    </div>
                    <h3>No Notifications</h3>
                    <p>You're all caught up! Check back later for updates.</p>
                </div>
            `;
        }, 300);
    };

    if (window.ShenaApp && typeof ShenaApp.confirmAction === 'function') {
        ShenaApp.confirmAction(
            'This will permanently delete all notifications. Continue?',
            proceed,
            null,
            { type: 'danger', title: 'Clear Notifications', confirmText: 'Clear All' }
        );
        return;
    }

    if (!confirm('This will permanently delete all notifications. Continue?')) return;
    proceed();
}
</script>

<?php include __DIR__ . '/../layouts/agent-footer.php'; ?>
