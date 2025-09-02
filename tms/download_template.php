<?php
// Make sure you installed PhpSpreadsheet via Composer:
// composer require phpoffice/phpspreadsheet

require 'D:\EA Dream Supporters Website\EA Web\xaampp\htdocs\eawebnew\vendor\autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

function createQuestionBankTemplate()
{
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Merge title cells across the header width (adjusted later)
    // We'll compute last column after headers are known and update the merge range.
    $sheet->setCellValue('A1', 'Sprints Pro Q/A Template');
    $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
    $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);

    // Leave row 4 empty, start headers at row 5
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

    $lastIndex = count($headers);
    for ($i = 1; $i <= $lastIndex; $i++) {
        $col = Coordinate::stringFromColumnIndex($i);
        $sheet->setCellValue($col . '5', $headers[$i - 1]);
    }

    // Style headers
    $lastCol = Coordinate::stringFromColumnIndex($lastIndex);
    // Update merged title to span across the header columns (row 1-3)
    $sheet->mergeCells("A1:{$lastCol}3");
    $headerRange = "A5:{$lastCol}5";
    $sheet->getStyle($headerRange)->getFont()->setBold(true);
    $sheet->getStyle($headerRange)->getFill()
        ->setFillType(Fill::FILL_SOLID)
        ->getStartColor()->setARGB('FFD9E1F2');
    $sheet->getStyle($headerRange)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle($headerRange)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

    // Auto-size columns using the coordinate helper
    for ($i = 1; $i <= $lastIndex; $i++) {
        $col = Coordinate::stringFromColumnIndex($i);
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    return $spreadsheet;
}

// ==================== Download Handler ====================
$spreadsheet = createQuestionBankTemplate();
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="question_template.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
