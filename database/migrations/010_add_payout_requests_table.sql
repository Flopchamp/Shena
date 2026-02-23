-- Migration: Add Payout Requests Table
-- Description: Creates table for agent payout requests with clear status tracking
-- Date: 2025-02-20

CREATE TABLE payout_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    agent_id INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    payment_method ENUM('mpesa', 'bank_transfer', 'cash') NOT NULL DEFAULT 'mpesa',
    payment_details TEXT NOT NULL,
    status ENUM('requested', 'processing', 'paid', 'rejected') DEFAULT 'requested',
    requested_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    processed_at TIMESTAMP NULL,
    processed_by INT NULL,
    payment_reference VARCHAR(100) NULL,
    notes TEXT NULL,
    admin_notes TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (agent_id) REFERENCES agents(id) ON DELETE CASCADE,
    FOREIGN KEY (processed_by) REFERENCES users(id) ON DELETE SET NULL
);

-- Add indexes for performance
CREATE INDEX idx_payout_requests_agent_id ON payout_requests(agent_id);
CREATE INDEX idx_payout_requests_status ON payout_requests(status);
CREATE INDEX idx_payout_requests_requested_at ON payout_requests(requested_at);

-- Add notification for payout status changes
ALTER TABLE communications ADD COLUMN payout_request_id INT NULL AFTER claim_id;
ALTER TABLE communications ADD CONSTRAINT fk_communications_payout_request 
    FOREIGN KEY (payout_request_id) REFERENCES payout_requests(id) ON DELETE SET NULL;
