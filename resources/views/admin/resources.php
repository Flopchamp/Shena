<?php 
$resources = $resources ?? [
    'marketing_materials' => [],
    'training_documents' => [],
    'policy_documents' => [],
    'forms' => []
];
?>
<?php include_once __DIR__ . '/../layouts/admin-header.php'; ?>

<style>
    .resources-container {
        padding: 20px;
        max-width: 1400px;
        margin: 0 auto;
    }

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
    }

    .actions-bar {
        background: white;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .search-box {
        flex: 1;
        max-width: 400px;
    }

    .search-input {
        width: 100%;
        padding: 10px 16px;
        border: 1px solid #D1D5DB;
        border-radius: 8px;
        font-size: 14px;
    }

    .action-buttons {
        display: flex;
        gap: 12px;
    }

    .btn-action {
        padding: 10px 20px;
        border: none;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .btn-primary {
        background: linear-gradient(135deg, #7F3D9E 0%, #7C3AED 100%);
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(127, 61, 158, 0.3);
    }

    .btn-secondary {
        background: white;
        color: #374151;
        border: 1px solid #D1D5DB;
    }

    .btn-secondary:hover {
        background: #F9FAFB;
    }

    .resources-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 24px;
    }

    .resource-category {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .category-header {
        background: linear-gradient(135deg, #7F3D9E 0%, #7C3AED 100%);
        color: white;
        padding: 16px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .category-title {
        font-size: 16px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .category-count {
        background: rgba(255, 255, 255, 0.2);
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
    }

    .category-body {
        padding: 20px;
    }

    .resource-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px;
        border-radius: 8px;
        margin-bottom: 12px;
        transition: all 0.2s;
        cursor: pointer;
    }

    .resource-item:hover {
        background: #F9FAFB;
    }

    .resource-icon {
        width: 48px;
        height: 48px;
        border-radius: 8px;
        background: linear-gradient(135deg, #7F3D9E 0%, #7C3AED 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 20px;
    }

    .resource-info {
        flex: 1;
    }

    .resource-name {
        font-weight: 600;
        color: #1F2937;
        margin-bottom: 4px;
    }

    .resource-meta {
        font-size: 12px;
        color: #6B7280;
    }

    .resource-actions {
        display: flex;
        gap: 8px;
    }

    .icon-btn {
        width: 32px;
        height: 32px;
        border-radius: 6px;
        border: none;
        background: #F3F4F6;
        color: #374151;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }

    .icon-btn:hover {
        background: #E5E7EB;
    }

    .empty-category {
        text-align: center;
        padding: 40px 20px;
        color: #9CA3AF;
    }

    .empty-category i {
        font-size: 48px;
        margin-bottom: 12px;
        opacity: 0.3;
    }

    .stats-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 24px;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .stat-label {
        font-size: 13px;
        color: #6B7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }

    .stat-value {
        font-size: 32px;
        font-weight: 700;
        background: linear-gradient(135deg, #7F3D9E 0%, #B91C1C 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1000;
        align-items: center;
        justify-content: center;
    }

    .modal.active {
        display: flex;
    }

    .modal-content {
        background: white;
        border-radius: 12px;
        padding: 30px;
        max-width: 600px;
        width: 90%;
    }

    .modal-header {
        font-size: 20px;
        font-weight: 700;
        margin-bottom: 20px;
    }

    .form-group {
        margin-bottom: 20px;
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
        padding: 10px;
        border: 1px solid #D1D5DB;
        border-radius: 6px;
        font-size: 14px;
    }

    .form-textarea {
        min-height: 100px;
        resize: vertical;
    }

    .file-upload-area {
        border: 2px dashed #D1D5DB;
        border-radius: 8px;
        padding: 40px;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s;
    }

    .file-upload-area:hover {
        border-color: #7F3D9E;
        background: #F9FAFB;
    }

    .modal-actions {
        display: flex;
        gap: 10px;
        justify-content: flex-end;
        margin-top: 20px;
    }
</style>

<div class="resources-container">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Agent Resources</h1>
        <p class="page-subtitle">Manage marketing materials, documents, and training resources for agents</p>
    </div>

    <!-- Statistics -->
    <div class="stats-row">
        <div class="stat-card">
            <div class="stat-label">Total Resources</div>
            <div class="stat-value">
                <?php 
                $total = count($resources['marketing_materials']) + 
                         count($resources['training_documents']) + 
                         count($resources['policy_documents']) + 
                         count($resources['forms']);
                echo $total;
                ?>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Marketing Materials</div>
            <div class="stat-value"><?php echo count($resources['marketing_materials']); ?></div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Training Docs</div>
            <div class="stat-value"><?php echo count($resources['training_documents']); ?></div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Downloads (Month)</div>
            <div class="stat-value">0</div>
        </div>
    </div>

    <!-- Actions Bar -->
    <div class="actions-bar">
        <div class="search-box">
            <input type="text" class="search-input" placeholder="Search resources..." id="searchInput">
        </div>
        <div class="action-buttons">
            <button class="btn-action btn-secondary">
                <i class="fas fa-filter"></i>
                Filter
            </button>
            <button class="btn-action btn-primary" onclick="openUploadModal()">
                <i class="fas fa-upload"></i>
                Upload Resource
            </button>
        </div>
    </div>

    <!-- Resources Grid -->
    <div class="resources-grid">
        <!-- Marketing Materials -->
        <div class="resource-category">
            <div class="category-header">
                <div class="category-title">
                    <i class="fas fa-bullhorn"></i>
                    Marketing Materials
                </div>
                <span class="category-count"><?php echo count($resources['marketing_materials']); ?></span>
            </div>
            <div class="category-body">
                <?php if (!empty($resources['marketing_materials'])): ?>
                    <?php foreach ($resources['marketing_materials'] as $resource): ?>
                    <div class="resource-item">
                        <div class="resource-icon">
                            <i class="fas fa-file-pdf"></i>
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
                        <p>No marketing materials yet</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Training Documents -->
        <div class="resource-category">
            <div class="category-header">
                <div class="category-title">
                    <i class="fas fa-graduation-cap"></i>
                    Training Documents
                </div>
                <span class="category-count"><?php echo count($resources['training_documents']); ?></span>
            </div>
            <div class="category-body">
                <?php if (!empty($resources['training_documents'])): ?>
                    <?php foreach ($resources['training_documents'] as $resource): ?>
                    <div class="resource-item">
                        <div class="resource-icon">
                            <i class="fas fa-file-alt"></i>
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
                        <p>No training documents yet</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Policy Documents -->
        <div class="resource-category">
            <div class="category-header">
                <div class="category-title">
                    <i class="fas fa-file-contract"></i>
                    Policy Documents
                </div>
                <span class="category-count"><?php echo count($resources['policy_documents']); ?></span>
            </div>
            <div class="category-body">
                <?php if (!empty($resources['policy_documents'])): ?>
                    <?php foreach ($resources['policy_documents'] as $resource): ?>
                    <div class="resource-item">
                        <div class="resource-icon">
                            <i class="fas fa-file-pdf"></i>
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
                        <p>No policy documents yet</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Forms & Templates -->
        <div class="resource-category">
            <div class="category-header">
                <div class="category-title">
                    <i class="fas fa-wpforms"></i>
                    Forms & Templates
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
        <h2 class="modal-header">Upload Resource</h2>
        <form id="uploadForm" method="POST" enctype="multipart/form-data" action="/admin/agents/resources/upload">
            <div class="form-group">
                <label class="form-label">Resource Name</label>
                <input type="text" name="name" class="form-input" placeholder="Enter resource name" required>
            </div>
            <div class="form-group">
                <label class="form-label">Category</label>
                <select name="category" class="form-select" required>
                    <option value="">Select Category</option>
                    <option value="marketing_materials">Marketing Materials</option>
                    <option value="training_documents">Training Documents</option>
                    <option value="policy_documents">Policy Documents</option>
                    <option value="forms">Forms & Templates</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-textarea" placeholder="Brief description of the resource"></textarea>
            </div>
            <div class="form-group">
                <label class="form-label">File</label>
                <div class="file-upload-area" onclick="document.getElementById('fileInput').click()">
                    <i class="fas fa-cloud-upload-alt" style="font-size: 48px; color: #7F3D9E; margin-bottom: 12px;"></i>
                    <p style="color: #374151; font-weight: 600; margin-bottom: 4px;">Click to upload or drag and drop</p>
                    <p style="color: #9CA3AF; font-size: 13px;">PDF, DOC, DOCX, XLS, XLSX, PNG, JPG (Max 10MB)</p>
                    <input type="file" id="fileInput" name="file" style="display: none;" required>
                </div>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn-action btn-secondary" onclick="closeUploadModal()">Cancel</button>
                <button type="submit" class="btn-action btn-primary">Upload</button>
            </div>
        </form>
    </div>
</div>

<script>
function openUploadModal() {
    document.getElementById('uploadModal').classList.add('active');
}

function closeUploadModal() {
    document.getElementById('uploadModal').classList.remove('active');
}

// Search functionality
document.getElementById('searchInput').addEventListener('input', function(e) {
    const search = e.target.value.toLowerCase();
    document.querySelectorAll('.resource-item').forEach(item => {
        const name = item.querySelector('.resource-name').textContent.toLowerCase();
        item.style.display = name.includes(search) ? '' : 'none';
    });
});

// Close modal on outside click
document.getElementById('uploadModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeUploadModal();
    }
});

// File input display
document.getElementById('fileInput').addEventListener('change', function(e) {
    if (e.target.files.length > 0) {
        const fileName = e.target.files[0].name;
        document.querySelector('.file-upload-area p').textContent = `Selected: ${fileName}`;
    }
});
</script>

<?php include_once __DIR__ . '/../layouts/admin-footer.php'; ?>
