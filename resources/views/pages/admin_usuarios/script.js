/**
 * Admin Usuários - Script.js
 * Gerencia interações HTMX, Alpine.js e validações de formulário
 * 
 * Features:
 * - Filtros dinâmicos com HTMX
 * - Modals de confirmação com Alpine.js
 * - Validação de formulários em tempo real
 * - Toasts de notificação
 * - Loading indicators
 */

// ===== ALPINE.JS DATA FUNCTIONS =====

/**
 * Filter Form Component (Alpine)
 * Gerencia estado dos filtros e requisições HTMX
 */
function filterForm() {
    return {
        search: '',
        role: '',
        status: '',
        sort: 'created_at',
        loading: false,

        /**
         * Submete filtros via HTMX
         */
        submitFilters() {
            this.loading = true;
            // HTMX handles the request, Alpine just manages loading state
            document.body.addEventListener('htmx:afterSwap', () => {
                this.loading = false;
            });
        },

        /**
         * Reseta todos os filtros
         */
        resetFilters() {
            this.search = '';
            this.role = '';
            this.status = '';
            this.sort = 'created_at';
            // Trigger form submission para resetar via HTMX
            document.querySelector('form').submit();
        }
    };
}

// ===== HTMX EVENT LISTENERS =====

document.addEventListener('DOMContentLoaded', function() {
    console.log('✅ Admin Usuários - Script inicializado');

    // Feedback visual no envio de requisições
    document.addEventListener('htmx:beforeRequest', function(evt) {
        showToast('⏳ Carregando dados...', 'info');
    });

    // Feedback ao completar requisição
    document.addEventListener('htmx:afterSwap', function(evt) {
        if (evt.detail.xhr.status === 200) {
            showToast('✅ Dados atualizados com sucesso', 'success');
        }
    });

    // Tratamento de erros HTMX
    document.addEventListener('htmx:responseError', function(evt) {
        showToast('❌ Erro ao carregar dados. Tente novamente.', 'error');
        console.error('HTMX Error:', evt.detail);
    });

    // Form submission handlers
    setupFormHandlers();

    // Table interactions
    setupTableInteractions();
});

// ===== FORM HANDLERS =====

/**
 * Setup de handlers para formulários (criar/editar usuário)
 */
function setupFormHandlers() {
    const forms = document.querySelectorAll('form[hx-post], form[hx-put], form[hx-patch]');

    forms.forEach(form => {
        // Validate antes de enviar
        form.addEventListener('htmx:beforeRequest', function(evt) {
            if (!validateForm(form)) {
                evt.preventDefault();
                showToast('❌ Preencha todos os campos obrigatórios', 'error');
            }
        });

        // Success feedback
        form.addEventListener('htmx:afterRequest', function(evt) {
            if (evt.detail.xhr.status === 201 || evt.detail.xhr.status === 200) {
                showToast('✅ Operação realizada com sucesso!', 'success');
                // Could redirect after delay
                setTimeout(() => {
                    window.location.href = '/admin/usuarios';
                }, 1500);
            }
        });
    });
}

/**
 * Setup de interações na tabela (delete, toggle, etc)
 */
function setupTableInteractions() {
    // Delete buttons com confirmatição via Alpine
    const deleteButtons = document.querySelectorAll('[data-action="delete"]');
    deleteButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            // Alpine.js modal será acionado via @click
        });
    });
}

// ===== VALIDATION FUNCTIONS =====

/**
 * Valida formulário básico
 * @param {HTMLFormElement} form 
 * @returns {boolean}
 */
function validateForm(form) {
    const requiredFields = form.querySelectorAll('[required]');
    let isValid = true;

    requiredFields.forEach(field => {
        if (!field.value || field.value.trim() === '') {
            isValid = false;
            field.classList.add('border-red-500');
            field.classList.remove('border-gray-300');
        } else {
            field.classList.remove('border-red-500');
            field.classList.add('border-gray-300');
        }
    });

    return isValid;
}

/**
 * Valida email em tempo real
 * @param {string} email 
 * @returns {boolean}
 */
function validateEmail(email) {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);
}

/**
 * Valida senha (mínimo 8 caracteres, 1 maiúscula, 1 número)
 * @param {string} password 
 * @returns {boolean}
 */
function validatePassword(password) {
    if (password.length < 8) return false;
    if (!/[A-Z]/.test(password)) return false;
    if (!/\d/.test(password)) return false;
    return true;
}

// ===== NOTIFICATION SYSTEM =====

/**
 * Toast notifications
 * @param {string} message 
 * @param {string} type - 'success', 'error', 'info', 'warning'
 * @param {number} duration - milliseconds
 */
function showToast(message, type = 'info', duration = 3000) {
    // Create toast element if not exists
    let container = document.getElementById('toast-container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'toast-container';
        container.className = 'fixed top-4 right-4 z-50 space-y-2';
        document.body.appendChild(container);
    }

    // Toast element
    const toast = document.createElement('div');
    const bgColors = {
        success: 'bg-green-500',
        error: 'bg-red-500',
        warning: 'bg-yellow-500',
        info: 'bg-blue-500'
    };

    toast.className = `${bgColors[type]} text-white px-4 py-3 rounded-lg shadow-lg animate-pulse`;
    toast.textContent = message;
    container.appendChild(toast);

    // Auto remove
    setTimeout(() => {
        toast.remove();
    }, duration);
}

/**
 * Confirmation modal (Alpine style)
 * @param {string} title 
 * @param {string} message 
 * @param {function} onConfirm 
 */
function showConfirmation(title, message, onConfirm) {
    // This is handled by Alpine.js inline in the view
    // Left here as reference for non-Alpine-managed components
    if (confirm(`${title}\n\n${message}`)) {
        onConfirm();
    }
}

// ===== UTILITY FUNCTIONS =====

/**
 * Export data para CSV
 */
function exportToCSV() {
    const table = document.querySelector('table');
    if (!table) return;

    let csv = [];
    // Headers
    const headers = table.querySelectorAll('th');
    csv.push(Array.from(headers).map(h => h.textContent).join(','));

    // Rows
    const rows = table.querySelectorAll('tbody tr');
    rows.forEach(row => {
        const cells = row.querySelectorAll('td');
        csv.push(Array.from(cells).map(c => c.textContent).join(','));
    });

    // Download
    const csvContent = csv.join('\n');
    const blob = new Blob([csvContent], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const link = document.createElement('a');
    link.href = url;
    link.download = `usuarios-${new Date().toISOString().split('T')[0]}.csv`;
    link.click();
    window.URL.revokeObjectURL(url);
}

/**
 * Copia texto para clipboard
 * @param {string} text 
 */
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        showToast('✅ Copiado para área de transferência', 'success', 2000);
    }).catch(() => {
        showToast('❌ Erro ao copiar', 'error');
    });
}

// ===== DEBOUNCE UTILITY =====

/**
 * Debounce function para throttle requests
 * @param {function} func 
 * @param {number} wait 
 * @returns {function}
 */
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
