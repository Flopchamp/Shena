-- Migration: Add notification action links and next of kin relationship

ALTER TABLE members
    ADD COLUMN next_of_kin_relationship VARCHAR(50) NULL AFTER next_of_kin;

ALTER TABLE communications
    ADD COLUMN action_url VARCHAR(255) NULL AFTER message,
    ADD COLUMN action_text VARCHAR(100) NULL AFTER action_url;
