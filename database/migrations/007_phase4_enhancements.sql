-- Migration: Phase 4 Enhancements
-- Description: Add features for notification settings, M-Pesa integration improvements, and financial tracking
-- Date: 2026-01-30

-- Add M-Pesa configuration table
CREATE TABLE IF NOT EXISTS mpesa_config (
    id INT AUTO_INCREMENT PRIMARY KEY,
    environment ENUM('sandbox', 'production') DEFAULT 'sandbox',
    consumer_key VARCHAR(255) NOT NULL,
    consumer_secret VARCHAR(255) NOT NULL,
    short_code VARCHAR(20) NOT NULL,
    pass_key VARCHAR(255) NOT NULL,
    callback_url VARCHAR(255) NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Add scheduled bulk campaigns tracking
CREATE TABLE IF NOT EXISTS scheduled_campaigns (
    id INT AUTO_INCREMENT PRIMARY KEY,
    campaign_name VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    recipient_type ENUM('all', 'active', 'inactive', 'by_package', 'custom') DEFAULT 'all',
    recipient_filter JSON NULL COMMENT 'Filter criteria for recipients',
    total_recipients INT DEFAULT 0,
    sent_count INT DEFAULT 0,
    failed_count INT DEFAULT 0,
    scheduled_at DATETIME NOT NULL,
    status ENUM('pending', 'processing', 'completed', 'failed', 'cancelled') DEFAULT 'pending',
    created_by INT NOT NULL,
    executed_at DATETIME NULL,
    completed_at DATETIME NULL,
    error_message TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_status_scheduled (status, scheduled_at),
    INDEX idx_created_by (created_by)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Add financial tracking table
CREATE TABLE IF NOT EXISTS financial_transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    transaction_type ENUM('payment', 'commission', 'refund', 'adjustment', 'upgrade') NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    member_id INT NULL,
    agent_id INT NULL,
    payment_id INT NULL,
    upgrade_request_id INT NULL,
    reference_number VARCHAR(100) NULL,
    description TEXT NULL,
    status ENUM('pending', 'completed', 'failed', 'reversed') DEFAULT 'completed',
    transaction_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE SET NULL,
    FOREIGN KEY (agent_id) REFERENCES agents(id) ON DELETE SET NULL,
    FOREIGN KEY (payment_id) REFERENCES payments(id) ON DELETE SET NULL,
    FOREIGN KEY (upgrade_request_id) REFERENCES plan_upgrade_requests(id) ON DELETE SET NULL,
    INDEX idx_transaction_type (transaction_type),
    INDEX idx_member_id (member_id),
    INDEX idx_agent_id (agent_id),
    INDEX idx_transaction_date (transaction_date),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Add monthly payment reminder tracking
CREATE TABLE IF NOT EXISTS payment_reminders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    member_id INT NOT NULL,
    reminder_type ENUM('upcoming', 'due', 'overdue', 'grace_period') NOT NULL,
    amount_due DECIMAL(10,2) NOT NULL,
    due_date DATE NOT NULL,
    sent_via ENUM('sms', 'email', 'both') NOT NULL,
    sent_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    sms_status ENUM('sent', 'failed', 'delivered') NULL,
    email_status ENUM('sent', 'failed', 'delivered') NULL,
    response_action ENUM('paid', 'ignored', 'contacted') NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE CASCADE,
    INDEX idx_member_reminder (member_id, reminder_type),
    INDEX idx_sent_at (sent_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- View for financial summary
CREATE OR REPLACE VIEW vw_financial_summary AS
SELECT 
    DATE_FORMAT(transaction_date, '%Y-%m') as month,
    SUM(CASE WHEN transaction_type = 'payment' AND status = 'completed' THEN amount ELSE 0 END) as total_payments,
    SUM(CASE WHEN transaction_type = 'commission' AND status = 'completed' THEN amount ELSE 0 END) as total_commissions,
    SUM(CASE WHEN transaction_type = 'upgrade' AND status = 'completed' THEN amount ELSE 0 END) as total_upgrades,
    SUM(CASE WHEN transaction_type = 'refund' AND status = 'completed' THEN amount ELSE 0 END) as total_refunds,
    COUNT(DISTINCT CASE WHEN transaction_type = 'payment' THEN member_id END) as paying_members,
    COUNT(DISTINCT CASE WHEN transaction_type = 'commission' THEN agent_id END) as earning_agents
FROM financial_transactions
WHERE status = 'completed'
GROUP BY DATE_FORMAT(transaction_date, '%Y-%m')
ORDER BY month DESC;

-- View for agent performance leaderboard
CREATE OR REPLACE VIEW vw_agent_leaderboard AS
SELECT 
    a.id,
    a.agent_code,
    CONCAT(u.first_name, ' ', u.last_name) as agent_name,
    COUNT(DISTINCT m.id) as total_members,
    COUNT(DISTINCT CASE WHEN m.status = 'active' THEN m.id END) as active_members,
    COALESCE(SUM(CASE WHEN ac.status = 'approved' THEN ac.commission_amount ELSE 0 END), 0) as total_commissions_approved,
    COALESCE(SUM(CASE WHEN ac.status = 'paid' THEN ac.commission_amount ELSE 0 END), 0) as total_commissions_paid,
    COALESCE(SUM(CASE WHEN ac.status = 'paid' AND ac.paid_at >= DATE_SUB(NOW(), INTERVAL 30 DAY) THEN ac.commission_amount ELSE 0 END), 0) as commissions_last_30_days,
    COUNT(DISTINCT CASE WHEN m.created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY) THEN m.id END) as members_last_30_days
FROM agents a
JOIN users u ON a.user_id = u.id
LEFT JOIN members m ON m.id = (
    SELECT member_id FROM agent_commissions WHERE agent_id = a.id LIMIT 1
)
LEFT JOIN agent_commissions ac ON ac.agent_id = a.id
WHERE a.status = 'active'
GROUP BY a.id, a.agent_code, u.first_name, u.last_name
ORDER BY total_commissions_paid DESC, active_members DESC;

-- View for scheduled campaigns summary
CREATE OR REPLACE VIEW vw_scheduled_campaigns_summary AS
SELECT 
    sc.id,
    sc.campaign_name,
    sc.scheduled_at,
    sc.status,
    sc.total_recipients,
    sc.sent_count,
    sc.failed_count,
    ROUND((sc.sent_count / NULLIF(sc.total_recipients, 0)) * 100, 2) as success_rate,
    CONCAT(u.first_name, ' ', u.last_name) as created_by_name,
    sc.executed_at,
    sc.completed_at
FROM scheduled_campaigns sc
JOIN users u ON sc.created_by = u.id
ORDER BY sc.scheduled_at DESC;
