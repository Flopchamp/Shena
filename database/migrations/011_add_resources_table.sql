-- Migration 011: Add Resources Table for Agent Resource Management
-- This migration creates tables for storing and tracking downloadable resources

-- Resources table - stores uploaded files for agents
CREATE TABLE IF NOT EXISTS resources (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    file_name VARCHAR(255) NOT NULL,
    file_path VARCHAR(500) NOT NULL,
    original_name VARCHAR(255),
    file_size INT,
    mime_type VARCHAR(100),
    category ENUM('marketing_materials', 'training_documents', 'policy_documents', 'forms', 'other') DEFAULT 'other',
    uploaded_by INT NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    download_count INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (uploaded_by) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_resources_category (category),
    INDEX idx_resources_active (is_active),
    INDEX idx_resources_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Resource downloads tracking table
CREATE TABLE IF NOT EXISTS resource_downloads (
    id INT AUTO_INCREMENT PRIMARY KEY,
    resource_id INT NOT NULL,
    user_id INT NOT NULL,
    downloaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ip_address VARCHAR(45),
    user_agent TEXT,
    FOREIGN KEY (resource_id) REFERENCES resources(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_downloads_resource (resource_id),
    INDEX idx_downloads_user (user_id),
    INDEX idx_downloads_date (downloaded_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default system setting for max resource file size
INSERT INTO system_settings (setting_key, setting_value, description) 
VALUES ('max_resource_file_size', '10485760', 'Maximum resource file upload size in bytes (10MB)')
ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value);

-- Insert default system setting for allowed resource file types
INSERT INTO system_settings (setting_key, setting_value, description) 
VALUES ('allowed_resource_types', 'pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png,zip', 'Allowed resource file upload types')
ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value);
