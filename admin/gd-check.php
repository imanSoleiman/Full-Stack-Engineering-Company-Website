<?php
header('Content-Type: text/plain; charset=utf-8');

echo 'PHP SAPI: ' . PHP_SAPI . PHP_EOL;
echo 'PHP Version: ' . PHP_VERSION . PHP_EOL;
echo 'Loaded php.ini: ' . (php_ini_loaded_file() ?: 'NONE') . PHP_EOL;
echo 'GD loaded: ' . (extension_loaded('gd') ? 'YES' : 'NO') . PHP_EOL;
echo 'imagecreatefromstring: ' . (function_exists('imagecreatefromstring') ? 'YES' : 'NO') . PHP_EOL;
echo 'imagewebp: ' . (function_exists('imagewebp') ? 'YES' : 'NO') . PHP_EOL;

if (function_exists('gd_info')) {
    $info = gd_info();
    echo 'WebP Support: ' . (!empty($info['WebP Support']) ? 'YES' : 'NO') . PHP_EOL;
    echo PHP_EOL;
    print_r($info);
}
