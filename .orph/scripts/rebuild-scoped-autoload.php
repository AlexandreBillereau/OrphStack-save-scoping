<?php

declare(strict_types=1);

/**
 * Regénère l'autoloader Composer dans composer-build/ après php-scoper.
 * Les fichiers autoload générés par le scope sont incomplets ; un dump-autoload
 * produit un classmap cohérent avec les namespaces préfixés.
 */

$pluginRoot = dirname(__DIR__, 2);
$buildDir   = $pluginRoot . '/composer-build';

if (! is_dir($buildDir)) {
  fwrite(STDERR, "[rebuild-scoped-autoload] composer-build/ introuvable. Lancez d'abord composer install.\n");
  exit(1);
}

$filesAutoload = $pluginRoot . '/vendor/composer/autoload_files.php';
$bootstrapFiles = [];

if (is_file($filesAutoload)) {
  $relativeFiles = require $filesAutoload;

  foreach ($relativeFiles as $file) {
    $relative = str_replace($pluginRoot . '/vendor/', '', $file);

    if ($relative !== $file && is_file($buildDir . '/' . $relative)) {
      $bootstrapFiles[] = $relative;
    }
  }
}

$composerConfig = [
  'name'        => 'test-vendor/scoped-runtime',
  'description' => 'Autoload régénéré après php-scoper (ne pas éditer à la main).',
  'autoload'    => [
    'classmap' => ['.'],
    'files'    => $bootstrapFiles,
    'exclude-from-classmap' => [
      '/composer/',
      '/bin/',
    ],
  ],
  'config' => [
    'classmap-authoritative' => true,
    'sort-packages'          => true,
  ],
];

$composerJsonPath = $buildDir . '/composer.json';
file_put_contents(
  $composerJsonPath,
  json_encode($composerConfig, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n"
);

// Retirer l'autoload cassé par php-scoper pour laisser Composer le régénérer proprement.
$pathsToRemove = [
  $buildDir . '/autoload.php',
  $buildDir . '/composer',
  $buildDir . '/vendor',
];

foreach ($pathsToRemove as $path) {
  if (! file_exists($path)) {
    continue;
  }

  if (is_dir($path)) {
    $iterator = new RecursiveIteratorIterator(
      new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS),
      RecursiveIteratorIterator::CHILD_FIRST
    );

    foreach ($iterator as $file) {
      $file->isDir() ? rmdir($file->getRealPath()) : unlink($file->getRealPath());
    }

    rmdir($path);
    continue;
  }

  unlink($path);
}

$command = sprintf(
  'composer dump-autoload --no-interaction --working-dir=%s 2>&1',
  escapeshellarg($buildDir)
);

passthru($command, $exitCode);

if ($exitCode !== 0) {
  fwrite(STDERR, "[rebuild-scoped-autoload] Échec de composer dump-autoload.\n");
  exit($exitCode);
}

exit(0);
