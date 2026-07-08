CREATE TABLE IF NOT EXISTS student_permissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    dashboard TINYINT(1) NOT NULL DEFAULT 1,
    assessments TINYINT(1) NOT NULL DEFAULT 1,
    career_maps TINYINT(1) NOT NULL DEFAULT 1,
    profile TINYINT(1) NOT NULL DEFAULT 1,
    settings TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_student_permission (user_id),
    CONSTRAINT fk_student_permissions_user
        FOREIGN KEY (user_id) REFERENCES users(user_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
