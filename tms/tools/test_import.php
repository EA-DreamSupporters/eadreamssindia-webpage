<?php
require_once __DIR__ . '/../../vendor/autoload.php';
// Minimal environment stubs used by import function
if (!function_exists('getCurrentUser')) {
    function getCurrentUser()
    {
        return ['id' => 1, 'institute_id' => 1, 'username' => 'cli_test'];
    }
}

require_once __DIR__ . '/../pages/questions/functions/parse_and_import.php';
require_once __DIR__ . '/../config/database.php';

// Build a sample parsed question with i18n
$questions = [
    [
        'subject' => 'General Knowledge',
        'topic' => 'Sample',
        'subtopic' => '',
        'question_text' => 'Sample Q',
        'question_text_ta' => 'தமிழ் கேள்வி',
        'question_text_hi' => 'हिन्दी प्रश्न',
        'option_a' => 'Option A EN',
        'option_b' => 'Option B EN',
        'option_c' => '',
        'option_d' => '',
        'option_e' => '',
        'option_a_ta' => 'விருப்பம் A TA',
        'option_b_ta' => '',
        'option_a_hi' => '',
        'option_b_hi' => 'विकल्प B HI',
        'correct_answer' => 'A',
        'difficulty' => 'medium',
        'exam_year' => 2021,
        'source' => 'Test',
        'explanation' => 'None',
        'is_public' => 1,
        'row_number' => 1
    ]
];

try {
    $res = importQuestionsToDatabase($questions, $db);
    echo json_encode($res, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

    // Fetch last inserted
    $stmt = $db->query("SELECT * FROM question_banks ORDER BY id DESC LIMIT 1");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "\nInserted options JSON: \n" . $row['options'] . "\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
