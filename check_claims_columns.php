<?php
/**
 * Check Claims Table Columns
 */
$pdo = new PDO('mysql:host=localhost;dbname=shena_welfare_db;charset=utf8mb4', 'root', '4885');
$stmt = $pdo->query('DESCRIBE claims');
$columns = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "Claims Table Columns:\n\n";
echo str_pad('Field', 35) . str_pad('Type', 30) . "Nullable\n";
echo str_repeat('-', 75) . "\n";

foreach ($columns as $col) {
    echo sprintf(
        "%-35s %-30s %s\n", 
        $col['Field'], 
        $col['Type'], 
        $col['Null'] === 'YES' ? 'YES' : 'NO'
    );
}

echo "\n";

// Check for required member form fields
$requiredFields = [
    'deceased_name',
    'deceased_id_number',
    'date_of_death',
    'place_of_death',
    'cause_of_death',
    'mortuary_name',
    'mortuary_bill_amount',
    'mortuary_days_count',
    'service_delivery_type',
    'cash_alternative_amount',
    'cash_alternative_reason',
    'cash_alternative_agreement_signed'
];

echo "Checking Required Member Form Fields:\n";
echo str_repeat('-', 75) . "\n";

$columnNames = array_column($columns, 'Field');

foreach ($requiredFields as $field) {
    $exists = in_array($field, $columnNames);
    $status = $exists ? '✓' : '✗';
    echo "$status $field\n";
}
