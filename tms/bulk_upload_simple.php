<?php
// bulk_upload_simple.php
// CSV parser + DB importer for question_banks

// if (!function_exists('parseSimpleCSV')) {
//     function parseSimpleCSV(string $filePath): array
//     {
//         $questions = [];
//         if (!is_readable($filePath))
//             return $questions;

//         $handle = fopen($filePath, 'r');
//         if ($handle === false)
//             return $questions;

//         // Skip first 4 rows (metadata), header at row 5
//         for ($i = 0; $i < 4; $i++) {
//             fgetcsv($handle);
//         }
//         $headers = fgetcsv($handle);

//         $isAdvanced = ($headers && count($headers) >= 20 && stripos(implode(',', $headers), 'Image') !== false);
//         $row = 5;

//         while (($data = fgetcsv($handle)) !== false) {
//             $row++;
//             if (!is_array($data) || count(array_filter($data)) === 0)
//                 continue;

//             if ($isAdvanced) {
//                 $options = [
//                     'A' => trim($data[5] ?? ''),
//                     'B' => trim($data[7] ?? ''),
//                     'C' => trim($data[9] ?? ''),
//                     'D' => trim($data[11] ?? ''),
//                     'E' => trim($data[13] ?? ''),
//                 ];
//                 $item = [
//                     'subject' => trim($data[0] ?? 'General'),
//                     'topic' => trim($data[1] ?? ''),
//                     'subtopic' => trim($data[2] ?? ''),
//                     'question_text' => trim($data[3] ?? ''),
//                     'image' => trim($data[4] ?? null),
//                     'options' => json_encode($options),
//                     'correct_answer' => strtoupper(trim($data[15] ?? 'A')),
//                     'explanation' => trim($data[19] ?? ''),
//                     'difficulty' => strtolower(trim($data[16] ?? 'medium')),
//                     'exam_year' => intval($data[17] ?? date('Y')),
//                     'source' => trim($data[18] ?? 'CSV Import'),
//                     'is_public' => 1,
//                     'row_number' => $row,
//                 ];
//                 $validAnswers = ['A', 'B', 'C', 'D', 'E'];
//             } else {
//                 $options = [
//                     'A' => trim($data[4] ?? ''),
//                     'B' => trim($data[5] ?? ''),
//                     'C' => trim($data[6] ?? ''),
//                     'D' => trim($data[7] ?? ''),
//                 ];
//                 $item = [
//                     'subject' => trim($data[0] ?? 'General'),
//                     'topic' => trim($data[1] ?? ''),
//                     'subtopic' => trim($data[2] ?? ''),
//                     'question_text' => trim($data[3] ?? ''),
//                     'options' => json_encode($options),
//                     'correct_answer' => strtoupper(trim($data[8] ?? 'A')),
//                     'explanation' => trim($data[9] ?? ''),
//                     'difficulty' => strtolower(trim($data[10] ?? 'medium')),
//                     'exam_year' => intval($data[11] ?? date('Y')),
//                     'source' => trim($data[12] ?? 'CSV Import'),
//                     'image' => null,
//                     'is_public' => (strtolower(trim($data[13] ?? 'yes')) === 'yes') ? 1 : 0,
//                     'row_number' => $row,
//                 ];
//                 $validAnswers = ['A', 'B', 'C', 'D'];
//             }

//             // Validation
//             if (!empty($item['question_text']) && !empty($item['subject'])) {
//                 if (!in_array($item['correct_answer'], $validAnswers)) {
//                     $item['correct_answer'] = 'A';
//                 }
//                 if (!in_array($item['difficulty'], ['easy', 'medium', 'hard'])) {
//                     $item['difficulty'] = 'medium';
//                 }
//                 $questions[] = $item;
//             }
//         }

//         fclose($handle);
//         return $questions;
//     }
// }

// if (!function_exists('importQuestionsToDatabase')) {
//     function importQuestionsToDatabase(array $questions, PDO $db): array
//     {
//         $success = 0;
//         $skipped = 0;
//         $errors = [];

//         $sql = "INSERT INTO question_banks
//             (title, subject, topic, subtopic, question_text, options, correct_answer, explanation, difficulty, exam_year,
//              source, image, is_public, institute_id, created_at)
//             VALUES
//             (:title, :subject, :topic, :subtopic, :question_text, :options, :correct_answer, :explanation, :difficulty,
//              :exam_year, :source, :image, :is_public, :institute_id, NOW())";

//         $stmt = $db->prepare($sql);

//         foreach ($questions as $q) {
//             try {
//                 $stmt->execute([
//                     ':title' => mb_substr($q['question_text'], 0, 50),
//                     ':subject' => $q['subject'],
//                     ':topic' => $q['topic'],
//                     ':subtopic' => $q['subtopic'],
//                     ':question_text' => $q['question_text'],
//                     ':options' => $q['options'],
//                     ':correct_answer' => $q['correct_answer'],
//                     ':explanation' => $q['explanation'] ?? null,
//                     ':difficulty' => $q['difficulty'] ?? 'medium',
//                     ':exam_year' => $q['exam_year'] ?? date('Y'),
//                     ':source' => $q['source'] ?? 'CSV Import',
//                     ':image' => $q['image'] ?? null,
//                     ':is_public' => $q['is_public'] ?? 1,
//                     ':institute_id' => $_SESSION['institute_id'] ?? 0,
//                 ]);
//                 $success++;
//             } catch (Exception $e) {
//                 error_log('Import question failed: ' . $e->getMessage());
//                 $errors[] = $e->getMessage();
//                 $skipped++;
//             }
//         }

//         return [
//             'success_count' => $success,
//             'error_count' => $skipped,
//             'errors' => $errors
//         ];
//     }
// }
