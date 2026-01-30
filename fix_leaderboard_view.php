<?php
define('ROOT_PATH', __DIR__);
require 'config/config.php';
require 'app/core/Database.php';

$db = Database::getInstance()->getConnection();
$db->exec('DROP VIEW IF EXISTS vw_agent_leaderboard');

$sql = "CREATE VIEW vw_agent_leaderboard AS
SELECT 
    a.id,
    a.agent_number,
    CONCAT(u.first_name, ' ', u.last_name) as agent_name,
    a.total_members,
    COALESCE(SUM(CASE WHEN ac.status = 'approved' THEN ac.commission_amount ELSE 0 END), 0) as total_commissions_approved,
    COALESCE(SUM(CASE WHEN ac.status = 'paid' THEN ac.commission_amount ELSE 0 END), 0) as total_commissions_paid,
    COALESCE(SUM(CASE WHEN ac.status = 'paid' AND ac.paid_at >= DATE_SUB(NOW(), INTERVAL 30 DAY) THEN ac.commission_amount ELSE 0 END), 0) as commissions_last_30_days,
    a.status
FROM agents a
JOIN users u ON a.user_id = u.id
LEFT JOIN agent_commissions ac ON ac.agent_id = a.id
WHERE a.status = 'active'
GROUP BY a.id, a.agent_number, u.first_name, u.last_name, a.total_members, a.status
ORDER BY total_commissions_paid DESC, a.total_members DESC";

$db->exec($sql);
echo "✓ View vw_agent_leaderboard created successfully\n";
