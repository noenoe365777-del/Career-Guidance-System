-- Seed an admin user for the separate admin login system.
-- This script matches the current users table schema in the app.

INSERT INTO users (
    username,
    email,
    password,
    user_role_id,
    status_id,
    is_verified,
    is_active,
    is_login,
    created_at,
    updated_at
)
VALUES (
    'admin',
    'admin@example.com',
    '$2y$10$EsHKvSkjZv2uAeFz5SRdNOXGibMVpKLASB2Aemg2SiYvgqp9A2fMi',
    1,
    3,
    1,
    1,
    0,
    NOW(),
    NOW()
)
ON DUPLICATE KEY UPDATE
    username = VALUES(username),
    password = VALUES(password),
    user_role_id = VALUES(user_role_id),
    status_id = VALUES(status_id),
    is_verified = VALUES(is_verified),
    is_active = VALUES(is_active),
    updated_at = NOW();
