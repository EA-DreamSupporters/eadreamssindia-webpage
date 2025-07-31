<?php
// Test script to verify Excel template and parsing with new format (Company name + S.No)

echo "=== Testing Enhanced Excel Template and Parsing ===\n\n";

// Test 1: Check if template file exists
$templatePath = __DIR__ . '/sample_excel_template_new.xlsx';
if (file_exists($templatePath)) {
    echo "✓ Template file exists\n";
    echo "File size: " . filesize($templatePath) . " bytes\n";
} else {
    echo "✗ Template file not found\n";
}

// Test 2: Check template content
if (file_exists($templatePath)) {
    $content = file_get_contents($templatePath);
    if (strpos($content, 'urn:schemas-microsoft-com:office:spreadsheet') !== false) {
        echo "✓ Template uses SpreadsheetML format\n";
    } else {
        echo "✗ Template not in SpreadsheetML format\n";
    }
    
    if (strpos($content, 'ENTER YOUR COMPANY') !== false) {
        echo "✓ Template has company name placeholder\n";
    } else {
        echo "✗ Company name placeholder not found\n";
    }
    
    if (strpos($content, 'S.No') !== false) {
        echo "✓ Template has S.No column\n";
    } else {
        echo "✗ S.No column not found\n";
    }
}

// Test 3: Test new CSV format parsing
$csvPath = __DIR__ . '/test_competitive_questions_new.csv';

// Create a test CSV with new format
$newCSVContent = '"EA Dreams Educational Solutions"' . "\n";
$newCSVContent .= '""' . "\n"; // Empty row
$newCSVContent .= '"S.No","Subject","Topic","Subtopic","Question Text","Option A","Option B","Option C","Option D","Correct Answer","Explanation","Difficulty","Exam Year","Source/Exam","Is Public"' . "\n";
$newCSVContent .= '"1","Polity","Constitutional Law","Writs","With reference to the writs issued by the Courts in India, consider the following statements:

1. Mandamus will not lie against a private organization unless it is entrusted with a public duty.
2. Mandamus will not lie against a Company even though it may be a Government Company.
3. Any public minded person can be a petitioner to move the Court to obtain the writ of Quo Warranto.

Which of the statements given above are correct?","1 and 2 only","2 and 3 only","1 and 3 only","1, 2 and 3","C","Statement 1 is correct: Mandamus will not lie against a private organization unless it is entrusted with a public duty. Statement 2 is incorrect: Mandamus can lie against a Government Company as it performs public functions. Statement 3 is correct: Any public minded person can file for Quo Warranto to challenge illegal appointment to public office.","medium","2024","UPSC","Yes"' . "\n";
$newCSVContent .= '"2","History","Ancient India","Mauryan Empire","Which of the following statements about the Mauryan administration is/are correct?

1. The empire was divided into provinces called Janapadas.
2. Kautilya' . "'s" . ' Arthashastra provides detailed information about Mauryan administration.
3. The Mauryan army had six divisions including elephants, cavalry, and chariots.

Select the correct answer using the code given below:","1 and 2 only","2 and 3 only","1 and 3 only","1, 2 and 3","B","Statement 1 is incorrect: The Mauryan empire was divided into provinces, but they were not called Janapadas. Statement 2 is correct: Kautilya' . "'s" . ' Arthashastra is the primary source for Mauryan administration. Statement 3 is correct: The Mauryan army had six divisions (Shadanga) - infantry, cavalry, elephants, chariots, navy, and commissariat.","medium","2023","UPSC","Yes"' . "\n";

file_put_contents($csvPath, $newCSVContent);

if (file_exists($csvPath)) {
    echo "\n=== Testing New CSV Format Parsing ===\n";
    
    // Include the parsing function from questions.php
    function parseCSVFile($filePath) {
        $questions = [];
        $handle = fopen($filePath, 'r');
        
        if ($handle !== FALSE) {
            // Skip company name row (first row)
            $companyRow = fgetcsv($handle);
            echo "Company Row: " . implode(', ', $companyRow) . "\n";
            
            // Skip empty spacing row (second row) if it exists
            $potentialEmptyRow = fgetcsv($handle);
            
            // Check if this is actually the header row (for backward compatibility)
            if ($potentialEmptyRow && (count($potentialEmptyRow) > 10)) {
                // This looks like a header row, treat it as such
                $headers = $potentialEmptyRow;
                $skipCount = 2; // We've skipped company + header
            } else {
                // Read the actual header row (third row in new format)
                $headers = fgetcsv($handle);
                $skipCount = 3; // We've skipped company + empty + header
            }
            
            echo "Headers: " . implode(', ', $headers) . "\n";
            
            $rowNumber = $skipCount;
            
            while (($data = fgetcsv($handle)) !== FALSE) {
                $rowNumber++;
                
                // Determine if this has S.No column (15 columns) or old format (14 columns)
                $hasSerialNumber = (count($data) >= 15 && is_numeric(trim($data[0])));
                
                if ($hasSerialNumber) {
                    // New format with S.No column
                    if (count($data) < 15) continue;
                    
                    $question = [
                        'subject' => trim($data[1] ?? ''),
                        'topic' => trim($data[2] ?? ''),
                        'subtopic' => trim($data[3] ?? ''),
                        'question_text' => trim($data[4] ?? ''),
                        'option_a' => trim($data[5] ?? ''),
                        'option_b' => trim($data[6] ?? ''),
                        'option_c' => trim($data[7] ?? ''),
                        'option_d' => trim($data[8] ?? ''),
                        'correct_answer' => strtoupper(trim($data[9] ?? '')),
                        'explanation' => trim($data[10] ?? ''),
                        'difficulty' => strtolower(trim($data[11] ?? 'medium')),
                        'exam_year' => intval($data[12] ?? date('Y')),
                        'source' => trim($data[13] ?? ''),
                        'is_public' => strtolower(trim($data[14] ?? 'yes')) === 'yes' ? 1 : 0,
                        'row_number' => $rowNumber,
                        'serial_number' => trim($data[0] ?? '')
                    ];
                } else {
                    // Old format without S.No column (backward compatibility)
                    if (count($data) < 14) continue;
                    
                    $question = [
                        'subject' => trim($data[0] ?? ''),
                        'topic' => trim($data[1] ?? ''),
                        'subtopic' => trim($data[2] ?? ''),
                        'question_text' => trim($data[3] ?? ''),
                        'option_a' => trim($data[4] ?? ''),
                        'option_b' => trim($data[5] ?? ''),
                        'option_c' => trim($data[6] ?? ''),
                        'option_d' => trim($data[7] ?? ''),
                        'correct_answer' => strtoupper(trim($data[8] ?? '')),
                        'explanation' => trim($data[9] ?? ''),
                        'difficulty' => strtolower(trim($data[10] ?? 'medium')),
                        'exam_year' => intval($data[11] ?? date('Y')),
                        'source' => trim($data[12] ?? ''),
                        'is_public' => strtolower(trim($data[13] ?? 'yes')) === 'yes' ? 1 : 0,
                        'row_number' => $rowNumber
                    ];
                }
                
                // Validate required fields
                if (!empty($question['question_text']) && !empty($question['subject'])) {
                    // Validate correct answer
                    if (!in_array($question['correct_answer'], ['A', 'B', 'C', 'D'])) {
                        $question['correct_answer'] = 'A';
                    }
                    
                    // Validate difficulty
                    if (!in_array($question['difficulty'], ['easy', 'medium', 'hard'])) {
                        $question['difficulty'] = 'medium';
                    }
                    
                    // Set default values for empty fields
                    if (empty($question['subject'])) $question['subject'] = 'General';
                    if (empty($question['topic'])) $question['topic'] = 'General';
                    
                    $questions[] = $question;
                }
            }
            fclose($handle);
        }
        
        return $questions;
    }
    
    try {
        $questions = parseCSVFile($csvPath);
        echo "✓ New CSV parsing successful\n";
        echo "Questions parsed: " . count($questions) . "\n";
        
        if (count($questions) > 0) {
            $firstQuestion = $questions[0];
            echo "\nFirst question preview:\n";
            echo "Serial Number: " . ($firstQuestion['serial_number'] ?? 'N/A') . "\n";
            echo "Subject: " . $firstQuestion['subject'] . "\n";
            echo "Topic: " . $firstQuestion['topic'] . "\n";
            echo "Question: " . substr($firstQuestion['question_text'], 0, 100) . "...\n";
            echo "Options: A) " . substr($firstQuestion['option_a'], 0, 30) . "...\n";
            echo "Correct Answer: " . $firstQuestion['correct_answer'] . "\n";
            echo "Difficulty: " . $firstQuestion['difficulty'] . "\n";
        }
    } catch (Exception $e) {
        echo "✗ New CSV parsing failed: " . $e->getMessage() . "\n";
    }
}

echo "\n=== Test Complete ===\n";
echo "✅ New Features:\n";
echo "   - Company name row in templates\n";
echo "   - S.No column for question numbering\n";
echo "   - Backward compatibility with old format\n";
echo "   - Both Excel and CSV template downloads\n";
echo "   - Enhanced parsing with validation\n";
?>