-- Restructure student_role_permissions: keep only the master feature list
ALTER TABLE student_role_permissions
  DROP COLUMN is_enabled,
  DROP COLUMN created_at,
  DROP COLUMN updated_at;

-- student_feature_permissions: per-student feature overrides
CREATE TABLE IF NOT EXISTS student_feature_permissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    feature_key VARCHAR(64) NOT NULL,
    is_enabled TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_student_feature_permission (user_id, feature_key),
    CONSTRAINT fk_student_feature_permissions_user
        FOREIGN KEY (user_id) REFERENCES users(user_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Migrate existing global defaults into per-student rows for all existing students
INSERT IGNORE INTO student_feature_permissions (user_id, feature_key, is_enabled)
SELECT u.user_id, srf.feature_key, 1
FROM users u
CROSS JOIN student_role_permissions srf
WHERE u.user_role_id = 2;
