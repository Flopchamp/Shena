-- Migration: Add Agent Commission System
-- Description: Adds agent_id to members table, creates agents table, and agent_commissions table
-- Date: 2024-12-19

-- Add agent_id column to members table
ALTER TABLE members ADD COLUMN agent_id INT NULL AFTER user_id;
ALTER TABLE members ADD CONSTRAINT fk_members_agent_id FOREIGN KEY (agent_id) REFERENCES agents(id) ON DELETE SET NULL;

-- Create agents table
CREATE TABLE agents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    agent_number VARCHAR(20) UNIQUE NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    national_id VARCHAR(20) UNIQUE NOT NULL,
    phone VARCHAR(20) NOT NULL,
    email VARCHAR(255) NOT NULL,
    address TEXT,
    county VARCHAR(100),
    commission_rate DECIMAL(5,2) DEFAULT 10.00,
    total_commission DECIMAL(10,2) DEFAULT 0,
    status ENUM('active', 'suspended', 'inactive') DEFAULT 'active',
    registration_date DATE DEFAULT (CURDATE()),
    suspended_at TIMESTAMP NULL,
    activated_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Create agent_commissions table
CREATE TABLE agent_commissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    agent_id INT NOT NULL,
    member_id INT NOT NULL,
    payment_id INT NULL,
    commission_type ENUM('registration', 'monthly_contribution', 'plan_upgrade') DEFAULT 'monthly_contribution',
    amount DECIMAL(10,2) NOT NULL,
    commission_rate DECIMAL(5,2) NOT NULL,
    commission_amount DECIMAL(10,2) NOT NULL,
    status ENUM('pending', 'approved', 'paid', 'cancelled') DEFAULT 'pending',
    approved_by INT NULL,
    approved_at TIMESTAMP NULL,
    paid_at TIMESTAMP NULL,
    payment_method VARCHAR(50) NULL,
    payment_reference VARCHAR(100) NULL,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (agent_id) REFERENCES agents(id) ON DELETE CASCADE,
    FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE CASCADE,
    FOREIGN KEY (payment_id) REFERENCES payments(id) ON DELETE SET NULL,
    FOREIGN KEY (approved_by) REFERENCES users(id) ON DELETE SET NULL
);

-- Add indexes for better performance
CREATE INDEX idx_members_agent_id ON members(agent_id);
CREATE INDEX idx_agents_user_id ON agents(user_id);
CREATE INDEX idx_agents_agent_number ON agents(agent_number);
CREATE INDEX idx_agents_status ON agents(status);
CREATE INDEX idx_agent_commissions_agent_id ON agent_commissions(agent_id);
CREATE INDEX idx_agent_commissions_member_id ON agent_commissions(member_id);
CREATE INDEX idx_agent_commissions_status ON agent_commissions(status);
CREATE INDEX idx_agent_commissions_created_at ON agent_commissions(created_at);
