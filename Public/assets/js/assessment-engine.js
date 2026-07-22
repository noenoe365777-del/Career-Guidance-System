(function () {
    'use strict';

    var BASE = document.querySelector('base') ? document.querySelector('base').getAttribute('href') || '' : '';
    if (!BASE || BASE === '#') {
        var scripts = document.getElementsByTagName('script');
        var src = scripts[scripts.length - 1].src;
        var parts = src.split('/');
        parts.pop();
        parts.pop();
        parts.pop();
        BASE = parts.join('/');
    }

    function apiUrl(page) {
        return BASE + '/index.php?page=' + page;
    }

    function apiEndpoint(name) {
        return state.isGuest ? 'guest-api-' + name : 'assessment-api-' + name;
    }

    function postJson(url, data, cb) {
        var xhr = new XMLHttpRequest();
        xhr.open('POST', url, true);
        xhr.setRequestHeader('Content-Type', 'application/json');
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) {
                if (xhr.status >= 200 && xhr.status < 300) {
                    try { cb(null, JSON.parse(xhr.responseText)); }
                    catch (e) { cb(new Error('Invalid JSON')); }
                } else {
                    cb(new Error('HTTP ' + xhr.status));
                }
            }
        };
        xhr.send(JSON.stringify(data));
    }

    function getJson(url, cb) {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', url, true);
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) {
                if (xhr.status >= 200 && xhr.status < 300) {
                    try { cb(null, JSON.parse(xhr.responseText)); }
                    catch (e) { cb(new Error('Invalid JSON')); }
                } else {
                    cb(new Error('HTTP ' + xhr.status));
                }
            }
        };
        xhr.send();
    }

    var state = {
        assessmentId: 0,
        attemptId: 0,
        currentIndex: 0,
        totalQuestions: 0,
        answeredCount: 0,
        assessmentName: '',
        timeLimit: 0,
        startTime: null,
        timerInterval: null,
        isSubmitting: false,
        isGuest: false,
    };

    var dashEl = document.querySelector('.assessment-dashboard');
    var qContainer = document.getElementById('assessmentQuestionContainer');
    var completeContainer = document.getElementById('assessmentCompleteContainer');
    var cardGrid = document.getElementById('assessmentCardGrid');

    function hideAll() {
        if (dashEl) dashEl.classList.add('hidden');
        if (qContainer) qContainer.classList.add('hidden');
        if (completeContainer) completeContainer.classList.add('hidden');
    }

    function showDashboard() {
        hideAll();
        if (dashEl) dashEl.classList.remove('hidden');
        if (state.timerInterval) { clearInterval(state.timerInterval); state.timerInterval = null; }
    }

    function showQuestionContainer() {
        hideAll();
        if (qContainer) qContainer.classList.remove('hidden');
    }

    function showCompleteContainer() {
        hideAll();
        if (completeContainer) completeContainer.classList.remove('hidden');
        if (state.timerInterval) { clearInterval(state.timerInterval); state.timerInterval = null; }
    }

    function attachCardListeners() {
        document.querySelectorAll('.assessment-action-btn:not(a)').forEach(function (btn) {
            btn.addEventListener('click', function (e) {
                var id = parseInt(this.getAttribute('data-assessment-id'), 10);
                startAssessment(id);
            });
        });
    }
    attachCardListeners();

    function setGuestMode(flag) {
        state.isGuest = !!flag;
    }

    // Expose engine for guest initialization
    window.assessmentEngine = {
        startAssessment: startAssessment,
        setGuestMode: setGuestMode,
    };

    function startAssessment(assessmentId) {
        state.assessmentId = assessmentId;
        state.currentIndex = 0;
        state.answeredCount = 0;

        postJson(apiUrl(apiEndpoint('start')), { assessment_id: assessmentId }, function (err, data) {
            if (err || !data.success) {
                alert('Failed to start assessment. Please try again.');
                return;
            }
            state.attemptId = data.attempt_id;
            state.totalQuestions = data.total_questions;
            state.assessmentName = data.assessment.name;
            state.timeLimit = data.assessment.time_limit;
            state.startTime = new Date();
            state.currentIndex = data.current_index;

            loadQuestion(state.currentIndex);
            showQuestionContainer();
            startTimer();
        });
    }

    function loadQuestion(index) {
        var url = apiUrl(apiEndpoint('question')) + '&attempt_id=' + state.attemptId + '&index=' + index;
        getJson(url, function (err, data) {
            if (err || !data.success) {
                if (data && data.done) {
                    finishAssessment();
                } else {
                    alert('Failed to load question.');
                }
                return;
            }
            state.currentIndex = index;
            state.totalQuestions = data.progress.total;
            renderQuestion(data);
        });
    }

    function renderQuestion(data) {
        var q = data.question;
        var options = data.options || [];
        var selectedId = data.selected_option_id;
        var nav = data.navigation;
        var prog = data.progress;
        state.answeredCount = prog.answered;

        var letters = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];

        var html = '<div class="question-text">' + escapeHtml(q.text) + '</div>';
        html += '<div class="question-options" data-question-id="' + q.id + '">';

        options.forEach(function (o, i) {
            var isSelected = selectedId === o.id;
            html += '<div class="option-item' + (isSelected ? ' selected' : '') + '" data-option-id="' + o.id + '" data-value="' + o.value + '">';
            html += '   <div class="option-radio"><div class="option-radio-inner"></div></div>';
            html += '   <span class="option-letter">' + (letters[i] || '?') + '</span>';
            html += '   <span class="option-text">' + escapeHtml(o.text) + '</span>';
            html += '</div>';
        });
        html += '</div>';

        var contentEl = document.getElementById('questionContent');
        contentEl.innerHTML = html;
        contentEl.style.animation = 'none';
        void contentEl.offsetWidth;
        contentEl.style.animation = 'questionSlideIn 0.35s cubic-bezier(0.22,1,0.36,1) both';

        document.querySelectorAll('.option-item').forEach(function (opt) {
            opt.addEventListener('click', function () {
                document.querySelectorAll('.option-item').forEach(function (o) { o.classList.remove('selected'); });
                this.classList.add('selected');
                document.getElementById('nextBtn').disabled = false;
            });
        });

        // Update progress
        updateProgress(prog.current, prog.total, prog.percent);

        // Update counter
        document.getElementById('qCounterDisplay').textContent = prog.current + '/' + prog.total;

        // Navigation buttons
        var prevBtn = document.getElementById('prevBtn');
        var nextBtn = document.getElementById('nextBtn');

        prevBtn.disabled = !nav.has_prev;
if (nav.is_last) {
    nextBtn.innerHTML =
        '<i class="bi bi-check-lg"></i> Finish';
} else {
            nextBtn.innerHTML = 'Next <i class="bi bi-chevron-right"></i>';
            nextBtn.style.background = 'linear-gradient(135deg,#5B5FEF,#7B7FF5)';
        }

        nextBtn.disabled = selectedId === null;

        prevBtn.onclick = function () {
            loadQuestion(state.currentIndex - 1);
        };

        nextBtn.onclick = function () {
            var selected = document.querySelector('.option-item.selected');
            if (!selected) return;
            var optionId = parseInt(selected.getAttribute('data-option-id'), 10);
            var questionId = parseInt(contentEl.querySelector('.question-options').getAttribute('data-question-id'), 10);
            saveAndNext(optionId, questionId, nav.is_last);
        };
    }

    function saveAndNext(optionId, questionId, isLast) {
        if (state.isSubmitting) return;
        state.isSubmitting = true;

        var nextBtn = document.getElementById('nextBtn');
        nextBtn.disabled = true;
        nextBtn.innerHTML = '<i class="bi bi-arrow-repeat animate-spin"></i> Saving...';

        postJson(apiUrl(apiEndpoint('save')), {
            attempt_id: state.attemptId,
            question_id: questionId,
            option_id: optionId,
        }, function (err, data) {
            state.isSubmitting = false;
            if (err || !data.success) {
                nextBtn.disabled = false;
                nextBtn.innerHTML = isLast ? '<i class="bi bi-check-lg"></i> Finish' : 'Next <i class="bi bi-chevron-right"></i>';
                return;
            }
            state.answeredCount = data.answered_count;

            if (isLast) {
                finishAssessment();
            } else {
                loadQuestion(state.currentIndex + 1);
            }
        });
    }

    function finishAssessment() {
        if (state.isSubmitting) return;
        state.isSubmitting = true;

        postJson(apiUrl(apiEndpoint('finish')), { attempt_id: state.attemptId }, function (err, data) {
            state.isSubmitting = false;
            if (err || !data.success) {
                alert('Failed to complete assessment.');
                return;
            }
            if (data.previewCompleted) {
                showPreviewCompleteModal(data);
            } else {
                showComplete(data);
            }
        });
    }

    function showComplete(data) {
        showCompleteContainer();

        completeContainer.querySelector('#finalScore').textContent = Math.round(data.score);
        completeContainer.querySelector('#finalAnswered').textContent = data.answered;
        completeContainer.querySelector('#finalTotal').textContent = data.total;
        completeContainer.querySelector('#finalTime').textContent = data.time_taken;
        completeContainer.querySelector('#completeAssessmentName').textContent = data.assessment_name + ' completed successfully!';
        completeContainer.querySelector('#scorePercentLabel').textContent = Math.round(data.score) + '%';

        var scoreBar = completeContainer.querySelector('#scoreBar');
        var score = Math.round(data.score);
        var color = score >= 80 ? '#059669' : (score >= 50 ? '#d97706' : '#ef4444');
        scoreBar.style.background = color;
        setTimeout(function () {
            scoreBar.style.width = score + '%';
        }, 100);
    }

    function showPreviewCompleteModal(data) {
        if (state.timerInterval) { clearInterval(state.timerInterval); state.timerInterval = null; }

        var base = BASE || '';
        var overlay = document.createElement('div');
        overlay.id = 'previewCompleteModal';
        overlay.style.cssText = 'position:fixed;inset:0;z-index:9999;display:flex;align-items:center;justify-content:center;padding:1rem;background:rgba(15,23,42,0.5);backdrop-filter:blur(6px);animation:prevFadeIn .3s ease-out;';

        overlay.innerHTML = '<style>@keyframes prevFadeIn{from{opacity:0}to{opacity:1}}@keyframes prevSlideUp{from{opacity:0;transform:translateY(24px) scale(.97)}to{opacity:1;transform:translateY(0) scale(1)}}</style>'
            + '<div style="background:#fff;border-radius:1.5rem;max-width:28rem;width:100%;box-shadow:0 25px 60px rgba(15,23,42,0.2);overflow:hidden;animation:prevSlideUp .35s cubic-bezier(.22,1,.36,1) both;">'

            + '<div style="padding:2.5rem 2rem 0;text-align:center;">'
            + '<div style="width:4rem;height:4rem;margin:0 auto;border-radius:50%;background:linear-gradient(135deg,#6366f1,#8b5cf6);display:flex;align-items:center;justify-content:center;box-shadow:0 8px 24px rgba(99,102,241,0.3);">'
            + '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>'
            + '</div>'
            + '<h2 style="margin-top:1.25rem;font-size:1.5rem;font-weight:800;color:#0f172a;letter-spacing:-.02em;">Preview Completed!</h2>'
            + '<p style="margin-top:.75rem;font-size:.875rem;color:#64748b;line-height:1.6;">You have completed the free preview.</p>'
            + '</div>'

            + '<div style="padding:1.5rem 2rem 0;">'
            + '<div style="background:#f8fafc;border-radius:1rem;padding:1rem 1.25rem;border:1px solid #e2e8f0;">'
            + '<p style="font-size:.8125rem;color:#475569;line-height:1.5;">Create an account or log in to:</p>'
            + '<ul style="margin-top:.625rem;list-style:none;padding:0;">'
            + '<li style="display:flex;align-items:center;gap:.5rem;padding:.35rem 0;font-size:.8125rem;color:#334155;"><span style="flex-shrink:0;width:1.25rem;height:1.25rem;border-radius:50%;background:#ede9fe;display:flex;align-items:center;justify-content:center;"><svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="#7c3aed" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg></span>Complete the full assessment</li>'
            + '<li style="display:flex;align-items:center;gap:.5rem;padding:.35rem 0;font-size:.8125rem;color:#334155;"><span style="flex-shrink:0;width:1.25rem;height:1.25rem;border-radius:50%;background:#ede9fe;display:flex;align-items:center;justify-content:center;"><svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="#7c3aed" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg></span>Receive your personality report</li>'
            + '<li style="display:flex;align-items:center;gap:.5rem;padding:.35rem 0;font-size:.8125rem;color:#334155;"><span style="flex-shrink:0;width:1.25rem;height:1.25rem;border-radius:50%;background:#ede9fe;display:flex;align-items:center;justify-content:center;"><svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="#7c3aed" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg></span>View career recommendations</li>'
            + '<li style="display:flex;align-items:center;gap:.5rem;padding:.35rem 0;font-size:.8125rem;color:#334155;"><span style="flex-shrink:0;width:1.25rem;height:1.25rem;border-radius:50%;background:#ede9fe;display:flex;align-items:center;justify-content:center;"><svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="#7c3aed" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg></span>Save your assessment history</li>'
            + '</ul>'
            + '</div>'
            + '</div>'

            + '<div style="padding:1.5rem 2rem 2rem;display:flex;flex-direction:column;gap:.625rem;">'
            + '<a href="' + base + '/index.php?page=register" style="display:flex;align-items:center;justify-content:center;gap:.5rem;padding:.8125rem 1rem;border-radius:.75rem;background:linear-gradient(135deg,#5B5FEF,#7B7FF5);color:#fff;font-size:.875rem;font-weight:700;text-decoration:none;box-shadow:0 4px 14px rgba(91,95,239,0.3);transition:all .2s;">'
            + '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>'
            + 'Register</a>'
            + '<a href="' + base + '/index.php?page=login" style="display:flex;align-items:center;justify-content:center;gap:.5rem;padding:.75rem 1rem;border-radius:.75rem;background:#fff;color:#334155;font-size:.875rem;font-weight:600;text-decoration:none;border:1.5px solid #e2e8f0;transition:all .2s;">'
            + '<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>'
            + 'Login</a>'
            + '<button type="button" id="previewModalCancelBtn" style="padding:.625rem 1rem;border-radius:.75rem;background:transparent;color:#94a3b8;font-size:.8125rem;font-weight:600;border:none;cursor:pointer;transition:color .2s;" onmouseover="this.style.color=\'#64748b\'" onmouseout="this.style.color=\'#94a3b8\'">Cancel</button>'
            + '</div>'

            + '</div>';

        document.body.appendChild(overlay);

        overlay.querySelector('#previewModalCancelBtn').addEventListener('click', function () {
            overlay.remove();
        });

        overlay.addEventListener('click', function (e) {
            if (e.target === overlay) overlay.remove();
        });
    }

    function updateProgress(current, total, percent) {
        var fill = document.getElementById('progressBarFill');
        var label = document.getElementById('progressLabel');
        var pct = document.getElementById('progressPercent');
        if (fill) fill.style.width = percent + '%';
        if (label) label.textContent = 'Question ' + current + ' of ' + total;
        if (pct) pct.textContent = percent + '%';
    }

    function startTimer() {
        if (state.timerInterval) clearInterval(state.timerInterval);
        var timerEl = document.getElementById('timerDisplay');
        var start = state.startTime || new Date();

        function update() {
            var now = new Date();
            var diff = Math.floor((now - start) / 1000);
            var mins = Math.floor(diff / 60);
            var secs = diff % 60;
            timerEl.textContent = (mins < 10 ? '0' : '') + mins + ':' + (secs < 10 ? '0' : '') + secs;
        }
        update();
        state.timerInterval = setInterval(update, 1000);
    }

    function escapeHtml(str) {
        if (typeof str !== 'string') return String(str);
        var div = document.createElement('div');
        div.textContent = str;
        return div.innerHTML;
    }

    document.addEventListener('click', function (e) {
        if (e.target && e.target.id === 'exitAssessmentBtn') {
            var msg = state.isGuest
                ? 'Are you sure you want to exit? Your preview progress will be lost.'
                : 'Are you sure you want to exit? Your progress will be saved.';
            if (confirm(msg)) {
                if (state.isGuest) {
                    postJson(apiUrl(apiEndpoint('exit')), { attempt_id: state.attemptId }, function (err, data) {
                        if (data && data.success && data.redirect) {
                            window.location = data.redirect;
                        }
                    });
                } else {
                    showDashboard();
                }
            }
        }
    });
})();