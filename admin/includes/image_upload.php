<?php
require_once __DIR__ . '/../session.php';
/**
 * Shared image helper for the admin panel.
 *
 * Image-only changes:
 * - Converts uploaded JPG/JPEG/PNG/GIF/WEBP images to WEBP.
 * - On Vercel, sends the WEBP file to the /blob-upload service.
 * - Locally, keeps the existing assets-folder workflow.
 * - Supports old local filenames and new Vercel Blob URLs.
 */

if (!function_exists('spectrum_is_remote_image')) {
    function spectrum_is_remote_image($value)
    {
        return is_string($value) && preg_match('#^https?://#i', $value);
    }
}

if (!function_exists('spectrum_admin_image_src')) {
    function spectrum_admin_image_src($storedValue, $legacyBase = '')
    {
        if (empty($storedValue)) {
            return '';
        }

        if (spectrum_is_remote_image($storedValue)) {
            return $storedValue;
        }

        // Some existing DB rows already store a relative path.
        if (preg_match('#^(?:\.\.?/|/)#', $storedValue)) {
            return $storedValue;
        }

        return rtrim($legacyBase, '/') . '/' . ltrim($storedValue, '/');
    }
}

if (!function_exists('spectrum_gd_webp_ready')) {
    function spectrum_gd_webp_ready()
    {
        if (!extension_loaded('gd')) {
            return false;
        }

        if (!function_exists('imagecreatefromstring') || !function_exists('imagewebp')) {
            return false;
        }

        if (function_exists('gd_info')) {
            $info = gd_info();
            if (array_key_exists('WebP Support', $info) && !$info['WebP Support']) {
                return false;
            }
        }

        return true;
    }
}

if (!function_exists('spectrum_gd_webp_error_message')) {
    function spectrum_gd_webp_error_message()
    {
        $ini = php_ini_loaded_file();
        $iniText = $ini ? $ini : 'no php.ini loaded';

        return 'PHP GD with WEBP support is required by the PHP process serving this page. '
            . 'SAPI: ' . PHP_SAPI . '. Loaded php.ini: ' . $iniText . '. '
            . 'Enable GD/WebP in this PHP runtime and restart the web server.';
    }
}

if (!function_exists('spectrum_image_webp_temp')) {
    function spectrum_image_webp_temp(array $file, $quality = 82)
    {
        if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
            throw new RuntimeException('Invalid image upload.');
        }

        if (empty($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
            throw new RuntimeException('The uploaded image is not valid.');
        }

        if (!spectrum_gd_webp_ready()) {
            throw new RuntimeException(spectrum_gd_webp_error_message());
        }

        $raw = file_get_contents($file['tmp_name']);
        if ($raw === false) {
            throw new RuntimeException('Unable to read the uploaded image.');
        }

        $image = @imagecreatefromstring($raw);
        if (!$image) {
            throw new RuntimeException('Unsupported or invalid image. Use JPG, JPEG, PNG, GIF, or WEBP.');
        }

        imagealphablending($image, true);
        imagesavealpha($image, true);

        $tmp = tempnam(sys_get_temp_dir(), 'spectrum_webp_');
        if ($tmp === false) {
            imagedestroy($image);
            throw new RuntimeException('Unable to create a temporary WEBP file.');
        }

        $qualities = array_values(array_unique([$quality, 76, 70, 64, 58]));
        $ok = false;

        foreach ($qualities as $q) {
            if (@imagewebp($image, $tmp, $q)) {
                clearstatcache(true, $tmp);
                if (is_file($tmp) && filesize($tmp) > 0 && filesize($tmp) <= 4 * 1024 * 1024) {
                    $ok = true;
                    break;
                }
            }
        }

        imagedestroy($image);

        if (!$ok) {
            @unlink($tmp);
            throw new RuntimeException('The converted WEBP image is still larger than 4 MB. Please choose a smaller image.');
        }

        return $tmp;
    }
}

if (!function_exists('spectrum_webp_name')) {
    function spectrum_webp_name($originalName)
    {
        $base = pathinfo((string)$originalName, PATHINFO_FILENAME);
        $base = preg_replace('/[^A-Za-z0-9_-]+/', '-', $base);
        $base = trim($base, '-_');

        if ($base === '') {
            $base = 'image';
        }

        return $base . '-' . time() . '-' . bin2hex(random_bytes(4)) . '.webp';
    }
}

if (!function_exists('spectrum_blob_request')) {
    function spectrum_blob_request($path, $body, array $headers = [])
    {
        if (!function_exists('curl_init')) {
            throw new RuntimeException('PHP cURL is required for Vercel Blob uploads.');
        }

        $secret = getenv('UPLOAD_API_SECRET');
        if (!$secret) {
            throw new RuntimeException('UPLOAD_API_SECRET is missing in Vercel Environment Variables.');
        }

        $host = $_SERVER['HTTP_HOST'] ?? '';
        if ($host === '') {
            $host = getenv('VERCEL_URL') ?: getenv('VERCEL_PROJECT_PRODUCTION_URL');
        }

        if (!$host) {
            throw new RuntimeException('Unable to determine the Vercel host.');
        }

        $url = 'https://' . preg_replace('#^https?://#i', '', $host) . $path;

        $headers[] = 'X-Upload-Secret: ' . $secret;

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $body,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 90,
            CURLOPT_HTTPHEADER => $headers,
        ]);

        $response = curl_exec($ch);

        if ($response === false) {
            $error = curl_error($ch);
            curl_close($ch);
            throw new RuntimeException('Blob request failed: ' . $error);
        }

        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $data = json_decode($response, true);

        if ($status < 200 || $status >= 300) {
            $message = is_array($data) && !empty($data['error'])
                ? $data['error']
                : 'Vercel Blob request failed with HTTP ' . $status . '.';
            throw new RuntimeException($message);
        }

        return is_array($data) ? $data : [];
    }
}

if (!function_exists('spectrum_store_image')) {
    function spectrum_store_image(array $file, $blobFolder, $localDirectory, $localDbPrefix = '', $quality = 82)
    {
        $tmpWebp = spectrum_image_webp_temp($file, $quality);
        $webpName = spectrum_webp_name($file['name'] ?? 'image');

        try {
            // Vercel production/preview: store persistently in Vercel Blob.
            if (getenv('VERCEL')) {
                $query = '?folder=' . rawurlencode($blobFolder)
                    . '&filename=' . rawurlencode($webpName);

                $data = spectrum_blob_request(
                    '/blob-upload' . $query,
                    file_get_contents($tmpWebp),
                    ['Content-Type: image/webp']
                );

                if (empty($data['url'])) {
                    throw new RuntimeException('Vercel Blob did not return an image URL.');
                }

                return $data['url'];
            }

            // Local XAMPP: keep the existing assets-folder workflow, but save WEBP.
            if (!is_dir($localDirectory) && !mkdir($localDirectory, 0755, true) && !is_dir($localDirectory)) {
                throw new RuntimeException('Unable to create the local image folder.');
            }

            $destination = rtrim($localDirectory, '/\\') . DIRECTORY_SEPARATOR . $webpName;

            if (!copy($tmpWebp, $destination)) {
                throw new RuntimeException('Unable to save the WEBP image locally.');
            }

            return $localDbPrefix . $webpName;
        } finally {
            if (is_file($tmpWebp)) {
                @unlink($tmpWebp);
            }
        }
    }
}

if (!function_exists('spectrum_delete_image')) {
    function spectrum_delete_image($storedValue, $localDirectory = '')
    {
        if (empty($storedValue)) {
            return;
        }

        if (spectrum_is_remote_image($storedValue)) {
            // Best-effort Blob deletion. Database deletion should not fail if storage cleanup fails.
            try {
                spectrum_blob_request(
                    '/blob-delete',
                    json_encode(['url' => $storedValue]),
                    ['Content-Type: application/json']
                );
            } catch (Throwable $e) {
                error_log('Blob delete warning: ' . $e->getMessage());
            }
            return;
        }

        $file = rtrim($localDirectory, '/\\') . DIRECTORY_SEPARATOR . basename($storedValue);
        if (is_file($file)) {
            @unlink($file);
        }
    }
}
