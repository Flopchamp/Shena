-- Migration: Add agents table and notification preferences
-- Phase 3: Communication System & Agent Management

-- Create agents table
CREATE TABLE IF NOT EXISTS agents (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    agent_number VARCHAR(20) UNIQUE NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    national_id VARCHAR(20) UNIQUE NOT NULL,
    phone VARCHAR(20) NOT NULL,
    email VARCHAR(100) NOT NULL,
    address TEXT,
    county VARCHAR(50),
    status ENUM('active', 'suspended', 'inactive') DEFAULT 'active',
    commission_rate DECIMAL(5,2) DEFAULT 10.00 COMMENT 'Percentage commission',
    total_members INT DEFAULT 0,
    total_commission DECIMAL(10,2) DEFAULT 0.00,
    registration_date DATE,
    activated_at TIMESTAMP NULL,
    suspended_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_agent_number (agent_number),
    INDEX idx_status (status),
    INDEX idx_phone (phone)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create agent_commissions table
CREATE TABLE IF NOT EXISTS agent_commissions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    agent_id INT NOT NULL,
    member_id INT NOT NULL,
    payment_id INT,
    commission_type ENUM('registration', 'monthly', 'renewal') NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    commission_rate DECIMAL(5,2) NOT NULL,
    commission_amount DECIMAL(10,2) NOT NULL,
    status ENUM('pending', 'approved', 'paid', 'cancelled') DEFAULT 'pending',
    approved_by INT NULL,
    approved_at TIMESTAMP NULL,
    paid_at TIMESTAMP NULL,
    payment_method VARCHAR(50),
    payment_reference VARCHAR(100),
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (agent_id) REFERENCES agents(id) ON DELETE CASCADE,
    FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE CASCADE,
    FOREIGN KEY (approved_by) REFERENCES users(id),
    INDEX idx_agent_status (agent_id, status),
    INDEX idx_payment (payment_id),
    INDEX idx_dates (created_at, paid_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create notification_preferences table
CREATE TABLE IF NOT EXISTS notification_preferences (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    email_enabled BOOLEAN DEFAULT TRUE,
    sms_enabled BOOLEAN DEFAULT TRUE,
    payment_reminders BOOLEAN DEFAULT TRUE,
    grace_period_alerts BOOLEAN DEFAULT TRUE,
    claim_updates BOOLEAN DEFAULT TRUE,
    general_announcements BOOLEAN DEFAULT TRUE,
    promotional_messages BOOLEAN DEFAULT FALSE,
    preferred_language VARCHAR(10) DEFAULT 'en',
    quiet_hours_start TIME NULL,
    quiet_hours_end TIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_prefs (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create bulk_messages table for tracking bulk communications
CREATE TABLE IF NOT EXISTS bulk_messages (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    message_type ENUM('sms', 'email', 'both') NOT NULL,
    target_audience ENUM('all_members', 'active', 'grace_period', 'defaulted', 'custom') NOT NULL,
    custom_filters JSON NULL COMMENT 'Custom filter criteria',
    total_recipients INT DEFAULT 0,
    sent_count INT DEFAULT 0,
    failed_count INT DEFAULT 0,
    status ENUM('draft', 'scheduled', 'sending', 'completed', 'failed') DEFAULT 'draft',
    scheduled_at TIMESTAMP NULL,
    started_at TIMESTAMP NULL,
    completed_at TIMESTAMP NULL,
    created_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id),
    INDEX idx_status (status),
    INDEX idx_type (message_type),
    INDEX idx_scheduled (scheduled_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create bulk_message_recipients table for individual tracking
CREATE TABLE IF NOT EXISTS bulk_message_recipients (
    id INT PRIMARY KEY AUTO_INCREMENT,
    bulk_message_id INT NOT NULL,
    user_id INT NOT NULL,
    recipient_type ENUM('email', 'sms') NOT NULL,
    recipient_value VARCHAR(100) NOT NULL COMMENT 'Email address or phone number',
    status ENUM('pending', 'sent', 'failed', 'bounced') DEFAULT 'pending',
    sent_at TIMESTAMP NULL,
    error_message TEXT NULL,
    FOREIGN KEY (bulk_message_id) REFERENCES bulk_messages(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_bulk_status (bulk_message_id, status),
    INDEX idx_user (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Add agent_id column to members table (for tracking who registered them)
ALTER TABLE members 
ADD COLUMN agent_id INT NULL AFTER user_id,
ADD FOREIGN KEY (agent_id) REFERENCES agents(id) ON DELETE SET NULL;

-- Add agent role to users table if not exists
ALTER TABLE users 
MODIFY COLUMN role ENUM('member', 'agent', 'manager', 'admin', 'super_admin') NOT NULL DEFAULT 'member';

-- Create default notification preferences for existing users
INSERT INTO notification_preferences (user_id, email_enabled, sms_enabled)
SELECT id, TRUE, TRUE FROM users
WHERE id NOT IN (SELECT user_id FROM notification_preferences);
