// Main JavaScript functionality
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Add fade-in animation to cards
    const cards = document.querySelectorAll('.card');
    cards.forEach((card, index) => {
        card.style.animationDelay = `${index * 0.1}s`;
        card.classList.add('fade-in');
    });

    // Stats counter animation
    const statsNumbers = document.querySelectorAll('.stats-number');
    statsNumbers.forEach(stat => {
        const target = parseInt(stat.textContent);
        let current = 0;
        const increment = target / 50;
        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                current = target;
                clearInterval(timer);
            }
            stat.textContent = Math.floor(current);
        }, 30);
    });

    // Enhanced form validation
    const forms = document.querySelectorAll('.needs-validation');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!form.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
            }
            form.classList.add('was-validated');
        });
    });

    // Auto-save functionality for forms
    const autoSaveForms = document.querySelectorAll('[data-autosave]');
    autoSaveForms.forEach(form => {
        const inputs = form.querySelectorAll('input, textarea, select');
        inputs.forEach(input => {
            input.addEventListener('input', debounce(() => {
                // Save to localStorage
                const formData = new FormData(form);
                const data = Object.fromEntries(formData);
                localStorage.setItem(`autosave_${form.id}`, JSON.stringify(data));
                showToast('Draft saved', 'success');
            }, 1000));
        });
    });

    // Search functionality
    const searchInputs = document.querySelectorAll('[data-search]');
    searchInputs.forEach(input => {
        input.addEventListener('input', debounce((e) => {
            const searchTerm = e.target.value.toLowerCase();
            const targetSelector = e.target.dataset.search;
            const items = document.querySelectorAll(targetSelector);
            
            items.forEach(item => {
                const text = item.textContent.toLowerCase();
                item.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        }, 300));
    });
});

// Utility Functions
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

function showToast(message, type = 'info') {
    // Create toast container if it doesn't exist
    let toastContainer = document.getElementById('toast-container');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.id = 'toast-container';
        toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
        toastContainer.style.zIndex = '9999';
        document.body.appendChild(toastContainer);
    }

    // Create toast element
    const toastEl = document.createElement('div');
    toastEl.className = `toast align-items-center text-bg-${type} border-0`;
    toastEl.setAttribute('role', 'alert');
    toastEl.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">${message}</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    `;

    toastContainer.appendChild(toastEl);
    const toast = new bootstrap.Toast(toastEl);
    toast.show();

    // Remove toast element after it's hidden
    toastEl.addEventListener('hidden.bs.toast', () => {
        toastEl.remove();
    });
}

// Test Builder Functions
function addQuestionToTest(questionId, questionText) {
    const selectedQuestions = document.getElementById('selected-questions');
    if (!selectedQuestions) return;

    const questionEl = document.createElement('div');
    questionEl.className = 'selected-question-item mb-2 p-3 bg-light rounded';
    questionEl.innerHTML = `
        <div class="d-flex justify-content-between align-items-start">
            <div class="question-text">${questionText}</div>
            <button class="btn btn-sm btn-outline-danger" onclick="removeQuestion(this)">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <input type="hidden" name="questions[]" value="${questionId}">
    `;
    
    selectedQuestions.appendChild(questionEl);
    updateQuestionCount();
}

function removeQuestion(button) {
    button.closest('.selected-question-item').remove();
    updateQuestionCount();
}

function updateQuestionCount() {
    const count = document.querySelectorAll('.selected-question-item').length;
    const countEl = document.getElementById('question-count');
    if (countEl) {
        countEl.textContent = count;
    }
}

// Analytics Chart Functions
function initializeCharts() {
    // Subject Distribution Chart
    const subjectCtx = document.getElementById('subjectChart');
    if (subjectCtx) {
        new Chart(subjectCtx, {
            type: 'doughnut',
            data: {
                labels: ['Mathematics', 'Science', 'English', 'History', 'Geography'],
                datasets: [{
                    data: [30, 25, 20, 15, 10],
                    backgroundColor: [
                        '#0066CC',
                        '#17A2B8',
                        '#FF6B35',
                        '#28A745',
                        '#FFC107'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            font: {
                                size: 12
                            }
                        }
                    }
                }
            }
        });
    }

    // Performance Trend Chart
    const trendCtx = document.getElementById('trendChart');
    if (trendCtx) {
        new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Test Scores',
                    data: [65, 70, 68, 75, 72, 78],
                    borderColor: '#0066CC',
                    backgroundColor: 'rgba(0, 102, 204, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100
                    }
                }
            }
        });
    }
}

// Initialize charts when the page loads
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(initializeCharts, 500);
});

// AJAX Helper Functions
async function makeRequest(url, options = {}) {
    try {
        const response = await fetch(url, {
            headers: {
                'Content-Type': 'application/json',
                ...options.headers
            },
            ...options
        });
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        return await response.json();
    } catch (error) {
        console.error('Request failed:', error);
        showToast('Request failed. Please try again.', 'danger');
        throw error;
    }
}

// Export functions for global use
window.TMS = {
    showToast,
    makeRequest,
    addQuestionToTest,
    removeQuestion,
    updateQuestionCount
};