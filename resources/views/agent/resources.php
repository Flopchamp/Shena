<?php 
$page = 'resources'; 
include __DIR__ . '/../layouts/agent-header.php';

// Sample data - replace with actual database queries
$flyers_brochures = [
    [
        'title' => 'Family Protection P...',
        'size' => '5.3 MB',
        'badge' => 'PREM-PLAN',
        'badge_class' => 'premium'
    ],
    [
        'title' => 'Senior Care Policy B...',
        'size' => '4.8 MB',
        'badge' => 'BANNER',
        'badge_class' => 'banner'
    ]
];

$social_media = [
    [
        'title' => 'WhatsApp Status Im...',
        'size' => '1.1 MB',
        'badge' => 'INSTAGRAM',
        'badge_class' => 'instagram'
    ],
    [
        'title' => 'Facebook Header Art',
        'size' => '3.3 MB',
        'badge' => 'FACEBOOK',
        'badge_class' => 'facebook'
    ]
];

$member_forms = [
    [
        'title' => 'Standard Registrati...',
        'size' => '302 KB'
    ]
];

$latest_updates = [
    [
        'title' => 'New 2024 Policy Guidelines Added',
        'time' => '2 hours ago',
        'color' => 'purple'
    ],
    [
        'title' => 'October Bonus Campaign - Kaizer',
        'time' => 'Yesterday',
        'color' => 'purple'
    ],
    [
        'title' => 'Updated Registration T&Cs',
        'time' => '5 days ago',
        'color' => 'purple'
    ]
];
?>

<style>
/* Resources Page Styles */
.resources-container {
    padding: 30px 30px 40px 25px;
    background: #F8F9FA;
    min-height: calc(100vh - 80px);
}

.resources-header {
    margin-bottom: 32px;
}

.resources-header h1 {
    font-family: 'Playfair Display', serif;
    font-size: 32px;
    font-weight: 700;
    color: #1F2937;
    margin: 0 0 4px 0;
}

.resources-header p {
    font-size: 14px;
    color: #6B7280;
    margin: 0;
}

/* Main Grid */
.resources-grid {
    display: grid;
    grid-template-columns: 1fr 350px;
    gap: 24px;
}

/* Resource Section */
.resource-section {
    margin-bottom: 32px;
}

.section-header-resources {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 16px;
}

.section-title-resources {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    font-weight: 700;
    color: #9CA3AF;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.section-title-resources i {
    font-size: 14px;
}

.view-all-link {
    font-size: 13px;
    font-weight: 600;
    color: #7F20B0;
    text-decoration: none;
    transition: color 0.2s;
}

.view-all-link:hover {
    color: #5E2B7A;
}

/* Resource Cards Grid */
.resource-cards-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 16px;
}

.resource-card {
    background: white;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    transition: transform 0.2s, box-shadow 0.2s;
}

.resource-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.resource-icon {
    width: 100%;
    height: 120px;
    background: #F3F4F6;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 12px;
    position: relative;
    overflow: hidden;
}

.resource-icon i {
    font-size: 48px;
    color: #D1D5DB;
}

.resource-badge {
    position: absolute;
    top: 8px;
    left: 8px;
    padding: 4px 10px;
    border-radius: 4px;
    font-size: 9px;
    font-weight: 700;
    letter-spacing: 0.5px;
    text-transform: uppercase;
}

.resource-badge.premium {
    background: #7F20B0;
    color: white;
}

.resource-badge.banner {
    background: #7F20B0;
    color: white;
}

.resource-badge.instagram {
    background: #059669;
    color: white;
}

.resource-badge.facebook {
    background: #2563EB;
    color: white;
}

.resource-info h6 {
    font-size: 14px;
    font-weight: 600;
    color: #1F2937;
    margin: 0 0 4px 0;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.resource-info p {
    font-size: 12px;
    color: #9CA3AF;
    margin: 0 0 12px 0;
}

.btn-download-resource {
    background: linear-gradient(135deg, #7F20B0 0%, #5E2B7A 100%);
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 6px;
    font-weight: 600;
    font-size: 12px;
    width: 100%;
    cursor: pointer;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
}

.btn-download-resource:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(127, 32, 176, 0.3);
}

/* Sidebar */
.resources-sidebar {
    display: flex;
    flex-direction: column;
    gap: 24px;
}

/* Latest Updates Card */
.latest-updates-card {
    background: white;
    border-radius: 16px;
    padding: 24px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.updates-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 16px;
    padding-bottom: 12px;
    border-bottom: 1px solid #E5E7EB;
}

.updates-header h3 {
    font-size: 13px;
    font-weight: 700;
    color: #9CA3AF;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin: 0;
}

.notification-dot {
    width: 8px;
    height: 8px;
    background: #EF4444;
    border-radius: 50%;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.5;
    }
}

.update-item {
    display: flex;
    gap: 12px;
    padding: 12px 0;
    border-bottom: 1px solid #F3F4F6;
}

.update-item:last-child {
    border-bottom: none;
}

.update-icon {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    background: #F3E8FF;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #7F20B0;
    font-size: 14px;
    flex-shrink: 0;
}

.update-content h6 {
    font-size: 13px;
    font-weight: 600;
    color: #1F2937;
    margin: 0 0 4px 0;
    line-height: 1.4;
}

.update-content p {
    font-size: 11px;
    color: #9CA3AF;
    margin: 0;
}

.clear-notifications {
    text-align: center;
    padding-top: 12px;
    margin-top: 12px;
    border-top: 1px solid #E5E7EB;
}

.btn-clear-notifications {
    color: #7F20B0;
    font-size: 12px;
    font-weight: 600;
    background: none;
    border: none;
    cursor: pointer;
    text-decoration: underline;
}

/* Resource Tip Card */
.resource-tip-card {
    background: linear-gradient(135deg, #7F20B0 0%, #5E2B7A 100%);
    border-radius: 16px;
    padding: 24px;
    color: white;
    box-shadow: 0 4px 12px rgba(127, 32, 176, 0.3);
}

.tip-header {
    font-size: 13px;
    font-weight: 700;
    color: white;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 12px;
}

.tip-content {
    font-size: 13px;
    line-height: 1.6;
    color: rgba(255, 255, 255, 0.95);
    margin-bottom: 16px;
}

.btn-read-guidelines {
    background: white;
    color: #7F20B0;
    border: none;
    padding: 10px 20px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 13px;
    width: 100%;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-read-guidelines:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(255, 255, 255, 0.3);
}

/* Responsive */
@media (max-width: 1200px) {
    .resources-grid {
        grid-template-columns: 1fr;
    }

    .resource-cards-grid {
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
    }
}

@media (max-width: 768px) {
    .resources-container {
        padding: 20px 15px;
    }

    .resource-cards-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<div class="resources-container">
    <div class="resources-header">
        <h1>Marketing Resources & Toolkit</h1>
        <p>Access sales tools to help grow your member base</p>
    </div>

    <!-- Main Grid -->
    <div class="resources-grid">
        <!-- Left Column -->
        <div>
            <!-- Flyers & Brochures Section -->
            <div class="resource-section">
                <div class="section-header-resources">
                    <div class="section-title-resources">
                        <i class="fas fa-file-alt"></i>
                        <span>Flyers & Brochures</span>
                    </div>
                    <a href="#" class="view-all-link">View All</a>
                </div>

                <div class="resource-cards-grid">
                    <?php foreach ($flyers_brochures as $resource): ?>
                    <div class="resource-card">
                        <div class="resource-icon">
                            <i class="fas fa-file-pdf"></i>
                            <span class="resource-badge <?php echo $resource['badge_class']; ?>">
                                <?php echo $resource['badge']; ?>
                            </span>
                        </div>
                        <div class="resource-info">
                            <h6><?php echo htmlspecialchars($resource['title']); ?></h6>
                            <p>PDF • <?php echo $resource['size']; ?></p>
                            <button class="btn-download-resource">
                                <i class="fas fa-download"></i>
                                Download
                            </button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Social Media Graphics Section -->
            <div class="resource-section">
                <div class="section-header-resources">
                    <div class="section-title-resources">
                        <i class="fas fa-images"></i>
                        <span>Social Media Graphics</span>
                    </div>
                    <a href="#" class="view-all-link">View All</a>
                </div>

                <div class="resource-cards-grid">
                    <?php foreach ($social_media as $resource): ?>
                    <div class="resource-card">
                        <div class="resource-icon">
                            <i class="fas fa-image"></i>
                            <span class="resource-badge <?php echo $resource['badge_class']; ?>">
                                <?php echo $resource['badge']; ?>
                            </span>
                        </div>
                        <div class="resource-info">
                            <h6><?php echo htmlspecialchars($resource['title']); ?></h6>
                            <p>PNG • <?php echo $resource['size']; ?></p>
                            <button class="btn-download-resource">
                                <i class="fas fa-download"></i>
                                Download
                            </button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Member Application Forms Section -->
            <div class="resource-section">
                <div class="section-header-resources">
                    <div class="section-title-resources">
                        <i class="fas fa-file-contract"></i>
                        <span>Member Application Forms</span>
                    </div>
                    <a href="#" class="view-all-link">View All</a>
                </div>

                <div class="resource-cards-grid">
                    <?php foreach ($member_forms as $resource): ?>
                    <div class="resource-card">
                        <div class="resource-icon">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <div class="resource-info">
                            <h6><?php echo htmlspecialchars($resource['title']); ?></h6>
                            <p>PDF • <?php echo $resource['size']; ?></p>
                            <button class="btn-download-resource">
                                <i class="fas fa-download"></i>
                                Download
                            </button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Right Sidebar -->
        <div class="resources-sidebar">
            <!-- Latest Updates -->
            <div class="latest-updates-card">
                <div class="updates-header">
                    <h3>Latest Updates</h3>
                    <span class="notification-dot"></span>
                </div>

                <?php foreach ($latest_updates as $update): ?>
                <div class="update-item">
                    <div class="update-icon">
                        <i class="fas fa-bell"></i>
                    </div>
                    <div class="update-content">
                        <h6><?php echo htmlspecialchars($update['title']); ?></h6>
                        <p><?php echo $update['time']; ?></p>
                    </div>
                </div>
                <?php endforeach; ?>

                <div class="clear-notifications">
                    <button class="btn-clear-notifications">Clear Notifications</button>
                </div>
            </div>

            <!-- Resource Tip -->
            <div class="resource-tip-card">
                <div class="tip-header">Resource Tip</div>
                <p class="tip-content">
                    Always use the latest 2025 versions for compliance with current policies before associating with registrants.
                </p>
                <button class="btn-read-guidelines">Read Guidelines</button>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/agent-footer.php'; ?>
