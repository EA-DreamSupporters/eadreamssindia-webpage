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
            
            // Store question data globally and use the language-aware preview system
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
            
            // Render using the global function (language buttons will be created by renderPreviewContent)
            if (typeof renderPreviewContent === 'function') {
                renderPreviewContent('en');
                
                const modalEl = document.getElementById('questionPreviewModal');
                if (modalEl) {
                    const modal = new bootstrap.Modal(modalEl);
                    modal.show();
                }
            }
        });

    });
})();
