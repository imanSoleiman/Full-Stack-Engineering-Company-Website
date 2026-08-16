<?php

require_once __DIR__ . '/../config.php';

/**
 * Persistent admin sessions for Vercel.
 *
 * - On Vercel/TiDB (TIDB_HOST is set), PHP session data is stored in TiDB.
 * - On local XAMPP (TIDB_HOST is not set), PHP keeps using its normal local
 *   file-based session storage so local development keeps working as before.
 */
class SpectrumDatabaseSessionHandler implements SessionHandlerInterface
{
    private mysqli $db;
    private int $lifetime;

    public function __construct(mysqli $db, int $lifetime = 86400)
    {
        $this->db = $db;
        $this->lifetime = $lifetime;
    }

    public function open(string $path, string $name): bool
    {
        return true;
    }

    public function close(): bool
    {
        return true;
    }

    public function read(string $id): string|false
    {
        $now = time();

        $stmt = $this->db->prepare(
            'SELECT session_data
             FROM php_sessions
             WHERE session_id = ? AND expires_at > ?
             LIMIT 1'
        );
        $stmt->bind_param('si', $id, $now);
        $stmt->execute();

        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();

        return $row ? (string) $row['session_data'] : '';
    }

    public function write(string $id, string $data): bool
    {
        $expiresAt = time() + $this->lifetime;

        $stmt = $this->db->prepare(
            'INSERT INTO php_sessions (session_id, session_data, expires_at)
             VALUES (?, ?, ?)
             ON DUPLICATE KEY UPDATE
               session_data = VALUES(session_data),
               expires_at = VALUES(expires_at)'
        );
        $stmt->bind_param('ssi', $id, $data, $expiresAt);
        $ok = $stmt->execute();
        $stmt->close();

        return $ok;
    }

    public function destroy(string $id): bool
    {
        $stmt = $this->db->prepare(
            'DELETE FROM php_sessions WHERE session_id = ?'
        );
        $stmt->bind_param('s', $id);
        $ok = $stmt->execute();
        $stmt->close();

        return $ok;
    }

    public function gc(int $max_lifetime): int|false
    {
        $now = time();

        $stmt = $this->db->prepare(
            'DELETE FROM php_sessions WHERE expires_at <= ?'
        );
        $stmt->bind_param('i', $now);

        if (!$stmt->execute()) {
            $stmt->close();
            return false;
        }

        $deleted = $stmt->affected_rows;
        $stmt->close();

        return $deleted;
    }
}

if (session_status() !== PHP_SESSION_ACTIVE) {
    $isHttps =
        (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ||
        (
            isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&
            strtolower((string) $_SERVER['HTTP_X_FORWARDED_PROTO']) === 'https'
        );

    // Harden the admin session cookie without changing the existing login flow.
    ini_set('session.use_strict_mode', '1');
    ini_set('session.use_only_cookies', '1');
    ini_set('session.gc_maxlifetime', '86400');

    session_name('spectrum_admin');
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'secure' => $isHttps,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);

    // Vercel uses the TiDB environment variables. Local XAMPP does not.
    if (getenv('TIDB_HOST')) {
        $spectrumSessionHandler = new SpectrumDatabaseSessionHandler($conn, 86400);
        session_set_save_handler($spectrumSessionHandler, true);
    }

    session_start();
}
