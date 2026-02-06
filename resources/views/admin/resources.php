<?php 
// Ensure all categories exist with default empty arrays
$resources = $resources ?? [];
$resources['marketing_materials'] = $resources['marketing_materials'] ?? [];
$resources['training_documents'] = $resources['training_documents'] ?? [];
$resources['policy_documents'] = $resources['policy_documents'] ?? [];
$resources['forms'] = $resources['forms'] ?? [];
$resources['videos'] = $resources['videos'] ?? [];
$resources['presentations'] = $resources['presentations'] ?? [];

// Calculate statistics
$totalResources = count($resources['marketing_materials']) + 
                  count($resources['training_documents']) + 
                  count($resources['policy_documents']) + 
                  count($resources['forms']) +
                  count($resources['videos']) +
                  count($resources['presentations']);
?>
<?php include_once __DIR__ . '/../layouts/admin-header.php'; ?>

<style>
    .resources-container {
        padding: 20px;
        max-width: 1600px;
        margin: 0 auto;
    }

    .page-header {
        margin-bottom: 32px;
    }

    .page-title {
        font-family: 'Playfair Display', serif;
        font-size: 32px;
        font-weight: 700;
        color: #1F2937;
        margin: 0 0 8px 0;
    }

    .page-subtitle {
        font-size: 15px;
        color: #6B7280;
        line-height: 1.6;
    }

    /* Upload Zone - Prominent Feature */
    .upload-showcase {
        background: linear-gradient(135deg, #7F3D9E 0%, #5B21B6 100%);
        border-radius: 16px;
        padding: 40px;
        margin-bottom: 32px;
        color: white;
        position: relative;
        overflow: hidden;
    }

    .upload-showcase::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 400px;
        height: 400px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }

    .upload-content {
        position: relative;
        z-index: 1;
        display: grid;
        grid-template-columns: 1fr 400px;
        gap: 40px;
        align-items: center;
    }

    .upload-info h2 {
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 12px;
    }

    .upload-info p {
        font-size: 16px;
        opacity: 0.9;
        margin-bottom: 24px;
        line-height: 1.6;
    }

    .upload-stats {
        display: flex;
        gap: 32px;
    }

    .upload-stat {
        display: flex;
        flex-direction: column;
    }

    .upload-stat-value {
        font-size: 32px;
        font-weight: 700;
    }

    .upload-stat-label {
        font-size: 13px;
        opacity: 0.8;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .upload-zone-container {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        border: 2px dashed rgba(255, 255, 255, 0.4);
        border-radius: 12px;
        padding: 32px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s;
    }

    .upload-zone-container:hover {
        background: rgba(255, 255, 255, 0.2);
        border-color: rgba(255, 255, 255, 0.6);
        transform: translateY(-2px);
    }

    .upload-icon {
        font-size: 48px;
        margin-bottom: 16px;
        opacity: 0.9;
    }

    .upload-text {
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 8px;
    }

    .upload-hint {
        font-size: 13px;
        opacity: 0.8;
    }

    /* Quick Actions Bar */
    .quick-actions {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        margin-bottom: 32px;
    }

    .quick-action-btn {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 12px 20px;
        background: white;
        border: 1px solid #E5E7EB;
        border-radius: 10px;
        color: #374151;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }

    .quick-action-btn:hover {
        background: #F9FAFB;
        border-color: #7F3D9E;
        color: #7F3D9E;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(127, 61, 158, 0.15);
    }

    .quick-action-btn.primary {
        background: linear-gradient(135deg, #7F3D9E 0%, #5B21B6 100%);
        color: white;
        border: none;
    }

    .quick-action-btn.primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(127, 61, 158, 0.4);
    }

    /* Search Bar */
    .search-section {
        background: white;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 32px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    .search-input {
        width: 100%;
        padding: 14px 20px 14px 48px;
        border: 2px solid #E5E7EB;
        border-radius: 10px;
        font-size: 15px;
        transition: all 0.2s;
        background: url('data:image/svg+xml,<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"20\" height=\"20\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"%239CA3AF\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"><circle cx=\"11\" cy=\"11\" r=\"8\"></circle><path d=\"m21 21-4.35-4.35\"></path></svg>') no-repeat 16px center;
    }

    .search-input:focus {
        outline: none;
        border-color: #7F3D9E;
        box-shadow: 0 0 0 3px rgba(127, 61, 158, 0.1);
    }

    /* Resources Grid Layout */
    .resources-layout {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 24px;
        margin-bottom: 40px;
    }

    .resource-category {
        background: white;
        border-radius: 16px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        overflow: hidden;
        transition: all 0.3s;
    }

    .resource-category:hover {
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
        transform: translateY(-4px);
    }

    .category-header {
        background: linear-gradient(135deg, #7F3D9E 0%, #5B21B6 100%);
        color: white;
        padding: 20px 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .category-title {
        font-size: 17px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .category-title i {
        font-size: 20px;
    }

    .category-count {
        background: rgba(255, 255, 255, 0.25);
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 700;
    }

    .category-body {
        padding: 24px;
        min-height: 200px;
    }

    .resource-item {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 16px;
        border-radius: 10px;
        margin-bottom: 12px;
        transition: all 0.2s;
        cursor: pointer;
        border: 1px solid transparent;
    }

    .resource-item:hover {
        background: #F9FAFB;
        border-color: #E5E7EB;
    }

    .resource-icon {
        width: 56px;
        height: 56px;
        border-radius: 12px;
        background: linear-gradient(135deg, #7F3D9E 0%, #5B21B6 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 24px;
        flex-shrink: 0;
    }

    .resource-info {
        flex: 1;
        min-width: 0;
    }

    .resource-name {
        font-weight: 600;
        color: #1F2937;
        margin-bottom: 6px;
        font-size: 15px;
    }

    .resource-meta {
        font-size: 13px;
        color: #6B7280;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .resource-actions {
        display: flex;
        gap: 8px;
    }

    .icon-btn {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        border: none;
        background: #F3F4F6;
        color: #6B7280;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
        font-size: 14px;
    }

    .icon-btn:hover {
        background: #7F3D9E;
        color: white;
    }

    .empty-category {
        text-align: center;
        padding: 60px 20px;
        color: #9CA3AF;
    }

    .empty-category i {
        font-size: 56px;
        margin-bottom: 16px;
        opacity: 0.3;
    }

    .empty-category p {
        font-size: 15px;
        margin-bottom: 12px;
    }

    .empty-action {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        background: linear-gradient(135deg, #7F3D9E 0%, #5B21B6 100%);
        color: white;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        margin-top: 12px;
        transition: all 0.2s;
    }

    .empty-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(127, 61, 158, 0.4);
    }

    /* Modal Styles */
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.6);
        z-index: 1000;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(4px);
    }

    .modal.active {
        display: flex;
    }

    .modal-content {
        background: white;
        border-radius: 16px;
        padding: 40px;
        max-width: 650px;
        width: 90%;
        max-height: 90vh;
        overflow-y: auto;
    }

    .modal-header {
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 24px;
        color: #1F2937;
    }

    .form-group {
        margin-bottom: 24px;
    }

    .form-label {
        display: block;
        font-size: 14px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 8px;
    }

    .form-input, .form-select, .form-textarea {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid #E5E7EB;
        border-radius: 10px;
        font-size: 15px;
        transition: all 0.2s;
    }

    .form-input:focus, .form-select:focus, .form-textarea:focus {
        outline: none;
        border-color: #7F3D9E;
        box-shadow: 0 0 0 3px rgba(127, 61, 158, 0.1);
    }

    .form-textarea {
        min-height: 100px;
        resize: vertical;
        font-family: inherit;
    }

    .file-upload-area {
        border: 2px dashed #D1D5DB;
        border-radius: 12px;
        padding: 48px 32px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s;
        background: #F9FAFB;
    }

    .file-upload-area:hover {
        border-color: #7F3D9E;
        background: linear-gradient(135deg, rgba(127, 61, 158, 0.05) 0%, rgba(91, 33, 182, 0.05) 100%);
    }

    .file-upload-area.drag-over {
        border-color: #7F3D9E;
        background: linear-gradient(135deg, rgba(127, 61, 158, 0.1) 0%, rgba(91, 33, 182, 0.1) 100%);
    }

    .upload-icon-large {
        font-size: 56px;
        color: #7F3D9E;
        margin-bottom: 16px;
    }

    .upload-text-large {
        color: #374151;
        font-weight: 600;
        font-size: 16px;
        margin-bottom: 8px;
    }

    .upload-hint-large {
        color: #9CA3AF;
        font-size: 14px;
    }

    .selected-file {
        margin-top: 16px;
        padding: 12px 16px;
        background: white;
        border-radius: 8px;
        display: flex;
        align-items: center;
        gap: 12px;
        border: 1px solid #E5E7EB;
    }

    .selected-file i {
        color: #7F3D9E;
        font-size: 20px;
    }

    .selected-file-name {
        flex: 1;
        font-weight: 600;
        color: #1F2937;
    }

    .modal-actions {
        display: flex;
        gap: 12px;
        justify-content: flex-end;
        margin-top: 32px;
    }

    .btn-modal {
        padding: 12px 28px;
        border-radius: 10px;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        border: none;
    }

    .btn-cancel {
        background: #F3F4F6;
        color: #374151;
    }

    .btn-cancel:hover {
        background: #E5E7EB;
    }

    .btn-submit {
        background: linear-gradient(135deg, #7F3D9E 0%, #5B21B6 100%);
        color: white;
    }

    .btn-submit:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(127, 61, 158, 0.4);
    }

    @media (max-width: 1024px) {
        .upload-content {
            grid-template-columns: 1fr;
        }

        .upload-stats {
            justify-content: space-around;
        }

        .resources-layout {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .resources-container {
            padding: 16px;
        }

        .upload-showcase {
            padding: 24px;
        }

        .upload-info h2 {
            font-size: 22px;
        }

        .page-title {
            font-size: 24px;
        }
    }
</style>

<div class="resources-container">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Resource Library</h1>
        <p class="page-subtitle">
            Centralized hub for all agent resources, training materials, marketing content, and essential documentation. 
            Upload, organize, and share resources with your agent network.
        </p>
    </div>

    <!-- Upload Showcase Zone -->
    <div class="upload-showcase">
        <div class="upload-content">
            <div class="upload-info">
                <h2>Quick Upload Resources</h2>
                <p>
                    Share important documents, training materials, marketing assets, and policy updates 
                    with your agents instantly. Drag and drop files or click to browse.
                </p>
                <div class="upload-stats">
                    <div class="upload-stat">
                        <div class="upload-stat-value"><?= $totalResources ?></div>
                        <div class="upload-stat-label">Total Resources</div>
                    </div>
                    <div class="upload-stat">
                        <div class="upload-stat-value">6</div>
                        <div class="upload-stat-label">Categories</div>
                    </div>
                    <div class="upload-stat">
                        <div class="upload-stat-value">10MB</div>
                        <div class="upload-stat-label">Max File Size</div>
                    </div>
                </div>
            </div>
            <div class="upload-zone-container" onclick="openUploadModal()">
                <div class="upload-icon">
                    <i class="fas fa-cloud-upload-alt"></i>
                </div>
                <div class="upload-text">Click or Drag Files Here</div>
                <div class="upload-hint">PDF, DOC, DOCX, XLS, PPT, Images & Videos</div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="quick-actions">
        <button class="quick-action-btn primary" onclick="openUploadModal()">
            <i class="fas fa-upload"></i>
            Upload New Resource
        </button>
        <button class="quick-action-btn" onclick="window.print()">
            <i class="fas fa-print"></i>
            Print Catalog
        </button>
        <button class="quick-action-btn" onclick="exportResources()">
            <i class="fas fa-download"></i>
            Export List
        </button>
        <button class="quick-action-btn" onclick="bulkDelete()">
            <i class="fas fa-trash-alt"></i>
            Bulk Manage
        </button>
    </div>

    <!-- Search Bar -->
    <div class="search-section">
        <input type="text" class="search-input" placeholder="Search by filename, category, or description..." id="searchInput">
    </div>

    <!-- Resources Grid -->
    <div class="resources-layout">
        <!-- Marketing Materials -->
        <div class="resource-category" data-category="marketing">
            <div class="category-header">
                <div class="category-title">
                    <i class="fas fa-bullhorn"></i>
                    Marketing Materials
                </div>
                <span class="category-count"><?= count($resources['marketing_materials']) ?></span>
            </div>
            <div class="category-body">
                <?php if (!empty($resources['marketing_materials'])): ?>
                    <?php foreach ($resources['marketing_materials'] as $resource): ?>
                    <div class="resource-item">
                        <div class="resource-icon">
                            <i class="fas fa-file-image"></i>
                        </div>
                        <div class="resource-info">
                            <div class="resource-name"><?= htmlspecialchars($resource['name']) ?></div>
                            <div class="resource-meta">
                                <span><i class="fas fa-file"></i> <?= $resource['size'] ?></span>
                                <span><i class="fas fa-calendar"></i> <?= $resource['date'] ?></span>
                            </div>
                        </div>
                        <div class="resource-actions">
                            <button class="icon-btn" title="Download" onclick="downloadResource('<?= $resource['id'] ?>')">
                                <i class="fas fa-download"></i>
                            </button>
                            <button class="icon-btn" title="Share" onclick="shareResource('<?= $resource['id'] ?>')">
                                <i class="fas fa-share-alt"></i>
                            </button>
                            <button class="icon-btn" title="Delete" onclick="deleteResource('<?= $resource['id'] ?>')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-category">
                        <i class="fas fa-image"></i>
                        <p>No marketing materials yet</p>
                        <button class="empty-action" onclick="openUploadModal('marketing_materials')">
                            <i class="fas fa-plus"></i>
                            Add First Resource
                        </button>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Training Documents -->
        <div class="resource-category" data-category="training">
            <div class="category-header">
                <div class="category-title">
                    <i class="fas fa-graduation-cap"></i>
                    Training Documents
                </div>
                <span class="category-count"><?= count($resources['training_documents']) ?></span>
            </div>
            <div class="category-body">
                <?php if (!empty($resources['training_documents'])): ?>
                    <?php foreach ($resources['training_documents'] as $resource): ?>
                    <div class="resource-item">
                        <div class="resource-icon">
                            <i class="fas fa-book"></i>
                        </div>
                        <div class="resource-info">
                            <div class="resource-name"><?= htmlspecialchars($resource['name']) ?></div>
                            <div class="resource-meta">
                                <span><i class="fas fa-file"></i> <?= $resource['size'] ?></span>
                                <span><i class="fas fa-calendar"></i> <?= $resource['date'] ?></span>
                            </div>
                        </div>
                        <div class="resource-actions">
                            <button class="icon-btn" title="Download" onclick="downloadResource('<?= $resource['id'] ?>')">
                                <i class="fas fa-download"></i>
                            </button>
                            <button class="icon-btn" title="Share" onclick="shareResource('<?= $resource['id'] ?>')">
                                <i class="fas fa-share-alt"></i>
                            </button>
                            <button class="icon-btn" title="Delete" onclick="deleteResource('<?= $resource['id'] ?>')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-category">
                        <i class="fas fa-book-open"></i>
                        <p>No training documents yet</p>
                        <button class="empty-action" onclick="openUploadModal('training_documents')">
                            <i class="fas fa-plus"></i>
                            Add First Resource
                        </button>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Policy Documents -->
        <div class="resource-category" data-category="policy">
            <div class="category-header">
                <div class="category-title">
                    <i class="fas fa-file-contract"></i>
                    Policy Documents
                </div>
                <span class="category-count"><?= count($resources['policy_documents']) ?></span>
            </div>
            <div class="category-body">
                <?php if (!empty($resources['policy_documents'])): ?>
                    <?php foreach ($resources['policy_documents'] as $resource): ?>
                    <div class="resource-item">
                        <div class="resource-icon">
                            <i class="fas fa-file-pdf"></i>
                        </div>
                        <div class="resource-info">
                            <div class="resource-name"><?= htmlspecialchars($resource['name']) ?></div>
                            <div class="resource-meta">
                                <span><i class="fas fa-file"></i> <?= $resource['size'] ?></span>
                                <span><i class="fas fa-calendar"></i> <?= $resource['date'] ?></span>
                            </div>
                        </div>
                        <div class="resource-actions">
                            <button class="icon-btn" title="Download" onclick="downloadResource('<?= $resource['id'] ?>')">
                                <i class="fas fa-download"></i>
                            </button>
                            <button class="icon-btn" title="Share" onclick="shareResource('<?= $resource['id'] ?>')">
                                <i class="fas fa-share-alt"></i>
                            </button>
                            <button class="icon-btn" title="Delete" onclick="deleteResource('<?= $resource['id'] ?>')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-category">
                        <i class="fas fa-file-contract"></i>
                        <p>No policy documents yet</p>
                        <button class="empty-action" onclick="openUploadModal('policy_documents')">
                            <i class="fas fa-plus"></i>
                            Add First Resource
                        </button>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Forms & Templates -->
        <div class="resource-category" data-category="forms">
            <div class="category-header">
                <div class="category-title">
                    <i class="fas fa-wpforms"></i>
                    Forms & Templates
                </div>
                <span class="category-count"><?= count($resources['forms']) ?></span>
            </div>
            <div class="category-body">
                <?php if (!empty($resources['forms'])): ?>
                    <?php foreach ($resources['forms'] as $resource): ?>
                    <div class="resource-item">
                        <div class="resource-icon">
                            <i class="fas fa-file-word"></i>
                        </div>
                        <div class="resource-info">
                            <div class="resource-name"><?= htmlspecialchars($resource['name']) ?></div>
                            <div class="resource-meta">
                                <span><i class="fas fa-file"></i> <?= $resource['size'] ?></span>
                                <span><i class="fas fa-calendar"></i> <?= $resource['date'] ?></span>
                            </div>
                        </div>
                        <div class="resource-actions">
                            <button class="icon-btn" title="Download" onclick="downloadResource('<?= $resource['id'] ?>')">
                                <i class="fas fa-download"></i>
                            </button>
                            <button class="icon-btn" title="Share" onclick="shareResource('<?= $resource['id'] ?>')">
                                <i class="fas fa-share-alt"></i>
                            </button>
                            <button class="icon-btn" title="Delete" onclick="deleteResource('<?= $resource['id'] ?>')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-category">
                        <i class="fas fa-file-alt"></i>
                        <p>No forms or templates yet</p>
                        <button class="empty-action" onclick="openUploadModal('forms')">
                            <i class="fas fa-plus"></i>
                            Add First Resource
                        </button>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Video Tutorials -->
        <div class="resource-category" data-category="videos">
            <div class="category-header">
                <div class="category-title">
                    <i class="fas fa-video"></i>
                    Video Tutorials
                </div>
                <span class="category-count"><?= count($resources['videos']) ?></span>
            </div>
            <div class="category-body">
                <?php if (!empty($resources['videos'])): ?>
                    <?php foreach ($resources['videos'] as $resource): ?>
                    <div class="resource-item">
                        <div class="resource-icon">
                            <i class="fas fa-play-circle"></i>
                        </div>
                        <div class="resource-info">
                            <div class="resource-name"><?= htmlspecialchars($resource['name']) ?></div>
                            <div class="resource-meta">
                                <span><i class="fas fa-file"></i> <?= $resource['size'] ?></span>
                                <span><i class="fas fa-calendar"></i> <?= $resource['date'] ?></span>
                            </div>
                        </div>
                        <div class="resource-actions">
                            <button class="icon-btn" title="Play" onclick="playVideo('<?= $resource['id'] ?>')">
                                <i class="fas fa-play"></i>
                            </button>
                            <button class="icon-btn" title="Download" onclick="downloadResource('<?= $resource['id'] ?>')">
                                <i class="fas fa-download"></i>
                            </button>
                            <button class="icon-btn" title="Delete" onclick="deleteResource('<?= $resource['id'] ?>')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-category">
                        <i class="fas fa-video"></i>
                        <p>No video tutorials yet</p>
                        <button class="empty-action" onclick="openUploadModal('videos')">
                            <i class="fas fa-plus"></i>
                            Add First Resource
                        </button>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Presentations -->
        <div class="resource-category" data-category="presentations">
            <div class="category-header">
                <div class="category-title">
                    <i class="fas fa-presentation"></i>
                    Presentations
                </div>
                <span class="category-count"><?= count($resources['presentations']) ?></span>
            </div>
            <div class="category-body">
                <?php if (!empty($resources['presentations'])): ?>
                    <?php foreach ($resources['presentations'] as $resource): ?>
                    <div class="resource-item">
                        <div class="resource-icon">
                            <i class="fas fa-file-powerpoint"></i>
                        </div>
                        <div class="resource-info">
                            <div class="resource-name"><?= htmlspecialchars($resource['name']) ?></div>
                            <div class="resource-meta">
                                <span><i class="fas fa-file"></i> <?= $resource['size'] ?></span>
                                <span><i class="fas fa-calendar"></i> <?= $resource['date'] ?></span>
                            </div>
                        </div>
                        <div class="resource-actions">
                            <button class="icon-btn" title="Download" onclick="downloadResource('<?= $resource['id'] ?>')">
                                <i class="fas fa-download"></i>
                            </button>
                            <button class="icon-btn" title="Share" onclick="shareResource('<?= $resource['id'] ?>')">
                                <i class="fas fa-share-alt"></i>
                            </button>
                            <button class="icon-btn" title="Delete" onclick="deleteResource('<?= $resource['id'] ?>')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-category">
                        <i class="fas fa-file-powerpoint"></i>
                        <p>No presentations yet</p>
                        <button class="empty-action" onclick="openUploadModal('presentations')">
                            <i class="fas fa-plus"></i>
                            Add First Resource
                        </button>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
                </div>
                <span class="category-count"><?php echo count($resources['forms']); ?></span>
            </div>
            <div class="category-body">
                <?php if (!empty($resources['forms'])): ?>
                    <?php foreach ($resources['forms'] as $resource): ?>
                    <div class="resource-item">
                        <div class="resource-icon">
                            <i class="fas fa-file-word"></i>
                        </div>
                        <div class="resource-info">
                            <div class="resource-name"><?php echo htmlspecialchars($resource['name']); ?></div>
                            <div class="resource-meta"><?php echo $resource['size']; ?> • Uploaded <?php echo $resource['date']; ?></div>
                        </div>
                        <div class="resource-actions">
                            <button class="icon-btn" title="Download">
                                <i class="fas fa-download"></i>
                            </button>
                            <button class="icon-btn" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-category">
                        <i class="fas fa-folder-open"></i>
                        <p>No forms or templates yet</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Upload Modal -->
<div id="uploadModal" class="modal">
    <div class="modal-content">
        <h2 class="modal-header">
            <i class="fas fa-cloud-upload-alt" style="color: #7F3D9E; margin-right: 8px;"></i>
            Upload New Resource
        </h2>
        <form id="uploadForm" method="POST" enctype="multipart/form-data" action="/admin/agents/resources/upload">
            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-tag"></i> Resource Name *
                </label>
                <input type="text" name="name" class="form-input" placeholder="Enter descriptive resource name" required>
            </div>
            
            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-folder"></i> Category *
                </label>
                <select name="category" class="form-select" id="categorySelect" required>
                    <option value="">Select Category</option>
                    <option value="marketing_materials">📢 Marketing Materials</option>
                    <option value="training_documents">🎓 Training Documents</option>
                    <option value="policy_documents">📋 Policy Documents</option>
                    <option value="forms">📝 Forms & Templates</option>
                    <option value="videos">🎥 Video Tutorials</option>
                    <option value="presentations">📊 Presentations</option>
                </select>
            </div>
            
            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-align-left"></i> Description
                </label>
                <textarea name="description" class="form-textarea" placeholder="Brief description of the resource (optional)" rows="3"></textarea>
            </div>
            
            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-file-upload"></i> Upload File *
                </label>
                <div class="file-upload-area" id="fileUploadArea">
                    <div class="upload-icon-large">
                        <i class="fas fa-cloud-upload-alt"></i>
                    </div>
                    <div class="upload-text-large">Drag and drop file here</div>
                    <div class="upload-hint-large">or click to browse • PDF, DOC, DOCX, XLS, XLSX, PPT, PNG, JPG, MP4 (Max 10MB)</div>
                    <input type="file" id="fileInput" name="file" style="display: none;" required accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.png,.jpg,.jpeg,.mp4,.avi">
                </div>
                <div id="selectedFile" class="selected-file" style="display: none;">
                    <i class="fas fa-file"></i>
                    <span class="selected-file-name"></span>
                    <button type="button" onclick="clearFile()" style="margin-left: auto; background: none; border: none; color: #EF4444; cursor: pointer;">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            
            <div class="modal-actions">
                <button type="button" class="btn-modal btn-cancel" onclick="closeUploadModal()">
                    <i class="fas fa-times"></i> Cancel
                </button>
                <button type="submit" class="btn-modal btn-submit">
                    <i class="fas fa-check"></i> Upload Resource
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Modal Functions
function openUploadModal(category = '') {
    document.getElementById('uploadModal').classList.add('active');
    if (category) {
        document.getElementById('categorySelect').value = category;
    }
}

function closeUploadModal() {
    document.getElementById('uploadModal').classList.remove('active');
    document.getElementById('uploadForm').reset();
    clearFile();
}

// File Upload Handling
const fileUploadArea = document.getElementById('fileUploadArea');
const fileInput = document.getElementById('fileInput');
const selectedFile = document.getElementById('selectedFile');

// Click to browse
fileUploadArea.addEventListener('click', () => fileInput.click());

// File selection
fileInput.addEventListener('change', function(e) {
    if (e.target.files.length > 0) {
        displaySelectedFile(e.target.files[0]);
    }
});

// Drag and drop
fileUploadArea.addEventListener('dragover', function(e) {
    e.preventDefault();
    e.stopPropagation();
    this.classList.add('drag-over');
});

fileUploadArea.addEventListener('dragleave', function(e) {
    e.preventDefault();
    e.stopPropagation();
    this.classList.remove('drag-over');
});

fileUploadArea.addEventListener('drop', function(e) {
    e.preventDefault();
    e.stopPropagation();
    this.classList.remove('drag-over');
    
    const files = e.dataTransfer.files;
    if (files.length > 0) {
        fileInput.files = files;
        displaySelectedFile(files[0]);
    }
});

function displaySelectedFile(file) {
    const fileName = file.name;
    const fileSize = (file.size / 1024 / 1024).toFixed(2) + ' MB';
    
    selectedFile.style.display = 'flex';
    selectedFile.querySelector('.selected-file-name').textContent = `${fileName} (${fileSize})`;
    fileUploadArea.style.display = 'none';
}

function clearFile() {
    fileInput.value = '';
    selectedFile.style.display = 'none';
    fileUploadArea.style.display = 'block';
}

// Search Functionality
document.getElementById('searchInput').addEventListener('input', function(e) {
    const search = e.target.value.toLowerCase();
    const categories = document.querySelectorAll('.resource-category');
    
    categories.forEach(category => {
        const items = category.querySelectorAll('.resource-item');
        let hasVisibleItems = false;
        
        items.forEach(item => {
            const name = item.querySelector('.resource-name').textContent.toLowerCase();
            if (name.includes(search)) {
                item.style.display = '';
                hasVisibleItems = true;
            } else {
                item.style.display = 'none';
            }
        });
        
        // Show/hide category based on visible items
        if (!hasVisibleItems && search) {
            category.style.display = 'none';
        } else {
            category.style.display = '';
        }
    });
});

// Resource Actions
function downloadResource(id) {
    // Implement download logic
    window.location.href = `/admin/agents/resources/download/${id}`;
}

function shareResource(id) {
    ShenaApp.alert('Share feature coming soon!', 'info', 'Share Resource');
}

function deleteResource(id) {
    ShenaApp.confirmAction(
        'Are you sure you want to delete this resource? This action cannot be undone.',
        function() {
            // Proceed with deletion
            window.location.href = `/admin/agents/resources/delete/${id}`;
        },
        null,
        { type: 'danger', confirmText: 'Delete', title: 'Delete Resource' }
    );
}

function playVideo(id) {
    // Implement video player
    window.open(`/admin/agents/resources/play/${id}`, '_blank');
}

function exportResources() {
    ShenaApp.alert('Exporting resource catalog...', 'info', 'Export');
    setTimeout(() => {
        window.location.href = '/admin/agents/resources/export';
    }, 500);
}

function bulkDelete() {
    ShenaApp.alert('Bulk management feature coming soon!', 'info', 'Bulk Manage');
}

// Close modal on outside click
document.getElementById('uploadModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeUploadModal();
    }
});

// Form submission
document.getElementById('uploadForm').addEventListener('submit', function(e) {
    const fileInput = document.getElementById('fileInput');
    if (fileInput.files.length === 0) {
        e.preventDefault();
        ShenaApp.alert('Please select a file to upload', 'warning', 'File Required');
        return false;
    }
    
    // Check file size (10MB limit)
    if (fileInput.files[0].size > 10 * 1024 * 1024) {
        e.preventDefault();
        ShenaApp.alert('File size must be less than 10MB', 'warning', 'File Too Large');
        return false;
    }
});
</script>

<?php include_once __DIR__ . '/../layouts/admin-footer.php'; ?>
