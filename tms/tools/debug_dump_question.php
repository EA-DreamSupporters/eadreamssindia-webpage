<?php
// Debug: dump a single question row including options column
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/config/database.php';
try {
    $db = Database::getInstance()->getConnection();
    $id = $argv[1] ?? 80;
    $stmt = $db->prepare('SELECT * FROM question_banks WHERE id = ? LIMIT 1');
    $stmt->execute([$id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$row) {
        echo "No row with id=$id\n";
        exit(0);
    }
    echo "Row id={$row['id']} title={$row['title']}\n";
    echo "options column raw:\n";
    var_export($row['options']);
    echo "\n\nDecoded JSON:\n";
    $decoded = json_decode($row['options'], true);
    var_export($decoded);
    echo "\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
