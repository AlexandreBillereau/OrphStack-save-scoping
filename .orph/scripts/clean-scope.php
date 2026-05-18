<?php

$target = __DIR__ . '/../../composer-build';

if (is_dir($target)) {
  $dir = new RecursiveDirectoryIterator($target, RecursiveDirectoryIterator::SKIP_DOTS);
  $files = new RecursiveIteratorIterator($dir, RecursiveIteratorIterator::CHILD_FIRST);

  foreach ($files as $file) {
    if ($file->isDir()) {
      rmdir($file->getRealPath());
    } else {
      unlink($file->getRealPath());
    }
  }
  rmdir($target);
}
