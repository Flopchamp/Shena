-- SMS Campaign Management Tables
-- Execute this to enable SMS queuing, scheduling, and campaign management

-- Bulk SMS campaigns table
CREATE TABLE IF NOT EXISTS bulk_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    message_type ENUM('sms', 'email') DEFAULT 'sms',
    target_audience VARCHAR(50) NOT NULL COMMENT 'all_members, active, grace_period, defaulted, custom',
    custom_filters JSON NULL COMMENT 'Additional filters for custom audience',
    status ENUM('draft', 'scheduled', 'sending', 'completed', 'cancelled') DEFAULT 'draft',
    scheduled_at DATETIME NULL COMMENT 'When to send (NULL for immediate)',
    started_at DATETIME NULL COMMENT 'When sending actually started',
    completed_at DATETIME NULL COMMENT 'When all messages were sent',
    total_recipients INT DEFAULT 0,
    sent_count INT DEFAULT 0,
    failed_count INT DEFAULT 0,
    created_by INT NOT NULL COMMENT 'Admin user who created the campaign',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id),
    INDEX idx_status (status),
    INDEX idx_scheduled_at (scheduled_at),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Recipients for bulk messages
CREATE TABLE IF NOT EXISTS bulk_message_recipients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    bulk_message_id INT NOT NULL,
    user_id INT NOT NULL,
    recipient_type VARCHAR(20) DEFAULT 'sms' COMMENT 'sms or email',
    recipient_value VARCHAR(255) NOT NULL COMMENT 'Phone number or email',
    status ENUM('pending', 'sent', 'failed', 'skipped') DEFAULT 'pending',
    error_message TEXT NULL,
    sent_at DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (bulk_message_id) REFERENCES bulk_messages(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_bulk_message_status (bulk_message_id, status),
    INDEX idx_user (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SMS queue for rate limiting and retry logic
CREATE TABLE IF NOT EXISTS sms_queue (
    id INT AUTO_INCREMENT PRIMARY KEY,
    phone_number VARCHAR(20) NOT NULL,
    message TEXT NOT NULL,
    priority ENUM('low', 'normal', 'high', 'urgent') DEFAULT 'normal',
    status ENUM('pending', 'processing', 'sent', 'failed') DEFAULT 'pending',
    retry_count INT DEFAULT 0,
    max_retries INT DEFAULT 3,
    error_message TEXT NULL,
    bulk_message_id INT NULL COMMENT 'Link to bulk campaign if applicable',
    user_id INT NULL COMMENT 'User this SMS is for',
    scheduled_at DATETIME NULL COMMENT 'Send at specific time',
    sent_at DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (bulk_message_id) REFERENCES bulk_messages(id) ON DELETE SET NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_status_priority (status, priority),
    INDEX idx_scheduled_at (scheduled_at),
    INDEX idx_bulk_message (bulk_message_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SMS templates for quick sending
CREATE TABLE IF NOT EXISTS sms_templates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT NULL,
    template TEXT NOT NULL COMMENT 'Template with {placeholders}',
    category VARCHAR(50) NULL COMMENT 'payment_reminder, claim_update, general, etc.',
    is_active BOOLEAN DEFAULT TRUE,
    created_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id),
    INDEX idx_category (category),
    INDEX idx_is_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default templates
INSERT INTO sms_templates (name, description, template, category, created_by, is_active) VALUES
('Payment Reminder', 'Monthly contribution reminder', 'Dear {name}, your monthly contribution of KES {amount} is due. Paybill: {paybill}, Account: {member_number}. Thank you!', 'payment_reminder', 1, TRUE),
('Payment Received', 'Payment confirmation', 'Hello {name}, we have received your payment of KES {amount}. Receipt No: {receipt}. Thank you for your contribution!', 'payment_confirmation', 1, TRUE),
('Welcome New Member', 'Welcome message for new members', 'Welcome to Shena Companion, {name}! Your member number is {member_number}. Monthly contribution: KES {amount}. We are glad to have you!', 'welcome', 1, TRUE),
('Claim Approved', 'Claim approval notification', 'Dear {name}, your claim #{claim_number} has been approved. Amount: KES {amount}. Processing will begin shortly.', 'claim_update', 1, TRUE),
('General Announcement', 'General announcement template', 'Hello {name}, {message}', 'general', 1, TRUE);

-- View for campaign statistics
CREATE OR REPLACE VIEW vw_campaign_stats AS
SELECT 
    bm.id,
    bm.title,
    bm.message_type,
    bm.status,
    bm.scheduled_at,
    bm.total_recipients,
    bm.sent_count,
    bm.failed_count,
    (bm.sent_count / GREATEST(bm.total_recipients, 1) * 100) as success_rate,
    COUNT(CASE WHEN bmr.status = 'pending' THEN 1 END) as pending_count,
    bm.created_at,
    CONCAT(u.first_name, ' ', u.last_name) as created_by_name
FROM bulk_messages bm
LEFT JOIN bulk_message_recipients bmr ON bm.id = bmr.bulk_message_id
LEFT JOIN users u ON bm.created_by = u.id
GROUP BY bm.id;

-- View for SMS queue dashboard
CREATE OR REPLACE VIEW vw_sms_queue_status AS
SELECT 
    DATE(created_at) as queue_date,
    status,
    priority,
    COUNT(*) as message_count,
    SUM(CASE WHEN status = 'sent' THEN 1 ELSE 0 END) as sent_count,
    SUM(CASE WHEN status = 'failed' THEN 1 ELSE 0 END) as failed_count,
    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_count
FROM sms_queue
GROUP BY DATE(created_at), status, priority
ORDER BY queue_date DESC, priority DESC;

-- Add SMS credits tracking (optional)
CREATE TABLE IF NOT EXISTS sms_credits (
    id INT AUTO_INCREMENT PRIMARY KEY,
    balance INT DEFAULT 0,
    last_purchase_amount INT DEFAULT 0,
    last_purchase_date DATETIME NULL,
    low_balance_threshold INT DEFAULT 100,
    alert_sent BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Initialize SMS credits (if table is empty)
INSERT INTO sms_credits (balance, low_balance_threshold) 
SELECT 1000, 100
WHERE NOT EXISTS (SELECT 1 FROM sms_credits);
