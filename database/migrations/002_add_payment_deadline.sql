-- Migration: Add payment_deadline to members table
-- Purpose: Track 2-week deadline for office/cash payments after registration
-- Created: <?php echo date('Y-m-d H:i:s'); ?>


ALTER TABLE members 
ADD COLUMN payment_deadline DATE NULL AFTER maturity_ends,
ADD COLUMN pending_payment_type ENUM('registration', 'monthly', 'reactivation') NULL AFTER payment_deadline;

-- Update existing members - no deadline since they're already registered
UPDATE members SET payment_deadline = NULL WHERE status != 'pending_payment';
