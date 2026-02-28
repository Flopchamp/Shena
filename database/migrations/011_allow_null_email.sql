-- Migration: Allow NULL emails for users (make email optional)
ALTER TABLE users
MODIFY COLUMN email varchar(255) COLLATE utf8mb4_unicode_ci NULL;
