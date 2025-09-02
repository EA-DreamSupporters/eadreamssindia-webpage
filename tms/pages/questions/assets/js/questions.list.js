// Questions list page JavaScript: handles selection, bulk actions, and preview modal behavior
(function () {
    'use strict';

    function getSelectedQuestionIds() {
        return Array.from(document.querySelectorAll('input[type="checkbox"][id^="select_"]:checked')).map(cb => cb.value);
    }

    function updateSelectedCount() {
        const count = getSelectedQuestionIds().length;
        const el = document.getElementById('selected-count');
        if (el) el.textContent = count;
        const totalCheckboxes = document.querySelectorAll('.form-check-input[type="checkbox"]:not(#select-all)').length;
        const selectAllCheckbox = document.getElementById('select-all');
        if (!selectAllCheckbox) return;
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

        const bulkCountEl = document.getElementById('bulk-selected-count');
        if (bulkCountEl) bulkCountEl.textContent = count;
    }

    document.addEventListener('DOMContentLoaded', function () {
        // Select all checkbox
        const selectAll = document.getElementById('select-all');
        if (selectAll) {
            selectAll.addEventListener('change', function () {
                const checked = this.checked;
                document.querySelectorAll('.form-check-input[type="checkbox"]:not(#select-all)').forEach(cb => cb.checked = checked);
                updateSelectedCount();
            });
        }

        document.querySelectorAll('.form-check-input[type="checkbox"]:not(#select-all)').forEach(cb => cb.addEventListener('change', updateSelectedCount));

        // Bulk duplicate
        const bulkDup = document.getElementById('bulk-duplicate-btn');
        if (bulkDup) {
            bulkDup.addEventListener('click', function () {
                const ids = getSelectedQuestionIds();
                if (ids.length === 0) return alert('Please select at least one question to duplicate.');
                if (!confirm('Duplicate selected questions?')) return;
                const btn = this;
                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Duplicating...';
                fetch('index.php?page=questions&action=bulk_duplicate', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ question_ids: ids })
                }).then(res => res.json()).then(data => {
                    if (data.success) {
                        window.location.href = 'index.php?page=questions&success=bulk_duplicated&message=' + encodeURIComponent(data.message || 'Questions duplicated successfully!');
                        return;
                    }
                    alert('Error: ' + (data.error || 'Could not duplicate.'));
                }).catch(err => {
                    console.error('Bulk duplicate error:', err);
                    alert('Network error: ' + err.message);
                }).finally(() => {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-copy me-2"></i>Duplicate Selected';
                });
            });
        }

        // Bulk delete
        const bulkDelete = document.getElementById('bulk-delete-btn');
        if (bulkDelete) {
            bulkDelete.addEventListener('click', function () {
                const ids = getSelectedQuestionIds();
                if (ids.length === 0) return alert('Please select at least one question to delete.');
                if (!confirm('Are you sure you want to delete selected questions?')) return;
                const btn = this;
                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Deleting...';
                fetch('index.php?page=questions&action=bulk_delete', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ question_ids: ids })
                }).then(res => res.json()).then(data => {
                    if (data.success) {
                        window.location.href = 'index.php?page=questions&success=bulk_deleted&message=' + encodeURIComponent(data.message || 'Questions deleted successfully!');
                        return;
                    }
                    alert('Error: ' + (data.error || 'Could not delete.'));
                }).catch(err => {
                    console.error('Error:', err);
                    alert('Network error: ' + err.message);
                }).finally(() => {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-trash me-2"></i>Delete Selected';
                });
            });
        }

        // View details / preview modal handled by delegated listener
        document.body.addEventListener('click', function (e) {
            const btn = e.target.closest('.view-details-btn');
            if (!btn) return;
            e.preventDefault();
            const data = {
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

            // Render preview content
            const contentEl = document.getElementById('preview-question-content');
            if (!contentEl) return;
            let html = '';
            html += `<div><span class='badge bg-primary'>${data.subject}</span> <span class='badge bg-secondary'>${data.topic}</span> <span class='badge bg-${data.difficulty === 'easy' ? 'success' : (data.difficulty === 'medium' ? 'warning' : 'danger')}'>${data.difficulty.charAt(0).toUpperCase() + data.difficulty.slice(1)}</span></div>`;
            html += `<h5 class='mt-3'>${data.question_text}</h5>`;
            try {
                const opts = JSON.parse(data.options || '{}');
                if (opts.image) {
                    html += `<div class="mb-2"><img src="${opts.image}" style="max-width:100%; height:auto;"></div>`;
                }
                if (opts.audio) {
                    html += `<div class="mb-2"><audio controls src="${opts.audio}"></audio></div>`;
                }
            } catch (e) { }
            html += renderOptions(data.options);
            html += `<div class='mb-2'><strong>Correct Answer:</strong> ${data.correct_answer}</div>`;
            html += `<div class='mb-2'><strong>Explanation:</strong> ${data.explanation || '-'}</div>`;
            html += `<div class='mb-2'><strong>Exam Year:</strong> ${data.exam_year} <strong>Source:</strong> ${data.source}</div>`;
            html += `<div class='mb-2'><strong>Subtopic:</strong> ${data.subtopic || '-'}</div>`;
            html += `<div class='mb-2'><strong>Public:</strong> ${data.is_public}</div>`;
            contentEl.innerHTML = html;
            const modalEl = document.getElementById('questionPreviewModal');
            if (modalEl) {
                const modal = new bootstrap.Modal(modalEl);
                modal.show();
            } else {
                alert(data.question_text);
            }
        });

        // Options render helper used by preview
        function renderOptions(optionsJson) {
            const escapeHtml = s => String(s == null ? '' : s)
                .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');

            let html = '';
            try {
                const options = (typeof optionsJson === 'object') ? optionsJson : JSON.parse(optionsJson || '{}');
                const keys = ['A', 'B', 'C', 'D', 'E'];
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
                            const parts = (typeof val === 'string' ? val : (Array.isArray(val) ? val.join(' ') : String(val))).trim().split(/\s+/);
                            html += `<tr><th style="width:60px">${k}</th>`;
                            for (let i = 0; i < cols; i++) html += `<td>${escapeHtml(parts[i] || '')}</td>`;
                            html += '</tr>';
                        }
                    }
                    html += '</tbody></table>';
                    return html;
                }
                html += '<ul class="list-group mb-2">';
                for (const key of keys) {
                    const raw = options[key];
                    const namedImage = options[`${key}_image`] || options[`${key} _image`] || options[`${key}_img`];
                    if (raw || namedImage) {
                        let text = '';
                        let imagePath = '';
                        if (raw && typeof raw === 'object') {
                            if (raw.text) text = raw.text;
                            else if (raw.i18n && raw.i18n.en) text = raw.i18n.en;
                            else {
                                for (const p in raw) if (typeof raw[p] === 'string') { text = raw[p]; break; }
                            }
                            if (raw.image) imagePath = raw.image;
                        } else if (Array.isArray(raw)) {
                            text = raw.join(' ');
                        } else {
                            text = raw != null ? String(raw) : '';
                        }
                        if (!imagePath) imagePath = namedImage || '';
                        let content = '';
                        if (imagePath) content += `<div><img src="${escapeHtml(imagePath)}" style="max-width:200px; max-height:120px;" alt="${key}"></div>`;
                        content += escapeHtml(text);
                        html += `<li class="list-group-item"><strong>${key}.</strong> ${content}</li>`;
                    }
                }
                html += '</ul>';
            } catch (e) { }
            return html;
        }

    });
})();
