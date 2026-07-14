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

    function startAssessment(assessmentId) {
        state.assessmentId = assessmentId;
        state.currentIndex = 0;
        state.answeredCount = 0;

        postJson(apiUrl('assessment-api-start'), { assessment_id: assessmentId }, function (err, data) {
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
        var url = apiUrl('assessment-api-question') + '&attempt_id=' + state.attemptId + '&index=' + index;
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
            nextBtn.innerHTML = '<i class="bi bi-check-lg"></i> Finish';
            nextBtn.style.background = 'linear-gradient(135deg,#059669,#34d399)';
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

        postJson(apiUrl('assessment-api-save-answer'), {
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

        postJson(apiUrl('assessment-api-finish'), { attempt_id: state.attemptId }, function (err, data) {
            state.isSubmitting = false;
            if (err || !data.success) {
                alert('Failed to complete assessment.');
                return;
            }
            showComplete(data);
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
            if (confirm('Are you sure you want to exit? Your progress will be saved.')) {
                showDashboard();
            }
        }
    });

})();
