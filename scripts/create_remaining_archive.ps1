$d = Get-Date -Format 'yyyy-MM-dd_HH-mm-ss'
New-Item -ItemType Directory -Force archives | Out-Null
$files = @(
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
    'test_stk_push.php'
)
$existing = $files | Where-Object { Test-Path $_ }
if ($existing.Count -gt 0) {
    $dest = "archives/remaining_files_$d.zip"
    Compress-Archive -Path $existing -DestinationPath $dest -Force
    Write-Output "CREATED $dest"
} else {
    Write-Output "NO_FILES_EXIST"
}
