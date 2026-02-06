<?php
/**
 * Create missing tables for Phase 1
 */

define('ROOT_PATH', __DIR__);
define('CONFIG_PATH', ROOT_PATH . '/config');

require_once CONFIG_PATH . '/config.php';

try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
    $pdo = new PDO($dsn, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Creating missing tables...\n\n";
    
    // Create claim_service_checklist table
    $sql = "CREATE TABLE IF NOT EXISTS claim_service_checklist (
        id INT AUTO_INCREMENT PRIMARY KEY,
        claim_id INT NOT NULL,
        service_type ENUM('mortuary_bill', 'body_dressing', 'coffin', 'transportation', 'equipment') NOT NULL,
        completed BOOLEAN DEFAULT FALSE,
        completed_at TIMESTAMP NULL,
        completed_by INT NULL,
        service_notes TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (claim_id) REFERENCES claims(id) ON DELETE CASCADE,
        FOREIGN KEY (completed_by) REFERENCES users(id) ON DELETE SET NULL,
        INDEX idx_claim_service (claim_id, service_type),
        INDEX idx_completed (completed)
    ) ENGINE=InnoDB";
    
    $pdo->exec($sql);
    echo "✓ Table 'claim_service_checklist' created\n";
    
    // Create claim_cash_alternative_agreements table
    $sql = "CREATE TABLE IF NOT EXISTS claim_cash_alternative_agreements (
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
    ) ENGINE=InnoDB";
    
    $pdo->exec($sql);
    echo "✓ Table 'claim_cash_alternative_agreements' created\n";
    
    echo "\n✓ All tables created successfully!\n";
    
} catch (PDOException $e) {
    echo "\n✗ Error: " . $e->getMessage() . "\n";
    exit(1);
}
