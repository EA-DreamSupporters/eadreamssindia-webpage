<?php
// Simple Bulk Upload Functions for Question Bank
// This is a clean implementation without complex CSV/Excel handling

function createSimpleCSVTemplate() {
    $csv = '"[ENTER YOUR COMPANY/INSTITUTION NAME HERE]"' . "\n";
    $csv .= '""' . "\n"; // Empty spacing row
    
    // Headers
    $csv .= '"S.No","Subject","Topic","Subtopic","Question Text","Option A","Option B","Option C","Option D","Correct Answer","Explanation","Difficulty","Exam Year","Source/Exam","Is Public"' . "\n";
    
    // Sample data
    $csv .= '"1","Polity","Constitutional Law","Writs","Sample Question 1","Option A","Option B","Option C","Option D","A","Sample explanation","medium","2024","UPSC","Yes"' . "\n";
    $csv .= '"2","Reasoning","Logical Reasoning","Series","Sample Question 2","Option A","Option B","Option C","Option D","B","Sample explanation","easy","2024","SSC","Yes"' . "\n";
    
    // Empty rows
    for ($i = 3; $i <= 12; $i++) {
        $csv .= '"' . $i . '","","","","","","","","","","","","","",""' . "\n";
    }
    
    return $csv;
}

function createSimpleExcelTemplate() {
    // Simple Excel template in SpreadsheetML format
    $xml = '<?xml version="1.0"?>
<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet" xmlns:o="urn:schemas-microsoft-com:office:office"
    xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"
    xmlns:html="http://www.w3.org/TR/REC-html40">
    <Worksheet ss:Name="Questions">
        <Table>
            <Row>
                <Cell><Data ss:Type="String">[ENTER YOUR COMPANY/INSTITUTION NAME HERE]</Data></Cell>
            </Row>
            <Row>
                <Cell><Data ss:Type="String"></Data></Cell>
            </Row>
            <Row>
                <Cell><Data ss:Type="String">S.No</Data></Cell>
                <Cell><Data ss:Type="String">Subject</Data></Cell>
                <Cell><Data ss:Type="String">Topic</Data></Cell>
                <Cell><Data ss:Type="String">Subtopic</Data></Cell>
                <Cell><Data ss:Type="String">Question Text</Data></Cell>
                <Cell><Data ss:Type="String">Option A</Data></Cell>
                <Cell><Data ss:Type="String">Option B</Data></Cell>
                <Cell><Data ss:Type="String">Option C</Data></Cell>
                <Cell><Data ss:Type="String">Option D</Data></Cell>
                <Cell><Data ss:Type="String">Correct Answer</Data></Cell>
                <Cell><Data ss:Type="String">Explanation</Data></Cell>
                <Cell><Data ss:Type="String">Difficulty</Data></Cell>
                <Cell><Data ss:Type="String">Exam Year</Data></Cell>
                <Cell><Data ss:Type="String">Source/Exam</Data></Cell>
                <Cell><Data ss:Type="String">Is Public</Data></Cell>
            </Row>
            <Row>
                <Cell><Data ss:Type="Number">1</Data></Cell>
                <Cell><Data ss:Type="String">Polity</Data></Cell>
                <Cell><Data ss:Type="String">Constitutional Law</Data></Cell>
                <Cell><Data ss:Type="String">Writs</Data></Cell>
                <Cell><Data ss:Type="String">Sample Question 1</Data></Cell>
                <Cell><Data ss:Type="String">Option A</Data></Cell>
                <Cell><Data ss:Type="String">Option B</Data></Cell>
                <Cell><Data ss:Type="String">Option C</Data></Cell>
                <Cell><Data ss:Type="String">Option D</Data></Cell>
                <Cell><Data ss:Type="String">A</Data></Cell>
                <Cell><Data ss:Type="String">Sample explanation</Data></Cell>
                <Cell><Data ss:Type="String">medium</Data></Cell>
                <Cell><Data ss:Type="Number">2024</Data></Cell>
                <Cell><Data ss:Type="String">UPSC</Data></Cell>
                <Cell><Data ss:Type="String">Yes</Data></Cell>
            </Row>';

            // Empty rows
            for ($i = 2; $i <= 12; $i++) { $xml .="\n<Row>" ; $xml .='<Cell><Data ss:Type="Number">' . $i
                . '</Data></Cell>' ; for ($j=1; $j <=14; $j++) { $xml .='<Cell><Data ss:Type="String"></Data></Cell>' ;
                } $xml .="</Row>" ; } $xml .='
</Table>
</Worksheet>
</Workbook>' ; return $xml; } function parseSimpleCSV($filePath) { $questions=[]; $handle=fopen($filePath, 'r' ); if
                ($handle !==FALSE) { fgetcsv($handle); fgetcsv($handle); fgetcsv($handle); $rowNumber=3; while
                (($data=fgetcsv($handle)) !==FALSE) { $rowNumber++; // Skip empty rows if (empty(trim($data[4] ?? '' )))
                continue; $question=[ 'subject'=>
                trim($data[1] ?? 'General'),
                'topic' => trim($data[2] ?? 'General'),
                'subtopic' => trim($data[3] ?? ''),
                'question_text' => trim($data[4] ?? ''),
                'option_a' => trim($data[5] ?? ''),
                'option_b' => trim($data[6] ?? ''),
                'option_c' => trim($data[7] ?? ''),
                'option_d' => trim($data[8] ?? ''),
                'correct_answer' => strtoupper(trim($data[9] ?? 'A')),
                'explanation' => trim($data[10] ?? ''),
                'difficulty' => strtolower(trim($data[11] ?? 'medium')),
                'exam_year' => intval($data[12] ?? date('Y')),
                'source' => trim($data[13] ?? 'CSV Import'),
                'is_public' => (strtolower(trim($data[14] ?? 'yes')) === 'yes') ? 1 : 0,
                'row_number' => $rowNumber,
                'serial_number' => trim($data[0] ?? '')
                ];

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

                $questions[] = $question;
                }
                }
                fclose($handle);
                }

                return $questions;
                }

                function importQuestionsToDatabase($questions, $db) {
                $successCount = 0;
                $errorCount = 0;
                $errors = [];

                foreach ($questions as $question) {
                try {
                // Validate required fields
                if (empty($question['question_text'])) {
                throw new Exception("Question text is required");
                }

                if (empty($question['subject'])) {
                throw new Exception("Subject is required");
                }

                if (!in_array($question['correct_answer'], ['A', 'B', 'C', 'D'])) {
                throw new Exception("Correct answer must be A, B, C, or D");
                }

                // Prepare options JSON
                $options = json_encode([
                'A' => $question['option_a'],
                'B' => $question['option_b'],
                'C' => $question['option_c'],
                'D' => $question['option_d']
                ]);

                // Insert question
                $stmt = $db->prepare("INSERT INTO question_banks (title, subject, topic, subtopic, question_text,
                options, correct_answer, explanation, difficulty, exam_year, source, is_public, institute_id,
                created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

                $result = $stmt->execute([
                '', // title (empty)
                $question['subject'],
                $question['topic'],
                $question['subtopic'],
                $question['question_text'],
                $options,
                $question['correct_answer'],
                $question['explanation'],
                $question['difficulty'],
                $question['exam_year'],
                $question['source'],
                $question['is_public'],
                null, // institute_id set to null
                date('Y-m-d H:i:s')
                ]);

                if ($result) {
                $successCount++;
                } else {
                $errorCount++;
                $errors[] = "Row {$question['row_number']}: Failed to insert question";
                }

                } catch (Exception $e) {
                $errorCount++;
                $errors[] = "Row {$question['row_number']}: " . $e->getMessage();
                }
                }

                return [
                'success_count' => $successCount,
                'error_count' => $errorCount,
                'errors' => $errors
                ];
                }
                ?>