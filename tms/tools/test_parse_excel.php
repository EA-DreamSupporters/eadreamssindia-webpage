<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../pages/questions/functions/parse_and_import.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

$tmpDir = __DIR__ . '/tmp';
@mkdir($tmpDir, 0755, true);
$testFile = $tmpDir . '/sample_upload.xlsx';

// Build spreadsheet matching the header row at row 5
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$headers = [
    "Subject",
    "Topic",
    "Subtopic",
    "Question Text",
    "Question Text (Tamil)",
    "Question Text (Hindi)",
    "Question Image",
    "Option A Text",
    "Option A Text (Tamil)",
    "Option A Text (Hindi)",
    "Option A Image",
    "Option B Text",
    "Option B Text (Tamil)",
    "Option B Text (Hindi)",
    "Option B Image",
    "Option C Text",
    "Option C Text (Tamil)",
    "Option C Text (Hindi)",
    "Option C Image",
    "Option D Text",
    "Option D Text (Tamil)",
    "Option D Text (Hindi)",
    "Option D Image",
    "Option E Text",
    "Option E Text (Tamil)",
    "Option E Text (Hindi)",
    "Option E Image",
    "Correct Answer",
    "Difficulty Level (Easy, Medium, Hard)",
    "Exam Year",
    "Source/Exam",
    "Explanation"
];

// put headers on row 5
$row = 5;
$colIndex = 1;
foreach ($headers as $h) {
    $col = Coordinate::stringFromColumnIndex($colIndex);
    $sheet->setCellValue($col . $row, $h);
    $colIndex++;
}

// one sample question on row 6
$values = [
    'General Knowledge',
    'Sample Topic',
    'Sample Subtopic',
    'What is 2+2?',
    '',
    '',
    '',
    '4',
    '',
    '',
    '',
    '3',
    '',
    '',
    '',
    '',
    '',
    '',
    '',
    '',
    '',
    '',
    '',
    '',
    '',
    '',
    '',
    'A',
    'Easy',
    '2020',
    'Sample Exam',
    'Simple math question'
];

$colIndex = 1;
foreach ($values as $v) {
    $col = Coordinate::stringFromColumnIndex($colIndex);
    $sheet->setCellValue($col . ($row + 1), $v);
    $colIndex++;
}

$writer = new Xlsx($spreadsheet);
$writer->save($testFile);

// Now run parseExcelFile
try {
    $questions = parseExcelFile($testFile);
    echo json_encode(['ok' => true, 'count' => count($questions), 'questions' => $questions], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
}

@unlink($testFile);
