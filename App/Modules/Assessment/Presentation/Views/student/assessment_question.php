<?php $guestMode = $guestMode ?? false; ?>
<div class="assessment-question-page">
    <div class="mb-6 flex items-center justify-between">
        <button type="button" id="exitAssessmentBtn" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-bold text-slate-600 transition-all hover:bg-slate-50 active:scale-[0.97]">
            <i class="bi bi-arrow-left"></i> Exit
        </button>
        <div class="flex items-center gap-3">
            <div class="flex items-center gap-1.5 text-sm font-semibold text-slate-400">
                <i class="bi bi-clock"></i>
                <span id="timerDisplay">--:--</span>
            </div>
            <div class="flex items-center gap-1.5 text-sm font-semibold text-slate-500">
                <i class="bi bi-question-circle"></i>
                <span id="qCounterDisplay">0/0</span>
            </div>
        </div>
    </div>

    <div class="mb-5">
        <div class="flex items-center justify-between text-xs font-semibold text-slate-500 mb-1.5">
            <span id="progressLabel">Progress</span>
            <span id="progressPercent">0%</span>
        </div>
        <div class="h-2 bg-slate-100 rounded-full overflow-hidden">
            <div id="progressBarFill" class="h-full rounded-full bg-gradient-to-r from-indigo-500 to-violet-500 transition-all duration-500 ease-out" style="width:0%"></div>
        </div>
    </div>

    <div class="question-card">
        <div id="questionContent">
            <div class="flex items-center justify-center py-16">
                <div class="flex flex-col items-center gap-3">
                    <div class="w-10 h-10 border-4 border-indigo-200 border-t-indigo-600 rounded-full animate-spin"></div>
                    <p class="text-sm font-medium text-slate-400">Loading question...</p>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-5 flex items-center justify-between">
        <button type="button" id="prevBtn" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-bold text-slate-600 transition-all hover:bg-slate-50 hover:border-slate-300 disabled:opacity-30 disabled:cursor-not-allowed active:scale-[0.97]" disabled>
            <i class="bi bi-chevron-left"></i> Previous
        </button>
        <button type="button" id="nextBtn" class="inline-flex items-center gap-2 rounded-xl px-5 py-2.5 text-sm font-bold text-white transition-all active:scale-[0.97] disabled:opacity-50 disabled:cursor-not-allowed" style="background:linear-gradient(135deg,#5B5FEF,#7B7FF5)" disabled>
            Next <i class="bi bi-chevron-right"></i>
        </button>
    </div>
</div>

<style>
@keyframes questionSlideIn {
    from { opacity: 0; transform: translateX(30px); }
    to { opacity: 1; transform: translateX(0); }
}
@keyframes questionSlideOut {
    from { opacity: 1; transform: translateX(0); }
    to { opacity: 0; transform: translateX(-30px); }
}
@keyframes optionFadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}
.question-card {
    background: #fff; border-radius: 24px; border: 1px solid #e2e8f0; padding: 28px;
    box-shadow: 0 4px 16px rgba(15,23,42,0.04);
}
.question-text {
    font-size: 1.25rem; font-weight: 700; line-height: 1.5; color: #0f172a; margin-bottom: 24px;
}
.option-item {
    display: flex; align-items: center; gap: 14px; padding: 14px 18px; margin-bottom: 10px;
    border-radius: 14px; border: 1.5px solid #e2e8f0; background: #fff; cursor: pointer;
    transition: all 0.2s ease-out;
}
.option-item:hover { border-color: #a5b4fc; background: #f8f7ff; }
.option-item.selected { border-color: #5B5FEF; background: #eef2ff; box-shadow: 0 0 0 3px rgba(91,95,239,0.12); }
.option-radio {
    width: 20px; height: 20px; border-radius: 50%; border: 2px solid #cbd5e1; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center; transition: all 0.2s;
}
.option-item.selected .option-radio { border-color: #5B5FEF; background: #5B5FEF; }
.option-radio-inner { width: 8px; height: 8px; border-radius: 50%; background: #fff; opacity: 0; transition: opacity 0.2s; }
.option-item.selected .option-radio-inner { opacity: 1; }
.option-text { font-size: 0.95rem; font-weight: 500; color: #1e293b; }
.option-item.selected .option-text { color: #0f172a; font-weight: 600; }
.option-letter {
    width: 28px; height: 28px; border-radius: 8px; background: #f1f5f9; color: #64748b;
    display: flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: 700; flex-shrink: 0;
    transition: all 0.2s;
}
.option-item.selected .option-letter { background: #5B5FEF; color: #fff; }
</style>

<?php if ($guestMode): ?>
<script>
(function () {
    'use strict';

    var currentQuestion = 0;
    var totalQuestions = 5;
    var selectedAnswer = null;

    function escapeHtml(str) {
        if (typeof str !== 'string') return String(str);
        var div = document.createElement('div');
        div.textContent = str;
        return div.innerHTML;
    }

    loadQuestion(0);

    function loadQuestion(index) {
        fetch('index.php?page=guest-api-question&index=' + index)
        .then(function (r) { return r.json(); })
        .then(function (data) {
            if (data.done) {
                window.location = 'index.php?page=guest-result';
                return;
            }
            currentQuestion = index;
            renderQuestion(data);
        });
    }

    function renderQuestion(data) {
        var q = data.question;
        var nav = data.navigation;
        var prog = data.progress;
        var selectedValue = data.selected;
        var options = q.options || [];
        var letters = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];

        var html = '<div class="question-text">' + escapeHtml(q.text) + '</div>';
        html += '<div class="question-options" data-question-id="' + q.id + '">';

        options.forEach(function (o, i) {
            var isSelected = selectedValue !== null && String(selectedValue) === String(o.value);
            html += '<div class="option-item' + (isSelected ? ' selected' : '') + '" data-value="' + o.value + '">';
            html += '   <div class="option-radio"><div class="option-radio-inner"></div></div>';
            html += '   <span class="option-letter">' + (letters[i] || '?') + '</span>';
            html += '   <span class="option-text">' + escapeHtml(o.label) + '</span>';
            html += '</div>';
        });

        html += '</div>';

        var contentEl = document.getElementById('questionContent');
        contentEl.innerHTML = html;
        contentEl.style.animation = 'none';
        void contentEl.offsetWidth;
        contentEl.style.animation = 'questionSlideIn 0.35s cubic-bezier(0.22,1,0.36,1) both';

        var percent = Math.round(prog.current / prog.total * 100);
        document.getElementById('progressLabel').textContent = 'Question ' + prog.current + ' of ' + prog.total;
        document.getElementById('progressPercent').textContent = percent + '%';
        document.getElementById('progressBarFill').style.width = percent + '%';
        document.getElementById('qCounterDisplay').textContent = prog.current + '/' + prog.total;

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

        nextBtn.disabled = selectedValue === null;
        selectedAnswer = selectedValue !== null ? String(selectedValue) : null;

        document.querySelectorAll('.option-item').forEach(function (opt) {
            opt.addEventListener('click', function () {
                document.querySelectorAll('.option-item').forEach(function (o) { o.classList.remove('selected'); });
                this.classList.add('selected');
                selectedAnswer = this.getAttribute('data-value');
                document.getElementById('nextBtn').disabled = false;
            });
        });

        prevBtn.onclick = function () {
            if (currentQuestion > 0) loadQuestion(currentQuestion - 1);
        };

        nextBtn.onclick = function () {
            if (selectedAnswer === null) return;

            fetch('index.php?page=guest-api-save', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ answer: selectedAnswer })
            })
            .then(function (r) { return r.json(); })
            .then(function () {
                if (currentQuestion + 1 >= totalQuestions) {
                    fetch('index.php?page=guest-api-finish', { method: 'POST' })
                    .then(function (r) { return r.json(); })
                    .then(function (data) {
                        window.location = data.redirect;
                    });
                } else {
                    loadQuestion(currentQuestion + 1);
                }
            });
        };
    }
})();
</script>
<?php else: ?>
<script src="<?= BASE_URL ?>/assets/js/assessment-engine.js"></script>
<?php endif; ?>
