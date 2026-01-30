-- Migration: Plan Upgrade Feature
-- Description: Add tables to support member plan upgrades from Basic to Premium
-- Date: 2026-01-30

-- Plan upgrade requests table
CREATE TABLE IF NOT EXISTS plan_upgrade_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    member_id INT NOT NULL,
    from_package ENUM('basic', 'premium') NOT NULL,
    to_package ENUM('basic', 'premium') NOT NULL,
    current_monthly_fee DECIMAL(10,2) NOT NULL,
    new_monthly_fee DECIMAL(10,2) NOT NULL,
    prorated_amount DECIMAL(10,2) NOT NULL,
    days_remaining INT NOT NULL,
    status ENUM('pending', 'payment_initiated', 'completed', 'failed', 'cancelled') DEFAULT 'pending',
    payment_method ENUM('mpesa', 'bank', 'cash') NULL,
    mpesa_checkout_id VARCHAR(100) NULL,
    mpesa_receipt_number VARCHAR(50) NULL,
    payment_date DATETIME NULL,
    effective_date DATE NULL,
    requested_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    completed_at DATETIME NULL,
    cancelled_at DATETIME NULL,
    cancellation_reason TEXT NULL,
    notes TEXT NULL,
    FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE CASCADE,
    INDEX idx_member_status (member_id, status),
    INDEX idx_status (status),
    INDEX idx_requested_at (requested_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Plan upgrade history table (for completed upgrades)
CREATE TABLE IF NOT EXISTS plan_upgrade_history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    member_id INT NOT NULL,
    upgrade_request_id INT NULL,
    from_package ENUM('basic', 'premium') NOT NULL,
    to_package ENUM('basic', 'premium') NOT NULL,
    amount_paid DECIMAL(10,2) NOT NULL,
    payment_method ENUM('mpesa', 'bank', 'cash') NOT NULL,
    payment_reference VARCHAR(100) NULL,
    effective_date DATE NOT NULL,
    upgraded_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    processed_by INT NULL COMMENT 'User ID who processed (for admin upgrades)',
    notes TEXT NULL,
    FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE CASCADE,
    FOREIGN KEY (upgrade_request_id) REFERENCES plan_upgrade_requests(id) ON DELETE SET NULL,
    FOREIGN KEY (processed_by) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_member (member_id),
    INDEX idx_effective_date (effective_date),
    INDEX idx_upgraded_at (upgraded_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Add upgrade-related columns to members table if not exist
-- Note: These will error if columns already exist, which is fine
SET @sql1 = IF((SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS 
    WHERE table_schema = DATABASE() AND table_name = 'members' AND column_name = 'last_upgrade_date') = 0,
    'ALTER TABLE members ADD COLUMN last_upgrade_date DATE NULL COMMENT "Date of last package upgrade"',
    'SELECT "Column last_upgrade_date already exists" as message');
PREPARE stmt1 FROM @sql1;
EXECUTE stmt1;
DEALLOCATE PREPARE stmt1;

SET @sql2 = IF((SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS 
    WHERE table_schema = DATABASE() AND table_name = 'members' AND column_name = 'upgrade_count') = 0,
    'ALTER TABLE members ADD COLUMN upgrade_count INT DEFAULT 0 COMMENT "Total number of upgrades"',
    'SELECT "Column upgrade_count already exists" as message');
PREPARE stmt2 FROM @sql2;
EXECUTE stmt2;
DEALLOCATE PREPARE stmt2;

-- Add index for package queries
SET @sql3 = IF((SELECT COUNT(*) FROM INFORMATION_SCHEMA.STATISTICS 
    WHERE table_schema = DATABASE() AND table_name = 'members' AND index_name = 'idx_package') = 0,
    'ALTER TABLE members ADD INDEX idx_package (package)',
    'SELECT "Index idx_package already exists" as message');
PREPARE stmt3 FROM @sql3;
EXECUTE stmt3;
DEALLOCATE PREPARE stmt3;

-- Create view for pending upgrades (admin use)
CREATE OR REPLACE VIEW vw_pending_upgrades AS
SELECT 
    pur.id,
    pur.member_id,
    m.member_number,
    u.first_name,
    u.last_name,
    u.phone,
    u.email,
    pur.from_package,
    pur.to_package,
    pur.prorated_amount,
    pur.days_remaining,
    pur.status,
    pur.payment_method,
    pur.mpesa_receipt_number,
    pur.requested_at,
    DATEDIFF(NOW(), pur.requested_at) as days_pending
FROM plan_upgrade_requests pur
JOIN members m ON pur.member_id = m.id
JOIN users u ON m.user_id = u.id
WHERE pur.status IN ('pending', 'payment_initiated')
ORDER BY pur.requested_at DESC;

-- Create view for upgrade statistics
CREATE OR REPLACE VIEW vw_upgrade_statistics AS
SELECT 
    COUNT(*) as total_upgrades,
    SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed_upgrades,
    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_upgrades,
    SUM(CASE WHEN status = 'failed' THEN 1 ELSE 0 END) as failed_upgrades,
    SUM(CASE WHEN status = 'completed' THEN prorated_amount ELSE 0 END) as total_upgrade_revenue,
    AVG(CASE WHEN status = 'completed' THEN prorated_amount END) as avg_upgrade_amount,
    AVG(CASE WHEN status = 'completed' THEN DATEDIFF(completed_at, requested_at) END) as avg_processing_days
FROM plan_upgrade_requests
WHERE requested_at >= DATE_SUB(NOW(), INTERVAL 12 MONTH);
