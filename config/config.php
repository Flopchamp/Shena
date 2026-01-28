<?php
/**
 * Application Configuration
 */

// Environment Settings
define('DEBUG_MODE', false); // CRITICAL: Set to false in production
define('APP_NAME', 'Shena Companion Welfare Association');
define('APP_URL', 'http://localhost');

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'shena_welfare_db');
define('DB_USER', 'root');
define('DB_PASS', '4885');
define('DB_CHARSET', 'utf8mb4');
define('DB_FILE', ROOT_PATH . '/database/shena_welfare.db');

// M-Pesa Configuration
define('MPESA_CONSUMER_KEY', getenv('MPESA_CONSUMER_KEY') ?: 'your_consumer_key_here');
define('MPESA_CONSUMER_SECRET', getenv('MPESA_CONSUMER_SECRET') ?: 'your_consumer_secret_here');
define('MPESA_BUSINESS_SHORTCODE', '4163987');
define('MPESA_PASSKEY', getenv('MPESA_PASSKEY') ?: 'your_passkey_here');
define('MPESA_CALLBACK_URL', APP_URL . '/api/mpesa/callback');

// Email Configuration (SMTP)
define('MAIL_ENABLED', false);
define('MAIL_HOST', 'smtp.gmail.com');
define('MAIL_PORT', 587);
define('MAIL_USERNAME', getenv('MAIL_USERNAME') ?: 'your_email@gmail.com');
define('MAIL_PASSWORD', getenv('MAIL_PASSWORD') ?: 'your_app_password');
define('MAIL_FROM_EMAIL', 'noreply@shenacompanion.org');
define('MAIL_FROM_NAME', 'Shena Companion Welfare Association');

// Twilio SMS Configuration
define('TWILIO_SID', getenv('TWILIO_SID') ?: 'your_twilio_sid_here');
define('TWILIO_AUTH_TOKEN', getenv('TWILIO_AUTH_TOKEN') ?: 'your_twilio_auth_token_here');
define('TWILIO_PHONE_NUMBER', '+1234567890');

// Security Settings - CRITICAL: Change these in production
define('ENCRYPTION_KEY', getenv('ENCRYPTION_KEY') ?: bin2hex(random_bytes(16)));
define('JWT_SECRET', getenv('JWT_SECRET') ?: bin2hex(random_bytes(32)));
define('SESSION_LIFETIME', 7200); // 2 hours

// File Upload Settings
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_FILE_TYPES', ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx']);
define('UPLOAD_PATH', ROOT_PATH . '/storage/uploads');

// Payment Settings
define('REGISTRATION_FEE', 200); // Ksh. 200
define('REACTIVATION_FEE', 100); // Ksh. 100

// Membership Maturity & Grace Period Settings (in months)
// MATURITY PERIOD: Waiting period before coverage becomes active
define('MATURITY_PERIOD_UNDER_80', 4); // 4 months for ages 1-80 years
define('MATURITY_PERIOD_80_AND_ABOVE', 5); // 5 months for ages 81-100 years

// GRACE PERIOD: Allowance for late payments (in months)
define('GRACE_PERIOD_MONTHS', 2); // 2 months max before default

// Mortuary Bill Payment Cap
define('MORTUARY_DAYS_COVERED', 14); // Maximum 14 days of mortuary preservation covered

// Exclusion death causes (policy-based)
define('EXCLUDED_CAUSES', [
    'self_medication',
    'drug_abuse',
    'substance_abuse',
    'criminal_activity',
    'civil_commotion',
    'riots',
    'war',
    'terrorism',
    'hazardous_activities'
]);

// Membership Package Prices - Aligned with Policy Booklet
$membership_packages = [
    // INDIVIDUAL PACKAGES BY AGE
    'individual_below_70' => [
        'name' => 'Individual Below 70 Years',
        'description' => 'Individual coverage for members below 70 years',
        'monthly_contribution' => 100,
        'age_min' => 18,
        'age_max' => 69,
        'category' => 'individual',
        'coverage_type' => 'principal_only',
        'services' => 'all'
    ],
    'individual_71_80' => [
        'name' => 'Individual 71-80 Years',
        'description' => 'Individual coverage for members aged 71-80 years',
        'monthly_contribution' => 350,
        'age_min' => 71,
        'age_max' => 80,
        'category' => 'individual',
        'coverage_type' => 'principal_only',
        'services' => 'all'
    ],
    'individual_81_90' => [
        'name' => 'Individual 81-90 Years',
        'description' => 'Individual coverage for members aged 81-90 years',
        'monthly_contribution' => 450,
        'age_min' => 81,
        'age_max' => 90,
        'category' => 'individual',
        'coverage_type' => 'principal_only',
        'services' => 'all'
    ],
    'individual_91_100' => [
        'name' => 'Individual 91-100 Years',
        'description' => 'Individual coverage for members aged 91-100 years',
        'monthly_contribution' => 650,
        'age_min' => 91,
        'age_max' => 100,
        'category' => 'individual',
        'coverage_type' => 'principal_only',
        'services' => 'all'
    ],

    // COUPLE PACKAGES BY AGE
    'couple_below_70' => [
        'name' => 'Couple Below 70 Years',
        'description' => 'Coverage for couples below 70 years',
        'monthly_contribution' => 150,
        'age_min' => 18,
        'age_max' => 69,
        'category' => 'couple',
        'coverage_type' => 'couple',
        'services' => 'all'
    ],

    // FAMILY PACKAGES (Couples + Children)
    'couple_children_below_70' => [
        'name' => 'Couple & Children Below 70 Years',
        'description' => 'Coverage for couple and children below 18 years',
        'monthly_contribution' => 200,
        'age_min' => 18,
        'age_max' => 69,
        'category' => 'family',
        'coverage_type' => 'couple_children',
        'max_children' => 10,
        'services' => 'all'
    ],

    // EXTENDED FAMILY PACKAGES (Couples + Children + Parents)
    'couple_children_parents_below_70' => [
        'name' => 'Couple, Children & Parents Below 70 Years',
        'description' => 'Coverage for couple, children and parents below 70 years',
        'monthly_contribution' => 250,
        'age_min' => 18,
        'age_max' => 69,
        'category' => 'extended_family',
        'coverage_type' => 'couple_children_parents',
        'max_children' => 10,
        'max_parents' => 4,
        'services' => 'all'
    ],

    // MAXIMUM FAMILY PACKAGES (Couples + Children + Parents + In-laws)
    'couple_children_parents_inlaws_below_70' => [
        'name' => 'Couple, Children, Parents & In-laws Below 70 Years',
        'description' => 'Coverage for couple, children, parents and in-laws below 70 years',
        'monthly_contribution' => 300,
        'age_min' => 18,
        'age_max' => 69,
        'category' => 'maximum_family',
        'coverage_type' => 'couple_children_parents_inlaws',
        'max_children' => 10,
        'max_parents' => 4,
        'max_inlaws' => 4,
        'services' => 'all'
    ],

    // FAMILY PACKAGES FOR 70-80 YEARS
    'couple_children_parents_70_80' => [
        'name' => 'Couple, Children & Parents 70-80 Years',
        'description' => 'Coverage for couple, children and parents aged 70-80 years',
        'monthly_contribution' => 350,
        'age_min' => 70,
        'age_max' => 80,
        'category' => 'extended_family',
        'coverage_type' => 'couple_children_parents',
        'max_children' => 10,
        'max_parents' => 4,
        'services' => 'all'
    ],

    'couple_children_parents_inlaws_71_80' => [
        'name' => 'Couple, Children, Parents & In-laws 71-80 Years',
        'description' => 'Coverage for couple, children, parents and in-laws aged 71-80 years',
        'monthly_contribution' => 400,
        'age_min' => 71,
        'age_max' => 80,
        'category' => 'maximum_family',
        'coverage_type' => 'couple_children_parents_inlaws',
        'max_children' => 10,
        'max_parents' => 4,
        'max_inlaws' => 4,
        'services' => 'all'
    ],

    // FAMILY PACKAGES FOR 81-90 YEARS
    'couple_children_parents_81_90' => [
        'name' => 'Couple, Children & Parents 81-90 Years',
        'description' => 'Coverage for couple, children and parents aged 81-90 years',
        'monthly_contribution' => 450,
        'age_min' => 81,
        'age_max' => 90,
        'category' => 'extended_family',
        'coverage_type' => 'couple_children_parents',
        'max_children' => 10,
        'max_parents' => 4,
        'services' => 'all'
    ],

    'couple_children_parents_inlaws_81_90' => [
        'name' => 'Couple, Children, Parents & In-laws 81-90 Years',
        'description' => 'Coverage for couple, children, parents and in-laws aged 81-90 years',
        'monthly_contribution' => 550,
        'age_min' => 81,
        'age_max' => 90,
        'category' => 'maximum_family',
        'coverage_type' => 'couple_children_parents_inlaws',
        'max_children' => 10,
        'max_parents' => 4,
        'max_inlaws' => 4,
        'services' => 'all'
    ],

    // FAMILY PACKAGES FOR 91-100 YEARS
    'couple_children_parents_91_100' => [
        'name' => 'Couple, Children & Parents 91-100 Years',
        'description' => 'Coverage for couple, children and parents aged 91-100 years',
        'monthly_contribution' => 650,
        'age_min' => 91,
        'age_max' => 100,
        'category' => 'extended_family',
        'coverage_type' => 'couple_children_parents',
        'max_children' => 10,
        'max_parents' => 4,
        'services' => 'all'
    ],

    'couple_children_parents_inlaws_91_100' => [
        'name' => 'Couple, Children, Parents & In-laws 91-100 Years',
        'description' => 'Coverage for couple, children, parents and in-laws aged 91-100 years',
        'monthly_contribution' => 650,
        'age_min' => 91,
        'age_max' => 100,
        'category' => 'maximum_family',
        'coverage_type' => 'couple_children_parents_inlaws',
        'max_children' => 10,
        'max_parents' => 4,
        'max_inlaws' => 4,
        'services' => 'all'
    ],

    // EXECUTIVE PACKAGES
    'executive_below_70' => [
        'name' => 'Executive Package Below 70 Years',
        'description' => 'Premium executive coverage for individuals below 70 years with enhanced services',
        'monthly_contribution' => 400,
        'age_min' => 18,
        'age_max' => 69,
        'category' => 'executive',
        'coverage_type' => 'executive',
        'premium_features' => true,
        'services' => 'all_premium'
    ],

    'executive_above_70' => [
        'name' => 'Executive Package Above 70 Years',
        'description' => 'Premium executive coverage for individuals above 70 years with enhanced services',
        'monthly_contribution' => 800,
        'age_min' => 70,
        'age_max' => 100,
        'category' => 'executive',
        'coverage_type' => 'executive',
        'premium_features' => true,
        'services' => 'all_premium'
    ]
];

// Core Services Available in All Packages
$core_services = [
    'mortuary_bill' => [
        'name' => 'Mortuary Bill Payment',
        'description' => 'Payment of mortuary preservation costs up to 14 days',
        'max_days' => 14,
        'includes' => 'Preservation costs only (excludes admission and dressing charges)'
    ],
    'body_dressing' => [
        'name' => 'Body Dressing',
        'description' => 'Professional and dignified body dressing services',
        'includes' => 'Preparation for viewing and burial as per cultural/religious preferences'
    ],
    'transportation' => [
        'name' => 'Body Transportation',
        'description' => 'Secure and respectful transportation of deceased',
        'includes' => 'From mortuary to funeral venue and final resting place'
    ],
    'coffin' => [
        'name' => 'Executive Coffin',
        'description' => 'High-quality executive coffin',
        'includes' => 'Suitable for viewing and burial'
    ],
    'burial_equipment' => [
        'name' => 'Burial Equipment',
        'description' => 'Professional burial equipment and ceremony setup',
        'includes' => 'Lowering gear, trolley, gazebo tent, and 100 chairs'
    ]
];

// Timezone
date_default_timezone_set('Africa/Nairobi');