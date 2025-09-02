<?php
// Extracted helpers and AJAX processing for questions page
// This file contains the functions and early POST/AJAX handlers originally inside questions.php
// It is intended to be required from questions.php without changing runtime behavior.

// Handle AJAX actions FIRST before any output
$action = $_GET['action'] ?? 'list';
$ajax_actions = ['bulk_duplicate', 'bulk_delete', 'process_upload', 'process_text', 'import_questions'];

// Helper: detect if the incoming request is an AJAX request (XHR or client expecting JSON)
if (!function_exists('is_ajax_request')) {
    function is_ajax_request()
    {
        $xreq = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
        $acceptsJson = isset($_SERVER['HTTP_ACCEPT']) && stripos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false;
        $contentJson = isset($_SERVER['CONTENT_TYPE']) && stripos($_SERVER['CONTENT_TYPE'], 'application/json') !== false;
        return $xreq || $acceptsJson || $contentJson;
    }
}

// If a POST arrives for the questions page with an action not in the allowed AJAX actions,
// only return a JSON error when the request is actually AJAX. Regular form POSTs (edit/create)
// should continue to normal handling.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && !in_array($_GET['action'], $ajax_actions)) {
    if (is_ajax_request()) {
        // Try to clear buffers but only if buffering is active
        if (ob_get_length() !== false) {
            while (ob_get_level()) {
                ob_end_clean();
            }
        }
        // Set header only if it hasn't already been sent (avoid PHP warning)
        if (!headers_sent()) {
            header('Content-Type: application/json; charset=utf-8');
        }
        echo json_encode(['success' => false, 'error' => 'Unknown AJAX action: ' . ($_GET['action'] ?? '')]);
        exit;
    }
    // Not an AJAX request: allow normal page handling (do not exit) so edit/create forms work.
}


// Defensive helper to read option text in multiple stored shapes.
if (!function_exists('get_option_value')) {
    function get_option_value($options, $key, $lang = 'en')
    {
        // Ensure $options is an array
        if (!is_array($options)) {
            $options = [];
        }

        // i18n entries often use either A/B keys or option_A field names
        if (!empty($options['i18n'][$lang][strtoupper($key)])) {
            return $options['i18n'][$lang][strtoupper($key)];
        }
        if (!empty($options['i18n'][$lang]['option_' . strtoupper($key)])) {
            return $options['i18n'][$lang]['option_' . strtoupper($key)];
        }

        // Primary shape: options['A'] may be string or array {text,image}
        if (isset($options[$key])) {
            if (is_array($options[$key])) {
                return $options[$key]['text'] ?? $options[$key]['label'] ?? '';
            }
            return $options[$key];
        }

        // Fallback older names
        $lower = 'option_' . strtolower($key);
        if (isset($options[$lower])) {
            if (is_array($options[$lower])) {
                return $options[$lower]['text'] ?? '';
            }
            return $options[$lower];
        }

        return '';
    }
}

// Include parsing and import helpers
$parseImport = __DIR__ . '/parse_and_import.php';
if (file_exists($parseImport)) {
    require_once $parseImport;
}


// Keep helper functions here (get_option_value etc.).
// Parsing and import implementations are moved to parse_and_import.php

// End of extracted helpers
