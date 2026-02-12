-- Shena Companion Welfare Association Database Schema
-- Execute this SQL to create the database structure

CREATE DATABASE IF NOT EXISTS shena_welfare_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE shena_welfare_db;

-- Users table (authentication and basic info)
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    phone VARCHAR(20) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('member', 'manager', 'super_admin') DEFAULT 'member',
    status ENUM('pending', 'active', 'inactive', 'suspended') DEFAULT 'pending',
    email_verified_at TIMESTAMP NULL,
    last_login TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Members table (member-specific information)
CREATE TABLE members (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    member_number VARCHAR(20) UNIQUE NOT NULL,
    id_number VARCHAR(20) UNIQUE NOT NULL,
    date_of_birth DATE,
    gender ENUM('male', 'female') NOT NULL,
    address TEXT,
    next_of_kin VARCHAR(200),
    next_of_kin_relationship VARCHAR(50),
    next_of_kin_phone VARCHAR(20),
    -- High-level category used for reporting (matches policy booklet groupings)
    package ENUM('individual', 'couple', 'family', 'executive') DEFAULT 'individual',
    -- Specific package key from configuration (e.g. individual_below_70, couple_children_parents_below_70)
    package_key VARCHAR(100) DEFAULT NULL,
    monthly_contribution DECIMAL(10,2) DEFAULT 0,
    status ENUM('active', 'inactive', 'grace_period', 'defaulted', 'suspended') DEFAULT 'inactive',
    -- Date when waiting/maturity period ends and cover first becomes active
    maturity_ends DATE NULL,
    -- Date when cover ends based on paid-up contributions (nullable if not yet established)
    coverage_ends DATE NULL,
    grace_period_expires TIMESTAMP NULL,
    reactivated_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Dependents table (covered family members under family packages)
CREATE TABLE dependents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    member_id INT NOT NULL,
    full_name VARCHAR(200) NOT NULL,
    relationship ENUM('spouse', 'child', 'parent', 'father_in_law', 'mother_in_law') NOT NULL,
    id_number VARCHAR(20), -- May be null for children without IDs
    birth_certificate VARCHAR(50), -- For children below 18
    date_of_birth DATE NOT NULL,
    gender ENUM('male', 'female') NOT NULL,
    phone_number VARCHAR(20),
    -- Track if dependent is still eligible (e.g., child turned 18)
    is_covered BOOLEAN DEFAULT TRUE,
    -- Date when dependent was added
    coverage_start_date DATE NOT NULL,
    -- Date when dependent coverage ended (e.g., child turned 18, removed from package)
    coverage_end_date DATE NULL,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE CASCADE,
    INDEX idx_dependents_member_id (member_id),
    INDEX idx_dependents_relationship (relationship),
    INDEX idx_dependents_is_covered (is_covered)
);

-- Beneficiaries table
CREATE TABLE beneficiaries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    member_id INT NOT NULL,
    full_name VARCHAR(200) NOT NULL,
    relationship VARCHAR(100) NOT NULL,
    id_number VARCHAR(20) NOT NULL,
    phone_number VARCHAR(20),
    percentage DECIMAL(5,2) DEFAULT 100.00,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE CASCADE
);

-- Payments table
CREATE TABLE payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    member_id INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    payment_type ENUM('registration', 'monthly', 'reactivation', 'penalty') DEFAULT 'monthly',
    payment_method ENUM('mpesa', 'bank', 'cash', 'cheque') DEFAULT 'mpesa',
    transaction_id VARCHAR(100),
    transaction_reference VARCHAR(100), -- For M-Pesa CheckoutRequestID
    phone_number VARCHAR(20),
    status ENUM('pending', 'completed', 'failed', 'cancelled') DEFAULT 'pending',
    payment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    confirmed_at TIMESTAMP NULL,
    failed_at TIMESTAMP NULL,
    failure_reason TEXT,
    reference VARCHAR(100),
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE CASCADE
);

-- Claims table
CREATE TABLE claims (
    id INT AUTO_INCREMENT PRIMARY KEY,
    member_id INT NOT NULL,
    dependent_id INT NULL, -- If claim is for a dependent, link here
    beneficiary_id INT,
    -- Who died: 'member' or 'dependent'
    deceased_type ENUM('member', 'dependent') DEFAULT 'member',
    deceased_name VARCHAR(200) NOT NULL,
    deceased_id_number VARCHAR(20) NOT NULL,
    date_of_birth DATE NULL,
    date_of_death DATE NOT NULL,
    place_of_death VARCHAR(200) NOT NULL,
    cause_of_death TEXT,
    mortuary_name VARCHAR(200),
    mortuary_bill_amount DECIMAL(10,2) DEFAULT 0,
    claim_amount DECIMAL(10,2) NOT NULL,
    approved_amount DECIMAL(10,2),
    -- How the claim is settled: full funeral services or cash alternative
    settlement_type ENUM('services', 'cash') DEFAULT 'services',
    cash_settlement_amount DECIMAL(10,2) DEFAULT NULL,
    status ENUM('submitted', 'under_review', 'approved', 'rejected', 'paid') DEFAULT 'submitted',
    processed_by INT,
    processed_at TIMESTAMP NULL,
    approved_at TIMESTAMP NULL,
    rejected_at TIMESTAMP NULL,
    processing_notes TEXT,
    settlement_reason TEXT,
    rejection_reason TEXT,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE CASCADE,
    FOREIGN KEY (dependent_id) REFERENCES dependents(id) ON DELETE SET NULL,
    FOREIGN KEY (beneficiary_id) REFERENCES beneficiaries(id) ON DELETE SET NULL,
    FOREIGN KEY (processed_by) REFERENCES users(id) ON DELETE SET NULL
);

-- Claim documents table
CREATE TABLE claim_documents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    claim_id INT NOT NULL,
    document_type ENUM('id_copy', 'birth_certificate', 'chief_letter', 'mortuary_invoice', 'death_certificate') NOT NULL,
    file_name VARCHAR(255) NOT NULL,
    file_path VARCHAR(500) NOT NULL,
    original_name VARCHAR(255),
    file_size INT,
    mime_type VARCHAR(100),
    uploaded_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (claim_id) REFERENCES claims(id) ON DELETE CASCADE,
    FOREIGN KEY (uploaded_by) REFERENCES users(id) ON DELETE SET NULL
);

-- Communications/Messages table
CREATE TABLE communications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sender_id INT,
    recipient_id INT, -- NULL for broadcast messages
    recipient_type ENUM('individual', 'all', 'package', 'status') DEFAULT 'individual',
    recipient_criteria JSON, -- Store filtering criteria for broadcasts
    subject VARCHAR(255),
    message TEXT NOT NULL,
    action_url VARCHAR(255),
    action_text VARCHAR(100),
    type ENUM('email', 'sms', 'both') DEFAULT 'both',
    status ENUM('draft', 'sent', 'failed') DEFAULT 'draft',
    sent_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (recipient_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Communication recipients table (for tracking individual sends)
CREATE TABLE communication_recipients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    communication_id INT NOT NULL,
    user_id INT NOT NULL,
    type ENUM('email', 'sms') NOT NULL,
    status ENUM('pending', 'sent', 'failed', 'delivered', 'read') DEFAULT 'pending',
    sent_at TIMESTAMP NULL,
    delivered_at TIMESTAMP NULL,
    read_at TIMESTAMP NULL,
    error_message TEXT,
    FOREIGN KEY (communication_id) REFERENCES communications(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Activity logs table
CREATE TABLE activity_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    action VARCHAR(255) NOT NULL,
    details TEXT,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- System settings table
CREATE TABLE system_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) UNIQUE NOT NULL,
    setting_value TEXT,
    description TEXT,
    updated_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (updated_by) REFERENCES users(id) ON DELETE SET NULL
);

-- Indexes for better performance
CREATE INDEX idx_users_email ON users(email);
CREATE INDEX idx_users_phone ON users(phone);
CREATE INDEX idx_users_status ON users(status);
CREATE INDEX idx_users_role ON users(role);

CREATE INDEX idx_members_user_id ON members(user_id);
CREATE INDEX idx_members_member_number ON members(member_number);
CREATE INDEX idx_members_id_number ON members(id_number);
CREATE INDEX idx_members_status ON members(status);
CREATE INDEX idx_members_package ON members(package);
CREATE INDEX idx_members_package_key ON members(package_key);
CREATE INDEX idx_members_maturity_ends ON members(maturity_ends);
CREATE INDEX idx_members_coverage_ends ON members(coverage_ends);

CREATE INDEX idx_payments_member_id ON payments(member_id);
CREATE INDEX idx_payments_status ON payments(status);
CREATE INDEX idx_payments_payment_date ON payments(payment_date);
CREATE INDEX idx_payments_transaction_id ON payments(transaction_id);

CREATE INDEX idx_claims_member_id ON claims(member_id);
CREATE INDEX idx_claims_status ON claims(status);
CREATE INDEX idx_claims_date_of_death ON claims(date_of_death);

CREATE INDEX idx_beneficiaries_member_id ON beneficiaries(member_id);
CREATE INDEX idx_beneficiaries_active ON beneficiaries(is_active);

CREATE INDEX idx_communications_sender ON communications(sender_id);
CREATE INDEX idx_communications_recipient ON communications(recipient_id);
CREATE INDEX idx_communications_status ON communications(status);

CREATE INDEX idx_activity_logs_user_id ON activity_logs(user_id);
CREATE INDEX idx_activity_logs_created_at ON activity_logs(created_at);

-- Insert default admin user (password: admin123)
INSERT INTO users (first_name, last_name, email, phone, password, role, status, email_verified_at) 
VALUES (
    'System', 
    'Administrator', 
    'admin@shenacompanion.org', 
    '+254700000000', 
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- admin123
    'super_admin', 
    'active',
    NOW()
);

-- Insert default system settings
INSERT INTO system_settings (setting_key, setting_value, description) VALUES
('site_name', 'Shena Companion Welfare Association', 'Website name'),
('site_email', 'info@shenacompanion.org', 'Primary contact email'),
('site_phone', '+254700000000', 'Primary contact phone'),
('registration_fee', '200', 'Member registration fee (KES)'),
('reactivation_fee', '100', 'Account reactivation fee (KES)'),
('grace_period_under_80', '4', 'Grace period in months for members under 80'),
('grace_period_80_and_above', '5', 'Grace period in months for members 80 and above'),
('mpesa_paybill', '4163987', 'M-Pesa Paybill number'),
('max_file_size', '5242880', 'Maximum file upload size in bytes (5MB)'),
('allowed_file_types', 'jpg,jpeg,png,pdf,doc,docx', 'Allowed file upload types');
