<?php
// Parsing and import helpers for Questions page
// Provides: parseSimpleCSV($filePath), parseExcelFile($filePath), importQuestionsToDatabase($questions, $db)

if (!function_exists('parseSimpleCSV')) {
    function parseSimpleCSV($filePath)
    {
        $questions = [];
        $handle = fopen($filePath, 'r');

        if ($handle !== FALSE) {
            $rowNumber = 0;

            // Read until we find a non-empty header row
            $headers = null;
            while (($row = fgetcsv($handle)) !== FALSE) {
                $rowNumber++;
                if (is_array($row) && count(array_filter($row)) > 0) {
                    $headers = $row;
                    break;
                }
            }

            if ($headers === null) {
                fclose($handle);
                return $questions;
            }

            // normalize header names -> index map using canonical mapping
            $headerMap = [];
            // helper to canonicalize header names to known fields
            $canonicalize = function ($h) {
                $s = strtolower(trim((string) $h));
                // remove extra punctuation
                $s = preg_replace('/[\"\'\`\*\?\!\:]/', '', $s);
                // normalize parentheses and multiple spaces
                $s = preg_replace('/\s+/', ' ', $s);
                $s = trim($s);
                // common aliases mapping
                // question
                if (preg_match('/question(\s|_)?text/', $s) || preg_match('/^question$/', $s)) {
                    if (strpos($s, 'tamil') !== false || strpos($s, '(ta)') !== false)
                        return 'question_text_ta';
                    if (strpos($s, 'hindi') !== false || strpos($s, '(hi)') !== false)
                        return 'question_text_hi';
                    return 'question_text';
                }
                // option X
                if (preg_match('/option\s*([a-e])/i', $s, $m)) {
                    $k = strtoupper($m[1]);
                    if (strpos($s, 'tamil') !== false || strpos($s, '(ta)') !== false)
                        return 'option_' . strtolower($k) . '_ta';
                    if (strpos($s, 'hindi') !== false || strpos($s, '(hi)') !== false)
                        return 'option_' . strtolower($k) . '_hi';
                    if (strpos($s, 'image') !== false || strpos($s, 'img') !== false)
                        return 'option_' . strtolower($k) . '_image';
                    return 'option_' . strtolower($k);
                }
                // generic option columns like A,B,C
                if (preg_match('/^([a-e])$/i', $s, $m)) {
                    return 'option_' . strtolower($m[1]);
                }
                if (strpos($s, 'question image') !== false || strpos($s, 'image') !== false)
                    return 'question_image';
                if (strpos($s, 'correct') !== false && strpos($s, 'answer') !== false)
                    return 'correct_answer';
                if (strpos($s, 'difficulty') !== false)
                    return 'difficulty';
                if (strpos($s, 'exam') !== false || strpos($s, 'year') !== false)
                    return 'exam_year';
                if (strpos($s, 'source') !== false || strpos($s, 'exam') !== false)
                    return 'source';
                if (strpos($s, 'explanation') !== false)
                    return 'explanation';
                if (strpos($s, 'subject') !== false)
                    return 'subject';
                if (strpos($s, 'topic') !== false)
                    return 'topic';
                if (strpos($s, 'subtopic') !== false)
                    return 'subtopic';
                return $s;
            };

            foreach ($headers as $i => $h) {
                $canon = $canonicalize($h);
                if (!isset($headerMap[$canon])) {
                    $headerMap[$canon] = $i;
                }
            }

            // helper to get a named column (input name can be a human header), fallback to default
            $get = function ($name, $default = '') use ($headerMap, $canonicalize) {
                return function ($row) use ($name, $headerMap, $default, $canonicalize) {
                    $n = $canonicalize($name);
                    if (isset($headerMap[$n]) && isset($row[$headerMap[$n]])) {
                        return trim($row[$headerMap[$n]]);
                    }
                    return $default;
                };
            };

            // iterate remaining rows
            while (($data = fgetcsv($handle)) !== FALSE) {
                $rowNumber++;
                if (count(array_filter($data)) === 0)
                    continue;

                // mapping similar to parseExcelFile
                $getVal = function ($name, $default = '') use ($headerMap, $data, $canonicalize) {
                    $n = $canonicalize($name);
                    if (isset($headerMap[$n]) && isset($data[$headerMap[$n]])) {
                        return trim($data[$headerMap[$n]]);
                    }
                    return $default;
                };

                $question = [
                    'subject' => trim($getVal('subject', $data[0] ?? '')) ?: 'General',
                    'topic' => trim($getVal('topic', $data[1] ?? '')) ?: 'General',
                    'subtopic' => trim($getVal('subtopic', $data[2] ?? '')) ?: '',
                    'question_text' => trim($getVal('question text', $data[3] ?? '')) ?: '',
                    'question_text_ta' => trim($getVal('question text (tamil)', $data[4] ?? '')) ?: '',
                    'question_text_hi' => trim($getVal('question text (hindi)', $data[5] ?? '')) ?: '',
                    'question_image' => trim($getVal('question image', $data[6] ?? '')) ?: '',
                    'option_a' => trim($getVal('option a text', $data[7] ?? '')) ?: '',
                    'option_a_ta' => trim($getVal('option a text (tamil)', $data[8] ?? '')) ?: '',
                    'option_a_hi' => trim($getVal('option a text (hindi)', $data[9] ?? '')) ?: '',
                    'option_a_image' => trim($getVal('option a image', $data[10] ?? '')) ?: '',
                    'option_b' => trim($getVal('option b text', $data[11] ?? '')) ?: '',
                    'option_b_ta' => trim($getVal('option b text (tamil)', $data[12] ?? '')) ?: '',
                    'option_b_hi' => trim($getVal('option b text (hindi)', $data[13] ?? '')) ?: '',
                    'option_b_image' => trim($getVal('option b image', $data[14] ?? '')) ?: '',
                    'option_c' => trim($getVal('option c text', $data[15] ?? '')) ?: '',
                    'option_c_ta' => trim($getVal('option c text (tamil)', $data[16] ?? '')) ?: '',
                    'option_c_hi' => trim($getVal('option c text (hindi)', $data[17] ?? '')) ?: '',
                    'option_c_image' => trim($getVal('option c image', $data[18] ?? '')) ?: '',
                    'option_d' => trim($getVal('option d text', $data[19] ?? '')) ?: '',
                    'option_d_ta' => trim($getVal('option d text (tamil)', $data[20] ?? '')) ?: '',
                    'option_d_hi' => trim($getVal('option d text (hindi)', $data[21] ?? '')) ?: '',
                    'option_d_image' => trim($getVal('option d image', $data[22] ?? '')) ?: '',
                    'option_e' => trim($getVal('option e text', $data[23] ?? '')) ?: '',
                    'option_e_ta' => trim($getVal('option e text (tamil)', $data[24] ?? '')) ?: '',
                    'option_e_hi' => trim($getVal('option e text (hindi)', $data[25] ?? '')) ?: '',
                    'option_e_image' => trim($getVal('option e image', $data[26] ?? '')) ?: '',
                    'correct_answer' => strtoupper(trim($getVal('correct answer', $data[27] ?? 'A'))) ?: 'A',
                    'difficulty' => strtolower(trim($getVal('difficulty level (easy, medium, hard)', $data[28] ?? 'medium'))) ?: 'medium',
                    'exam_year' => intval($getVal('exam year', $data[29] ?? date('Y'))) ?: date('Y'),
                    'source' => trim($getVal('source/exam', $data[30] ?? 'CSV Import')) ?: 'CSV Import',
                    'explanation' => trim($getVal('explanation', $data[31] ?? '')) ?: '',
                    'is_public' => 1,
                    'row_number' => $rowNumber
                ];

                // build i18n similar to Excel parser
                $i18n = [];
                if (!empty($question['question_text_ta'])) {
                    $i18n['ta']['question_text'] = $question['question_text_ta'];
                }
                if (!empty($question['question_text_hi'])) {
                    $i18n['hi']['question_text'] = $question['question_text_hi'];
                }
                foreach (['a', 'b', 'c', 'd', 'e'] as $opt) {
                    $k_en = 'option_' . $opt;
                    $k_ta = $k_en . '_ta';
                    $k_hi = $k_en . '_hi';
                    if (!empty($question[$k_en])) {
                        $i18n['en'][strtoupper($opt)] = $question[$k_en];
                    }
                    if (!empty($question[$k_ta])) {
                        $i18n['ta'][strtoupper($opt)] = $question[$k_ta];
                    }
                    if (!empty($question[$k_hi])) {
                        $i18n['hi'][strtoupper($opt)] = $question[$k_hi];
                    }
                }
                if (!empty($i18n)) {
                    $question['i18n'] = $i18n;
                }

                $validAnswers = ['A', 'B', 'C', 'D', 'E'];
                if (!in_array($question['correct_answer'], $validAnswers)) {
                    $question['correct_answer'] = 'A';
                }

                $difficultyMap = [
                    'easy' => 'easy',
                    'medium' => 'medium',
                    'hard' => 'hard',
                    'simple' => 'easy',
                    'normal' => 'medium',
                    'difficult' => 'hard'
                ];
                $normalizedDifficulty = $difficultyMap[$question['difficulty']] ?? 'medium';
                $question['difficulty'] = $normalizedDifficulty;

                // only include rows that have required fields
                if (!empty(trim($question['question_text'])) && !empty(trim($question['subject']))) {
                    $questions[] = $question;
                }
            }

            fclose($handle);
        }

        return $questions;
    }
}

if (!function_exists('parseExcelFile')) {
    function parseExcelFile($filePath)
    {
        try {
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filePath);
            $worksheet = $spreadsheet->getActiveSheet();
            $questions = [];

            $highestRow = $worksheet->getHighestRow();
            $highestColumn = $worksheet->getHighestColumn();

            $headerRow = 5;
            $startRow = 6;

            // canonicalize header names to known fields
            $headerMap = [];
            $maxColIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);
            $canonicalize = function ($h) {
                $s = strtolower(trim((string) $h));
                $s = preg_replace('/[\"\'\`\*\?\!\:]/', '', $s);
                $s = preg_replace('/\s+/', ' ', $s);
                $s = trim($s);
                if (preg_match('/question(\s|_)?text/', $s) || preg_match('/^question$/', $s)) {
                    if (strpos($s, 'tamil') !== false || strpos($s, '(ta)') !== false)
                        return 'question_text_ta';
                    if (strpos($s, 'hindi') !== false || strpos($s, '(hi)') !== false)
                        return 'question_text_hi';
                    return 'question_text';
                }
                if (preg_match('/option\s*([a-e])/i', $s, $m)) {
                    $k = strtoupper($m[1]);
                    if (strpos($s, 'tamil') !== false || strpos($s, '(ta)') !== false)
                        return 'option_' . strtolower($k) . '_ta';
                    if (strpos($s, 'hindi') !== false || strpos($s, '(hi)') !== false)
                        return 'option_' . strtolower($k) . '_hi';
                    if (strpos($s, 'image') !== false || strpos($s, 'img') !== false)
                        return 'option_' . strtolower($k) . '_image';
                    return 'option_' . strtolower($k);
                }
                if (preg_match('/^([a-e])$/i', $s, $m)) {
                    return 'option_' . strtolower($m[1]);
                }
                if (strpos($s, 'question image') !== false || strpos($s, 'image') !== false)
                    return 'question_image';
                if (strpos($s, 'correct') !== false && strpos($s, 'answer') !== false)
                    return 'correct_answer';
                if (strpos($s, 'difficulty') !== false)
                    return 'difficulty';
                if (strpos($s, 'exam') !== false || strpos($s, 'year') !== false)
                    return 'exam_year';
                if (strpos($s, 'source') !== false || strpos($s, 'exam') !== false)
                    return 'source';
                if (strpos($s, 'explanation') !== false)
                    return 'explanation';
                if (strpos($s, 'subject') !== false)
                    return 'subject';
                if (strpos($s, 'topic') !== false)
                    return 'topic';
                if (strpos($s, 'subtopic') !== false)
                    return 'subtopic';
                return $s;
            };

            for ($ci = 1; $ci <= $maxColIndex; $ci++) {
                $col = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($ci);
                $h = trim((string) $worksheet->getCell($col . $headerRow)->getCalculatedValue());
                if ($h !== '') {
                    $canon = $canonicalize($h);
                    if (!isset($headerMap[$canon]))
                        $headerMap[$canon] = $ci - 1;
                }
            }

            for ($row = $startRow; $row <= $highestRow; $row++) {
                $data = [];
                $maxColIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);
                for ($ci = 1; $ci <= $maxColIndex; $ci++) {
                    $col = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($ci);
                    $data[] = trim($worksheet->getCell($col . $row)->getCalculatedValue() ?? '');
                }

                if (count(array_filter($data)) === 0) {
                    continue;
                }

                $get = function ($name, $default = '') use ($data, $headerMap) {
                    $name = strtolower($name);
                    if (isset($headerMap[$name]) && isset($data[$headerMap[$name]])) {
                        return $data[$headerMap[$name]];
                    }
                    return $default;
                };

                $question = [
                    'subject' => trim($get('subject', $data[0] ?? '')) ?: 'General',
                    'topic' => trim($get('topic', $data[1] ?? '')) ?: 'General',
                    'subtopic' => trim($get('subtopic', $data[2] ?? '')) ?: '',
                    'question_text' => trim($get('question text', $data[3] ?? '')) ?: '',
                    'question_text_ta' => trim($get('question text (tamil)', $data[4] ?? '')) ?: '',
                    'question_text_hi' => trim($get('question text (hindi)', $data[5] ?? '')) ?: '',
                    'question_image' => trim($get('question image', $data[6] ?? '')) ?: '',
                    'option_a' => trim($get('option a text', $data[7] ?? '')) ?: '',
                    'option_a_ta' => trim($get('option a text (tamil)', $data[8] ?? '')) ?: '',
                    'option_a_hi' => trim($get('option a text (hindi)', $data[9] ?? '')) ?: '',
                    'option_a_image' => trim($get('option a image', $data[10] ?? '')) ?: '',
                    'option_b' => trim($get('option b text', $data[11] ?? '')) ?: '',
                    'option_b_ta' => trim($get('option b text (tamil)', $data[12] ?? '')) ?: '',
                    'option_b_hi' => trim($get('option b text (hindi)', $data[13] ?? '')) ?: '',
                    'option_b_image' => trim($get('option b image', $data[14] ?? '')) ?: '',
                    'option_c' => trim($get('option c text', $data[15] ?? '')) ?: '',
                    'option_c_ta' => trim($get('option c text (tamil)', $data[16] ?? '')) ?: '',
                    'option_c_hi' => trim($get('option c text (hindi)', $data[17] ?? '')) ?: '',
                    'option_c_image' => trim($get('option c image', $data[18] ?? '')) ?: '',
                    'option_d' => trim($get('option d text', $data[19] ?? '')) ?: '',
                    'option_d_ta' => trim($get('option d text (tamil)', $data[20] ?? '')) ?: '',
                    'option_d_hi' => trim($get('option d text (hindi)', $data[21] ?? '')) ?: '',
                    'option_d_image' => trim($get('option d image', $data[22] ?? '')) ?: '',
                    'option_e' => trim($get('option e text', $data[23] ?? '')) ?: '',
                    'option_e_ta' => trim($get('option e text (tamil)', $data[24] ?? '')) ?: '',
                    'option_e_hi' => trim($get('option e text (hindi)', $data[25] ?? '')) ?: '',
                    'option_e_image' => trim($get('option e image', $data[26] ?? '')) ?: '',
                    'correct_answer' => strtoupper(trim($get('correct answer', $data[27] ?? 'A'))) ?: 'A',
                    'difficulty' => strtolower(trim($get('difficulty level (easy, medium, hard)', $data[28] ?? 'medium'))) ?: 'medium',
                    'exam_year' => intval($get('exam year', $data[29] ?? date('Y'))) ?: date('Y'),
                    'source' => trim($get('source/exam', $data[30] ?? 'Excel Import')) ?: 'Excel Import',
                    'explanation' => trim($get('explanation', $data[31] ?? '')) ?: '',
                    'is_public' => 1,
                    'row_number' => $row
                ];

                $i18n = [];
                if (!empty($question['question_text_ta'])) {
                    $i18n['ta']['question_text'] = $question['question_text_ta'];
                }
                if (!empty($question['question_text_hi'])) {
                    $i18n['hi']['question_text'] = $question['question_text_hi'];
                }
                foreach (['a', 'b', 'c', 'd', 'e'] as $opt) {
                    $k_en = 'option_' . $opt;
                    $k_ta = $k_en . '_ta';
                    $k_hi = $k_en . '_hi';
                    if (!empty($question[$k_en])) {
                        $i18n['en'][strtoupper($opt)] = $question[$k_en];
                    }
                    if (!empty($question[$k_ta])) {
                        $i18n['ta'][strtoupper($opt)] = $question[$k_ta];
                    }
                    if (!empty($question[$k_hi])) {
                        $i18n['hi'][strtoupper($opt)] = $question[$k_hi];
                    }
                }
                if (!empty($i18n)) {
                    $question['i18n'] = $i18n;
                }

                $validAnswers = ['A', 'B', 'C', 'D', 'E'];
                if (!in_array($question['correct_answer'], $validAnswers)) {
                    $question['correct_answer'] = 'A';
                }

                $difficultyMap = [
                    'easy' => 'easy',
                    'medium' => 'medium',
                    'hard' => 'hard',
                    'simple' => 'easy',
                    'normal' => 'medium',
                    'difficult' => 'hard'
                ];
                $normalizedDifficulty = $difficultyMap[$question['difficulty']] ?? 'medium';
                $question['difficulty'] = $normalizedDifficulty;

                $questions[] = $question;
            }

            return $questions;
        } catch (Exception $e) {
            throw new Exception('Error parsing Excel file: ' . $e->getMessage());
        }
    }
}

if (!function_exists('importQuestionsToDatabase')) {
    function importQuestionsToDatabase($questions, $db)
    {
        $successCount = 0;
        $errorCount = 0;
        $errors = [];

        $user = getCurrentUser();
        $instituteId = (isset($user['institute_id']) && $user['institute_id']) ? (int) $user['institute_id'] : 1;

        foreach ($questions as $index => $question) {
            try {
                $options = [];

                if (!empty($question['option_a'])) {
                    $options['A'] = ['text' => $question['option_a'], 'image' => $question['option_a_image'] ?? ''];
                }
                if (!empty($question['option_b'])) {
                    $options['B'] = ['text' => $question['option_b'], 'image' => $question['option_b_image'] ?? ''];
                }
                if (!empty($question['option_c'])) {
                    $options['C'] = ['text' => $question['option_c'], 'image' => $question['option_c_image'] ?? ''];
                }
                if (!empty($question['option_d'])) {
                    $options['D'] = ['text' => $question['option_d'], 'image' => $question['option_d_image'] ?? ''];
                }
                if (!empty($question['option_e'])) {
                    $options['E'] = ['text' => $question['option_e'], 'image' => $question['option_e_image'] ?? ''];
                }

                if (!empty($question['question_image'])) {
                    $options['image'] = $question['question_image'];
                }

                if (!empty($question['i18n']) && is_array($question['i18n'])) {
                    $options['i18n'] = array_merge($options['i18n'] ?? [], $question['i18n']);
                }
                $optionsJson = json_encode($options);

                if (empty(trim($question['question_text'] ?? '')) || empty(trim($question['subject'] ?? ''))) {
                    $errorCount++;
                    $errors[] = "Row " . ($question['row_number'] ?? $index + 1) . ": Missing required fields (subject or question_text). Skipped.";
                    continue;
                }

                $titleWords = explode(' ', trim($question['question_text']));
                $title = trim(($question['subject'] ?? 'General') . ' - ' . implode(' ', array_slice($titleWords, 0, 5)));
                if (empty($title)) {
                    $title = 'Imported Question';
                }
                if (strlen($title) > 100) {
                    $title = mb_substr($title, 0, 97) . '...';
                }

                $stmt = $db->prepare("INSERT INTO question_banks (title, subject, topic, subtopic, question_text, options, correct_answer, explanation, difficulty, exam_year, source, image, is_public, institute_id, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

                $result = $stmt->execute([
                    $title,
                    $question['subject'],
                    $question['topic'],
                    $question['subtopic'],
                    $question['question_text'],
                    $optionsJson,
                    $question['correct_answer'],
                    $question['explanation'],
                    $question['difficulty'],
                    $question['exam_year'],
                    $question['source'],
                    $question['question_image'] ?? '',
                    $question['is_public'] ? 1 : 0,
                    $instituteId,
                    date('Y-m-d H:i:s')
                ]);

                if ($result) {
                    // After successful insert, persist question_text_ta / question_text_hi into dedicated columns
                    // if those columns exist in the target table. This is optional and safe for schemas
                    // that don't have these columns.
                    try {
                        $lastId = $db->lastInsertId();
                        $checkCol = $db->prepare("SHOW COLUMNS FROM question_banks LIKE ?");
                        // question_text_ta
                        $checkCol->execute(['question_text_ta']);
                        if ($checkCol->fetch()) {
                            $db->prepare("UPDATE question_banks SET question_text_ta = ? WHERE id = ?")->execute([
                                $question['question_text_ta'] ?? '',
                                $lastId
                            ]);
                        }
                        // question_text_hi
                        $checkCol->execute(['question_text_hi']);
                        if ($checkCol->fetch()) {
                            $db->prepare("UPDATE question_banks SET question_text_hi = ? WHERE id = ?")->execute([
                                $question['question_text_hi'] ?? '',
                                $lastId
                            ]);
                        }
                    } catch (Exception $e) {
                        // Non-fatal: ignore DB-level issues here and continue
                    }
                    $successCount++;
                } else {
                    $errorCount++;
                    $errors[] = "Row " . ($question['row_number'] ?? $index + 1) . ": Failed to insert question";
                }

            } catch (Exception $e) {
                $errorCount++;
                $errors[] = "Row " . ($question['row_number'] ?? $index + 1) . ": " . $e->getMessage();
            }
        }

        return [
            'success_count' => $successCount,
            'error_count' => $errorCount,
            'errors' => $errors
        ];
    }
}
