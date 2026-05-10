<?php

/**
 * QA smoke pass — Member C deliverable.
 * Runs PHP syntax lint on all application sources (no DB required).
 *
 * Usage: php scripts/qa-smoke.php
 * Exit code: 0 if all files pass, 1 otherwise.
 */

declare(strict_types=1);

$root = dirname(__DIR__);

$dirs = [
    $root . DIRECTORY_SEPARATOR . 'public',
    $root . DIRECTORY_SEPARATOR . 'src',
];

$files = [];
foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        fwrite(STDERR, "Missing directory: {$dir}\n");
        exit(1);
    }
    $it = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS)
    );
    foreach ($it as $file) {
        if ($file->isFile() && strcasecmp($file->getExtension(), 'php') === 0) {
            $files[] = $file->getPathname();
        }
    }
}

sort($files);

$failed = 0;
$php = PHP_BINARY;

foreach ($files as $path) {
    $cmd = escapeshellarg($php) . ' -l ' . escapeshellarg($path) . ' 2>&1';
    $out = [];
    $code = 0;
    exec($cmd, $out, $code);
    $line = implode("\n", $out);
    if ($code !== 0) {
        fwrite(STDERR, $line . "\n");
        $failed++;
    } else {
        echo $line . "\n";
    }
}

if ($failed > 0) {
    fwrite(STDERR, "\n{$failed} file(s) failed lint.\n");
    exit(1);
}

echo "\nOK — " . count($files) . " PHP file(s) passed syntax check.\n";
