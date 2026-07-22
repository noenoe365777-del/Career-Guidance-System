#!/usr/bin/env python3
import re

# Read the file
with open('C:/xampp/htdocs/career-guidance-system/App/Modules/Admin/Infrastructure/Persistence/QuestionRepository.php', 'r', encoding='utf-8') as f:
    content = f.read()

# Fix the getQuestionsCountByType function - line 219
# Replace the malformed query with a properly formatted one
old_pattern = r'\$stmt = \$this->connection->query\("\\n                SELECT question_type, COUNT\\(\*\\) AS question_count\\n                FROM assessment_questions\\n                GROUP BY question_type\\n            \\"\);'
new_pattern = '            $stmt = $this->connection->query("\\n                SELECT question_type, COUNT(*) AS question_count\\n                FROM assessment_questions\\n                GROUP BY question_type\\n            ");'

content = re.sub(old_pattern, new_pattern, content)

# Fix the getQuestionsCountByDifficulty function - line 243
old_pattern2 = r'            \\"\\";'
new_pattern2 = '            ");'

content = re.sub(old_pattern2, new_pattern2, content)

# Write the fixed content back
with open('C:/xampp/htdocs/career-guidance-system/App/Modules/Admin/Infrastructure/Persistence/QuestionRepository.php', 'w', encoding='utf-8') as f:
    f.write(content)

print('✓ QuestionRepository.php syntax errors fixed')
