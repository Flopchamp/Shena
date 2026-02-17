<?php
/**
 * Agent Resources View - Modern UI
 * 
 * @package Shena\Views\Agent
 */
$page = 'resources'; 
include __DIR__ . '/../layouts/agent-header.php';

// Calculate totals
$totalResources = 0;
foreach ($resources as $category => $items) {
    $totalResources += count($items);
}

$categoryLabels = [
    'marketing_materials' => ['label' => 'Marketing Materials', 'icon' => 'fa-bullhorn', 'color' => '#7F20B0'],
    'training_documents' => ['label' => 'Training Documents', 'icon' => 'fa-graduation-cap', 'color' => '#059669'],
    'policy_documents' => ['label' => 'Policy Documents', 'icon' => 'fa-file-contract', 'color' => '#2563eb'],
    'forms' => ['label' => 'Forms', 'icon' => 'fa-file-alt', 'color' => '#d97706'],
    'other' => ['label' => 'Other Resources', 'icon' => 'fa-file', 'color' => '#6b7280']
];
?>

<style>
/* Resources Page Styles - Modern UI */
.resources-container {
    padding: 30px 30px 40px 25px;
    background: #F8F9FA;
    min-height: calc(100vh - 80px);
}

.resources-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 32px;
}

.resources-title-section h1 {
    font-family: 'Playfair Display', serif;
    font-size: 32px;
    font-weight: 700;
    color: #1F2937;
    margin: 0 0 4px 0;
}

.resources-title-section p {
    font-size: 14px;
    color: #6B7280;
    margin: 0;
}

/* Stats Cards */
.stats-section {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 32px;
}

.stat-card {
    background: white;
    border-radius: 16px;
    padding: 24px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    display: flex;
    align-items: center;
    gap: 16px;
    transition: transform 0.2s, box-shadow 0.2s;
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.stat-icon {
    width: 56px;
    height: 56px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    flex-shrink: 0;
}

.stat-icon.purple {
    background: linear-gradient(135deg, #F3E8FF 0%, #E9D5FF 100%);
    color: #7F20B0;
}

.stat-icon.green {
    background: linear-gradient(135deg, #D1FAE5 0%, #A7F3D0 100%);
    color: #059669;
}

.stat-icon.blue {
    background: linear-gradient(135deg, #DBEAFE 0%, #BFDBFE 100%);
    color: #2563eb;
}

.stat-icon.orange {
    background: linear-gradient(135deg, #FEF3C7 0%, #FDE68A 100%);
    color: #d97706;
}

.stat-content h3 {
    font-size: 28px;
    font-weight: 700;
    color: #1F2937;
    margin: 0 0 4px 0;
}

.stat-content p {
    font-size: 13px;
    color: #6B7280;
    margin: 0;
}

/* Category Section */
.category-section {
    background: white;
    border-radius: 16px;
    padding: 32px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    margin-bottom: 24px;
}

.category-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 24px;
    padding-bottom: 20px;
    border-bottom: 1px solid #E5E7EB;
}

.category-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    flex-shrink: 0;
}

.category-header h2 {
    font-family: 'Playfair Display', serif;
    font-size: 20px;
    font-weight: 700;
    color: #1F2937;
    margin: 0;
    flex: 1;
}

.category-count {
    background: #F3F4F6;
    color: #6B7280;
    font-size: 12px;
    font-weight: 600;
    padding: 6px 12px;
    border-radius: 20px;
}

.category-desc {
    font-size: 14px;
    color: #6B7280;
    margin: -12px 0 24px 0;
}

/* Resources Table */
.resources-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
}

.resources-table thead th {
    font-size: 11px;
    font-weight: 700;
    color: #9CA3AF;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 12px 16px;
    border-bottom: 1px solid #E5E7EB;
    text-align: left;
}

.resources-table tbody tr {
    transition: background-color 0.2s;
}

.resources-table tbody tr:hover {
    background: #F9FAFB;
}

.resources-table tbody td {
    padding: 16px;
    border-bottom: 1px solid #F3F4F6;
    vertical-align: middle;
}

.resource-info {
    display: flex;
    align-items: center;
    gap: 12px;
}

.resource-icon-small {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: #F3F4F6;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #6B7280;
    font-size: 16px;
    flex-shrink: 0;
}

.resource-details h6 {
    font-size: 14px;
    font-weight: 600;
    color: #1F2937;
    margin: 0 0 2px 0;
}

.resource-details p {
    font-size: 12px;
    color: #9CA3AF;
    margin: 0;
}

.file-size {
    font-size: 13px;
    color: #4B5563;
    font-weight: 500;
}

.download-count {
    font-size: 13px;
    color: #6B7280;
}

.upload-date {
    font-size: 13px;
    color: #6B7280;
}

.new-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    background: linear-gradient(135deg, #7F20B0 0%, #5E2B7A 100%);
    color: white;
    font-size: 10px;
    font-weight: 700;
    padding: 4px 10px;
    border-radius: 20px;
    margin-left: 8px;
}

.btn-download {
    background: linear-gradient(135deg, #7F20B0 0%, #5E2B7A 100%);
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 13px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    cursor: pointer;
    transition: transform 0.2s, box-shadow 0.2s;
    text-decoration: none;
}

.btn-download:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(127, 32, 176, 0.3);
    color: white;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 60px 20px;
}

.empty-state-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto 24px;
    background: #F3F4F6;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #9CA3AF;
    font-size: 32px;
}

.empty-state h3 {
    font-family: 'Playfair Display', serif;
    font-size: 20px;
    font-weight: 700;
    color: #1F2937;
    margin: 0 0 8px 0;
}

.empty-state p {
    font-size: 14px;
    color: #6B7280;
    margin: 0;
}

/* Alert Messages */
.alert {
    padding: 16px 20px;
    border-radius: 12px;
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 14px;
}

.alert-success {
    background: #D1FAE5;
    color: #065F46;
    border: 1px solid #A7F3D0;
}

.alert-error {
    background: #FEE2E2;
    color: #991B1B;
    border: 1px solid #FECACA;
}

.alert i {
    font-size: 18px;
}

/* Responsive */
@media (max-width: 768px) {
    .resources-container {
        padding: 20px 15px;
    }

    .resources-header {
        flex-direction: column;
        gap: 16px;
    }

    .stats-section {
        grid-template-columns: repeat(2, 1fr);
    }

    .stat-card {
        padding: 16px;
    }

    .stat-icon {
        width: 44px;
        height: 44px;
        font-size: 18px;
    }

    .stat-content h3 {
        font-size: 20px;
    }

    .category-section {
        padding: 20px;
    }

    .resources-table {
        display: block;
        overflow-x: auto;
    }
}
</style>

<div class="resources-container">
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            <?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i>
            <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>
    
    <div class="resources-header">
        <div class="resources-title-section">
            <h1>Resources & Materials</h1>
            <p>Download marketing materials, training documents, forms, and other resources</p>
        </div>
    </div>

    <!-- Stats Section -->
    <div class="stats-section">
        <div class="stat-card">
            <div class="stat-icon purple">
                <i class="fas fa-folder-open"></i>
            </div>
            <div class="stat-content">
                <h3><?php echo $totalResources; ?></h3>
                <p>Total Resources</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon green">
                <i class="fas fa-bullhorn"></i>
            </div>
            <div class="stat-content">
                <h3><?php echo count($resources['marketing_materials'] ?? []); ?></h3>
                <p>Marketing Materials</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon blue">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <div class="stat-content">
                <h3><?php echo count($resources['training_documents'] ?? []); ?></h3>
                <p>Training Documents</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon orange">
                <i class="fas fa-file-alt"></i>
            </div>
            <div class="stat-content">
                <h3><?php echo count($resources['forms'] ?? []); ?></h3>
                <p>Forms</p>
            </div>
        </div>
    </div>
    
    <?php foreach ($categoryLabels as $categoryKey => $categoryInfo): 
        $categoryResources = $resources[$categoryKey] ?? [];
        if (empty($categoryResources)) continue;
    ?>
        <div class="category-section">
            <div class="category-header">
                <div class="category-icon" style="background: <?php echo $categoryInfo['color']; ?>15; color: <?php echo $categoryInfo['color']; ?>">
                    <i class="fas <?php echo $categoryInfo['icon']; ?>"></i>
                </div>
                <h2><?php echo $categoryInfo['label']; ?></h2>
                <span class="category-count"><?php echo count($categoryResources); ?> files</span>
            </div>
            
            <table class="resources-table">
                <thead>
                    <tr>
                        <th>RESOURCE</th>
                        <th>FILE SIZE</th>
                        <th>DOWNLOADS</th>
                        <th>UPLOADED</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categoryResources as $resource): 
                        $isNew = strtotime($resource['created_at']) > strtotime('-7 days');
                        $fileExt = pathinfo($resource['original_name'], PATHINFO_EXTENSION);
                    ?>
                        <tr>
                            <td>
                                <div class="resource-info">
                                    <div class="resource-icon-small">
                                        <i class="fas fa-file<?php echo in_array(strtolower($fileExt), ['pdf']) ? '-pdf' : ''; ?>"></i>
                                    </div>
                                    <div class="resource-details">
                                        <h6>
                                            <?php echo htmlspecialchars($resource['title']); ?>
                                            <?php if ($isNew): ?>
                                                <span class="new-badge"><i class="fas fa-sparkles"></i> NEW</span>
                                            <?php endif; ?>
                                        </h6>
                                        <p><?php echo htmlspecialchars($resource['description'] ?: $resource['original_name']); ?></p>
                                    </div>
                                </div>
                            </td>
                            <td class="file-size"><?php echo Resource::formatFileSize($resource['file_size']); ?></td>
                            <td class="download-count">
                                <i class="fas fa-download" style="margin-right: 4px; color: #9CA3AF;"></i>
                                <?php echo $resource['download_count']; ?>
                            </td>
                            <td class="upload-date"><?php echo date('M d, Y', strtotime($resource['created_at'])); ?></td>
                            <td>
                                <a href="/agent/resources/download/<?php echo $resource['id']; ?>" class="btn-download">
                                    <i class="fas fa-download"></i>
                                    Download
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endforeach; ?>
    
    <?php if ($totalResources === 0): ?>
        <div class="category-section">
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="fas fa-folder-open"></i>
                </div>
                <h3>No Resources Available</h3>
                <p>Check back later for new marketing materials, training documents, and forms.</p>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../layouts/agent-footer.php'; ?>
