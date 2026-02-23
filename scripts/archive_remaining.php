<?php
$root = realpath(__DIR__ . '/..');
$archives = $root . DIRECTORY_SEPARATOR . 'archives';
if (!is_dir($archives)) mkdir($archives, 0755, true);
$d = date('Y-m-d_H-i-s');
$zipPath = $archives . DIRECTORY_SEPARATOR . "remaining_files_{$d}.zip";
$files = [
    'AGENT_PORTAL_REORGANIZATION.md',
    'CLAIMS_SYSTEM_IMPLEMENTATION.md',
    'MODAL_SYSTEM_DOCUMENTATION.md',
    'PHASE1_IMPLEMENTATION.md',
    'STK_PUSH_QUICK_START.md',
    'deleted_list.txt',
    'fix_leaderboard_view.php',
    'modal-demo.html',
    'run_migration.php',
    'run_phase1_migration.php',
    'run_phase3_migration.php',
    'run_phase4_migration.php',
    'setup.php',
    'test_email_fallback.php',
    'test_phase1_service_claims.php',
    'test_phase2.php',
    'test_phase2_reconciliation.php',
    'test_phase3.php',
    'test_stk_push.php',
];
$existing = [];
foreach ($files as $f) {
    $p = $root . DIRECTORY_SEPARATOR . $f;
    if (file_exists($p)) $existing[$f] = $p;
}
if (count($existing) === 0) {
    echo "NO_FILES_EXIST\n";
    exit(0);
}
$zip = new ZipArchive();
if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
    echo "FAILED_OPEN $zipPath\n";
    exit(1);
}
foreach ($existing as $name => $path) {
    $zip->addFile($path, $name);
}
$zip->close();
echo "CREATED $zipPath\n";
