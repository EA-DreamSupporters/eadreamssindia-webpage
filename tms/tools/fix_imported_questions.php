<?php
// Safe fixer: backup and fix question_banks rows with NULL/0 institute_id or empty title
// Usage: php tms/fix_imported_questions.php

error_reporting(E_ALL);
ini_set('display_errors', '1');

$dbConfigPath = __DIR__ . '/config/database.php';
if (!file_exists($dbConfigPath)) {
    echo "Database config not found at: $dbConfigPath\n";
    exit(1);
}
require_once $dbConfigPath;

try {
    $db = Database::getInstance()->getConnection();

    // Select affected rows for backup
    $stmt = $db->prepare('SELECT * FROM question_banks WHERE institute_id IS NULL OR institute_id = 0 OR title = "" OR title IS NULL ORDER BY created_at DESC LIMIT 100');
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($rows)) {
        echo "No affected rows found. Nothing to do.\n";
        exit(0);
    }

    $backupFile = __DIR__ . '/debug_question_banks_backup_' . date('Ymd_His') . '.json';
    file_put_contents($backupFile, json_encode($rows, JSON_PRETTY_PRINT));
    echo "Backed up " . count($rows) . " rows to $backupFile\n";

    $updated = 0;
    foreach ($rows as $r) {
        $id = (int) $r['id'];
        $newInstitute = ($r['institute_id'] && (int) $r['institute_id'] > 0) ? (int) $r['institute_id'] : 1;
        $title = trim($r['title'] ?? '');
        if (empty($title)) {
            $snippet = trim(($r['subject'] ?? 'General') . ' - ' . mb_substr(trim($r['question_text'] ?? ''), 0, 50));
            if (empty($snippet))
                $snippet = 'Imported Question ' . $id;
            $title = $snippet;
        }

        $u = $db->prepare('UPDATE question_banks SET institute_id = ?, title = ? WHERE id = ?');
        $u->execute([$newInstitute, $title, $id]);
        $updated += $u->rowCount();
        echo "Updated id=$id -> institute_id=$newInstitute, title='" . addslashes($title) . "'\n";
    }

    echo "Total rows updated: $updated\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
