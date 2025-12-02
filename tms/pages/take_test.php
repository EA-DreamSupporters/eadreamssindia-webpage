<?php
// Take Test - Protected Online Test Interface
$user = getCurrentUser();
if ($user['role'] !== 'student') {
    header('Location: index.php?page=dashboard');
    exit;
}

$testId = $_GET['id'] ?? 0;
$mode = $_GET['mode'] ?? 'exam'; // exam or practice

if (!$testId) {
    header('Location: index.php?page=my_tests');
    exit;
}

// Fetch test details
try {
    $stmt = $db->prepare("SELECT * FROM test_packs WHERE id = ?");
    $stmt->execute([$testId]);
    $test = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$test) {
        header('Location: index.php?page=my_tests');
        exit;
    }
} catch (Exception $e) {
    error_log("Test fetch error: " . $e->getMessage());
    header('Location: index.php?page=my_tests');
    exit;
}

// Fetch test questions from database
try {
    $questionsStmt = $db->prepare("
        SELECT q.id, q.question_text, q.options, q.correct_answer
        FROM test_questions tq
        INNER JOIN question_banks q ON tq.question_id = q.id
        WHERE tq.test_id = ?
        ORDER BY tq.id
    ");
    $questionsStmt->execute([$testId]);
    $questions = $questionsStmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($questions)) {
        // No questions found
        header('Location: index.php?page=my_tests&error=no_questions');
        exit;
    }
} catch (Exception $e) {
    error_log("Failed to fetch questions: " . $e->getMessage());
    header('Location: index.php?page=my_tests&error=fetch_error');
    exit;
}

$totalQuestions = count($questions);
$durationMinutes = $test['duration_minutes'] ?? 60;

// Determine available languages
$availableLanguages = ['en' => 'English'];
foreach ($questions as $q) {
    $opts = json_decode($q['options'] ?? '{}', true);
    if (isset($opts['i18n']) && is_array($opts['i18n'])) {
        foreach (array_keys($opts['i18n']) as $langCode) {
            if (!isset($availableLanguages[$langCode])) {
                // Map code to name
                $langName = match($langCode) {
                    'ta' => 'Tamil',
                    'hi' => 'Hindi',
                    'te' => 'Telugu',
                    'ml' => 'Malayalam',
                    'kn' => 'Kannada',
                    default => strtoupper($langCode)
                };
                $availableLanguages[$langCode] = $langName;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($test['title']) ?> - Sprint Pro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
    body {
        background: #0f1419;
        color: #fff;
        font-family: 'Inter', sans-serif;
        height: 100vh;
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }

    .test-header {
        background: #151a21;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        padding: 0 1.5rem;
        height: 64px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-shrink: 0;
        z-index: 1000;
    }

    .test-body {
        flex: 1;
        display: flex;
        overflow: hidden;
        position: relative;
    }

    .main-content {
        flex: 1;
        overflow-y: auto;
        padding: 2rem;
        position: relative;
        background: #0f1419;
    }

    .question-palette {
        width: 320px;
        background: #151a21;
        border-left: 1px solid rgba(255, 255, 255, 0.1);
        display: flex;
        flex-direction: column;
        flex-shrink: 0;
        z-index: 900;
    }

    .palette-header {
        padding: 1.25rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        background: #192029;
    }

    .palette-grid {
        padding: 1.25rem;
        overflow-y: auto;
        flex: 1;
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 0.75rem;
        align-content: start;
    }

    .palette-footer {
        padding: 1.25rem;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        background: #192029;
    }

    .timer {
        font-size: 1.2rem;
        font-weight: 700;
        padding: 0.5rem 1.25rem;
        background: rgba(66, 153, 225, 0.15);
        color: #63b3ed;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        border: 1px solid rgba(66, 153, 225, 0.3);
    }

    .timer.warning {
        background: rgba(245, 101, 101, 0.15);
        color: #fc8181;
        border-color: rgba(245, 101, 101, 0.3);
        animation: pulse 1s infinite;
    }

    @keyframes pulse {

        0%,
        100% {
            opacity: 1;
        }

        50% {
            opacity: 0.7;
        }
    }

    .question-card {
        background: #192029;
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 16px;
        padding: 2.5rem;
        max-width: 900px;
        margin: 0 auto;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
    }

    .question-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .option-btn {
        width: 100%;
        text-align: left;
        padding: 1rem 1.25rem;
        margin-bottom: 1rem;
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: #e2e8f0;
        border-radius: 10px;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        font-size: 1.05rem;
    }

    .option-btn:hover {
        background: rgba(255, 255, 255, 0.08);
        border-color: rgba(255, 255, 255, 0.2);
        transform: translateX(5px);
    }

    .option-btn.selected {
        background: rgba(66, 153, 225, 0.15);
        border-color: #4299e1;
        color: #63b3ed;
    }

    .option-marker {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        border: 2px solid rgba(255, 255, 255, 0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        font-weight: 600;
        font-size: 0.9rem;
        flex-shrink: 0;
        transition: all 0.2s;
    }

    .option-btn.selected .option-marker {
        background: #4299e1;
        border-color: #4299e1;
        color: #fff;
    }

    .palette-btn {
        width: 100%;
        aspect-ratio: 1;
        border-radius: 8px;
        border: 1px solid rgba(255, 255, 255, 0.1);
        background: rgba(255, 255, 255, 0.05);
        color: #a0aec0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        font-size: 0.9rem;
    }

    .palette-btn:hover {
        background: rgba(255, 255, 255, 0.1);
        color: #fff;
    }

    .palette-btn.answered {
        background: #2f855a;
        border-color: #2f855a;
        color: #fff;
    }

    .palette-btn.marked {
        background: #b7791f;
        border-color: #b7791f;
        color: #fff;
    }

    .palette-btn.not-answered {
        background: #c53030;
        border-color: #c53030;
        color: #fff;
    }

    .palette-btn.active {
        box-shadow: 0 0 0 2px #4299e1;
        border-color: #4299e1;
        z-index: 1;
        transform: scale(1.1);
    }

    .legend-item {
        display: flex;
        align-items: center;
        font-size: 0.85rem;
        color: #a0aec0;
        margin-bottom: 0.5rem;
    }

    .legend-box {
        width: 16px;
        height: 16px;
        margin-right: 0.75rem;
        border-radius: 4px;
    }

    .nav-buttons {
        display: flex;
        justify-content: space-between;
        margin-top: 2.5rem;
        padding-top: 1.5rem;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
    }

    /* Scrollbar styling */
    ::-webkit-scrollbar {
        width: 8px;
    }

    ::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.02);
    }

    ::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: rgba(255, 255, 255, 0.2);
    }
    </style>
</head>

<body>
    <!-- Test Header -->
    <div class="test-header">
        <div class="d-flex align-items-center">
            <h5 class="mb-0 fw-bold text-white">
                <i class="fas fa-clipboard-list me-2 text-primary"></i><?= htmlspecialchars($test['title']) ?>
            </h5>
        </div>
        <div class="d-flex align-items-center gap-3">
            <select id="langSelector" class="form-select form-select-sm bg-dark text-white border-secondary"
                style="width: auto;" onchange="switchLanguage(this.value)">
                <?php foreach ($availableLanguages as $code => $name): ?>
                <option value="<?= htmlspecialchars($code) ?>"><?= htmlspecialchars($name) ?></option>
                <?php endforeach; ?>
            </select>
            <div class="timer" id="timer">
                <i class="fas fa-clock me-2"></i>
                <span id="timeDisplay"><?= $durationMinutes ?>:00</span>
            </div>
            <button class="btn btn-primary" onclick="submitTest()">
                <i class="fas fa-paper-plane me-2"></i>Submit
            </button>
        </div>
    </div>

    <div class="test-body">
        <!-- Main Content -->
        <div class="main-content">
            <div id="questionContainer">
                <?php foreach ($questions as $index => $q): ?>
                <?php 
                // Decode options
                $opts = json_decode($q['options'] ?? '{}', true);
                if (empty($opts)) {
                    $opts = ['A' => 'Option A', 'B' => 'Option B', 'C' => 'Option C', 'D' => 'Option D'];
                }
                ?>
                <div class="question-card" id="question-<?= $index + 1 ?>"
                    style="display: <?= $index === 0 ? 'block' : 'none' ?>">
                    <div class="question-header">
                        <h5 class="mb-0 text-white-50">Question <?= $index + 1 ?> of <?= $totalQuestions ?></h5>
                        <button class="btn btn-sm btn-outline-warning" onclick="markForReview(<?= $index + 1 ?>)">
                            <i class="fas fa-bookmark me-1"></i>Mark for Review
                        </button>
                    </div>

                    <h5 class="mb-4 lh-base question-text" style="font-size: 1.1rem;">
                        <?= htmlspecialchars($q['question_text']) ?>
                    </h5>

                    <div class="options">
                        <?php foreach ($opts as $key => $value): ?>
                        <?php 
                        // Skip metadata keys
                        if ($key === 'i18n') continue;
                        
                        // Handle new option format (array with text/image) vs old format (string)
                        $optionText = is_array($value) ? ($value['text'] ?? '') : $value;
                        $optionImage = is_array($value) ? ($value['image'] ?? '') : '';
                        ?>
                        <button class="option-btn" data-key="<?= $key ?>"
                            onclick="selectOption(<?= $index + 1 ?>, '<?= $key ?>')">
                            <span class="option-marker"><?= $key ?></span>
                            <div class="option-content">
                                <span class="text-content"><?= htmlspecialchars($optionText) ?></span>
                                <?php if (!empty($optionImage)): ?>
                                <div class="mt-2 image-content">
                                    <img src="<?= htmlspecialchars($optionImage) ?>" alt="Option Image"
                                        class="img-fluid" style="max-height: 150px;">
                                </div>
                                <?php endif; ?>
                            </div>
                        </button>
                        <?php endforeach; ?>
                    </div>

                    <div class="nav-buttons">
                        <button class="btn btn-outline-secondary" onclick="clearResponse(<?= $index + 1 ?>)">
                            <i class="fas fa-eraser me-2"></i>Clear
                        </button>
                        <div class="d-flex gap-2">
                            <button class="btn btn-outline-light px-4" onclick="previousQuestion()"
                                <?= $index === 0 ? 'disabled' : '' ?>>
                                <i class="fas fa-chevron-left me-2"></i>Previous
                            </button>
                            <button class="btn btn-primary px-4" onclick="nextQuestion()"
                                <?= $index === $totalQuestions - 1 ? 'disabled' : '' ?>>
                                Next<i class="fas fa-chevron-right ms-2"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Question Palette -->
        <div class="question-palette">
            <div class="palette-header">
                <h6 class="mb-0 text-white"><i class="fas fa-th me-2"></i>Question Palette</h6>
            </div>

            <div class="palette-grid" id="palette">
                <?php for ($i = 1; $i <= $totalQuestions; $i++): ?>
                <button class="palette-btn <?= $i === 1 ? 'active' : '' ?>" id="palette-<?= $i ?>"
                    onclick="goToQuestion(<?= $i ?>)">
                    <?= $i ?>
                </button>
                <?php endfor; ?>
            </div>

            <div class="palette-footer">
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <div class="legend-item">
                            <div class="legend-box" style="background: #2f855a;"></div>
                            <span>Answered</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="legend-item">
                            <div class="legend-box" style="background: #b7791f;"></div>
                            <span>Marked</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="legend-item">
                            <div class="legend-box"
                                style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1);">
                            </div>
                            <span>Not Visited</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="legend-item">
                            <div class="legend-box" style="background: #c53030;"></div>
                            <span>Not Answered</span>
                        </div>
                    </div>
                </div>
                <div class="d-grid">
                    <button class="btn btn-success" onclick="submitTest()">
                        Submit Test
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // Prepare question data for JS
    const questionsData = <?php 
        $jsQuestions = [];
        foreach ($questions as $idx => $q) {
            $opts = json_decode($q['options'] ?? '{}', true);
            $jsQuestions[$idx + 1] = [
                'id' => $q['id'],
                'question_text' => $q['question_text'],
                'options' => $opts,
                'i18n' => $opts['i18n'] ?? null
            ];
        }
        echo json_encode($jsQuestions);
    ?>;

    let currentLanguage = 'en';

    function switchLanguage(lang) {
        currentLanguage = lang;
        // Update current question immediately
        renderQuestionLanguage(currentQuestion);
    }

    function renderQuestionLanguage(num) {
        const data = questionsData[num];
        if (!data) return;

        const container = document.getElementById(`question-${num}`);
        if (!container) return;

        // Determine text based on language
        let qText = data.question_text;
        let opts = data.options;

        if (currentLanguage !== 'en' && data.i18n && data.i18n[currentLanguage]) {
            const langData = data.i18n[currentLanguage];
            if (langData.question_text) {
                qText = langData.question_text;
            }
            // Merge options (override English with translated)
            // We need to be careful not to lose the structure if English was object and translation is string
            // But usually translation is just string text.
            // Let's create a merged options object
            opts = {
                ...opts
            }; // Copy original

            // Override keys present in langData
            for (const key in langData) {
                if (key === 'question_text') continue;

                const original = opts[key];
                const translation = langData[key];

                if (typeof original === 'object' && original !== null && typeof translation === 'string') {
                    // Preserve image, update text
                    opts[key] = {
                        ...original,
                        text: translation
                    };
                } else {
                    // Direct replacement
                    opts[key] = translation;
                }
            }
        }

        // Update Question Text
        const qTextEl = container.querySelector('.question-text');
        if (qTextEl) qTextEl.innerHTML = qText;

        // Update Options
        const buttons = container.querySelectorAll('.option-btn');
        buttons.forEach(btn => {
            const key = btn.getAttribute('data-key');
            if (opts[key]) {
                const optVal = opts[key];
                const textSpan = btn.querySelector('.text-content');
                const imgDiv = btn.querySelector('.image-content');

                let text = '';
                let image = '';

                if (typeof optVal === 'object' && optVal !== null) {
                    text = optVal.text || '';
                    image = optVal.image || '';
                } else {
                    text = optVal;
                }

                if (textSpan) textSpan.innerHTML = text;

                if (image) {
                    if (imgDiv) {
                        const img = imgDiv.querySelector('img');
                        if (img) img.src = image;
                        imgDiv.style.display = 'block';
                    }
                } else {
                    if (imgDiv) imgDiv.style.display = 'none';
                }
            }
        });
    }

    let currentQuestion = 1;
    const totalQuestions = <?= $totalQuestions ?>;
    const testDuration = <?= $durationMinutes ?> * 60; // in seconds
    let timeLeft = testDuration;
    let responses = {};
    let markedQuestions = new Set();
    let visitedQuestions = new Set([1]);

    // Timer
    const timerInterval = setInterval(() => {
        timeLeft--;

        const minutes = Math.floor(timeLeft / 60);
        const seconds = timeLeft % 60;
        document.getElementById('timeDisplay').textContent =
            `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;

        if (timeLeft <= 300) { // Last 5 minutes
            document.getElementById('timer').classList.add('warning');
        }

        if (timeLeft <= 0) {
            clearInterval(timerInterval);
            submitTest();
        }
    }, 1000);

    function updateQuestionStatus(num) {
        const paletteBtn = document.getElementById(`palette-${num}`);

        // Remove all status classes first (except active which is handled separately)
        paletteBtn.classList.remove('answered', 'marked', 'not-answered');

        if (markedQuestions.has(num)) {
            paletteBtn.classList.add('marked');
        } else if (responses[num]) {
            paletteBtn.classList.add('answered');
        } else if (visitedQuestions.has(num)) {
            paletteBtn.classList.add('not-answered');
        }
    }

    function goToQuestion(num) {
        // Update status of current question before leaving
        visitedQuestions.add(currentQuestion);
        updateQuestionStatus(currentQuestion);

        // Hide current
        document.getElementById(`question-${currentQuestion}`).style.display = 'none';
        document.getElementById(`palette-${currentQuestion}`).classList.remove('active');

        // Show new
        currentQuestion = num;
        renderQuestionLanguage(currentQuestion);
        document.getElementById(`question-${currentQuestion}`).style.display = 'block';
        document.getElementById(`palette-${currentQuestion}`).classList.add('active');

        // Mark new as visited
        visitedQuestions.add(currentQuestion);
        // We don't necessarily want to mark it red immediately upon entry, 
        // but if we want "Not Answered" to mean "Visited but not answered", 
        // it will become red when we leave it. 
        // However, if we want it to show red immediately (like "I am here and haven't answered yet"), 
        // we could call updateQuestionStatus(currentQuestion) here too.
        // Usually "Not Answered" is a state applied when you move AWAY without answering.
        // But let's stick to the logic: if visited and no answer -> red.
        // If I am ON the question, it is visited. So it should be red?
        // Let's leave it as "active" (blue border) and only apply red when we leave or update status.
        // Actually, let's apply updateStatus to the new question too, so if I revisit a red one, it stays red (plus active border).
        updateQuestionStatus(currentQuestion);
    }

    function nextQuestion() {
        if (currentQuestion < totalQuestions) {
            goToQuestion(currentQuestion + 1);
        }
    }

    function previousQuestion() {
        if (currentQuestion > 1) {
            goToQuestion(currentQuestion - 1);
        }
    }

    function selectOption(questionNum, option) {
        // Clear previous selection
        const buttons = document.querySelectorAll(`#question-${questionNum} .option-btn`);
        buttons.forEach(btn => btn.classList.remove('selected'));

        // Select new option
        event.target.closest('.option-btn').classList.add('selected');

        // Store response
        responses[questionNum] = option;
        visitedQuestions.add(questionNum);

        // Update palette
        markedQuestions.delete(questionNum);
        updateQuestionStatus(questionNum);
    }

    function markForReview(questionNum) {
        markedQuestions.add(questionNum);
        visitedQuestions.add(questionNum);
        updateQuestionStatus(questionNum);
    }

    function clearResponse(questionNum) {
        // Clear visual selection
        const buttons = document.querySelectorAll(`#question-${questionNum} .option-btn`);
        buttons.forEach(btn => btn.classList.remove('selected'));

        // Clear response
        delete responses[questionNum];

        // Update palette
        updateQuestionStatus(questionNum);
    }

    function submitTest() {
        const answered = Object.keys(responses).length;
        const unanswered = totalQuestions - answered;
        const marked = markedQuestions.size;

        if (confirm(`Submit Test?\n\nAnswered: ${answered}\nUnanswered: ${unanswered}\nMarked for Review: ${marked}`)) {
            clearInterval(timerInterval);

            // Create form to submit data
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = 'index.php?page=submit_test';

            const responsesInput = document.createElement('input');
            responsesInput.type = 'hidden';
            responsesInput.name = 'responses';
            responsesInput.value = JSON.stringify(responses);
            form.appendChild(responsesInput);

            const testIdInput = document.createElement('input');
            testIdInput.type = 'hidden';
            testIdInput.name = 'test_id';
            testIdInput.value = '<?= $testId ?>';
            form.appendChild(testIdInput);

            const timeInput = document.createElement('input');
            timeInput.type = 'hidden';
            timeInput.name = 'time_taken';
            timeInput.value = testDuration - timeLeft;
            form.appendChild(timeInput);

            document.body.appendChild(form);
            form.submit();
        }
    }

    // Disable right-click and F12 for test security
    <?php if ($mode === 'exam'): ?>
    document.addEventListener('contextmenu', e => e.preventDefault());
    document.addEventListener('keydown', e => {
        if (e.key === 'F12' || (e.ctrlKey && e.shiftKey && e.key === 'I')) {
            e.preventDefault();
        }
    });

    // Detect tab switch
    document.addEventListener('visibilitychange', () => {
        if (document.hidden) {
            alert('⚠️ Warning: Switching tabs during the test is not allowed!');
            // TODO: Log suspicious activity
        }
    });
    <?php endif; ?>
    </script>
</body>

</html>