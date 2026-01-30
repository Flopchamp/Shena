-- STK Push Support Migration
-- Adds necessary columns and tables for M-Pesa STK Push functionality
-- Business Shortcode: Sandbox 174379, Production 4163987

-- Add STK Push columns to payments table if not exist
ALTER TABLE payments 
ADD COLUMN IF NOT EXISTS merchant_request_id VARCHAR(100) NULL COMMENT 'M-Pesa Merchant Request ID',
ADD COLUMN IF NOT EXISTS checkout_request_id VARCHAR(100) NULL COMMENT 'M-Pesa Checkout Request ID (STK)',
ADD COLUMN IF NOT EXISTS result_code VARCHAR(10) NULL COMMENT 'M-Pesa Result Code',
ADD COLUMN IF NOT EXISTS result_desc TEXT NULL COMMENT 'M-Pesa Result Description',
ADD COLUMN IF NOT EXISTS phone_number VARCHAR(20) NULL COMMENT 'Phone number used for STK push';

-- Create index for checkout request ID lookups
CREATE INDEX IF NOT EXISTS idx_payments_checkout_request 
ON payments(checkout_request_id);

-- Create index for merchant request ID
CREATE INDEX IF NOT EXISTS idx_payments_merchant_request 
ON payments(merchant_request_id);

-- Create STK Push logs table for detailed tracking
CREATE TABLE IF NOT EXISTS mpesa_stk_push_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    payment_id INT NULL,
    merchant_request_id VARCHAR(100) NOT NULL,
    checkout_request_id VARCHAR(100) NOT NULL,
    phone_number VARCHAR(20) NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    account_reference VARCHAR(100) NULL,
    transaction_desc VARCHAR(255) NULL,
    result_code VARCHAR(10) NULL,
    result_desc TEXT NULL,
    mpesa_receipt_number VARCHAR(50) NULL,
    transaction_date DATETIME NULL,
    request_sent_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    callback_received_at DATETIME NULL,
    callback_data TEXT NULL COMMENT 'Full callback JSON for debugging',
    status ENUM('pending', 'success', 'failed', 'timeout') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (payment_id) REFERENCES payments(id) ON DELETE SET NULL,
    INDEX idx_checkout_request (checkout_request_id),
    INDEX idx_merchant_request (merchant_request_id),
    INDEX idx_phone_number (phone_number),
    INDEX idx_status (status),
    INDEX idx_request_sent_at (request_sent_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Detailed logs for all M-Pesa STK Push requests and callbacks';

-- Update payment_type enum to ensure all types are supported
ALTER TABLE payments 
MODIFY COLUMN payment_type ENUM('monthly', 'registration', 'reactivation', 'upgrade', 'penalty', 'other') 
DEFAULT 'monthly';

-- Create view for pending STK push requests
CREATE OR REPLACE VIEW vw_pending_stk_pushes AS
SELECT 
    l.id,
    l.checkout_request_id,
    l.phone_number,
    l.amount,
    l.account_reference,
    l.status,
    l.request_sent_at,
    TIMESTAMPDIFF(MINUTE, l.request_sent_at, NOW()) as minutes_pending,
    p.id as payment_id,
    p.member_id,
    p.payment_type,
    m.member_number,
    CONCAT(m.first_name, ' ', m.last_name) as member_name
FROM mpesa_stk_push_logs l
LEFT JOIN payments p ON l.payment_id = p.id
LEFT JOIN members m ON p.member_id = m.id
WHERE l.status = 'pending'
AND TIMESTAMPDIFF(MINUTE, l.request_sent_at, NOW()) < 5
ORDER BY l.request_sent_at DESC;

-- Create view for failed STK pushes that need retry
CREATE OR REPLACE VIEW vw_failed_stk_pushes AS
SELECT 
    l.id,
    l.checkout_request_id,
    l.phone_number,
    l.amount,
    l.result_code,
    l.result_desc,
    l.request_sent_at,
    l.callback_received_at,
    p.id as payment_id,
    p.member_id,
    m.member_number,
    CONCAT(m.first_name, ' ', m.last_name) as member_name
FROM mpesa_stk_push_logs l
LEFT JOIN payments p ON l.payment_id = p.id
LEFT JOIN members m ON p.member_id = m.id
WHERE l.status = 'failed'
ORDER BY l.request_sent_at DESC;

-- Create stored procedure to check and timeout old pending STK pushes
DELIMITER //
CREATE PROCEDURE IF NOT EXISTS timeout_old_stk_pushes()
BEGIN
    -- Mark STK push requests older than 5 minutes as timeout
    UPDATE mpesa_stk_push_logs
    SET status = 'timeout',
        result_desc = 'Request timed out - no callback received within 5 minutes',
        updated_at = NOW()
    WHERE status = 'pending'
    AND TIMESTAMPDIFF(MINUTE, request_sent_at, NOW()) >= 5;
    
    -- Also update corresponding payment records
    UPDATE payments p
    INNER JOIN mpesa_stk_push_logs l ON p.transaction_reference = l.checkout_request_id
    SET p.status = 'failed',
        p.notes = CONCAT(COALESCE(p.notes, ''), ' [STK Push Timeout]')
    WHERE l.status = 'timeout'
    AND p.status = 'pending';
END //
DELIMITER ;

-- Add configuration table for M-Pesa settings (environment switching)
CREATE TABLE IF NOT EXISTS mpesa_configuration (
    id INT AUTO_INCREMENT PRIMARY KEY,
    config_key VARCHAR(100) UNIQUE NOT NULL,
    config_value TEXT NOT NULL,
    environment ENUM('sandbox', 'production') DEFAULT 'sandbox',
    description TEXT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_config_key (config_key),
    INDEX idx_environment (environment),
    INDEX idx_is_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='M-Pesa configuration settings for sandbox and production';

-- Insert default M-Pesa configurations
INSERT INTO mpesa_configuration (config_key, config_value, environment, description) VALUES
('business_shortcode', '174379', 'sandbox', 'Sandbox Business Shortcode for Testing'),
('business_shortcode', '4163987', 'production', 'Production Business Shortcode'),
('passkey', 'bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919', 'sandbox', 'Sandbox Passkey'),
('api_url', 'https://sandbox.safaricom.co.ke', 'sandbox', 'Sandbox API Base URL'),
('api_url', 'https://api.safaricom.co.ke', 'production', 'Production API Base URL'),
('callback_url', 'https://yourdomain.com/public/mpesa-stk-callback.php', 'sandbox', 'STK Push Callback URL'),
('c2b_callback_url', 'https://yourdomain.com/public/mpesa-c2b-callback.php', 'sandbox', 'C2B Payment Callback URL')
ON DUPLICATE KEY UPDATE updated_at = CURRENT_TIMESTAMP;

-- Add audit log for configuration changes
CREATE TABLE IF NOT EXISTS mpesa_config_audit (
    id INT AUTO_INCREMENT PRIMARY KEY,
    config_id INT NOT NULL,
    config_key VARCHAR(100) NOT NULL,
    old_value TEXT NULL,
    new_value TEXT NOT NULL,
    changed_by INT NULL COMMENT 'User ID who made the change',
    changed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (config_id) REFERENCES mpesa_configuration(id) ON DELETE CASCADE,
    FOREIGN KEY (changed_by) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_config_id (config_id),
    INDEX idx_changed_at (changed_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Audit trail for M-Pesa configuration changes';

-- Migration complete message
SELECT 'STK Push Support Migration Completed Successfully' as status,
       'Tables created: mpesa_stk_push_logs, mpesa_configuration, mpesa_config_audit' as tables,
       'Columns added to payments: merchant_request_id, checkout_request_id, result_code, result_desc, phone_number' as columns,
       'Views created: vw_pending_stk_pushes, vw_failed_stk_pushes' as views,
       'Procedures created: timeout_old_stk_pushes' as procedures;
