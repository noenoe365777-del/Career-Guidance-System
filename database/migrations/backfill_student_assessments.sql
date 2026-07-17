-- ============================================================================
-- One-time backfill: synchronize student_assessments from assessment_results
-- ============================================================================
-- 
-- Root cause:
--   The V2 assessment engine (NewAssessmentRepository::completeAssessment())
--   wrote completed assessments to assessment_results and
--   student_assessment_scores, but never to student_assessments.
--   The Student Dashboard reads progress from student_assessments,
--   so affected users saw 0 completed assessments despite having
--   all 4 done.
-- 
-- This migration:
--   For every completed assessment in assessment_results:
--     1. INSERTS a new row into student_assessments if none exists
--        for that (user_id, assessment_id) pair.
--     2. UPDATES the existing row if one exists but has stale data
--        (status ≠ 'completed' or completed_at IS NULL).
--
-- Assumptions:
--   - assessment_results.status = 'completed' is the source of truth.
--   - student_assessments has NO unique constraint on (user_id, assessment_id).
--   - There is no updated_at column in student_assessments (it does not exist
--     in the current schema), so that part of requirement 3 cannot be satisfied.
--   - Multiple assessment_results rows may exist per (user_id, assessment_id);
--     we use the row with the MAX(completed_at) as the authoritative source.
--   - Multiple student_assessments rows may exist per (user_id, assessment_id);
--     for UPDATE we target the row with the MAX(student_assessment_id).
--
-- Run from the project root:  mysql -u root career_guidance < database/migrations/backfill_student_assessments.sql
-- Or paste into phpMyAdmin / Adminer.
-- ============================================================================

START TRANSACTION;

-- --------------------------------------------------------------------------
-- Report: what we are about to migrate
-- --------------------------------------------------------------------------
SELECT 'BACKFILL: student_assessments ← assessment_results' AS step;

SELECT CONCAT(
       'User ', ar.user_id,
       ', assessment_id ', ar.assessment_id,
       ', completed_at ', ar.completed_at) AS will_process
FROM assessment_results ar
WHERE ar.status = 'completed'
  AND NOT EXISTS (
      SELECT 1 FROM student_assessments sa
      WHERE sa.user_id = ar.user_id
        AND sa.assessment_id = ar.assessment_id
        AND sa.status = 'completed'
        AND sa.completed_at IS NOT NULL
  )
ORDER BY ar.user_id, ar.assessment_id;

-- --------------------------------------------------------------------------
-- STEP 1 — Insert rows for (user_id, assessment_id) pairs that have NO row
--           at all in student_assessments.
--           Only backfills users that exist in the users table (skips orphans).
-- --------------------------------------------------------------------------
INSERT INTO student_assessments (user_id, assessment_id, status, progress, started_at, completed_at)
SELECT ar.user_id,
       ar.assessment_id,
       'completed',
       100.00,
       COALESCE(ar.started_at, ar.completed_at, NOW()),
       ar.completed_at
FROM assessment_results ar
WHERE ar.status = 'completed'
  AND NOT EXISTS (
      SELECT 1 FROM student_assessments sa
      WHERE sa.user_id = ar.user_id
        AND sa.assessment_id = ar.assessment_id
  )
  AND EXISTS (
      SELECT 1 FROM users u WHERE u.user_id = ar.user_id
  );

SET @inserted = ROW_COUNT();
SELECT CONCAT('Inserted ', @inserted, ' new row(s) into student_assessments') AS result;

-- --------------------------------------------------------------------------
-- STEP 2 — Update existing rows that have stale completion data.
--           Target the row with the highest student_assessment_id per pair.
-- --------------------------------------------------------------------------
UPDATE student_assessments sa
  JOIN (
      SELECT ar.user_id,
             ar.assessment_id,
             ar.completed_at,
             (
                 SELECT MAX(sa2.student_assessment_id)
                 FROM student_assessments sa2
                 WHERE sa2.user_id = ar.user_id
                   AND sa2.assessment_id = ar.assessment_id
             ) AS target_id
      FROM assessment_results ar
      WHERE ar.status = 'completed'
        AND EXISTS (SELECT 1 FROM users u2 WHERE u2.user_id = ar.user_id)
        AND EXISTS (
            SELECT 1 FROM student_assessments sa3
            WHERE sa3.user_id = ar.user_id
              AND sa3.assessment_id = ar.assessment_id
        )
        AND ( -- only pairs where sa data is stale
            SELECT sa4.status
            FROM student_assessments sa4
            WHERE sa4.user_id = ar.user_id
              AND sa4.assessment_id = ar.assessment_id
            ORDER BY sa4.student_assessment_id DESC
            LIMIT 1
        ) != 'completed'
  ) src ON sa.student_assessment_id = src.target_id
SET sa.status       = 'completed',
    sa.progress     = 100.00,
    sa.completed_at = src.completed_at;

SET @updated = ROW_COUNT();
SELECT CONCAT('Updated ', @updated, ' existing stale row(s) in student_assessments') AS result;

-- --------------------------------------------------------------------------
-- Report: final state
-- --------------------------------------------------------------------------
SELECT CONCAT(
       'User ', sa.user_id,
       ', assessment_id ', sa.assessment_id,
       ', status ', sa.status,
       ', progress ', sa.progress,
       ', completed_at ', sa.completed_at) AS final_state
FROM student_assessments sa
WHERE sa.status = 'completed'
ORDER BY sa.user_id, sa.assessment_id;

SELECT CONCAT('Total completed rows in student_assessments now: ', COUNT(*)) AS summary
FROM student_assessments
WHERE status = 'completed';

COMMIT;
