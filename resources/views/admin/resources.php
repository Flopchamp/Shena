<?php
/**
 * Admin Resources View - Modern UI
 * 
 * @package Shena\Views\Admin
 */
$resources = $resources ?? [];
$stats = $stats ?? ['total' => 0, 'marketing' => 0, 'training' => 0, 'forms' => 0, 'policy' => 0, 'other' => 0];
$category = $category ?? 'all';
?>
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

            font-size: 28px;
            color: #1f2937;
            margin: 0;
        } 
        
        .header-actions {
            display: flex;
            gap: 10px;
        }
        
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
        }
        
        .btn-primary {
            background: #3b82f6;
            color: white;
        }
        
        .btn-primary:hover {
            background: #2563eb;
        }
        
        .btn-secondary {
            background: #6b7280;
            color: white;
        }
        
        .btn-secondary:hover {
            background: #4b5563;
        }
        
        .btn-danger {
            background: #ef4444;
            color: white;
        }
        
        .btn-danger:hover {
            background: #dc2626;
        }
        
        .btn-sm {
            padding: 6px 12px;
            font-size: 12px;
        }
        
        .filter-bar {
            display: flex;
            gap: 15px;
            margin-bottom: 25px;
            padding: 15px;
            background: #f9fafb;
            border-radius: 8px;
        }
        
        .filter-bar select {
            padding: 8px 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
        }
        
        .category-section {
            margin-bottom: 40px;
        }
        
        .category-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e5e7eb;
        }
        
        .category-header h2 {
            font-size: 20px;
            color: #374151;
            margin: 0;
        }
        
        .category-header i {
            font-size: 24px;
            color: #3b82f6;
        }
        
        .resources-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }
        
        .resource-card {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 20px;
            transition: box-shadow 0.2s;
        }
        
        .resource-card:hover {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        
        .resource-icon {
            width: 50px;
            height: 50px;
            background: #eff6ff;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
        }
        
        .resource-icon i {
            font-size: 24px;
            color: #3b82f6;
        }
        
        .resource-title {
            font-size: 16px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 8px;
        }
        
        .resource-description {
            font-size: 14px;
            color: #6b7280;
            margin-bottom: 15px;
            line-height: 1.5;
        }
        
        .resource-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            font-size: 12px;
            color: #9ca3af;
            margin-bottom: 15px;
        }
        
        .resource-meta span {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .resource-actions {
            display: flex;
            gap: 10px;
        }
        
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #6b7280;
        }
        
        .empty-state i {
            font-size: 48px;
            margin-bottom: 20px;
            color: #d1d5db;
        }
        
        .empty-state h3 {
            font-size: 18px;
            margin-bottom: 10px;
        }
        
        /* Modal Styles */
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
            border-radius: 8px;
            width: 90%;
            max-width: 600px;
            max-height: 90vh;
            overflow-y: auto;
        }
        
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .modal-header h2 {
            margin: 0;
            font-size: 20px;
        }
        
        .modal-close {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: #6b7280;
        }
        
        .modal-body {
            padding: 20px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #374151;
        }
        
        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
        }
        
        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }
        
        .file-input-wrapper {
            position: relative;
        }
        
        .file-input-wrapper input[type="file"] {
            position: absolute;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }
        
        .file-input-label {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 15px;
            border: 2px dashed #d1d5db;
            border-radius: 6px;
            cursor: pointer;
            transition: border-color 0.2s;
        }
        
        .file-input-label:hover {
            border-color: #3b82f6;
        }
        
        .file-input-label i {
            font-size: 24px;
            color: #6b7280;
        }
        
        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            padding: 20px;
            border-top: 1px solid #e5e7eb;
        }
        
        .alert {
            padding: 12px 16px;
            border-radius: 6px;
            margin-bottom: 20px;
        }
        
        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }
        
        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }
        
        .stats-bar {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
        }
        
        .stat-value {
            font-size: 32px;
            font-weight: 700;
            color: #3b82f6;
        }
        
        .stat-label {
            font-size: 14px;
            color: #6b7280;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <?php include VIEWS_PATH . '/layouts/admin-header.php'; ?>
    
    <div class="resources-container">
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>
        
        <div class="page-header">
            <h1><i class="fas fa-folder-open"></i> Agent Resources</h1>
            <div class="header-actions">
                <button class="btn btn-primary" onclick="openUploadModal()">
                    <i class="fas fa-upload"></i> Upload Resource
                </button>
                <a href="/admin/agents/resources/export" class="btn btn-secondary">
                    <i class="fas fa-download"></i> Export CSV
                </a>
            </div>
        </div>
        
        <?php
        $totalResources = 0;
        foreach ($resources as $category => $items) {
            $totalResources += count($items);
        }
        ?>
        
        <div class="stats-bar">
            <div class="stat-card">
                <div class="stat-value"><?php echo $totalResources; ?></div>
                <div class="stat-label">Total Resources</div>
            </div>
            <div class="stat-card">
                <div class="stat-value"><?php echo count($resources['marketing_materials'] ?? []); ?></div>
                <div class="stat-label">Marketing Materials</div>
            </div>
            <div class="stat-card">
                <div class="stat-value"><?php echo count($resources['training_documents'] ?? []); ?></div>
                <div class="stat-label">Training Documents</div>
            </div>
            <div class="stat-card">
                <div class="stat-value"><?php echo count($resources['forms'] ?? []); ?></div>
                <div class="stat-label">Forms</div>
            </div>
        </div>
        
        <div class="filter-bar">
            <form method="GET" action="/admin/agents/resources">
                <select name="category" onchange="this.form.submit()">
                    <option value="all" <?php echo ($categoryFilter ?? 'all') === 'all' ? 'selected' : ''; ?>>All Categories</option>
                    <option value="marketing_materials" <?php echo ($categoryFilter ?? '') === 'marketing_materials' ? 'selected' : ''; ?>>Marketing Materials</option>
                    <option value="training_documents" <?php echo ($categoryFilter ?? '') === 'training_documents' ? 'selected' : ''; ?>>Training Documents</option>
                    <option value="policy_documents" <?php echo ($categoryFilter ?? '') === 'policy_documents' ? 'selected' : ''; ?>>Policy Documents</option>
                    <option value="forms" <?php echo ($categoryFilter ?? '') === 'forms' ? 'selected' : ''; ?>>Forms</option>
                    <option value="other" <?php echo ($categoryFilter ?? '') === 'other' ? 'selected' : ''; ?>>Other</option>
                </select>
            </form>
        </div>
        
        <?php 
        $categoryLabels = [
            'marketing_materials' => ['label' => 'Marketing Materials', 'icon' => 'fa-bullhorn'],
            'training_documents' => ['label' => 'Training Documents', 'icon' => 'fa-graduation-cap'],
            'policy_documents' => ['label' => 'Policy Documents', 'icon' => 'fa-file-contract'],
            'forms' => ['label' => 'Forms', 'icon' => 'fa-file-alt'],
            'other' => ['label' => 'Other', 'icon' => 'fa-file']
        ];
        
        foreach ($categoryLabels as $categoryKey => $categoryInfo): 
            $categoryResources = $resources[$categoryKey] ?? [];
            if (empty($categoryResources) && ($categoryFilter ?? 'all') !== 'all' && ($categoryFilter ?? 'all') !== $categoryKey) {
                continue;
            }
        ?>
            <div class="category-section">
                <div class="category-header">
                    <i class="fas <?php echo $categoryInfo['icon']; ?>"></i>
                    <h2><?php echo $categoryInfo['label']; ?> (<?php echo count($categoryResources); ?>)</h2>
                </div>
                
                <?php if (empty($categoryResources)): ?>
                    <div class="empty-state">
                        <i class="fas fa-folder-open"></i>
                        <h3>No resources in this category</h3>
                        <p>Upload resources to make them available to agents.</p>
                    </div>
                <?php else: ?>
                    <div class="resources-grid">
                        <?php foreach ($categoryResources as $resource): ?>
                            <div class="resource-card">
                                <div class="resource-icon">
                                    <i class="fas fa-file"></i>
                                </div>
                                <div class="resource-title"><?php echo htmlspecialchars($resource['title']); ?></div>
                                <?php if (!empty($resource['description'])): ?>
                                    <div class="resource-description"><?php echo htmlspecialchars($resource['description']); ?></div>
                                <?php endif; ?>
                                <div class="resource-meta">
                                    <span><i class="fas fa-hdd"></i> <?php echo Resource::formatFileSize($resource['file_size']); ?></span>
                                    <span><i class="fas fa-download"></i> <?php echo $resource['download_count']; ?> downloads</span>
                                    <span><i class="fas fa-calendar"></i> <?php echo date('M d, Y', strtotime($resource['created_at'])); ?></span>
                                </div>
                                <div class="resource-actions">
                                    <a href="/admin/agents/resources/download/<?php echo $resource['id']; ?>" class="btn btn-primary btn-sm">
                                        <i class="fas fa-download"></i> Download
                                    </a>
                                    <form method="POST" action="/admin/agents/resources/delete/<?php echo $resource['id']; ?>" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this resource?');">
                                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
    
    <!-- Upload Modal -->
    <div id="uploadModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2><i class="fas fa-upload"></i> Upload New Resource</h2>
                <button class="modal-close" onclick="closeUploadModal()">&times;</button>
            </div>
            <form method="POST" action="/admin/agents/resources/upload" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="title">Resource Title *</label>
                        <input type="text" id="title" name="title" required placeholder="Enter resource title">
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" placeholder="Enter resource description (optional)"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="category">Category *</label>
                        <select id="category" name="category" required>
                            <option value="marketing_materials">Marketing Materials</option>
                            <option value="training_documents">Training Documents</option>
                            <option value="policy_documents">Policy Documents</option>
                            <option value="forms">Forms</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>File *</label>
                        <div class="file-input-wrapper">
                            <input type="file" id="resource_file" name="resource_file" required accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png,.zip">
                            <div class="file-input-label">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <span id="fileLabel">Click to select file (Max 10MB)</span>
                            </div>
                        </div>
                        <small style="color: #6b7280; margin-top: 5px; display: block;">
                            Allowed: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, JPG, PNG, ZIP
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeUploadModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-upload"></i> Upload Resource
                    </button>
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
        
        // Close modal when clicking outside
        document.getElementById('uploadModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeUploadModal();
            }
        });
        
        // Update file label when file is selected
        document.getElementById('resource_file').addEventListener('change', function() {
            const fileLabel = document.getElementById('fileLabel');
            if (this.files && this.files[0]) {
                fileLabel.textContent = this.files[0].name;
            } else {
                fileLabel.textContent = 'Click to select file (Max 10MB)';
            }
        });
    </script>
</body>
</html>
