<?php
define('ROOT_PATH', __DIR__);
require 'config/config.php';
require 'app/core/Database.php';

$db = Database::getInstance();
$members = $db->getConnection()->query('
    SELECT m.id, m.member_number, m.status, m.maturity_ends, u.first_name, u.last_name, u.email, u.phone 
    FROM members m 
    JOIN users u ON m.user_id = u.id 
    ORDER BY m.id DESC 
    LIMIT 1
')->fetchAll(PDO::FETCH_ASSOC);

echo "Latest Member:\n";
echo str_repeat('=', 80) . "\n";
if (empty($members)) {
    echo "No members found\n";
} else {
    foreach ($members[0] as $key => $value) {
        echo str_pad($key . ':', 20) . $value . "\n";
    }
}
