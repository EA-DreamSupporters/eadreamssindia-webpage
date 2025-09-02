<?php
// Verify database table structure
require_once 'config/database.php';

try {
    $pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->query('DESCRIBE question_banks');
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "Current question_banks table structure:\n";
    foreach ($columns as $column) {
        echo "- {$column['Field']} ({$column['Type']})\n";
    }

    // Check if image column exists
    $columnNames = array_column($columns, 'Field');
    if (in_array('image', $columnNames)) {
        echo "\n✅ SUCCESS: 'image' column exists in the table!\n";
    } else {
        echo "\n❌ ERROR: 'image' column is missing from the table!\n";
    }

} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage() . "\n";
}
?>
