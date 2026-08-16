-- Run this ONCE in your TiDB SQL Editor before using the deployed admin panel.
CREATE TABLE IF NOT EXISTS php_sessions (
    session_id VARCHAR(128) NOT NULL PRIMARY KEY,
    session_data MEDIUMBLOB NOT NULL,
    expires_at BIGINT NOT NULL,
    INDEX idx_php_sessions_expires (expires_at)
);
