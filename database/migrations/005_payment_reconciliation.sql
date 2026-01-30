-- Phase 2: Payment Auto-Reconciliation Migration
-- Date: 2026-01-30
-- Purpose: Add payment reconciliation tracking for Paybill payments

-- Add reconciliation fields to payments table (one by one to handle duplicates)
ALTER TABLE payments ADD COLUMN reconciliation_status ENUM('pending', 'matched', 'unmatched', 'manual') DEFAULT 'pending' AFTER status;
ALTER TABLE payments ADD COLUMN mpesa_receipt_number VARCHAR(50) AFTER amount;
ALTER TABLE payments ADD COLUMN transaction_date DATETIME AFTER payment_date;
ALTER TABLE payments ADD COLUMN sender_phone VARCHAR(15) AFTER member_id;
ALTER TABLE payments ADD COLUMN sender_name VARCHAR(100) AFTER sender_phone;
ALTER TABLE payments ADD COLUMN paybill_account VARCHAR(50) AFTER payment_method;
ALTER TABLE payments ADD COLUMN reconciled_at DATETIME NULL;
ALTER TABLE payments ADD COLUMN reconciled_by INT NULL;
ALTER TABLE payments ADD COLUMN reconciliation_notes TEXT NULL;
ALTER TABLE payments ADD COLUMN auto_matched BOOLEAN DEFAULT FALSE;

-- Add indexes
ALTER TABLE payments ADD INDEX idx_reconciliation_status (reconciliation_status);
ALTER TABLE payments ADD INDEX idx_mpesa_receipt (mpesa_receipt_number);
ALTER TABLE payments ADD INDEX idx_sender_phone (sender_phone);
ALTER TABLE payments ADD INDEX idx_transaction_date (transaction_date);

-- Create payment_reconciliation_log table
CREATE TABLE IF NOT EXISTS payment_reconciliation_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    payment_id INT NOT NULL,
    action VARCHAR(50) NOT NULL COMMENT 'matched, unmatched, manual_match, rejected',
    previous_status VARCHAR(20),
    new_status VARCHAR(20),
    matched_member_id INT NULL,
    match_method VARCHAR(50) COMMENT 'auto_id_number, auto_phone, manual',
    confidence_score DECIMAL(5,2) NULL COMMENT 'Matching confidence 0-100',
    reconciled_by INT NULL,
    notes TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (payment_id) REFERENCES payments(id) ON DELETE CASCADE,
    FOREIGN KEY (matched_member_id) REFERENCES members(id) ON DELETE SET NULL,
    FOREIGN KEY (reconciled_by) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_payment_id (payment_id),
    INDEX idx_matched_member (matched_member_id),
    INDEX idx_action (action),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create mpesa_c2b_callbacks table for storing raw C2B callbacks
CREATE TABLE IF NOT EXISTS mpesa_c2b_callbacks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    transaction_type VARCHAR(50) NOT NULL,
    trans_id VARCHAR(50) UNIQUE NOT NULL COMMENT 'M-Pesa Transaction ID',
    trans_time VARCHAR(20) NOT NULL,
    trans_amount DECIMAL(10,2) NOT NULL,
    business_short_code VARCHAR(20) NOT NULL,
    bill_ref_number VARCHAR(50) NULL COMMENT 'Account number sent by customer',
    invoice_number VARCHAR(50) NULL,
    org_account_balance DECIMAL(15,2) NULL,
    third_party_trans_id VARCHAR(50) NULL,
    msisdn VARCHAR(15) NOT NULL COMMENT 'Customer phone number',
    first_name VARCHAR(50) NULL,
    middle_name VARCHAR(50) NULL,
    last_name VARCHAR(50) NULL,
    raw_callback TEXT NOT NULL COMMENT 'Full JSON callback data',
    processed BOOLEAN DEFAULT FALSE,
    processed_at DATETIME NULL,
    payment_id INT NULL COMMENT 'Linked payment after reconciliation',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (payment_id) REFERENCES payments(id) ON DELETE SET NULL,
    INDEX idx_trans_id (trans_id),
    INDEX idx_bill_ref (bill_ref_number),
    INDEX idx_msisdn (msisdn),
    INDEX idx_processed (processed),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create unmatched_payments view for easy querying
CREATE OR REPLACE VIEW vw_unmatched_payments AS
SELECT 
    p.id,
    p.mpesa_receipt_number,
    p.amount,
    p.transaction_date,
    p.sender_phone,
    p.sender_name,
    p.paybill_account,
    p.reconciliation_status,
    p.created_at,
    m.id as member_id,
    m.member_number,
    u.first_name,
    u.last_name,
    m.id_number,
    u.phone as phone_number
FROM payments p
LEFT JOIN members m ON p.member_id = m.id
LEFT JOIN users u ON m.user_id = u.id
WHERE p.reconciliation_status = 'unmatched'
ORDER BY p.transaction_date DESC;

-- Create pending_reconciliation view
CREATE OR REPLACE VIEW vw_pending_reconciliation AS
SELECT 
    c.id as callback_id,
    c.trans_id,
    c.trans_amount,
    c.trans_time,
    c.msisdn,
    c.bill_ref_number,
    CONCAT(c.first_name, ' ', COALESCE(c.middle_name, ''), ' ', c.last_name) as sender_name,
    c.processed,
    c.created_at,
    m.id as potential_member_id,
    m.member_number,
    CONCAT(u.first_name, ' ', u.last_name) as member_name,
    u.phone as member_phone,
    CASE 
        WHEN m.id_number = c.bill_ref_number THEN 'id_match'
        WHEN u.phone = c.msisdn THEN 'phone_match'
        WHEN m.member_number = c.bill_ref_number THEN 'member_number_match'
        ELSE 'no_match'
    END as match_type
FROM mpesa_c2b_callbacks c
LEFT JOIN members m ON (
    m.id_number = c.bill_ref_number 
    OR m.member_number = c.bill_ref_number
)
LEFT JOIN users u ON m.user_id = u.id AND u.phone = c.msisdn
WHERE c.processed = FALSE
ORDER BY c.created_at DESC;
