-- Migration: Add maturity_ends and coverage_ends columns
-- Purpose: Track waiting period end date and coverage end date based on paid contributions
-- Created: 2026-01-31

ALTER TABLE members 
ADD COLUMN maturity_ends DATE NULL AFTER status,
ADD COLUMN coverage_ends DATE NULL AFTER maturity_ends,
ADD COLUMN package_key VARCHAR(100) DEFAULT NULL AFTER package;

-- Add index for maturity_ends for efficient queries
CREATE INDEX idx_members_maturity_ends ON members(maturity_ends);

-- Add index for coverage_ends
CREATE INDEX idx_members_coverage_ends ON members(coverage_ends);
