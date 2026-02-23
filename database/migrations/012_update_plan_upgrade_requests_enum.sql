-- Migration: Update plan_upgrade_requests ENUM columns
-- Description: Allow all package types for plan upgrades
-- Date: 2026-02-22

ALTER TABLE plan_upgrade_requests
    MODIFY from_package ENUM('individual', 'couple', 'family', 'executive') NOT NULL,
    MODIFY to_package ENUM('individual', 'couple', 'family', 'executive') NOT NULL;

-- Also update plan_upgrade_history if needed
ALTER TABLE plan_upgrade_history
    MODIFY from_package ENUM('individual', 'couple', 'family', 'executive') NOT NULL,
    MODIFY to_package ENUM('individual', 'couple', 'family', 'executive') NOT NULL;
