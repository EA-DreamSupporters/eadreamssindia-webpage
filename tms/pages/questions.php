<?php

// Handle AJAX actions first (setting JSON headers for AJAX responses)
$action = $_GET['action'] ?? 'list';

// For AJAX actions, set JSON content type
$ajax_actions = ['bulk_duplicate', 'bulk_delete', 'process_upload', 'process_text', 'import_questions'];
if (in_array($action, $ajax_actions) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
}

// Ensure bulk upload helper functions are available before any AJAX handlers execute
require_once dirname(__DIR__) . '/bulk_upload_simple.php';

// Get current user (authentication already handled in index.php)
$user = getCurrentUser();

// Bulk Duplicate Questions
if ($action === 'bulk_duplicate' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // bulk_duplicate handler is implemented elsewhere (controller).
        // This stub returns a simple JSON response to avoid fatal parse errors
        echo json_encode(['success' => false, 'error' => 'bulk_duplicate not available in this context']);
        exit;
    } catch (Exception $e) {
        error_log('bulk_duplicate stub error: ' . $e->getMessage());
        echo json_encode(['success' => false, 'error' => 'Internal error']);
        exit;
    }
}
// Update question
// NOTE: Edit/create handlers were moved to a centralized controller file
// `tms/pages/questions/controllers/forms.php` which runs before HTML output.
// Keeping the inline handler here would risk duplicating updates and
// overwriting richer shapes (images, i18n, option objects). The controller
// handles merging of i18n/options and media uploads.

// Fetch subjects and topics
$allSubjects = $db->query("SELECT DISTINCT subject FROM question_banks WHERE subject IS NOT NULL AND subject != ''")->fetchAll(PDO::FETCH_COLUMN);
$allTopics = $db->query("SELECT DISTINCT topic FROM question_banks WHERE topic IS NOT NULL AND topic != ''")->fetchAll(PDO::FETCH_COLUMN);
$allSources = $db->query("SELECT DISTINCT source FROM question_banks WHERE source IS NOT NULL AND source != ''")->fetchAll(PDO::FETCH_COLUMN);

// Fetch questions
if ($user['role'] === 'super_admin') {
    $stmt = $db->query("SELECT * FROM question_banks ORDER BY created_at DESC LIMIT 50");
    $questions = $stmt->fetchAll();
} elseif ($user['role'] === 'vendor') {
    $stmt = $db->prepare("SELECT * FROM question_banks WHERE institute_id = ? OR is_public = 1 ORDER BY created_at DESC LIMIT 50");
    $stmt->execute([$user['institute_id']]);
    $questions = $stmt->fetchAll();
} else {
    $questions = [];
}

// If editing, try to fetch the question early so templates can access it
if ($action === 'edit' && isset($_GET['id'])) {
    $qid = intval($_GET['id']);
    if ($qid > 0) {
        $stmt = $db->prepare("SELECT * FROM question_banks WHERE id = ?");
        $stmt->execute([$qid]);
        $editQuestion = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        if ($editQuestion) {
            // Ensure options are available as decoded array for templates
            $editQuestion['options'] = is_string($editQuestion['options']) ? json_decode($editQuestion['options'], true) : ($editQuestion['options'] ?? []);
        }
    }
}

// Helper Functions for Bulk Upload

// Include simple bulk upload functions
require_once dirname(__DIR__) . '/bulk_upload_simple.php';

// Provide small helper template generators if the detailed helpers are not available
if (!function_exists('createSimpleCSVTemplate')) {
    function createSimpleCSVTemplate()
    {
        $lines = [];
        $lines[] = "question_text,option_a,option_b,option_c,option_d,correct_answer,subject,topic,exam_year,source";
        $lines[] = 'Sample question text,Option A,Option B,Option C,Option D,A,General Knowledge,Sample Topic,' . date('Y') . ',Sample Exam';
        return implode("\n", $lines) . "\n";
    }
}

if (!function_exists('createSimpleExcelTemplate')) {
    // For simplicity, provide the same CSV content for Excel download as well
    function createSimpleExcelTemplate()
    {
        return createSimpleCSVTemplate();
    }
}

// Use the simple functions
function createExcelTemplate()
{
    return createSimpleExcelTemplate();
}

function createCSVTemplate()
{
    return createSimpleCSVTemplate();
}

function parseUploadedFile($uploadedFile, $fileExtension)
{
    $questions = [];
    if ($fileExtension === 'csv') {
        $questions = parseSimpleCSV($uploadedFile['tmp_name']);
    } elseif ($fileExtension === 'xlsx') {
        // For now, Excel support is limited - suggest using CSV format
        throw new Exception('Excel (.xlsx) support is currently limited. Please convert your file to CSV format and try again.');
    }
    return $questions;
}

function parseTextContent($textContent)
{
    $questions = [];
    $lines = explode("\n", $textContent);
    $currentQuestion = null;
    $collectingQuestion = false;
    $questionNumber = 0;

    foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line))
            continue;

        // Check if this is a new question (starts with number)
        if (preg_match('/^(\d+[\.\)]\s*|Q\d*[\.\)]\s*)/i', $line)) {
            // Save previous question if exists
            if ($currentQuestion && !empty($currentQuestion['question_text'])) {
                $questions[] = $currentQuestion;
            }

            $questionNumber++;
            $currentQuestion = [
                'subject' => 'General',
                'topic' => 'General',
                'subtopic' => '',
                'question_text' => preg_replace('/^(\d+[\.\)]\s*|Q\d*[\.\)]\s*)/i', '', $line),
                'option_a' => '',
                'option_b' => '',
                'option_c' => '',
                'option_d' => '',
                'correct_answer' => '',
                'explanation' => '',
                'difficulty' => 'medium',
                'exam_year' => intval(date('Y')),
                'source' => 'Text Import',
                'is_public' => 1,
                'row_number' => $questionNumber
            ];
            $collectingQuestion = true;
        }
        // Check if this is an option line
        elseif (preg_match('/^([A-D][\.\)]\s*)/i', $line) && $currentQuestion) {
            $optionLetter = strtoupper(substr($line, 0, 1));
            $optionText = trim(preg_replace('/^[A-D][\.\)]\s*/i', '', $line));

            switch ($optionLetter) {
                case 'A':
                    $currentQuestion['option_a'] = $optionText;
                    break;
                case 'B':
                    $currentQuestion['option_b'] = $optionText;
                    break;
                case 'C':
                    $currentQuestion['option_c'] = $optionText;
                    break;
                case 'D':
                    $currentQuestion['option_d'] = $optionText;
                    break;
            }
            $collectingQuestion = false;
        }
        // Check if this is an answer line
        elseif (preg_match('/^(Answer|Ans|Correct)[\s\:]*([A-D])/i', $line) && $currentQuestion) {
            preg_match('/([A-D])/i', $line, $matches);
            if (isset($matches[1])) {
                $currentQuestion['correct_answer'] = strtoupper($matches[1]);
            }
            $collectingQuestion = false;
        }
        // Check if this is an explanation line
        elseif (preg_match('/^(Explanation|Exp)[\s\:]/i', $line) && $currentQuestion) {
            $currentQuestion['explanation'] = trim(preg_replace('/^(Explanation|Exp)[\s\:]*/i', '', $line));
            $collectingQuestion = false;
        }
        // If we're still collecting question text and this is not an option/answer line
        elseif (
            $collectingQuestion && $currentQuestion && !preg_match('/^[A-D][\.\)]/i', $line) &&
            !preg_match('/^(Answer|Explanation)/i', $line)
        ) {
            // Continue building the question text for multi-line questions
            $currentQuestion['question_text'] .= "\n" . $line;
        }
    }

    // Add the last question
    if ($currentQuestion && !empty($currentQuestion['question_text'])) {
        $questions[] = $currentQuestion;
    }

    return $questions;
}

// Removed problematic parsing functions - using simple ones from bulk_upload_simple.php

// End of parsing functions

?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="text-gradient mb-0">Question Bank</h1>
                <p class="text-muted">Manage your comprehensive question database</p>
            </div>
            <div>
                <button class="btn btn-success me-2"
                    onclick="window.location.href='index.php?page=questions&action=upload'">
                    <i class="fas fa-upload me-2"></i>Bulk Upload
                </button>
                <button class="btn btn-primary" onclick="window.location.href='index.php?page=questions&action=create'">
                    <i class="fas fa-plus me-2"></i>Add Question
                </button>
            </div>
        </div>
    </div>
</div>

<?php if (isset($success)): ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fas fa-check-circle me-2"></i><?= htmlspecialchars($success) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<?php if (isset($error)): ?>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="fas fa-exclamation-circle me-2"></i><?= htmlspecialchars($error) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<?php if ($action === 'list'): ?>

<style>
/* Multilingual form improvements */
.lang-block {
    margin-bottom: 1rem;
    transition: all 0.3s ease;
}

.lang-block.active {
    border-left: 3px solid #007bff !important;
    padding-left: 15px !important;
    background-color: #f8f9fa !important;
}

.lang-input {
    margin-bottom: 0.5rem !important;
    transition: border-color 0.3s ease;
}

.lang-input:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

/* Option language inputs grouping */
.col-6 .lang-input,
.col-12 .lang-input {
    margin-bottom: 0.4rem;
    font-size: 0.9rem;
}

/* Language switcher improvements */
.btn-group .lang-btn {
    transition: all 0.3s ease;
}

.btn-group .lang-btn.active {
    background-color: #007bff;
    border-color: #007bff;
    color: white;
}

/* Subtle borders for different languages */
input[name*="_en"] {
    border-left: 3px solid #28a745;
}

input[name*="_ta"] {
    border-left: 3px solid #fd7e14;
}

input[name*="_hi"] {
    border-left: 3px solid #6f42c1;
}

textarea[name*="_en"] {
    border-left: 3px solid #28a745;
}

textarea[name*="_ta"] {
    border-left: 3px solid #fd7e14;
}

textarea[name*="_hi"] {
    border-left: 3px solid #6f42c1;
}
</style>

<!-- Question Bank Dashboard -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="stats-card primary">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stats-number"><?= count($questions) ?></div>
                    <div class="stats-label">Total Questions</div>
                </div>
                <i class="fas fa-question-circle fa-2x opacity-75"></i>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stats-card success">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stats-number">
                        <?= count(array_filter($questions, fn($q) => $q['is_public'])) ?>
                    </div>
                    <div class="stats-label">Public Questions</div>
                </div>
                <i class="fas fa-globe fa-2x opacity-75"></i>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stats-card warning">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stats-number">
                        <?= count(array_unique(array_column($questions, 'subject'))) ?>
                    </div>
                    <div class="stats-label">Subjects</div>
                </div>
                <i class="fas fa-book fa-2x opacity-75"></i>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stats-card danger">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stats-number">
                        <?= count(array_unique(array_column($questions, 'topic'))) ?>
                    </div>
                    <div class="stats-label">Topics</div>
                </div>
                <i class="fas fa-tags fa-2x opacity-75"></i>
            </div>
        </div>
    </div>
</div>

<!-- Filters and Search -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="index.php">
            <input type="hidden" name="page" value="questions">
            <div class="row align-items-end">

                <div class="col-md-2">
                    <label class="form-label">Exam (Source)</label>
                    <select class="form-select" name="source">
                        <option value="">All Exams</option>
                        <?php foreach ($allSources as $source): ?>
                        <option value="<?= htmlspecialchars($source) ?>"
                            <?= ($_GET['source'] ?? '') == $source ? 'selected' : '' ?>>
                            <?= htmlspecialchars($source) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Subject</label>
                    <select class="form-select" name="subject">
                        <option value="">All Subjects</option>
                        <?php foreach ($allSubjects as $subject): ?>
                        <option value="<?= htmlspecialchars($subject) ?>"
                            <?= ($_GET['subject'] ?? '') == $subject ? 'selected' : '' ?>>
                            <?= htmlspecialchars($subject) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Topic</label>
                    <select class="form-select" name="topic">
                        <option value="">All Topics</option>
                        <?php foreach ($allTopics as $topic): ?>
                        <option value="<?= htmlspecialchars($topic) ?>"
                            <?= ($_GET['topic'] ?? '') == $topic ? 'selected' : '' ?>>
                            <?= htmlspecialchars($topic) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>


                <div class="col-md-2">
                    <label class="form-label">Difficulty</label>
                    <select class="form-select" name="difficulty">
                        <option value="">All Levels</option>
                        <option value="easy" <?= ($_GET['difficulty'] ?? '') == 'easy' ? 'selected' : '' ?>>Easy
                        </option>
                        <option value="medium" <?= ($_GET['difficulty'] ?? '') == 'medium' ? 'selected' : '' ?>>Medium
                        </option>
                        <option value="hard" <?= ($_GET['difficulty'] ?? '') == 'hard' ? 'selected' : '' ?>>Hard
                        </option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Search Text</label>
                    <input type="text" class="form-control" name="search_text"
                        value="<?= htmlspecialchars($_GET['search_text'] ?? '') ?>" placeholder="Search questions...">
                </div>

                <div class="col-md-1">
                    <button class="btn btn-primary w-100" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>



<!-- Questions List -->
<div class="row">
    <?php foreach ($questions as $question): ?>
    <?php include __DIR__ . '/questions/components/_question_card.php'; ?>
    <?php endforeach; ?>
</div>

<!-- Modal: Add Question to Test -->
<div class="modal fade" id="addToTestModal" tabindex="-1" aria-labelledby="addToTestModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="index.php?page=questions&action=add_question_to_test">
            <input type="hidden" name="question_id" id="modalQuestionId">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Question to Test Pack</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="fw-bold" id="modalQuestionText"></p>
                    <div class="mb-3">
                        <label class="form-label">Select Test Pack</label>
                        <select class="form-select" name="test_id" required>
                            <?php
                                $packs = $db->query("SELECT id, title FROM test_packs WHERE is_active = 1 AND is_visible_to_students = 1 ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
                                foreach ($packs as $pack) {
                                    echo "<option value=\"{$pack['id']}\">" . htmlspecialchars($pack['title']) . "</option>";
                                }
                                ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary">Add to Test</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Question Preview Modal -->
<div class="modal fade" id="questionPreviewModal" tabindex="-1" aria-labelledby="questionPreviewModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="questionPreviewModalLabel">Question Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="preview-question-content">
                    <!-- Content will be loaded by JS -->
                </div>
            </div>
            <div class="modal-footer">
                <span class="text-muted small me-auto" id="preview-available-langs"></span>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
// Helper to decode options JSON and render options safely (handles objects/arrays)
function renderOptions(optionsJson) {
    const escapeHtml = s => String(s == null ? '' : s)
        .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');

    let html = '';
    try {
        const options = typeof optionsJson === 'object' ? optionsJson : JSON.parse(optionsJson || '{}');
        const keys = ['A', 'B', 'C', 'D', 'E'];

        // If options look like numeric sequences (e.g. "2 3 4 1"), render as a matrix
        const firstRaw = options[keys[0]];
        const first = (typeof firstRaw === 'string') ? firstRaw : (Array.isArray(firstRaw) ? firstRaw.join(' ') : '');
        const looksLikeSequence = typeof first === 'string' && /^\s*\d+(\s+\d+)+\s*$/.test(first);
        if (looksLikeSequence) {
            const cols = (first.trim().split(/\s+/)).length;
            html += '<table class="table table-sm table-bordered mb-2"><thead><tr><th></th>';
            for (let i = 0; i < cols; i++) html += `<th>(${['a', 'b', 'c', 'd', 'e'][i] || i + 1})</th>`;
            html += '</tr></thead><tbody>';

            for (const k of keys) {
                if (options[k]) {
                    const val = options[k];
                    const parts = (typeof val === 'string' ? val : (Array.isArray(val) ? val.join(' ') : String(val)))
                        .trim().split(/\s+/);
                    html += `<tr><th style="width:60px">${k}</th>`;
                    for (let i = 0; i < cols; i++) html += `<td>${escapeHtml(parts[i] || '')}</td>`;
                    html += '</tr>';
                }
            }
            html += '</tbody></table>';
            return html;
        }

        // Generic list rendering
        html += '<ul class="list-group mb-2">';
        for (const key of keys) {
            const raw = options[key];
            const namedImage = options[`${key}_image`] || options[`${key} _image`] || options[`${key}_img`];
            if (raw || namedImage) {
                let text = '';
                let imagePath = '';

                if (raw && typeof raw === 'object') {
                    // common shapes: { text: '...', image: '...'} or { i18n: { en: '...' } }
                    if (raw.text) text = raw.text;
                    else if (raw.i18n && raw.i18n.en) text = raw.i18n.en;
                    else if (typeof raw === 'object') {
                        // fallback: pick first string property
                        for (const p in raw) {
                            if (typeof raw[p] === 'string') {
                                text = raw[p];
                                break;
                            }
                        }
                    }
                    if (raw.image) imagePath = raw.image;
                } else if (Array.isArray(raw)) {
                    text = raw.join(' ');
                } else {
                    text = raw != null ? String(raw) : '';
                }

                if (!imagePath) imagePath = namedImage || '';

                let content = '';
                if (imagePath) content +=
                    `<div><img src="${escapeHtml(imagePath)}" style="max-width:200px; max-height:120px;" alt="${key}"></div>`;
                content += escapeHtml(text);

                html += `<li class="list-group-item"><strong>${key}.</strong> ${content}</li>`;
            }
        }
        html += '</ul>';
    } catch (e) {}
    return html;
}

// Store current preview question data globally for language switching
window._currentPreviewQuestion = null;

// Render preview content in the selected language
function renderPreviewContent(lang) {
    if (!window._currentPreviewQuestion) return;

    const data = window._currentPreviewQuestion;
    const escapeHtml = s => String(s == null ? '' : s)
        .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');

    let opts = {};
    try {
        opts = typeof data.options === 'object' ? data.options : JSON.parse(data.options || '{}');
    } catch (e) {}

    // Extract i18n data
    const i18n = opts.i18n || {};
    const availableLangs = ['en'];
    if (i18n.ta && (i18n.ta.question_text || Object.keys(i18n.ta).length > 0)) availableLangs.push('ta');
    if (i18n.hi && (i18n.hi.question_text || Object.keys(i18n.hi).length > 0)) availableLangs.push('hi');

    // Check if current language is available
    const isLangAvailable = availableLangs.includes(lang);

    // Show available languages in footer
    document.getElementById('preview-available-langs').textContent =
        'Available languages: ' + availableLangs.map(l => l.toUpperCase()).join(', ');

    let html = '';
    html += `<div class="d-flex justify-content-between align-items-center mb-2">`;
    html +=
        `<div><span class='badge bg-primary'>${escapeHtml(data.subject)}</span> <span class='badge bg-secondary'>${escapeHtml(data.topic)}</span> <span class='badge bg-info'>${escapeHtml(data.subtopic || 'No subtopic')}</span></div>`;
    html += `<div class="btn-group btn-group-sm" role="group">`;
    html +=
        `<button type="button" class="btn btn-outline-primary btn-sm preview-lang-btn ${lang === 'en' ? 'active' : ''}" data-lang="en">EN</button>`;
    html +=
        `<button type="button" class="btn btn-outline-primary btn-sm preview-lang-btn ${lang === 'ta' ? 'active' : ''}" data-lang="ta">TA</button>`;
    html +=
        `<button type="button" class="btn btn-outline-primary btn-sm preview-lang-btn ${lang === 'hi' ? 'active' : ''}" data-lang="hi">HI</button>`;
    html += `</div></div>`;

    // If language not available, show message and edit button
    if (!isLangAvailable) {
        const langName = lang === 'ta' ? 'Tamil' : lang === 'hi' ? 'Hindi' : lang.toUpperCase();
        html += `<div class="alert alert-warning d-flex justify-content-between align-items-center mt-2" role="alert">
            <div>
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>${langName} version not available.</strong> Please add ${langName} translation for this question.
            </div>
            <a href="index.php?page=questions&action=edit&id=${data.id || ''}" class="btn btn-sm btn-primary">
                <i class="fas fa-edit me-1"></i>Add ${langName} Version
            </a>
        </div>`;
        document.getElementById('preview-question-content').innerHTML = html;
        return;
    }

    // Get question text for selected language
    let questionText = data.question_text;
    if (lang !== 'en' && i18n[lang] && i18n[lang].question_text) {
        questionText = i18n[lang].question_text;
    }

    html += `<h5 class='mt-2'>${escapeHtml(questionText)}</h5>`;

    // Render question-level media
    if (opts.image) {
        html += `<div class="mb-2"><img src="${escapeHtml(opts.image)}" style="max-width:100%; height:auto;"></div>`;
    }
    if (opts.audio) {
        html += `<div class="mb-2"><audio controls src="${escapeHtml(opts.audio)}"></audio></div>`;
    }

    // Render options for the selected language
    html += renderOptionsForLanguage(opts, lang);

    // Get explanation for selected language
    let explanation = data.explanation || '-';
    if (lang !== 'en' && i18n[lang] && i18n[lang].explanation) {
        explanation = i18n[lang].explanation;
    }

    html += `<div class='mb-2'><strong>Correct Answer:</strong> ${escapeHtml(data.correct_answer)}</div>`;
    html += `<div class='mb-2'><strong>Explanation:</strong> ${escapeHtml(explanation)}</div>`;
    html +=
        `<div class='mb-2'><strong>Exam Year:</strong> ${escapeHtml(data.exam_year)} <strong>Source:</strong> ${escapeHtml(data.source)}</div>`;
    html += `<div class='mb-2'><strong>Public:</strong> ${escapeHtml(data.is_public)}</div>`;

    document.getElementById('preview-question-content').innerHTML = html;

    // Trigger MathJax
    if (window.MathJax && MathJax.typesetPromise) {
        MathJax.typesetPromise().catch(err => console.error('MathJax error:', err));
    }
}

// Render options for a specific language
function renderOptionsForLanguage(opts, lang) {
    const escapeHtml = s => String(s == null ? '' : s)
        .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');

    const keys = ['A', 'B', 'C', 'D', 'E'];
    const i18n = opts.i18n || {};
    let html = '<ul class="list-group mb-2">';

    for (const key of keys) {
        let text = '';
        let imagePath = '';

        // Try to get option text for the selected language
        if (lang !== 'en' && i18n[lang]) {
            const langBlock = i18n[lang];
            if (langBlock[key]) text = langBlock[key];
            else if (langBlock.options && langBlock.options[key]) {
                const opt = langBlock.options[key];
                text = typeof opt === 'object' ? (opt.text || '') : opt;
            } else if (langBlock['option_' + key]) text = langBlock['option_' + key];
        }

        // Fallback to English
        if (!text) {
            if (opts[key]) {
                const raw = opts[key];
                if (typeof raw === 'object') text = raw.text || '';
                else text = raw;
            } else if (opts['option_' + key.toLowerCase()]) {
                const raw = opts['option_' + key.toLowerCase()];
                if (typeof raw === 'object') text = raw.text || '';
                else text = raw;
            }
        }

        // Get image path
        imagePath = opts[key + '_image'] || opts['option_' + key.toLowerCase() + '_image'] || '';
        if (!imagePath && typeof opts[key] === 'object') imagePath = opts[key].image || '';

        if (text || imagePath) {
            let content = '';
            if (imagePath) content +=
                `<div><img src="${escapeHtml(imagePath)}" style="max-width:200px; max-height:120px;" alt="${key}"></div>`;
            content += escapeHtml(text);
            html += `<li class="list-group-item"><strong>${key}.</strong> ${content}</li>`;
        }
    }

    html += '</ul>';
    return html;
}

// Preview button click handler (converted to vanilla JavaScript)
document.addEventListener('click', function(e) {
    if (e.target.closest('.preview-btn')) {
        const btn = e.target.closest('.preview-btn');

        window._currentPreviewQuestion = {
            id: btn.getAttribute('data-id'),
            subject: btn.getAttribute('data-subject'),
            topic: btn.getAttribute('data-topic'),
            subtopic: btn.getAttribute('data-subtopic'),
            question_text: btn.getAttribute('data-question_text'),
            options: btn.getAttribute('data-options'),
            correct_answer: btn.getAttribute('data-correct_answer'),
            explanation: btn.getAttribute('data-explanation'),
            difficulty: btn.getAttribute('data-difficulty'),
            exam_year: btn.getAttribute('data-exam_year'),
            source: btn.getAttribute('data-source'),
            is_public: btn.getAttribute('data-is_public')
        };

        // Render content in English (language buttons will be created by renderPreviewContent)
        renderPreviewContent('en');

        var modal = new bootstrap.Modal(document.getElementById('questionPreviewModal'));
        modal.show();
    }
});

// Language toggle handler for preview modal
document.addEventListener('click', function(e) {
    if (e.target.closest('.preview-lang-btn')) {
        const btn = e.target.closest('.preview-lang-btn');
        const lang = btn.getAttribute('data-lang');

        document.querySelectorAll('.preview-lang-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

        renderPreviewContent(lang);
    }
});
</script>



<!-- Bulk Actions -->
<div class="card mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <div class="form-check d-inline-block me-3">
                    <input class="form-check-input" type="checkbox" id="select-all">
                    <label class="form-check-label" for="select-all">Select All</label>
                </div>
                <span class="text-muted">Selected: <span id="selected-count">0</span>
                    questions</span>
            </div>
            <div>
                <button class="btn btn-outline-primary me-2" id="bulk-duplicate-btn" type="button">
                    <i class="fas fa-copy me-2"></i>Duplicate Selected
                </button>
                <button class="btn btn-outline-success me-2" id="bulk-add-to-test-btn" type="button"
                    data-bs-toggle="modal" data-bs-target="#bulkAddToTestModal">
                    <i class="fas fa-plus me-2"></i>Add to Test
                </button>
                <button class="btn btn-outline-danger" id="bulk-delete-btn" type="button">
                    <i class="fas fa-trash me-2"></i>Delete Selected
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Add to Test Modal -->
<div class="modal fade" id="bulkAddToTestModal" tabindex="-1" aria-labelledby="bulkAddToTestModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="index.php?page=questions&action=bulk_add_to_test" id="bulk-add-to-test-form">
            <input type="hidden" name="question_ids" id="bulkQuestionIds">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Selected Questions to Test Pack</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Select Test Pack</label>
                        <select class="form-select" name="test_id" required>
                            <?php
                                $packs = $db->query("SELECT id, title FROM test_packs WHERE is_active = 1 AND is_visible_to_students = 1 ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
                                foreach ($packs as $pack) {
                                    echo "<option value=\"{$pack['id']}\">" . htmlspecialchars($pack['title']) . "</option>";
                                }
                                ?>
                        </select>
                    </div>
                    <div>
                        <small class="text-muted">You are adding <span id="bulk-selected-count">0</span>
                            questions.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary">Add to Test</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
// Track selected checkboxes
function getSelectedQuestionIds() {
    const selected = Array.from(document.querySelectorAll(
            '.form-check-input[type="checkbox"]:checked:not(#select-all)'))
        .map(cb => cb.value);
    console.log('Selected question IDs:', selected);
    return selected;
}

function updateSelectedCount() {
    const count = getSelectedQuestionIds().length;
    document.getElementById('selected-count').textContent = count;

    // Update select-all checkbox state
    const totalCheckboxes = document.querySelectorAll(
        '.form-check-input[type="checkbox"]:not(#select-all)').length;
    const selectAllCheckbox = document.getElementById('select-all');
    if (count === 0) {
        selectAllCheckbox.indeterminate = false;
        selectAllCheckbox.checked = false;
    } else if (count === totalCheckboxes) {
        selectAllCheckbox.indeterminate = false;
        selectAllCheckbox.checked = true;
    } else {
        selectAllCheckbox.indeterminate = true;
        selectAllCheckbox.checked = false;
    }
}

// Select All functionality
document.getElementById('select-all').addEventListener('change', function() {
    const isChecked = this.checked;
    document.querySelectorAll('.form-check-input[type="checkbox"]:not(#select-all)')
        .forEach(cb => {
            cb.checked = isChecked;
        });
    updateSelectedCount();
});

// Update count on checkbox change
document.querySelectorAll('.form-check-input[type="checkbox"]:not(#select-all)').forEach(cb => {
    cb.addEventListener('change', updateSelectedCount);
});

// Bulk Duplicate
document.getElementById('bulk-duplicate-btn').addEventListener('click', function() {
    console.log('Duplicate button clicked');
    const ids = getSelectedQuestionIds();
    console.log('Selected IDs for duplication:', ids);

    if (ids.length === 0) {
        alert('Please select at least one question to duplicate.');
        return;
    }
    if (!confirm('Duplicate selected questions?')) return;

    console.log('Sending duplicate request with IDs:', ids);

    // Show loading state
    this.disabled = true;
    this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Duplicating...';

    // Send AJAX request to duplicate
    fetch('index.php?page=questions&action=bulk_duplicate', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                question_ids: ids
            })
        })
        .then(res => {
            console.log('Response status:', res.status);
            if (!res.ok) {
                throw new Error(`HTTP error! status: ${res.status}`);
            }
            return res.json();
        })
        .then(data => {
            console.log('Response data:', data);
            if (data.success) {
                alert('Questions duplicated successfully!');
                window.location.reload();
            } else {
                alert('Error: ' + (data.error || 'Could not duplicate.'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Network error: ' + error.message);
        })
        .finally(() => {
            // Reset button state
            this.disabled = false;
            this.innerHTML = '<i class="fas fa-copy me-2"></i>Duplicate Selected';
        });
});

// Bulk Add to Test
document.getElementById('bulk-add-to-test-btn').addEventListener('click', function(e) {
    const ids = getSelectedQuestionIds();
    if (ids.length === 0) {
        e.preventDefault();
        e.stopPropagation();
        alert('Please select at least one question to add to test.');
        return false;
    }
    document.getElementById('bulkQuestionIds').value = ids.join(',');
    document.getElementById('bulk-selected-count').textContent = ids.length;
});

// Bulk Delete
document.getElementById('bulk-delete-btn').addEventListener('click', function() {
    console.log('Bulk delete button clicked');
    const ids = getSelectedQuestionIds();
    console.log('Selected question IDs:', ids);
    if (ids.length === 0) {
        alert('Please select at least one question to delete.');
        return;
    }
    if (!confirm('Are you sure you want to delete selected questions?')) return;

    console.log('Sending delete request...');

    // Show loading state
    this.disabled = true;
    this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Deleting...';

    // Send AJAX request to dedicated endpoint
    fetch('index.php?page=questions&action=bulk_delete', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                question_ids: ids
            })
        })
        .then(res => {
            console.log('Delete response status:', res.status);
            if (!res.ok) {
                throw new Error(`HTTP error! status: ${res.status}`);
            }
            return res.json();
        })
        .then(data => {
            console.log('Delete parsed JSON:', data);
            if (data.success) {
                alert('Questions deleted successfully!');
                window.location.reload();
            } else {
                alert('Error: ' + (data.error || 'Could not delete.'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Network error: ' + error.message);
        })
        .finally(() => {
            // Reset button state
            this.disabled = false;
            this.innerHTML = '<i class="fas fa-trash me-2"></i>Delete Selected';
        });
});
</script>

<!-- Load external questions list JavaScript -->
<script src="pages/questions/assets/js/questions.list.js"></script>

<?php elseif ($action === 'create'): ?>
<!-- Create Question Form (enhanced for multiple offline-capable types) -->
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-plus me-2"></i>Add New Question</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="index.php?page=questions&action=create" class="needs-validation" novalidate
                    id="question-form" enctype="multipart/form-data">
                    <input type="hidden" name="save_and_add_another" id="save_and_add_another" value="0">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Subject</label>
                            <select class="form-select" name="subject">
                                <option value="">Select Subject</option>
                                <?php foreach ($allSubjects as $s): ?>
                                <option value="<?= htmlspecialchars($s) ?>"><?= htmlspecialchars($s) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <div class="form-text" style="margin-top: 15px;">Or add below</div>
                            <input type="text" class="form-control mt-2" name="new_subject"
                                placeholder="Add a New subject">
                        </div>

                        <div class="col-md-4 d-flex flex-column justify-content-between" style="height: 100%;">
                            <div>
                                <label class="form-label">Topic</label>
                                <input class="form-control" name="topic" placeholder="Topic">
                            </div>
                            <div class="mt-3">
                                <label class="form-label mb-1">Subtopic</label>
                                <input type="text" class="form-control" name="subtopic" placeholder="Subtopic">
                            </div>
                        </div>


                        <div class="col-md-3">
                            <label class="form-label">Question Type</label>
                            <select class="form-select" id="question-type" name="question_type">
                                <option value="text_mcq">Text-Based MCQ (Single Correct)</option>
                                <option value="image_mcq">Image-Based MCQ</option>
                                <option value="passage">Passage-Based / Comprehension</option>
                                <option value="fill_blank">Fill in the Blanks</option>
                                <option value="match">Match the Following</option>
                                <option value="assertion">Assertion & Reasoning</option>
                                <option value="true_false">True / False</option>
                                <option value="numerical">Numerical</option>
                                <option value="multi_correct">Multiple Correct Answers (Multi-select)</option>
                                <option value="audio">Audio-Based Question</option>
                            </select>
                        </div>

                        <div class="col-12" id="passage-block" style="display:none;">
                            <label class="form-label">Passage</label>
                            <textarea class="form-control" name="passage" rows="4"></textarea>
                        </div>

                        <!-- Language switcher -->
                        <div class="col-12 mb-2">
                            <div class="btn-group" role="group" aria-label="Language switcher">
                                <button type="button" class="btn btn-outline-primary lang-btn active"
                                    data-lang="en">English</button>
                                <button type="button" class="btn btn-outline-secondary lang-btn"
                                    data-lang="ta">Tamil</button>
                                <button type="button" class="btn btn-outline-secondary lang-btn"
                                    data-lang="hi">Hindi</button>
                            </div>
                            <div class="form-text">Type translations per language. Switching preserves your inputs.
                            </div>
                        </div>

                        <!-- Left: question text, question image, match pairs and options -->

                        <!-- Media block: placed above multilingual question text fields -->
                        <div class="col-12 mb-3" id="media-block" style="display:none;">
                            <label class="form-label">Upload Image / Audio</label>
                            <input type="file" class="form-control mb-2" name="image" accept="image/*">
                            <input type="file" class="form-control" name="audio" accept="audio/*">
                        </div>

                        <div class="col-md-6">
                            <!-- English inputs -->
                            <div class="lang-block" data-lang="en">
                                <label class="form-label">Question Text (English)</label>
                                <textarea class="form-control lang-input" id="question_text_en" name="question_text_en"
                                    rows="3"></textarea>
                            </div>
                            <!-- Tamil inputs -->
                            <div class="lang-block" data-lang="ta">
                                <label class="form-label">Question Text (Tamil)</label>
                                <textarea class="form-control lang-input" id="question_text_ta" name="question_text_ta"
                                    rows="3"></textarea>
                            </div>
                            <!-- Hindi inputs -->
                            <div class="lang-block" data-lang="hi">
                                <label class="form-label">Question Text (Hindi)</label>
                                <textarea class="form-control lang-input" id="question_text_hi" name="question_text_hi"
                                    rows="3"></textarea>
                            </div>

                            <!-- Match pairs block (will be shown when question type is 'match') -->
                            <div id="match-block" style="display:none;" class="mb-3">
                                <label class="form-label">Match Pairs</label>
                                <div id="match-rows">
                                    <div class="d-flex mb-2">
                                        <input class="form-control me-2" name="match_left[]" placeholder="Left">
                                        <input class="form-control" name="match_right[]" placeholder="Right">
                                        <button type="button"
                                            class="btn btn-sm btn-outline-danger ms-2 remove-match-row"
                                            title="Remove">&times;</button>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <button type="button" class="btn btn-sm btn-outline-secondary"
                                        id="add-match-row">Add Row</button>
                                </div>
                            </div>

                            <!-- Options laid out in two columns (A/B then C/D). Option E is hidden by default and toggleable. -->
                            <div id="options-block">
                                <label class="form-label">Options (A - D)</label>
                                <div class="row">
                                    <div class="col-6 mb-3">
                                        <label class="form-label small">Option A</label>
                                        <input class="form-control mb-1 lang-input" name="option_a_en"
                                            placeholder="Option A (English)">
                                        <input class="form-control mb-1 lang-input" name="option_a_ta"
                                            placeholder="Option A (Tamil)">
                                        <input class="form-control mb-1 lang-input" name="option_a_hi"
                                            placeholder="Option A (Hindi)">
                                        <input type="file" class="form-control form-control-sm option-image-input"
                                            name="option_a_image" accept="image/*" />
                                    </div>
                                    <div class="col-6 mb-3">
                                        <label class="form-label small">Option B</label>
                                        <input class="form-control mb-1 lang-input" name="option_b_en"
                                            placeholder="Option B (English)">
                                        <input class="form-control mb-1 lang-input" name="option_b_ta"
                                            placeholder="Option B (Tamil)">
                                        <input class="form-control mb-1 lang-input" name="option_b_hi"
                                            placeholder="Option B (Hindi)">
                                        <input type="file" class="form-control form-control-sm option-image-input"
                                            name="option_b_image" accept="image/*" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6 mb-3">
                                        <label class="form-label small">Option C</label>
                                        <input class="form-control mb-1 lang-input" name="option_c_en"
                                            placeholder="Option C (English)">
                                        <input class="form-control mb-1 lang-input" name="option_c_ta"
                                            placeholder="Option C (Tamil)">
                                        <input class="form-control mb-1 lang-input" name="option_c_hi"
                                            placeholder="Option C (Hindi)">
                                        <input type="file" class="form-control form-control-sm option-image-input"
                                            name="option_c_image" accept="image/*" />
                                    </div>
                                    <div class="col-6 mb-3">
                                        <label class="form-label small">Option D</label>
                                        <input class="form-control mb-1 lang-input" name="option_d_en"
                                            placeholder="Option D (English)">
                                        <input class="form-control mb-1 lang-input" name="option_d_ta"
                                            placeholder="Option D (Tamil)">
                                        <input class="form-control mb-1 lang-input" name="option_d_hi"
                                            placeholder="Option D (Hindi)">
                                        <input type="file" class="form-control form-control-sm option-image-input"
                                            name="option_d_image" accept="image/*" />
                                    </div>
                                </div>
                                <div class="row" id="option-e-row" style="display:none;">
                                    <div class="col-12 mb-3">
                                        <label class="form-label small">Option E</label>
                                        <input class="form-control mb-1 lang-input" name="option_e_en"
                                            placeholder="Option E (English)">
                                        <input class="form-control mb-1 lang-input" name="option_e_ta"
                                            placeholder="Option E (Tamil)">
                                        <input class="form-control mb-1 lang-input" name="option_e_hi"
                                            placeholder="Option E (Hindi)">
                                        <input type="file" class="form-control form-control-sm option-image-input"
                                            name="option_e_image" accept="image/*" />
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <button type="button" class="toggle-option-e btn btn-sm btn-outline-secondary"
                                        data-target="#option-e-row">+ Add Option E</button>
                                </div>
                            </div>
                        </div>

                        <script>
                        // Language switcher: show only inputs for selected language suffix (_en/_ta/_hi)
                        (function() {
                            function setActiveLanguage(lang) {
                                // toggle button classes
                                document.querySelectorAll('.lang-btn').forEach(btn => {
                                    if (btn.dataset && btn.dataset.lang === lang) btn.classList.add(
                                        'active');
                                    else btn.classList.remove('active');
                                });

                                // show/hide lang-block containers
                                document.querySelectorAll('.lang-block').forEach(lb => {
                                    try {
                                        if (lb.dataset && lb.dataset.lang === lang) lb.style.display = '';
                                        else lb.style.display = 'none';
                                    } catch (e) {}
                                });

                                // show/hide per-field lang-inputs based on suffix
                                document.querySelectorAll('.lang-input').forEach(inp => {
                                    try {
                                        const name = inp.name || inp.getAttribute('name') || '';
                                        if (/_?(en|ta|hi)$/.test(name)) {
                                            // language-specific input: show only if suffix matches active lang
                                            if (new RegExp('_' + lang + '$').test(name)) inp.style.display =
                                                '';
                                            else inp.style.display = 'none';
                                        } else {
                                            // non-language-specific inputs remain visible
                                            inp.style.display = '';
                                        }
                                    } catch (er) {}
                                });

                                // update preview if available
                                if (typeof updateLivePreview === 'function') updateLivePreview();
                                else if (typeof window.updateLivePreview === 'function') window.updateLivePreview();
                            }

                            // attach handlers
                            document.querySelectorAll('.lang-btn').forEach(btn => {
                                btn.addEventListener('click', function() {
                                    const lang = (this.dataset && this.dataset.lang) || 'en';
                                    setActiveLanguage(lang);
                                });
                            });

                            // init on DOM ready
                            if (document.readyState === 'loading') {
                                document.addEventListener('DOMContentLoaded', function() {
                                    setActiveLanguage('en');
                                });
                            } else setActiveLanguage('en');

                            // Option E toggle (delegated, per-form)
                            document.addEventListener('click', function(e) {
                                const toggle = e.target.closest('.toggle-option-e');
                                if (!toggle) return;
                                // Prefer row inside the same form as the toggle; fall back to global id
                                const form = toggle.closest('form') || document;
                                const row = form.querySelector('#option-e-row') || document.getElementById(
                                    'option-e-row');
                                if (!row) return;
                                if (row.style.display === 'none' || row.style.display === '') {
                                    row.style.display = 'block';
                                    toggle.textContent = '- Remove Option E';
                                } else {
                                    // clear non-file inputs in row when hiding
                                    row.querySelectorAll('input').forEach(i => {
                                        if (i.type !== 'file') i.value = '';
                                    });
                                    row.style.display = 'none';
                                    toggle.textContent = '+ Add Option E';
                                }
                                if (typeof updateLivePreview === 'function') updateLivePreview();
                            });

                            // delegated remove-match-row handler
                            document.addEventListener('click', function(e) {
                                if (e.target && e.target.classList && e.target.classList.contains(
                                        'remove-match-row')) {
                                    const row = e.target.closest('.d-flex');
                                    if (row) row.remove();
                                }
                            });

                            // ensure add-match-row works (delegated in case of re-render)
                            document.addEventListener('click', function(e) {
                                if (e.target && (e.target.id === 'add-match-row' || e.target.closest(
                                        '#add-match-row'))) {
                                    const container = document.getElementById('match-rows');
                                    if (!container) return;
                                    const div = document.createElement('div');
                                    div.className = 'd-flex mb-2';
                                    div.innerHTML =
                                        '<input class="form-control me-2" name="match_left[]" placeholder="Left"> <input class="form-control" name="match_right[]" placeholder="Right"> <button type="button" class="btn btn-sm btn-outline-danger ms-2 remove-match-row">&times;</button>';
                                    container.appendChild(div);
                                }
                            });
                        })();
                        </script>

                        <!-- Right: Live preview (narrower to sit closer to options) -->
                        <div class="col-md-5 d-flex align-items-start">
                            <div class="card mt-1 w-100" style="min-height: 350px; max-height: 600px;">
                                <div class="card-header"><strong>Live Preview</strong></div>
                                <div class="card-body" id="live-preview-edit" style="height: 300px; overflow-y: auto;">
                                    <div id="live-preview-content">Fill the form to see live preview here.</div>
                                </div>
                            </div>
                        </div>

                        <script>
                        (function() {
                            // Provide minimal preview helpers for edit form when create-form scripts are not loaded
                            if (typeof escapeHtml !== 'function') {
                                window.escapeHtml = function(str) {
                                    return String(str == null ? '' : str)
                                        .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
                                };
                            }

                            if (typeof renderOptions !== 'function') {
                                window.renderOptions = function(optionsJson) {
                                    try {
                                        var options = (typeof optionsJson === 'object') ? optionsJson : JSON
                                            .parse(optionsJson || '{}');
                                        var keys = ['A', 'B', 'C', 'D', 'E'];
                                        var html = '';
                                        html += '<ul class="list-group mb-2">';
                                        keys.forEach(function(key) {
                                            var raw = options[key];
                                            var namedImage = options[key + '_image'] || options[key +
                                                '_img'];
                                            if (raw || namedImage) {
                                                var text = '';
                                                var imagePath = '';
                                                if (raw && typeof raw === 'object') {
                                                    if (raw.text) text = raw.text;
                                                    else if (raw.i18n && raw.i18n.en) text = raw.i18n
                                                        .en;
                                                    else {
                                                        for (var p in raw)
                                                            if (typeof raw[p] === 'string') {
                                                                text = raw[p];
                                                                break;
                                                            }
                                                    }
                                                    if (raw.image) imagePath = raw.image;
                                                } else if (Array.isArray(raw)) {
                                                    text = raw.join(' ');
                                                } else {
                                                    text = raw != null ? String(raw) : '';
                                                }
                                                if (!imagePath) imagePath = namedImage || '';
                                                var content = '';
                                                if (imagePath) content += '<div><img src="' +
                                                    escapeHtml(imagePath) +
                                                    '" style="max-width:200px; max-height:120px;" alt="' +
                                                    key + '"></div>';
                                                content += escapeHtml(text);
                                                html += '<li class="list-group-item"><strong>' + key +
                                                    '.</strong> ' + content + '</li>';
                                            }
                                        });
                                        html += '</ul>';
                                        return html;
                                    } catch (e) {
                                        return '';
                                    }
                                };
                            }

                            function buildPreviewHtmlEdit() {
                                var formPrefix = '#question-form-edit ';
                                var subject = (document.querySelector(formPrefix + '[name="subject"]') || {})
                                    .value || '';
                                var topic = (document.querySelector(formPrefix + '[name="topic"]') || {}).value ||
                                    '';
                                var activeLang = (document.querySelector(formPrefix + '.lang-btn.active') &&
                                        document.querySelector(formPrefix + '.lang-btn.active').dataset.lang) ||
                                    'en';

                                var enText = (document.querySelector(formPrefix + '[name="question_text_en"]') ||
                                    {}).value || '';
                                var activeText = (document.querySelector(formPrefix + '[name="question_text_' +
                                    activeLang + '"]') || {}).value || '';

                                var optsEn = {};
                                var optsLang = {};
                                ['A', 'B', 'C', 'D', 'E'].forEach(function(k) {
                                    var nameEn = 'option_' + k.toLowerCase() + '_en';
                                    var nameLang = 'option_' + k.toLowerCase() + '_' + activeLang;
                                    var elEn = document.querySelector(formPrefix + '[name="' + nameEn +
                                        '"]');
                                    var elLang = document.querySelector(formPrefix + '[name="' + nameLang +
                                        '"]');
                                    if (elEn && elEn.value) optsEn[k] = elEn.value;
                                    if (elLang && elLang.value) optsLang[k] = elLang.value;
                                });

                                var tempOptionsEn = Object.assign({}, optsEn);
                                var tempOptionsLang = Object.assign({}, optsLang);

                                // question-level image/audio
                                var qimg = document.querySelector(formPrefix + '[name="image"]');
                                if (qimg && qimg.files && qimg.files[0]) {
                                    tempOptionsEn.image = URL.createObjectURL(qimg.files[0]);
                                    tempOptionsLang.image = tempOptionsEn.image;
                                }
                                var qaud = document.querySelector(formPrefix + '[name="audio"]');
                                if (qaud && qaud.files && qaud.files[0]) {
                                    tempOptionsEn.audio = URL.createObjectURL(qaud.files[0]);
                                    tempOptionsLang.audio = tempOptionsEn.audio;
                                }

                                ['a', 'b', 'c', 'd', 'e'].forEach(function(ch) {
                                    var inp = document.querySelector(formPrefix + '[name="option_' + ch +
                                        '_image"]');
                                    if (inp && inp.files && inp.files[0]) {
                                        var url = URL.createObjectURL(inp.files[0]);
                                        tempOptionsEn[ch.toUpperCase() + '_image'] = url;
                                        tempOptionsLang[ch.toUpperCase() + '_image'] = url;
                                    }
                                });

                                var html = '';
                                html += '<div><strong>' + escapeHtml(subject) +
                                    '</strong> <small class="text-muted">' + escapeHtml(topic) + '</small></div>';
                                // Show only active language text; fallback to English if active language is empty
                                var displayText = (activeLang === 'en') ? enText : (activeText || enText);
                                if (displayText) html += '<h6 class="mt-2">' + escapeHtml(displayText) + '</h6>';

                                if (tempOptionsEn.image) html += '<div class="mb-2"><img src="' + tempOptionsEn
                                    .image + '" style="max-width:100%; height:auto"></div>';
                                if (tempOptionsEn.audio) html += '<div class="mb-2"><audio controls src="' +
                                    tempOptionsEn.audio + '"></audio></div>';

                                // Show options for active language only, fallback to English if empty.
                                if (activeLang === 'en') {
                                    if (Object.keys(tempOptionsEn).length > 0) html += renderOptions(JSON.stringify(
                                        tempOptionsEn));
                                } else {
                                    if (Object.keys(tempOptionsLang).length > 0) html += renderOptions(JSON
                                        .stringify(tempOptionsLang));
                                    else if (Object.keys(tempOptionsEn).length > 0) html += renderOptions(JSON
                                        .stringify(tempOptionsEn));
                                }

                                var correct = (document.querySelector(formPrefix + '[name="correct_answer"]') || {})
                                    .value || '';
                                if (correct) html += '<div class="mt-2"><strong>Correct:</strong> ' + escapeHtml(
                                    correct) + '</div>';

                                return html;
                            }

                            window.updateLivePreview = function() {
                                var cont = document.querySelector('#question-form-edit #live-preview-content');
                                if (!cont) return;
                                cont.innerHTML = buildPreviewHtmlEdit();
                            };

                            // Wire events for edit form
                            ['input', 'change'].forEach(function(evt) {
                                var f = document.getElementById('question-form-edit');
                                if (!f) return;
                                f.addEventListener(evt, function(e) {
                                    updateLivePreview();
                                }, {
                                    capture: true
                                });
                            });

                            // Initial preview on load
                            if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded',
                                updateLivePreview);
                            else updateLivePreview();

                            // Language switcher init for edit form (if not already initialized on page)
                            (function initEditLangSwitcher() {
                                function setActiveLanguageEdit(lang) {
                                    document.querySelectorAll('#question-form-edit .lang-btn').forEach(function(
                                        b) {
                                        if (b && b.dataset && b.dataset.lang === lang) b.classList.add(
                                            'active');
                                        else if (b && b.classList) b.classList.remove('active');
                                    });
                                    // Show only the active language block (hide others)
                                    document.querySelectorAll('#question-form-edit .lang-block').forEach(
                                        function(lb) {
                                            try {
                                                if (lb.dataset && lb.dataset.lang === lang) {
                                                    lb.style.display = '';
                                                    lb.style.borderLeft = '3px solid #007bff';
                                                    lb.style.paddingLeft = '15px';
                                                    lb.style.backgroundColor = '#f8f9fa';
                                                } else {
                                                    lb.style.display = 'none';
                                                    lb.style.borderLeft = 'none';
                                                    lb.style.paddingLeft = '0px';
                                                    lb.style.backgroundColor = 'transparent';
                                                }
                                            } catch (er) {}
                                        });
                                    // per-field lang-input visibility: show only inputs for the active language
                                    // and keep non-language-specific inputs visible
                                    document.querySelectorAll('#question-form-edit .lang-input').forEach(
                                        function(inp) {
                                            try {
                                                var name = inp.name || inp.getAttribute('name') || '';
                                                if (/_?(en|ta|hi)$/.test(name)) {
                                                    if (new RegExp('_' + lang + '$').test(name)) inp.style
                                                        .display = '';
                                                    else inp.style.display = 'none';
                                                } else {
                                                    inp.style.display = '';
                                                }
                                            } catch (er) {}
                                        });
                                    updateLivePreview();
                                }

                                document.querySelectorAll('#question-form-edit .lang-btn').forEach(function(
                                    btn) {
                                    btn.addEventListener('click', function() {
                                        var lang = (this.dataset && this.dataset.lang) || 'en';
                                        setActiveLanguageEdit(lang);
                                    });
                                });

                                // initialize to pre-marked active or default
                                var initial = (document.querySelector('#question-form-edit .lang-btn.active') &&
                                    document.querySelector('#question-form-edit .lang-btn.active').dataset
                                    .lang) || 'en';
                                setActiveLanguageEdit(initial);
                            })();
                        })();
                        </script>

                        <script>
                        // Ensure edit form uses the same language switcher and live preview logic as create
                        (function() {
                            var editForm = document.getElementById('question-form-edit');
                            if (!editForm) return; // nothing to do

                            // Simple language switcher for the form (works independently of create/initLangSwitcher)
                            function setActiveLanguageForForm(form, lang) {
                                // buttons
                                form.querySelectorAll('.lang-btn').forEach(function(b) {
                                    try {
                                        if (b && b.dataset && b.dataset.lang === lang) b.classList.add(
                                            'active');
                                        else if (b && b.classList) b.classList.remove('active');
                                    } catch (e) {}
                                });
                                // Show only the active language block (hide others)
                                form.querySelectorAll('.lang-block').forEach(function(lb) {
                                    try {
                                        if (lb.dataset && lb.dataset.lang === lang) {
                                            lb.style.display = '';
                                            lb.style.borderLeft = '3px solid #007bff';
                                            lb.style.paddingLeft = '15px';
                                            lb.style.backgroundColor = '#f8f9fa';
                                        } else {
                                            lb.style.display = 'none';
                                            lb.style.borderLeft = 'none';
                                            lb.style.paddingLeft = '0px';
                                            lb.style.backgroundColor = 'transparent';
                                        }
                                    } catch (e) {}
                                });
                                // per-field lang-input visibility: show only inputs for the active language
                                // and keep non-language-specific inputs visible
                                form.querySelectorAll('.lang-input').forEach(function(inp) {
                                    try {
                                        var name = inp.name || inp.getAttribute('name') || '';
                                        if (/_?(en|ta|hi)$/.test(name)) {
                                            if (new RegExp('_' + lang + '$').test(name)) inp.style.display =
                                                '';
                                            else inp.style.display = 'none';
                                        } else {
                                            inp.style.display = '';
                                        }
                                    } catch (e) {}
                                });
                                if (typeof updateLivePreview === 'function') updateLivePreview();
                            }

                            // Attach to buttons inside the form
                            editForm.querySelectorAll('.lang-btn').forEach(function(btn) {
                                btn.addEventListener('click', function() {
                                    var lang = (this.dataset && this.dataset.lang) || 'en';
                                    setActiveLanguageForForm(editForm, lang);
                                });
                            });

                            // Wire preview update events (use existing updateLivePreview if present)
                            ['input', 'change'].forEach(function(evt) {
                                editForm.addEventListener(evt, function() {
                                    if (typeof updateLivePreview === 'function') {
                                        updateLivePreview();
                                        return;
                                    }
                                    // Fallback: if buildPreviewHtmlEdit exists, call it
                                    if (typeof buildPreviewHtmlEdit === 'function') {
                                        var cont = document.querySelector(
                                            '#question-form-edit #live-preview-content');
                                        if (cont) cont.innerHTML = buildPreviewHtmlEdit();
                                    }
                                }, {
                                    capture: true
                                });
                            });

                            // Initialize to the active button or default 'en'
                            var initial = (editForm.querySelector('.lang-btn.active') && editForm.querySelector(
                                '.lang-btn.active').dataset.lang) || 'en';
                            setActiveLanguageForForm(editForm, initial);

                            // Trigger initial preview
                            if (typeof updateLivePreview === 'function') updateLivePreview();
                            else if (typeof buildPreviewHtmlEdit === 'function') {
                                var cont = document.querySelector('#question-form-edit #live-preview-content');
                                if (cont) cont.innerHTML = buildPreviewHtmlEdit();
                            }
                        })();
                        </script>

                        <script>
                        // Toggle per-form option image inputs when question type changes (works for create & edit)
                        function toggleOptionImagesForForms() {
                            document.querySelectorAll('#question-type').forEach(function(sel) {
                                var show = (sel.value === 'image_mcq');
                                var form = sel.closest('form');
                                if (!form) return;
                                form.querySelectorAll('.option-image-input').forEach(function(input) {
                                    input.style.display = show ? '' : 'none';
                                });
                                // also ensure media block visibility inside the form (if present)
                                var mb = form.querySelector('#media-block') || form.querySelector(
                                    '#media-block-edit');
                                if (mb) mb.style.display = show ? '' : 'none';
                            });
                        }
                        document.querySelectorAll('#question-type').forEach(function(s) {
                            s.addEventListener('change', toggleOptionImagesForForms);
                        });
                        toggleOptionImagesForForms();
                        </script>




                        <div class="col-md-6" id="match-block" style="display:none;">
                            <label class="form-label">Match Pairs</label>
                            <div id="match-rows">
                                <div class="d-flex mb-2">
                                    <input class="form-control me-2" name="match_left[]" placeholder="Left">
                                    <input class="form-control" name="match_right[]" placeholder="Right">
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-secondary" id="add-match-row">Add
                                Row</button>
                        </div>


                        <div class="col-md-4" id="correct-answer-block">
                            <label class="form-label">Correct Answer (single)</label>
                            <input type="text" class="form-control" name="correct_answer"
                                placeholder="E.g. A or 42 or True">
                        </div>

                        <div class="col-md-4" id="multi-correct-block" style="display:none;">
                            <label class="form-label">Multi-select Correct Answers</label>
                            <select multiple class="form-select" name="correct_answers[]">
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="C">C</option>
                                <option value="D">D</option>
                            </select>
                        </div>

                        <div class="col-md-4" id="fill-blank-block" style="display:none;">
                            <label class="form-label">Fill Blanks Answers</label>
                            <input class="form-control" name="blanks"
                                placeholder="Separate answers using |, e.g. ans1|ans2">
                        </div>

                        <!-- removed duplicate media-block: using top-placed media upload to appear above question texts -->

                        <div class="col-md-3">
                            <label class="form-label">Difficulty</label>
                            <select class="form-select" name="difficulty">
                                <option value="easy">Easy</option>
                                <option value="medium" selected>Medium</option>
                                <option value="hard">Hard</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Exam Year</label>
                            <input class="form-control" name="exam_year" value="<?= date('Y') ?>">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Source / Exam</label>
                            <input class="form-control" name="source" placeholder="e.g., SSC, Bank">
                        </div>

                        <div class="col-12">
                            <label class="form-label">Explanation</label>
                            <textarea class="form-control" name="explanation" rows="3"></textarea>
                        </div>

                        <div class="col-12 d-flex justify-content-between align-items-center">
                            <div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="is_public" name="is_public"
                                        checked>
                                    <label class="form-check-label" for="is_public">Public</label>
                                </div>
                            </div>
                            <div>
                                <button type="button" class="btn btn-secondary me-2"
                                    onclick="window.location.href='index.php?page=questions'">Cancel</button>
                                <button type="button" class="btn btn-outline-primary me-2"
                                    id="save-and-add-another-btn"><i class="fas fa-save me-2"></i>Save & Add
                                    Another</button>
                                <button type="submit" class="btn btn-primary"><i class="fas fa-check me-2"></i>Save
                                    Question</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function updateFormByType() {
    const type = document.getElementById('question-type').value;
    document.getElementById('passage-block').style.display = (type === 'passage') ? 'block' : 'none';
    document.getElementById('options-block').style.display = (['text_mcq', 'image_mcq', 'multi_correct'].includes(
        type)) ? 'block' : 'none';
    document.getElementById('multi-correct-block').style.display = (type === 'multi_correct') ? 'block' : 'none';
    document.getElementById('fill-blank-block').style.display = (type === 'fill_blank') ? 'block' : 'none';
    document.getElementById('match-block').style.display = (type === 'match') ? 'block' : 'none';
    document.getElementById('media-block').style.display = (type === 'image_mcq' || type === 'audio') ? 'block' :
        'none';
    document.getElementById('correct-answer-block').style.display = (['numerical', 'true_false', 'text_mcq',
        'image_mcq', 'multi_correct'
    ].includes(type)) ? 'block' : 'none';
}
document.getElementById('question-type').addEventListener('change', updateFormByType);
updateFormByType();
document.getElementById('add-match-row').addEventListener('click', function() {
    const container = document.getElementById('match-rows');
    const div = document.createElement('div');
    div.className = 'd-flex mb-2';
    div.innerHTML =
        '<input class="form-control me-2" name="match_left[]" placeholder="Left"> <input class="form-control" name="match_right[]" placeholder="Right">';
    container.appendChild(div);
});
document.getElementById('save-and-add-another-btn').addEventListener('click', function() {
    document.getElementById('save_and_add_another').value = '1';
    this.disabled = true;
    this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Saving...';
    document.getElementById('question-form').submit();
});

// Live preview logic
// Ensure renderOptions is available on this page (fallback when list-page scripts are not loaded)
if (typeof renderOptions !== 'function') {
    function renderOptions(optionsJson) {
        let html = '';
        try {
            const options = JSON.parse(optionsJson || '{}');
            const keys = ['A', 'B', 'C', 'D', 'E'];
            html += '<ul class="list-group mb-2">';
            for (const key of keys) {
                if (options[key] || options[key + '_image'] || options[key + '_img']) {
                    let content = options[key] || '';
                    if (options[key + '_image']) {
                        content =
                            `<div><img src="${options[key + '_image']}" style="max-width:200px; max-height:120px;" alt="${key}"></div>` +
                            content;
                    } else if (options[key + '_img']) {
                        content =
                            `<div><img src="${options[key + '_img']}" style="max-width:200px; max-height:120px;" alt="${key}"></div>` +
                            content;
                    }
                    html += `<li class="list-group-item"><strong>${key}.</strong> ${content}</li>`;
                }
            }
            html += '</ul>';
        } catch (e) {}
        return html;
    }
}

function buildPreviewHtml() {
    const subject = document.querySelector('[name="subject"]').value || '';
    const topic = document.querySelector('[name="topic"]').value || '';
    // read active language
    const activeLang = document.querySelector('.lang-btn.active') ? document.querySelector('.lang-btn.active').dataset
        .lang : 'en';

    // read english and active language texts so we can show both
    const enTextEl = document.querySelector('[name="question_text_en"]');
    const enText = enTextEl ? enTextEl.value : '';
    const activeTextEl = document.querySelector('[name="question_text_' + activeLang + '"]');
    const activeText = activeTextEl ? activeTextEl.value : '';

    // collect options for english and active language
    const optsEn = {};
    const optsLang = {};
    ['A', 'B', 'C', 'D', 'E'].forEach(k => {
        const nameEn = 'option_' + k.toLowerCase() + '_en';
        const nameLang = 'option_' + k.toLowerCase() + '_' + activeLang;
        const elEn = document.querySelector('[name="' + nameEn + '"]');
        const elLang = document.querySelector('[name="' + nameLang + '"]');
        if (elEn && elEn.value) optsEn[k] = elEn.value;
        if (elLang && elLang.value) optsLang[k] = elLang.value;
    });

    // Build temp options objects and attach uploaded media object URLs if any
    const tempOptionsEn = Object.assign({}, optsEn);
    const tempOptionsLang = Object.assign({}, optsLang);

    // question-level image/audio (shared)
    const qimg = document.querySelector('[name="image"]');
    if (qimg && qimg.files && qimg.files[0]) {
        tempOptionsEn.image = URL.createObjectURL(qimg.files[0]);
        tempOptionsLang.image = tempOptionsEn.image;
    }
    const qaud = document.querySelector('[name="audio"]');
    if (qaud && qaud.files && qaud.files[0]) {
        tempOptionsEn.audio = URL.createObjectURL(qaud.files[0]);
        tempOptionsLang.audio = tempOptionsEn.audio;
    }

    // per-option images (shared across langs)
    ['a', 'b', 'c', 'd', 'e'].forEach(ch => {
        const inp = document.querySelector('[name="option_' + ch + '_image"]');
        if (inp && inp.files && inp.files[0]) {
            const url = URL.createObjectURL(inp.files[0]);
            tempOptionsEn[ch.toUpperCase() + '_image'] = url;
            tempOptionsLang[ch.toUpperCase() + '_image'] = url;
        }
    });

    let html = '';
    html += `<div><strong>${subject}</strong> <small class="text-muted">${topic}</small></div>`;

    // Show only the active language's question text.
    // If active language text is empty, fall back to English so preview isn't blank.
    let displayText = '';
    if (activeLang === 'en') displayText = enText;
    else displayText = activeText || enText;
    if (displayText) html += `<h6 class="mt-2">${escapeHtml(displayText)}</h6>`;

    // show question image/audio
    if (tempOptionsEn.image) html +=
        `<div class="mb-2"><img src="${tempOptionsEn.image}" style="max-width:100%; height:auto"></div>`;
    if (tempOptionsEn.audio) html += `<div class="mb-2"><audio controls src="${tempOptionsEn.audio}"></audio></div>`;

    // Show options for the active language only. If active language has no options,
    // fall back to English options so preview isn't empty. Do not render language headings.
    if (activeLang === 'en') {
        if (Object.keys(tempOptionsEn).length > 0) {
            html += renderOptions(JSON.stringify(tempOptionsEn));
        }
    } else {
        if (Object.keys(tempOptionsLang).length > 0) {
            html += renderOptions(JSON.stringify(tempOptionsLang));
        } else if (Object.keys(tempOptionsEn).length > 0) {
            html += renderOptions(JSON.stringify(tempOptionsEn));
        }
    }

    const correct = document.querySelector('[name="correct_answer"]').value || '';
    if (correct) html += `<div class="mt-2"><strong>Correct:</strong> ${escapeHtml(correct)}</div>`;

    return html;
}

function escapeHtml(str) {
    return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
}

function updateLivePreview() {
    const cont = document.getElementById('live-preview-content');
    cont.innerHTML = buildPreviewHtml();
    // Ensure MathJax renders any inline math added to the live preview
    if (window.MathJax && MathJax.typesetPromise) {
        MathJax.typesetPromise().catch(function(err) {
            console.error('MathJax typeset error (live preview):', err);
        });
    } else {
        // Retry once shortly after in case MathJax is still loading
        setTimeout(function() {
            if (window.MathJax && MathJax.typesetPromise) {
                MathJax.typesetPromise().catch(function(err) {
                    console.error('MathJax typeset error (live preview, retry):', err);
                });
            }
        }, 500);
    }
}

// Wire events
['input', 'change'].forEach(evt => {
    document.getElementById('question-form').addEventListener(evt, function(e) {
        // update preview on relevant fields only
        const relevant = ['question_text', 'option_a', 'option_b', 'option_c', 'option_d', 'option_e',
            'image', 'audio', 'option_a_image', 'option_b_image', 'option_c_image',
            'option_d_image', 'option_e_image', 'correct_answer', 'subject', 'topic'
        ];
        if (e.target && (relevant.includes(e.target.name) || e.target.closest('#question-form'))) {
            updateLivePreview();
        }
    }, {
        capture: true
    });
});

// Initial preview
updateLivePreview();

// Language switcher behaviour - show only active language inputs (English default)
(function() {
    function setActiveLanguage(lang) {
        // set active class on buttons
        document.querySelectorAll('.lang-btn').forEach(b => {
            if (b && b.dataset && b.dataset.lang === lang) b.classList.add('active');
            else if (b && b.classList) b.classList.remove('active');
        });

        // show/hide lang-blocks: show only active language block
        document.querySelectorAll('.lang-block').forEach(lb => {
            try {
                if (lb.dataset && lb.dataset.lang === lang) {
                    lb.style.display = '';
                    lb.classList.add('active');
                } else {
                    lb.style.display = 'none';
                    lb.classList.remove('active');
                }
            } catch (er) {}
        });

        // per-field lang-input visibility:
        // - show inputs that match the active language suffix (e.g. _ta or _hi) when a non-English language is active
        // - when active language is 'en' show *_en inputs
        // - always show non-language-specific inputs (names without language suffix)
        document.querySelectorAll('.lang-input').forEach(inp => {
            try {
                const name = inp.name || inp.getAttribute('name') || '';
                // language-specific inputs end with _en/_ta/_hi
                if (/_(en|ta|hi)$/.test(name)) {
                    // show only the inputs that match the active language
                    if (new RegExp('_' + lang + '$').test(name)) {
                        inp.style.display = '';
                    } else {
                        inp.style.display = 'none';
                    }
                } else {
                    // non-language-specific inputs (e.g. option_a) should remain visible
                    inp.style.display = '';
                }
            } catch (er) {}
        });

        // refresh preview to show active language
        if (typeof updateLivePreview === 'function') updateLivePreview();
    }

    function initLangSwitcher() {
        // attach click handlers
        document.querySelectorAll('.lang-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const lang = (this.dataset && this.dataset.lang) || 'en';
                setActiveLanguage(lang);
            });
        });

        // initialize to whatever button is marked active or default to en
        const initial = (document.querySelector('.lang-btn.active') && document.querySelector('.lang-btn.active')
            .dataset.lang) || 'en';
        setActiveLanguage(initial);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initLangSwitcher);
    } else {
        initLangSwitcher();
    }
})();
</script>

<script>
// Option E toggle and preview wiring for create & edit forms
function wireOptionEToggle(rootSelector) {
    const root = document.querySelector(rootSelector);
    if (!root) return;
    // use class-based selector so multiple instances on the page don't conflict
    const toggle = root.querySelector('.toggle-option-e');
    const row = root.querySelector('#option-e-row');
    if (!toggle || !row) return;
    toggle.addEventListener('click', function() {
        if (row.style.display === 'none' || row.style.display === '') {
            row.style.display = 'block';
            toggle.textContent = '- Remove Option E';
        } else {
            // clear inputs (clear files and text inputs)
            row.querySelectorAll('input').forEach(i => {
                if (i.type === 'file') i.value = '';
                else i.value = '';
            });
            row.style.display = 'none';
            toggle.textContent = '+ Add Option E';
        }
        if (typeof updateLivePreview === 'function') updateLivePreview();
    });
}

wireOptionEToggle('#question-form');
wireOptionEToggle('#question-form-edit');
wireOptionEToggle('#question-form-edit');

// Ensure match-row add works for both forms
document.querySelectorAll('#add-match-row').forEach(btn => {
    btn.addEventListener('click', function() {
        const container = this.closest('form').querySelector('#match-rows');
        const div = document.createElement('div');
        div.className = 'd-flex mb-2';
        div.innerHTML =
            '<input class="form-control me-2" name="match_left[]" placeholder="Left"> <input class="form-control" name="match_right[]" placeholder="Right"> <button type="button" class="btn btn-sm btn-outline-danger ms-2 remove-match-row">&times;</button>';
        container.appendChild(div);
    });
});

// Remove-match-row handler (delegated)
document.addEventListener('click', function(e) {
    if (e.target && e.target.classList && e.target.classList.contains('remove-match-row')) {
        const row = e.target.closest('.d-flex');
        if (row) row.remove();
    }
});

// Ensure preview updates for edit form as well
['input', 'change'].forEach(evt => {
    ['#question-form', '#question-form-edit'].forEach(sel => {
        const f = document.querySelector(sel);
        if (!f) return;
        f.addEventListener(evt, function(e) {
            if (typeof updateLivePreview === 'function') updateLivePreview();
        }, {
            capture: true
        });
    });
});

// Media visibility: show question image only for image_mcq type
function refreshQuestionImageVisibility() {
    const type = (document.querySelector('#question-type') || {}).value || '';
    const show = (type === 'image_mcq');
    const block = document.getElementById('media-block');
    const blockEdit = document.getElementById('media-block-edit');
    if (block) block.style.display = show ? '' : 'none';
    if (blockEdit) blockEdit.style.display = show ? '' : 'none';
}
document.querySelectorAll('#question-type').forEach(s => s.addEventListener('change', refreshQuestionImageVisibility));
// initialize
refreshQuestionImageVisibility();
// Save & Add Another for edit form
var saveAndAddBtnEdit = document.getElementById('save-and-add-another-btn-edit');
if (saveAndAddBtnEdit) {
    saveAndAddBtnEdit.addEventListener('click', function() {
        var hidden = document.getElementById('save_and_add_another_edit');
        if (hidden) hidden.value = '1';
        this.disabled = true;
        this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Saving...';
        document.getElementById('question-form-edit').submit();
    });
}
})();
</script>

<?php elseif ($action === 'edit'): ?>
<?php if (empty($editQuestion) || !is_array($editQuestion)): ?>
<div class="alert alert-danger">
    <strong>No question selected for edit.</strong>
    <div>Please open the edit page with a valid question id.</div>
    <div class="mt-2">
        <a href="index.php?page=questions" class="btn btn-sm btn-primary">Back to Questions</a>
    </div>
    <script>
    // Redirect back to questions list after 3 seconds to help users
    setTimeout(function() {
        window.location.href = 'index.php?page=questions';
    }, 3000);
    </script>
</div>
<?php else: ?>
<!-- Edit Question Form (matches Create layout) -->
<?php
        // Ensure $options is decoded
        $options = is_string($editQuestion['options']) ? json_decode($editQuestion['options'], true) : ($editQuestion['options'] ?? []);
        $i18n = $options['i18n'] ?? [];

        // Enhanced $getI18n that checks both dedicated columns and options->i18n
        $getI18n = function ($lang, $field, $fallback = '') use ($editQuestion, $i18n) {
            // For question_text, check dedicated columns first
            if ($field === 'question_text') {
                $columnName = 'question_text_' . $lang;
                if (isset($editQuestion[$columnName]) && !empty(trim($editQuestion[$columnName]))) {
                    return $editQuestion[$columnName];
                }
            }

            // Then check options->i18n
            if (isset($i18n[$lang][$field]) && $i18n[$lang][$field] !== '') {
                return $i18n[$lang][$field];
            }

            return $fallback;
        };
        $optFor = function ($key, $lang) use ($options, $editQuestion) {
            $kUp = strtoupper($key);
            // 1) structured i18n blocks
            if (!empty($options['i18n'][$lang]) && is_array($options['i18n'][$lang])) {
                $langBlock = $options['i18n'][$lang];
                // direct mapping: A => 'text'
                if (isset($langBlock[$kUp]) && $langBlock[$kUp] !== '')
                    return $langBlock[$kUp];
                // nested options: options => [A => 'text' or ['text'=>..]]
                if (isset($langBlock['options'][$kUp]) && $langBlock['options'][$kUp] !== '') {
                    $v = $langBlock['options'][$kUp];
                    if (is_array($v))
                        return $v['text'] ?? (is_string($v) ? $v : '');
                    return $v;
                }
                // option_A style
                if (isset($langBlock['option_' . $kUp]) && $langBlock['option_' . $kUp] !== '')
                    return $langBlock['option_' . $kUp];
                // options as objects: options[A]['text']
                if (isset($langBlock['options'][$kUp]['text']))
                    return $langBlock['options'][$kUp]['text'];
            }

            // 2) top-level option entries (A, option_a, option_a_en etc.)
            if (isset($options[$kUp])) {
                $val = $options[$kUp];
                if (is_array($val)) {
                    if (!empty($val['text']))
                        return $val['text'];
                    if (!empty($val['i18n'][$lang]) && is_string($val['i18n'][$lang]))
                        return $val['i18n'][$lang];
                    if (!empty($val['i18n'][$lang]['text']))
                        return $val['i18n'][$lang]['text'];
                } else if ($val !== '')
                    return $val;
            }

            $lower = 'option_' . strtolower($key);
            if (isset($options[$lower])) {
                $val = $options[$lower];
                if (is_array($val))
                    return $val['text'] ?? '';
                if ($val !== '')
                    return $val;
            }

            // language-specific top level keys (option_a_en)
            $langKey = $lower . '_' . $lang;
            if (isset($options[$langKey]) && $options[$langKey] !== '')
                return $options[$langKey];

            return '';
        };
        $optImage = function ($key) use ($options) {
            // check direct keys: A_image, option_a_image, option_a, and nested shapes
            $kUp = strtoupper($key);
            if (!empty($options[$kUp . '_image']))
                return $options[$kUp . '_image'];
            if (!empty($options[$kUp . '_img']))
                return $options[$kUp . '_img'];
            $lower = 'option_' . strtolower($key) . '_image';
            if (!empty($options[$lower]))
                return $options[$lower];
            // if option is object with image
            if (!empty($options[$kUp]) && is_array($options[$kUp]) && !empty($options[$kUp]['image']))
                return $options[$kUp]['image'];
            if (!empty($options[$lower]) && is_array($options[$lower]) && !empty($options[$lower]['image']))
                return $options[$lower]['image'];
            return '';
        };
        $qtype = $options['type'] ?? 'text_mcq';
        ?>
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-edit me-2"></i>Edit Question</h5>
            </div>
            <div class="card-body">
                <form method="POST" class="needs-validation" novalidate id="question-form-edit"
                    enctype="multipart/form-data">
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="question_id" value="<?= $editQuestion['id'] ?>">
                    <!-- edit does not support Save & Add Another -->
                    <div class="row g-3">
                        <div class=" col-md-4">
                            <label class="form-label">Subject</label>
                            <select class="form-select" name="subject">
                                <option value="">Select Subject</option>
                                <?php foreach ($allSubjects as $s): ?>
                                <option value="<?= htmlspecialchars($s) ?>"
                                    <?= ($editQuestion['subject'] == $s) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($s) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                            <div style="margin-top: 1rem;">
                                <div class="form-text">Or add below</div>
                                <input type="text" class="form-control mt-2" name="new_subject" value="">

                            </div>
                        </div>

                        <div class="col-md-4 d-flex flex-column justify-content-between" style="height: 100%;">
                            <div>
                                <label class="form-label">Topic</label>
                                <input class="form-control" name="topic"
                                    value="<?= htmlspecialchars($editQuestion['topic']) ?>">
                            </div>
                            <div class="mt-3">
                                <label class="form-label mb-1">Subtopic</label>
                                <input type="text" class="form-control" name="subtopic" placeholder="Subtopic"
                                    value="<?= htmlspecialchars($editQuestion['subtopic'] ?? '') ?>">
                            </div>
                        </div>

                        <div class=" col-md-4">
                            <label class="form-label">Question Type</label>
                            <select class="form-select" id="question-type" name="question_type">
                                <option value="text_mcq" <?= $qtype === 'text_mcq' ? 'selected' : '' ?>>Text-Based MCQ
                                    (Single Correct)</option>
                                <option value="image_mcq" <?= $qtype === 'image_mcq' ? 'selected' : '' ?>>Image-Based
                                    MCQ
                                </option>
                                <option value="passage" <?= $qtype === 'passage' ? 'selected' : '' ?>>Passage-Based
                                    /
                                    Comprehension</option>
                                <option value="fill_blank" <?= $qtype === 'fill_blank' ? 'selected' : '' ?>>Fill in
                                    the
                                    Blanks</option>
                                <option value="match" <?= $qtype === 'match' ? 'selected' : '' ?>>Match the
                                    Following
                                </option>
                                <option value="assertion" <?= $qtype === 'assertion' ? 'selected' : '' ?>>Assertion
                                    &
                                    Reasoning</option>
                                <option value="true_false" <?= $qtype === 'true_false' ? 'selected' : '' ?>>True /
                                    False
                                </option>
                                <option value="numerical" <?= $qtype === 'numerical' ? 'selected' : '' ?>>Numerical
                                </option>
                                <option value="multi_correct" <?= $qtype === 'multi_correct' ? 'selected' : '' ?>>
                                    Multiple
                                    Correct Answers (Multi-select)</option>
                                <option value="audio" <?= $qtype === 'audio' ? 'selected' : '' ?>>Audio-Based
                                    Question
                                </option>
                            </select>
                        </div>

                        <div class="col-12" id="passage-block" style="display:none;">
                            <label class="form-label">Passage</label>
                            <textarea class="form-control" name="passage"
                                rows="4"><?= htmlspecialchars($options['passage'] ?? '') ?></textarea>
                        </div>

                        <div class="col-12 mb-2">
                            <div class="btn-group" role="group" aria-label="Language switcher">
                                <button type="button" class="btn btn-outline-primary lang-btn active"
                                    data-lang="en">English</button>
                                <button type="button" class="btn btn-outline-secondary lang-btn"
                                    data-lang="ta">Tamil</button>
                                <button type="button" class="btn btn-outline-secondary lang-btn"
                                    data-lang="hi">Hindi</button>
                            </div>
                            <div class="form-text">Type translations per language. Switching preserves your inputs.
                            </div>
                        </div>

                        <!-- Edit media block: placed above multilingual question text fields -->
                        <div class="col-12 mb-3" id="media-block-edit" style="display:none;">
                            <label class="form-label">Upload Image / Audio</label>
                            <input type="file" class="form-control mb-2" name="image" accept="image/*">
                            <input type="file" class="form-control" name="audio" accept="audio/*">
                            <?php if (!empty($options['image'])): ?>
                            <div class="mt-2"><img src="<?= htmlspecialchars($options['image']) ?>"
                                    style="max-width:220px; max-height:140px;"></div>
                            <?php endif; ?>
                        </div>

                        <!-- Left: question text, question image, match and options (prefilled) -->
                        <div class="col-md-7">
                            <div class="lang-block" data-lang="en">
                                <label class="form-label">Question Text (English)</label>
                                <textarea class="form-control lang-input" id="question_text_en" name="question_text_en"
                                    rows="3"><?= htmlspecialchars($getI18n('en', 'question_text', $editQuestion['question_text'])) ?></textarea>
                            </div>
                            <div class="lang-block" data-lang="ta">
                                <label class="form-label">Question Text (Tamil)</label>
                                <textarea class="form-control lang-input" id="question_text_ta" name="question_text_ta"
                                    rows="3"><?= htmlspecialchars($getI18n('ta', 'question_text', '')) ?></textarea>
                            </div>
                            <div class="lang-block" data-lang="hi">
                                <label class="form-label">Question Text (Hindi)</label>
                                <textarea class="form-control lang-input" id="question_text_hi" name="question_text_hi"
                                    rows="3"><?= htmlspecialchars($getI18n('hi', 'question_text', '')) ?></textarea>
                            </div>

                            <!-- media-block-edit removed from here (moved above language inputs) -->

                            <div id="match-block" style="display:<?= $qtype === 'match' ? 'block' : 'none' ?>;"
                                class="mb-3">
                                <label class="form-label">Match Pairs</label>
                                <div id="match-rows">
                                    <?php if (!empty($options['match']) && is_array($options['match'])):
                                                foreach ($options['match'] as $pair): ?>
                                    <div class="d-flex mb-2">
                                        <input class="form-control me-2" name="match_left[]"
                                            value="<?= htmlspecialchars($pair[0] ?? '') ?>" placeholder="Left">
                                        <input class="form-control" name="match_right[]"
                                            value="<?= htmlspecialchars($pair[1] ?? '') ?>" placeholder="Right">
                                        <button type="button"
                                            class="btn btn-sm btn-outline-danger ms-2 remove-match-row">&times;</button>
                                    </div>
                                    <?php endforeach; else: ?>
                                    <div class="d-flex mb-2">
                                        <input class="form-control me-2" name="match_left[]" placeholder="Left">
                                        <input class="form-control" name="match_right[]" placeholder="Right">
                                        <button type="button"
                                            class="btn btn-sm btn-outline-danger ms-2 remove-match-row">&times;</button>
                                    </div>
                                    <?php endif; ?>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-secondary" id="add-match-row">Add
                                    Row</button>
                            </div>

                            <div id="options-block">
                                <label class="form-label">Options (A - D)</label>
                                <div class="row">
                                    <div class="col-6 mb-3">
                                        <label class="form-label small">Option A</label>
                                        <input class="form-control mb-1 lang-input" name="option_a_en"
                                            placeholder="Option A (English)"
                                            value="<?= htmlspecialchars($optFor('A', 'en')) ?>">
                                        <input class="form-control mb-1 lang-input" name="option_a_ta"
                                            placeholder="Option A (Tamil)"
                                            value="<?= htmlspecialchars($optFor('A', 'ta')) ?>">
                                        <input class="form-control mb-1 lang-input" name="option_a_hi"
                                            placeholder="Option A (Hindi)"
                                            value="<?= htmlspecialchars($optFor('A', 'hi')) ?>">
                                        <input type="file" class="form-control form-control-sm option-image-input"
                                            name="option_a_image" accept="image/*" />
                                        <?php if ($img = $optImage('A')): ?>
                                        <div class="mt-2"><img src="<?= htmlspecialchars($img) ?>"
                                                style="max-width:180px; max-height:120px;"></div><?php endif; ?>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <label class="form-label small">Option B</label>
                                        <input class="form-control mb-1 lang-input" name="option_b_en"
                                            placeholder="Option B (English)"
                                            value="<?= htmlspecialchars($optFor('B', 'en')) ?>">
                                        <input class="form-control mb-1 lang-input" name="option_b_ta"
                                            placeholder="Option B (Tamil)"
                                            value="<?= htmlspecialchars($optFor('B', 'ta')) ?>">
                                        <input class="form-control mb-1 lang-input" name="option_b_hi"
                                            placeholder="Option B (Hindi)"
                                            value="<?= htmlspecialchars($optFor('B', 'hi')) ?>">
                                        <input type="file" class="form-control form-control-sm option-image-input"
                                            name="option_b_image" accept="image/*" />
                                        <?php if ($img = $optImage('B')): ?>
                                        <div class="mt-2"><img src="<?= htmlspecialchars($img) ?>"
                                                style="max-width:180px; max-height:120px;"></div><?php endif; ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6 mb-3">
                                        <label class="form-label small">Option C</label>
                                        <input class="form-control mb-1 lang-input" name="option_c_en"
                                            placeholder="Option C (English)"
                                            value="<?= htmlspecialchars($optFor('C', 'en')) ?>">
                                        <input class="form-control mb-1 lang-input" name="option_c_ta"
                                            placeholder="Option C (Tamil)"
                                            value="<?= htmlspecialchars($optFor('C', 'ta')) ?>">
                                        <input class="form-control mb-1 lang-input" name="option_c_hi"
                                            placeholder="Option C (Hindi)"
                                            value="<?= htmlspecialchars($optFor('C', 'hi')) ?>">
                                        <input type="file" class="form-control form-control-sm option-image-input"
                                            name="option_c_image" accept="image/*" />
                                        <?php if ($img = $optImage('C')): ?>
                                        <div class="mt-2"><img src="<?= htmlspecialchars($img) ?>"
                                                style="max-width:180px; max-height:120px;"></div><?php endif; ?>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <label class="form-label small">Option D</label>
                                        <input class="form-control mb-1 lang-input" name="option_d_en"
                                            placeholder="Option D (English)"
                                            value="<?= htmlspecialchars($optFor('D', 'en')) ?>">
                                        <input class="form-control mb-1 lang-input" name="option_d_ta"
                                            placeholder="Option D (Tamil)"
                                            value="<?= htmlspecialchars($optFor('D', 'ta')) ?>">
                                        <input class="form-control mb-1 lang-input" name="option_d_hi"
                                            placeholder="Option D (Hindi)"
                                            value="<?= htmlspecialchars($optFor('D', 'hi')) ?>">
                                        <input type="file" class="form-control form-control-sm option-image-input"
                                            name="option_d_image" accept="image/*" />
                                        <?php if ($img = $optImage('D')): ?>
                                        <div class="mt-2"><img src="<?= htmlspecialchars($img) ?>"
                                                style="max-width:180px; max-height:120px;"></div><?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <?php
                                    // Detect whether Option E exists in various shapes (bulk import may store fields differently)
                                    $hasOptionE = false;
                                    if (!empty($optFor('E', 'en')) || !empty($optFor('E', 'ta')) || !empty($optFor('E', 'hi')) || !empty($optImage('E'))) {
                                        $hasOptionE = true;
                                    }
                                    // Check common top-level keys that bulk import may produce
                                    $candidates = ['option_e', 'option_e_en', 'option_e_ta', 'option_e_hi', 'optionE', 'E', 'e'];
                                    foreach ($candidates as $k) {
                                        if (!$hasOptionE && isset($editQuestion[$k]) && trim((string) $editQuestion[$k]) !== '') {
                                            $hasOptionE = true;
                                            break;
                                        }
                                    }
                                    // Also check $options array shapes not caught by optFor
                                    if (!$hasOptionE) {
                                        if (!empty($options['E']) || !empty($options['option_e']) || (!empty($options['options']) && !empty($options['options']['E']))) {
                                            $hasOptionE = true;
                                        }
                                    }
                                    ?>
                            <div class="row" id="option-e-row" style="display:<?= $hasOptionE ? 'block' : 'none' ?>;">
                                <div class="col-12 mb-3">
                                    <label class="form-label small">Option E</label>
                                    <input class="form-control mb-1 lang-input" name="option_e_en"
                                        placeholder="Option E (English)"
                                        value="<?= htmlspecialchars($optFor('E', 'en')) ?>">
                                    <input class="form-control mb-1 lang-input" name="option_e_ta"
                                        placeholder="Option E (Tamil)"
                                        value="<?= htmlspecialchars($optFor('E', 'ta')) ?>">
                                    <input class="form-control mb-1 lang-input" name="option_e_hi"
                                        placeholder="Option E (Hindi)"
                                        value="<?= htmlspecialchars($optFor('E', 'hi')) ?>">
                                    <input type="file" class="form-control form-control-sm option-image-input"
                                        name="option_e_image" accept="image/*" />
                                    <?php if ($img = $optImage('E')): ?>
                                    <div class="mt-2"><img src="<?= htmlspecialchars($img) ?>"
                                            style="max-width:180px; max-height:120px;"></div><?php endif; ?>
                                </div>
                            </div>
                            <div class="mb-2">
                                <button type="button" id="toggle-option-e"
                                    class="btn btn-sm btn-outline-secondary"><?php echo ($hasOptionE ? '- Remove Option E' : '+ Add Option E'); ?></button>
                            </div>
                            <!-- Correct answer selector for edit -->
                            <div id="correct-answer-block-edit" class="mt-2" style="display:block;">
                                <label class="form-label small">Correct Answer</label>
                                <select class="form-select" name="correct_answer">
                                    <option value="">Select Correct Answer</option>
                                    <option value="A"
                                        <?= (isset($editQuestion['correct_answer']) && trim($editQuestion['correct_answer']) === 'A') ? 'selected' : '' ?>>
                                        A</option>
                                    <option value="B"
                                        <?= (isset($editQuestion['correct_answer']) && trim($editQuestion['correct_answer']) === 'B') ? 'selected' : '' ?>>
                                        B</option>
                                    <option value="C"
                                        <?= (isset($editQuestion['correct_answer']) && trim($editQuestion['correct_answer']) === 'C') ? 'selected' : '' ?>>
                                        C</option>
                                    <option value="D"
                                        <?= (isset($editQuestion['correct_answer']) && trim($editQuestion['correct_answer']) === 'D') ? 'selected' : '' ?>>
                                        D</option>
                                    <option value="E"
                                        <?= (isset($editQuestion['correct_answer']) && trim($editQuestion['correct_answer']) === 'E') ? 'selected' : '' ?>>
                                        E</option>
                                </select>
                            </div>
                        </div>
                        <!-- Right: Live preview -->
                        <div class="col-md-5 d-flex align-items-start">
                            <div class="card mt-1 w-100" style="height: 500px;">
                                <div class="card-header"><strong>Live Preview</strong></div>
                                <div class="card-body" id="live-preview" style="height: 300px; overflow-y: auto;">
                                    <div id="live-preview-content">Fill the form to see live preview here.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <script>
                    // Edit-form live preview implementation (scoped to #question-form-edit)
                    (function() {
                        function escapeHtml(str) {
                            return String(str == null ? '' : str).replace(/&/g, '&amp;').replace(/</g, '&lt;')
                                .replace(/>/g, '&gt;');
                        }

                        function renderOptionsForPreview(optsObj) {
                            // optsObj is a map like {A: 'text', B: 'text', image: 'url', audio: 'url'}
                            var html = '<ul class="list-group list-group-flush">';
                            ['A', 'B', 'C', 'D', 'E'].forEach(function(k) {
                                if (optsObj && optsObj[k]) {
                                    html += '<li class="list-group-item">' + escapeHtml(optsObj[k]) + (
                                            optsObj[k + '_image'] ? (' <img src="' + escapeHtml(optsObj[k +
                                                    '_image']) +
                                                '" style="max-height:40px; margin-left:8px;">') : '') +
                                        '</li>';
                                }
                            });
                            html += '</ul>';
                            return html;
                        }

                        function getActiveLang() {
                            var btn = document.querySelector('#question-form-edit .lang-btn.active') || document
                                .querySelector('.lang-btn.active');
                            return (btn && btn.dataset && btn.dataset.lang) ? btn.dataset.lang : 'en';
                        }

                        function buildPreviewHtmlEdit() {
                            var form = document.getElementById('question-form-edit');
                            if (!form) return '<div>No form found</div>';

                            var subject = (form.querySelector('[name="subject"]') || {}).value || '';
                            var topic = (form.querySelector('[name="topic"]') || {}).value || '';
                            var activeLang = getActiveLang();

                            var enText = (form.querySelector('[name="question_text_en"]') || {}).value || '';
                            var activeText = (form.querySelector('[name="question_text_' + activeLang + '"]') || {})
                                .value || '';

                            var optsEn = {},
                                optsLang = {};
                            ['A', 'B', 'C', 'D', 'E'].forEach(function(k) {
                                var en = form.querySelector('[name="option_' + k.toLowerCase() + '_en"]');
                                var lang = form.querySelector('[name="option_' + k.toLowerCase() + '_' +
                                    activeLang + '"]');
                                if (en && en.value) optsEn[k] = en.value;
                                if (lang && lang.value) optsLang[k] = lang.value;
                            });

                            // attach images if present (file inputs show files)
                            ['a', 'b', 'c', 'd', 'e'].forEach(function(ch) {
                                var fi = form.querySelector('[name="option_' + ch + '_image"]');
                                if (fi && fi.files && fi.files[0]) {
                                    var url = URL.createObjectURL(fi.files[0]);
                                    optsEn[ch.toUpperCase() + '_image'] = url;
                                    optsLang[ch.toUpperCase() + '_image'] = url;
                                }
                            });

                            var qimg = form.querySelector('[name="image"]');
                            var qaud = form.querySelector('[name="audio"]');
                            var qimgUrl = (qimg && qimg.files && qimg.files[0]) ? URL.createObjectURL(qimg.files[
                                0]) : (window._edit_prefilled_qimage || '');
                            var qaudUrl = (qaud && qaud.files && qaud.files[0]) ? URL.createObjectURL(qaud.files[
                                0]) : (window._edit_prefilled_qaud || '');

                            var html = '';
                            html += '<div><strong>' + escapeHtml(subject) + '</strong> <small class="text-muted">' +
                                escapeHtml(topic) + '</small></div>';

                            var displayText = (activeLang === 'en') ? enText : (activeText || enText);
                            if (displayText) html += '<h6 class="mt-2">' + escapeHtml(displayText) + '</h6>';

                            if (qimgUrl) html += '<div class="mb-2"><img src="' + escapeHtml(qimgUrl) +
                                '" style="max-width:100%; height:auto"></div>';
                            if (qaudUrl) html += '<div class="mb-2"><audio controls src="' + escapeHtml(qaudUrl) +
                                '"></audio></div>';

                            // Choose active options, fallback to English
                            var chosen = (Object.keys(optsLang).length > 0) ? optsLang : optsEn;
                            if (Object.keys(chosen).length > 0) html += renderOptionsForPreview(chosen);

                            var correct = (form.querySelector('[name="correct_answer"]') || {}).value || '';
                            if (correct) html += '<div class="mt-2"><strong>Correct:</strong> ' + escapeHtml(
                                correct) + '</div>';

                            return html;
                        }

                        function updateLivePreview() {
                            var cont = document.querySelector('#question-form-edit #live-preview-content') ||
                                document.getElementById('live-preview-content');
                            if (!cont) return;
                            cont.innerHTML = buildPreviewHtmlEdit();
                            // Ensure MathJax renders any inline math in the edit live preview
                            if (window.MathJax && MathJax.typesetPromise) {
                                MathJax.typesetPromise().catch(function(err) {
                                    console.error('MathJax typeset error (edit live preview):', err);
                                });
                            } else {
                                // retry shortly in case MathJax is still loading
                                setTimeout(function() {
                                    if (window.MathJax && MathJax.typesetPromise) {
                                        MathJax.typesetPromise().catch(function(err) {
                                            console.error(
                                                'MathJax typeset error (edit live preview, retry):',
                                                err);
                                        });
                                    }
                                }, 500);
                            }
                        }

                        // init language switcher for edit form: show only active lang inputs
                        function setActiveLanguageEdit(lang) {
                            document.querySelectorAll('#question-form-edit .lang-btn').forEach(function(b) {
                                if (b.dataset && b.dataset.lang === lang) b.classList.add('active');
                                else b.classList.remove('active');
                            });

                            // show/hide lang-inputs inside this form and lang-block containers
                            document.querySelectorAll('#question-form-edit .lang-input').forEach(function(inp) {
                                var name = inp.name || '';
                                if (!name) return;
                                if (/_en$/.test(name)) inp.style.display = (lang === 'en') ? '' : 'none';
                                else if (/_ta$/.test(name)) inp.style.display = (lang === 'ta') ? '' :
                                    'none';
                                else if (/_hi$/.test(name)) inp.style.display = (lang === 'hi') ? '' :
                                    'none';
                                else inp.style.display = '';
                            });
                            // hide/show the larger lang-block sections
                            document.querySelectorAll('#question-form-edit .lang-block').forEach(function(lb) {
                                if (lb.dataset && lb.dataset.lang === lang) lb.style.display = '';
                                else lb.style.display = 'none';
                            });

                            updateLivePreview();
                        }

                        // attach handlers once DOM ready
                        document.addEventListener('DOMContentLoaded', function() {
                            // set initial active language from button class
                            var initial = (document.querySelector('#question-form-edit .lang-btn.active') &&
                                document.querySelector('#question-form-edit .lang-btn.active').dataset
                                .lang) || 'en';
                            setActiveLanguageEdit(initial);

                            // attach click listeners on edit lang buttons
                            document.querySelectorAll('#question-form-edit .lang-btn').forEach(function(
                                btn) {
                                btn.addEventListener('click', function() {
                                    setActiveLanguageEdit(btn.dataset.lang);
                                });
                            });

                            // wire preview triggers
                            var f = document.getElementById('question-form-edit');
                            if (f) {
                                ['input', 'change'].forEach(function(evt) {
                                    f.addEventListener(evt, function(e) {
                                        updateLivePreview();
                                    }, {
                                        capture: true
                                    });
                                });
                            }

                            // show/hide media upload for edit depending on question_type
                            function updateFormByTypeEdit() {
                                var type = (document.getElementById('question-type') || {}).value || '';
                                var showMedia = (type === 'image_mcq' || type === 'audio');
                                var mediaBlock = document.getElementById('media-block-edit');
                                if (mediaBlock) mediaBlock.style.display = showMedia ? '' : 'none';

                                // Option image inputs should only be visible for image_mcq
                                document.querySelectorAll('#question-form-edit .option-image-input')
                                    .forEach(function(el) {
                                        el.style.display = (type === 'image_mcq') ? '' : 'none';
                                    });
                            }
                            document.getElementById('question-type').addEventListener('change',
                                updateFormByTypeEdit);
                            updateFormByTypeEdit();

                            // Option E toggle wiring
                            (function() {
                                var toggle = document.getElementById('toggle-option-e');
                                var row = document.getElementById('option-e-row');
                                if (toggle && row) {
                                    toggle.addEventListener('click', function() {
                                        if (row.style.display === 'none' || row.style
                                            .display === '') {
                                            row.style.display = 'block';
                                            toggle.textContent = '- Remove Option E';
                                        } else {
                                            // clear fields when hiding
                                            row.querySelectorAll('input').forEach(function(i) {
                                                if (i.type !== 'file') i.value = '';
                                            });
                                            row.style.display = 'none';
                                            toggle.textContent = '+ Add Option E';
                                        }
                                        updateLivePreview();
                                    });
                                }
                            })();

                            // initial update
                            setTimeout(updateLivePreview, 50);
                        });

                        // expose for external calls
                        window.buildPreviewHtmlEdit = buildPreviewHtmlEdit;
                        window.updateLivePreview = updateLivePreview;
                    })();
                    </script>

                    <div class="row mt-3">
                        <div class="col-md-4">
                            <label class="form-label">Difficulty</label>
                            <select class="form-select" name="difficulty">
                                <option value="easy" <?= $editQuestion['difficulty'] === 'easy' ? 'selected' : '' ?>>
                                    Easy
                                </option>
                                <option value="medium"
                                    <?= $editQuestion['difficulty'] === 'medium' ? 'selected' : '' ?>>
                                    Medium</option>
                                <option value="hard" <?= $editQuestion['difficulty'] === 'hard' ? 'selected' : '' ?>>
                                    Hard
                                </option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Exam Year</label>
                            <input class="form-control" name="exam_year"
                                value="<?= htmlspecialchars($editQuestion['exam_year']) ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Source / Exam</label>
                            <input class="form-control" name="source"
                                value="<?= htmlspecialchars($editQuestion['source']) ?>">
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-12">
                            <label class="form-label">Explanation</label>
                            <textarea class="form-control" name="explanation"
                                rows="3"><?= htmlspecialchars($editQuestion['explanation']) ?></textarea>
                        </div>
                    </div>

                    <div class="col-12 d-flex justify-content-between align-items-center mt-3">
                        <div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_public" name="is_public"
                                    <?= $editQuestion['is_public'] ? 'checked' : '' ?>>
                                <label class="form-check-label" for="is_public">Public</label>
                            </div>
                        </div>
                        <div>
                            <button type="button" class="btn btn-secondary me-2"
                                onclick="window.location.href='index.php?page=questions'">Cancel</button>
                            <button type="submit" class="btn btn-primary"><i class="fas fa-check me-2"></i>Update
                                Question</button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<?php elseif ($action === 'upload'): ?>
<!-- Bulk Upload -->
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"> <i class="fas fa-upload me-2"></i>Bulk Upload Questions
                </h5>
            </div>
            <div class="card-body">

                <?php if (($_GET['step'] ?? '') === 'preview' && isset($_SESSION['bulk_upload_questions'])): ?>
                <!-- Preview Step -->
                <div class="mb-4">
                    <h6>Preview Questions Before Import</h6>
                    <p class="text-muted">Review the parsed questions below and click "Import All"
                        to add them to your
                        question bank.</p>
                </div>

                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class=" badge bg-info">Total Questions:
                            <?= count($_SESSION['bulk_upload_questions']) ?></span>
                        <div>
                            <button class="btn btn-secondary me-2"
                                onclick="window.location.href='index.php?page=questions&action=upload'">
                                <i class="fas fa-arrow-left me-2"></i>Back to Upload
                            </button>
                            <button class=" btn btn-success" id="import-questions-btn">
                                <i class="fas fa-check me-2"></i>Import All Questions
                            </button>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Subject</th>
                                <th>Topic</th>
                                <th>Question</th>
                                <th>Options</th>
                                <th>Answer</th>
                                <th>Difficulty</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                    // robust helper for preview rows: prefer Tamil/Hindi if available, but fall back
                                    // to English and multiple shapes that may be present after parsing/import.
                                    $previewOpt = function ($question, $opt) {
                                        $k = strtoupper($opt);

                                        // 1) i18n block (common shape)
                                        if (!empty($question['i18n']['ta'][$k]))
                                            return $question['i18n']['ta'][$k];
                                        if (!empty($question['i18n']['hi'][$k]))
                                            return $question['i18n']['hi'][$k];
                                        if (!empty($question['i18n']['en'][$k]))
                                            return $question['i18n']['en'][$k];

                                        // 2) options top-level as stored by importQuestionsToDatabase (options JSON may be in $question['options'] or option_A fields)
                                        // If parser kept flat keys like option_a, option_a_en, option_a_ta
                                        $low = 'option_' . strtolower($opt);
                                        if (!empty($question[$low . '_ta']))
                                            return $question[$low . '_ta'];
                                        if (!empty($question[$low . '_hi']))
                                            return $question[$low . '_hi'];
                                        if (!empty($question[$low . '_en']))
                                            return $question[$low . '_en'];
                                        if (!empty($question[$low]))
                                            return $question[$low];

                                        // 3) options JSON object (sometimes kept as nested 'options' or 'options' key in parsed row)
                                        if (!empty($question['options']) && is_array($question['options'])) {
                                            // A/B keys
                                            if (!empty($question['options'][$k])) {
                                                $val = $question['options'][$k];
                                                if (is_array($val)) {
                                                    return $val['text'] ?? $val['label'] ?? '';
                                                }
                                                return (string) $val;
                                            }
                                            // option_A_image or A_image not used here
                                            // i18n inside options
                                            if (!empty($question['options']['i18n']['ta'][$k]))
                                                return $question['options']['i18n']['ta'][$k];
                                            if (!empty($question['options']['i18n']['hi'][$k]))
                                                return $question['options']['i18n']['hi'][$k];
                                            if (!empty($question['options']['i18n']['en'][$k]))
                                                return $question['options']['i18n']['en'][$k];
                                        }

                                        // As a last resort, return empty string
                                        return '';
                                    };
                                    foreach ($_SESSION['bulk_upload_questions'] as $index => $question): ?>
                            <tr>
                                <td>
                                    <?= $index + 1 ?>
                                </td>
                                <td>
                                    <?= htmlspecialchars($question['subject']) ?>
                                </td>
                                <td>
                                    <?= htmlspecialchars($question['topic']) ?>
                                </td>
                                <td>
                                    <?= htmlspecialchars(substr($question['question_text'], 0, 100)) ?>
                                    <?= strlen($question['question_text']) > 100 ? '...' : '' ?>
                                    <?php if (!empty($question['i18n']['ta']['question_text']) || !empty($question['i18n']['hi']['question_text'])): ?>
                                    <div class="small text-muted mt-1">
                                        <?php if (!empty($question['i18n']['ta']['question_text'])): ?>
                                        <div><strong>TA:</strong>
                                            <?= htmlspecialchars(substr($question['i18n']['ta']['question_text'], 0, 80)) ?>
                                            <?= strlen($question['i18n']['ta']['question_text']) > 80 ? '...' : '' ?>
                                        </div>
                                        <?php endif; ?>
                                        <?php if (!empty($question['i18n']['hi']['question_text'])): ?>
                                        <div><strong>HI:</strong>
                                            <?= htmlspecialchars(substr($question['i18n']['hi']['question_text'], 0, 80)) ?>
                                            <?= strlen($question['i18n']['hi']['question_text']) > 80 ? '...' : '' ?>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <small>
                                        A: <?= htmlspecialchars(substr($previewOpt($question, 'A'), 0, 30)) ?><br>
                                        B: <?= htmlspecialchars(substr($previewOpt($question, 'B'), 0, 30)) ?><br>
                                        C: <?= htmlspecialchars(substr($previewOpt($question, 'C'), 0, 30)) ?><br>
                                        D: <?= htmlspecialchars(substr($previewOpt($question, 'D'), 0, 30)) ?>
                                    </small>
                                </td>
                                <td><span class="badge bg-primary">
                                        <?= $question['correct_answer'] ?>
                                    </span>
                                </td>
                                <td><span
                                        class="badge bg-<?= $question['difficulty'] === 'easy' ? 'success' : ($question['difficulty'] === 'medium' ? 'warning' : 'danger') ?>">
                                        <?= ucfirst($question['difficulty']) ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <script>
                document.getElementById('import-questions-btn').addEventListener('click',
                    function() {
                        if (!confirm('Are you sure you want to import all these questions?'))
                            return;

                        this.disabled = true;
                        this.innerHTML =
                            '<i class="fas fa-spinner fa-spin me-2"></i>Importing...';

                        fetch('index.php?page=questions&action=import_questions', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded'
                                }
                            })
                            .then(async (response) => {
                                const text = await response.text();
                                try {
                                    return JSON.parse(text);
                                } catch (err) {
                                    throw new Error(text || 'Invalid server response');
                                }
                            })
                            .then(data => {
                                if (data.success) {
                                    alert(data.message);
                                    if (data.redirect) {
                                        window.location.href = data.redirect;
                                    }
                                } else {
                                    alert('Error: ' + data.error);
                                }
                            })
                            .catch(error => {
                                alert('Network error: ' + error.message);
                            })
                            .finally(() => {
                                this.disabled = false;
                                this.innerHTML =
                                    '<i class="fas fa-check me-2"></i>Import All Questions';
                            });
                    });
                </script>

                <?php else: ?>
                <!-- Upload Step -->
                <div class="row">
                    <div class="col-md-6">
                        <h6>Upload Methods</h6>
                        <div class="list-group mb-4">
                            <div class="list-group-item d-flex justify-content-between align-items-start">
                                <div class="ms-2 me-auto">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Excel/CSV Upload</h6>
                                        <small>Recommended</small>
                                    </div>
                                    <p class="mb-1">Upload .xlsx or .csv files with question data
                                    </p>
                                    <small>Best for large datasets</small>
                                </div>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-start">
                                <div class="ms-2 me-auto">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Text Import</h6>
                                        <small>Manual</small>
                                    </div>
                                    <p class="mb-1">Copy-paste questions in structured format</p>
                                    <small>For quick manual entry</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <h6>Upload Area</h6>
                        <div class="border border-dashed border-primary rounded p-5 text-center" id="upload-area">
                            <i class="fas fa-cloud-upload-alt fa-3x text-primary mb-3"></i>
                            <h5>Drag & Drop Files Here</h5>
                            <p class="text-muted">or click to browse</p>
                            <input type="file" id="file-input" accept=".xlsx,.csv" style="display: none;">
                            <button class="btn btn-primary" onclick="document.getElementById('file-input').click()">
                                <i class="fas fa-file me-2"></i>Choose Files
                            </button>
                        </div>

                        <div class="mt-3">
                            <small class="text-muted">
                                Supported formats: .xlsx, .csv<br>
                                Maximum file size: 10MB
                            </small>
                        </div>
                    </div>
                </div>

                <hr>

                <!-- Template Download -->
                <div class="row">
                    <div class="col-md-12">
                        <h6>Template & Instructions</h6>
                        <div class="bg-light p-3 rounded">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>Excel Template (.xlsx)</strong>
                                            <br><small class="text-muted">Professional format with
                                                company header & serial numbers</small>
                                        </div>
                                        <button class="btn btn-success btn-sm"
                                            onclick="window.location.href='index.php?page=questions&action=download_template&format=excel'">
                                            <i class="fas fa-file-excel me-2"></i>Excel
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>CSV Template (.csv)</strong>
                                            <br><small class="text-muted">Simple format compatible
                                                with all spreadsheet apps</small>
                                        </div>
                                        <button class="btn btn-outline-success btn-sm"
                                            onclick="window.location.href='index.php?page=questions&action=download_template&format=csv'">
                                            <i class="fas fa-file-csv me-2"></i>CSV
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3 p-2 bg-warning bg-opacity-10 rounded">
                                <small class="text-warning">
                                    <i class="fas fa-info-circle me-1"></i>
                                    <strong>New Format:</strong> Templates now include a company
                                    name row and S.No column for better organization. Both old and
                                    new formats are supported for upload.
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                <!-- Text Import -->
                <div class="row">
                    <div class="col-md-12">
                        <h6>Text Import</h6>
                        <p class="text-muted">Paste questions in the following format:</p>
                        <div class="bg-light p-3 rounded mb-3">
                            <small>
                                <strong>Example format:</strong><br>
                                1. With reference to the writs issued by the Courts in India,
                                consider the following
                                statements:<br><br>
                                1. Mandamus will not lie against a private organization unless it is
                                entrusted with a
                                public duty.<br>
                                2. Mandamus will not lie against a Company even though it may be a
                                Government
                                Company.<br>
                                3. Any public minded person can be a petitioner to move the Court to
                                obtain the writ of
                                Quo Warranto.<br><br>
                                Which of the statements given above are correct?<br><br>
                                A) 1 and 2 only<br>
                                B) 2 and 3 only<br>
                                C) 1 and 3 only<br>
                                D) 1, 2 and 3<br>
                                Answer: C<br>
                                Explanation: Statement 1 is correct, Statement 2 is incorrect,
                                Statement 3 is
                                correct<br><br>

                                2. In the series: 2, 6, 12, 20, 30, ? What comes next?<br>
                                A) 42<br>
                                B) 40<br>
                                C) 36<br>
                                D) 48<br>
                                Answer: A<br>
                            </small>
                        </div>

                        <form id="text-import-form">
                            <div class="mb-3">
                                <label for="text-content" class="form-label">Paste Questions
                                    Here</label>
                                <textarea class="form-control" id="text-content" name="text_content" rows="10"
                                    placeholder="Paste your questions here in the format shown above..."></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-upload me-2"></i>Import Text
                            </button>
                        </form>
                    </div>
                </div>

                <script>
                // File upload handling
                const uploadArea = document.getElementById('upload-area');

                // Drag and drop handlers
                uploadArea.addEventListener('dragover', function(e) {
                    e.preventDefault();
                    uploadArea.classList.add('border-success');
                });

                uploadArea.addEventListener('dragleave', function(e) {
                    e.preventDefault();
                    uploadArea.classList.remove('border-success');
                });

                uploadArea.addEventListener('drop', function(e) {
                    e.preventDefault();
                    uploadArea.classList.remove('border-success');

                    const files = e.dataTransfer.files;
                    if (files.length > 0) {
                        handleFileUpload(files[0]);
                    }
                });

                // Delegate change event so it still works if input is re-rendered
                document.addEventListener('change', function(e) {
                    if (e.target && e.target.id === 'file-input' && e.target.files.length > 0) {
                        handleFileUpload(e.target.files[0]);
                    }
                });

                function handleFileUpload(file) {
                    // Validate file
                    const allowedTypes = [
                        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                        'text/csv'
                    ];
                    if (!allowedTypes.includes(file.type) && !file.name.match(/\.(xlsx|csv)$/i)) {
                        alert('Please upload only Excel (.xlsx) or CSV (.csv) files');
                        return;
                    }

                    if (file.size > 10 * 1024 * 1024) {
                        alert('File size must be less than 10MB');
                        return;
                    }

                    // Show upload progress
                    uploadArea.innerHTML =
                        '<i class="fas fa-spinner fa-spin fa-2x text-primary mb-3"></i><h5>Processing file...</h5><p class="text-muted">Please wait while we parse your file</p>';

                    // Create form data and upload
                    const formData = new FormData();
                    formData.append('upload_file', file);

                    fetch('index.php?page=questions&action=process_upload', {
                            method: 'POST',
                            body: formData
                        })
                        .then(async (response) => {
                            const text = await response.text();
                            try {
                                return JSON.parse(text);
                            } catch (err) {
                                throw new Error(text || 'Invalid server response');
                            }
                        })
                        .then(data => {
                            if (data.success) {
                                alert(data.message + '. Found ' + data.question_count +
                                    ' questions.');
                                if (data.redirect) {
                                    window.location.href = data.redirect;
                                }
                            } else {
                                alert('Error: ' + data.error);
                                resetUploadArea();
                            }
                        })
                        .catch(error => {
                            alert('Network error: ' + error.message);
                            resetUploadArea();
                        });
                }

                function resetUploadArea() {
                    uploadArea.innerHTML =
                        '<i class="fas fa-cloud-upload-alt fa-3x text-primary mb-3"></i>' +
                        '<h5>Drag & Drop Files Here</h5>' +
                        '<p class="text-muted">or click to browse</p>' +
                        '<input type="file" id="file-input" accept=".xlsx,.csv" style="display: none;">' +
                        '<button class="btn btn-primary" onclick="document.getElementById(\'file-input\').click()">' +
                        '<i class="fas fa-file me-2"></i>Choose Files</button>';
                }

                // Text import handling
                document.getElementById('text-import-form').addEventListener('submit', function(e) {
                    e.preventDefault();

                    const textContent = document.getElementById('text-content').value
                        .trim();
                    if (!textContent) {
                        alert('Please enter some text content');
                        return;
                    }

                    const submitBtn = this.querySelector('button[type="submit"]');
                    submitBtn.disabled = true;
                    submitBtn.innerHTML =
                        '<i class="fas fa-spinner fa-spin me-2"></i>Processing...';

                    const formData = new FormData();
                    formData.append('text_content', textContent);

                    fetch('index.php?page=questions&action=process_text', {
                            method: 'POST',
                            body: formData
                        })
                        .then(async (response) => {
                            const text = await response.text();
                            try {
                                return JSON.parse(text);
                            } catch (err) {
                                throw new Error(text || 'Invalid server response');
                            }
                        })
                        .then(data => {
                            if (data.success) {
                                alert(data.message + '. Found ' + data.question_count +
                                    ' questions.');
                                if (data.redirect) {
                                    window.location.href = data.redirect;
                                }
                            } else {
                                alert('Error: ' + data.error);
                            }
                        })
                        .catch(error => {
                            alert('Network error: ' + error.message);
                        })
                        .finally(() => {
                            submitBtn.disabled = false;
                            submitBtn.innerHTML =
                                '<i class="fas fa-upload me-2"></i>Import Text';
                        });
                });
                </script>

                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php endif; ?>

<script>
document.querySelectorAll('.view-details-btn').forEach(function(button) {
    button.addEventListener('click', function() {
        // Store question data globally and use the language-aware preview system
        window._currentPreviewQuestion = {
            subject: this.getAttribute('data-subject'),
            topic: this.getAttribute('data-topic'),
            subtopic: this.getAttribute('data-subtopic'),
            question_text: this.getAttribute('data-question_text'),
            options: this.getAttribute('data-options'),
            correct_answer: this.getAttribute('data-correct_answer'),
            explanation: this.getAttribute('data-explanation'),
            difficulty: this.getAttribute('data-difficulty'),
            exam_year: this.getAttribute('data-exam_year'),
            source: this.getAttribute('data-source'),
            is_public: this.getAttribute('data-is_public')
        };

        // Reset to English and render
        document.querySelectorAll('.preview-lang-btn').forEach(b => b.classList.remove('active'));
        document.querySelector('.preview-lang-btn[data-lang="en"]').classList.add('active');

        if (typeof renderPreviewContent === 'function') {
            renderPreviewContent('en');
        }

        let modal = new bootstrap.Modal(document.getElementById('questionPreviewModal'));
        modal.show();
    });
});
</script>

<script>
document.querySelectorAll('.add-to-test-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const questionId = this.dataset.questionId;
        const questionLabel = this.dataset.questionLabel;

        document.getElementById('modalQuestionId').value = questionId;
        document.getElementById('modalQuestionText').textContent = questionLabel;
    });
});
</script>