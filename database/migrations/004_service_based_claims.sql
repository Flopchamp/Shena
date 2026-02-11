-- Migration: 004_service_based_claims.sql
-- Convert claims from money-based to service-based processing
-- Aligned with SHENA Companion Policy Booklet January 2026

USE shena_welfare_db;

-- Modify claims table structure (split into separate statements for compatibility)
ALTER TABLE claims MODIFY COLUMN claim_amount DECIMAL(10,2) DEFAULT 0 COMMENT 'Deprecated - for reference only';

ALTER TABLE claims MODIFY COLUMN approved_amount DECIMAL(10,2) DEFAULT 0 COMMENT 'Only used for cash alternative';

-- Ensure member view fields exist (safe to skip if already present)
ALTER TABLE claims ADD COLUMN place_of_death VARCHAR(200) NULL;

ALTER TABLE claims ADD COLUMN cause_of_death TEXT;

ALTER TABLE claims ADD COLUMN mortuary_name VARCHAR(200);

ALTER TABLE claims ADD COLUMN mortuary_bill_amount DECIMAL(10,2) DEFAULT 0;

ALTER TABLE claims ADD COLUMN service_delivery_type ENUM('standard_services', 'cash_alternative') DEFAULT 'standard_services';

ALTER TABLE claims ADD COLUMN cash_alternative_reason TEXT;

ALTER TABLE claims ADD COLUMN cash_alternative_agreement_signed BOOLEAN DEFAULT FALSE;

ALTER TABLE claims ADD COLUMN cash_alternative_amount DECIMAL(10,2) DEFAULT 20000.00;

ALTER TABLE claims ADD COLUMN mortuary_bill_settled BOOLEAN DEFAULT FALSE;

ALTER TABLE claims ADD COLUMN body_dressing_completed BOOLEAN DEFAULT FALSE;

ALTER TABLE claims ADD COLUMN coffin_delivered BOOLEAN DEFAULT FALSE;

ALTER TABLE claims ADD COLUMN transportation_arranged BOOLEAN DEFAULT FALSE;

ALTER TABLE claims ADD COLUMN equipment_delivered BOOLEAN DEFAULT FALSE;

ALTER TABLE claims ADD COLUMN services_delivery_date DATE NULL;

ALTER TABLE claims ADD COLUMN mortuary_days_count INT DEFAULT 0;

ALTER TABLE claims ADD COLUMN mortuary_bill_reference VARCHAR(100);

ALTER TABLE claims MODIFY COLUMN status ENUM('submitted', 'under_review', 'approved', 'services_arranged', 'completed', 'rejected') DEFAULT 'submitted';

-- Add indexes for filtering
CREATE INDEX idx_claims_service_type ON claims(service_delivery_type);
CREATE INDEX idx_claims_delivery_date ON claims(services_delivery_date);
CREATE INDEX idx_claims_mortuary_settled ON claims(mortuary_bill_settled);

-- Create table for service delivery checklist tracking
CREATE TABLE IF NOT EXISTS claim_service_checklist (
    id INT AUTO_INCREMENT PRIMARY KEY,
    claim_id INT NOT NULL,
    service_type ENUM('mortuary_bill', 'body_dressing', 'coffin', 'transportation', 'equipment') NOT NULL,
    completed BOOLEAN DEFAULT FALSE,
    completed_at TIMESTAMP NULL,
    completed_by INT NULL COMMENT 'Admin/staff who marked completed',
    service_notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (claim_id) REFERENCES claims(id) ON DELETE CASCADE,
    FOREIGN KEY (completed_by) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_claim_service (claim_id, service_type),
    INDEX idx_completed (completed)
) ENGINE=InnoDB;

-- Create table for cash alternative agreements
CREATE TABLE IF NOT EXISTS claim_cash_alternative_agreements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    claim_id INT NOT NULL UNIQUE,
    reason_category ENUM('security_risk', 'client_request', 'logistical_issue', 'other') NOT NULL,
    detailed_reason TEXT NOT NULL,
    requested_by ENUM('company', 'client') NOT NULL,
    agreement_signed BOOLEAN DEFAULT FALSE,
    signature_document_path VARCHAR(500),
    amount_paid DECIMAL(10,2) DEFAULT 20000.00,
    payment_method ENUM('mpesa', 'bank', 'cash') DEFAULT 'mpesa',
    payment_reference VARCHAR(100),
    paid_at TIMESTAMP NULL,
    approved_by INT NOT NULL,
    approved_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    notes TEXT,
    FOREIGN KEY (claim_id) REFERENCES claims(id) ON DELETE CASCADE,
    FOREIGN KEY (approved_by) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_claim_agreement (claim_id)
) ENGINE=InnoDB;

-- Update existing claims to default values
UPDATE claims 
SET 
    service_delivery_type = 'standard_services',
    mortuary_bill_settled = FALSE,
    body_dressing_completed = FALSE,
    coffin_delivered = FALSE,
    transportation_arranged = FALSE,
    equipment_delivered = FALSE,
    mortuary_days_count = 0
WHERE service_delivery_type IS NULL;

-- Add comments to document the service-based approach
ALTER TABLE claims 
    COMMENT = 'Claims for funeral services per SHENA Companion Policy - Service delivery is default, cash alternative only in exceptional cases';
