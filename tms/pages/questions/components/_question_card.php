<?php
// Renders a single question card. Expects $question in scope.
?>
<div class="col-12 mb-3">
    <div class="question-card card <?= htmlspecialchars($question['difficulty']) ?>">
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <div class="d-flex align-items-start mb-2">
                        <div class="me-3">
                            <span class="badge bg-primary"><?= htmlspecialchars($question['subject']) ?></span>
                            <span class="badge bg-secondary"><?= htmlspecialchars($question['topic']) ?></span>
                            <span
                                class="badge bg-<?= $question['difficulty'] === 'easy' ? 'success' : ($question['difficulty'] === 'medium' ? 'warning' : 'danger') ?>">
                                <?= ucfirst($question['difficulty']) ?>
                            </span>
                        </div>
                    </div>

                    <h6 class="question-text">
                        <?= nl2br(htmlspecialchars($question['question_text'])) ?>
                    </h6>

                    <?php
                    // Display question image if exists
                    if (!empty($question['image'])): ?>
                        <div class="question-image mb-2">
                            <img src="<?= htmlspecialchars($question['image']) ?>" class="img-fluid rounded"
                                style="max-width: 300px; max-height: 200px;" alt="Question Image">
                        </div>
                    <?php endif; ?>

                    <?php
                    // Display question image from options if exists
                    $options = json_decode($question['options'], true);
                    if (is_array($options) && !empty($options['image'])): ?>
                        <div class="question-image mb-2">
                            <img src="<?= htmlspecialchars($options['image']) ?>" class="img-fluid rounded"
                                style="max-width: 300px; max-height: 200px;" alt="Question Image">
                        </div>
                    <?php endif; ?>

                    <div class="question-meta mt-2">
                        <small class="text-muted">
                            <i class="fas fa-calendar me-1"></i><?= htmlspecialchars($question['exam_year']) ?>
                            <i class="fas fa-building ms-3 me-1"></i><?= htmlspecialchars($question['source']) ?>
                            <?php if ($question['is_public']): ?>
                                <i class="fas fa-globe ms-3 me-1"></i>Public
                            <?php endif; ?>
                        </small>
                    </div>
                </div>

                <div class="col-md-4 text-end">
                    <div class="d-flex justify-content-end align-items-center gap-2">

                        <!-- Selection Checkbox -->
                        <div class="form-check mb-0">
                            <label class="form-check-label" for="select_<?= $question['id'] ?>">Select</label>
                            <input class="form-check-input" type="checkbox" value="<?= $question['id'] ?>"
                                id="select_<?= $question['id'] ?>">
                        </div>

                        <!-- Preview / View Details -->
                        <?php
                        // Normalize options into an array/object so JS always receives valid JSON.
                        $optsArr = [];
                        if (isset($question['options'])) {
                            if (is_string($question['options'])) {
                                $decoded = json_decode($question['options'], true);
                                if ($decoded !== null && (is_array($decoded) || is_object($decoded))) {
                                    $optsArr = $decoded;
                                } else {
                                    // Try common double-encoded case or escaped strings
                                    $maybe = stripslashes($question['options']);
                                    $decoded2 = json_decode($maybe, true);
                                    if ($decoded2 !== null && (is_array($decoded2) || is_object($decoded2))) {
                                        $optsArr = $decoded2;
                                    } else {
                                        // final fallback: keep empty array so preview JS gets {}
                                        $optsArr = [];
                                    }
                                }
                            } elseif (is_array($question['options']) || is_object($question['options'])) {
                                $optsArr = $question['options'];
                            }
                        }
                        $optsJson = htmlspecialchars(json_encode($optsArr, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE), ENT_QUOTES);
                        ?>
                        <button class="btn btn-sm btn-outline-success view-details-btn" title="View Details"
                            data-id="<?= $question['id'] ?>"
                            data-subject="<?= htmlspecialchars($question['subject']) ?>"
                            data-topic="<?= htmlspecialchars($question['topic']) ?>"
                            data-subtopic="<?= htmlspecialchars($question['subtopic']) ?>"
                            data-question_text="<?= htmlspecialchars($question['question_text']) ?>"
                            data-options="<?= $optsJson ?>"
                            data-correct_answer="<?= htmlspecialchars($question['correct_answer']) ?>"
                            data-explanation="<?= htmlspecialchars($question['explanation']) ?>"
                            data-difficulty="<?= htmlspecialchars($question['difficulty']) ?>"
                            data-exam_year="<?= htmlspecialchars($question['exam_year']) ?>"
                            data-source="<?= htmlspecialchars($question['source']) ?>"
                            data-is_public="<?= $question['is_public'] ? 'Yes' : 'No' ?>">
                            <i class="fas fa-eye"></i>
                        </button>

                        <!-- Edit -->
                        <button class="btn btn-sm btn-outline-primary" title="Edit"
                            onclick="window.location.href='index.php?page=questions&action=edit&id=<?= $question['id'] ?>'">
                            <i class="fas fa-edit"></i>
                        </button>

                        <!-- Add to Test -->
                        <button class="btn btn-sm btn-outline-info add-to-test-btn" title="Add to Test Pack"
                            data-question-id="<?= $question['id'] ?>"
                            data-question-label="<?= htmlspecialchars($question['question_text']) ?>"
                            data-bs-toggle="modal" data-bs-target="#addToTestModal">
                            <i class="fas fa-plus"></i>
                        </button>

                        <!-- Delete -->
                        <button class="btn btn-sm btn-outline-danger" title="Delete"
                            onclick="if(confirm('Are you sure?')) { window.location.href='index.php?page=questions&action=delete&id=<?= $question['id'] ?>' }">
                            <i class="fas fa-trash"></i>
                        </button>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>