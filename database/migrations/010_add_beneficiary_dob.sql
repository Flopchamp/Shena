-- Migration: Add date_of_birth to beneficiaries
-- Adds a nullable DATE column used for age-based contribution rules
ALTER TABLE beneficiaries
ADD COLUMN date_of_birth DATE NULL AFTER relationship;
