-- Migration Script: Add Dependents Support and Update Claims Table
-- Run this script to update existing database with new features
-- Date: January 29, 2026

USE shena_welfare_db;

-- Step 1: Create dependents table
CREATE TABLE IF NOT EXISTS dependents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    member_id INT NOT NULL,
    full_name VARCHAR(200) NOT NULL,
    relationship ENUM('spouse', 'child', 'parent', 'father_in_law', 'mother_in_law') NOT NULL,
    id_number VARCHAR(20),
    birth_certificate VARCHAR(50),
    date_of_birth DATE NOT NULL,
    gender ENUM('male', 'female') NOT NULL,
    phone_number VARCHAR(20),
    is_covered BOOLEAN DEFAULT TRUE,
    coverage_start_date DATE NOT NULL,
    coverage_end_date DATE NULL,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE CASCADE,
    INDEX idx_dependents_member_id (member_id),
    INDEX idx_dependents_relationship (relationship),
    INDEX idx_dependents_is_covered (is_covered)
);

-- Step 2: Add dependent-related columns to claims table
ALTER TABLE claims 
ADD COLUMN IF NOT EXISTS dependent_id INT NULL AFTER beneficiary_id,
ADD COLUMN IF NOT EXISTS deceased_type ENUM('member', 'dependent') DEFAULT 'member' AFTER dependent_id,
ADD COLUMN IF NOT EXISTS date_of_birth DATE NULL AFTER deceased_id_number;

-- Step 3: Add foreign key for dependent_id
ALTER TABLE claims 
ADD CONSTRAINT fk_claims_dependent 
FOREIGN KEY (dependent_id) REFERENCES dependents(id) ON DELETE SET NULL;

-- Step 4: Add indexes for better performance
CREATE INDEX IF NOT EXISTS idx_claims_dependent_id ON claims(dependent_id);
CREATE INDEX IF NOT EXISTS idx_claims_deceased_type ON claims(deceased_type);

-- Step 5: Update existing claims to have deceased_type = 'member'
UPDATE claims SET deceased_type = 'member' WHERE deceased_type IS NULL;

-- Migration complete
SELECT 'Migration completed successfully! Dependents table created and claims table updated.' as status;
