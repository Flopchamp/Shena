-- Add maturity_ends column to members table if it doesn't exist
-- Run this SQL in your database

ALTER TABLE members 
ADD COLUMN IF NOT EXISTS maturity_ends DATE NULL AFTER status;

-- Verify the column was added
DESCRIBE members;
