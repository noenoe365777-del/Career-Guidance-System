CREATE DATABASE IF NOT EXISTS career_guidance;
USE career_guidance;

SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS career_recommendations;
DROP TABLE IF EXISTS assessment_result_breakdown;
DROP TABLE IF EXISTS assessment_results;
DROP TABLE IF EXISTS student_answers;
DROP TABLE IF EXISTS student_assessments;
DROP TABLE IF EXISTS question_options;
DROP TABLE IF EXISTS questions;
DROP TABLE IF EXISTS career_assessment_mapping;
DROP TABLE IF EXISTS careers;
DROP TABLE IF EXISTS career_categories;
DROP TABLE IF EXISTS assessments;
DROP TABLE IF EXISTS student_profiles;
DROP TABLE IF EXISTS users;

SET FOREIGN_KEY_CHECKS = 1;

-- ============================================
-- 1. USERS
-- ============================================
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    education_level VARCHAR(100) NOT NULL,
    role ENUM('student','admin') NOT NULL DEFAULT 'student',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ============================================
-- 2. STUDENT PROFILES
-- ============================================
CREATE TABLE student_profiles (
    profile_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL UNIQUE,
    phone VARCHAR(30) DEFAULT NULL,
    gender ENUM('Male','Female','Other') DEFAULT NULL,
    date_of_birth DATE DEFAULT NULL,
    field_of_study VARCHAR(150) DEFAULT NULL,
    location VARCHAR(150) DEFAULT NULL,
    bio TEXT DEFAULT NULL,
    profile_image VARCHAR(255) DEFAULT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_student_profiles_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

-- ============================================
-- 2A. PASSWORD RESET REQUESTS
-- ============================================
CREATE TABLE IF NOT EXISTS password_resets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    code VARCHAR(32) NOT NULL,
    expires_at DATETIME NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_password_resets_user_id (user_id),
    INDEX idx_password_resets_code (code),
    CONSTRAINT fk_password_resets_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

-- ============================================
-- 3. ASSESSMENTS
-- ============================================
CREATE TABLE assessments (
    assessment_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    description TEXT DEFAULT NULL,
    assessment_type VARCHAR(100) DEFAULT NULL,
    total_questions INT DEFAULT 0,
    status ENUM('active','inactive') DEFAULT 'active',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ============================================
-- 3A. DEFAULT ASSESSMENT SEED DATA
-- ============================================
INSERT INTO assessments (title, description, assessment_type, total_questions, status) VALUES
('Personality Assessment', 'Understand your personality traits and work style.', 'personality', 10, 'active'),
('Interest Assessment', 'Discover careers that match your interests and passions.', 'interest', 10, 'active'),
('Aptitude Assessment', 'Measure your reasoning abilities and problem-solving skills.', 'aptitude', 5, 'active'),
('Career Values Assessment', 'Identify what matters most to you in a future career.', 'values', 5, 'active');

INSERT INTO questions (assessment_id, question_text, question_type, question_order) VALUES
((SELECT assessment_id FROM assessments WHERE assessment_type = 'personality'), 'I enjoy meeting and talking with new people.', 'single_choice', 1),
((SELECT assessment_id FROM assessments WHERE assessment_type = 'personality'), 'I like taking responsibility and leading group work.', 'single_choice', 2),
((SELECT assessment_id FROM assessments WHERE assessment_type = 'personality'), 'I prefer planning tasks before I start working.', 'single_choice', 3),
((SELECT assessment_id FROM assessments WHERE assessment_type = 'personality'), 'I stay calm when I have to work under pressure.', 'single_choice', 4),
((SELECT assessment_id FROM assessments WHERE assessment_type = 'personality'), 'I enjoy working as part of a team.', 'single_choice', 5),
((SELECT assessment_id FROM assessments WHERE assessment_type = 'personality'), 'I make decisions based on logic rather than emotions.', 'single_choice', 6),
((SELECT assessment_id FROM assessments WHERE assessment_type = 'personality'), 'I enjoy trying new ideas and experiences.', 'single_choice', 7),
((SELECT assessment_id FROM assessments WHERE assessment_type = 'personality'), 'I finish my work before deadlines.', 'single_choice', 8),
((SELECT assessment_id FROM assessments WHERE assessment_type = 'personality'), 'I easily adapt to unexpected changes.', 'single_choice', 9),
((SELECT assessment_id FROM assessments WHERE assessment_type = 'personality'), 'I like solving difficult problems.', 'single_choice', 10),
((SELECT assessment_id FROM assessments WHERE assessment_type = 'interest'), 'I enjoy helping other people solve their problems.', 'single_choice', 1),
((SELECT assessment_id FROM assessments WHERE assessment_type = 'interest'), 'I like creating artwork or designs.', 'single_choice', 2),
((SELECT assessment_id FROM assessments WHERE assessment_type = 'interest'), 'I enjoy programming or using computers.', 'single_choice', 3),
((SELECT assessment_id FROM assessments WHERE assessment_type = 'interest'), 'I like repairing machines or equipment.', 'single_choice', 4),
((SELECT assessment_id FROM assessments WHERE assessment_type = 'interest'), 'I enjoy organizing events or activities.', 'single_choice', 5),
((SELECT assessment_id FROM assessments WHERE assessment_type = 'interest'), 'I enjoy teaching others new skills.', 'single_choice', 6),
((SELECT assessment_id FROM assessments WHERE assessment_type = 'interest'), 'I like conducting science experiments.', 'single_choice', 7),
((SELECT assessment_id FROM assessments WHERE assessment_type = 'interest'), 'I enjoy writing stories or articles.', 'single_choice', 8),
((SELECT assessment_id FROM assessments WHERE assessment_type = 'interest'), 'I like managing money or budgets.', 'single_choice', 9),
((SELECT assessment_id FROM assessments WHERE assessment_type = 'interest'), 'I enjoy working outdoors.', 'single_choice', 10),
((SELECT assessment_id FROM assessments WHERE assessment_type = 'aptitude'), 'I enjoy solving logic puzzles and number challenges.', 'single_choice', 1),
((SELECT assessment_id FROM assessments WHERE assessment_type = 'aptitude'), 'I like understanding how systems and processes work.', 'single_choice', 2),
((SELECT assessment_id FROM assessments WHERE assessment_type = 'aptitude'), 'I am comfortable working with data and patterns.', 'single_choice', 3),
((SELECT assessment_id FROM assessments WHERE assessment_type = 'aptitude'), 'I enjoy planning solutions before taking action.', 'single_choice', 4),
((SELECT assessment_id FROM assessments WHERE assessment_type = 'aptitude'), 'I can focus on detailed tasks for long periods.', 'single_choice', 5),
((SELECT assessment_id FROM assessments WHERE assessment_type = 'values'), 'I value stability and security in my career.', 'single_choice', 1),
((SELECT assessment_id FROM assessments WHERE assessment_type = 'values'), 'I want a role that helps other people.', 'single_choice', 2),
((SELECT assessment_id FROM assessments WHERE assessment_type = 'values'), 'I am motivated by creativity and innovation.', 'single_choice', 3),
((SELECT assessment_id FROM assessments WHERE assessment_type = 'values'), 'I want flexibility and independence in my work.', 'single_choice', 4),
((SELECT assessment_id FROM assessments WHERE assessment_type = 'values'), 'I care about earning a high income.', 'single_choice', 5);

-- ============================================
-- 4. QUESTIONS
-- ============================================
CREATE TABLE questions (
    question_id INT AUTO_INCREMENT PRIMARY KEY,
    assessment_id INT NOT NULL,
    question_text TEXT NOT NULL,
    question_type ENUM('single_choice','multiple_choice','text') DEFAULT 'single_choice',
    question_order INT DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_questions_assessment
        FOREIGN KEY (assessment_id) REFERENCES assessments(assessment_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

-- ============================================
-- 5. QUESTION OPTIONS
-- ============================================
CREATE TABLE question_options (
    option_id INT AUTO_INCREMENT PRIMARY KEY,
    question_id INT NOT NULL,
    option_text VARCHAR(255) NOT NULL,
    score_value DECIMAL(5,2) DEFAULT 0,
    option_order INT DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_question_options_question
        FOREIGN KEY (question_id) REFERENCES questions(question_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

-- ============================================
-- 6. STUDENT ASSESSMENTS
-- ============================================
CREATE TABLE student_assessments (
    student_assessment_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    assessment_id INT NOT NULL,
    started_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    completed_at DATETIME DEFAULT NULL,
    status ENUM('in_progress','completed') DEFAULT 'in_progress',
    total_score DECIMAL(8,2) DEFAULT 0,
    CONSTRAINT fk_student_assessments_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT fk_student_assessments_assessment
        FOREIGN KEY (assessment_id) REFERENCES assessments(assessment_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

-- ============================================
-- 7. STUDENT ANSWERS
-- ============================================
CREATE TABLE student_answers (
    answer_id INT AUTO_INCREMENT PRIMARY KEY,
    student_assessment_id INT NOT NULL,
    question_id INT NOT NULL,
    option_id INT DEFAULT NULL,
    answer_text TEXT DEFAULT NULL,
    score_awarded DECIMAL(5,2) DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_student_answers_student_assessment
        FOREIGN KEY (student_assessment_id) REFERENCES student_assessments(student_assessment_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT fk_student_answers_question
        FOREIGN KEY (question_id) REFERENCES questions(question_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT fk_student_answers_option
        FOREIGN KEY (option_id) REFERENCES question_options(option_id)
        ON DELETE SET NULL
        ON UPDATE CASCADE
);

-- ============================================
-- 8. ASSESSMENT RESULTS
-- ============================================
CREATE TABLE assessment_results (
    result_id INT AUTO_INCREMENT PRIMARY KEY,
    student_assessment_id INT NOT NULL UNIQUE,
    user_id INT NOT NULL,
    assessment_id INT NOT NULL,
    total_score DECIMAL(8,2) NOT NULL,
    result_summary TEXT DEFAULT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_assessment_results_student_assessment
        FOREIGN KEY (student_assessment_id) REFERENCES student_assessments(student_assessment_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT fk_assessment_results_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT fk_assessment_results_assessment
        FOREIGN KEY (assessment_id) REFERENCES assessments(assessment_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

-- ============================================
-- 9. ASSESSMENT RESULT BREAKDOWN
-- ============================================
CREATE TABLE assessment_result_breakdown (
    breakdown_id INT AUTO_INCREMENT PRIMARY KEY,
    result_id INT NOT NULL,
    category_name VARCHAR(100) NOT NULL,
    category_score DECIMAL(8,2) NOT NULL DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_assessment_breakdown_result
        FOREIGN KEY (result_id) REFERENCES assessment_results(result_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

-- ============================================
-- 10. CAREER CATEGORIES
-- ============================================
CREATE TABLE career_categories (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(100) NOT NULL UNIQUE,
    description TEXT DEFAULT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- ============================================
-- 11. CAREERS
-- ============================================
CREATE TABLE careers (
    career_id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT DEFAULT NULL,
    career_name VARCHAR(150) NOT NULL,
    description TEXT DEFAULT NULL,
    required_skills TEXT DEFAULT NULL,
    education_path TEXT DEFAULT NULL,
    salary_range VARCHAR(100) DEFAULT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_careers_category
        FOREIGN KEY (category_id) REFERENCES career_categories(category_id)
        ON DELETE SET NULL
        ON UPDATE CASCADE
);

-- ============================================
-- 12. CAREER ASSESSMENT MAPPING
-- ============================================
CREATE TABLE career_assessment_mapping (
    mapping_id INT AUTO_INCREMENT PRIMARY KEY,
    assessment_id INT NOT NULL,
    career_id INT NOT NULL,
    weight DECIMAL(5,2) DEFAULT 1.00,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_career_assessment_mapping_assessment
        FOREIGN KEY (assessment_id) REFERENCES assessments(assessment_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT fk_career_assessment_mapping_career
        FOREIGN KEY (career_id) REFERENCES careers(career_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

-- ============================================
-- 13. CAREER RECOMMENDATIONS
-- ============================================
CREATE TABLE career_recommendations (
    recommendation_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    career_id INT NOT NULL,
    assessment_id INT DEFAULT NULL,
    recommended_score DECIMAL(8,2) NOT NULL,
    notes TEXT DEFAULT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_career_recommendations_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT fk_career_recommendations_career
        FOREIGN KEY (career_id) REFERENCES careers(career_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT fk_career_recommendations_assessment
        FOREIGN KEY (assessment_id) REFERENCES assessments(assessment_id)
        ON DELETE SET NULL
        ON UPDATE CASCADE
);

-- =========================================================
-- STORED PROCEDURES
-- =========================================================

DELIMITER $$

-- ============================================
-- REGISTER USER
-- ============================================
CREATE PROCEDURE sp_register_user(
    IN p_full_name VARCHAR(150),
    IN p_email VARCHAR(150),
    IN p_password VARCHAR(255),
    IN p_education_level VARCHAR(100)
)
BEGIN
    DECLARE v_user_count INT DEFAULT 0;
    DECLARE v_user_id INT;

    SELECT COUNT(*) INTO v_user_count
    FROM users
    WHERE email = p_email;

    IF v_user_count > 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Email already exists';
    ELSE
        INSERT INTO users (
            full_name,
            email,
            password,
            education_level,
            role,
            created_at,
            updated_at
        )
        VALUES (
            p_full_name,
            p_email,
            p_password,
            p_education_level,
            'student',
            NOW(),
            NOW()
        );

        SET v_user_id = LAST_INSERT_ID();

        INSERT INTO student_profiles (
            user_id,
            created_at,
            updated_at
        )
        VALUES (
            v_user_id,
            NOW(),
            NOW()
        );

        SELECT v_user_id AS user_id;
    END IF;
END$$

-- ============================================
-- LOGIN USER
-- ============================================
CREATE PROCEDURE sp_get_user_by_email(
    IN p_email VARCHAR(150)
)
BEGIN
    SELECT
        id,
        full_name,
        email,
        password,
        education_level,
        role,
        created_at,
        updated_at
    FROM users
    WHERE email = p_email
    LIMIT 1;
END$$

-- ============================================
-- GET USER PROFILE
-- ============================================
CREATE PROCEDURE sp_get_student_profile(
    IN p_user_id INT
)
BEGIN
    SELECT
        u.id,
        u.full_name,
        u.email,
        u.education_level,
        u.role,
        u.created_at AS member_since,
        sp.phone,
        sp.gender,
        sp.date_of_birth,
        sp.field_of_study,
        sp.location,
        sp.bio,
        sp.profile_image
    FROM users u
    LEFT JOIN student_profiles sp ON sp.user_id = u.id
    WHERE u.id = p_user_id
    LIMIT 1;
END$$

-- ============================================
-- UPDATE STUDENT PROFILE
-- ============================================
CREATE PROCEDURE sp_update_student_profile(
    IN p_user_id INT,
    IN p_full_name VARCHAR(150),
    IN p_phone VARCHAR(30),
    IN p_gender VARCHAR(20),
    IN p_date_of_birth DATE,
    IN p_education_level VARCHAR(100),
    IN p_field_of_study VARCHAR(150),
    IN p_location VARCHAR(150),
    IN p_bio TEXT
)
BEGIN
    UPDATE users
    SET
        full_name = p_full_name,
        education_level = p_education_level,
        updated_at = NOW()
    WHERE id = p_user_id;

    UPDATE student_profiles
    SET
        phone = p_phone,
        gender = p_gender,
        date_of_birth = p_date_of_birth,
        field_of_study = p_field_of_study,
        location = p_location,
        bio = p_bio,
        updated_at = NOW()
    WHERE user_id = p_user_id;
END$$

DELIMITER ;