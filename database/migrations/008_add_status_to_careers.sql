ALTER TABLE careers
ADD COLUMN status ENUM('active', 'inactive') NOT NULL DEFAULT 'active' AFTER career_icon,
ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP AFTER values_type;

UPDATE careers SET status = 'active' WHERE status IS NULL;
