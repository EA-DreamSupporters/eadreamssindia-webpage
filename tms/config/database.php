<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'ea_tms_db');
define('DB_USER', 'root');
define('DB_PASS', '');

class Database {
    private static $instance = null;
    private $connection;
    
    private function __construct() {
        try {
            $this->connection = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
                DB_USER,
                DB_PASS,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
                ]
            );
        } catch(PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->connection;
    }
}

// Initialize database connection
$db = Database::getInstance()->getConnection();

// Create tables if they don't exist
$tables = [
    "CREATE TABLE IF NOT EXISTS users (
        id INT PRIMARY KEY AUTO_INCREMENT,
        username VARCHAR(50) UNIQUE NOT NULL,
        email VARCHAR(100) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        role ENUM('super_admin', 'admin', 'vendor', 'student') DEFAULT 'student',
        institute_id INT DEFAULT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )",
    
    "CREATE TABLE IF NOT EXISTS institutions (
        id INT PRIMARY KEY AUTO_INCREMENT,
        name VARCHAR(200) NOT NULL,
        logo VARCHAR(255),
        branding_config JSON,
        subscription_plan ENUM('basic', 'premium', 'enterprise') DEFAULT 'basic',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",
    
    "CREATE TABLE IF NOT EXISTS question_banks (
        id INT PRIMARY KEY AUTO_INCREMENT,
        title VARCHAR(200) NOT NULL,
        subject VARCHAR(100),
        topic VARCHAR(100),
        subtopic VARCHAR(100),
        question_text TEXT NOT NULL,
        options JSON,
        correct_answer VARCHAR(10),
        explanation TEXT,
        difficulty ENUM('easy', 'medium', 'hard') DEFAULT 'medium',
        exam_year YEAR,
        source VARCHAR(100),
        is_public BOOLEAN DEFAULT TRUE,
        institute_id INT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (institute_id) REFERENCES institutions(id)
    )",
    
    "CREATE TABLE IF NOT EXISTS test_packs (
        id INT PRIMARY KEY AUTO_INCREMENT,
        title VARCHAR(200) NOT NULL,
        description TEXT,
        cover_image VARCHAR(255),
        price DECIMAL(10,2),
        mrp DECIMAL(10,2),
        test_type ENUM('mock', 'real', 'instant') NOT NULL,
        timer_type ENUM('per_question', 'full_test') DEFAULT 'full_test',
        duration_minutes INT DEFAULT 60,
        institute_id INT,
        is_active BOOLEAN DEFAULT TRUE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (institute_id) REFERENCES institutions(id)
    )",
    
    "CREATE TABLE IF NOT EXISTS test_sessions (
        id INT PRIMARY KEY AUTO_INCREMENT,
        test_pack_id INT NOT NULL,
        student_id INT NOT NULL,
        session_token VARCHAR(255) UNIQUE,
        start_time TIMESTAMP NULL,
        end_time TIMESTAMP NULL,
        status ENUM('scheduled', 'in_progress', 'completed', 'cancelled') DEFAULT 'scheduled',
        proctoring_enabled BOOLEAN DEFAULT FALSE,
        recording_url VARCHAR(255),
        score DECIMAL(5,2),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (test_pack_id) REFERENCES test_packs(id),
        FOREIGN KEY (student_id) REFERENCES users(id)
    )",
    
    "CREATE TABLE IF NOT EXISTS analytics_data (
        id INT PRIMARY KEY AUTO_INCREMENT,
        question_id INT,
        topic VARCHAR(100),
        repetition_count INT DEFAULT 1,
        exam_years JSON,
        prediction_score DECIMAL(5,2),
        last_analyzed TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (question_id) REFERENCES question_banks(id)
    )"
];

foreach ($tables as $sql) {
    try {
        $db->exec($sql);
    } catch(PDOException $e) {
        error_log("Table creation error: " . $e->getMessage());
    }
}
?>