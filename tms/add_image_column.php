<?php
// Database migration script to add image column
require_once 'config/database.php';

try {
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME,
        DB_USER,
        DB_PASS
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if image column already exists
    $stmt = $pdo->query("DESCRIBE question_banks");
    $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);

    if (!in_array('image', $columns)) {
        // Add the image column to question_banks table
        $sql = 'ALTER TABLE question_banks ADD COLUMN image VARCHAR(255) AFTER source';
        $pdo->exec($sql);
        echo "Successfully added 'image' column to question_banks table\n";
    } else {
        echo "'image' column already exists in question_banks table\n";
    }

} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage() . "\n";
}
?>
