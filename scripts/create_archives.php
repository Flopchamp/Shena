<?php
// Create archives/ and archive the repository and (if present) deleted tracked files list
$root = realpath(__DIR__ . '/..');
$archives = $root . DIRECTORY_SEPARATOR . 'archives';
if (!is_dir($archives)) mkdir($archives, 0755, true);

$d = date('Y-m-d_H-i-s');
$repoArchive = $archives . DIRECTORY_SEPARATOR . "repo_{$d}.zip";
echo "Creating repo archive: $repoArchive\n";
exec("git -C " . escapeshellarg($root) . " archive --format=zip --output=" . escapeshellarg($repoArchive) . " HEAD", $out, $rc);
if ($rc === 0) {
    echo "CREATED: $repoArchive\n";
} else {
    echo "FAILED to create repo archive (exit $rc)\n";
}

$deletedList = $root . DIRECTORY_SEPARATOR . 'deleted_list.txt';
if (file_exists($deletedList) && filesize($deletedList) > 0) {
    $lines = file($deletedList, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $paths = array_map('trim', $lines);
    $paths = array_filter($paths, function($p) { return $p !== ''; });
    if (count($paths) > 0) {
        $delArchive = $archives . DIRECTORY_SEPARATOR . "deleted_files_{$d}.zip";
        echo "Creating deleted-files archive: $delArchive\n";
        $escaped = array_map('escapeshellarg', $paths);
        $cmd = "git -C " . escapeshellarg($root) . " archive --format=zip --output=" . escapeshellarg($delArchive) . " HEAD " . implode(' ', $escaped);
        exec($cmd, $out2, $rc2);
        if ($rc2 === 0) {
            echo "CREATED: $delArchive\n";
        } else {
            echo "FAILED to create deleted-files archive (exit $rc2)\n";
        }
    } else {
        echo "deleted_list.txt empty; skipped deleted files archive.\n";
    }
} else {
    echo "No deleted_list.txt present or file empty; skipped deleted files archive.\n";
}

echo "Done.\n";
