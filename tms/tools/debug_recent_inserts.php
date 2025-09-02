<?php
// CLI helper: list recent inserts from question_banks for debugging bulk upload
// Usage: php "d:/EA Dream Supporters Website/EA Web/xaampp/htdocs/eawebnew/tms/debug_recent_inserts.php"

// Don't produce HTML or depend on web environment
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Load database connection from the project's config
$dbConfigPath = __DIR__ . '/config/database.php';
if (!file_exists($dbConfigPath)) {
    echo "Database config not found at: $dbConfigPath\n";
    exit(1);
}

require_once $dbConfigPath;

try {
    // Use the Database singleton from config
    $db = Database::getInstance()->getConnection();

    $limit = 20;
    $stmt = $db->prepare('SELECT id, title, institute_id, is_public, created_at FROM question_banks ORDER BY created_at DESC LIMIT ?');
    $stmt->bindValue(1, (int) $limit, PDO::PARAM_INT);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "Recent $limit rows from question_banks:\n";
    if (empty($rows)) {
        echo "(no rows found)\n";
        exit(0);
    }

    // Pretty print table-like output
    $fmt = "%-6s %-40s %-12s %-8s %-20s\n";
    printf($fmt, 'id', 'title', 'institute_id', 'is_public', 'created_at');
    printf($fmt, str_repeat('-', 6), str_repeat('-', 40), str_repeat('-', 12), str_repeat('-', 8), str_repeat('-', 20));
    foreach ($rows as $r) {
        $title = mb_substr($r['title'] ?? '', 0, 40);
        printf($fmt, $r['id'], $title, $r['institute_id'] ?? 'NULL', ($r['is_public'] ? '1' : '0'), $r['created_at']);
    }

    // Also dump JSON for copy/paste
    echo "\nJSON output:\n";
    echo json_encode($rows, JSON_PRETTY_PRINT) . "\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}

