<?php
// Scans top-level files in project root and reports how many times each
// filename (basename) appears elsewhere in the repository. Excludes vendor and .git directories.

$root = realpath(__DIR__ . '/..');
$items = scandir($root);
$candidates = [];

foreach ($items as $item) {
    if ($item === '.' || $item === '..') continue;
    $path = $root . DIRECTORY_SEPARATOR . $item;
    if (is_dir($path)) continue; // only top-level files
    // ignore important files by default
    $ignore = ['.env', '.env.example', 'composer.json', 'composer.lock', 'vendor', 'README.md', '.gitignore', '.htaccess'];
    if (in_array($item, $ignore, true)) continue;
    $candidates[] = $item;
}

// Gather list of searchable files (code/docs) excluding vendor and .git
$rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root));
$searchFiles = [];
foreach ($rii as $file) {
    if ($file->isDir()) continue;
    $filePath = $file->getPathname();
    // skip vendor and .git
    if (strpos($filePath, DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR) !== false) continue;
    if (strpos($filePath, DIRECTORY_SEPARATOR . '.git' . DIRECTORY_SEPARATOR) !== false) continue;
    // skip binary-like files
    $ext = pathinfo($filePath, PATHINFO_EXTENSION);
    if (in_array(strtolower($ext), ['png','jpg','jpeg','zip','phar','exe','dll','so','bin','tar','gz','mp4','mp3'])) continue;
    $searchFiles[] = $filePath;
}

foreach ($candidates as $file) {
    $count = 0;
    foreach ($searchFiles as $f) {
        // skip the file itself
        if (realpath($f) === realpath($root . DIRECTORY_SEPARATOR . $file)) continue;
        $contents = @file_get_contents($f);
        if ($contents === false) continue;
        if (strpos($contents, $file) !== false) $count++;
    }
    echo str_pad($file, 40) . " -> references: " . $count . "\n";
}

echo "\nNote: references count is number of files where the filename appears (not exact link counts).\n";
