<?php
/**
 * Application Configuration
 */

// Environment Settings
define('DEBUG_MODE', true);
define('APP_NAME', 'Shena Companion Welfare Association');
define('APP_URL', 'http://localhost');

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'shena_welfare_db');
define('DB_USER', 'root');
define('DB_PASS', '4885'); // XAMPP default has no password for root
define('DB_CHARSET', 'utf8mb4');
define('DB_FILE', ROOT_PATH . '/database/shena_welfare.db'); // SQLite database file

// M-Pesa Configuration (Placeholder)
define('MPESA_CONSUMER_KEY', 'your_consumer_key_here');
define('MPESA_CONSUMER_SECRET', 'your_consumer_secret_here');
define('MPESA_BUSINESS_SHORTCODE', '4163987');
define('MPESA_PASSKEY', 'your_passkey_here');
define('MPESA_CALLBACK_URL', APP_URL . '/api/mpesa/callback');

// Email Configuration (SMTP)
define('MAIL_HOST', 'smtp.gmail.com');
define('MAIL_PORT', 587);
define('MAIL_USERNAME', 'your_email@gmail.com');
define('MAIL_PASSWORD', 'your_app_password');
define('MAIL_FROM_EMAIL', 'noreply@shenacompanion.org');
define('MAIL_FROM_NAME', 'Shena Companion Welfare Association');

// Twilio SMS Configuration (Placeholder)
define('TWILIO_SID', 'your_twilio_sid_here');
define('TWILIO_AUTH_TOKEN', 'your_twilio_auth_token_here');
define('TWILIO_PHONE_NUMBER', '+1234567890');

// Security Settings
define('ENCRYPTION_KEY', 'your-32-character-secret-key-here');
define('JWT_SECRET', 'your-jwt-secret-key-here');
define('SESSION_LIFETIME', 7200); // 2 hours

// File Upload Settings
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_FILE_TYPES', ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx']);

// Payment Settings
define('REGISTRATION_FEE', 200); // KES 200
define('REACTIVATION_FEE', 100); // KES 100

// Grace Period Settings (in months)
define('GRACE_PERIOD_UNDER_80', 4);
define('GRACE_PERIOD_80_AND_ABOVE', 5);

// Membership Package Prices
$membership_packages = [
    'individual' => [
        'name' => 'Individual Package',
        'base_price' => 500, // Base monthly contribution
        'age_multipliers' => [
            '18-30' => 1.0,
            '31-50' => 1.2,
            '51-65' => 1.5,
            '66-80' => 1.8,
            '81-100' => 2.0
        ]
    ],
    'couple' => [
        'name' => 'Couple Package',
        'base_price' => 800,
        'discount' => 0.1 // 10% discount
    ],
    'family' => [
        'name' => 'Family Package',
        'base_price' => 1200,
        'discount' => 0.15, // 15% discount
        'max_members' => 6
    ],
    'executive' => [
        'name' => 'Executive Package',
        'base_price' => 2000,
        'premium_services' => true
    ]
];

// Timezone
date_default_timezone_set('Africa/Nairobi');
