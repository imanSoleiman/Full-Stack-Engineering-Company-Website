<?php

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if (getenv('TIDB_HOST')) {

    $host = getenv('TIDB_HOST');
    $port = (int) getenv('TIDB_PORT');
    $user = getenv('TIDB_USER');
    $password = getenv('TIDB_PASSWORD');
    $database = getenv('TIDB_DATABASE');

    $conn = mysqli_init();

    mysqli_ssl_set(
        $conn,
        null,
        null,
        "/etc/ssl/certs/ca-certificates.crt",
        null,
        null
    );

    mysqli_real_connect(
        $conn,
        $host,
        $user,
        $password,
        $database,
        $port,
        null,
        MYSQLI_CLIENT_SSL
    );

} else {

    $conn = new mysqli(
        "localhost",
        "root",
        "",
        "spectrum",
        3306
    );
}

$conn->set_charset("utf8mb4");


/*
|--------------------------------------------------------------------------
| IMAGE URL HELPER
|--------------------------------------------------------------------------
| Supports:
| 1. Existing local images inside assets/
| 2. New images stored as full Vercel Blob URLs
|--------------------------------------------------------------------------
*/

if (!function_exists('image_url')) {

    function image_url($image, $localFolder = '')
    {
        if (empty($image)) {
            return '';
        }

        $image = (string) $image;

        // Vercel Blob / external image URL
        if (preg_match('#^https?://#i', $image)) {
            return $image;
        }

        // Existing local image
        if ($localFolder === '') {
            return ltrim($image, '/');
        }

        return rtrim($localFolder, '/')
            . '/'
            . ltrim($image, '/');
    }
}
?>