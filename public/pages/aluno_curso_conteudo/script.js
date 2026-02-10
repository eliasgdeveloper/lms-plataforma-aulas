document.addEventListener('DOMContentLoaded', function () {
    const preview = document.getElementById('content-preview');
    const title = document.getElementById('content-title');
    const description = document.getElementById('content-description');
    const items = Array.from(document.querySelectorAll('[data-content-id]'));
    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    const progressBar = document.getElementById('overall-progress-bar');
    const progressText = document.getElementById('overall-progress-text');
    const progressMeta = document.getElementById('overall-progress-meta');
    const progressMetric = document.getElementById('overall-progress-metric');
    const pointsEl = document.getElementById('overall-points');
    const completedCountEl = document.getElementById('overall-completed-count');
    const totalCountEl = document.getElementById('overall-total-count');
    const completionThreshold = 90;
    const lastSent = new Map();
    let activeButton = null;
    let noticeEl = null;

    function escapeHtml(value) {
        return String(value || '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;');
    }

    function renderPreview(data) {
        title.textContent = data.title || 'Conteudo';
        description.textContent = data.description || '';

        let html = '';
        const type = (data.type || '').toLowerCase();
        const allowDownload = data.allowDownload === true;
        const fileUrl = data.fileUrl || '';
        const url = data.url || '';
        const safeFileUrl = escapeHtml(fileUrl);
        const safeUrl = escapeHtml(url);
        const openUrl = fileUrl || url;
        const safeOpenUrl = escapeHtml(openUrl);

        let actions = '';
        if (openUrl) {
            actions += '<a class="btn btn-primary" href="' + safeOpenUrl + '" target="_blank" rel="noopener">Abrir arquivo</a>';
        }
        if (type === 'excel' && openUrl) {
            const googleUrl = 'https://docs.google.com/gview?embedded=1&url=' + encodeURIComponent(openUrl);
            actions += '<a class="btn" href="' + googleUrl + '" target="_blank" rel="noopener">Abrir no Google Sheets</a>';
        }
        if (allowDownload && openUrl) {
            actions += '<a class="btn" href="' + safeOpenUrl + '" download>Baixar arquivo</a>';
        }

        if (type === 'video') {
            if (fileUrl) {
                html = '<video controls src="' + safeFileUrl + '"></video>';
            } else if (url) {
                html = '<iframe src="' + safeUrl + '" allowfullscreen></iframe>';
            }
        } else if (type === 'pdf') {
            const pdfUrl = fileUrl || url;
            const safePdfUrl = escapeHtml(pdfUrl);
            if (pdfUrl) {
                html = '<iframe src="' + safePdfUrl + '"></iframe>';
            }
        } else if (type === 'link') {
            if (url) {
                html = '<div class="content-placeholder"><a href="' + safeUrl + '" target="_blank" rel="noopener">Abrir link externo</a></div>';
            }
        } else if (type === 'texto') {
            html = '<div class="content-placeholder">' + escapeHtml(url || 'Conteudo sem texto') + '</div>';
        } else if (type === 'arquivo' || type === 'word' || type === 'excel' || type === 'quiz' || type === 'prova' || type === 'tarefa') {
            html = '<div class="content-placeholder">Este leitor nao permite ler este arquivo aqui, utilize outros programas para abrir.</div>';
            if (actions) {
                html += '<div class="content-actions">' + actions + '</div>';
            }
        } else {
            html = '<div class="content-placeholder">Este leitor nao permite ler este arquivo aqui, utilize outros programas para abrir.</div>';
            if (actions) {
                html += '<div class="content-actions">' + actions + '</div>';
            }
        }

        if (!html) {
            html = '<div class="content-placeholder">Este leitor nao permite ler este arquivo aqui, utilize outros programas para abrir.</div>';
            if (actions) {
                html += '<div class="content-actions">' + actions + '</div>';
            }
        }

        preview.innerHTML = html;
    }

    function ensureNotice() {
        if (noticeEl) {
            return noticeEl;
        }

        noticeEl = document.createElement('div');
        noticeEl.setAttribute('role', 'status');
        noticeEl.style.position = 'fixed';
        noticeEl.style.right = '16px';
        noticeEl.style.bottom = '16px';
        noticeEl.style.padding = '10px 14px';
        noticeEl.style.borderRadius = '10px';
        noticeEl.style.boxShadow = '0 12px 30px rgba(0,0,0,0.18)';
        noticeEl.style.fontSize = '13px';
        noticeEl.style.fontWeight = '600';
        noticeEl.style.zIndex = '9999';
        noticeEl.style.opacity = '0';
        noticeEl.style.transition = 'opacity 150ms ease';
        document.body.appendChild(noticeEl);
        return noticeEl;
    }

    function showSaveNotice(message, type) {
        const el = ensureNotice();
        const background = type === 'error'
            ? '#c62828'
            : type === 'success'
                ? '#2e7d32'
                : '#1565c0';

        el.textContent = message;
        el.style.background = background;
        el.style.color = '#ffffff';
        el.style.opacity = '1';

        window.clearTimeout(el._hideTimer);
        el._hideTimer = window.setTimeout(function () {
            el.style.opacity = '0';
        }, 3000);
    }

    function updateProgress(contentId, progress, options) {
        if (!csrf) {
            showSaveNotice('Token CSRF nao encontrado.', 'error');
            return;
        }

        if (!contentId) {
            return;
        }

        const settings = options || {};
        const shouldNotify = settings.notice === true;

        if (shouldNotify) {
            showSaveNotice('Salvando progresso: ' + progress + '%', 'info');
        }

        fetch('/aluno/conteudos/' + contentId + '/progress', {
            method: 'POST',
            credentials: 'same-origin',
            keepalive: settings.keepalive === true,
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrf,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ progress: progress })
        }).then(function (response) {
            if (!response.ok) {
                return response.text().then(function (text) {
                    showSaveNotice('Erro ao salvar progresso (' + response.status + ').', 'error');
                    console.error('Erro ao salvar progresso', response.status, text);
                });
            }

            return response.json().then(function (data) {
                if (shouldNotify) {
                    showSaveNotice('Progresso salvo: ' + data.progress + '%', 'success');
                }
            }).catch(function () {
                if (shouldNotify) {
                    showSaveNotice('Progresso salvo.', 'success');
                }
            });
        }).catch(function (error) {
            showSaveNotice('Falha ao salvar progresso.', 'error');
            console.error('Falha ao salvar progresso', error);
        });
    }

    function sendProgressBeacon(contentId, progress) {
        if (!csrf || !contentId || progress < 0) {
            return;
        }

        const url = '/aluno/conteudos/' + contentId + '/progress';
        const payload = new URLSearchParams({
            _token: csrf,
            progress: String(progress)
        });

        if (navigator.sendBeacon) {
            navigator.sendBeacon(url, payload);
            return;
        }

        updateProgress(contentId, progress, { keepalive: true });
    }

    function getProgressValue(button) {
        return parseInt(button.getAttribute('data-progress') || '0', 10);
    }

    function setProgressValue(button, progress) {
        const clamped = Math.max(0, Math.min(100, progress));
        button.setAttribute('data-progress', String(clamped));
        const status = button.querySelector('.item__status');
        if (status) {
            status.textContent = clamped + '%';
        }
    }

    function recalcOverall() {
        if (items.length === 0) {
            return;
        }

        const total = items.length;
        let sum = 0;
        let completed = 0;
        let points = 0;

        items.forEach(function (button) {
            const progress = getProgressValue(button);
            sum += progress;
            points += Math.round((progress / 100) * 10);
            if (progress >= completionThreshold) {
                completed += 1;
            }
        });

        const overall = Math.round(sum / total);
        if (progressBar) {
            progressBar.style.width = overall + '%';
        }
        if (progressText) {
            progressText.textContent = overall;
        }
        if (progressMetric) {
            progressMetric.textContent = overall + '%';
        }
        if (pointsEl) {
            pointsEl.textContent = points;
        }
        if (completedCountEl) {
            completedCountEl.textContent = completed;
        }
        if (totalCountEl) {
            totalCountEl.textContent = total;
        }
        if (progressMeta) {
            progressMeta.dataset.progress = overall;
        }
    }

    function recalcModuleProgress(moduleId) {
        const moduleItems = items.filter(function (button) {
            return button.getAttribute('data-module-id') === moduleId;
        });
        if (moduleItems.length === 0) {
            return;
        }

        const sum = moduleItems.reduce(function (carry, button) {
            return carry + getProgressValue(button);
        }, 0);
        const progress = Math.round(sum / moduleItems.length);
        const progressEl = document.querySelector('[data-module-progress="' + moduleId + '"]');
        if (progressEl) {
            progressEl.textContent = progress + '%';
        }
    }

    function sendProgressIfNeeded(contentId, progress) {
        const last = lastSent.get(contentId) || -1;
        if (progress === 100 || progress - last >= 5) {
            lastSent.set(contentId, progress);
            updateProgress(contentId, progress, { notice: progress === 100 });
        }
    }

    function attachVideoProgress(button) {
        const video = preview.querySelector('video');
        if (!video) {
            return;
        }

        video.addEventListener('timeupdate', function () {
            if (!video.duration || Number.isNaN(video.duration)) {
                return;
            }

            const progress = Math.min(100, Math.round((video.currentTime / video.duration) * 100));
            const contentId = button.getAttribute('data-content-id');
            const currentProgress = getProgressValue(button);
            if (progress <= currentProgress) {
                return;
            }
            setProgressValue(button, progress);
            sendProgressIfNeeded(contentId, progress);
            recalcModuleProgress(button.getAttribute('data-module-id'));
            recalcOverall();
        });
    }

    function selectItem(button) {
        const data = {
            id: button.getAttribute('data-content-id'),
            title: button.getAttribute('data-title'),
            description: button.getAttribute('data-description'),
            type: button.getAttribute('data-type'),
            url: button.getAttribute('data-url'),
            fileUrl: button.getAttribute('data-file-url'),
            allowDownload: button.getAttribute('data-allow-download') === '1'
        };

        activeButton = button;
        renderPreview(data);
        const currentProgress = getProgressValue(button);
        const type = (data.type || '').toLowerCase();

        if (type === 'video') {
            attachVideoProgress(button);
        } else if (currentProgress < 100) {
            const nextProgress = 100;
            setProgressValue(button, nextProgress);
            sendProgressIfNeeded(data.id, nextProgress);
        }

        recalcModuleProgress(button.getAttribute('data-module-id'));
        recalcOverall();
    }

    items.forEach(function (button) {
        button.addEventListener('click', function () {
            selectItem(button);
        });
    });

    if (items.length > 0) {
        selectItem(items[0]);
    }

    document.addEventListener('visibilitychange', function () {
        if (document.visibilityState !== 'hidden') {
            return;
        }

        if (!activeButton) {
            return;
        }

        const progress = getProgressValue(activeButton);
        if (progress <= 0) {
            return;
        }

        const contentId = activeButton.getAttribute('data-content-id');
        sendProgressBeacon(contentId, progress);
    });

    window.addEventListener('pagehide', function () {
        if (!activeButton) {
            return;
        }

        const progress = getProgressValue(activeButton);
        if (progress <= 0) {
            return;
        }

        const contentId = activeButton.getAttribute('data-content-id');
        sendProgressBeacon(contentId, progress);
    });
});
